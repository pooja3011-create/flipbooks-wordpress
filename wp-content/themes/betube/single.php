<?php 
get_header(); 
betube_breadcrumbs();
global $post;
global $redux_demo;
$betubeSingleVideoLayout = $redux_demo['betube-single-video-layout'];
$betubeRelatedVideoCount = $redux_demo['betube_related_video_count'];
$betubeMultiPlayer = $redux_demo['betube-multi-player'];
$betubePluginAdv = $redux_demo['betube-plugin-adv'];
$betubeSocialSharebtn = $redux_demo['betube_social_share_btn'];
if(isset($_POST['favorite'])){
	$author_id = $_POST['author_id'];
	$post_id = $_POST['post_id'];
	echo betube_favorite_insert($author_id, $post_id);
}
if(isset($_POST['follower'])){
	$author_id = $_POST['author_id'];
	$follower_id = $_POST['follower_id'];
	echo betube_authors_insert($author_id, $follower_id);	
}
if(isset($_POST['unfollow'])){
	$author_id = $_POST['author_id'];
	$follower_id = $_POST['follower_id'];
	echo betube_authors_unfollow($author_id, $follower_id);	
}
?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<?php $betubePostFormat = get_post_format();?>
<?php if($betubeSingleVideoLayout == 1){?>
<section class="fullwidth-single-video inner-video">
	<div class="row">
		<div class="large-12 columns">
		<?php 
		
		if($betubeMultiPlayer == 1){
			global $post;
			$post_id = $post->ID;
			$betubeVideoURL = trim(get_post_meta($post_id, 'jtheme_video_url', true));
			$betubeVideoURLSecond = get_post_meta($post_id, 'single_video_link_second', true);
			$betubeVideoURLThird = get_post_meta($post_id, 'single_video_link_third', true);			
		?>
			<ul class="tabs" data-tabs id="example-tabs">
				<?php if(!empty($betubeVideoURL)){?>
				<li class="tabs-title is-active"><a href="#player1" aria-selected="true"><i class="fa fa-play-circle-o"></i><?php esc_html_e( 'Link 1', 'betube' ); ?></a></li>
				<?php } ?>
				<?php if(!empty($betubeVideoURLSecond)){?>
				<li class="tabs-title"><a href="#player2"><i class="fa fa-play-circle-o"></i><?php esc_html_e( 'Link 2', 'betube' ); ?></a></li>
				<?php } ?>
				<?php if(!empty($betubeVideoURLThird)){?>
				<li class="tabs-title"><a href="#player3"><i class="fa fa-play-circle-o"></i><?php esc_html_e( 'Link 3', 'betube' ); ?></a></li>
				<?php } ?>
			</ul>
		<?php } ?>	
			<div class="tabs-content" data-tabs-content="example-tabs">
			<?php 
			$betubeFlotingVideo = $redux_demo['betube-floting-video'];
			$floatingClass = "";
			if($betubeFlotingVideo == 1){
				$floatingClass = "betube-pop-video";
			}else{
				$floatingClass = "nothing";
			}
			?>
				<div class="<?php echo $floatingClass;?>">
				<span class="close-betube-pop dark"><i class="fa fa-times"></i></span>
				<div class="tabs-panel is-active" id="player1">
					<div class="video-plugin-for-adv">
						<div class="flex-video widescreen">
						<?php 						
						$post_id = $post->ID;
						$betubeVideoURL = trim(get_post_meta($post_id, 'jtheme_video_url', true));
						if(empty($betubeVideoURL)){
							$betubeVideoURL = trim(get_post_meta($post_id, '_ayvpp_video_url', true));
						}						
						$betubeVideoEmbed = trim(get_post_meta($post_id, 'jtheme_video_code', true));
						$betubeCustomVideo = trim(get_post_meta($post_id, 'jtheme_video_file', true));
						if(!empty($betubeVideoURL)){
							$betubePlayer = "link";
							$betubesource = $betubeVideoURL;
							betubeVideoFunction($betubePlayer, $betubesource, $post_id);
						}elseif(!empty($betubeVideoEmbed)){
							$betubePlayer = "embed";
							$betubesource = $betubeVideoEmbed;
							betubeVideoFunction($betubePlayer, $betubesource, $post_id);
						}elseif(!empty($betubeCustomVideo)){
							$betubePlayer = "customlink";
							$betubesource = $betubeCustomVideo;
							betubeVideoFunction($betubePlayer, $betubesource, $post_id);
						}
						if(empty($betubeVideoURL) && empty($betubeVideoEmbed) && empty($betubeCustomVideo)){
										$thumbURL = betube_thumb_url($post_id);
										$altTag = betube_thumb_alt($post_id);
									}
						if(!empty($thumbURL)){								
								?>
								<img src="<?php echo esc_url($thumbURL); ?>" alt="<?php echo $altTag; ?>"/>
						<?php }?>
						
						</div><!--flex-video-->
						<!--Video Skip Adv-->
					<?php if (function_exists( 'beAds_By_ID_Input' ) && $betubePluginAdv == 1 ){ ?>
						<div class="video-plugin-div flex-video widescreen">
						<?php
							$betubeVideoAdsID = trim(get_post_meta($post_id, 'video_adv_id', true));
							
							if(empty($betubeVideoAdsID)){
								echo betube_Get_Random_IDS();
							}else{
								echo beAds_By_ID_Input($betubeVideoAdsID);
							}
							
						?>							
							<div class="video-plugin-skip-button">
								<button class="button skip-video"><?php esc_html_e( 'Skip Ad', 'betube' ); ?></button>
								<button class="button" id="count"></button>
							</div>
						</div>
					<?php }?>
						<!--Video Skip Adv-->
					</div><!--Plugin Adv Div-->	
				</div><!--player1-->
				<?php 
				if($betubeMultiPlayer == 1){
					$betubeVideoURLSecond = trim(get_post_meta($post_id, 'single_video_link_second', true));
					$betubeVideoURLThird = trim(get_post_meta($post_id, 'single_video_link_third', true));
					if(!empty($betubeVideoURLSecond)){
				?>
					<div class="tabs-panel" id="player2">
						<div class="flex-video widescreen">
							<?php 
							$betubePlayer = "link";
							$betubesource = $betubeVideoURLSecond;
							betubeVideoFunction($betubePlayer, $betubesource, $post_id);
							?>
						</div>
					</div><!--player2-->
					<?php }?>
					<?php if(!empty($betubeVideoURLThird)){ ?>	
					<div class="tabs-panel" id="player3">
						<div class="flex-video widescreen">
							<?php 
							$betubePlayer = "link";
							$betubesource = $betubeVideoURLThird;
							betubeVideoFunction($betubePlayer, $betubesource, $post_id);
							?>
						</div>
					</div><!--player3-->
					<?php } ?>
				<?php } ?>	
				</div>	
			</div><!--End tabs-content-->			 
		</div><!--End large12-->
	</div><!--End row-->
</section><!--End Section-->
<?php } /*End Full Width Player If */?>
<?php if($betubeSingleVideoLayout == 3){?>
<section class="mainContentv3">
<?php }?>
<div class="row">
	<!-- left side content area -->
	<div class="large-8 columns <?php if($betubeSingleVideoLayout == 3){echo "parentbg";} ?>">
	<?php if($betubeSingleVideoLayout == 2 || $betubeSingleVideoLayout == 3){?>
			<?php if($betubeSingleVideoLayout == 3){?>
			<div class="sidebarBg"></div>
			<?php }?>
		<!--Small Player-->
		<?php 
	$post_id = $post->ID;
	$betubeVideoURL = trim(get_post_meta($post_id, 'jtheme_video_url', true));
	if(empty($betubeVideoURL)){
		$betubeVideoURL = trim(get_post_meta($post_id, '_ayvpp_video_url', true));
	}
if (!empty($betubeVideoURL) || has_post_thumbnail()) {	
?>
		<section class="inner-video inner-video-light">
			<div class="row <?php if($betubeSingleVideoLayout != 3){echo "secBg";}?>">
				<div class="large-12 columns inner-flex-video">
					<?php if($betubeMultiPlayer == 1){ ?>
					<?php 
					$post_id = $post->ID;					
					$betubeVideoURL = trim(get_post_meta($post_id, 'jtheme_video_url', true));
					if(empty($betubeVideoURL)){
						$betubeVideoURL = trim(get_post_meta($post_id, '_ayvpp_video_url', true));
					}
					$betubeVideoURLSecond = trim(get_post_meta($post_id, 'single_video_link_second', true));
					$betubeVideoURLThird = trim(get_post_meta($post_id, 'single_video_link_third', true));
					?>
					<ul class="tabs" data-tabs id="example-tabs">
						<?php if(!empty($betubeVideoURL)){?>
						<li class="tabs-title is-active"><a href="#player1" aria-selected="true"><i class="fa fa-play-circle-o"></i><?php esc_html_e( 'Link 1', 'betube' ); ?></a></li>
						<?php }?>
						<?php if(!empty($betubeVideoURLSecond)){?>
						<li class="tabs-title"><a href="#player2"><i class="fa fa-play-circle-o"></i><?php esc_html_e( 'Link 2', 'betube' ); ?></a></li>
						<?php }?>
						<?php if(!empty($betubeVideoURLThird)){?>
						<li class="tabs-title"><a href="#player3"><i class="fa fa-play-circle-o"></i><?php esc_html_e( 'Link 3', 'betube' ); ?></a></li>
						<?php }?>
					</ul>
					<?php }?>
					<div class="tabs-content" data-tabs-content="example-tabs">
					<?php 
					$betubeFlotingVideo = $redux_demo['betube-floting-video'];
					$floatingClass = "";
					if($betubeFlotingVideo == 1){
						$floatingClass = "betube-pop-video";
					}else{
						$floatingClass = "nothing";
					}
					?>
						<div class="<?php echo $floatingClass; ?>">
						<span class="close-betube-pop dark"><i class="fa fa-times"></i></span>
						<div class="tabs-panel is-active" id="player1">
							<div class="video-plugin-for-adv">
								<div class="flex-video widescreen">
								<?php 
									$post_id = $post->ID;								
									$betubeVideoURL = trim(get_post_meta($post_id, 'jtheme_video_url', true));
									if(empty($betubeVideoURL)){
										$betubeVideoURL = trim(get_post_meta($post_id, '_ayvpp_video_url', true));
									}
									$betubeVideoEmbed = trim(get_post_meta($post_id, 'jtheme_video_code', true));$betubeCustomVideo = trim(get_post_meta($post_id, 'jtheme_video_file', true));
									//echo $betubeCustomVideo."shabir";
									if(!empty($betubeVideoURL)){
										$betubePlayer = "link";
										$betubesource = $betubeVideoURL;
										betubeVideoFunction($betubePlayer, $betubesource, $post_id);
									}elseif(!empty($betubeVideoEmbed)){
										$betubePlayer = "embed";
										$betubesource = $betubeVideoEmbed;
										betubeVideoFunction($betubePlayer, $betubesource, $post_id);
									}elseif(!empty($betubeCustomVideo)){
										$betubePlayer = "customlink";
										$betubesource = $betubeCustomVideo;
										betubeVideoFunction($betubePlayer, $betubesource, $post_id);
									}									
									if(empty($betubeVideoURL) && empty($betubeVideoEmbed) && empty($betubeCustomVideo)){	
										$thumbURL = betube_thumb_url($post_id);
										$altTag = betube_thumb_alt($post_id);
									}
									if(!empty($thumbURL)){								
								?>
								<img src="<?php echo esc_url($thumbURL); ?>" alt="<?php echo $altTag; ?>"/>
									<?php }?>
								</div><!--End flex-video-->
								<!--Video Skip Adv-->
								<?php if (function_exists( 'beAds_By_ID_Input' ) && $betubePluginAdv == 1){ ?>
								<div class="video-plugin-div flex-video widescreen">
								<?php 
								
									$betubeVideoAdsID = trim(get_post_meta($post_id, 'video_adv_id', true));
									
									if(empty($betubeVideoAdsID)){
										echo betube_Get_Random_IDS();
									}else{
										echo beAds_By_ID_Input($betubeVideoAdsID);
									}								
								?>							
									<div class="video-plugin-skip-button">
										<button class="button skip-video"><?php esc_html_e( 'Skip Ad', 'betube' ); ?></button>
										<button class="button" id="count"></button>
									</div>
								</div>
								<?php }?>
								<!--Video Skip Adv-->
							</div><!--Plugin ADVdic-->
						</div><!--End Player1-->
						<!--Multi Player Start-->
						<?php 
						if($betubeMultiPlayer == 1){
							$betubeVideoURLSecond = trim(get_post_meta($post_id, 'single_video_link_second', true));
							$betubeVideoURLThird = trim(get_post_meta($post_id, 'single_video_link_third', true));
							if(!empty($betubeVideoURLSecond)){
						?>
							<div class="tabs-panel" id="player2">
								<div class="flex-video widescreen">
									<?php 
									$betubePlayer = "link";
									$betubesource = $betubeVideoURLSecond;
									betubeVideoFunction($betubePlayer, $betubesource, $post_id);
									?>
								</div>
							</div><!--player2-->
							<?php }?>
							<?php if(!empty($betubeVideoURLThird)){ ?>	
							<div class="tabs-panel" id="player3">
								<div class="flex-video widescreen">
									<?php 
									$betubePlayer = "link";
									$betubesource = $betubeVideoURLThird;
									betubeVideoFunction($betubePlayer, $betubesource, $post_id);
									?>
								</div>
							</div><!--player3-->
							<?php } ?>
						<?php } ?>
						<!--Multi Player End-->
						</div>
					</div><!--End tabs-content-->
				</div><!--End large-12-->
			</div><!--End row secBg-->
		</section>
		<?php } /*if there is no thumb or video */?>
		<!--Small Player End-->
	<?php } /*End Player Screen check*/?>	
		<!-- single post stats -->
		<section class="SinglePostStats">
			<div class="row <?php if($betubeSingleVideoLayout != 3){echo "secBg";}?>">
				<div class="large-12 columns">
					<div class="media-object stack-for-small">
						<div class="media-object-section">
							<div class="author-img-sec">
								<div class="thumbnail author-single-post">
									<?php 
									$user_ID = $post->post_author;									
									$authorAvatarURL = get_user_meta($user_ID, "betube_author_avatar_url", true);
									if(!empty($authorAvatarURL)) {										
										?>
										<img src="<?php echo esc_url($authorAvatarURL); ?>" alt="author">
										<?php
									}else{
										$authorID = get_the_author_meta('user_email', $user_ID);
										$avatar_url = betube_get_avatar_url($authorID, $size = '150' );
										?>
										<img src="<?php echo esc_url($avatar_url); ?>" alt="profile author img">
										<?php
									}
									?>									
								</div><!--thumbnail-->
								<p class="text-center"><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php echo $betubeDisplayName = get_the_author_meta('display_name', $user_ID); ?></a></p>
							</div><!--author-img-sec-->
						</div><!--media-object-section-->
						<div class="media-object-section object-second">
							<div class="author-des clearfix">
								<div class="post-title">
									<h4><?php the_title(); ?></h4>
									<p>
										<span>
											<?php $beTubedateFormat = get_option( 'date_format' );?>
											<?php global $post;
											$post_id = $post->ID;
											?>
											<i class="fa fa-clock-o"></i><?php echo get_the_date($beTubedateFormat, $post_id); ?>
										</span>
										<span>
											<i class="fa fa-eye"></i><?php echo betube_get_post_views(get_the_ID()); ?>
										</span>
										<?php
										/* Detect plugin. For use on Front End only.*/
										include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
										// check for plugin using plugin name
										if ( is_plugin_active( "betube-helper/index.php" ) ) {
											//plugin is activated
										?>
										<span>
											<?php echo get_simple_likes_button( get_the_ID() );?>
										</span>	
										<?php } ?>
										<span>
											<i class="fa fa-commenting"></i><?php echo comments_number(); ?>
										</span>
									</p>
								</div><!--post-title-->
								<div class="subscribe">
								<?php 
								if ( is_user_logged_in() ) { 
									global $current_user;
									wp_get_current_user();
									$user_id = $current_user->ID;
									if(isset($user_id)){
										if($user_ID != $user_id){							
										echo betube_authors_follower_check($user_ID, $user_id);
										}
									}
								}								
								?>
									
								</div><!--subscribe-->
							</div><!--author-des-->
							<div class="social-share">
								<div class="post-like-btn clearfix">
									<form method="post">
									<?php $postID = $post->ID;?>
									<?php ?>
										<button type="submit" name="favorite"><i class="fa fa-heart"></i><?php esc_html_e( 'Add to', 'betube' ); ?></button>
										<input type="hidden" name="author_id" value="<?php echo $user_id; ?>"/>
										<input type="hidden" name="post_id" value="<?php echo $postID; ?>"/>
									</form>
									<?php
										/* Detect plugin. For use on Front End only.*/
										include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
										// check for plugin using plugin name
										if ( is_plugin_active( "betube-helper/index.php" ) ) {
											//plugin is activated
										?>
									<?php echo get_simple_likes_button( get_the_ID() );?>	
										<?php } ?>
									<?php
									if($betubeSocialSharebtn == 1){
									$easyShare = "";
										/* Detect plugin. For use on Front End only.*/
										include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
										// check for plugin using plugin name
										if ( is_plugin_active( "betube-helper/index.php" ) ) {
											$easyShare = plugin_dir_url('betube-helper/index.php') . 'api/easyshare.php';
										}										
										?>
									<div id="sharePath" data-share="<?php echo $easyShare;?>"></div>
									<div class="float-right easy-share" data-easyshare data-easyshare-http data-easyshare-url="<?php the_permalink(); ?>">
										<!-- Facebook -->
										<button data-easyshare-button="facebook">
											<span class="fa fa-facebook"></span>
											<span><?php esc_html_e( 'Share', 'betube' ); ?></span>
										</button>
										<span data-easyshare-button-count="facebook">0</span>

										<!-- Twitter -->
										<button data-easyshare-button="twitter" data-easyshare-tweet-text="">
											<span class="fa fa-twitter"></span>
											<span><?php esc_html_e( 'Tweet', 'betube' ); ?></span>
										</button>
										<span data-easyshare-button-count="twitter">0</span>

										<!-- Google+ -->
										<button data-easyshare-button="google">
											<span class="fa fa-google-plus"></span>
											<span>+1</span>
										</button>
										<span data-easyshare-button-count="google">0</span>

										<div data-easyshare-loader><?php esc_html_e( 'Loading', 'betube' ); ?>...</div>
									</div><!--easy-share-->
									<?php } ?>
								</div><!--post-like-btn-->
							</div><!--social-share-->
						</div><!--media-object-section object-second-->
					</div><!--media-object-->
				</div><!--large-12-->
			</div><!--row secBg-->
		</section><!--End SinglePostStats-->
		<section class="singlePostDescription">
			<div class="row <?php if($betubeSingleVideoLayout != 3){echo "secBg";}?>">
				<div class="large-12 columns">
					<div class="heading">
						<h5><?php esc_html_e( 'Description', 'betube' ); ?></h5>
					</div><!--heading-->
					<!--<div class="description showmore_one">-->
					<div class="description">
						<?php echo the_content(); ?>
						<div class="categories">
							<button><i class="fa fa-folder"></i><?php esc_html_e( 'Categories', 'betube' ); ?></button>
							<?php 
								$betubeSingleCat = get_the_category();
								if ($betubeSingleCat) {								
							?>
							<a href="<?php echo get_category_link( $betubeSingleCat[0]->term_id );?>" class="inner-btn"><?php echo $betubeSingleCat[0]->name; ?></a>
								<?php }?>							
						</div><!--categories-->
						<div class="tags">
							<button><i class="fa fa-tags"></i><?php esc_html_e( 'Tags', 'betube' ); ?></button>
							<?php
							$before ="";
							$sep ="&nbsp;";
							$after ="";
							?>
							<?php the_tags( $before, $sep, $after ); ?> 							
						</div><!--tags-->
					</div><!--description showmore_one-->
				</div><!--large-12-->
				<!--Paginatation-->
				<div class="large-12 columns">
					<?php wp_link_pages( array(
						'before'      => '<div class="pagination"><span class="page-links-title">' . esc_html__( 'Pages:', 'betube' ) . '</span>',
						'after'       => '</div>',
						'link_before' => '<span class="page-numbers">',
						'link_after'  => '</span>',
						) );
					?>
				</div>
				<!--Paginatation-->
			</div><!--row secBg-->
		</section><!--End singlePostDescription-->
		<section class="content comments">
			<div class="row <?php if($betubeSingleVideoLayout != 3){echo "secBg";}?>">
				<?php 
					$file ='';
					$separate_comments ='';
					comments_template( $file, $separate_comments );
					
				?>				
			</div><!--row secBg-->
		</section><!--End Comments Area-->
