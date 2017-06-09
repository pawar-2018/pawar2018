<?php

defined('ABSPATH') or die('No direct access permitted');

function ssbp_admin_post_types()
{
    // make sure we have settings ready
    $ssbp_settings = get_ssbp_settings();

    // ssbp header
    $htmlShareButtonsForm = ssbp_admin_header();

    // heading
    $htmlShareButtonsForm .= '<h2>Post Types</h2>';

    // intro info
    $htmlShareButtonsForm .= '<blockquote><p>Use the switches to enable/disable share buttons for the corresponding post types.</p></blockquote>';

    // initiate forms helper
    $ssbpForm = new ssbpForms();

    // prepare action url
    $action = admin_url('admin.php?page=simple-share-buttons-post_types');

    // opening form tag
    $htmlShareButtonsForm .= $ssbpForm->open(true, $action);

    // fetch all post types
    $post_types = get_post_types('', 'names');

    // create an array of post types to ignore
    $arrIgnoreTypes = array(
        'post',
        'page',
        'attachment',
        'revision',
        'nav_menu_item',
    );

    // create a count
    $countPostTypes = 0;

    // loop through them
    foreach ($post_types as $post_type) {
        // skip those we don't want
        if (in_array($post_type, $arrIgnoreTypes)) {
            continue;
        }

        // add to counter
        $countPostTypes++;

        // post type
        $opts = array(
            'form_group' => false,
            'type' => 'checkbox',
            'class' => 'ssbp-post-type',
            'name' => 'ssbp_disabled_types[]',
            'label' => $post_type,
            'value' => $post_type,
            'checked' => (in_array($post_type, explode(',', $ssbp_settings['disabled_types'])) ? 'checked' : null),
        );
        $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);
    }

    // if there are no relevant custom post types
    if ($countPostTypes == 0) {
        $htmlShareButtonsForm .= '<h4>No relevant custom post types found</h4>';
    }

    // close off form with save button
    $htmlShareButtonsForm .= $ssbpForm->close();

    // ssbp footer
    $htmlShareButtonsForm .= ssbp_admin_footer();

    echo $htmlShareButtonsForm;
}
