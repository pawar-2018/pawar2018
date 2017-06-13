<?php

/**
 * Links Model for translating slugs
 *
 * @since 1.9
 */
class PLL_Translate_Slugs_Model {
	public $translated_slugs;

	/**
	 * Constructor
	 *
	 * @since 1.9
	 */
	public function __construct( &$polylang ) {
		$this->model = &$polylang->model;
		$this->links_model = &$polylang->links_model;

		add_action( 'switch_blog', array( $this, 'switch_blog' ), 20, 2 );

		add_action( 'wp_loaded', array( $this, 'init_translated_slugs' ) );

		// Make sure to prepare rewrite rules when flushing
		add_action( 'pre_option_rewrite_rules', array( $this, 'prepare_rewrite_rules' ), 20 ); // after Polylang

		// Flush rewrite rules when saving string translations
		add_action( 'pll_save_strings_translations', array( $this, 'flush_rewrite_rules' ) );

		// Register strings for translated slugs
		add_action( 'admin_init', array( $this, 'register_slugs' ) );
		add_filter( 'pll_sanitize_string_translation', array( $this, 'sanitize_string_translation' ), 10, 3 );

		// Reset cache when adding or modifying languages
		add_action( 'pll_add_language', array( $this, 'clean_cache' ) );
		add_action( 'pll_update_language', array( $this, 'clean_cache' ) );

		// Make sure we have all (possibly new) translatable slugs in the strings list table
		if ( $polylang instanceof PLL_Settings && isset( $_GET['page'] ) && 'mlang_strings' == $_GET['page'] ) {
			delete_transient( 'pll_translated_slugs' );
		}
	}

	/**
	 * Updates the list of slugs to translate when switching blog
	 *
	 * @since 1.9
	 *
	 * @param int $new_blog
	 * @param int $old_blog
	 */
	public function switch_blog( $new_blog, $old_blog ) {
		$plugins = ( $sitewide_plugins = get_site_option( 'active_sitewide_plugins' ) ) && is_array( $sitewide_plugins ) ? array_keys( $sitewide_plugins ) : array();
		$plugins = array_merge( $plugins, get_option( 'active_plugins', array() ) );

		// FIXME should I wait for an action as I must have *all* registered post types and taxonomies
		if ( $new_blog != $old_blog && in_array( POLYLANG_BASENAME, $plugins ) && get_option( 'polylang' ) ) {
			$this->init_translated_slugs();
		}
	}

	/**
	 * Initializes the list of translated slugs
	 * Need to wait for all post types and taxonomies to be registered
	 *
	 * @since 1.9
	 */
	public function init_translated_slugs() {
		$this->translated_slugs = $this->get_translatable_slugs();

		// Keep only slugs which are translated to avoid unnecessary rewrite rules
		foreach ( $this->translated_slugs as $key => $value ) {
			if ( 1 == count( array_unique( $value['translations'] ) ) && reset( $value['translations'] ) == $value['slug'] ) {
				unset( $this->translated_slugs[ $key ] );
			}
		}
	}

	/**
	 * Translates a slug in a permalink ( from original slug )
	 *
	 * @since 1.9
	 *
	 * @param string $link url to modify
	 * @param object $lang language
	 * @param string $type type of slug to translate
	 * @return string modified url
	 */
	public function translate_slug( $link, $lang, $type ) {
		if ( ! empty( $lang ) && isset( $this->translated_slugs[ $type ] ) ) {
			$link = str_replace(
				'/' . $this->translated_slugs[ $type ]['slug'] . '/',
				'/' . $this->translated_slugs[ $type ]['translations'][ $lang->slug ] . '/',
				$link
			);
		}
		return $link;
	}

	/**
	 * Translates a slug in a permalink ( from an already translated slug )
	 *
	 * @since 1.9
	 *
	 * @param string $link url to modify
	 * @param object $lang language
	 * @param string $type type of slug to translate
	 * @return string modified url
	 */
	public function switch_translated_slug( $link, $lang, $type ) {
		if ( isset( $this->translated_slugs[ $type ] ) ) {
			$slugs = $this->translated_slugs[ $type ]['translations'];
			$slugs[] = $this->translated_slugs[ $type ]['slug'];

			$link = preg_replace(
				'#\/(' . implode( '|', array_unique( $slugs ) ) . ')\/#',
				'/' . $this->translated_slugs[ $type ]['translations'][ $lang->slug ] . '/',
				$link
			);
		}
		return $link;
	}

