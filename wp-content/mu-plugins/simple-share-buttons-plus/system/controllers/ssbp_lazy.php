<?php

defined('ABSPATH') or die('No direct access permitted');

// ajax call load share buttons
function ssbp_lazy_callback()
{
    // initiate ssbp button class
    $ssbpButtons = new ssbpShareButtons();

    // the buttons!
    echo $ssbpButtons->get_ssbp_buttons($_POST['ssbpurl'], $_POST['ssbptitle'], $_POST['ssbpshorturl'], $_POST['ssbppostid']);

    // if the minimum share count has been met
    if ($ssbpButtons->ssbp_settings['counters_enabled'] == 'Y' && $ssbpButtons->ssbpShareCountData['total'] >= intval($ssbpButtons->ssbp_settings['min_shares'])) {

        // if not using custom images
        if ($ssbpButtons->ssbp_settings['custom_images'] != 'Y') {
            // add total shares here
            echo '<span class="ssbp-total-shares"><b>' . ssbp_format_number($ssbpButtons->ssbpShareCountData['total']) . '</b></span>';
        }
    }

    // exit so zero is not returned
    exit;
}

// format the returned number
function ssbp_format_number($intNumber)
{
    // if the number is greater than or equal to 1000
    if ($intNumber >= 1000) {
        // divide by 1000 and add k
        $intNumber = round(($intNumber / 1000), 1) . 'k';
    }

    // return the number
    return $intNumber;
}

// the main share buttons class
class ssbpShareButtons
{
    // declare variables
    public $htmlShareButtons = '';
    public $booShowShareCount = false;
    public $strPageTitle;
    public $urlCurrentPage;
    public $old_counts;

    // count variables
    public $ssbpShareCountData = array();
    public $ssbpTotalShareCount = 0;
    public $ssbpfacebookShareCount = 0;
    public $ssbpgoogleShareCount = 0;
    public $ssbplinkedinShareCount = 0;
    public $ssbppinterestShareCount = 0;
    public $ssbpredditShareCount = 0;
    public $ssbpstumbleuponShareCount = 0;
    public $ssbpvkShareCount = 0;

    // construct buttons function
    public function __construct($url = null)
    {
        // get ssbp settings
        $this->ssbp_settings = get_ssbp_settings();

        // explode saved include list and add to a new array
        $this->arrSelectedSSBP = explode(',', $this->ssbp_settings['selected_buttons']);

        // if a url is set then use it
        if ($url !== null) {
            $this->urlCurrentPage = $url;
        }
    }

    // the buttons themselves
    public function get_ssbp_buttons($urlCurrentPage, $strPageTitle, $urlShortened = '', $post_id = null, $all = false, $counters = true)
    {
        // variables
        $this->intPostID = $post_id;
        $this->strPageTitle = $strPageTitle;
        $this->urlCurrentPage = $urlCurrentPage;
        $this->urlShortened = $urlShortened;
        $this->strShareText = stripslashes_deep($this->ssbp_settings['share_text']);

        // if share counts are enabled then get them
        if ($counters && $this->ssbp_settings['counters_enabled'] == 'Y') {
            $this->get_ssbp_share_counts();
        } else {
            $this->ssbp_settings['counters_enabled'] = false;
        }

        // share text is above add it now
        if (in_array($this->ssbp_settings['text_placement'], array('above', 'left')) && $this->strShareText != '') {
            // add share text
            $this->htmlShareButtons .= '<span class="ssbp-share-text">' . stripslashes_deep($this->strShareText) . '</span>';

            // if above add line break
            if ($this->ssbp_settings['text_placement'] == 'above') {
                $this->htmlShareButtons .= '<br/>';
            }
        }

        // check if array is not empty
        if ($this->ssbp_settings['selected_buttons'] != '') {
            // explode saved include list and add to a new array
            $this->arrSelectedSSBP = explode(',', $this->ssbp_settings['selected_buttons']);

            // if getting all buttons
            if ($all) {
                // fetch from option
                $this->arrSelectedSSBP = array_keys(json_decode(get_option('ssbp_buttons'), true));
            }

            // start the list
            $this->htmlShareButtons .= '<ul class="ssbp-list">';

            // for each included button
            foreach ($this->arrSelectedSSBP as $strSelected) {
                // new list item
                $this->htmlShareButtons .= '<li class="ssbp-li--' . $strSelected . '">';

                // prepare function name
                $strGetButton = 'ssbp_' . $strSelected;

                // add a button for each selected
                $this->htmlShareButtons .= $this->$strGetButton();

                // new list item
                $this->htmlShareButtons .= '</li>';
            }

            // if using custom images
            if ($this->ssbp_settings['custom_images'] == 'Y') {
                // add total count as a list item
                $this->htmlShareButtons .= '<li><span class="ssbp-total-shares">' . $this->ssbpShareCountData['total'] . '</span></li>';
            }

            // close the list
            $this->htmlShareButtons .= '</ul>';
        }

        // link option
        $this->htmlShareButtons .= '<div class="ssbp-input-url-div">';
        $this->htmlShareButtons .= '<input class="ssbp-input-url" type="text" value="' . ($urlShortened != '' ? $urlShortened : $urlCurrentPage) . '" />';
        $this->htmlShareButtons .= '</div>';

        // share text is right or below
        if (in_array($this->ssbp_settings['text_placement'], array('right', 'below')) && $this->strShareText != '') {
            // if above add line break
            if ($this->ssbp_settings['text_placement'] == 'below') {
                $this->htmlShareButtons .= '<br/>';
            }

            // add share text
            $this->htmlShareButtons .= '<span class="ssbp-share-text">' . stripslashes_deep($this->strShareText) . '</span>';
        }

        // return share buttons
        return $this->htmlShareButtons;
    }

    // get url's share count data
    public function get_url_share_counts($hash)
    {
        // start a session in case one hasn't been for any reason
        @session_start();

        // check if already performing this
        if (isset($_SESSION['ssbp_' . $hash])) {
            // unset session var
            unset($_SESSION['ssbp_' . $hash]);

            // no need to continue
            return true;
        }

        // set a unique session var to overcome race condition
        @$_SESSION['ssbp_' . $hash] == true;

        // global db
        global $wpdb;

        // get ssbp table name
        $table_name = $wpdb->prefix . 'ssbp_share_counts';

        // query db
        $counts = $wpdb->get_row("SELECT data, expires FROM $table_name WHERE hash = '$hash'");

        // no match found, go get share counts
        if ($counts === null) {
            return false;
        }

        // decode share counts
        $this->old_counts = $this->ssbpShareCountData = json_decode($counts->data, true);

        // date and time now
        $now = date('Y-m-d H:i:s');

        // check if the share counts have expired
        if ($now > $counts->expires) {
            // delete the record
            $wpdb->delete($table_name, array('hash' => $hash));

            // return false so that fresh counts are retrieved
            return false;
        }

        // match found
        return true;
    }

