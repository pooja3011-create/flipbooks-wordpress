<?php
/**
 * Template name: Profile Page
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage betube
 * @since betube
 */
global $user_ID;
$page = get_page($post->ID);
$author = get_user_by('slug', get_query_var('author_name'));
$user_ID = $author->ID;
$user_info = get_userdata($user_ID);
get_header();
betube_breadcrumbs();
global $redux_demo;
/* First Section */
$betubeFirstSecON = $redux_demo['landing-first-section-on'];
$betubeFirstSecTitle = $redux_demo['landing-first-section-title'];
$betubeFirstSecView = $redux_demo['landing-first-grid-selection'];
$betubeFirstSecCategory = $redux_demo['landing-first-section-category'];
$betubeFirstSecCount = $redux_demo['landing-first-section-pcount'];
$betubeFirstSecOrder = $redux_demo['landing-first-section-order'];
$betubeFirstSecAds = $redux_demo['landing-first-section-ad_code'];

$contact_email = get_the_author_meta('user_email', $user_ID);
$betubeProfileIMG = get_user_meta($user_ID, "betube_author_profile_bg", true);
$profileCoverPhoto = get_option('profile_cover_photo');
//if (isset($_POST['follow'])) {
//    $author_id = $_POST['author_id'];
//    $follower_id = $_POST['follower_id'];
//    global $wpdb;
//    $author_insert = ("INSERT into {$wpdb->prefix}author_followers (author_id,follower_id)value('" . $author_id . "','" . $follower_id . "')");
//    $wpdb->query($author_insert);
//}
if (isset($_POST['unfollow'])) {
    $author_id = $_POST['author_id'];
    $follower_id = $_POST['follower_id'];
    $author_del = ("DELETE from {$wpdb->prefix}author_followers WHERE author_id = $author_id AND follower_id = $follower_id ");

    $wpdb->query($author_del);
}
?>
<?php
$profilePhoto = $betubeProfileIMG;
if ($betubeProfileIMG == '') {
    $profilePhoto = $profileCoverPhoto;
}
?>

