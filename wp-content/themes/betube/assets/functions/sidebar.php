<?php
// SIDEBARS AND WIDGETIZED AREAS
function betube_register_sidebars() {
	if (function_exists('register_sidebar')) {
		register_sidebar(array(
			'id' => 'main',
			'name' => esc_html__('Main Sidebar', 'betube'),
			'description' => esc_html__('The first (primary) sidebar.', 'betube'),
			'before_widget' => '<div class="large-12 medium-7 medium-centered columns"><div class="widgetBox">',
			'after_widget' => '</div></div>',
			'before_title' => '<div class="widgetTitle"><h5>',
			'after_title' => '</h5></div>',
		));
	}
	if (function_exists('register_sidebar')) {
		register_sidebar(array(
			'id' => 'category',
			'name' => esc_html__('Category Sidebar', 'betube'),
			'description' => esc_html__('Category Sidebar', 'betube'),
			'before_widget' => '<div class="large-12 medium-7 medium-centered columns"><div class="widgetBox">',
			'after_widget' => '</div></div>',
			'before_title' => '<div class="widgetTitle"><h5>',
			'after_title' => '</h5></div>',
		));
	}
	if (function_exists('register_sidebar')) {
		register_sidebar(array(
			'id' => 'single-video',
			'name' => esc_html__('Single Video Sidebar', 'betube'),
			'description' => esc_html__('Single Video Sidebar', 'betube'),
			'before_widget' => '<div class="large-12 medium-7 medium-centered columns"><div class="widgetBox">',
			'after_widget' => '</div></div>',
			'before_title' => '<div class="widgetTitle"><h5>',
			'after_title' => '</h5></div>',
		));
	}
	if (function_exists('register_sidebar')) {
		register_sidebar(array(
			'id' => 'footer',
			'name' => esc_html__('Footer Widgets', 'betube'),
			'description' => esc_html__('Footer Widgets Area', 'betube'),
			'before_widget' => '<div class="column"><div class="widgetBox">',
			'after_widget' => '</div></div>',
			'before_title' => '<div class="widgetTitle"><h5>',
			'after_title' => '</h5></div>',
		));
	}
	if (function_exists('register_sidebar')) {
		register_sidebar(array(
			'id' => 'blog',
			'name' => esc_html__('Blogs Widgets', 'betube'),
			'description' => esc_html__('Blogs Widgets Area', 'betube'),
			'before_widget' => '<div class="large-12 medium-7 medium-centered columns"><div class="widgetBox">',
			'after_widget' => '</div></div>',
			'before_title' => '<div class="widgetTitle"><h5>',
			'after_title' => '</h5></div>',
		));
	}
	
	if(function_exists('is_buddypress')) {
		register_sidebar(array(
			'id' => 'buddypress',
			'name' => esc_html__('BuddyPress Sidebar', 'betube'),
			'description' => esc_html__('This sidebar will displayed on BuddyPress pages.', 'betube'),
			'before_widget' => '<div class="large-12 medium-7 medium-centered columns"><div class="widgetBox">',
			'after_widget' => '</div></div>',
			'before_title' => '<div class="widgetTitle"><h5>',
			'after_title' => '</h5></div>',
		));
	}
	if(function_exists('is_bbpress')) {
		register_sidebar(array(
			'id' => 'bbpress',
			'name' => esc_html__('bbPress Sidebar', 'betube'),
			'description' => esc_html__('bbPress Sidebar', 'betube'),
			'before_widget' => '<div class="large-12 medium-7 medium-centered columns"><div class="widgetBox">',
			'after_widget' => '</div></div>',
			'before_title' => '<div class="widgetTitle"><h5>',
			'after_title' => '</h5></div>',
		));
	}	
} // don't remove this bracket!