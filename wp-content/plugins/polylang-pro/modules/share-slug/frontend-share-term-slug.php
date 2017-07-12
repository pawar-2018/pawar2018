<?php

/**
 * Manages shared slugs for taxonomy terms on frontend side
 *
 * @since 1.9
 */
class PLL_Frontend_Share_Term_Slug extends PLL_Share_Term_Slug {
	public $curlang;

	/**
	 * Constructor
	 *
	 * @since 1.9
	 *
	 * @param object $polylang
	 */
	public function __construct( &$polylang ) {
		parent::__construct( $polylang );

		$this->curlang = &$polylang->curlang;

		add_filter( 'get_term', array( $this, 'get_term' ), 10, 2 );
	}

	/**
	 * Get a term by slug in the current language
	 *
	 * @since 1.9
	 *
	 * @param object $term
	 * @param string $taxonomy
	 * @return object
	 */
	public function get_term( $term, $taxonomy ) {
		global $wpdb;

		if ( $this->model->is_translated_taxonomy( $taxonomy ) ) {
			$traces = version_compare( PHP_VERSION, '5.2.5', '>=' ) ? debug_backtrace( false ) : debug_backtrace();

			// PHP 7 does not include call_user_func
			$n = version_compare( PHP_VERSION, '7', '>=' ) ? 3 : 4;

			// FIXME Backward compatibility with WP < 4.7
			if ( version_compare( $GLOBALS['wp_version'], '4.7', '>=' ) ) {
				$n++; // Since WP 4.7 get_term_by calls get_terms
			}

			// The filter get_term is the same in get_term and get_term_by, moreover we need to know if we get_term_by slug
			if ( isset( $traces[ $n ]['function'], $traces[ $n ]['args'][0] ) && 'get_term_by' === $traces[ $n ]['function'] && 'slug' === $traces[ $n ]['args'][0] ) {
				$join = "INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id" . $this->model->term->join_clause();
				$where = $wpdb->prepare( 'WHERE tt.taxonomy = %s AND t.slug = %s', $taxonomy, $term->slug ) . $this->model->term->where_clause( $this->curlang );
				$term = $wpdb->get_row( "SELECT t.*, tt.* FROM $wpdb->terms AS t $join $where LIMIT 1" );

				if ( ! $term ) {
					return false;
				}

				wp_cache_add( $term->term_id, $term, $taxonomy );
			}
		}

		// Since WP 4.4, get_term returns a WP_Term instance
		return new WP_Term( $term );
	}
}
