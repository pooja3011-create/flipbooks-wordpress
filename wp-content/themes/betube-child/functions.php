<?php
ini_set("display_error", 1);

function theme_enqueue_styles() {

    if (is_rtl()) {
        wp_enqueue_style('child-rtl', get_stylesheet_directory_uri() . '/rtl.css');
    }
    $parent_style = 'parent-style';
    wp_enqueue_style($parent_style, get_template_directory_uri() . '/style.css');
    wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', array(
        $parent_style)
    );
    wp_enqueue_style('custom', get_stylesheet_directory_uri() . '/custom.css');

    wp_enqueue_style('bootstrap-style', get_stylesheet_directory_uri() . '/css/bootstrap.min.css');
    wp_enqueue_script('bootstrap', get_stylesheet_directory_uri() . '/js/bootstrap.min.js', 'jquery', '', true);
    // wp_enqueue_script('login js', get_stylesheet_directory_uri() . '/js/ajax-login-script.js', 'jquery', '', true);

    wp_enqueue_script('custom', get_stylesheet_directory_uri() . '/custom.js', 'jquery', '', true);
}

add_action('wp_enqueue_scripts', 'theme_enqueue_styles');

function cookie_widgets_init() {

    register_sidebar(array(
        'name' => 'Cookie Popup Message',
        'id' => 'cookie-header',
        'before_widget' => '<div>',
        'after_widget' => '</div>',
        'before_title' => '<h2',
        'after_title' => '</h2>',
    ));
}

add_action('widgets_init', 'cookie_widgets_init');

// Our custom post type function
function create_posttype() {

    register_post_type('flipbook',
            // CPT Options
            array(
        'labels' => array(
            'name' => __('Flipbook'),
            'singular_name' => __('Flipbook'),
            'add_new' => __('Add New'),
            'add_new_item' => __('Add New Flipbook'),
            'edit' => __('Edit'),
            'edit_item' => __('Edit Flipbook'),
            'new_item' => __('New Flipbook'),
            'view' => __('View Flipbook'),
            'view_item' => __('View Flipbook'),
            'search_items' => __('Search Flipbook'),
            'not_found' => __('No flipbook found'),
            'not_found_in_trash' => __('No flipbook found in Trash'),
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
        'rewrite' => array('slug' => 'flipbook'),
            )
    );
}

// Hooking up our function to theme setup
//add_action('init', 'create_posttype');

function my_handle_attachment($file_handler, $post_id, $set_thu = false) {
    // check to make sure its a successful upload
    if ($_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK)
        __return_false();

    require_once(ABSPATH . "wp-admin" . '/includes/image.php');
    require_once(ABSPATH . "wp-admin" . '/includes/file.php');
    require_once(ABSPATH . "wp-admin" . '/includes/media.php');

    $attach_id = media_handle_upload($file_handler, $post_id);
    if (is_numeric($attach_id)) {
        update_post_meta($post_id, '_my_file_upload', $attach_id);
    }
    return $attach_id;
}

function hide_admin_bar_from_front_end() {
    if (is_blog_admin()) {
        return true;
    }
    return false;
}

add_filter('show_admin_bar', 'hide_admin_bar_from_front_end');

function custom_get_user_posts_count($user_id, $args) {
    $args['author'] = $user_id;
    $args['fields'] = 'ids';
    $args['posts_per_page'] = -1;
    $ps = get_posts($args);
    return count($ps);
}

//wp_clear_scheduled_hook('publish_flipbooks');
if (!wp_next_scheduled('publish_flipbooks')) {
    wp_schedule_event(time(), 'daily', 'publish_flipbooks');
}

function publish_flipbooks() {
    $args = array(
        'post_type' => 'post',
        'post_status' => 'draft',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => 'flipbook_publish_date',
                'value' => date("d/m/Y")
            )
        )
    );
    $getPosts = new WP_Query($args);

    if ($getPosts->have_posts()) : while ($getPosts->have_posts()) : $getPosts->the_post();
            global $post;

            wp_publish_post($post->ID);
        endwhile;
    endif;

    wp_reset_postdata();
}

add_action("wp_ajax_subscribe_newsletter", "subscribe_newsletter");
add_action("wp_ajax_nopriv_subscribe_newsletter", "subscribe_newsletter");

function subscribe_newsletter() {
    global $wpdb;

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $resp = array("status" => "1", "message" => "Newsletter subscription created successfully.");

    $results = $wpdb->get_results("SELECT * from {$wpdb->prefix}newsletter_subscribers where sub_email='" . $email . "'", OBJECT);

    if (empty($results)) {
        $sub_insert = ("INSERT into {$wpdb->prefix}newsletter_subscribers (sub_email,sub_name)value('" . $email . "','" . $name . "')");
        $wpdb->query($sub_insert);
        $lastid = $wpdb->insert_id;

        $categories = get_categories(array(
            'orderby' => 'name',
            'parent' => 0,
            'hide_empty' => 0,
        ));

        foreach ($categories as $category) {
            $sub_insert = ("INSERT into {$wpdb->prefix}newsletter_subscribers_category (sub_id,cat_id)value('" . $lastid . "','" . $category->term_id . "')");
            $wpdb->query($sub_insert);
        }
    } else {
        $resp["status"] = "0";
        $resp["message"] = "Email address already subscribed.";
    }

    echo json_encode($resp);
    exit;
}

function get_newsletter_count($email) {
    global $wpdb;
    $cnt = 0;

    $results = $wpdb->get_results("SELECT nsc.sub_id,nsc.cat_id from {$wpdb->prefix}newsletter_subscribers ns, {$wpdb->prefix}newsletter_subscribers_category nsc where ns.sub_email='" . $email . "' AND ns.id=nsc.sub_id", OBJECT);

    if (!empty($results)) {
        $cnt = count($results);
    }
    echo $cnt;
}

