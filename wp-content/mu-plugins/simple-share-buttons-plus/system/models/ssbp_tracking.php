<?php
defined('ABSPATH') or die('No direct access permitted');

// tracking page
function ssbp_tracking()
{
    // check if user has the rights to manage options
    if (!current_user_can('edit_posts')) {

        // permissions message
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    // --------- TRACKING PANEL ------------ //
    ssbp_admin_tracking();
}

// ajax call to save to tracking table
function ssbp_tracking_callback()
{
    // global db
    global $wpdb;

    // get ssbp table name
    $table_name = $wpdb->prefix . 'ssbp_tracking';

    // get ip
    $the_ip = ssbp_get_ip();

    // exist if data doesn't checkout
    if (!ssbp_tracking_data_ok($the_ip)) {
        return false;
    }

    // if Pinterest_force set to Pinterest
    if ($_POST['site'] == 'Pinterest_force') {
        $_POST['site'] = 'Pinterest';
    }

    // insert the share
    if ($wpdb->insert($table_name, array(
        'title' => wp_strip_all_tags($_POST['title']),
        'url' => $_POST['url'],
        'site' => wp_strip_all_tags($_POST['site']),
        'ip' => $the_ip,
        'datetime' => date('Y-m-d H:i:s'),
    ))
    ) {
        // everything went ok
        echo 'Share click saved';
        exit;
    } else {
        // failed to insert share
        echo 'Unable to save share click';
        exit;
    }
}

// ajax call to save to tracking table
function ssbp_standard_callback()
{
    // global db
    global $wpdb;

    // get ssbp table name
    $table_name = $wpdb->prefix . 'ssbp_tracking';

    // get ip
    $the_ip = ssbp_get_ip();

    // exist if data doesn't checkout
    if (!ssbp_tracking_data_ok($the_ip)) {
        return false;
    }

    // if Pinterest_force set to Pinterest
    if ($_POST['site'] == 'Pinterest_force') {
        $_POST['site'] = 'Pinterest';
    }

    // insert the share
    if ($wpdb->insert($table_name, array(
        'title' => wp_strip_all_tags($_POST['title']),
        'url' => $_POST['url'],
        'site' => wp_strip_all_tags($_POST['site']),
        'ip' => $the_ip,
        'datetime' => date('Y-m-d H:i:s'),
    ))
    ) {
        // everything went ok
        echo 'Share click saved';
        exit;
    } else {
        // failed to insert share
        echo 'Unable to save share click';
        exit;
    }
}

function ssbp_tracking_data_ok($the_ip)
{
    // check all fields have values
    if (
        (
            $_POST['title'] ||
            $_POST['url'] ||
            $_POST['site'] ||
            $the_ip
        )
        == ''
    ) {
        // a required field was missing
        return false;
    }

    // check the url is valid
    if (filter_var($_POST['url'], FILTER_VALIDATE_URL) === false) {
        return false;
    }

    // everything seems ok with the data
    return true;
}

function ssbp_get_ip()
{

    //Just get the headers if we can or else use the SERVER global
    if (function_exists('apache_request_headers')) {
        $headers = apache_request_headers();
    } else {
        $headers = $_SERVER;
    }

    //Get the forwarded IP if it exists
    if (array_key_exists('X-Forwarded-For', $headers) && filter_var($headers['X-Forwarded-For'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
        $the_ip = $headers['X-Forwarded-For'];
    } elseif (array_key_exists('HTTP_X_FORWARDED_FOR', $headers) && filter_var($headers['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)
    ) {
        $the_ip = $headers['HTTP_X_FORWARDED_FOR'];
    } else {
        $the_ip = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
    }

    // return the ip
    return $the_ip;
}
