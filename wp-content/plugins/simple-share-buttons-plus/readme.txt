=== Simple Share Buttons Plus ===
 Contributors: DavidoffNeal
 Tags: share buttons, facebook, twitter, google+, share, share links, stumble upon, linkedin, pinterest
 Requires at least: 3.9
 Tested up to: 4.7
Stable tag: 1.2.4

  == Description ==
  One of the most advanced WordPress share button plugins available.

  == Installation ==  
* Upload the 'simple-share-buttons-plus' folder to the /wp-content/plugins/ directory.
 * Activate the plugin through the 'Plugins' menu in WordPress. 
* Navigate to the Simple Share Buttons Plus Admin Panel via 'Share Buttons' to change your settings as desired. 
* Save then activate your licence via the 'Licence' page.

  == Changelog ==

= 1.2.4 =
* NEW: Changed the way plugin gets FB counts to make it more stable
* Support for ASYNC and lazy load FB counts
* Fixed t function issue for some versions of php
* User interface changes. Set One is Share Bar now and Set Two is Share Buttons

= 1.2.3 =
* Fix: There were some issues when Simple Share Adder and Plus were active at the same time

= 1.2.2 =
* Fixes for old versions of PHP

= 1.2.1 =
* Fix: Minor bugfixes

= 1.2.0 =
* Feature: Add Facebook insights
* Feature: Add Facebook new iframe mobile sharing
* Fix: Facebook share counts are back! We implemented a solution to Facebook's deprecated API.
* Fix: Caching layer on top of Facebook's API to ensure fallback share counts

= 1.1.5 =
* Feature: Add new style 11!
* Tweak: Add 'Search Results' as an option to make disabling buttons on search results easier
* Tweak: Update main class name to __construct to resolve PHP notices
* Fix: Remove duplicate instances of Twitter counts

= 1.1.4 =
* Feature: Add newsharecounts.com option and functionality to show Twitter share counts again
* Update: Fallback to previous share counts if any share count API's ever fail or return zero incorrectly

= 1.1.3 =
* Fix: Ensure toggle buttons are hidden if not set to be shown

= 1.1.2 =
* Feature: Add SSBP OnScroll functionality!
* Feature: Add further customisation options to the Email Popup - Labels, button text, placeholder
* Tweak: Change & to &amp; within attribution links for validation
* Tweak: Set official settings import to ignore twitter username and tags
* Tweak: Remove a couple of unneeded declared variables
* Tweak: Re-include small HTML comment above share buttons div
* Fix: Change 'tags' to 'hashtags' to fix the hashtags feature for Twitter

