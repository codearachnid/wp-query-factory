<?php

if ( !defined('ABSPATH') )
	die('-1');

wp_nonce_field( parent::instance()->base_name, parent::DOMAIN );

?>
<label><?php _e('Query ID', 'wp-query-factory'); ?></label>
<input type="text" name="query_builder[post_name]" value="<?php echo $post->post_name; ?>" />

<br />
<label><?php _e('Query Type', 'wp-query-factory'); ?></label><br />
<select id="query_type" name="query_builder[query_type]" data-placeholder="<?php _e('Select type of query to create', 'wp-query-factory'); ?>" class="chzn-select" style="width:350px" tabindex="3">
	<?php foreach($query_types as $query_type ) : ?>
	<option value="<?php echo $query_type; ?>" <?php selected( $query_type, $post->post_mime_type ); ?>><?php echo $query_type; ?></option>
	<?php endforeach; ?>
</select>
<br class="clear" />
<div id="WP_Query" class="query_type">
	<label><?php _e('User', 'wp-query-factory'); ?></label><br />
	<div class="left_half">
		<label><?php _e('Include', 'wp-query-factory'); ?></label><br />
		<select name="query_builder[include_author][]" data-placeholder="<?php _e('Select users to include', 'wp-query-factory'); ?>" class="chzn-select" style="width:350px" multiple tabindex="3">
			<?php foreach($users as $user ) : ?>
			<option value="<?php echo $user->ID; ?>" <?php selected( in_array($user->ID, $saved_arguments['author']) ); ?>><?php echo $user->display_name; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<div class="right_half">
		<label><?php _e('Exclude', 'wp-query-factory'); ?></label><br />
		<select name="query_builder[exclude_author][]" data-placeholder="<?php _e('Select users to exclude', 'wp-query-factory'); ?>" class="chzn-select" style="width:350px" multiple tabindex="3">
			<?php foreach($users as $user ) : ?>
			<option value="-<?php echo $user->ID; ?>" <?php selected( in_array('-'.$user->ID, $saved_arguments['author']) ); ?>><?php echo $user->display_name; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<br class="clear" />
	<label><?php _e('Category', 'wp-query-factory'); ?></label><br />
	<label><?php _e('Tag', 'wp-query-factory'); ?></label><br />
	<label><?php _e('Taxonomy', 'wp-query-factory'); ?></label><br />
	<label><?php _e('Post & Page', 'wp-query-factory'); ?></label><br />
	<br class="clear" />
	<div class="left_half">
		<label><?php _e('Type', 'wp-query-factory'); ?></label><br />
		<select name="query_builder[post_type][]" data-placeholder="<?php _e('Select types to query', 'wp-query-factory'); ?>" class="chzn-select" style="width:350px" multiple tabindex="3">
			<?php foreach($post_types as $post_type ) : ?>
			<option value="<?php echo $post_type; ?>" <?php selected( in_array($post_type, $saved_arguments['post_type']) ); ?>><?php echo ucwords(str_replace('-', ' ', str_replace('_', ' ', $post_type))); ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<div class="right_half">
		<label><?php _e('Status', 'wp-query-factory'); ?></label><br />
		<select name="query_builder[post_status][]" data-placeholder="<?php _e('Select status to query', 'wp-query-factory'); ?>" class="chzn-select" style="width:350px" multiple tabindex="3">
		<?php foreach($post_status as $status ) : ?>
			<option value="<?php echo $status; ?>" <?php selected( in_array($status, $saved_arguments['post_status']) ); ?>><?php echo ucwords(str_replace('-', ' ', str_replace('_', ' ', $status))); ?></option>
		<?php endforeach; ?>
		</select>
		<p class="description"><?php _e("NOTE: 'Any' - retrieves any status except those from post types with 'exclude_from_search' set to true.", 'wp-query-factory'); ?></p>
	</div>
	<br class="clear" />
	<label><?php _e('Pagination', 'wp-query-factory'); ?></label><br />
	<label><?php _e('Offset', 'wp-query-factory'); ?></label><br />
	<div class="left_half">
		<label><?php _e('Order', 'wp-query-factory'); ?></label><br />
		<select name="query_builder[order]" data-placeholder="<?php _e('Order query', 'wp-query-factory'); ?>" class="chzn-select" style="width:350px" tabindex="3">
			<?php foreach($order as $order_type ) : ?>
			<option value="<?php echo $order_type; ?>" <?php selected( in_array($order_type, $saved_arguments['order']) ); ?>><?php echo ucwords(str_replace('-', ' ', str_replace('_', ' ', $order_type))); ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<div class="right_half">
		<label><?php _e('Order By', 'wp-query-factory'); ?></label><br />
		<select name="query_builder[orderby][]" data-placeholder="<?php _e('Select status to query', 'wp-query-factory'); ?>" class="chzn-select" style="width:350px" multiple tabindex="3">
		<?php foreach($orderby as $orderby_type ) : ?>
			<option value="<?php echo $orderby_type; ?>" <?php selected( in_array($orderby_type, $saved_arguments['orderby']) ); ?>><?php echo ucwords(str_replace('-', ' ', str_replace('_', ' ', $orderby_type))); ?></option>
		<?php endforeach; ?>
		</select>
	</div>
	<br class="clear" />
	<label><?php _e('Sticky Post', 'wp-query-factory'); ?></label><br />
	<label><?php _e('Time', 'wp-query-factory'); ?></label><br />
	<label><?php _e('Custom Fields', 'wp-query-factory'); ?></label><br />
	<label><?php _e('Permission', 'wp-query-factory'); ?></label><br />
	<label><?php _e('Caching', 'wp-query-factory'); ?></label><br />
	<label><?php _e('Field Parameters', 'wp-query-factory'); ?></label><br />
	<label><?php _e('Search', 'wp-query-factory'); ?></label><br />
	<label><?php _e('Filters', 'wp-query-factory'); ?></label><br />
</div>
<div id="WP_User_Query" class="query_type">
	WP_User_Query Form
</div>
