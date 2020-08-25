<?php
/**
 * Template Name: Submit Video
 * 
 * @package WordPress
 * @subpackage betube
 * @since betube
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);


if (!is_user_logged_in()) {
    global $redux_demo;
    $login = $redux_demo['login'];
    wp_redirect($login);
    exit;
}

global $redux_demo, $user_ID;
$user_info = get_userdata($user_ID);

$postTitleError = "";
$hasError = "";
$postError = "";
$betubeMultiPlayer = $redux_demo['betube-multi-player'];

$hdn_edit = "";
$thumb_id = '';
if (isset($_GET["edit"]) && trim($_GET["edit"]) != "") {
    $hdn_edit = trim($_GET["edit"]);

    $edit_post = get_post($hdn_edit);
    $edit_post_meta = get_post_meta($hdn_edit);
    $edit_post_category = wp_get_post_categories($hdn_edit);

    if (isset($edit_post_meta['_thumbnail_id'])) {
        $thumb_id = $edit_post_meta['_thumbnail_id'][0];
    }
}
$flipbook_type = "";
if (isset($_POST["fb_type"])) {
    $flipbook_type = $_POST["fb_type"];
} else if ($hdn_edit != "" && $edit_post_meta["flipbook_type"][0] != "") {
    $flipbook_type = $edit_post_meta["flipbook_type"][0];
}

$media = get_attached_media('image', 190);

if (isset($_POST['submitted']) && isset($_POST['post_nonce_field']) && wp_verify_nonce($_POST['post_nonce_field'], 'post_nonce')) {
//else if($hdn_edit == "" || ($hdn_edit != "" && count($files["name"]) > 0 && $files["name"][0] != "")) {

    $files = $_FILES["my_file_upload"];
    if (trim($_POST['postTitle']) === '') {
        $postError = 'Please enter Title';
        $hasError = true;
    } else if (trim($_POST['cat']) === '-1') {
        $postError = 'Please Select Category';
        $hasError = true;
    } else if ($hdn_edit == "" || ($hdn_edit != "" && count($files["name"]) > 0)) {

        if ($hdn_edit == "" && count($files["name"]) > 0 && $files["name"][0] == "") {
            $postError = 'Please upload image or pdf type file';
            $hasError = true;
        } else if ($flipbook_type == "1") {
            //Image type

            if ($hdn_edit == "" && count($files["name"]) == 1) {

                $postError = 'Atleast 2 images are required to generate flipbook';
                $hasError = true;
            } else if ($hdn_edit != "" && isset($_POST['hdn_img_remove'])) {
                $availImg = 0;
                foreach ($_POST['hdn_img_remove'] as $k1 => $v1) {
                    if ($v1 == 0) {
                        $availImg++;
                    }
                }

                if ($availImg <= 1) {
                    if (count($files["name"]) > 0 && $files["name"][0] == "") {
                        $postError = 'Atleast 2 images are required to generate flipbook';
                        $hasError = true;
                    } else if ($availImg == 0 && count($files["name"]) >= 2) {
                        foreach ($files['type'] as $key => $value) {
                            if ($value == "image/png" || $value == "image/jpeg" || $value == "image/gif" || $value == "image/bmp") {
                                //DO NOTHING
                            } else {
                                $postError = 'Valid file types are .jpg | .jpeg | .png | .gif';
                                $hasError = true;
                            }
                        }
                    } else if ($availImg == 1 && count($files["name"]) >= 1) {
                        foreach ($files['type'] as $key => $value) {
                            if ($value == "image/png" || $value == "image/jpeg" || $value == "image/gif" || $value == "image/bmp") {
                                //DO NOTHING
                            } else {
                                $postError = 'Valid file types are .jpg | .jpeg | .png | .gif';
                                $hasError = true;
                            }
                        }
                    } else {

                        $postError = 'Atleast 2 images are required to generate flipbook';
                        $hasError = true;
                    }
                }
            } else {
                foreach ($files['type'] as $key => $value) {
                    if ($value == "image/png" || $value == "image/jpeg" || $value == "image/gif" || $value == "image/bmp") {
                        //DO NOTHING
                    } else {
                        $postError = 'Valid file types are .jpg | .jpeg | .png | .gif';
                        $hasError = true;
                    }
                }
            }
        } else if ($flipbook_type == "2") {
            //PDF type
            $typeArr = explode('.', $files['name'][0]);
            $fileType = strtolower(end($typeArr));
            if ($fileType == 'pdf') {
                $files['type'][0] = "application/pdf";
            }
            if ($hdn_edit == "") {
                if (count($files['name']) > 1) {
                    $postError = 'Only one PDF file can be uploaded';
                    $hasError = true;
                } else if ($files['type'][0] != "application/pdf") {
                    $postError = 'Please upload .pdf type file';
                    $hasError = true;
                }
            } else if (isset($_POST['hdn_img_remove'])) {
                $availImg = 0;
                foreach ($_POST['hdn_img_remove'] as $k1 => $v1) {
                    if ($v1 == 0) {
                        $availImg++;
                    }
                }

                if ($availImg == 0) {
                    if (count($files['name']) > 1) {
                        $postError = 'Only one PDF file can be uploaded';
                        $hasError = true;
                    } else if ($files['type'][0] != "application/pdf") {
                        $postError = 'Please upload .pdf type file';
                        $hasError = true;
                    }
                }
            }
        }
    }
    //echo $postError." -- ".$hasError;exit;

    $postTitle = trim($_POST['postTitle']);

    if ($postError != '') {
        $_SESSION['error'] = $postError;
    }

    /* If there is no error then we move on next */
    if ($hasError != true) {
        /* If User Is Admin then Post Status will be Published */

        $post_information = array(
            'post_title' => esc_attr(strip_tags($_POST['postTitle'])),
            'post_content' => strip_tags(stripslashes($_POST['video-body']), '<a><h1><h2><h3><strong><b>'),
            'post-type' => 'post',
            'post_category' => array($_POST['cat']),
            'tags_input' => explode(',', $_POST['post_tags']),
            'comment_status' => 'open',
            'ping_status' => 'open',
            'post_status' => 'publish'
        );

        if ($_POST["fb_pubdate"] != "") {
            $post_information["post_status"] = "draft";
        }

        $post_id = "";
        if ($hdn_edit == "") {
            $currentDateTime = date('Y-m-d H:i:s');
            $newflipbook_array = array();
            $post_id = wp_insert_post($post_information);
            $newflipbook_array[] = $post_id;
            $getFlipbookData = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}followers_log WHERE `author_id` = $user_ID AND `status` = '0' AND `type` = 'flipbook_upload'", OBJECT);
            if (count($getFlipbookData) > 0) {
                $flipbookData = unserialize($getFlipbookData->log);
                foreach ($newflipbook_array as $key => $value) {
                    //echo $value;exit;
                    if (!in_array($value, $flipbookData)) {
                        array_push($flipbookData, $value);
                    }
                }
                $serilizeflipbookData = serialize($flipbookData);
                $wpdb->query("UPDATE {$wpdb->prefix}followers_log SET log= '$serilizeflipbookData', updated_date= '$currentDateTime' WHERE `id` = $getFlipbookData->id");
//                $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}followers_log SET log= '$serilizeflipbookData', updated_date= '$currentDateTime' WHERE `id` = $getFlipbookData->id"));
            } else {
                $flipbookSerilizeData = serialize($newflipbook_array);
                $authorflipbook_insert = ("INSERT into {$wpdb->prefix}followers_log (author_id,type,log,created_date,updated_date)value('" . $user_ID . "','flipbook_upload','" . $flipbookSerilizeData . "','" . $currentDateTime . "','" . $currentDateTime . "')");
                $wpdb->query($authorflipbook_insert);
            }

            add_post_meta($post_id, "flipbook_type", $_POST["fb_type"]);
            add_post_meta($post_id, "flipbook_downloadable", $_POST["fb_downloadable"]);
            add_post_meta($post_id, "enable_flipbook_comment", $_POST["fb_comment"]);
            add_post_meta($post_id, "flipbook_publish_date", $_POST["fb_pubdate"]);
        } else {

            $post_information['ID'] = $hdn_edit;
            wp_update_post($post_information);

            if ($thumb_id != "") {
                set_post_thumbnail($hdn_edit, $thumb_id);
            }
            if (isset($_POST["fb_type"])) {
                update_post_meta($hdn_edit, "flipbook_type", $_POST["fb_type"]);
            }
            update_post_meta($hdn_edit, "flipbook_downloadable", $_POST["fb_downloadable"]);
            update_post_meta($hdn_edit, "enable_flipbook_comment", $_POST["fb_comment"]);
            update_post_meta($hdn_edit, "flipbook_publish_date", $_POST["fb_pubdate"]);
            $post_id = $hdn_edit;

            if (isset($_POST['hdn_img_remove']) && count($_POST['hdn_img_remove']) > 0) {
                foreach ($_POST['hdn_img_remove'] as $k => $v) {
                    if ($v == 1) {
                        wp_delete_attachment($k);
                    }
                }
            }

            if ($_POST["hdn_feat_remove"] == "1") {
                delete_post_thumbnail($hdn_edit);
            }
        }

        //$post_type = 'flipbook';

        /* $query = "UPDATE {$wpdb->prefix}posts SET post_type='" . $post_type . "' WHERE id='" . $post_id . "' LIMIT 1";

          GLOBAL $wpdb;
          $wpdb->query($query); */
        $permalink = get_permalink($post_id);

        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            if ($_FILES) {
                $uploaddir = wp_upload_dir();


                $files = $_FILES["my_file_upload"];
                foreach ($files['name'] as $key => $value) {
                    $typeArr = explode('.', $files['name'][$key]);
                    $fileType = strtolower(end($typeArr));
                    if ($fileType == 'pdf') {
                        $files['type'][$key] = "application/pdf";
                    }
                    if ($files['name'][$key]) {
                        $file = array(
                            'name' => $files['name'][$key],
                            'type' => $files['type'][$key],
                            'tmp_name' => $files['tmp_name'][$key],
                            'error' => $files['error'][$key],
                            'size' => $files['size'][$key]
                        );
                        $_FILES = array("my_file_upload" => $file);
                        foreach ($_FILES as $file => $array) {

                            $newupload = my_handle_attachment($file, $post_id);
                        }
                    }
                }

                $file = $_FILES['featured_image']["tmp_name"];

                if ($file != "") {
                    $uploadfile = $uploaddir['path'] . '/' . basename($_FILES['featured_image']["name"]);

                    move_uploaded_file($file, $uploadfile);
                    $filename = basename($uploadfile);

                    $wp_filetype = wp_check_filetype(basename($filename), null);

                    $attachment = array(
                        'post_mime_type' => $wp_filetype['type'],
                        'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
                        'post_content' => '',
                        'post_status' => 'inherit',
                    );

                    $attach_id = wp_insert_attachment($attachment, $uploadfile);
                    set_post_thumbnail($post_id, $attach_id);
                } else {
                    if ($flipbook_type == "2") {
                        $attaches = get_posts('post_parent=' . $post_id . '&numberposts=-1&post_type=attachment&post_mime_type=application/pdf&orderby=menu_order&order=ASC');

                        if ($attaches): foreach ($attaches as $attach):
                                if ($thumb_id = get_post_meta($attach->ID, '_thumbnail_id', true)) { // if pdf has thumbnail
                                    update_post_meta($post_id, '_thumbnail_id', $thumb_id);
                                    break;
                                }
                            endforeach;
                        endif;
                    }
                }
            }
        }

        /* If Post Insert Successfully */
        $i = 0;
        $flipbookContent[] = array('title' => '',
            'src' => '',
            'thumb' => '',
            'htmlContent' => '');
        $type = 'jpg';
        $pdfUrl = '';
        $media = get_attached_media('image', $post_id);
        if (count($media) > 0) {
            foreach ($media as $key => $val) {
                $s3Media = get_post_meta($val->ID);
                $mediaArr = unserialize($s3Media['amazonS3_info'][0]);
                $flipbookContent[$i] = array(
                    'title' => $val->post_title,
//                    'src' => $val->guid,
//                    'thumb' => $val->guid,
                    'src' => S3_UPLOADS . $mediaArr['key'],
                    'thumb' => S3_UPLOADS . $mediaArr['key'],
                    'htmlContent' => '',);
                $i++;
            }
        } else {
            $type = 'pdf';
            $media = get_attached_media('application/pdf', $post_id);
            foreach ($media as $key => $val) {
                $s3Media = get_post_meta($val->ID);
                $mediaArr = unserialize($s3Media['amazonS3_info'][0]);
//                $pdfUrl = $val->guid;
                $pdfUrl = S3_UPLOADS . $mediaArr['key'];
            }
        }

        $flipbookId = "";
        if ($hdn_edit == "") {
            $arr = get_option('real3dflipbooks_ids');

            if (count($arr) == 0) {
                $flipbookId = 1;
            } else {
                $flipbookId = end($arr) + 1;
            }
            array_push($arr, $flipbookId);
            //print_r($arr);exit;

            update_option('real3dflipbooks_ids', ($arr));
        } else {
            $flipbookId = $edit_post->flipbook_id;
        }

        $fb_download = "false";
        $fb_download_url = "";
        if ($_POST["fb_downloadable"] == 1) {
            $fb_download = "true";
            $fb_download_url = get_site_url() . '/download-flipbook?id=' . $post_id;
        }

        $flipbookArr = array(
            'id' => $flipbookId,
            'name' => trim($_POST['postTitle']),
            'pages' => $flipbookContent,
            'date' => date('d-m-Y H:i:s'),
            'pdfUrl' => $pdfUrl,
            'type' => $type,
            'mode' => 'normal',
            'menuSelector' => '',
            'zIndex' => 'auto',
            'viewMode' => '3d',
            'pageMode' => 'singlePage',
            'singlePageMode' => 'false',
            'sideNavigationButtons' => 'true',
            'hideMenu' => 'false',
            'sound' => 'true',
            'pageFlipDuration' => '1',
            'tableOfContentCloseOnClick' => 'true',
            'allowPageScroll' => 'vertical',
            'pageTextureSize' => '2048',
            'height' => '400',
            'fitToWindow' => 'false',
            'fitToParent' => 'false',
            'fitToHeight' => 'false',
            'offsetTop' => '0',
            'responsiveHeight' => 'true',
            'aspectRatio' => '2',
            'thumbnailsOnStart' => 'false',
            'contentOnStart' => 'false',
            'autoplayOnStart' => 'false',
            'autoplayInterval' => '3000',
            'rightToLeft' => 'false',
            'loadAllPages' => 'false',
            'pageWidth' => '',
            'pageHeight' => '',
            'thumbnailWidth' => '100',
            'thumbnailHeight' => '141',
            'zoomLevels' => '0.9,2,5',
            'zoomDisabled' => 'false',
            'startPage' => '1',
            'deeplinking' => array('enabled' => 'false', 'prefix' => ''),
            'logoImg' => '',
            'logoUrl' => '',
            'logoCSS' => 'position:absolute;',
            'pdfPageScale' => '',
            'singlePageModeIfMobile' => 'false',
            'pdfBrowserViewerIfMobile' => 'false',
            'pdfBrowserViewerFullscreen' => 'true',
            'pdfBrowserViewerFullscreenTarget' => '_blank',
            'btnTocIfMobile' => '',
            'btnThumbsIfMobile' => 'true',
            'btnShareIfMobile' => 'true',
            'btnDownloadPagesIfMobile' => 'true',
            'btnDownloadPdfIfMobile' => 'true',
            'btnSoundIfMobile' => 'false',
            'btnExpandIfMobile' => 'true',
            'btnPrintIfMobile' => 'false',
            'lightboxBackground' => 'rgb(81, 85, 88)',
            'lightboxCssClass' => '',
            'lightboxContainerCSS' => 'display:inline-block;padding:10px;',
            'lightboxThumbnailUrl' => 'http://localhost/youflips/wp-content/uploads/2017/04/light-color-wallpaper-3-300x169.jpg',
            'lightboxThumbnailUrlCSS' => 'display:block;',
            'lightboxText' => '',
            'lightboxTextCSS' => 'display:block;',
            'lightboxTextPosition' => 'top',
            'lightBoxOpened' => 'false',
            'lightBoxFullscreen' => 'false',
            'lightboxCloseOnClick' => 'false',
            'lights' => 'true',
            'spotlightColor' => '#FFFFFF',
            'ambientLightColor' => '3355443',
            'spotlightIntensity' => '0.14',
            'responsiveView' => 'true',
            'responsiveViewTreshold' => '768',
            'minPixelRatio' => '1',
            'pageHardness' => '2',
            'coverHardness' => '2',
            'pageSegmentsW' => '6',
            'pageSegmentsH' => '1',
            'pageRoughness' => '1',
            'pageMetalness' => '0',
            'pageMiddleShadowSize' => '2',
            'pageMiddleShadowColorL' => '#999999',
            'pageMiddleShadowColorR' => '#777777',
            'antialias' => 'false',
            'pan' => '0',
            'tilt' => '0',
            'rotateCameraOnMouseDrag' => 'true',
            'panMax' => '20',
            'panMin' => '-20',
            'tiltMax' => '0',
            'tiltMin' => '-60',
            'rotateCameraOnMouseMove' => 'false',
            'panMax2' => '2',
            'panMin2' => '-2',
            'tiltMax2' => '0',
            'tiltMin2' => '-5',
            'webglMinAndroidVersion' => '404',
            'currentPage' => array('enabled' => 'true', 'title' => 'Current Page'),
            'btnAutoplay' => array('enabled' => 'true', 'icon' => 'fa-play', 'title' => 'Autoplay'),
            'btnNext' => array('enabled' => 'true', 'icon' => 'fa-chevron-right',
                'title' => 'Next Page'),
            'btnLast' => array('enabled' => 'true', 'icon' => 'fa-step-forward',
                'title' => 'Last Page'),
            'btnPrev' => array('enabled' => 'true', 'icon' => 'fa-chevron-left',
                'title' => 'Next Page'),
            'btnFirst' => array('enabled' => 'true', 'icon' => 'fa-step-backward',
                'title' => 'First Page'),
            'btnZoomIn' => array('enabled' => 'true', 'icon' => 'fa-plus', 'title' => 'Zoom in'),
            'btnZoomOut' => array('enabled' => 'true', 'icon' => 'fa-minus', 'title' => 'Zoom out'),
            'btnToc' => array('enabled' => 'true', 'icon' => 'fa-list-ol', 'title' => 'Table of content'),
            'btnThumbs' => array('enabled' => 'true', 'icon' => 'fa-th-large', 'title' => 'Pages'),
            'btnShare' => array('enabled' => 'true', 'icon' => 'fa-share-alt', 'title' => 'Share'),
            'btnDownloadPages' => array('enabled' => $fb_download, 'url' => $fb_download_url, 'icon' => 'fa-download',
                'title' => 'Download pages'),
            'btnDownloadPdf' => array('enabled' => 'false', 'url' => '', 'icon' => 'fa-file',
                'title' => 'Download PDF', 'forceDownload' => 'true', 'openInNewWindow' => 'true'),
            'btnSound' => array('enabled' => 'true', 'icon' => 'fa-volume-up', 'title' => 'Sound'),
            'btnExpand' => array('enabled' => 'true', 'icon' => 'fa-expand', 'iconAlt' => 'fa-compress',
                'title' => 'Toggle fullscreen'),
            'btnExpandLightbox' => array('enabled' => 'true', 'icon' => 'fa-expand',
                'iconAlt' => 'fa-compress', 'title' => 'Toggle fullscreen'),
            'btnPrint' => array('enabled' => 'true', 'icon' => 'fa-print', 'title' => 'Print'),
            'google_plus' => array('enabled' => 'true', 'url' => ''),
            'twitter' => array('enabled' => 'true', 'url' => '', 'description' => ''),
            'facebook' => array('enabled' => 'true', 'url' => '', 'description' => '',
                'title' => '', 'image' => '', 'caption' => ''),
            'pinterest' => array('enabled' => 'true', 'url' => '', 'image' => '',
                'description' => ''),
            'email' => array('enabled' => 'true', 'url' => '', 'description' => ''),
            'skin' => 'light',
            'backgroundColor' => 'rgb(81, 85, 88)',
            'backgroundPattern' => '',
            'backgroundTransparent' => '',
            'menuBackground' => '',
            'menuShadow' => '0 0 6px rgba(0,0,0,0.16), 0 0 6px rgba(0,0,0,0.23)',
            'menuMargin' => '0',
            'menuPadding' => '0',
            'menuOverBook' => 'false',
            'menuAlignHorizontal' => 'center',
            'btnColor' => '',
            'btnBackground' => 'none',
            'btnRadius' => '0',
            'btnMargin' => '0',
            'btnSize' => '12',
            'btnPaddingV' => '10',
            'btnPaddingH' => '10',
            'btnShadow' => '',
            'btnTextShadow' => '',
            'btnBorder' => '',
            'sideBtnColor' => '',
            'sideBtnBackground' => 'rgba(255,255,255,.2)',
            'sideBtnRadius' => '0',
            'sideBtnMargin' => '0',
            'sideBtnSize' => '40',
            'sideBtnPaddingV' => '',
            'sideBtnPaddingH' => '',
            'sideBtnShadow' => '',
            'sideBtnTextShadow' => '',
            'sideBtnBorder' => '',
            'currentPagePositionV' => 'top',
            'currentPagePositionH' => 'left',
            'currentPageMarginV' => '5',
            'currentPageMarginH' => '5',
            'submit' => 'save flipbook'
        );

        if ($hdn_edit == "") {
            add_option('real3dflipbook_' . $flipbookId, ($flipbookArr));
            $query = "UPDATE {$wpdb->prefix}posts SET flipbook_id='" . $flipbookId . "' WHERE id='" . $post_id . "' LIMIT 1";
            GLOBAL $wpdb;
            $wpdb->query($query);
        } else {
            update_option('real3dflipbook_' . $flipbookId, ($flipbookArr));
        }

        $pageLink = get_page_link(33);
        wp_redirect($pageLink);
        exit;
    }/* End If there is no error */
}
get_header();
betube_breadcrumbs();
?>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
    jQuery(document).ready(function () {
        jQuery("#fb_pubdate").datepicker({dateFormat: 'dd/mm/yy', minDate: 1});
    });
