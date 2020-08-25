<?php

class Betube_Mostviewed_Videos extends WP_Widget {

    public function __construct() {
        $widget_ops = array('classname' => 'Betube_Mostviewed_Videos', 'description' => __("Show MostViewed Video Posts.", 'betube-helper'));
        parent::__construct('betube-mostviewed-videos', __('BeTube MostViewed Videos', 'betube-helper'), $widget_ops);
        $this->alt_option_name = 'Betube_Mostviewed_Videos';
    }

    public function widget($args, $instance) {
        $cache = array();
        if (!$this->is_preview()) {
            $cache = wp_cache_get('betube_widget_mostviewed_videos', 'widget');
        }

        if (!is_array($cache)) {
            $cache = array();
        }

        if (!isset($args['widget_id'])) {
            $args['widget_id'] = $this->id;
        }

        if (isset($cache[$args['widget_id']])) {
            echo $cache[$args['widget_id']];
            return;
        }

        ob_start();

        $title = (!empty($instance['title']) ) ? $instance['title'] : __('Most Viewed Videos', 'betube-helper');

        /** This filter is documented in wp-includes/default-widgets.php */
        $title = apply_filters('widget_title', $title, $instance, $this->id_base);

        $number = (!empty($instance['number']) ) ? absint($instance['number']) : 5;
        if (!$number)
            $number = 5;
        $show_date = isset($instance['show_date']) ? $instance['show_date'] : false;


        $r = new WP_Query(apply_filters('widget_posts_args', array(
                    'posts_per_page' => $number,
                    'no_found_rows' => true,
                    'post_status' => 'publish',
                    'meta_key' => 'wpb_post_views_count',
                    'orderby' => 'meta_value_num',
                    'order' => 'DESC',
                    'post_type' => array('post',
                        'ignore_sticky_posts' => true
        ))));

        if ($r->have_posts()) :
            ?>
            <?php echo $args['before_widget']; ?>
            <?php
            if ($title) {
                echo $args['before_title'] . $title . $args['after_title'];
                echo '<div class="widgetContent">';
            }
            ?>
            <?php while ($r->have_posts()) : $r->the_post(); ?>
                <div class="video-box thumb-border">
                    <div class="video-img-thumb">
                        <?php
                        global $post;
                        $post_id = $post->ID;
                        if (has_post_thumbnail()) {
                            $thumbURL = betube_thumb_url($post_id);
                        } else {
                            $media = get_attached_media('image', $post_id);
                            $mediaArr = array();
                            foreach ($media as $key => $val) {
                                $s3Media = get_post_meta($val->ID);
                                $mediaArr = unserialize($s3Media['amazonS3_info'][0]);
                                $thumbURL = S3_UPLOADS . $mediaArr['key'];
                                break;
                            }
                            if (empty($mediaArr['key'])) {
                                $thumbURL = betube_thumb_url($post_id);
                            }
                        }
                        $altTag = betube_thumb_alt($post_id);
                        ?>
                        <img src="<?php echo $thumbURL; ?>" alt="<?php echo $altTag; ?>"/>
                        <a href="<?php the_permalink(); ?>" class="hover-posts">
                            <span><i class="flaticon-open-book-top-view"></i><?php esc_html_e('View Flipbook', 'betube-helper'); ?></span>
                        </a>
                    </div><!--video-img-thumb-->
                    <div class="video-box-content">
                        <h6><a href="<?php the_permalink(); ?>"><?php echo get_the_title(); ?></a></h6>
                        <?php /*                         * <p>
                          <span><i class="fa fa-user"></i>
                          <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><?php echo get_the_author_meta('display_name'); ?></a>
                          </span>
                          <?php if ($show_date) : ?>
                          <?php $beTubedateFormat = get_option('date_format'); ?>
                          <span><i class="fa fa-clock-o"></i><?php echo get_the_date($beTubedateFormat, $post_id); ?></span>
                          <?php endif; ?>
                          <span><i class="fa fa-eye"></i><?php echo betube_get_post_views(get_the_ID()); ?></span>
                          </p> */ ?> 
                        <p>
                            <i class="fa fa-user"></i>
                            <?php /*<span><?php echo get_the_author_meta('display_name'); ?></span>*/
                            $user_ID = $post->post_author;
                            ?>
                            <span><a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>">&nbsp;<?php echo get_the_author_meta('display_name', $user_ID); ?></a></span>
                            
                            <?php if ($show_date) : ?>
                                <?php $beTubedateFormat = get_option('date_format'); ?>
                                <i class="fa fa-clock-o"></i>&nbsp;<span><?php echo get_the_date($beTubedateFormat, $post_id); ?></span>
                            <?php endif; ?>
                            <i class="fa fa-eye"></i>&nbsp;<span><?php echo betube_get_post_views(get_the_ID()); ?></span>
                        </p>
                    </div><!--video-box-content-->
                </div><!--video-box-->		
            <?php endwhile; ?>
            <?php echo '</div>'; ?>
            <?php echo $args['after_widget']; ?>
            <?php
            wp_reset_postdata();

        endif;

        if (!$this->is_preview()) {
            $cache[$args['widget_id']] = ob_get_flush();
            wp_cache_set('betube_widget_recent_videos', $cache, 'widget');
        } else {
            ob_end_flush();
        }
    }

    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['number'] = (int) $new_instance['number'];
        $instance['show_date'] = isset($new_instance['show_date']) ? (bool) $new_instance['show_date'] : false;

        $alloptions = wp_cache_get('alloptions', 'options');
        if (isset($alloptions['Betube_Mostviewed_Videos']))
            delete_option('Betube_Mostviewed_Videos');

        return $instance;
    }

    public function form($instance) {
        $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
        $number = isset($instance['number']) ? absint($instance['number']) : 5;
        $show_date = isset($instance['show_date']) ? (bool) $instance['show_date'] : false;
        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title:', 'betube-helper'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

        <p><label for="<?php echo $this->get_field_id('number'); ?>"><?php esc_html_e('Number of posts to show:', 'betube-helper'); ?></label>
            <input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>

        <p><input class="checkbox" type="checkbox" <?php checked($show_date); ?> id="<?php echo $this->get_field_id('show_date'); ?>" name="<?php echo $this->get_field_name('show_date'); ?>" />
            <label for="<?php echo $this->get_field_id('show_date'); ?>"><?php esc_html_e('Display post date?', 'betube-helper'); ?></label></p>
        <?php
    }

}

add_action('widgets_init', create_function('', 'return register_widget("Betube_Mostviewed_Videos");'));
