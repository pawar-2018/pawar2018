<?php get_header(); ?>
<main>
	<section class="wrapper">
		<div class="main body">
			<div class="row align-center">
				<div class="small-10 columns">
					<?php
					/* Start the Loop */
					while ( have_posts() ) : the_post();
						get_template_part( 'template-parts/content-post', get_post_format() );

					endwhile; ?>

				</div>
			</div>
		</div>
		<aside class="sidebar" role="complementary">
          <div class="row align-center">
            <div class="small-12 medium-8 columns">
                <?php if ( is_active_sidebar( 'sidebar-2' ) ) : ?>
                    <div class="widget-sidebar">
                  	    <?php dynamic_sidebar( 'sidebar-2' ); ?>
                    </div>
                <?php endif; ?>
            </div>
          </div>
        </aside>
	</section>
</main><!-- #main -->
<?php get_footer(); ?>
