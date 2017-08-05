<?php get_header(); ?>
<main class="ndj">
    <section class="hero inner-page" style="background-image: url('<?php the_field('hero_image', 298); ?>');">
    </section>
    <section class="wrapper">
      <div class="main body">
          <div class="row align-center">
            <div class="small-12 medium-8 columns text-center">
                <span class="section-title"></span>
              <?php $page = get_page_by_title( 'New Deal Journal' ); ?>
              <?=$page->post_content;?>
            </div>
          </div>
			<div class="row align-center">
                <div class="press-release small-11 medium-11 large-11 small-order-2 medium-order-1 columns">
                    <div class="row">
        				<?php if ( have_posts() ) : ?>
        					<?php while ( have_posts() ) : the_post(); ?>
                            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                                <aside class="feature-image">
                                    <?php the_post_thumbnail(); ?>
                                </aside>
                                <div class="article-inner">
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
                                </div>
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
