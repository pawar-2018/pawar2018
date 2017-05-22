<?php

defined('ABSPATH') or die('No direct access permitted');

// get ssbp settings
$ssbp_settings = get_ssbp_settings();

// add settings link on plugin page
function ssbp_settings_link($links)
{
    // add to plugins links
    array_unshift($links, '<a href="admin.php?page=simple-share-buttons-plus">Get started</a>');

    // return all links
    return $links;
}

// admin js
function ssbp_preview_js()
{
    // preview js
    wp_register_script('ssbpPreview', plugins_url('js/admin/ssbp_preview.js', SSBP_FILE));
    wp_enqueue_script('ssbpPreview');
}

// include js files and upload script
function ssbp_admin_scripts()
{
    wp_enqueue_media();

    // ready available with wp
    wp_enqueue_script('media-upload');
    wp_enqueue_script('jquery-ui');
    wp_enqueue_script('jquery-ui-sortable');

    // ssbp unique
    wp_register_script('ssbp-countup', plugins_url('js/admin/ssbp_countup.js', SSBP_FILE));
    wp_enqueue_script('ssbp-countup');

    // charts for tracking
    wp_register_script('ssbpCharts', plugins_url('js/admin/ssbp_charts.js', SSBP_FILE));
    wp_enqueue_script('ssbpCharts');

    // bootstrap
    wp_register_script('ssbpBootstrap', plugins_url('js/admin/ssbp_bootstrap.js', SSBP_FILE));
    wp_enqueue_script('ssbpBootstrap');

    // filestyle
    wp_register_script('ssbpFilestyle', plugins_url('js/admin/ssbp_filestyle.js', SSBP_FILE));
    wp_enqueue_script('ssbpFilestyle');

    // bootstrap switch
    wp_register_script('ssbpSwitch', plugins_url('js/admin/ssbp_switch.js', SSBP_FILE));
    wp_enqueue_script('ssbpSwitch');

    // bootstrap colorpicker
    wp_register_script('ssbpColorPicker', plugins_url('js/admin/ssbp_colorpicker.js', SSBP_FILE));
    wp_enqueue_script('ssbpColorPicker');

    // if we're viewing the tracking page
    if (isset($_GET['page']) && $_GET['page'] == 'simple-share-buttons-tracking') {
        // admin ajax post
        wp_enqueue_script('ssbp_total_shares_callback', plugins_url('js/admin/ssbp_tracking_ajax.js', SSBP_FILE), array('jquery'), 1.0, true);
        wp_localize_script('ssbp_total_shares_callback', 'ssbpTotal', array());

        wp_enqueue_script('ssbp_top_three_callback', plugins_url('js/admin/ssbp_tracking_ajax.js', SSBP_FILE), array('jquery'), 1.0, true);
        wp_localize_script('ssbp_top_three_callback', 'ssbpTop', array());

        // admin ajax post
        wp_enqueue_script('ssbp_latest_shares_callback', plugins_url('js/admin/ssbp_tracking_ajax.js', SSBP_FILE), array('jquery'), 1.0, true);
        wp_localize_script('ssbp_latest_shares_callback', 'ssbpLatest', array());

        // admin ajax post
        wp_enqueue_script('ssbp_geoip_stats_callback', plugins_url('js/admin/ssbp_tracking_ajax.js', SSBP_FILE), array('jquery'), 1.0, true);
        wp_localize_script('ssbp_geoip_stats_callback', 'ssbpGeoIP', array());
    }

    // if viewing the styling page
    if ($_GET['page'] == 'simple-share-buttons-styling') {
        // include custom css file
        add_action('admin_head', 'ssbp_style_head');

        // include preview js
        add_action('admin_head', 'ssbp_preview_js');
    }

    // finish with ssbp admin
    wp_register_script('ssbp-js', plugins_url('js/admin/ssbp_admin.js', SSBP_FILE));
    wp_enqueue_script('ssbp-js');
}

