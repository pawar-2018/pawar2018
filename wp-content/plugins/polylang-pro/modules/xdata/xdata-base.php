<?php

/**
 * An abstract class to handle cross domain data and single sign on
 * Inspired by https://github.com/humanmade/Mercator/
 *
 * @since 2.0
 */
abstract class PLL_Xdata_Base {
	public $options, $model, $links_model;
	private $token;

	/**
	 * Constructor
	 *
	 * @since 2.0
	 */
	public function __construct( &$polylang ) {
		$this->options = &$polylang->options;
		$this->model = &$polylang->model;
		$this->links_model = &$polylang->links_model;

		if ( empty( $_POST['wp_customize'] ) ) {
			// Don't do that in the customizr as the redirect breaks things
			add_action( 'wp_ajax_pll_xdata_get', array( $this, 'xdata_get' ) );
			add_action( 'wp_ajax_nopriv_pll_xdata_get', array( $this, 'xdata_get' ) );
			add_action( 'wp_ajax_pll_xdata_set', array( $this, 'xdata_set' ) );
			add_action( 'wp_ajax_nopriv_pll_xdata_set', array( $this, 'xdata_set' ) );
		}

		// Login redirect
		add_action( 'set_auth_cookie', array( $this, 'set_auth_cookie' ), 10, 5 );
		add_action( 'login_redirect', array( $this, 'login_redirect' ), 10, 3 ); // Must be defined in child class
		add_filter( 'admin_url', array( $this, 'admin_url' ) );

		// Customizer
		add_filter( 'customize_allowed_urls', array( $this, 'customize_allowed_urls' ) );
		add_filter( 'allowed_http_origins', array( $this, 'allowed_http_origins' ) );
	}

	/**
	 * Get the time-dependent variable for nonce creation.
	 * Same as wp_nonce_tick() with a specific filter.
	 *
	 * @since 2.1.2
	 *
	 * @return float
	 */
	protected function nonce_tick() {
		/**
		 * Filters the lifespan of nonces in seconds.
		 * For full compatibility with cache plugins, the nonce life should be 2 times greater than the cache lifespan
		 *
		 * @since 2.1.2
		 *
		 * @param int $lifespan Lifespan of nonces in seconds. 0 makes the "nonce" time independent.
		 */
		$nonce_life = apply_filters( 'pll_xdata_nonce_life', DAY_IN_SECONDS );
		return $nonce_life ? ceil( time() / ( $nonce_life / 2 ) ) : 0;
	}

	/**
	 * Creates a user independent nonce
	 *
	 * @since 2.0
	 *
	 * @param string $action
	 * @return string
	 */
	public function create_nonce( $action ) {
		$i = $this->nonce_tick();
		return substr( wp_hash( $i . '|' . $action, 'nonce' ), -12, 10 );
	}

	/**
	 * Verifies a user independent nonce
	 *
	 * @since 2.0
	 *
	 * @param string $nonce
	 * @param string $action
	 * @return bool
	 */
	public function verify_nonce( $nonce, $action ) {
		$nonce = (string) $nonce;

		if ( empty( $nonce ) ) {
			return false;
		}

		$i = $this->nonce_tick();

		$expected = substr( wp_hash( $i . '|' . $action, 'nonce' ), -12, 10 );
		if ( hash_equals( $expected, $nonce ) ) {
			return true;
		}

		$expected = substr( wp_hash( ( $i - 1 ) . '|' . $action, 'nonce' ), -12, 10 );
		return hash_equals( $expected, $nonce );
	}

	/**
	 * Builds an ajax request url to the domain defined by a language
	 *
	 * @since 2.0
	 *
	 * @param string $lang
	 * @param array  $args
	 * @return string
	 */
	public function ajax_url( $lang, $args ) {
		$url = admin_url( 'admin-ajax.php' );
		$url = $this->links_model->switch_language_in_link( $url, $this->model->get_language( $lang ) );
		$url = add_query_arg( $args, $url );
		return $url;
	}

	/**
	 * Stores data to transfer in a user session
	 *
	 * @param
	 * @return string session key
	 */
	protected function create_data_session( $redirect, $nologin ) {
		$key = '';

		/**
		 * Filters the data to transfer from one domain to the other
		 *
		 * @since 2.0
		 *
		 * @param array $data
		 */
		$data = apply_filters( 'pll_get_xdata', array() );

		if ( is_user_logged_in() && ! $nologin ) {
			$data['token'] = wp_get_session_token();
		}

		if ( ! empty( $data ) ) {
			$data['redirect'] = $redirect;
			$data['time'] = time();
			$key = wp_hash( wp_json_encode( $data ) );
			/**
			 * Filters the session manager class where cross domain data are temporarily stored
			 *
			 * @since 2.0
			 *
			 * @param string $class class name
			 */
			$session_manager_class = apply_filters( 'pll_xdata_session_manager', 'PLL_Xdata_Session_Manager' );
			$session_manager = new $session_manager_class;
			$session_manager->set( $key, $data );
		}

		return $key;
	}

	/**
	 * Get javascript for the cross domain request when the language has just switched
	 *
	 * @since 2.0
	 *
	 * @param string $redirect redirect url
	 * @param string $lang     new language slug
	 * @return string javascript code
	 */
	protected function maybe_get_xdomain_js( $redirect, $lang ) {
		if ( ! empty( $_COOKIE[ PLL_COOKIE ] ) && $_COOKIE[ PLL_COOKIE ] !== $lang ) {
			$args = array(
				'action'   => 'pll_xdata_get',
				'redirect' => $redirect,
				'nonce'    => $this->create_nonce( 'xdata_get' ),
				'nologin'  => ! empty( $_GET['nologin'] ),
			);

			return sprintf( '
				xhr = new XMLHttpRequest();
				xhr.open( "GET", "%1$s", true );
				xhr.withCredentials = true;
				xhr.onreadystatechange = function() {
					if ( 4 == this.readyState && 200 == this.status && this.responseText && -1 != this.responseText ) {
						window.location.replace( "%2$s" + "&key=" + this.responseText );
					}
				}
				xhr.send();',
				esc_url_raw( $this->ajax_url( $_COOKIE[ PLL_COOKIE ], $args ) ),
				esc_url_raw( $this->ajax_url( $lang, array( 'action' => 'pll_xdata_set' ) ) )
			);
		}
		return '';
	}

