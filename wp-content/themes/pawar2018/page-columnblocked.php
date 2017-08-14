<?php
/*
Template name: Column Blocked Template
 */
get_header(); ?>

<main class="two-column">
  <section class="hero inner-page" style="background-image: url('<?php the_field('hero_image'); ?>');">
    <div class="row align-center align-middle">
      <h1 class="page-title"><?php the_title(); ?></h1>
    </div>
  </section>
  <section class="wrapper">
    <div class="main body">
      <div class="row align-center">
        <div class="small-12 medium-12 columns">
          <h1 class="section-title special-event__title"><?php echo the_field('left_column_title'); ?></h1>
          <h2 class="section-subtitle special-event__title"><?php echo the_field('left_column_subtitle'); ?></h2>
         <?php echo the_field('first_column'); ?>
       </div>
     </div>
   </div>
   <aside class="sidebar" role="complementary">
      <div class="row align-center">
        <div class="small-12 medium-12 columns">
         <h1 class="section-title special-event__title"><?php echo the_field('right_column_title'); ?></h1>
          <h2 class="section-subtitle special-event__title"><?php echo the_field('right_column_subtitle'); ?></h2>
          <?php echo the_field('second_column'); ?>
        </div>
      </div>
    </aside>
  </section>
  <div class="row align-center">
    <?php

    if (get_field('bottom_button') !== 'no button') {
        echo '<a href="' . get_field('button_1_link') . '" class="button">' . get_field('button_1_text') . '</a>';
    }
    ?>
    <?php
    if (get_field('bottom_button') === 'two buttons') {
        echo '<a href="' . get_field('button_2_link') . '" class="button">' . get_field('button_2_text') . '</a>';
    }
    ?>

  </div>
</main>

<?php get_footer(); ?>
