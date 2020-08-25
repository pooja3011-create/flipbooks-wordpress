<?php
//chceckadscapability();
if (isset($_GET['like_flipbook']) && $_GET['like_flipbook'] != '') {
    global $post;
    $count = 1;
    $currentUserID = get_current_user_id();
    $postMeta = get_post_meta($post->ID, '_post_like_count');
    if (!empty($postMeta)) {
        $count = $postMeta[0] + 1;
    }
    $likeUser = get_post_meta($post->ID, '_user_post_liked');

    if (!empty($likeUser) && !empty($likeUser[0])) {
        $likeUser = $likeUser[0] . ',' . $currentUserID;
    } else {
        $likeUser = $currentUserID;
    }
    update_post_meta($post->ID, '_post_like_count', $count);
    update_post_meta($post->ID, '_user_post_liked', $likeUser);
    $data = array('like' => $count);
    echo json_encode($count);
    exit;
}
get_header();
betube_breadcrumbs();
global $post;

global $redux_demo;
$betubeSingleVideoLayout = $redux_demo['betube-single-video-layout'];
$betubeRelatedVideoCount = $redux_demo['betube_related_video_count'];
$betubeMultiPlayer = $redux_demo['betube-multi-player'];
$betubePluginAdv = $redux_demo['betube-plugin-adv'];
$betubeSocialSharebtn = $redux_demo['betube_social_share_btn'];
if (isset($_POST['favorite'])) {
    $author_id = $_POST['author_id'];
    $post_id = $_POST['post_id'];
    echo betube_favorite_insert($author_id, $post_id);
}
if (isset($_POST['unfavorite'])) {
    $author_id = $_POST['author_id'];
    $post_id = $_POST['post_id'];
    echo betube_authors_unfavorite($author_id, $post_id);
}
if (isset($_POST['follower'])) {
    $author_id = $_POST['author_id'];
    $follower_id = $_POST['follower_id'];
//    echo betube_authors_insert($author_id, $follower_id);
    global $wpdb;
    $author_insert = ("INSERT into {$wpdb->prefix}author_followers (author_id,follower_id)value('" . $author_id . "','" . $follower_id . "')");
    $wpdb->query($author_insert);
}
if (isset($_POST['unfollow'])) {
    $author_id = $_POST['author_id'];
    $follower_id = $_POST['follower_id'];
    $author_del = ("DELETE from {$wpdb->prefix}author_followers WHERE author_id = $author_id AND follower_id = $follower_id ");
    //print_r($author_del);

    $wpdb->query($author_del);
    //echo betube_authors_unfollow($author_id, $follower_id);
}

$betubePostFormat = get_post_format();
$flipbook_id = $post->flipbook_id != '' ? $post->flipbook_id : '1';

$media_image = get_attached_media('image', $post->ID);
$media_pdf = get_attached_media('application/pdf', $post->ID);
$no_items = 0;
if (count($media_image) == 0 && count($media_pdf) == 0) {
    $no_items = 1;
}
?>
<section class="fullwidth-single-video inner-video"> 
    <div class="row">
        <div class="large-12 columns">
            <?php
            if ($no_items) {
                echo "<b>Nothing found in flipbook.</b>";
            } else {
                echo do_shortcode('[real3dflipbook id="' . $flipbook_id . '"]');
            }
            ?>			 
        </div><!--End large12-->
    </div><!--End row-->
