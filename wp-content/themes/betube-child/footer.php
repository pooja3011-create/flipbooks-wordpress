<?php
global $redux_demo;
$betubeFooterON = $redux_demo['betube-footer-on'];
$betubeBacktoTop = $redux_demo['backtotop'];
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
if ($betubeFooterON == 1) {
    ?>
    <footer class="footer" role="contentinfo">
        <div class="row small-up-1 medium-up-2 large-up-4" data-equalizer data-equalize-by-row="true" id="footer">							
            <?php dynamic_sidebar('footer'); ?>

        </div><!--End Footer Row-->
        <?php if ($betubeBacktoTop == 1) { ?>
            <a href="#" id="back-to-top" title="<?php esc_html_e('Back to top', 'betube'); ?>"><i class="fa fa-angle-double-up"></i></a>
        <?php } ?>
    </footer> <!-- end .footer -->
<?php } ?>

<div id="footer-bottom">
    <?php
    global $redux_demo;
    $betubeFLogo = $redux_demo['footer-logo']['url'];
    $betubeCopyRights = $redux_demo['footer_copyright'];
    $betubeBlogTitle = get_bloginfo('name');
    if (!empty($betubeFLogo)) {
        ?>
        <div class="logo text-center">
            <img src="<?php echo esc_url($betubeFLogo); ?>" alt="<?php echo $betubeBlogTitle; ?>">
        </div><!--Footer LOGO -->
        <?php
    }
    if (!empty($betubeCopyRights)) {
        ?>
        <div class="btm-footer-text text-center">
            <p><?php echo $betubeCopyRights; ?></p>
        </div><!--CopyRightText -->
    <?php } ?>
</div><!--footer-bottom -->
</div>  <!-- end .main-content -->
</div> <!-- end .off-canvas-wrapper-inner -->
</div> <!-- end .off-canvas-wrapper -->
<style>
    .sub_button {
        background: #2e2e2e none repeat scroll 0 0;
        padding: 12px 0;
        text-align: center;
        text-transform: uppercase;
        width: 100%;
        color: white;
        font-weight:bold;
        border-radius: 5px;
    }
</style>
<?php wp_footer(); ?>

<script>
    function isValidEmailAddress(emailAddress) {
        var pattern = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        return pattern.test(emailAddress);
    }

    function subscribe_newsletter() {
        var url = "<?php echo admin_url('admin-ajax.php?action=subscribe_newsletter'); ?>";
        var name = jQuery.trim(jQuery("#sub_name").val());
        var email = jQuery.trim(jQuery("#sub_email").val());

        if (name == "") {
            alert("Please enter full name");
            return;
        } else if (email == "") {
            alert("Please enter email address");
            return;
        } else if (!isValidEmailAddress(email)) {
            alert("Please enter valid email address");
            return;
        }

        jQuery.ajax({
            type: "post",
            dataType: "json",
            url: url,
            data: {name: name, email: email},
            success: function (r) {
                if (r.status == "1") {
                    jQuery("#sub_name").val("");
                    jQuery("#sub_email").val("");
                }
                alert(r.message);
            }
        })
    }

    function createCookie(name, value, days) {
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            var expires = "; expires=" + date.toGMTString();
        } else
            var expires = "";

        document.cookie = name + "=" + value + expires + "; path=/";
        var elem = document.getElementById("cookieDiv");
        elem.remove();
    }
    var html = '<div class="widgetContent"><div class="social-links"><h5>We’re a Social Bunch</h5> ';
<?php if (!empty($betubeFB)) { ?>
        html += '<a href="<?php echo $betubeFB; ?>" target="_blank" class="secondary-button"><i class="fa fa-facebook"></i></a> ';
    <?php
}
if (!empty($beTubeTW)) {
    ?>
        html += '<a href="<?php echo $beTubeTW; ?>" target="_blank" class="secondary-button"><i class="fa fa-twitter"></i></a> ';
    <?php
}
if (!empty($beTubeGPlus)) {
    ?>
        html += '<a href="<?php echo $beTubeGPlus; ?>" target="_blank" class="secondary-button"><i class="fa fa-google-plus"></i></a> ';
    <?php
}
if (!empty($beTubePin)) {
    ?>
        html += '<a href="<?php echo $beTubePin; ?>" target="_blank" class="secondary-button"><i class="fa fa-pinterest-p"></i></a> ';
    <?php
}
if (!empty($beTubeLinkedin)) {
    ?>
        html += '<a href="<?php echo $beTubeLinkedin; ?>" target="_blank" class="secondary-button"><i class="fa fa-linkedin"></i></a> ';
    <?php
}
if (!empty($beTubeInsta)) {
    ?>
        html += '<a href="<?php echo $beTubeInsta; ?>" target="_blank" class="secondary-button"><i class="fa fa-instagram"></i></a> ';
    <?php
}
if (!empty($beTubeYT)) {
    ?>

        html += '<a href="<?php echo $beTubeYT; ?>" target="_blank" class="secondary-button"><i class="fa fa-youtube"></i></a> ';
<?php } if (!empty($beTubeVi)) { ?>
        html += '<a href="<?php echo $beTubeVi; ?>" target="_blank" class="secondary-button"><i class="fa fa-vimeo-square"></i></a> ';
    <?php
}
if (!empty($beTubeDirbb)) {
    ?>
        html += '<a href="<?php echo $beTubeDirbb; ?>" target="_blank" class="secondary-button"><i class="fa fa-dribbble"></i></a> ';
    <?php
}
if (!empty($beTubeFlic)) {
    ?>
        html += '<a href="<?php echo $beTubeFlic; ?>" target="_blank" class="secondary-button"><i class="fa fa-flickr"></i></a> ';
    <?php
}
if (!empty($beTubeGith)) {
    ?>
        html += '<a href="<?php echo $beTubeGith; ?>" target="_blank" class="secondary-button"><i class="fa fa-github"></i></a>';
<?php } ?>
    html += '</div></div>';
    jQuery("#footer div.column:last .widgetBox").append(html);

    jQuery('.accordion-content').remove();
    
    jQuery( document ).ready(function() {
        jQuery('.wc-forward').text("Continue Flipping");
        jQuery('.wc-proceed-to-checkout .wc-forward').text("Continue Checkout");
    });
   
</script>
</body>
</html> <!-- end page -->