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
if (!is_user_logged_in()) {

    global $redux_demo;
    $login = $redux_demo['login'];
    wp_redirect($login);
    exit;
}


global $redux_demo;
$edit = $redux_demo['edit_post'];
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
$allFavourite = $redux_demo['all-favourite'];
$newPostAds = $redux_demo['new_post'];
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
                            <img src="<?php echo esc_url($authorAvatarURL); ?>" alt="author">						
                        </div>
                        <?php
                    } else {
                        $authorID = get_the_author_meta('user_email', $user_ID);
                        $avatar_url = betube_get_avatar_url($authorID, $size = '150');
                        ?>
                        <div class="profile-author-img">
                            <img src="<?php echo esc_url($avatar_url); ?>" alt="profile author img">
                        </div><!--profile-author-img-->
    <?php
}
?>
                    
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
                                    <p class="number-text"><?php echo custom_get_user_posts_count($user_ID, array('post_type' => 'post', 'post_status' => array('draft', 'publish'))); ?></p>
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
                </div><!--row secBg-->
            </div><!--row secBg-->
        </div><!--profile-stats-->
    </section><!--Section topProfile-->
    <div class="clearfix"></div>
    <div class="row">
        <!-- left sidebar -->
        <div class="large-4 columns leftsidebar">
<?php include_once 'profile-left-sidebar.php'; ?>
        </div><!--Large4-->
        <!-- left sidebar -->
        <!-- right side content area -->
        <div class="large-8 columns profile-inner">
            <!-- single post description -->
            <section class="singlePostDescription">
                <div class="row secBg">
                    <div class="large-12 columns">
                        <div class="heading">
                            <i class="flaticon-man-user"></i>
                            <h4><?php esc_html_e("About", 'betube') ?>&nbsp;<?php echo $user_identity; ?></h4>
                        </div>
                        <div class="description">
                            <p style="word-wrap: break-word;"><?php $user_id = $current_user->ID;
$author_desc = get_the_author_meta('description', $user_id);
echo $author_desc; ?></p>

                            <?php
                            $autherWebsite = get_the_author_meta('user_url', $user_id);
                            $autherPhone = get_the_author_meta('phone', $user_id);
                            ?>


                            <?php
                            if ($autherWebsite != '') {
                                ?>
                                <div class="site profile-margin">
                                    <button><i class="fa fa-globe"></i><?php esc_html_e("Website", 'betube') ?></button>
                                    <a href="<?php the_author_meta('user_url', $user_id); ?>" class="inner-btn"><?php the_author_meta('user_url', $user_id); ?></a>
                                </div>
                            <?php }
                            ?>

                            <div class="email profile-margin">
                                <button><i class="fa fa-envelope"></i><?php esc_html_e("Email", 'betube') ?></button>
                                <span class="inner-btn"><?php the_author_meta('user_email', $user_id); ?></span>
                            </div><!--email-->
                            <?php
                            if ($autherPhone != '') {
                                ?>
                                <div class="phone profile-margin">
                                    <button><i class="fa fa-phone"></i><?php esc_html_e("Phone", 'betube') ?></button>
                                    <span class="inner-btn"><?php the_author_meta('phone', $user_id); ?></span>
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
                                        <?php $userVimeo = $user_info->vimeo; ?>
                                <?php if ($userVimeo) { ?>	
                                        <a href="<?php echo $user_info->vimeo; ?>" class="inner-btn"><i class="fa fa-vimeo"></i></a>
    <?php } ?>
    <?php $userYoutube = $user_info->youtube; ?>
    <?php if ($userYoutube) { ?>	
                                        <a href="<?php echo $user_info->youtube; ?>" class="inner-btn"><i class="fa fa-youtube"></i></a>
    <?php } ?>
                                </div><!--socialLinks-->
    <?php } ?>
                        </div><!--Description-->
                    </div><!--large12-->
                </div><!--row-->
            </section><!-- End single post description -->
        </div><!-- end left side content area -->
        <!-- right side content area -->
    </div><!--End Row-->
<?php get_footer(); ?>
