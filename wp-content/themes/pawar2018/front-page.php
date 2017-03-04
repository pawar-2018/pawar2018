<?php get_header(); ?>
  <main>
    <section class="hero">
      <div class="row align-bottom">
        <div class="small-9 columns">
          <h1>A New Deal For Illinois</h1>
        </div>
      </div>
      <div class="row align-middle">
        <div class="small-12 medium-7 columns">
          <h2>It‘s time to grab a clipboard, lace up our boots, and work together to restore progressive values in 2018.</h2>
        </div>
      </div>
      <div class="row align-middle">
        <div class="small-12 medium-7 columns">
      <h3>Get the latest updates</h3>
      <a href="/newsletter" class="button">Subscribe</a>
        </div>
      </div>
    </section>
    <section class="row main-content align-middle align-center">
      <div class="small-11 large-4 columns">
        <h1 class="main-callout">I’m running for governor to forge a New Deal for Illinois.</h1>
      </div>
      <div class="small-11 large-8 columns">
        <p class="main-copy">
          President Roosevelt’s New Deal took our country out of depression and laid the foundation for decades of growth and prosperity. We can do the same thing in Illinois by rebuilding our crumbling infrastructure, reinvesting in good-paying jobs, and recommitting to early childhood education and public schools. And we can do it without further burdening the middle class.
        </p>
      </div>
    </section>
    <section class="action-content">
      <div class="pillar-wrapper">
        <h4 class="section-title">Ameya's Four Pillars</h4>
        <h1 class="main-callout">
          What's in a New Deal for 2018.
        </h1>
        <div class="row small-up-1">
          <div class="column column-block pillar-content">
            <img src="<?php echo get_bloginfo('template_url') ?>/assets/school.svg" class="pillar-icon" alt="School Icon">
            <div class="pillar-copy">
              <h4 class="pillar-header">Education</h4>
              <p>
                Increase funding to all public schools by eliminating corporate tax loopholes and make millionaires pay their fair share.
              </p>
            </div>
          </div>
          <div class="column column-block pillar-content">
            <img src="<?php echo get_bloginfo('template_url') ?>/assets/family.svg" class="pillar-icon" alt="Family Icon">
            <div class="pillar-copy">
              <h4 class="pillar-header">Childcare</h4>
              <p>
                Provide universal access to childcare and support working families with paid sick leave, fair scheduling practices, parental leave, and a living wage.
              </p>
            </div>
          </div>
          <div class="column column-block pillar-content">
            <img src="<?php echo get_bloginfo('template_url') ?>/assets/bridge.svg" class="pillar-icon" alt="Bridge Icon">
            <div class="pillar-copy">
              <h4 class="pillar-header">Jobs & Infrastructure</h4>
              <p>
                Create tens of thousands of new middle-class jobs with a New Deal infrastructure program.
              </p>
            </div>
          </div>
          <div class="column column-block pillar-content">
            <img src="<?php echo get_bloginfo('template_url') ?>/assets/hammer.svg" class="pillar-icon" alt="Gavel Icon">
            <div class="pillar-copy">
              <h4 class="pillar-header">criminal justice reform</h4>
              <p>
                Pass criminal justice reform and refocus resources from prisons to diversion programs, job training and placement, and social and mental health services.
              </p>
            </div>
          </div>
        </div>
        <div class="column column-block pillar-content">
          <a href="<?php echo esc_url( home_url( '/issues' )) ?>" class="button">
            See the Issues
          </a>
        </div>
      </div>
      <div class="event-wrapper">
        <div class="event-content">
          <h4 class="section-title">
            Upcoming Events
          </h4>
          <h1 class="main-callout">Let's talk.</h1>
          <div class="row small-collapse">
            <div class="column small-11 large-8 event-copy">
                <p class="event-date">Saturday, March 4th at 1:45PM</p>
                <h5 class="event-header">Brady Campaign</h5>
                <p class="event-locale">Simeon Career Academy</p>
                <p class="event-address">
                  8147 S Vicennes Ave
                  <br />
                  Chicago, IL
                </p>
              </div>
            <div class="column small-11 large-8 event-copy">
                <p class="event-date">Sunday, March 5th at 4:30PM</p>
                <h5 class="event-header">Meet & Greet with Action for a Better Tomorrow - South Suburbs</h5>
                <p class="event-locale">Flossmoor Community Church</p>
                <p class="event-address">
                  2218 Hutchinson Rd.
                  <br />
                  Flossmoor, IL
                </p>
              </div>
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
          <img src="<?php echo get_bloginfo('template_url') ?>/assets/ameya02.jpg" class="event-photo" alt="Ameya Pawar with Microphone">
        </div>
      </div>
    </section>
    <div class="row align-middle align-center">
      <div class="small-11 large-10 columns">
        <h1 class="main-quote">
          "We have the power to use government as a force for good—if we elect people who believe in the power of government to improve the lives of people in every community across Illinois. That’s why I’m running for Governor.”
        </h1>
        <p class="main-caption">
          - Ameya Pawar
        </p>
      </div>
    </div>
    <div class="row bottom-content small-collapse align-middle align-justify">
      <div class="small-12 large-6 columns">
        <img src="<?php echo get_bloginfo('template_url') ?>/assets/ameya03.jpg" alt="Ameya Pawar with Schoolchildren">
      </div>
      <div class="small-12 large-6 columns">
        <img src="<?php echo get_bloginfo('template_url') ?>/assets/ameya04.jpg" alt="Ameya Pawar in front of a construction site">
      </div>
    </div>
  </div>
  </main>
<?php get_footer(); ?>