</section><!--End Section-->
<div class="row">
    <!-- Display Google Ads -->
    <div class="large-8 columns large-centered adsflipbook">
        <?php
        $author_id = $post->post_author;
        adserve(1, $author_id);
        ?>
    </div>
    <!-- End Display Google Ads --> 
    <div class="large-8 columns">
        <!-- single post stats -->
        <section class="SinglePostStats">
            <div class="row secBg">
                <div class="large-12 columns">
                    <div class="media-object stack-for-small">
                        <div class="media-object-section" style="text-align: center;">
                            <div class="author-img-sec">
                                <div class="thumbnail author-single-post">
                                    <?php
                                    $user_ID = $post->post_author;
                                    $authorAvatarURL = get_user_meta($user_ID, "betube_author_avatar_url", true);
                                    
                                    $authoremail = get_the_author_meta('user_email', $user_ID);
                                    if (!empty($authorAvatarURL)) {
                                        ?>
                                        <img src="<?php echo esc_url($authorAvatarURL); ?>" alt="author">
                                        <?php
                                    } else {
                                        $authorID = get_the_author_meta('user_email', $user_ID);
                                        $avatar_url = betube_get_avatar_url($authorID, $size = '150');
                                        ?>
                                        <img src="<?php echo esc_url($avatar_url); ?>" alt="profile author img">
                                        <?php
                                    }
                                    ?>									
                                </div><!--thumbnail-->
                                <p class="text-center" style="text-align: center;">
                                    <a href="<?php echo get_author_posts_url(get_the_author_meta('ID', $user_ID)); ?>"><?php echo $betubeDisplayName = get_the_author_meta('display_name', $user_ID); ?></a>
                                </p>
                                <?php
                                if (is_user_logged_in()) {
                                    global $current_user;
                                    wp_get_current_user();
                                    $user_id = $current_user->ID;
                                    if (isset($user_id)) {
                                        if ($user_ID != $user_id) {
                                            ?>
                                            <div class="author-follower" style="margin-top: 10px;">
                                                <?php
                                                //echo betube_authors_follower_check($user_ID, $user_id);
                                                global $wpdb;
                                                $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}author_followers WHERE follower_id = $user_id AND author_id = $author_id", OBJECT);
                                                if (empty($results)) {
                                                    ?>
                                                    <form method="post">

                                                        <input type="hidden" name="author_id" value="<?php echo $author_id; ?>"/>

                                                        <input type="hidden" name="follower_id" value="<?php echo $user_id; ?>"/>

                                                        <button type="submit" name="follower" value="Follow"><?php esc_html_e("Follow", 'betube') ?></button>
                                                    </form>
                                                    <div class="clearfix"></div>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <form method="post">

                                                        <input type="hidden" name="author_id" value="<?php echo $author_id; ?>"/>

                                                        <input type="hidden" name="follower_id" value="<?php echo $user_id; ?>"/>

                                                        <button type="submit" name="unfollow" value="Unfollow"><?php esc_html_e("Unfollow", 'betube') ?></button>

                                                    </form>

                                                    <div class="clearfix"></div>
                                                    <?php
                                                }
                                                ?>
                                            </div><!--subscribe--><?php
                                            }
                                        }
                                    }
                                    ?>
                            </div><!--author-img-sec-->
                        </div><!--media-object-section-->
                        <div class="media-object-section object-second">
                            <div class="author-des clearfix">
                                <div class="post-title">
                                    <h4><?php the_title(); ?></h4>
                                    <p>
                                        <span>
<?php $beTubedateFormat = get_option('date_format'); ?>
                                            <?php
                                            global $post;
                                            $post_id = $post->ID;
                                            ?>
                                            <i class="fa fa-clock-o"></i><?php echo get_the_date($beTubedateFormat, $post_id); ?>
                                        </span>
                                        <span>
                                            <i class="fa fa-eye"></i><?php echo betube_get_post_views(get_the_ID()); ?>
                                        </span>

