<?php
/**
 * Template name: All posts
 *
 * This is the most generic template file in a WordPress theme and one of the
 * two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * For example, it puts together the home page when no home.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage betube
 * @since betube 1.0
 */
get_header(); 
betube_breadcrumbs();
 ?>
 <?php 
	global $redux_demo;
	$page = get_page($post->ID);
	$current_page_id = $page->ID;
	$betubeGridView = $redux_demo['betube-main-grid-selection'];
	$betubeScroll = $redux_demo['infinite-scroll'];
	$betubeGoogleAdsAllVideos = $redux_demo['betube-google-ads-for-all-video-page'];
	$betubeFeaturedSlider = $redux_demo['featured-slider-on'];
	$betubeVideoCount = $redux_demo['all-videos-page-counter'];
	$myClass = "";
	if($betubeGridView == "gridsmall"){
		$myClass = "group-item-grid-default";
	}elseif($betubeGridView == "gridmedium"){
		$myClass = "grid-medium";
	}elseif($betubeGridView == "listmedium"){
		$myClass = "list";
	}
?>
<?php 
if($betubeFeaturedSlider == 1){	
	get_template_part( 'templates/featuredvideos' );
}
?>
<section class="category-content">
	<div class="row">
		<!-- left side content area -->
		<div class="large-8 columns">
			<section class="content content-with-sidebar">
				<div class="main-heading removeMargin">
					<div class="row secBg padding-14 removeBorderBottom">
						<div class="medium-8 small-8 columns">
							<div class="head-title">
								<i class="fa fa-film"></i>
								<h4><?php esc_html_e('All Videos', 'betube') ?></h4>
							</div><!--head-title-->
						</div><!--medium-8-->
						<div class="medium-4 small-4 columns">
							<ul class="tabs text-right pull-right" data-tabs id="newVideos">
								<li class="tabs-title is-active"><a href="#new-all"><?php esc_html_e('All', 'betube') ?></a></li>
								<li class="tabs-title"><a href="#new-hd"><?php esc_html_e('HD', 'betube') ?></a></li>
							</ul>
						</div><!--medium-4-->
					</div><!--row-->
				</div><!--main-heading-->
				<div class="row secBg">
					<div class="large-12 columns">
						<div class="row column head-text clearfix">						
						<?php
							$count_posts = wp_count_posts();
							$published_posts = $count_posts->publish;							
						?>
							<p class="pull-left"><?php esc_html_e('All Videos', 'betube') ?>&nbsp;: <span><?php echo $published_posts; ?>&nbsp;<?php esc_html_e('Videos posted', 'betube') ?></span></p>
							<div class="grid-system pull-right show-for-large">
								<a class="secondary-button <?php if($betubeGridView == "gridsmall"){echo "current";} ?> grid-default" href="#"><i class="fa fa-th"></i></a>
								<a class="secondary-button <?php if($betubeGridView == "gridmedium"){echo "current";} ?> grid-medium" href="#"><i class="fa fa-th-large"></i></a>
								<a class="secondary-button <?php if($betubeGridView == "listmedium"){echo "current";} ?> list" href="#"><i class="fa fa-th-list"></i></a>
							</div><!--grid-system-->
						</div><!--row column-->
						<div class="tabs-content" data-tabs-content="newVideos">
							<div class="tabs-panel is-active" id="new-all">
								<div class="row list-group">
								<?php 
									global $paged, $wp_query, $wp;
									$args = wp_parse_args($wp->matched_query);
									if ( !empty ( $args['paged'] ) && 0 == $paged ) {
										$wp_query->set('paged', $args['paged']);
										$paged = $args['paged'];
									}
									$cat_id = get_cat_ID(single_cat_title('', false));
									$temp = $wp_query;
									$wp_query= null;
									$wp_query = new WP_Query();
									//$wp_query->query('post_type=post&posts_per_page=-1');
									$wp_query->query('post_type=post&posts_per_page='.$betubeVideoCount.'&paged='.$paged);
									$current = -1;
									$current2 = 0;
								?>
								<?php while ($wp_query->have_posts()) : $wp_query->the_post(); $current++; $current2++; ?>
									<div class="item large-4 medium-6 columns end <?php echo $myClass; ?>">
										<div class="post thumb-border">
											<div class="post-thumb">
												<?php 
													if( has_post_thumbnail()){
														echo get_the_post_thumbnail();
													}else{
														?>
														<img src="<?php echo get_template_directory_uri() . '/assets/images/nothumb.png' ?>" alt="No Thumb"/>
														<?php
													}
												?>
												<a href="<?php the_permalink(); ?>" class="hover-posts">
													<span><i class="fa fa-play"></i><?php esc_html_e( 'Watch Video', 'betube' ); ?></span>
												</a>
												<div class="video-stats clearfix">
												<?php 
													$beTubePostHD = get_post_meta($post->ID, 'post_quality', true);
													if(!empty($beTubePostHD)){
												?>
													<div class="thumb-stats pull-left">
														<h6><?php echo $beTubePostHD; ?></h6>
													</div><!--Video HD-->
													<?php }?>
													<?php
													include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
													if(is_plugin_active("betube-helper/index.php")){	
													?>
													<div class="thumb-stats pull-left">		
														<span><?php echo get_simple_likes_button( get_the_ID() ); ?></span>
													</div><!--Video Likes-->
													<?php } ?>
													<?php 
													$beTubePostTime = get_post_meta($post->ID, 'post_time', true);
													if(!empty($beTubePostTime)){
													?>
													<div class="thumb-stats pull-right">
														<span><?php echo $beTubePostTime; ?></span>
													</div><!--Video Time-->
													<?php }?>
												</div><!--video-stats-->
											</div><!--post-thumb-->
											<div class="post-des">
												<h6>
													<a href="<?php the_permalink(); ?>">
													<?php $theTitle = get_the_title(); $theTitle = (strlen($theTitle) > 25) ? substr($theTitle,0,25).'...' : $theTitle; echo $theTitle; ?>
													</a>
												</h6>
												<div class="post-stats clearfix">
													<p class="pull-left">
														<i class="fa fa-user"></i>
														<?php 
														$user_ID = $post->post_author;
														?>
														<span><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php echo get_the_author_meta('display_name', $user_ID ); ?></a></span>
													</p>
													<p class="pull-left">
														<i class="fa fa-clock-o"></i>
														<?php $beTubedateFormat = get_option( 'date_format' );?>
														<span><?php echo get_the_date($beTubedateFormat, $post_id); ?></span>
													</p>
													<p class="pull-left">
														<i class="fa fa-eye"></i>
														<span><?php echo betube_get_post_views(get_the_ID()); ?></span>
													</p>
												</div><!--post-stats-->
												<div class="post-summary">
													<p>
														<?php echo substr(get_the_excerpt(), 0,260); ?>
													</p>
												</div><!--post-summary-->
												<div class="post-button">
													<a href="<?php the_permalink(); ?>" class="secondary-button"><i class="fa fa-play-circle"></i><?php esc_html_e( 'Watch Video', 'betube' ); ?></a>
												</div><!--post-button-->
											</div><!--post-des-->
										</div><!--post thumb-border-->
									</div><!--item large-4-->
									<?php endwhile; ?>
									<?php 
										if($betubeScroll == 1){
											echo infinite($wp_query);
										}else{
											get_template_part('pagination');
										}
									?>
									<?php wp_reset_query(); ?>
								</div><!--row list-group-->
							</div><!--new-all-->
							<!--HD Section -->
							<div class="tabs-panel" id="new-hd">
								<div class="row list-group">
								<?php 
									global $paged, $wp_query, $wp;
									$args = wp_parse_args($wp->matched_query);
									if ( !empty ( $args['paged'] ) && 0 == $paged ) {
										$wp_query->set('paged', $args['paged']);
										$paged = $args['paged'];
									}
									$cat_id = get_cat_ID(single_cat_title('', false));
									$temp = $wp_query;
									$wp_query= null;
									$wp_query = new WP_Query();
									$wp_query->query('post_type=post&posts_per_page=-1');
									$current = -1;
									$current2 = 0;
									while ($wp_query->have_posts()) : $wp_query->the_post();
									$betubeHDMeta = get_post_meta($post->ID, 'hd_post', true);
									if($betubeHDMeta == 1) {
								?>	
									<div class="item large-4 medium-6 columns end <?php echo $myClass; ?>">
										<div class="post thumb-border">
											<div class="post-thumb">
												<?php 
													if( has_post_thumbnail()){
														echo get_the_post_thumbnail();
													}else{
														?>
														<img src="<?php echo get_template_directory_uri() . '/assets/images/nothumb.png' ?>" alt="No Thumb"/>
														<?php
													}
												?>
												<a href="<?php the_permalink(); ?>" class="hover-posts">
													<span><i class="fa fa-play"></i><?php esc_html_e( 'Watch Video', 'betube' ); ?></span>
												</a>
												<div class="video-stats clearfix">
												<?php 
													$beTubePostHD = get_post_meta($post->ID, 'post_quality', true);
													if(!empty($beTubePostHD)){
												?>
													<div class="thumb-stats pull-left">
														<h6><?php echo $beTubePostHD; ?></h6>
													</div><!--Video HD-->
													<?php }?>
													<?php
													include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
													if(is_plugin_active("betube-helper/index.php")){	
													?>
													<div class="thumb-stats pull-left">		
														<span><?php echo get_simple_likes_button( get_the_ID() ); ?></span>
													</div><!--Video Likes-->
													<?php } ?>
													<?php 
													$beTubePostTime = get_post_meta($post->ID, 'post_time', true);
													if(!empty($beTubePostTime)){
													?>
													<div class="thumb-stats pull-right">
														<span><?php echo $beTubePostTime; ?></span>
													</div><!--Video Time-->
													<?php }?>
												</div><!--video-stats-->
											</div><!--post-thumb-->
											<div class="post-des">
												<h6>
													<a href="<?php the_permalink(); ?>">
													<?php $theTitle = get_the_title(); $theTitle = (strlen($theTitle) > 25) ? substr($theTitle,0,25).'...' : $theTitle; echo $theTitle; ?>
													</a>
												</h6>
												<div class="post-stats clearfix">
													<p class="pull-left">
														<i class="fa fa-user"></i>
														<?php 
														$user_ID = $post->post_author;
														?>
														<span><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php echo get_the_author_meta('display_name', $user_ID ); ?></a></span>
													</p>
													<p class="pull-left">
														<i class="fa fa-clock-o"></i>
														<?php $beTubedateFormat = get_option( 'date_format' );?>
														<span><?php echo get_the_date($beTubedateFormat, $post_id); ?></span>
													</p>
													<p class="pull-left">
														<i class="fa fa-eye"></i>
														<span><?php echo betube_get_post_views(get_the_ID()); ?></span>
													</p>
												</div><!--post-stats-->
												<div class="post-summary">
													<p>
														<?php echo substr(get_the_excerpt(), 0,260); ?>
													</p>
												</div><!--post-summary-->
												<div class="post-button">
													<a href="<?php the_permalink(); ?>" class="secondary-button"><i class="fa fa-play-circle"></i><?php esc_html_e( 'Watch Video', 'betube' ); ?></a>
												</div><!--post-button-->
											</div><!--post-des-->
										</div><!--post thumb-border-->
									</div><!--item large-4-->
									<?php } /*End HD Post Check */?>
									<?php endwhile; ?>
									<?php 
										if($betubeScroll == 1){
											echo infinite($wp_query);
										}else{
											get_template_part('pagination');
										}
									?>
									<?php wp_reset_query(); ?>
								</div><!--row list-group-->
							</div>
							<!--HD Section-->
						</div><!--tabs-contentnewVideos-->
					</div><!--large-12-->
				</div><!--row-->
			</section><!--content content-with-sidebar-->
			<div class="googleAdv">
				<?php echo $betubeGoogleAdsAllVideos; ?>
			</div><!-- End ad Section -->
		</div><!--large8-->
		<!-- left side content area -->
		<!-- sidebar -->
		<div class="large-4 columns">
			<aside class="secBg sidebar">
				<div class="row">
					<?php get_sidebar('main'); ?>
				</div>
			</aside>
		</div><!--large4-->
		<!-- sidebar -->
	</div><!--row-->
</section><!--category-content-->
<?php get_footer(); ?>