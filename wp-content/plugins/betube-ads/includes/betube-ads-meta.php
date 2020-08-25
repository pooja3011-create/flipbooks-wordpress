<?php 
/*Meta Box Data*/
add_action("add_meta_boxes", "add_custom_meta_box");
function add_custom_meta_box(){
    add_meta_box("betube-ads", "BeTube Custom Ads", "betube_select_ads_type", "video-ads", "normal", "high", null);
}
function betube_select_ads_type($object){
	wp_nonce_field(basename(__FILE__), "meta-box-nonce");
	?>
	<div class="beAds-meta">
		<!--Ads Type-->
		<label for="meta-box-dropdown"><?php esc_html_e( 'Select Ads Type', 'betube-ads' );?></label>
		<select id= "videoAdv" name="meta-box-dropdown" required>
			<option selected disabled value="none"><?php esc_html_e( 'Select Type', 'betube-ads' );?></option>
			<?php 
				$option_values = array('image', 'video', 'html');

				foreach($option_values as $key => $value) 
				{
					if($value == get_post_meta($object->ID, "meta-box-dropdown", true))
					{
						?>
							<option selected value="<?php echo $value;?>"><?php echo $value; ?></option>
						<?php    
					}
					else
					{
						?>
							<option><?php echo $value; ?></option>
						<?php
					}
				}
			?>
		</select>
		<br>
		<!--Ads Type-->
		<!--Image Ads-->
		<div id="image">
			<p><?php esc_html_e( 'If You want to use Image Ads then Set Thumbnail form right side, Otherwise leave it.', 'betube-ads' );?></p>
			<label for="be-img-url"><?php esc_html_e( 'Click able URL', 'betube-ads' );?></label>
			<input name="be-img-url" type="text" style="width:50%;" placeholder="<?php esc_html_e( 'www.google.com', 'betube-ads' );?>" value="<?php echo get_post_meta($object->ID, "be-img-url", true); ?>">			
		</div>
		<!--Image Ads-->
		<!--Video URL-->
		<div id="video">
			<p><?php esc_html_e( 'If you are using Video Ads then dont upload image as a thumbnail', 'betube-ads' );?></p>
			<label for="meta-box-text"><?php esc_html_e( 'Video URL', 'betube-ads' );?></label>
			<input name="meta-box-text" type="text" style="width:50%;" value="<?php echo get_post_meta($object->ID, "meta-box-text", true); ?>">
			<p class="videoURLtxt"><?php esc_html_e( 'Video url from Youtube or Vimeo. Ex: ', 'betube-ads' );?>https://www.youtube.com/watch?v=CevxZvSJLk8</p>
			<br>
		</div>
		<!--Video URL-->
		<!--HTML Ads-->
		<style>
		label#meta-html-code{
		 vertical-align: top;
		}
		</style>
		<div id="html">
			<p><?php esc_html_e( 'If you are using HTML for ads then dont upload image as a thumbnail.', 'betube-ads' );?></p>
			<label id="meta-html-code" for="meta-html-code"><?php esc_html_e( 'HTML Ad', 'betube-ads' );?></label>
			<textarea id="meta-html-code" name="meta-html-code" cols="60"><?php echo get_post_meta($object->ID, "meta-html-code", true); ?></textarea>
		</div>
		<br />
		<!--HTML Ads-->
		<!--Data Picker-->
		<p><?php esc_html_e( 'How Many days this ad will be active? Please put number of days like (1 or 30 or 60)', 'betube-ads' );?></p>
		<label for="bedatepicker"><?php esc_html_e( 'Expiry Date', 'betube-ads' );?></label>
		<input id="bedatepicker" name="bedatepicker" type="text" value="<?php echo get_post_meta($object->ID, "bedatepicker", true); ?>">
		<!--Data Picker-->
	</div>
	<?php
    
}
/*Save Function*/
function save_custom_meta_box($post_id, $post, $update)
{
    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
        return $post_id;

    if(!current_user_can("edit_post", $post_id))
        return $post_id;

    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;

    $slug = "video-ads";
    if($slug != $post->post_type)
        return $post_id;

    $meta_box_text_value = "";
    $meta_box_dropdown_value = "";
    $meta_box_checkbox_value = "";
	/*Text*/
    if(isset($_POST["meta-box-text"]))
    {
        $meta_box_text_value = $_POST["meta-box-text"];
    }   
    update_post_meta($post_id, "meta-box-text", $meta_box_text_value);
	/*Text*/
	/*imgURL*/
    if(isset($_POST["be-img-url"]))
    {
        $meta_img_position = $_POST["be-img-url"];
    }   
    update_post_meta($post_id, "be-img-url", $meta_img_position);
	/*imgURL*/
	/*dropdown*/
    if(isset($_POST["meta-box-dropdown"]))
    {
        $meta_box_dropdown_value = $_POST["meta-box-dropdown"];
    }   
    update_post_meta($post_id, "meta-box-dropdown", $meta_box_dropdown_value);
	/*dropdown*/
	/*HTML Code*/
	if(isset($_POST["meta-html-code"]))
    {
        $meta_html_code_value = $_POST["meta-html-code"];
    }   
    update_post_meta($post_id, "meta-html-code", $meta_html_code_value);
	/*HTML Code*/
	/*Date*/
	if(isset($_POST["bedatepicker"]))
    {
        $bedatepicker = $_POST["bedatepicker"];
    }   
    update_post_meta($post_id, "bedatepicker", $bedatepicker);
	/*Date*/
    
}

add_action("save_post", "save_custom_meta_box", 10, 3);
/*Meta Box Data*/
/*Ads Meta Data Table*/
add_action( 'manage_posts_custom_column' , 'betube_ads_custom_colums', 10, 2 );
function betube_ads_custom_colums( $column ) {
		global $post;
		//print_r($post);
		switch ( $column ) {
            case 'id':
                echo $post->ID;
                break;
			case 'bedatepicker':
				$days = get_post_meta($post->ID, 'bedatepicker', TRUE);	
				echo $days. esc_html__( ' Days', 'betube-ads' );
				break;
		}
	}
	
/*Ads Meta Data Table*/
?>