<?php
defined('ABSPATH') or die('No direct access permitted');

// get and show share buttons
function ssbp_show_share_buttons($content, $booShortCode = false, $atts = '')
{
    // globals
    global $post;

    // get ssbp settings
    $ssbp_settings = get_ssbp_settings();

    // variables
    $pattern = get_shortcode_regex();
    $urlShortened = '';

    // ssbp_hide shortcode is in the post content and instance is not called by shortcode ssbp
    if (preg_match_all('/' . $pattern . '/s', $post->post_content, $matches)
        && array_key_exists(2, $matches)
        && in_array('ssbp_hide', $matches[2])
        && $booShortCode == false
    ) {
        // exit the function returning the content without the buttons
        return $content;
    }

    // if option is set to use free version shortcode
    if ($ssbp_settings['use_ssba'] == 'Y') {
        // ssba_hide shortcode is in the post content and instance is not called by shortcode ssba
        if (preg_match_all('/' . $pattern . '/s', $post->post_content, $matches)
            && array_key_exists(2, $matches)
            && in_array('ssba_hide', $matches[2])
            && $booShortCode == false
        ) {
            // exit the function returning the content without the buttons
            return $content;
        }
    }

    // check if the current post type is not wanted
    if (in_array(get_post_type($post->ID), explode(',', $ssbp_settings['disabled_types']))) {
        return $content;
    }

    // placement on pages/posts/categories/archives/homepage
    if ((!is_home() && !is_front_page() && is_page() && $ssbp_settings['pages'] == 'Y') ||
        (is_single() && $ssbp_settings['posts'] == 'Y') ||
        (is_category() && $ssbp_settings['cats_archs'] == 'Y') ||
        (is_archive() && $ssbp_settings['cats_archs'] == 'Y') ||
        ((is_home() || is_front_page()) && $ssbp_settings['homepage'] == 'Y') ||
        $booShortCode == true ||
        (is_search() && $ssbp_settings['search_results'] == 'Y')) {

        // get license status
        $status = get_option('ssbp_license_status');

        // ssbp comment
        $htmlComment = '<!-- Simple Share Buttons Plus (v' . SSBP_VERSION . ') simplesharebuttons.com/plus ' . ($status === false || $status != 'valid' ? ' License Inactive ' : null) . '-->';

        // if widget use widget share text
        if (isset($atts['widget']) && $atts['widget'] == 'Y') {
            $strShareText = $ssbp_settings['widget_text'];
        } else {// use normal share text
            $strShareText = $ssbp_settings['share_text'];
        }

        // if running standard
        if ($booShortCode == false) {
            // if using shortlinks
            if ($ssbp_settings['use_shortlinks'] == 'Y') {
                $urlShortened = wp_get_shortlink($post->ID);
            }

            // get title and url
            $strPageTitle = get_the_title($post->ID);
            $urlCurrentPage = get_permalink($post->ID);
        } // using shortcode
        else {
            // if we're not viewing a post
            if (!is_single()) {
                // if a title has been provided
                if (isset($atts['title']) && $atts['title'] != '') {
                    // use the set title
                    $strPageTitle = $atts['title'];
                } else {
                    // get the page title
                    $strPageTitle = wp_title('', false);
                }
            } // viewing a single post
            else {
                // set page title as set by user or get if needed
                $strPageTitle = (isset($atts['title']) ? $atts['title'] : get_the_title());
            }

            // set the url as set by user or get if needed
            $urlCurrentPage = (isset($atts['url']) ? $atts['url'] : ssbp_current_url());
        }

        // strip any unwanted tags from the page title
        $strPageTitle = esc_attr(strip_tags($strPageTitle));

        // if ortsh is enabled and we're not previewing
        if ($ssbp_settings['ortsh_enabled'] == 'Y' && (!isset($_GET['preview']) || !isset($_GET['bbp_reply_to']))) {
            // retrieve our license key from the DB
            $ssbpLicense_key = trim(get_option('ssbp_license_key'));

            // check license key is there
            if ($ssbpLicense_key && $ssbpLicense_key != '') {
                // shorten the URL
                $urlShortened = ssbp_ortsh_shorten($ssbpLicense_key, $urlCurrentPage, $strPageTitle);
            }
        } // if both bitly details are set
        elseif ($ssbp_settings['bitly_login'] != '' && $ssbp_settings['bitly_api_key'] != '') {
            // shorten the URL
            $urlShortened = ssbp_bitly_shorten($ssbp_settings['bitly_login'], $ssbp_settings['bitly_api_key'], $urlCurrentPage);
        }

        // get wrap
        $wrap = $htmlComment.'<div class="ssbp-set--one ssbp--state-hidden ssbp-wrap ssbp--' . $ssbp_settings['set_one_position'] . ' ssbp--theme-' . $ssbp_settings['default_style'] . '"' .
            ($ssbp_settings['one_share_counts'] == 'Y' ? ' data-ssbp-counts="true"' : null) .
            ($ssbp_settings['one_total_share_counts'] == 'Y' ? ' data-ssbp-total-counts="true"' : null) .
            ($ssbp_settings['one_toggle'] == 'Y' ? ' data-ssbp-toggle="true"' : null) .
            ($ssbp_settings['one_responsive'] == 'Y' ? ' data-ssbp-responsive="true"' : null) .
            ($ssbp_settings['onscroll_enabled'] == 'Y' ? ' data-ssbp-onscroll="true"' : null) .
            '>';

        // start to prepare everything that goes within the wrap
        $innards = '<button class="ssbp-toggle-switch ssbp-toggle-close"><span></span></button>';

        // ssbp div
        $innards .= '<div class="ssbp-container" data-ssbp-share-text="' . $strShareText . '" data-ssbp-url="' . $urlCurrentPage . '" data-ssbp-title="' . $strPageTitle . '" data-ssbp-short-url="' . $urlShortened . '" data-ssbp-post-id="' . $post->ID . '">';

        // if lazy load is not enabled
        if ($ssbp_settings['lazy_load'] != 'Y') {
            // initiate ssbp button class
            $ssbpButtons = new ssbpShareButtons();

            // the buttons!
            $innards .= $ssbpButtons->get_ssbp_buttons($urlCurrentPage, $strPageTitle, $urlShortened, $post->ID);

            // ifshare counts are enabled add the total share count if it's high enough
            if ($ssbp_settings['counters_enabled'] == 'Y' && $ssbpButtons->ssbpShareCountData['total'] >= intval($ssbpButtons->ssbp_settings['min_shares'])) {

                // if not using custom images
                if ($ssbpButtons->ssbp_settings['custom_images'] != 'Y') {
                    // add total share count
                    $innards .= '<span class="ssbp-total-shares"><b>' . ssbp_format_number($ssbpButtons->ssbpShareCountData['total']) . '</b></span>';
                }
            }
        }

        // close container div
        $innards .= '</div>';

        // innards are ready, add to set one wrap
        $wrap .= $innards;

        // close wrap div
        $wrap .= '</div>';

        // if a second style has been set
        if ($ssbp_settings['two_style'] != '') {
            // open wrap
            $wrap .= '<div class="ssbp-set--two ssbp--state-hidden ssbp-wrap ssbp--' . $ssbp_settings['set_two_position'] . ' ssbp--theme-' . $ssbp_settings['two_style'] . '"' . ($ssbp_settings['two_share_counts'] == 'Y' ? ' data-ssbp-counts="true"' : null) . ($ssbp_settings['two_total_share_counts'] == 'Y' ? ' data-ssbp-total-counts="true"' : null) . ($ssbp_settings['two_toggle'] == 'Y' ? ' data-ssbp-toggle="true"' : null) . '>';

            // add innards
            $wrap .= $innards;

            // close wrap div
            $wrap .= '</div>';
        }

        // adding shortcode buttons
        if ($booShortCode == true) {
            return $wrap;
        } else {
            return ssbp_add_to_content($ssbp_settings['before_or_after'], $content, $wrap);
        }
    } else {
        return $content;
    }
}

