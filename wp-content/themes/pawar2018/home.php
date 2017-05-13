<?php get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
            <header class="page-header ">
                <section class="hero inner-page" style="background-image: url('<?php the_field('hero_image'); ?>');">
                  <div class="row align-center align-middle">
                    <h1 class="page-title"><?php the_title(); ?></h1>
                  </div>
                </section>
            </header><!-- .page-header -->
			<div class="row align-center">
				<?php if ( have_posts() ) : ?>
					<?php while ( have_posts() ) : the_post();
						get_template_part( 'template-parts/content-index-post', get_post_format() );
					endwhile;

				endif; ?>
			</div>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
