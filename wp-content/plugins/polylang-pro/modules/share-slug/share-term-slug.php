<?php

/**
 * Base class for managing shared slugs for taxonomy terms
 *
 * @since 1.9
 */
class PLL_Share_Term_Slug {

	/**
	 * Constructor
	 *
	 * @since 1.9
	 *
	 * @param object $polylang
	 */
	public function __construct( &$polylang ) {
		$this->options = &$polylang->options;
		$this->model = &$polylang->model;
		$this->links_model = &$polylang->links_model;
	}

	/**
	 * Will make slug unique per language and taxonomy
	 * Mostly taken from wp_unique_term_slug
	 *
	 * @since 1.9
	 *
	 * @param string $slug The string that will be tried for a unique slug
	 * @param string $lang language slug
	 * @param object $term The term object that the $slug will belong too
	 * @return string Will return a true unique slug.
	 */
	protected function unique_term_slug( $slug, $lang, $term ) {
		global $wpdb;

		// Quick check
		if ( ! $this->term_exists( $slug, $lang, $term->taxonomy ) ) {
			return $slug;
		}

		// As done by WP in term_exists except that we use our own term_exist
		// If the taxonomy supports hierarchy and the term has a parent, make the slug unique
		// by incorporating parent slugs.
		if ( is_taxonomy_hierarchical( $term->taxonomy ) && ! empty( $term->parent ) ) {
			$the_parent = $term->parent;
			while ( ! empty( $the_parent ) ) {
				$parent_term = get_term( $the_parent, $term->taxonomy );
				if ( is_wp_error( $parent_term ) || empty( $parent_term ) )
					break;
				$slug .= '-' . $parent_term->slug;
				if ( ! $this->term_exists( $slug, $lang ) ) // calls our own term_exists
					return $slug;

				if ( empty( $parent_term->parent ) )
					break;
				$the_parent = $parent_term->parent;
			}
		}

		// If we didn't get a unique slug, try appending a number to make it unique.
		if ( ! empty( $term->term_id ) ) {
			$query = $wpdb->prepare( "SELECT slug FROM $wpdb->terms WHERE slug = %s AND term_id != %d", $slug, $term->term_id );
		}
		else {
			$query = $wpdb->prepare( "SELECT slug FROM $wpdb->terms WHERE slug = %s", $slug );
		}

		if ( $wpdb->get_var( $query ) ) {
			$num = 2;
			do {
				$alt_slug = $slug . "-$num";
				$num++;
				$slug_check = $wpdb->get_var( $wpdb->prepare( "SELECT slug FROM $wpdb->terms WHERE slug = %s", $alt_slug ) );
			} while ( $slug_check );
			$slug = $alt_slug;
		}

		return $slug;
	}

	/**
	 * Checks if a term slug exists in a given language, taxonomy, hierarchy
	 *
	 * @since 1.9
	 *
	 * @param string        $slug
	 * @param string|object $language the language slug or object
	 * @param string        $taxonomy optional taxonomy name
	 * @param int           $parent   optional parent term id
	 * @return null|int the term_id of the found term
	 */
	protected function term_exists( $slug, $language, $taxonomy = '', $parent = 0 ) {
		global $wpdb;

		$select = "SELECT t.term_id FROM $wpdb->terms AS t";
		$join = " INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id";
		$join .= $this->model->term->join_clause();
		$where = $wpdb->prepare( ' WHERE t.slug = %s', $slug );
		$where .= $this->model->term->where_clause( $this->model->get_language( $language ) );

		if ( ! empty( $taxonomy ) ) {
			$where .= $wpdb->prepare( ' AND tt.taxonomy = %s', $taxonomy );
		}

		if ( $parent > 0 ) {
			$where .= $wpdb->prepare( ' AND tt.parent = %d', $parent );
		}

		return $wpdb->get_var( $select . $join . $where );
	}
}