	/**
	 * Returns informations on translatable slugs
	 * stores them in a transient
	 *
	 * @since 1.9
	 *
	 * @return array
	 */
	public function get_translatable_slugs() {
		global $wp_rewrite;

		$slugs = get_transient( 'pll_translated_slugs' );

		if ( false === $slugs ) {
			$slugs = array();

			foreach ( $this->model->get_languages_list() as $language ) {
				$mo = new PLL_MO();
				$mo->import_from_db( $language );

				// Post types
				foreach ( get_post_types() as $type ) {
					$type = get_post_type_object( $type );

					if ( ! empty( $type->rewrite['slug'] ) && $this->model->is_translated_post_type( $type->name ) ) {
						$slug = preg_replace( '#%.+?%#', '', $type->rewrite['slug'] ); // For those adding a taxonomy base in custom post type link. See http://wordpress.stackexchange.com/questions/94817/add-category-base-to-url-in-custom-post-type-taxonomy
						$slug = trim( $slug, '/' ); // It seems that some plugins add / (ex: woocommerce)
						$slugs[ $type->name ]['slug'] = $slug;
						$tr_slug = $mo->translate( $slug );
						$slugs[ $type->name ]['translations'][ $language->slug ] = empty( $tr_slug ) ? $slug : $tr_slug;

						// Post types archives
						if ( ! empty( $type->has_archive ) ) {
							if ( true === $type->has_archive ) {
								$slugs[ 'archive_' . $type->name ]['hide'] = true;
							} else {
								$slug = $type->has_archive;
							}

							$slugs[ 'archive_' . $type->name ]['slug'] = $slug;
							$tr_slug = $mo->translate( $slug );
							$slugs[ 'archive_' . $type->name ]['translations'][ $language->slug ] = empty( $tr_slug ) ? $slug : $tr_slug;
						}
					}
				}

				// Taxonomies
				foreach ( get_taxonomies() as $tax ) {
					$tax = get_taxonomy( $tax );
					if ( ! empty( $tax->rewrite['slug'] ) && ( $this->model->is_translated_taxonomy( $tax->name ) || 'post_format' == $tax->name ) ) {
						$slug = trim( $tax->rewrite['slug'], '/' ); // It seems that some plugins add / (ex: woocommerce for product attributes)
						$slugs[ $tax->name ]['slug'] = $slug;
						$tr_slug = $mo->translate( $slug );
						$slugs[ $tax->name ]['translations'][ $language->slug ] = empty( $tr_slug ) ? $slug : $tr_slug;
					}
				}

				// Post formats
				// get_theme_support sends an array of array
				$formats = get_theme_support( 'post-formats' );
				if ( isset( $formats[0] ) && is_array( $formats[0] ) ) {
					foreach ( $formats[0] as $format ) {
						$slugs[ 'post-format-' . $format ]['slug'] = $format;
						$tr_format = $mo->translate( $format );
						$slugs[ 'post-format-' . $format ]['translations'][ $language->slug ] = empty( $tr_format ) ? $format : $tr_format;
					}
				}

				// Misc
				foreach ( array( 'author', 'search', 'attachment' ) as $slug ) {
					$slugs[ $slug ]['slug'] = $slug;
					$tr_slug = $mo->translate( $slug );
					$slugs[ $slug ]['translations'][ $language->slug ] = empty( $tr_slug ) ? $slug : $tr_slug;
				}

				// Paged pages
				$slugs['paged']['slug'] = 'page';
				$tr_slug = $mo->translate( 'page' );
				$slugs['paged']['translations'][ $language->slug ] = empty( $tr_slug ) ? 'page' : $tr_slug;

				// /blog/
				if ( ! empty( $wp_rewrite->front ) ) {
					$slug = trim( $wp_rewrite->front, '/' );
					$slugs['front']['slug'] = $slug;
					$tr_slug = $mo->translate( $slug );
					$slugs['front']['translations'][ $language->slug ] = empty( $tr_slug ) ? $slug : $tr_slug;
				}

				/**
				 * Filter the list of translated slugs
				 *
				 * @since 1.9
				 *
				 * @param array  $slugs    the list of slugs
				 * @param object $language the language object
				 * @param object $mo       the translations object
				 */
				$slugs = apply_filters_ref_array( 'pll_translated_slugs', array( $slugs, $language, &$mo ) );
			}

			// Make sure to store the transient only after 'wp_loaded' has been fired to avoid a conflict with Page Builder 2.4.10+
			if ( did_action( 'wp_loaded' ) ) {
				set_transient( 'pll_translated_slugs', $slugs );
			}
		}

		return $slugs;
	}

