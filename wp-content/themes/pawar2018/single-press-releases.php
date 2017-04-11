<?php get_header(); ?>

<main>
  <section class="hero inner-page" style="background-image: url('<?php echo get_bloginfo('template_url') ?>/assets/press-releases-hero.jpg'); background-size: cover; background-position-y: 28%;">
    <div class="row align-center align-middle">
      <h1 class="page-title"><?php the_title(); ?></h1>
    </div>
  </section>
  <section class="wrapper">
    <div class="main body">
      <div class="row align-center">
        <div class="small-12 medium-12 columns">
          <h1 class="section-title"><?php echo the_title(); ?></h1>
          <h6 class="press-release__date"><?php the_date('F jS, Y'); ?></h6>
          <?php the_content(); ?>
        </div>
      </div>
    </div>
  </section>
</main>

<?php get_footer(); ?>
