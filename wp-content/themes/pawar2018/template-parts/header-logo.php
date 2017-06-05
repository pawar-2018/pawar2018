<a class="header-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" aria-label="Back to the Home Page">
  <?php
    $header_menu_items = get_items_by_location('header-menu');
    foreach ($header_menu_items as $item) {
      if ($item->title === "Meet Ameya") {
        echo "<img src=" . get_field( 'main_logo', $item->ID ) . " alt='Ameya Pawar 2018 Logo'/>";
      }
    }
  ?>
</a>
