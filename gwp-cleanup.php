<?php

/**
 * Plugin Name: Gwp Cleanup
 * Version: 1.0
 * Description: Ce plugin retire ou cache certaines fonctionnalités non nécessaires de WordPress pour réduire les données envoyées au  navigateur et des balises cleans.
 * Author: Gino Sotongbe
 * Author URI: https://digino.github.io
 * Plugin URI:
 */


 /*
 |--------------------------------------------------------------------------
 | If this file is called directly, abort.
 |--------------------------------------------------------------------------
 */
 if ( ! defined( 'WPINC' ) ) {
 	die;
 }

 /*
 |--------------------------------------------------------------------------
 | Remove WordPress version | remove version from head
 |--------------------------------------------------------------------------
 */
 remove_action('wp_head', 'wp_generator');

 /*
 |--------------------------------------------------------------------------
 | remove wp-head unecessary
 |--------------------------------------------------------------------------
 */
 remove_action('wp_head', 'wp_shortlink_wp_head'); //removes shortlink.
 remove_action( 'wp_head', 'wp_oembed_add_discovery_links'); //Removes wp-oembed
 remove_action ('wp_head', 'wlwmanifest_link'); //remove wlwmanifest link
 remove_action ('wp_head', 'rsd_link'); //remove rsd links

 /*
 |--------------------------------------------------------------------------
 | Hide api link
 |--------------------------------------------------------------------------
 */
  remove_action( 'wp_head','rest_output_link_wp_head');
  remove_action( 'template_redirect', 'rest_output_link_header', 11 ); //To remove the rest_output_link_header

 /*
 |--------------------------------------------------------------------------
 | Remove link and prefetch to WordPress
 |--------------------------------------------------------------------------
 */
 remove_action('wp_head', 'wp_resource_hints', 2);

 /*
 |--------------------------------------------------------------------------
 | remove Xml-rpc
 |--------------------------------------------------------------------------
 */
 add_filter('xmlrpc_enabled', '__return_false');

 /*
 |--------------------------------------------------------------------------
 | remove xml links
 |--------------------------------------------------------------------------
 */
 remove_action( 'wp_head', 'feed_links_extra', 3 ); // Display the links to the extra feeds such as category feeds
 remove_action( 'wp_head', 'feed_links', 2 ); // Display the links to the general feeds: Post and Comment Feed

 /*
 |--------------------------------------------------------------------------
 |  Remove block library css and theme json
 |--------------------------------------------------------------------------
 */
 function remove_block_library_css() {
 	 wp_dequeue_style( 'wp-block-library' );
 }
 add_action ( 'wp_enqueue_scripts', 'remove_block_library_css' );

 add_filter( 'show_recent_comments_widget_style', function() { return false; }); //remove recents comments css


 remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
 remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );

 /*
 |--------------------------------------------------------------------------
 | Replace WordPress footer in admin Dashboard
 |--------------------------------------------------------------------------
 */
 function custom_footer_admin_text () {
     echo "";
   }
 add_filter('admin_footer_text', 'custom_footer_admin_text');

 /*
 |--------------------------------------------------------------------------
 | Remove wordpress in title
 |--------------------------------------------------------------------------
 */

 //in login page title
 function custom_login_title( $login_title ) {
 	return str_replace(array ( ' &lsaquo;', ' &#8212; WordPress'), array( '&bull;', ' '), $login_title );
 }
 add_filter( 'login_title', 'custom_login_title' );

 //in admin page title
 add_filter('admin_title', 'my_admin_title', 10, 2);
 function my_admin_title($admin_title, $title) {
 	return get_bloginfo('name').' &bull; '.$title;
 }

 /*
 |--------------------------------------------------------------------------
 | Remove the dashboard widgets, but only for non-admin user
 |--------------------------------------------------------------------------
 */
 function gerbb_remove_dashboard_widgets() {
 		remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
 		remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
 		remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
 		remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
 		remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
 		remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
 		remove_meta_box( 'dashboard_secondary', 'dashboard', 'side' );
 }
 add_action( 'wp_dashboard_setup', 'gerbb_remove_dashboard_widgets' );

/*
|--------------------------------------------------------------------------
| Remove update notification for non admin users
|| Remove Wordpress logo from admin bar
|| Top Help Tab
|--------------------------------------------------------------------------
*/

//Update notification
function hide_core_update_notif () {
	if ( ! current_user_can( 'update_core' ) ) {
			remove_action( 'admin_notices', 'update_nag', 3 );
	}
}
add_action( 'admin_head', 'hide_core_update_notif', 1 );

// WordPress logo
function remove_admin_bar_logo() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu( 'wp-logo' );
}
add_action('wp_before_admin_bar_render', 'remove_admin_bar_logo');

// Top help
function remove_top_help() {
	$screen = get_current_screen();
	$screen->remove_help_tabs();
}
add_action('admin_head', 'remove_top_help');


 /*
 |--------------------------------------------------------------------------
 | Disable the emoji's output
 |--------------------------------------------------------------------------
 */
function disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

}
add_action( 'init', 'disable_emojis' );
