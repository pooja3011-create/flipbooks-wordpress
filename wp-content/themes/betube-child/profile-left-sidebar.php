<aside class="secBg sidebar">
    <div class="row">
        <!-- profile overview -->
        <div class="large-12 columns">
            <div class="widgetBox">
                <div class="widgetTitle">
                    <h5><?php esc_html_e("Profile Overview", 'betube') ?></h5>
                </div>
                <div class="widgetContent">
                    <?php
                    global $redux_demo;
                    $betubeProfile = $redux_demo['profile'];
                    $betubeVideoSingleUser = $redux_demo['all-video-single-user'];
                    $betubeFavourite = $redux_demo['all-favourite'];
                    $beTubeallFollowers = $redux_demo['all-followers'];
                    $beTubeAddPost = $redux_demo['new_post'];
                    $beTubefollowers = $redux_demo['all-followers'];
                    $beTubeEditProfile = $redux_demo['edit-profile'];
                    ?>
                    <ul class="profile-overview">
                        <li class="clearfix">
                            <a class="" href="<?php echo $betubeProfile; ?>">
                                <i class="flaticon-man-user"></i><?php esc_html_e("About Me", 'betube') ?>
                            </a>
                        </li><!--AboutMe-->
                        <li class="clearfix">
                            <a href="<?php echo $betubeVideoSingleUser; ?>">
                                <i class="flaticon-open-book"></i><?php esc_html_e("Flipbooks", 'betube') ?> 
                                <span class="float-right"><?php echo custom_get_user_posts_count($user_ID, array(
                        'post_type' => 'post', 'post_status' => array('draft', 'publish'))); ?></span>
                            </a>
                        </li><!--Videos-->
                        <li class="clearfix">
                            <a href="<?php echo $betubeFavourite; ?>">
                                <i class="flaticon-like"></i><?php esc_html_e("Favorites", 'betube') ?>
                                <span class="float-right">
                                    <?php
                                    global $current_user;
                                    wp_get_current_user();
                                    $user_id = $current_user->ID;
                                    echo $totalfavorite = betubeFavoriteCount($user_id);
                                    ?>
                                </span>
                            </a>
                        </li><!--Favorite Videos-->
                        <li class="clearfix">
                            <a class="" href="<?php echo $beTubeallFollowers; ?>">
                                <i class="flaticon-following"></i><?php esc_html_e("Followers", 'betube') ?>
                                <span class="float-right">
                                    
                                    <?php
                                    global $wpdb;
                                    $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}author_followers WHERE author_id = $user_id", OBJECT);
                                    echo $followcounter = count($results);
                                    ?>
                                </span>
                            </a>
                        </li><!--Followers-->
                        <li class="clearfix">
                            <a class="" href="<?php echo get_page_link(1156); ?>">
                                <i class="flaticon-follower"></i><?php esc_html_e("Following", 'betube') ?>
                                <span class="float-right">
                                    <?php echo betubeFollowingCount($user_id); ?>
                                </span>
                            </a>
                        </li><!--Following-->
                        <li class="clearfix">
                            <a href="<?php echo get_page_link(871); ?>">
                                <i class="flaticon-newsletter"></i><?php esc_html_e("Newsletter Subscription", 'betube') ?>
                                <span class="float-right"><?php echo get_newsletter_count($user_info->data->user_email); ?></span>
                            </a>
                        </li>

                        <li class="clearfix">
                            <a href="<?php echo $beTubeEditProfile; ?>">
                                <i class="flaticon-user"></i><?php esc_html_e("Profile Settings", 'betube') ?>
                            </a>
                        </li><!--Profile Settings-->
                        <li class="clearfix">
                            <a href="<?php echo get_page_link(934); ?>">
                                <i class="flaticon-shopping"></i><?php esc_html_e("Pricing Plans", 'betube') ?>
                            </a>
                        </li><!--order Settings-->
                        <li class="clearfix">
                            <a href="<?php echo wp_logout_url(get_option('siteurl')); ?>">
                                <i class="flaticon-logout"></i><?php esc_html_e("Logout", 'betube') ?>
                            </a>
                        </li><!--Logout-->
<?php if (!empty($beTubeAddPost)) { ?>
                            <a href="<?php echo $beTubeAddPost; ?>" class="button">
                                <i class="fa fa-plus-circle"></i><?php esc_html_e("Upload Flipbook", 'betube') ?>
                            </a><!--Submit Video-->
<?php } ?>
                    </ul><!--UL-->
                </div><!--widgetContent -->
            </div><!--widgetBox -->
        </div><!--Large12 -->
    </div><!--row-->
</aside><!--aside-->
<!--code for add class active onclick tab-->
<script>
    jQuery(function () {
        var pgurl = window.location.href.substr(window.location.href);
        // alert(pgurl);
        jQuery("ul.profile-overview li a").each(function () {
            //alert(jQuery(this).attr("href"));
            if (jQuery(this).attr("href") == pgurl || jQuery(this).attr("href") == '')
                jQuery(this).addClass("active");
        })
    });
</script>

