<?php
/**
 * Template Name: Download
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that other
 * 'pages' on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage betube
 * @since betube 1.0
 */

$id = trim($_GET['id']);

$fb = get_post($id);
$fb_post_meta = get_post_meta($id);

if($fb_post_meta["flipbook_type"][0] == 2) {
	//PDF case
	
	$media = get_attached_media('application/pdf', $id);
	$pdfUrl = "";
    foreach ($media as $key => $val) {
        $s3Media = get_post_meta($val->ID);
        $mediaArr = unserialize($s3Media['amazonS3_info'][0]);
        $pdfUrl = S3_UPLOADS . $mediaArr['key'];
    }
    
    header("Content-Type: application/pdf");

	header("Content-Disposition: attachment; filename=download.pdf");   
	//header("Content-Type: application/octet-stream");
	//header("Content-Type: application/download");
	//header("Content-Description: File Transfer");            
	//header("Content-Length: " . filesize($pdfUrl));
	flush();
		
	$fp = fopen($pdfUrl, "r");
	while (!feof($fp))
	{
    	echo fread($fp, 65536);
    	flush();
	} 
	fclose($fp);
	
} else {
	//Images case
	
	$postImgArr = array();
	$media = get_attached_media('image', $id);
    if (count($media) > 0) {
        foreach ($media as $key => $val) {
            $s3Media = get_post_meta($val->ID);
            $mediaArr = unserialize($s3Media['amazonS3_info'][0]);
            $postImgArr[$val->ID] = S3_UPLOADS . $mediaArr['key'];
        }
    }
	
	if(count($postImgArr) > 0) {
	$zip = new ZipArchive();
	$f = time();
	$zip_name = get_stylesheet_directory()."/".$f.".zip";
	$zip->open($zip_name,  ZipArchive::CREATE);
	foreach ($postImgArr as $key=>$value) {
  		$zip->addFromString(basename($value),  file_get_contents($value));  
	}
	$zip->close();
	
	header("Content-Type: application/zip");

	header("Content-Disposition: attachment; filename=download.zip");   
	//header("Content-Type: application/octet-stream");
	//header("Content-Type: application/download");
	//header("Content-Description: File Transfer");            
	//header("Content-Length: " . filesize($zip_name));
	flush();
		
	$fp = fopen($zip_name, "r");
	while (!feof($fp))
	{
    	echo fread($fp, 65536);
    	flush();
	} 
	fclose($fp);
	
	unlink($zip_name);
	}
	
}
?>