    // share counts function
    public function get_ssbp_share_counts()
    {
        // hash the url
        $hash = md5($this->urlCurrentPage);

        // check if there's a share count record for this url
        $counts = $this->get_url_share_counts($hash);

        // if no counts were found
        if (! $counts) {
            // an array of networks with share counts
            $hasCounts = array(
                'facebook', 'google', 'linkedin', 'pinterest', 'reddit', 'stumbleupon', 'tumblr', 'vk', 'yummly',
            );

            // if user has newsharecounts.com enabled
            if ($this->ssbp_settings['twitter_newsharecounts'] == 'Y') {
                // add twitter to the hascounts array
                array_push($hasCounts, 'twitter');
            }

            // create empty total
            $total = 0;

            // loop through each selected button
            foreach ($this->arrSelectedSSBP as $strSelected) {
                // if this button has a share count
                if (in_array($strSelected, $hasCounts)) {
                    // prepare class function and name
                    $strGetCount = 'ssbp_' . $strSelected . '_count';
                    $strThisCount = 'ssbp' . $strSelected . 'ShareCount';

                    // get the count
                    $this->$strGetCount($this->urlCurrentPage);

                    // if the old count was higher
                    if ($this->old_counts[$strSelected] > $this->$strThisCount) {
                        // stick with the old count
                        $this->$strThisCount = $this->old_counts[$strSelected];
                    }

                    // add to total share count
                    $total = $total + $this->$strThisCount;

                    // add to share count data array
                    $this->ssbpShareCountData[$strSelected] = $this->$strThisCount;
                }
            }

            // add total shares to the array
            $this->ssbpShareCountData['total'] = $total;

            // global db
            global $wpdb;

            // get ssbp table name
            $table_name = $wpdb->prefix . 'ssbp_share_counts';

            // typecast share count cache
            $count_cache = (int)$this->ssbp_settings['count_cache'];

            // if count cache is less than 10 minutes
            $count_cache = ($count_cache < 600 ? 600 : $count_cache);

            // prepare expiry
            $expiry = time() + $count_cache;

            // insert the share counts
            $wpdb->insert($table_name, array(
                'hash' => $hash,
                'data' => json_encode($this->ssbpShareCountData),
                'expires' => date('Y-m-d H:i:s', $expiry),
            ));
        }
    }

    // get buffer button
    public function ssbp_buffer()
    {
        // buffer share link
        $this->htmlShareButtons .= '<a href="https://bufferapp.com/add?url=' . $this->urlCurrentPage . '&amp;text=' . ($this->ssbp_settings['buffer_text'] != '' ? $this->ssbp_settings['buffer_text'] : null) . ' ' . $this->strPageTitle . '" class="ssbp-btn ssbp-buffer" ' . ($this->ssbp_settings['rel_nofollow'] == 'Y' ? 'rel="nofollow"' : null) . ' data-ssbp-title="' . $this->strPageTitle . '" data-ssbp-url="' . $this->urlCurrentPage . '" data-ssbp-site="Buffer" ' . ($this->ssbp_settings['ga_onclick'] == 'Y' ? 'onclick="ssbpTrackGA(\'' . $this->urlCurrentPage . '\', \'Buffer\'); return false;"' : null) . '>';

        // not using custom images
        if ($this->ssbp_settings['custom_images'] != 'Y') {
            $this->htmlShareButtons .= '<span class="ssbp-text">Buffer</span>';
        } else {// using custom images
            $this->htmlShareButtons .= '<img src="' . $this->ssbp_settings['custom_buffer'] . '" title="Buffer this page" class="ssbp" alt="Buffer this page" />';
        }

        // close link
        $this->htmlShareButtons .= '</a>';
    }

    // get diggit button
    public function ssbp_diggit()
    {
        // diggit share link
        $this->htmlShareButtons .= '<a href="http://www.digg.com/submit?url=' . $this->urlCurrentPage . '" class="ssbp-btn ssbp-diggit" ' . ($this->ssbp_settings['rel_nofollow'] == 'Y' ? 'rel="nofollow"' : null) . ' data-ssbp-title="' . $this->strPageTitle . '" data-ssbp-url="' . $this->urlCurrentPage . '" data-ssbp-site="Digg" ' . ($this->ssbp_settings['ga_onclick'] == 'Y' ? 'onclick="ssbpTrackGA(\'' . $this->urlCurrentPage . '\', \'Digg\'); return false;"' : null) . '>';

        // not using custom images
        if ($this->ssbp_settings['custom_images'] != 'Y') {
            $this->htmlShareButtons .= '<span class="ssbp-text">Diggit</span>';
        } else {// using custom images
            $this->htmlShareButtons .= '<img src="' . $this->ssbp_settings['custom_diggit'] . '" title="Digg this page" class="ssbp" alt="Digg this page" />';
        }

        // close link
        $this->htmlShareButtons .= '</a>';
    }

    // get email button
    public function ssbp_email()
    {
        // replace ampersands with %26
        $this->strPageTitle = str_replace('&', '%26', $this->strPageTitle);
        $this->ssbp_settings['email_message'] = str_replace('&', '%26', $this->ssbp_settings['email_message']);

        // extra class var defined
        $class = '';

        // prepare email popup class if needed
        if ($this->ssbp_settings['email_popup'] == 'Y') {
            $class = ' ssbp-email-popup';
        }

        // email share link
        $this->htmlShareButtons .= '<a href="mailto:?Subject=' . $this->strPageTitle . '&amp;Body=' . $this->ssbp_settings['email_message'] . '%20' . ($this->urlShortened == '' ? $this->urlCurrentPage : $this->urlShortened) . '" class="ssbp-btn ssbp-email'.$class.'" data-ssbp-title="' . $this->strPageTitle . '" data-ssbp-url="' . $this->urlCurrentPage . '" data-ssbp-site="Email" ' . ($this->ssbp_settings['ga_onclick'] == 'Y' ? 'onclick="ssbpTrackGA(\'' . $this->urlCurrentPage . '\', \'Email\'); return false;"' : null) . '>';

        // not using custom images
        if ($this->ssbp_settings['custom_images'] != 'Y') {
            $this->htmlShareButtons .= '<span class="ssbp-text">Email</span>';
        } else {// using custom images
            $this->htmlShareButtons .= '<img src="' . $this->ssbp_settings['custom_email'] . '" title="Email this page" class="ssbp" alt="Email this page" />';
        }

        // close link
        $this->htmlShareButtons .= '</a>';
    }

