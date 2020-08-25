<?php
/**
 * Template name: View Orders
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
                    
                    <!-- <div class="profile-share">
                            <div class="easy-share" data-easyshare data-easyshare-http data-easyshare-url="<?php echo esc_url(home_url('/')); ?>">
                                    <button data-easyshare-button="facebook">
                                            <span class="fa fa-facebook"></span>
                                            <span><?php esc_html_e("Share", 'betube') ?></span>
                                    </button>
                                    <span data-easyshare-button-count="facebook">0</span>
    
                                    <button data-easyshare-button="twitter" data-easyshare-tweet-text="">
                                            <span class="fa fa-twitter"></span>
                                            <span><?php esc_html_e("Tweet", 'betube') ?></span>
                                    </button>
                                    <span data-easyshare-button-count="twitter">0</span>
    
                                    <button data-easyshare-button="google">
                                            <span class="fa fa-google-plus"></span>
                                            <span>+1</span>
                                    </button>
                                    <span data-easyshare-button-count="google">0</span>
    
                                    <div data-easyshare-loader><?php esc_html_e("Loading", 'betube') ?>...</div>
                            </div>
                    </div> -->
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
                                        echo custom_get_user_posts_count($user_ID, array(
                                            'post_type' => 'post', 'post_status' => array(
                                                'draft', 'publish')));
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
            <!-- Ads Subscription  -->
            <section class="profile-settings">
                <div class="row secBg">
                    <div class="large-12 columns">
                        <div class="heading">
                            <i class="flaticon-shopping"></i>
                            <h4><?php esc_html_e("Pricing Plans View", 'betube') ?></h4>
                        </div><!--heading-->
                        <div class="row">
                            <div class="large-12 columns">
                                <section class="woocommerce-order-details">
                                    <h2 class="woocommerce-order-details__title"><?php _e('Order details', 'woocommerce'); ?></h2>
                                    <table class="woocommerce-table woocommerce-table--order-details shop_table order_details">
                                        <thead>
                                            <tr>
                                                <th class="woocommerce-table__product-name product-name"><?php _e('Product', 'woocommerce'); ?></th>
                                                <th class="woocommerce-table__product-table product-total"><?php _e('Total', 'woocommerce'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $order_id = $_GET['view-order'];
                                            $order = wc_get_order($order_id);
                                            foreach ($order->get_items() as $item_id => $item) {
                                                $order_id = $item['order_id'];
                                                $product_name = $item['name'];
                                                $product = apply_filters('woocommerce_order_item_product', $item->get_product(), $item);
                                                wc_get_template('order-details-item.php', array(
                                                    'order' => $order,
                                                    'item_id' => $item_id,
                                                    'item' => $item,
                                                    'show_purchase_note' => $show_purchase_note,
                                                    'purchase_note' => $product ? $product->get_purchase_note() : '',
                                                    'product' => $product,
                                                ));
                                            }
                                            do_action('woocommerce_order_items_table', $order);
                                            ?>
                                        </tbody>
                                        <tfoot>
<?php
foreach ($order->get_order_item_totals() as $key => $total) {
    ?>
                                                <tr>
                                                    <th scope="row"><?php echo $total['label']; ?></th>
                                                    <td><?php echo $total['value']; ?></td>
                                                </tr>
    <?php
}
?>
                                        </tfoot>
                                    </table>
                                </section>
                            </div><!--End large-12-->
                        </div><!--End Row-->
                    </div><!--large-12-->
                </div><!--End row secBg-->
            </section><!--End profile-settings-->
            <!-- Ads Subscription -->
        </div>
        <!-- right side content area -->
    </div><!--End Row-->
<?php get_footer(); ?>