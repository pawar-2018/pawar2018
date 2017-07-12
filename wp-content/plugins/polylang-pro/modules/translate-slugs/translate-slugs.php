<?php

/**
 * Modifies links both on frontend and admin side
 *
 * @since 1.9
 */
class PLL_Translate_Slugs {

	/**
	 * Constructor
	 *
	 * @since 1.9
	 *
	 * @param object $slugs_model
	 */
	public function __construct( &$slugs_model ) {
		$this->slugs_model = &$slugs_model;

		add_filter( 'pll_post_type_link', array( $this, 'pll_post_type_link' ), 10, 3 );
		add_filter( 'pll_term_link', array( $this, 'pll_term_link' ), 10, 3 );
	}

	/**
	 * Modifies custom post type links
	 *
	 * @since 1.9
	 *
	 * @param string $url
	 * @param object $lang
	 * @param object $post
	 * @return string
	 */
	public function pll_post_type_link( $url, $lang, $post ) {
		if ( ! empty( $GLOBALS['wp_rewrite'] ) ) {
			$url = $this->slugs_model->translate_slug( $url, $lang, 'front' );
		}

		return $this->slugs_model->translate_slug( $url, $lang, $post->post_type );
	}

	/**
	 * Modifies term links
	 *
	 * @since 1.9
	 *
	 * @param string $url
	 * @param object $lang
	 * @param object $term
	 * @return string
	 */
	public function pll_term_link( $url, $lang, $term ) {
		if ( 'post_format' == $term->taxonomy ) {
			$url = $this->slugs_model->translate_slug( $url, $lang, $term->slug ); // occurs only on frontend
		}

		if ( ! empty( $GLOBALS['wp_rewrite'] ) ) {
			$url = $this->slugs_model->translate_slug( $url, $lang, 'front' );
		}

		return $this->slugs_model->translate_slug( $url, $lang, $term->taxonomy );
	}
}
