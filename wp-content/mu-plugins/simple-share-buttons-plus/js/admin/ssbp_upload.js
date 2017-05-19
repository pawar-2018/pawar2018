jQuery(document).ready(function() {

	// ----- IMAGE UPLOADS ------ //	
	var file_frame; 

	 jQuery('.ssbpUpload').click(function(event){

	    event.preventDefault();

	    // set the field ID we shall add the img url to
	    var strInputID = jQuery(this).data('ssbp-input');
	 
	    // Create the media frame.
	    file_frame = wp.media.frames.file_frame = wp.media({
	      multiple: false  // Set to true to allow multiple files to be selected
	    });
	 
	    // When an image is selected, run a callback.
	    file_frame.on( 'select', function() {
	      	// We set multiple to false so only get one image from the uploader
	      	var attachment = file_frame.state().get('selection').first().toJSON();
			jQuery('#' + strInputID).val(attachment['url']);
	    });
	 
	    // Finally, open the modal
	    file_frame.open();
	  });
	
	// select ortsh url upon clicking the text input
	jQuery(".ssbp-ortsh-input-url").on("click", function () {
	   jQuery(this).select();
	});
});