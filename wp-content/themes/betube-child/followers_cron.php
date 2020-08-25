<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
set_time_limit(0);
include_once("/var/www/html/youflips/wp-load.php");
global $wpdb;

include_once(get_stylesheet_directory() . '/PHPMailer/class.phpmailer.php');
$logo = $redux_demo['betube-logo']['url'];
$mailLogo = $logo;
if ($mailLogo == '' && !file_exists($mailLogo)) {
    $mailLogo = get_template_directory_uri() . '/images/logo.png';
}

$adminMailId = get_option('admin_email');
$adminName = get_option('blogname');

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




$logIds = array();
$query = "SELECT id,author_id,log,type from {$wpdb->prefix}followers_log where status='0' && type='profile_update'";
$results = $wpdb->get_results($query, OBJECT);
if (!empty($results)) {
    $content = '';
    foreach ($results as $result) {

        array_push($logIds, $result->id);
        $author = get_userdata($result->author_id);
        $authorData = get_user_meta($result->author_id);
        $authorData['user_email'][0] = $author->user_email;
        $authorData['user_url'][0] = $author->user_url;
        $log = unserialize($result->log);

        $authorUpdate = '<strong>Username :</strong> ' . ucwords($authorData['display_name'][0]) . '<br>';
        foreach ($log as $key) {
            $label = $key;
            if ($label == 'user_url') {
                $label = 'website';
            }
            $authorUpdate .= '<strong>' . ucwords(str_replace('_', ' ', $label)) . ':</strong> ' . ucwords($authorData[$key][0]) . '<br>';
        }

        $query = "SELECT f.follower_id from {$wpdb->prefix}author_followers f where f.author_id='" . $result->author_id . "' and f.recieve_update='1' group by f.follower_id ;";
        $followers = $wpdb->get_results($query, OBJECT);

        if (!empty($followers)) {
            foreach ($followers as $follow) {
                $userData = get_userdata($follow->follower_id);
                $mailToName = $userData->user_login;
                $mailTo = $userData->user_email;
                $mailFrom = ($adminMailId != '') ? $adminMailId : 'hitaxi.tt@gmail.com';
                $mailFromName = ($adminName != '') ? $adminName : 'YouFlip$';

                if ($mailTo != '') {
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
                            Hi ' . ucwords($mailToName) . ',
                        </td>
                    </tr>
                    <tr>
                        <td>
                            &nbsp;
                        </td>
                    </tr>        
                    <tr>
                        <td style="font-family: comic sans ms;font-size: 15px">
                            ' . $author->display_name . ' profile has been updated with new info.
                        </td>
                    </tr> 
                    <tr>
                        <td>
                            Find out what new, click on the below link.
                        </td>
                    </tr> 
                    <tr>
                    <td>
                    ' . $authorUpdate . '
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
                            YouFlip$.
                        </td>
                    </tr>    
                    <tr>
                    <td align="center" style="font-family: comic sans ms;font-size: 15px">
                    <span style="font-size:11px">Copyright &copy; ' . date('Y') . ' Virtus Mind Ltd, 272 Bath Street, Glasgow, G2 4JR, UK. All rights reserved. </span>
                    </td>
                    </tr>                                        
                </table>';
//                    echo $content;
                    // echo $mailTo . '--' . $mailToName;
                    $phpmailer->AddAddress($mailTo, $mailToName);
                    $phpmailer->IsHTML(true);
                    $phpmailer->Subject =   '"'.ucwords($mailToName).'" Have has changed';
                    $phpmailer->MsgHTML($content);
                    $phpmailer->Send();
                    $phpmailer->clearAddresses();
                    //echo 'test';
                    sleep(2);
                }
            }
        }
    }
    $ids = implode(',', $logIds);
    $query = "Update {$wpdb->prefix}followers_log set status='1' where id in (" . $ids . ")";
    $results = $wpdb->query($query);
}

