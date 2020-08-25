<?php
global $redux_demo;
$betubeFcatON = $redux_demo['featured-caton'];
$betubeFcat = $redux_demo['featured-cat'];
$betubeFcatCount = $redux_demo['featured-counter'];


global $paged, $wp_query, $wp, $post;
             $arags = array(
                 'post_type' => 'post',
                 'meta_key' => 'wpb_post_views_count',
                 'orderby' => 'meta_value_num',
                 'order' => 'DESC',
                 'post_status' => 'publish',
                 'posts_per_page' => 10,
             );
             
             if(is_category()) {
             	$category = get_category( get_query_var( 'cat' ) );
             	$cat_arr = array($category->cat_ID);
             	
             	if($category->parent == 0) {
             		$terms = get_terms(array('category'), array('parent'=>$category->cat_ID));
             		
             		if(!empty($terms)) {
             			foreach($terms as $term) {
             				$cat_arr[] = $term->term_id;
             			}
             		}
             	}
             	
				$arags['category__in'] = $cat_arr;
             }
             //print_r($arags);
             
    $wp_query = new WP_Query($arags);
    //print_r($wp_query);
    
    if($wp_query->post_count > 0) {
?>
<section id="premium">
    <div class="row">
        <div class="heading clearfix">
            <div class="large-11 columns">				
                <h4><i class="flaticon-bar-chart"></i><?php esc_html_e('Trending Flipbooks', 'betube'); ?></h4>
            </div>
            <div class="large-1 columns">
                <div class="navText show-for-large">
                    <a class="prev secondary-button"><i class="fa fa-angle-left"></i></a>
                    <a class="next secondary-button"><i class="fa fa-angle-right"></i></a>
                </div>
            </div><!--End large-1-->
        </div><!--End heading-->
    </div><!--End Row-->
    <div id="owl-demo" class="owl-carousel carousel" data-right="<?php
    if (is_rtl()) {
        echo 'true';
    } else {
        echo 'false';
    }
    ?>" data-car-length="4" data-items="4" data-loop="true" data-nav="false" data-autoplay="true" data-autoplay-timeout="3000" data-dots="false" data-auto-width="false" data-responsive-small="1" data-responsive-medium="2" data-responsive-xlarge="5">
             <?php
             $number = 1;
             while ($wp_query->have_posts()) : $wp_query->the_post();
                 $beTubeFeaturedPost = get_post_meta($post->ID, 'featured_post', true);
                 ?>  <div class="item <?php echo $number; ?>">
                <figure class="premium-img" style="display:none;">			
                    <?php
                    global $post;
                    $post_id = $post->ID;

                    if (has_post_thumbnail()) { 
                        $thumbURL = the_post_thumbnail();
                        if (empty($thumbURL)) {
                            $thumbURL = betube_thumb_url($post_id);
                        }
                    } else { 
                        global $post;
                        $post_id = $post->ID;
                        $media = get_attached_media('image', $post_id);
                        $mediaArr = array();
                        foreach ($media as $key => $val) {
                            $s3Media = get_post_meta($val->ID);
                            $mediaArr = unserialize($s3Media['amazonS3_info'][0]);
                            $thumbURL = S3_UPLOADS . $mediaArr['key'];
                            break;
                        }
//                        echo $mediaArr['key'];
                        if (empty($mediaArr['key'])) {
                            $thumbURL = betube_thumb_url($post_id);
                        }
                    }
                    $altTag = betube_thumb_alt($post_id);
                    ?>
                    <img src="<?php echo esc_url($thumbURL); ?>" alt="<?php echo $altTag; ?>"/>
                    <figcaption>
                        <?php
                        $betubepostCatgory = get_the_category($post->ID);
                        ?>
                        <h5>
                            <?php $betubePostTitle = get_the_title(); echo $betubePostTitle; ?>
                        </h5>
                        <p><?php echo $betubepostCatgory[0]->name; ?></p>
                    </figcaption>
                </figure>
                <a href="<?php the_permalink(); ?>" class="hover-posts">
                    <span><i class="flaticon-open-book-top-view"></i><?php esc_html_e('view flipbook', 'betube'); ?></span>
                </a>
            </div> <!--End item-->
        <?php endwhile; ?>
        <?php wp_reset_postdata(); ?>
        <?php wp_reset_query(); ?>
    </div><!--End owl-demo-->
</section><!--End Featured Section-->

<?php
	}
?>