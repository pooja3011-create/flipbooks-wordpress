<?php defined('ABSPATH') or die("Not Allowed"); ?>

<div class="wrap">
<h2>Add Channel</h2>

<form action="./admin.php?page=dmap_channels" method="post">
<table class="form-table">
<tbody>
	<tr>
		<th scope="row">
			<label>Name</label>
		</th>
		<td>
			<input type="text" class="regular-text" name="channel_name">
		</td>
	</tr>
	<tr>
		<th scope="row">
			<label>Channel/User ID</label>
		</th>
		<td>
			<input type="text" class="regular-text" name="channel_id">
		</td>
	</tr>
	<tr>
		<th scope="row">
			<label>Video Categories</label>
		</th>
		<td>
			<?php  $categories = get_categories(array('hide_empty' => 0)); 
  foreach ($categories as $category) { ?>
					<input type="checkbox" value="<?php echo $category->cat_ID; ?>" class="chk" name="categories[]"><?php echo $category->name; ?><br/>
					<?php } ?>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<label>Author</label>
		</th>
		<td>
			<select class="" id="author" name="author">
			<?php echo $blogusers = get_users( array( 'fields' => array( 'user_nicename', 'ID' ) ) );
			foreach ( $blogusers as $user ) { ?>
				<option value="<?php echo $user->ID; ?>"><?php echo $user->user_nicename; ?></option>
			<?php } ?>
			</select>
		</td>
	</tr>
	
</tbody>
</table>


<p class="submit"><input type="submit" value="Save Changes" class="button button-primary" id="submit" name="add_channel"></p></form>

</div>