<?php
//set_site_transient( 'update_plugins', null );
include dirname(__FILE__) . '/EDD_SL_Plugin_Updater.php';

define('SSBP_SL_STORE_URL', 'https://simplesharebuttons.com');
define('SSBP_SL_ITEM_NAME', 'Simple Share Buttons Plus');

function ssbp_sl_plugin_updater()
{
    // retrieve our license key from the DB
    $license_key = trim(get_option('ssbp_license_key'));

    // setup the updater
    $ssbp_updater = new SSB_SL_Plugin_Updater(SSBP_SL_STORE_URL, SSBP_FILE, array(
            'version' => SSBP_VERSION,        // current version number
            'license' => $license_key,    // license key (used get_option above to retrieve from DB)
            'item_name' => SSBP_SL_ITEM_NAME,    // name of this plugin
            'author' => 'Simple Share Buttons',  // author of this plugin
            'url' => home_url(),
        )
    );
}

add_action('admin_init', 'ssbp_sl_plugin_updater');

function ssbp_activate_license()
{
    // listen for our activate button to be clicked
    if (isset($_POST['ssbp_license_activate'])) {

        // run a quick security check
        if (!check_admin_referer('ssbp_activate_nonce', 'ssbp_activate_nonce')) {
            return;
        } // get out if we didn't click the Activate button

        // retrieve the license from the database
        $license = trim(get_option('ssbp_license_key'));

        // data to send in our API request
        $api_params = array(
            'edd_action' => 'activate_license',
            'license' => $license,
            'item_name' => urlencode(SSBP_SL_ITEM_NAME), // the name of our product in EDD,
            'url' => home_url(),
        );

        // Call the custom API.
        $response = wp_remote_post(SSBP_SL_STORE_URL, array('timeout' => 15,  'sslverify' => false, 'body' => $api_params));

        // make sure the response came back okay
        if (is_wp_error($response)) {
            $postvars = '';
            foreach($api_params as $key=>$value) {
                $postvars .= $key . "=" . $value . "&";
            }

            $curl2 = curl_init();
            curl_setopt($curl2,CURLOPT_URL,'https://simplesharebuttons.com');
            curl_setopt($curl2, CURLOPT_POST, 1);
            curl_setopt($curl2, CURLOPT_POSTFIELDS, $postvars);
            curl_setopt( $curl2, CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $curl2, CURLOPT_TIMEOUT, 50 );
            curl_setopt($curl2, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);

            $r['body'] = curl_exec( $curl2 );
            $response = $r;

            if( 0 !== curl_errno( $curl2 ) || 200 !== curl_getinfo( $curl2, CURLINFO_HTTP_CODE ) ) {
                $response = null;
            } // end if
            curl_close( $curl2 );
            if($response == null) {
                // start a session if needed
                @session_start();

                // set error in session
                $_SESSION['ssbp_license_error'] =  $response->get_error_message();

                // signify error
                return false;
            }

        }

        // decode the license data
        $license_data = json_decode(wp_remote_retrieve_body($response));

        // $license_data->license will be either "active" or "inactive"
        update_option('ssbp_license_status', $license_data->license);

        // signify success
        return true;
    }
}

add_action('admin_init', 'ssbp_activate_license');