    public function ssbp_ellipsis()
    {
        // ellipsis share link
        $this->htmlShareButtons .= '<a href="javascript:;" class="ssbp-btn ssbp-ellipsis" data-ssbp-title="' . $this->strPageTitle . '" data-ssbp-url="' . $this->urlCurrentPage . '" data-ssbp-site="Ellipsis">';

        // not using custom images
        if ($this->ssbp_settings['custom_images'] != 'Y') {
            $this->htmlShareButtons .= '<span class="ssbp-text">More</span>';
        } else {// using custom images
            $this->htmlShareButtons .= '<img src="' . $this->ssbp_settings['custom_ellipsis'] . '" title="Show more share options" class="ssbp" alt="Show more share options" />';
        }

        // close link
        $this->htmlShareButtons .= '</a>';
    }

    // get facebook button
    public function ssbp_facebook()
    {
        // declare variable
        $facebook_share_count = '';

        // if share counts are enabled
        if ($this->ssbp_settings['counters_enabled'] == 'Y') {
            // if the min count has been met or exceeded
            if ((int)$this->ssbpShareCountData['facebook'] >= intval($this->ssbp_settings['min_shares'])) {
                $facebook_share_count = sprintf(
                    '<span class="%s" data-url="%s">%s</span>',
                    'ssbp-total-facebook-shares ssbp-each-share',
                    esc_url( $this->urlCurrentPage ),
                    ssbp_format_number( $this->ssbpShareCountData['facebook'] )
                );
                wp_enqueue_script( 'ssbp-facebook' );
            }
        }

        // if there's a facebook app id
        if ($this->ssbp_settings['facebook_app_id'] != '') {
            // facebook share link
            $facebook_link = '<a data-site="" data-facebook="mobile" class="ssbp-btn ssbp-facebook ssbp-facebook--standard" href="https://www.facebook.com/dialog/share?app_id='.$this->ssbp_settings['facebook_app_id'].'&display=popup&href='.$this->urlCurrentPage.'&redirect_uri='.$this->urlCurrentPage.'">';
        } else {
            // facebook share link
            $facebook_link = '<a href="http://www.facebook.com/sharer.php?u=' . $this->urlCurrentPage . '" class="ssbp-btn ssbp-facebook ssbp-facebook--standard" ' .
            $facebook_link .= ($this->ssbp_settings['rel_nofollow'] == 'Y' ? ' rel="nofollow"' : null) . ' data-ssbp-title="' . $this->strPageTitle . '" data-ssbp-url="' . $this->urlCurrentPage . '" data-ssbp-site="Facebook" ' . ($this->ssbp_settings['ga_onclick'] == 'Y' ? 'onclick="ssbpTrackGA(\'' . $this->urlCurrentPage . '\', \'Facebook\'); return false;"' : null) . '>';
        }

        // not using custom images
        if ($this->ssbp_settings['custom_images'] != 'Y') {
            $facebook_button = '<span class="ssbp-text">Facebook</span>';
        } else {// using custom images
            $facebook_button = '<img src="' . $this->ssbp_settings['custom_facebook'] . '" title="Share this on Facebook" class="ssbp" alt="Share this on Facebook" />';
        }

        // put button together
        $facebook_buttons = $facebook_link.$facebook_button.'</a>'.$facebook_share_count;

        // add facebook buttons
        $this->htmlShareButtons .= $facebook_buttons;
    }

    // get facebook button
    function ssbp_facebook_save() {
        // add facebook save button
        // return '<div class="fb-save" style="display:inline-block" data-uri="'.$this->urlCurrentPage.'"></div>';
    }

    // get facebook share counts
    public function ssbp_facebook_count()
    {
        // get results from facebook and return the number of shares
        $fb_resp = wp_remote_get(
            'http://graph.facebook.com/' . $this->urlCurrentPage,
            array( 'timeout' => $this->ssbp_settings['count_timeout'] )
        );

        $count = 0;

        // if no error
        if ( ! is_wp_error( $fb_resp ) ) {
            $json = json_decode( $fb_resp['body'], true );
            if ( isset( $json['share']['share_count'] ) ) {
                $count = $json['share']['share_count'];
                set_transient( 'ssbp_fb_cache_' . $this->intPostID, $count, DAY_IN_SECONDS );
            }
        } elseif ( $cached = get_transient( 'ssbp_fb_cache_' . $this->intPostID ) ) {
            $count = (int) $cached;
        }
        $this->ssbpfacebookShareCount = $count;

        // return the count
        return $this->ssbpfacebookShareCount;
    }

    // get flattr button
    public function ssbp_flattr()
    {
        // check for dedicated flattr URL
        if ($this->ssbp_settings['flattr_url'] != '') {
            // update url to specified URL
            $this->urlCurrentPage = $this->ssbp_settings['flattr_url'];
        }

        // flattr share link
        $this->htmlShareButtons .= '<a href="https://flattr.com/submit/auto?user_id=' . $this->ssbp_settings['flattr_user_id'] . '&amp;title=' . $this->strPageTitle . '&amp;url=' . $this->urlCurrentPage . '" class="ssbp-btn ssbp-flattr" ' . ($this->ssbp_settings['rel_nofollow'] == 'Y' ? 'rel="nofollow"' : null) . ' data-ssbp-title="' . $this->strPageTitle . '" data-ssbp-url="' . $this->urlCurrentPage . '" data-ssbp-site="Flattr" ' . ($this->ssbp_settings['ga_onclick'] == 'Y' ? 'onclick="ssbpTrackGA(\'' . $this->urlCurrentPage . '\', \'Flattr\'); return false;"' : null) . '>';

        // not using custom images
        if ($this->ssbp_settings['custom_images'] != 'Y') {
            $this->htmlShareButtons .= '<span class="ssbp-text">Flattr</span>';
        } else {// using custom images
            $this->htmlShareButtons .= '<img src="' . $this->ssbp_settings['custom_flattr'] . '" title="Flattr this" class="ssbp" alt="Flattr this" />';
        }

        // close link
        $this->htmlShareButtons .= '</a>';
    }