	/**
	 * Prepares rewrite rules filters to translate slugs
	 *
	 * @since 1.9
	 *
	 * @param array $pre not used
	 * @return unmodified $pre
	 */
	public function prepare_rewrite_rules( $pre ) {
		if ( did_action( 'wp_loaded' ) && ! has_filter( 'rewrite_rules_array', array( $this, 'rewrite_translated_slug' ) ) ) {
			$this->init_translated_slugs();
			foreach ( $this->links_model->get_rewrite_rules_filters() as $type ) {
				add_filter( $type . '_rewrite_rules', array( $this, 'rewrite_translated_slug' ), 5 );
			}

			add_filter( 'rewrite_rules_array', array( $this, 'rewrite_translated_slug' ), 5 );
		}

		return $pre;
	}

	/**
	 * Flush rewrite rules when saving strings translations
	 *
	 * @since 1.9
	 */
	public function flush_rewrite_rules() {
		delete_transient( 'pll_translated_slugs' );
		flush_rewrite_rules();
	}

	/**
	 * Returns the rewrite rule pattern for the new slug
	 *
	 * @ince 1.8
	 *
	 * @param string $type type of slug
	 * @return string the pattern
	 */
	protected function get_translated_slugs_pattern( $type ) {
		$slugs = $this->translated_slugs[ $type ]['translations'];
		$slugs[] = $this->translated_slugs[ $type ]['slug'];
		return '(' . implode( '|', array_unique( $slugs ) ) . ')/';
	}

	/**
	 * Translates a slug in rewrite rules
	 *
	 * @since 1.9
	 *
	 * @param array  $rules rewrite rules
	 * @param string $type  type of slug to translate
	 * @return array modified rewrite rules
	 */
	protected function translate_rule( $rules, $type ) {
		if ( empty( $this->translated_slugs[ $type ] ) ) {
			return $rules;
		}

		$old = $this->translated_slugs[ $type ]['slug'] . '/';
		$new = $this->get_translated_slugs_pattern( $type );

		foreach ( $rules as $key => $rule ) {
			if ( false !== $found = strpos( $key, $old ) ) {
				$new_key = 0 === $found ?  str_replace( $old, $new, $key ) : str_replace( '/' . $old, '/' . $new, $key );
				$newrules[ $new_key ] = str_replace(
					array( '[8]', '[7]', '[6]', '[5]', '[4]', '[3]', '[2]', '[1]' ),
					array( '[9]', '[8]', '[7]', '[6]', '[5]', '[4]', '[3]', '[2]' ),
					$rule
				); // Hopefully it is sufficient!
			} else {
				$newrules[ $key ] = $rule;
			}
		}
		return $newrules;
	}

	/**
	 * Translates the post format slug in rewrite rules
	 *
	 * @since 1.9
	 *
	 * @param array $rules rewrite rules
	 * @return array modified rewrite rules
	 */
	protected function translate_post_format_rule( $rules ) {
		$newrules = array();
		$formats = get_theme_support( 'post-formats' );

		if ( isset( $formats[0] ) && is_array( $formats[0] ) ) {
			foreach ( $formats[0] as $format ) {
				if ( isset( $this->translated_slugs[ 'post-format-' . $format ] ) ) {
					$new_slug = '/' . $this->get_translated_slugs_pattern( 'post-format-' . $format );
					foreach ( $rules as $key => $rule ) {
						$newrules[ str_replace( '/([^/]+)/', $new_slug, $key ) ] = str_replace( '$matches[1]', $format, $rule );
					}
				}
			}
		}

		return $newrules + $rules;
	}

	/**
	 * Translates the post type archive in rewrite rules
	 *
	 * @since 1.9
	 *
	 * @param array $rules rewrite rules
	 * @return array modified rewrite rules
	 */
	protected function translate_post_type_archive_rule( $rules ) {
		$cpts = array_intersect( $this->model->get_translated_post_types(), get_post_types( array( '_builtin' => false ) ) );
		$cpts = $cpts ? '#post_type=(' . implode( '|', $cpts ) . ')#' : '';

		foreach ( $rules as $key => $rule ) {
			if ( $cpts && preg_match( $cpts, $rule, $matches ) && ! strpos( $rule, 'name=' ) && ( $post_type = $matches[1] ) && isset( $this->translated_slugs[ 'archive_' . $post_type ] ) ) {
				$new_slug = $this->get_translated_slugs_pattern( 'archive_' . $post_type );

				$newrules[ str_replace( $this->translated_slugs[ 'archive_' . $post_type ]['slug'] . '/', $new_slug, $key ) ] = str_replace(
					array( '[8]', '[7]', '[6]', '[5]', '[4]', '[3]', '[2]', '[1]' ),
					array( '[9]', '[8]', '[7]', '[6]', '[5]', '[4]', '[3]', '[2]' ),
					$rule
				); // Hopefully it is sufficient!
			} else {
				$newrules[ $key ] = $rule;
			}
		}
		return $newrules;
	}

