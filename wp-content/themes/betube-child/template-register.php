<?php
/**
 * Template name: Register Page
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage betube
 * @since betube
 */
if (is_user_logged_in()) {

    global $redux_demo;
    $profile = $redux_demo['profile'];
    wp_redirect($profile); exit;
}
global $user_ID, $user_identity, $user_level, $registerSuccess;

$registerSuccess = "";
if (!$user_ID) {

    if ($_POST) {

        $message = esc_html__('Registration successful.', 'betube');

        $username = $wpdb->escape($_POST['username']);

        $email = $wpdb->escape($_POST['email']);

        $password = $wpdb->escape($_POST['pwd']);

        $confirm_password = $wpdb->escape($_POST['confirm']);

        $registerSuccess = 1;

        if (empty($username)) {
            $message = esc_html__('User name should not be empty.', 'betube');
            $registerSuccess = 0;
        }



        if (isset($email)) {

            if (preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $email)) {

                wp_update_user(array('ID' => $user_ID, 'user_email' => $email));
            } else {
                $message = esc_html__('Please enter a Valid Email', 'betube');
            }

            $registerSuccess = 0;
        }

        if ($password) {

            if (strlen($password) < 5 || strlen($password) > 15) {

                $message = esc_html__('Password must be 5 to 15 characters in length.', 'betube');

                $registerSuccess = 0;
            }

            //elseif( $password == $confirm_password ) {
            elseif (isset($password) && $password != $confirm_password) {

                $message = esc_html__('Password Mismatch', 'betube');

                $registerSuccess = 0;
            } elseif (isset($password) && !empty($password)) {

                $update = wp_set_password($password, $user_ID);

                $message = esc_html__('Registration successful.', 'betube');

                $registerSuccess = 1;
            }
        }

        $status = wp_create_user($username, $password, $email);
        if (is_wp_error($status)) {
            $registerSuccess = 0;
            $message = esc_html__('Username or E-mail already exists. Please try another one.', 'betube');
        } else {

            beTubeUserNotification($email, $password, $username);
            global $redux_demo;
            $newUsernotification = $redux_demo['newusernotification'];
            if ($newUsernotification == 1) {
                beTubeNewUserNotifiy($email, $username);
            }

            $registerSuccess = 1;
        }


        if ($registerSuccess == 1) {

            $login_data = array();
            $login_data['user_login'] = $username;
            $login_data['user_password'] = $password;
            $user_verify = wp_signon($login_data, false);

            global $redux_demo;
            $profile = $redux_demo['profile'];
            wp_redirect($profile); exit;
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
            <?php $betubeCanReg = get_option('users_can_register'); ?>
            <?php if ($betubeCanReg == '0') { ?>
                <span class='registration-closed'><?php esc_html_e('Registration is currently disabled. Please try again later.', 'betube') ?></span>
            <?php } else { ?>			

                <div class="login-register-content">
                    <div class="row collapse borderBottom">
                        <div class="medium-6 large-centered medium-centered">
                            <div class="page-heading text-center">
                                <h3><?php esc_html_e('User Registeration', 'betube') ?></h3>
                                <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                                        <?php the_content(); ?>
                                    <?php endwhile; endif; ?>
                            </div><!--page-heading-->
                        </div><!--medium-6-->
                    </div><!--row collapse-->

                    <div class="row" data-equalizer data-equalize-on="medium" id="test-eq">
                        <div class="large-4 medium-6 large-centered medium-centered columns">
                            <div class="register-form">
                                <h5 class="text-center"><?php esc_html_e('Create your Account', 'betube') ?></h5>
                                <?php if (!empty($message)) { ?>
                                    <div>
                                        <p>
                                            <i class="fa fa-exclamation-triangle"></i>
                                            <?php echo $message; ?>										
                                        </p>
                                    </div>
                                <?php } ?>
                                <form id="myform" method="POST" enctype="multipart/form-data" data-abide novalidate>
                                    <div data-abide-error class="alert callout" style="display: none;">
                                        <p><i class="fa fa-exclamation-triangle"></i> <?php esc_html_e('There are some errors in your form.', 'betube') ?></p>
                                    </div>
                                    <?php if (!empty($registerSuccess) && $registerSuccess == 0) { ?>

                                        <div class="alert callout">
                                            <p> <?php echo $message; ?></p>
                                        </div>

                                    <?php } ?>
                                    <div class="input-group">
                                        <span class="input-group-label"><i class="fa fa-user"></i></span>
                                        <input class="input-group-field" type="text" id="contactName" name="username" maxlength="30" placeholder="<?php esc_html_e('Enter your username or company name ', 'betube') ?>" required>
                                    </div>

                                    <div class="input-group">
                                        <span class="input-group-label"><i class="fa fa-envelope"></i></span>
                                        <input class="input-group-field" type="email" id="email" name="email" placeholder="<?php esc_html_e('Enter your email', 'betube') ?>" required>
                                    </div>

                                    <div class="input-group">
                                        <span class="input-group-label"><i class="fa fa-lock"></i></span>
                                        <input type="password" id="password" name="pwd" placeholder="<?php esc_html_e('Enter your password', 'betube') ?>" required>
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-label"><i class="fa fa-lock"></i></span>
                                        <input type="password" name="confirm" placeholder="<?php esc_html_e('Re-type your password', 'betube') ?>" required pattern="alpha_numeric">
                                    </div>
                                    <div class="checkbox">
                                        <input id="termsconditionsreg" type="checkbox" name="termsconditionsreg" required>
                                        <label class="customLabel" for="termsconditionsreg">I agree to the <a class="termslink" href="<?php echo get_page_link(52); ?>">Terms and Conditions</a></label>
                                        <span class="error-message" id="error_regterms" style="display: none;">Please agree to the Terms and Conditions</span>
                                    </div>
                                    <span class="form-error"><?php esc_html_e('Your email is invalid', 'betube') ?></span>
                                    <input type="hidden" name="submit" value="Register" id="submit" />
                                   
                                    <button class="button expanded"  onClick="return ValidateFormreg(this.form)"  value="Publish Ad"><?php esc_html_e('Register Now', 'betube') ?></button>
                                    <?php
                                    global $redux_demo;
                                    $reset = $redux_demo['reset'];
                                    $login = $redux_demo['login'];
                                    ?>
                                    <p class="loginclick"> 
                                        <a href="<?php echo $reset; ?>"><?php esc_html_e('Forgot Password?', 'betube') ?>
                                        </a>
                                        <a href="<?php echo $login; ?>"><?php esc_html_e('Login here', 'betube') ?></a>
                                    </p>
                                </form>
                            </div>
                        </div><!--End Large 4 For Form-->
                    </div><!--row test-eq-->
                </div><!--login-register-content-->	
            <?php } ?>			
        </div><!--large-12-->
    </div><!--row secBg-->
</section><!--registration-->
<script LANGUAGE="JavaScript">
    function ValidateFormreg(form) {
       var name = jQuery('#contactName').val();
        if (name.length > 0 ) {           
            if (jQuery('#termsconditionsreg').is(':checked')) {
                jQuery('#myform').submit();
            } else {
                jQuery('#error_regterms').css('display', 'block');
                return false;
            }
        } else { 
            jQuery('#myform').submit();
        }
    }
</script>
<?php get_footer(); ?>