function chceckadscapability($auther_id = "") {
    if (!is_plugin_active('woocommerce/woocommerce.php')) {
        return true;
    }

    $adFreeUserCheckId = $auther_id;
    if ($adFreeUserCheckId == "") {
        $adFreeUserCheckId = get_current_user_id();
    }

    if ($adFreeUserCheckId != "") {
        $adfreeuserstatus = get_user_meta($adFreeUserCheckId, 'user_ads_show', true);
        if ($adfreeuserstatus == 1) {
            return false;
        }
    }

    if ($auther_id == "") {
        $auther_id = get_current_user_id();

        if ($auther_id > 0) {
            $subscriberList = array(
                'numberposts' => -1,
                'meta_key' => '_customer_user',
                'meta_value' => $auther_id,
                'post_type' => 'shop_order',
                'post_status' => array_keys(wc_get_order_statuses()),
            );
        } else {
            return true;
        }
    } else {
        $userArr = array($auther_id);

        if (get_current_user_id() > 0) {
            $userArr[] = get_current_user_id();
        }
        $subscriberList = array(
            'numberposts' => -1,
            'post_type' => 'shop_order',
            'post_status' => array_keys(wc_get_order_statuses()),
            'meta_query' => array(
                array(
                    'key' => '_customer_user',
                    'value' => $userArr,
                    'compare' => 'IN',
                ),
            ),
        );
    }

    $subscriberListloop = new WP_Query($subscriberList);
    //echo "******".$subscriberListloop->post_count;
    //echo "<pre>";print_r($subscriberListloop);exit;
    if ($subscriberListloop->post_count > 0) {
        while ($subscriberListloop->have_posts()) {
            // $subscriberListloop->the_post();
            $flag = true;
            $order = new WC_Order($subscriberListloop->post->ID);
            $order_date = date('Y-m-d', strtotime($order->order_date));
            $order_last_date = "";

            foreach ($order->get_items() as $key => $lineItem) {

                $subscribermonth = get_field('subscriber_package_month', $lineItem['product_id']);
                $order_last_date = date('Y-m-d', strtotime("+$subscribermonth months", strtotime($order_date)));
            }

            $currentDate = strtotime(date("Y-m-d"));
            $orderlastDate = strtotime($order_last_date);

            if ($currentDate > $orderlastDate) {
                //echo "expired";
            } else {
                $flag = false;
                break;
            }
        }
        return $flag;
    } else {
        return true;
    }
}

function adserve($type, $auther_id = "") {
    if (chceckadscapability($auther_id) == '1') {
        if ($type == 1) {
            echo '<img src="' . get_stylesheet_directory_uri() . '/assets/images/ads1.png" />';
        } else if ($type == 2) {
            echo '<img src="' . get_stylesheet_directory_uri() . '/assets/images/ads3.png" />';
        }
    }
}

// WooCommerce remove the product title link on checkout page
add_action('woocommerce_before_shop_loop_item_title', 'wcname_close_link', 9);

// Woocommerec Remove Product title link on order table
add_filter('woocommerce_order_item_name', 'remove_permalink_order_table', 10, 3);

function remove_permalink_order_table($name, $item, $order) {
    $name = $item['name'];
    return $name;
}

function wcname_close_link() {
    echo "</a>";
}

//Run Php Code in Widget
function php_execute($html) {
    if (strpos($html, "<" . "?php") !== false) {
        ob_start(); eval("?" . ">" . $html);
        $html = ob_get_contents();
        ob_end_clean();
    }
    return $html;
}

add_filter('widget_text', 'php_execute', 100);

global $authordata;
if (( is_singular() or is_author() )
        and is_object($authordata)
        and isset($authordata->ID)
) {
    return $authordata->ID;
}
//code for after payment redirect 
if (is_plugin_active('woocommerce/woocommerce.php')) {
    add_action('template_redirect', 'wc_custom_redirect_after_purchase');

    function wc_custom_redirect_after_purchase($order_id) {
        global $wp;
        $location = get_page_link(942);
        if (is_checkout() && !empty($wp->query_vars['order-received'])) {
            wp_redirect($location, 301);
            exit;
        }
    }

}

/* Code to create custom Profile cover photo options page */
add_action("admin_menu", "add_appearance_menu_item");

function add_appearance_menu_item() {
    add_menu_page("Profile Cover Photo", "Theme Settings", "manage_options", "theme-options", "theme_options_page");
}

function theme_options_page() {
    ?>
    <div class="section panel">
        <h1>Theme Settings</h1>
        <?php settings_errors(); ?>
        <form method="post" enctype="multipart/form-data" action="options.php">			
            <?php
            add_settings_section("custom_theme_options", "", null, "theme-options");

            add_settings_field("profilecoverphoto", "Profile Cover Photo :", "display_profile", "theme-options", "custom_theme_options", array(
                'class' => 'profilespan'));

            add_settings_field("comment_emailid", "Comment Email :", "comment_email", "theme-options", "custom_theme_options");
            settings_fields('custom_theme_options');
            do_settings_sections('theme-options');
            ?>
            <p class="submit">  
                <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />  
            </p>  
        </form>
    </div>
    <?php
}

function display_profile() {
    ?>
    <div class="headermain">
        <div class="upprofilediv">
            <?php
            if ((get_option('profile_cover_photo')) != '') {
                ?>
                <div class="profilecoverphotoimg">
                    <img style="width: 500px;" src="<?php echo esc_url(get_option('profile_cover_photo')); ?>">
                </div>
            <?php } ?>
            <input type="text" class="profileurl" id="mainprofile" name="mainprofile" value="<?php echo get_option('profile_cover_photo'); ?>" />
            <input id="profileUplBtn" type="button" class="button" value="<?php _e('Upload Profile Cover Photo', 'betube'); ?>" />

            <input type="file" name="profile_cover_photo" id="profilecoverphoto" style="display:none;" />
        </div>
    </div>
    <?php
}

function comment_email() {
    ?>
    <input type="text" name="comment_emailid" id="comment_emailid" value="<?php echo get_option('comment_emailid'); ?>" class="comment_emailid" />
    <?php
}

function handle_profile_upload_ajax() {
    $uplfileurl = $_POST['attachmenturl'];
    $updatedprofile = update_option('profile_cover_photo', $uplfileurl);
    echo $updatedprofile; exit;
}

add_action('wp_ajax_handle_profile_upload_ajax', 'handle_profile_upload_ajax');

function betube_include_customuploadscript() {
    if (!did_action('wp_enqueue_media')) {
        wp_enqueue_media();
    }
}

add_action('admin_enqueue_scripts', 'betube_include_customuploadscript');

add_action('admin_head', 'custom_css_theme_options_page');

function custom_css_theme_options_page() {
    ?>
    <script>
        jQuery(document).ready(function () {
            jQuery("#profileUplBtn").click(function () {
                jQuery("#profilecoverphoto").click();
            });
            jQuery('body').on('click', '#profilecoverphoto', function (e) {
                e.preventDefault();
                var button = jQuery(this),
                        custom_uploader = wp.media({
                            title: 'Insert image',
                            library: {
                                type: 'image, application/pdf',
                            },
                            button: {
                                text: 'Select image'
                            },
                            multiple: false
                        }).on('select', function () {
                    var attachment = custom_uploader.state().get('selection').first().toJSON();

                    jQuery.post(
                            ajaxurl,
                            {
                                'action': 'handle_profile_upload_ajax',
                                'attachmenturl': attachment.url
                            },
                            function (response) {
                                if (response == 1) {
                                    jQuery("#mainprofile").val(attachment.url);
                                    jQuery(".profilecoverphotoimg").html('<img src="' + attachment.url + '" />');
                                }
                            }
                    );
                })
                        .open();

            });
        });
    </script>
    <?php
}

