<?php get_header(); ?>
<main>
	<section>
		<div class="row">
			<?php
			/* Start the Loop */
			while ( have_posts() ) : the_post();
				get_template_part( 'template-parts/content-post', get_post_format() );

			endwhile; ?>
		</div>
	</section>
</main><!-- #main -->
<?php get_footer(); ?>
