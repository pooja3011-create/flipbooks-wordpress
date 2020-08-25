<?php
global $redux_demo;
$beTubeFBCommentOn = $redux_demo['betube-facebook-comment'];
$beTubeFBAPPID = $redux_demo['betubefbappid'];
if ($beTubeFBCommentOn == 1) {
    ?>
    <div id="fb-root"></div>
    <script>(function (d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id))
                return;
            js = d.createElement(s);
            js.id = id;
            js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=<?php echo $beTubeFBAPPID; ?>&version=v2.0";
            fjs.parentNode.insertBefore(js, fjs);
                }(document, 'script', 'facebook-jssdk'));</script>
    <div class="fb-comments" data-href="<?php the_permalink(); ?>" data-numposts="5" data-width="780" data-colorscheme="light"></div>
    <?php
} else {
    if (post_password_required()) {
        return;
    }
    ?>
    <div class="large-12 columns">
        <?php // You can start editing here ?>

        <?php if (have_comments()) : ?>
            <div class="main-heading borderBottom">
                <div class="row padding-14">
                    <div class="medium-12 small-12 columns">
                        <div class="head-title">
                            <i class="fa fa-comments"></i>
                            <h4>
                                <?php
                                printf(// WPCS: XSS OK.
                                        esc_html(_nx('One comment on &ldquo;%2$s&rdquo;', '%1$s comments on &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', 'betube')), number_format_i18n(get_comments_number()), '<span>' . get_the_title() . '</span>'
                                );
                                ?>
                            </h4>
                        </div><!--head-title-->
                    </div><!--medium-12-->
                </div><!--row padding-14-->
            </div><!--main-heading-->

        <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : // Are there comments to navigate through?  ?>
                <nav id="comment-nav-above" class="navigation comment-navigation" role="navigation">
                    <h2 class="screen-reader-text"><?php esc_html_e('Comment navigation', 'betube'); ?></h2>
                    <div class="nav-links">

                        <div class="nav-previous"><?php previous_comments_link(esc_html__('Older Comments', 'betube')); ?></div>
                        <div class="nav-next"><?php next_comments_link(esc_html__('Newer Comments', 'betube')); ?></div>
                    </div><!-- .nav-links -->
                </nav><!-- #comment-nav-above -->
        <?php endif; // Check for comment navigation.  ?>

            <ol class="commentlist">
        <?php wp_list_comments('type=comment&callback=betube_comments'); ?>
            </ol>
                <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : // Are there comments to navigate through? ?>
                <nav id="comment-nav-below" class="navigation comment-navigation" role="navigation">
                    <h2 class="screen-reader-text"><?php esc_html_e('Comment navigation', 'betube'); ?></h2>
                    <div class="nav-links">

                        <div class="nav-previous"><?php previous_comments_link(esc_html__('Older Comments', 'betube')); ?></div>
                        <div class="nav-next"><?php next_comments_link(esc_html__('Newer Comments', 'betube')); ?></div>

                    </div><!-- .nav-links -->
                </nav><!-- #comment-nav-below -->
        <?php endif; // Check for comment navigation.  ?>

        <?php endif; // Check for have_comments(). ?>
        <?php
        // If comments are closed and there are comments, let's leave a little note, shall we?
        if (!comments_open() && '0' != get_comments_number() && post_type_supports(get_post_type(), 'comments')) :
            ?>
            <p class="no-comments"><?php esc_html_e('Comments are closed.', 'betube'); ?></p>
        <?php endif; ?>

        <?php comment_form(array('class_submit' => 'button')); ?>

    </div><!--large-12 -->
<?php } ?>  
<div class="modal fade contentpopupform" id="commentpopup" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4><?php esc_html_e("Report Comment", 'betube') ?></h4>
            </div>
            <section class="registration reportcommentsec">
                <div class="row secBg reportbg commentform">
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
                                    <form class="form-item" action="" id="commentPostFormdata" method="POST" enctype="multipart/form-data">
                                        <div data-abide-error class="alert callout" style="display: none;">
                                            <p><i class="fa fa-exclamation-triangle"></i> <?php esc_html_e('There are some errors in your form.', 'betube') ?></p>
                                        </div>
                                        <div class="contentmsg">
                                            <span class="message"></span>
                                        </div>

                                            <?php
                                            global $current_user;
                                            wp_get_current_user();
                                            $userEmail = $current_user->user_email;
                                            $userName = $current_user->user_login;
                                            ?>                                       
                                            <?php $authorUsername = get_the_author_meta('user_login', $user_ID); ?>
                                            <input class="input-group-field" type="hidden" id="commentname" name="reportedname" value="<?php echo $userName; ?>">             

                                            <?php $authorEmailID = get_the_author_meta('user_email', $user_ID); ?>                                  
                                            <input class="input-group-field required" type="hidden" id="commentemail" name="reportedemail" value="<?php echo $userEmail; ?>">                

                                        <input class="input-group-field" type="hidden" id="frontendurl" name="frontendurl" value="<?php echo the_permalink(); ?>">

                                        <input class="input-group-field" type="hidden" id="backendurl" name="backendurl" value="">          
                                        <input class="input-group-field" type="hidden" id="profileurl" name="profileurl" value="">          
                                        <input class="input-group-field" type="hidden" id="profilename" name="profilename" value=""> 
                                        <input class="input-group-field" type="hidden" id="commenttime" name="commenttime" value=""> 
                                        <input class="input-group-field" type="hidden" id="commentid" name="commentid" value=""> 
                                        
                                        <input class="input-group-field" type="hidden" id="flipbookname" name="flipbookname" value="<?php echo get_the_title();?>"> 
 
                                        <div class="input-group reportmsg">
                                            <span class="input-group-label"><i class="fa fa-envelope"></i></span>
                                            <textarea placeholder="<?php esc_html_e("Message", 'betube') ?>" rows="4" cols="50" class="input-group-field required" name="message" id="message"></textarea>
                                            <span class="error-message" id="error_msg"></span>
                                        </div>

                                        <div class="setting-form-inner">
                                            <input type="hidden" name="submitted" id="submitted" value="true" />
                                            <input type="submit" name="submit12" class="button expanded submit_comment" value="Submit">
                                        </div>
                                        <input type="hidden" name="post_type" id="post_type" value="report_comments" /> 
                                        <input type="hidden" name="posttitle" value="<?php echo $post->post_title; ?>">
        <?php wp_nonce_field('content_nonce_action', 'content_nonce_field'); ?>                                       
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
<script>
    var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    jQuery(document).ready(function ($) {
        var error;
        var click = 0;
        jQuery('#commentPostFormdata').on('submit', function () {
            error = false;
//            if (click == 1) {
//                jQuery('.contentmsg').show();
//                jQuery('.contentmsg').removeClass('successgreen');
//                jQuery('.contentmsg').addClass('errormsg');
//                jQuery('.contentmsg').find('.message').html('You already sent');
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
            if (error == true) {
            } else {
                jQuery('.submit_comment').val('please wait..');
                //jQuery('input:submit').val('Please wait...');
                click = 1;
                jQuery.post(ajaxurl, {
                    'action': 'save_content_form',
                    'data': $(this).serializeArray()
                }, function (response) {
//                    /alert(response);
                    var obj = JSON.parse(response);
                    //console.log(obj);
                    if (obj.status.length > 0 && obj.status == '1') {
                        jQuery('.contentmsg').show();
                        jQuery('.contentmsg').removeClass('errormsg');
                        jQuery('.contentmsg').addClass('successgreen');
                        jQuery('.contentmsg').find('.message').html(obj.message);
//                        jQuery('.contentmsg').find('.message').html(obj.message).hide()
//                                .fadeIn(1500, function () {
//                                    jQuery('.contentmsg');
//                                });
//                        setTimeout(reset, 000);
                        jQuery("#commentPostFormdata")[0].reset();
                        jQuery('.submit_comment').val('Submit');
                        //jQuery('input:submit').val('Submit');
                    } else {
                        jQuery('.contentmsg').removeClass('successgreen');
                        jQuery('.contentmsg').addClass('errormsg');
                        jQuery('.contentmsg').find('.message').html(obj.message);
//                        setTimeout(reset, 3000);
                    }
                }
                );
            }
            return false;
        });
    });
    function resetAll() {
        jQuery(".contentmsg").show();
        jQuery('.contentmsg').remove(); // Removing it as with next form submit you will be adding the div again in your code. 
    }