<?php
$likeCount = 0;
$postMeta = get_post_meta($post->ID, '_post_like_count');
if (!empty($postMeta)) {
    $likeCount = $postMeta[0];
}
?>
                                        <span style="margin-right: 0px;"><i class="fa fa-thumbs-o-up"></i><span><?php echo ($likeCount != '') ? $likeCount : '0'; ?></span></span>                                      
                                        <span>
                                            <i class="fa fa-commenting"></i><?php echo comments_number(); ?>
                                        </span>
                                    </p>
                                </div><!--post-title-->                                
                            </div><!--author-des-->
                            <div class="social-share">
                                <div class="post-like-btn clearfix">
                                    <!--code for embed code genrate-->
                                    <form>
                                        <button type="button" class="embedcode" name="embed"><?php esc_html_e('Embed', 'betube'); ?></button>
                                    </form>
                                    <?php
                                    if (is_user_logged_in()) {
                                        ?>      
                                        <!-- Trigger the modal with a button -->
                                        <button class="commentpopup" type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#contentpopup"><?php esc_html_e("Report Content", 'betube') ?>
                                        </button>
                                <?php } else { ?>

                                        <div class="commentpopup unclickable" style="display: none;">
                                            <a class="thickbox video-btn">
    <?php esc_html_e("Report Content", 'betube') ?>
                                            </a>
                                        </div>
<?php } ?>
                                    <?php
                                    if (is_user_logged_in()) {
                                        $postID = $post->ID;
                                        $currentUserID = get_current_user_id();
                                        global $wpdb;
                                        // echo  "SELECT author_id,post_id FROM $wpdb->author_favorite";
                                        $favRes = $wpdb->get_results("SELECT author_id,post_id FROM {$wpdb->prefix}author_favorite WHERE (author_id = '" . $currentUserID . "' AND post_id = '" . $postID . "')");
                                        if (!empty($favRes)) {
                                            ?>
                                            <form class="unfavorite" method="post">
                                                <input type="hidden" value="<?php echo $user_id; ?>" name="author_id">
                                                <input type="hidden" value="<?php echo $postID; ?>" name="post_id">
                                                <button value="unfavorite" name="unfavorite" type="submit"><i class="fa fa-heart-o"></i>Unfavorite</button>			
                                            </form>
        <?php
    } else {
        ?>
                                            <form method="post">
                                                <button type="submit" name="favorite"><i class="fa fa-heart"></i><?php esc_html_e('Add to', 'betube'); ?></button>
                                                <input type="hidden" name="author_id" value="<?php echo $user_id; ?>"/>
                                                <input type="hidden" name="post_id" value="<?php echo $postID; ?>"/>
                                            </form>
        <?php
    }
}
?>   

                                    <?php
                                    if (is_user_logged_in()) {
                                        $likeUserArr = array();
                                        $postMeta = get_post_meta($post->ID, '_post_like_count');
                                        $likeCount = 0;
                                        if (!empty($postMeta)) {
                                            $likeUser = get_post_meta($post->ID, '_user_post_liked');
                                            if (!empty($likeUser) && isset($likeUser[0]) && !empty($likeUser[0])) {
                                                $likeUserArr = explode(',', $likeUser[0]);
                                            }
                                            $likeCount = $postMeta[0];
                                        }

                                        $flipLike = '';
                                        if (!in_array($currentUserID, $likeUserArr)) {
                                            $flipLike = "likeFlipbook('" . $post_id . "')";
                                        }
                                        ?>
                                        <span class="sl-wrapper">
                                            <a title="Like" class="sl-button" href="javascript:;" onclick="<?php echo $flipLike; ?>">
                                                <i class="fa fa-thumbs-o-up"></i>
                                                <span class="sl-count"><?php echo ($likeCount != '') ? $likeCount : '0'; ?></span>
                                            </a>
                                            <span class="sl-loader"></span> 
                                        </span>
    <?php
}

if ($betubeSocialSharebtn == 1) {
    $easyShare = "";
    /* Detect plugin. For use on Front End only. */
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    // check for plugin using plugin name
    if (is_plugin_active("betube-helper/index.php")) {
        $easyShare = plugin_dir_url('betube-helper/index.php') . 'api/easyshare.php';
    }
    ?>
                                        <div id="sharePath" data-share="<?php echo $easyShare; ?>"></div>

<?php } ?>
                                </div><!--post-like-btn-->  
                            </div><!--social-share-->
                            <!--code for embed code genrate-->
                            <div id="embed-bar" style="display:none;" class="clearfix search-bar-light <?php echo $searchbardark; ?>">
<?php
$pageLink = get_page_link(1042);
if (parse_url($pageLink, PHP_URL_QUERY)) {
    $url = $pageLink . '&flipbook_id=' . $flipbook_id;
} else {
    $url = $pageLink . '?flipbook_id=' . $flipbook_id;
}
?>
                                <input id="copyTarget" class="embedcodecls" readonly value='<iframe width="640" height="360" src="<?php echo $url; ?>" border="0" allowfullscreen></iframe>'>
                                <button id="copyButton">Copy to Clipboard</button>
                            </div>
                        </div><!--media-object-section object-second-->
                    </div><!--media-object-->
                </div><!--large-12-->
            </div><!--row secBg-->
        </section><!--End SinglePostStats-->

        <section class="singlePostDescription">
            <div class="row secBg">
                <div class="large-12 columns">
                    <div class="heading">
                        <h5><?php esc_html_e('Description', 'betube'); ?></h5>
                    </div><!--heading-->
                    <!--<div class="description showmore_one">-->
                    <div class="description">
