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

          $bottom_button = get_field('bottom_button');

          if ($bottom_button !== 'no button') {
              echo '<a href="' . $button_1_link . '" class="button">' . $button_1_text . '</a>';
          }
          if ($bottom_button == 'two buttons') {
              echo '<a href="' . $button_2_link . '" class="button">' . $button_2_text . '</a>';
          }

          <?php endif; ?>
      </div>
    </div>
  </section>
</main>

<?php get_footer(); ?>
