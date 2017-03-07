<?php get_header(); ?>
<main>
	<section>
		<div class="row">
			<?php
				if ( have_posts() ) {
					while ( have_posts() ) {
						the_post();
					}
				}
			?>
		</div>
	</section>
</main><!-- #main -->
<?php get_footer(); ?>
