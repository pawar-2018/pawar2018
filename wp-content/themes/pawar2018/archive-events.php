<?php 
/*
Template name: Eventbrite Events
 */
get_header(); ?>

<main>
  <section class="hero inner-page"
           style="background-image: url('<?php echo get_bloginfo('template_url') ?>/assets/events-hero.jpg'); background-size: cover;">
    <div class="row align-center align-middle">
      <h1 class="page-title"><?php if(function_exists('pll_e')) pll_e('Events'); ?></h1>
    </div>
  </section>
  <section class="body">
    <div class="row align-center">
      <div class="small-11 medium-5 large-4 small-order-2 medium-order-1 columns">
        <h1 class="section-title"><?php if(function_exists('pll_e')) pll_e("Let's Talk"); ?></h1>
        <p><?php if(function_exists('pll_e')) pll_e('Looking to host an event?'); ?></p>
        <a href="<?php if(function_exists('pll_e')) pll_e('/get-involved'); ?>" class="button"><?php if(function_exists('pll_e')) pll_e('Contact Us'); ?></a>
      </div>
      <div class="small-12 medium-5 large-6 small-order-1 medium-order-2 columns">

        <?php
        error_log('archive');
        // Set up and call our Eventbrite query.
        $events = new Eventbrite_Query(apply_filters('eventbrite_query_args', array(
          'nopaging' => true,
          'organizer_id' => 13080157631
          )));

        if ($events->have_posts()) :
          while ($events->have_posts()) :
            $events->the_post(); ?>

            <div class="event row align-center">
              <div class="small-3 medium-10 large-2 columns">
                <div class="event-date_circle">
                  <?php $date = date_create(eventbrite_event_start()->local); ?>
                  <?php echo date_format($date, 'd'); ?><br><?php echo date_i18n('F', $date->getTimestamp()); ?>
                </div>
              </div>
              <div id="event-<?php the_ID(); ?>" class="small-11 large-7 large-offset-1 columns event-copy">
                <a href="<?= eventbrite_event_eb_url(); ?>">

                  <p class="event-date">
                    <?php
                      $formatString = function_exists('pll_e') ? pll__('l, F d \a\t g:i a') : 'l, F d \a\t g:i a';
                      echo date_i18n( $formatString, strtotime(eventbrite_event_start()->local) );
                    ?>
                  </p>

                  <?php the_title(sprintf('<h5 class="event-title">', esc_url(get_permalink())), '</h5>'); ?>

                  <p class="event-locale"><?= eventbrite_event_venue()->name; ?><br/>
                  <?php echo eventbrite_event_venue()->address->localized_multi_line_address_display[0]; ?><br/>
                  <?php echo eventbrite_event_venue()->address->localized_multi_line_address_display[1]; ?></p>

                  <footer class="entry-footer">
                    <?php eventbrite_edit_post_link(__('Edit', 'eventbrite_api'), '<span class="edit-link">', '</span>'); ?>
                  </footer><!-- .entry-footer -->
                </a>
              </div>
            </div>

          <?php endwhile;

        else :
          // If no content, include the "No posts found" template.
          get_template_part('content', 'none');

        endif;

        // Return $post to its rightful owner.
        wp_reset_postdata();
        ?>

  </section>
</main>

<?php get_footer(); ?>
