<?php

defined('ABSPATH') or die('No direct access permitted');

function ssbp_admin_styling()
{
    // make sure we have settings ready
    $ssbp_settings = get_ssbp_settings();

    // prepare array of buttons
    $arrButtons = json_decode(get_option('ssbp_buttons'), true);

    // array of button set positions
    $arrButtonPositions = array(
        'Left-aligned' => '',
        'Centred' => 'centred',
        'Right-aligned' => 'aligned-right',
        'Stacked' => 'stacked',
        'Fixed Left' => 'fixed-left',
        'Fixed Right' => 'fixed-right',
        'Fixed Bottom' => 'fixed-bottom',
    );

    // ssbp header
    $htmlShareButtonsForm = ssbp_admin_header();

    // get the font family needed
    $htmlShareButtonsForm .= '<style>' . ssbp_get_font_family() . '</style>';

    $htmlShareButtonsForm .= '<h2 class="ssbp-heading-styling">Style Settings</h2>';

    // initiate forms helper
    $ssbpForm = new ssbpForms();

    // prepare action url
    $action = admin_url('admin.php?page=simple-share-buttons-styling');

    // opening form tag
    $htmlShareButtonsForm .= $ssbpForm->open(false, $action);

    // tabs
    $htmlShareButtonsForm .= '<ul class="nav nav-tabs">
							  <li class="active"><a href="#button_sets" data-toggle="tab">Button Sets</a></li>
							  <li><a href="#colours" data-toggle="tab">Button Colours</a></li>
							  <li><a href="#sizes" data-toggle="tab">Sizes</a></li>
							  <li><a href="#share_text" data-toggle="tab">Share Text</a></li>
							  <li><a href="#images" data-toggle="tab">Images</a></li>
							  <li class="dropdown">
							    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
							      CSS <span class="caret"></span>
							    </a>
							    <ul class="dropdown-menu">
							      <li><a href="#css_additional" data-toggle="tab">Additional</a></li>
							      <li><a href="#css_custom" data-toggle="tab">Custom</a></li>
							    </ul>
							  </li>
							</ul>';
    // tab content div
    $htmlShareButtonsForm .= '<div id="ssbpTabContent" class="tab-content">';

    //======================================================================
    // 		BUTTON SETS
    //======================================================================
    $htmlShareButtonsForm .= '<div class="tab-pane fade active in" id="button_sets">';

    // intro info
    $htmlShareButtonsForm .= '<blockquote><p>Use the options below to choose your favourite button set(s) and how it/they should appear. If you wish to use a button set from Simple Share Buttons Adder please use the \'Images\' tab and upload them as required.</p></blockquote>';

    // SET ONE COLUMN --------------------------------
    $htmlShareButtonsForm .= '<div class="col-md-6">';

    // heading
    $htmlShareButtonsForm .= '<h3>Share Bar</h3>';

    // open well
    $htmlShareButtonsForm .= '<div class="well">';

    // array of button sets available for set 1
    $arrButtonSets = array(
        '1' => '1',
        '2' => '2',
        '3' => '3',
        '4' => '4',
        '5' => '5',
        '6' => '6',
        '7' => '7',
        '8' => '8',
        '9' => '9',
        '10' => '10',
        '11' => '11',
    );

    // button set
    $opts = array(
        'form_group' => false,
        'type' => 'select',
        'name' => 'default_style',
        'label' => 'Button Set',
        'tooltip' => 'Choose the style of buttons you want',
        'selected' => $ssbp_settings['default_style'],
        'options' => $arrButtonSets,
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // button positioning
    $opts = array(
        'form_group' => false,
        'type' => 'select',
        'name' => 'set_one_position',
        'label' => 'Button Positioning',
        'tooltip' => 'Set the way your share buttons should position themselves',
        'selected' => $ssbp_settings['set_one_position'],
        'options' => $arrButtonPositions,
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // show share counts
    $opts = array(
        'form_group' => false,
        'type' => 'checkbox',
        'name' => 'one_share_counts',
        'label' => 'Show Share Counts',
        'tooltip' => 'Switch on to show share counts (must be enabled on the Counters page)',
        'value' => 'Y',
        'checked' => ($ssbp_settings['one_share_counts'] == 'Y' ? 'checked' : null),
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // show total share counts
    $opts = array(
        'form_group' => false,
        'type' => 'checkbox',
        'name' => 'one_total_share_counts',
        'label' => 'Show Total Share Counts',
        'tooltip' => 'Switch on to show share total counts (must be enabled on the Counters page)',
        'value' => 'Y',
        'checked' => ($ssbp_settings['one_total_share_counts'] == 'Y' ? 'checked' : null),
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // show toggle
    $opts = array(
        'form_group' => false,
        'type' => 'checkbox',
        'name' => 'one_toggle',
        'label' => 'Show Toggle',
        'tooltip' => 'Switch on to show toggle switch to show hide the button set',
        'value' => 'Y',
        'checked' => ($ssbp_settings['one_toggle'] == 'Y' ? 'checked' : null),
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // make responsive
    $opts = array(
        'form_group' => false,
        'type' => 'checkbox',
        'name' => 'one_responsive',
        'label' => 'Responsive',
        'tooltip' => 'Make button set responsive when viewing on smaller devices',
        'value' => 'Y',
        'checked' => ($ssbp_settings['one_responsive'] == 'Y' ? 'checked' : null),
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // breakpoint
    $opts = array(
        'form_group' => false,
        'type' => 'number_addon',
        'addon' => 'px',
        'placeholder' => '480',
        'name' => 'one_breakpoint',
        'label' => 'Mobile Breakpoint',
        'tooltip' => 'Set the screenwidth that buttons should switch to mobile-view',
        'value' => $ssbp_settings['one_breakpoint'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // close well
    $htmlShareButtonsForm .= '</div>';

    // close column one
    $htmlShareButtonsForm .= '</div>';

    // SET TWO COLUMN --------------------------------
    $htmlShareButtonsForm .= '<div class="col-md-6">';

    // heading
    $htmlShareButtonsForm .= '<h3>Share Buttons</h3>';

    // well
    $htmlShareButtonsForm .= '<div class="well">';

    // array of button sets available for set 2
    $arrButtonSets = array(
        'None' => '',
        '1' => '1',
        '2' => '2',
        '3' => '3',
        '4' => '4',
        '5' => '5',
        '6' => '6',
        '7' => '7',
        '8' => '8',
        '9' => '9',
        '10' => '10',
        '11' => '11',
    );

    // button set two
    $opts = array(
        'form_group' => false,
        'type' => 'select',
        'name' => 'two_style',
        'label' => 'Button Set',
        'tooltip' => 'Choose the style of buttons you want',
        'selected' => $ssbp_settings['two_style'],
        'options' => $arrButtonSets,
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // button positioning
    $opts = array(
        'form_group' => false,
        'type' => 'select',
        'name' => 'set_two_position',
        'label' => 'Button Positioning',
        'tooltip' => 'Set the way your share buttons should position themselves',
        'selected' => $ssbp_settings['set_two_position'],
        'options' => $arrButtonPositions,
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // show share counts
    $opts = array(
        'form_group' => false,
        'type' => 'checkbox',
        'name' => 'two_share_counts',
        'label' => 'Show Share Counts',
        'tooltip' => 'Switch on to show share counts (must be enabled on the Counters page)',
        'value' => 'Y',
        'checked' => ($ssbp_settings['two_share_counts'] == 'Y' ? 'checked' : null),
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // show total share counts
    $opts = array(
        'form_group' => false,
        'type' => 'checkbox',
        'name' => 'two_total_share_counts',
        'label' => 'Show Total Share Counts',
        'tooltip' => 'Switch on to show share total counts (must be enabled on the Counters page)',
        'value' => 'Y',
        'checked' => ($ssbp_settings['two_total_share_counts'] == 'Y' ? 'checked' : null),
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // show toggle
    $opts = array(
        'form_group' => false,
        'type' => 'checkbox',
        'name' => 'two_toggle',
        'label' => 'Show Toggle',
        'tooltip' => 'Switch on to show toggle switch to show hide the button set',
        'value' => 'Y',
        'checked' => ($ssbp_settings['two_toggle'] == 'Y' ? 'checked' : null),
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // close well
    $htmlShareButtonsForm .= '</div>';

    // CLOSE SET TWO COL -----------------------------
    $htmlShareButtonsForm .= '</div>';

    // close share buttons tab
    $htmlShareButtonsForm .= '</div>';

    //======================================================================
    // 		COLOURS
    //======================================================================
    $htmlShareButtonsForm .= '<div class="tab-pane fade" id="colours">';

    // intro info
    $htmlShareButtonsForm .= '<blockquote><p>All of these colour options are <b>optional</b>. If not set the default colours of your selected button sets will be used. You can set as few or as many of these colour options as you wish.</p></blockquote>';

    // SET ONE COLUMN --------------------------------
    $htmlShareButtonsForm .= '<div class="col-md-6">';

    // heading
    $htmlShareButtonsForm .= '<h3>Share Bar</h3>';

    // open well
    $htmlShareButtonsForm .= '<div class="well">';

    // button colour
    $opts = array(
        'form_group' => false,
        'type' => 'colorpicker',
        'name' => 'color_main',
        'label' => 'Button Colour',
        'tooltip' => 'Choose a colour for your buttons or leave blank',
        'value' => $ssbp_settings['color_main'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // hover colour
    $opts = array(
        'form_group' => false,
        'type' => 'colorpicker',
        'name' => 'color_hover',
        'label' => 'Button Hover Colour',
        'tooltip' => 'Choose a colour for your buttons when hovering or leave blank',
        'value' => $ssbp_settings['color_hover'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // icon colour
    $opts = array(
        'form_group' => false,
        'type' => 'colorpicker',
        'name' => 'icon_color',
        'label' => 'Icon Colour',
        'tooltip' => 'Choose a colour for your icons or leave blank',
        'value' => $ssbp_settings['icon_color'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // icon hover colour
    $opts = array(
        'form_group' => false,
        'type' => 'colorpicker',
        'name' => 'icon_color_hover',
        'label' => 'Icon Hover Colour',
        'tooltip' => 'Choose a colour for your buttons when hovering or leave blank',
        'value' => $ssbp_settings['icon_color_hover'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // close well
    $htmlShareButtonsForm .= '</div>';

    // close column one
    $htmlShareButtonsForm .= '</div>';

    // SET TWO COLUMN --------------------------------
    $htmlShareButtonsForm .= '<div class="col-md-6">';

    // heading
    $htmlShareButtonsForm .= '<h3>Share Buttons</h3>';

    // well
    $htmlShareButtonsForm .= '<div class="well">';

    // button colour
    $opts = array(
        'form_group' => false,
        'type' => 'colorpicker',
        'name' => 'color_main_two',
        'label' => 'Button Colour',
        'tooltip' => 'Choose a colour for your buttons or leave blank',
        'value' => $ssbp_settings['color_main_two'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // hover colour
    $opts = array(
        'form_group' => false,
        'type' => 'colorpicker',
        'name' => 'color_hover_two',
        'label' => 'Button Hover Colour',
        'tooltip' => 'Choose a colour for your buttons when hovering or leave blank',
        'value' => $ssbp_settings['color_hover_two'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // icon colour
    $opts = array(
        'form_group' => false,
        'type' => 'colorpicker',
        'name' => 'icon_color_two',
        'label' => 'Icon Colour',
        'tooltip' => 'Choose a colour for your icons or leave blank',
        'value' => $ssbp_settings['icon_color_two'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // icon hover colour
    $opts = array(
        'form_group' => false,
        'type' => 'colorpicker',
        'name' => 'icon_color_hover_two',
        'label' => 'Icon Hover Colour',
        'tooltip' => 'Choose a colour for your buttons when hovering or leave blank',
        'value' => $ssbp_settings['icon_color_hover_two'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // close well
    $htmlShareButtonsForm .= '</div>';

    // CLOSE SET TWO COL -----------------------------
    $htmlShareButtonsForm .= '</div>';

    // close colours
    $htmlShareButtonsForm .= '</div>';

    //======================================================================
    // 		SIZES
    //======================================================================
    $htmlShareButtonsForm .= '<div class="tab-pane fade" id="sizes">';

    // intro info
    $htmlShareButtonsForm .= '<blockquote><p>Use the size options below to tweak the share button sizes to best suit your website.</p></blockquote>';

    // SET ONE COLUMN --------------------------------
    $htmlShareButtonsForm .= '<div class="col-md-6">';

    // heading
    $htmlShareButtonsForm .= '<h3>Share Bar</h3>';

    // open well
    $htmlShareButtonsForm .= '<div class="well">';

    // button height
    $opts = array(
        'form_group' => false,
        'type' => 'number_addon',
        'addon' => 'em',
        'placeholder' => '3',
        'name' => 'button_height',
        'label' => 'Button Height',
        'tooltip' => 'Set the height for your buttons',
        'value' => $ssbp_settings['button_height'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // button width
    $opts = array(
        'form_group' => false,
        'type' => 'number_addon',
        'addon' => 'em',
        'placeholder' => '3',
        'name' => 'button_width',
        'label' => 'Button Width',
        'tooltip' => 'Set the width for your buttons',
        'value' => $ssbp_settings['button_width'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // icon size
    $opts = array(
        'form_group' => false,
        'type' => 'number_addon',
        'addon' => 'px',
        'placeholder' => '24',
        'name' => 'icon_size',
        'label' => 'Icon Size',
        'tooltip' => 'Set the icon size for your buttons',
        'value' => $ssbp_settings['icon_size'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // button margin
    $opts = array(
        'form_group' => false,
        'type' => 'number_addon',
        'addon' => 'px',
        'placeholder' => '12',
        'name' => 'button_margin',
        'label' => 'Button Margin',
        'tooltip' => 'Set the margin/spacing around your buttons',
        'value' => $ssbp_settings['button_margin'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // close well
    $htmlShareButtonsForm .= '</div>';

    // close column one
    $htmlShareButtonsForm .= '</div>';

    // SET TWO COLUMN --------------------------------
    $htmlShareButtonsForm .= '<div class="col-md-6">';

    // heading
    $htmlShareButtonsForm .= '<h3>Share Buttons</h3>';

    // well
    $htmlShareButtonsForm .= '<div class="well">';

    // button height
    $opts = array(
        'form_group' => false,
        'type' => 'number_addon',
        'addon' => 'em',
        'placeholder' => '3',
        'name' => 'button_two_height',
        'label' => 'Button Height',
        'tooltip' => 'Set the height for your buttons',
        'value' => $ssbp_settings['button_two_height'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // button width
    $opts = array(
        'form_group' => false,
        'type' => 'number_addon',
        'addon' => 'em',
        'placeholder' => '3',
        'name' => 'button_two_width',
        'label' => 'Button Width',
        'tooltip' => 'Set the width for your buttons',
        'value' => $ssbp_settings['button_two_width'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // icon size
    $opts = array(
        'form_group' => false,
        'type' => 'number_addon',
        'addon' => 'px',
        'placeholder' => '24',
        'name' => 'icon_two_size',
        'label' => 'Icon Size',
        'tooltip' => 'Set the icon size for your buttons',
        'value' => $ssbp_settings['icon_two_size'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // button margin
    $opts = array(
        'form_group' => false,
        'type' => 'number_addon',
        'addon' => 'px',
        'placeholder' => '12',
        'name' => 'button_two_margin',
        'label' => 'Button Margin',
        'tooltip' => 'Set the margin/spacing around your buttons',
        'value' => $ssbp_settings['button_two_margin'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // close well
    $htmlShareButtonsForm .= '</div>';

    // CLOSE SET TWO COL -----------------------------
    $htmlShareButtonsForm .= '</div>';

    // close colours
    $htmlShareButtonsForm .= '</div>';

    //======================================================================
    // 		SHARE TEXT
    //======================================================================
    $htmlShareButtonsForm .= '<div class="tab-pane fade" id="share_text">';

    // intro info
    $htmlShareButtonsForm .= '<blockquote><p>The share text options below relate to the share text that you set on the main setup page. <b>Note</b> that \'Inherit\' will simply use the same font family as your theme.</p></blockquote>';

    // column for padding
    $htmlShareButtonsForm .= '<div class="col-sm-12">';

    // share text placement
    $opts = array(
        'form_group' => false,
        'type' => 'select',
        'name' => 'text_placement',
        'label' => 'Text Placement',
        'tooltip' => 'Choose where in relation to your buttons you wish your share text to appear - may differ with each style',
        'selected' => $ssbp_settings['text_placement'],
        'options' => array(
            'Above' => 'above',
            'Below' => 'below',
            'Left' => 'left',
            'Right' => 'right',
        ),
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // share text size
    $opts = array(
        'form_group' => false,
        'type' => 'number_addon',
        'addon' => 'px',
        'placeholder' => '15',
        'name' => 'font_size',
        'label' => 'Font Size',
        'tooltip' => 'Set the font size for your share text',
        'value' => $ssbp_settings['font_size'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // font colour
    $opts = array(
        'form_group' => false,
        'type' => 'colorpicker',
        'name' => 'font_color',
        'label' => 'Font Colour',
        'tooltip' => 'Choose a colour for your share text or leave blank',
        'value' => $ssbp_settings['font_color'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // share text font family
    $opts = array(
        'form_group' => false,
        'type' => 'select',
        'name' => 'font_family',
        'label' => 'Font Family',
        'tooltip' => 'Choose a font available or inherit the font from your website',
        'selected' => $ssbp_settings['font_family'],
        'options' => array(
            'Inherit' => '',
            'Indie Flower' => 'Indie+Flower',
            'Lato' => 'Lato',
            'Merriweather' => 'Merriweather',
            'Montserrat' => 'Montserrat',
            'Open Sans' => 'Open+Sans',
            'Raleway' => 'Raleway',
            'Reenie Beanie' => 'Reenie+Beanie',
            'Shadows Into Light' => 'Shadows+Into+Light',
        ),
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // share text font weight
    $opts = array(
        'form_group' => false,
        'type' => 'select',
        'name' => 'font_weight',
        'label' => 'Font Weight',
        'tooltip' => 'Choose the weight of your share text',
        'selected' => $ssbp_settings['font_weight'],
        'options' => array(
            'Light' => 'light',
            'Normal' => 'normal',
            'Bold' => 'bold',
        ),
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // close column
    $htmlShareButtonsForm .= '</div>';

    // close share text
    $htmlShareButtonsForm .= '</div>';

    //======================================================================
    // 		IMAGES
    //======================================================================
    $htmlShareButtonsForm .= '<div class="tab-pane fade" id="images">';

    // intro info
    $htmlShareButtonsForm .= '<blockquote><p>If you wish to use your own custom images, simply enable them via the switch below, set the sizing and padding and upload/select the images for each social network. <b>Previews are unavailable for this option</b>.</p></blockquote>';

    // column for padding
    $htmlShareButtonsForm .= '<div class="col-sm-12">';

    // enable custom images
    $opts = array(
        'form_group' => false,
        'type' => 'checkbox',
        'name' => 'custom_images',
        'label' => 'Custom Images',
        'tooltip' => 'Switch on to use your own images',
        'value' => 'Y',
        'checked' => ($ssbp_settings['custom_images'] == 'Y' ? 'checked' : null),
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // image width
    $opts = array(
        'form_group' => false,
        'type' => 'number_addon',
        'addon' => 'px',
        'placeholder' => '40',
        'name' => 'image_width',
        'label' => 'Image Width',
        'tooltip' => 'Set the width to display your images',
        'value' => $ssbp_settings['image_width'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // image height
    $opts = array(
        'form_group' => false,
        'type' => 'number_addon',
        'addon' => 'px',
        'placeholder' => '40',
        'name' => 'image_height',
        'label' => 'Image Height',
        'tooltip' => 'Set the height to display your images',
        'value' => $ssbp_settings['image_height'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // image padding
    $opts = array(
        'form_group' => false,
        'type' => 'number_addon',
        'addon' => 'px',
        'placeholder' => '20',
        'name' => 'image_padding',
        'label' => 'Image Padding',
        'tooltip' => 'Set the padding size around your images',
        'value' => $ssbp_settings['image_padding'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // well
    $htmlShareButtonsForm .= '<div class="well">';

    // loop through each button
    foreach ($arrButtons as $button => $arrButton) {
        // enable custom images
        $opts = array(
            'form_group' => false,
            'type' => 'image_upload',
            'name' => 'custom_' . $button,
            'label' => $arrButton['full_name'],
            'tooltip' => 'Upload a custom ' . $arrButton['full_name'] . ' image',
            'value' => (isset($ssbp_settings['custom_' . $button]) ? $ssbp_settings['custom_' . $button] : null),
        );
        $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);
    }

    // close well
    $htmlShareButtonsForm .= '</div>';

    // close column
    $htmlShareButtonsForm .= '</div>';

    // close images
    $htmlShareButtonsForm .= '</div>';

    //======================================================================
    // 		ADDITIONAL CSS
    //======================================================================
    $htmlShareButtonsForm .= '<div class="tab-pane fade" id="css_additional">';

    // intro info
    $htmlShareButtonsForm .= '<blockquote><p>The contents of the text area below will be minified then appended to a unique CSS file.</p></blockquote>';

    // column for padding
    $htmlShareButtonsForm .= '<div class="col-sm-12">';

    // additional css
    $opts = array(
        'form_group' => false,
        'type' => 'textarea',
        'rows' => '15',
        'class' => 'code-font',
        'name' => 'additional_css',
        'label' => 'Additional CSS',
        'tooltip' => 'Add your own additional CSS if you wish',
        'value' => $ssbp_settings['additional_css'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // close column
    $htmlShareButtonsForm .= '</div>';

    // close additional css
    $htmlShareButtonsForm .= '</div>';

    //======================================================================
    // 		CUSTOM CSS
    //======================================================================
    $htmlShareButtonsForm .= '<div class="tab-pane fade" id="css_custom">';

    // intro info
    $htmlShareButtonsForm .= '<blockquote><p>If you want to take over control of your share buttons\' CSS entirely, turn on the switch below and enter your custom CSS. <strong>ALL of Simple Share Buttons Plus\' CSS will be disabled</strong>. The contents of the text area below will be minified and added to a unique CSS file.</p></blockquote>';

    // column for padding
    $htmlShareButtonsForm .= '<div class="col-sm-12">';

    // enable custom css
    $opts = array(
        'form_group' => false,
        'type' => 'checkbox',
        'name' => 'custom_styles_enabled',
        'label' => 'Enable Custom CSS',
        'tooltip' => 'Switch on to disable all SSBP styles and use your own custom CSS',
        'value' => 'Y',
        'checked' => ($ssbp_settings['custom_styles_enabled'] == 'Y' ? 'checked' : null),
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // custom css
    $opts = array(
        'form_group' => false,
        'type' => 'textarea',
        'rows' => '15',
        'class' => 'code-font',
        'name' => 'custom_styles',
        'label' => 'Custom CSS',
        'tooltip' => 'Enter in your own custom CSS for your share buttons',
        'value' => $ssbp_settings['custom_styles'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // close column
    $htmlShareButtonsForm .= '</div>';

    // close custom css
    $htmlShareButtonsForm .= '</div>';

    // close tab content div
    $htmlShareButtonsForm .= '</div>';

    // close off form with save button
    $htmlShareButtonsForm .= $ssbpForm->close();

    // PREVIEWS -------------------------------------------
    $htmlShareButtonsForm .= '<div id="ssbp-previews" class="row container ' . ($ssbp_settings['custom_images'] == 'Y' ? 'hidden' : null) . '">';
    $htmlShareButtonsForm .= '<div class="col-sm-12">';
    $htmlShareButtonsForm .= '<blockquote><p>These previews are in place to give you a good idea of how your buttons will look, but are not necessarily an exact representation of how they will look with your theme. <b>Note that the icons in place are just for demonstration purposes and not all style setting changes will be reflected here.</b></p></blockquote>';
    $htmlShareButtonsForm .= '</div>';
    $htmlShareButtonsForm .= '<div id="set-one-col" class="col-md-6 text-center">';

    $htmlShareButtonsForm .= '<h3>Share Bar Preview</h3>';

    $htmlShareButtonsForm .= '<div id="ssbp-preview--one" class="ssbp-set--one ssbp-wrap ssbp--theme-' . $ssbp_settings['default_style'] . '" ' . ($ssbp_settings['one_share_counts'] == 'Y' ? 'data-ssbp-counts="true"' : null) . '>
										<div class="ssbp-container">
											<ul class="ssbp-list">
												<li class="ssbp-li--facebook"><a href="#" class="ssbp-btn ssbp-facebook"><span class="ssbp-text">Facebook</span></a><span class="ssbp-total-facebook-shares ssbp-each-share">1.8k</span></li>
												<li class="ssbp-li--twitter"><a href="#" class="ssbp-btn ssbp-twitter"><span class="ssbp-text">Twitter</span></a><span class="ssbp-total-twitter-shares ssbp-each-share">1.8k</span></li>
												<li class="ssbp-li--google"><a href="#" class="ssbp-btn ssbp-google"><span class="ssbp-text">Google+</span></a><span class="ssbp-total-google-shares ssbp-each-share">1.8k</span></li>
												<li class="ssbp-li--linkedin"><a href="#" class="ssbp-btn ssbp-linkedin"><span class="ssbp-text">LinkedIn</span></a><span class="ssbp-total-linkedin-shares ssbp-each-share">1.8k</span></li>
											</ul>
										</div>
									</div>';

    $htmlShareButtonsForm .= '</div>';

    // only show the second preview if a set is selected
    $htmlShareButtonsForm .= '<div id="set-two-col" class="col-md-6 text-center ' . ($ssbp_settings['two_style'] == '' ? 'hidden' : null) . '">';
    $htmlShareButtonsForm .= '<h3>Share Buttons Preview</h3>';

    $htmlShareButtonsForm .= '<div id="ssbp-preview--two" class="ssbp-set--two ssbp-wrap ssbp--theme-' . $ssbp_settings['two_style'] . '" ' . ($ssbp_settings['two_share_counts'] == 'Y' ? ' data-ssbp-counts="true"' : null) . '>
										<div class="ssbp-container">
											<ul class="ssbp-list">
												<li class="ssbp-li--facebook"><a href="#" class="ssbp-btn ssbp-facebook"><span class="ssbp-text">Facebook</span></a><span class="ssbp-total-facebook-shares ssbp-each-share">1.8k</span></li>
												<li class="ssbp-li--twitter"><a href="#" class="ssbp-btn ssbp-twitter"><span class="ssbp-text">Twitter</span></a><span class="ssbp-total-twitter-shares ssbp-each-share">1.8k</span></li>
												<li class="ssbp-li--google"><a href="#" class="ssbp-btn ssbp-google"><span class="ssbp-text">Google+</span></a><span class="ssbp-total-google-shares ssbp-each-share">1.8k</span></li>
												<li class="ssbp-li--linkedin"><a href="#" class="ssbp-btn ssbp-linkedin"><span class="ssbp-text">LinkedIn</span></a><span class="ssbp-total-linkedin-shares ssbp-each-share">1.8k</span></li>
											</ul>
										</div>
									</div>';

    $htmlShareButtonsForm .= '</div>';
    $htmlShareButtonsForm .= '</div>';
    // CLOSE PREVIEWS -------------------------------------

    // ssbp footer
    $htmlShareButtonsForm .= ssbp_admin_footer();

    echo $htmlShareButtonsForm;
}