<section class="topProfile" style="background: url('<?php echo $profilePhoto; ?>') no-repeat;">
    <div class="main-text text-center">
        <div class="row">
            <div class="large-12 columns">	
                <?php
                $firstText = get_the_author_meta('firsttext', $user_id);
                $secondText = get_the_author_meta('secondtext', $user_id);
                if ($firstText != '') {
                    ?>
                    <h3><?php echo $betubeFirstTXT = the_author_meta('firsttext', $user_id); ?></h3>
                    <?php
                }
                if ($secondText != '') {
                    ?>
                    <h1><?php echo $betubeFirstTXT = the_author_meta('secondtext', $user_id); ?></h1>
                <?php } ?>
            </div><!--large-12-->
        </div><!--row-->
    </div><!--main-text-->
    <div class="profile-stats">
        <div class="row secBg">
            <div class="large-12 columns">
                <?php
                $authorAvatarURL = get_user_meta($user_ID, "betube_author_avatar_url", true);
                if (!empty($authorAvatarURL)) {
                    ?>
                    <div class="profile-author-img">
                        <img src="<?php echo esc_url($authorAvatarURL); ?>" alt="<?php esc_html_e("Author", 'betube') ?>">
                    </div>
                    <?php
                } else {
                    $authorID = get_the_author_meta('user_email', $user_ID);
                    $avatar_url = betube_get_avatar_url($authorID, $size = '150');
                    ?>
                    <div class="profile-author-img">
                        <img src="<?php echo esc_url($avatar_url); ?>" alt="<?php esc_html_e("Author", 'betube') ?>">
                    </div><!--profile-author-img-->
                    <?php
                }
                ?>               
                <div class="">
                    <div class="profile-author-name float-left">
                        <h4><?php echo $betubeDisplayName = get_the_author_meta('display_name', $user_ID); ?></h4>
                        <?php $betubeRegDate = get_the_author_meta('user_registered', $user_ID); ?>
                        <?php $dateFormat = get_option('date_format'); ?>
                        <p><?php esc_html_e("Join Date", 'betube') ?> : <span><?php echo date($dateFormat, strtotime($betubeRegDate)); ?></span></p>
                        <div style="text-align: center;">
                            <?php
                            if (is_user_logged_in()) {
                                global $current_user;
                                $user_id = $current_user->ID;
                                $author_id = get_the_author_meta('ID', $user_ID);
                                if (isset($user_id)) {
                                    if ($user_id != $author_id) {
                                        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}author_followers WHERE follower_id = $user_id AND author_id = $author_id", OBJECT);?>
                                       
                                        <?php
                                        if (!empty($results)) {
                                            //$followLabel = 'unfollow';
                                       
                                        ?>
                                       <div class="authorfollower unfollowdiv">
                                            <form method="post">

                                                <input type="hidden" name="author_id" value="<?php echo $author_id; ?>"/>

                                                <input type="hidden" name="follower_id" value="<?php echo $user_id; ?>"/>
                                                <button class="unfollow" type="submit" name="unfollow" value="<?php echo ucfirst($followLabel); ?>" ><?php esc_html_e("Unfollow", 'betube') ?></button>
                                            </form>
                                        </div>
                                        <?php } ?>
                                            <?php 
                                             if (empty($results)) { 
                                            //$followLabel = 'unfollow';?>
                                            <div class="authorfollower">
                                                <button class="follow" type="button" class="btn btn-info btn-lg" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#followpopup"><?php esc_html_e("Follow", 'betube') ?></button>
                                                 <?php } ?>
                                            </div>
                                            <div class="authorfollower" id="unfollowbtn" style="display: none;">
                                                <form method="post">

                                                    <input type="hidden" name="author_id" value="<?php echo $author_id; ?>"/>

                                                    <input type="hidden" name="follower_id" value="<?php echo $user_id; ?>"/>

                                                    <button type="submit" class="unfollow" name="unfollow" value="Unfollow"><?php esc_html_e("Unfollow", 'betube') ?></button>

                                                </form>
                                            </div>
                                        <div class="clearfix"></div>
                                        <?php
                                    }
                                }
                            }
                            ?>
                        </div>
                    </div><!--profile-author-name-->
                    <div class="profile-author-stats float-right">
                        <ul class="menu">

                            <li>
                                <div class="icon float-left">
                                    <i class="flaticon-open-book"></i>
                                </div>
                                <div class="li-text float-left">
                                    <?php $userid = get_the_author_meta('ID', $user_ID); ?>
                                    <p class="number-text"><?php echo count_user_posts($userid); ?></p>
                                    <span><?php esc_html_e("Flipbooks   ", 'betube') ?></span>
                                </div>
                            </li><!--Total Videos-->
                            <li>
                                <div class="icon float-left">
                                    <i class="flaticon-like"></i>
                                </div>
                                <div class="li-text float-left">
                                    <p class="number-text">
                                        <?php
                                        echo $totalfavorite = betubeFavoriteCount($user_ID);
                                        ?>
                                    </p>
                                    <span><?php esc_html_e("Favorites", 'betube') ?></span>
                                </div>
                            </li><!--Total favorites-->                          
                            <li>
                                <div class="icon float-left">
                                    <i class="flaticon-comments"></i>
                                </div>
                                <div class="li-text float-left">
                                    <?php
                                    $args = array(
                                        'user_id' => $user_ID, // use user_id
                                        'count' => true, //return only the count
                                        'status' => 'approve'
                                    );
                                    $betubeUsercomments = get_comments($args);
                                    ?>
                                    <p class="number-text"><?php echo $betubeUsercomments; ?></p>
                                    <span><?php esc_html_e("Comments", 'betube') ?></span>
                                </div>
                            </li><!--Total comments-->
                        </ul>
                    </div><!--profile-author-stats-->
                </div><!--clearfix-->
            </div><!--row secBg-->
        </div><!--row secBg-->
    </div><!--profile-stats-->
