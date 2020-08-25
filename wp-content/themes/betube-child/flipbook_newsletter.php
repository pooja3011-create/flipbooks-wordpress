<?php

include_once("/var/www/html/youflips/wp-load.php");
global $wpdb;
$logo = $redux_demo['betube-logo']['url'];
include_once(get_stylesheet_directory() . '/PHPMailer/class.phpmailer.php');

$query = "SELECT id,sub_email,sub_name from {$wpdb->prefix}newsletter_subscribers;";

$results = $wpdb->get_results($query, OBJECT);
if (!empty($results)) {

    $phpmailer = new PHPMailer();
    $phpmailer->IsSMTP(true); //switch to smtp
    $phpmailer->Host = 'smtp.gmail.com';
    $phpmailer->SMTPAuth = 1;
    $phpmailer->Port = 587;
    $phpmailer->Username = 'hitaxi.tt@gmail.com';
    $phpmailer->Password = 'hitaxi@trident';
    $phpmailer->SMTPSecure = 'tls';
    $phpmailer->From = $mailFrom;
    $phpmailer->FromName = $mailFromName;

    $adminMailId = get_option('admin_email');
    $adminName = get_option('blogname');

    foreach ($results as $result) {

        $mailFrom = ($adminMailId != '') ? $adminMailId : 'hitaxi.tt@gmail.com';
        $mailFromName = ($adminName != '') ? $adminName : 'YouFlip$';
        $mailTo = $result->sub_email;
        $mailToName = $result->sub_name;

        if ($mailTo != '') {
            $content = '';
            $mailLogo = $logo;
            if ($mailLogo == '' && !file_exists($mailLogo)) {
                $mailLogo = get_template_directory_uri() . '/images/logo.png';
            }
            $flipbookBlock = getFlipbooks($result->id);
            if ($flipbookBlock != '') {
                $content = '<table width="100%" cellpadding="3" cellspacing="3" style="border:3px solid grey;color:black;font-family: comic sans ms;padding-left: 15px;padding-right: 15px;padding-top: 15px;" background="#EEF3F7" width="275" height="95">
                    <tr>
                        <td>
                           <a target="_blank" href="javascript:;">
                           <img src="' . $mailLogo . '" border="0"/></a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            &nbsp;
                        </td>
                    </tr>      
                    <tr>
                        <td style="font-family: comic sans ms;font-size: 15px">
                            Hi ' . ucwords($mailToName) . ',
                        </td>
                    </tr>
                    <tr>
                        <td style="font-family: comic sans ms;font-size: 15px">
                            Check out the latest publications.
                        </td>
                    </tr>
                    <tr>
                        <td>
                            &nbsp;
                        </td>
                    </tr>        
                    <tr>
                    <td>
                    <tr>
  <td align="center">
   <table style="background-color:#ebebeb;padding:20px;width:500px;">
  <tr><td>
  
' . $flipbookBlock . '</td></tr>
  </table>
  </td>
  </tr>                    
                    </td>
                    </tr>
                      
                    <tr>
                        <td>
                            &nbsp;
                        </td>
                    </tr> 
                    <tr>
                        <td style="font-family: comic sans ms;font-size: 15px" align="center">
                           <a href="' . site_url() . '" style="background-color: #444444 !important; border-radius: 5px;font-weight: bold;text-transform: uppercase;margin: 20px 0 50px;background-color: #444;
    color: #fff;
    cursor: pointer;
    font-size: 0.9rem;
    line-height: 1;
    padding: 1.1875em 1.5625em;
    text-align: center;
    transition: background-color 0.25s ease-out 0s, color 0.25s ease-out 0s;
    vertical-align: middle;text-decoration: none;">View More Flipbooks</a>
                        </td>
                    </tr>
                     <tr>
                        <td>
                            &nbsp;
                        </td>
                    </tr> 
                    <tr>
                        <td>
                            &nbsp;
                        </td>
                    </tr> 

                    <tr>
                        <td style="font-family: comic sans ms;font-size: 15px">
                            Please do not reply to this email. Emails sent to this address will not be answered. 
                        </td>
                    </tr>
  
                    <tr>
                        <td align="center" style="font-family: comic sans ms;font-size: 15px">
                            <span style="font-size:11px">Copyright &copy; ' . date('Y') . ' Virtus Mind Ltd, 272 Bath Street, Glasgow, G2 4JR, UK. All rights reserved. </span>
                        </td>
                    </tr>                                                   
                </table>';
//                echo $content;
                $phpmailer->AddAddress($mailTo, $mailToName);
                $phpmailer->IsHTML(true);
                $phpmailer->Subject = '"Newsletter Category"';
                $phpmailer->MsgHTML($content);
                $phpmailer->Send();
                $phpmailer->clearAddresses();
            }
        }
    }
}

