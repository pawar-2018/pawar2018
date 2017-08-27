<?php get_header(); ?>
<main>
  <section class="hero inner-page" style="background-image: url('<?php echo get_bloginfo('template_url') ?>/assets/ameya-pawar-in-studio.jpg'); background-size: cover; background-position: 0 65%;">
    <div class="row align-center align-middle">
      <h1 class="page-title">Press Releases</h1>
    </div>
  </section>
  <section class="wrapper">
    <div class="main body">
      <div class="row align-center">
        <div class="press-release small-12 medium-11 large-11 small-order-2 medium-order-1 columns">
        <?php

        $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
        $args = array(
          'post_type' => 'press-releases',
          'posts_per_page' => 5,
          'paged' => $paged,
          'order' => 'DESC'
        ) ?>
      <?php $loop = new WP_Query( $args ); ?>
      <?php while ($loop->have_posts() ) : $loop->the_post(); ?>
          <h1 class="press-release__title section-title">
            <a href="<?php the_permalink(); ?>">
              <?php the_title();?>
            </a>
          </h1>
          <h6 class="press-release__date">
            <?php the_time( get_option( 'date_format' ) ); ?>
          </h6>
          <p><?php the_content( 'Read more...') ?></p>
          <?php
          // clean up after the query and pagination
          wp_reset_postdata();
          ?>
          <?php endwhile; ?>
        </div>
      </div>
      <div class="pagination-button row align-center">
        <?php echo get_next_posts_link( 'See More', $loop->max_num_pages ); ?>
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
      </div>
    </aside>
  </section>
</main>
<?php get_footer(); ?>
