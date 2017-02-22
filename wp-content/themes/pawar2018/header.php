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

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<header>
	<div class="row small-collapse align-between align-middle">
		<div class="logo small-3 columns">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<img src="<?php echo get_bloginfo('template_url') ?>/assets/logo.png">
			</a>
		</div>
		<div class="small-offset-1 columns">
			<a href="/">
				Meet Ameya
			</a>
		</div>
		<div class="columns">
			<a href="/">
				Issues
			</a>
		</div>
		<div class="columns">
			<a href="/">
			  Events
			</a>
		</div>
		<div class="columns">
			<a href="/">
				Get Involved
			</a>
		</div>
		<div class="social small-2 small-offset-2 columns row align-middle align-center">
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
		<div class="column">
			<a href="/">
				<button class="small" type="submit">Donate</button>
			</a>
		</div>
	</div>
</header>
