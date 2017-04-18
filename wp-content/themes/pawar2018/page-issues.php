<?php get_header(); ?>
  <main>
  	<section class="hero inner-page" style="background-image: url('<?php echo get_bloginfo('template_url') ?>/assets/issues-hero.jpg'); background-size: cover;">
        <div class="row align-center align-middle">
            <h1 class="page-title">Issues</h1>
       </div>
  	</section>
    <section class="body">
        <div class="row align-center">
            <div class="small-11 medium-10 columns">
                <h1 class="section-title">Out with the old, in with the New Deal.</h1>
                <p>President Roosevelt’s New Deal took our country out of depression and laid the foundation for decades of growth and prosperity. We can do the same thing in Illinois by rebuilding our crumbling infrastructure, reinvesting in good-paying jobs, and recommitting to early childhood education and public schools. And we can do it without further burdening the middle class.</p>
            </div>
            <?php
                $args = array(
                'post_type' => 'pillars',
                'orderby' => 'meta_value',
                'order' => 'ASC') ?>
              <?php $loop = new WP_Query( $args ); ?>
              <?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
                <div class="feature-issue small-12 medium-10 large-5">
                    <img src="<?php echo get_field('pillar_icon') ?>" class="issue-icon" alt="School Icon">
                    <div class="issue-summary">
                      <h6><?php the_title();?></h6>
                      <p><?php the_content() ?></p>
                    </div>
                </div>
              <?php endwhile; ?>
        </div>
    </section>
    <section class="issue-accordion">
        <div class="row align-center">
            <div class="small-12 medium-10 columns">
                <ul class="accordion">
                  <?php
                    $args = array(
                    'post_type' => 'issues',
                    'orderby' => 'title',
                    'order' => 'ASC') ?>
                  <?php $loop = new WP_Query( $args ); ?>
                  <?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
                      <li class="accordion-item">
                        <a href="#" name="<?php global $post; $post_slug=$post->post_name; echo $post_slug; ?>" class="accordion-title"><?php the_title();?></a>
                        <div class="accordion-content">
                          <p><?php the_content() ?></p>
                        </div>
                      </li>
                  <?php endwhile; ?>
                </ul>
            </div>
        <div class="row">
          <a href="/events" class="button">See Ameya</a>
          <a href="https://act.myngp.com/Forms/9188561423484586496" target="_blank" class="button">Donate</a>
        </div>
      </section>
    </div>
</section>
<section class="bottom-content">
  <img src="<?php echo get_bloginfo('template_url') ?>/assets/issues-01.jpg">
</section>
</main>
<?php get_footer(); ?>
