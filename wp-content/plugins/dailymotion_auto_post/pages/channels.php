<?php defined('ABSPATH') or die("Not Allowed");


if(isset($_POST['add_channel'])) {
	if(!empty($_POST['channel_id']) && !empty($_POST['channel_name'])) {
		$channel_id = $_POST['channel_id'];
		$channels = get_option("dmap_channels");
		$channels = (!empty($channels)) ? unserialize($channels) : array();
		$channels[$channel_id] = array('name' => $_POST['channel_name'],  'cats' => $_POST['categories'], 'author' => $_POST['author'], 'last_updated' => '' );
		update_option("dmap_channels", serialize($channels));
	}
}

if(isset($_GET['action']) && $_GET['action'] == "delete") {
	if(!empty($_GET['cid'])) {
		$channel_id = $_GET['cid'];
		$channels = get_option("dmap_channels");
		$channels = (!empty($channels)) ? unserialize($channels) : array();
		unset($channels[$channel_id]);
		update_option("dmap_channels", serialize($channels));
	}
}

if(isset($_GET['action']) && $_GET['action'] == "add") {
	include(DMAP_ROOT . "/pages/add_channel.php");
} else {
	include(DMAP_ROOT . "/pages/view_channels.php");
}
	

?>