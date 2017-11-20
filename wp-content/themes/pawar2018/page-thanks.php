<?php get_template_part('template-parts/header', 'head');
/*
Template name: Thanks Template
 */
?>

<main class="thanks">
  <section class="hero" style="background-image: url('<?php the_field('hero_image'); ?>');">
    <div class="row align-center align-middle">
      <div class="small-11 shrink columns">
        <img src="<?php the_field('header_logo'); ?>" alt="Header Logo">
      </div>
    </div>
    <div class="row align-center align-middle">
      <div class="small-11 shrink columns">
        <h1 class="page-title">
          <?php the_title(); ?>
        </h1>
      </div>
    </div>
    <div class="row align-center align-middle">
      <div class="small-11 shrink columns">
        <h2 class="page-subtitle">
          <?php echo the_field('inner_section_subtitle'); ?>
        </h2>
      </div>
    </div>
    <div class="row align-center">
      <div class="small-11 shrink columns">
        <?php echo '<a href="' . get_field('button_1_link') . '" class="button">' . get_field('button_1_text') . '</a>';
        ?>
      </div>
      <div class="small-11 shrink columns">
        <?php echo '<a href="' . get_field('button_2_link') . '" class="button">' . get_field('button_2_text') . '</a>';
        ?>
      </div>
    </div>
  </section>
  <section class="wrapper">
    <div class="main body">
      <div class="row align-center">
        <div class="small-12 medium-10 columns">
          <h1 class="section-title">
            <?php echo the_field('inner_section_title'); ?>
          </h1>
          <?php the_content(); ?>
        </div>
      </div>
      <div class="row align-center align-middle">
        <div class="small-11 shrink columns">
          <img src="<?php the_field('bottom_logo'); ?>" alt="One Illinois Logo">
        </div>
        <div class="small-11 shrink columns">
          <?php echo '<a href="' . get_field('bottom_button_link') . '" class="button">' . get_field('bottom_button_text') . '</a>';
          ?>
        </div>
      </div>
    </div>
  </section>
</main>

<?php get_template_part('template-parts/footer', 'paid'); ?>

<?php wp_footer(); ?>
