<?php

/**
 * Buttons for posts synchronization
 *
 * @since 2.1
 */
class PLL_Sync_Post_Button extends PLL_Metabox_Button {
	public $model;
	protected $language;

	/**
	 * Constructor
	 *
	 * @since 2.1
	 */
	public function __construct( &$polylang, $language ) {
		$args = array(
			'position'   => "before_post_translation_{$language->slug}",
			'activate'   => __( 'Synchronize this post', 'polylang-pro' ),
			'deactivate' => __( "Don't synchronize this post", 'polylang-pro' ),
			'class'      => 'dashicons-before dashicons-controls-repeat',
			'before'     => '<td class="pll-sync-column pll-column-icon">',
			'after'      => '</td>'
		);

		parent::__construct( "pll_sync_post[{$language->slug}]", $args );

		$this->model = &$polylang->model;
		$this->language = $language;
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

		if ( empty( $post ) ) {
			return false; // FIXME this resets all sync when the language is changed
		}

		$term = $this->model->post->get_object_term( $post->ID, 'post_translations' );

		if ( ! empty( $term ) ) {
			$language = $this->model->post->get_language( $post->ID ); // FIXME is it already evaluated?
			$d = unserialize( $term->description );
			return isset( $d['sync'][ $this->language->slug ], $d['sync'][ $language->slug ] ) && $d['sync'][ $this->language->slug ] === $d['sync'][ $language->slug ];
		}

		return false;
	}

}
