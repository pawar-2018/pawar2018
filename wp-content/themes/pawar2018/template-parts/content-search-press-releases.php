<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package _s
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="press-release__title section-title">
      <a href="<?php the_permalink(); ?>">
        <?php the_title();?>
      </a>
    </h1>
    <h6 class="press-release__date">
      <?php the_time( get_option( 'date_format' ) ); ?>
    </h6>
	</header><!-- .entry-header -->

	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->
</article><!-- #post-## -->
