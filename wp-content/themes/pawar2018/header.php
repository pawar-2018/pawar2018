<?php ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.3.0/css/foundation.min.css">
<link rel="icon" href="<?php echo get_bloginfo('template_url') ?>/assets/favicon.ico" type="image/x-icon" />

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<header>
	<div class="row small-collapse align-justify align-middle">
		<div class="logo small-10 medium-11 large-4 columns">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<img src="<?php echo get_bloginfo('template_url') ?>/assets/logo.png">
			</a>
		</div>
		<div class="title-bar columns" data-responsive-toggle="top-menu" data-hide-for="large">
		  <div class="menu-icon" data-toggle="top-menu"></div>
		  <div class="title-bar-title"></div>
		</div>
		<nav class="row large-collapse columns align-middle" id="top-menu">
			<div class="small-order-1 columns">
				<a href="/meet-ameya" <?php echo esc_url( home_url( '/meet-ameya' ) ) ?' class="active"' : ''; ?>>
					Meet Ameya
				</a>
			</div>
			<div class="columns small-order-2">
				<a href="/issues" <?php echo esc_url( home_url( '/issues' ) ) ?' class="active"' : ''; ?>>
					Issues
				</a>
			</div>
			<div class="columns small-order-3">
				<a href="/events" <?php echo esc_url( home_url( '/events' ) ) ?' class="active"' : ''; ?>>
				  Events
				</a>
			</div>
			<div class="columns small-order-4">
				<a href="/page-get-involved.php" <?php echo (basename( $_SERVER['REQUEST_URI'] ) == 'page-get-involved.php') ? ' class="active"' : ''; ?>>
					Get Involved
				</a>
			</div>
			<div class="columns small-order-5 large-order-6">
				<a href="/">
					<button class="small" type="submit">Donate</button>
				</a>
			</div>
			<div class="social small-order-6 large-order-5 columns row align-middle">
				<a href="/">
					<img src="<?php echo get_bloginfo('template_url') ?>/assets/facebook.svg">
				</a>
				<a href="/">
					<img src="<?php echo get_bloginfo('template_url') ?>/assets/twitter.svg">
				</a>
				<a href="/">
					<img src="<?php echo get_bloginfo('template_url') ?>/assets/instagram.svg">
				</a>
				<a href="/">
					<img src="<?php echo get_bloginfo('template_url') ?>/assets/youtube.svg">
				</a>
			</div>
		</nav>
	</div>
</header>