add_action('admin_init', 'register_custom_settings');

function register_custom_settings() {
    register_setting("custom_theme_options");
    register_setting("custom_theme_options", "comment_emailid");
}

// ADDING CUSTOM POST TYPE
add_action('init', 'betube_custom_post_types');

function betube_custom_post_types() {
    $allPostTypes = array(
        // Report content Post Type
        array(
            'post_type' => 'report_content',
            'singular' => 'Report Content',
            'plural' => 'Report Content',
            'genname' => 'report content',
            'supportarr' => array('title', 'editor', 'thumbnail', 'excerpt')
        ),
        // Report reply content Post Type
        array(
            'post_type' => 'report_comments',
            'singular' => 'Report Comments',
            'plural' => 'Report Comments',
            'genname' => 'report comments',
            'supportarr' => array('title', 'editor', 'thumbnail', 'excerpt')
        ),
    );
    foreach ($allPostTypes as $postType) {
        $post_type = $postType['post_type'];
        $singular = $postType['singular'];
        $plural = $postType['plural'];
        $genrlname = $postType['genname'];
        $menuicon = $postType['menuicon'];
        $supportarr = $postType['supportarr'];

        $labels = array(
            'name' => _x($plural, $genrlname, 'betube'),
            'singular_name' => _x($singular, $genrlname, 'betube'),
            'menu_name' => _x($plural, $genrlname, 'betube'),
            'name_admin_bar' => _x($singular, $genrlname, 'betube'),
            'add_new' => _x('Add New', $singular, 'betube'),
            'add_new_item' => __('Add New ' . $singular, 'betube'),
            'new_item' => __('New ' . $singular, 'betube'),
            'edit_item' => __('Edit ' . $singular, 'betube'),
            'view_item' => __('View ' . $singular, 'betube'),
            'all_items' => __('All ' . $plural, 'betube'),
            'search_items' => __('Search ' . $plural, 'betube'),
            'parent_item_colon' => __('Parent ' . $plural . ':', 'betube'),
            'not_found' => __('No ' . $singular . ' found.', 'betube'),
            'not_found_in_trash' => __('No ' . $singular . ' found in Trash.', 'betube')
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => true,
            'capability_type' => 'post',
            'capabilities' => array(
                'publish_posts' => 'manage_options',
                'edit_posts' => 'manage_options',
                'edit_others_posts' => 'manage_options',
                'delete_posts' => 'manage_options',
                'delete_others_posts' => 'manage_options',
                'read_private_posts' => 'manage_options',
                'edit_post' => 'manage_options',
                'delete_post' => 'manage_options',
                'read_post' => 'manage_options',
            ),
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 5,
            'menu_icon' => $menuicon,
            'supports' => $supportarr,
                //'register_meta_box_cb' => 'add_reportcomment_metaboxes'
        );

        register_post_type($post_type, $args);
    }
}

// Add the Report comment Meta Boxes
function add_reportcomment_metaboxes() {
    add_meta_box('_name ', 'Reported Name', 'reported_name', 'report_content', 'normal', 'high');
    add_meta_box('_email ', 'Reported Email', 'reported_email', 'report_content', 'normal', 'high');
    add_meta_box('_category ', 'Reported Category', 'reported_category', 'report_content', 'normal', 'high');
    add_meta_box('fronted_url ', 'Fronted Url', 'fronted_url', 'report_content', 'normal', 'high');
    add_meta_box('_backend_url ', 'Backend Url', 'backend_url', 'report_content', 'normal', 'high');

    add_meta_box('commented_name ', 'Claimer Name', 'comment_reported_name', 'report_comments', 'normal', 'high');
    add_meta_box('commented_email ', 'Claimer Email', 'comment_reported_email', 'report_comments', 'normal', 'high');
    add_meta_box('commented_fronted_url ', 'Commented Fronted Url', 'comment_fronted_url', 'report_comments', 'normal', 'high');
    add_meta_box('commented_backend_url ', 'Commented Backend Url', 'comment_backend_url', 'report_comments', 'normal', 'high');
    add_meta_box('commented_by_username ', 'Commented By User', 'comment_by_username', 'report_comments', 'normal', 'high');
    add_meta_box('commented_user_profile ', 'Commented User Profile', 'comment_user_profile', 'report_comments', 'normal', 'high');
}

add_action('add_meta_boxes', 'add_reportcomment_metaboxes');

//show meta box in Report Comment Post
function reported_name() {
    global $post;
    $reportedName = get_post_meta($post->ID, 'reported_name', true);
    echo ucwords($reportedName);
}

function reported_email() {
    global $post;
    echo get_post_meta($post->ID, 'reported_email', true);
}

function reported_category() {
    global $post;
    $reportedCat = get_post_meta($post->ID, 'reported_category', true);
    echo ucwords($reportedCat);
}

function fronted_url() {
    global $post;
    //echo get_post_meta( $post->ID,'fronted_url',true ); 
    echo '<a target="_blank" href="' . get_post_meta($post->ID, 'fronted_url', true) . '">' . get_post_meta($post->ID, 'fronted_url', true) . '</a>';
}

function backend_url() {
    global $post;
    //echo get_post_meta( $post->ID,'backend_url',true ); 
    echo '<a target="_blank" href="' . get_post_meta($post->ID, 'backend_url', true) . '">' . get_post_meta($post->ID, 'backend_url', true) . '</a>';
}

function comment_reported_name() {
    global $post;
    $reportedCommentName = get_post_meta($post->ID, 'comment_reported_name', true);
    echo ucwords($reportedCommentName);
}

function comment_reported_email() {
    global $post;
    echo get_post_meta($post->ID, 'comment_reported_email', true);
}

function comment_fronted_url() {
    global $post;
    //echo get_post_meta( $post->ID,'comment_fronted_url',true ); 
    echo '<a target="_blank" href="' . get_post_meta($post->ID, 'comment_fronted_url', true) . '">' . get_post_meta($post->ID, 'comment_fronted_url', true) . '</a>';
}

function comment_backend_url() {
    global $post;
    echo '<a target="_blank" href="' . get_post_meta($post->ID, 'comment_backend_url', true) . '">' . get_post_meta($post->ID, 'comment_backend_url', true) . '</a>';
}