function getFlipbooks($id) {
    global $wpdb;
    $query = "SELECT cat_id from {$wpdb->prefix}newsletter_subscribers_category where sub_id='" . $id . "' group by cat_id ;";

    $categories = $wpdb->get_results($query, OBJECT);

    foreach ($categories as $cats) {
        $catId .= ($catId != '') ? ',' . $cats->cat_id : $cats->cat_id;
    }

    $today = getdate();

    $flipbookPostArgs = array(
        'post_type' => 'post',
        'taxonomy' => 'category',
        'cat' => explode(',', $catId),
        'post_status' => 'publish',
        'posts_per_page' => '12',
        'order' => 'ASC',
        'orderby' => 'ID',
        'year' => $today['year'],
        'monthnum' => $today['mon'],
        'day' => $today['mday'],
        'suppress_filters' => false
    );

    $flipbookBlock = '';
    $flippost_query = null;
    $flippost_query = new WP_Query($flipbookPostArgs);

    if ($flippost_query->have_posts()) {
        $postCount++;
        while ($flippost_query->have_posts()) : $flippost_query->the_post();
            $image = '';
            global $post;
            if (has_post_thumbnail()) {
                $image = get_the_post_thumbnail_url();
            } else {
                $media = get_attached_media('image', $post->ID);
                foreach ($media as $key => $val) {
                    $s3Media = get_post_meta($val->ID);
                    $mediaArr = unserialize($s3Media['amazonS3_info'][0]);
                    $image = S3_UPLOADS . $mediaArr['key'];
                    break;
                }
                if (empty($mediaArr['key'])) {
                    $image = betube_thumb_url($post_id);
                }
            }

            $content = $post->post_content;
            $content = mb_strimwidth(strip_tags($content), 0, 80, '...');
            /*  $flipbookBlock .= '
              <div style="border: 1px solid grey; font-family: comic sans ms; font-size: 15px; width: 30%; float: left; margin: 1px; padding: 5px; text-align: center;">
              <img src="' . $image . '" title="" height="150" width="150"/>
              <br>
              <b><a href="' . get_permalink($post->ID) . '" style="text-decoration: none;color:#444444;">' . ucwords($post->post_title) . '</a></b><br><span> - ' . ucwords(get_the_author()) . '</span><br>
              ' . $content . '

              </div> ';
             */
            $flipbookBlock .= '
      <div style="border: 1px solid #C0C0C0; font-family: comic sans ms; font-size: 15px; width: 210; float: left; margin: 1px; padding: 5px;">
      <img src="' . $image . '" title="" height="150" width="200"/>
      <br>
      <b><a href="' . get_permalink($post->ID) . '" style="text-decoration: none;color:#444444;">' . ucwords($post->post_title) . '</a></b>'
                    . '<br>'
                    . '<img src="' . get_avatar_url(get_the_author_meta('ID', $user_ID)) . '" height="20" width="20">'
                    . '<span>&nbsp;' . ucwords(get_the_author()) . '</span>
                            &nbsp;<a href="' . get_permalink($post->ID) . '" style="background-color: #444444 !important; border-radius: 5px;text-transform: uppercase;background-color: #444;
    color: #fff;
    cursor: pointer;
    font-size: 10px;
    line-height: 1;
    padding: 6px;
    transition: background-color 0.25s ease-out 0s, color 0.25s ease-out 0s;text-decoration: none;float:right;">View</a>
                            </div> ';

        endwhile;
    }
    wp_reset_query();
    wp_reset_postdata();

    return $flipbookBlock;
}

