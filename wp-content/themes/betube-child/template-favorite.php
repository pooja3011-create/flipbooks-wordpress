<?php
/**
 * Template name: Favorite Posts
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage betube
 * @since betube 1.0
 */
if (!is_user_logged_in()) {

    global $redux_demo;
    $login = $redux_demo['login'];
    wp_redirect($login);
    exit;
}
get_header();
betube_breadcrumbs();
if (isset($_POST['unfavorite'])) {
    $author_id = $_POST['author_id'];
    $post_id = $_POST['post_id'];
    echo betube_authors_unfavorite($author_id, $post_id);
}
global $current_user, $user_id;
global $redux_demo;
wp_get_current_user();
$user_info = get_userdata($user_ID);
$user_id = $current_user->ID; // You can set $user_id to any users, but this gets the current users ID.
$beTubeEdit = $redux_demo['edit'];
$pagepermalink = get_permalink($post->ID);
$beTubeprofile = $redux_demo['profile'];
$beTubeallVideos = $redux_demo['all-ads'];
$beTubeallFavourite = $redux_demo['all-favourite'];
$beTubenewPost = $redux_demo['new_post'];
?>
<?php
$page = get_page($post->ID);
$current_page_id = $page->ID;
$betubeProfileIMG = get_user_meta($user_ID, "betube_author_profile_bg", true);
$profileCoverPhoto = get_option('profile_cover_photo');
?>
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
                                        //echo count_user_posts($user_ID);
                                        echo custom_get_user_posts_count($user_ID, array('post_type' => 'post', 'post_status' => array('draft', 'publish')));
                                        ?>
                                    </p>
                                    <span><?php esc_html_e("Flipbooks", 'betube') ?></span>
                                </div>
                            </li><!--Total Videos-->
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
                            <!-- <li>
                                    <div class="icon float-left">
                                            <i class="fa fa-users"></i>
                                    </div>
                                    <div class="li-text float-left">
                                            <p class="number-text"><?php echo betubeFollowerCount($user_id); ?></p>
                                            <span><?php esc_html_e("Followers", 'betube') ?></span>
                                    </div>
                            </li> --><!--Total followers-->
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
        <!-- left sidebar -->
        <div class="large-4 columns leftsidebar">
            <?php include_once 'profile-left-sidebar.php'; ?>
        </div>
        <!-- left sidebar -->
        <!-- right side content area -->
        <div class="large-8 columns profile-inner">
            <section class="profile-videos">
                <div class="row secBg">
                    <div class="large-12 columns">
                        <div class="heading">
                            <i class="flaticon-like"></i>
                            <h4><?php esc_html_e("My Favorite Flipbooks", 'betube') ?></h4>
                        </div><!--heading-->
                        <?php
                        global $paged, $wp_query, $wp;
                        $args = wp_parse_args($wp->matched_query);
                        if (!empty($args['paged']) && 0 == $paged) {
                            $wp_query->set('paged', $args['paged']);
                            $paged = $args['paged'];
                        }
                        $cat_id = get_cat_ID(single_cat_title('', false));
                        $temp = $wp_query;
                        $wp_query = null;
                        $wp_query = new WP_Query();
                        global $current_user;
                        wp_get_current_user();
                        $user_id = $current_user->ID;
                        $myarray = betube_authors_all_favorite($user_id);
                        if (!empty($myarray)) {
                            $args = array(
                                'post_type' => 'post',
                                'post__in' => $myarray,
                                'posts_per_page' => get_option('posts_per_page'),
                                'paged' => $paged,
                            );
                            // The Query
                            $wp_query = new WP_Query($args);
                            $current = -1;
                            $current2 = 0;
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
                                                    <div class="video-btns">
                                                        <?php echo betube_authors_favorite_remove($user_id, $post->ID); ?>
                                                    </div>
                                                </div><!--video-detail-->
                                            </div><!--media-object-section-->
                                        </div><!--media-object-->
                                    </div><!--profile-video-->
                                    <?php
                                endwhile;
                            } else {
                                echo 'No favorite flipbook found.';
                            }
                            ?>

                            <!--Show More-->
                            <?php
                            $totalPosts = count($myarray);

                            $postPerPage = get_option('posts_per_page');

                            $totalPages = ceil($totalPosts / $postPerPage);
                            $url = get_page_link(868);
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
                                <?php
                            }
                            ?>

                            <?php wp_reset_postdata(); ?>
                        <?php } else { ?>
                            <p><?php esc_html_e("No favorites available.", 'betube') ?></p>
                        <?php } ?>
                        <!--Show More-->
                    </div><!--large-12-->
                </div><!--End Row-->
            </section><!--End profile-videos-->
        </div>
        <!-- right side content area -->
    </div><!--End Row-->
    <?php get_footer(); ?>