    // get google+ button
    public function ssbp_google()
    {
        // google share link
        $this->htmlShareButtons .= '<a href="https://plus.google.com/share?url=' . ($this->urlShortened == '' ? $this->urlCurrentPage : $this->urlShortened) . '" class="ssbp-btn ssbp-google" ' . ($this->ssbp_settings['rel_nofollow'] == 'Y' ? 'rel="nofollow"' : null) . ' data-ssbp-title="' . $this->strPageTitle . '" data-ssbp-url="' . $this->urlCurrentPage . '" data-ssbp-site="Google+" ' . ($this->ssbp_settings['ga_onclick'] == 'Y' ? 'onclick="ssbpTrackGA(\'' . $this->urlCurrentPage . '\', \'Google+\'); return false;"' : null) . '>';

        // not using custom images
        if ($this->ssbp_settings['custom_images'] != 'Y') {
            $this->htmlShareButtons .= '<span class="ssbp-text">Google+</span>';
        } else {// using custom images
            $this->htmlShareButtons .= '<img src="' . $this->ssbp_settings['custom_google'] . '" title="Share this on Google+" class="ssbp" alt="Share this on Google+" />';
        }

        // close link
        $this->htmlShareButtons .= '</a>';

        // if share counts are enabled
        if ($this->ssbp_settings['counters_enabled'] == 'Y') {

            // if the min count has been met or exceeded
            if ((int)$this->ssbpShareCountData['google'] >= intval($this->ssbp_settings['min_shares'])) {
                $this->htmlShareButtons .= '<span class="ssbp-total-google-shares ssbp-each-share">' . ssbp_format_number($this->ssbpShareCountData['google']) . '</span>';
            }
        }
    }

    // get google+ count
    public function ssbp_google_count()
    {
        // prepare data for post
        $args = array(
            'method' => 'POST',
            'headers' => array(
                // setup content type to JSON
                'Content-Type' => 'application/json',
            ),
            // setup POST options to Google API
            'body' => json_encode(array(
                'method' => 'pos.plusones.get',
                'id' => 'p',
                'method' => 'pos.plusones.get',
                'jsonrpc' => '2.0',
                'key' => 'p',
                'apiVersion' => 'v1',
                'params' => array(
                    'nolog' => true,
                    'id' => $this->urlCurrentPage,
                    'source' => 'widget',
                    'userId' => '@viewer',
                    'groupId' => '@self',
                ),
            )),
            // disable checking SSL sertificates
            'sslverify' => false,
        );

        // retrieves JSON with HTTP POST method for current URL
        $json_string = wp_remote_post('https://clients6.google.com/rpc', $args);

        // if there was an error
        if (is_wp_error($json_string)) {
            // zero if response is error
            $this->ssbpgoogleShareCount = 0;

            // return
            return;
        }

        // decode result
        $json = json_decode($json_string['body'], true);

        // return count of Google +1 for requsted URL
        $this->ssbpgoogleShareCount = intval($json['result']['metadata']['globalCounts']['count']);

        // return
        return;
    }

    // get linkedin button
    public function ssbp_linkedin()
    {
        // linkedin share link
        $this->htmlShareButtons .= '<a href="http://www.linkedin.com/shareArticle?mini=true&amp;url=' . ($this->urlShortened == '' ? $this->urlCurrentPage : $this->urlShortened) . '" class="ssbp-btn ssbp-linkedin" ' . ($this->ssbp_settings['rel_nofollow'] == 'Y' ? 'rel="nofollow"' : null) . ' data-ssbp-title="' . $this->strPageTitle . '" data-ssbp-url="' . $this->urlCurrentPage . '" data-ssbp-site="LinkedIn" ' . ($this->ssbp_settings['ga_onclick'] == 'Y' ? 'onclick="ssbpTrackGA(\'' . $this->urlCurrentPage . '\', \'LinkedIn\'); return false;"' : null) . '>';

        // not using custom images
        if ($this->ssbp_settings['custom_images'] != 'Y') {
            $this->htmlShareButtons .= '<span class="ssbp-text">Linkedin</span>';
        } else {// using custom images
            $this->htmlShareButtons .= '<img src="' . $this->ssbp_settings['custom_linkedin'] . '" title="Share this on Linkedin" class="ssbp" alt="Share this on Linkedin" />';
        }

        // close link
        $this->htmlShareButtons .= '</a>';

        // if share counts are enabled
        if ($this->ssbp_settings['counters_enabled'] == 'Y') {

            // if the min count has been met or exceeded
            if ((int)$this->ssbpShareCountData['linkedin'] >= intval($this->ssbp_settings['min_shares'])) {
                $this->htmlShareButtons .= '<span class="ssbp-total-linkedin-shares ssbp-each-share">' . ssbp_format_number($this->ssbpShareCountData['linkedin']) . '</span>';
            }
        }
    }

    // get linkedin count
    public function ssbp_linkedin_count()
    {
        // get results from linkedin and return the number of shares
        $htmlLinkedinShareDetails = wp_remote_get('http://www.linkedin.com/countserv/count/share?url=' . $this->urlCurrentPage, array('timeout' => $this->ssbp_settings['count_timeout']));

        // if there was an error
        if (is_wp_error($htmlLinkedinShareDetails)) {
            // set share count
            $this->ssbplinkedinShareCount = 0;

            // return
            return;
        }

        // extract/decode share count
        $htmlLinkedinShareDetails = str_replace('IN.Tags.Share.handleCount(', '', $htmlLinkedinShareDetails);
        $htmlLinkedinShareDetails = str_replace(');', '', $htmlLinkedinShareDetails);
        $arrLinkedinShareDetails = json_decode($htmlLinkedinShareDetails['body'], true);
        $intLinkedinShareCount = $arrLinkedinShareDetails['count'];
        $this->ssbplinkedinShareCount = ($intLinkedinShareCount) ? $intLinkedinShareCount : '0';

        // return
        return;
    }

