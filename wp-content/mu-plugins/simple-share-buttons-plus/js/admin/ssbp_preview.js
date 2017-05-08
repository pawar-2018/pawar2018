jQuery(document).ready(function() {

    jQuery('#font_color').colpick({
        layout:'hex',
        submit:1,
        onSubmit:function(hsb,hex,rgb,el,colid) {
            jQuery(el).val('#'+hex);
            jQuery(el).css('border-color', '#'+hex);
            jQuery(el).colpickHide();
        }
    });

	// DEFAULT STYLES
	// default style for set one is changed
	jQuery( "#default_style" ).change(function() {
		jQuery('#ssbp-preview--one').removeClass('ssbp--theme-1 ssbp--theme-2 ssbp--theme-3 ssbp--theme-4 ssbp--theme-5 ssbp--theme-6 ssbp--theme-7 ssbp--theme-8 ssbp--theme-9 ssbp--theme-10 ssbp--theme-11');
		jQuery('#ssbp-preview--one').addClass('ssbp--theme-'+jQuery(this).val());
	});
	// default style for set two is changed
	jQuery( "#two_style" ).change(function() {
		if(jQuery(this).val() == '') {
			jQuery('#set-two-col').addClass('hidden');
			jQuery('#ssbp-preview--two').removeClass('ssbp--theme-1 ssbp--theme-2 ssbp--theme-3 ssbp--theme-4 ssbp--theme-5 ssbp--theme-6 ssbp--theme-7 ssbp--theme-8 ssbp--theme-9 ssbp--theme-10 ssbp--theme-11');
		}
		else {
			jQuery('#set-two-col').removeClass('hidden');
			jQuery('#ssbp-preview--two').removeClass('ssbp--theme-1 ssbp--theme-2 ssbp--theme-3 ssbp--theme-4 ssbp--theme-5 ssbp--theme-6 ssbp--theme-7 ssbp--theme-8 ssbp--theme-9 ssbp--theme-10 ssbp--theme-11');
			jQuery('#ssbp-preview--two').addClass('ssbp--theme-'+jQuery(this).val());
		}
	});

	// SHARE COUNTS
	// show/hide share counts set one
	jQuery('input[name="one_share_counts"]').on('switchChange.bootstrapSwitch', function(event, state) {
		if(state === true) {
			jQuery('#ssbp-preview--one').data('ssbp-counts', true).attr('data-ssbp-counts', true);
		}
		else {
			jQuery('#ssbp-preview--one').removeData('ssbp-counts').removeAttr('data-ssbp-counts');
		}
	});
	// show/hide share counts set two
	jQuery('input[name="two_share_counts"]').on('switchChange.bootstrapSwitch', function(event, state) {
		if(state === true) {
			jQuery('#ssbp-preview--two').data('ssbp-counts', true).attr('data-ssbp-counts', true);
		}
		else {
			jQuery('#ssbp-preview--two').removeData('ssbp-counts').removeAttr('data-ssbp-counts');
		}
	});

	// COLOURS
	// set one button colour change
	jQuery('#color_main').colpick({
		layout:'hex',
		submit:1,
		onSubmit:function(hsb,hex,rgb,el,colid) {
			jQuery(el).val('#'+hex);
			jQuery(el).css('border-color', '#'+hex);
			jQuery(el).colpickHide();
			jQuery('#ssbp-preview--one .ssbp-btn').css("background-color", '#'+hex);
		}
	});
	// set tw0 button colour change
	jQuery('#color_main_two').colpick({
		layout:'hex',
		submit:1,
		onSubmit:function(hsb,hex,rgb,el,colid) {
			jQuery(el).val('#'+hex);
			jQuery(el).css('border-color', '#'+hex);
			jQuery(el).colpickHide();
			jQuery('#ssbp-preview--two .ssbp-btn').css("background-color", '#'+hex);
		}
	});

	// HOVER COLOURS
	jQuery('#color_hover').colpick({
		layout:'hex',
		submit:1,
		onSubmit:function(hsb,hex,rgb,el,colid) {
			jQuery(el).val('#'+hex);
			jQuery(el).css('border-color', '#'+hex);
			jQuery(el).colpickHide();
			jQuery('head').append('<style>#ssbp-preview--one .ssbp-btn:hover {background-color : #'+hex+'!important;}</style>');
		}
	});
	jQuery('#color_hover_two').colpick({
		layout:'hex',
		submit:1,
		onSubmit:function(hsb,hex,rgb,el,colid) {
			jQuery(el).val('#'+hex);
			jQuery(el).css('border-color', '#'+hex);
			jQuery(el).colpickHide();
			jQuery('head').append('<style>#ssbp-preview--two .ssbp-btn:hover {background-color : #'+hex+'!important;}</style>');
		}
	});

	jQuery('#icon_color').colpick({
		layout:'hex',
		submit:1,
		onSubmit:function(hsb,hex,rgb,el,colid) {
			jQuery(el).val('#'+hex);
			jQuery(el).css('border-color', '#'+hex);
			jQuery(el).colpickHide();
			jQuery('head').append('<style>#ssbp-preview--one .ssbp-btn:before {color : #'+hex+';}</style>');
		}
	});
	jQuery('#icon_color_two').colpick({
		layout:'hex',
		submit:1,
		onSubmit:function(hsb,hex,rgb,el,colid) {
			jQuery(el).val('#'+hex);
			jQuery(el).css('border-color', '#'+hex);
			jQuery(el).colpickHide();
			jQuery('head').append('<style>#ssbp-preview--two .ssbp-btn:before {color : #'+hex+';}</style>');
		}
	});

	jQuery('#icon_color_hover').colpick({
		layout:'hex',
		submit:1,
		onSubmit:function(hsb,hex,rgb,el,colid) {
			jQuery(el).val('#'+hex);
			jQuery(el).css('border-color', '#'+hex);
			jQuery(el).colpickHide();
			jQuery('head').append('<style>#ssbp-preview--one .ssbp-btn:hover:before {color : #'+hex+'!important;}</style>');
		}
	});
	jQuery('#icon_color_hover_two').colpick({
		layout:'hex',
		submit:1,
		onSubmit:function(hsb,hex,rgb,el,colid) {
			jQuery(el).val('#'+hex);
			jQuery(el).css('border-color', '#'+hex);
			jQuery(el).colpickHide();
			jQuery('head').append('<style>#ssbp-preview--two .ssbp-btn:hover:before {color : #'+hex+'!important;}</style>');
		}
	});

	// show/hide previews
	jQuery('input[name="custom_images"]').on('switchChange.bootstrapSwitch', function(event, state) {
		if(state === true) {
			jQuery('#ssbp-previews').addClass('hidden');
		}
		else {
			jQuery('#ssbp-previews').removeClass('hidden');
		}
	});

	// SIZES
	jQuery( "#button_height" ).change(function() {
		jQuery('#ssbp-preview--one .ssbp-btn').css('height', jQuery(this).val()+'em');
		jQuery('#ssbp-preview--one .ssbp-btn').css('line-height', jQuery(this).val()+'em');
	});
	jQuery( "#button_two_height" ).change(function() {
		jQuery('#ssbp-preview--two .ssbp-btn').css('height', jQuery(this).val()+'em');
		jQuery('#ssbp-preview--two .ssbp-btn').css('line-height', jQuery(this).val()+'em');
	});

	jQuery( "#button_width" ).change(function() {
		jQuery('#ssbp-preview--one .ssbp-btn').css('width', jQuery(this).val()+'em');
	});
	jQuery( "#button_two_width" ).change(function() {
		jQuery('#ssbp-preview--two .ssbp-btn').css('width', jQuery(this).val()+'em');
	});

	jQuery( "#icon_size" ).change(function() {
		jQuery('head').append('<style>#ssbp-preview--one .ssbp-btn:before {font-size : '+jQuery(this).val()+'px}</style>');
	});
	jQuery( "#icon_two_size" ).change(function() {
		jQuery('head').append('<style>#ssbp-preview--two .ssbp-btn:before {font-size : '+jQuery(this).val()+'px}</style>');
	});

	jQuery( "#button_margin" ).change(function() {
		jQuery('#ssbp-preview--one .ssbp-btn').css('margin', jQuery(this).val()+'px');
	});
	jQuery( "#button_two_margin" ).change(function() {
		jQuery('#ssbp-preview--two .ssbp-btn').css('margin', jQuery(this).val()+'px');
	});


});
