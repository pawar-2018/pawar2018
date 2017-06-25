<?php
/*
Plugin Name: Simple Share Buttons Plus
Plugin URI: https://simplesharebuttons.com/plus/
Description: One of the most advanced WordPress share button plugins available.
Version: 1.2.4
Author: Simple Share Buttons
Author URI: https://simplesharebuttons.com
License: GPLv2

Copyright 2014 - 2016 Simple Share Buttons admin@simplesharebuttons.com

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
      _                    _           _   _
  ___| |__   __ _ _ __ ___| |__  _   _| |_| |_ ___  _ __  ___
 / __| '_ \ / _` | '__/ _ \ '_ \| | | | __| __/ _ \| '_ \/ __|
 \__ \ | | | (_| | | |  __/ |_) | |_| | |_| || (_) | | | \__ \
 |___/_| |_|\__,_|_|  \___|_.__/ \__,_|\__|\__\___/|_| |_|___/

*/

//======================================================================
// 		CONSTANTS
//======================================================================

// set constants
define('SSBP_FILE', __FILE__);
define('SSBP_ROOT', dirname(__FILE__));
define('SSBP_VERSION', '1.2.4');
define('SSBP_CSS', plugin_dir_path(__FILE__) . 'ssbp.min.css');

//======================================================================
// 		 SSBP SETTINGS
//======================================================================

// get ssbp settings
$ssbp_settings = get_ssbp_settings();

//======================================================================
// 		INCLUDES
//======================================================================

// must be available for ajax calls
include_once plugin_dir_path(__FILE__) . '/system/views/ssbp_admin_panel.php';

// if tracking is enabled
if ($ssbp_settings['tracking_enabled'] == 'Y') {
    include_once plugin_dir_path(__FILE__) . '/system/models/ssbp_tracking.php';
}

// frontend side functions
include_once plugin_dir_path(__FILE__) . '/system/controllers/ssbp_buttons.php';
include_once plugin_dir_path(__FILE__) . '/system/controllers/ssbp_styles.php';
include_once plugin_dir_path(__FILE__) . '/system/controllers/ssbp_widget.php';

// the main share buttons class that is called via AJAX
include_once plugin_dir_path(__FILE__) . '/system/controllers/ssbp_lazy.php';

// if meta is enabled
if ($ssbp_settings['meta_enabled'] == 'Y') {
    // the the meta file
    include_once plugin_dir_path(__FILE__) . '/system/controllers/ssbp_meta.php';

    // add meta details to the head
    add_action('wp_head', 'get_ssbp_meta', 1);
}

//======================================================================
// 		ADMIN ONLY
//======================================================================

// update first if needed
include_once plugin_dir_path(__FILE__) . '/system/models/ssbp_database.php';

// register/deactivate/uninstall
register_activation_hook(__FILE__, 'ssbp_activate');
//register_deactivation_hook( __FILE__, 'ssbp_deactivate' );
register_uninstall_hook(__FILE__, 'ssbp_uninstall');

// ssbp admin area hook
add_action('plugins_loaded', 'ssbp_admin_area');

