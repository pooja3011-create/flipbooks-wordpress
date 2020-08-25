<!-- By default, this menu will use off-canvas for small
         and a topbar for medium-up -->
<?php
global $redux_demo;
$topBarBlack = "";
$navBlack = "";
$navFull = "";
$navFullv2 = "";
$navfullv2light = "";
$divClass = "";
$titlebardark = "";
$topbarlightdark = "";
$topbarlight = "";
$darkClass = "";
$searchbardark = "";
$betubeStickyClass = "";
$betubeMainLOGO = $redux_demo['betube-logo']['url'];
$betubeFB = $redux_demo['facebook-link'];
$beTubeTW = $redux_demo['twitter-link'];
$beTubeGPlus = $redux_demo['google-plus-link'];
$beTubePin = $redux_demo['pinterest-link'];
$beTubeLinkedin = $redux_demo['linkedin-link'];
$beTubeInsta = $redux_demo['instagram-link'];
$beTubeYT = $redux_demo['youtube-link'];
$beTubeVi = $redux_demo['vimeo-link'];
$beTubeDirbb = $redux_demo['dribbble-link'];
$beTubeFlic = $redux_demo['flickr-link'];
$beTubeGith = $redux_demo['github-link'];

$beTubeNewsCats = $redux_demo['betube_news_categories'];
$beTubeNewsCount = $redux_demo['betube_news_count'];
$beTubeNewsSortOrder = $redux_demo['betube_news_sort_order'];

$beTubeUploadVideo = $redux_demo['new_post'];
$beTubeRegister = $redux_demo['register'];
$beTubeLogin = $redux_demo['login'];
$beTubeProfile = $redux_demo['profile'];
$beTubeHeaderStyle = "";
$beTubeHeaderStyle = $redux_demo['betube-header-style'];
$beTubeSticky = $redux_demo['nav-sticky'];
if ($beTubeSticky == 1) {
    $betubeStickyClass = "sticky";
}
if (empty($beTubeHeaderStyle)) {
    $beTubeHeaderStyle = "v1";
}
if ($beTubeHeaderStyle == "v4") {
    $divClass = "topnav-dark";
    $divClass2 = "topbar-dark";
}
if ($beTubeHeaderStyle == "v2" || $beTubeHeaderStyle == "v5" || $beTubeHeaderStyle == "v6" || $beTubeHeaderStyle == "v7") {
    $topBarBlack = "topBarBlack";
}
if ($beTubeHeaderStyle == "v6") {
    $navFull = "navFull";
}
if ($beTubeHeaderStyle == "v7" || $beTubeHeaderStyle == "v8") {
    $navFullv2 = "navFull-v2";
}

