<?php get_header(); ?>
<main>
	<section class="wrapper">
		<div class="main body">
			<div class="row align-center">
				<div class="small-12 medium-10 columns">
					<?php while ( have_posts() ) : the_post(); ?>
						<?php get_template_part( 'template-parts/content-post', get_post_format());?>

						<a class="social-btn twitter" target="_blank" href="https://twitter.com/share?url=<?php echo(the_permalink());?>&via=ameya_pawar_il&text=<?php echo(the_title()); ?>">
						<i class="fa fa-twitter"></i>Tweet
					   </a>
					<?php endwhile; ?>

				</div>
			</div>
		</div>
	</section>
</main><!-- #main -->
<?php get_footer(); ?>
