<?php
/**
 * Template name: Single User All Video
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage betube
 * @since betube
 */
if (!is_user_logged_in()) {

    global $redux_demo;
    $login = $redux_demo['login'];
    wp_redirect($login);
    exit;
}


global $redux_demo;
$edit = $redux_demo['edit'];
$pagepermalink = get_permalink($post->ID);
if (isset($_GET['delete_id'])) {
    $deleteUrl = $_GET['delete_id'];
    wp_delete_post($deleteUrl);
}
global $current_user, $user_id;
wp_get_current_user();
$user_info = get_userdata($user_ID);
$user_id = $current_user->ID; // You can set $user_id to any users, but this gets the current users ID.

get_header();
betube_breadcrumbs();
?>
<?php
global $redux_demo;
$profile = $redux_demo['profile'];
$all_adds = $redux_demo['all-ads'];
$allFavourite = $redux_demo['all-favourite'];
$newPostAds = $redux_demo['new_post'];
?>
<?php
$page = get_page($post->ID);
$current_page_id = $page->ID;
$betubeProfileIMG = get_user_meta($user_ID, "betube_author_profile_bg", true);
$profileCoverPhoto = get_option('profile_cover_photo');

if (isset($_GET["del_fb"])) {
    $del_post_id = trim($_GET["del_fb"]);
    $p = get_post($del_post_id);
    $fb_id = $p->flipbook_id;

    delete_option("real3dflipbook_" . $fb_id);
    wp_delete_post($del_post_id);

    echo "<script>location.href='" . get_site_url() . "/flipbooks/';</script>";
}
?>

<style>
    .screen-reader-text {display:none;}
</style>
<?php if ($betubeProfileIMG == '') {
    ?>
    <section class="topProfile" style="background: url('<?php echo $profileCoverPhoto; ?>') no-repeat;">
    <?php } else {
        ?>
        <section class="topProfile" style="background: url('<?php echo $betubeProfileIMG; ?>') no-repeat;">
        <?php } ?>
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
            </div><!--Row upload cover-->	
        </div>
        <div class="profile-stats">
            <div class="row secBg">
                <div class="large-12 columns">
                    <div class="profile-author-img">
                        <?php
                        $author_avatar_url = get_user_meta($user_ID, "betube_author_avatar_url", true);
                        if (!empty($author_avatar_url)) {
                            ?>
                            <img src="<?php echo esc_url($author_avatar_url); ?>" alt="author">
                            <?php
                        } else {
                            $avatar_url = betube_get_avatar_url(get_the_author_meta('user_email', $user_ID), $size = '130');
                            ?>
                            <img class="author-avatar" src="<?php echo $avatar_url; ?>" alt="" />
                            <?php
                        }
                        ?>					
                    </div><!--profile-author-img-->
                   

                    <div class="profile-author-name float-left">
                        <h4><?php echo $betubeDisplayName = get_the_author_meta('display_name', $user_ID); ?></h4>
                        <?php $betubeRegDate = get_the_author_meta('user_registered', $user_ID); ?>
                        <?php $dateFormat = get_option('date_format'); ?>
                        <p><?php esc_html_e("Join Date", 'betube') ?> : <span><?php echo date($dateFormat, strtotime($betubeRegDate)); ?></span></p>
                    </div><!--profile-author-name-->
                    <div class="profile-author-stats float-right">
                        <ul class="menu">
                            <li>
                                <div class="icon float-left">
                                    <i class="flaticon-open-book"></i>
                                </div>
                                <div class="li-text float-left">
                                    <p class="number-text">
                                        <?php
                                        //echo count_user_posts($user_ID, 'post');
                                        echo custom_get_user_posts_count($user_ID, array('post_type' => 'post', 'post_status' => array('draft', 'publish')));
                                        ?>
                                    </p>
                                    <span><?php esc_html_e("Flipbooks", 'betube') ?></span>
                                </div>
                            </li><!--Total Flipbooks-->
                            <li>
                                <div class="icon float-left">
                                    <i class="flaticon-like"></i>
                                </div>
                                <div class="li-text float-left">
                                    <p class="number-text">
                                        <?php
                                        global $current_user;
                                        wp_get_current_user();
                                        $user_id = $current_user->ID;
                                        echo $totalfavorite = betubeFavoriteCount($user_id);
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
                                        'user_id' => get_current_user_id(), // use user_id
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
                </div><!--large-12-->
            </div><!--row secBg-->
        </div><!--end profile-stats-->
    </section><!--end Section topProfile-->
    <div class="clearfix"></div>
    <div class="row">
        <!--LeftSidebar-->
        <div class="large-4 columns leftsidebar">
            <?php include_once 'profile-left-sidebar.php'; ?>
        </div>
        <!--LeftSidebar-->
        <div class="large-8 columns profile-inner">
            <section class="profile-videos">
                <div class="row secBg">
                    <div class="large-12 columns">
                        <div class="heading">
                            <i class="flaticon-open-book"></i>
                            <h4><?php esc_html_e("My Flipbooks", 'betube') ?></h4>
                        </div><!--Heading-->
                        <?php
                        global $paged, $wp_query, $wp;
                        $args = wp_parse_args($wp->matched_query);
                        if (!empty($args['paged']) && 0 == $paged) {
                            $wp_query->set('paged', $args['paged']);
                            $paged = $args['paged'];
                        }
