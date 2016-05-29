<?php
/*
Plugin Name: Goodlayers Lawyer Plugin
Plugin URI: 
Description: A Custom Post Type Plugin To Use With Goodlayers Theme ( This plugin functionality might not working properly on another theme )
Version: 1.0.0
Author: Goodlayers
Author URI: http://www.goodlayers.com
License: 
*/

include_once( 'framework/gdlr-attorney-option.php');	
include_once( 'framework/gdlr-practice-option.php');	

include_once( 'include/gdlr-attorney-item.php');	
include_once( 'include/gdlr-practice-item.php');	

// action to loaded the plugin translation file
add_action('plugins_loaded', 'gdlr_lawyer_init');
if( !function_exists('gdlr_lawyer_init') ){
	function gdlr_lawyer_init() {
		load_plugin_textdomain('gdlr-lawyer', false, dirname(plugin_basename( __FILE__ ))  . '/languages/' ); 
	}
}

add_action('wp_enqueue_scripts', 'gdlr_lawyer_script');
function gdlr_lawyer_script() {
	wp_enqueue_script('gdlr-lawyer', plugins_url('gdlr-lawyer.js', __FILE__), array(), '1.0.0', true );
}


?>