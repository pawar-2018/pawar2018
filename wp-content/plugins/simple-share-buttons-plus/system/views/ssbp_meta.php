<?php

defined('ABSPATH') or die('No direct access permitted');

function ssbp_admin_meta()
{

    // make sure we have settings ready
    $ssbp_settings = get_ssbp_settings();

    // ssbp header
    $htmlShareButtonsForm = ssbp_admin_header();

    // heading
    $htmlShareButtonsForm .= '<h2>Share Meta Tags</h2>';

    // intro info
    $htmlShareButtonsForm .= '<blockquote><p>If enabled, Simple Share Buttons Plus will add a number of meta fields to the head of your webpages. Please note that some themes/SEO plugins also offer this functionality, <b>please check this if results aren\'t as expected</b>.</p><p>Please also note that <strong>Facebook caches webpage details</strong> on each share click, until updated using the <a href="https://developers.facebook.com/tools/debug/" target="_blank">Facebook debugger</a>. You can <a href="https://simplesharebuttons.com/plus/tutorials/share-meta-tags/" target="blank">learn more about how to use the Facebook debugger here</a>.</p></blockquote>';

    // initiate forms helper
    $ssbpForm = new ssbpForms();

    // prepare action url
    $action = admin_url('admin.php?page=simple-share-buttons-meta');

    // opening form tag
    $htmlShareButtonsForm .= $ssbpForm->open(true, $action);

    // enable meta tags
    $opts = array(
        'form_group' => true,
        'type' => 'checkbox',
        'name' => 'meta_enabled',
        'label' => 'Enable Meta Tags',
        'tooltip' => 'Switch on to enable share counts',
        'value' => 'Y',
        'checked' => ($ssbp_settings['meta_enabled'] == 'Y' ? 'checked' : null),
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // array of of meta types
    $types = array(
        'Article' => 'article',
        'Books - Author' => 'books.author',
        'Book - Book' => 'books.book',
        'Books - Genre' => 'books.genre',
        'Books - Business' => 'books.business',
        'Fitness - Course' => 'fitness.course',
        'Game - Achievement' => 'game.achievement',
        'Music - Album' => 'music.album',
        'Music - Playlist' => 'music.playlist',
        'Music - Radio Station' => 'music.radio_station',
        'Music - Song' => 'music.song',
        'Place' => 'place',
        'Product' => 'product',
        'Product - Group' => 'product.group',
        'Product - Item' => 'product.item',
        'Profile' => 'profile',
        'Restaurant' => 'restaurant.restaurant',
        'Restaurant - Menu' => 'restaurant.menu',
        'Restaurant - Menu Item' => 'restaurant.menu_item',
        'Restaurant - Menu Section' => 'restaurant.menu_section',
        'Video - Episode' => 'video.episode',
        'Video - Movie' => 'video.movie',
        'Video - Other' => 'video.other',
        'Video - TV Show' => 'video.tv_show',
        'Website' => 'website',
    );

    // og type
    $opts = array(
        'form_group' => true,
        'type' => 'select',
        'name' => 'meta_type',
        'label' => 'Default Content Type',
        'tooltip' => 'Set the default OG meta type for your website content',
        'selected' => $ssbp_settings['meta_type'],
        'options' => $types,
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // default share title
    $opts = array(
        'form_group' => true,
        'type' => 'text',
        'placeholder' => 'Simple Share Buttons',
        'name' => 'meta_title',
        'label' => 'Default Title',
        'tooltip' => 'Add a default share title',
        'value' => $ssbp_settings['meta_title'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // default share description
    $opts = array(
        'form_group' => true,
        'type' => 'textarea',
        'rows' => '3',
        'name' => 'meta_description',
        'label' => 'Default Description',
        'tooltip' => 'Add a default share description',
        'value' => $ssbp_settings['meta_description'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // enable use of featured images
    $opts = array(
        'form_group' => true,
        'type' => 'checkbox',
        'name' => 'meta_use_featured_image',
        'label' => 'Use Featured Images',
        'tooltip' => 'Switch on to use featured images for your sharing images. You can override these on any pages simply by uploading a different image in the SSBP options for each post/page',
        'value' => 'Y',
        'checked' => ($ssbp_settings['meta_use_featured_image'] == 'Y' ? 'checked' : null),
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // default image
    $opts = array(
        'form_group' => true,
        'type' => 'image_upload',
        'class' => 'image_upload',
        'placeholder' => 'https://simplesharebuttons.com/wp-content/uploads/2014/08/simple-share-buttons-logo-square.png',
        'name' => 'meta_image',
        'label' => 'Default Image',
        'tooltip' => 'Add a default share image',
        'value' => $ssbp_settings['meta_image'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // close off form with save button
    $htmlShareButtonsForm .= $ssbpForm->close();

    // ssbp footer
    $htmlShareButtonsForm .= ssbp_admin_footer();

    echo $htmlShareButtonsForm;
}