    // get pinterest button
    public function ssbp_pinterest()
    {
        // if using ssbp meta or featured images for pinning
        if ($this->ssbp_settings['pinterest_use_featured'] || $this->ssbp_settings['pinterest_use_ssbp_meta'] == 'Y') {
            // using featured images
            if ($this->ssbp_settings['pinterest_use_featured'] == 'Y') {
                // get the featured image
                $strSSBPImage = wp_get_attachment_image_src(get_post_thumbnail_id($this->intPostID), 'full');
                $strSSBPImage = $strSSBPImage[0];

                // use the title for the description
                $strSSBPDescription = $this->strPageTitle;
            } // using ssbp meta
            else {
                // if a custom share description had been set, use the custom share description
                if (get_post_meta($this->intPostID, '_ssbp_meta_description', true)) {
                    $strSSBPDescription = get_post_meta($this->intPostID, '_ssbp_meta_description', true);
                } else {// or use the default set in SSBP admin
                    $strSSBPDescription = $this->ssbp_settings['meta_description'];
                }

                // if a custom share image is set, use the custom share image
                if (get_post_meta($this->intPostID, '_ssbp_meta_image', true)) {
                    $strSSBPImage = get_post_meta($this->intPostID, '_ssbp_meta_image', true);
                } else {// or use the default set in SSBP admin
                    $strSSBPImage = $this->ssbp_settings['meta_image'];
                }
            }

            // pinterest share link
            $this->htmlShareButtons .= '<a href="http://pinterest.com/pin/create/bookmarklet/?is_video=false&url=' . $this->urlCurrentPage . '&media=' . $strSSBPImage . '&description=' . $strSSBPDescription . '" class="ssbp-btn ssbp-pinterest" ' . ($this->ssbp_settings['rel_nofollow'] == 'Y' ? 'rel="nofollow"' : null) . ' data-ssbp-title="' . $this->strPageTitle . '" data-ssbp-url="' . $this->urlCurrentPage . '" data-ssbp-site="Pinterest_force" ' . ($this->ssbp_settings['ga_onclick'] == 'Y' ? 'onclick="ssbpTrackGA(\'' . $this->urlCurrentPage . '\', \'Pinterest\'); return false;"' : null) . '>';
        } // using all available images approach
        else {
            // pinterest share link
            $this->htmlShareButtons .= "<a href='javascript:void((function()%7Bvar%20e=document.createElement(&apos;script&apos;);e.setAttribute(&apos;type&apos;,&apos;text/javascript&apos;);e.setAttribute(&apos;charset&apos;,&apos;UTF-8&apos;);e.setAttribute(&apos;src&apos;,&apos;//assets.pinterest.com/js/pinmarklet.js?r=&apos;+Math.random()*99999999);document.body.appendChild(e)%7D)());' class='ssbp-btn ssbp-pinterest' data-ssbp-title='" . $this->strPageTitle . "' data-ssbp-url=" . $this->urlCurrentPage . " data-ssbp-site='Pinterest'" . ($this->ssbp_settings['ga_onclick'] == 'Y' ? 'onclick="ssbpTrackGA(\'' . $this->urlCurrentPage . '\', \'Pinterest\'); return false;"' : null) . '>';
        }

        // not using custom images
        if ($this->ssbp_settings['custom_images'] != 'Y') {
            $this->htmlShareButtons .= '<span class="ssbp-text">Pinterest</span>';
        } else {// using custom images
            $this->htmlShareButtons .= '<img src="' . $this->ssbp_settings['custom_pinterest'] . '" title="Pin this page" class="ssbp" alt="Pin this page" />';
        }

        // close link
        $this->htmlShareButtons .= '</a>';

        // if share counts are enabled
        if ($this->ssbp_settings['counters_enabled'] == 'Y') {

            // if the min count has been met or exceeded
            if ((int)$this->ssbpShareCountData['pinterest'] >= intval($this->ssbp_settings['min_shares'])) {
                $this->htmlShareButtons .= '<span class="ssbp-total-pinterest-shares ssbp-each-share">' . ssbp_format_number($this->ssbpShareCountData['pinterest']) . '</span>';
            }
        }
    }

    // get pinterest share count
    public function ssbp_pinterest_count($urlCurrentPage)
    {
        // get results from pinterest
        $htmlPinterestShareDetails = wp_remote_get('http://api.pinterest.com/v1/urls/count.json?url=' . $urlCurrentPage, array('timeout' => $this->ssbp_settings['count_timeout']));

        // check there was an error
        if (is_wp_error($htmlPinterestShareDetails)) {
            // set share count
            $this->ssbppinterestShareCount = 0;

            return;
        }

        // decode data
        $htmlPinterestShareDetails = str_replace('receiveCount(', '', $htmlPinterestShareDetails);
        $htmlPinterestShareDetails = str_replace(')', '', $htmlPinterestShareDetails);
        $arrPinterestShareDetails = json_decode($htmlPinterestShareDetails['body'], true);
        $intPinterestShareCount = $arrPinterestShareDetails['count'];
        $this->ssbppinterestShareCount = ($intPinterestShareCount) ? $intPinterestShareCount : '0';

        // return
        return;
    }

    // get print button
    public function ssbp_print()
    {

        // linkedin share link
        $this->htmlShareButtons .= '<a href="#" class="ssbp-btn ssbp-print" onclick="window.print()" data-ssbp-title="' . $this->strPageTitle . '" data-ssbp-url="' . $this->urlCurrentPage . '" data-ssbp-site="Print">';

        // not using custom images
        if ($this->ssbp_settings['custom_images'] != 'Y') {
            $this->htmlShareButtons .= '<span class="ssbp-text">Print</span>';
        } else {// using custom images
            $this->htmlShareButtons .= '<img src="' . $this->ssbp_settings['custom_print'] . '" title="Print this page" class="ssbp" alt="Print this page" />';
        }

        // close link
        $this->htmlShareButtons .= '</a>';
    }

    // get reddit button
    public function ssbp_reddit()
    {

        // reddit share link
        $this->htmlShareButtons .= '<a href="http://reddit.com/submit?url=' . $this->urlCurrentPage . '&amp;title=' . $this->strPageTitle . '" class="ssbp-btn ssbp-reddit" ' . ($this->ssbp_settings['rel_nofollow'] == 'Y' ? 'rel="nofollow"' : null) . ' data-ssbp-title="' . $this->strPageTitle . '" data-ssbp-url="' . $this->urlCurrentPage . '" data-ssbp-site="Reddit" ' . ($this->ssbp_settings['ga_onclick'] == 'Y' ? 'onclick="ssbpTrackGA(\'' . $this->urlCurrentPage . '\', \'Reddit\'); return false;"' : null) . '>';

        // not using custom images
        if ($this->ssbp_settings['custom_images'] != 'Y') {
            $this->htmlShareButtons .= '<span class="ssbp-text">Reddit</span>';
        } else {// using custom images
            $this->htmlShareButtons .= '<img src="' . $this->ssbp_settings['custom_reddit'] . '" title="Share this on Reddit" class="ssbp" alt="Share this on Reddit" />';
        }

        // close link
        $this->htmlShareButtons .= '</a>';

        // if share counts are enabled
        if ($this->ssbp_settings['counters_enabled'] == 'Y') {

            // if the min count has been met or exceeded
            if ((int)$this->ssbpShareCountData['reddit'] >= intval($this->ssbp_settings['min_shares'])) {
                $this->htmlShareButtons .= '<span class="ssbp-total-reddit-shares ssbp-each-share">' . ssbp_format_number($this->ssbpShareCountData['reddit']) . '</span>';
            }
        }
    }

