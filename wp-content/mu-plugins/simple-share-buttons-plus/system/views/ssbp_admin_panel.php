<?php

defined('ABSPATH') or die('No direct access permitted');

function ssbp_admin_header()
{
    // get license status
    $status = get_option('ssbp_license_status');

    // open wrap
    $htmlHeader = '<div class="ssbp-admin-wrap">';

    // if left to right
    if (is_rtl()) {
        // move save button
        $htmlHeader .= '<style>.ssbp-btn-save{left: 0!important;
                                        right: auto !important;
                                        border-radius: 0 5px 5px 0;}
                                </style>';
    }

    $arrSettings = get_ssbp_settings();

    // if terms have just been accepted
    if ( 'Y' === $_GET['accept-terms'] ) {
        $htmlHeader .= '<div class="alert alert-success text-center">
            <p>Thanks for accepting the terms, you can now take advantage of the great new features!</p>
        </div>';
    } elseif ( 'Y' !== $arrSettings['accepted_sharethis_terms'] ) {
        $htmlHeader .= '<div class="alert alert-warning text-center">
            <p>New features require acceptance of the terms before they can be used. <a href="' . esc_url( add_query_arg( 'accept-terms', 'Y' ) ) . '"><span class="button button-secondary">I accept</span></a></p>
        </div>';
    }

    // navbar/header
    $htmlHeader .= '<nav class="navbar navbar-default">
					  <div class="container-fluid">
					    <div class="navbar-header">
					      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					        <span class="sr-only">Toggle navigation</span>
					        <span class="icon-bar"></span>
					        <span class="icon-bar"></span>
					        <span class="icon-bar"></span>
					      </button>
					      <a class="navbar-brand" href="https://simplesharebuttons.com"><img src="' . plugins_url() . '/simple-share-buttons-plus/images/simplesharebuttons.png" alt="Simple Share Buttons Plus" class="ssbp-logo-img" /></a>
					    </div>

					    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					      <ul class="nav navbar-nav navbar-right">
					        <li><a href="https://docs.simplesharebuttons.com/plus/" target="_blank">Documentation</a></li>
					        <li><a href="https://simplesharebuttons.com/forums/forum/simple-share-buttons-plus/" target="_blank">Support</a></li>';
    // the license is not valid
    if ($status === false || $status != 'valid') {
        // add license notification
        $htmlHeader .= '<li><button type="button" class="ssbp-btn-inactive btn-danger ssbp-float-right-btn btn" data-toggle="modal" data-target="#ssbpLicenseModal">License inactive</button></li>';
    }
    $htmlHeader .= '</ul>
					    </div>
					  </div>
					</nav>';

    $htmlHeader .= '<div class="modal fade" id="ssbpLicenseModal" tabindex="-1" role="dialog" aria-labelledby="ssbpFooterModalLabel" aria-hidden="true">
			  <div class="modal-dialog">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			        <h4 class="modal-title">Simple Share Buttons Licensing</h4>
			      </div>
			      <div class="modal-body">
			        <p>You do not have a <strong>valid and activated</strong> license for this installation of <b>Simple Share Buttons Plus</b>.</p>
					<p>If you have a license, please save and activate it on the <a href="admin.php?page=simple-share-buttons-license">license page</a>. A valid license must be in place to take advantage of all of the plugin\'s features, including update notifications and support.</p>
				<p class="text-center">Licenses can be <a href="https://simplesharebuttons.com/plus/" target="_blank"><b>purchased here</b></a> and <a href="https://simplesharebuttons.com/purchases/" target="_blank"><b>managed here</b></a></p>
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			      </div>
			    </div>
			  </div>
			</div>';

    // open container - closed in footer
    $htmlHeader .= '<div class="container">';

    // return
    return $htmlHeader;
}

function ssbp_admin_footer()
{
    // row
    $htmlFooter = '<footer class="row">';

    // col
    $htmlFooter .= '<div class="col-sm-12">';

    // link to show footer content
    $htmlFooter .= '<a href="https://simplesharebuttons.com/plus/" target="_blank">Simple Share Buttons Plus</a> <span class="badge">' . SSBP_VERSION . '</span>';

    // show more/less links
    $htmlFooter .= '<button type="button" class="ssbp-btn-thank-you pull-right btn btn-primary" data-toggle="modal" data-target="#ssbpFooterModal"><i class="fa fa-info"></i></button>';

    $htmlFooter .= '<div class="modal fade" id="ssbpFooterModal" tabindex="-1" role="dialog" aria-labelledby="ssbpFooterModalLabel" aria-hidden="true">
        						  <div class="modal-dialog">
        						    <div class="modal-content">
        						      <div class="modal-header">
        						        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        						        <h4 class="modal-title">Simple Share Buttons Plus</h4>
        						      </div>
        						      <div class="modal-body">
        						        <p>Many thanks for choosing <a href="https://simplesharebuttons.com/plus/" target="_blank">Simple Share Buttons Plus</a> for your share buttons plugin, we\'re confident you won\'t be disappointed in your decision. If you require any support, please visit the <a href="https://simplesharebuttons.com/forums/forum/simple-share-buttons-plus" target="_blank">support forum</a>, there are also some tutorials available on <a href="https://simplesharebuttons.com/plus/tutorials/" target="_blank">this page</a>.</p>
        						        <p>If you like the plugin, we\'d really appreciate it if you took a moment to <a href="https://simplesharebuttons.com/plus/reviews/" target="_blank">leave a review</a>, if there\'s anything missing to get 5 stars do please <a href="https://simplesharebuttons.com/contact/" target="_blank">let us know</a>. If you feel your website is worthy of appearing on our <a href="https://simplesharebuttons.com/showcase/" target="_blank">showcase page</a> do <a href="https://simplesharebuttons.com/contact/" target="_blank">get in touch</a>.</p>
        						      </div>
        						      <div class="modal-footer">
        						        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        						      </div>
        						    </div>
        						  </div>
        						</div>';

    // close col
    $htmlFooter .= '</div>';

    // close row
    $htmlFooter .= '</footer>';

    // close container - opened in header
    $htmlFooter .= '</div>';

    // close ssbp-admin-wrap - opened in header
    $htmlFooter .= '</div>';

    // return
    return $htmlFooter;
}