function comment_by_username() {
    global $post;
    $commentByUsername = get_post_meta($post->ID, 'comment_by_username', true);
    echo ucwords($commentByUsername);
}

function comment_user_profile() {
    global $post;
    echo '<a target="_blank" href="' . get_post_meta($post->ID, 'comment_user_profile', true) . '">' . get_post_meta($post->ID, 'comment_user_profile', true) . '</a>';
}

//save Report Comment Data
function save_commentdata_form() {
    include_once(get_stylesheet_directory() . '/PHPMailer/class.phpmailer.php');
    global $wpdb;
    global $redux_demo;
    $inapp_content_title = 'Inappropriate content policy.';
    $inapp_content_url = esc_url(home_url());
    $copyright_url = esc_url(home_url());
    $adminMailId = get_option('comment_emailid');
    $adminMail = get_option('admin_email');
    $adminName = get_option('blogname');
    $mailFrom = ($adminMail != '') ? $adminMail : 'hitaxi.tt@gmail.com';
    $mailFromName = ($adminName != '') ? $adminName : 'YouFlip$';
    $currentdatetime = date('d/m/Y');
    $logo = $redux_demo['betube-logo']['url'];
    foreach ($_POST['data'] as $value) {
        $$value['name'] = $value['value'];
    }
    if (isset($cpt_nonce_field) && wp_verify_nonce($cpt_nonce_field, 'cpt_nonce_action')) {
        $post_commentdata_args = array(
            'post_status' => 'publish',
            'post_type' => $post_type,
            'post_content' => $message,
            'post_title' => $posttitle,
        );
        $reportCommentData = wp_insert_post($post_commentdata_args, $wp_error);
        add_post_meta($reportCommentData, 'reported_name', $reportedname, true);
        add_post_meta($reportCommentData, 'reported_email', $reportedemail, true);
        add_post_meta($reportCommentData, 'reported_category', $reportedcat, true);
        add_post_meta($reportCommentData, 'fronted_url', $frontendurl, true);
        add_post_meta($reportCommentData, 'backend_url', $backendurl, true);
    }


    if ($reportedcat == 'inappropriate content') {
        ob_start();
        include(get_template_directory() . '/templates/email/email-header.php');
        $email_subject = 'Report Content - "Inappropriate Content"';
        ?>
        <p>
            <?php if (!empty($logo)) { ?>
                <img src="<?php echo $logo; ?>" alt="Logo" />
            <?php } else { ?>
                <img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="Logo" />
            <?php } ?>
        </p>  
        <p><?php esc_html_e('Dear', 'betube'); ?> <?php echo ucwords($authername) ?>,</p>
        <p><?php esc_html_e('We value diversity and respect for others, and we strive to avoid offending users, so we don’t allow anything that display shocking content or promote hatred, intolerance, discrimination, or violence.', 'betube'); ?>		
        </p>
        <p>
            <?php esc_html_e('Your publication', 'betube'); ?> <strong>"<?php echo $posttitle; ?>"</strong> <?php esc_html_e('has been reported to have Inappropriate content. Please see below the comments provided.', 'betube'); ?><br>"<?php echo $message; ?>" <?php esc_html_e('dated', 'betube'); ?> <?php echo $currentdatetime; ?></p>
        <p>
            <?php esc_html_e('Please refer to our', 'betube'); ?> <a href="<?php echo $inapp_content_url ?>"><?php echo $inapp_content_title; ?></a> </p>
        <p><?php esc_html_e('If your publication violates our policies, please remove it IMMEDIATELY or we may suspend or terminate your account.', 'betube'); ?> </p>
        <p><?php esc_html_e('Please do not reply to this email. Emails sent to this address will not be answered.', 'betube'); ?> </p>

        <?php
        include(get_template_directory() . '/templates/email/email-footer.php');
        $reportcontentmsg = ob_get_contents();
        ob_end_clean();
        
        ob_start();
        include(get_template_directory() . '/templates/email/email-header.php');
        $email_subject_user = 'Report Content - "'.ucwords($reportedcat).'"';
        ?>
        <p>
        <?php if (!empty($logo)) { ?>
            <img src="<?php echo $logo; ?>" alt="Logo" />
        <?php } else { ?>
            <img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="Logo" />
        <?php } ?>
        </p> 
        <p><?php esc_html_e('Dear', 'betube'); ?> <?php echo ucwords($authername); ?>,</p>
        <p>
        <?php esc_html_e('Thank you for having reported one issue related to', 'betube'); ?> <strong>"<?php echo $posttitle; ?>"</strong> <?php esc_html_e('published by ', 'betube'); ?>..<?php echo ucwords($authername); ?></p>
        <p>
        <?php esc_html_e('We will investigate on this issue and inform you on the same.', 'betube'); ?></p>
        <p><?php esc_html_e('Please do not reply to this email. Emails sent to this address will not be answered.', 'betube'); ?> </p>

        <?php
        include(get_template_directory() . '/templates/email/email-footer.php');
        $reportedusermsg = ob_get_contents();
        ob_end_clean();
        
    }
    if ($reportedcat == 'copyright issue') {
        ob_start();
        include(get_template_directory() . '/templates/email/email-header.php');
        $email_subject = 'Report Content - "Copyright Issue"';
        ?>
        <p>
            <?php if (!empty($logo)) { ?>
                <img src="<?php echo $logo; ?>" alt="Logo" />
            <?php } else { ?>
                <img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="Logo" />
            <?php } ?>
        </p>  

        <p><?php esc_html_e('Dear', 'betube'); ?> <?php echo ucwords($authername) ?>,</p>
        <p><?php esc_html_e('Copyright is an important topic for the YouFlip$ community.', 'betube'); ?><br><?php esc_html_e('In many jurisdictions, when a person creates an original work that is fixed in a physical medium, he or she generally automatically owns copyright to the work. The owner has the exclusive right to use the work in certain, specific ways.', 'betube'); ?>		
        </p>
        <p>
            <?php esc_html_e('Your publication', 'betube'); ?> <strong>"<?php echo $posttitle; ?>"</strong> <?php esc_html_e('has been reported to have copyright issues. Please see below the comments provided.', 'betube'); ?><br><br>"<?php echo $message; ?>" <?php esc_html_e('dated', 'betube'); ?> <?php echo $currentdatetime; ?></p>
        <p>
            <?php esc_html_e('Please refer to our', 'betube'); ?> <a href="<?php echo $copyright_url ?>"><?php echo $reportedcat; ?></a> <?php esc_html_e('Page.', 'betube'); ?></p>

        <p><?php esc_html_e('If your publication infringes the copyright, please remove it IMMEDIATELY or we may suspend or terminate your account.', 'betube'); ?> </p>
        <p><?php esc_html_e('Please do not reply to this email. Emails sent to this address will not be answered.', 'betube'); ?> </p>


        <?php
        include(get_template_directory() . '/templates/email/email-footer.php');
        $reportcontentmsg = ob_get_contents();
        ob_end_clean();
        
        ob_start();
        include(get_template_directory() . '/templates/email/email-header.php');
        $email_subject_user = 'Report Content - "'.ucwords($reportedcat).'"';
        ?>
        <p>
        <?php if (!empty($logo)) { ?>
            <img src="<?php echo $logo; ?>" alt="Logo" />
        <?php } else { ?>
            <img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="Logo" />
        <?php } ?>
        </p> 
        <p><?php esc_html_e('Dear', 'betube'); ?> <?php echo ucwords($authername); ?>,</p>
        <p>
        <?php esc_html_e('Thank you for having reported one issue related to', 'betube'); ?> <strong>"<?php echo $posttitle; ?>"</strong> <?php esc_html_e('published by ', 'betube'); ?>..<?php echo ucwords($authername); ?></p>
        <p>
        <?php esc_html_e('We will investigate on this issue and inform you on the same.', 'betube'); ?></p>
        <p><?php esc_html_e('Please do not reply to this email. Emails sent to this address will not be answered.', 'betube'); ?> </p>

        <?php
        include(get_template_directory() . '/templates/email/email-footer.php');
        $reportedusermsg = ob_get_contents();
        ob_end_clean();
    }
    if ($reportedcat == 'others') {
        ob_start();
        include(get_template_directory() . '/templates/email/email-header.php');
        $email_subject = 'Report Content - "Other Issue"';
        ?>
        <p>
            <?php if (!empty($logo)) { ?>
                <img src="<?php echo $logo; ?>" alt="Logo" />
            <?php } else { ?>
                <img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="Logo" />
            <?php } ?>
        </p> 
        <p><?php esc_html_e('Dear', 'betube'); ?> <?php echo ucwords($authername) ?>,</p>
        <p><?php esc_html_e('Our community opinion matter.', 'betube'); ?></p>
        <p>
            <?php esc_html_e('Your publication', 'betube'); ?> <strong>"<?php echo $posttitle; ?>"</strong> <?php esc_html_e('has been reported to have some issues. Please see below the comments provided.', 'betube'); ?><br><br>"<?php echo $message; ?>" <?php esc_html_e('dated', 'betube'); ?> <?php echo $currentdatetime; ?></p>
        <p>
            <?php esc_html_e('Please take in serious consideration feedback from other users and act if required.', 'betube'); ?></p>
        <p><?php esc_html_e('By behaving with integrity and having respect for others will keep our community safe and respectful.', 'betube'); ?> </p>

        <p><?php esc_html_e('Please do not reply to this email. Emails sent to this address will not be answered.', 'betube'); ?> </p>

        <?php
        include(get_template_directory() . '/templates/email/email-footer.php');
        $reportcontentmsg = ob_get_contents();
        ob_end_clean();
        
        ob_start();
        include(get_template_directory() . '/templates/email/email-header.php');
        $email_subject_user = 'Report Content - "'.ucwords($reportedcat).'"';
        ?>
        <p>
        <?php if (!empty($logo)) { ?>
            <img src="<?php echo $logo; ?>" alt="Logo" />
        <?php } else { ?>
            <img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="Logo" />
        <?php } ?>
        </p> 
        <p><?php esc_html_e('Dear', 'betube'); ?> <?php echo ucwords($authername); ?>,</p>
        <p>
        <?php esc_html_e('Thank you for having reported one issue related to', 'betube'); ?> <strong>"<?php echo $posttitle; ?>"</strong> <?php esc_html_e('published by ', 'betube'); ?>..<?php echo ucwords($authername); ?></p>
        <p>
        <?php esc_html_e('We will investigate on this issue and inform you on the same.', 'betube'); ?></p>
        <p><?php esc_html_e('Please do not reply to this email. Emails sent to this address will not be answered.', 'betube'); ?> </p>

        <?php
        include(get_template_directory() . '/templates/email/email-footer.php');
        $reportedusermsg = ob_get_contents();
        ob_end_clean();
    }
   
    $msg = 'Hi Admin,<br/><br/> Some user has reported comments on content whoes details are as belove.<br/><br/>' .
            $msg .= '<strong>Reported Name :</strong> ' . $reportedname . '</p>' . "<br/>";
    $msg .= '<strong>Reported Email : </strong> ' . $reportedemail . '</p>' . "<br/>";
    $msg .= '<strong>Reported Category : </strong> ' . $reportedcat . '</p>' . "<br/>";
    $msg .= '<strong>Fronted Url : </strong> ' . $frontendurl . '</p>' . "<br/>";
    $msg .= '<strong>Backend Url : </strong> ' . $backendurl . '</p>' . "<br/>";
    $msg .= '<strong>Reported Message :</strong> ' . nl2br($message) . '</p>' . "<br/>";
    $msg .= '<br/>Thank you, <br/> Team Youflip$';


    $phpmailer = new PHPMailer();
    $phpmailer->IsSMTP(true); //switch to smtp
    $phpmailer->Host = 'smtp.gmail.com';
    $phpmailer->SMTPAuth = 1;
    $phpmailer->Port = 587;
    $phpmailer->Username = 'hitaxi.tt@gmail.com';
    $phpmailer->Password = 'hitaxi@trident';
    $phpmailer->SMTPSecure = 'tls';
    $phpmailer->From = $mailFrom;
    $phpmailer->FromName = $mailFromName;
    $phpmailer->AddAddress($adminMailId, $reportedname);
    $phpmailer->IsHTML(true);
    $phpmailer->Subject = "Content Report Submission - YouFlip$";
    $phpmailer->MsgHTML($msg);
    $phpmailer->Send();
    $phpmailer->clearAddresses();

    $phpmailer->From = $reportedemail;
    $phpmailer->FromName = ucwords($reportedname);
    $phpmailer->AddAddress($autheremail, $reportedname);
    $phpmailer->IsHTML(true);
    $phpmailer->Subject = $email_subject;
    $phpmailer->MsgHTML($reportcontentmsg);
    $phpmailer->Send();
    $phpmailer->clearAddresses();

    $phpmailer->From = $reportedemail;
    $phpmailer->FromName = ucwords($reportedname);
    $phpmailer->AddAddress($reportedemail, $reportedname);
    $phpmailer->IsHTML(true);
    $phpmailer->Subject = $email_subject_user;
    $phpmailer->MsgHTML($reportedusermsg);
    $phpmailer->Send();
    $phpmailer->clearAddresses();

    $response = array('status' => '1', 'message' => 'Report sent successfully.');
    echo json_encode($response); exit;
    exit;
}

