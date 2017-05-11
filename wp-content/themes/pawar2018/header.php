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
            <a class="header-nav__link <?php if(is_page('meet-ameya')) {?> active <?php } ?>" href="/meet-ameya" aria-label="Link to Meet Ameya Page">
                Meet Ameya
            </a>
            <a class="header-nav__link  <?php if(is_page('issues')) {?> active <?php } ?>" href="/issues" aria-label="Link to Issues Page">
                Issues
            </a>
			<a class="header-nav__link <?php if(is_page('events')) {?> active <?php } ?>" href="/events" aria-label="Link to Events Page">
              Events
            </a>
			<a class="header-nav__link <?php if(is_page('get-involved')) {?> active <?php } ?>" href="/get-involved" aria-label="Link to Get Involved Page">
                Get Involved
            </a>
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
			<a class="header-social__button button" href="/donate">
                Donate
            </a>
		</nav>

	</div>
</header>
