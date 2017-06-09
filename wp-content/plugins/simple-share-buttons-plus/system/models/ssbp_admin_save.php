<?php
defined('ABSPATH') or die('No direct access permitted');

// main dashboard
function ssbp_dashboard()
{
    // check if user has the rights to manage options
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    // --------- ADMIN DASHBOARD ------------ //
    ssbp_admin_dashboard();
}

// main settings
function ssbp_settings()
{
    // check if user has the rights to manage options
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    // if a post has been made
    if (isset($_POST['ssbpData'])) {
        // get posted data
        $ssbpPost = $_POST['ssbpData'];
        parse_str($ssbpPost, $ssbpPost);

        // if the nonce doesn't check out...
        if (!isset($ssbpPost['ssbp_save_nonce']) || ! wp_verify_nonce($ssbpPost['ssbp_save_nonce'], 'ssbp_save_settings')) {
            die('There was no nonce provided, or the one provided did not verify.');
        }

        // prepare array to save
        $arrOptions = array(
            'pages' => (isset($ssbpPost['pages']) ? $ssbpPost['pages'] : null),
            'posts' => (isset($ssbpPost['posts']) ? $ssbpPost['posts'] : null),
            'cats_archs' => (isset($ssbpPost['cats_archs']) ? $ssbpPost['cats_archs'] : null),
            'homepage' => (isset($ssbpPost['homepage']) ? $ssbpPost['homepage'] : null),
            'excerpts' => (isset($ssbpPost['excerpts']) ? $ssbpPost['excerpts'] : null),
            'search_results' => (isset($ssbpPost['search_results']) ? $ssbpPost['search_results'] : null),
            'before_or_after' => (isset($ssbpPost['before_or_after']) ? $ssbpPost['before_or_after'] : null),
            'share_text' => (isset($ssbpPost['share_text']) ? stripslashes_deep($ssbpPost['share_text']) : null),
            'selected_buttons' => (isset($ssbpPost['selected_buttons']) ? $ssbpPost['selected_buttons'] : null),
        );

        // save the settings
        ssbp_update_options($arrOptions);

        // delete the existing css file
        // it will be recreated the next time a page is loaded
        @unlink(SSBP_CSS);

        return true;
    }

    // include required admin view
    include_once SSBP_ROOT . '/system/views/ssbp_settings.php';

    // --------- ADMIN PANEL ------------ //
    ssbp_admin_panel();
}