</script>

<?php while (have_posts()) : the_post(); ?>
    <?php
    $page = get_page($post->ID);
    $current_page_id = $page->ID;
    $current_user = wp_get_current_user();
    $user_ID = $current_user->ID;
    $betubeProfileIMG = get_user_meta($user_ID, "betube_author_profile_bg", true);
    $profileCoverPhoto = get_option('profile_cover_photo');
    ?>
    <?php if ($betubeProfileIMG == '') {
        ?>
        <section class="topProfile topProfile-inner" style="background: url('<?php echo $profileCoverPhoto; ?>') no-repeat;">
        <?php } else {
            ?>
            <section class="topProfile topProfile-inner" style="background: url('<?php echo $betubeProfileIMG; ?>') no-repeat;">
            <?php } ?> 
            <div class="main-text text-center">
                <div class="row">
                    <div class="large-12 columns">
                        <?php
                        $firstText = get_the_author_meta('firsttext', $user_ID);
                        $secondText = get_the_author_meta('secondtext', $user_ID);
                        if ($firstText != '') {
                            ?>
                            <h3><?php echo $betubeFirstTXT = the_author_meta('firsttext', $user_ID); ?></h3>
                            <?php
                        }
                        if ($secondText != '') {
                            ?>
                            <h1><?php echo $betubeFirstTXT = the_author_meta('secondtext', $user_ID); ?></h1>
                        <?php } ?>
                    </div><!--large-12-->
                </div><!--row-->
            </div><!--main-text-->
            <div class="profile-stats">
                <div class="row secBg">
                    <div class="large-12 columns">
                        <?php
                        $authorAvatarURL = get_user_meta($user_ID, "betube_author_avatar_url", true);

                        if (!empty($authorAvatarURL)) {
                            ?>
                            <div class="profile-author-img">
                                <img src="<?php echo esc_url($authorAvatarURL); ?>" alt="author">
                            </div>
                            <?php
                        } else {
                            $authorID = $current_user->user_email;
                            $avatar_url = betube_get_avatar_url($authorID, $size = '150');
                            ?>
                            <div class="profile-author-img">
                                <img src="<?php echo esc_url($avatar_url); ?>" alt="profile author img">
                            </div><!--profile-author-img-->
                            <?php
                        }
                        ?>
                        
                        <div class="profile-author-name float-left">
                            <h4><?php echo $betubeDisplayName = get_the_author_meta('display_name', $user_ID); ?></h4>
                            <?php $betubeRegDate = get_the_author_meta('user_registered', $user_ID); ?>
                            <?php $dateFormat = get_option('date_format'); ?>
                            <p><?php esc_html_e("Join Date", 'betube') ?> : <span><?php echo date($dateFormat, strtotime($betubeRegDate)); ?></span></p>
                        </div><!--profile-author-name-->					
                        <div class="profile-author-stats float-right">
                            <ul class="menu">
                                <li>
                                    <div class="icon float-left">
                                        <i class="flaticon-open-book"></i>
                                    </div>
                                    <div class="li-text float-left">
                                        <p class="number-text"><?php echo custom_get_user_posts_count($user_ID, array('post_type' => 'post', 'post_status' => array('draft', 'publish'))); ?></p>
                                        <span><?php esc_html_e("Flipbooks", 'betube') ?></span>
                                    </div>
                                </li><!--Total Flipbooks-->
                                <li>
                                    <div class="icon float-left">
                                        <i class="flaticon-like"></i>
                                    </div>
                                    <div class="li-text float-left">
                                        <p class="number-text">
                                            <?php
                                            global $current_user;
                                            wp_get_current_user();
                                            $user_id = $current_user->ID;
                                            echo $totalfavorite = betubeFavoriteCount($user_id);
                                            ?>
                                        </p>
                                        <span><?php esc_html_e("Favorites", 'betube') ?></span>
                                    </div>
                                </li><!--Total favorites-->

                                <li>
                                    <div class="icon float-left">
                                        <i class="flaticon-comments"></i>
                                    </div>
                                    <div class="li-text float-left">
                                        <?php
                                        $args = array(
                                            'user_id' => get_current_user_id(), // use user_id
                                            'count' => true, //return only the count
                                            'status' => 'approve'
                                        );
                                        $betubeUsercomments = get_comments($args);
                                        ?>
                                        <p class="number-text"><?php echo $betubeUsercomments; ?></p>
                                        <span><?php esc_html_e("Comments", 'betube') ?></span>
                                    </div>
                                </li><!--Total comments-->
                            </ul>
                        </div><!--profile-author-stats-->
                       
                    </div><!--large-12-->
                </div><!--row secBg-->
            </div><!--profile-stats-->
        </section><!--topProfile-->
        <div class="clearfix"></div>
        <div class="row">
            <!--left sidebar-->
            <div class="large-4 columns leftsidebar">
                <?php include_once 'profile-left-sidebar.php'; ?>
            </div><!--Large4-->
            <!--End left sidebar-->
            <!-- right side content area -->
            <div class="large-8 columns profile-inner">
                <section class="submit-post">
                    <div class="row secBg">
                        <div class="large-12 columns">
                            <div class="heading">
                                <?php
                                if ($hdn_edit == "") {
                                    echo '<i class="fa fa-plus-circle"></i><h4>Add new Flipbook</h4>';
                                } else {
                                    echo '<i class="fa fa-pencil-square-o"></i><h4>Edit Flipbook</h4>';
                                }

                                $imgArr = $media = get_attached_media('image');
                                ?>
                            </div><!--heading-->
                            <div class="row">
                                <div class="large-12 columns">
                                    <form data-abide novalidate method="POST" id="submitNewPost" enctype="multipart/form-data">
                                        <input type="hidden" name="hdn_edit" value="<?php echo $hdn_edit; ?>" />
                                        <?php
                                        if (isset($_SESSION['error']) && $_SESSION['error'] != '') {
                                            ?>
                                            <div data-abide-error class="alert callout">
                                                <p>
                                                    <i class="fa fa-exclamation-triangle"></i>
                                                    <?php
                                                    echo $_SESSION['error'];
                                                    unset($_SESSION['error']);
                                                    ?>

                                                </p>
                                            </div><!--alert callout--><?php
                                        }
                                        ?>

                                        <div class="row">
                                            <div class="large-12 columns">
                                                <?php
                                                $postTitle = "";
                                                if ($hdn_edit != "") {
                                                    if (isset($_POST['postTitle'])) {
                                                        $postTitle = trim($_POST['postTitle']);
                                                    } else {
                                                        $postTitle = $edit_post->post_title;
                                                    }
                                                } else if (isset($_POST['postTitle'])) {
                                                    $postTitle = trim($_POST['postTitle']);
                                                }
                                                ?>
                                                <label><?php esc_html_e("Title", 'betube') ?>:
                                                    <input type="text" id="postTitle" name="postTitle" placeholder="<?php esc_html_e("Enter your Flipbook Title", 'betube') ?>..." required value="<?php echo $postTitle; ?>">
                                                    <span class="form-error">
                                                        <?php esc_html_e("Please enter flipbook title.", 'betube') ?>
                                                    </span>
                                                </label>
                                            </div><!--End Title-->

                                            <div class="large-12 columns">
                                                <?php
                                                $postContent = "";
                                                if ($hdn_edit != "") {
                                                    if (isset($_POST['video-body'])) {
                                                        $postContent = trim($_POST['video-body']);
                                                    } else {
                                                        $postContent = $edit_post->post_content;
                                                    }
                                                } else if (isset($_POST['video-body'])) {
                                                    $postContent = trim($_POST['video-body']);
                                                }
                                                ?>
                                                <label><?php esc_html_e("Description", 'betube') ?>:
                                                    <textarea name="video-body" id="video-body"><?php echo $postContent; ?></textarea>
                                                </label>
                                            </div><!--End Description-->

                                            <div class="large-12 columns">
                                                <?php
                                                $postCatSelected = "";
                                                if ($hdn_edit != "") {
                                                    if (isset($_POST['cat'])) {
                                                        $postCatSelected = trim($_POST['cat']);
                                                    } else {
                                                        $postCatSelected = $edit_post_category[0];
                                                    }
                                                } else if (isset($_POST['cat'])) {
                                                    $postCatSelected = trim($_POST['cat']);
                                                }
                                                ?>		
                                                <div class="post-category">
                                                    <label><?php esc_html_e("Choose Category", 'betube') ?>:
                                                        <?php wp_dropdown_categories('show_option_none=Category&hide_empty=0&hierarchical=1&orderby=name&id=catID&required=true&selected=' . $postCatSelected); ?>
                                                    </label>
                                                </div>

                                            </div><!--End SEO Meta, Images, Category-->	

                                            <div class="large-12 columns">
                                                <?php
                                                $postFbType = "";
                                                $dis = "";
                                                if ($hdn_edit != "") {
                                                    $postFbType = get_field("flipbook_type", $hdn_edit);
                                                    $dis = "disabled";
                                                } else if (isset($_POST['fb_type'])) {
                                                    $postFbType = $_POST['fb_type'];
                                                }
                                                ?>
                                                <label><?php esc_html_e("Flipbook Type", 'betube') ?>:
                                                    <select required="" name="fb_type" id="fb_type" class="postform" style="height:3em;" <?php echo $dis; ?>>
                                                        <option class="level-0" value="1" <?php
                                                        if ($postFbType == "1") {
                                                            echo 'selected="selected"';
                                                        }
                                                        ?> >Image</option>
                                                        <option class="level-0" value="2" <?php
                                                        if ($postFbType == "2") {
                                                            echo 'selected="selected"';
                                                        }
                                                        ?> >PDF</option>
                                                    </select>
                                                </label>
                                            </div>

                                            <div class="large-12 columns">
                                                <?php
                                                $featImage = "";
                                                if ($hdn_edit != "") {
                                                    $featImage = get_the_post_thumbnail_url($hdn_edit);
                                                }
                                                ?>
                                                <label><?php esc_html_e("Featured Image", 'betube') ?>:</label>
                                                <div class="upload-video browse-wrap">
                                                    <input type="hidden" id="hdn_feat_remove" name="hdn_feat_remove" value="0" />
                                                    <label class="button" for="featured_image">Upload File</label>
                                                    <input type="file" class="show-for-sr" id="featured_image" name="featured_image" />
                                                    <span class="upload-path" style="padding-right:0px !important;">No file chosen</span>
                                                </div>


                                                <?php
                                                if ($hdn_edit != "" && $featImage != "") {
                                                    ?>
                                                    <div class="upload-video" id="featImg">
                                                        <img src="<?php echo $featImage; ?>" alt="" class="attachment-post-thumbnail size-post-thumbnail wp-post-image" width="160"/><br/><a href="javascript:removeFeatImage();" style="float:right;">Remove</a>
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                            </div>

                                            <div class="large-12 columns">
                                                <label><?php esc_html_e("Upload File(s)", 'betube') ?>:</label>

                                                <div class="upload-video browse-wrap">
                                                    <label class="button" for="my_file_upload">Upload File(s)</label>
                                                    <input type="file" class="show-for-sr" id="my_file_upload"  multiple="" name="my_file_upload[]">
                                                    <span class="upload-path" style="padding-right:0px !important;">No file chosen</span>
                                                </div>

                                                <?php
                                                if ($hdn_edit != "") {
                                                    $pdfUrl = '';
                                                    $pdfID = '';
                                                    $postImgArr = Array();
                                                    $media = get_attached_media('image', $hdn_edit);
                                                    //echo "<pre>";print_r($media);
                                                    if (count($media) > 0) {
                                                        foreach ($media as $key => $val) {
                                                            $s3Media = get_post_meta($val->ID);
                                                            $mediaArr = unserialize($s3Media['amazonS3_info'][0]);
                                                            $postImgArr[$val->ID] = S3_UPLOADS . $mediaArr['key'];
                                                        }
                                                    } else {
                                                        $type = 'pdf';
                                                        $media = get_attached_media('application/pdf', $hdn_edit);
                                                        foreach ($media as $key => $val) {
                                                            $s3Media = get_post_meta($val->ID);
                                                            $mediaArr = unserialize($s3Media['amazonS3_info'][0]);
                                                            $pdfUrl = S3_UPLOADS . $mediaArr['key'];
                                                            $pdfID = $val->ID;
                                                        }
                                                    }

                                                    if (count($postImgArr) > 0 || $pdfUrl != "") {
                                                        echo '<div style="clear:both;"></div>';
                                                        echo '<div class="upload-video">';
                                                        if (count($postImgArr) > 0) {
                                                            foreach ($postImgArr as $key => $val) {
                                                                echo '<input type="hidden" id="hdn_img_remove_' . $key . '" name="hdn_img_remove[' . $key . ']" value="0" /><div style="float:left;" id="div_img_remove_' . $key . '"><img src="' . $val . '" alt="" class="attachment-post-thumbnail size-post-thumbnail wp-post-image" style="margin:5px;width:160px;height:160px;"/><br/><a href="javascript:removeImage(' . $key . ');" style="float:right;">Remove</a></div>';
                                                            }
                                                        } else if ($pdfUrl != "") {
                                                            echo '<input type="hidden" id="hdn_img_remove_' . $pdfID . '" name="hdn_img_remove[' . $pdfID . ']" value="0" /><div style="float:left;" id="div_img_remove_' . $pdfID . '"><a href="' . $pdfUrl . '" target="_blank"><img src="' . get_stylesheet_directory_uri() . '/assets/images/pdf-icon.png" alt="" class="attachment-post-thumbnail size-post-thumbnail wp-post-image" width="80" style="margin:5px;" /></a><br/><a href="javascript:removeImage(' . $pdfID . ');" style="float:right;">Remove</a></div>';
                                                        }
                                                    }
                                                }
                                                ?>


                                            </div>

                                            <div class="large-12 columns">
                                                <?php
                                                
                                                $postFbDownloadable = "";
                                                if ($hdn_edit != "") {
                                                    if (isset($_POST['fb_downloadable'])) {
                                                        $postFbDownloadable = $_POST['fb_downloadable'];
                                                    } else {
                                                        $postFbDownloadable = get_field("flipbook_downloadable", $hdn_edit);
                                                    }
                                                } else if (isset($_POST['fb_downloadable'])) {
                                                        $postFbDownloadable = $_POST['fb_downloadable'];
                                                }
                                                ?>
                                                <label><?php esc_html_e("Is Flipbook Downloadable?", 'betube') ?>:
                                                    <select required="" name="fb_downloadable" id="fb_downloadable" class="postform" style="height:3em;">
                                                        <option class="level-0" value="0" <?php
                                                        if ($postFbDownloadable == "0") {
                                                            echo 'selected="selected"';
                                                        }
                                                        ?> >No</option>
                                                        <option class="level-0" value="1" <?php
                                                        if ($postFbDownloadable == "1") {
                                                            echo 'selected="selected"';
                                                        }
                                                        ?>>Yes</option>
                                                    </select>
                                                </label>
                                            </div>
                                            
                                            <div class="large-12 columns">
                                                <?php
                                                $postFbcomment = "";
                                                if ($hdn_edit != "") {
                                                    if (isset($_POST['fb_comment'])) {
                                                        $postFbcomment = $_POST['fb_comment'];
                                                    } else {
                                                         $postFbcomment = get_field("enable_flipbook_comment", $hdn_edit);
                                                    }
                                                } else if (isset($_POST['fb_comment'])) {
                                                         $postFbcomment = $_POST['fb_comment'];
                                                }
                                                ?>
                                                <label><?php esc_html_e("Can Registered User Comment On Flipbook?", 'betube') ?>:
                                                    <select required="" name="fb_comment" id="fb_comment" class="postform" style="height:3em;">
                                                        
                                                        <option class="level-0" value="yes" <?php
                                                        if ($postFbcomment == "yes") {
                                                            echo 'selected="selected"';
                                                        }
                                                        ?>>Yes</option>
                                                        <option class="level-0" value="no" <?php
                                                        if ($postFbcomment == "no") {
                                                            echo 'selected="selected"';
                                                        }
                                                        ?> >No</option>
                                                    </select>
                                                </label>
                                            </div>

                                            <div class="large-12 columns">
                                                <?php
                                                $postFbPubDate = "";
                                                if ($hdn_edit != "") {
                                                    if (isset($_POST['fb_pubdate'])) {
                                                        $postFbPubDate = $_POST['fb_pubdate'];
                                                    } else {
                                                        $postFbPubDate = get_field("flipbook_publish_date", $hdn_edit);
                                                    }
                                                } else if (isset($_POST['fb_pubdate'])) {
                                                    $postFbPubDate = $_POST['fb_pubdate'];
                                                }
                                                ?>
                                                <label><?php esc_html_e("Flipbook Publish Date", 'betube') ?>:
                                                    <input type="text" id="fb_pubdate" name="fb_pubdate" value="<?php echo $postFbPubDate; ?>">
                                                </label>
                                            </div>

                                            <?php $betubeTags = $redux_demo['betube-tags-on']; ?>
                                            <?php if ($betubeTags == 1) { ?>
                                                <div class="large-12 columns">
                                                    <?php
                                                    $postFbTags = "";
                                                    if ($hdn_edit != "") {
                                                        if (isset($_POST['post_tags'])) {
                                                            $postFbTags = trim($_POST['post_tags']);
                                                        } else {
                                                            $postFbTags = implode(", ", wp_get_post_tags($hdn_edit, array("fields" => "names")));
                                                        }
                                                    } else if (isset($_POST['post_tags'])) {
                                                        $postFbTags = trim($_POST['post_tags']);
                                                    }
                                                    ?>
                                                    <label><?php esc_html_e("Tags", 'betube') ?>:
                                                        <input type="text" name="post_tags" id="post_tags" placeholder="<?php esc_html_e("Tags: video, movie, cartoon", 'betube') ?>" required value="<?php echo $postFbTags; ?>">
                                                        <span class="form-error">
                                                            <?php esc_html_e("Please enter tags.", 'betube') ?>
                                                        </span>
                                                    </label> 
                                                </div><!--End Tags-->
                                            <?php } ?>
                                        </div>

                                        <div class="large-12 columns">
                                            <?php wp_nonce_field('post_nonce', 'post_nonce_field'); ?>
                                            <input type="hidden" name="submitted" id="submitted" value="true" />
                                            <button class="button expanded" type="submit" name="submit"><?php esc_html_e("Submit", 'betube') ?></button>
                                        </div><!--Publish Button-->	
                                    </form><!--End Form-->
                                </div><!--Row-->
                            </div><!--large-12 columns-->
                        </div><!--End Row-->
                    </div><!--large-12-->
            </div><!--row secBg-->
        </div><!--Large8-->
        <!-- right side content area -->
    </div>
<?php endwhile; ?>
<script>
    var image_custom_uploader;
    var $thisItem = '';

    function removeFeatImage() {
        jQuery("#hdn_feat_remove").val("1");
        jQuery("#featImg").remove();
    }

    function removeImage(id) {
        jQuery("#hdn_img_remove_" + id).val("1");
        jQuery("#div_img_remove_" + id).remove();
    }

    jQuery('input[type="file"]').change(function () {
        jQuery(this).parent().find('.upload-path').text(jQuery(this)[0].files.length + " file(s) selected.");
    });

    jQuery(document).on('click', '.upload-featured-image', function (e) {
        e.preventDefault();
        $thisItem = jQuery(this);
        $form = jQuery('#submitNewPost');

        //Extend the wp.media object
        image_custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });

        //When a file is selected, grab the URL and set it as the text field's value
        image_custom_uploader.on('select', function () {
            attachment = image_custom_uploader.state().get('selection').first().toJSON();
            var url = '';
            url = attachment['url'];
            var attachId = '';
            attachId = attachment['id'];
            $thisItem.parent().parent().find("img.featuredIMG").attr({
                src: url
            });
            $form.parent().parent().find(".featured-image-url").attr({
                value: url
            });
            $form.parent().parent().find(".criteria-image-id").attr({
                value: attachId
            });
        });

        //Open the uploader dialog
        image_custom_uploader.open();
    });
</script>
<?php get_footer(); ?>