$logIds = array();
$query = "SELECT id,author_id,log,type from {$wpdb->prefix}followers_log where status='0' && type='flipbook_upload'";
$results = $wpdb->get_results($query, OBJECT);
if (!empty($results)) {
    $followerIds = array();
    foreach ($results as $result) {
        array_push($logIds, $result->id);
        $query = "SELECT f.follower_id from {$wpdb->prefix}author_followers f where f.author_id='" . $result->author_id . "' and f.recieve_update='1' group by f.follower_id ;";
        $followers = $wpdb->get_results($query, OBJECT);

        if (!empty($followers)) {
            foreach ($followers as $follower) {
                $content = '';
                $userData = get_userdata($follower->follower_id);
                $mailToName = $userData->user_login;
                $mailTo = $userData->user_email;
                $mailFrom = ($adminMailId != '') ? $adminMailId : 'hitaxi.tt@gmail.com';
                $mailFromName = ($adminName != '') ? $adminName : 'YouFlip$';

                if ($mailTo != '') {
                    if (!in_array($follower->follower_id, $followerIds)) {
                        array_push($followerIds, $follower->follower_id);
                        $flipbookBlock = getFlipbook($follower->follower_id);
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
  <td align="center">
  <table style="background-color:#ebebeb;padding:20px;width:500px;">
  ' . $flipbookBlock . '
</table>
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
  YouFlip$.
  </td>
  </tr>
  <tr>
  <td align="center" style="font-family: comic sans ms;font-size: 15px">
   <span style="font-size:11px">Copyright &copy; ' . date('Y') . ' Virtus Mind Ltd, 272 Bath Street, Glasgow, G2 4JR, UK. All rights reserved. </span>
  </td>
  </tr>
  </table>';
//                        echo $content;
                        $phpmailer->AddAddress($mailTo, $mailToName);
                        $phpmailer->IsHTML(true);
                        $phpmailer->Subject = '"'.ucwords($mailToName).'"'. "New Publication";
                        $phpmailer->MsgHTML($content);
                        $phpmailer->Send();
                        $phpmailer->clearAddresses();
                        sleep(2);
                    }
                }
            }
        }
    }
    $ids = implode(',', $logIds);
      $query = "Update {$wpdb->prefix}followers_log set status='1' where id in (" . $ids . ")";
    $results = $wpdb->query($query);
}

function getFlipbook($id) {
    global $wpdb;
    $query = "SELECT f.id,f.author_id,f.log,type from wp_followers_log f
        LEFT JOIN wp_author_followers a ON a.author_id=f.author_id
        where f.type='flipbook_upload' && a.follower_id='" . $id . "' && f.status ='0' && a.recieve_update='1'";

    $authors = $wpdb->get_results($query, OBJECT);

    $flipbookBlock = '';
    foreach ($authors as $author) {
        $authorData = get_user_by('ID', $author->author_id);
        $postIds = unserialize($author->log);
        $flipbookPostArgs = array(
            'post_type' => 'post',
            'author' => $authors->author_id,
            'order' => 'ASC',
            'orderby' => 'ID',
            'post__in' => $postIds
        );
        $flipbookBlock .= '<tr>
  <td>
 <div>Check out the latest publications from <a target="_blank" href="' . get_author_posts_url(get_the_author_meta('ID', $author->author_id)) . '">' . ucwords($authorData->display_name) . '</a>.</div>';

        $flippost_query = new WP_Query($flipbookPostArgs);
        if ($flippost_query->have_posts()) {
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

                $content = mb_strimwidth(strip_tags($content), 0, 80, '...');
                $flipbookBlock .= '
      <div style="border: 1px solid #C0C0C0; font-family: comic sans ms; font-size: 15px; width: 210; float: left; margin: 1px; padding: 5px;">
      <img src="' . $image . '" title="" height="150" width="200"/>
      <br>
      <b><a href="' . get_permalink($post->ID) . '" style="text-decoration: none;color:#444444;">' . ucwords($post->post_title) . '</a></b>'
                        . '<br>'
                        . '<img src="'.get_avatar_url(get_the_author_meta('ID', $user_ID)).'" height="20" width="20">'
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
        $flipbookBlock .= '</td>
  </tr>
  <tr>
  <td>&nbsp;</td>
  <tr>';
    }
    return $flipbookBlock;
}
