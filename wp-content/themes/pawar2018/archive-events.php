<?php get_header(); ?>
  <main>
  	<section class="hero inner-page" style="background-image: url('<?php echo get_bloginfo('template_url') ?>/assets/events-hero.jpg'); background-size: cover;">
        <div class="row align-center align-middle">
            <h1 class="page-title">Events</h1>
       </div>
  	</section>
    <section class="body">
        <div class="row align-center">
            <div class="small-11 medium-5 large-4 small-order-2 medium-order-1 columns">
                <h1 class="section-title">Let's Talk</h1>
                <p>Looking to host an event?</p>
                <a href='/get-involved' class="button">Contact Us</a>
            </div>
            <div class="small-12 medium-5 large-6 small-order-1 medium-order-2 columns">
            <?php
              $today = date('Ymd');
              $args = array(
              'post_type' => 'events',
              'posts_per_page' => -1,
              'meta_key' => 'start_date',
              'orderby' => 'meta_value_num',
              'order' => 'ASC',
              'meta_query'  => array(
                array(
                    'key' => 'start_date',
                    'type' => 'NUMERIC',
                    'value' => $today,
                    'compare' => '>=', // Greater than or equal to value
                    )
                ),
            );

              ?>
              <?php $loop = new WP_Query( $args ); ?>
              <?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
              <div class="event row align-center">
                <div class="small-3 medium-10 large-2 columns">
                    <div class="event-date_circle">
                        <?php $date = get_field('start_date');
                          $day = date("j", strtotime($date));
                          $month = date("F", strtotime($date));
                        ?>
                        <?php echo $day; ?><br><?php echo $month; ?>
                  </div>
                </div>
                <div class="small-11 large-7 large-offset-1 columns event-copy">
                    <?php if (get_field('link')) : ?>
                    <a href="<?php the_field('link'); ?>">
                    <?php endif; ?>
                        <p class="event-date">
                            <?php the_field('start_date'); ?> at <?php the_field('start_time'); ?>
                        </p>
                        <h5 class="event-title">
                            <?php the_title();?>
                        </h5>
                        <p class="event-locale"><?php the_field('location');?></p>
                        <span class="event-address"><?php the_field('address');?></span>
                    <?php if (get_field('link')) : ?>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
          <?php endwhile; ?>
         </div>
        </div>
    </div>
</section>
</main>
<?php get_footer(); ?>
