<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Don't access directly
};

/**
 * A class to load specific Pro modules
 *
 * @since 1.9
 */
class Polylang_Pro {
	/**
	 * Initialization
	 *
	 * @since 1.9
	 */
	public function init( &$polylang ) {
		if ( ! $polylang instanceof PLL_Frontend ) {
			load_plugin_textdomain( 'polylang-pro', false, basename( POLYLANG_DIR ) . '/languages' );
			new PLL_License( POLYLANG_FILE, 'Polylang Pro', POLYLANG_VERSION, 'Frédéric Demarle' );

			// Download Polylang language packs
			add_filter( 'http_request_args', array( $this, 'http_request_args' ), 10, 2 );
			add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'pre_set_site_transient_update_plugins' ) );
		}

		$this->load_modules( $polylang );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	/**
	 * Hack to download Polylang languages packs
	 *
	 * @since 1.9
	 *
	 * @param array  $args http request args
	 * @param string $url  url of the request
	 * @return array
	 */
	public function http_request_args( $args, $url ) {
		if ( false !== strpos( $url, '//api.wordpress.org/plugins/update-check/' ) ) {
			$plugins = (array) json_decode( $args['body']['plugins'], true );
			if ( empty( $plugins['plugins']['polylang/polylang.php'] ) ) {
				$plugins['plugins']['polylang/polylang.php'] = array( 'Version' => POLYLANG_VERSION );
				$args['body']['plugins'] = wp_json_encode( $plugins );
			}
		}
		return $args;
	}

	/**
	 * Remove Polylang from the list of plugins to udpate if it is not installed
	 *
	 * @since 2.1.1
	 *
	 * @param array  $value
	 * @return array
	 */
	public function pre_set_site_transient_update_plugins( $value ) {
		$plugins = get_plugins();
		if ( isset( $value->response ) ) {
			if ( empty( $plugins['polylang/polylang.php'] ) ) {
				unset( $value->response['polylang/polylang.php'] );
			} elseif ( isset( $value->response['polylang/polylang.php']->new_version ) && $plugins['polylang/polylang.php']['Version'] == $value->response['polylang/polylang.php']->new_version ) {
				$value->no_update['polylang/polylang.php'] = $value->response['polylang/polylang.php'];
				unset( $value->response['polylang/polylang.php'] );
			}
		}
		return $value;
	}

	/**
	 * Load modules
	 *
	 * @since 1.9
	 *
	 * @param object $polylang
	 */
	public function load_modules( &$polylang ) {
		$options = &$polylang->options;

		if ( get_option( 'permalink_structure' ) ) {
			// Translate slugs, only for pretty permalinks
			$slugs_model = new PLL_Translate_Slugs_Model( $polylang );
			$polylang->translate_slugs = $polylang instanceof PLL_Frontend ?
				new PLL_Frontend_Translate_Slugs( $slugs_model, $polylang->curlang ) :
				new PLL_Translate_Slugs( $slugs_model );

			// Share slugs only for pretty permalinks and language information in url
			if ( $options['force_lang'] ) {
				// Share post slugs
				$polylang->share_post_slug = $polylang instanceof PLL_Frontend ?
					new PLL_Frontend_Share_Post_Slug( $polylang ) :
					new PLL_Share_Post_Slug( $polylang );

				// Share term slugs
				// The unique key for term slug has been removed in WP 4.1
				$polylang->share_term_slug = $polylang instanceof PLL_Frontend ?
					new PLL_Frontend_Share_Term_Slug( $polylang ) :
					new PLL_Admin_Share_Term_Slug( $polylang );
			}
		}

		// Active languages
		$polylang->active_languages = new PLL_Active_Languages( $polylang );

		// Advanced media
		if ( ! $polylang instanceof PLL_Frontend && $polylang->options['media_support'] ) {
			$polylang->advanced_media = new PLL_Admin_Advanced_Media( $polylang );
		}

		// Duplicate content
		if ( ! $polylang instanceof PLL_Frontend ) {
			$polylang->duplicate = new PLL_Duplicate( $polylang );
			$polylang->sync_post = new PLL_Sync_Post( $polylang );
		}

		// Cross domain
		if ( PLL_COOKIE ) {
			switch ( $polylang->options['force_lang'] ) {
				case 2:
					$polylang->xdata = new PLL_Xdata_Subdomain( $polylang );
				break;
				case 3:
					$polylang->xdata = new PLL_Xdata_Domain( $polylang );
				break;
			}
		}
	}

	/**
	 * @since 2.1
	 */
	public function admin_enqueue_scripts() {
		$screen = get_current_screen();
		if ( 'post' != $screen->base ) { // At least for now
			return;
		}

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		wp_enqueue_script( 'pll_advanced_post', plugins_url( '/js/advanced-post' . $suffix . '.js', POLYLANG_FILE ), array( 'jquery' ), POLYLANG_VERSION, true );
		wp_enqueue_style( 'pll_advanced_admin', plugins_url( '/css/advanced-admin' . $suffix . '.css', POLYLANG_FILE ), array(), POLYLANG_VERSION );
	}
}

add_action( 'pll_pre_init', array( new Polylang_Pro(), 'init' ) );
PLL_Advanced_Plugins_Compat::instance();
