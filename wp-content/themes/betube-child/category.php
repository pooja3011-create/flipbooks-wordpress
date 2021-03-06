<?php
/**
 * The template for displaying Category pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage betube
 * @since betube 1.0
 */
get_header();
betube_breadcrumbs();
global $redux_demo;
$betubeFirstSecView = $redux_demo['landing-first-grid-selection'];
$betubeFeaturedSlider = $redux_demo['featured-slider-on'];
$myClass = "";
if ($betubeFirstSecView == "gridsmall") {
    $myClass = "group-item-grid-default";
} elseif ($betubeFirstSecView == "gridmedium") {
    $myClass = "grid-medium";
} elseif ($betubeFirstSecView == "listmedium") {
    $myClass = "list";
}
if ($betubeFeaturedSlider == 1) {
    get_template_part('templates/featuredvideos');
}
?>
<section class="category-content">
    <div class="row">
        <!-- left side content area -->
        <div class="large-8 columns">
            <section class="content content-with-sidebar">
                <div class="main-heading removeMargin">
                    <div class="row secBg padding-14 removeBorderBottom">
                        <?php
                        $cat_id = get_cat_ID(single_cat_title('', false));
                        $this_category = get_category($cat_id);
                        $cat_id = get_cat_ID(single_cat_title('', false));
                        $cat_parent_ID = isset($cat_id->category_parent) ? $cat_id->category_parent : '';
                        if ($cat_parent_ID == 0) {
                            $tag = $cat_id;
                        } else {
                            $tag = $cat_parent_ID;
                        }
                        $category = get_category($tag);
                        $count = $category->category_count;
                        $catName = get_cat_name($tag);
                        ?>
                        <div class="medium-8 small-8 columns">
                            <div class="head-title">
                                <i class="flaticon-2-squares"></i>
                                <h4><?php echo $catName; ?></h4>
                            </div>
                        </div><!--End medium-8-->

                    </div><!--End Row-->
                </div><!--main-heading-->
                <div class="row secBg">
                    <div class="large-12 columns">
                        <div class="row column head-text clearfix">
                            <?php
                            $q = new WP_Query(array(
                                'nopaging' => true,
                                'tax_query' => array(
                                    array(
                                        'taxonomy' => 'category',
                                        'field' => 'id',
                                        'terms' => $tag,
                                        'include_children' => true,
                                    ),
                                ),
                                'fields' => 'ids',
                                'post_type' => 'post'
                            ));
                            $allPosts = $q->post_count;
                            ?>

                            <p class="pull-left"><?php esc_html_e('All Flipbooks', 'betube'); ?> : <span><?php echo $allPosts; ?>&nbsp; <?php esc_html_e('Flipbook Posted', 'betube'); ?></span></p>
                            <div class="grid-system pull-right show-for-large">
                                <a class="secondary-button <?php
                                if ($betubeFirstSecView == "gridsmall") {
                                    echo "current";
                                }
                                ?> grid-default" href="#"><i class="fa fa-th"></i></a>
                                <a class="secondary-button <?php
                                if ($betubeFirstSecView == "gridmedium") {
                                    echo "current";
                                }
                                ?> grid-medium" href="#"><i class="fa fa-th-large"></i></a>
                                <a class="secondary-button <?php
                                if ($betubeFirstSecView == "listmedium") {
                                    echo "current";
                                }
                                ?> list" href="#"><i class="fa fa-th-list"></i></a>
                            </div><!--grid-system-->
                        </div><!--row column-->
                        <div class="tabs-content" data-tabs-content="newVideos">
                            <div class="tabs-panel is-active" id="new-all">
                                <?php
                                global $paged, $wp_query, $wp;
//                                $args = wp_parse_args($wp->matched_query);
//                                echo '<pre>';
//                                print_r($args);
                                $page = get_query_var('page');