function ssbp_admin_dashboard()
{

    // make sure we have settings ready
    $ssbp_settings = get_ssbp_settings();

    // ssbp header
    $htmlShareButtonsDash = ssbp_admin_header();

    // row
    $htmlShareButtonsDash .= '<div class="row">';

    // video setup div
    $htmlShareButtonsDash .= '<div class="col-sm-6">';
    $htmlShareButtonsDash .= '<h3 class="ssbp-setup">Simple Setup</h3>';
    $htmlShareButtonsDash .= '<div class="ssbp-video-container">';
    $htmlShareButtonsDash .= '<iframe width="560" height="315" src="//www.youtube.com/embed/WUVHlSqxC2w?rel=0&vq=hd720" frameborder="0" allowfullscreen></iframe>';
    $htmlShareButtonsDash .= '</div>';
    $htmlShareButtonsDash .= '<p>Please watch the video above which talks you through a basic setup of Simple Share Buttons Plus. When you are ready, visit the <a href="admin.php?page=simple-share-buttons-setup">main settings</a> page to get started.</p>';
    $htmlShareButtonsDash .= '</div>';

    // support div
    $htmlShareButtonsDash .= '<div class="col-sm-6">';
    $htmlShareButtonsDash .= '<h3 class="ssbp-support">Support</h3>';
    $htmlShareButtonsDash .= '<p>Please ensure that you include as many details as possible when posting, ideally including a link to an example or screenshots wherever you can.</p>';
    $htmlShareButtonsDash .= '<p>It is strongly recommended that you copy and paste the support details below to ensure that assistance can be provided and a solution found as swiftly as possible. The more information provided the better. It is also recommended that you export your SSBP settings and provide the .txt file too.</p>';

    // hidden support details
    $htmlShareButtonsDash .= '<fieldset>';
    $htmlShareButtonsDash .= '<div class="form-group">';
    $htmlShareButtonsDash .= ssbp_support_details($ssbp_settings);
    $htmlShareButtonsDash .= '</div>';
    $htmlShareButtonsDash .= '</fieldset>';

    // close col
    $htmlShareButtonsDash .= '</div>';

    // close row
    $htmlShareButtonsDash .= '</div>';

    // new row
    $htmlShareButtonsDash .= '<div class="row">';

    // new col
    $htmlShareButtonsDash .= '<div class="col-sm-6">';

    // import/export
    $htmlShareButtonsDash .= '<h3 class="margin-top--md">Export/Import</h3>';
    $htmlShareButtonsDash .= '<p>When moving Simple Share Buttons Plus from one site to another, save yourself the hassle of going through all your settings again by simply exporting/importing them using the buttons below. <b>Note that licenses are outside the scope of this functionality</b>.</p>';

    // new row
    $htmlShareButtonsDash .= '<div class="row">';

    // if we've just imported ssbp settings
    if (isset($_SESSION['ssbp_import']) && $_SESSION['ssbp_import'] === true) {
        // new col
        $htmlShareButtonsDash .= '<div class="col-sm-12">';

        // confirmation/disabled button
        $htmlShareButtonsDash .= '<button type="button" class="btn btn-success btn-block">Settings imported successfully!</button>';

        // close col
        $htmlShareButtonsDash .= '</div>';

        // unset import
        unset($_SESSION['ssbp_import']);
    } else {
        // new col
        $htmlShareButtonsDash .= '<div class="col-sm-4">';

        // export csv form
        $htmlShareButtonsDash .= '<form method="post" target="_blank">';
        $htmlShareButtonsDash .= wp_nonce_field('export_ssbp_settings_nonce');
        $htmlShareButtonsDash .= '<input type="hidden" name="export_ssbp_settings" />';
        $htmlShareButtonsDash .= '<button type="submit" class="btn btn-default btn-block">Export</button>';
        $htmlShareButtonsDash .= '</form>';

        // close col
        $htmlShareButtonsDash .= '</div>';

        // new col
        $htmlShareButtonsDash .= '<div class="col-sm-4">';

        // text file validation
        $htmlShareButtonsDash .= '<script language="javascript">
    												function Checkfiles()
    												{
    												var fup = document.getElementById("ssbp_settings_txt");
    												var fileName = fup.value;
    												var ext = (/[.]/.exec(fileName)) ? /[^.]+$/.exec(fileName) : undefined;
    												if(ext == "txt")
    												{
    												document.getElementById("ssbp-import-button").disabled = false;
    												return true;
    												}
    												else
    												{
    												alert("Upload .txt files only");
    												fup.focus();
    												return false;
    												}
    												}
    												</script>';

        // import csv form
        $htmlShareButtonsDash .= '<form method="post" id="ssbp_import_form" enctype="multipart/form-data" action="?page=simple-share-buttons-plus">';

        // file input
        $htmlShareButtonsDash .= '<input class="filestyle" id="ssbp_settings_txt" onchange="Checkfiles()" type="file" size="30" name="ssbp_settings_txt"  data-icon="false" data-input="false" data-buttonName="btn-default btn-block" />';

        // close col
        $htmlShareButtonsDash .= '</div>';

        // new col
        $htmlShareButtonsDash .= '<div class="col-sm-4">';

        $htmlShareButtonsDash .= '<button disabled id="ssbp-import-button" class="btn btn-primary btn-block">Import</button>';
        $htmlShareButtonsDash .= wp_nonce_field('import_ssbp_settings_nonce');
        $htmlShareButtonsDash .= '<input type="hidden" name="import_ssbp_settings" />';
        $htmlShareButtonsDash .= '</form>';

        // close col
        $htmlShareButtonsDash .= '</div>';
    }

    // close row
    $htmlShareButtonsDash .= '</div>';

    // close col
    $htmlShareButtonsDash .= '</div>';

    // new col
    $htmlShareButtonsDash .= '<div class="col-sm-6">';

    // heading
    $htmlShareButtonsDash .= '<h3 class="margin-top--md">Default Settings</h3>';
    $htmlShareButtonsDash .= '<p>Whether you\'ve decided you\'d like the same share buttons as us, or you just want some help getting started, click the button below to import all of the share button settings from <a href="https://simplesharebuttons.com">simplesharebuttons.com</a>. <strong>Note that all your current settings will be overwritten.</strong></p>';

    // ssbp settings import
    $htmlShareButtonsDash .= '<form method="post">';
    $htmlShareButtonsDash .= wp_nonce_field('import_official_settings_nonce');
    $htmlShareButtonsDash .= '<input type="hidden" name="import_official_settings" />';

    // if we've just imported official ssbp settings
    if (isset($_SESSION['ssbp_official_import']) && $_SESSION['ssbp_official_import'] === true) {
        // confirmation/disabled button
        $htmlShareButtonsDash .= '<button type="button" class="btn btn-success btn-block">Settings imported successfully!</button>';

        // unset import
        unset($_SESSION['ssbp_official_import']);
    } else {
        // import button
        $htmlShareButtonsDash .= '<button id="ssb-official-import" type="submit" class="btn btn-warning btn-block">Use simplesharebuttons.com\'s Settings</button>';
    }

    $htmlShareButtonsDash .= '</form>';

    // close col
    $htmlShareButtonsDash .= '</div>';

    // close row
    $htmlShareButtonsDash .= '</div>';

    // ssbp footer
    $htmlShareButtonsDash .= ssbp_admin_footer();

    echo $htmlShareButtonsDash;
}

