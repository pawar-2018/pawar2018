<?php

/**
 * Manages shared slugs for posts on frontend side
 *
 * @since 1.9
 */
class PLL_Frontend_Share_Post_Slug extends PLL_Share_Post_Slug {
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

		// Get page by pagename and lang
		add_action( 'parse_query', array( $this, 'parse_query' ), 0 ); // Before all other functions hooked to 'parse_query'

		// Get post by name and lang
		add_filter( 'posts_join', array( $this, 'posts_join' ), 10, 2 );
		add_filter( 'posts_where', array( $this, 'posts_where' ), 10, 2 );
	}

	/**
	 * Modifies the query object when a page is queried by slug and language
	 * This must be the first function hooked to 'parse_query' to run so that others get the right queried page
	 *
	 * @since 1.9
	 *
	 * @param object $query reference to query object
	 */
	public function parse_query( $query ) {
		if ( empty( $this->curlang ) ) {
			return;
		}

		$qv = $query->query_vars;

		// For hierarchical custom post types
		if ( empty( $qv['pagename'] ) && ! empty( $qv['name'] ) && ! empty( $qv['post_type'] ) && array_intersect( get_post_types( array( 'hierarchical' => true ) ), (array) $qv['post_type'] ) ) {
			$qv['pagename'] = $qv['name'];
		}

		if ( ! empty( $qv['pagename'] ) ) {
			// FIXME see simpler solution at https://github.com/mirsch/polylang-slug/commit/4bf2cb80256fc31347455f6539fac0c20f403c04
			$query->queried_object = $this->get_page_by_path( $qv['pagename'], $this->curlang->slug, OBJECT, empty( $qv['post_type'] ) ? 'page' : $qv['post_type'] );

			if ( ! empty( $query->queried_object ) ) {
				$query->queried_object_id = (int) $query->queried_object->ID;
			} else {
				unset( $query->queried_object );
			}
		}
	}

	/**
	 * Retrieves a page given its path.
	 * This is the same function as WP get_page_by_path()
	 * Rewritten to make it language dependent
	 *
	 * @since 1.9
	 *
	 * @param string       $page_path Page path
	 * @param string       $lang      language slug
	 * @param string       $output    Optional. Output type. Accepts OBJECT, ARRAY_N, or ARRAY_A. Default OBJECT.
	 * @param string|array $post_type Optional. Post type or array of post types. Default 'page'.
	 * @return WP_Post|null WP_Post on success or null on failure.
	 */
	protected function get_page_by_path( $page_path, $lang, $output = OBJECT, $post_type = 'page' ) {
		global $wpdb;

		$page_path = rawurlencode( urldecode( $page_path ) );
		$page_path = str_replace( '%2F', '/', $page_path );
		$page_path = str_replace( '%20', ' ', $page_path );
		$parts = explode( '/', trim( $page_path, '/' ) );
		$parts = esc_sql( $parts );
		$parts = array_map( 'sanitize_title_for_query', $parts );

		$in_string = "'" . implode( "','", $parts ) . "'";

		if ( is_array( $post_type ) ) {
			$post_types = $post_type;
		} else {
			$post_types = array( $post_type, 'attachment' );
		}

		$post_types = esc_sql( $post_types );
		$post_type_in_string = "'" . implode( "','", $post_types ) . "'";
		$sql = "SELECT ID, post_name, post_parent, post_type FROM $wpdb->posts";
		$sql .= $this->model->post->join_clause();
		$sql .= " WHERE post_name IN ( $in_string ) AND post_type IN ( $post_type_in_string )";
		$sql .= $this->model->post->where_clause( $lang );

		$pages = $wpdb->get_results( $sql, OBJECT_K );

		$revparts = array_reverse( $parts );

		$foundid = 0;
		foreach ( (array) $pages as $page ) {
			if ( $page->post_name == $revparts[0] ) {
				$count = 0;
				$p = $page;
				while ( $p->post_parent != 0 && isset( $pages[ $p->post_parent ] ) ) {
					$count++;
					$parent = $pages[ $p->post_parent ];
					if ( ! isset( $revparts[ $count ] ) || $parent->post_name != $revparts[ $count ] )
						break;
					$p = $parent;
				}

				if ( $p->post_parent == 0 && $count+1 == count( $revparts ) && $p->post_name == $revparts[ $count ] ) {
					$foundid = $page->ID;
					if ( $page->post_type == $post_type )
						break;
				}
			}
		}

		if ( $foundid )
			return get_post( $foundid, $output );

		return null;
	}

	/**
	 * Adds our join clause to sql query
	 * Useful when querying a post by name
	 *
	 * @since 1.9
	 *
	 * @param string $join original join clause
	 * @param object $query
	 * @return string modified join clause
	 */
	public function posts_join( $join, $query ) {
		if ( $this->can_filter( $query ) ) {
			return $join . $this->model->post->join_clause();
		}
		return $join;
	}

	/**
	 * Adds our where clause to sql query
	 * Useful when querying a post by name
	 *
	 * @since 1.9
	 *
	 * @param string $where original where clause
	 * @param object $query
	 * @return string modified where clause
	 */
	public function posts_where( $where, $query ) {
		if ( $this->can_filter( $query ) ) {
			return $where . $this->model->post->where_clause( $this->curlang );
		}
		return $where;
	}

	/**
	 * Checks if the query must be filtered or not
	 *
	 * @since 1.9
	 *
	 * @param object $query
	 * @return bool
	 */
	protected function can_filter( $query ) {
		// Don't filter if the 'lang' query var is explicitely set to an empty string
		if ( ( isset( $query->query_vars['lang'] ) && ! $query->query_vars['lang'] ) || empty( $this->curlang ) ) {
			return false;
		}

		// Don't filter shortlinks to avoid 404
		if ( 1 === count( $query->query ) && isset( $query->query['p'] ) ) {
			return false;
		}

		if ( isset( $query->tax_query->queried_terms ) ) {
			$taxonomies = array_keys( wp_list_filter( $query->tax_query->queried_terms, array( 'operator' => 'NOT IN' ), 'NOT' ) );
			$taxonomies = array_diff( $taxonomies, array( 'language' ) );
			return $this->model->is_translated_taxonomy( $taxonomies );
		}

		$post_type = empty( $query->query_vars['post_type'] ) ? 'post' : $query->query_vars['post_type'];
		return $this->model->is_translated_post_type( $post_type );
	}
}
