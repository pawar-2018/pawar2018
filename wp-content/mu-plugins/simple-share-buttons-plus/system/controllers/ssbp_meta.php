<?php

defined('ABSPATH') or die('No direct access permitted');

// add meta data to head as required
function get_ssbp_meta()
{

    // get ssbp settings
    $ssbp_settings = get_ssbp_settings();

    // if a custom share title is set,  use the custom share title
    if (get_post_meta(get_the_ID(), '_ssbp_meta_title', true)) {
        $title = get_post_meta(get_the_ID(), '_ssbp_meta_title', true);
    } else {// or use the default set in SSBP admin
        $title = $ssbp_settings['meta_title'];
    }

    // if a custom og type is set,  use the custom type
    if (get_post_meta(get_the_ID(), '_ssbp_meta_type', true)) {
        $type = get_post_meta(get_the_ID(), '_ssbp_meta_type', true);
    } else {// or use the default set in SSBP admin
        $type = $ssbp_settings['meta_type'];
    }

    // if a custom share description had been set, use the custom share description
    if (get_post_meta(get_the_ID(), '_ssbp_meta_description', true)) {
        $description = get_post_meta(get_the_ID(), '_ssbp_meta_description', true);
    } else {// or use the default set in SSBP admin
        $description = $ssbp_settings['meta_description'];
    }

    // if a custom share image is set, use the custom share image
    if (get_post_meta(get_the_ID(), '_ssbp_meta_image', true)) {
        $image = get_post_meta(get_the_ID(), '_ssbp_meta_image', true);
    } // if featured images are enabled
    elseif ($ssbp_settings['meta_use_featured_image'] == 'Y') {
        $image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()));
        $image = $image[0];
    } // or use the default set in SSBP admin
    else {
        $image = $ssbp_settings['meta_image'];
    }

    // viewing a main page of sorts that has posts on
    if (is_home() || is_front_page() || is_category()) {
        // use all the defaults
        $title = $ssbp_settings['meta_title'];
        $type = $ssbp_settings['meta_type'];
        $description = $ssbp_settings['meta_description'];
        $image = $ssbp_settings['meta_image'];
    }

// insert left aligned meta information, with line breaks above and below
    echo "\n" . '<!-- START simplesharebuttons.com/plus meta data -->
<!-- simplesharebuttons.com/plus opengraph share details -->
<meta property="og:title" content="' . $title . '"/>
<meta property="og:type" content="' . $type . '"/>
<meta property="og:description" content="' . $description . '"/>
<meta property="og:image" content="' . $image . '"/>
<!-- simplesharebuttons.com/plus twitter share details -->
<meta name="twitter:title" content="' . $title . '">
<meta name="twitter:description" content="' . $description . '">
<meta name="twitter:image:src" content="' . $image . '">
<!-- simplesharebuttons.com/plus google+ share details -->
<meta itemprop="name" content="' . $title . '">
<meta itemprop="description" content="' . $description . '">
<meta itemprop="image" content="' . $image . '">
<!-- END simplesharebuttons.com/plus meta data -->' . "\n";
}

// Add meta boxes to the main column on the Post and Page edit screens
function ssbp_add_meta_box()
{
    $screens = array('post', 'page');

    foreach ($screens as $screen) {
        add_meta_box(
            'ssbp_sectionid',
            __('Simple Share Buttons Meta', 'ssbp_title'),
            'ssbp_meta_box_callback',
            $screen
        );
    }
}

add_action('add_meta_boxes', 'ssbp_add_meta_box');

/**
 * Prints the box content.
 *
 * @param WP_Post $post The object for the current post/page.
 */