//                                echo '</pre>';
//                                if (!empty($args['paged']) && 0 == $paged) {
//                                    $wp_query->set('paged', $args['paged']);
//                                   echo  $paged = $args['paged'];
//                                }
                                $cat_id = get_cat_ID(single_cat_title('', false));
                                $temp = $wp_query;
                                $wp_query = null;
                                $wp_query = new WP_Query();
                                $postPerPage = get_option('posts_per_page');
                                $wp_query->query('post_type=post&posts_per_page=' . $postPerPage . '&paged=' . $page . '&cat=' . $cat_id);
                                $current = -1;
                                $current2 = 0;
                                //print_r($wp_query);
                                ?>
                                <div class="row list-group">
                                    <?php while ($wp_query->have_posts()) : $wp_query->the_post(); $current++; $current2++; ?>
                                        <div class="item large-3 medium-6 columns end <?php echo $myClass; ?>">
                                            <div class="post thumb-border">
                                                <?php if (has_post_thumbnail()) { ?>
                                                    <div class="post-thumb">
                                                        <?php
                                                        global $post;
                                                        $post_id = $post->ID;
                                                        $thumbURL = the_post_thumbnail();
                                                        if (empty($thumbURL)) {
                                                            $thumbURL = betube_thumb_url($post_id);
                                                        }
                                                        //$thumbURL = betube_thumb_url($post_id);
                                                        $altTag = betube_thumb_alt($post_id);
                                                        ?>
                                                        <img src="<?php echo esc_url($thumbURL); ?>" alt="<?php echo $altTag; ?>"/>
                                                        <a href="<?php the_permalink(); ?>" class="hover-posts">
                                                            <span><i class="flaticon-open-book-top-view"></i><?php esc_html_e('View Flipbook', 'betube'); ?></span>
                                                        </a>
                                                        <div class="video-stats clearfix">
                                                            <?php /* $beTubePostHD = get_post_meta($post->ID, 'post_quality', true);
                                                              if (!empty($beTubePostHD)) {
                                                              ?>
                                                              <div class="thumb-stats pull-left">
                                                              <h6><?php echo $beTubePostHD; ?></h6>
                                                              </div>
                                                              <?php } */ ?>
                                                            <?php /* include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                                                              if (is_plugin_active("betube-helper/index.php")) {
                                                              ?>
                                                              <div class="thumb-stats pull-left">
                                                              <!--<i class="fa fa-heart"></i>-->
                                                              <span><?php echo get_simple_likes_button(get_the_ID()); ?></span>
                                                              </div>
                                                              <?php } */ ?>
                                                            <?php
                                                            $beTubePostTime = get_post_meta($post->ID, 'post_time', true);
                                                            if (!empty($beTubePostTime)) {
                                                                ?>
                                                                <div class="thumb-stats pull-right">
                                                                    <span><?php echo $beTubePostTime; ?></span>
                                                                </div>
                                                            <?php } ?>
                                                        </div><!--video-stats-->
                                                    </div><!--post-thumb-->
                                                <?php } else {
                                                    ?>
                                                    <div class="post-thumb">
                                                        <?php
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
                                                        if (empty($mediaArr['key'])) {
                                    						$thumbURL = betube_thumb_url($post_id);
                                						}

                                                        $altTag = betube_thumb_alt($post_id);
                                                        ?>
                                                        <img src="<?php echo esc_url($thumbURL); ?>" alt="<?php echo $altTag; ?>"/>
                                                        <a href="<?php the_permalink(); ?>" class="hover-posts">
                                                            <span><i class="flaticon-open-book-top-view"></i><?php esc_html_e('View Flipbook', 'betube'); ?></span>
                                                        </a>
                                                        <div class="video-stats clearfix">
                                                            <?php
                                                            /* $beTubePostHD = get_post_meta($post->ID, 'post_quality', true);
                                                              if (!empty($beTubePostHD)) {
                                                              ?>
                                                              <div class="thumb-stats pull-left">
                                                              <h6><?php echo $beTubePostHD; ?></h6>
                                                              </div>
                                                              <?php
                                                              }
                                                              include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                                                              if (is_plugin_active("betube-helper/index.php")) {
                                                              ?>
                                                              <div class="thumb-stats pull-left">
                                                              <!--<i class="fa fa-heart"></i>-->
                                                              <span><?php echo get_simple_likes_button(get_the_ID()); ?></span>
                                                              </div>
                                                              <?php
                                                              } */
                                                            $beTubePostTime = get_post_meta($post->ID, 'post_time', true);
                                                            if (!empty($beTubePostTime)) {
                                                                ?>
                                                                <div class="thumb-stats pull-right">
                                                                    <span><?php echo $beTubePostTime; ?></span>
                                                                </div>
                                                            <?php } ?>
                                                        </div><!--video-stats-->
                                                    </div><!--post-thumb-->
                                                <?php }
                                                ?>
                                                <div class="post-des">
                                                    <h6>
                                                        <a href="<?php the_permalink(); ?>">
                                                            <?php $theTitle = get_the_title(); echo $theTitle; ?>
                                                        </a>
                                                    </h6>
                                                    <div class="post-stats clearfix">
                                                        <p class="pull-left">
                                                            <i class="fa fa-user"></i>
                                                            <?php
                                                            $user_ID = $post->post_author;
                                                            ?>
                                                            <span><a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><?php echo get_the_author_meta('display_name', $user_ID); ?></a></span>
                                                        </p>
                                                        <p class="pull-left">
                                                            <i class="fa fa-clock-o"></i>
                                                            <?php $beTubedateFormat = get_option('date_format'); ?>
                                                            <?php $post_id = $post->ID; ?>
                                                            <span><?php echo get_the_date($beTubedateFormat, $post_id); ?></span>
                                                        </p>
                                                        <p class="pull-left">
                                                            <i class="fa fa-eye"></i>
                                                            <span><?php echo betube_get_post_views(get_the_ID()); ?></span>
                                                        </p>
                                                    </div><!--post-stats-->
                                                    <div class="post-summary">
                                                        <p>
                                                            <?php echo substr(get_the_excerpt(), 0, 260); ?>
                                                        </p>
                                                    </div><!--post-summary-->
                                                    <div class="post-button">
                                                        <a href="<?php the_permalink(); ?>" class="secondary-button"><i class="flaticon-open-book-top-view"></i><?php esc_html_e('View Flipbook', 'betube'); ?></a>
                                                    </div><!--post-button-->
                                                </div><!--post-des-->
                                            </div><!--post thumb-border-->
                                        </div><!--item-->
                                    <?php endwhile; ?>
                                </div><!--row list-group-->
                                <?php
                                /* global $redux_demo;
                                  $beTubeScroll = $redux_demo['infinite-scroll'];
                                  if ($beTubeScroll == 1) {
                                  echo infinite($wp_query);
                                  } else {
                                  wp_reset_postdata();
                                  get_template_part('pagination');
                                  } */
                                $totalPosts = $allPosts;

                                $postPerPage = get_option('posts_per_page');

                                $totalPages = ceil($totalPosts / $postPerPage);
                                $url = get_category_link($cat_id);
                                if (parse_url($url, PHP_URL_QUERY)) {
                                    $url .= '&page=';
                                } else {
                                    $url .= '?page=';
                                }
                                if(isset($_GET['page'])){
                                    $page = $_GET['page'];
                                }else{
                                    $page = 0;
                                }
