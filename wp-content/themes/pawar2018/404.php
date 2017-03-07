<?php get_header(); ?>
<main>
	<section class="hero inner-page">
		<div class="row align-center align-middle">
            <h1 class="page-title">404 &mdash; Page Not Found</h1>
       </div>
	</section>
	<section class="404 body">
		<div class="row align-center">
            <div class="small-11 medium-10 columns">
				<header class="page-header">
					<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'Pawar2018' ); ?></h1>
				</header><!-- .page-header -->
				<div class="page-content">
					<p><?php esc_html_e( 'It looks like we couldn&rsquo;t find what you were looking for. Sorry about that.', 'Pawar2018' ); ?></p>
				</div><!-- .page-content -->
			</div>
		</div>
	</section><!-- .error-404 -->
</main><!-- #main -->
<?php
get_footer();
