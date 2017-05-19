<?php

defined('ABSPATH') or die('No direct access permitted');

function ssbp_admin_panel()
{
    // make sure we have settings ready
    $ssbp_settings = get_ssbp_settings();

    // ssbp header
    $htmlShareButtonsForm = ssbp_admin_header();

    // heading
    $htmlShareButtonsForm .= '<h2>Share Buttons Setup</h2>';

    // get the font family needed
    $htmlShareButtonsForm .= '<style>' . ssbp_get_font_family() . '.ssbp-whatsapp{display: inline-block!important;}</style>';

    // intro info
    $htmlShareButtonsForm .= '<blockquote><p>The <b>simple</b> options you can see below are all you need to complete to get your <b>share buttons</b> to appear on your website. Once you\'re done here, you can further customise the share buttons via the \'<a href="admin.php?page=simple-share-buttons-styling">Styling</a>\' page.</p></blockquote>';

    // initiate forms helper
    $ssbpForm = new ssbpForms();

    // prepare action url
    $action = admin_url('admin.php?page=simple-share-buttons-setup');

    // opening form tag
    $htmlShareButtonsForm .= $ssbpForm->open(true, $action);

    // locations array
    $locs = array(
        'Homepage' => array(
            'value' => 'homepage',
            'checked' => ($ssbp_settings['homepage'] == 'Y' ? true : false),
        ),
        'Pages' => array(
            'value' => 'pages',
            'checked' => ($ssbp_settings['pages'] == 'Y' ? true : false),
        ),
        'Posts' => array(
            'value' => 'posts',
            'checked' => ($ssbp_settings['posts'] == 'Y' ? true : false),
        ),
        'Excerpts' => array(
            'value' => 'excerpts',
            'checked' => ($ssbp_settings['excerpts'] == 'Y' ? true : false),
        ),
        'Categories/Archives' => array(
            'value' => 'cats_archs',
            'checked' => ($ssbp_settings['cats_archs'] == 'Y' ? true : false),
        ),
        'Search Results' => array(
            'value' => 'search_results',
            'checked' => ($ssbp_settings['search_results'] == 'Y' ? true : false),
        ),
    );
    // locations
    $opts = array(
        'form_group' => true,
        'label' => 'Locations',
        'tooltip' => 'Enable the locations you wish for share buttons to appear',
        'value' => 'Y',
        'checkboxes' => $locs,
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_checkboxes($opts);

    // placement
    $opts = array(
        'form_group' => true,
        'type' => 'select',
        'name' => 'before_or_after',
        'label' => 'Placement',
        'tooltip' => 'Place share buttons before or after your content',
        'selected' => $ssbp_settings['before_or_after'],
        'options' => array(
            'After' => 'after',
            'Before' => 'before',
            'Both' => 'both',
        ),
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // share text
    $opts = array(
        'form_group' => true,
        'type' => 'text',
        'placeholder' => 'Keeping sharing simple...',
        'name' => 'share_text',
        'label' => 'Share Text',
        'tooltip' => 'Add some custom text by your share buttons',
        'value' => $ssbp_settings['share_text'],
    );
    $htmlShareButtonsForm .= $ssbpForm->ssbp_input($opts);

    // networks
    $htmlShareButtonsForm .= '<div class="form-group">
										<label for="ssbp_choices" class="control-label" data-toggle="tooltip" data-placement="right" data-original-title="Drag, drop and reorder those buttons that you wish to include">Networks</label>
										<div class="">';

    $htmlShareButtonsForm .= '<div class="ssbp-wrap ssbp--centred ssbp--theme-4">
												<div class="ssbp-container">
													<ul id="ssbpsort1" class="ssbp-list ssbpSortable">';
    $htmlShareButtonsForm .= getAvailableSSBP($ssbp_settings['selected_buttons']);
    $htmlShareButtonsForm .= '</ul>
												</div>
											</div>';
    $htmlShareButtonsForm .= '<div class="well">';
    $htmlShareButtonsForm .= '<div class="ssbp-well-instruction"><i class="fa fa-download"></i> Drop icons below</div>';
    $htmlShareButtonsForm .= '<div class="ssbp-wrap ssbp--centred ssbp--theme-4">
												<div class="ssbp-container">
													<ul id="ssbpsort2" class="ssbp-include-list ssbp-list ssbpSortable">';
    $htmlShareButtonsForm .= getSelectedSSBP($ssbp_settings['selected_buttons']);
    $htmlShareButtonsForm .= '</ul>
											</div>';
    $htmlShareButtonsForm .= '</div>';
    $htmlShareButtonsForm .= '</div>';
    $htmlShareButtonsForm .= '<input type="hidden" name="selected_buttons" id="selected_buttons" value="' . $ssbp_settings['selected_buttons'] . '"/>';

    $htmlShareButtonsForm .= '</div>';

    // close off form with save button
    $htmlShareButtonsForm .= $ssbpForm->close();

    // ssbp footer
    $htmlShareButtonsForm .= ssbp_admin_footer();

    echo $htmlShareButtonsForm;
}

// get an html formatted of currently selected and ordered buttons
function getSelectedssbp($strSelectedssbp)
{
    // variables
    $htmlSelectedList = '';
    $arrSelectedssbp = '';

    // prepare array of buttons
    $arrButtons = json_decode(get_option('ssbp_buttons'), true);

    // if there are some selected buttons
    if ($strSelectedssbp != '') {
        // explode saved include list and add to a new array
        $arrSelectedssbp = explode(',', $strSelectedssbp);

        // check if array is not empty
        if ($arrSelectedssbp != '') {
            // for each included button
            foreach ($arrSelectedssbp as $strSelected) {
                // add a list item for each selected option
                $htmlSelectedList .= '<li class="ssbp-option-item" id="' . $strSelected . '"><a href="javascript:;" title="'.$arrButtons[$strSelected]['full_name'].'" class="ssbp-btn ssbp-' . $strSelected . '"><span class="ssbp-text">' . $arrButtons[$strSelected]['full_name'] . '</span></a></li>';
            }
        }
    }

    return $htmlSelectedList;
}

// get an html formatted of currently selected and ordered buttons
function getAvailablessbp($strSelectedssbp)
{
    // variables
    $htmlAvailableList = '';
    $arrSelectedssbp = '';

    // prepare array of buttons
    $arrButtons = json_decode(get_option('ssbp_buttons'), true);

    // explode saved include list and add to a new array
    $arrSelectedssbp = explode(',', $strSelectedssbp);

    // create array of all available buttons
    $arrAllAvailablessbp = json_decode(get_option('ssbp_buttons'), true);

    // extract the available buttons
    $arrAvailablessbp = array_diff(array_keys($arrAllAvailablessbp), $arrSelectedssbp);

    // check if array is not empty
    if ($arrSelectedssbp != '') {
        // for each included button
        foreach ($arrAvailablessbp as $strAvailable) {
            // add a list item for each available option
            $htmlAvailableList .= '<li class="ssbp-option-item" id="' . $strAvailable . '"><a href="javascript:;" title="'.$arrButtons[$strAvailable]['full_name'].'" class="ssbp-btn ssbp-' . $strAvailable . '"><span class="ssbp-text">' . $arrButtons[$strAvailable]['full_name'] . '</span></a></li>';
        }
    }

    // return html list options
    return $htmlAvailableList;
}