// ssbp admin area
function ssbp_admin_area()
{
    // if in admin area
    if (is_admin()) {
        // editor role effectively
        if (current_user_can('edit_posts')) {
            include_once plugin_dir_path(__FILE__) . '/system/controllers/ssbp_admin_bits.php';

            // add the admin-wide styles
            add_action('admin_head', 'ssbp_admin_wide_styles');

            // if viewing an ssbp admin page
            if (is_ssbp_admin_page() === true) {
                // add the admin styles
                add_action('admin_print_styles', 'ssbp_admin_styles');

                // also include js
                add_action('admin_print_scripts', 'ssbp_admin_scripts');
            }

            // add menu to dashboard
            add_action('admin_menu', 'ssbp_menu');

            // if a post is set
            if (isset($_POST)) {
                // if the export csv function has been run
                if (isset($_POST['ssvp_export'])) {
                    // export csv
                    ssbp_export_csv();
                }

                // if the export csv function has been run for post shares
                if (isset($_POST['ssbp_post_data_export'])) {
                    // export csv
                    ssbp_post_data_export();
                }
            }
        }

        // check user has the right kind of access
        if (current_user_can('manage_options')) {
            // lower than current version
            if (get_option('ssbp_version') < SSBP_VERSION) {
                // run upgrade script
                upgrade_ssbp(get_option('ssbp_version'));
            }

            // admin side functions
            include_once plugin_dir_path(__FILE__) . '/system/controllers/ssbp_licensing.php';

            // if a post is set
            if (isset($_POST)) {
                // if the export settings function has been called
                if (isset($_POST['export_ssbp_settings']) && wp_verify_nonce($_POST['_wpnonce'], 'export_ssbp_settings_nonce')) {
                    // export ssbp settings
                    export_ssbp_settings();
                }

                // if the import settings function has been called
                if (isset($_POST['import_ssbp_settings']) && wp_verify_nonce($_POST['_wpnonce'], 'import_ssbp_settings_nonce')) {
                    // import ssbp settings
                    if (import_ssbp_settings()) {
                        // start a session if needed
                        @session_start();

                        // save success bool
                        $_SESSION['ssbp_import'] = true;
                    }
                }

                // if the import official settings function has been called
                if (isset($_POST['import_official_settings']) && wp_verify_nonce($_POST['_wpnonce'], 'import_official_settings_nonce')) {
                    // delete the existing css file
                    // it will be recreated the next time a page is loaded
                    @unlink(SSBP_CSS);

                    // import official ssbp settings
                    if (import_official_settings()) {
                        // start a session if needed
                        @session_start();

                        // save success bool
                        $_SESSION['ssbp_official_import'] = true;
                    }
                }
            }

            // if viewing an ssbp admin page
            if (is_ssbp_admin_page() === true) {
                // admin and ssbp admin pages only includes
                include_once plugin_dir_path(__FILE__) . '/system/models/ssbp_admin_save.php';
                include_once plugin_dir_path(__FILE__) . '/system/helpers/ssbp_forms.php';
            }

            // Save acceptance.
            if ( isset( $_GET['accept-terms'] ) && 'Y' === $_GET['accept-terms'] ) {
                ssbp_update_options( array( 'accepted_sharethis_terms' => 'Y' ) );
            }

        }
    }
}

//======================================================================
// 		ADMIN HOOKS
//======================================================================

// add filter hook for plugin action links
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'ssbp_settings_link');

// if tracking is enabled
if ($ssbp_settings['tracking_enabled'] == 'Y') {
    // register the ajax action tracking
    add_action('wp_ajax_ssbp_total_shares', 'ssbp_total_shares_callback');
    add_action('wp_ajax_ssbp_top_three', 'ssbp_top_three_callback');
    add_action('wp_ajax_ssbp_latest_shares', 'ssbp_latest_shares_callback');
    add_action('wp_ajax_ssbp_past_week', 'ssbp_past_week_callback');
    add_action('wp_ajax_ssbp_geoip_stats', 'ssbp_geoip_stats_callback');
}

//======================================================================
// 		SHORTCODES
//======================================================================

// if option is set to use free version shortcode
if ($ssbp_settings['use_ssba'] == 'Y') {
    // use old shortcode for new functions
    add_shortcode('ssba', 'ssbp_buttons');
    add_shortcode('ssba_hide', 'ssbp_hide');
} // using new shortcode
else {
    // register shortcode [ssbp] to show [ssbp_hide]
    add_shortcode('ssbp', 'ssbp_buttons');
    add_shortcode('ssbp_hide', 'ssbp_hide');
}

//======================================================================
// 		FRONTEND HOOKS
//======================================================================

/**
* Adds a filter around the content.
*/
function ssbp_add_button_filter() {
    $ssbp_settings = get_ssbp_settings();
    add_filter(
        'the_content',
        'ssbp_show_share_buttons',
        (int) $ssbp_settings['ssbp_content_priority']
    );
}
add_action( 'wp_head', 'ssbp_add_button_filter', 99 );

// add ssbp to available widgets
add_action('widgets_init', create_function('', 'register_widget( "ssbp_widget" );'));

// add the hook to include css
add_action('wp_enqueue_scripts', 'ssbp_stylesheets');

// call scripts add function
add_action('wp_enqueue_scripts', 'ssbp_page_scripts');

