<?php get_header(); ?>

<main>
  <section class="hero inner-page" style="background-image: url('<?php echo get_bloginfo('template_url') ?>/assets/ameya-pawar-in-studio.jpg'); background-size: cover; background-position: 0 65%;">
    <div class="row align-center align-middle">
      <h1 class="page-title">Press Releases</h1>
    </div>
  </section>
  <section class="wrapper">
    <div class="main body">
      <div class="row align-center">
        <div class="small-12 medium-12 columns">
          <h1 class="press-release__title section-title"><?php echo the_title(); ?></h1>
          <h6 class="press-release__date"><?php the_time( get_option( 'date_format' ) ); ?></h6>
          <?php the_content(); ?>
        </div>
      </div>
    </div>
    <aside class="sidebar" role="complementary">
      <div class="row align-center">
        <div class="small-12 medium-8 columns">
          <p><strong>Contact: Tom Elliott</strong><br/>
          773-819-0503<br/>
          <a href="mailto:media@pawar2018.com">media@pawar2018.com</a></p>
        </div>
      </div>
    </aside>
  </section>
</main>

<?php get_footer(); ?>