function ssbp_support_details()
{
    // open textarea
    $htmlSupportDetails = '<textarea class="form-control" id="ssbp-support-textarea" rows="7">';

    // get wordpress version
    $wp_version = get_bloginfo('version');
    $htmlSupportDetails .= 'WordPress Version: ' . $wp_version . '|';

    // get theme details
    $my_theme = wp_get_theme();
    $htmlSupportDetails .= 'Theme: ' . $my_theme->get('Name') . '| Theme Version: ' . $my_theme->get('Version') . '|';

    // other plugins installed
    $all_plugins = get_plugins();

    // loop through and output
    foreach ($all_plugins as $arrPlugin) {

        // add to textarea
        $htmlSupportDetails .= $arrPlugin['Name'] . ': ' . $arrPlugin['Version'] . '|';
    }

    // close text area
    $htmlSupportDetails .= '</textarea>';

    // echo details
    return $htmlSupportDetails;
}

function ssbp_dashboard_stats()
{
    // include chart.js
    $htmlDashboard = "<script src='" . plugins_url('js/admin/ssbp_charts.js', SSBP_FILE) . "'></script>";

    // create the canvas
    $htmlDashboard .= '<div class="chart_wrap"><canvas id="share_week" height="200"></canvas></div>';

    // create chart
    $htmlDashboard .= "<script type='text/javascript'>";
    $htmlDashboard .= ssbpShareCountGraph();
    $htmlDashboard .= 'Chart.defaults.global.responsive = true;
								  var canvas = document.getElementById("share_week");
								  var ctx = canvas.getContext("2d");
								  new Chart(ctx).Line(data);';
    $htmlDashboard .= '</script>';

    // heading for latest shares
    $htmlDashboard .= '<h3 class="margin-top--md">Latest Shares</h3>';

    // get the latest shares
    $arrLatestShares = ssbpLatestShares();

    // create a table for latest shares
    $htmlDashboard .= '<table style="width:100%; text-align:left; padding:8px;">';
    $htmlDashboard .= '<tr>';
    $htmlDashboard .= '<th>Post/Page</th>';
    $htmlDashboard .= '<th>Network</th>';
    $htmlDashboard .= '<th>Date</th>';

    // close the heading row
    $htmlDashboard .= '</tr>';

    // start a count
    $ssbpShareCount = 0;

    // loop through our share data
    foreach ($arrLatestShares as $latestShare) {

        // add to count
        $ssbpShareCount++;

        // only show 5
        if ($ssbpShareCount > 5) {
            continue;
        }

        // add each row to our table
        $htmlDashboard .= '<tr>';
        $htmlDashboard .= '<td style="padding-top:5px;"><a href="' . $latestShare['url'] . '">' . htmlspecialchars($latestShare['title']) . '</a></td>';
        $htmlDashboard .= '<td>' . htmlspecialchars($latestShare['site']) . '</td>';
        $htmlDashboard .= '<td>' . $latestShare['datetime'] . '</td>';
        // close the row
        $htmlDashboard .= '</tr>';
    }

    // close the table off
    $htmlDashboard .= '</table>';

    return $htmlDashboard;
}

function ssbp_admin_tracking()
{

    // if the truncate function has been run
    if (isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'ssbp_truncate_tracking')) {
        // truncate the table
        ssbp_truncate_tracking();
    }

    // ssbp header
    $htmlShareButtonsForm = ssbp_admin_header();

    // the container to replace with content
    $htmlShareButtonsForm .= '<div class="ssbp-total-container"><h3 class="margin-top--md">Total Share Clicks</h3>';
    $htmlShareButtonsForm .= '<div class="text-center ssbp-loading"><i class="fa fa-spinner fa-spin"></i></div>';
    $htmlShareButtonsForm .= '</div>';

    // the container to replace with content
    $htmlShareButtonsForm .= '<div class="ssbp-top-container"><h3 class="margin-top--md">Top Pages/Posts</h3>';
    $htmlShareButtonsForm .= '<div class="text-center ssbp-loading"><i class="fa fa-spinner fa-spin"></i></div>';
    $htmlShareButtonsForm .= '</div>';

    // the container to replace with content
    $htmlShareButtonsForm .= '<div class="ssbp-latest-container"><h3 class="margin-top--md">Share History</h3>';
    $htmlShareButtonsForm .= '<div class="text-center ssbp-loading"><i class="fa fa-spinner fa-spin"></i></div>';
    $htmlShareButtonsForm .= '</div>';

    // past week div
    $htmlShareButtonsForm .= '<div class="col-md-6 text-center">';

    // past week heading
    $htmlShareButtonsForm .= '<h3 class="margin-top--md">Shares by Week</h3>';

    // include chart.js
    $htmlShareButtonsForm .= "<script src='" . plugins_url('js/admin/ssbp_charts.js', SSBP_FILE) . "'></script>";

    // create the canvas
    $htmlShareButtonsForm .= '<div class="chart_wrap"><canvas id="share_week" height="200"></canvas></div>';

    // create chart
    $htmlShareButtonsForm .= "<script type='text/javascript'>";
    $htmlShareButtonsForm .= ssbpShareCountGraph();
    $htmlShareButtonsForm .= 'Chart.defaults.global.responsive = true;
									  var canvas = document.getElementById("share_week");
									  var ctx = canvas.getContext("2d");
									  new Chart(ctx).Line(data);';
    $htmlShareButtonsForm .= '</script>';

    // close radar chart div
    $htmlShareButtonsForm .= '</div>';

    // past week div
    $htmlShareButtonsForm .= '<div class="col-md-6 text-center">';

    // past week heading
    $htmlShareButtonsForm .= '<h3 class="margin-top--md">Shares by Network</h3>';
    // create the canvas
    $htmlShareButtonsForm .= '<div class="chart_wrap"><canvas id="channel_radar" height="200"></canvas></div>';

    // create chart
    $htmlShareButtonsForm .= "<script type='text/javascript'>";
    $htmlShareButtonsForm .= ssbpShareChannelRadar();
    $htmlShareButtonsForm .= 'Chart.defaults.global.responsive = true;
									  var canvas = document.getElementById("channel_radar");
									  var ctx = canvas.getContext("2d");
									  new Chart(ctx).Radar(data);';
    $htmlShareButtonsForm .= '</script>';

    // close radar chart div
    $htmlShareButtonsForm .= '</div>';

    // the container to replace with content
    $htmlShareButtonsForm .= '<div class="ssbp-geoip-container"><h3 class="margin-top--md">GeoIP Stats</h3>';
    $htmlShareButtonsForm .= '<div class="text-center ssbp-loading"><i class="fa fa-spinner fa-spin"></i></div>';
    $htmlShareButtonsForm .= '</div>';

    // ssbp footer
    $htmlShareButtonsForm .= '</div>';
    // hide footer for now, experiencing issues with the positioning of the border appearing within the content
