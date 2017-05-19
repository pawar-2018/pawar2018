<?php
defined('ABSPATH') or die('No direct access permitted');

/**
 * SSBP Send Email
 */
class SSBP_Send_Email {

    // vars
    public $settings;

    function __construct()
    {
        // get ssbp settings
        $this->settings = get_ssbp_settings();
    }

    function is_brute()
    {
        // include wpdb
        global $wpdb;

        // prepare table name
        $table_name = $wpdb->prefix.'ssbp_email_log';

        // get the ip
        $ip = $this->get_ip();

        // check against chosen time between emails
        $time_ago = date('Y-m-d H:i:s', time() - $this->settings['email_popup_brute_time'] * 60);

        // run query
        $result = $wpdb->get_row("SELECT * FROM $table_name
                                          WHERE ip = '$ip'
                                            AND datetime >= '$time_ago'");

        // no record found
        if (empty($result) || $result === NUll) {
            // no email sent in the past 5 mins
            return false;
        }

        // email sent too recently, presume brute
        return true;
    }

    function valid_inputs($email, $message, $url)
    {
        // no/invalid email address
        if ($email == '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        // message is not a string or is longer than it should be
        if (! is_string($message) || strlen($message) > 250) {
            return false;
        }

        // check the url is valid
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            return false;
        }

        return true;
    }

    function send_email($email, $message)
    {
        // define headers
        $headers = array(
            'From: '.$this->settings['email_popup_from_name'].' <'.$this->settings['email_popup_from_email'].'>'
        );

        // email the person that signed up
        wp_mail($email, $this->settings['email_popup_subject'], (string) $message, $headers);
    }

    function get_ip()
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

    function log_email($url, $email)
    {
        // include wpdb
        global $wpdb;

        // prepare table name
        $table_name = $wpdb->prefix.'ssbp_email_log';

        // get the ip
        $ip = $this->get_ip();

        $wpdb->insert($table_name, array(
            'url'      => wp_strip_all_tags($url),
            'email'    => wp_strip_all_tags($email),
            'ip'       => $ip,
            'datetime' => date('Y-m-d H:i:s'),
        ));
    }

}