add_action('wp_ajax_save_commentdata_form', 'save_commentdata_form');
add_action('wp_ajax_nopriv_save_commentdata_form', 'save_commentdata_form');

//save Report Content Data
function save_content_form() {
    include_once(get_stylesheet_directory() . '/PHPMailer/class.phpmailer.php');
    global $wpdb;
    global $redux_demo;
    $logo = $redux_demo['betube-logo']['url'];
    $email_subject = 'Report Content - "Inappropriate Content"';
    $inapp_content_title = 'Inappropriate content policy';
    $harassment_content_title = 'Harassment Cyberbullying policy';
    $inapp_content_url = esc_url(home_url());
    $harassment_content_url = esc_url(home_url());
    $adminMailId = get_option('comment_emailid');
    $adminMail = get_option('admin_email');
    $adminName = get_option('blogname');
    $mailFrom = ($adminMail != '') ? $adminMail : 'hitaxi.tt@gmail.com';
    $mailFromName = ($adminName != '') ? $adminName : 'YouFlip$';
    //echo "<pre>"; print_r($_POST['data']);
    foreach ($_POST['data'] as $value) {
        $$value['name'] = $value['value'];
    }
    
    $commenttimestr = substr($commenttime, 0, strpos($commenttime, '-'));
    $commented_date = date('d/m/Y', strtotime($commenttimestr));
    
    $comment = get_comment($commentid);
    $author_email = get_comment_author_email($comment);

    if (isset($content_nonce_field) && wp_verify_nonce($content_nonce_field, 'content_nonce_action')) {
        $post_conetentdata_args = array(
            'post_status' => 'publish',
            'post_type' => $post_type,
            'post_content' => $message,
            'post_title' => $posttitle,
        );
        $reportContentData = wp_insert_post($post_conetentdata_args, $wp_error);
        add_post_meta($reportContentData, 'comment_reported_name', $reportedname, true);
        add_post_meta($reportContentData, 'comment_reported_email', $reportedemail, true);
        add_post_meta($reportContentData, 'comment_fronted_url', $frontendurl, true);
        add_post_meta($reportContentData, 'comment_backend_url', $backendurl, true);
        add_post_meta($reportContentData, 'comment_user_profile', $profileurl, true);
        add_post_meta($reportContentData, 'comment_by_username', $profilename, true);
    }
    $msg = 'Hi Admin,<br/><br/> Some user has reported comments on content whoes details are as belove.<br/><br/>' .
            $msg .= '<strong>Reporte Commented Name :</strong> ' . $reportedname . '</p>' . "<br/>";
    $msg .= '<strong>Reporte Commented Email : </strong> ' . $reportedemail . '</p>' . "<br/>";
    $msg .= '<strong>Fronted Url : </strong> ' . $frontendurl . '</p>' . "<br/>";
    $msg .= '<strong>Backend Url : </strong> ' . $backendurl . '</p>' . "<br/>";
    $msg .= '<strong>Reported Commented Message :</strong> ' . nl2br($message) . '</p>' . "<br/>";
    $msg .= '<strong>Commented By User :</strong> ' . $profilename . '</p>' . "<br/>";
    $msg .= '<strong>Commented User Profile :</strong> ' . $profileurl . '</p>' . "<br/>";
    $msg .= '<br/>Thank you, <br/> Team Youflip$';


    ob_start();
    include(get_template_directory() . '/templates/email/email-header.php');
    ?>
    <p>
        <?php if (!empty($logo)) { ?>
            <img src="<?php echo $logo; ?>" alt="Logo" />
        <?php } else { ?>
            <img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="Logo" />
        <?php } ?>
    </p>
    <p><?php esc_html_e('Dear', 'betube'); ?> <?php echo ucwords($profilename) ?>,</p>
    <p><?php esc_html_e('We value diversity and respect for others, and we strive to avoid offending users, so we don’t allow harassment, cyberbullying, shocking content, promoting hatred, intolerance, discrimination, or verbal violence.', 'betube'); ?>		
    </p>
    <p>
        <?php esc_html_e('Your comment on', 'betube'); ?> <strong>"<?php echo $flipbookname; ?>"</strong> <?php esc_html_e('publication has been reported to be Inappropriate. Please see below the comments  provided.', 'betube'); ?><br>"<?php echo $message; ?>" <?php esc_html_e('dated', 'betube'); ?> <?php echo $commented_date; ?></p>
    <p>
        <?php esc_html_e('Please refer to our', 'betube'); ?> <a href="<?php echo $inapp_content_url ?> & <?php echo $harassment_content_url; ?>"><?php echo $inapp_content_title; ?> & <?php echo $harassment_content_title; ?></a></p>
    <p><?php esc_html_e('If your comment violates our policies, we will remove it immediately and your account may be suspended or terminate.', 'betube'); ?> </p>
    <p><?php esc_html_e('Please do not reply to this email. Emails sent to this address will not be answered.', 'betube'); ?> </p>

    <?php
    include(get_template_directory() . '/templates/email/email-footer.php');

    $reportcommentemail = ob_get_contents();
    ob_end_clean();

    $phpmailer = new PHPMailer();
    $phpmailer->IsSMTP(true); //switch to smtp
    $phpmailer->Host = 'smtp.gmail.com';
    $phpmailer->SMTPAuth = 1;
    $phpmailer->Port = 587;
    $phpmailer->Username = 'hitaxi.tt@gmail.com';
    $phpmailer->Password = 'hitaxi@trident';
    $phpmailer->SMTPSecure = 'tls';
    $phpmailer->From = $mailFrom;
    $phpmailer->FromName = $mailFromName;
    $phpmailer->AddAddress($adminMailId, $reportedname);
    $phpmailer->IsHTML(true);
    $phpmailer->Subject = "Comment Report Submission - YouFlip$";
    $phpmailer->MsgHTML($msg);
    $phpmailer->Send();
    $phpmailer->clearAddresses();


    $phpmailer->From = $mailFrom;
    $phpmailer->FromName = $mailFromName;
    $phpmailer->AddAddress($author_email, $profilename);
    $phpmailer->IsHTML(true);
    $phpmailer->Subject = $email_subject;
    $phpmailer->MsgHTML($reportcommentemail);
    $phpmailer->Send();
    $phpmailer->clearAddresses();

    $response = array('status' => '1', 'message' => 'Report comment sent successfully.');
    echo json_encode($response); exit;
    exit;
}

