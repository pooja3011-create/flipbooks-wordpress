<?php
/**
 * Template name: Reset Password Page
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage betube
 * @since betube 1.0
 */
if (is_user_logged_in()) {

    global $redux_demo;
    $profile = $redux_demo['profile'];
    wp_redirect($profile); exit;
}

global $resetSuccess;

if (!$user_ID) {

    if ($_POST['submit'] == 'Reset') {
        // First, make sure the email address is set
        if (isset($_POST['email']) && !empty($_POST['email'])) {

            $userarr = $wpdb->get_results("select ID from `wp_users` where `user_email`='" . $_POST['email'] . "'");
            // Next, sanitize the data
            $email_addr = trim(strip_tags(stripslashes($_POST['email'])));

            $user = get_user_by('email', $email_addr);
            $user_ID = $user->ID;
            $userName = $user->user_login;

            if (!empty($userarr)) {
                $new_password = wp_generate_password(12, false);

                if (isset($new_password)) {

                    wp_set_password($new_password, $user_ID);
                    $successmessage = esc_html__('Check your email for new password', 'betube');
                    $from = get_option('admin_email');
                    $headers = 'From: ' . $from . "\r\n";
                    global $redux_demo;
                    $logo = $redux_demo['betube-logo']['url'];
                    $blog_title = get_bloginfo('name');
                    $email_subject = $blog_title . " - Reset your password";
                    $forgotemail_contact = 'security@youflips.com';
                    ob_start();
                    include(get_template_directory() . '/templates/email/email-header.php');
//                                        $subject = "Password reset!";
//					$msg = "Your New Password is: $new_password and your user name is:$userName";
                    ?>
                    <p>
                    <?php if (!empty($logo)) { ?>
                            <img src="<?php echo $logo; ?>" alt="Logo" />
                    <?php } else { ?>
                            <img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="Logo" />
                        <?php } ?>
                    </p>
                    <p><?php esc_html_e('Thanks for contacting us.', 'betube'); ?><br><?php esc_html_e('Hi', 'betube'); ?> <?php echo ucwords($userName) ?>,<br><?php esc_html_e('Your temporary password is', 'betube'); ?> <?php echo ucwords($new_password) ?></p> 
                        
                    <p><?php esc_html_e("If you didn't request a password reset or you feel you've received this message in error, please contact us at", 'betube'); ?> <strong><?php echo $forgotemail_contact; ?> </strong><?php esc_html_e("our 24/7 support team. If you take no action, don't worry – nobody will be able to change your password without access to this email. Please do not reply to this email. Emails sent to this address will not be answered. ", 'betube'); ?> </p>
                 
                    <?php
                    include(get_template_directory() . '/templates/email/email-footer.php');
                    $msg = ob_get_contents();
                    ob_end_clean();
                    wp_mail($email_addr, $email_subject, $msg, $headers);
                    $resetSuccess = 1;
                }
            } else {
                $message = esc_html__('There is no user available for this email', 'betube');
            } // end if/else
        } else {
            $message = esc_html__('Email should not be empty.', 'betube');
        }
    }
}

get_header();
betube_breadcrumbs();
?>
<?php
$page = get_page($post->ID);
$current_page_id = $page->ID;
?>
<section class="registration">
    <div class="row secBg">
        <div class="large-12 columns">
            <div class="login-register-content">
                <div class="row collapse borderBottom">
                    <div class="medium-6 large-centered medium-centered">
                        <div class="page-heading text-center">
                            <h3><?php esc_html_e('Reset Your Password', 'betube') ?></h3>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <?php the_content(); ?>
    <?php endwhile; endif; ?>
                        </div>
                    </div><!--medium-6-->
                </div><!--Row-->
                <div class="row" data-equalizer data-equalize-on="medium" id="test-eq">
                    <div class="large-4 text-center medium-6 large-centered medium-centered columns">
                        <div class="register-form">
                            <?php if ($resetSuccess == 1) {
                                
                            } else { ?>
                                <h5 class="text-center"><?php esc_html_e('Enter Email', 'betube') ?></h5>
<?php } ?>
                            <form method="post" action="" id="myform" method="POST" enctype="multipart/form-data" data-abide novalidate>
<?php if ($message != '') { ?>
                                    <div style="display: block;" class="alert callout" data-abide-error="">
                                        <p><i class="fa fa-exclamation-triangle"></i> <?php echo $message; ?></p>
                                    </div>   
                            <?php } ?>	
                            <?php echo $successmessage; ?>
                                <?php if ($resetSuccess == 1) {
                                    
                                } else { ?>
                                    <div class="input-group">
                                        <span class="input-group-label"><i class="fa fa-user"></i></span>
                                        <input type="email" name="email" id="email" placeholder="<?php esc_html_e('Enter your email...', 'betube') ?>" required>
                                        <span class="form-error"><?php esc_html_e('Email is required', 'betube') ?></span>
                                    </div>
                                    <input type="hidden" id="submit" name="submit" value="Reset" />
                                    <button class="button expanded" type="submit" name="op" value="Reset"><?php esc_html_e('Reset Now', 'betube') ?></button>
<?php } ?>
                            </form>
                        </div><!--register-form-->
                    </div><!--large4-->
                </div><!--inner row-->
            </div><!--login-register-content-->
        </div><!--Large12-->
    </div><!--Row-->
</section><!--Main Section-->
<?php get_footer(); ?>