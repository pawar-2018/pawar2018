<?php get_header(); ?>
<main>
	<section>
		<div class="row">
            <?php post_query('post_type' => 'events' ); ?>
            <?php while ( have_posts() ) : the_post(); ?>
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
	</section>
</main><!-- #main -->
<?php get_footer(); ?>
