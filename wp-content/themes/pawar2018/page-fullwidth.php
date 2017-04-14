<?php
/*
Template name: Full Width Template
 */
get_header(); ?>

<main>
  <section class="hero inner-page" style="background-image: url('<?php the_field('hero_image'); ?>');">
    <div class="row align-center align-middle">
      <h1 class="page-title"><?php the_title(); ?></h1>
    </div>
  </section>
  <section class="wrapper">
    <div class="main body">
      <div class="row align-center">
        <div class="small-12 columns">
          <h1 class="section-title"><?php echo the_field('inner_section_title'); ?></h1>
          <?php the_content(); ?>
        </div>
      </div>
        <div class="row align-center">
          <?php

          if (get_field('bottom_button') !== 'no button') {
              echo '<a href="' . get_field('button_1_link') . '" class="button">' . get_field('button_1_text') . '</a>';
          }
          ?>
          <?php
          if (get_field('bottom_button') == 'two buttons') {
              echo '<a href="' . get_field('button_2_link') . '" class="button">' . get_field('button_2_text') . '</a>';
          }
          ?>

      </div>
    </div>
  </section>
</main>

<?php get_footer(); ?>
