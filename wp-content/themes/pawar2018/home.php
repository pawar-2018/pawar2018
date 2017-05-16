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
        					<?php while ( have_posts() ) : the_post();
        						get_template_part( 'template-parts/content-index-post', get_post_format() );
        					endwhile;

        				endif; ?>
                    </div>
			   </div>
            </div>
		</div>
        <aside class="sidebar" role="complementary">
          <div class="row align-center">
            <div class="small-12 medium-8 columns">
                <?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
                    <div class="widget-sidebar">
                  	    <?php dynamic_sidebar( 'sidebar-1' ); ?>
                    </div>
                <?php endif; ?>
            </div>
          </div>
        </aside>
	</section>
</main>
<?php
get_footer();
