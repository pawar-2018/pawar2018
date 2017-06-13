<?php

/**
 * Copy the title, content and excerpt from the source when creating a new post translation
 *
 * @since 1.9
 */
class PLL_Duplicate extends PLL_Metabox_Button {
	public $options, $model, $filters_media;

	/**
	 * Constructor
	 *
	 * @since 1.9
	 *
	 * @param object $polylang
	 */
	public function __construct( &$polylang ) {
		$args = array(
			'position'   => 'before_post_translations',
			'activate'   => __( 'Activate the content duplication', 'polylang-pro' ),
			'deactivate' => __( 'Deactivate the content duplication', 'polylang-pro' ),
			'class'      => 'dashicons-before dashicons-admin-page',
		);

		parent::__construct( 'pll-duplicate', $args );

		$this->options = &$polylang->options;
		$this->model = &$polylang->model;
		$this->filters_media = &$polylang->filters_media;

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 5, 2 );
	}

	/**
	 * Tells whether the button is active or not
	 *
	 * @since 2.1
	 *
	 * @return bool
	 */
	public function is_active() {
		global $post;
		$duplicate_options = get_user_meta( get_current_user_id(), 'pll_duplicate_content', true );
		return isset( $duplicate_options[ $post->post_type ] ) ? $duplicate_options[ $post->post_type ] : false;
	}

	/**
	 * Saves the button state
	 *
	 * @since 2.1
	 *
	 * @param string $post_type current post type
	 * @param bool   $active    new requested button state
	 * @return bool whether the new button state is accepted or not
	 */
	protected function toggle_option( $post_type, $active ) {
		$duplicate_options = get_user_meta( get_current_user_id(), 'pll_duplicate_content', true );
		$duplicate_options[ $post_type ] = $active;
		return update_user_meta( get_current_user_id(), 'pll_duplicate_content', $duplicate_options );
	}

	/**
	 * Fires the content copy
	 *
	 * @since 1.9
	 *
	 * @param string $post_type
	 * @param object $post current post object
	 */
	public function add_meta_boxes( $post_type, $post ) {
		global $post_type;

		$duplicate_options = get_user_meta( get_current_user_id(), 'pll_duplicate_content', true );
		$this->active = ! empty( $duplicate_options ) && ! empty( $duplicate_options[ $post_type ] );

		if ( $this->active && 'post-new.php' === $GLOBALS['pagenow'] && isset( $_GET['from_post'], $_GET['new_lang'] ) ) {
			// Capability check already done in post-new.php
			$this->copy_content( get_post( (int) $_GET['from_post'] ), $post, $_GET['new_lang'] );
		}
	}

	/**
	 * Copy the content from one post to the other
	 *
	 * @since 1.9
	 *
	 * @param object        $from_post the post to copy from
	 * @param object        $post      the post to copy to
	 * @param object|string $language  the language of the post to copy to
	 */
	public function copy_content( $from_post, $post, $language ) {
		global $shortcode_tags;

		$this->post_id = $post->ID;
		$this->language = $this->model->get_language( $language );

		if ( ! $from_post || ! $this->language ) {
			return;
		}

		// Hack shortcodes
		$backup = $shortcode_tags;
		$shortcode_tags = array();

		// Add our own shorcode actions
		if ( $this->options['media_support'] ) {
			add_shortcode( 'gallery', array( $this, 'ids_list_shortcode' ) );
			add_shortcode( 'playlist', array( $this, 'ids_list_shortcode' ) );
			add_shortcode( 'caption', array( $this, 'caption_shortcode' ) );
			add_shortcode( 'wp_caption', array( $this, 'caption_shortcode' ) );
		}

		$post->post_title = $from_post->post_title;
		$post->post_excerpt = $this->translate( $from_post->post_excerpt );
		$post->post_content = $this->translate( $from_post->post_content );

		// Get the shorcodes back
		$shortcode_tags = $backup;

		return $post;
	}

	/**
	 * Get the media translation id
	 * Create the translation if it does not exist
	 * Attach the media to the parent post
	 *
	 * @since 1.9
	 *
	 * @param int media id
	 * @return int translated media id
	 */
	public function translate_media( $id ) {
		global $wpdb;

		if ( ! $tr_id = $this->model->post->get( $id, $this->language ) ) {
			$tr_id = $this->filters_media->create_media_translation( $id, $this->language );
		}

		// If we don't have a translation and did not success to create one, return current media
		if ( empty( $tr_id ) ) {
			return $id;
		}

		// Attach to the translated post
		if ( ! wp_get_post_parent_id( $tr_id ) ) {
			// Query inspired by wp_media_attach_action()
			$wpdb->query( $wpdb->prepare( "UPDATE $wpdb->posts SET post_parent = %d WHERE post_type = 'attachment' AND ID = %d", $this->post_id , $tr_id ) );
			clean_attachment_cache( $tr_id );
		}

		return $tr_id;
	}

	/**
	 * Translates the 'gallery' and 'playlist' shortcodes
	 *
	 * @since 1.9
	 *
	 * @param array  $attr shortcode attribute
	 * @param null   $null
	 * @param string $tag  shortcode tag (either 'gallery' or 'playlist')
	 * @return string translated shortcode
	 */
	function ids_list_shortcode( $attr, $null, $tag ) {
		foreach ( $attr as $k => $v ) {
			if ( 'ids' == $k ) {
				$ids = explode( ',', $v );
				$tr_ids = array();
				foreach ( $ids as $id ) {
					$tr_ids[] = $this->translate_media( $id );
				}
				$v = implode( ',', $tr_ids );
			}
			$out[] = $k . '="' . $v .'"';
		}

		return '[' . $tag . ' ' . implode( ' ', $out ) . ']';
	}

	/**
	 * Translates the caption shortcode
	 * Compatible only with the new style introduced in WP 3.4
	 *
	 * @since 1.9
	 *
	 * @param array  $attr    shortcode attrbute
	 * @param string $content shortcode content
	 * @param string $tag     shortcode tag (either 'caption' or 'wp-caption')
	 * @return string translated shortcode
	 */
	function caption_shortcode( $attr, $content, $tag ) {
		// Translate the caption id
		foreach ( $attr as $k => $v ) {
			if ( 'id' == $k ) {
				$idarr = explode( '_', $v );
				$id = $idarr[1]; // Remember this
				$tr_id = $idarr[1] = $this->translate_media( $id );
				$v = implode( '_', $idarr );
			}
			$out[] = $k . '="' . $v .'"';
		}

		// Translate the caption content
		if ( ! empty( $id ) ) {
			$p = get_post( $id );
			$tr_p = get_post( $tr_id );
			$content = str_replace( $p->post_excerpt, $tr_p->post_excerpt, $content );
		}

		return '[' . $tag . ' ' . implode( ' ', $out ) . ']' . $content . '[/' . $tag . ']';
	}

	/**
	 * Translate shortcodes and <img> attributes in a given text
	 *
	 * @since 1.9
	 *
	 * @param string $content text to translate
	 * @return string translated text
	 */
	public function translate( $content ) {
		$content = do_shortcode( $content ); // translate shorcodes

		$textarr = wp_html_split( $content ); // Since 4.2.3

		// Translate img class and alternative text
		if ( $this->options['media_support'] ) {
			foreach ( $textarr as $i => $text ) {
				if ( 0 === strpos( $text, '<img' ) ) {
					$textarr[ $i ] = $this->translate_img( $text );
				}
			}
		}

		return implode( $textarr );
	}

	/**
	 * Translates <img> 'class' and 'alt' attributes
	 *
	 * @since 1.9
	 *
	 * @param string $text img attributes
	 * @return string translated attributes
	 */
	public function translate_img( $text ) {
		$attributes = wp_kses_attr_parse( $text ); // since WP 4.2.3

		// Replace class
		foreach ( $attributes as $k => $attr ) {
			if ( 0 === strpos( $attr, 'class' ) ) {
				if ( preg_match( '#wp\-image\-([0-9]+)#', $attr, $matches ) && $id = $matches[1] ) {
					$tr_id = $this->translate_media( $id );
					$attributes[ $k ] = str_replace( 'wp-image-' . $id, 'wp-image-' . $tr_id, $attr );
				}
			}
		}

		if ( ! empty( $tr_id ) ) {
			// Got a tr_id, attempt to replace the alt text
			foreach ( $attributes as $k => $attr ) {
				if ( 0 === strpos( $attr, 'alt' ) && $alt = get_post_meta( $tr_id, '_wp_attachment_image_alt', true ) ) {
					$attributes[ $k ] = 'alt="' . esc_attr( $alt ) . '" ';
				}
			}
		}

		return implode( $attributes );
	}
}
