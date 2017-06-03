<?php

/**
 * Manages compatibility with Advanced Custom Fields Pro
 *
 * @since 2.0
 */
class PLL_ACF {
	/**
	 * Initializes filters for ACF
	 *
	 * @since 2.0
	 */
	public function init() {
		if ( ! class_exists( 'acf' ) ) {
			return;
		}

		add_action( 'add_meta_boxes_acf-field-group', array( $this, 'remove_sync' ) );

		add_filter( 'acf/location/rule_match/page_type', array( $this, 'rule_match_page_type' ), 20, 3 ); // After ACF

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 10, 2 ); // After Polylang
		add_action( 'pll_save_post', array( $this, 'save_post' ), 20, 3 ); // After Polylang

		add_filter( 'pll_get_post_types', array( $this, 'get_post_types' ), 10, 2 );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		add_action( 'wp_ajax_acf_post_lang_choice', array( $this, 'acf_post_lang_choice' ) );
	}

	/**
	 * Deactivate synchronization for ACF field groups
	 *
	 * @since 2.1
	 */
	public function remove_sync( $post_type ) {
		foreach ( pll_languages_list() as $lang ) {
			remove_action( "pll_before_post_translation_{$lang}", array( PLL()->sync_post->buttons[ $lang ], 'add_icon' ) );
		}
	}

	/**
	 * Allow page on front and page for posts translations to match the corresponding page type
	 *
	 * @since 2.0
	 *
	 * @param bool  $match
	 * @param array $rule
	 * @param array $options
	 * @return bool
	 */
	function rule_match_page_type( $match, $rule, $options ) {
		if ( $options['post_id'] ) {
			$post = get_post( $options['post_id'] );

			if ( 'front_page' === $rule['value'] && $front_page = (int) get_option( 'page_on_front' ) ) {
				$translations = pll_get_post_translations( $front_page );

				if ( '==' === $rule['operator'] ) {
					$match = in_array( $post->ID, $translations );
				} elseif ( '!=' === $rule['operator'] ) {
					$match = ! in_array( $post->ID, $translations );
				}
			} elseif ( 'posts_page' === $rule['value'] && $posts_page = (int) get_option( 'page_for_posts' ) ) {
				$translations = pll_get_post_translations( $posts_page );

				if ( '==' === $rule['operator'] ) {
					$match = in_array( $post->ID, $translations );
				} elseif ( '!=' === $rule['operator'] ) {
					$match = ! in_array( $post->ID, $translations );
				}
			}
		}

		return $match;
	}

	/**
	 * Copy metas when using "Add new" ( translation )
	 *
	 * @since 2.0
	 *
	 * @param string $post_type
	 * @param object $post      current post object
	 */
	public function add_meta_boxes( $post_type, $post ) {
		if ( 'post-new.php' === $GLOBALS['pagenow'] && isset( $_GET['from_post'], $_GET['new_lang'] ) && PLL()->model->is_translated_post_type( $post_type ) ) {
			// Capability check already done in post-new.php
			$lang = PLL()->model->get_language( $_GET['new_lang'] ); // Make sure we have a valid language

			if ( 'acf-field-group' === $post_type ) {
				$duplicate_options = get_user_meta( get_current_user_id(), 'pll_duplicate_content', true );
				$active = ! empty( $duplicate_options ) && ! empty( $duplicate_options[ $post_type ] );

				if ( $active ) {
					acf_duplicate_field_group( (int) $_GET['from_post'], $post->ID );
					if ( version_compare( acf()->get_setting( 'version' ), '5.4.0', '>=' ) ) {
						acf_delete_cache( 'get_fields/ID=' . $post->ID ); // Since ACF 5.4.0
					}
				}

			} else {
				$this->copy_post_metas( (int) $_GET['from_post'], $post->ID, $lang->slug );
			}
		}
	}

	/**
	 * Synchronizes metas in translations
	 *
	 * @since 2.0
	 *
	 * @param int    $post_id      post id
	 * @param object $post         post object
	 * @param array  $translations post translations
	 */
	public function save_post( $post_id, $post, $translations ) {
		// Synchronize terms and metas in translations
		foreach ( $translations as $lang => $tr_id ) {
			if ( $tr_id && $tr_id !== $post_id ) {
				$this->copy_post_metas( $post_id, $tr_id, $lang, true );
			}
		}
	}

	/**
	 * Copy or synchronize metas
	 *
	 * @since 2.0
	 *
	 * @param int    $from id of the post from which we copy informations
	 * @param int    $to   id of the post to which we paste informations
	 * @param string $lang language slug
	 * @param bool $sync true if it is synchronization, false if it is a copy, defaults to false
	 */
	public function copy_post_metas( $from, $to, $lang, $sync = false ) {
		if ( ( ! $sync || in_array( 'post_meta', PLL()->options['sync'] ) || PLL()->sync_post->are_synchronized( $from, $to ) ) && $fields = get_field_objects( $from ) ) {
			foreach ( $fields as $field ) {
				$translated_fields = array();
				$this->translate_fields( $translated_fields, $field['value'], $field['name'], $field, $lang );
				foreach ( $translated_fields as $key => $value ) {
					update_post_meta( $to, $key, $value );
				}
			}
		}
	}

	/**
	 * Translate custom fields if needed
	 * Recursive for repeaters and flexible content
	 *
	 * @since 2.0
	 *
	 * @param array         $r     list of translated custom fields
	 * @param array|object  $value custom field value
	 * @param string        $name  custom field name
	 * @param array         $field ACF field or subfield
	 * @param string        $lang  language slug
	 */
	protected function translate_fields( &$r, $value, $name, $field, $lang ) {
		if ( empty( $value ) ) {
			return;
		}

		switch ( $field['type'] ) {
			case 'image':
			case 'file':
				if ( PLL()->options['media_support'] ) {
					// Nothing to do if return_format is url
					if ( 'array' === $field['return_format'] && $tr_id = pll_get_post( $value['ID'], $lang ) ) {
						$r[ $name ] = $tr_id;
					} elseif ( 'id' === $field['return_format'] && $tr_id = pll_get_post( $value, $lang ) ) {
						$r[ $name ] = $tr_id;
					}
				}
			break;

			case 'post_object':
				if ( 'object' === $field['return_format'] && $tr_id = pll_get_post( $value->ID, $lang ) ) {
					$r[ $name ] = $tr_id;
				} elseif ( 'id' === $field['return_format'] && $tr_id = pll_get_post( $value, $lang ) ) {
					$r[ $name ] = $tr_id;
				}
			break;

			case 'gallery':
				if ( PLL()->options['media_support'] ) {
					$tr_ids = array();
					foreach ( $value as $img ) {
						if ( $tr_id = pll_get_post( $img['ID'], $lang ) ) {
							$tr_ids[] = (string) $tr_id; // ACF stores strings instead of int
						}
					}
					$r[ $name ] = $tr_ids;
				}
			break;

			case 'relationship':
				$tr_ids = array();
				foreach ( $value as $p ) {
					if ( 'object' === $field['return_format'] && $tr_id = pll_get_post( $p->ID, $lang ) ) {
						$tr_ids[] = (string) $tr_id; // ACF stores strings instead of int
					} elseif ( 'id' === $field['return_format'] && $tr_id = pll_get_post( $p, $lang ) ) {
						$tr_ids[] = (string) $tr_id;
					}
				}
				$r[ $name ] = $tr_ids;
			break;

			case 'page_link':
			// FIXME need to translate the link
			break;

			case 'taxonomy':
				$tr_ids = array();
				foreach ( $value as $t ) {
						if ( 'object' === $field['return_format'] && $tr_id = pll_get_term( $t->term_id, $lang ) ) {
						$tr_ids[] = (string) $tr_id; // ACF stores strings instead of int
					} elseif ( 'id' === $field['return_format'] && $tr_id = pll_get_term( $t, $lang ) ) {
						$tr_ids[] = (string) $tr_id;
					}
				}
				$r[ $name ] = $tr_ids;
			break;

			case 'repeater':
				foreach ( $value as $row => $sub_fields ) {
					foreach ( $field['sub_fields'] as $sub_field ) {
						if ( ! empty( $sub_fields[ $sub_field['name'] ] ) ) {
							$this->translate_fields( $r, $sub_fields[ $sub_field['name'] ], $name . '_' . $row . '_' . $sub_field['name'], $sub_field, $lang );
						}
					}
				}
			break;

			case 'flexible_content':
				foreach ( $value as $row => $sub_fields ) {
					foreach ( $field['layouts'] as $layout ) {
						foreach ( $layout['sub_fields'] as $sub_field ) {
							if ( ! empty( $sub_fields[ $sub_field['name'] ] ) ) {
								$this->translate_fields( $r, $sub_fields[ $sub_field['name'] ], $name . '_' . $row . '_' . $sub_field['name'], $sub_field, $lang );
							}
						}
					}
				}
			break;
		}
	}

	/**
	 * Add the Field Groups post type to the list of translatable post types
	 *
	 * @since 2.0
	 *
	 * @param array $post_types  list of post types
	 * @param bool  $is_settings true when displaying the list of custom post types in Polylang settings
	 * @return array
	 */
	public function get_post_types( $post_types, $is_settings ) {
		if ( $is_settings ) {
			$post_types['acf-field-group'] = 'acf-field-group';
		}
		return $post_types;
	}

	/**
	 * Enqueues javascript to react to a language change in the post metabox
	 *
	 * @since 2.0
	 */
	public function admin_enqueue_scripts() {
		global $pagenow, $typenow;

		if ( in_array( $pagenow, array( 'post.php', 'post-new.php' ) ) && ! in_array( $typenow, array( 'acf-field-group', 'attachment' ) ) && PLL()->model->is_translated_post_type( $typenow ) ) {
			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
			wp_enqueue_script( 'pll_acf', plugins_url( '/js/acf' . $suffix . '.js', POLYLANG_FILE ), array( 'acf-input' ), POLYLANG_VERSION );
		}
	}

	/**
	 * Ajax response for changing the language in the post metabox
	 *
	 * @since 2.0
	 */
	public function acf_post_lang_choice() {
		check_ajax_referer( 'pll_language', '_pll_nonce' );

		$x = new WP_Ajax_Response();
		foreach ( $_POST['fields'] as $field ) {
			ob_start();
			acf_render_field_wrap( acf_get_field( $field ), 'div', 'label' );
			$x->Add( array( 'what' => str_replace( '_', '-', $field ), 'data' => ob_get_contents() ) );
			ob_end_clean();
		}

		$x->send();
	}
}