    // get reddit count
    public function ssbp_reddit_count()
    {
        // get results from reddit and return the number of shares
        $htmlRedditShareDetails = wp_remote_get('http://www.reddit.com/api/info.json?url=' . $this->urlCurrentPage, array('timeout' => $this->ssbp_settings['count_timeout']));

        // check there was an error
        if (is_wp_error($htmlRedditShareDetails)) {
            // set share count
            $this->ssbpredditShareCount = 0;

            // return
            return;
        }

        // decode and get share count
        $arrRedditResult = json_decode($htmlRedditShareDetails['body'], true);
        $intRedditShareCount = (isset($arrRedditResult['data']['children']['0']['data']['score']) ? $arrRedditResult['data']['children']['0']['data']['score'] : 0);
        $this->ssbpredditShareCount = ($intRedditShareCount) ? $intRedditShareCount : '0';

        // return
        return;
    }

    // get stumbleupon button
    public function ssbp_stumbleupon()
    {

        // stumbleupon share link
        $this->htmlShareButtons .= '<a href="http://www.stumbleupon.com/submit?url=' . $this->urlCurrentPage . '&amp;title=' . $this->strPageTitle . '" class="ssbp-btn ssbp-stumbleupon" ' . ($this->ssbp_settings['rel_nofollow'] == 'Y' ? 'rel="nofollow"' : null) . ' data-ssbp-title="' . $this->strPageTitle . '" data-ssbp-url="' . $this->urlCurrentPage . '" data-ssbp-site="StumbleUpon" ' . ($this->ssbp_settings['ga_onclick'] == 'Y' ? 'onclick="ssbpTrackGA(\'' . $this->urlCurrentPage . '\', \'StumbleUpon\'); return false;"' : null) . '>';

        // not using custom images
        if ($this->ssbp_settings['custom_images'] != 'Y') {
            $this->htmlShareButtons .= '<span class="ssbp-text">Stumble</span>';
        } else {// using custom images
            $this->htmlShareButtons .= '<img src="' . $this->ssbp_settings['custom_stumbleupon'] . '" title="StumbleUpon this page" class="ssbp" alt="StumbleUpon this page" />';
        }

        // close link
        $this->htmlShareButtons .= '</a>';

        // if share counts are enabled
        if ($this->ssbp_settings['counters_enabled'] == 'Y') {

            // if the min count has been met or exceeded
            if ((int)$this->ssbpShareCountData['stumbleupon'] >= intval($this->ssbp_settings['min_shares'])) {
                $this->htmlShareButtons .= '<span class="ssbp-total-stumbleupon-shares ssbp-each-share">' . ssbp_format_number($this->ssbpShareCountData['stumbleupon']) . '</span>';
            }
        }
    }

    // get stumbleupon share count
    public function ssbp_stumbleupon_count()
    {
        // get results from stumbleupon and return the number of shares
        $htmlStumbleUponShareDetails = wp_remote_get('http://www.stumbleupon.com/services/1.01/badge.getinfo?url=' . $this->urlCurrentPage, array('timeout' => $this->ssbp_settings['count_timeout']));

        // check there was an error
        if (is_wp_error($htmlStumbleUponShareDetails)) {
            // set share count
            $this->ssbpstumbleuponShareCount = 0;

            // return
            return;
        }

        // decode data
        $arrStumbleUponResult = json_decode($htmlStumbleUponShareDetails['body'], true);
        $intStumbleUponShareCount = (isset($arrStumbleUponResult['result']['views']) ? $arrStumbleUponResult['result']['views'] : 0);
        $this->ssbpstumbleuponShareCount = ($intStumbleUponShareCount) ? $intStumbleUponShareCount : '0';

        // return
        return;
    }

    // get tumblr button
    public function ssbp_tumblr()
    {
        // tumblr share link
        $this->htmlShareButtons .= '<a href="http://www.tumblr.com/share/link?url='.$this->urlCurrentPage.'" class="ssbp-btn ssbp-tumblr" ' . ($this->ssbp_settings['rel_nofollow'] == 'Y' ? 'rel="nofollow"' : null) . ' data-ssbp-title="' . $this->strPageTitle . '" data-ssbp-url="' . $this->urlCurrentPage . '" data-ssbp-site="Tumblr" ' . ($this->ssbp_settings['ga_onclick'] == 'Y' ? 'onclick="ssbpTrackGA(\'' . $this->urlCurrentPage . '\', \'Tumblr\'); return false;"' : null) . '>';

        // not using custom images
        if ($this->ssbp_settings['custom_images'] != 'Y') {
            $this->htmlShareButtons .= '<span class="ssbp-text">tumblr</span>';
        } else {// using custom images
            $this->htmlShareButtons .= '<img src="' . $this->ssbp_settings['custom_tumblr'] . '" title="Share this on tumblr" class="ssbp" alt="Share this on tumblr" />';
        }

        // close link
        $this->htmlShareButtons .= '</a>';

        // if share counts are enabled
        if ($this->ssbp_settings['counters_enabled'] == 'Y') {

            // if the min count has been met or exceeded
            if ((int)$this->ssbpShareCountData['tumblr'] >= intval($this->ssbp_settings['min_shares'])) {
                $this->htmlShareButtons .= '<span class="ssbp-total-tumblr-shares ssbp-each-share">' . ssbp_format_number($this->ssbpShareCountData['tumblr']) . '</span>';
            }
        }
    }

    // get tumblr share count
    public function ssbp_tumblr_count()
    {
        // get results from tumblr and return the number of shares
        $htmlTumblrShareDetails = wp_remote_get('http://api.tumblr.com/v2/share/stats?url=' . $this->urlCurrentPage, array('timeout' => $this->ssbp_settings['count_timeout']));

        // check there was an error
        if (is_wp_error($htmlTumblrShareDetails)) {
            // set share count
            $this->ssbptumblrShareCount = 0;

            // return
            return;
        }

        // decode data
        $arrTumblrResult = json_decode($htmlTumblrShareDetails['body'], true);
        $intTumblrShareCount = (isset($arrTumblrResult['response']['note_count']) ? $arrTumblrResult['response']['note_count'] : 0);
        $this->ssbptumblrShareCount = ($intTumblrShareCount) ? $intTumblrShareCount : '0';

        // return
        return;
    }

