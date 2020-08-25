<?php 
defined('ABSPATH') or die("Not Allowed");


add_action( 'admin_menu', 'register_dmap_pages' );

function register_dmap_pages() {
	$icon_url= 'dashicons-controls-play';
	$position = null;
	add_menu_page( "Dailymotion Auto Poster", "Dailymotion Posts", "manage_options", "dmap_posting", "dmap_settings_view", $icon_url, $position );	
	add_submenu_page( "dmap_posting", "Dailymotion Channels", "Channels",  "manage_options", "dmap_channels", "dmap_channels_view" ); 
	add_submenu_page( null, "Dailymotion Process", "Process",  "manage_options", "dmap_process", "dmap_process_view" ); 
}

function dmap_settings_view() {
	include(DMAP_ROOT . "/pages/settings.php");
}

function  dmap_channels_view() {
	include(DMAP_ROOT . "/pages/channels.php");
}

function  dmap_process_view() {
	include(DMAP_ROOT . "/pages/process.php");
}

