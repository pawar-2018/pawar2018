<?php

/**
 * Manages compatibility with Custom Post Type UI
 * Version tested: 1.4.3
 *
 * @since 2.1
 */
class PLL_CPTUI {

	/**
	 * Initializes filters and actions
	 *
	 * @since 2.1
	 */
	public function init() {
		if ( ! defined( 'CPTUI_VERSION' ) ) {
			return;
		}

		if ( PLL() instanceof PLL_Frontend ) {
			if ( PLL()->options['force_lang'] ) {
				// Translate strings
				add_filter( 'option_cptui_post_types', array( $this, 'translate_strings' ) );
				add_filter( 'option_cptui_taxonomies', array( $this, 'translate_strings' ) );
			} else {
				// Special case when the language is set from the content
				add_action( 'pll_language_defined', array( $this, 'pll_language_defined' ) );
			}
		} else {
			// Register strings on admin
			$cptui_post_types = get_option( 'cptui_post_types' );
			$this->register_strings( $cptui_post_types );

			$cptui_taxonomies = get_option( 'cptui_taxonomies' );
			$this->register_strings( $cptui_taxonomies );

			// Add CPT UI post types and taxonomies to Polylang settings
			add_filter( 'pll_get_post_types', array( $this, 'pll_get_types' ), 10, 2 );
			add_filter( 'pll_get_taxonomies', array( $this, 'pll_get_types' ), 10, 2 );
		}
	}

	/**
	 * Translates custom post types and taxonomies labels
	 *
	 * @since 2.1
	 *
	 * @param array $objects Array of CPT UI post types or taxonomies
	 * @return array
	 */
	public function translate_strings( $objects ) {
		foreach ( $objects as $name => $obj ) {
			$objects[ $name ]['label'] = pll__( $obj['label'] );
			$objects[ $name ]['singular_label'] = pll__( $obj['singular_label'] );
			$objects[ $name ]['description'] = pll__( $obj['description'] );

			foreach ( $obj['labels'] as $key => $label ) {
				$objects[ $name ]['labels'][ $key ] = pll__( $label );
			}
		}
		return $objects;
	}

	/**
	 * Translates custom post types and taxonomies labels when the language is set from the content
	 *
	 * @since 2.1
	 *
	 * @param array $types       Array of registered post types or taxonomies
	 * @param array $cptui_types Array of CPT UI post types or taxonomies
	 */
	public function translate_registered_types( $types, $cptui_types ) {
		foreach ( $types as $name => $type ) {
			if ( in_array( $name, $cptui_types ) ) {
				foreach ( $type->labels as $key => $label ) {
					$type->labels->$key = pll__( $type->labels->$key );
				}
			}
		}
	}

	/**
	 * Translates custom post types and taxonomies labels when the language is set from the content
	 *
	 * @since 2.1
	 */
	public function pll_language_defined() {
		$this->translate_registered_types( $GLOBALS['wp_post_types'], array_keys( get_option( 'cptui_post_types' ) ) );
		$this->translate_registered_types( $GLOBALS['wp_taxonomies'], array_keys( get_option( 'cptui_taxonomies' ) ) );
	}

	/**
	 * Registers custom post types and taxonomies labels
	 *
	 * @since 2.1
	 *
	 * @param array $objects Array of CPT UI post types or taxonomies
	 */
	public function register_strings( $objects ) {
		if ( ! empty( $objects ) ) {
			foreach ( $objects as $name => $obj ) {
				pll_register_string( $name . '_label', $obj['label'], 'CPT UI' );
				pll_register_string( $name . '_singular_label', $obj['singular_label'], 'CPT UI' );
				pll_register_string( $name . '_description', $obj['description'], 'CPT UI' );

				foreach ( $obj['labels'] as $key => $label ) {
					pll_register_string( $name . '_' . $key, $label, 'CPT UI' );
				}
			}
		}
	}

	/**
	 * Add CPT UI post types and taxonomies to Polylang settings
	 *
	 * @since 2.1
	 *
	 * @param array $types       List of post type or taxonomies names
	 * @param bool  $is_settings true when displaying the list in Polylang settings
	 * @return array
	 */
	public function pll_get_types( $types, $is_settings ) {
		if ( $is_settings ) {
			$type = substr( current_filter(), 8 );
			$cptui_types = get_option( "cptui_{$type}" );
			if ( is_array( $cptui_types ) ) {
				$types = array_merge( $types, array_keys( $cptui_types ) );
			}
		}
		return $types;
	}
}
