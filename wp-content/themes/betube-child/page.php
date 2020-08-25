<?php
get_header();
betube_breadcrumbs();
$page = get_page($post->ID);
?>
<section class="category-content">
    <div class="row">
        <!-- left side content area -->
        <div class="large-8 columns">
            <section class="content content-with-sidebar">
                <div class="main-heading removeMargin">
                    <div class="row secBg padding-14 removeBorderBottom">
                        <div class="medium-8 small-8 columns">
                            <div class="head-title">
                                <i class="flaticon-man-user"></i>
                                <h4><?php the_title(); ?></h4>
                            </div><!--head-title-->
                        </div><!--medium-8-->
                    </div><!--row-->
                </div><!--main-heading-->
                <div class="row secBg">
                    <div class="large-12 columns">
                        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                                <?php get_template_part('parts/loop', 'page'); ?>
                            <?php endwhile; endif; ?>
                    </div><!--large-12 columns-->
                </div><!--row secBg-->
            </section><!--content-->
            <!-- ad Section -->
            <?php
            $betubeGoogleAds = $redux_demo['betube-google-ads-for-all-video-page'];
            if (!empty($betubeGoogleAds)) {
                ?>
                <div class="googleAdv">
                    <?php echo $betubeGoogleAds; ?>
                </div>
                <!-- End ad Section -->
            <?php } ?>
        </div>
        <!-- left side content area -->
        <div class="large-4 columns">
            <aside class="secBg sidebar">
                <div class="row">
                    <?php get_sidebar(); ?>
                </div>
            </aside>
        </div><!--large-4-->
    </div><!--row-->
</section><!--category-content-->
<?php get_footer(); ?>