function ssbp_meta_box_callback($post)
{

    // Add an nonce field so we can check for it later.
    wp_nonce_field('ssbp_meta_box', 'ssbp_meta_box_nonce');

    /*
     * Use get_post_meta() to retrieve an existing value
     * from the database and use the value for the form.
     */
    $title = get_post_meta($post->ID, '_ssbp_meta_title', true);
    $type = get_post_meta($post->ID, '_ssbp_meta_type', true);
    $image = get_post_meta($post->ID, '_ssbp_meta_image', true);
    $description = get_post_meta($post->ID, '_ssbp_meta_description', true);

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

    echo '<table>';
    echo '<tr>';
    echo '<td style="min-width:115px">';
    echo '<label for="ssbp_meta_title">';
    _e('Share Title', 'ssbp_meta_title');
    echo '</label> ';
    echo '</td>';
    echo '<td style="width:100%">';
    echo '<input type="text" style="width:100%" id="ssbp_meta_title" name="ssbp_meta_title" value="' . esc_attr($title) . '" />';
    echo '</td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td style="min-width:115px">';
    echo '<label for="ssbp_meta_type">';
    _e('Content Type', 'ssbp_meta_type');
    echo '</label> ';
    echo '</td>';
    echo '<td style="width:100%">';

    // open select box
    echo '<select style="width:100%" id="ssbp_meta_type" name="ssbp_meta_type">';

    // loop through content types
    foreach ($types as $name => $value) {
        // output option
        echo '<option value="' . esc_attr($value) . '"' . ($value == $type ? ' selected' : null) . '>' . esc_attr($name) . '</option>';
    }

    echo '</select>';
    echo '</td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td style="min-width:115px">';
    echo '<label for="ssbp_meta_description">';
    _e('Share Description', 'ssbp_meta_description');
    echo '</label> ';
    echo '</td>';
    echo '<td style="width:100%">';
    echo '<textarea id="ssbp_meta_description" style="width:100%" name="ssbp_meta_description">' . esc_attr($description) . '</textarea>';
    echo '</td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td style="min-width:115px">';
    echo '<label for="ssbp_meta_image">';
    _e('Share Image', 'ssbp_meta_image');
    echo '</label> ';
    echo '</td>';
    echo '<td style="width:100%">';
    echo '<input id="ssbp_meta_image" type="text" style="width:78%" name="ssbp_meta_image" value="' . esc_attr($image) . '" />';
    echo '<input id="upload_image_button" style="width:20%; float: right;" data-ssbp-input="ssbp_meta_image" class="button ssbpUpload ssbp_upload_btn" type="button" value="Upload Image" />';
    echo '</td>';
    echo '</tr>';
    echo '</table>';
    echo '<p>Please also note that Facebook caches webpage details on each share click, until updated using the <a href="https://developers.facebook.com/tools/debug/" target="_blank">Facebook debugger</a></p>';
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function ssbp_save_meta_box_data($post_id)
{

    /*
     * We need to verify this came from our screen and with proper authorization,
     * because the save_post action can be triggered at other times.
     */

    // Check if our nonce is set.
    if (!isset($_POST['ssbp_meta_box_nonce'])) {
        return;
    }

    // Verify that the nonce is valid.
    if (!wp_verify_nonce($_POST['ssbp_meta_box_nonce'], 'ssbp_meta_box')) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check the user's permissions.
    if (isset($_POST['post_type']) && 'page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return;
        }
    } else {
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
    }

    /* OK, it's safe for us to save the data now. */

    // Make sure that it is set.
    if (!isset($_POST['ssbp_meta_title'])) {
        return;
    }

    // Sanitize user input.
    $title = sanitize_text_field($_POST['ssbp_meta_title']);
    $type = sanitize_text_field($_POST['ssbp_meta_type']);
    $image = sanitize_text_field($_POST['ssbp_meta_image']);
    $description = sanitize_text_field($_POST['ssbp_meta_description']);

    // Update the meta field in the database.
    update_post_meta($post_id, '_ssbp_meta_title', $title);
    update_post_meta($post_id, '_ssbp_meta_type', $type);
    update_post_meta($post_id, '_ssbp_meta_image', $image);
    update_post_meta($post_id, '_ssbp_meta_description', $description);
}

add_action('save_post', 'ssbp_save_meta_box_data');
