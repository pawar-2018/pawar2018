<?php

defined('ABSPATH') or die('No direct access permitted');

// activate ssbp function
function ssbp_activate()
{
    // likely a reactivation, return doing nothing
    if (get_option('ssbp_version') !== false) {
        return;
    }

    // get site name and admin email
    $site_name = get_bloginfo('name');
    $admin_email = get_bloginfo('admin_email');

    // array ready with defaults
    $ssbp_settings = array(
        'accepted_sharethis_terms' => 'Y',
        'additional_css' => '',
        'admin_only_tracking' => '',
        'before_or_after' => 'after',
        'bitly_api_key' => '',
        'bitly_login' => '',
        'buffer_text' => '',
        'button_margin' => '',
        'button_two_margin' => '',
        'button_percent' => '',
        'button_height' => '',
        'button_width' => '',
        'button_two_height' => '',
        'button_two_width' => '',
        'cats_archs' => '',
        'color_main' => '',
        'color_main_two' => '',
        'color_hover' => '',
        'color_hover_two' => '',
        'count_cache' => '600',
        'count_timeout' => '4',
        'counters_enabled' => '',
        'counters_type' => 'total',
        'custom_buffer' => '',
        'custom_diggit' => '',
        'custom_email' => '',
        'custom_google' => '',
        'custom_facebook' => '',
        'custom_flattr' => '',
        'custom_images' => '',
        'custom_linkedin' => '',
        'custom_pinterest' => '',
        'custom_print' => '',
        'custom_reddit' => '',
        'custom_stumbleupon' => '',
        'custom_styles' => '',
        'custom_styles_enabled' => '',
        'custom_tumblr' => '',
        'custom_twitter' => '',
        'custom_vk' => '',
        'custom_yummly' => '',
        'default_style' => '2',
        'disabled_types' => '',
        'email_message' => '',
        'email_popup' => 'Y',
        'email_popup_brute_time' => '5',
        'email_popup_alert_brute' => 'The email to friend functionality is restricted to one email every five minutes, please try again soon',
        'email_popup_alert_success' => 'Thanks, your email has been sent',
        'email_popup_alert_warning' => 'Please check the fields and try again',
        'email_popup_subject' => "Your friend thinks you'd be interested in this",
        'email_popup_from_name' => $site_name,
        'email_popup_from_email' => $admin_email,
        'email_popup_email_label' => "Friend's email",
        'email_popup_email_placeholder' => 'friends@email.com',
        'email_popup_message_label' => 'Message',
        'email_popup_button_text' => 'Send',
        'excerpts' => '',
        'facebook_app_id' => '',
        'facebook_insights' => '',
        'flattr_user_id' => '',
        'flattr_url' => '',
        'font_color' => '',
        'font_family' => '',
        'font_size' => '20',
        'font_weight' => 'normal',
        'ga_onclick' => '',
        'ga_tracking_id' => '',
        'ga_track_logged_in' => '',
        'homepage' => '',
        'icon_color' => '',
        'icon_color_two' => '',
        'icon_color_hover' => '',
        'icon_color_hover_two' => '',
        'icon_size' => '',
        'icon_two_size' => '',
        'image_height' => '32',
        'image_padding' => '5',
        'image_width' => '32',
        'lazy_load' => '',
        'load_font_in_head' => '',
        'meta_enabled' => '',
        'meta_description' => '',
        'meta_image' => '',
        'meta_title' => '',
        'meta_type' => 'website',
        'meta_use_featured_image' => 'meta_use_featured_image',
        'min_shares' => '0',
        'onscroll_enabled' => '',
        'onscroll_top' => '',
        'onscroll_bottom' => '',
        'ortsh_enabled' => '',
        'one_breakpoint' => '480',
        'one_responsive' => 'Y',
        'one_share_counts' => '',
        'one_toggle' => 'Y',
        'one_total_share_counts' => '',
        'pages' => '',
        'pinterest_use_featured' => '',
        'pinterest_use_ssbp_meta' => '',
        'posts' => '',
        'rel_nofollow' => '',
        'search_results' => '',
        'selected_buttons' => 'facebook,google,linkedin,twitter,ellipsis',
        'set_one_position' => 'fixed-left',
        'set_two_position' => '',
        'share_api' => '',
        'share_text' => '',
        'show_full_stats' => '',
        'ssb_attribution' => 'Y',
        'ssbp_content_priority' => '10',
        'text_placement' => 'above',
        'tracking_enabled' => '',
        'twitter_tags' => '',
        'twitter_newsharecounts' => '',
        'twitter_text' => '',
        'twitter_username' => '',
        'two_enabled' => '',
        'two_share_counts' => '',
        'two_style' => '',
        'two_toggle' => '',
        'two_total_share_counts' => '',
        'use_native_links' => '',
        'use_ssba' => '',
        'use_shortlinks' => '',
        'whatsapp_shortlinks' => '',
        'widget_text' => '',
    );

    // json encode
    $jsonSettings = json_encode($ssbp_settings);

    // insert default options for ssbp
    add_option('ssbp_settings', $jsonSettings);

    // save settings to json file
    ssbp_update_options($ssbp_settings);

    // add email log table
    ssbp_add_email_log_table();

    // add ssbp tracking table
    ssbp_add_tracking_table();

    // add ortsh table
    ssbp_add_ortsh_table();

    // add ssbp counters table
    ssbp_add_share_counts_table();

    // button helper array
    ssbp_button_helper_array();

    // add ssbp version as a separate option
    add_option('ssbp_version', SSBP_VERSION);
}

