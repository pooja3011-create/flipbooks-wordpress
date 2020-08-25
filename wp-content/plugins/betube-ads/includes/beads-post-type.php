<?php 
add_action( 'init', 'betube_Video_Ads_PostType', 0 );
function betube_Video_Ads_PostType(){

		//$label contain text realated post's name
		$label = array(
			'name' 			=> esc_html__('BeTube Video Ads', 'betube-ads'),
			'singular_name' => esc_html__('Video Ad', 'betube-ads')
			);
		//args for custom post type
		$args = array(
			'labels' => $label,
			'description' => esc_html__('Post type about video advs management', 'betube-ads'),
			'supports' => array('title', 'thumbnail'),
	        'taxonomies' => array(),
	        'hierarchical' => false,
	        'public' => false,
	        'show_ui' => true,
	        'show_in_menu' => true,
	        'show_in_nav_menus' => true,
	        'show_in_admin_bar' => true,
	        'menu_position' => 5,
	        'menu_icon' => 'dashicons-format-gallery',
	        'can_export' => true,
	        'has_archive' => true,
	        'exclude_from_search' => true,
	        'publicly_queryable' => false,
	        'capability_type' => 'post'
				);

		//register post type
		register_post_type('video-ads', $args);
	}
add_filter('manage_video-ads_posts_columns' , 'betube_ads_edit_columns');	
function betube_ads_edit_columns( $columns ) {
		$columns = array(
			'cb' => '<input type="checkbox" />',
			'id' => esc_html__( 'ID', 'betube-ads' ),
			'title' => esc_html__( 'Description', 'betube-ads' ),
			'bedatepicker' => esc_html__( 'Expire After', 'betube-ads' ),
			'date' => esc_html__( 'Date', 'betube-ads' ),
		);
		return $columns;
	}	
?>