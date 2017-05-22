jQuery(document).ready(function() {

	// switch for checkboxes
	jQuery(".ssbp-admin-wrap input:checkbox:not('.ssbp-post-type')").bootstrapSwitch({
		onColor: 	'primary',
		size:		'normal'
	});

	// switch for post type checkboxes
	jQuery("input.ssbp-post-type").bootstrapSwitch({
		onColor: 	'default',
		onText:		'OFF',
		offColor:	'primary',
		offText:	'ON',
		inverse:	true,
		size:		'normal'
	});

	jQuery('[data-toggle="tooltip"]').tooltip();

	// simple file input
	jQuery(".filestyle").filestyle({icon: false});

	jQuery('.ssbp-updated').fadeIn('fast');
	jQuery('.ssbp-updated').delay(1000).fadeOut('slow');

	//------- INCLUDE LIST ----------//

	// add drag and sort functions to include table
	jQuery(function() {
		jQuery( "#ssbpsort1, #ssbpsort2" ).sortable({
			connectWith: ".ssbpSortable"
		}).disableSelection();
	  });


	// extract and add include list to hidden field
	jQuery('#ssbp_selected_buttons').val(jQuery('#ssbpsort2 li').map(function() {
	// For each <li> in the list, return its inner text and let .map()
	//  build an array of those values.
	return jQuery(this).attr('id');
	}).get());

	// after a change, extract and add include list to hidden field
	jQuery('.ssbp-wrap').mouseout(function() {
		jQuery('#selected_buttons').val(jQuery('#ssbpsort2 li').map(function() {
		// For each <li> in the list, return its inner text and let .map()
		//  build an array of those values.
		return jQuery(this).attr('id');
		}).get());
	});

	// when support details textarea is clicked
	jQuery('#ssbp-support-textarea,.support-details-btn').click(function(){
		// select text in support details textarea
		document.getElementById("ssbp-support-textarea").select();
	});

	jQuery("#ssb-official-import").click(function(){
		if(confirm("Are you sure? All your current settings will be overwritten!")) {
	        return true;
	    }
	    return false;
	});

	//---------------------------------------------------------------------------------------//
    //
    // SSBP ADMIN FORM
    //
    jQuery( "#ssbp-admin-form:not('.ssbp-form-non-ajax')" ).on( 'submit', function(e) {

        // don't submit the form
        e.preventDefault();

        // show spinner to show save in progress
        jQuery("button.ssbp-btn-save").html('<i class="fa fa-spinner fa-spin"></i>');

        // get posted data and serialise
        var ssbpData = jQuery("#ssbp-admin-form").serialize();

        // disable all inputs
        jQuery(':input').prop('disabled', true);
		jQuery(".ssbp-admin-wrap input:checkbox").bootstrapSwitch('disabled', true);


        jQuery.post(
            jQuery( this ).prop( 'action' ),
            {
                ssbpData: ssbpData
            },
            function() {

				// show success
                jQuery('button.ssbp-btn-save-success').fadeIn(100).delay(2500).fadeOut(200);

	            // re-enable inputs and reset save button
	            jQuery(':input').prop('disabled', false);
				jQuery(".ssbp-admin-wrap input:checkbox").bootstrapSwitch('disabled', false);
                jQuery("button.ssbp-btn-save").html('<i class="fa fa-floppy-o"></i>');
            }
        ); // end post
    } ); // end form submit

});
