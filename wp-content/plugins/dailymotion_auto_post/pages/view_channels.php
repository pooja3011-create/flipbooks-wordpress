<?php defined('ABSPATH') or die("Not Allowed"); ?>
<div class="wrap WP_ayvpp_styling">
	<div class="icon32" id="icon-options-general"><br></div>
	<h2>Channels / Playlists <a class="button" href="./admin.php?page=dmap_channels&action=add">Add New</a></h2>
	
	<form action="" method="post" id="tern_wp_youtube_channel_fm">
		
		<div class="tablenav">
			<!--<div class="alignleft actions">
				<select name="action">
					<option selected="selected" value="">Bulk Actions</option>
					<option value="delete">Delete</option>
				</select>
				<input type="submit" class="button-secondary action" name="doaction" value="Apply">
			</div>-->
		</div>
		
		<table cellspacing="0" class="widefat fixed">
			<thead>
			<tr class="thead">
				<th style="width:150px;" class="manage-column column-name" id="channel" scope="col">Name</th>
				<th style="width:150px;" class="manage-column column-channel" id="channel" scope="col">Channel/Playlist</th>
				<th style="width:20%;" class="manage-column column-cat" id="cat" scope="col">Catgories</th>
				<th class="manage-column column-author" id="author" scope="col">Author</th>
				<th class="manage-column " scope="col">Actions</th>
			</tr>
			</thead>
			<tfoot>
			<tr class="thead">
				<th class="manage-column column-name" scope="col">Name</th>
				<th class="manage-column column-channel" scope="col">Channel/Playlist</th>
				<th class="manage-column column-cat" scope="col">Catgories</th>
				<th class="manage-column column-author" scope="col">Author</th>
				<th class="manage-column " scope="col">Actions</th>
			</tr>
			</tfoot>
			<tbody class="list:fields field-list" id="fields">
			<?php 
				$channels = get_option("dmap_channels");
				$channels = (!empty($channels)) ? unserialize($channels) : array();
				if(!empty($channels )) : 
					foreach($channels as $c_id => $channel) :?> 
				<tr>
					<td class="manage-column column-name" scope="col"><?php echo $channel['name']; ?></td>
					<td class="manage-column column-channel" scope="col"><?php echo $c_id; ?></td>
					<td class="manage-column column-cat" scope="col"><?php 
					if(!empty($channel['cats'])) : 
						foreach($channel['cats'] as $cat) :
							echo get_cat_name( $cat ) . ",";
						endforeach;
					endif;
					?></td>
					<td class="manage-column column-author" scope="col"><?php $user_info = get_userdata($channel['author']); echo $user_info->user_nicename; ?></td>
					<td class="manage-column column-actions" scope="col"><input type="button" class="button-secondary action" value="Process Now" onclick="window.location= './admin.php?page=dmap_process&cid=<?php echo $c_id; ?>'; this.disabled=true;"> <input type="button" class="button-secondary action" value="Delete" onclick="window.location= './admin.php?page=dmap_channels&action=delete&cid=<?php echo $c_id; ?>'"></td>
				</tr><?php endforeach; endif; ?>
			</tbody>
		</table>
		
		<div class="tablenav">
			<!--<div class="alignleft actions">
				<select name="action2">
					<option selected="selected" value="">Bulk Actions</option>
					<option value="delete">Delete</option>
				</select>
				<input type="submit" class="button-secondary action" name="doaction2" value="Apply">
			</div>-->
			<br class="clear">
		</div>
		
		<input type="hidden" value="ayvpp-channels" name="page" id="page">
	</form>
</div>