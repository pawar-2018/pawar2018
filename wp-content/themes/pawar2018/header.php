<?php ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet">
<link rel="icon" href="<?php echo get_bloginfo('template_url') ?>/assets/favicon.ico" type="image/x-icon" />
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-5FX333H');</script>
<!-- End Google Tag Manager -->

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<!-- Google Tag Manager (noscript) -->
<noscript>
	<iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5FX333H"
	height="0" width="0" style="display:none;visibility:hidden">
	</iframe>
</noscript>
<!-- End Google Tag Manager (noscript) -->

<header class="header">
	<div class="header__content">

        <a class="header-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" aria-label="Back to the Home Page">
            <img src="<?php echo get_bloginfo('template_url') ?>/assets/logo.svg" alt="Ameya Pawar 2018 Logo">
        </a>

		<div class="header-burger">
		  <div class="header-burger__icon"></div>
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
				<a class="header-social__link" href="https://www.instagram.com/ameya.s.pawar/" target="_blank" aria-label="Link to Instagram ameya.s.pawar">
					<img src="<?php echo get_bloginfo('template_url') ?>/assets/instagram.svg" alt="Instagram Icon">
				</a>
				<a class="header-social__link" href="https://www.youtube.com/user/RenewChicago/videos" target="_blank" aria-label="Link to Youtube RenewChicago">
					<img src="<?php echo get_bloginfo('template_url') ?>/assets/youtube.svg" alt="Youtube Icon">
				</a>
			</div>
			<a class="header-social__button button" href="https://act.myngp.com/Forms/9188561423484586496" target="_blank">
                Donate
            </a>
		</nav>

	</div>
</header>
