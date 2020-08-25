<?php
$postPerPage = get_option('posts_per_page');

$url = get_search_link();
if (parse_url($url, PHP_URL_QUERY)) {
    $url .= '&page=';
} else {
    $url .= '?page=';
}
if (isset($_GET['page'])) {
    $currPage = $_GET['page'];
} else {
    $currPage = 0;
}

get_header();
betube_breadcrumbs();
global $redux_demo;
$betubeGridView = $redux_demo['betube-main-grid-selection'];
$betubeGoogleAdsAllVideos = $redux_demo['betube-google-ads-for-all-video-page'];
$myClass = "";
if ($betubeGridView == "gridsmall") {
    $myClass = "group-item-grid-default";
} elseif ($betubeGridView == "gridmedium") {
    $myClass = "grid-medium";
} elseif ($betubeGridView == "listmedium") {
    $myClass = "list";
}
?>
<section class="category-content">
    <div class="row">
        <!-- left side content area -->
        <div class="large-8 columns">
            <section class="content content-with-sidebar">
                <div class="main-heading removeMargin">
                    <div class="row secBg padding-14 removeBorderBottom">
                        <div class="medium-8 small-12 columns">
                            <div class="head-title">
                                <i class="fa fa-search"></i>
                                <h4><?php esc_html_e('Search Results for:', 'betube'); ?> <?php echo esc_attr(get_search_query()); ?></h4>
                            </div><!--head-title-->
                        </div><!--medium-8-->
                    </div><!--row-->
                </div><!--main-heading-->
                <div class="row secBg">
                    <div class="large-12 columns">
                        <div class="row column head-text clearfix">
                            <?php
                            //$allsearch = new WP_Query("s=$s&showposts=-1");
                            $allsearch = new WP_Query(array('post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => $postPerPage, 'paged' => $currPage, 's' => $s));
                            $allResult = $allsearch->found_posts;
                            ?>
                            <p class="pull-left"><?php esc_html_e('Search Results', 'betube'); ?>&nbsp;:&nbsp;<span><?php echo $allResult; ?>&nbsp;<?php esc_html_e('Result(s) Found.', 'betube'); ?></span></p>
                            <div class="grid-system pull-right show-for-large">
                                <a class="secondary-button <?php
                                if ($betubeGridView == "gridsmall") {
                                    echo "current";
                                }
                                ?> grid-default" href="#"><i class="fa fa-th"></i></a>
                                <a class="secondary-button <?php
                                if ($betubeGridView == "gridmedium") {
                                    echo "current";
                                }
                                ?> grid-medium" href="#"><i class="fa fa-th-large"></i></a>
                                <a class="secondary-button <?php
                                if ($betubeGridView == "listmedium") {
                                    echo "current";
                                }
                                ?> list" href="#"><i class="fa fa-th-list"></i></a>
                            </div><!--grid-system-->
                        </div><!--row column-->
                        <div class="tabs-content" data-tabs-content="newVideos">
                            <div class="tabs-panel is-active" id="new-all">
                                <div class="row list-group">
                                    <?php
                                    if ($allsearch->have_posts()) : while ($allsearch->have_posts()) : $allsearch->the_post();

                                            global $post;
                                            $post_id = $post->ID;
                                            $likeCountArr = get_post_meta($post_id, '_post_like_count');
                                            $likeCount = 0;
                                            if (!empty($likeCountArr)) {
                                                $likeCount = $likeCountArr[0];
                                            }
                                            ?>
                                            <div class="item large-4 medium-6 columns end <?php echo $myClass; ?>">
                                                <div class="post thumb-border">

                                                    <div class="post-thumb">
                                                        <?php
                                                        if (has_post_thumbnail()) {
                                                            $thumbURL = the_post_thumbnail();
                                                            if (empty($thumbURL)) {
                                                                $thumbURL = betube_thumb_url($post_id);
                                                            }
                                                        } else {

                                                            $post_id = $post->ID;
                                                            $media = get_attached_media('image', $post_id);
                                                            foreach ($media as $key => $val) {
                                                                $s3Media = get_post_meta($val->ID);
                                                                $mediaArr = unserialize($s3Media['amazonS3_info'][0]);
                                                                $thumbURL = S3_UPLOADS . $mediaArr['key'];
                                                                break;
                                                            }
                                                            if (empty($mediaArr['key'])) {
                                                                $thumbURL = betube_thumb_url($post_id);
                                                            }
                                                        }
                                                        //$thumbURL = betube_thumb_url($post_id);
                                                        $altTag = betube_thumb_alt($post_id);
                                                        ?>


                                                        <img src="<?php echo $thumbURL; ?>" alt="<?php echo $altTag; ?>"/>

                                                        <a href="<?php the_permalink(); ?>" class="hover-posts">
                                                            <span><i class="flaticon-open-book-top-view"></i><?php esc_html_e('View Flipbook', 'betube'); ?></span>
                                                        </a>
                                                        <div class="video-stats clearfix">
                                                            <?php
                                                            $beTubePostHD = get_post_meta($post->ID, 'post_quality', true);
                                                            if (!empty($beTubePostHD)) {
                                                                ?>
                                                                <div class="thumb-stats pull-left">
                                                                    <h6><?php echo $beTubePostHD; ?></h6>
                                                                </div><!--Video HD-->
                                                            <?php } ?>
                                                            <div class="thumb-stats pull-left">

                                                                <i class="fa fa-thumbs-o-up"></i><span><?php echo $likeCount; ?></span>
                                                            </div>
                                                            <?php
                                                            $beTubePostTime = get_post_meta($post->ID, 'post_time', true);
                                                            if (!empty($beTubePostTime)) {
                                                                ?>
                                                                <div class="thumb-stats pull-right">
                                                                    <span><?php echo $beTubePostTime; ?></span>
                                                                </div><!--Video Time-->
                                                            <?php } ?>
                                                        </div><!--video-stats-->
                                                    </div><!--post-thumb-->

                                                    <div class="post-des">
                                                        <h6>
                                                            <a href="<?php the_permalink(); ?>">
                                                                <?php
                                                                $theTitle = get_the_title();
                                                                $theTitle = (strlen($theTitle) > 25) ? substr($theTitle, 0, 25) . '...' : $theTitle;
                                                                echo $theTitle;
                                                                ?>
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
                                                                <?php
                                                                global $post;
                                                                $post_id = $post->ID;
                                                                ?>
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
                                                            <a href="<?php the_permalink(); ?>" class="secondary-button"><i class="flaticon-open-book"></i><?php esc_html_e('View Flipbook', 'betube'); ?></a>
                                                        </div><!--post-button-->
                                                    </div><!--post-des-->
                                                </div><!--post thumb-border-->
                                            </div><!--item large-4-->
                                        <?php endwhile; ?>								
                                    <?php else : ?>
                                        <div class="searchMising">
                                            <?php get_template_part('parts/content', 'missing'); ?>
                                        </div>
                                    <?php endif; ?>
                                </div><!--row list-group-->
                                <?php
                                $totalPosts = $allsearch->found_posts;

                                $totalPages = ceil($totalPosts / $postPerPage);

                                if ($currPage == 0) {
                                    $currPage = 1;
                                }

                                if ($totalPages <= 1) {
                                    //DO NOTHING
                                } else {
                                    ?>
                                    <div class="post_pagination">
                                        <?php
                                        if ($currPage <= 1) {
                                            ?> <a class="prev page-numbers" href="javascript:void(0);">« Previous</a> <?php
                                        } else {
                                            ?> <a class="prev page-numbers" href="<?php echo $url . ($currPage - 1); ?>">« Previous</a> <?php
                                        }
                                        for ($i = 1; $i <= $totalPages; $i++) {
                                            if ($currPage == $i) {
                                                ?><span class="page-numbers current"><?php echo $i ?></span> <?php
                                            } else {
                                                ?><a class="page-numbers" href="<?php echo $url . $i; ?>"><?php echo $i ?></a> <?php
                                            }
                                        }
                                        if ($currPage >= $totalPages) {
                                            ?> <a class="next page-numbers" href="javascript:void(0);">Next »</a> <?php
                                        } else {
                                            ?> <a class="next page-numbers" href="<?php echo $url . ($currPage + 1); ?>">Next »</a> <?php
                                        }
                                        ?>
                                    </div>
                                    <?php
                                }


                                wp_reset_postdata();
                                ?>
                            </div><!--tabs-panelt-->
                        </div><!--tabs-content-->
                    </div><!--large-12-->
                </div><!--row-->
            </section><!--content content-with-sidebar-->
            <div class="googleAdv">
                <?php echo $betubeGoogleAdsAllVideos; ?>
            </div><!-- End ad Section -->
            <!-- Display Google Ads -->
            <?php adserve(1); ?>
            <!-- End Display Google Ads -->
        </div><!--large8-->
        <!-- left side content area -->
        <!--sidebar-->
        <div class="large-4 columns">
            <aside class="secBg sidebar">
                <div class="row">
                    <?php get_sidebar('main'); ?>
                </div>
            </aside><!--secBg sidebar-->
        </div><!--large-4-->
    </div><!--row-->
</section><!--category-content-->
<?php get_footer(); ?>