<?php endwhile; ?>		
		<section class="content content-with-sidebar related">
			<div class="row <?php if($betubeSingleVideoLayout != 3){echo "secBg";}?>">
				<div class="large-12 columns">
					<div class="main-heading borderBottom">
						<div class="row padding-14">
							<div class="medium-12 small-12 columns">
								<div class="head-title">
									<i class="fa fa-film"></i>
									<h4><?php esc_html_e( 'Related Videos', 'betube' ); ?></h4>
								</div><!--head-title-->
							</div><!--medium-12-->
						</div><!--row padding-14-->
					</div><!--main-heading borderBottom-->
					<?php 
					global $post;
					$tags = wp_get_post_tags($post->ID);
					$relatedCat = wp_get_post_categories($post->ID);
					if ($tags || $relatedCat){ 
						$tag_ids = array();
						foreach($tags as $individual_tag)
						$tag_ids[] = $individual_tag->term_id;
						$args=array(  
							'tag__in' => $tag_ids,  
							'post__not_in' => array($post->ID),  
							'posts_per_page'=> $betubeRelatedVideoCount, // Number of related posts to display.  
							'ignore_sticky_posts'=> 1,
							'category__in' => $relatedCat,
						);
						$current = -1;
						$my_query = new wp_query( $args );
					?>
					<div class="row list-group">
					
					<?php 
						while( $my_query->have_posts() ) {
							$my_query->the_post();
							global $postID;
							$current++;
							$category = get_the_category();
					?>
						<div class="item large-4 columns end group-item-grid-default">							
							<div class="post thumb-border">
							<?php if( has_post_thumbnail()){ ?>
								<div class="post-thumb">
								
									<?php 
									$post_id = $post->ID;
									$thumbURL = the_post_thumbnail();
									if(empty($thumbURL)){
										$thumbURL = betube_thumb_url($post_id);
									}									
									$altTag = betube_thumb_alt($post_id);
									if(!empty($thumbURL)){
									?>
									<img src="<?php echo esc_url($thumbURL); ?>" alt="<?php echo $altTag; ?>"/>
									<?php } ?>
									
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
										</div><!--thumb-stats-->
										<?php } ?>
										<?php
										include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
										if ( is_plugin_active( "betube-helper/index.php" ) ) {	
										?>
										<div class="thumb-stats pull-left">
											<span><?php echo get_simple_likes_button( get_the_ID() ); ?></span>
										</div><!--thumb-stats-->
										<?php } ?>
										<?php 
										$beTubePostTime = get_post_meta($post->ID, 'post_time', true);
										if(!empty($beTubePostTime)){
										?>
										<div class="thumb-stats pull-right">
											<span><?php echo $beTubePostTime; ?></span>
										</div><!--thumb-stats-->
										<?php }?>
										
									</div><!--video-stats-->
								</div><!--post-thumb-->
								<?php } ?>
								<div class="post-des">
									<h6>
										<a href="<?php the_permalink(); ?>">
										<?php $theTitle = get_the_title(); echo $theTitle; ?>
										</a>
									</h6>
									<div class="post-stats clearfix">
										<p class="pull-left">
											<?php $user_ID = $post->post_author; ?>
											<i class="fa fa-user"></i>
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
						</div><!--item-->
						<?php } /*End While*/?>
					</div><!--row list-group-->
					<?php } /*End Main Tag IF*/?>
				</div><!--large-12-->
			</div><!--row secBg-->
		</section><!--content content-with-sidebar related-->		
	</div><!--End Large8-->
	<!-- left side content area -->
	<!-- sidebar -->
	<div class="large-4 columns">
		<aside class="secBg sidebar">
		<?php if($betubeSingleVideoLayout== 3){?>
			<div class="sidebarBg"></div>
		<?php }?>
			<div class="row">
				<?php get_sidebar('single-video'); ?>
			</div>
		</aside>
	</div>
	<!-- sidebar -->
</div>
<?php if($betubeSingleVideoLayout== 3){?>
</section>
<?php }?>
<?php else : ?>
<?php get_template_part( 'parts/content', 'missing' ); ?>
<?php endif; ?>	
<?php get_footer(); ?>