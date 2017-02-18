<footer>
	<div class="site-info">
		<a href="<?php echo esc_url( __( 'https://wordpress.org/', '_s' ) ); ?>"><?php printf( esc_html__( 'Proudly powered by %s', '_s' ), 'WordPress' ); ?></a>
		<span class="sep"> | </span>
		<?php printf( esc_html__( 'Theme: %1$s by %2$s.', '_s' ), '_s', '<a href="https://automattic.com/" rel="designer">Automattic</a>' ); ?>
	</div>
</footer>

<?php wp_footer(); ?>

</body>
</html>
