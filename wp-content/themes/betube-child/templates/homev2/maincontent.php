<?php
global $redux_demo;
/* First Section */
$betubeFirstSecON = $redux_demo['landing-first-section-on'];
$betubeFirstSecTitle = $redux_demo['landing-first-section-title'];
$betubeFirstSecView = $redux_demo['landing-first-grid-selection'];
$betubeFirstSecCategory = $redux_demo['landing-first-section-category'];
$betubeFirstSecCount = $redux_demo['landing-first-section-pcount'];
$betubeFirstSecOrder = $redux_demo['landing-first-section-order'];
$betubeFirstSecAds = $redux_demo['landing-first-section-ad_code'];
/* Second Section */
$betubeSecondSecON = $redux_demo['landing-second-section-on'];
$betubeSecondSecTitle = $redux_demo['landing-second-section-title'];
$betubeSecondSecView = $redux_demo['landing-second-grid-selection'];
$betubeSecondSecCategory = $redux_demo['landing-second-section-category'];
$betubeSecondSecCount = $redux_demo['landing-second-section-pcount'];
$betubeSecondSecOrder = $redux_demo['landing-second-section-order'];
$betubeSecondSecAds = $redux_demo['landing-second-section-ad_code'];
/* Third Section */
$betubeThirdSecON = $redux_demo['landing-third-section-on'];
$betubeThirdSecTitle = $redux_demo['landing-third-section-title'];
$betubeThirdSecView = $redux_demo['landing-third-grid-selection'];
$betubeThirdSecCategory = $redux_demo['landing-third-section-category'];
$betubeThirdSecCount = $redux_demo['landing-third-section-pcount'];
$betubeThirdSecOrder = $redux_demo['landing-third-section-order'];
$betubeThirdSecAds = $redux_demo['landing-third-section-ad_code'];
/* Fourth Section */
$betubeFourthSecON = $redux_demo['landing-fourth-section-on'];
$betubeFourthSecTitle = $redux_demo['landing-fourth-section-title'];
$betubeFourthSecView = $redux_demo['landing-fourth-grid-selection'];
$betubeFourthSecCategory = $redux_demo['landing-fourth-section-category'];
$betubeFourthSecCount = $redux_demo['landing-fourth-section-pcount'];
$betubeFourthSecOrder = $redux_demo['landing-fourth-section-order'];
$betubeFourthSecAds = $redux_demo['landing-fourth-section-ad_code'];
/* Five Section */
$betubeFiveSecON = $redux_demo['landing-five-section-on'];
$betubeFiveSecTitle = $redux_demo['landing-five-section-title'];
$betubeFiveSecView = $redux_demo['landing-five-grid-selection'];
$betubeFiveSecCategory = $redux_demo['landing-five-section-category'];
$betubeFiveSecCount = $redux_demo['landing-five-section-pcount'];
$betubeFiveSecOrder = $redux_demo['landing-five-section-order'];
$betubeFiveSecAds = $redux_demo['landing-five-section-ad_code'];
?>
<div class="row">
    <div class="large-8 columns">
        <?php
        /* First Section */
        if ($betubeFirstSecON == 1) {
            $sectionTitle = $betubeFirstSecTitle;
            $sectionCategory = $betubeFirstSecCategory;
            $sectionView = $betubeFirstSecView;
            $sectionCount = $betubeFirstSecCount;
            $sectionOrder = $betubeFirstSecOrder;
            $sectionAdsCode = $betubeFirstSecAds;
//            beTubeGetHomeContent($sectionTitle, $sectionCategory, $sectionView, $sectionCount, $sectionOrder, $sectionAdsCode);
            ?>
            <!-- main content -->
            <section class="content content-with-sidebar">	
                <!-- newest video -->
                <div class="main-heading">
                    <div class="row secBg padding-14">
                        <div class="medium-8 small-8 columns">
                            <div class="head-title">
                                <i class="flaticon-agenda"></i>
                                <h4><?php
                                    if (empty($sectionTitle)) {
                                        echo $sectionCategory;
                                    } else {
                                        echo $sectionTitle;
                                    }
                                    ?>
                                </h4>
                            </div>
                        </div> 
                    </div>
                </div><!--mainheading-->
                <div class="row secBg">
                    <div class="large-12 columns">
                        <div class="row column head-text clearfix">
                            <?php
                            $postsInCat = get_term_by('id', $sectionCategory, 'category');
                            $totalPost = $postsInCat->count;
                            ?>
                            <!--<p class="pull-left"><?php esc_html_e('All Flipbooks', 'betube'); ?>&nbsp;:&nbsp;<span><?php echo $totalPost; ?>&nbsp;<?php esc_html_e('Flipbook posted', 'betube'); ?></span></p>-->
                            <div class="grid-system pull-right show-for-large">
                                <a class="secondary-button <?php
                                if ($sectionView == "gridsmall") {
                                    echo "current";
                                }
                                ?> grid-default" href="#"><i class="fa fa-th"></i></a>
                                <a class="secondary-button <?php
                                if ($sectionView == "gridmedium") {
                                    echo "current";
                                }
                                ?> grid-medium" href="#"><i class="fa fa-th-large"></i></a>
                                <a class="secondary-button <?php
                                if ($sectionView == "listmedium") {
                                    echo "current";
                                }
                                ?> list" href="#"><i class="fa fa-th-list"></i></a>
                            </div>
                        </div><!--headtext-->
                        <div class="tabs-content">
                            <div class="tabs-panel tab-content active" data-content="1">
                                <?php
                                global $paged, $wp_query, $wp;
                                $args = wp_parse_args($wp->matched_query);
                                if (!empty($args['paged']) && 0 == $paged) {
                                    $wp_query->set('paged', $args['paged']);
                                    $paged = $args['paged'];
                                }
//                                if ($sectionOrder == 'views') {
//                                    $arags = array(
//                                        'post_type' => 'post',
//                                        'posts_per_page' => $sectionCount,
//                                        'paged' => $paged,
//                                        'cat' => $sectionCategory,
//                                        'order' => 'DESC',
//                                        'meta_key' => 'wpb_post_views_count',
//                                        'orderby' => 'meta_value_num'
//                                    );
//                                } else {
                                    $arags = array(
                                        'post_type' => 'post',
                                        'posts_per_page' => 12,
                                        'paged' => $paged,
                                        'taxonomy' => 'category',
                                        'order' => 'DESC',
                                        'orderby' => 'date',
                                    );
                                //}
                                $wsp_query = new WP_Query($arags);
                                $current = 1;
                                ?>
                                <div class="row list-group">
                                    <?php
                                    $myClass = "";
                                    if ($sectionView == "gridsmall") {
                                        $myClass = "group-item-grid-default";
                                    } elseif ($sectionView == "gridmedium") {
                                        $myClass = "grid-medium";
                                    } elseif ($sectionView == "listmedium") {
                                        $myClass = "list";
                                    }
                                    ?>
                                    <?php
                                    while ($wsp_query->have_posts()) : $wsp_query->the_post();
                                        $current++;

                                        global $post;
                                        $post_id = $post->ID;
                                        $likeCountArr  = get_post_meta($post_id, '_post_like_count');
                                        $likeCount = 0;
                                        if(!empty($likeCountArr)){
                                            $likeCount = $likeCountArr[0];
                                        }
                                        ?>
                                        <div class="item large-3 medium-6 columns end <?php echo $myClass; ?>">
                                            <div class="post thumb-border">
                                                <div class="post-thumb">
                                                    <?php
                                                    if (has_post_thumbnail()) {
                                                        $thumbURL = the_post_thumbnail();
                                                        if (empty($thumbURL)) {
                                                            $thumbURL = betube_thumb_url($post_id);
                                                        }
                                                    } else {


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
                                                    }
                                                    //$thumbURL = betube_thumb_url($post_id);
                                                    $altTag = betube_thumb_alt($post_id);
                                                    ?>
                                                    <img src="<?php echo esc_url($thumbURL); ?>" alt="<?php echo $altTag; ?>"/>
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
                                                            </div>
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
                                                            </div>
                                                        <?php } ?>
                                                    </div><!--video-stats-->
                                                </div><!--post-thumb-->
                                                <div class="post-des">
                                                    <h6>
                                                        <a href="<?php the_permalink(); ?>">
                                                            <?php
                                                            $theTitle = get_the_title();
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
                                    <?php wp_reset_postdata(); ?>
                                    <?php //echo $current."Shabir";    ?>
                                </div><!--row list-group-->
                            </div><!--tabspanel-->

                        </div><!--tabscontent-->
                        <?php
                        global $redux_demo;
                        $betubeAllVideoURL = $redux_demo['all-video-page-link'];
                        if (!empty($sectionCategory)) {
                            $currentCatLink = get_category_link($sectionCategory);
                            $currentCatName = get_the_category_by_ID($sectionCategory);
                        }
                        ?>
                        <!-- <div class="text-center row-btn">
                        <?php if (!empty($currentCatLink)) { ?>
                                                        <a class="button radius" href="<?php echo $currentCatLink; ?>"><?php esc_html_e('View All Flipbook In', 'betube'); ?> <?php echo $currentCatName; ?></a>
                        <?php } else { ?>
                                                        <a class="button radius" href="<?php echo $betubeAllVideoURL; ?>"><?php esc_html_e('View All Flipbook', 'betube'); ?></a>
                        <?php } ?>	
                        </div> --><!--End View All Button-->
                    </div><!--large12-->
                </div><!--row secBg-->
                <!-- newest video -->
            </section>
            <!-- main content -->
            <?php if (!empty($sectionAdsCode)) { ?>
                <div class="googleAdv text-center">
                    <?php echo $sectionAdsCode; ?>
                </div><!-- End ad Section -->
                <?php
            }
        }
        ?>
        <!-- Display Google Ads -->
        <div class="content">
            <div class="main-heading">
                <?php adserve(1); ?>
            </div>
        </div>
        <!-- End Display Google Ads -->      
        <?php
        /* Second Section */
        if ($betubeSecondSecON == 1) {
            $sectionTitle = $betubeSecondSecTitle;
            $sectionCategory = $betubeSecondSecCategory;
            $sectionView = $betubeSecondSecView;
            $sectionCount = $betubeSecondSecCount;
            $sectionOrder = $betubeSecondSecOrder;
            $sectionAdsCode = $betubeSecondSecAds;
//            beTubeGetHomeContent($sectionTitle, $sectionCategory, $sectionView, $sectionCount, $sectionOrder, $sectionAdsCode);
            ?>
            <!-- main content -->
            <section class="content content-with-sidebar">	
                <!-- newest video -->
                <div class="main-heading">
                    <div class="row secBg padding-14">
                        <div class="medium-8 small-8 columns">
                            <div class="head-title">
                                <i class="flaticon-notebook-2"></i>
                                <h4><?php
                                    if (empty($sectionTitle)) {
                                        echo $sectionCategory;
                                    } else {
                                        echo $sectionTitle;
                                    }
                                    ?></h4>
                            </div>
                        </div>

                    </div>
                </div><!--mainheading-->
                <div class="row secBg">
                    <div class="large-12 columns">
                        <div class="row column head-text clearfix">
                            <?php
                            $postsInCat = get_term_by('id', $sectionCategory, 'category');
                            $totalPost = $postsInCat->count;
                            ?>
                            <!--<p class="pull-left"><?php esc_html_e('All Flipbooks', 'betube'); ?>&nbsp;:&nbsp;<span><?php echo $totalPost; ?>&nbsp;<?php esc_html_e('Videos Flipbooks', 'betube'); ?></span></p>-->
                            <div class="grid-system pull-right show-for-large">
                                <a class="secondary-button <?php
                                if ($sectionView == "gridsmall") {
                                    echo "current";
                                }
                                ?> grid-default" href="#"><i class="fa fa-th"></i></a>
                                <a class="secondary-button <?php
                                if ($sectionView == "gridmedium") {
                                    echo "current";
                                }
                                ?> grid-medium" href="#"><i class="fa fa-th-large"></i></a>
                                <a class="secondary-button <?php
                                if ($sectionView == "listmedium") {
                                    echo "current";
                                }
                                ?> list" href="#"><i class="fa fa-th-list"></i></a>
                            </div>
                        </div><!--headtext-->
                        <div class="tabs-content">
                            <div class="tabs-panel tab-content active" data-content="1">
                                <?php
                                global $paged, $wp_query, $wp;
                                $args = wp_parse_args($wp->matched_query);
                                if (!empty($args['paged']) && 0 == $paged) {
                                    $wp_query->set('paged', $args['paged']);
                                    $paged = $args['paged'];
                                }

                                $arags = array(
                                    'post_type' => 'post',
                                    'posts_per_page' => 12,
                                    'paged' => $paged,
//                                    'cat' => $sectionCategory,
//                                    'order' => $sectionOrder,
                                    //'orderby' => $sectionOrder,
                                    'meta_key' => '_post_like_count',
                                    'orderby' => 'meta_value_num'
                                );
                                $wsp_query = new WP_Query($arags);
                                $current = 1;
                                ?>
                                <div class="row list-group">
                                    <?php
                                    $myClass = "";
                                    if ($sectionView == "gridsmall") {
                                        $myClass = "group-item-grid-default";
                                    } elseif ($sectionView == "gridmedium") {
                                        $myClass = "grid-medium";
                                    } elseif ($sectionView == "listmedium") {
                                        $myClass = "list";
                                    }
                                    ?>
                                    <?php
                                    while ($wsp_query->have_posts()) : $wsp_query->the_post();
                                        $current++;
                                        global $post;
                                        $post_id = $post->ID;
                                        $likeCountArr  = get_post_meta($post_id, '_post_like_count');
                                        $likeCount = 0;
                                        if(!empty($likeCountArr)){
                                            $likeCount = $likeCountArr[0];
                                        }
                                        ?>
                                        <div class="item large-3 medium-6 columns end <?php echo $myClass; ?>">
                                            <div class="post thumb-border">
                                                <div class="post-thumb">
                                                    <?php
                                                    if (has_post_thumbnail()) {
                                                        $thumbURL = the_post_thumbnail();
                                                        if (empty($thumbURL)) {
                                                            $thumbURL = betube_thumb_url($post_id);
                                                        }
                                                    } else {

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
                                                    }
                                                    //$thumbURL = betube_thumb_url($post_id);
                                                    $altTag = betube_thumb_alt($post_id);
                                                    ?>
                                                    <img src="<?php echo esc_url($thumbURL); ?>" alt="<?php echo $altTag; ?>"/>

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
                                                            </div>
                                                        <?php } ?>
                                                        <div class="thumb-stats pull-left">
                                                                    <!--<i class="fa fa-heart"></i>-->
                                                            <i class="fa fa-thumbs-o-up"></i><span><?php echo $likeCount; ?></span>
                                                        </div>

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
                                                <div class="post-des">
                                                    <h6>
                                                        <a href="<?php the_permalink(); ?>">
                                                            <?php
                                                            $theTitle = get_the_title();
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
                                    <?php wp_reset_postdata(); ?>
                                    <?php //echo $current."Shabir";     ?>
                                </div><!--row list-group-->
                            </div><!--tabspanel-->

                        </div><!--tabscontent-->
                        <?php
                        global $redux_demo;
                        $betubeAllVideoURL = $redux_demo['all-video-page-link'];
                        if (!empty($sectionCategory)) {
                            $currentCatLink = get_category_link($sectionCategory);
                            $currentCatName = get_the_category_by_ID($sectionCategory);
                        }
                        ?>
                        <!-- <div class="text-center row-btn">
                        <?php if (!empty($currentCatLink)) { ?>
                                                        <a class="button radius" href="<?php echo $currentCatLink; ?>"><?php esc_html_e('View All Flipbook In', 'betube'); ?> <?php echo $currentCatName; ?>
                                                        </a>
                        <?php } else { ?>
                                                        <a class="button radius" href="<?php echo $betubeAllVideoURL; ?>"><?php esc_html_e('View All Flipbook', 'betube'); ?>
                                                        </a>
                        <?php } ?>	
                        </div> --><!--End View All Button-->
                    </div><!--large12-->
                </div><!--row secBg-->
                <!-- newest video -->
            </section>
            <!-- main content -->
            <?php if (!empty($sectionAdsCode)) { ?>
                <div class="googleAdv text-center">
                    <?php echo $sectionAdsCode; ?>
                </div><!-- End ad Section -->
                <?php
            }
        }
        /* Third Section */
        /* if($betubeThirdSecON == 1){
          $sectionTitle = $betubeThirdSecTitle;
          $sectionCategory = $betubeThirdSecCategory;
          $sectionView = $betubeThirdSecView;
          $sectionCount = $betubeThirdSecCount;
          $sectionOrder = $betubeThirdSecOrder;
          $sectionAdsCode = $betubeThirdSecAds;
          beTubeGetHomeContent($sectionTitle, $sectionCategory, $sectionView, $sectionCount, $sectionOrder, $sectionAdsCode);
          }
          /*Fourth Section */
        /* if($betubeFourthSecON == 1){
          $sectionTitle = $betubeFourthSecTitle;
          $sectionCategory = $betubeFourthSecCategory;
          $sectionView = $betubeFourthSecView;
          $sectionCount = $betubeFourthSecCount;
          $sectionOrder = $betubeFourthSecOrder;
          $sectionAdsCode = $betubeFourthSecAds;
          beTubeGetHomeContent($sectionTitle, $sectionCategory, $sectionView, $sectionCount, $sectionOrder, $sectionAdsCode);
          }
          /*Fifth Section */
        /* if($betubeFiveSecON == 1){
          $sectionTitle = $betubeFiveSecTitle;
          $sectionCategory = $betubeFiveSecCategory;
          $sectionView = $betubeFiveSecView;
          $sectionCount = $betubeFiveSecCount;
          $sectionOrder = $betubeFiveSecOrder;
          $sectionAdsCode = $betubeFiveSecAds;
          beTubeGetHomeContent($sectionTitle, $sectionCategory, $sectionView, $sectionCount, $sectionOrder, $sectionAdsCode);
          } */
        ?>
    </div><!--End Large8-->
    <div class="large-4 columns rightsidebar">
        <aside class="secBg sidebar">
            <div class="row">
                <?php dynamic_sidebar('main'); ?>

            </div>
        </aside>
    </div>
</div>