<?php echo apply_filters('the_content', $post->post_content); ?>
                        <div class="categories">
                            <button><i class="flaticon-2-squares"></i><?php esc_html_e('Categories', 'betube'); ?></button>
<?php
$betubeSingleCat = get_the_category();
if ($betubeSingleCat) {
    ?>
                                <a href="<?php echo get_category_link($betubeSingleCat[0]->term_id); ?>" class="inner-btn"><?php echo $betubeSingleCat[0]->name; ?></a>
                            <?php } ?>							
                        </div><!--categories-->
                        <div class="tags">
                            <button><i class="fa fa-tags"></i><?php esc_html_e('Tags', 'betube'); ?></button>
<?php
$before = "";
$sep = "&nbsp;";
$after = "";
?>
                            <?php the_tags($before, $sep, $after); ?> 							
                        </div><!--tags-->
                    </div><!--description showmore_one-->
                </div><!--large-12-->
                <!--Paginatation-->
                <div class="large-12 columns">
<?php
wp_link_pages(array(
    'before' => '<div class="pagination"><span class="page-links-title">' . esc_html__('Pages:', 'betube') . '</span>',
    'after' => '</div>',
    'link_before' => '<span class="page-numbers">',
    'link_after' => '</span>',
));
?>
                </div>
                <!--Paginatation-->
            </div><!--row secBg-->
        </section><!--End singlePostDescription-->
    <?php
     $flipbookCommentStatus = get_post_meta($post->ID, 'enable_flipbook_comment', true);
     if($flipbookCommentStatus == 'yes'){
     ?>
    <section class="content comments">
        <div class="row secBg">
    <?php
    $file = '';
    $separate_comments = '';
    comments_template($file, $separate_comments);
    ?>				
        </div><!--row secBg-->
    </section><!--End Comments Area-->
     <?php } ?>
    </div>
    <div class="large-4 columns">  
        <aside class="secBg sidebar">
            <div class="row">
<?php echo get_sidebar('single-video'); ?>
            </div>
        </aside>
    </div>
</div>
<script>
// copy Embed Code Button
    document.getElementById("copyButton").addEventListener("click", function () {
        copyToClipboard(document.getElementById("copyTarget"));
    });

    function copyToClipboard(elem) {
        // create hidden text element, if it doesn't already exist
        var targetId = "_hiddenCopyText_";
        var isInput = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA";
        var origSelectionStart, origSelectionEnd;
        if (isInput) {
            // can just use the original source element for the selection and copy
            target = elem;
            origSelectionStart = elem.selectionStart;
            origSelectionEnd = elem.selectionEnd;
        } else {
            // must use a temporary form element for the selection and copy
            target = document.getElementById(targetId);
            if (!target) {
                var target = document.createElement("textarea");
                target.style.position = "absolute";
                target.style.left = "-9999px";
                target.style.top = "0";
                target.id = targetId;
                document.body.appendChild(target);
            }
            target.textContent = elem.textContent;
        }
        // select the content
        var currentFocus = document.activeElement;
        target.focus();
        target.setSelectionRange(0, target.value.length);

        // copy the selection
        var succeed;
        try {
            succeed = document.execCommand("copy");
        } catch (e) {
            succeed = false;
        }
        // restore original focus
        if (currentFocus && typeof currentFocus.focus === "function") {
            currentFocus.focus();
        }

        if (isInput) {
            // restore prior selection
            elem.setSelectionRange(origSelectionStart, origSelectionEnd);
        } else {
            // clear temporary content
            target.textContent = "";
        }
        return succeed;
    }
</script>
<script>
    var myVar;
    function myFunction() {
        window.setInterval(function () {
            if (jQuery(".social ul .fa-linkedin").length > 0) {
                myStopFunction();
                return;
            }

            jQuery(".social ul").prepend('<li class="fa fa-linkedin skin-color skin-color-bg flipbook-bg-light flipbook-color-light" id="flipbook-share-linkedin" style="display: block;" onclick="linkedinShare();"></li>');
        }, 2000);
    }
    function myStopFunction() {
        clearTimeout(myVar);
    }

    function linkedinShare() {
        var url = "<?php the_permalink(); ?>";
        var post_title = "<?php echo rawurlencode($post->post_title); ?>";
        var post_cont = "<?php echo rawurlencode($post->post_content); ?>";
        window.open("https://www.linkedin.com/shareArticle?mini=true&url=" + encodeURIComponent(url) + " &title=" + post_title + "&summary=" + post_cont);
    }
    myFunction();
