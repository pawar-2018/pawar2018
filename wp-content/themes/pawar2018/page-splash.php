<?php
/*
Template name: Splash Template
 */
?>
<?php get_template_part('template-parts/header', 'head'); ?>

<main class="splash" style="background-image: url('<?php the_field('splash_image'); ?>');">
  <section class="wrapper">
    <div class=" main">
      <div class="row align-center align-middle content">
        <div class="small-10 large-4 columns">
        <a href="<?php the_field('splash_link'); ?>" target="_blank">
          <img src="<?php the_field('splash_logo'); ?>" alt="Ameya Pawar 2018 Logo">
        </a>
        </div>
        <div class="small-10 large-6 columns">
          <?php the_content(); ?>
        </div>
      </div>
    </div>
  </section>
</main>
