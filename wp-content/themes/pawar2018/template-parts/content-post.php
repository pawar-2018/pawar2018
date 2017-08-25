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
        <h6 class="entry-meta">
          <?php the_date('F j, Y'); ?> by <?php the_author(); ?>
        </h6>
        <!--
        <div class="social-share-btns">
            <a class="social-btn twitter" target="_blank" href="https://twitter.com/share?url=<?php echo(the_permalink());?>&via=ameya_pawar_il&text=<?php echo(the_title()); ?>">Tweet
            </a>
        </div>
        -->
		<?php endif; ?>
	</header><!-- .entry-header -->
	<div class="entry-content">
		<?php the_content(); ?>
	</div><!-- .entry-content -->
    <a href="/new-deal-journal">&larr; Back to the New Deal Journal</a>
</article><!-- #post-## -->
