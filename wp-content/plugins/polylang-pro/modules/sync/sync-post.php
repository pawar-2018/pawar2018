<?php

/**
 * Manages the synchronization of posts across languages
 *
 * @since 2.1
 */
class PLL_Sync_Post {
	public $model, $sync, $duplicate, $filters_post, $buttons;

	/**
	 * Constructor
	 *
	 * @since 2.1
	 */
	public function __construct( &$polylang ) {
		$this->model = &$polylang->model;
		$this->sync = &$polylang->sync;
		$this->duplicate = &$polylang->duplicate;
		$this->filters_post = &$polylang->filters_post;

		add_filter( 'pll_copy_taxonomies', array( $this, 'copy_taxonomies' ), 5, 4 );
		add_filter( 'pll_copy_post_metas', array( $this, 'copy_post_metas' ), 5, 4 );
		add_action( 'pll_save_post', array( $this, 'sync_post' ), 5, 3 ); // Before PLL_Admin_Sync, Before PLL_ACF, Before PLLWC

		// Create buttons
		foreach ( $this->model->get_languages_list() as $language ) {
			$this->buttons[ $language->slug ] = new PLL_Sync_Post_Button( $polylang, $language );
		}
	}

	/**
	 * Checks if the synchronized post is included in bulk trashing or restoring posts
	 *
	 * @since 2.1.2
	 *
	 * @param int $post_id ID of the target post
	 * @return bool
	 */
	protected function doing_bulk_trash( $post_id ) {
		return 'edit.php' === $GLOBALS['pagenow'] && isset( $_GET['action'], $_GET['post'] ) && in_array( $_GET['action'], array( 'trash', 'untrash' ) ) && in_array( $post_id, $_GET['post'] );
	}

	/**
	 * Copies all taxonomies
	 *
	 * @since 2.1
	 *
	 * @param array $taxonomies list of taxonomy names
	 * @return array
	 */
	public function copy_taxonomies( $taxonomies, $sync, $from, $to ) {
		if ( ! empty( $from ) &&  ! empty( $to ) && $this->are_synchronized( $from, $to ) ) {
			$taxonomies = array_diff( get_post_taxonomies( $from ), get_taxonomies( array( '_pll' => true ) ) );
		}
		return $taxonomies;
	}

	/**
	 * Copies all custom fields
	 *
	 * @since 2.1
	 *
	 * @param array  $keys list of custom fields names
	 * @param bool   $sync true if it is synchronization, false if it is a copy
	 * @param int    $from id of the post from which we copy informations
	 * @param int    $to   id of the post to which we paste informations
	 * @return array
	 */
	public function copy_post_metas( $keys, $sync, $from, $to ) {
		if ( ! empty( $from ) &&  ! empty( $to ) && $this->are_synchronized( $from, $to ) ) {
			$from_keys = array_keys( get_post_custom( $from ) ); // *All* custom fields
			$to_keys = array_keys( get_post_custom( $to ) ); // Adding custom fields of the destination allow to synchronize deleted custom fields
			$keys = array_merge( $from_keys, $to_keys );
			$keys = array_unique( $keys );
			$keys = array_diff( $keys, array( '_edit_last', '_edit_lock' ) );

			// Trash meta status must not be synchronized when bulk trashing / restoring posts otherwise WP can't restore the right post status
			if ( $this->doing_bulk_trash( $to ) ) {
				$keys = array_diff( $keys, array( '_wp_trash_meta_status', '_wp_trash_meta_time' ) );
			}
		}
		return $keys;
	}

