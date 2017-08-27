<?php
/* Template Name: Search Results */
$post_type = $_GET["post_type"];
if ($post_type == 'press-releases') { load_template(TEMPLATEPATH . '/search-press-releases.php'); }
else { load_template(TEMPLATEPATH . '/search-all.php'); }; ?>