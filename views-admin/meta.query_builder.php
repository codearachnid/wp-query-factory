<?php

if ( !defined('ABSPATH') )
	die('-1');

wp_nonce_field( parent::instance()->base_name, parent::DOMAIN );

?>
<label><?php _e('Query ID', 'wp-query-factory'); ?></label>
<input type="text" name="query_builder[post_name]" value="<?php echo $post->post_name; ?>" />

<br />
<label><?php _e('Query Type', 'wp-query-factory'); ?></label>
<ul>
	<?php foreach(parent::$query_types as $query_type) : ?>
	<li><label><input type="radio" name="query_builder[query_type]" value="<?php echo $query_type; ?>" <?php checked( $query_type, $post->post_mime_type ); ?> /> <?php echo $query_type; ?></label></li>
	<?php endforeach; ?>
</ul>

<br />
<label><?php _e('Users', 'wp-query-factory'); ?></label><br />
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

<br />
<label><?php _e('Select types to query', 'wp-query-factory'); ?></label>
<ul>
<?php foreach ($post_types as $post_type ) : ?>
	<li><label><input type="checkbox" name="query_builder[post_type][]" value="<?php echo $post_type; ?>" <?php checked( in_array($post_type, $saved_arguments['post_type']) ); ?> /> <?php echo ucwords(str_replace('-', ' ', str_replace('_', ' ', $post_type))); ?></label></li>
<?php endforeach; ?>
</ul>