add_action('wp_ajax_save_content_form', 'save_content_form');
add_action('wp_ajax_nopriv_save_content_form', 'save_content_form');

//// Add profile link on comment -auther Title 
if (!function_exists('t5_comment_uri_to_author_archive')) {
    add_filter('get_comment_author_url', 't5_comment_uri_to_author_archive');

    function t5_comment_uri_to_author_archive($uri) {
        global $comment;
        //echo "<pre>";print_r($comment);
        // We do not get the real comment with this filter.
        if (empty($comment)
                or ! is_object($comment)
                or empty($comment->comment_author_email)
                or ! $user = get_user_by('email', $comment->comment_author_email)
        ) {
            return $uri;
        }

        return get_author_posts_url($user->ID);
    }

}

function auto_approve_comments_in_category($approved, $commentdata) {
    return 1;
}

add_filter('pre_comment_approved', 'auto_approve_comments_in_category', '99', 2);


/* custom image sizes for images */
add_image_size('flipbokkimg', 100, 100, true);

//subscriber login after redirect home page
function my_login_redirect($redirect_to, $request, $user) {
    //is there a user to check?
    if (isset($user->roles) && is_array($user->roles)) {
        //check for admins
        if (in_array('administrator', $user->roles)) {
            // redirect them to the default place
            return $redirect_to;
        } else {
            return home_url();
        }
    } else {
        return $redirect_to;
    }
}