function ssbp_licensing()
{
    $license = get_option('ssbp_license_key');
    $status = get_option('ssbp_license_status');

    // ssbp header
    echo ssbp_admin_header();

    // heading
    echo '<h2>Licensing</h2>';

    // intro info
    echo '<blockquote><p>A valid license is required to take advantage of the SSB API, ort.sh URL shortening and Developer Support. Enter your license below, save, then click activate. You can <a href="https://simplesharebuttons.com/purchases/" target="_blank">manage your licenses here</a> and <a href="https://simplesharebuttons.com/plus/" target="_blank">buy more/renew licenses here</a>.</p>
	<p><b>If you are having problems activating your license</b>, please ensure you/your host\'s version of <b>cURL</b> is up-to-date.</p></blockquote>';

    // start a session if needed
    @session_start();

    // if a license error is present
    if (isset($_SESSION['ssbp_license_error']) && $_SESSION['ssbp_license_error'] != '') {
        // output error alert
        echo '<div class="alert alert-danger"><strong>Activation error:</strong> ' . $_SESSION['ssbp_license_error'] . '</div>';

        // unset license error, hopefully the next try will be successful
        unset($_SESSION['ssbp_license_error']);
    }

    // initiate forms helper
    $ssbpForm = new ssbpForms();

    // opening form tag
    echo $ssbpForm->open(true, 'options.php', 'ssbp-form-non-ajax');

    settings_fields('ssbp_license');

    // default share title
    $opts = array(
        'form_group' => true,
        'type' => 'text',
        'placeholder' => '555ab175b3fd6747a7da2fc652509e56',
        'name' => 'ssbp_license_key',
        'label' => 'License Key',
        'tooltip' => 'Enter your SSBP license key',
        'value' => esc_attr($license),
    );
    echo $ssbpForm->ssbp_input($opts);

    if (false !== $license && $license != '') {
        ?>

        <div class="form-group">
            <label for="ssbp_license_activate" class="control-label" data-toggle="tooltip" data-placement="right"
                   data-original-title="Activate your license here">Activate License</label>

        </div>
        <?php if ($status !== false && $status == 'valid') {
            ?>
            <span style="color:green;"><?php _e('Active');
                ?></span>
            <?php
        } else {
            wp_nonce_field('ssbp_activate_nonce', 'ssbp_activate_nonce');
            ?>
            <button type="submit" class="btn btn-primary" name="ssbp_license_activate">Activate License</button>
            <?php
        }
        ?>
        <?php
    }
    ?>

    <?php
    // close off form with save button
    echo $ssbpForm->close();

    // ssbp footer
    echo ssbp_admin_footer();
}

function ssbp_register_option()
{
    // creates our settings in the options table
    register_setting('ssbp_license', 'ssbp_license_key', 'ssbp_sanitize_license');
}

add_action('admin_init', 'ssbp_register_option');

function ssbp_sanitize_license($new)
{
    $old = get_option('ssbp_license_key');
    if ($old && $old != $new) {
        delete_option('ssbp_license_status'); // new license has been entered, so must reactivate
    }

    return $new;
}

function ssbp_deactivate_license()
{
    // listen for our activate button to be clicked
    if (isset($_POST['edd_license_deactivate'])) {

        // run a quick security check
        if (!check_admin_referer('edd_sample_nonce', 'edd_sample_nonce')) {
            return;
        } // get out if we didn't click the Activate button

        // retrieve the license from the database
        $license = trim(get_option('edd_sample_license_key'));

        // data to send in our API request
        $api_params = array(
            'edd_action' => 'deactivate_license',
            'license' => $license,
            'item_name' => urlencode(SSBP_SL_ITEM_NAME), // the name of our product in EDD
            'url' => home_url(),
        );

        // Call the custom API.
        $response = wp_remote_get(add_query_arg($api_params, SSBP_SL_STORE_URL), array('timeout' => 15, 'sslverify' => false));

        // make sure the response came back okay
        if (is_wp_error($response)) {
            return false;
        }

        // decode the license data
        $license_data = json_decode(wp_remote_retrieve_body($response));

        // $license_data->license will be either "deactivated" or "failed"
        if ($license_data->license == 'deactivated') {
            delete_option('edd_sample_license_status');
        }
    }
}

add_action('admin_init', 'ssbp_deactivate_license');

function ssbp_check_license()
{
    global $wp_version;

    $license = trim(get_option('edd_sample_license_key'));

    $api_params = array(
        'edd_action' => 'check_license',
        'license' => $license,
        'item_name' => urlencode(SSBP_SL_ITEM_NAME),
        'url' => home_url(),
    );

    // Call the custom API.
    $response = wp_remote_get(add_query_arg($api_params, SSBP_SL_STORE_URL), array('timeout' => 15, 'sslverify' => false));

    if (is_wp_error($response)) {
        return false;
    }

    $license_data = json_decode(wp_remote_retrieve_body($response));

    if ($license_data->license == 'valid') {
        echo 'valid';
        exit;
        // this license is still valid
    } else {
        echo 'invalid';
        exit;
        // this license is no longer valid
    }
}