//	$htmlShareButtonsForm .= ssbp_admin_footer();

    echo $htmlShareButtonsForm;
}

function ssbp_total_shares_callback()
{

    // get total share count
    $intTotalShareCount = ssbpTotalShareCount();

    // if there is any data
    if (intval($intTotalShareCount) > 0) {

        // check if user is admin
        if (current_user_can('manage_options')) {

            // jquery on click truncate
            echo '<script>
        			// when truncate buttons is clicked
        			jQuery("#ssbp-truncate-table").click(function(){

        				if(confirm("Are you sure? This action CANNOT be undone!")) {
        			        return true;
        			    }
        			    return false;

        			});
        		</script>';

            // truncate table form
            echo '<form method="post">';
            wp_nonce_field('ssbp_truncate_tracking');
            echo '<button class="btn btn-danger ssbp-float-right-btn" id="ssbp-truncate-table">Clear Stats</button>';
            echo '</form>';
        }

        // export csv form
        echo '<form method="post" target="_blank">';
        echo '<input type="hidden" name="ssvp_export" />';
        echo '<button type="submit" class="btn btn-default ssbp-float-right-btn" id="ssbp-export-csv">Export CSV</button>';
        echo '</form>';
    }

    // total share data
    echo '<h3 class="margin-top--md">Total Share Clicks <span class="badge ssbp-share-count" data-from="0" data-to="' . $intTotalShareCount . '">0</span></h3>';

    // count to JS
    echo '<script type="text/javascript">
				jQuery(".ssbp-share-count").countTo();
		  </script>';

    // exit so no zeros are returned
    exit();
}

