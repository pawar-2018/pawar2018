<?php get_header(); ?>
<main>
	<section class="wrapper">
		<div class="main body">
			<div class="row align-center">
				<div class="small-12 medium-10 columns">
					<?php while ( have_posts() ) : the_post(); ?>
						<?php get_template_part( 'template-parts/content-post', get_post_format());?>
					<?php endwhile; ?>
				</div>
			</div>
		</div>
		<?php
		  $args = array(
			  'category__in' => wp_get_post_categories($post->ID),
			  'posts_per_page' => 1,
			  'post__not_in' => array($post->ID))
			  ?>
		<?php $related = new WP_Query($args);?>
		<?php if ($related->have_posts()) : ?>
			 <?php while ($related->have_posts()) : $related->the_post(); ?>
				 <div class="related-section">
					 <div class="main row align-center">
						 <div class="small-12 medium10 columns">
							 <h3>UP NEXT</h3>
							 <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
								<aside class="feature-image">
									<?php the_post_thumbnail(); ?>
								</aside>
								<div class="article-inner">
									<a href="<?php the_permalink(); ?>"><h3><?php the_title(); ?></h3></a>
								</div>
							</article>
						</div>
					</div>
				</div>
			<?php endwhile; ?>
		<?php endif; ?>
		<?php wp_reset_postdata(); ?>
	</section>
</main><!-- #main -->
<?php get_footer(); ?>