if ($beTubeHeaderStyle == "v5" || $beTubeHeaderStyle == "v7") {
    $searchbardark = "search-bar-dark";
}
if ($beTubeHeaderStyle == "v5" || $beTubeHeaderStyle == "v6" || $beTubeHeaderStyle == "v7") {
    $navBlack = "navBlack";
    $background = "#444";
    $titlebardark = "title-bar-dark";
    $buttontoggle = "offCanvas";
    $topbarlightdark = "topbar-light-dark";
} else {
    $buttontoggle = "offCanvas-responsive";
    $background = "#fff";
}
if ($beTubeHeaderStyle == "v8") {
    $navfullv2light = "navfull-v2-light";
    $background = "#f6f6f6";
    $buttontoggle = "offCanvas";
    $topbarlight = "top-bar-light";
}
?>
<section id="top" class="topBar show-for-large <?php echo $topBarBlack; ?>">
    <div class="row">
        <div class="medium-6 columns">
            <div class="socialLinks">

                <?php if (!empty($betubeFB)) { ?>
                    <a href="<?php echo $betubeFB; ?>" target="_blank"><i class="fa fa-facebook"></i></a>
                <?php } ?>

                <?php if (!empty($beTubeTW)) { ?>
                    <a href="<?php echo $beTubeTW; ?>" target="_blank"><i class="fa fa-twitter"></i></a>
                <?php } ?>

                <?php if (!empty($beTubeGPlus)) { ?>
                    <a href="<?php echo $beTubeGPlus; ?>" target="_blank"><i class="fa fa-google-plus"></i></a>
                <?php } ?>

                <?php if (!empty($beTubePin)) { ?>
                    <a href="<?php echo $beTubePin; ?>" target="_blank"><i class="fa fa-pinterest-p"></i></a>
                <?php } ?>

                <?php if (!empty($beTubeLinkedin)) { ?>
                    <a href="<?php echo $beTubeLinkedin; ?>" target="_blank"><i class="fa fa-linkedin"></i></a>
                <?php } ?>

                <?php if (!empty($beTubeInsta)) { ?>
                    <a href="<?php echo $beTubeInsta; ?>" target="_blank"><i class="fa fa-instagram"></i></a>
                <?php } ?>

                <?php if (!empty($beTubeYT)) { ?>
                    <a href="<?php echo $beTubeYT; ?>" target="_blank"><i class="fa fa-youtube"></i></a>
                <?php } ?>

                <?php if (!empty($beTubeVi)) { ?>
                    <a href="<?php echo $beTubeVi; ?>" target="_blank"><i class="fa fa-vimeo-square"></i></a>
                <?php } ?>

                <?php if (!empty($beTubeDirbb)) { ?>
                    <a href="<?php echo $beTubeDirbb; ?>" target="_blank"><i class="fa fa-dribbble"></i></a>
                <?php } ?>

                <?php if (!empty($beTubeFlic)) { ?>
                    <a href="<?php echo $beTubeFlic; ?>" target="_blank"><i class="fa fa-flickr"></i></a>
                <?php } ?>

                <?php if (!empty($beTubeGith)) { ?>
                    <a href="<?php echo $beTubeGith; ?>" target="_blank"><i class="fa fa-github"></i></a>
                <?php } ?>
            </div>
        </div><!--End social Medium6-->
        <div class="medium-6 columns">
            <div class="top-button">
                <ul class="menu float-right">
                    <?php if (!empty($beTubeUploadVideo)) { ?>
                        <li>
                            <a href="<?php echo $beTubeUploadVideo; ?>"><?php esc_html_e('Upload Flipbook', 'betube'); ?></a>
                        </li>
                    <?php } ?>			
                    <?php if (!is_user_logged_in()) { ?>
                        <li class="dropdown-login">
                            <a class="loginReg" data-toggle="example-dropdown" href="#"><?php esc_html_e('Login/Register', 'betube'); ?></a>						
                            <div class="login-form">						
                                <h6 class="text-center"><?php esc_html_e('Great to have you back!', 'betube'); ?></h6>
                                <p class="status"></p>
                                <form method="post" action="login" id="login" enctype="multipart/form-data" data-abide novalidate>
                                    <div class="input-group">
                                        <span class="input-group-label"><i class="fa fa-user"></i></span>
                                        <input class="input-group-field" type="text" id="username" name="username" placeholder="<?php esc_html_e('Enter username', 'betube') ?>" required>
                                        <span class="form-error"><?php esc_html_e('username is required', 'betube') ?></span>
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-label"><i class="fa fa-lock"></i></span>
                                        <input class="input-group-field" type="password" id="password" name="password" placeholder="<?php esc_html_e('Enter Password', 'betube') ?>" required>
                                        <span class="form-error"><?php esc_html_e('Password is required', 'betube') ?></span>
                                    </div>
                                    <div class="checkbox">
                                        <input id="remember" type="checkbox" name="rememberme">
                                        <label class="customLabel" for="remember"><?php esc_html_e('Remember me', 'betube'); ?></label>
                                    </div>
                                    <?php /** <div class="checkbox">
                                      <input id="termsconditions" type="checkbox" name="termsconditions" required>
                                      <label class="customLabel termslable" for="termsconditions">I agree to the</label> <a class="termslink" href="<?php echo get_page_link(52); ?>">Terms and Conditions</a>
                                      <!--                                                <span class="error-message" id="error_terms"></span>-->
                                      <span class="error-message" id="error_terms" style="display: none;">Please agree to terms and conditions.</span>
                                      </div> */ ?>
                                    <input type="hidden" id="submitbtn" name="submit" value="Login" />
                                    <input type="submit" name="submit" value="<?php esc_html_e('Login Now', 'betube'); ?>">
                                    <?php /** <button class="btn-login" type="submit" onClick="return ValidateFormlogin(this.form)" name="op" value="Login"><?php esc_html_e('LOGIN NOW', 'betube') ?></button> */ ?>
                                    <?php wp_nonce_field('ajax-login-nonce', 'security'); ?>
                                </form>
                                <?php if (!empty($beTubeRegister)) { ?>							
                                    <p class="text-center">
                                        <?php esc_html_e('New here?', 'betube'); ?>&nbsp;
                                        <a class="newaccount" href="<?php echo $beTubeRegister; ?>"><?php esc_html_e('Create a new Account', 'betube'); ?></a>
                                    </p>

                                <?php } ?>
                                    <?php
                                    global $redux_demo;
                                    $reset = $redux_demo['reset'];
                                    ?>
                                    <p class="text-center forgotlink">
                                        <?php esc_html_e('Forgot Password?', 'betube'); ?>&nbsp;
                                        <a class="newaccount" href="<?php echo $reset; ?>"><?php esc_html_e('Click here', 'betube'); ?></a>
                                    </p>
                            </div>						
                        </li>
                    <?php } else { ?>
                        <li class="dropdown-login">
                            <a class="loginReg" data-toggle="example-dropdown" href="#"><?php esc_html_e('My Account', 'betube'); ?></a>
                            <div class="login-form">						<p class="text-center topProfilebtn"><a class="button expanded" href="<?php echo $beTubeProfile; ?>"><?php esc_html_e('Visit Profile', 'betube'); ?></a></p>
                                <p class="text-center topProfilebtn">
                                    <a class="button expanded" href="<?php echo wp_logout_url(get_option('siteurl')); ?>">
                                        <i class="fa fa-sign-out"></i><?php esc_html_e("Logout", 'betube') ?>
                                    </a>
                                </p>
                            </div>
                        </li><!--Profile Button-->
                    <?php } ?>
                </ul><!--End menu float-right-->
            </div><!--End top-button-->
        </div><!--End Medium6-->		
    </div><!--End Row-->