add_filter('login_redirect', 'my_login_redirect', 10, 3);

//code for following count
function betubeFollowingCount($author_id) {
    global $wpdb;
    $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}author_followers WHERE follower_id
 = $author_id", OBJECT);
    $followcounter = count($results);
    return $followcounter;
}

//code for auther keywords get auther post
add_filter('posts_search', 'db_filter_authors_search');

function db_filter_authors_search($posts_search) {
    // Don't modify the query at all if we're not on the search template
    // or if the LIKE is empty
    if (!is_search() || empty($posts_search))
        return $posts_search;
    global $wpdb;
    // Get all of the users of the blog and see if the search query matches either
    // the display name or the user login
    add_filter('pre_user_query', 'db_filter_user_query');
    $search = sanitize_text_field(get_query_var('s'));
    $args = array(
        'count_total' => false,
        'exclude_admin' => true,
        'search' => sprintf('*%s*', $search),
        'search_fields' => array(
            'display_name',
            'user_login',
        ),
        'fields' => 'ID',
    );
    $matching_users = get_users($args);
    remove_filter('pre_user_query', 'db_filter_user_query');
    // Don't modify the query if there aren't any matching users
    if (empty($matching_users))
        return $posts_search;
    // Take a slightly different approach than core where we want all of the posts from these authors
    $posts_search = str_replace(')))', ")) OR ( {$wpdb->posts}.post_author IN (" . implode(',', array_map('absint', $matching_users)) . ")))", $posts_search);
    return $posts_search;
}

//change url continue shopping button
function my_woocommerce_continue_shopping_redirect($return_to) {
    return home_url();
}

add_filter('woocommerce_continue_shopping_redirect', 'my_woocommerce_continue_shopping_redirect', 20);

//save_follower_form
function save_follower_form() {
    global $wpdb;
    $reciveUpdateBtnValue = $_POST['ID'];
    parse_str($_POST['data'], $my_array);
    //print_r($my_array);
    $author_id = $my_array['author_id'];
    $follower_id = $my_array['follower_id'];

    $author_insert = ("INSERT into {$wpdb->prefix}author_followers (author_id,follower_id)value('" . $author_id . "','" . $follower_id . "')");
    $author_insertqry = $wpdb->query($author_insert);

    if ($author_insertqry) {
        $lastInsertid = $wpdb->insert_id;
        $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}author_followers SET recieve_update= '$reciveUpdateBtnValue' WHERE `id` = $lastInsertid"));
        $response = array('status' => '1', 'text' => 'unfollow');
        echo json_encode($response); exit;
        exit;
        //echo "success";
    }
    exit;
}

add_action('wp_ajax_save_follower_form', 'save_follower_form');
add_action('wp_ajax_nopriv_save_follower_form', 'save_follower_form');

//auther_receviupdate_following_form
function recieve_update_followingform() {
    global $wpdb;
    $author_id = $_POST['auther_id'];
    $follower_id = $_POST['follower_id'];
    $autherupdateval = $_POST['autherupdateval'];
    $reciveupdatestatus = ($autherupdateval == 'true') ? '1' : '0';
    $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}author_followers SET recieve_update= '" . $reciveupdatestatus . "' WHERE `author_id` = $author_id AND `follower_id` = $follower_id"));
    exit;
}

add_action('wp_ajax_recieve_update_followingform', 'recieve_update_followingform');
add_action('wp_ajax_nopriv_recieve_update_followingform', 'recieve_update_followingform');

add_filter('manage_users_columns', 'pippin_add_user_id_column');

//code for Ads User Display
function pippin_add_user_id_column($columns) {
    $columns['user_id'] = 'Action';
    return $columns;
}

function user_ads_display() {

    $userID = $_POST['data'];
    update_user_meta($userID, 'user_ads_show', 1);
    $response = array('status' => '1', 'text' => 'yes');
    echo json_encode($response); exit;
    exit;
}

add_action('wp_ajax_user_ads_display', 'user_ads_display');

function user_ads_revert() {

    $userID = $_POST['data'];
    delete_user_meta($userID, 'user_ads_show');
    $response = array('status' => '0', 'text' => 'no');
    echo json_encode($response); exit;
    exit;
}

add_action('wp_ajax_user_ads_revert', 'user_ads_revert');

add_action('manage_users_custom_column', 'pippin_show_user_id_column_content', 10, 3);

function pippin_show_user_id_column_content($value, $column_name, $user_id) {

    $user = get_userdata($user_id);
    if ('user_id' == $column_name) {
        $userAdsStatus = get_user_meta($user_id, 'user_ads_show', true);
        if ($userAdsStatus == '') {
            $btn .= '<div id="divUseradsid_' . $user_id . '""><button type="button" id="useradsid_' . $user_id . '" onclick="javascript:user_ads(' . $user_id . ');" class="button-primary">Ad Free User</button></div>';
        } else {
            $btn .= '<div id="divUseradsid_' . $user_id . '""><button type="button" id="useradsrevertid_' . $user_id . '" onclick="javascript:user_revertads(' . $user_id . ');" class="button-primary">Revert</button></div>';
        }
    }
    return $btn;
}

