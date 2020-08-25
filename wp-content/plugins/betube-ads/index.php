<?php
/*
Plugin Name: beTube Ads
Plugin URI: http://joinwebs.com/
Description: BeTube Ads.
Version: 1.0.1
Author: JoinWebs
Author URI: http://joinwebs.com/
License: GPLv2
*/
?>
<?php 
// Load Ads Post Meta
require('includes/beads-post-type.php');
require('includes/betube-ads-meta.php');
require('includes/beads-post-meta.php');

//Call Language
function locations_beads_textdomain() { 
	load_plugin_textdomain( 'betube-ads', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'locations_beads_textdomain' );
	
// Load scripts for admin
add_action( 'admin_enqueue_scripts', 'betube_ads_backend_scripts' );
function betube_ads_backend_scripts(){		
	wp_enqueue_script( 'betube-backend-functions', plugins_url( "js/betube-backend-functions.js", __FILE__ ), array( 'jquery' ), '2016-03-10', true );
}

// Load scripts at front-end
function betube_adv_script() {
	wp_register_script( 'betube-plugin-custom', plugins_url( "js/betube-plugin-custom.js", __FILE__ ), array( 'jquery' ), '2016-03-10', true );
	wp_enqueue_script('betube-plugin-custom');
	wp_enqueue_style( 'betube-plugin-custom', plugins_url( "css/betube-plugin-custom.css", __FILE__ ));
}
add_action('wp_enqueue_scripts', 'betube_adv_script');

//Video Filter
function betube_adv_video_filter($videoString){	
	if(preg_match("/youtu.be\/[a-z1-9.-_]+/", $videoString)) {
		preg_match("/youtu.be\/([a-z1-9.-_]+)/", $videoString, $matches);
		if(isset($matches[1])) {
			$url = 'http://www.youtube.com/embed/'.$matches[1];
			$finalVideoframe = '<iframe width="710" height="400" src="'.$url.'" frameborder="0" allowfullscreen></iframe>';
		}
	}elseif(preg_match("/youtube.com(.+)v=([^&]+)/", $videoString)) {
		preg_match("/v=([^&]+)/", $videoString, $matches);
		if(isset($matches[1])) {
			$url = 'http://www.youtube.com/embed/'.$matches[1];
			$finalVideoframe = '<iframe width="710" height="400" src="'.$url.'" frameborder="0" allowfullscreen></iframe>';
		}
	}elseif(preg_match("#https?://(?:www\.)?vimeo\.com/(\w*/)*(([a-z]{0,2}-)?\d+)#", $videoString)) {
		preg_match("/vimeo.com\/([1-9.-_]+)/", $videoString, $matches);		
		if(isset($matches[1])) {
			$url = 'https://player.vimeo.com/video/'.$matches[1];
			$finalVideoframe = '<iframe width="710" height="400" src="'.$url.'" frameborder="0" allowfullscreen webkitallowfullscreen mozallowfullscreen></iframe>';
		}
	}else{
		$finalVideoframe = $videoString;
	}
	return $finalVideoframe;
}
//Video Filter
//Set Expiry Schedule
add_action( 'wp', 'beads_ad_expiry_schedule' );
function beads_ad_expiry_schedule() {
	if ( ! wp_next_scheduled( 'beads_ad_expiry_event' ) ) {
		wp_schedule_event( time(), 'hourly', 'beads_ad_expiry_event');
	}
}
add_action( 'beads_ad_expiry_event', 'betube_Ads_expiry' );
//Set Expiry Schedule
//Post Expiry
function betube_Ads_expiry(){
	
	$argsallpost = array(
		'orderby'          => 'date',
		'order'            => 'DESC',
		'post_type'        => 'video-ads',
		'author_name'	   => '',
		'post_status'      => 'publish',
		'suppress_filters' => true 
	);
	$allPost = get_posts( $argsallpost );
	
	//Move to trash	
	foreach($allPost as $id){		
			$postID = $id->ID;
			$daystogo = get_post_meta($postID, 'bedatepicker', true);			
			global $wpdb;
			$sql ="UPDATE {$wpdb->posts}
			SET post_status = 'trash'
			WHERE (post_type = 'video-ads' AND ID = '$postID' AND post_status = 'publish')
			AND DATEDIFF(NOW(), post_date) > %d";		
			$wpdb->query($wpdb->prepare( $sql, $daystogo ));		
		}
	//Move to trash	
	
}
//Post Expiry

//Frontend Play Function
function beAds_By_ID_Input($randomID){	
	//dropdown Value
	$adsdropdown = trim(get_post_meta($randomID, 'meta-box-dropdown', true));
	//Images
	$imageAdsURL = trim(get_post_meta($randomID, 'be-img-url', true));
	//HTML
	$htmlAds = get_post_meta($randomID, 'meta-html-code', true);
	//Video
	$videoAds = get_post_meta($randomID, 'meta-box-text', true);
	//Check Ads Type
	if($adsdropdown == 'image'){
		$beadsimgSrc = get_the_post_thumbnail($randomID, 'full', 'string');	
		$returnValue = '<a href='.$imageAdsURL.' target="_blank">'.$beadsimgSrc.'</a>';
	}elseif($adsdropdown == 'html'){
		$returnValue = "<div class='betube-html-content'>$htmlAds</div>";
	}elseif($adsdropdown == 'video'){			
		$returnValue = betube_adv_video_filter($videoAds);
	}
	return $returnValue;
}
function betube_Get_Random_IDS(){
	$args = array(
		'posts_per_page'   => 1,
		'orderby'          => 'rand',
		'post_type'        => 'video-ads',
		'author_name'	   => '',
		'post_status'      => 'publish',
		'suppress_filters' => true 
	);
	$randomPost = get_posts( $args );	
	$randomID = $randomPost[0]->ID;
	$returnValue= beAds_By_ID_Input($randomID);
	return $returnValue;
}
//Frontend Play Function
?>