	/**
	 * Response to pll_xdata_get request
	 * Writes cross domain data in a user session
	 *
	 * @since 2.0
	 */
	public function xdata_get() {
		// Whitelist origin + nonce verification
		if ( ! is_allowed_http_origin() || ! $this->verify_nonce( $_GET['nonce'], 'xdata_get' ) ) {
			wp_die();
		}

		// CORS
		header( sprintf( 'Access-Control-Allow-Origin: %s', $_SERVER['HTTP_ORIGIN'] ) );
		header( 'Access-Control-Allow-Credentials: true' );

		// Response
		$key = $this->create_data_session( wp_unslash( $_GET['redirect'] ), ! empty( $_GET['nologin'] ) );
		die( empty( $key ) ? '-1' : $key );
	}

	/**
	 * Response to pll_xdata_set request
	 * Final step in the cross domain data
	 * Login the user on the current domain
	 * Redirect to the url requested by the usee
	 *
	 * @since 2.0
	 */
	public function xdata_set() {
		/** This filter is documented in modules/xdata/xdata.php */
		$session_manager_class = apply_filters( 'pll_xdata_session_manager', 'PLL_Xdata_Session_Manager' );
		$session_manager = new $session_manager_class;
		$data = $session_manager->get( wp_unslash( $_GET['key'] ) );

		if ( ! empty( $data['user_id'] ) && ! empty( $data['token'] )  && time() < $data['time'] + 2 * MINUTE_IN_SECONDS ) {
			$manager = WP_Session_Tokens::get_instance( $data['user_id'] );
			// FIXME Use auth_cookie_expiration to sync the expiration time?
			wp_set_auth_cookie( $data['user_id'], false, '', $data['token'] ); // WP 4.3+
		}

		/**
		 * Fires before the redirection to the requested url
		 *
		 * @since 2.0
		 *
		 * @param array $data data transferred from one domain to the other
		 */
		do_action( 'pll_set_xdata', $data );
		wp_redirect( $data['redirect'] );
		exit;
	}

	/**
	 * Saves the user session when a user logs in, for use in login_redirect
	 *
	 * @since 2.0
	 *
	 * @param string $auth_cookie Authentication cookie.
	 * @param int    $expire      Login grace period in seconds. Default 43,200 seconds, or 12 hours.
	 * @param int    $expiration  Duration in seconds the authentication cookie should be valid.
	 * @param int    $user_id     User ID.
	 * @param string $scheme      Authentication scheme. Values include 'auth', 'secure_auth', or 'logged_in'.
	 */
	function set_auth_cookie( $auth_cookie, $expire, $expiration, $user_id, $scheme ) {
		$cookie = wp_parse_auth_cookie( $auth_cookie, $scheme );
		$this->token = $cookie['token'];
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
	function _login_redirect( $redirect_to, $requested_redirect_to, $user ) {
		if ( false !== strpos( $redirect_to, 'wp-admin' ) ) {
			$redirect_to = $this->links_model->remove_language_from_link( $redirect_to );
		}

		$data = array(
			'token'    => $this->token,
			'redirect' => $redirect_to,
			'time'     => time(),
		);

		$key = wp_hash( implode( '|', $data ) );
		/** This filter is documented in modules/xdata/xdata.php */
		$session_manager_class = apply_filters( 'pll_xdata_session_manager', 'PLL_Xdata_Session_Manager' );
		$session_manager = new $session_manager_class;
		$session_manager->set( $key, $data, $user->ID );

		// Login on main domain to access admin
		// Or if the wp-login.php is already on main domain, login on the current domain
		$url = admin_url( 'admin-ajax.php' );
		$lang = $this->links_model->get_language_from_url();
		if ( ! empty( $lang ) ) {
			$url = $this->links_model->remove_language_from_link( $url );
		}

		return add_query_arg( array( 'action' => 'pll_xdata_set', 'key' => $key ), $url );
	}

	/**
	 * Forces the admin on the main domain
	 *
	 * @since 2.0
	 *
	 * @param string $url
	 * @return string
	 */
	public function admin_url( $url ) {
		return $this->links_model->remove_language_from_link( $url );
	}

	/**
	 * Allows all our domains for cross domain requests in the customizer
	 *
	 * @since 2.0
	 *
	 * @param array $urls list of allowed urls
	 * @return array
	 */
	public function customize_allowed_urls( $urls ) {
		foreach ( $this->links_model->get_hosts() as $host ) {
			$urls[] = ( is_ssl() ? 'https://' : 'http://' ) . trailingslashit( $host );
		}
		return array_unique( $urls );
	}

	/**
	 * Allows all our domains as origins ( for customizer cross domain requests )
	 *
	 * @since 2.0
	 *
	 * @param array $origins list of allowed urls
	 * @return array
	 */
	public function allowed_http_origins( $origins ) {
		foreach ( $this->links_model->get_hosts() as $host ) {
			$origins = array_merge( $origins, array( 'http://' . $host, 'https://' . $host ) );
		}
		return array_unique( $origins );
	}
}