// include styles for all admin pages
function ssbp_admin_wide_styles()
{
    // add image upload functionality
    wp_register_script('ssbp-upload-js', plugins_url('js/admin/ssbp_upload.js', SSBP_FILE));
    wp_enqueue_script('ssbp-upload-js');

    // css styling
    $htmlSSBPStyle = '.toplevel_page_simple-share-buttons-plus.wp-not-current-submenu > a > div.wp-menu-image.dashicons-before {
							content:""; background: url(' . plugins_url() . '/simple-share-buttons-plus/images/simplesharebuttons_menu.png) no-repeat center;background-size:26px 26px;background-position: center;
						}';
    $htmlSSBPStyle .= '.toplevel_page_simple-share-buttons-plus.wp-has-current-submenu > a > div.wp-menu-image.dashicons-before {
							content:""; background: url(' . plugins_url() . '/simple-share-buttons-plus/images/simplesharebuttons_menu_selected.png) no-repeat center;background-size:26px 26px;background-position: center;
						}';

    // wrap css in style tags
    $htmlSSBPStyle = '<style type="text/css">' . $htmlSSBPStyle . '</style>';

    // return
    echo $htmlSSBPStyle;
}

// include styles for the ssbp admin panel
function ssbp_admin_styles()
{
    // admin styles
    wp_register_style('ssbp-colorpicker', plugins_url('css/colorpicker.css', SSBP_FILE));
    wp_enqueue_style('ssbp-colorpicker');
    wp_register_style('ssbp-bootstrap-style', plugins_url('css/readable.css', SSBP_FILE));
    wp_enqueue_style('ssbp-bootstrap-style');
    wp_register_style('ssbp-admin-theme', plugins_url('sharebuttons/assets/css/ssbp-all.css', SSBP_FILE));
    wp_enqueue_style('ssbp-admin-theme');
    wp_register_style('ssbp-switch-styles', plugins_url('css/ssbp_switch.css', SSBP_FILE));
    wp_enqueue_style('ssbp-switch-styles');
    wp_register_style('ssbp-font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');
    wp_enqueue_style('ssbp-font-awesome');

    // this one last to overwrite any CSS it needs to
    wp_register_style('ssbp-admin-style', plugins_url('css/style.css', SSBP_FILE));
    wp_enqueue_style('ssbp-admin-style');
}

// menu settings
function ssbp_menu()
{
    // get ssbp settings
    $ssbp_settings = get_ssbp_settings();

    // add menu page
    add_menu_page('Simple Share Buttons Plus', 'Share Buttons', 'manage_options', 'simple-share-buttons-plus', 'ssbp_dashboard', 'none');
    add_submenu_page('Simple Share Buttons Plus', 'Share Buttons', 'manage_options', 'simple-share-buttons-plus', 'ssbp_dashboard');
    add_submenu_page('simple-share-buttons-plus', 'Setup', 'Setup', 'manage_options', 'simple-share-buttons-setup', 'ssbp_settings');
    add_submenu_page('simple-share-buttons-plus', 'Styling', 'Styling', 'manage_options', 'simple-share-buttons-styling', 'ssbp_styling');
    add_submenu_page('simple-share-buttons-plus', 'Counters', 'Counters', 'manage_options', 'simple-share-buttons-counters', 'ssbp_counters');
    add_submenu_page('simple-share-buttons-plus', 'Meta Tags', 'Meta Tags', 'manage_options', 'simple-share-buttons-meta', 'ssbp_meta');
    add_submenu_page('simple-share-buttons-plus', 'Post Types', 'Post Types', 'manage_options', 'simple-share-buttons-post_types', 'ssbp_post_types');
    add_submenu_page('simple-share-buttons-plus', 'Advanced', 'Advanced', 'manage_options', 'simple-share-buttons-advanced', 'ssbp_advanced');

    // if tracking is enabled
    if ($ssbp_settings['tracking_enabled'] == 'Y') {
        // if tracking page is set for admins only
        if ($ssbp_settings['admin_only_tracking'] == 'Y') {
            // show tracking page to admins only
            add_submenu_page('simple-share-buttons-plus', 'Tracking', 'Tracking', 'manage_options', 'simple-share-buttons-tracking', 'ssbp_tracking');
        } else {
            // show to all people that can edit posts
            add_submenu_page('simple-share-buttons-plus', 'Tracking', 'Tracking', 'edit_posts', 'simple-share-buttons-tracking', 'ssbp_tracking');
        }
    }

    add_submenu_page('simple-share-buttons-plus', 'ort.sh', 'ort.sh', 'manage_options', 'simple-share-buttons-ortsh', 'ssbp_ortsh');
    add_submenu_page('simple-share-buttons-plus', 'License', 'License', 'manage_options', 'simple-share-buttons-license', 'ssbp_licensing');

    // lower than current version
    if (get_option('ssbp_version') < SSBP_VERSION) {
        // run the upgrade function
        upgrade_ssbp(get_option('ssbp_version'));
    }
}