// add the buttons to the content where needed
function ssbp_add_to_content($beforeOrAfter, $content, $htmlShareButtons)
{
    // switch for placement of ssbp
    switch ($beforeOrAfter) {
        case 'before': // before the content
            return $htmlShareButtons . $content;
            break;

        case 'after': // after the content
        default:
            return $content . $htmlShareButtons;
            break;

        case 'both': // before and after the content
            return $htmlShareButtons . $content . $htmlShareButtons;
            break;
    }
}

// shortcode for adding buttons
function ssbp_buttons($atts)
{
    // get buttons - NULL for $content, TRUE for shortcode flag
    return ssbp_show_share_buttons(null, true, $atts);
}

// shortcode for hiding buttons
function ssbp_hide($content)
{
    // nothing to do here
}

// get URL function
function ssbp_current_url()
{
    // add http
    $urlCurrentPage = 'http';

    // add s to http if required
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
        $urlCurrentPage .= 's';
    }

    // add colon and forward slashes
    $urlCurrentPage .= '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    // return url
    return htmlspecialchars($urlCurrentPage);
}

// shorten url with ortsh
function ssbp_ortsh_shorten($ssbpLicense_key, $urlLong, $strPageTitle)
{
    // try to get the ortshened key
    $ssbpOrtshKey = get_ortsh(md5($urlLong));

    // the ortsh table isn't there
    if ($ssbpOrtshKey === false) {
        return $urlLong;
    }

    // if this URL hasn't already been ortshened
    if ($ssbpOrtshKey === null) {
        // encryption key
        $ssbpKey = 'FF48C66D642487644BDB69C93C32C';

        // securely encrypt the license key
        $ssbpLicense_key = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($ssbpKey), $ssbpLicense_key, MCRYPT_MODE_CBC, md5(md5($ssbpKey))));

        // get results from ortsh
        $htmlOrtsh = wp_remote_post('http://ort.sh/create', array(
            'method' => 'POST',
            'timeout' => 4,
            'body' => array(
                'version' => SSBP_VERSION,
                'url' => $urlLong,
                'license' => $ssbpLicense_key,
                'title' => $strPageTitle,
            ),
        ));

        // check there was an error
        if (is_wp_error($htmlOrtsh)) {
            return $urlLong;
        } // return urllong so we can still load buttons

        // decode
        $arrOrtsh = json_decode($htmlOrtsh['body'], true);

        // check the response was successful
        if ($arrOrtsh['status'] == 'success' && isset($arrOrtsh['ortsh_key'])) {
            // prepare data
            $arrOrtshData = array(
                'url' => $urlLong,
                'title' => $strPageTitle,
                'ortsh_key' => $arrOrtsh['ortsh_key'],
            );

            // save ortsh data
            add_ortsh(md5($urlLong), $arrOrtshData);

            // prepare ortsh url
            $urlShort = 'http://ort.sh/' . $arrOrtsh['ortsh_key'];

            // return ortsh url
            return $urlShort;
        } else {
            return $urlLong;
        }
    } else {
        // return the ortsh url
        return 'http://ort.sh/' . $ssbpOrtshKey;
    }
}

