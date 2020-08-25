<?php 
/*Post video ADV ID */	
add_action( 'add_meta_boxes', 'video_adv_id' );
function video_adv_id() {
	add_meta_box( 
		'video_adv_id',
		esc_html__( 'Video Ads ID', 'betube-ads' ),
		'video_adv_id_content',
		'post',
		'normal',
		'high'
	);
}

function video_adv_id_content( $post ) {
	wp_nonce_field( 'videoadv_meta_boxeee', 'videoadv_meta_box_nonceeee' );
	$video_adv_id = get_post_meta( $post->ID, 'video_adv_id', true );
	echo '<p>' . esc_html__( 'If you will put Ads ID here this that ad will work on this post, otherwise random ads will be shown. Please go to BeTube Video Ads --> You will see ID there just copy that ID and paste in this box.', 'betube-ads' ) . '</p>';
	echo '<label for="video_adv_id"></label>';
	echo '<input type="text" id="video_adv_id" name="video_adv_id" value="';
	echo $video_adv_id; 
	echo '">';
	
}

add_action( 'save_post', 'video_adv_id_content_save' );
function video_adv_id_content_save( $post_id ) {		

	global $video_adv_id;
	
	if ( ! isset( $_POST['videoadv_meta_box_nonceeee'] ) ) {
	return;
	}
	if ( ! wp_verify_nonce( $_POST['videoadv_meta_box_nonceeee'], 'videoadv_meta_boxeee' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if(isset($_POST["video_adv_id"]))
	$video_adv_id = $_POST['video_adv_id'];
	update_post_meta( $post_id, 'video_adv_id', $video_adv_id );

}
/*Post video ADV ID */
?>