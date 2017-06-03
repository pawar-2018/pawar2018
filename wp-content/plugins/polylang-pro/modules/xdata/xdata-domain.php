<?php

/**
 * A class to handle cross domain data and single sign on for multiple domains
 *
 * @since 2.0
 */
class PLL_Xdata_Domain extends PLL_Xdata_Base {
	public $choose_lang;

	/**
	 * Constructor
	 *
	 * @since 2.0
	 */
	public function __construct( &$polylang ) {
		parent::__construct( $polylang );

		$this->choose_lang = &$polylang->choose_lang;

		add_action( 'pll_init', array( $this, 'pll_init' ) );
		add_action( 'admin_init', array( $this, 'admin_init' ) );

		if ( empty( $_POST['wp_customize'] ) ) {
			add_action( 'wp_head', array( $this, 'check_request' ), 0 ); // As soon as possible
			add_action( 'wp_ajax_pll_xdata_check', array( $this, 'xdata_check' ) );
			add_action( 'wp_ajax_nopriv_pll_xdata_check', array( $this, 'xdata_check' ) );
		}

		// Post preview
		if ( isset( $_GET['preview_id'] ) ) {
			add_action( 'init', array( $this, 'check_request' ), 5 ); // Before _show_post_preview
		}
	}

	/**
	 * Set language cookie
	 *
	 * @since 2.0
	 *
	 * @param string $lang language code
	 */
	protected function maybe_set_cookie( $lang ) {
		if ( ! headers_sent() && ( ! isset( $_COOKIE[ PLL_COOKIE ] ) || $_COOKIE[ PLL_COOKIE ] !== $lang ) ) {
			/** This filter is documented in frontend/choose-lang.php */
			$expiration = apply_filters( 'pll_cookie_expiration', YEAR_IN_SECONDS );
			setcookie( PLL_COOKIE, $lang, time() + $expiration, COOKIEPATH, COOKIE_DOMAIN );
		}
	}

	/**
	 * Allow to use the correct domain for preview links
	 * as we force them to be on main domain when not using the Xdata module
	 *
	 * @since 2.0
	 *
	 * @param object $polylang
	 */
	public function pll_init( $polylang ) {
		remove_filter( 'preview_post_link', array( $polylang->filters_links, 'preview_post_link' ), 20 );
	}

	/**
	 * Set the cookie to main domain when visiting the admin
	 * This is necessary to avoid 404 when previewing a post on non default domain
	 * Make sure the cookie is set on admin and not on ajax request to avoid infinite redirect loop
	 *
	 * @since 2.0
	 */
	public function admin_init() {
		if ( ! PLL() instanceof PLL_Frontend ) {
			$this->maybe_set_cookie( $this->links_model->get_language_from_url() );
		}
	}

	/**
	 * Outputs the link to the javascript request to main domain
	 *
	 * @since 2.0
	 */
	public function check_request() {
		$args = array(
			'action'   => 'pll_xdata_check',
			'redirect' => urlencode( ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ),
			'nonce'    => $this->create_nonce( 'xdata_check' ),
			'nologin'  => is_user_logged_in(),
		);

		$url = $this->ajax_url( $this->options['default_lang'], $args );
		printf( '<script type="text/javascript" src="%s"></script>', esc_url( $url ) );
	}

	/**
	 * Response to pll_xdata_check request
	 * Redirects to the preferred language home page at first visit
	 * Sets the language cookie on main domain
	 * Initiates a cross domain data transfer if the language has just changed
	 *
	 * @since 2.0
	 */
	public function xdata_check() {
		if ( ! $this->verify_nonce( $_GET['nonce'], 'xdata_check' ) ) {
			wp_die();
		}

		$redirect = wp_unslash( $_GET['redirect'] );
		$lang = $this->links_model->get_language_from_url( $redirect );

		// Redirects to the preferred language home page at first visit
		if ( ! empty( $this->options['browser'] ) && ! isset( $_COOKIE[ PLL_COOKIE ] ) && trailingslashit( $redirect ) === $this->model->get_language( $lang )->home_url ) {
			$preflang = $this->choose_lang->get_preferred_browser_language();
			if ( $preflang && $preflang !== $lang ) {
				header( 'Content-Type: application/javascript' );
				printf( 'window.location.replace("%s");', esc_url_raw( $this->model->get_language( $preflang )->home_url ) );
				exit;
			}
		}

		$this->maybe_set_cookie( $lang ); // Sets the language cookie on main domain

		header( 'Content-Type: application/javascript' );
		echo $this->maybe_get_xdomain_js( $redirect, $lang );
		exit;
	}

	/**
	 * Saves info on the current user session and redirects to the main domain
	 *
	 * @since 2.0
	 *
	 * @param string           $redirect_to           The redirect destination URL.
	 * @param string           $requested_redirect_to The requested redirect destination URL passed as a parameter.
	 * @param WP_User|WP_Error $user                  WP_User object if login was successful, WP_Error object otherwise.
	 * @return string
	 */
	function login_redirect( $redirect_to, $requested_redirect_to, $user ) {
		$main_host = parse_url( $this->links_model->remove_language_from_link( $redirect_to ), PHP_URL_HOST );
		if ( $main_host !== $_SERVER['HTTP_HOST'] && ! is_wp_error( $user ) ) {
			$redirect_to = $this->_login_redirect( $redirect_to, $requested_redirect_to, $user );
		}
		return $redirect_to;
	}
}