	/**
	 * Generates a reverse array of [$i]
	 *
	 * @since 1.9
	 *
	 * @param int $start
	 * @param int $end
	 * @return array
	 */
	protected function range( $start, $end ) {
		foreach ( range( $start, $end ) as $i ) {
			$arr[] = "[$i]";
		}
		return array_reverse( $arr );
	}

	/**
	 * Translates the page slug in rewrite rules
	 *
	 * @since 1.9
	 *
	 * @param array $rules rewrite rules
	 * @return array modified rewrite rules
	 */
	protected function translate_paged_rule( $rules ) {
		if ( empty( $this->translated_slugs['paged'] ) ) {
			return $rules;
		}

		$newrules = array();

		$old = $this->translated_slugs['paged']['slug'] . '/';
		$new = $this->get_translated_slugs_pattern( 'paged' );

		foreach ( $rules as $key => $rule ) {
			if ( strpos( $key, '/page/' ) && $count = preg_match_all( '#\[\d\]|\$\d#', $rule ) ) {
				$newrules[ str_replace( '/' . $old, '/' . $new, $key ) ] = str_replace(
					$this->range( $count, 8 ),
					$this->range( $count + 1, 9 ),
					$rule
				); // Hopefully it is sufficient!
			} elseif ( 0 === strpos( $key, 'page/' ) && $count = preg_match_all( '#\[\d\]|\$\d#', $rule ) ) {
				// Special case for root
				$newrules[ str_replace( $old, $new, $key ) ] = str_replace(
					$this->range( $count, 8 ),
					$this->range( $count + 1, 9 ),
					$rule
				);
			} else {
				$newrules[ $key ] = $rule;
			}
		}

		return $newrules;
	}

	/**
	 * Modifies rewrite rules to translate post types and taxonomies slugs
	 *
	 * @since 1.9
	 *
	 * @param array $rules rewrite rules
	 * @return array modified rewrite rules
	 */
	function rewrite_translated_slug( $rules ) {
		$filter = str_replace( '_rewrite_rules', '', current_filter() );

		$rules = $this->translate_paged_rule( $rules ); // Important that it is the first

		if ( 'rewrite_rules_array' === $filter ) {
			$rules = $this->translate_post_type_archive_rule( $rules );
		} else {
			if ( 'post_format' === $filter ) {
				$rules = $this->translate_post_format_rule( $rules );
			}

			$rules = $this->translate_rule( $rules, $filter );
		}

		$rules = $this->translate_rule( $rules, 'attachment' );
		$rules = $this->translate_rule( $rules, 'front' );

		return $rules;
	}

	/**
	 * Register strings for translated slugs
	 *
	 * @since 1.9
	 */
	public function register_slugs() {
		foreach ( $this->get_translatable_slugs() as $key => $type ) {
			if ( empty( $type['hide'] ) ) {
				pll_register_string( 'slug_' . $key, $type['slug'], __( 'URL slugs', 'polylang-pro' ) );
			}
		}
	}

	/**
	 * Performs the sanitization ( before saving in DB ) of slugs translations
	 *
	 * @since 1.9
	 *
	 * @param string $translation translation to sanitize
	 * @param string $name        unique name for the string, not used
	 * @param string $context     the group in which the string is registered
	 * @return string
	 */
	public function sanitize_string_translation( $translation, $name, $context ) {
		if ( 0 === strpos( $name, 'slug_' ) ) {
			// Inspired by category base sanitization
			$translation = preg_replace('#/+#', '/', str_replace( '#', '', $translation ) );
			$translation = trim( $translation, '/' );
			$translation = esc_url_raw( $translation );
			$translation = str_replace( 'http://', '', $translation );
		}
		return $translation;
	}

	/**
	 * Deletes the transient when adding or modifying a language
	 *
	 * @since 1.9
	 */
	public function clean_cache() {
		delete_transient( 'pll_translated_slugs' );
	}
}

