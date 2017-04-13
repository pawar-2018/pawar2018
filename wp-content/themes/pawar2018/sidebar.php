<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package _s
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>

<aside id="secondary" class="sidebar" role="complementary">
  <div class="row align-center">
    <div class="small-12 medium-8 columns">
    	<?php dynamic_sidebar( 'sidebar-1' ); ?>
    </div>
  </div>
</aside>