//$wp_query = new WP_Query();
                        $temp = $wp_query;
                        $wp_query = null;
                        $kulPost = array(
                            'post_type' => 'post',
                            'author' => $user_id,
                            'posts_per_page' => get_option('posts_per_page'),
                            'paged' => $paged,
                            'post_status' => array('draft', 'publish')
                        );
                        $wp_query = new WP_Query($kulPost);
                        $current = -1;
                        $current2 = 0;
                        $count = 0;
                        ?>
                        <?php
                        if ($wp_query->have_posts()) {
                            while ($wp_query->have_posts()) : $wp_query->the_post();
                                $current++;
                                $current2++;
                                ?>                    
                                <div class="profile-video">
                                    <div class="media-object stack-for-small">
                                        <div class="media-object-section media-img-content">
                                            <div class="video-img">
                                                <?php if (has_post_thumbnail()) { ?>
                                                    <?php echo get_the_post_thumbnail(); ?>
                                                    <?php
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
                                                    if (empty($mediaArr['key'])) {
                                                        //  save_pdf_thumb_as_featuredimage($post_id);
                                                        $thumbURL = betube_thumb_url($post_id);
                                                        ?> <img src="<?php echo $thumbURL; ?>" alt="No Thumb"/> <?php
                                                    } else {
                                                        ?> <img src="<?php echo $thumbURL; ?>" alt=""/> <?php
                                                    }
                                                    ?>	
                                                <?php } ?>
                                            </div>
                                        </div><!--media-object-section-->
                                        <div class="media-object-section media-video-content">
                                            <div class="video-content">
                                                <h5>
                                                    <?php
                                                    if (get_post_status($post->ID) == 'publish') {
                                                        ?>
                                                        <a href="<?php the_permalink(); ?>"><?php echo get_the_title(); ?></a>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <?php echo get_the_title(); ?>
                                                        <?php
                                                    }
                                                    ?>
                                                </h5>
                                                <p><?php echo substr(get_the_excerpt(), 0, 260); ?></p>
                                            </div><!--video-content-->
                                            <div class="video-detail clearfix">
                                                <div class="video-stats">
                                                    <span><i class="fa fa-check-square-o"></i><?php
                                                        if (get_post_status($post->ID) == 'publish') {
                                                            echo 'published';
                                                        } else {
                                                            echo get_post_status($post->ID);
                                                        }
                                                        ?></span>
                                                    <?php $dateFormat = get_option('date_format'); ?>
                                                    <span><i class="fa fa-clock-o"></i><?php echo get_the_date($dateFormat, $post_id); ?></span>
                                                    <span><i class="fa fa-eye"></i><?php echo betube_get_post_views($post->ID); ?></span>
                                                </div>
                                                <div class="video-btns deletebtn">
                                                    <?php
                                                    global $redux_demo;
                                                    $edit_post_page_id = $redux_demo['edit_post'];
                                                    $postID = $post->ID;
                                                    global $wp_rewrite;
                                                    if ($wp_rewrite->permalink_structure == '') {
                                                        //we are using ?page_id
                                                        $edit_post = $edit_post_page_id . "&post=" . $post->ID;
                                                        $del_post = $pagepermalink . "&delete_id=" . $post->ID;
                                                    } else {
                                                        //we are using permalinks
                                                        $edit_post = $edit_post_page_id . "?post=" . $post->ID;
                                                        $del_post = $pagepermalink . "?delete_id=" . $post->ID;
                                                    }
                                                    $edit_post = get_site_url() . "/?page_id=29&edit=" . $post->ID;
                                                    if (get_post_status($post->ID) !== 'private') {
                                                        ?>
                                                        <a class="video-btn" href="<?php echo $edit_post; ?>"><i class="fa fa-pencil-square-o"></i><?php esc_html_e("Edit", 'betube') ?></a>
                                                    <?php } ?>
                                                        
                                                    <button class="deletePopup thickbox video-btn" type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#delPopup"><i class="fa fa-trash"></i><?php esc_html_e("Delete", 'betube') ?>
                                                    </button>
                                                    <div class="modal fade deletePopupform" id="delPopup" tabindex="-1" role="dialog">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                            <div class="deleteheader">
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>
                                                                <div class="deletecontent">
                                                                    <h4><?php esc_html_e("Are you sure you want to delete this?", 'betube') ?></h4>
                                                                    <a class="button-ag large green" href="<?php echo get_site_url(); ?>/flipbooks?del_fb=<?php echo $post->ID; ?>">
                                                                        <span class="button-inner"><?php esc_html_e("Confirm", 'betube') ?></span>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                     </div>
                                                </div>
                                            </div><!--video-detail-->
                                        </div><!--media-object-section-->
                                    </div><!--media-object-->
                                </div><!--profile-video-->
                                <?php
                            endwhile;
                        } else {
                            echo 'No flipbook found.';
                        }
                        ?>
                        <?php
//                    global $redux_demo;
//                    $beTubeScroll = $redux_demo['infinite-scroll'];
//                    if ($beTubeScroll == 1) {
//                        echo infinite($wp_query);
//                    } else {
//                        get_template_part('pagination');
//                    }
//wp_reset_query();
//get_template_part('pagination');

                        $totalPosts = count_user_posts($user_ID, 'post');

                        $postPerPage = get_option('posts_per_page');

                        $totalPages = ceil($totalPosts / $postPerPage);
                        $url = get_page_link(33);
                        if (parse_url($url, PHP_URL_QUERY)) {
                            $url .= '&paged=';
                        } else {
                            $url .= '?paged=';
                        }
                        $page = get_query_var('paged');
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
                        ?>

                        <?php wp_reset_postdata(); ?>
                    </div><!--large-12-->
                </div><!--row-->
            </section><!--profile-videos-->
        </div><!--Large8-->
    </div><!--row-->
    <?php get_footer(); ?>
