<?php get_header(); ?>

    <section class="wrapper">
      <div class="main body">

			<div class="row align-center">
                <div class="small-12 columns">
                <h1 class="section-title"><?php single_post_title(); ?></h1>
                </div>
				<?php if ( have_posts() ) : ?>
					<?php while ( have_posts() ) : the_post();
						get_template_part( 'template-parts/content-index-post', get_post_format() );
					endwhile;

				endif; ?>
			</div>
		</div><!-- #main -->
	</section><!-- #primary -->

<?php
get_footer();