// add email log table
function ssbp_add_email_log_table()
{
    // wpdb global
    global $wpdb;

    // use prefix to signup table name
    $table_name = $wpdb->prefix.'ssbp_email_log';

    // prepare sql
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
			  id mediumint(9) NOT NULL AUTO_INCREMENT,
			  url VARCHAR(250) NOT NULL,
			  email VARCHAR(250) NOT NULL,
			  ip VARCHAR(45) NOT NULL,
			  datetime DATETIME NOT NULL,
			  UNIQUE KEY id (id)
			) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;";

    // include wp upgrade functionality and add table
    require_once ABSPATH.'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}

// add ssbp tracking table
function ssbp_add_tracking_table()
{
    // wpdb global
    global $wpdb;

    // use prefix to ssbp table name
    $table_name = $wpdb->prefix . 'ssbp_tracking';

    // prepare sql
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
				  id mediumint(9) NOT NULL AUTO_INCREMENT,
				  title text NOT NULL,
				  url VARCHAR(250) NOT NULL,
				  site VARCHAR(90) NOT NULL,
				  ip VARCHAR(15) DEFAULT NULL,
				  geoip VARCHAR(85) DEFAULT NULL,
				  datetime DATETIME NOT NULL,
				  UNIQUE KEY id (id)
				) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;";

    // include wp upgrade functionality and add table
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}

// add ortsh table
function ssbp_add_ortsh_table()
{
    // wpdb global
    global $wpdb;

    // use prefix to ssbp table name
    $table_name = $wpdb->prefix . 'ssbp_ortsh_urls';

    // prepare sql
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
				  id int(11) unsigned NOT NULL AUTO_INCREMENT,
				  hash varchar(32) COLLATE utf8_unicode_ci NOT NULL,
				  ortsh_key varchar(15) COLLATE utf8_unicode_ci NOT NULL,
				  title varchar(125) COLLATE utf8_unicode_ci NOT NULL,
				  url varchar(250) COLLATE utf8_unicode_ci NOT NULL,
				  PRIMARY KEY (id),
				  UNIQUE KEY hash (hash, url)
				) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;";

    // include wp upgrade functionality and add table
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}

// add ssbp counters table
function ssbp_add_share_counts_table()
{
    // wpdb global
    global $wpdb;

    // use prefix to ssbp table name
    $table_name = $wpdb->prefix . 'ssbp_share_counts';

    // prepare sql
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
				  id int(11) unsigned NOT NULL AUTO_INCREMENT,
				  hash varchar(32) COLLATE utf8_unicode_ci NOT NULL,
				  data text COLLATE utf8_unicode_ci NOT NULL,
				  expires datetime NOT NULL,
				  PRIMARY KEY (id),
				  KEY hash (hash)
				) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;";

    // include wp upgrade functionality and add table
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}

