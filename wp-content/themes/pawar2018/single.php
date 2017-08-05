<?php get_header(); ?>
<main>
	<section class="wrapper">
		<div class="main body">
			<div class="row align-center">
				<div class="small-12 medium-10 columns">
					<?php
					/* Start the Loop */
					while ( have_posts() ) : the_post();
						get_template_part( 'template-parts/content-post', get_post_format() );

					endwhile; ?>

				</div>
			</div>
		</div>
	</section>
</main><!-- #main -->
<?php get_footer(); ?>
