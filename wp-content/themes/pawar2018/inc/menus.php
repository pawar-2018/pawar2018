<?php

function get_items_by_location( $theme_location ) {
	$locations = get_nav_menu_locations();
	$menu_id = $locations[ $theme_location ] ;
	$menu = wp_get_nav_menu_object($menu_id);
	return wp_get_nav_menu_items( $menu->term_id, array( 'order' => 'DESC' ) );
}
