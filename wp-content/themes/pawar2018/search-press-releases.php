<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package _s
 */

get_header(); ?>
<main>
  <section class="wrapper">
    <div class="main body">
      <div class="row align-center">
        <div class="small-12 medium-11 large-11 small-order-2 medium-order-1 columns">
        	<h2 class="section-subtitle"><?php printf( esc_html__( 'Press releases for: %s', 'Pawar2018' ), '<span>' . get_search_query() . '</span>' ); ?></h2>

					<?php
					if ( have_posts() ) : ?>
						<?php
						/* Start the Loop */
						while ( have_posts() ) : the_post();
							/**
							 * Run the loop for the search to output the results.
							 * If you want to overload this in a child theme then include a file
							 * called content-search.php and that will be used instead.
							 */
							get_template_part( 'template-parts/content', 'search-press-releases' );
						endwhile;
						the_posts_navigation( array( 'prev_text' => 'More results', 'next_text' => 'Previous results' ) );
					else :
						get_template_part( 'template-parts/content', 'none' );
					endif; ?>

    		</div>
    	</div>
		</div>
		<aside class="sidebar widget-sidebar" role="complementary">
			<div class="row align-center">
				<div class="small-12 medium-8 columns">
		      <?php get_template_part( 'template-parts/widget', 'search-press-releases' ); ?>
		    </div>
		    <div class="small-12 medium-8 columns">
		    	<?php get_template_part( 'template-parts/widget', 'press-contact' ); ?>
		    </div>
	  </aside>
  </section><!-- .wrapper -->
</main>
<?php
get_footer();
