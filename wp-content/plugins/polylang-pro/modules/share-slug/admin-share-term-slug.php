<?php

/**
 * Manages shared slugs for taxonomy terms on admin side
 *
 * @since 1.9
 */
class PLL_Admin_Share_Term_Slug extends PLL_Share_Term_Slug {

	/**
	 * Constructor
	 *
	 * @since 1.9
	 *
	 * @param object $polylang
	 */
	public function __construct( &$polylang ) {
		parent::__construct( $polylang );

		remove_action( 'pre_post_update', array( &$polylang->filters_term, 'pre_post_update' ) );
		remove_filter( 'pre_term_name', array( &$polylang->filters_term, 'pre_term_name' ) );
		remove_filter( 'pre_term_slug', array( &$polylang->filters_term, 'pre_term_slug' ), 10, 2 );

		add_action( 'pre_post_update', array( $this, 'pre_post_update' ) );
		add_filter( 'pre_term_name', array( $this, 'pre_term_name' ) );
		add_filter( 'pre_term_slug', array( $this, 'pre_term_slug' ), 10, 2 );

		add_action( 'create_term', array( $this, 'save_term' ), 1, 3 );
		add_action( 'edit_term', array( $this, 'save_term' ), 1, 3 );
	}

	/**
	 * Stores the term name for use in pre_term_slug
	 *
	 * @since 1.9
	 *
	 * @param string $name term name
	 * @return string unmodified term name
	 */
	public function pre_term_name( $name ) {
		return $this->pre_term_name = $name;
	}

	/**
	 * Stores the current post_id when bulk editing posts for use in save_language and pre_term_slug
	 *
	 * @since 1.9
	 *
	 * @param int $post_id
	 */
	public function pre_post_update( $post_id ) {
		if ( isset( $_GET['bulk_edit'] ) ) {
			$this->post_id = $post_id;
		}
	}

	/**
	 * Creates the term slug in case the term already exists in another language
	 *
	 * @since 1.9
	 *
	 * @param string $slug
	 * @param string $taxonomy
	 * @return string
	 */
	public function pre_term_slug( $slug, $taxonomy ) {
		if ( ! $slug ) {
			$slug = sanitize_title( $this->pre_term_name );
		}

		if ( $this->model->is_translated_taxonomy( $taxonomy ) && term_exists( $slug, $taxonomy ) ) {
			if ( isset( $_POST['term_lang_choice'] ) ) {
				$slug .= '___' . $this->model->get_language( $_POST['term_lang_choice'] )->slug;
			}

			elseif ( isset( $_POST['inline_lang_choice'] ) ) {
				$slug .= '___' . $this->model->get_language( $_POST['inline_lang_choice'] )->slug;
			}

			// *Post* bulk edit, in case a new term is created
			elseif ( isset( $_GET['bulk_edit'], $_GET['inline_lang_choice'] ) ) {
				// Bulk edit does not modify the language
				if ( -1 == $_GET['inline_lang_choice'] ) {
					$slug .= '___' . $this->model->post->get_language( $this->post_id )->slug;
				} else {
					$slug .= '___' . $this->model->get_language( $_GET['inline_lang_choice'] )->slug;
				}
			}

			// Special cases for default categories as the select is disabled
			elseif ( ! empty( $_POST['tag_ID'] ) && in_array( get_option( 'default_category' ), $this->model->term->get_translations( $_POST['tag_ID'] ) ) ) {
				$slug .= '___' . $this->model->term->get_language( $_POST['tag_ID'] )->slug;
			}

			elseif ( ! empty( $_POST['tax_ID'] ) && in_array( get_option( 'default_category' ), $this->model->term->get_translations( $_POST['tax_ID'] ) ) ) {
				$slug .= '___' . $this->model->term->get_language( $_POST['tax_ID'] )->slug;
			}
		}

		return $slug;
	}

	/**
	 * Ugly hack to enable the same slug in several languages
	 *
	 * @since 1.9
	 *
	 * @param int $term_id
	 * @param int $tt_id term taxononomy id
	 * @param string $taxonomy
	 */
	public function save_term( $term_id, $tt_id, $taxonomy ) {
		global $wpdb;

		// Does nothing except on taxonomies which are filterable
		if ( ! $this->model->is_translated_taxonomy( $taxonomy ) || 0 === $this->options['force_lang'] ) {
			return;
		}

		wp_cache_delete( $term_id, 'terms' ); // Forces deleting the get_term cache
		$term = get_term( $term_id, $taxonomy );

		if ( false === ( $pos = strpos( $term->slug, '___' ) ) ) {
			return;
		}

		$slug = substr( $term->slug, 0, $pos );
		$lang = substr( $term->slug, $pos + 3 );

		// Need to check for unique slug as we tricked wp_unique_term_slug from WP
		$slug = $this->unique_term_slug( $slug, $lang, (object) $term );
		$wpdb->update( $wpdb->terms, compact( 'slug' ), compact( 'term_id' ) );
	}
}
