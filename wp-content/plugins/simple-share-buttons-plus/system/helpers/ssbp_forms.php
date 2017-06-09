<?php

defined('ABSPATH') or die('No direct access permitted');

// forms class
class ssbpForms
{
    // variables
    public $ssbp_checkboxes;

    // opening form tag
    public function open($wrap, $action = '', $class = '')
    {
        $return = '<div class="' . ($wrap ? 'ssbp-form-wrap' : null) . '">';
        $return .= '<form class="form-horizontal ' . $class . '" id="ssbp-admin-form" method="post" action="' . $action . '">';

        // required hidden fields
        $return .= wp_nonce_field('ssbp_save_settings', 'ssbp_save_nonce');
        $return .= '<input type="hidden" name="ssbp_options" />';

        // open fieldset
        $return .= '<fieldset>';

        return $return;
    }

    // close form tag
    public function close()
    {
        // save button
        $return = '<button type="submit" id="submit" class="ssbp-btn-save btn btn-lg btn-primary"><i class="fa fa-floppy-o"></i></button>';

        // success button
        $return .= '<button type="button" class="ssbp-btn-save-success btn btn-lg btn-success"><i class="fa fa-check"></i></button>';

        // close fieldset
        $return .= '</fieldset>';

        // close form
        $return .= '</form>';
        $return .= '</div>';

        return $return;
    }

    // inline checkboxes
    public function ssbp_checkboxes($opts)
    {
        // check if opts passed is an array
        if (!is_array($opts)) {
            return 'Variable passed not an array';
        }

        // define variable
        $input = '';

        // if we're including the form group div
        if ($opts['form_group'] === true) {
            $input .= '<div class="form-group">';
        }

        // if a tooltip has been set
        if (isset($opts['tooltip']) && $opts['tooltip'] != '') {
            $tooltip = 'data-toggle="tooltip" data-placement="right" data-original-title="' . $opts['tooltip'] . '"';
        } // no tooltip
        else {
            $tooltip = '';
        }

        // label with tooltip
        $input .= '<label class="control-label" ' . $tooltip . '>' . $opts['label'] . '</label>';

        // input div
        $input .= '<div class="">';

        // add all checkboxes
        array_walk($opts['checkboxes'], array($this, '_ssbp_add_checkboxes'));
        $input .= $this->ssbp_checkboxes;

        // close input div
        $input .= '</div>';

        // if we're including the form group div
        if ($opts['form_group'] === true) {
            $input .= '</div>';
        }

        // return the input
        return $input;
    }

    // checkboxes
    private function _ssbp_add_checkboxes($value, $key)
    {
        $this->ssbp_checkboxes .= '<label class="checkbox-inline no_indent">
									' . $key . '<br />
									<input type="checkbox" id="' . $value['value'] . '" name="' . $value['value'] . '" value="Y" ' . ($value['checked'] === true ? 'checked="checked"' : null) . '>
									</label>';
    }

    // form input with group
    public function ssbp_input($opts)
    {
        // check if opts passed is an array
        if (!is_array($opts)) {
            return 'Variable passed not an array';
        }

        // define variable
        $input = '';

        // if we're including the form group div
        if ($opts['form_group'] === true) {
            $input .= '<div class="form-group">';
        }

        // if a tooltip has been set
        if (isset($opts['tooltip']) && $opts['tooltip'] != '') {
            $tooltip = 'data-toggle="tooltip" data-placement="right" data-original-title="' . $opts['tooltip'] . '"';
        } // no tooltip
        else {
            $tooltip = '';
        }

        // label with tooltip
        $input .= '<label for="' . $opts['name'] . '" class="control-label" ' . $tooltip . '>' . $opts['label'] . '</label>';

        // input div
        $input .= '<div class="input-div">';

        // switch based on the inputn type
        switch ($opts['type']) {
            case 'text':
            default:
                $input .= '<input class="form-control" name="' . $opts['name'] . '" id="' . $opts['name'] . '" type="text" value="' . $opts['value'] . '" placeholder="' . $opts['placeholder'] . '" />';
                break;

            case 'text_prefix':
                $input .= '<div class="input-group">
						    <span class="input-group-addon">' . $opts['prefix'] . '</span>
						    <input name="' . $opts['name'] . '" id="' . $opts['name'] . '" type="text" value="' . $opts['value'] . '" class="form-control" placeholder="' . $opts['placeholder'] . '">
						  </div>';
                break;

            case 'error':
                $input .= '<p class="text-danger">' . $opts['error'] . '</p>';
                break;

            case 'number':
                $input .= '<input class="form-control" name="' . $opts['name'] . '" id="' . $opts['name'] . '" type="number" value="' . $opts['value'] . '" placeholder="' . $opts['placeholder'] . '" />';
                break;

            case 'image_upload':
                $input .= '<div class="input-group">
						    <input id="' . $opts['name'] . '" name="' . $opts['name'] . '" type="text" class="form-control" value="' . $opts['value'] . '">
						    <span class="input-group-btn">
						      <button id="upload_' . $opts['name'] . '_button" class="ssbpUpload ssbp_upload_btn btn btn-default" data-ssbp-input="' . $opts['name'] . '" type="button">Upload</button>
						    </span>
						  </div>';
                break;

            case 'number_addon':
                $input .= '<div class="input-group">
						    <input id="' . $opts['name'] . '" name="' . $opts['name'] . '" type="number" step="any" class="form-control" value="' . $opts['value'] . '" placeholder="' . $opts['placeholder'] . '" />
						    <span class="input-group-addon">' . $opts['addon'] . '</span>
						  </div>';
                break;

            case 'colorpicker':

                $input .= '<input id="' . $opts['name'] . '" name="' . $opts['name'] . '" type="text" class="ssbp-colorpicker form-control" value="' . $opts['value'] . '" placeholder="#4582ec" style="border-color: ' . ($opts['value'] != '' ? $opts['value'] : '#eaeaea') . '" />';
                break;

            case 'textarea':
                $input .= '<textarea class="form-control ' . (isset($opts['class']) ? $opts['class'] : null) . '" name="' . $opts['name'] . '" id="' . $opts['name'] . '" rows="' . $opts['rows'] . '">' . $opts['value'] . '</textarea>';
                break;

            case 'checkbox':
                $input .= '<input class="' . (isset($opts['class']) ? $opts['class'] : null) . '" name="' . $opts['name'] . '" id="' . $opts['name'] . '" type="checkbox" ' . $opts['checked'] . ' value="' . $opts['value'] . '" />';
                break;

            case 'select':
                $input .= '<select class="form-control" name="' . $opts['name'] . '" id="' . $opts['name'] . '">';

                // add all options
                foreach ($opts['options'] as $key => $value) {
                    $input .= '<option value="' . $value . '" ' . ($value == $opts['selected'] ? 'selected="selected"' : null) . '>' . $key . '</option>';
                }

                $input .= '</select>';
                break;
        }

        // close input div
        $input .= '</div>';

        // if we're including the form group div
        if ($opts['form_group'] === true) {
            $input .= '</div>';
        }

        // return the input
        return $input;
    }
}