= 1.1.1 =
* Feature: New Yummly sharing feature, as recommended by Yummly
* Update: Add new Email Popup options - set subject, from name and from email
* Update: Add option to remove Simple Share Buttons attribution links from popups
* Update: Remove Twitter share counts :(
* Fix: iOS native Twitter sharing URL encoding amendment
* Fix: Ellipsis and Email popup bug for lazy-loaded buttons
* Fix: Specify action for forms so they save when using Internet Explorer
* Fix: Colourpicker for share text colour fixed
* Fix: Resolve PHP notices when using Ellipsis alongside share counts

= 1.1.0 =
* Feature: Add Yummly share counts
* Update: New Google+ logo
* Tweak: Add title attributes to the buttons on the network select option to assist recognition of each network
* Fix: Log the URL correctly when sharing via the Email Popup option

= 1.0.9 =
* Feature: Add Tumblr share count
* Tweak: Hide share buttons for print
* Fix: Add 'spinner' icon for when the Email Popup send button is clicked
* Fix: Email Popup reliance on having Ellipsis enabled has been sorted

= 1.0.8 =
* Feature: Add Email Popup feature (Beta)
* Feature: Add Ellipsis (show more share options - Beta)
* Feature: Add 'Native Links' option for Twitter share buttons
* Feature: Add option to use shortlinks for WhatsApp sharing
* Tweak: URL Encode WhatsApp URLs for improved iOS compatibility
* Fix: Re-include http/https for URL being shared to Tumblr (404 error)
* Fix: Sort WP Dashboard widget bug and PHP notice alongside it  

= 1.0.7 = * Tweak: Add RTL compatibility to the admin pages by moving the save button to the left
* Fix: Fix styling for when 'Responsive' is not set, @1.0.6 bug
* Fix: Allow buttons to be removed if all share buttons have been added

= 1.0.6 =
* Feature: Add option to disable tracking functionality
* Feature: Add option to set the screen width at which the mobile view should begin/finish
* Update: Add link to documentation in the admin navbar
* Tweak: Improve reliability of license activation by changing wp_remote_get to wp_remote_post * Tweak: Add information to clearly indicate that adding your GA tracking ID adds GA tracking code
* Tweak: Correct labels for fields in admin panels
* Tweak: Reorder fields in admin panels

= 1.0.5 =
* Fix: Set two total/each share count display
* Fix: Small XSS vulnerability

= 1.0.4 =
* Update: Change SSB API URL to new subdomain

= 1.0.3 =
* Update: Add left/right/centre-aligned compatibility when using custom images
* Tweak: Update style 10-centred colours to new SSB brand colours
* Tweak: Change add_object_page to add_menu_page for those exceeding the object_page limit
* Fix: Delete ssbp.min.css after importing simplesharebuttons.com settings so it's created as required
* Fix: Pinterest bookmark rel="nofollow" * Fix: Total share count for lazy loading users
* Fix: Small XSS vulnerability
* Fix: Ensure ga_tracking_id is added upon upgrading to 1.0.2+  = 1.0.2 = * Feature: Add Google Analytics code option, with choice of whether or not to track logged-in users
* Feature: Add option to hide tracking page for non-admins
* Tweak: Disable option to delete/clear tracking stats for non-admins * Tweak: Allow decimals in size fields * Tweak: Format all PHP files to conform with PSR Standards (https://github.com/php-fig/fig-standards/tree/master/accepted) * Tweak: Add "Successfully Imported" messages when importing settings * Tweak: Display license activation error if present
* Tweak: Set total share count to appear as a list item when using custom images, improving placement of the count itself
* Tweak: If set to load the SSBP font in the head, don't include it in the custom CSS file when it's created
* Fix: Show/hide total/each share counts as specified when using custom images * Fix: Set relevant file permissions when creating ssbp.min.css so that it can be deleted after changes are made to style settings * Fix: Fix share count issue when using WP Shortlinks * Fix: Force use of set title when using shortcode anywhere that's not a post  = 1.0.1 = * Feature: Add option to load SSBP font in the head of your website allowing Async loading of CSS * Fix: Replace deactivate hook function with uninstall hook as required * Fix: Ensure full URL is used when retrieving share counts * Fix: Fix 'Use simplesharebuttons.com settings' option * Fix: Return support for custom images * Fix: Load SSBP icon font over http or https as necessary  = 1.0.0 = * Feature: Add option to enable Google Analytics Event Tracking * Feature: Add two new services - WhatsApp and Xing * Feature: Add Open Graph Type select list to SSBP Share Meta functionality * Feature: Add the option to customise colours separately for each button set * Feature: Add the option to customise icon colours to any colour desired * Feature: Add the option to enable use of featured images to be used with share meta details * Feature: Use a new SSBP font-family to allow scaling without any loss in quality * Feature: Reformat all settings pages using Bootstrap and a Bootswatch theme * Feature: Post and save all admin forms via AJAX for a better user experience * Feature: Create new table especially for share count data to improve speed if counters are enabled and avoid using options table * Feature: Create new table especially for ortsh URLs to improve speed if in use and avoid using options table * Feature: Add priority option in advanced settings, providing the ability to prioritise SSBP or other plugins within content * Feature: Allow review of official share counts in the tracking dashboard regardless of if counters are enabled in the front-end (via existing full share stats option) * Feature: New GoogleFonts families added for share text - Lato, Merriweather, Montserrat and Raleway * Feature: Add option to use WP shortlinks in place of full URLs * Feature: Live preview feature for button sets when making changes on the styling page * Tweak: Disable functionality that requires Mcrypt by default, and display a notice on settings pages * Tweak: Set share meta hook to priority 1 to further encourage use of SSBP share meta when enabled * Tweak: Remove jQuery UI effects and replace with CSS transitions for improved performance * Tweak: Ensure direct URLs are used when accessing ssbp.min.css * Tweak: Ensure direct URLs are used when accessing SSBP JS files * Tweak: Revert to database calls to get settings, to improve performance on high-traffic or low-spec environments * Tweak: Amend ssbp_get_url() function to use HTTP_HOST * Tweak: Add new ssbpForms helper class for admin screens * Tweak: ALL posts/pages in ranking order restricted to 10 for performance on the tracking page, in lieu of a load/more feature * Fix: Fix stripping of https/http from all share URLs if Tumblr button is in use * Fix: Set to ignore meta settings when importing settings from simplesharebuttons.com  = 0.5.8 = * Fix: Add SSBP Tracking table upon all installations and create it if not already there * Tweak: Better aligned Yummly share icon * Fix: Error checking fallback when fetching Reddit share counts  = 0.5.7 = * Tweak: Add success/failure notifications to console logs after share clicks  = 0.5.6 = * Tweak: Load icons via http or https as/if needed * Tweak: Amend permissions checking in Admin to resolve clashes with another plugin * Fix: Use default SSBP share meta information if set for homepages and categories/archives  = 0.5.5 = * Feature: Option to use SSBP share meta or featured images for Pinterest * Feature: Two new share set styles! * Feature: Make custom styling options available even when using preset styles * Feature: Generate and utilise a single custom minified CSS file for all implementations * Feature: Calculate required page-percentage width for mobile share bars to be more flexible * Feature: Copy all the official Simple Share Buttons Plus settings from simplesharebuttons.com * Feature: Use the custom styles option to overwrite all SSBP CSS with your own * Tweak: Add and use compressed versions of SSBP's JS files * Tweak: Add .htaccess file to disallow direct access to the JSON settings file * Tweak: Create header nav in admin and add a link to the support forum * Tweak: Add inactive license notification  = 0.5.4 = * Tweak: Allow access to share stats for 'Editors' * Fix: Remove errors for 'Editors' * Fix: PHP notice upon initial installation/activation  = 0.5.3 = * Tweak: Prevent linebreak tags from being added to responsive share bars * Tweak: Hide share text by default for share bars * Fix: Add fallbacks for errors when retrieving share counts  = 0.5.2 = * Feature: Add show/hide buttons to responsive share bars * Feature: Add a choice of effects that buttons can appear with * Feature: Add a mobile button load effect option * Feature: Easily transfer settings using the new import/export functionality * Tweak: Dramatically increase performance with the use of a JSON file * Tweak: Reduce 75 database options to 2 * Tweak: Reduce calls to get_ssbp_settings to one using PHP $GLOBALS * Tweak: Add custom menu icon * Tweak: Small security enhancement * Fix: Small amendment to processing of additional CSS when saving to strip slashes  = 0.5.1 = * Feature: Add Yummly button! * Feature: Option added to have a second set of buttons display! * Feature: Image set previews added to styling page * Update: Update to use the latest upload modal for image uploads * Update: Further improved styling throughout the SSBP settings pages * Tweak: Ensure correct title and URL are used for widget/shortcode use * Tweak: Reduce DB calls by adding SSBP settings to sessions * Fix: Custom meta image upload popup fix * Fix: Only include admin CSS on SSBP admin pages * Fix: Replace ampersands in page titles with %26 for email links  = 0.5.0 = * Feature: Official share counts by network added to dashboard * Feature: Radar chart added for social network breakdown * Update: Improved charts using http://www.chartjs.org/ * Update: Improved styling for the share tracking dashboard * Update: View full breakdown shares via countries with flag images * Update: Improved IP detection for sites with CDN and/or alternative proxies * Update: Tooltips added to all option labels * Tweak: Remove ort.sh for post/page previews and bbPress replies and prevent creating any more * Tweak: Ensure HTML tags are stripped from all page titles * Tweak: Improved validation of posted tracking data * Tweak: Improved security of admin panels * Fix: Button spacing when using styling options  = 0.4.2 = * Feature: Add a Twitter username to tweets under the 'Advanced' settings page. * Fix: Save all required ortsh URL information for dashboard display. Remove any incorrect data so it can be recreated.  = 0.4.1 = * Fix: Support in the tracking dashboard for users with unicode characters  = 0.4.0 = * Feature: Exclusive ort.sh URL shortener functionality added * Feature: SSB API added to offer more-consistent Facebook share count retrieval * Tweak: Show latest shares in a more logical order  = 0.3.1 = * Feature: Option to convert share count retrieval protocol from http/https and www/non-www * Update: Minify CSS on the fly for those using the custom styling options * Update: Link to new Support Forum * Update: Link added to Facebook Debugger on edit screen * Tweak: Wording in Admin pages  = 0.3.0 = * Feature: Share count caching added with custom setting * Feature: CSV export option added for share stats * Feature: Pagination of all shares made available * Feature: Ability to clear all share stats added * Feature: All pages/posts now listed in a table in order of number of shares * Fix: Remove line breaks if not required  = 0.2.2 = * Feature: New fixed share bar * Fix: Show total share count  = 0.2.1 = * Feature: Minimum share count option added * Feature: New preset style * Tweak: Improved bitly support for share counts * Tweak: Share count timeout option added  = 0.2.0 = * GeoIP country locations added * Shorten and track URLs using Bitly * Option added to use [ssba] shortcode if desired * Great new preset style! * Disable custom post types if you need to  = 0.1.8 = * Default style options to keep your HEAD clear from SSBP styles * Added option to select black or white icons * NEW logo and amended styles within the dashboard  = 0.1.7 = * Add new Meta-tag functionality! http://youtu.be/3h_ngMMjkTc * Fix tracking issue with non-lazy load option  = 0.1.6 = * Option added to switch lazy loading on or off  = 0.1.5 = * Fix download page share buttons  = 0.1.4 = * Added option to place share text above/left/right/below * Select font-weight option added * Share text now fades in too with the buttons * Choose margin for buttons * Support link updated to Zendesk! * Fix excerpt hook  = 0.1.3 = * Square button option added! * Fix for when showing duplicate sets of buttons * Categories/Archives and Homepage option sorted  = 0.1.2 = * Custom images now available!  = 0.1.1 = * Hotfix for version 0.1.0 allowing users not logged in to view buttons  = 0.1.0 = * Share counts added! * Choose between total share counts or per-site share counts * Lazy loading - Buttons fade in once ready, loading your pages/posts even more quickly! * Remove the bottom border if you wish  = 0.0.4 = * Hotfix for Firefox * Share buttons added to a class in preparation for share counts!  = 0.0.3 = * Hotfix for activation  = 0.0.2 = * New option in settings to only show buttons with excerpts if wanted * Port number not added if present * Page title pulled more accurately and efficiently, most noticed by those using twitter and/or with shortcode  = 0.0.1 = * Initial release 
 
 
 
 