// add latest stats to dashboard
function ssbp_add_dashboard_widgets()
{
    wp_add_dashboard_widget(
        'ssbp_dashboard_widget',         // Widget slug.
        'Simple Share Buttons Stats',    // Title.
        'ssbp_dashboard_widget_function' // Display function.
    );
}

// if tracking is enabled
if ($ssbp_settings['tracking_enabled'] == 'Y') {
    // add the dashboard widget
    add_action('wp_dashboard_setup', 'ssbp_add_dashboard_widgets');
}

// output the dash stats
function ssbp_dashboard_widget_function()
{
    // echo out the stats
    echo ssbp_dashboard_stats();
}

// export ssbp settings
function export_ssbp_settings()
{
    // get settings
    $jsonSettings = get_option('ssbp_settings');

    // create file name variable
    $ssbpFilename = 'ssbp_settings.txt';

    // output headers so that the file is downloaded rather than displayed
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Content-Description: File Transfer');
    header('Content-type: text/csv');
    header("Content-Disposition: attachment; filename=$ssbpFilename");
    header('Expires: 0');
    header('Pragma: public');

    // open temp file
    $out = fopen('php://output', 'w');

    // add headings to csv
    fwrite($out, $jsonSettings);

    // close and exit, forcing download
    fclose($out);

    // exit and download
    exit;
}

// import official ssbp settings
function import_official_settings()
{
    // get the settings
    $get = wp_remote_get('http://simplesharebuttons.com/ssbp_settings.php', array('timeout' => 8));

    // if error
    if (is_wp_error($get)) {
        die('Unable to retrieve settings');
    }

    // extract body content
    $body = $get['body'];

    // decode settings
    $settings = json_decode($body, true);

    // array of settings to ignore
    $ignore = array(
        'meta_enabled',
        'meta_title',
        'meta_description',
        'meta_image',
        'twitter_username',
        'twitter_tags',
    );

    // remove the ignore keys
    $settings = array_diff_key($settings, array_flip($ignore));

    // update the options with the official ones
    ssbp_update_options($settings);

    // return success
    return true;
}

// import ssbp settings
function import_ssbp_settings()
{
    // if not file has been posted die
    if (!isset($_FILES['ssbp_settings_txt']['tmp_name'])) {
        die('No settings file found');
    }

    // set a variable
    $ssbpSettingsJsonFile = $_FILES['ssbp_settings_txt']['tmp_name'];

    // read json contents and add to variable
    $fp = fopen($ssbpSettingsJsonFile, 'r');
    $jsonSettings = fread($fp, filesize($ssbpSettingsJsonFile));

    // decode and return settings
    $ssbp_settings = json_decode($jsonSettings, true);

    // update the options with the imported ones
    ssbp_update_options($ssbp_settings);

    // return success
    return true;
}

// Show ShareThis terms.
function ssbp_sharethis_terms_notice() {
?>
    <div id="sharethis_terms_notice" class="update-nag notice is-dismissible" style="margin-bottom: 20px;">
        <p>There are some <strong>great new features</strong> available with Simple Share Buttons Plus <?php echo esc_html( SSBP_VERSION ); ?>, such as an improved mobile Facebook sharing experience and Facebook analytics.
        We've updated our <a href="http://simplesharebuttons.com/privacy" target="_blank">privacy policy and terms of use</a> with important changes you should review. To take advantage of the new features, please review and accept the new <a href="http://simplesharebuttons.com/privacy" target="_blank">terms and privacy policy</a>.
        <a href="admin.php?page=simple-share-buttons-plus&accept-terms=Y"><span class="button button-primary">I accept</span></a></p>
    </div>
    <script type="text/javascript">
    jQuery( '#sharethis_terms_notice' ).on( 'click', '.notice-dismiss', function( event ) {
        jQuery.post( ajaxurl, { action: 'ssba_hide_terms' } );
    });
    </script>
    <?php
}

// Hides the terms agreement at user's request.
function ssbp_admin_hide_callback() {
    ssbp_update_options( array( 'hide_sharethis_terms' => true ) );
    wp_die();
}

if (
    'Y' !== $ssbp_settings['accepted_sharethis_terms'] &&
    true !== $ssbp_settings['hide_sharethis_terms']
) {
    add_action( 'admin_notices',           'ssbp_sharethis_terms_notice' );
    add_action( 'wp_ajax_ssba_hide_terms', 'ssbp_admin_hide_callback' );
}