// uninstall ssbp function
function ssbp_uninstall()
{
    //if uninstall not called from WordPress exit
    if (defined('WP_UNINSTALL_PLUGIN')) {
        exit();
    }

    // delete ssbp options
    delete_option('ssbp_version');
    delete_option('ssbp_settings');
    delete_option('ssbp_buttons');

    // global db
    global $wpdb;

    // drop ortsh
    $table_name = $wpdb->prefix . 'ssbp_ortsh_urls';
    $wpdb->query("DROP TABLE IF EXISTS $table_name");

    // drop tracking
    $table_name = $wpdb->prefix . 'ssbp_tracking';
    $wpdb->query("DROP TABLE IF EXISTS $table_name");

    // drop counts
    $table_name = $wpdb->prefix . 'ssbp_share_counts';
    $wpdb->query("DROP TABLE IF EXISTS $table_name");
}

// the upgrade function
function upgrade_ssbp($ssbpVersion)
{
    // initial installation, do not proceed with upgrade script
    if ($ssbpVersion === false) {
        return;
    }

    // lower than 0.5.2
    // will need to get old settings to use to upgrade
    if ($ssbpVersion < '0.5.2') {
        // get existing options - old way
        $arrOldSettings = get_old_ssbp_settings();

        // if version is before 0.1.0
        if ($ssbpVersion < '0.1.0') {

            // counters added
            // added in 0.1.0
            add_option('ssbp_counters_enabled', '');
            add_option('ssbp_counters_type', 'total');
        }

        // added in 0.1.4
        if ($ssbpVersion < '0.1.4') {

            // old school styling options
            add_option('ssbp_text_placement', 'above');
            add_option('ssbp_button_margin', '');
            add_option('ssbp_font_weight', 'normal');
        }

        // lower than 0.1.6
        if ($ssbpVersion < '0.1.6') {

            // added in 0.1.6
            add_option('ssbp_lazy_load', '');
        }

        // lower than 0.1.7
        if ($ssbpVersion < '0.1.7') {

            // meta added in 0.1.7
            add_option('ssbp_meta_enabled', '');
            add_option('ssbp_meta_title', '');
            add_option('ssbp_meta_descripton', '');
            add_option('ssbp_meta_image', '');
        }

        // lower than 0.1.7
        if ($ssbpVersion < '0.1.7') {

            // added in 0.1.8
            add_option('ssbp_icon_color', '');
            add_option('ssbp_default_style', '');
            add_option('ssbp_additional_css', '');
            add_option('ssbp_rel_nofollow', '');
        }

        // lower than 0.2.0
        if ($ssbpVersion < '0.2.0') {
            // added in 0.2.0
            add_option('ssbp_use_ssba', '');
            add_option('ssbp_bitly_login', '');
            add_option('ssbp_bitly_api_key', '');
            add_option('ssbp_disabled_types', '');

            // add new geoip field
            ssbp_add_geoip();
        }

        // lower than 0.2.1
        if ($ssbpVersion < '0.2.1') {
            // added in 0.2.1
            add_option('ssbp_min_shares', '0');
            add_option('ssbp_count_timeout', '4');
        }

        // lower than 0.3.0
        if ($ssbpVersion < '0.3.0') {
            // new in 0.3.0
            add_option('ssbp_count_cache', '600');
        }

        // lower than 0.4.1
        if ($ssbpVersion < '0.4.1') {
            // new in 0.4.0
            add_option('ssbp_ortsh_enabled', '');
            add_option('ssbp_share_api', '');

            // ensure encoding of tracking table is unicode
            ssbp_tracking_unicode();
        }

        // lower than 0.4.2
        if ($ssbpVersion < '0.4.2') {
            // new in 0.4.1
            // ensure encoding of tracking table is unicode
            ssbp_tracking_unicode();

            // new in 0.4.2
            // delete all ortsh to fix those that don't contain full json encoded info
            delete_old_ortsh_data();
            // add twitter username/via
            add_option('ssbp_twitter_username', '');
        }

        // lower than 0.5.0
        if ($ssbpVersion < '0.5.0') {
            // new in 0.5.0
            add_option('ssbp_show_full_stats', 'Y');

            // clear all ortsh urls that are previews
            remove_ortsh_previews();

            // make url field longer
            url_field_longer();
        }

        // lower than 0.5.1
        if ($ssbpVersion < '0.5.1') {
            // new in 0.5.1
            add_option('ssbp_two_enabled', '');
            add_option('ssbp_two_style', '');
            add_option('ssbp_custom_yummly', '');
        }

        // lower than 0.5.2
        if ($ssbpVersion < '0.5.2') {
            // added in 0.5.2
            // upgrade to new options format
            ssbp_options_upgrade();
        }
    }

    // get all the latest settings
    $arrSettings = get_ssbp_settings();

    // lower than 0.5.5
    if ($ssbpVersion < '0.5.5') {
        // added in 0.5.5
        $new = array(
            'custom_styles' => '',
            'additional_css' => $arrSettings['custom_styles'] . $arrSettings['additional_css'],
            'pinterest_use_featured' => '',
            'pinterest_use_ssbp_meta' => '',
        );

        // save thew new option
        ssbp_update_options($new);
    }

    // lower than 0.5.8
    if ($ssbpVersion < '0.5.8') {
        // wpdb global
        global $wpdb;

        // use prefix to ssbp table name
        $table_name = $wpdb->prefix . 'ssbp_tracking';

        // check if the ssbp_tracking table exists
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            // ssbp table not there, add it
            ssbp_add_tracking_table();
        }
    }

    // lower than 1.0.0
    if ($ssbpVersion < '1.0.0') {
        // added in 1.0.0
        $new = array(
            'ga_onclick' => '',
            'custom_styles_enabled' => '',
            'set_one_position' => '',
            'set_two_position' => '',
            'one_responsive' => '',
            'one_share_counts' => '',
            'one_toggle' => '',
            'two_share_counts' => '',
            'two_toggle' => '',
            'button_height' => '',
            'button_width' => '',
            'icon_size' => '',
            'icon_color' => '',
            'icon_color_hover' => '',
            'icon_color_two' => '',
            'color_main_two' => '',
            'color_hover_two' => '',
            'icon_color_hover_two' => '',
            'meta_type' => '',
            'ssbp_content_priority' => '10',
            'icon_two_size' => '',
            'button_two_height' => '',
            'button_two_width' => '',
            'button_two_margin' => '',
            'meta_use_featured_image' => '',
            'use_shortlinks' => '',
            'one_total_share_counts' => '',
            'two_total_share_counts' => '',
        );

        // if the set selected is no longer available
        if (in_array($arrSettings['default_style'], array('11', '12', '13'))) {
            // switch on old style 1
            switch ($arrSettings['default_style']) {
                case '11':
                    $new['default_style'] = '9';
                    $new['one_responsive'] = 'Y';
                    $new['set_one_position'] = 'fixed-left';
                    break;

                case '12':
                    $new['default_style'] = '8';
                    break;

                case '13':
                    $new['default_style'] = '1';
                    break;
            }
        }

        // if a second set wasn't enabled
        if ($arrSettings['two_enabled'] != 'Y') {
            // ensure no second set is selected - no longer needs 'enabling'
            $new['two_style'] = '';
        } // a second set was being used
        else {
            // if the set selected for set two is no longer available
            if (in_array($arrSettings['two_style'], array('11', '12', '13'))) {
                // switch on old style 1
                switch ($arrSettings['two_style']) {
                    case '11':
                        $new['two_style'] = '9';
                        $new['set_two_position'] = 'fixed-left';
                        break;

                    case '12':
                        $new['two_style'] = '8';
                        break;

                    case '13':
                        $new['two_style'] = '1';
                        break;
                }
            }
        }

        // if custom styles were in use then 'enable' them in 1.0.0+
        if ($arrSettings['custom_styles'] != '') {
            $new['custom_styles_enabled'] = 'Y';
        }

        // if no preset style was in place before, we need one
        if ($arrSettings['default_style'] == '') {
            $new['default_style'] = '1';
        }

        // main colour set
        if ($arrSettings['color_main'] != '') {
            $new['color_main'] = $new['color_main_two'] = $arrSettings['color_main'];
        }

        // hover colour set
        if ($arrSettings['color_hover'] != '') {
            $new['color_hover'] = $new['color_hover_two'] = $arrSettings['color_hover'];
        }

        // icon colors
        if ($arrSettings['icon_color'] == 'white') {
            $new['icon_color'] = $new['icon_color_two'] = '#fff';
        }

        // icon colors
        if ($arrSettings['icon_color'] == 'black') {
            $new['icon_color'] = $new['icon_color_two'] = '#272727';
        }

        // reset button margin
        $new['button_margin'] = '';

        // save the new options
        ssbp_update_options($new);

        // add new tables
        ssbp_add_share_counts_table();
        ssbp_add_ortsh_table();

        // cleanup old ortsh data
        delete_old_ortsh_data();
    }

    // lower than 1.0.1
    if ($ssbpVersion < '1.0.1') {
        // added in 1.0.1
        $new = array(
            'load_font_in_head' => '',
        );

        // save the new option
        ssbp_update_options($new);
    }

    // lower than 1.0.2
    if ($ssbpVersion < '1.0.2') {
        // added in 1.0.2
        $new = array(
            'admin_only_tracking' => '',
            'ga_track_logged_in' => '',
            'ga_tracking_id' => '',
        );

        // save the new option
        ssbp_update_options($new);
    }

    // lower than 1.0.6
    if ($ssbpVersion < '1.0.6') {
        // added in 1.0.6
        $new = array(
            'one_breakpoint' => '480',
            'tracking_enabled' => 'Y',
        );

        // save the new option
        ssbp_update_options($new);
    }

    // lower than 1.0.8
    if ($ssbpVersion < '1.0.8') {
        // added in 1.0.8
        $new = array(
            'whatsapp_shortlinks' => '',
            'use_native_links' => '',
            'email_popup' => '',
            'email_popup_brute_time' => '5',
            'email_popup_alert_brute' => 'Sorry, the time between sending emails is limited',
            'email_popup_alert_success' => 'Thanks, your email has been sent',
            'email_popup_alert_warning' => 'Please check the fields and try again',
        );

        // save the new option
        ssbp_update_options($new);

        // add email log table
        ssbp_add_email_log_table();
    }

    // lower than 1.1.1
    if ($ssbpVersion < '1.1.1') {
        // get site name and admin email
        $site_name = get_bloginfo('name');
        $admin_email = get_bloginfo('admin_email');

        // added in 1.1.1
        $new = array(
            'email_popup_subject' => "Your friend thinks you'd be interested in this",
            'email_popup_from_name' => $site_name,
            'email_popup_from_email' => $admin_email,
            'ssb_attribution' => 'Y',
        );

        // save the new option
        ssbp_update_options($new);
    }

    // lower than 1.1.2
    if ($ssbpVersion < '1.1.2') {
        // added in 1.1.2
        $new = array(
            'email_popup_email_label' => "Friend's email",
            'email_popup_email_placeholder' => 'friends@email.com',
            'email_popup_message_label' => 'Message',
            'email_popup_button_text' => 'Send',
            'onscroll_enabled' => '',
            'onscroll_top' => '',
            'onscroll_bottom' => '',
        );

        // save the new option
        ssbp_update_options($new);
    }

    // lower than 1.1.4
    if ($ssbpVersion < '1.1.4') {
        // added in 1.1.4
        $new = array(
            'twitter_newsharecounts' => '',
        );

        // save the new option
        ssbp_update_options($new);
    }

    // lower than 1.1.5
    if ($ssbpVersion < '1.1.5') {
        // added in 1.1.5
        $new = array(
            'search_results' => '',
        );

        // save the new option
        ssbp_update_options($new);
    }

    // lower than 1.2.0
    if ($ssbpVersion < '1.2.0') {
        // added in 1.2.0
        $new = array(
            'facebook_insights' => '',
            'facebook_app_id' => '',
        );

        // save the new option
        ssbp_update_options($new);
    }

    // button helper array
    ssbp_button_helper_array();

    // set new version number
    update_option('ssbp_version', SSBP_VERSION);
}