// styling settings
function ssbp_styling()
{
    // check if user has the rights to manage options
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    // if a post has been made
    if (isset($_POST['ssbpData'])) {
        // get posted data
        $ssbpPost = $_POST['ssbpData'];
        parse_str($ssbpPost, $ssbpPost);

        // if the nonce doesn't check out...
        if (!isset($ssbpPost['ssbp_save_nonce']) || !wp_verify_nonce($ssbpPost['ssbp_save_nonce'], 'ssbp_save_settings')) {
            die('There was no nonce provided, or the one provided did not verify.');
        }

        // prepare array to save
        $arrOptions = array(
            // set two
            'two_enabled' => (isset($ssbpPost['two_enabled']) ? $ssbpPost['two_enabled'] : null),
            'two_share_counts' => (isset($ssbpPost['two_share_counts']) ? $ssbpPost['two_share_counts'] : null),
            'two_total_share_counts' => (isset($ssbpPost['two_total_share_counts']) ? $ssbpPost['two_total_share_counts'] : null),
            'two_toggle' => (isset($ssbpPost['two_toggle']) ? $ssbpPost['two_toggle'] : null),
            'two_style' => (isset($ssbpPost['two_style']) ? $ssbpPost['two_style'] : null),
            'one_breakpoint' => (isset($ssbpPost['one_breakpoint']) ? $ssbpPost['one_breakpoint'] : null),
            'one_share_counts' => (isset($ssbpPost['one_share_counts']) ? $ssbpPost['one_share_counts'] : null),
            'one_total_share_counts' => (isset($ssbpPost['one_total_share_counts']) ? $ssbpPost['one_total_share_counts'] : null),
            'one_toggle' => (isset($ssbpPost['one_toggle']) ? $ssbpPost['one_toggle'] : null),
            'one_responsive' => (isset($ssbpPost['one_responsive']) ? $ssbpPost['one_responsive'] : null),
            'default_style' => (isset($ssbpPost['default_style']) ? $ssbpPost['default_style'] : null),
            'set_one_position' => (isset($ssbpPost['set_one_position']) ? $ssbpPost['set_one_position'] : null),
            'set_two_position' => (isset($ssbpPost['set_two_position']) ? $ssbpPost['set_two_position'] : null),
            'additional_css' => (isset($ssbpPost['additional_css']) ? stripslashes_deep($ssbpPost['additional_css']) : null),
            'icon_color' => (isset($ssbpPost['icon_color']) ? $ssbpPost['icon_color'] : null),
            'icon_color_two' => (isset($ssbpPost['icon_color_two']) ? $ssbpPost['icon_color_two'] : null),
            'icon_color_hover' => (isset($ssbpPost['icon_color_hover']) ? $ssbpPost['icon_color_hover'] : null),
            'icon_color_hover_two' => (isset($ssbpPost['icon_color_hover_two']) ? $ssbpPost['icon_color_hover_two'] : null),
            'icon_size' => (isset($ssbpPost['icon_size']) ? $ssbpPost['icon_size'] : null),
            'icon_two_size' => (isset($ssbpPost['icon_two_size']) ? $ssbpPost['icon_two_size'] : null),
            'button_height' => (isset($ssbpPost['button_height']) ? $ssbpPost['button_height'] : null),
            'button_width' => (isset($ssbpPost['button_width']) ? $ssbpPost['button_width'] : null),
            'button_two_height' => (isset($ssbpPost['button_two_height']) ? $ssbpPost['button_two_height'] : null),
            'button_two_width' => (isset($ssbpPost['button_two_width']) ? $ssbpPost['button_two_width'] : null),
            'custom_styles_enabled' => (isset($ssbpPost['custom_styles_enabled']) ? $ssbpPost['custom_styles_enabled'] : null),
            'custom_styles' => (isset($ssbpPost['custom_styles']) ? $ssbpPost['custom_styles'] : null),
            'button_margin' => (isset($ssbpPost['button_margin']) ? $ssbpPost['button_margin'] : null),
            'button_two_margin' => (isset($ssbpPost['button_two_margin']) ? $ssbpPost['button_two_margin'] : null),
            'text_placement' => (isset($ssbpPost['text_placement']) ? $ssbpPost['text_placement'] : null),
            'font_family' => $ssbpPost['font_family'],
            'font_color' => $ssbpPost['font_color'],
            'font_size' => $ssbpPost['font_size'],
            'font_weight' => $ssbpPost['font_weight'],
            'color_main' => (isset($ssbpPost['color_main']) ? $ssbpPost['color_main'] : null),
            'color_main_two' => (isset($ssbpPost['color_main_two']) ? $ssbpPost['color_main_two'] : null),
            'color_hover' => (isset($ssbpPost['color_hover']) ? $ssbpPost['color_hover'] : null),
            'color_hover_two' => (isset($ssbpPost['color_hover_two']) ? $ssbpPost['color_hover_two'] : null),
            'custom_images' => (isset($ssbpPost['custom_images']) ? $ssbpPost['custom_images'] : null),
            'image_width' => (isset($ssbpPost['image_width']) ? $ssbpPost['image_width'] : null),
            'image_height' => (isset($ssbpPost['image_height']) ? $ssbpPost['image_height'] : null),
            'image_padding' => (isset($ssbpPost['image_padding']) ? $ssbpPost['image_padding'] : null),
        );

        // displaying share counts does not necessarily mean that share counts are enabled
        // similarly, not displaying share counts doesn't mean that share counts are disabled
        // if no share counts are set to display then disable share counts
        if ($arrOptions['one_share_counts'] != 'Y' && $arrOptions['two_share_counts'] != 'Y' && $arrOptions['one_total_share_counts'] != 'Y' && $arrOptions['two_total_share_counts'] != 'Y') {
            $arrOptions['counters_enabled'] = '';
        } else {// user wants share counts to show, ensure they are enabled
            $arrOptions['counters_enabled'] = 'Y';
        }

        // prepare array of buttons
        $arrButtons = json_decode(get_option('ssbp_buttons'), true);

        // loop through each button
        foreach ($arrButtons as $button => $arrButton) {
            // add custom buttons to the array
            $arrOptions['custom_' . $button] = (isset($ssbpPost['custom_' . $button]) ? $ssbpPost['custom_' . $button] : null);
        }

        // save the settings
        ssbp_update_options($arrOptions);

        // delete the existing css file
        // it will be recreated the next time a page is loaded
        @unlink(SSBP_CSS);

        return true;
    }

    // include required admin view
    include_once SSBP_ROOT . '/system/views/ssbp_styling.php';

    // --------- STYLING PANEL ------------ //
    ssbp_admin_styling();
}