// if lazy load is enabled
if ($ssbp_settings['lazy_load'] == 'Y') {
    // register the ajax action for lazy load
    add_action('wp_ajax_ssbp_lazy', 'ssbp_lazy_callback');
    add_action('wp_ajax_nopriv_ssbp_lazy', 'ssbp_lazy_callback');

    // if tracking is enabled
    if ($ssbp_settings['tracking_enabled'] == 'Y') {
        // register the ajax action for tracking
        add_action('wp_ajax_nopriv_ssbp_tracking', 'ssbp_tracking_callback');
        add_action('wp_ajax_ssbp_tracking', 'ssbp_tracking_callback');
    }
} //not lazy loading
else {
    // if tracking is enabled
    if ($ssbp_settings['tracking_enabled'] == 'Y') {
        // register the ajax action for tracking
        add_action('wp_ajax_nopriv_ssbp_standard', 'ssbp_standard_callback');
        add_action('wp_ajax_ssbp_standard', 'ssbp_standard_callback');
    }
}

// if onscroll is enabled
if ($ssbp_settings['onscroll_enabled'] == 'Y') {

    function ssbp_scroll_footer() {
        $ssbp_settings = get_ssbp_settings();

        echo '<script>';
        // on scroll
        echo 'jQuery(window).scroll(function() {';
            // calculate distance from the top
            echo 'var fromTop = jQuery(window).scrollTop();';
            // get full doc height
            echo 'var docHeight = jQuery(document).height();';
            // get window height
            echo 'var windowHeight = jQuery(window).height();';
            // calculate required distance from the top
            echo 'var fromBottom = docHeight - windowHeight - '.$ssbp_settings['onscroll_bottom'].';';
            // if far enough from the top and not close enough to the bottom
            echo 'if(fromTop >= '.$ssbp_settings['onscroll_top'].' && fromTop < fromBottom) {';
                // show share buttons
                echo 'jQuery(".ssbp-set--one").removeClass("ssbp--state-hidden");';
                // show toggle (if there)
                echo 'jQuery(".ssbp-set--one .ssbp-toggle-switch").fadeIn(200);';
            // endif far enough from the top
            echo '}';
            // if less than far enough from the top or too close to the bottom
            echo 'if(fromTop < '.$ssbp_settings['onscroll_top'].' || fromTop >= fromBottom) {';
                // hide the share buttons
                echo 'jQuery(".ssbp-set--one").addClass("ssbp--state-hidden");';
                // hide toggle (if there)
                echo 'jQuery(".ssbp-set--one .ssbp-toggle-switch").fadeOut(200);';
            // endif less than far enough from the top
            echo '}';
        // close onscroll
        echo '});';
        echo '</script>';
    }

    // add required js to footer
    add_action( 'wp_footer', 'ssbp_scroll_footer' );
}

// if GA tracking is enabled
if ($ssbp_settings['ga_onclick'] == 'Y') {
    // add GA event tracking function
    add_action('wp_footer', 'ssbp_ga_onclick');
}

// if GA tracking id is not empty
if ($ssbp_settings['ga_tracking_id'] != '') {

    function ssbp_add_ga() {
        // only add for logged in users if enabled, always add for guests
        if ((is_user_logged_in() && $ssbp_settings['ga_track_logged_in'] == 'Y') || !is_user_logged_in()) {
            // add GA tracking function
            echo "<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
ga('create', '" . htmlspecialchars( $ssbp_settings['ga_tracking_id'] ) . "', 'auto');
ga('send', 'pageview');
</script>";
        }
    }

    add_action( 'wp_footer', 'ssbp_add_ga' );
}

// facebook app ID is set
if ($ssbp_settings['facebook_app_id'] != '') {
    // facebook
    function ssbp_wp_head() {
        // if facebook insights have been enabled
        if ($ssbp_settings['facebook_insights'] == 'Y') {
            // add facebook meta tag
            echo '<meta property="fb:app_id" content="'.$ssbp_settings['facebook_app_id'].'" />';
        }

        // include facebook js sdk
        echo "<script>
			  window.fbAsyncInit = function() {
				FB.init({
				  appId      : '".$ssbp_settings['facebook_app_id']."',
				  xfbml      : true,
				  version    : 'v2.6'
				});
			  };

			  (function(d, s, id){
				 var js, fjs = d.getElementsByTagName(s)[0];
				 if (d.getElementById(id)) {return;}
				 js = d.createElement(s); js.id = id;
				 js.src = \"//connect.facebook.net/en_US/sdk.js\";
				 fjs.parentNode.insertBefore(js, fjs);
			   }(document, 'script', 'facebook-jssdk'));
			</script>";
    }
    add_action( 'wp_head', 'ssbp_wp_head' );
}