function ssbp_top_three_callback()
{

    // the full counters could take some time
    set_time_limit(0);

    // start a session to store top three data
    @session_start();

    // create blank session variable for csv headings
    $_SESSION['ssbp_post_data_headings'] = array(
        'Position',
        'Title',
        'URL',
        'Clicks',
    );

    // create an empty array to populate and add to session
    $arrSSBPData = array();

    // make sure we have settings ready
    $ssbp_settings = get_ssbp_settings();

    // get data ready for top 3
    $arrTopThree = ssbpTopThree();

    // include table sort js
    echo "<script src='" . plugins_url('js/admin/ssbp_table_sort.js', SSBP_FILE) . "'></script>";
    echo "<script src='" . plugins_url('js/admin/ssbp_thead_scroll.js', SSBP_FILE) . "'></script>";

    // enable floating thead and table sorting
    echo '<script>jQuery(function(){
		        jQuery("#ssbp-top-shares").stupidtable();
		        jQuery("#ssbp-top-shares").floatThead({
						useAbsolutePositioning: true,
						scrollingTop: 30
					})
		    });</script>';

    // export csv form
    echo '<form method="post" target="_blank">';
    wp_nonce_field('ssbp_post_data_export', 'ssbp_post_data_export');
    echo '<button type="submit" class="btn btn-default ssbp-float-right-btn-other" id="ssbp-export-csv">Export CSV</button>';
    echo '</form>';

    // top three heading
    echo '<h3 class="margin-top--md">Top Pages/Posts</h3>';

    // place top shares in a div wrapper
    echo '<div id="ssbp-top-shares-wrap">';

    // table for shares
    echo '<table id="ssbp-top-shares" class="table table-striped table-hover ssbp-top-shares">';

    // headings
    echo '<thead>';
    echo '<tr>';
    echo '<th data-sort="int">Position</th>';
    echo '<th data-sort="string">Post/Page</th>';
    echo '<th class="ssbp-sort-shares ssbp-share-count-heading" data-sort="int">Clicks</th>';

    // total columns
    $totalCols = 3;

    // if counters are enabled
    if ($ssbp_settings['counters_enabled'] == 'Y' && $ssbp_settings['show_full_stats'] == 'Y') {
        // check if facebook is selected
        if (strpos($ssbp_settings['selected_buttons'], 'facebook') !== false) {
            // add col to count
            $totalCols++;

            // add facebook count heading
            echo '<th class="ssbp-share-count-heading" data-sort="int">Facebook</th>';

            // add to session array for csv heading
            $_SESSION['ssbp_post_data_headings'][] = 'Facebook';
        }
        // check if google is selected
        if (strpos($ssbp_settings['selected_buttons'], 'google') !== false) {
            // add col to count
            $totalCols++;

            // add google count heading
            echo '<th class="ssbp-share-count-heading" data-sort="int">Google+</th>';

            // add to session array for csv heading
            $_SESSION['ssbp_post_data_headings'][] = 'Google+';
        }
        // check if linkedin is selected
        if (strpos($ssbp_settings['selected_buttons'], 'linkedin') !== false) {
            // add col to count
            $totalCols++;

            // add linkedin count heading
            echo '<th class="ssbp-share-count-heading" data-sort="int">LinkedIn</th>';

            // add to session array for csv heading
            $_SESSION['ssbp_post_data_headings'][] = 'LinkedIn';
        }
        // check if pinterest is selected
        if (strpos($ssbp_settings['selected_buttons'], 'pinterest') !== false) {
            // add col to count
            $totalCols++;

            // add pinterest count heading
            echo '<th class="ssbp-share-count-heading" data-sort="int">Pinterest</th>';

            // add to session array for csv heading
            $_SESSION['ssbp_post_data_headings'][] = 'Pinterest';
        }
        // check if reddit is selected
        if (strpos($ssbp_settings['selected_buttons'], 'reddit') !== false) {
            // add col to count
            $totalCols++;

            // add reddit count heading
            echo '<th class="ssbp-share-count-heading" data-sort="int">Reddit</th>';

            // add to session array for csv heading
            $_SESSION['ssbp_post_data_headings'][] = 'Reddit';
        }
        // check if stumbleupon is selected
        if (strpos($ssbp_settings['selected_buttons'], 'stumbleupon') !== false) {
            // add col to count
            $totalCols++;

            // add stumbleupon count heading
            echo '<th class="ssbp-share-count-heading" data-sort="int">StumbleUpon</th>';

            // add to session array for csv heading
            $_SESSION['ssbp_post_data_headings'][] = 'StumbleUpon';
        }
        // check if twitter is selected
        if (strpos($ssbp_settings['selected_buttons'], 'twitter') !== false) {
            // add col to count
            $totalCols++;

            // add twitter count heading
            echo '<th class="ssbp-share-count-heading" data-sort="int">Twitter</th>';

            // add to session array for csv heading
            $_SESSION['ssbp_post_data_headings'][] = 'Twitter';
        }
        // check if vk is selected
        if (strpos($ssbp_settings['selected_buttons'], 'vk') !== false) {
            // add col to count
            $totalCols++;

            // add vk count heading
            echo '<th class="ssbp-share-count-heading" data-sort="int">VK</th>';

            // add to session array for csv heading
            $_SESSION['ssbp_post_data_headings'][] = 'VK';
        }
    }

    echo '</tr>';
    echo '</thead>';

    // open table body
    echo '<tbody>';

    // start a counter
    $intCount = 0;

    // loop through our top three
    foreach ($arrTopThree as $topShare) {

        // add to counter
        $intCount++;

        // add each row to our table
        echo '<tr>';
        echo '<td>' . $intCount . '</td>';
        echo '<td><a href="' . $topShare['url'] . '">' . htmlspecialchars($topShare['title']) . '</a></td>';
        echo '<td class="ssbp-share-count-cell">' . $topShare['total_shares'] . '</td>';

        // unique array for each post
        $arrPostData = array();

        // add data to the array ready for the session
        $arrPostData['position'] = $intCount;
        $arrPostData['title'] = htmlspecialchars($topShare['title']);
        $arrPostData['url'] = $topShare['url'];
        $arrPostData['clicks'] = $topShare['total_shares'];

        // if full stats are enabled
        if ($ssbp_settings['show_full_stats'] == 'Y') {
            // initiate ssbp class
            $ssbp = new ssbpShareButtons($topShare['url']);

            // get this post/page's share counts
            $ssbp->get_ssbp_share_counts();

            // check if facebook is selected
            if (strpos($ssbp_settings['selected_buttons'], 'facebook') !== false) {
                // add facebook count
                echo '<td class="ssbp-share-count-cell ssbp-facebook-count-cell">' . $ssbp->ssbpShareCountData['facebook'] . '</td>';

                // add data to the array ready for the session
                $arrPostData['facebook'] = $ssbp->ssbpShareCountData['facebook'];
            }
            // check if google is selected
            if (strpos($ssbp_settings['selected_buttons'], 'google') !== false) {
                // add google count
                echo '<td class="ssbp-share-count-cell ssbp-google-count-cell">' . $ssbp->ssbpShareCountData['google'] . '</td>';

                // add data to the array ready for the session
                $arrPostData['google+'] = $ssbp->ssbpShareCountData['google'];
            }
            // check if linkedin is selected
            if (strpos($ssbp_settings['selected_buttons'], 'linkedin') !== false) {
                // add linkedin count
                echo '<td class="ssbp-share-count-cell ssbp-linkedin-count-cell">' . $ssbp->ssbpShareCountData['linkedin'] . '</td>';

                // add data to the array ready for the session
                $arrPostData['linkedin'] = $ssbp->ssbpShareCountData['linkedin'];
            }
            // check if pinterest is selected
            if (strpos($ssbp_settings['selected_buttons'], 'pinterest') !== false) {
                // add pinterest count
                echo '<td class="ssbp-share-count-cell ssbp-pinterest-count-cell">' . $ssbp->ssbpShareCountData['pinterest'] . '</td>';

                // add data to the array ready for the session
                $arrPostData['pinterest'] = $ssbp->ssbpShareCountData['pinterest'];
            }
            // check if reddit is selected
            if (strpos($ssbp_settings['selected_buttons'], 'reddit') !== false) {
                // add reddit count
                echo '<td class="ssbp-share-count-cell ssbp-reddit-count-cell">' . $ssbp->ssbpShareCountData['reddit'] . '</td>';

                // add data to the array ready for the session
                $arrPostData['reddit'] = $ssbp->ssbpShareCountData['reddit'];
            }
            // check if stumbleupon is selected
            if (strpos($ssbp_settings['selected_buttons'], 'stumbleupon') !== false) {
                // add stumbleupon count
                echo '<td class="ssbp-share-count-cell ssbp-stumbleupon-count-cell">' . $ssbp->ssbpShareCountData['stumbleupon'] . '</td>';

                // add data to the array ready for the session
                $arrPostData['stumbleupon'] = $ssbp->ssbpShareCountData['stumbleupon'];
            }
            // check if twitter is selected
            if (strpos($ssbp_settings['selected_buttons'], 'twitter') !== false) {
                // add twitter count
                echo '<td class="ssbp-share-count-cell ssbp-twitter-count-cell">' . $ssbp->ssbpShareCountData['twitter'] . '</td>';

                // add data to the array ready for the session
                $arrPostData['twitter'] = $ssbp->ssbpShareCountData['twitter'];
            }
            // check if vk is selected
            if (strpos($ssbp_settings['selected_buttons'], 'vk') !== false) {
                // add vk count
                echo '<td class="ssbp-share-count-cell ssbp-vk-count-cell">' . $ssbp->ssbpShareCountData['vk'] . '</td>';

                // add data to the array ready for the session
                $arrPostData['vk'] = $ssbp->ssbpShareCountData['vk'];
            }
        }

        // add current post/page's data to the array
        $arrSSBPData[] = $arrPostData;

        echo '</tr>';

        /*
                            // if showing the 5th and there are more
                            if($intCount == 5 && count($arrTopThree) > 5)
                            {
                                // show more button
                                echo '<button id="show_more_posts" class="ssbp-fixed-show ssbp-btn">Show all</button></td>';
                            }

                            // if we're viewing all and it's after the final row
                            if($intCount == count($arrTopThree) && $intCount> 5)
                            {
                                // show more button
                                echo '<button style="display:none;" id="show_fewer_posts" class="ssbp-fixed-show ssbp-btn">Show fewer</button>';
                            }
        */
    }

    // add all post/page's data to the session array
    $_SESSION['ssbp_post_data'] = $arrSSBPData;

    // close table body
    echo '</tbody>';

    // close table
    echo '</table>';

    // close div wrapper
    echo '</div>';

    /*
        // jquery click events
        echo '<script>
                // when show more posts buttons is clicked
                jQuery("#show_more_posts").click(function(){
                    // hide the button and row
                    jQuery("#show_more_posts").hide();
                    jQuery("#top_shares_button_row").hide();

                    // show the currently hidden rows of posts and show fewer button
                    jQuery(".top_shares_row").show(850);
                    jQuery("#show_fewer_posts").show();
                });

                // when show fewer posts buttons is clicked
                jQuery("#show_fewer_posts").click(function(){
                    // show the more button and row
                    jQuery("#show_more_posts").show();
                    jQuery("#top_shares_button_row").show();

                    jQuery("html, body").stop().animate({
                         "scrollTop": jQuery("#ssbp-top-shares-section").offset().top
                    }, 900, "swing");

                    // hide the rows of posts and show more button
                    jQuery(".extra_posts_row").hide(850);
                    jQuery("#show_fewer_posts").hide();
                });</script>';
    */
    // float head scroll top
    echo '<script>
				jQuery(".floatThead-container").click(function(){
					jQuery("html, body").stop().animate({
					     "scrollTop": jQuery("#ssbp-top-shares-section").offset().top
					}, 900, "swing");
				});
			</script>';

    // exit so no zeros are returned
    exit();
}

