<?php get_header(); ?>
<main>
  <section class="hero inner-page" style="background-image: url('<?php echo get_bloginfo('template_url') ?>/assets/press-releases-hero.jpg'); background-size: cover;">
    <div class="row align-center align-middle">
      <h1 class="page-title">Press Releases</h1>
    </div>
  </section>
  <section class="body">
    <?php

      $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
      $args = array(
        'post_type' => 'press-releases',
        'posts_per_page' => 5,
        'meta_key' => 'date',
        'orderby' => 'meta_value_num',
        'paged' => $paged,
        'order' => 'DESC') ?>
    <?php $loop = new WP_Query( $args ); ?>
    <?php while ($loop->have_posts() ) : $loop->the_post(); ?>
    <div class="row align-center">
      <div class="press-release small-11 medium-11 large-11 small-order-2 medium-order-1 columns">
        <h1 class="press-release__title section-title"><?php the_title();?></h1>
        <h6 class="press-release__date"><?php the_field('date'); ?></h6>
        <p><?php the_field('content'); ?></p>
      </div>
    </div>
    <?php endwhile; ?>

<div class="pagination-button row align-center">
      <?php echo get_next_posts_link( 'See More', $loop->max_num_pages ); ?>
    </div>
  </section>
</main>
<?php get_footer(); ?>