</script>
<div class="modal fade contentpopupform" id="contentpopup" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4><?php esc_html_e("Report Content", 'betube') ?></h4>
            </div>
            <section class="registration reportcommentsec">
                <div class="row secBg reportbg">
                    <div class="large-12 columns">
                        <div class="row" data-equalizer data-equalize-on="medium" id="test-eq">
                            <div class="medium-12 large-centered medium-centered columns">
                                <div class="register-form">
                                    <?php if (!empty($message)) { ?>
                                        <div>
                                            <p>
                                                <i class="fa fa-exclamation-triangle"></i>
                                                <?php echo $message; ?>										
                                            </p>
                                        </div>
                                    <?php } ?>
                                    <form class="form-item" action="" id="commentPostForm" method="POST" enctype="multipart/form-data">
                                        <div data-abide-error class="alert callout" style="display: none;">
                                            <p><i class="fa fa-exclamation-triangle"></i> <?php esc_html_e('There are some errors in your form.', 'betube') ?></p>
                                        </div>

                                        <div class="commentmsg">
                                            <span class="message"></span>
                                        </div>
                                        <?php if (!empty($registerSuccess) && $registerSuccess == 0) { ?>

                                            <div class="alert callout">
                                                <p> <?php echo $message; ?></p>
                                            </div>

                                        <?php } ?>

                                        <?php
                                        global $current_user;
                                        wp_get_current_user();
                                        $userEmail = $current_user->user_email;
                                        $userName = $current_user->user_login;
                                        ?>
                                        <?php $authoruserEmail = get_the_author_meta('user_login', $user_ID); ?>
                                        <?php
                                        if (is_user_logged_in()) {
                                            ?>
                                            <input class="input-group-field" type="hidden" id="commentname" name="reportedname" value="<?php echo $userName; ?>">             
                                        <?php } else { ?>
                                            <input class="input-group-field" type="text" id="contactName" name="reportedname" maxlength="30" placeholder="<?php esc_html_e("Enter your Name", 'betube') ?>" value="">
                                            <span class="error-message" id="error_name"></span>
                                        <?php } ?>



                                        <?php $authorEmailID = get_the_author_meta('user_email', $user_ID); ?>                                  
                                        <?php
                                        if (is_user_logged_in()) {
                                            ?>
                                            <input class="input-group-field" type="hidden" id="commentemail" name="reportedemail" value="<?php echo $userEmail; ?>">                
                                        <?php } else { ?>
                                            <input class="input-group-field" type="text" id="commentemail" name="reportedemail" placeholder="<?php esc_html_e("Enter your Email", 'betube') ?>" value=""> 
                                        <?php } ?>
                                        <span class="error-message" id="error_email"></span>

                                        <div class="input-group">
                                            <span class="input-group-label"><i class="fa fa-tags"></i></span>
                                            <select class="input-group-field postform" id="catID" name="reportedcat" required="">
                                                <option value="0">Category</option>
                                                <option value="inappropriate content" class="level-0">Inappropriate Content</option>
                                                <option value="copyright issue" class="level-0">Copyright Issue</option>
                                                <option value="others" class="level-0">Others</option>
                                            </select>
                                            <span style="color: red;" class="error-msg" id="error_msg"></span>
                                        </div>

                                        <input class="input-group-field" type="hidden" id="frontendurl" name="frontendurl" value="<?php echo the_permalink(); ?>">
                                        <input class="input-group-field" type="hidden" id="backendurl" name="backendurl" value="<?php echo site_url(); ?>/wp-admin/post.php?post=<?php echo $post->ID; ?>&action=edit">          
                                        <input type="hidden" name="posttitle" value="<?php echo $post->post_title; ?>">
                                        <input type="hidden" name="autheremail" value="<?php echo $authoremail; ?>">
                                        <input type="hidden" name="authername" value="<?php echo $betubeDisplayName; ?>">

                                        <div class="input-group reportmsg">
                                            <span class="input-group-label"><i class="fa fa-envelope"></i></span>
                                            <textarea placeholder="<?php esc_html_e("Message", 'betube') ?>" rows="4" cols="50" class="input-group-field required" name="message" id="message"></textarea>
                                            <span class="error-message" id="error_msg"></span>
                                        </div>

                                        <div class="setting-form-inner">
                                            <input type="hidden" name="submitted" id="submitted" value="true" />
                                            <input type="submit" name="submit" class="button expanded submit_content" value="Submit">
                                            <input type="hidden" name="posttitle" value="<?php echo $post->post_title; ?>">
                                        </div>
                                        <input type="hidden" name="post_type" id="post_type" value="report_content" /> 
                                        <?php wp_nonce_field('cpt_nonce_action', 'cpt_nonce_field'); ?>                                       
                                    </form>
                                </div>
                            </div><!--End Large 4 For Form-->
                        </div><!--row test-eq-->
                    </div>
                </div>
            </section>  
        </div>
    </div>
