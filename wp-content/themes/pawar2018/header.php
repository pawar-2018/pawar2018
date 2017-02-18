<?php ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<header>
	<div class="row small-collapse align-spaced">
		<div class="logo small-4 columns">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<img src="/assets/logo.png">
			</a>
		</div>
		<div class="small-1 columns">
			<a href="/">
				Meet Ameya
			</a>
		</div>
		<div class="small-1 columns">
			<a href="/">
				Issues
			</a>
		</div>
		<div class="small-1 columns">
			<a href="/">
			  Events
			</a>
		</div>
		<div class="small-1 columns">
			<a href="/">
				Get Involved
			</a>
		</div>
		<div class="small-2 columns">
			<a href="/">
				<img src="/assets/facebook.svg">
			</a>
			<a href="/">
				<img src="/assets/twitter.svg">
			</a>
			<a href="/">
				<img src="/assets/instagram.svg">
			</a>
			<a href="/">
				<img src="/assets/youtube.svg">
			</a>
		</div>
		<div class="small-1 columns">
			<a href="/">
				Button
			</a>
		</div>
	</div>
</header>