// counter settings
function ssbp_counters()
{
    // if a post has been made
    if (isset($_POST['ssbpData'])) {
        // get posted data
        $ssbpPost = $_POST['ssbpData'];
        parse_str($ssbpPost, $ssbpPost);

        // if the nonce doesn't check out...
        if (!isset($ssbpPost['ssbp_save_nonce']) || !wp_verify_nonce($ssbpPost['ssbp_save_nonce'], 'ssbp_save_settings')) {
            die('There was no nonce provided, or the one provided did not verify.');
        }

        // prepare array to save
        $arrOptions = array(
            'counters_enabled' => (isset($ssbpPost['counters_enabled']) ? $ssbpPost['counters_enabled'] : null),
            'show_full_stats' => (isset($ssbpPost['show_full_stats']) ? $ssbpPost['show_full_stats'] : null),
            'counters_type' => (isset($ssbpPost['counters_type']) ? $ssbpPost['counters_type'] : null),
            'min_shares' => (isset($ssbpPost['min_shares']) ? $ssbpPost['min_shares'] : null),
            'count_timeout' => (isset($ssbpPost['count_timeout']) ? $ssbpPost['count_timeout'] : null),
            'count_cache' => (isset($ssbpPost['count_cache']) ? $ssbpPost['count_cache'] : null),
            'share_api' => (isset($ssbpPost['share_api']) ? $ssbpPost['share_api'] : null),
            'twitter_newsharecounts' => (isset($ssbpPost['twitter_newsharecounts']) ? $ssbpPost['twitter_newsharecounts'] : null),
        );

        // if user has just enabled newsharecounts
        if (isset($ssbpPost['twitter_newsharecounts']) && $ssbpPost['twitter_newsharecounts'] == 'Y') {
            // clear the share counts table
            // required to avoid undefined indexes if people enable twitter_newsharecounts
            truncateSharecounts();
        }

        // save the settings
        ssbp_update_options($arrOptions);
    }

    // include required admin view
    include_once SSBP_ROOT . '/system/views/ssbp_counters.php';

    // --------- COUNTERS PANEL ------------ //
    ssbp_admin_counters();
}

// meta settings
function ssbp_meta()
{
    // if a post has been made
    if (isset($_POST['ssbpData'])) {
        // get posted data
        $ssbpPost = $_POST['ssbpData'];
        parse_str($ssbpPost, $ssbpPost);

        // if the nonce doesn't check out...
        if (!isset($ssbpPost['ssbp_save_nonce']) || !wp_verify_nonce($ssbpPost['ssbp_save_nonce'], 'ssbp_save_settings')) {
            die('There was no nonce provided, or the one provided did not verify.');
        }

        // prepare array to save
        $arrOptions = array(
            'meta_enabled' => (isset($ssbpPost['meta_enabled']) ? $ssbpPost['meta_enabled'] : null),
            'meta_title' => stripslashes_deep($ssbpPost['meta_title']),
            'meta_type' => stripslashes_deep($ssbpPost['meta_type']),
            'meta_description' => stripslashes_deep($ssbpPost['meta_description']),
            'meta_image' => stripslashes_deep($ssbpPost['meta_image']),
            'meta_use_featured_image' => stripslashes_deep($ssbpPost['meta_use_featured_image']),
        );

        // save the settings
        ssbp_update_options($arrOptions);
    }

    // include required admin view
    include_once SSBP_ROOT . '/system/views/ssbp_meta.php';

    // --------- META PANEL ------------ //
    ssbp_admin_meta();
}

