<?php
/**
 * Template name: Thank You
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage betube
 * @since betube 1.0
 */
get_header();
betube_breadcrumbs();
global $redux_demo;
$betubeFirstSecView = $redux_demo['landing-first-grid-selection'];
$betubeFeaturedSlider = $redux_demo['featured-slider-on'];
$myClass = "";
if ($betubeFirstSecView == "gridsmall") {
    $myClass = "group-item-grid-default";
} elseif ($betubeFirstSecView == "gridmedium") {
    $myClass = "grid-medium";
} elseif ($betubeFirstSecView == "listmedium") {
    $myClass = "list";
}
?>
<section class="category-content">
    <div class="row">
        <!-- left side content area -->
        <div class="large-8 columns">
            <section class="content content-with-sidebar">
                <div class="row secBg">
                    <div class="large-12 columns">
                        <div class="row column head-text clearfix">
                            <h4>Thank you. Your order has been received.</h4>
                        </div>
                    </div><!--End large-12-->
                </div><!--End row secBg-->
            </section><!--End Content Section-->
        </div><!--End large-8-->
        <!-- left side content area -->
        <div class="large-4 columns">
            <aside class="secBg sidebar">
                <div class="row">
                    <?php get_sidebar('main'); ?>
                </div>
            </aside>
        </div><!--End large-4-->
    </div><!--End Row-->
</section><!--End category-content-->
<?php get_footer(); ?>