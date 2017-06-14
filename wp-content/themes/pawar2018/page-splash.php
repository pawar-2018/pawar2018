<?php
/*
Template name: Splash Template
 */
?>

<main>
  <section class="wrapper">
    <div class="splash main" style="background-image: url('<?php the_field('splash_image'); ?>');">
      <div class="row align-center align-top content">
        <div class="small-10 medium-5 columns">
          <img src="<?php echo get_bloginfo('template_url') ?>/assets/pawar2018_logo.png" alt="Ameya Pawar 2018 Logo">
        </div>
        <div class="small-10 medium-5 columns">
          <?php the_content(); ?>
        </div>
      </div>
    </div>
  </section>
</main>