</section><!--Section topProfile-->
<div class="row">   
    <!-- right side content area -->
    <div id="foo" class="large-12 columns profile-inner">
        <div class="tabs-content vertical" data-tabs-content="vert-tabs">
            <section class="singlePostDescription">
                <div class="row secBg">
                    <div class="large-12 columns">
                        <div class="heading">
                            <i class="flaticon-man-user"></i>
                            <h4><?php esc_html_e("About", 'betube') ?>&nbsp;<?php echo get_the_author_meta('display_name', $user_ID); ?></h4>
                        </div><!--Heading-->
                        <div class="description">
                            <p style="word-wrap: break-word;"><?php
                                $author_desc = get_the_author_meta('description', $user_ID);
                                echo $author_desc;
                                ?></p>
                            <?php
                            $emailShowHide = get_the_author_meta('emailshowhide', $user_ID);
                            $autherWebsite = get_the_author_meta('user_url', $user_ID);
                            $autherPhone = get_the_author_meta('phone', $user_ID);
                            ?>
                            <?php
                            if ($autherWebsite != '') {
                                ?>
                                <div class="site profile-margin">
                                    <button><i class="fa fa-globe"></i><?php esc_html_e("Website", 'betube') ?></button>
                                    <a href="<?php the_author_meta('user_url', $user_ID); ?>" class="inner-btn"><?php the_author_meta('user_url', $user_ID); ?></a>
                                </div><!--website-->
                            <?php }
                            ?>
                            <?php
                            if ($emailShowHide == 'yes') {
                                ?>
                                <div class="email profile-margin">
                                    <button><i class="fa fa-envelope"></i><?php esc_html_e("Email", 'betube') ?></button>
                                    <span class="inner-btn"><?php the_author_meta('user_email', $user_ID); ?></span>
                                </div><!--email-->
                            <?php }
                            ?>
                            <?php
                            if ($autherPhone != '') {
                                ?>
                                <div class="phone profile-margin">
                                    <button><i class="fa fa-phone"></i><?php esc_html_e("Phone", 'betube') ?></button>
                                    <span class="inner-btn"><?php the_author_meta('phone', $user_ID); ?></span>
                                </div><!--phone-->
                            <?php } ?>
                            <?php
                            $userFB = $user_info->facebook;
                            $userTW = $user_info->twitter;
                            $userGoogle = $user_info->googleplus;
                            $userPin = $user_info->pinterest;
                            $userLin = $user_info->linkedin;
                            $userVimeo = $user_info->vimeo;
                            $userYoutube = $user_info->youtube;
                            ?>
                            <?php
                            if ($userFB != '' || $userTW != '' || $userGoogle != '' || $userPin != '' || $userLin != '' || $userVimeo != '' || $userYoutube != '') {
                                ?>
                                <div class="socialLinks profile-margin">
                                    <button><i class="fa fa-share-alt"></i><?php esc_html_e("Social", 'betube') ?></button>

                                    <?php $userFB = $user_info->facebook; ?>
                                    <?php if ($userFB) { ?>
                                        <a href="<?php echo $userFB; ?>" class="inner-btn"><i class="fa fa-facebook"></i></a>
                                    <?php } ?>

                                    <?php $userTW = $user_info->twitter; ?>
                                    <?php if ($userTW) { ?>
                                        <a href="<?php echo $userTW; ?>" class="inner-btn"><i class="fa fa-twitter"></i></a>
                                    <?php } ?>

                                    <?php $userGoogle = $user_info->googleplus; ?>
                                    <?php if ($userGoogle) { ?>
                                        <a href="<?php echo $userGoogle; ?>" class="inner-btn"><i class="fa fa-google-plus"></i></a>
                                    <?php } ?>

                                    <?php $userPin = $user_info->pinterest; ?>
                                    <?php if ($userPin) { ?>	
                                        <a href="<?php echo $user_info->pinterest; ?>" class="inner-btn"><i class="fa fa-pinterest-p"></i></a>
                                    <?php } ?>

                                    <?php $userLin = $user_info->linkedin; ?>
                                    <?php if ($userLin) { ?>	
                                        <a href="<?php echo $user_info->linkedin; ?>" class="inner-btn"><i class="fa fa-linkedin"></i></a>
                                    <?php } ?>

                                </div><!--socialLinks-->
                            <?php } ?>
                        </div><!--description-->
                    </div><!--large-12 columns-->
                </div><!--row secBg-->
            </section><!--singlePostDescription-->
            <section class="profile-videos">


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
                        <div class="main-heading">
                            <div class="row secBg padding-14">
                                <div class="medium-8 small-8 columns">
                                    <div class="head-title">
                                        <i class="flaticon-agenda"></i>
                                        <h4><?php esc_html_e("Flipbooks", 'betube') ?></h4>
                                    </div>
                                </div> 
                            </div>
                        </div><!--mainheading-->
                        <?php
                        $userid = get_the_author_meta('ID', $user_ID);
                        $q = new WP_Query(array(
                            'nopaging' => true,
                            'author' => $userid,
                            'post_type' => 'post'
                        ));
                        $allPosts = $q->post_count;
