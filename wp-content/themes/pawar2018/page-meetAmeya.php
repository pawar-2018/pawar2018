<?php
/*
Template name: Meet Ameya
 */
get_header(); ?>
  <main>
    <section class="hero inner-page" style="background-image: url('<?php the_field('hero_image'); ?>');background-size: cover;">
         <div class="row align-center align-middle">
                <h1 class="page-title"><?php get_the_title($post->ID) ?></h1>
        </div>
    </section>
    <section class="body">
        <div class="row align-center">
            <div class="small-11 medium-10 columns">
                    <?php
                    // Section Titles
                    $section_title = get_field("inner_section_title");
                        echo '<h1 class="section-title">' . $section_title  . '</h1>';
                    ?>
                    <?php
                    while ( have_posts() ) : the_post();

                        get_template_part( 'template-parts/content', 'page' );

                    endwhile;
                    ?>
            </div>
        </div>
        <div class="row small-collapse large-uncollapse align-center">
            <div class="small-12 medium-10 columns">
                <?php

                $photo_content = get_field('photo_content');

                if( !empty($photo_content) ): ?>

                    <img src="<?php echo $photo_content['url']; ?>" alt="<?php echo $photo_content['alt']; ?>" class="inner-photo" />

                <?php endif; ?>
            </div>
        </div>
        <div class="row small-collapse large-uncollapse align-center">
            <div class="small-11 medium-10 columns">
                <?php

                $bottom_text_content = get_field('bottom_text_content');

                if( !empty($bottom_text_content) ): ?>

                    <?php echo $bottom_text_content ?>

                <?php endif; ?>

            </div>
        </div>
        <div class="row align-center">
                <?php

                $bottom_button = get_field('bottom_button');

                if ($bottom_button !== 'no button') {
                    echo '<a href="' . $button_1_link . '" class="button">' . $button_1_text . '</a>';
                }
                if ($bottom_button == 'two buttons') {
                    echo '<a href="' . $button_2_link . '" class="button">' . $button_2_text . '</a>';
                }

                <?php endif; ?>
            </div>
          </div>
        </div>
    </section>
  </main>

<?php get_footer(); ?>