// make url field longer
function url_field_longer()
{
    // wpdb global
    global $wpdb;

    // hide errors in case
    $wpdb->hide_errors();

    // use prefix to ssbp table name
    $table_name = $wpdb->prefix . 'ssbp_tracking';

    // include wp upgrade functionality and add field
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    // prepare and run sql
    $sql = "ALTER TABLE $table_name MODIFY url VARCHAR(250) NOT NULL;";
    $wpdb->query($sql);
}

// clear all ortsh urls that are previews
function remove_ortsh_previews()
{
    // wpdb global
    global $wpdb;

    // hide errors in case
    $wpdb->hide_errors();

    // use prefix to ssbp table name
    $table_name = $wpdb->prefix . 'options';

    // include wp upgrade functionality and add field
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    // prepare and run sql
    $sql = "DELETE FROM $table_name WHERE option_name LIKE 'ortsh_%' AND (option_value LIKE '%preview=true%' OR option_value LIKE '%bbp_reply_to%');";
    $wpdb->query($sql);
}

// clear the share counts table
function truncateSharecounts()
{
    // wpdb global
    global $wpdb;

    // hide errors in case
    $wpdb->hide_errors();

    // use prefix to ssbp table name
    $table_name = $wpdb->prefix . 'ssbp_share_counts';

    // include wp upgrade functionality and add field
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    // prepare and run sql
    $sql = "TRUNCATE $table_name;";
    $wpdb->query($sql);
}