    // get twitter button
    public function ssbp_twitter()
    {
        // format the URL into friendly code
        $twitterShareText = urlencode(html_entity_decode($this->strPageTitle . ' ' . $this->ssbp_settings['twitter_text'], ENT_COMPAT, 'UTF-8'));

        // set via if set
        $ssbpTwitterVia = ($this->ssbp_settings['twitter_username'] != '' ? '&amp;via=' . $this->ssbp_settings['twitter_username'] : null);

        // twitter share link
        $twitterLink = '<a href="https://twitter.com/share?url=' . ($this->urlShortened == '' ? $this->urlCurrentPage : $this->urlShortened) . '&amp;text=' . $twitterShareText . '&amp;hashtags=' . $this->ssbp_settings['twitter_tags'] . $ssbpTwitterVia . '" class="ssbp-btn ssbp-twitter ssbp-twitter--standard"' . ($this->ssbp_settings['rel_nofollow'] == 'Y' ? 'rel="nofollow"' : null) .
        ' data-ssbp-title="' . $this->strPageTitle . '" data-ssbp-url="' . $this->urlCurrentPage . '" data-ssbp-site="Twitter" ' . ($this->ssbp_settings['ga_onclick'] == 'Y' ? 'onclick="ssbpTrackGA(\'' . $this->urlCurrentPage . '\', \'Twitter\'); return false;"' : null) . '>';

        // not using custom images
        if ($this->ssbp_settings['custom_images'] != 'Y') {
            $twitterButton = '<span class="ssbp-text">Twitter</span>';
        } else {// using custom images
            $twitterButton = '<img src="' . $this->ssbp_settings['custom_twitter'] . '" title="Tweet about this" class="ssbp" alt="Tweet about this" />';
        }

        // put button together
        $twitterButtons = $twitterLink.$twitterButton.'</a>';

        // if share counts are enabled
        if ($this->ssbp_settings['counters_enabled'] == 'Y' && $this->ssbp_settings['twitter_newsharecounts'] == 'Y') {
            // if the min count has been met or exceeded
            if ((int)$this->ssbpShareCountData['twitter'] >= intval($this->ssbp_settings['min_shares'])) {
                $twitterButtons .= '<span class="ssbp-total-twitter-shares ssbp-each-share">' . ssbp_format_number($this->ssbpShareCountData['twitter']) . '</span>';
            }
        }

        // if native links are on for twitter
        if ($this->ssbp_settings['use_native_links'] == 'Y') {
            // format the URL into friendly code
            $twitterShareText = rawurlencode($this->strPageTitle . ' ' . $this->ssbp_settings['twitter_text']);

            // prepare native link
            $twitterLink = '<a href="twitter://post/?url=' . ($this->urlShortened == '' ? $this->urlCurrentPage : $this->urlShortened) . '&amp;text=' . $twitterShareText . '&amp;hashtags=' . $this->ssbp_settings['twitter_tags'] . $ssbpTwitterVia . '" class="ssbp-btn ssbp-twitter ssbp-twitter--native"' . ($this->ssbp_settings['rel_nofollow'] == 'Y' ? 'rel="nofollow"' : null) . ' data-ssbp-title="' . $this->strPageTitle .
            '" data-ssbp-url="' . $this->urlCurrentPage . '" data-ssbp-site="Twitter" ' . ($this->ssbp_settings['ga_onclick'] == 'Y' ? 'onclick="ssbpTrackGA(\'' . $this->urlCurrentPage . '\', \'Twitter\'); return false;"' : null) . '>';

            // close off the standard list item and start a new one
            $twitterButtons .= '</li>';
            $twitterButtons .= ' <li class="ssbp-li--twitter--native">';

            // add native twitter button
            $twitterButtons .= $twitterLink . $twitterButton . '</a>';

            // if share counts are enabled
            if ($this->ssbp_settings['counters_enabled'] == 'Y' && $this->ssbp_settings['twitter_newsharecounts'] == 'Y') {
                // if the min count has been met or exceeded
                if ((int)$this->ssbpShareCountData['twitter'] >= intval($this->ssbp_settings['min_shares'])) {
                    $twitterButtons .= '<span class="ssbp-total-twitter-shares ssbp-each-share">' . ssbp_format_number($this->ssbpShareCountData['twitter']) . '</span>';
                }
            }
        }

        // add one/two twitter share buttons
        $this->htmlShareButtons .= $twitterButtons;
    }

    // get twitter share count
    public function ssbp_twitter_count()
    {
        // get results from newsharecounts and return the number of shares
        $result = wp_remote_get(
            'http://public.newsharecounts.com/count.json?url=' . $this->urlCurrentPage,
            array( 'timeout' => $this->ssbp_settings['count_timeout'] )
        );

        // check there was an error
        if ( is_wp_error( $result ) ) {
            // set share count
            $this->ssbptwitterShareCount = 0;

            // return
            return;
        }

        // decode data
        $result = json_decode( $result['body'], true );
        $count = ( isset( $result['count'] ) ? $result['count'] : 0 );
        $this->ssbptwitterShareCount = $count;
    }

    // get vk button
    public function ssbp_vk()
    {

        // vk share link
        $this->htmlShareButtons .= '<a href="http://vkontakte.ru/share.php?url=' . $this->urlCurrentPage . '" class="ssbp-btn ssbp-vk" ' . ($this->ssbp_settings['rel_nofollow'] == 'Y' ? 'rel="nofollow"' : null) . ' data-ssbp-title="' . $this->strPageTitle . '" data-ssbp-url="' . $this->urlCurrentPage . '" data-ssbp-site="VK" ' . ($this->ssbp_settings['ga_onclick'] == 'Y' ? 'onclick="ssbpTrackGA(\'' . $this->urlCurrentPage . '\', \'VK\'); return false;"' : null) . '>';

        // not using custom images
        if ($this->ssbp_settings['custom_images'] != 'Y') {
            $this->htmlShareButtons .= '<span class="ssbp-text">VK</span>';
        } else {// using custom images
            $this->htmlShareButtons .= '<img src="' . $this->ssbp_settings['custom_vk'] . '" title="Share this on VK" class="ssbp" alt="Share this on VK" />';
        }

        // close link
        $this->htmlShareButtons .= '</a>';

        // if share counts are enabled
        if ($this->ssbp_settings['counters_enabled'] == 'Y') {

            // if the min count has been met or exceeded
            if ((int)$this->ssbpShareCountData['vk'] >= intval($this->ssbp_settings['min_shares'])) {
                $this->htmlShareButtons .= '<span class="ssbp-total-vk-shares ssbp-each-share">' . ssbp_format_number($this->ssbpShareCountData['vk']) . '</span>';
            }
        }
    }