function ssbp_latest_shares_callback()
{

    // if deleting a share
    if (isset($_POST['delete'])) {
        ssbp_delete_share($_POST['delete']);
    }

    // the geoip could take some time
    set_time_limit(0);

    // no error reporting
    error_reporting(0);

    // get data ready for latest shares
    $arrLatestShares = ssbpLatestShares();
    $intTotalShares = ssbpTotalShareCount();

    // reverse latest shares around to display most recent last
    $arrLatestShares = array_reverse($arrLatestShares);

    // latest heading
    echo '<div class="ssbp-latest-container"><h3 class="margin-top--md">Share History</h3>';

    // create a table for latest shares
    echo '<table class="table table-striped table-hover ssbp-latest-shares">
		  <thead>
		    <tr>
		      <th>Post/Page</th>
		      <th>Site</th>
		      <th>Date</th>
		      <th>GeoIP</th>';

    // check if user is admin
    if (current_user_can('manage_options')) {
        // add delete column
        echo '<th></th>';
    }

    echo '</tr>
		  </thead>
		  <tbody>';

    // loop through our share data
    foreach ($arrLatestShares as $latestShare) {
        // add each row to our table
        echo '<tr>';
        echo '<td><a href="' . $latestShare['url'] . '">' . htmlspecialchars($latestShare['title']) . '</a></td>';
        echo '<td>' . htmlspecialchars($latestShare['site']) . '</td>';
        echo '<td>' . $latestShare['datetime'] . '</td>';

        // if geoip isn't set yet
        if ($latestShare['geoip'] == '' && $latestShare['ip'] != '127.0.0.1') {
            // retrieve our license key from the DB
            $ssbpLicense_key = trim(get_option('ssbp_license_key'));

            // check license key is there
            if ($ssbpLicense_key && $ssbpLicense_key != '') {
                // encryption key
                $ssbpKey = '7649E9A8A8319D47D4499B316BEA3';

                // securely encrypt the license key
                $ssbpLicense_key = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($ssbpKey), $ssbpLicense_key, MCRYPT_MODE_CBC, md5(md5($ssbpKey))));

                // call ssb api
                $jsonGeoIP = wp_remote_post('https://api.simplesharebuttons.com/v1/plus_geoip', array(
                    'method' => 'POST',
                    'timeout' => 3,
                    'body' => array(
                        'license' => $ssbpLicense_key,
                        'ip' => $latestShare['ip'],
                    ),
                ));

                // skip if there's an error for now
                if (is_wp_error($jsonGeoIP)) {
                    continue;
                }
            } else {
                continue;
            }

            // decode and assign to variables
            $arrGeoIP = json_decode($jsonGeoIP['body'], true);
            $geoipCountry = $arrGeoIP['country'];
            $geoipCode = strtolower($arrGeoIP['country_code']);
        } else { // geoip is set
            // decode and assign to variables
            $arrGeoIP = json_decode($latestShare['geoip'], true);
            $geoipCountry = $arrGeoIP['country'];
            $geoipCode = strtolower($arrGeoIP['code']);
        }

        // if not localhost
        if ($latestShare['ip'] != 'localhost' && $latestShare['ip'] != '127.0.0.1') {
            // add the geoip data
            echo '<td><img src="' . plugins_url() . '/simple-share-buttons-plus/images/flags/' . $geoipCode . '.png' . '" class="ssbg-flag ssbg-flag-small" />' . $geoipCountry . '</td>';
        } else { //localhost
            // add the geoip data
            echo '<td><b>Localhost - Testing</b></td>';
        }

        // check if user is admin
        if (current_user_can('manage_options')) {
            // delete option
            echo '<td class="text-center"><span class="text-danger ssbp-delete-share" data-ssbp-share-id="' . $latestShare['id'] . '">Delete</span></td>';
        }

        // close the row
        echo '</tr>';
    }

    // close the table off
    echo '</tbody></table>';

    // set new start
    $ssbpCurrentStart = (isset($_POST['start']) ? intval($_POST['start']) : 0);
    $ssbpPreviousStart = $ssbpCurrentStart + 10;
    $ssbpNextStart = $ssbpCurrentStart - 10;

    // calculate start point after delete
    // if there are NO previous share pages available
    if ($ssbpPreviousStart > intval($intTotalShares)) {
        if (intval($intTotalShares) - $ssbpCurrentStart == 1) {
            if ($intTotalShares == 1) {
                $ssbpAfterDeleteStart = 0;
            } else {
                $ssbpAfterDeleteStart = $ssbpNextStart;
            }
        } else {
            $ssbpAfterDeleteStart = $ssbpCurrentStart;
        }
    } elseif (intval($intTotalShares) - $ssbpCurrentStart == 0) {
        $ssbpAfterDeleteStart = 0;
    } else {
        $ssbpAfterDeleteStart = $ssbpCurrentStart;
    }

    // add pagination function
    echo '<script>
			jQuery(document).ready(function(){

				jQuery("#ssbp-previous-shares").click(function(){

					// show spinner to show progress
					jQuery("#ssbp-previous-shares").html("<i class=\"fa fa-spinner fa-spin\"></i>");

					dataLatest = {
						action: "ssbp_latest_shares",
						start: "' . $ssbpPreviousStart . '"
					}

					jQuery.post(ajaxurl, dataLatest, function(response) {
						// display tracking
						jQuery(".ssbp-latest-container").replaceWith(response).fadeIn(500);
					});
				});

				jQuery("#ssbp-next-shares").click(function(){

					// show spinner to show progress
					jQuery("#ssbp-next-shares").html("<i class=\"fa fa-spinner fa-spin\"></i>");

					dataLatest = {
						action: "ssbp_latest_shares",
						start: "' . $ssbpNextStart . '"
					}

					jQuery.post(ajaxurl, dataLatest, function(response) {
						// display tracking
						jQuery(".ssbp-latest-container").replaceWith(response).fadeIn(500);
					});
				});';

    // check if user is admin
    if (current_user_can('manage_options')) {

        echo 'jQuery(".ssbp-delete-share").click(function(){

    					jQuery(this).html("<i class=\"fa fa-spinner fa-spin\"></i>");

    					var delete_share = jQuery(this).data("ssbp-share-id");

    					dataLatest = {
    						action: "ssbp_latest_shares",
    						start: "' . $ssbpAfterDeleteStart . '",
    						delete: delete_share
    					}

    					jQuery.post(ajaxurl, dataLatest, function(response) {
    						// display tracking
    						jQuery(".ssbp-latest-container").replaceWith(response).fadeIn(500);
    						jQuery(".ssbp-latest-shares").removeClass("ssbp-semi-transparent");
    					});
    				});';
    }
    echo '});
		</script>';

    // if there are previous shares available
    if ($ssbpPreviousStart < intval($intTotalShares)) {
        echo '<button type="button" class="btn btn-primary btn-paginate" id="ssbp-previous-shares">&lt; Previous</button>';
        $ssbpMarginTop = 'margin-top:-30px;';
    }

    // if the start is not 0
    if (isset($_POST['start']) && $_POST['start'] != 0) {
        echo '<button type="button" class="btn btn-primary btn-paginate pull-right" id="ssbp-next-shares">Next &gt;</button>';
    }

    // calculate pages
    $intTotalPages = ceil($intTotalShares / 10);
    $intCurrentPage = ceil($ssbpCurrentStart / 10);
    $intCurrentPage = abs($intCurrentPage - $intTotalPages);

    echo '<div style="width:100%;text-align:center;' . $ssbpMarginTop . '"><b>Page ' . $intCurrentPage . ' of ' . $intTotalPages . '</b></div>';

    // close div
    echo '</div>';

    // exit so no zeros are returned
    exit();
}

