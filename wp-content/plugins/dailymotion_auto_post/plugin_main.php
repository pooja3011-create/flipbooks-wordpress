<?php 
defined('ABSPATH') or die("Not Allowed");

require("libs/DailymotionAPI.php");
require("pages/register_pages.php");

function dmap_post_video($post,$meta) {
	$post_id = wp_insert_post($post);
	if(!empty($meta)) {
		foreach($meta as $k => $v) {
			update_post_meta($post_id, $k , $v );
		}
	}
	return $post_id;
}

function dmap_prepear_post_data($video, $status, $author, $cats) {
	$post = array(
	  'post_title'    => $video['title'],
	  'post_content'  => $video['description'],
	  'post_status'   => $status,
	  'post_author'   => $author,
	  'post_category' => $cats
	);
	$settings = get_option("dmap_settings");
	if(!empty($settings['syndication'])) $video['embed_url'] .= "?syndication=".$settings['syndication'];
	$meta = array('jtheme_video_poster' => $video['thumbnail_url'], 'jtheme_video_url' => $video['embed_url'] );
	return array($post, $meta);
}

register_activation_hook( __FILE__, 'prefix_activation' );

function dmap_activation() {
	wp_schedule_event( time(), 'hourly', 'dmap_hourly_event_hook' );
}

add_action( 'dmap_hourly_event_hook', 'dmap_hourly_schedule' );


function dmap_hourly_schedule() {
	$settings = get_option("dmap_settings");
	$last_cron = get_option('dmap_last_cron');
	if((intval($last_cron)+(intval($settings['cron_interval'])*60*60)) > time())
		return;
	update_option('dmap_last_cron',time());
	$channels = get_option("dmap_channels");
	
	$channels = (!empty($channels)) ? unserialize($channels) : array();
	
	$dm = new  FB_DailyMotion("","");
	if(!empty($channels))
	foreach($channels as $channel_id => $channel) :
		$ch_videos = (!empty($channel['posted'])) ? $channel['posted'] : array();
		$page = 1;
		$videos = $dm->getUserVideos($channel_id);
		$count = 1;
		$totalVideo = $settings['posts_total'];
		if(empty($totalVideo)){
			$posts_total = "20";
		}else{
			$posts_total =  $totalVideo;
		}
		while($videos) {
			foreach($videos['list'] as $vid) {
				if($count <= $posts_total){	
					if(!in_array($vid->id,$ch_videos)) {
						$video = $dm->getVideo($vid->id, "poster_url,embed_url,id,title,description,thumbnail_url");
						$status = ( $settings['auto_publish'] ) ? "publish" : "draft";
						$post_data = dmap_prepear_post_data($video, $status, $channel['author'], $channel['cats']);
						$post_id = dmap_post_video($post_data[0],$post_data[1]);
						$ch_videos[] = $vid->id;
						$count++;
					}
				}
			}
			if($videos['has_more'] == 'true' && $count < $posts_total) {
				$page++;
				$videos = $dm->getUserVideos($channel_id,$page);
			} else {
				$videos = false;
			}
		}
		$channels[$channel_id]['posted'] = $ch_videos;
	endforeach;
	update_option("dmap_channels", serialize($channels));
}

register_deactivation_hook( __FILE__, 'dmap_deactivation' );

function dmap_deactivation() {
	wp_clear_scheduled_hook( 'dmap_hourly_event_hook' );
}

function dmap_init_hook() {
	dmap_hourly_schedule();
}

add_action('init', 'dmap_init_hook');