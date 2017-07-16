<?php
/*
Plugin Name: Eventbrite API - One Hour Cache
Plugin URI: https://github.com/Automattic/eventbrite-api-cache-one-hour
Description: Reduce the default cache time from one day to one hour.
Version: 1.0
Author: Automattic
Author URI: http://automattic.com
License: GPL v2 or newer <https://www.gnu.org/licenses/gpl.txt>
*/

/**
 * Reduce the default cache time from one day to one hour.
 */
function eventbrite_api_cache_one_hour() {
	return MINUTE_IN_SECONDS;
}
add_filter( 'eventbrite_cache_expiry', 'eventbrite_api_cache_one_hour' );
