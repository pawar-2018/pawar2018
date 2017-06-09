<?php

defined('ABSPATH') or die('No direct access permitted');

function ssbp_admin_counters()
{

    // make sure we have settings ready
    $ssbp_settings = get_ssbp_settings();

    // get license status
    $status = get_option('ssbp_license_status');

    // ssbp header
    $htmlShareButtonsForm = ssbp_admin_header();

    // heading
    $htmlShareButtonsForm .= '<h2>Share Counters</h2>';

    // intro info
    $htmlShareButtonsForm .= '<blockquote><p>You can tweak share counter settings to your liking here. If you have found that Facebook share counts have reverted to zero on occasion, please switch on the SSB API option below. You can <a href="https://simplesharebuttons.com/plus/features/api/" target="_blank">find out more about it here</a>.</p></blockquote>';

    // initiate forms helper
    $ssbpForm = new ssbpForms();

    // prepare action url
    $action = admin_url('admin.php?page=simple-share-buttons-counters');

    // opening form tag
    $htmlShareButtonsForm .= $ssbpForm->open(true, $action);

    // show share counts
    $opts = array(
        'form_group' => true,
        'type' => 'checkbox',
        'name' => 'counters_enabled',
        'label' => 'Enable Share Counts',
        'tooltip' => 'Switch on to enable share counts',
        'value' => 'Y',
        'checked' => ($ssbp_settings['counters_enabled'] == 'Y' ? 'checked' : null),
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // the license is not valid
    if ($status === false || $status != 'valid') {
        // error
        $opts = array(
            'form_group' => true,
            'type' => 'error',
            'name' => 'share_api',
            'label' => 'SSB API',
            'error' => 'A <b>valid, active license</b> is required to take advantage of the SSB API',
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
                'name' => 'share_api',
                'label' => 'SSB API',
                'error' => 'The PHP extension <b>Mcrypt</b> is required to use the SSB API - please inform your host, it is a common requirement',
                'tooltip' => 'If your buttons are incorrectly displaying zero share counts for Facebook, switch this on to retrieve counts via the SSB API',
            );
            $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);
        } // mcrypt is available
        else {
            // ssb api
            $opts = array(
                'form_group' => true,
                'type' => 'checkbox',
                'name' => 'share_api',
                'label' => 'SSB API',
                'tooltip' => 'If your buttons are incorrectly displaying zero share counts for Facebook, switch this on to retrieve counts via the SSB API',
                'value' => 'Y',
                'checked' => ($ssbp_settings['share_api'] == 'Y' ? 'checked' : null),
            );
            $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);
        }
    }

    // show full share stats
    $opts = array(
        'form_group' => true,
        'type' => 'checkbox',
        'name' => 'show_full_stats',
        'label' => 'Full Share Stats',
        'tooltip' => 'Switch on to enable full share count stats in the tracking dashboard',
        'value' => 'Y',
        'checked' => ($ssbp_settings['show_full_stats'] == 'Y' ? 'checked' : null),
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // min shares
    $opts = array(
        'form_group' => true,
        'type' => 'number_addon',
        'addon' => 'shares',
        'placeholder' => '0',
        'name' => 'min_shares',
        'label' => 'Minimum Shares',
        'tooltip' => 'Set the minimum number of shares required before showing share counts',
        'value' => $ssbp_settings['min_shares'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // count timeout
    $opts = array(
        'form_group' => true,
        'type' => 'number_addon',
        'addon' => 'seconds',
        'placeholder' => '6',
        'name' => 'count_timeout',
        'label' => 'Count Timeout',
        'tooltip' => 'The maximum number of seconds to try and fetch share counts. Gradually set this number higher if your server is not retrieving share counts consistently',
        'value' => $ssbp_settings['count_timeout'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // min shares
    $opts = array(
        'form_group' => true,
        'type' => 'number_addon',
        'addon' => 'seconds',
        'placeholder' => '1800',
        'name' => 'count_cache',
        'label' => 'Count Cache',
        'tooltip' => 'The number of seconds to cache share counts for before refreshing',
        'value' => $ssbp_settings['count_cache'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // use newshare counts.com for twitter counts
    $opts = array(
        'form_group' => true,
        'type' => 'checkbox',
        'name' => 'twitter_newsharecounts',
        'label' => 'newsharecounts.com Counts for Twitter',
        'tooltip' => 'Switch on to enable the use of the newsharecounts.com API for Twitter share counts',
        'value' => 'Y',
        'checked' => ($ssbp_settings['twitter_newsharecounts'] == 'Y' ? 'checked' : null),
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // info
    $htmlShareButtonsForm .= '<div class="form-group"><p>You shall need to follow the instructions here before enabling this feature - <a target="_blank" href="http://newsharecounts.com/">newsharecounts.com</a></div>';

    // close off form with save button
    $htmlShareButtonsForm .= $ssbpForm->close();

    // ssbp footer
    $htmlShareButtonsForm .= ssbp_admin_footer();

    echo $htmlShareButtonsForm;
}