</section><!--End Top Section-->

<section id="navBar">   
    <nav class="sticky-container <?php echo $navBlack; ?> <?php echo $navFull; ?> <?php echo $navFullv2; ?> <?php echo $navfullv2light; ?>" data-sticky-container>
        <div class="<?php echo $betubeStickyClass; ?> topnav <?php echo $divClass; ?>" data-sticky data-top-anchor="navBar" data-btm-anchor="footer-bottom:bottom" data-margin-top="0" data-margin-bottom="0" style="width: 100%; background: <?php echo $background; ?>;" data-sticky-on="large">
            <div class="row <?php echo $beTubeHeaderStyle; ?>">
                <div class="large-12 columns">
                    <div class="title-bar <?php echo $titlebardark; ?>" data-responsive-toggle="beNav" data-hide-for="large">
                        <button class="menu-icon" type="button" data-toggle="<?php echo $buttontoggle; ?>"></button>
                        <div class="title-bar-title">
                            <?php if (!empty($betubeMainLOGO)) { ?>
                                <img src="<?php echo esc_url($betubeMainLOGO); ?>" alt="<?php bloginfo('name'); ?>" />
                            <?php } else { ?>
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo-small.png" alt="<?php bloginfo('name'); ?>" />
                            <?php } ?>	
                        </div>
                    </div>

                    <div class="top-bar show-for-large <?php echo $topbarlightdark; ?> <?php echo $topbarlight; ?>" id="beNav" style="width: 100%;">
                        <div class="top-bar-right search-btn">
                            <ul class="menu">
                                <li class="betubeSearch">
                                    <i class="fa fa-search"></i>
                                </li>
                            </ul>
                        </div>
                        <div class="top-bar-left">
                            <ul class="menu">
                                <li class="menu-text">
                                    <a href="<?php echo esc_url(home_url('/')); ?>">
                                        <?php if (!empty($betubeMainLOGO)) { ?>
                                            <img src="<?php echo esc_url($betubeMainLOGO); ?>" alt="<?php bloginfo('name'); ?>" />
                                        <?php } else { ?>
                                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.png" alt="<?php bloginfo('name'); ?>" />
                                        <?php } ?>
                                    </a>								
                                </li>
                            </ul>
                        </div>
                        <div class="top-bar-right">
                            <?php betube_top_nav(); ?>
                        </div>

                    </div><!--top-bar-->
                </div><!--large12-->
            </div>
            <div id="betube-bar" class="clearfix search-bar-light <?php echo $searchbardark; ?>">
                <form role="search" method="get" class="search-form" action="<?php echo home_url('/'); ?>">
                    <div class="search-input float-left">
                        <input class="input-group-field search-field" type="search" placeholder="<?php echo esc_attr_x('Search...', '', 'betube') ?>" value="<?php echo get_search_query() ?>" name="s" title="<?php echo esc_attr_x('Search for:', '', 'betube') ?>">
                    </div>
                    <div class="search-btn float-right text-right">						
                        <button class="button" type="submit" name="search" value="<?php echo esc_attr_x('Search Now', '', 'betube') ?>"><?php esc_html_e('Search Now', 'betube'); ?></button>						
                    </div>
                </form>
            </div>
        </div>
    </nav>
</section>

