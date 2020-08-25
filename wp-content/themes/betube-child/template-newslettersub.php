<?php
/**
 * Template name: Newsletter Subscription
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

if (isset($_POST["sub_email"])) {
    global $wpdb;
    $sub_email = trim($_POST['sub_email']);
    $sub_name = trim($_POST['sub_name']);
    $sub_id = trim($_POST['sub_id']);
    $qry = "DELETE from {$wpdb->prefix}newsletter_subscribers_category where sub_id='" . $sub_id . "'";
    $wpdb->query($qry);

    if (isset($_POST["chk_groups"])) {
        if ($sub_id == "") {
            $sub_insert = ("INSERT into {$wpdb->prefix}newsletter_subscribers (sub_email,sub_name)value('" . $sub_email . "','" . $sub_name . "')");
            $wpdb->query($sub_insert);
            $sub_id = $wpdb->insert_id;
        }

        foreach ($_POST["chk_groups"] as $k => $v) {
            $sub_insert = ("INSERT into {$wpdb->prefix}newsletter_subscribers_category (sub_id,cat_id)value('" . $sub_id . "','" . $v . "')");
            $wpdb->query($sub_insert);
        }
    }
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
<style>
    input[type="checkbox"] {display:inline; margin:0px;}
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
                            <i class="flaticon-newsletter"></i>
                            <h4><?php esc_html_e("Newsletter Subscription", 'betube') ?></h4>
                        </div>

                        <div class="description">
                            <?php
                            $email = $user_info->data->user_email;
                            $nicename = $user_info->data->user_nicename;
                            $sel_cat = array();
                            ?>
                            <p><b>Select Newsletter Category</b></p>
                            <div>
                                <form method="POST">
                                    <input type="hidden" name="sub_email" value="<?php echo $email; ?>" />
                                    <input type="hidden" name="sub_name" value="<?php echo $nicename; ?>" />
                                    <?php
                                    global $wpdb;
                                    $hdn_sub_id = "";

                                    $results = $wpdb->get_results("SELECT nsc.sub_id,nsc.cat_id from {$wpdb->prefix}newsletter_subscribers ns, {$wpdb->prefix}newsletter_subscribers_category nsc where ns.sub_email='" . $email . "' AND ns.id=nsc.sub_id", OBJECT);

                                    if (!empty($results)) {
                                        foreach ($results as $res) {
                                            $sel_cat[] = $res->cat_id;
                                            $hdn_sub_id = $res->sub_id;
                                        }
                                    }

                                    $categories = get_categories(array(
                                        'orderby' => 'name',
                                        'parent' => 0,
                                        'hide_empty' => 0,
                                    ));
                                    ?> <div class="large-12 columns">
                                    <?php
                                    foreach ($categories as $category) {
                                        $sel_text = "";
                                        $t = $category->term_id;
                                        if (in_array($t, $sel_cat)) {
                                            $sel_text = "checked='checked'";
                                        }
                                        ?>
                                            <div class="large-4 columns"><input type="checkbox" name="chk_groups[<?php echo $t; ?>]" id="chk_<?php echo $t; ?>" value="<?php echo $t; ?>" <?php echo $sel_text; ?>/>&nbsp;<?php echo $category->name; ?></div>

                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="large-12" style="margin-top: 20px; margin-bottom: 20px;">
                                        <input type="hidden" name="sub_id" value="<?php echo $hdn_sub_id; ?>" />
                                        <input class="button expanded" type="submit" value="Save" />
                                    </div>

                                </form>
                            </div>

                        </div><!--Description-->
                    </div><!--large12-->
                </div><!--row-->
            </section><!-- End single post description -->
        </div><!-- end left side content area -->
        <!-- right side content area -->
    </div><!--End Row-->
    <?php get_footer(); ?>