// get ortsh
function get_ortsh($hash)
{
    // global db
    global $wpdb;

    // get ssbp table name
    $table_name = $wpdb->prefix . 'ssbp_ortsh_urls';

    // check if the table exists
    $table = $wpdb->get_row("SHOW TABLES LIKE '$table_name'");

    // no table found
    if ($table === null) {
        return false;
    }

    // query db
    $ortsh = $wpdb->get_row("SELECT ortsh_key FROM $table_name WHERE hash = '$hash'");

    // no match found
    if ($ortsh === null) {
        return;
    }

    // match found, return it
    return $ortsh->ortsh_key;
}

// get ortsh
function add_ortsh($hash, $data)
{
    // global db
    global $wpdb;

    // get ssbp table name
    $table_name = $wpdb->prefix . 'ssbp_ortsh_urls';

    // insert the share
    if ($wpdb->insert($table_name, array(
        'hash' => $hash,
        'url' => $data['url'],
        'title' => $data['title'],
        'ortsh_key' => $data['ortsh_key'],
    ))
    ) {
        return true;
    }

    // failed to add otsh
    return false;
}

// shorten URL with bit.ly
function ssbp_bitly_shorten($ssbpBitlyLogin, $ssbpBitlyAPIKey, $urlLong)
{
    // try to get the bitly url
    $ssbpBitlyURL = get_option('ssbp_bl_' . md5($urlLong));

    // if this URL hasn't already been shortened
    if (!$ssbpBitlyURL) {
        // get results from bitly
        $hmtlBitly = wp_remote_get('http://api.bit.ly/v3/shorten?login=' . $ssbpBitlyLogin . '&apiKey=' . $ssbpBitlyAPIKey . '&longUrl=' . $urlLong, array('timeout' => 3));
        $arrBitly = json_decode($hmtlBitly['body'], true);

        // check a URL is returned
        if (isset($arrBitly['data']['url'])) {
            $urlShort = $arrBitly['data']['url'];
            add_option('ssbp_bl_' . md5($urlLong), $urlShort);

            return $urlShort;
        } else {
            return $urlLong;
        }
    }

    // return the bitly url
    return $ssbpBitlyURL;
}
