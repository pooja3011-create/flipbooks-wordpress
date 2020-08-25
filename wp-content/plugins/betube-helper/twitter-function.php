<?php
//////////////////////////////////////////////////////////////////
//// function to display tweets with api
//////////////////////////////////////////////////////////////////

function twitter_build($atts) {
	$redux_demo = get_option( 'redux_demo' );
	global $redux_demo;	
	$consumerkey = $redux_demo['consumer_key'];
	$consumerSecret = $redux_demo['consumer_secret'];
	$accessToken = $redux_demo['access_token'];
	$accessTokenSecret = $redux_demo['access_token_secret'];
	$twitterUserName = $redux_demo['twitter_user_name'];
	
    //require_once (get_template_directory() . "/inc/twitteroauth.php");
	include_once('twitteroauth.php');	
    $atts = shortcode_atts(array(
        'consumerkey' => $consumerkey,
        'consumersecret' => $consumerSecret,
        'accesstoken' => $accessToken,
        'accesstokensecret' => $accessTokenSecret,
        'cachetime' => '1',
        'username' => $twitterUserName,
        'tweetstoshow' => '10',
            ), $atts);
    //check settings and die if not set
    if (empty($atts['consumerkey']) || empty($atts['consumersecret']) || empty($atts['accesstoken']) || empty($atts['accesstokensecret']) || !isset($atts['cachetime']) || empty($atts['username'])) {
        return '<strong>' . __('Due to Twitter API changed you must insert Twitter APP. Check Our theme Options there you have Option for Twitter API, insert the Keys One Time', 'betube-helper') . '</strong>';
    }
    //check if cache needs update
    $jw_twitter_last_cache_time = get_option('jw_twitter_last_cache_time_' . $atts['username']);
    $diff = time() - $jw_twitter_last_cache_time;
    $crt = $atts['cachetime'] * 3600;

    //yes, it needs update			
    if ($diff >= $crt || empty($jw_twitter_last_cache_time)) {
        $connection = new TwitterOAuth($atts['consumerkey'], $atts['consumersecret'], $atts['accesstoken'], $atts['accesstokensecret']);
        $tweets = $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=" . $atts['username'] . "&count=10") or die('Couldn\'t retrieve tweets! Wrong username?');
        if (!empty($tweets->errors)) {
            if ($tweets->errors[0]->message == 'Invalid or expired token') {
                return '<strong>' . $tweets->errors[0]->message . '!</strong><br />'.__('You\'ll need to regenerate it <a href="https://dev.twitter.com/apps" target="_blank">here</a>!','betube-helper');
            } else {
                return '<strong>' . $tweets->errors[0]->message . '</strong>';
            }
            return;
        }
        $tweets_array = array();
        for ($i = 0; is_array($tweets) && $i <= count($tweets); $i++) {
            if (!empty($tweets[$i])) {
                $tweets_array[$i]['created_at'] = $tweets[$i]->created_at;
                $tweets_array[$i]['text'] = $tweets[$i]->text;
                $tweets_array[$i]['status_id'] = $tweets[$i]->id_str;
            }
        }
        //save tweets to wp option 		
        update_option('jw_twitter_tweets_' . $atts['username'], serialize($tweets_array));
        update_option('jw_twitter_last_cache_time_' . $atts['username'], time());
        echo '<!-- twitter cache has been updated! -->';
    }
    //convert links to clickable format
    if (!function_exists('convert_links')) {

        function convert_links($status, $targetBlank = true, $linkMaxLen = 250) {
            // the target
            $target = $targetBlank ? " target=\"_blank\" " : "";
            // convert link to url
            $status = preg_replace("/((http:\/\/|https:\/\/)[^ )]+)/e", "'<a href=\"$1\" title=\"$1\" $target >'. ((strlen('$1')>=$linkMaxLen ? substr('$1',0,$linkMaxLen).'...':'$1')).'</a>'", $status);
            // convert @ to follow
            $status = preg_replace("/(@([_a-z0-9\-]+))/i", "<a href=\"http://twitter.com/$2\" title=\"Follow $2\" $target >$1</a>", $status);
            // convert # to search
            $status = preg_replace("/(#([_a-z0-9\-]+))/i", "<a href=\"https://twitter.com/search?q=$2\" title=\"Search $1\" $target >$1</a>", $status);
            // return the status
            return $status;
        }

    }
    //convert dates to readable format
    if (!function_exists('relative_time')) {

        function relative_time($a) {
            //get current timestampt
            $b = strtotime("now");
            //get timestamp when tweet created
            $c = strtotime($a);
            //get difference
            $d = $b - $c;
            //calculate different time values
            $minute = 60;
            $hour = $minute * 60;
            $day = $hour * 24;
            $week = $day * 7;
    
        }

    }
    $jw_twitter_tweets = maybe_unserialize(get_option('jw_twitter_tweets_' . $atts['username']));
    return $jw_twitter_tweets;
}

if (!function_exists('shortcode_jw_twitter')) {

    function shortcode_jw_twitter($atts, $content) {
        $jw_twitter_tweets = twitter_build($atts);
        if (is_array($jw_twitter_tweets)) {
            $output = '<div class="widgetContent">';
            $output.='<div class="owl-carousel carousel twitter-carousel" data-car-length="1" data-items="1" data-loop="true" data-nav="false" data-autoplay="true" data-autoplay-timeout="4000" data-dots="true">';
            $fctr = '1';
            foreach ($jw_twitter_tweets as $tweet) {
                $output.='<div class="item twitter-item"><i class="fa fa-twitter-square"></i><span>' . convert_links($tweet['text']) . '<a class="twitter_time" target="_blank" href="http://twitter.com/' . $atts['username'] . '/statuses/' . $tweet['status_id'] . '">' . relative_time($tweet['created_at']) . '</a></span></div>';
                if ($fctr == $atts['tweetstoshow']) {
                    break;
                }
                $fctr++;
            }
            $output.='</div>';            
            $output.='</div>';
            return $output;
        } else {
            return $jw_twitter_tweets;
        }
    }

}
add_shortcode('jw_twitter', 'shortcode_jw_twitter');
/* Home Page Twitter Function */
if (!function_exists('classiera_jw_twitter')) {				
    function classiera_jw_twitter($tUsername, $tshow) {
		$atts = array(
			'username' => $tUsername,
			'tweetstoshow' => $tshow,
		);
        $jw_twitter_tweets = twitter_build($atts);
        if (is_array($jw_twitter_tweets)) {
            $output = '<div class="orbit-content" data-orbit data-options="bullets:true; animation_speed: 1000; navigation_arrows:false; slide_number:false; pause_on_hover:false; swipe:false; next_on_click:false; ">';
			$fctr = '1';
            foreach ($jw_twitter_tweets as $tweet) {
                $output.='<div data-orbit-slide="headline-'.$fctr.'"><div class="orbit-text"><span><i class="fa fa-twitter"></i></span><p>' . convert_links($tweet['text']) . '<a class="twitter_time" target="_blank" href="http://twitter.com/' . $atts['username'] . '/statuses/' . $tweet['status_id'] . '">' . relative_time($tweet['created_at']) . '</a></p></div></div>';
                if ($fctr == $atts['tweetstoshow']) {
                    break;
                }
                $fctr++;
            }
            $output.='</div>';
            return $output;
        } else {
            return $jw_twitter_tweets;
        }
    }

}