add_action('admin_head', 'user_ads_ajax');

function user_ads_ajax() {
    ?>
    <script>
        function user_ads(user_id) {
            jQuery.post(
                    ajaxurl,
                    {
                        'action': 'user_ads_display',
                        'data': user_id
                    }, function (response) {
                var obj = JSON.parse(response);
                //console.log(obj.status);
                if (obj.status.length > 0 && obj.status == '1') {
                    var useradsrevertbtn = '<button type="button" id="useradsrevertid_' + user_id + '" onclick="javascript:user_revertads(' + user_id + ');" class="button-primary">Revert</button>'
                    jQuery("#divUseradsid_" + user_id).html(useradsrevertbtn);
                }
            }
            );
            return false;
        }
        function user_revertads(user_id) {
            jQuery.post(
                    ajaxurl,
                    {
                        'action': 'user_ads_revert',
                        'data': user_id
                    }, function (response) {
                var obj = JSON.parse(response);
                if (obj.status.length > 0 && obj.status == '0') {

                    var useradstbtn = '<button type="button" id="useradsid_' + user_id + '" onclick="javascript:user_ads(' + user_id + ');" class="button-primary">Ad Free User</button>';
                    jQuery("#divUseradsid_" + user_id).html(useradstbtn);

                }
            }
            );
            return false;
        }
    </script>
    <?php
}

//comment on post email template change.
add_filter('comment_post', 'comment_notification');

function comment_notification($comment_ID, $comment_approved) {

    include_once(get_stylesheet_directory() . '/PHPMailer/class.phpmailer.php');
    global $redux_demo;
    $adminMailId = get_option('admin_email');
    $adminName = get_option('blogname');
    $mailFrom = ($adminMailId != '') ? $adminMailId : 'hitaxi.tt@gmail.com';
    $mailFromName = ($adminName != '') ? $adminName : 'YouFlip$';
    $logo = $redux_demo['betube-logo']['url'];
    $comment = get_comment($comment_ID);
    if (empty($comment) || empty($comment->comment_post_ID))
        return false;

    $post = get_post($comment->comment_post_ID);
    $author = get_userdata($post->post_author);
    $emails = array();
    if ($author) {
        $emails[] = $author->user_email;
    }

    $emails = apply_filters('comment_notification_recipients', $emails, $comment->comment_ID);
    $emails = array_filter($emails);

    // If there are no addresses to send the comment to, bail.
    if (!count($emails)) {
        return false;
    }

    // Facilitate unsetting below without knowing the keys.
    $emails = array_flip($emails);
    $notify_author = apply_filters('comment_notification_notify_author', false, $comment->comment_ID);

    // If there's no email to send the comment to, bail, otherwise flip array back around for use below
    if (!count($emails)) {
        return false;
    } else {
        $emails = array_flip($emails);
    }
    $email_subject = 'New Comment on your publication "' . $post->post_title . '" by ' . " " . $comment->comment_author;
    $reply_btn_title = "Reply";
    $reply_btn_link = get_comment_link($comment);
    $currentdatetime = date('d/m/Y');
    $comment_content = wp_specialchars_decode($comment->comment_content);

    ob_start();
    include(get_template_directory() . '/templates/email/email-header.php');
    ?>
    <p>
        <?php if (!empty($logo)) { ?>
            <img src="<?php echo $logo; ?>" alt="Logo" />
        <?php } else { ?>
            <img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="Logo" />
        <?php } ?>
    </p>
    <p><?php esc_html_e('Hi', 'betube'); ?> <?php echo ucwords($author->user_login) ?>,</p>
    <p><?php echo ucwords($comment->comment_author) ?> <?php esc_html_e('commented on your publication.', 'betube'); ?><?php echo $post->post_title; ?>,</p>

    <p>"<?php echo $comment_content; ?>" <?php esc_html_e('dated', 'betube'); ?> <?php echo $currentdatetime; ?></p>

    <p><a href="<?php echo $reply_btn_link ?>"><?php echo $reply_btn_title; ?></a></p>

    <p><?php esc_html_e('Please do not reply to this email. Emails sent to this address will not be answered.', 'betube'); ?> </p><br>

    <?php
    include(get_template_directory() . '/templates/email/email-footer.php');

    $reportcommentmsg = ob_get_contents();
    ob_end_clean();
    $emails = apply_filters('comment_moderation_recipients', $emails, $comment_id);
    $phpmailer = new PHPMailer();
    $phpmailer->IsSMTP(true); //switch to smtp
    $phpmailer->Host = 'smtp.gmail.com';
    $phpmailer->SMTPAuth = 1;
    $phpmailer->Port = 587;
    $phpmailer->Username = 'hitaxi.tt@gmail.com';
    $phpmailer->Password = 'hitaxi@trident';
    $phpmailer->SMTPSecure = 'tls';
    $phpmailer->From = $mailFrom;
    $phpmailer->FromName = $mailFromName;
    foreach ($emails as $email) {
        $phpmailer->AddAddress($email, $reportedname);
        $phpmailer->IsHTML(true);
        $phpmailer->Subject = $email_subject;
        $phpmailer->MsgHTML($reportcommentmsg);
        $phpmailer->Send();
        $phpmailer->clearAddresses();
    }
}
//user edit settings Update email notification custom
function custom_email_content($email_change, $user, $userdata) {
    global $redux_demo;
    $logo = $redux_demo['betube-logo']['url'];
    $email_subject = 'Profile Settings up-to-date';
    $currentdatetime = date('d/m/Y');
    ob_start();
    include(get_template_directory() . '/templates/email/email-header.php');
    ?>
    <p>
        <?php if (!empty($logo)) { ?>
            <img src="<?php echo $logo; ?>" alt="Logo" />
        <?php } else { ?>
            <img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="Logo" />
        <?php } ?>
    </p>
    <p><?php esc_html_e('Hi', 'betube'); ?> <?php echo ucwords($user['user_login']) ?>,</p>
    <p><?php esc_html_e('Please be informed that your profile setting have been successfully modified on', 'betube'); ?> <?php echo $currentdatetime; ?></p><br>

    <p><?php esc_html_e('Please do not reply to this email. Emails sent to this address will not be answered.', 'betube'); ?> </p>
    <?php
    include(get_template_directory() . '/templates/email/email-footer.php');

    $reportcommentmsg = ob_get_contents();
    ob_end_clean();

    $email_change['message'] = $reportcommentmsg;
    $email_change['subject'] = $email_subject;

    return $email_change;
}

add_filter('email_change_email', 'custom_email_content', 10, 3);
