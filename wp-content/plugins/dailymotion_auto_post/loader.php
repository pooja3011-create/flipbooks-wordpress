<?php
/*
Plugin Name: Dailymotion Auto Poster
Plugin URI: http://www.joinwebs.com
Description: Dailymotion Auto Post Plugin automatically imports Dailymotion videos from any Dailymotion channel, creates posts for them and publishes them or creates a post draft according to your settings.
Version: 1.1
Author: JoinWebs
Author URI: http://www.joinwebs.com
*/

//Defining Plugin PATH and URI
define ('DMAP_URI',plugins_url('',__FILE__));
define ('DMAP_ROOT',__DIR__);

include('plugin_main.php');