	/**
	 * Duplicates the post and saves the synchronization group
	 *
	 * @since 2.1
	 *
	 * @param int    $post_id      post id
	 * @param object $post         post object
	 * @param array  $translations post translations
	 */
	public function sync_post( $post_id, $post, $translations ) {
		global $wpdb;
		static $avoid_recursion = false;

		if ( $avoid_recursion ) {
			return;
		}

		if ( ! empty( $_POST['post_lang_choice'] ) ) { // Detect the languages metabox
			// We are editing the post from post.php (only place where we can change the option to sync)
			if ( ! empty( $_POST['pll_sync_post'] ) ) {
				$sync_post = array_intersect( $_POST['pll_sync_post'], array( 'true' ) );
			}

			if( empty( $sync_post ) ) {
				$this->save_group( $post_id, array() );
				return;
			}
		} else {
			// Quick edit or bulk edit or any place where the Languages metabox is not displayed
			$sync_post = array_diff( $this->get( $post_id ), array( $post_id ) ); // Just remove this post form the list
		}

		$avoid_recursion = true;
		$languages = array_keys( $sync_post );

		foreach ( $languages as $lang ) {
			$tr_id = $this->model->post->get( $post_id, $this->model->get_language( $lang ) );

			$tr_post = $post;

			// If it does not exist, create it
			if ( ! $tr_id ) {
				$tr_post->ID = null;
				$tr_id = wp_insert_post( $tr_post );
				$this->model->post->set_language( $tr_id, $lang ); // Necessary to do it now to share slug
				$translations[ $lang ] = $tr_id;
				$this->model->post->save_translations( $post_id, $translations ); // Saves translations in case we created a post
				$this->save_group( $post_id, $languages ); // Save synchronization group
				$_POST['post_tr_lang'][ $lang ] = $tr_id; // Hack to avoid creating multiple posts if the original post is saved several times (ex WooCommerce 2.7+)

				/** This action is documented in admin/admin-filters-post.php */
				do_action( 'pll_save_post', $post_id, $post, $translations ); // Fire again as we just updated $translations
			}

			$tr_post->post_parent = $this->model->post->get( $post->post_parent, $lang ); // Translates post parent
			$tr_post = $this->duplicate->copy_content( $post, $tr_post, $lang );

			// The columns to copy in DB
			$columns = array(
				'post_author',
				'post_date',
				'post_date_gmt',
				'post_content',
				'post_title',
				'post_excerpt',
				'comment_status',
				'ping_status',
				'post_name',
				'post_modified',
				'post_modified_gmt',
				'post_parent',
				'menu_order',
				'post_mime_type',
			);

			// Don't synchronize when trashing / restoring in bulk as it causes an error fired by WP.
			if ( ! $this->doing_bulk_trash( $tr_id ) ) {
				$columns[] = 'post_status';
			}

			$tr_post = array_intersect_key( (array) $tr_post, array_flip( $columns ) );
			$wpdb->update( $wpdb->posts, $tr_post, array( 'ID' => $tr_id ) ); // Don't use wp_update_post to avoid conflict (reverse sync)
			clean_post_cache( $tr_id );

			isset( $_REQUEST['sticky'] ) && 'sticky' === $_REQUEST['sticky'] ? stick_post( $tr_id ) : unstick_post( $tr_id );
		}

		// Save group if the languages metabox is displayed
		if ( ! empty( $_POST['post_lang_choice'] ) ) {
			$this->save_group( $post_id, $languages );
		}

		$avoid_recursion = false;
	}

	/**
	 * Saves the synchronization group
	 * This is stored as an array beside the translations in the post_translations term description
	 *
	 * @since 2.1
	 *
	 * @param int   $post_id   Post currently being saved
	 * @param array $sync_post Array of languages to sync with this post
	 */
	public function save_group( $post_id, $sync_post ) {
		$term = $this->model->post->get_object_term( $post_id, 'post_translations' );

		if ( empty( $term ) ) {
			return;
		}

		$d = unserialize( $term->description );
		$lang = $this->model->post->get_language( $post_id )->slug;

		if ( empty( $sync_post ) ) {
			if ( isset( $d['sync'][ $lang ] ) ) {
				$d['sync'] = array_diff( $d['sync'], array( $d['sync'][ $lang ] ) );
			}
		} else {
			$sync_post[] = $lang;
			$d['sync'] = empty( $d['sync'] ) ? array_fill_keys( $sync_post, $lang ) : array_merge( array_diff( $d['sync'], array( $lang ) ),  array_fill_keys( $sync_post, $lang ) );
		}

		wp_update_term( (int) $term->term_id, 'post_translations', array( 'description' => serialize( $d ) ) );
	}

	/**
	 * Get all posts synchronized with a given post
	 *
	 * @since 2.1
	 *
	 * @param int $post_id
	 * @return array an associative array of with language code as key and post id as value
	 */
	public function get( $post_id ) {
		$term = $this->model->post->get_object_term( $post_id, 'post_translations' );

		if ( ! empty( $term ) ) {
			$lang = $this->model->post->get_language( $post_id );
			$d = unserialize( $term->description );
			if ( ! empty( $d['sync'][ $lang->slug ] ) ) {
				$keys = array_keys( $d['sync'], $d['sync'][ $lang->slug ] );
				return array_intersect_key( $d, array_flip( $keys ) );
			}
		}

		return array();
	}

	/**
	 * Checks whether two posts are synchronized
	 *
	 * @since 2.1
	 *
	 * @param int $post_id
	 * @param int $other_id
	 * @return bool
	 */
	public function are_synchronized( $post_id, $other_id ) {
		return in_array( $other_id, $this->get( $post_id ) );
	}
}
