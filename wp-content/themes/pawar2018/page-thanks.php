<?php
/*
Template name: Thanks Template
 */
?>

<main>
  <section class="hero thanks" style="background-image: url('<?php the_field('hero_image'); ?>');">
    <div class="row align-center align-middle">
      <img src="<?php the_field('bottom_logo'); ?>" alt="Header Logo">
    </div>
    <div class="row align-center align-middle">
      <h1 class="page-title"><?php the_title(); ?></h1>
    </div>
    <div class="row align-center align-middle">
      <h2 class="page-title"><?php echo the_field('inner_section_subtitle'); ?></h2>
    </div>
    <div class="row align-center">
      <?php echo '<a href="' . get_field('button_1_link') . '" class="button">' . get_field('button_1_text') . '</a>';
      ?>
      <?php echo '<a href="' . get_field('button_2_link') . '" class="button">' . get_field('button_2_text') . '</a>';
      ?>
    </div>
  </section>
  <section class="wrapper">
    <div class="main body">
      <div class="row align-center">
        <div class="small-11 medium-10 columns">
          <h1 class="section-title">
            <?php echo the_field('inner_section_title'); ?>
          </h1>
          <?php the_content(); ?>
        </div>
      </div>
      <div class="row align-center">
        <div class="small-11 medium-10 columns">
          <img src="<?php the_field('bottom_logo'); ?>" alt="One Illinois Logo">
          <?php echo '<a href="' . get_field('bottom_button_link') . '" class="button">' . get_field('button_2_text') . '</a>';
          ?>
        </div>
      </div>
    </div>
  </section>
</main>

<?php get_footer(); ?>
