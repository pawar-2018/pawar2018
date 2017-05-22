<?php

defined('ABSPATH') or die('No direct access permitted');

function ssbp_admin_advanced()
{

    // make sure we have settings ready
    $ssbp_settings = get_ssbp_settings();

    // ssbp header
    $htmlShareButtonsForm = ssbp_admin_header();

    // heading
    $htmlShareButtonsForm .= '<h2>Advanced Settings</h2>';

    // initiate forms helper
    $ssbpForm = new ssbpForms();

    // prepare action url
    $action = admin_url('admin.php?page=simple-share-buttons-advanced');

    // opening form tag
    $htmlShareButtonsForm .= $ssbpForm->open(false, $action);

    // tabs
    $htmlShareButtonsForm .= '<ul class="nav nav-tabs">
							  <li class="active"><a href="#misc" data-toggle="tab">Misc</a></li>
							  <li><a href="#onscroll" data-toggle="tab">OnScroll</a></li>
							  <li><a href="#email" data-toggle="tab">Email</a></li>
							  <li><a href="#facebook" data-toggle="tab">Facebook</a></li>
							  <li><a href="#google_analytics" data-toggle="tab">Google Analytics</a></li>
							  <li><a href="#pinterest" data-toggle="tab">Pinterest</a></li>
							  <li><a href="#twitter" data-toggle="tab">Twitter</a></li>
							  <li><a href="#short_urls" data-toggle="tab">Short URLs</a></li>
							  <li><a href="#others" data-toggle="tab">Others</a></li>
							</ul>';
    // tab content div
    $htmlShareButtonsForm .= '<div id="ssbpTabContent" class="tab-content">';

    //======================================================================
    // 		MISC
    //======================================================================
    $htmlShareButtonsForm .= '<div class="tab-pane fade active in" id="misc">';

    // intro info
    $htmlShareButtonsForm .= '<blockquote><p>You\'ll find a number of advanced and miscellaneous options below, to get your share buttons functioning just how you would like.</p></blockquote>';

    // column for padding
    $htmlShareButtonsForm .= '<div class="col-sm-12">';

    // content priority
    $opts = array(
        'form_group' => false,
        'type' => 'number',
        'placeholder' => '10',
        'name' => 'ssbp_content_priority',
        'label' => 'Content Priority',
        'tooltip' => 'Set the priority for your share buttons within your content. 1-10, default is 10',
        'value' => $ssbp_settings['ssbp_content_priority'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // enable tracking
    $opts = array(
        'form_group' => false,
        'type' => 'checkbox',
        'name' => 'tracking_enabled',
        'label' => 'Tracking Enabled',
        'tooltip' => 'Enable or disable the tracking features of SSBP',
        'value' => 'Y',
        'checked' => ($ssbp_settings['tracking_enabled'] == 'Y' ? 'checked' : null),
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // show tracking page to admins only
    $opts = array(
        'form_group' => false,
        'type' => 'checkbox',
        'name' => 'admin_only_tracking',
        'label' => 'Admin Only Tracking Access',
        'tooltip' => 'Enable this option to hide tracking to all users that are not Admins',
        'value' => 'Y',
        'checked' => ($ssbp_settings['admin_only_tracking'] == 'Y' ? 'checked' : null),
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // load_font_in_head
    $opts = array(
        'form_group' => false,
        'type' => 'checkbox',
        'name' => 'load_font_in_head',
        'label' => 'Load SSBP Font in Head',
        'tooltip' => 'Enable this option to allow for the use of Async CSS loading',
        'value' => 'Y',
        'checked' => ($ssbp_settings['load_font_in_head'] == 'Y' ? 'checked' : null),
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // lazy loading
    $opts = array(
        'form_group' => false,
        'type' => 'checkbox',
        'name' => 'lazy_load',
        'label' => 'Lazy Loading',
        'tooltip' => 'Buttons will appear after page loads, strongly recommended if counters are enabled',
        'value' => 'Y',
        'checked' => ($ssbp_settings['lazy_load'] == 'Y' ? 'checked' : null),
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // use ssba
    $opts = array(
        'form_group' => false,
        'type' => 'checkbox',
        'name' => 'use_ssba',
        'label' => 'Use [ssba]',
        'tooltip' => 'Disable Simple Share Buttons Adder first! Use [ssba] instead of [ssbp]',
        'value' => 'Y',
        'checked' => ($ssbp_settings['use_ssba'] == 'Y' ? 'checked' : null),
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // nofollow
    $opts = array(
        'form_group' => false,
        'type' => 'checkbox',
        'name' => 'rel_nofollow',
        'label' => 'Nofollow',
        'tooltip' => 'Switch on to add nofollow to links',
        'value' => 'Y',
        'checked' => ($ssbp_settings['rel_nofollow'] == 'Y' ? 'checked' : null),
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // ssb_attribution
    $opts = array(
        'form_group' => false,
        'type' => 'checkbox',
        'name' => 'ssb_attribution',
        'label' => 'Attribution',
        'tooltip' => 'Switch off to remove Simple Share Buttons links and logos from popups',
        'value' => 'Y',
        'checked' => ($ssbp_settings['ssb_attribution'] == 'Y' ? 'checked' : null),
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // widget share text
    $opts = array(
        'form_group' => false,
        'type' => 'text',
        'placeholder' => 'Keeping sharing simple...',
        'name' => 'widget_text',
        'label' => 'Widget Share Text',
        'tooltip' => 'Add custom share text when used as a widget',
        'value' => $ssbp_settings['widget_text'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // close column
    $htmlShareButtonsForm .= '</div>';

    // close misc
    $htmlShareButtonsForm .= '</div>';

    //======================================================================
    // 		ONSCROLL
    //======================================================================
    $htmlShareButtonsForm .= '<div class="tab-pane fade" id="onscroll">';

    // intro info
    $htmlShareButtonsForm .= '<blockquote><p>Simple Share Buttons Plus show/hide OnScroll functionality.</p></blockquote>';

    // column for padding
    $htmlShareButtonsForm .= '<div class="col-sm-12">';

    // onscroll_enabled
    $opts = array(
        'form_group' => false,
        'type' => 'checkbox',
        'name' => 'onscroll_enabled',
        'label' => 'Enable OnScroll',
        'tooltip' => 'Enable to show/hide share buttons at set distances from the top/bottom of your website',
        'value' => 'Y',
        'checked' => ($ssbp_settings['onscroll_enabled'] == 'Y' ? 'checked' : null),
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // onscroll_top
    $opts = array(
        'form_group' => false,
        'type' => 'number_addon',
        'addon' => 'px',
        'placeholder' => '150',
        'name' => 'onscroll_top',
        'label' => 'OnScroll Top',
        'tooltip' => 'Set the distance from the top before showing share buttons',
        'value' => $ssbp_settings['onscroll_top'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // onscroll_bottom
    $opts = array(
        'form_group' => false,
        'type' => 'number_addon',
        'addon' => 'px',
        'placeholder' => '150',
        'name' => 'onscroll_bottom',
        'label' => 'OnScroll Bottom',
        'tooltip' => 'Set the distance from the bottom to hide share buttons',
        'value' => $ssbp_settings['onscroll_bottom'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // close column
    $htmlShareButtonsForm .= '</div>';

    // close onscroll
    $htmlShareButtonsForm .= '</div>';

    //======================================================================
    // 		EMAIL
    //======================================================================
    $htmlShareButtonsForm .= '<div class="tab-pane fade" id="email">';

    // intro info
    $htmlShareButtonsForm .= '<blockquote><p>Customisable email functionality options.</p></blockquote>';

    // column for padding
    $htmlShareButtonsForm .= '<div class="col-sm-12">';

    // email message
    $opts = array(
        'form_group' => false,
        'type' => 'text',
        'placeholder' => 'I saw this and thought of you...',
        'name' => 'email_message',
        'label' => 'Email Text',
        'tooltip' => 'Add some text included in the email when people share that way',
        'value' => $ssbp_settings['email_message'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // open well
    $htmlShareButtonsForm .= '<div class="well">';

    // email popup heading
    $htmlShareButtonsForm .= '<h3>Email Popup</h3>';

    // email_popup
    $opts = array(
        'form_group' => false,
        'type' => 'checkbox',
        'name' => 'email_popup',
        'label' => 'Enable Email Popup',
        'tooltip' => 'Enables the SSBP Email Share functionality. Disabling this results in mailto links in it\'s place',
        'value' => 'Y',
        'checked' => ($ssbp_settings['email_popup'] == 'Y' ? 'checked' : null),
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // email_popup_email_label
    $opts = array(
        'form_group' => false,
        'type' => 'text',
        'placeholder' => "Friend's email",
        'name' => 'email_popup_email_label',
        'label' => 'Email Field Label',
        'tooltip' => 'The label that will show above the email address input',
        'value' => $ssbp_settings['email_popup_email_label'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // email_popup_email_placeholder
    $opts = array(
        'form_group' => false,
        'type' => 'text',
        'placeholder' => "friends@email.com",
        'name' => 'email_popup_email_placeholder',
        'label' => 'Email Field Placeholder',
        'tooltip' => 'The placeholder for the empty email field',
        'value' => $ssbp_settings['email_popup_email_placeholder'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // email_popup_message_label
    $opts = array(
        'form_group' => false,
        'type' => 'text',
        'placeholder' => 'Message',
        'name' => 'email_popup_message_label',
        'label' => 'Message Field Label',
        'tooltip' => 'The label that will show above the message textarea',
        'value' => $ssbp_settings['email_popup_message_label'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // email_popup_button_text
    $opts = array(
        'form_group' => false,
        'type' => 'text',
        'placeholder' => 'Send',
        'name' => 'email_popup_button_text',
        'label' => 'Send Button Text',
        'tooltip' => 'The subject that will be used for Email Popup emails',
        'value' => $ssbp_settings['email_popup_button_text'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // email_popup_subject
    $opts = array(
        'form_group' => false,
        'type' => 'text',
        'placeholder' => "Your friend thinks you'd be interested in this",
        'name' => 'email_popup_subject',
        'label' => 'Email Subject',
        'tooltip' => 'The subject that will be used for Email Popup emails',
        'value' => $ssbp_settings['email_popup_subject'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // email_popup_from_name
    $opts = array(
        'form_group' => false,
        'type' => 'text',
        'placeholder' => "Site Name",
        'name' => 'email_popup_from_name',
        'label' => 'Email From Name',
        'tooltip' => 'The from name that will be used for Email Popup emails',
        'value' => $ssbp_settings['email_popup_from_name'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // email_popup_from_email
    $opts = array(
        'form_group' => false,
        'type' => 'text',
        'placeholder' => "your@domain.com",
        'name' => 'email_popup_from_email',
        'label' => 'Email From Email',
        'tooltip' => 'The from email will be used for Email Popup emails',
        'value' => $ssbp_settings['email_popup_from_email'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // email_popup_brute_time
    $opts = array(
        'form_group' => false,
        'type' => 'number_addon',
        'addon' => 'minutes',
        'placeholder' => '5',
        'name' => 'email_popup_brute_time',
        'label' => 'Time Between Emails',
        'tooltip' => 'The length of time (in minutes) between which an individual IP can send an email.',
        'value' => $ssbp_settings['email_popup_brute_time'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // email_popup_alert_success
    $opts = array(
        'form_group' => false,
        'type' => 'text',
        'placeholder' => 'Thanks, your email has been sent',
        'name' => 'email_popup_alert_success',
        'label' => 'Success Alert Text',
        'tooltip' => 'The alert displayed upon successfully sending an email',
        'value' => $ssbp_settings['email_popup_alert_success'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // email_popup_alert_warning
    $opts = array(
        'form_group' => false,
        'type' => 'text',
        'placeholder' => 'Please check the fields and try again',
        'name' => 'email_popup_alert_warning',
        'label' => 'Warning Alert Text',
        'tooltip' => 'Add some text included in the email when people share that way',
        'value' => $ssbp_settings['email_popup_alert_warning'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // email_popup_alert_brute
    $opts = array(
        'form_group' => false,
        'type' => 'text',
        'placeholder' => 'Sorry, the time between sending emails is limited',
        'name' => 'email_popup_alert_brute',
        'label' => 'Brute Alert Text',
        'tooltip' => 'The alert displayed if someone tries to email before their Time Between Emails has passed.',
        'value' => $ssbp_settings['email_popup_alert_brute'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // close column
    $htmlShareButtonsForm .= '</div>';

    // close column
    $htmlShareButtonsForm .= '</div>';

    // close google analytics
    $htmlShareButtonsForm .= '</div>';

    //======================================================================
    // 		FACEBOOK
    //======================================================================
    $htmlShareButtonsForm .= '<div class="tab-pane fade" id="facebook">';

    // intro info
    $htmlShareButtonsForm .= '<blockquote><p>Added Facebook functionality.</p></blockquote>';

    // column for padding
    $htmlShareButtonsForm .= '<div class="col-sm-12">';

    // facebook app id
    $opts = array(
        'form_group'    => false,
        'type'          => 'text',
        'placeholder'	=> '123456789123',
        'name'          => 'facebook_app_id',
        'label'        	=> 'Facebook App ID',
        'tooltip'       => 'Enter your Facebook App ID, e.g. 123456789123',
        'value'         => $ssbp_settings['facebook_app_id'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // info
    $htmlShareButtonsForm .= '<p>You shall need to follow the instructions here before enabling this feature - <a target="_blank" href="https://developers.facebook.com/docs/apps/register">https://developers.facebook.com/docs/apps/register</a></p>';

    // facebook insights
    $opts = array(
        'form_group'	=> false,
        'type'          => 'checkbox',
        'name'          => 'facebook_insights',
        'label'        	=> 'Facebook Insights',
        'tooltip'       => 'Enable this feature to enable Facebook Insights',
        'value'         => 'Y',
        'checked'       => ($ssbp_settings['facebook_insights'] == 'Y'  ? 'checked' : null),
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // info
    $htmlShareButtonsForm .= '<p>You shall need have created and added a Facebook App ID above to make use of this feature</p>';

    // close column
    $htmlShareButtonsForm .= '</div>';

    // close facebook
    $htmlShareButtonsForm .= '</div>';

    //======================================================================
    // 		GOOGLE ANALYTICS
    //======================================================================
    $htmlShareButtonsForm .= '<div class="tab-pane fade" id="google_analytics">';

    // intro info
    $htmlShareButtonsForm .= '<blockquote><p>Enable Google Analytics event tracking to see share clicks in your Analytics.</p></blockquote>';

    // column for padding
    $htmlShareButtonsForm .= '<div class="col-sm-12">';

    // ga event tracking
    $opts = array(
        'form_group' => false,
        'type' => 'checkbox',
        'name' => 'ga_onclick',
        'label' => 'Google Analytics Event Tracking',
        'tooltip' => 'Will show share button clicks within your Google Analytics Events, requires Google Analytics core tracking code to be present',
        'value' => 'Y',
        'checked' => ($ssbp_settings['ga_onclick'] == 'Y' ? 'checked' : null),
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // open well
    $htmlShareButtonsForm .= '<div class="well">';

    // ga tracking info
    $htmlShareButtonsForm .= '<p>If you <strong>do not already have Google Analytics enabled</strong>, you can enable it here by simply adding your Tracking ID.</p>';

    // ga tracking
    $opts = array(
        'form_group' => false,
        'type' => 'text',
        'placeholder' => 'UA-3823XX74-X',
        'name' => 'ga_tracking_id',
        'label' => 'Google Analytics Tracking ID',
        'tooltip' => 'Enable Google Analytics tracking by entering your Tracking ID here',
        'value' => $ssbp_settings['ga_tracking_id'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // google track logged in users
    $opts = array(
        'form_group' => false,
        'type' => 'checkbox',
        'name' => 'ga_track_logged_in',
        'label' => 'Track Logged In Users',
        'tooltip' => 'Switch on to track logged in users',
        'value' => 'Y',
        'checked' => ($ssbp_settings['ga_track_logged_in'] == 'Y' ? 'checked' : null),
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // close column
    $htmlShareButtonsForm .= '</div>';

    // close column
    $htmlShareButtonsForm .= '</div>';

    // close google analytics
    $htmlShareButtonsForm .= '</div>';

    //======================================================================
    // 		SHORT URLS
    //======================================================================
    $htmlShareButtonsForm .= '<div class="tab-pane fade" id="short_urls">';

    // intro info
    $htmlShareButtonsForm .= '<blockquote><p>Short URL settings, including third-party URL shorteners</p></blockquote>';

    // column for padding
    $htmlShareButtonsForm .= '<div class="col-sm-12">';

    // use shortlinks
    $opts = array(
        'form_group' => false,
        'type' => 'checkbox',
        'name' => 'use_shortlinks',
        'label' => 'Use WP Shortlinks',
        'tooltip' => 'Your WP shortlinks will be used when sharing',
        'value' => 'Y',
        'checked' => ($ssbp_settings['use_shortlinks'] == 'Y' ? 'checked' : null),
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // bitly login
    $opts = array(
        'form_group' => false,
        'type' => 'text',
        'placeholder' => 'simplesharebuttons',
        'name' => 'bitly_login',
        'label' => 'Bitly Login',
        'tooltip' => 'Enter your Bitly login username',
        'value' => $ssbp_settings['bitly_login'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // bitly api key
    $opts = array(
        'form_group' => false,
        'type' => 'text',
        'placeholder' => 'E186C1237B6F1AC822EA62CE5EB95',
        'name' => 'bitly_api_key',
        'label' => 'Bitly API Key',
        'tooltip' => 'Your Bitly API Key from your registered application',
        'value' => $ssbp_settings['bitly_api_key'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // close column
    $htmlShareButtonsForm .= '</div>';

    // close pinterest
    $htmlShareButtonsForm .= '</div>';

    //======================================================================
    // 		TWITTER
    //======================================================================
    $htmlShareButtonsForm .= '<div class="tab-pane fade" id="twitter">';

    // intro info
    $htmlShareButtonsForm .= '<blockquote><p>Various extra options, especially for Twitter</p></blockquote>';

    // column for padding
    $htmlShareButtonsForm .= '<div class="col-sm-12">';

    // use native links
    $opts = array(
        'form_group' => false,
        'type' => 'checkbox',
        'name' => 'use_native_links',
        'label' => 'Use Native Links',
        'tooltip' => 'On devices smaller than your mobile breakpoint (set on the styling page), SSBP will use native share links',
        'value' => 'Y',
        'checked' => ($ssbp_settings['use_native_links'] == 'Y' ? 'checked' : null),
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // twitter username
    $opts = array(
        'form_group' => false,
        'type' => 'text_prefix',
        'prefix' => '@',
        'placeholder' => 'fairtoshare',
        'name' => 'twitter_username',
        'label' => 'Twitter Username',
        'tooltip' => 'Add your username to tweets, will appear as \'via @fairtoshare\' for example',
        'value' => $ssbp_settings['twitter_username'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // twitter text
    $opts = array(
        'form_group' => false,
        'type' => 'text',
        'placeholder' => 'Simple Share Buttons',
        'name' => 'twitter_text',
        'label' => 'Twitter Text',
        'tooltip' => 'Add some custom text for when people share via Twitter',
        'value' => $ssbp_settings['twitter_text'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // twitter tags
    $opts = array(
        'form_group' => false,
        'type' => 'text',
        'placeholder' => 'fairtoshare,simplesharebuttons',
        'name' => 'twitter_tags',
        'label' => 'Twitter Tags',
        'tooltip' => 'Add hashtags for when people share via Twitter, comma-separated',
        'value' => $ssbp_settings['twitter_tags'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // close column
    $htmlShareButtonsForm .= '</div>';

    // close twitter
    $htmlShareButtonsForm .= '</div>';

    //======================================================================
    // 		PINTEREST
    //======================================================================
    $htmlShareButtonsForm .= '<div class="tab-pane fade" id="pinterest">';

    // intro info
    $htmlShareButtonsForm .= '<blockquote><p>Various extra options, especially for Pinterest</p></blockquote>';

    // column for padding
    $htmlShareButtonsForm .= '<div class="col-sm-12">';

    // feature image pinning
    $opts = array(
        'form_group' => false,
        'type' => 'checkbox',
        'name' => 'pinterest_use_featured',
        'label' => 'Featured Image Pinning',
        'tooltip' => 'Enable to force the use of your Featured images for users when \'Pinning\'',
        'value' => 'Y',
        'checked' => ($ssbp_settings['pinterest_use_featured'] == 'Y' ? 'checked' : null),
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // feature image pinning
    $opts = array(
        'form_group' => false,
        'type' => 'checkbox',
        'name' => 'pinterest_use_ssbp_meta',
        'label' => 'SSBP Meta Image Pinning',
        'tooltip' => 'Enable to force the use of your chosen SSBP Meta Images for users when \'Pinning\', requries SSBP meta information to be set',
        'value' => 'Y',
        'checked' => ($ssbp_settings['pinterest_use_ssbp_meta'] == 'Y' ? 'checked' : null),
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // close column
    $htmlShareButtonsForm .= '</div>';

    // close pinterest
    $htmlShareButtonsForm .= '</div>';

    //======================================================================
    // 		OTHERS
    //======================================================================
    $htmlShareButtonsForm .= '<div class="tab-pane fade" id="others">';

    // intro info
    $htmlShareButtonsForm .= '<blockquote><p>Various extra options, for any other social networks</p></blockquote>';

    // column for padding
    $htmlShareButtonsForm .= '<div class="col-sm-12">';

    // whatsapp shortlinks
    $opts = array(
        'form_group' => false,
        'type' => 'checkbox',
        'name' => 'whatsapp_shortlinks',
        'label' => 'WhatsApp Shortlinks',
        'tooltip' => 'Enable to use shortlinks for WhatsApp sharing',
        'value' => 'Y',
        'checked' => ($ssbp_settings['whatsapp_shortlinks'] == 'Y' ? 'checked' : null),
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // flattr user id
    $opts = array(
        'form_group' => false,
        'type' => 'text',
        'placeholder' => 'davidsneal',
        'name' => 'flattr_user_id',
        'label' => 'Flattr User ID',
        'tooltip' => 'Enter your Flattr ID',
        'value' => $ssbp_settings['flattr_user_id'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // flattr url
    $opts = array(
        'form_group' => false,
        'type' => 'text',
        'placeholder' => 'https://simplesharebuttons.com',
        'name' => 'flattr_url',
        'label' => 'Flattr URL',
        'tooltip' => 'This option is perfect for dedicated sites',
        'value' => $ssbp_settings['flattr_url'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // custom buffer text
    $opts = array(
        'form_group' => false,
        'type' => 'text',
        'placeholder' => 'This was shared via Buffer...',
        'name' => 'buffer_text',
        'label' => 'Custom Buffer Text',
        'tooltip' => 'Add some custom text for when people share via Buffer',
        'value' => $ssbp_settings['buffer_text'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // close column
    $htmlShareButtonsForm .= '</div>';

    // close others
    $htmlShareButtonsForm .= '</div>';

    // close tab content div
    $htmlShareButtonsForm .= '</div>';

    // close off form with save button
    $htmlShareButtonsForm .= $ssbpForm->close();

    // ssbp footer
    $htmlShareButtonsForm .= ssbp_admin_footer();

    echo $htmlShareButtonsForm;
}
