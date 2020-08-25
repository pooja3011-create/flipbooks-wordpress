<?php
/**
 * Template name: Orders
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
                    <h3><?php echo $betubeFirstTXT = the_author_meta('firsttext', $user_id); ?></h3>
                    <h1><?php echo $betubeSecondTXT = the_author_meta('secondtext', $user_id); ?></h1>
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
                            <h4><?php esc_html_e("Pricing Plans", 'betube') ?></h4>
                        </div><!--heading-->
                        <div class="row">
                            <div class="large-12 columns">
                                <?php
                                $subscriberList = array(
                                    'numberposts' => -1,
                                    'meta_key' => '_customer_user',
                                    'meta_value' => get_current_user_id(),
                                    'post_type' => 'shop_order',
                                    'post_status' => array_keys(wc_get_order_statuses()),
                                );
                                $subscriberListloop = new WP_Query($subscriberList);
                                if ($subscriberListloop->have_posts()) {
                                    ?>
                                    <table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
                                        <thead>
                                            <tr>
                                                <?php foreach (wc_get_account_orders_columns() as $column_id => $column_name) : ?>
                                                    <th class="woocommerce-orders-table__header woocommerce-orders-table__header-<?php echo esc_attr($column_id); ?>"><span class="nobr"><?php echo esc_html($column_name); ?></span></th>
    <?php endforeach; ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $subscriberListloop = new WP_Query($subscriberList);
                                            if ($subscriberListloop->have_posts()) {
                                                while ($subscriberListloop->have_posts()) {
                                                    $subscriberListloop->the_post();
                                                    $order = new WC_Order($subscriberListloop->post->ID);
                                                    ?>
                                                    <tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-<?php echo esc_attr($order->get_status()); ?> order">
                                                            <?php foreach (wc_get_account_orders_columns() as $column_id => $column_name) : ?>
                                                            <td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-<?php echo esc_attr($column_id); ?>" data-title="<?php echo esc_attr($column_name); ?>">
                                                                <?php if (has_action('woocommerce_my_account_my_orders_column_' . $column_id)) : ?>
                                                                    <?php do_action('woocommerce_my_account_my_orders_column_' . $column_id, $order); ?>

                                                                    <?php elseif ('order-number' === $column_id) : ?>
                                                                    <span>
                    <?php echo _x('#', 'hash before order number', 'woocommerce') . $order->get_order_number(); ?>
                                                                    </span>

                <?php elseif ('order-date' === $column_id) : ?>
                                                                    <time datetime="<?php echo esc_attr($order->get_date_created()->date('c')); ?>"><?php echo esc_html(wc_format_datetime($order->get_date_created())); ?></time>

                                                                <?php elseif ('order-status' === $column_id) : ?>
                                                                    <?php echo esc_html(wc_get_order_status_name($order->get_status())); ?>

                                                                <?php elseif ('order-total' === $column_id) : ?>
                                                                    <?php
                                                                    /* translators: 1: formatted order total 2: total order items */
                                                                    printf(_n('%1$s for %2$s item', '%1$s for %2$s items', $item_count, 'woocommerce'), $order->get_formatted_order_total(), $item_count);
                                                                    ?>

                                                                <?php elseif ('order-actions' === $column_id) : ?>
                                                                    <?php
                                                                    $actions = array(
                                                                        'view' => array(
                                                                            'url' => $order->get_view_order_url(),
                                                                            'name' => __('View', 'woocommerce'),
                                                                        ),
                                                                    );
                                                                    if ($actions = apply_filters('woocommerce_my_account_my_orders_actions', $actions, $order)) {
                                                                        foreach ($actions as $key => $action) {
                                                                            $pageLink = get_page_link(939);
                                                                            if (parse_url($pageLink, PHP_URL_QUERY)) {
                                                                                $url = $pageLink . '&view-order=' . $order->get_order_number();
                                                                            } else {
                                                                                $url = $pageLink . '?view-order=' . $order->get_order_number();
                                                                            }
                                                                            echo '<a href="' . $url . '" class="woocommerce-button button ' . sanitize_html_class($key) . '">' . esc_html($action['name']) . '</a>';
                                                                        }
                                                                    }
                                                                    ?>
                                                                <?php endif; ?>
                                                            </td>
                                                        <?php endforeach; ?>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                    <?php
                                } else {
                                    echo '<p>No Subscription Found. </p>
                                            <a href="' . get_page_link(904) . '" class="buynowbtn">Buy Now</a>';
                                }
                                ?>
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