// delete a single share
function ssbp_delete_share($intID)
{
    // wpdb functionality
    global $wpdb;

    // the table we'll be querying
    $wpdb->ssbp_tracking = $wpdb->prefix . 'ssbp_tracking';

    // delete the share
    $wpdb->delete($wpdb->ssbp_tracking, array('id' => $intID));
}

// export csv of post share data
function ssbp_post_data_export()
{
    // any errors would crash out
    error_reporting(0);

    // start session
    @session_start();

    // wpdb functionality
    global $wpdb;

    // create file name variable
    $ssbpFilename = 'SSBP Shares - ' . date('Y-m-d') . '.csv';

    // output headers so that the file is downloaded rather than displayed
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Content-Description: File Transfer');
    header('Content-type: text/csv');
    header("Content-Disposition: attachment; filename=$ssbpFilename");
    header('Expires: 0');
    header('Pragma: public');

    // open temp file
    $out = fopen('php://output', 'w');

    // add headings to csv
    fputcsv($out, $_SESSION['ssbp_post_data_headings']);

    // loop over the rows, outputting them
    foreach ($_SESSION['ssbp_post_data'] as $row) {
        // add row to csv
        fputcsv($out, $row);
    }

    // close and exit, forcing download
    fclose($out);
    exit;
}

// export csv of share stats
function ssbp_export_csv()
{
    // any errors would crash out
    error_reporting(0);

    // wpdb functionality
    global $wpdb;

    // the table we'll be querying
    $wpdb->ssbp_tracking = $wpdb->prefix . 'ssbp_tracking';

    // create file name variable
    $ssbpFilename = 'SSBP Stats - ' . date('Y-m-d') . '.csv';

    // output headers so that the file is downloaded rather than displayed
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Content-Description: File Transfer');
    header('Content-type: text/csv');
    header("Content-Disposition: attachment; filename=$ssbpFilename");
    header('Expires: 0');
    header('Pragma: public');

    $out = fopen('php://output', 'w');

    // get the shares
    $resultLatest = $wpdb->get_results("SELECT * FROM $wpdb->ssbp_tracking ORDER BY datetime ASC", ARRAY_A);

    // start a counter
    $count = 0;

    // loop over the rows, outputting them
    foreach ($resultLatest as $row) {
        // add to counter
        $count++;

        // if the first row
        if ($count == 1) {
            $arrHeadings = array(
                'Title',
                'URL',
                'Site',
                'Date',
                'IP',
                'Country',
            );
            // add to csv
            fputcsv($out, $arrHeadings);
        } else {
            // decode the geoip data
            $arrGeoData = json_decode($row['geoip']);

            // content array
            $arrContent = array(
                $row['title'],
                $row['url'],
                $row['site'],
                $row['datetime'],
                $row['ip'],
                (isset($arrGeoData->country) ? $arrGeoData->country : null),
            );

            // add to csv
            fputcsv($out, $arrContent);
        }
    }

    // close and exit, forcing download
    fclose($out);
    exit;
}

// get the top three
function ssbpTopThree()
{

    // wpdb functionality
    global $wpdb;

    // the table we'll be querying
    $wpdb->ssbp_tracking = $wpdb->prefix . 'ssbp_tracking';

    // get the share count
    $resultTopThree = $wpdb->get_results("SELECT count(*) AS total_shares, title, url FROM $wpdb->ssbp_tracking WHERE title != '' GROUP BY title ORDER BY total_shares DESC LIMIT 10", ARRAY_A);

    // return results
    return $resultTopThree;
}

// get the total number of posts/pages that have been shared
function ssbpGetSharedPageCount()
{

    // wpdb functionality
    global $wpdb;

    // the table we'll be querying
    $wpdb->ssbp_tracking = $wpdb->prefix . 'ssbp_tracking';

    // get the share count
    $intTotalSharedPages = $wpdb->get_results("SELECT count(*) AS count FROM $wpdb->ssbp_tracking GROUP BY title");

    // return count
    return count($intTotalSharedPages);
}

// get total share count
function ssbpLatestShares()
{

    // wpdb functionality
    global $wpdb;

    // the table we'll be querying
    $wpdb->ssbp_tracking = $wpdb->prefix . 'ssbp_tracking';

    // which results to get
    $ssbpStart = (isset($_POST['start']) ? $_POST['start'] : '0');

    // get the share count
    $resultLatest = $wpdb->get_results("SELECT * FROM $wpdb->ssbp_tracking WHERE title != '' ORDER BY datetime DESC LIMIT " . $ssbpStart . ',10', ARRAY_A);

    // return results
    return $resultLatest;
}

// truncate tracking table
function ssbp_truncate_tracking()
{
    // wpdb functionality
    global $wpdb;

    // the table we'll be querying
    $wpdb->ssbp_tracking = $wpdb->prefix . 'ssbp_tracking';

    if ($wpdb->query("TRUNCATE TABLE `$wpdb->ssbp_tracking`")) {
        echo '<div class="ssbp-updated"><p><strong>Successfully cleared sharing stats</p></div>';
    } else {
        echo '<div class="ssbp-updated"><p><strong>Failed to clear sharing stats!</p></div>';
    }
}

// get total share count
function ssbpTotalShareCount()
{

    // wpdb functionality
    global $wpdb;

    // the table we'll be querying
    $wpdb->ssbp_tracking = $wpdb->prefix . 'ssbp_tracking';

    // get the share count
    $intTotalShares = $wpdb->get_row("SELECT COUNT(*) AS count FROM $wpdb->ssbp_tracking WHERE title != ''");

    // return count
    return $intTotalShares->count;
}