// delete all ortsh data
function delete_old_ortsh_data()
{
    // wpdb global
    global $wpdb;

    // hide errors in case
    $wpdb->hide_errors();

    // use prefix to ssbp table name
    $table_name = $wpdb->prefix . 'options';

    // include wp upgrade functionality and add field
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    // prepare and run sql
    $sql = "DELETE FROM $table_name WHERE option_name LIKE 'ortsh_%';";
    $wpdb->query($sql);
}

// make ssbp tracking table unicode
function ssbp_tracking_unicode()
{
    // wpdb global
    global $wpdb;

    // hide errors in case
    $wpdb->hide_errors();

    // use prefix to ssbp table name
    $table_name = $wpdb->prefix . 'ssbp_tracking';

    // include wp upgrade functionality and add field
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    // prepare and run sql
    $sql = "alter table $table_name convert to character set utf8 collate utf8_unicode_ci;";
    $wpdb->query($sql);
}

// alter ssbp tracking table
function ssbp_add_geoip()
{
    // wpdb global
    global $wpdb;

    // hide errors in case the column already exists
    $wpdb->hide_errors();

    // use prefix to ssbp table name
    $table_name = $wpdb->prefix . 'ssbp_tracking';

    // include wp upgrade functionality and add field
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    // prepare and run sql
    $sql = "ALTER IGNORE TABLE $table_name ADD geoip VARCHAR(85);";
    $wpdb->query($sql);

    // set to utf8 unicode
    ssbp_tracking_unicode();
}

