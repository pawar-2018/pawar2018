<?php

/**
 * Manages compatibility with 3rd party plugins ( and themes )
 * This class is available as soon as the plugin is loaded
 *
 * @since 1.9.1
 */
class PLL_Advanced_Plugins_Compat {
	static protected $instance; // For singleton
	public $acf;

	/**
	 * Constructor
	 *
	 * @since 1.9.1
	 */
	protected function __construct() {
		// Beaver Builder
		add_filter( 'pll_copy_post_metas', array( $this, 'fl_builder_copy_post_metas' ), 10, 2 );

		// Divi Builder
		add_filter( 'pll_copy_post_metas', array( $this, 'divi_builder_copy_post_metas' ), 10, 2 );

		// Advanced Custom Fields Pro
		add_action( 'init', array( $this->acf = new PLL_ACF(), 'init' ) );

		// Custom Post Type UI
		add_action( 'pll_init', array( $this->cptui = new PLL_CPTUI(), 'init' ) );
	}

	/**
	 * Access to the single instance of the class
	 *
	 * @since 1.9.1
	 *
	 * @return object
	 */
	static public function instance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Allow to copy Beaver Builder data when creating a translation
	 *
	 * @since 1.9.1
	 *
	 * @param array $keys list of custom fields names
	 * @param bool  $sync true if it is synchronization, false if it is a copy
	 * @return array
	 */
	function fl_builder_copy_post_metas( $metas, $sync ) {
		$bb_metas = array(
			'_fl_builder_draft',
			'_fl_builder_draft_settings',
			'_fl_builder_data',
			'_fl_builder_data_settings',
			'_fl_builder_enabled'
		);

		return $sync ? $metas : array_merge( $metas, $bb_metas );
	}

	/**
	 * Allow to copy Divi Builder data when creating a translation
	 *
	 * @since 2.1
	 *
	 * @param array $keys list of custom fields names
	 * @param bool  $sync true if it is synchronization, false if it is a copy
	 * @return array
	 */
	function divi_builder_copy_post_metas( $metas, $sync ) {
		$divi_metas = array(
			'_et_pb_post_hide_nav',
			'_et_pb_page_layout',
			'_et_pb_side_nav',
			'_et_pb_use_builder',
			'_et_pb_ab_bounce_rate_limit',
			'_et_pb_ab_stats_refresh_interval',
			'_et_pb_old_content',
			'_et_pb_enable_shortcode_tracking',
			'_et_pb_custom_css',
			'_et_pb_light_text_color',
			'_et_pb_dark_text_color',
			'_et_pb_content_area_background_color',
			'_et_pb_section_background_color',
		);

		return $sync ? $metas : array_merge( $metas, $divi_metas );
	}
}