</div>
<style>
    .screen-reader-text {display: none;}
</style>
<script>

    var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    jQuery(document).ready(function ($) {
        var error;
        var click = 0;
        jQuery('#commentPostForm').on('submit', function () {
            error = false;
//            if (click == 1) {
//                jQuery('.commentmsg').show();
//                jQuery('.commentmsg').removeClass('successgreen');
//                jQuery('.commentmsg').addClass('errormsg');
//                jQuery('.commentmsg').find('.message').html('You already sent');
//                return false;
//            }
            $(this).find('.required').each(function () {
                if ($(this).val().trim() == '') {
                    error = true;
                    $(this).siblings('.error-message').html('This field is required');
                } else {
                    $(this).siblings('.error-message').html('');
                }
            });
            $(this).find('.email').each(function () {
                if ($(this).val().trim() != '') {
                    if (!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test($(this).val().trim()))) {
                        error = true;
                        $(this).siblings('.error-message').html('Valid email required');
                    } else {
                        $(this).siblings('.error-message').html('');
                    }
                }
            });

            if (jQuery("select[name=reportedcat]").val() == 0) {
                error = true;
                jQuery(".error-msg").html('This field is required');
            } else {
                jQuery(".error-msg").html('');
            }
            if (error == true) {
            } else {
                //jQuery('input:submit').val('Please wait...');
                jQuery('.submit_content').val('please wait..');

                click = 1;
                jQuery.post(ajaxurl, {
                    'action': 'save_commentdata_form',
                    'data': $(this).serializeArray()
                }, function (response) {

                    var obj = JSON.parse(response);

                    if (obj.status.length > 0 && obj.status == '1') {
                        jQuery('.commentmsg').show();
                        jQuery('.commentmsg').removeClass('errormsg');
                        jQuery('.commentmsg').addClass('successgreen');
                        jQuery('.commentmsg').find('.message').html(obj.message);
//                        jQuery('.commentmsg').find('.message').html(obj.message).hide()
//                                .fadeIn(1500, function () {
//                                    jQuery('.commentmsg');
//                                });
//                        setTimeout(reset, 000);
                        jQuery("#commentPostForm")[0].reset();

                        jQuery('.submit_content').val('Submit');
                        //jQuery('input:submit').val('Submit');
                    } else {
                        jQuery('.commentmsg').removeClass('successgreen');
                        jQuery('.commentmsg').addClass('errormsg');
                        jQuery('.commentmsg').find('.message').html(obj.message);
//                        setTimeout(reset, 3000);
                    }

                }
                );
            }
            return false;
        });
    });
    function reset() {
        jQuery("#commentPostForm")[0].reset();
        jQuery(".commentmsg").show();
        jQuery('.commentmsg').remove(); // Removing it as with next form submit you will be adding the div again in your code. 
    }

    function likeFlipbook(postId) {

        var ajaxUrl = location.href;
        console.log(ajaxUrl);
        var sep = '?';
        if (ajaxUrl.indexOf('?') > 0) {
            sep = '&';
        }

        jQuery.ajax({
            url: ajaxUrl + sep + 'like_flipbook=' + postId,
            success: function (data) {
                window.location = location.href;
            }
        });
    }
    jQuery('#contentpopup').on('hidden.bs.modal', function (e) {
    jQuery("#commentPostForm")[0].reset();
    jQuery('.error-msg').html('');
    jQuery('.error-message').html('');
    jQuery('.commentmsg').html('');
    //jQuery('.commentmsg').hide();
    })
</script>

<?php get_footer(); ?>