// advanced settings
function ssbp_advanced()
{
    // if a post has been made
    if (isset($_POST['ssbpData'])) {
        // get posted data
        $ssbpPost = $_POST['ssbpData'];
        parse_str($ssbpPost, $ssbpPost);

        // if the nonce doesn't check out...
        if (!isset($ssbpPost['ssbp_save_nonce']) || !wp_verify_nonce($ssbpPost['ssbp_save_nonce'], 'ssbp_save_settings')) {
            die('There was no nonce provided, or the one provided did not verify.');
        }

        // prepare array to save
        $arrOptions = array(
            'admin_only_tracking' => (isset($ssbpPost['admin_only_tracking']) ? $ssbpPost['admin_only_tracking'] : null),
            'ga_onclick' => (isset($ssbpPost['ga_onclick']) ? $ssbpPost['ga_onclick'] : null),
            'ga_tracking_id' => (isset($ssbpPost['ga_tracking_id']) ? $ssbpPost['ga_tracking_id'] : null),
            'ga_track_logged_in' => (isset($ssbpPost['ga_track_logged_in']) ? $ssbpPost['ga_track_logged_in'] : null),
            'ssbp_content_priority' => (isset($ssbpPost['ssbp_content_priority']) ? $ssbpPost['ssbp_content_priority'] : null),
            'lazy_load' => (isset($ssbpPost['lazy_load']) ? $ssbpPost['lazy_load'] : null),
            'bitly_login' => stripslashes_deep($ssbpPost['bitly_login']),
            'bitly_api_key' => stripslashes_deep($ssbpPost['bitly_api_key']),
            'rel_nofollow' => (isset($ssbpPost['rel_nofollow']) ? stripslashes_deep($ssbpPost['rel_nofollow']) : null),
            'widget_text' => stripslashes_deep($ssbpPost['widget_text']),
            'email_message' => stripslashes_deep($ssbpPost['email_message']),
            'email_popup' => stripslashes_deep($ssbpPost['email_popup']),
            'email_popup_brute_time' => stripslashes_deep($ssbpPost['email_popup_brute_time']),
            'email_popup_alert_brute' => stripslashes_deep($ssbpPost['email_popup_alert_brute']),
            'email_popup_alert_success' => stripslashes_deep($ssbpPost['email_popup_alert_success']),
            'email_popup_alert_warning' => stripslashes_deep($ssbpPost['email_popup_alert_warning']),
            'email_popup_subject' => stripslashes_deep($ssbpPost['email_popup_subject']),
            'email_popup_from_name' => stripslashes_deep($ssbpPost['email_popup_from_name']),
            'email_popup_from_email' => stripslashes_deep($ssbpPost['email_popup_from_email']),
            'email_popup_email_label' => stripslashes_deep($ssbpPost['email_popup_email_label']),
            'email_popup_email_placeholder' => stripslashes_deep($ssbpPost['email_popup_email_placeholder']),
            'email_popup_message_label' => stripslashes_deep($ssbpPost['email_popup_message_label']),
            'email_popup_button_text' => stripslashes_deep($ssbpPost['email_popup_button_text']),
            'facebook_app_id' => stripslashes_deep($ssbpPost['facebook_app_id']),
            'facebook_insights' => stripslashes_deep($ssbpPost['facebook_insights']),
            'onscroll_enabled' => stripslashes_deep($ssbpPost['onscroll_enabled']),
            'onscroll_top' => stripslashes_deep($ssbpPost['onscroll_top']),
            'onscroll_bottom' => stripslashes_deep($ssbpPost['onscroll_bottom']),
            'twitter_username' => stripslashes_deep(str_replace('@', '', $ssbpPost['twitter_username'])),
            'twitter_text' => stripslashes_deep($ssbpPost['twitter_text']),
            'twitter_tags' => stripslashes_deep($ssbpPost['twitter_tags']),
            'buffer_text' => stripslashes_deep($ssbpPost['buffer_text']),
            'flattr_user_id' => stripslashes_deep($ssbpPost['flattr_user_id']),
            'flattr_url' => stripslashes_deep($ssbpPost['flatt§r_url']),
            'pinterest_use_featured' => (isset($ssbpPost['pinterest_use_featured']) ? stripslashes_deep($ssbpPost['pinterest_use_featured']) : null),
            'pinterest_use_ssbp_meta' => (isset($ssbpPost['pinterest_use_ssbp_meta']) ? stripslashes_deep($ssbpPost['pinterest_use_ssbp_meta']) : null),
            'ssb_attribution' => (isset($ssbpPost['ssb_attribution']) ? stripslashes_deep($ssbpPost['ssb_attribution']) : null),
            'use_ssba' => (isset($ssbpPost['use_ssba']) ? $ssbpPost['use_ssba'] : null),
            'use_shortlinks' => (isset($ssbpPost['use_shortlinks']) ? $ssbpPost['use_shortlinks'] : null),
            'load_font_in_head' => (isset($ssbpPost['load_font_in_head']) ? $ssbpPost['load_font_in_head'] : null),
            'tracking_enabled' => (isset($ssbpPost['tracking_enabled']) ? $ssbpPost['tracking_enabled'] : null),
            'whatsapp_shortlinks' => (isset($ssbpPost['whatsapp_shortlinks']) ? $ssbpPost['whatsapp_shortlinks'] : null),
            'use_native_links' => (isset($ssbpPost['use_native_links']) ? $ssbpPost['use_native_links'] : null),

        );

        // save the settings
        ssbp_update_options($arrOptions);
    }

    // include required admin view
    include_once SSBP_ROOT . '/system/views/ssbp_advanced.php';

    // --------- ADVANCED PANEL ------------ //
    ssbp_admin_advanced();
}

