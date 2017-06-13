<?php get_template_part('template-parts/header', 'head'); ?>

<header class="header">
	<div class="header__content">

        <?php get_template_part('template-parts/header', 'logo'); ?>

		<div class="header-burger__icon">
      		<span class="line line-top"></span>
      		<span class="line line-middle"></span>
      		<span class="line line-bottom"></span>
		</div>

		<nav class="header-nav">
            <?php 
				$header_menu_items = get_items_by_location('header-menu');
				if($header_menu_items) {
					foreach ($header_menu_items as $item) {
						$classes = implode(' ', $item->classes);
						$classes .= ' header-nav__link';
						if(is_page($item->title) || is_post_type_archive( $item->object )) {
							$classes .= ' active';
						}

						echo "<a class=\"{$classes}\" href=\"{$item->url}\" aria-label=\"Link to {$item->title} Page\">{$item->title}</a>";
					}
				}
			?>

			<div class="header-social">
				<a class="header-social__link" href="https://www.facebook.com/AmeyaPawarIL/" target="_blank" aria-label="Link to Facebook AmeyaPawarIL">
					<img src="<?php echo get_bloginfo('template_url') ?>/assets/facebook.svg" alt="Facebook Icon">
				</a>
				<a class="header-social__link" href="https://twitter.com/Ameya_Pawar_IL" target="_blank" aria-label="Link to Twitter Ameya_Pawar_IL">
					<img src="<?php echo get_bloginfo('template_url') ?>/assets/twitter.svg" alt="Twitter Icon">
				</a>
				<a class="header-social__link" href="https://www.instagram.com/ameya_pawar_IL" target="_blank" aria-label="Link to Instagram ameya.s.pawar">
					<img src="<?php echo get_bloginfo('template_url') ?>/assets/instagram.svg" alt="Instagram Icon">
				</a>
				<a class="header-social__link" href="https://www.youtube.com/user/RenewChicago/videos" target="_blank" aria-label="Link to Youtube RenewChicago">
					<img src="<?php echo get_bloginfo('template_url') ?>/assets/youtube.svg" alt="Youtube Icon">
				</a>
			</div>

			<?php 
				$donate_button_items = get_items_by_location('donate-button');
				if($donate_button_items) {
					foreach ($donate_button_items as $item) {
						$classes = implode(' ', $item->classes);
						$classes .= ' header-social__button button';
						echo "<a class=\"{$classes}\" href=\"{$item->url}\">{$item->title}</a>";
					}					
				}
			?>

			<div class="header-language">
				<?php if (function_exists('pll_the_languages')) pll_the_languages( array( 'dropdown' => 1, 'display_names_as' => 'slug' ) ); ?>
			</div>
			
		</nav>

	</div>
</header>
