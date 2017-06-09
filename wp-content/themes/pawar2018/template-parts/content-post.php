<?php ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
		<?php
		if ( is_single() ) :
			the_title( '<h1 class="section-title">', '</h1>' );
		else :
			the_title( '<h1 class="section-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;

		if ( 'post' === get_post_type() ) : ?>
        <h6 class="entre-date">
          <?php the_date('F j, Y'); ?>
        </h6>
		<?php
		endif; ?>
	</header><!-- .entry-header -->
	<div class="entry-content">
		<?php the_content(); ?>
	</div><!-- .entry-content -->
    <a href="/new-deal-journal">&larr; Back to the journal</a>
</article><!-- #post-## -->