</script>
<script>
    jQuery(document).ready(function () {
        jQuery('.commentlist article').each(function () {
            var id = jQuery(this).attr('id');
            var ids = id.split('-')[1];
            jQuery('#' + id).append(<?php
                                    if (is_user_logged_in()) {
                                    ?>'|  <a data-toggle="modal" data-target="#commentpopup" class="commentreportbtn" href="#commentid=' + ids + '"><?php esc_html_e("Report Comment", 'betube') ?></a>'<?php } else { ?>'<a style="display: none;" class="unclickable"><?php esc_html_e("Report Comment", 'betube') ?></a>'<?php } ?>);
        });
        jQuery("a.commentreportbtn").click(function () {
            var profileurl = jQuery(this).siblings('header').find('.url').attr('href');
            var profilename = jQuery(this).siblings('header').find('.url').text();
            var commenttime = jQuery(this).siblings('header').find('time').text();
            var commenthref = jQuery(this).attr('href');
            var commentid = commenthref.split('#commentid=')[1];
            jQuery("#backendurl").val("<?php echo site_url(); ?>/wp-admin/comment.php?action=editcomment&c=" + commentid);
            jQuery("#profileurl").val(profileurl);
            jQuery("#profilename").val(profilename);
            jQuery("#commenttime").val(commenttime);
            jQuery("#commentid").val(commentid);

        });
    });
     
    jQuery('#commentpopup').on('hidden.bs.modal', function (e) {
    jQuery("#commentPostFormdata")[0].reset();
    jQuery('.error-msg').html('');
    jQuery('.error-message').html('');
    jQuery('.contentmsg').html('');
    })
</script>