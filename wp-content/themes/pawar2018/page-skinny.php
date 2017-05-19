<?php
/*
Template name: Skinny Template For Forms
 */
get_header('skinny'); ?>

<main>
  <section class="wrapper">
    <div class="main body">
      <div class="row align-center">
        <div class="small-10 medium-10 columns">
          <img src="<?php echo get_bloginfo('template_url') ?>/assets/Pawar18_Logo_Horizontal_Lockup.svg" alt="Ameya Pawar 2018 Logo">
        </div>
      </div>
      <div class="row align-center">
        <div class="small-12 medium-12 columns">
          <?php the_content(); ?>
        </div>
      </div>
    </div>
  </section>
</main>

<script type="text/javascript" src="https://d1aqhv4sn5kxtx.cloudfront.net/actiontag/at.js"></script>

<?php get_footer('skinny'); ?>