//echo "$allPosts";
                        ?>
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
                                        $page = get_query_var('page');
                                        $cat_id = get_cat_ID(single_cat_title('', false));
                                        $temp = $wp_query;
                                        $wp_query = null;
                                        $wp_query = new WP_Query();

                                        $arags = array(
                                            'post_type' => 'post',
                                            'posts_per_page' => 6,
                                            'paged' => $page,
                                            'author' => $userid
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
                                                $likeCountArr = get_post_meta($post_id, '_post_like_count');
                                                $likeCount = 0;
                                                if (!empty($likeCountArr)) {
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


                                            <?php //echo $current."Shabir";    ?>
                                        </div><!--row list-group-->
                                        <?php
                                        $totalPosts = $allPosts;

                                        $postPerPage = 6;
//                                $postPerPage = get_option('posts_per_page');

                                        $totalPages = ceil($totalPosts / $postPerPage);
//                                $url = get_category_link($cat_id);
                                        $url = '?author=' . $_GET['author'];
                                        if (parse_url($url, PHP_URL_QUERY)) {
                                            $url .= '&page=';
                                        } else {
                                            $url .= '?page=';
                                        }
                                        if (isset($_GET['page'])) {
                                            $page = $_GET['page'];
                                        } else {
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
                                            <?php
                                        }
                                        ?><?php wp_reset_postdata(); ?>
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
                        </div>

                    </section>
                    <!-- main content -->
                    <?php
                }
                ?>


            </section>
        </div>
        <!--Panel2v End-->        			
        <!-- right side content area -->
    </div><!--tabs-content-->
</div><!--IF FOO-->
</div><!--End Row-->
<div class="modal fade followpopupform" id="followpopup" tabindex="-1" role="dialog">
    <div class="modal-dialog followingpopupform" role="document">
        <div class="modal-content deletecontent">
            <div class="followcontent">
                <h4><?php esc_html_e("Recieve email Updates from this auther?", 'betube') ?></h4>
            </div>
            <form class="form-item" action="" id="followerUpdateForm" method="POST" enctype="multipart/form-data"> 
                <input type="hidden" name="author_id" value="<?php echo $author_id; ?>"/>

                <input type="hidden" name="follower_id" value="<?php echo $user_id; ?>"/>
                
                <button class="followbtnupdate" id="followyes" type="submit" name="followyes" value="1" ><?php esc_html_e("Yes", 'betube') ?></button>
                <button class="followbtnupdate" id="followno" type="submit" name="followno" value="0" ><?php esc_html_e("No", 'betube') ?></button>
<!--                <input type="submit" name="submit" class="button expanded submit_content" value="Submit">-->
            </form>
        </div>
    </div>
</div>
<script>
    var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    jQuery(document).ready(function ($) {
        //var ajaxUrl = location.href;
        
        jQuery(".followbtnupdate").click(function(){
            var btnvalue = $(this).attr('value');
            var dataString = jQuery( "#followerUpdateForm" ).serialize();
                jQuery.post(ajaxurl, {
                    'action': 'save_follower_form',
                    'ID': btnvalue,
                    'data': dataString
                }, function (response) {
                    var obj = JSON.parse(response);
                    if (obj.status.length > 0 && obj.status == '1') {
                            jQuery('#followpopup').modal('hide');
                            jQuery('.follow').css('display','none');
                            jQuery('#unfollowbtn').css('display','block');
                        }
                    }
                );
        return false;
            });
            
        });
        </script>
<?php get_footer(); ?>
