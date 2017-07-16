<?php
/*
Template name: Two Column Template
 */
get_header(); ?>

<main>
  <section class="hero inner-page" style="background-image: url('<?php the_field('hero_image'); ?>');">
  </section>
  <section class="wrapper">
    <div class="main body">
      <div class="row align-center">
        <div class="small-12 columns">
          <h1 class="section-title special-event__title"><?php echo the_field('inner_section_title'); ?></h1>
          <h2 class="section-subtitle special-event__title"><?php echo the_field('inner_section_subtitle'); ?></h1>
        </div>
      </div>
      <div class="row align-spaced small-collapse large-uncollapse">
        <div class="small-12 medium-6 xlarge-7 columns special-event__copy">
          <?php the_content(); ?>
        </div>
        <div class="small-12 medium-5 xlarge-5 columns">
          <?php
          $events = new Eventbrite_Query(apply_filters('eventbrite_query_args', array(
            'nopaging' => true,
            'category_id' => 112,
            'organizer_id' => 13080157631
          )));

          if ($events->have_posts()) :
            while ($events->have_posts()) :
              $events->the_post(); ?>

              <div class="row align-justify special-event" id="event-<?php the_ID(); ?>">
                <div class="small-12 large-4 xlarge-3 columns">
                  <a href="<?= eventbrite_event_eb_url(); ?>">
                    <h6 class="special-event___date">
                      <?php
                        $formatString = function_exists('pll_e') ? pll__("D, F d \n g:i a") : "D, F d \n g:i a";
                        echo nl2br(date_i18n($formatString, strtotime(eventbrite_event_start()->local)));
                      ?>
                    </h6>
                  </div>
                  <div class="small-12 large-8 xlarge-9 columns">
                    <?php the_title(sprintf('<h5 class="special-event___title">', esc_url(get_permalink())), '</h5>'); ?>

                    <p class="special-event___locale"><?php echo eventbrite_event_venue()->name; ?><br/>
                    <?php echo eventbrite_event_venue()->address->localized_multi_line_address_display[1]; ?><br/>
                     <?php echo eventbrite_event_format()->name; ?></p>

                    <footer class="entry-footer">
                      <?php eventbrite_edit_post_link(__('Edit', 'eventbrite_api'), '<span class="edit-link">', '</span>'); ?>
                    </footer>
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
        </div>
      </div>
    </div>
  </section>
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
</main>

<?php get_footer(); ?>
