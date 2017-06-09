<?php

defined('ABSPATH') or die('No direct access permitted');

function ssbp_ortsh_dashboard()
{
    // make sure we have settings ready
    $ssbp_settings = get_ssbp_settings();

    // get license status
    $status = get_option('ssbp_license_status');

    // ssbp header
    $htmlShareButtonsForm = ssbp_admin_header();

    // heading
    $htmlShareButtonsForm .= '<h2>ort.sh</h2>';

    // intro info
    $htmlShareButtonsForm .= '<blockquote><p>ort.sh is a URL shortening service, with an API that is currently <b>exclusive to Simple Share Buttons Plus users</b>. All visits are tracked and will become available soon.</p></blockquote>';

    // initiate forms helper
    $ssbpForm = new ssbpForms();

    // prepare action url
    $action = admin_url('admin.php?page=simple-share-buttons-ortsh');

    // opening form tag
    $htmlShareButtonsForm .= $ssbpForm->open(true, $action);

    // the license is not valid
    if ($status === false || $status != 'valid') {
        // error
        $opts = array(
            'form_group' => true,
            'type' => 'error',
            'name' => 'share_api',
            'label' => 'SSB API',
            'error' => 'A <b>valid, active license</b> is required to take advantage of ort.sh',
            'tooltip' => 'If your buttons are incorrectly displaying zero share counts for Facebook, switch this on to retrieve counts via the SSB API',
        );
        $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);
    } // no valid license
    else {
        // if mcrypt isn't available
        if (!function_exists('mcrypt_encrypt')) {
            // error
            $opts = array(
                'form_group' => true,
                'type' => 'error',
                'name' => 'ortsh_enabled',
                'label' => 'Enable ort.sh',
                'error' => 'The PHP extension <b>Mcrypt</b> is required to use ort.sh - please inform your host, it is a common requirement',
                'tooltip' => 'Enable URL shortening via ort.sh',
            );
            $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);
        } // mcrypt is available
        else {
            // enable ortsh
            $opts = array(
                'form_group' => true,
                'type' => 'checkbox',
                'name' => 'ortsh_enabled',
                'label' => 'Enable ort.sh',
                'tooltip' => 'Enable URL shortening via ort.sh',
                'value' => 'Y',
                'checked' => ($ssbp_settings['ortsh_enabled'] == 'Y' ? 'checked' : null),
            );
            $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);
        }
    }

    // close off form with save button
    $htmlShareButtonsForm .= $ssbpForm->close();

    // ouput form
    echo $htmlShareButtonsForm;

    // if ortsh is enabled
    if ($ssbp_settings['ortsh_enabled'] == 'Y') {
        // heading
        $htmlOrtshTable = '<h3>ort.sh URLs</h3>';

        // get ortsh urls data ready
        $ortshURLs = get_ortsh_urls();

        $htmlOrtshTable .= '<table class="table table-striped table-hover table-responsive">
						  <thead>
						    <tr>
						      <th>Post/Page</th>
						      <th>Full URL</th>
						      <th>ort.sh URL</th>
						    </tr>
						  </thead>
						  <tbody>';

        // if no ortsh urls are there yet
        if (count($ortshURLs) == 0) {
            $htmlOrtshTable .= '<tr>';
            $htmlOrtshTable .= '<td colspan="3"  class="text-center warning">No ort.sh URLs are currently set. Once enabled they are created when each post/page is visited.</td>';
            $htmlOrtshTable .= '</tr>';
        } else {
            // loop through each ortsh url
            foreach ($ortshURLs as $ortshURL) {
                // new row
                $htmlOrtshTable .= '<tr class="ortsh-row">';

                // output the details for each
                $htmlOrtshTable .= '<td>' . $ortshURL->title . '</td>';
                $htmlOrtshTable .= '<td><a href="' . $ortshURL->url . '" target="_blank">' . $ortshURL->url . '</a></td>';
                $htmlOrtshTable .= '<td><input class="ssbp-ortsh-input-url form-control" type="text" value="http://ort.sh/' . $ortshURL->ortsh_key . '" /></td>';

                // close row
                $htmlOrtshTable .= '</tr>';
            }
        }

        // close off table
        $htmlOrtshTable .= '</tbody>
						</table> ';

        // output the table
        echo $htmlOrtshTable;

        // heading
        $htmlOrtshVisits = '<h3>ort.sh Visits</h3>';

        $htmlOrtshVisits .= '<div class="text-center">
								<h2>Coming Soon</h2>
								<p>All visits in the meantime will be stored</p>
							</div>';

        // output visits
        echo $htmlOrtshVisits;
    } // close if ort.sh is enabled

    // ssbp footer
    $htmlShareButtonsForm .= ssbp_admin_footer();
}

// get ortsh urls
function get_ortsh_urls()
{
    // globals
    global $wpdb;

    // use prefix to ssbp table name
    $table_name = $wpdb->prefix . 'ssbp_ortsh_urls';

    // query the db for current ssbp settings
    $objOrtsh = $wpdb->get_results("SELECT * FROM $table_name");

    // return
    return $objOrtsh;
}
