<?php get_header(); ?>
  <main>
  	<section class="hero">
  	</section>
    <section class="body">
        <div class="row">
                <div class="small-12 medium-8 columns">
                    <h1 class="section-title">Sign up to receive updates</h1>
                </div>

            <?php //TODO: change CSS to not be blue on blue header ?>
            <script type='text/javascript' src='//d1aqhv4sn5kxtx.cloudfront.net/actiontag/at.js'></script>
            <div class='ngp-form'
                 data-template='oberon'
                 data-id='8251531226014812672'
                 data-endpoint='https://api.myngp.com/'
                 data-formdef-endpoint='//formdefs.s3.amazonaws.com/api.myngp.com'
                 data-fastaction-endpoint='https://fastaction.ngpvan.com/'
                 data-databag='everybody'
                 data-resource-path='https://d1aqhv4sn5kxtx.cloudfront.net/'
                 data-inline-errors='true'
                 data-fastaction-nologin='true'
                 data-embed='true'></div>
        </div>
    </section>
  </main>
<?php get_footer(); ?>
