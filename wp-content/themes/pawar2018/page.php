<?php get_header(); ?>
<main>
	<!-- Hero image will go here when we build it out
	<section class="hero inner-page" style="background-image: url('<?php echo get_bloginfo('template_url') ?>/assets/get-involved-hero.jpg'); background-size: cover;">
		<div class="row align-center align-middle">
			<h1 class="page-title"><?php single_post_title(); ?></h1>
		</div>
	</section>
	-->
	<section class="wrapper">
		<div class="main body">
			<div class="row align-center">
				<div class="small-11 medium-10 columns">
					<?php
					if ( have_posts() ) :


						/* Start the Loop */
						while ( have_posts() ) : the_post();

							/*
                             * Include the Post-Format-specific template for the content.
                             * If you want to override this in a child theme, then include a file
                             * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                             */
							get_template_part( 'template-parts/content', get_post_format() );

						endwhile;

						the_posts_navigation();

					else :

						get_template_part( 'template-parts/content', 'none' );

					endif; ?>
					</div>
				</div>
			</div>
	</section>
</main>
<?php get_footer(); ?>