// remove ssbp tracking table
function ssbp_remove_table()
{
    // wpdb global
    global $wpdb;

    // use prefix to ssbp table name
    $id = $wpdb->prefix . 'ssbp_tracking';

    // delete table
    $wpdb->query($wpdb->prepare('DROP TABLE %d;', $id));
}

// upgrade to new ssbp settings
function ssbp_options_upgrade()
{
    // get existing options
    $arrOldSettings = get_old_ssbp_settings();

    // create an empty new option ready
    add_option('ssbp_settings', '');

    // blank array for new settings
    $arrNewSettings = array();

    // loop through each old setting
    foreach ($arrOldSettings as $key => $value) {
        // remove ssbp from option key
        $newKey = str_replace('ssbp_', '', $key);

        // update the array with the new one
        $arrNewSettings[$newKey] = $arrOldSettings[$key];

        // unset the old element to clear space
        unset($arrOldSettings[$key]);
    }

    // update the options
    ssbp_update_options($arrNewSettings);

    // remove the old style options
    ssbp_remove_old_options();
}

// remove old ssbp options
function ssbp_remove_old_options()
{
    // delete all options
    delete_option('ssbp_install_date');
    delete_option('ssbp_two_enabled');
    delete_option('ssbp_two_style');
    delete_option('ssbp_ortsh_enabled');
    delete_option('ssbp_use_ssba');
    delete_option('ssbp_lazy_load');
    delete_option('ssbp_pages');
    delete_option('ssbp_posts');
    delete_option('ssbp_cats_archs');
    delete_option('ssbp_homepage');
    delete_option('ssbp_excerpts');
    delete_option('ssbp_before_or_after');
    delete_option('ssbp_custom_styles');
    delete_option('ssbp_button_percent');
    delete_option('ssbp_email_message');
    delete_option('ssbp_rel_nofollow');
    delete_option('ssbp_twitter_text');
    delete_option('ssbp_twitter_tags');
    delete_option('ssbp_twitter_username');
    delete_option('ssbp_buffer_text');
    delete_option('ssbp_flattr_user_id');
    delete_option('ssbp_flattr_url');
    delete_option('ssbp_show_share_count');
    delete_option('ssbp_share_count_once');
    delete_option('ssbp_show_btn_txt');
    delete_option('ssbp_default_style');
    delete_option('ssbp_additional_css');
    delete_option('ssbp_button_shape');
    delete_option('ssbp_size');
    delete_option('ssbp_icon_color');
    delete_option('ssbp_counters_enabled');
    delete_option('ssbp_share_api');
    delete_option('ssbp_counters_type');
    delete_option('ssbp_min_shares');
    delete_option('ssbp_count_timeout');
    delete_option('ssbp_count_cache');
    delete_option('ssbp_show_full_stats');

    // extra styling
    delete_option('ssbp_button_borders');
    delete_option('ssbp_button_margin');

    // bitly
    delete_option('ssbp_bitly_login');
    delete_option('ssbp_bitly_api_key');

    // custom post types
    delete_option('ssbp_disabled_types');

    // meta defaults
    delete_option('ssbp_meta_enabled');
    delete_option('ssbp_meta_title');
    delete_option('ssbp_meta_description');
    delete_option('ssbp_meta_image');

    // share text
    delete_option('ssbp_share_text');
    delete_option('ssbp_widget_text');
    delete_option('ssbp_text_placement');
    delete_option('ssbp_font_family');
    delete_option('ssbp_font_color');
    delete_option('ssbp_font_size');
    delete_option('ssbp_font_weight');

    // include
    delete_option('ssbp_selected_buttons');

    // custom colours
    delete_option('ssbp_color_main');
    delete_option('ssbp_color_border');
    delete_option('ssbp_color_hover');

    // custom images
    delete_option('ssbp_custom_images');
    delete_option('ssbp_image_width');
    delete_option('ssbp_image_height');
    delete_option('ssbp_image_padding');
    delete_option('ssbp_custom_buffer');
    delete_option('ssbp_custom_diggit');
    delete_option('ssbp_custom_email');
    delete_option('ssbp_custom_google');
    delete_option('ssbp_custom_facebook');
    delete_option('ssbp_custom_flattr');
    delete_option('ssbp_custom_linkedin');
    delete_option('ssbp_custom_pinterest');
    delete_option('ssbp_custom_print');
    delete_option('ssbp_custom_reddit');
    delete_option('ssbp_custom_stumbleupon');
    delete_option('ssbp_custom_tumblr');
    delete_option('ssbp_custom_twitter');
    delete_option('ssbp_custom_vk');
    delete_option('ssbp_custom_yummly');

    // all done
    return true;
}