//                                 $page = get_query_var('page');
                                if ($page == 0) {
                                    $page = 1;
                                }                                
                                if ($totalPages <= 1) {
                                    //DO NOTHING
                                } else {
                                    ?>
                                    <div class="post_pagination">
                                        <?php
                                        if ($page <= 1) {
                                            ?> <a class="prev page-numbers" href="javascript:;">« Previous</a> <?php
                                        } else {
                                            ?> <a class="prev page-numbers" href="<?php echo $url . ($page - 1); ?>">« Previous</a> <?php
                                        }
                                        for ($i = 1; $i <= $totalPages; $i++) {
                                            if ($page == $i) {
                                                ?><span class="page-numbers current"><?php echo $i ?></span> <?php
                                            } else {
                                                ?><a class="page-numbers" href="<?php echo $url . $i; ?>"><?php echo $i ?></a> <?php
                                            }
                                        }
                                        if ($page >= $totalPages) {
                                            ?> <a class="next page-numbers" href="javascript:;">Next »</a> <?php
                                        } else {
                                            ?> <a class="next page-numbers" href="<?php echo $url . ($page + 1); ?>">Next »</a> <?php
                                        }
                                        ?>
                                    </div>
                                <?php }
                                
                                 wp_reset_postdata();?>
                              
                            </div><!--tabs-panel-->

                        </div><!--tabs-content-->
                    </div><!--End large-12-->
                </div><!--End row secBg-->
            </section><!--End Content Section-->
            <?php
            $beTubeCatAds = $redux_demo['betube-google-ads-for-category-page'];
            if (!empty($beTubeCatAds)) {
                ?>
                <div class="googleAdv">
                    <?php echo $beTubeCatAds; ?>
                </div><!-- End ad Section -->
            <?php } ?>
         <!-- Display Google Ads -->
           <?php adserve(1); ?>
        <!-- End Display Google Ads -->
        </div><!--End large-8-->
        <!-- left side content area -->
        <div class="large-4 columns">
            <aside class="secBg sidebar">
                <div class="row">
                    <?php get_sidebar('main'); ?>
                </div>
            </aside>
        </div><!--End large-4-->
    </div><!--End Row-->
</section><!--End category-content-->
<?php get_footer(); ?>