    // get vk share count
    public function ssbp_vk_count()
    {
        // get results from vk and return the number of shares
        $htmlVKShareDetails = wp_remote_get('http://vk.com/share.php?act=count&url=' . $this->urlCurrentPage, array('timeout' => $this->ssbp_settings['count_timeout']));

        // check there was an error
        if (is_wp_error($htmlVKShareDetails)) {
            // set share count
            $this->ssbpvkShareCount = 0;

            // return
            return;
        } // share data retrieved
        else {
            // decode data
            if (!$htmlVKShareDetails['body']) {
                $this->ssbpvkShareCount = 0;
            } else {
                preg_match('/^VK.Share.count\((\d+),\s+(\d+)\);$/i', $htmlVKShareDetails['body'], $matches);
                $this->ssbpvkShareCount = $matches[2];
            }
        }

        // return
        return;
    }

    // get whatsapp button
    public function ssbp_whatsapp()
    {
        // if whatsapp shortlinks are on
        if ($this->ssbp_settings['whatsapp_shortlinks'] == 'Y') {
            $whatsapp_url = $this->urlShortened;
        } else {// use normal url
            $whatsapp_url = $this->urlCurrentPage;
        }

        // whatsapp share link
        $this->htmlShareButtons .= '<a href="whatsapp://send?text='.urlencode($whatsapp_url. ' ' . $this->strPageTitle). '" class="ssbp-btn ssbp-whatsapp" ' . ($this->ssbp_settings['rel_nofollow'] == 'Y' ? 'rel="nofollow"' : null) . ' data-ssbp-title="' . $this->strPageTitle . '" data-ssbp-url="' . $this->urlCurrentPage . '" data-ssbp-site="WhatsApp" ' . ($this->ssbp_settings['ga_onclick'] == 'Y' ? 'onclick="ssbpTrackGA(\'' . $this->urlCurrentPage . '\', \'WhatsApp\'); return false;"' : null) . '>';

        // not using custom images
        if ($this->ssbp_settings['custom_images'] != 'Y') {
            $this->htmlShareButtons .= '<span class="ssbp-text">WhatsApp</span>';
        } else {// using custom images
            $this->htmlShareButtons .= '<img src="' . $this->ssbp_settings['custom_whatsapp'] . '" title="Share this on WhatsApp" class="ssbp" alt="Share this on WhatsApp" />';
        }

        // close link
        $this->htmlShareButtons .= '</a>';
    }

    // get yummly button
    public function ssbp_yummly()
    {
        // yummly share link
        $this->htmlShareButtons .= '<a href="http://www.yummly.com/urb/verify?url=' . $this->urlCurrentPage . '&title=' . urlencode($this->strPageTitle) . '" class="ssbp-btn ssbp-yummly" ' . ($this->ssbp_settings['rel_nofollow'] == 'Y' ? 'rel="nofollow"' : null) . ' data-ssbp-title="' . $this->strPageTitle . '" data-ssbp-url="' . $this->urlCurrentPage . '" data-ssbp-site="Yummly" ' . ($this->ssbp_settings['ga_onclick'] == 'Y' ? 'onclick="ssbpTrackGA(\'' . $this->urlCurrentPage . '\', \'Yummly\'); return false;"' : null) . '>';

        // not using custom images
        if ($this->ssbp_settings['custom_images'] != 'Y') {
            $this->htmlShareButtons .= '<span class="ssbp-text">Yummly</span>';
        } else {// using custom images
            $this->htmlShareButtons .= '<img src="' . $this->ssbp_settings['custom_yummly'] . '" title="Share this on Yummly" class="ssbp" alt="Yummly" />';
        }

        // close link
        $this->htmlShareButtons .= '</a>';

        // if share counts are enabled
        if ($this->ssbp_settings['counters_enabled'] == 'Y') {

            // if the min count has been met or exceeded
            if ((int)$this->ssbpShareCountData['yummly'] >= intval($this->ssbp_settings['min_shares'])) {
                $this->htmlShareButtons .= '<span class="ssbp-total-yummly-shares ssbp-each-share">' . ssbp_format_number($this->ssbpShareCountData['yummly']) . '</span>';
            }
        }
    }

    // get yummly share count
    public function ssbp_yummly_count()
    {
        // get results from yummly and return the number of shares
        $result = wp_remote_get('http://www.yummly.com/services/yum-count?url=' . $this->urlCurrentPage, array('timeout' => $this->ssbp_settings['count_timeout']));

        // check there was an error
        if (is_wp_error($result)) {
            // set share count
            $this->ssbpyummlyShareCount = 0;

            // return
            return;
        }

        // decode data
        $array = json_decode($result['body'], true);
        $count = (isset($array['count']) ? $array['count'] : 0);
        $this->ssbpyummlyShareCount = ($count) ? $count : '0';

        // return
        return;
    }

    // get xing button
    public function ssbp_xing()
    {
        // xing share link
        $this->htmlShareButtons .= '<a href="https://www.xing.com/spi/shares/new?url=' . $this->urlCurrentPage . '" class="ssbp-btn ssbp-xing" ' . ($this->ssbp_settings['rel_nofollow'] == 'Y' ? 'rel="nofollow"' : null) . ' data-ssbp-title="' . $this->strPageTitle . '" data-ssbp-url="' . $this->urlCurrentPage . '" data-ssbp-site="Xing" ' . ($this->ssbp_settings['ga_onclick'] == 'Y' ? 'onclick="ssbpTrackGA(\'' . $this->urlCurrentPage . '\', \'Xing\'); return false;"' : null) . '>';

        // not using custom images
        if ($this->ssbp_settings['custom_images'] != 'Y') {
            $this->htmlShareButtons .= '<span class="ssbp-text">Xing</span>';
        } else {// using custom images
            $this->htmlShareButtons .= '<img src="' . $this->ssbp_settings['custom_xing'] . '" title="Share this on Xing" class="ssbp" alt="Share this on Xing" />';
        }

        // close link
        $this->htmlShareButtons .= '</a>';
    }
}