// custom post type settings
function ssbp_post_types()
{
    // if a post has been made
    if (isset($_POST['ssbpData'])) {
        // get posted data
        $ssbpPost = $_POST['ssbpData'];
        parse_str($ssbpPost, $ssbpPost);

        // if the nonce doesn't check out...
        if (!isset($ssbpPost['ssbp_save_nonce']) || !wp_verify_nonce($ssbpPost['ssbp_save_nonce'], 'ssbp_save_settings')) {
            die('There was no nonce provided, or the one provided did not verify.');
        }

        // prepare array to save
        $arrOptions = array(
            'disabled_types' => (isset($ssbpPost['ssbp_disabled_types']) ? implode(',', $ssbpPost['ssbp_disabled_types']) : null),
        );

        // save the settings
        ssbp_update_options($arrOptions);
    }

    // include required admin view
    include_once SSBP_ROOT . '/system/views/ssbp_post_types.php';

    // --------- CUSTOM POST TYPES PANEL ------------ //
    ssbp_admin_post_types();
}

// ortsh dashboard
function ssbp_ortsh()
{
    // if a post has been made
    if (isset($_POST['ssbpData'])) {
        // get posted data
        $ssbpPost = $_POST['ssbpData'];
        parse_str($ssbpPost, $ssbpPost);

        // if the nonce doesn't check out...
        if (!isset($ssbpPost['ssbp_save_nonce']) || !wp_verify_nonce($ssbpPost['ssbp_save_nonce'], 'ssbp_save_settings')) {
            die('There was no nonce provided, or the one provided did not verify.');
        }

        // prepare array to save
        $arrOptions = array(
            'ortsh_enabled' => (isset($ssbpPost['ortsh_enabled']) ? stripslashes_deep($ssbpPost['ortsh_enabled']) : null),
        );

        // save the settings
        ssbp_update_options($arrOptions);
    }

    // include required admin view
    include_once SSBP_ROOT . '/system/views/ssbp_ortsh.php';

    // --------- ORTSH DASHBOARD ------------ //
    ssbp_ortsh_dashboard();
}
