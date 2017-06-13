<div class="footer-social__container">
	<div class="row align-center">
		<a class="footer-social__link" href="https://www.facebook.com/AmeyaPawarIL/" target="_blank" aria-label="Link to Facebook AmeyaPawarIL">
			<img src="<?php echo get_bloginfo('template_url') ?>/assets/facebook.svg" alt="Facebook Icon">
		</a>
		<a class="footer-social__link" href="https://twitter.com/Ameya_Pawar_IL" target="_blank" aria-label="Link to Twitter Ameya_Pawar_IL">
			<img src="<?php echo get_bloginfo('template_url') ?>/assets/twitter.svg" alt="Twitter Icon">
		</a>
		<a class="footer-social__link" href="https://www.instagram.com/ameya_pawar_IL" target="_blank" aria-label="Link to Instagram ameya.s.pawar">
			<img src="<?php echo get_bloginfo('template_url') ?>/assets/instagram.svg" alt="Instagram Icon">
		</a>
		<a class="footer-social__link" href="https://www.youtube.com/user/RenewChicago/videos" target="_blank" aria-label="Link to Youtube RenewChicago">
			<img src="<?php echo get_bloginfo('template_url') ?>/assets/youtube.svg"  alt="Youtube Icon">
		</a>
	</div>
</div>

<footer class="footer">
	<div class="row align-center">
		<div class="small-11 medium-3 large-4 columns">
			<nav class="footer-nav">
		        <a class="footer-nav__link show-for-large" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="Back to the Home Page">
		          <img class="footer-nav__logo" src="<?php echo get_bloginfo('template_url') ?>/assets/logo.<?php echo pll_current_language(); ?>.svg"  alt="Ameya Pawar 2018 Logo">
		        </a>
		        <?php
					$footer_menu_items = get_items_by_location('footer-menu');
					if($footer_menu_items) {
						foreach ($footer_menu_items as $item) {
							$classes = implode(' ', $item->classes);
							$classes .= ' footer-nav__link';
							echo "<a class=\"{$classes}\" href=\"{$item->url}\">{$item->title}</a>";
						}
					}
				?>
			</nav>
		</div>
		<div class="small-11 medium-5 large-4 columns">
			<div class="footer-signup">
				<h6 class="section-title"><?php pll_e('Newsletter'); ?></h6>
				<h3 class="footer-newsletter"><?php pll_e('Stay in the loop.'); ?></h3>
				<a href="<?php pll_e('/newsletter') ?>" class="button"><?php pll_e('Subscribe'); ?></a>
			</div>
		</div>
		<div class="small-11 medium-4 large-4 columns">
			<div class="footer-info">
				<h6 class="section-title"><?php pll_e('Ameya Pawar for Governor'); ?></h6>
				<a class="footer__link" href="mailto:info@pawar2018.com">info@pawar2018.com</a>
				<p>P.O. Box 577162<br>Chicago, Il 60657</p>
		  </div>
	   </div>
  </div>
</footer>

<?php get_template_part('template-parts/footer', 'paid'); ?>

<?php wp_footer(); ?>
</body>
</html>