//======================================================================
// 		GET OLD SSBP SETTINGS
//======================================================================

// return old ssbp settings
function get_old_ssbp_settings()
{
    // globals
    global $wpdb;

    // blank array ready
    $ssbp_settings = array();

    // the ONLY options we want
    $arrWanted = array(
        'ssbp_two_enabled',
        'ssbp_two_style',
        'ssbp_ortsh_enabled',
        'ssbp_use_ssba',
        'ssbp_lazy_load',
        'ssbp_pages',
        'ssbp_posts',
        'ssbp_cats_archs',
        'ssbp_homepage',
        'ssbp_excerpts',
        'ssbp_before_or_after',
        'ssbp_alignment',
        'ssbp_custom_styles',
        'ssbp_button_percent',
        'ssbp_email_message',
        'ssbp_rel_nofollow',
        'ssbp_twitter_text',
        'ssbp_twitter_tags',
        'ssbp_twitter_username',
        'ssbp_buffer_text',
        'ssbp_flattr_user_id',
        'ssbp_flattr_url',
        'ssbp_show_btn_txt',
        'ssbp_default_style',
        'ssbp_additional_css',
        'ssbp_button_shape',
        'ssbp_size',
        'ssbp_icon_color',
        'ssbp_counters_enabled',
        'ssbp_share_api',
        'ssbp_counters_type',
        'ssbp_min_shares',
        'ssbp_count_timeout',
        'ssbp_count_cache',
        'ssbp_show_full_stats',
        'ssbp_button_borders',
        'ssbp_button_margin',
        'ssbp_bitly_login',
        'ssbp_bitly_api_key',
        'ssbp_disabled_types',
        'ssbp_meta_enabled',
        'ssbp_meta_title',
        'ssbp_meta_description',
        'ssbp_meta_image',
        'ssbp_text_placement',
        'ssbp_widget_text',
        'ssbp_share_text',
        'ssbp_font_family',
        'ssbp_font_color',
        'ssbp_font_size',
        'ssbp_font_weight',
        'ssbp_selected_buttons',
        'ssbp_color_main',
        'ssbp_color_border',
        'ssbp_color_hover',
        'ssbp_custom_images',
        'ssbp_image_width',
        'ssbp_image_height',
        'ssbp_image_padding',
        'ssbp_custom_buffer',
        'ssbp_custom_diggit',
        'ssbp_custom_email',
        'ssbp_custom_google',
        'ssbp_custom_facebook',
        'ssbp_custom_flattr',
        'ssbp_custom_linkedin',
        'ssbp_custom_pinterest',
        'ssbp_custom_print',
        'ssbp_custom_reddit',
        'ssbp_custom_stumbleupon',
        'ssbp_custom_tumblr',
        'ssbp_custom_twitter',
        'ssbp_custom_vk',
        'ssbp_custom_yummly',
    );

    // query the db for current ssbp settings
    $result = $wpdb->get_results("SELECT option_name, option_value
											 FROM $wpdb->options
											WHERE option_name LIKE 'ssbp_%'");

    // loop through each setting in the array
    foreach ($result as $setting) {
        // skip uneeded results that may not be options
        if (!in_array($setting->option_name, $arrWanted)) {
            continue;
        }

        // add each setting to the array by name
        $ssbp_settings[$setting->option_name] = $setting->option_value;
    }

    // return
    return $ssbp_settings;
}


// button helper option
function ssbp_button_helper_array()
{
    // helper array for ssbp
    update_option('ssbp_buttons', json_encode(array(
        'buffer' => array(
            'full_name'    => 'Buffer',
        ),
        'diggit' => array(
            'full_name'    => 'Diggit',
        ),
        'email' => array(
            'full_name'    => 'Email',
        ),
        'ellipsis' => array(
            'full_name'    => 'More',
        ),
        'facebook' => array(
            'full_name'    => 'Facebook',
        ),
        // 'facebook_save' => array(
        //     'full_name'    => 'Facebook Save',
        // ),
        'flattr' => array(
            'full_name'    => 'Flattr',
        ),
        'google' => array(
            'full_name'    => 'Google+',
        ),
        'linkedin' => array(
            'full_name'    => 'LinkedIn',
        ),
        'pinterest' => array(
            'full_name'    => 'Pinterest',
        ),
        'print' => array(
            'full_name'    => 'Print',
        ),
        'reddit' => array(
            'full_name'    => 'Reddit',
        ),
        'stumbleupon' => array(
            'full_name'    => 'StumbleUpon',
        ),
        'tumblr' => array(
            'full_name'    => 'Tumblr',
        ),
        'twitter' => array(
            'full_name'    => 'Twitter',
        ),
        'vk' => array(
            'full_name'    => 'VK',
        ),
        'whatsapp' => array(
            'full_name'    => 'WhatsApp',
        ),
        'yummly' => array(
            'full_name'    => 'Yummly',
        ),
        'xing' => array(
            'full_name'    => 'Xing',
        ),
    )));
}
