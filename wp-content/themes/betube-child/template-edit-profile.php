<?php
/**
 * Template name: Edit Profile
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

global $user_ID, $user_identity, $user_level;
global $redux_demo;
$profile = $redux_demo['profile'];

$user_info = get_userdata($user_ID);
//echo "<pre>";print_r($user_info);

if ($user_ID) {
    if ($_POST) {
        //print_r($_POST);
        $new_array = array();
        $userDataArr = array('display_name', 'user_email', 'user_url', 'phone', 'description', 'facebook', 'twitter', 'googleplus', 'youtube', 'vimeo', 'linkedin', 'pinterest', 'instagram');
        $user = get_userdata($user_ID);
        $userData = get_user_meta($user_ID);
        $userData['user_email'][0] = $user->user_email;
        $userData['user_url'][0] = $user->user_url;
        $userData['display_name'][0] = $user->display_name;
        foreach ($userData as $key => $value) {
            if (in_array($key, $userDataArr) && $_POST[$key] != $value[0]) {
                $new_array[] = $key;
            }
        }
        if (!empty($new_array)) {
            $currentDateTime = date('Y-m-d H:i:s');
            global $wpdb;
            $getFollowerData = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}followers_log WHERE `author_id` = $user_ID AND `status` = '0' AND `type` = 'profile_update'", OBJECT);

            if (count($getFollowerData) > 0) {
                $mydata = unserialize($getFollowerData->log);
                foreach ($new_array as $key => $value) {
                    if (!in_array($value, $mydata)) {
                        array_push($mydata, $value);
                    }
                }
                $serilizeData = serialize($mydata);
                $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}followers_log SET log= '$serilizeData', updated_date= '$currentDateTime' WHERE `id` = $getFollowerData->id"));
            } else {
                $serilizeData = serialize($new_array);
                $author_insert = ("INSERT into {$wpdb->prefix}followers_log (author_id,type,log,created_date,updated_date)value('" . $user_ID . "','profile_update','" . $serilizeData . "','" . $currentDateTime . "','" . $currentDateTime . "')");
                $wpdb->query($author_insert);
            }
        }

        $message = esc_html__('Your profile updated successfully.', 'betube');
        global $wpdb;
        $firstName = $wpdb->escape($_POST['first_name']);
        $lastName = $wpdb->escape($_POST['last_name']);
        $nickName = $wpdb->escape($_POST['user_nicename']);
        $displayName = $wpdb->escape($_POST['display_name']);
        $firsttext = $wpdb->escape($_POST['betube_heading_one']);
        $secondtext = $wpdb->escape($_POST['betube_heading_second']);
        $email = $wpdb->escape($_POST['user_email']);
        $user_url = $wpdb->escape($_POST['user_url']);
        $user_phone = $wpdb->escape($_POST['phone']);
        $facebook = $wpdb->escape($_POST['facebook']);
        $twitter = $wpdb->escape($_POST['twitter']);
        $googleplus = $wpdb->escape($_POST['google-plus']);
        $youtube = $wpdb->escape($_POST['youtube']);
        $vimeo = $wpdb->escape($_POST['vimeo']);
        $pinterest = $wpdb->escape($_POST['pinterest']);
        $instagram = $wpdb->escape($_POST['instagram']);
        $linkedin = $wpdb->escape($_POST['linkedin']);
        $description = $wpdb->escape(stripslashes($_POST['desc']));
        $password = $wpdb->escape($_POST['pwd']);
        $newPassword = $wpdb->escape($_POST['confirm']);
        $confirmPass= $wpdb->escape($_POST['confirmpass']);
        $emailshowhide= $wpdb->escape($_POST['emailshowhide']);
        
        $your_image_url = $wpdb->escape($_POST['your_author_image_url']);
        $authorProfileBG = $wpdb->escape($_POST['author-bg-image']);

        update_user_meta($user_ID, 'first_name', $firstName);
        update_user_meta($user_ID, 'last_name', $lastName);
        update_user_meta($user_ID, 'user_nicename', $nickName);
        update_user_meta($user_ID, 'firsttext', $firsttext);
        update_user_meta($user_ID, 'secondtext', $secondtext);
        update_user_meta($user_ID, 'phone', $user_phone);
        update_user_meta($user_ID, 'facebook', $facebook);
        update_user_meta($user_ID, 'twitter', $twitter);
        update_user_meta($user_ID, 'googleplus', $googleplus);
        update_user_meta($user_ID, 'youtube', $youtube);
        update_user_meta($user_ID, 'vimeo', $vimeo);
        update_user_meta($user_ID, 'linkedin', $linkedin);
        update_user_meta($user_ID, 'pinterest', $pinterest);
        update_user_meta($user_ID, 'instagram', $instagram);
        update_user_meta($user_ID, 'description', ($description));
        update_user_meta($user_ID, 'emailshowhide', $emailshowhide);
        wp_update_user(array('ID' => $user_ID, 'user_url' => $user_url, 'display_name' => $displayName));
        if (!empty($your_image_url)) {
            update_user_meta($user_ID, 'betube_author_avatar_url', $your_image_url);
        }
        if (!empty($authorProfileBG)) {
            update_user_meta($user_ID, 'betube_author_profile_bg', $authorProfileBG);
        }
        if (isset($email)) {

            if (preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $email)) {

                wp_update_user(array('ID' => $user_ID, 'user_email' => $email));
            } else {

                $message = '<div id="error">' . esc_html__('Please enter a valid email id.', 'betube') . '</div>';
            }
        }
        if (isset($password) && !empty($newPassword)) {

            if (strlen($password) < 5 || strlen($password) > 25) {

                $message = '<div id="error">' . esc_html__('Password must be 5 to 15 characters in length.', 'betube') . '</div>';
            }


            if (isset($newPassword) && $newPassword == $password) {

                $message = '<div id="error">' . esc_html__('You are using Old Password for update, Please use new Password.', 'betube') . '</div>';
            } 
            elseif ($newPassword != $confirmPass) {
             $message = '<div id="error">' . esc_html__('Passwords do not match.', 'betube') . '</div>';
            }
            elseif (isset($newPassword) && !empty($password)) {
                $update = wp_set_password($newPassword, $user_ID);

                $message = '<div id="success">' . esc_html__('Your Profile is Updated Successfully!', 'betube') . '</div>';
            }
        }
         
        $user_info = get_userdata($user_ID);
    }   
}
get_header();
betube_breadcrumbs();
?>
<?php
$page = get_page($post->ID);
$current_page_id = $page->ID;
$betubeProfileIMG = get_user_meta($user_ID, "betube_author_profile_bg", true);
$profileCoverPhoto = get_option('profile_cover_photo');
?>
<?php while (have_posts()) : the_post(); ?>
    <?php if ($betubeProfileIMG == '') {
        ?>
        <section class="topProfile topProfile-inner" style="background: url('<?php echo $profileCoverPhoto; ?>') no-repeat;">
        <?php } else {
            ?>
            <section class="topProfile topProfile-inner" style="background: url('<?php echo $betubeProfileIMG; ?>') no-repeat;">
            <?php } ?>
            <div class="row">
                <div class="large-12 columns">
                    <div class="upload-bg">			
                        <label for="topfileupload" class="btn-upload"><i class="fa fa-camera"></i><span><?php esc_html_e("update cover image", 'betube') ?></span></label>
                        <input type="file" id="topfileupload" class="uploadauthorBG show-for-sr">
                    </div><!--upload-bg-->
                </div><!--large-12-->
            </div><!--Row upload cover-->
            <div class="main-text">
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
                </div>
            </div><!--main-text-->
            <div class="profile-stats">
                <div class="row secBg">
                    <div class="large-12 columns">
                        <div class="profile-author-img">
                            <?php
                            $author_avatar_url = get_user_meta($user_ID, "betube_author_avatar_url", true);
                            if (!empty($author_avatar_url)) {
                                ?>
                                <img class="author-avatar" src="<?php echo $author_avatar_url; ?>" alt="" />
                                <?php
                            } else {
                                $avatar_url = betube_get_avatar_url(get_the_author_meta('user_email', $user_ID), $size = '130');
                                ?>
                                <img class="author-avatar" src="<?php echo $avatar_url; ?>" alt="" />
                                <?php
                            }
                            ?>

                            <!--<form method="post" id="">-->
                            <label for="imgfileupload" class="btn-upload"><i class="fa fa-camera"></i><span class=""><?php esc_html_e("update Avatar", 'betube') ?></span></label>
                            <input type="text" id="imgfileupload" class="upload-author-image show-for-sr">
                            <!--</form>-->

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
                                        <p class="number-text"><?php
                                            echo custom_get_user_posts_count($user_ID, array(
                                                'post_type' => 'post', 'post_status' => array(
                                                    'draft',
                                                    'publish')));
                                            ?></p>
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
                    </div><!--large-12-->
                </div><!--row secBg-->
            </div><!--end profile-stats-->
        </section><!--end Section topProfile-->
        <div class="clearfix"></div>
        <div class="row">
            <!-- left sidebar -->
            <div class="large-4 columns leftsidebar">
                <?php include_once 'profile-left-sidebar.php'; ?>
            </div><!--large-4-->
            <!-- left sidebar -->
            <!-- right side content area -->
            <div class="large-8 columns profile-inner">
                <!-- profile settings -->
                <section class="profile-settings">
                    <div class="row secBg">
                        <div class="large-12 columns">
                            <div class="heading">
                                <i class="flaticon-user"></i>
                                <h4><?php esc_html_e("Profile Settings", 'betube') ?></h4>
                            </div><!--heading-->
                            <div class="row">
                                <div class="large-12 columns">
                                    <?php 
//                                    if(isset($_SESSION["message"])){
//                                        echo 'YYYY';
//                                        echo $_SESSION["message"];
//                                    }else{
//                                        echo 'NNNN';
//                                    }
                                    if ($message != '') {
                                        ?>
                                        
                                        <div data-abide-error class="callout">
                                            <?php echo $message; 
                                                //unset($_SESSION["message"]);
                                                
                                             ?>
                                        </div><?php }
                                      
                                        ?>
                                    <div class="setting-form">
                                        <form class="form-item" action="" id="primaryPostForm" method="POST" enctype="multipart/form-data">
                                            <div class="setting-form-inner">
                                                <div class="row">
                                                    <div class="large-12 columns">
                                                        <h6 class="borderBottom"><?php esc_html_e("Username Setting", 'betube') ?>:</h6>
                                                    </div><!--large-12-->
                                                    <div class="medium-6 columns">
                                                        <label><?php esc_html_e("First Name", 'betube') ?>:
                                                            <input type="text" id="contactName" name="first_name" placeholder="<?php esc_html_e("Enter your first name", 'betube') ?>" value="<?php echo $user_info->first_name; ?>">
                                                        </label>
                                                    </div><!--First Name-->
                                                    <div class="medium-6 columns">
                                                        <label><?php esc_html_e("Last Name", 'betube') ?>:
                                                            <input type="text" id="contactName" name="last_name" placeholder="<?php esc_html_e("Enter your last name", 'betube') ?>" value="<?php echo $user_info->last_name; ?>">
                                                        </label>
                                                    </div><!--Last Name-->
                                                    <div class="medium-6 columns">
                                                        <label><?php esc_html_e("Nick Name", 'betube') ?>:
                                                            <input type="text" id="displayname" name="user_nicename" placeholder="<?php esc_html_e("Enter your nice Name", 'betube') ?>" value="<?php echo $user_info->user_nicename; ?>">
                                                        </label>
                                                    </div><!--Nick Name-->
                                                    <div class="medium-6 columns">
                                                        <label><?php esc_html_e("Display Name / Company Name", 'betube') ?>:
                                                            <input type="text" id="display_name" name="display_name" placeholder="<?php esc_html_e("Enter your Display Name", 'betube') ?>" value="<?php echo $user_info->display_name; ?>">
                                                        </label>
                                                    </div><!--Display Name-->
                                                </div><!--row-->
                                            </div><!--setting-form-inner-->
                                            <div class="setting-form-inner">
                                                <div class="row">
                                                    <div class="large-12 columns">
                                                        <h6 class="borderBottom"><?php esc_html_e("Profile Heading", 'betube') ?>:</h6>
                                                    </div><!--Profile Heading Text-->
                                                    <div class="medium-6 columns">
                                                        <label><?php esc_html_e("First Heading", 'betube') ?>:
                                                            <input type="text" name="betube_heading_one" id="betube_heading_one" placeholder="<?php esc_html_e("Enter First Heading..", 'betube') ?>" value="<?php echo $user_info->firsttext; ?>">
                                                        </label>
                                                    </div><!--First Heading-->
                                                    <div class="medium-6 columns">
                                                        <label><?php esc_html_e("Second Heading", 'betube') ?>:
                                                            <input type="text" name="betube_heading_second" id="betube_heading_second" placeholder="<?php esc_html_e("Enter Second Heading..", 'betube') ?>" value="<?php echo $user_info->secondtext; ?>">
                                                        </label>
                                                    </div><!--First Heading-->
                                                </div><!--row-->
                                            </div><!--setting-form-inner-->
                                            <div class="setting-form-inner">
                                                <div class="row">
                                                    <div class="large-12 columns">
                                                        <h6 class="borderBottom"><?php esc_html_e("Update Password", 'betube') ?>:</h6>
                                                    </div><!--Update Password-->
                                                    <div class="medium-6 columns">
                                                        <label><?php esc_html_e("Current Password", 'betube') ?>:
                                                            <input type="password" name="pwd" id="password" placeholder="<?php esc_html_e("Enter current password..", 'betube') ?>">
                                                        </label>
                                                    </div><!--New Password-->
                                                    <div class="medium-6 columns">
                                                        <label><?php esc_html_e("New Password", 'betube') ?>:
                                                            <input type="password" id="password" name="confirm" placeholder="<?php esc_html_e("Enter new password..", 'betube') ?>">
                                                        </label>
                                                    </div><!--Retype Password-->
                                                    <div class="medium-6 columns">
                                                        <label><?php esc_html_e("Confirm Password", 'betube') ?>:
                                                            <input type="password" id="confirmpass" name="confirmpass" placeholder="<?php esc_html_e("Enter confirm password..", 'betube') ?>">
                                                        </label>
                                                    </div><!--Retype Password-->
                                                </div><!--row-->
                                            </div><!--setting-form-inner-->
                                            <div class="setting-form-inner">
                                                <div class="row">
                                                    <div class="large-12 columns">
                                                        <h6 class="borderBottom"><?php esc_html_e("About Me", 'betube') ?>:</h6>
                                                    </div><!--large-12-->
                                                    <div class="medium-6 columns">
                                                        <label><?php esc_html_e("Email ID", 'betube') ?>:
                                                            <input type="email" id="email" name="user_email" placeholder="<?php esc_html_e("enter your email address..", 'betube') ?>" value="<?php echo $user_info->user_email; ?>">
                                                        </label>
                                                        <div class="checkbox">
                                                            <?php $emailshowhide =  $user_info->emailshowhide; ?>
                                                            <input value="yes" <?php echo ($emailshowhide=='yes' ? 'checked' : '');?> type="checkbox" name="emailshowhide" id="remember">
                                                            <label for="remember" class="customLabel"><?php esc_html_e("Show email on auther profile page?", 'betube') ?></label>
                                                        </div>
                                                    </div><!--Email ID-->
                                                    
                                                    <div class="medium-6 columns">
                                                        <label><?php esc_html_e("Website URL", 'betube') ?>:
                                                            <input type="url" id="website" name="user_url" placeholder="<?php esc_html_e("Enter your Website URL", 'betube') ?>" value="<?php echo $user_info->user_url; ?>">
                                                        </label>
                                                    </div><!--Website URL-->
                                                    <div class="medium-6 columns end">
                                                        <label><?php esc_html_e("Phone No", 'betube') ?>:
                                                            <input type="tel" placeholder="<?php esc_html_e("Enter your phone number", 'betube') ?>" name="phone" value="<?php echo $user_info->phone; ?>">
                                                        </label>
                                                    </div><!--Phone No-->
                                                    <div class="medium-12 columns">
                                                        <label><?php esc_html_e("About", 'betube') ?>:
                                                            <textarea name="desc" id="desc" class="text" placeholder="<?php esc_html_e('About', 'betube'); ?>" rows="10"><?php echo $user_info->description; ?></textarea>
                                                        </label>
                                                    </div><!--About-->
                                                </div><!--row-->
                                            </div><!--setting-form-inner-->
                                            <div class="setting-form-inner">
                                                <div class="row">
                                                    <div class="large-12 columns">
                                                        <h6 class="borderBottom"><?php esc_html_e("Social Profile links", 'betube') ?>:</h6>
                                                    </div><!--Social Profile-->
                                                    <div class="medium-6 columns">
                                                        <label><?php esc_html_e("Facebook", 'betube') ?>:
                                                            <input type="url" id="facebook" name="facebook" placeholder="<?php esc_html_e("Your facebook url", 'betube') ?>" value="<?php echo $user_info->facebook; ?>">
                                                        </label>
                                                    </div><!--facebook-->
                                                    <div class="medium-6 columns">
                                                        <label><?php esc_html_e("Twitter", 'betube') ?>:
                                                            <input type="url" id="twitter" name="twitter" placeholder="<?php esc_html_e("Your twitter URL", 'betube') ?>" value="<?php echo $user_info->twitter; ?>">
                                                        </label>
                                                    </div><!--twitter-->
                                                    <div class="medium-6 columns end">
                                                        <label><?php esc_html_e("Google Plus", 'betube') ?>:
                                                            <input type="url" id="google-plus" name="google-plus" placeholder="<?php esc_html_e("Your Google Plus URL", 'betube') ?>" value="<?php echo $user_info->googleplus; ?>">
                                                        </label>
                                                    </div><!--Google Plus-->
                                                    <div class="medium-6 columns">
                                                        <label><?php esc_html_e("Youtube", 'betube') ?>:
                                                            <input type="url" name="youtube" id="youtube" placeholder="<?php esc_html_e("Your Youtube URL", 'betube') ?>" value="<?php echo $user_info->youtube; ?>">
                                                        </label>
                                                    </div><!--Youtube-->
                                                    <div class="medium-6 columns">
                                                        <label><?php esc_html_e("Vimeo", 'betube') ?>:
                                                            <input type="url" id="vimeo" name="vimeo" placeholder="<?php esc_html_e("Your Vimeo URL", 'betube') ?>" value="<?php echo $user_info->vimeo; ?>">
                                                        </label>
                                                    </div><!--vimeo-->
                                                    <div class="medium-6 columns end">
                                                        <label><?php esc_html_e("Pinterest", 'betube') ?>:
                                                            <input type="url" id="pinterest" name="pinterest" placeholder="<?php esc_html_e("Your Pinterest URL", 'betube') ?>" value="<?php echo $user_info->pinterest; ?>">
                                                        </label>
                                                    </div><!--Pinterest-->
                                                    <div class="medium-6 columns">
                                                        <label><?php esc_html_e("Instagram", 'betube') ?>:
                                                            <input type="url" id="instagram" name="instagram" placeholder="<?php esc_html_e("Your Instagram URL", 'betube') ?>" value="<?php echo $user_info->instagram; ?>">
                                                        </label>
                                                    </div><!--Instagram-->
                                                    <div class="medium-6 columns end">
                                                        <label><?php esc_html_e("Linkedin", 'betube') ?>:
                                                            <input type="url" id="linkedin" name="linkedin" placeholder="<?php esc_html_e("Your Linkedin URL", 'betube') ?>" value="<?php echo $user_info->linkedin; ?>">
                                                        </label>
                                                    </div><!--Linkedin-->											
                                                    <!--Author BG-->
                                                    <input class="author-bg-image" id="author-bg-image" type="hidden" name="author-bg-image" value="" />
                                                    <!--Author BG-->
                                                    <!--Author IMG-->
                                                    <input class="criteria-image-url" id="your_image_url" type="hidden" name="your_author_image_url" value="" />
                                                    <!--Author IMG-->
                                                </div><!--row-->
                                            </div><!--setting-form-inner-->
                                            <div class="setting-form-inner">
                                                <?php wp_nonce_field('post_nonce', 'post_nonce_field'); ?>
                                                <input type="hidden" name="submitted" id="submitted" value="true" />
                                                <button class="button expanded" type="submit" name="op"><?php esc_html_e("Update", 'betube') ?></button>
                                            </div>
                                        </form>
                                    </div><!--End setting-form-->
                                </div><!--End large-12-->
                            </div><!--End Row-->
                        </div><!--large-12-->
                    </div><!--End row secBg-->
                </section><!--End profile-settings-->
                <!-- profile settings -->
            </div>
            <!-- right side content area -->
        </div><!--End Row-->

    <?php endwhile; ?>
    <script>
        var image_custom_uploader;
        var $thisItem = '';

        jQuery(document).on('click', '.upload-author-image', function (e) {
            e.preventDefault();
            $thisItem = jQuery(this);
            $form = jQuery('#primaryPostForm');

            //Extend the wp.media object
            image_custom_uploader = wp.media.frames.file_frame = wp.media({
                title: 'Choose Image',
                button: {
                    text: 'Choose Image'
                },
                multiple: false
            });

            //When a file is selected, grab the URL and set it as the text field's value
            image_custom_uploader.on('select', function () {
                attachment = image_custom_uploader.state().get('selection').first().toJSON();
                var url = '';
                url = attachment['url'];
                var attachId = '';
                attachId = attachment['id'];
                $thisItem.parent().parent().find("img.author-avatar").attr({
                    src: url
                });
                $form.parent().parent().find(".criteria-image-url").attr({
                    value: url
                });
                $form.parent().parent().find(".criteria-image-id").attr({
                    value: attachId
                });
            });

            //Open the uploader dialog
            image_custom_uploader.open();
        });
        //Author Profile BG Function
        jQuery(document).on('click', '.uploadauthorBG', function (e) {
            e.preventDefault();
            $Itemthis = jQuery(this);
            $form = jQuery('#primaryPostForm');

            //Extend the wp.media object
            image_custom_uploader = wp.media.frames.file_frame = wp.media({
                title: 'Choose Image',
                button: {
                    text: 'Choose Image'
                },
                multiple: false
            });

            //When a file is selected, grab the URL and set it as the text field's value
            image_custom_uploader.on('select', function () {
                attachment = image_custom_uploader.state().get('selection').first().toJSON();
                var bgurl = '';
                bgurl = attachment['url'];
                var attachId = '';
                attachId = attachment['id'];

                jQuery('.topProfile').css('background', 'url(' + bgurl + ') no-repeat');
                $form.parent().parent().find("#author-bg-image").attr({
                    value: bgurl
                });
            });

            //Open the uploader dialog
            image_custom_uploader.open();
        });
        //Author Profile BG Function	
    </script>
    <?php get_footer(); ?>