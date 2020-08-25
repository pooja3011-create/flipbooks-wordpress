<?php
/**
 * Template name: Login Page
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

global $user_ID, $username, $password, $remember;

//We shall SQL escape all inputs
$username = esc_sql(isset($_REQUEST['username']) ? $_REQUEST['username'] : '');
$password = esc_sql(isset($_REQUEST['password']) ? $_REQUEST['password'] : '');
$remember = esc_sql(isset($_REQUEST['rememberme']) ? $_REQUEST['rememberme'] : '');
$termsconditions = esc_sql(isset($_REQUEST['termsconditions']) ? $_REQUEST['termsconditions'] : '');

if ($remember)
    $remember = "true";
else
    $remember = "false";
$login_data = array();
$login_data['user_login'] = $username;
$login_data['user_password'] = $password;
$login_data['remember'] = $remember;
$user_verify = wp_signon($login_data, false);
//wp_signon is a wordpress function which authenticates a user. It accepts user info parameters as an array.

if (isset($_POST['submit'])) {
    if ($_POST['submit'] == 'Login') {
        $login_data = array();
        $login_data['user_login'] = $username;
        $login_data['user_password'] = $password;
        $login_data['remember'] = $remember;
        $login_data['termsconditions'] = $termsconditions;
        $user_verify = wp_signon($login_data, false);
        //print_r($user_verify); exit();

        if (is_wp_error($user_verify)) {
            $UserError = esc_html__('Invalid username or password. Please try again!', 'betube');
        } else {

            global $redux_demo;
            $profile = $redux_demo['profile'];
            wp_redirect($profile); exit;
        }
    }
}

get_header();
//betube_breadcrumbs();
?>
<!-- advertisement -->
<?php global $redux_demo;
?>
<section class="registration">
    <div class="row secBg">
        <div class="large-12 columns">
            <div class="login-register-content">
                <div class="row collapse borderBottom">
                    <div class="medium-6 large-centered medium-centered">
                        <div class="page-heading text-center">
                            <h3><?php esc_html_e('User login', 'betube') ?></h3>
                            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                                    <?php the_content(); ?>
                                <?php endwhile; endif; ?>
                        </div><!--page-heading-->
                    </div><!--medium-6-->
                </div><!--row collapse-->
                <div class="row" data-equalizer data-equalize-on="medium" id="test-eq">
                    <div class="large-4 medium-6 large-centered medium-centered columns">
                        <div class="register-form">
                            <h5 class="text-center"><?php esc_html_e('Login Here...', 'betube') ?></h5>
                            <form id="myform" method="POST" enctype="multipart/form-data" data-abide novalidate>
                                <div data-abide-error class="alert callout" style="display: none;">
                                    <p><i class="fa fa-exclamation-triangle"></i>
                                        <?php esc_html_e('There are some errors in your form.', 'betube') ?>																	                                    </p>
                                </div>
                                <?php if (!empty($UserError)) { ?>
                                    <div>
                                        <p class="errormsg">
                                            <i class="fa fa-exclamation-triangle"></i>
                                            <?php echo $UserError; ?>										
                                        </p>
                                    </div>
                                <?php } ?>	
                                <div class="input-group">
                                    <span class="input-group-label"><i class="fa fa-user"></i></span>
                                    <input class="input-group-field" type="text" id="contactName" name="username" placeholder="<?php esc_html_e('Enter your username', 'betube') ?>" required>
                                    <span class="form-error"><?php esc_html_e('username is required', 'betube') ?></span>
                                </div><!--input-group UserName-->
                                <div class="input-group">
                                    <span class="input-group-label"><i class="fa fa-lock"></i></span>
                                    <input type="password" id="password" name="password" placeholder="<?php esc_html_e('Enter your Password', 'betube') ?>" required>
                                    <span class="form-error"><?php esc_html_e('Password is required', 'betube') ?></span>
                                </div><!--input-group password-->
                                <div class="checkbox">
                                    <input id="remember" type="checkbox" value="forever" name="rememberme">
                                    <label class="customLabel" for="remember"><?php esc_html_e('Remember me', 'betube'); ?></label>
                                </div>
                               <?php /**  <div class="checkbox">
                                    <input id="termsconditionslogin" type="checkbox" name="termsconditionslogin" required>
                                    <label class="customLabel" for="termsconditionslogin">I agree to the <a class="termslink" href="<?php echo get_page_link(52); ?>">Terms and Conditions</a></label>
                                    <span class="error-message" id="error_termslogin" style="display: none;">Please agree to terms and conditions.</span>
                                </div>*/ ?>

                                <input type="hidden" id="submitbtn" name="submit" value="Login" /> 
                                <button class="button expanded" type="submit" name="op" value="Login"><?php esc_html_e('LOGIN NOW', 'betube') ?></button>
                                <!--<button class="button expanded" type="submit" onClick="return ValidateForm(this.form)" name="op" value="Login"><?php esc_html_e('LOGIN NOW', 'betube') ?></button>-->
                                <?php
                                global $redux_demo;
                                $reset = $redux_demo['reset'];
                                $register = $redux_demo['register'];
                                ?>
                                <p class="loginclick">
                                    <a href="<?php echo $reset; ?>"><?php esc_html_e('Forgot Password?', 'betube') ?>
                                    </a> 
                                        <?php esc_html_e('New Here?', 'betube') ?>&nbsp;<a href="<?php echo $register; ?>"><?php esc_html_e('Create a new Account', 'betube') ?></a></p>
                            </form>
                        </div><!--register-form-->
                    </div><!--large-4 Login Form-->
                </div><!--row-->
            </div><!--login-register-content-->
        </div><!--large-12-->
    </div><!--row secBg-->
</section><!--Section-->
<?php /** <script LANGUAGE="JavaScript">
    function ValidateForm(form) {

//        var ele = document.getElementById('termsconditionsreg');        
       var name = jQuery('#contactName').val();
        if (name.length > 0 ) {           
            if (jQuery('#termsconditionslogin').is(':checked')) {
                jQuery('#myform').submit();
            } else {
                jQuery('#error_termslogin').css('display', 'block');
                return false;
            }
        } else { 
            jQuery('#myform').submit();
        }

    }
</script>**/ ?>
<?php get_footer(); ?>