// get recent dates and share counts for graph
function ssbpShareCountGraph()
{

    // wpdb functionality
    global $wpdb;

    // the table we'll be querying
    $wpdb->ssbp_tracking = $wpdb->prefix . 'ssbp_tracking';

    // the date we'll start from, a week ago
    $dateStart = date('Y-m-d', strtotime('-6 days'));

    // variables
    $ssbpLabels = '';
    $ssbpData = '';

    // loop through the last 7 days and get details
    while ($dateStart <= date('Y-m-d')) {

        // get the share count
        $intDayShareCount = $wpdb->get_row("SELECT COUNT(*) AS count FROM $wpdb->ssbp_tracking WHERE title != '' AND datetime LIKE '%" . $dateStart . "%'");

        // add day to the labels
        $ssbpLabels .= '"' . date('Y-m-d', strtotime($dateStart)) . '",';

        // add the data
        $ssbpData .= $intDayShareCount->count . ',';

        // add a day to loop through next
        $dateStart = date('Y-m-d', strtotime($dateStart . '+1 day'));
    }

    // trim trailing comma from labels and data
    $ssbpLabels = rtrim($ssbpLabels, ',');
    $ssbpData = rtrim($ssbpData, ',');

    return 'var data = {
					    labels : [' . $ssbpLabels . '],
					    datasets : [
					        {
					           fillColor : "#DCE6F7",
					            strokeColor : "#4582ec",
					            pointColor : "#4582ec",
					            pointStrokeColor : "#fff",
					            data : [' . $ssbpData . ']
					        }
					    ]
					};';
}

function ssbpShareChannelRadar()
{

    // wpdb functionality
    global $wpdb;

    // variables
    $radarLabels = '';
    $radarData = '';

    // the table we'll be querying
    $wpdb->ssbp_tracking = $wpdb->prefix . 'ssbp_tracking';

    // get the channel count
    $resultChannelCount = $wpdb->get_results("SELECT count(*) AS total_shares, site FROM $wpdb->ssbp_tracking WHERE site != '' GROUP BY site ORDER BY total_shares DESC", ARRAY_A);

    // loop through each channel
    foreach ($resultChannelCount as $channel) {
        // add to labels
        $radarLabels .= '"' . $channel['site'] . '",';

        // add to data
        $radarData .= intval($channel['total_shares']) . ',';
    }

    // trim trailing comma from labels and data
    $radarLabels = rtrim($radarLabels, ',');
    $radarData = rtrim($radarData, ',');

    return 'var data = {
					    labels : [' . $radarLabels . '],
					    datasets : [
					        {
					            fillColor : "#DCE6F7",
					            strokeColor : "#4582ec",
					            pointColor : "#4582ec",
					            pointStrokeColor : "#fff",
					            data : [' . $radarData . ']
					        }
					    ]
					};';
}

// display the main stats section for geoip
function ssbp_geoip_stats_callback()
{

    // the geoip could take some time
    set_time_limit(0);

    // include geoip functionality
    include_once SSBP_ROOT . '/system/models/ssbp_geoip.php';

    // init geoip stats
    $ssbgStats = new ssbg_stats();

    // 50% div
    echo '<div class="col-md-6 text-center">';

    // top countries heading
    echo '<h3 class="margin-top--md">Top Countries</h3>';

    // geoip-flags div
    echo '<div class="geoip-flags">';

    // get the top five countries
    $ssbgTopFive = $ssbgStats->ssbg_top_five();

    // create a limit counter
    $intFlagLimit = 0;

    // open table
    echo '<table class="ssbg-top-five">';

    // loop through the top five countries
    foreach ($ssbgTopFive as $ssbgTop) {

        // add 1 to the count
        $intFlagLimit++;

        // decode the geoip data
        $arrGeoIP = json_decode($ssbgTop['geoip']);

        // open row
        echo '<tr>';

        // if there is a country code
        if ($arrGeoIP->code != '') {
            // output flag with shares
            echo '<td><div class="flag-container">
								    <div class="flag-shares"><span class="flag-country">' . $arrGeoIP->country . '</span><span class="flag-text">' . ssbp_format_number($ssbgTop['total_shares']) . ' Share' . ($ssbgTop['total_shares'] > 1 ? 's' : null) . '</span></div>
								    <img class="ssbg-flag" src="' . plugins_url() . '/simple-share-buttons-plus/images/flags/' . $arrGeoIP->code . '.png">
								</div></td>';
        }

        // close row
        echo '</tr>';
    }

    // close table
    echo '</table>';

    // close div
    echo '</div>';

    // close div
    echo '</div>';

    // 50% div
    echo '<div class="col-md-6 text-center">';

    // countries breakdown heading
    echo '<h3 class="margin-top--md">Countries Breakdown %</h3>';

    // include chart.js
    $htmlShareButtonsForm .= "<script src='" . plugins_url('js/admin/ssbp_charts.js', SSBP_FILE) . "'></script>";

    // create the canvas
    echo '<div class="chart_wrap"><canvas id="countries_pie" width="200" height="100"></canvas></div>';

    // get the data for the countries pie chart
    $ssbgPieData = $ssbgStats->ssbg_pie_data();

    // get total share count to calculate percentages
    $intTotalShareCount = ssbpTotalShareCount();

    // start counters to limit results
    $countCountries = 0;
    $totalOthers = 0;

    // an array of colours
    $ssbpColours = array(
        1 => '175fdd',
        2 => '1863e6',
        3 => '4582ec',
        4 => '578eed',
        5 => '6799ee',
        6 => '76a3ef',
        7 => '83acf0',
        8 => '9abbf2',
        9 => 'a4c1f3',
        10 => 'cdddf8',
    );

    // loop through the top five countries
    foreach ($ssbgPieData as $ssbgPie) {
        // add to countries counter
        $countCountries++;

        // if we're 10 or over
        if ($countCountries >= 10) {
            // add to others total
            $totalOthers = $totalOthers + intval($ssbgPie['total_shares']);
        } // the first 9 countries
        else {
            // decode the geoip data
            $arrGeoIP = json_decode($ssbgPie['geoip']);

            // calculate the percentage
            $intPercentage = round((intval($ssbgPie['total_shares']) / $intTotalShareCount) * 100, 2);

            // add the data needed
            $strPieData .= "{
									label: '" . $arrGeoIP->country . "',
									value:" . $intPercentage . ",
									color:'#" . $ssbpColours[$countCountries] . "'
								},";
        }
    }

    // if we have an 'others' collection
    if ($totalOthers) {
        // calculate the percentage
        $intPercentage = round(($totalOthers / $intTotalShareCount) * 100, 2);

        // add to the chart
        $strPieData .= "{
								label: 'Others',
								value:" . $intPercentage . ",
								color:'#" . $ssbpColours[10] . "'
							}";
    } // no others
    else {
        // trim trailing comma from data
        $strPieData = rtrim($strPieData, ',');
    }

    // create pie chart
    echo "<script type='text/javascript'>";
    echo 'var data = [' . $strPieData . '];
				  Chart.defaults.global.responsive = true;
				  var countries_pie = document.getElementById("countries_pie");
				  var ctx = countries_pie.getContext("2d");
				  new Chart(ctx).Doughnut(data);';
    echo '</script>';

    // close div
    echo '</div>';

    // exit so no zeros are returned
    exit();
}
