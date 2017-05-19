<?php get_header(); ?>
  <div class="promo-banner">
    <h1 class="banner-headline">
      Join Ameya at the June 15th Virtual Town Hall.
      <a href="http://watchparty.pawar2018.com" target="_blank">RSVP Now</a>
    </h1>
  </div>
  <main>
    <section class="hero" style="background-image: url('<?php the_field('hero_image'); ?>');">
      <div class="row align-bottom">
        <div class="small-11 large-9 columns">
          <h1><?php the_field('hero_headline'); ?></h1>
        </div>
      </div>
      <div class="row align-middle">
        <div class="small-11 large-7 columns">
          <h2><?php the_field('hero_subheadline'); ?></h2>
        </div>
      </div>
      <div class="row align-middle">
        <div class="small-11 columns">
          <h3><?php the_field('hero_cta'); ?></h3>
          <a href="<?php the_field('hero_button_link'); ?>" class="button">
            <?php the_field('hero_button_text'); ?>
          </a>
        </div>
      </div>
    </section>
    <section class="row main-content align-center align-middle">
      <?php if (get_field('has_video')) : ?>
        <div class="small-11 large-6 columns">
          <div class="flex-video widescreen">
            <?php the_field('featured_video'); ?>
          </div>
        </div>
      <div class="small-11 large-6 columns">
      <?php else: ?>
      <div class="small-11 large-4 columns">
        <h1 class="main-callout"><?php the_field('main_callout'); ?></h1>
      </div>
      <div class="small-11 large-8 columns">
      <?php endif; ?>
        <?php get_field('has_video') ? echo '' : echo '<h1 class="main-callout">' . get_field('main-callout') . '</h1>';
        ?>
        <p class="main-copy">
          <?php the_field('callout_copy'); ?>
        </p>
      </div>
    </section>
    <section class="action-content">
      <div class="pillar-wrapper">
        <h4 class="section-title"><?php the_field('pillar_headline'); ?></h4>
        <h1 class="main-callout">
          <?php the_field('pillar_subheadline'); ?>
        </h1>
        <div class="row small-up-1">
          <?php $loop = new WP_Query( $args ); ?>
          <?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
            <div class="column column-block pillar-content">
              <img src="<?php echo get_field('pillar_icon') ?>" class="issue-icon" alt="<?php echo get_field('pillar_alt') ?>">
              <div class="pillar-copy">
                <h4 class="pillar-header"><?php the_title();?></h4>
                <p><?php the_content() ?></p>
              </div>
            </div>
            <?php
            // clean up after the query and pagination
            wp_reset_postdata();
            ?>
          <?php endwhile; ?>
        </div>
        <div class="pillar-button">
          <a href="<?php the_field('pillar_button_link'); ?>" class="button">
            <?php the_field('pillar_button_text'); ?>
          </a>
        </div>
      </div>
      <div class="event-wrapper">
        <div class="event-content">
          <h4 class="section-title">
            <?php the_field('event_headline'); ?>
          </h4>
          <h1 class="main-callout"><?php the_field('event_subheadline'); ?></h1>
          <div class="row small-collapse">
             <?php
                $today = date('Ymd');
                $args = array(
                'post_type' => 'events',
                'posts_per_page' => 2,
                'meta_key' => 'start_date',
                'orderby' => 'meta_value_num',
                'order' => 'ASC',
                'meta_query'  => array(
                      array(
                          'key' => 'start_date',
                          'type' => 'NUMERIC',
                          'value' => $today,
                          'compare' => '>=',
                          )
                      ),
                  );

              ?>
              <?php $loop = new WP_Query( $args ); ?>
              <?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
              <div class="column small-11 large-8 event-copy">
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
              <?php wp_reset_postdata(); ?>
             <?php endwhile; ?>
          </div>
          <div class="column event-copy">
            <a href="<?php echo esc_url( home_url( '/events' )) ?>" class="button">
              See All
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
                    $my_query = new WP_Query($args);
                    $count = $my_query->post_count;
                    echo $count;
               ?>
              Events
            </a>
          </div>
        </div>
        <div class="event-photo">
          <img src="<?php echo get_field('event_photo') ?>" class="event-photo" alt="<?php echo get_field('event_photo_alt') ?>">
        </div>
      </div>
    </section>
    <div class="row align-middle align-center">
      <div class="small-11 large-10 columns">
        <h1 class="main-quote">
          <?php the_field('main_quote'); ?>
        </h1>
        <p class="main-caption">
          <?php the_field('quote_caption'); ?>
        </p>
      </div>
    </div>
    <div class="row bottom-content small-collapse align-middle align-justify">
      <div class="small-12 large-6 columns">
        <img src="<?php echo get_field('bottom_first_photo') ?>" alt="<?php echo get_field('bottom_first_photo_alt') ?>">
      </div>
      <div class="small-12 large-6 columns">
        <img src="<?php echo get_field('bottom_second_photo') ?>" alt="?php echo get_field('bottom_second_photo_alt') ?>">
      </div>
    </div>
  </div>
  </main>
<?php get_footer(); ?>