// if ellipsis has been set
if (strpos($ssbp_settings['selected_buttons'], 'ellipsis') !== false) {
    // add ellipsis div to footer
    function ssbp_ellipsis_footer() {
        $ssbp_settings = get_ssbp_settings();

        // open ellipsis div
        echo '<div id="ssbp-ellipsis-div"><span class="ssbp-x ssbp-close-ellipsis-div"></span>';

        // if attribution is on
        if ($ssbp_settings['ssb_attribution'] == 'Y') {
            // add powered by link
            echo '<a href="https://simplesharebuttons.com/plus/?utm_source=plus&amp;utm_medium=plugin_powered_by&utm_campaign=powered_by&amp;utm_content=plus_ellipsis" target="_blank"><img class="ssbp-ellipsis-powered-by" src="' . plugins_url() . '/simple-share-buttons-plus/images/simple-share-buttons-logo-white.png" alt="Simple Share Buttons" /></a>';
        }

        // initiate ssbp button class
        $ssbpButtons = new ssbpShareButtons();

        // the buttons!
        echo $ssbpButtons->get_ssbp_buttons(ssbp_current_url(), get_the_title(), '', null, true, false);

        // close ellipsis div
        echo '</div>';

        // add required JS
        echo '<script>
                jQuery("body").on("click", ".ssbp-ellipsis", function() {
                    jQuery("#ssbp-ellipsis-div").fadeIn(500);
                });
                jQuery("body").on("click", ".ssbp-close-ellipsis-div", function() {
                    jQuery("#ssbp-ellipsis-div").fadeOut(500);
                });
              </script>';
    }
    add_action( 'wp_footer', 'ssbp_ellipsis_footer' );
}

// if email popup option is set
if ($ssbp_settings['email_popup'] == 'Y') {
    // include all required ssbp email functionality
    include_once SSBP_ROOT.'/system/controllers/email.php';
}

// if we need to load the font in the head
if ($ssbp_settings['load_font_in_head'] == 'Y') {
    // add ssbp font in head
    add_action('wp_head', 'ssbp_font_in_head');
}

// if we wish to add to excerpts
if (isset($ssbp_settings['excerpts']) && $ssbp_settings['excerpts'] == 'Y') {
    // add a hook
    add_filter('the_excerpt', 'ssbp_show_share_buttons');
}

//======================================================================
// 		GET SSBP SETTINGS
//======================================================================

// return ssbp settings
function get_ssbp_settings()
{
    // get json array settings from DB
    $jsonSettings = get_option('ssbp_settings');

    // decode and return settings
    return json_decode($jsonSettings, true);
}

//======================================================================
// 		VIEWING AN SSBP ADMIN PAGE?
//======================================================================

// check we're on an SSBP admin page
function is_ssbp_admin_page()
{
    // are we viewing a page?
    if (isset($_GET['page'])) {
        // an array of ssbp admin pages
        $arrSSBPAdmin = array(
            'simple-share-buttons-plus',
            'simple-share-buttons-setup',
            'simple-share-buttons-styling',
            'simple-share-buttons-counters',
            'simple-share-buttons-meta',
            'simple-share-buttons-post_types',
            'simple-share-buttons-advanced',
            'simple-share-buttons-tracking',
            'simple-share-buttons-ortsh',
            'simple-share-buttons-license',
        );

        // are we viewing an ssbp admin page?
        if (in_array($_GET['page'], $arrSSBPAdmin)) {
            // return true
            return true;
        } // not an ssbp admin page
        else {
            // return false
            return false;
        }
    } // not viewing an admin page
    else {
        // return false
        return false;
    }
}

//======================================================================
// 		UPDATE SSBP SETTINGS
//======================================================================

// update an array of options
function ssbp_update_options($arrOptions)
{
    // if not given an array
    if (!is_array($arrOptions)) {
        die('Value parsed not an array');
    }

    // get ssbp settings
    $jsonSettings = get_option('ssbp_settings');

    // decode the settings
    $ssbp_settings = json_decode($jsonSettings, true);

    // loop through array given
    foreach ($arrOptions as $name => $value) {
        // update/add the option in the array
        $ssbp_settings[$name] = $value;
    }

    // encode the options ready to save back
    $jsonSettings = json_encode($ssbp_settings);

    // update the option in the db
    update_option('ssbp_settings', $jsonSettings);
}
