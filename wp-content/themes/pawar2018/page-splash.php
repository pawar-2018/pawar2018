<?php
/*
Template name: Splash Template
 */
?>
<?php get_template_part('template-parts/header', 'head'); ?>

<main>
  <section class="wrapper">
    <div class="splash main" style="background-image: url('<?php the_field('splash_image'); ?>');">
      <div class="row align-center align-middle content">
        <div class="small-10 medium-4 columns">
        <a href="<?php the_field('splash_link'); ?>" target="_blank">
          <img src="<?php the_field('splash_logo'); ?>" alt="Ameya Pawar 2018 Logo">
        </a>
        </div>
        <div class="small-10 medium-6 columns">
          <?php the_content(); ?>
        </div>
      </div>
    </div>
  </section>
</main>