/** 
$flipbookArgs = array(
    'hide_empty' => 0,
    'orderby' => 'term_id',
    'hierarchical' => true,
    'taxonomy' => 'category',
    'parent' => 0
);

$categories = get_categories($flipbookArgs);
$postCount = 0;
$catId = "";
if (!empty($categories)) {
    foreach ($categories as $cats) {
        $catSlug = $cats->slug;
        $catId .= ($catId != '') ? ',' . $cats->term_id : $cats->term_id;

        $catname = $cats->name . '</br>';
    }

    $today = getdate();
    $flipbookPostArgs = array(
        'post_type' => 'post',
        'taxonomy' => 'category',
        'cat' => explode(',', $catId),
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'order' => 'ASC',
        'orderby' => 'ID',
        'year' => $today['year'],
        'monthnum' => $today['mon'],
        'day' => $today['mday'],
        'suppress_filters' => false
    );

    $flipbookBlock = '';
    $flippost_query = null;
    $flippost_query = new WP_Query($flipbookPostArgs);

    if ($flippost_query->have_posts()) {
        $postCount++;
        while ($flippost_query->have_posts()) : $flippost_query->the_post();
            $image = '';
            if (has_post_thumbnail()) {
                $image = get_the_post_thumbnail_url();
            } else {
                $media = get_attached_media('image', $post->ID);
                foreach ($media as $key => $val) {
                    $s3Media = get_post_meta($val->ID);
                    $mediaArr = unserialize($s3Media['amazonS3_info'][0]);
                    $image = S3_UPLOADS . $mediaArr['key'];
                    break;
                }
                if (empty($mediaArr['key'])) {
                    $image = betube_thumb_url($post_id);
                }
            }

            $content = $post->post_content;
            $content = mb_strimwidth(strip_tags($content), 0, 80, '...');
           
            $flipbookBlock .= '
                 <div style="border: 1px solid grey; font-family: comic sans ms; font-size: 15px; width: 30%; float: left; margin: 1px; padding: 5px; text-align: center;">
                        <img src="' . $image . '" title="" height="150" width="150"/>
                        <br>
                        <b><a href="' . get_permalink($post->ID) . '" style="text-decoration: none;color:#444444;">' . ucwords($post->post_title) . '</a></b><br><span> - ' . ucwords(get_the_author()) . '</span><br>
                        ' . $content . '
                        
                 </div> ';

        endwhile;
    }
    wp_reset_query();
    wp_reset_postdata();
    //}

    if ($postCount > 0) {
        $qur = "SELECT ns2.sub_email, ns2.sub_name from {$wpdb->prefix}newsletter_subscribers_category ns1, {$wpdb->prefix}newsletter_subscribers ns2 where ns1.cat_id IN (" . $catId . ") AND ns1.sub_id=ns2.id";

        $results = $wpdb->get_results($qur, OBJECT);

//        include(get_template_directory() . '/templates/email/email-header.php');
        $phpmailer = new PHPMailer();
        $phpmailer->IsSMTP(true); //switch to smtp
        $phpmailer->Host = 'smtp.gmail.com';
        $phpmailer->SMTPAuth = 1;
        $phpmailer->Port = 587;
        $phpmailer->Username = 'hitaxi.tt@gmail.com';
        $phpmailer->Password = 'hitaxi@trident';
        $phpmailer->SMTPSecure = 'tls';
        $phpmailer->From = $mailFrom;
        $phpmailer->FromName = $mailFromName;

        $adminMailId = get_option('admin_email');
        $adminName = get_option('blogname');

        foreach ($results as $result) {

            $mailFrom = ($adminMailId != '') ? $adminMailId : 'hitaxi.tt@gmail.com';
            $mailFromName = ($adminName != '') ? $adminName : 'YouFlip$';
            $mailTo = $result->sub_email;
            $mailToName = $result->sub_name;

            if ($mailTo != '') {
                $content = '';
                $mailLogo = $logo;
                if ($mailLogo == '' && !file_exists($mailLogo)) {
                    $mailLogo = get_template_directory_uri() . '/images/logo.png';
                }
                $content = '<table width="100%" cellpadding="3" cellspacing="3" style="border:2px solid grey;color:black;font-family: comic sans ms;padding-left: 15px;padding-right: 15px;padding-top: 15px;" background="#EEF3F7" width="275" height="95">
                    <tr>
                        <td>
                           <a target="_blank" href="javascript:;">
                           <img src="' . $mailLogo . '" border="0"/></a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            &nbsp;
                        </td>
                    </tr>      
                    <tr>
                        <td style="font-family: comic sans ms;font-size: 15px">
                            Hi ' . $mailToName . '
                        </td>
                    </tr>
                    <tr>
                        <td>
                            &nbsp;
                        </td>
                    </tr>        
                    <tr>
                        <td style="font-family: comic sans ms;font-size: 15px">
                            Check out newly Added Flipbooks
                        </td>
                    </tr> 
                    <tr>
                    <td>
                    ' . $flipbookBlock . '
                    </td>
                    </tr>
                      
                    <tr>
                        <td>
                            &nbsp;
                        </td>
                    </tr> 
                    <tr>
                        <td style="font-family: comic sans ms;font-size: 15px" align="center">
                           <a href="' . site_url() . '" style="background-color: #444444 !important; border-radius: 5px;font-weight: bold;text-transform: uppercase;margin: 20px 0 50px;background-color: #444;
    color: #fff;
    cursor: pointer;
    font-size: 0.9rem;
    line-height: 1;
    padding: 1.1875em 1.5625em;
    text-align: center;
    transition: background-color 0.25s ease-out 0s, color 0.25s ease-out 0s;
    vertical-align: middle;text-decoration: none;">View More Flipbooks</a>
                        </td>
                    </tr>
                     <tr>
                        <td>
                            &nbsp;
                        </td>
                    </tr> 
                    <tr>
                        <td style="font-family: comic sans ms;font-size: 15px">
                            Regards,
                        </td>
                    </tr>  
                    <tr>
                        <td style="font-family: comic sans ms;font-size: 15px">
                            Team Flipbooks.
                        </td>
                    </tr>    
                    <tr>
                        <td align="center" style="font-family: comic sans ms;font-size: 15px">
                            <span style="font-size:11px">&copy; ' . date('Y') . ' YouFlip$, All Rights Reserved. </span>
                        </td>
                    </tr>                                                   
                </table>';
//                echo $content;
//                exit;
                $phpmailer->AddAddress($mailTo, $mailToName);
                $phpmailer->IsHTML(true);
                $phpmailer->Subject = "Update From Flipbook";
                $phpmailer->MsgHTML($content);
                $phpmailer->Send();
                $phpmailer->clearAddresses();
            }
        }
    }
}
   */
