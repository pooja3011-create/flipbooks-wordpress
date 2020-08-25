<?php 
defined('ABSPATH') or die("Not Allowed");

if(isset($_POST['save_dmap'])) {
	$settings = array();
	$settings['auto_publish'] = $_POST['auto_publish'];
	$settings['cron_interval'] = $_POST['cron_interval'];
	$settings['posts_total'] = $_POST['posts_total'];
	$settings['w_width'] = $_POST['w_width'];
	$settings['w_height'] = $_POST['w_height'];
	$settings['syndication'] = $_POST['syndication'];
	update_option("dmap_settings", $settings);
}
$settings = get_option("dmap_settings");
?>

<div class="wrap">
<h2>General Settings</h2>

<form action="" method="post">
<table class="form-table">
<tbody>
	<tr>
		<th scope="row">
			<label>Post Status</label>
		</th>
		<td>
			<input type="radio" value="1" name="auto_publish" <?php if($settings['auto_publish'] == 1) echo 'checked="checked"'; ?>> Yes
			<input type="radio" value="0" name="auto_publish" <?php if($settings['auto_publish'] == 0) echo 'checked="checked"'; ?>> No<br>
			<span class="setting-description">This option will make posts immediately viewable to the public.</span>
		</td>
	</tr>
    <tr>
		<th scope="row">
			<label>Number of Posts in Single Cron</label>
		</th>
		<td>
			<input type="text" value="<?php echo $settings['posts_total']; ?>" name="posts_total" placholder="1000"/>
		</td>
	</tr>
    <tr>
		<th scope="row">
			<label>Video Size (Width x Height)</label>
		</th>
		<td>
			<input type="text" value="<?php echo $settings['w_width']; ?>" name="w_width" placholder="530" size="6"/> x <input type="text" value="<?php echo $settings['w_height']; ?>" name="w_height" placholder="530" size="6"/>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<label>Import the latest videos every:</label>
		</th>
		<td>
			<select name="cron_interval" id="" class="" title="">
			<?php $options = array(1,4,6,12,24);
					foreach($options as $opt) {
						$selected = ($settings['cron_interval'] == $opt) ? ' selected="selected"': '';
						echo '<option value="'.$opt.'"'.$selected.'>'.$opt.'</option>"';
					}
			?>
			</select> hours<br>
			<span class="setting-description">Set this to determine how many hours to wait between imports. PLEASE NOTE: THIS PLUGIN USES PSEUDO CRON JOBS. IT IS NOT AN ACTUAL CRON JOB. THEREFORE UNLESS SOMEONE VISITS YOUR SITE AT OR AFTER THE SPECIFIED AMOUNT OF TIME IN THIS SETTING THE VIDEOS WILL NOT BE IMPORTED UNTIL THE NEXT VISIT.
		</td>
	</tr>
	<tr>
		<th scope="row">
			<label>Syndication Key:</label>
		</th>
		<td>
			<input type="text" value="<?php echo $settings['syndication']; ?>" name="syndication"/><br/>
			<span class="setting-description">Set syndication key for your videos which will be imported from dailymotion. You must have Publisher account to set this syndication key. 
		</td>
	</tr>
</tbody>
</table>


<p class="submit"><input type="submit" value="Save Changes" class="button button-primary" id="submit" name="save_dmap"></p></form>

</div>