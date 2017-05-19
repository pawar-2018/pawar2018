jQuery(document).ready(function(){

	dataGeoIP = {
			action: 'ssbp_geoip_stats'
			}

	// post each tracking  via ajax
	jQuery.post(ajaxurl, dataGeoIP, function(response) {
		// display tracking
		jQuery('.ssbp-geoip-container').replaceWith(response).fadeIn(500);
	});

	dataTotal = {
			action: 'ssbp_total_shares'
			}

	// post each tracking  via ajax
	jQuery.post(ajaxurl, dataTotal, function(response) {
		// display tracking
		jQuery('.ssbp-total-container').replaceWith(response).fadeIn(500);
	});
	
	dataTop = {
			action: 'ssbp_top_three'
			}

	// post each tracking  via ajax
	jQuery.post(ajaxurl, dataTop, function(response) {
		// display tracking
		jQuery('.ssbp-top-container').replaceWith(response).fadeIn(500);
	});

	dataLatest = {
			action: 'ssbp_latest_shares'
			}

	// post each tracking  via ajax
	jQuery.post(ajaxurl, dataLatest, function(response) {
		// display tracking
		jQuery('.ssbp-latest-container').replaceWith(response).fadeIn(500);
	});

});