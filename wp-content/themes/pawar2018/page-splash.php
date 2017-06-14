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
        <div class="small-12 large-6 columns">
          <?php the_content(); ?>
        </div>
      </div>
      <?php if (get_field('has_social_buttons')) : ?>
        <div class="row align-middle social">
          <div class="small-11 large-6 columns">
            <div class="splash-social">
              <a class="splash-social__link" href="https://www.facebook.com/AmeyaPawarIL/" target="_blank" aria-label="Link to Facebook AmeyaPawarIL">
                <img src="<?php echo get_bloginfo('template_url') ?>/assets/facebook.svg" alt="Facebook Icon">
              </a>
              <a class="splash-social__link" href="https://twitter.com/Ameya_Pawar_IL" target="_blank" aria-label="Link to Twitter Ameya_Pawar_IL">
                <img src="<?php echo get_bloginfo('template_url') ?>/assets/twitter.svg" alt="Twitter Icon">
              </a>
              <a class="splash-social__link" href="https://www.instagram.com/ameya_pawar_IL" target="_blank" aria-label="Link to Instagram ameya.s.pawar">
                <img src="<?php echo get_bloginfo('template_url') ?>/assets/instagram.svg" alt="Instagram Icon">
              </a>
              <a class="splash-social__link" href="https://www.youtube.com/user/RenewChicago/videos" target="_blank" aria-label="Link to Youtube RenewChicago">
                <img src="<?php echo get_bloginfo('template_url') ?>/assets/youtube.svg" alt="Youtube Icon">
              </a>
              <a class="splash-social__link" href="https://shop.pawar2018.com" target="_blank" aria-label="Link to Campaign Shop">
                <img src="<?php echo get_bloginfo('template_url') ?>/assets/shopping-icon2.svg" alt="Shop Icon">
              </a>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </section>
</main>
