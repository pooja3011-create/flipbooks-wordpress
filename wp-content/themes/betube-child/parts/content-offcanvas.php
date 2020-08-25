<?php
global $redux_demo;
$darkClass = "";
$myheaderID = "";
$beTubeHeaderStyle = $redux_demo['betube-header-style'];
if ($beTubeHeaderStyle == "v3" || $beTubeHeaderStyle == "v4" || $beTubeHeaderStyle == "v5" || $beTubeHeaderStyle == "v6" || $beTubeHeaderStyle == "v7" || $beTubeHeaderStyle == "v8") {
    $myheaderID = "offCanvas";
} elseif ($beTubeHeaderStyle == "v1" || $beTubeHeaderStyle == "v2") {
    $myheaderID = "offCanvas-responsive";
}
if ($beTubeHeaderStyle == "v4" || $beTubeHeaderStyle == "v5" || $beTubeHeaderStyle == "v6" || $beTubeHeaderStyle == "v7") {
    $darkClass = "dark-off-menu";
}
$betubeFBLink = $redux_demo['facebook-link'];
$betubeTwitterLink = $redux_demo['twitter-link'];
$betubeDribbbleLink = $redux_demo['dribbble-link'];
$betubeFlickerLink = $redux_demo['flickr-link'];
$betubeGithubLink = $redux_demo['github-link'];
$betubePinLink = $redux_demo['pinterest-link'];
$betubeYoutubeLink = $redux_demo['youtube-link'];
$betubeGoogleLink = $redux_demo['google-plus-link'];
$betubeLinkedinLink = $redux_demo['linkedin-link'];
$betubeInstaLink = $redux_demo['instagram-link'];
$betubeVimeoLink = $redux_demo['vimeo-link'];

$beTubeUploadVideo = $redux_demo['new_post'];
$beTubeRegister = $redux_demo['register'];
$beTubeProfile = $redux_demo['profile'];
?>
<div class="off-canvas position-left light-off-menu <?php echo $darkClass; ?>" id="<?php echo $myheaderID; ?>" data-off-canvas>
    <div class="off-menu-close">
        <h3><?php esc_html_e("Menu", 'betube') ?></h3>
        <span data-toggle="<?php echo $myheaderID; ?>"><i class="fa fa-times"></i></span>
    </div><!--off-menu-close-->	

    <?php betube_off_canvas_nav(); ?>
    <div class="responsive-search">
        <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
            <div class="input-group">
                <input class="input-group-field search-field" type="search" placeholder="<?php echo esc_attr_x('Search...', '', 'betube') ?>" value="<?php echo get_search_query() ?>" name="s" title="<?php echo esc_attr_x('Search for:', '', 'betube') ?>">
                <div class="input-group-button">
                    <button type="submit" name="search" value="<?php echo esc_attr_x('Search', '', 'betube') ?>"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>		
    </div><!--responsive-search-->	
    <div class="off-social">
        <h6><?php esc_html_e("Get Socialize", 'betube') ?></h6>
        <?php if (!empty($betubeFBLink)) { ?>
            <a href="<?php echo $betubeFBLink; ?>"><i class="fa fa-facebook"></i></a>
        <?php } ?>

        <?php if (!empty($betubeTwitterLink)) { ?>
            <a href="<?php echo $betubeTwitterLink; ?>"><i class="fa fa-twitter"></i></a>
        <?php } ?>

        <?php if (!empty($betubeDribbbleLink)) { ?>
            <a href="<?php echo $betubeDribbbleLink; ?>"><i class="fa fa-dribbble"></i></a>
        <?php } ?>

        <?php if (!empty($betubeFlickerLink)) { ?>
            <a href="<?php echo $betubeFlickerLink; ?>"><i class="fa fa-flickr"></i></a>
        <?php } ?>

        <?php if (!empty($betubeGithubLink)) { ?>
            <a href="<?php echo $betubeGithubLink; ?>"><i class="fa fa-github"></i></a>
        <?php } ?>

        <?php if (!empty($betubePinLink)) { ?>
            <a href="<?php echo $betubePinLink; ?>"><i class="fa fa-pinterest-p"></i></a>
        <?php } ?>

        <?php if (!empty($betubeYoutubeLink)) { ?>
            <a href="<?php echo $betubeYoutubeLink; ?>"><i class="fa fa-youtube"></i></a>
        <?php } ?>

        <?php if (!empty($betubeGoogleLink)) { ?>
            <a href="<?php echo $betubeGoogleLink; ?>"><i class="fa fa-google-plus"></i></a>
        <?php } ?>

        <?php if (!empty($betubeLinkedinLink)) { ?>
            <a href="<?php echo $betubeLinkedinLink; ?>"><i class="fa fa-linkedin"></i></a>
        <?php } ?>

        <?php if (!empty($betubeInstaLink)) { ?>
            <a href="<?php echo $betubeInstaLink; ?>"><i class="fa fa-instagram"></i></a>
        <?php } ?>

        <?php if (!empty($betubeVimeoLink)) { ?>
            <a href="<?php echo $betubeVimeoLink; ?>"><i class="fa fa-vimeo"></i></a>
        <?php } ?>	

    </div><!--Get Socialize-->
    <?php
    global $redux_demo;
    $login = $redux_demo['login'];
    ?>
    <div class="top-button mobile-menu" >
        <ul class="menu">
            <li>
                <a href="<?php echo $beTubeUploadVideo; ?>">Upload Flipbook</a>
            </li>
            <?php
            if (!is_user_logged_in()) {
                ?>
                <li class="dropdown-login" style="float:left;">
                    <a href="<?php echo $login; ?>">login/Register</a>
                </li>
                <?php
            } else {
                ?>
                <li class="dropdown-login">
                    <a href="<?php echo $beTubeProfile; ?>">Visit Profile</a>
                </li>
                <li class="dropdown-login">
                    <a href="<?php echo wp_logout_url(get_option('siteurl')); ?>">Logout</a>
                </li>
                <?php
            }
            ?>

        </ul>
    </div>
</div>
<!--Only for Header V3 and V4-->
<style>
    .mobile-menu{
        padding: 0px 60px;
    }
    .mobile-menu ul li{
        float: left;
        margin-bottom: 10px;
        width: 100%;
        text-align: center;
    }
    .mobile-menu ul li a{ width: 100%; }
    
</style>