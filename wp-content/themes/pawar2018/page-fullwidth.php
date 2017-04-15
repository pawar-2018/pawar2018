<?php
/*
Template name: Full Width Template
 */
get_header(); ?>

<main>
  <section class="hero inner-page" style="background-image: url('<?php the_field('hero_image'); ?>');">
    <div class="row align-center align-middle">
      <h1 class="page-title"><?php the_title(); ?></h1>
    </div>
  </section>
  <section class="wrapper">
    <div class="main body">
      <div class="row align-center">
        <div class="small-11 medium-10 columns">
          <h1 class="section-title"><?php echo the_field('inner_section_title'); ?></h1>
          <?php the_content(); ?>
        </div>
      </div>
      <div class="row align-center">
          <?php if (get_field('has_pillars')) : ?>
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
              <?php
              // clean up after the query and pagination
              wp_reset_postdata();
              ?>
            <?php endwhile; ?>
          <?php endif; ?>
        </div>
    </div>
  </section>
      <?php if (get_field('has_issues')) : ?>
      <section class="issue-accordion">
        <div class="row align-center">
          <div class="small-12 columns">
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
              <?php
              // clean up after the query and pagination
              wp_reset_postdata();
              ?>
              <?php endwhile; ?>
            </ul>
          </div>
        </div>
        <?php endif; ?>
        <div class="row align-center">
          <?php

          if (get_field('bottom_button') !== 'no button') {
              echo '<a href="' . get_field('button_1_link') . '" class="button">' . get_field('button_1_text') . '</a>';
          }
          ?>
          <?php
          if (get_field('bottom_button') === 'two buttons') {
              echo '<a href="' . get_field('button_2_link') . '" class="button">' . get_field('button_2_text') . '</a>';
          }
          ?>

        </div>
      <?php if (get_field('has_issues')) : ?>
      </section>
      <?php endif; ?>
</main>

<?php get_footer(); ?>
