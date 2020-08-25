<?php
/**
 * Template name: Flipbokk iframe
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage betube
 * @since betube 1.0
 */
get_header();
?>

<?php
    $flipbook_id = $_GET['flipbook_id'];
    echo do_shortcode('[real3dflipbook id="' . $flipbook_id . '"]');
?>
<?php get_footer(); ?>
<script>
    jQuery('footer').css('display', 'none');
    jQuery('header').css('display', 'none');
    jQuery('#footer-bottom').css('display', 'none');
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
