<?php
/*
Template name: Skinny Template For Forms
 */
get_header('skinny'); ?>

<main>
  <section class="wrapper">
    <div class="main body">
      <div class="row align-center">
        <div class="small-12 medium-12 columns">
          <?php the_content(); ?>
          <div class="ngp-form" data-id="<?php echo get_post_meta(get_the_ID(), 'form_id', true); ?>"></div>
          <p></p>
        </div>
      </div>
    </div>
  </section>
</main>

<script type="text/javascript" src="https://d1aqhv4sn5kxtx.cloudfront.net/actiontag/at.js"></script>

<?php get_footer('skinny'); ?>
