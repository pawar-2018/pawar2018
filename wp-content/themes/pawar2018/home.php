<?php get_header(); ?>
<main>
    <section class="wrapper">
      <div class="main body">
			<div class="row align-center">
                <div class="press-release small-11 medium-11 large-11 small-order-2 medium-order-1 columns">
                    <div class="small-12 columns">
                    <h1 class="section-title">
                        <?php single_post_title(); ?>
                    </h1>
                    </div>
                    <div class="small-12 columns">
        				<?php if ( have_posts() ) : ?>
        					<?php while ( have_posts() ) : the_post(); ?>
                            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                                <header class="entry-header">
                                    <?php
                                    if ( is_single() ) :
                                        the_title( '<h2 class="post-title">', '</h2>' );
                                    else :
                                        the_title( '<h2 class="post-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
                                    endif;

                                    if ( 'post' === get_post_type() ) : ?>
                                    <h6 class="entre-date">
                                      <?php the_date('F j, Y'); ?>
                                    </h6>
                                    <?php
                                    endif; ?>
                                </header><!-- .entry-header -->
                                <div class="entry-content">
                                    <?php the_excerpt(); ?>
                                </div><!-- .entry-content -->
                            </article><!-- #post-## -->
        				<?php endwhile; ?>
        				<?php endif; ?>
                    </div>
			   </div>
            </div>
		</div>
	</section>
</main>
<?php
get_footer();
