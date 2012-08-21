<?php

if ( !defined('ABSPATH') )
	die('-1');

wp_nonce_field( parent::instance()->base_name, parent::DOMAIN );

?>
<label><?php _e('Query ID', 'wp-query-factory'); ?></label>
<input type="text" name="query_builder[post_name]" value="<?php echo $post->post_name; ?>" />

<br />
<label><?php _e('Query Type', 'wp-query-factory'); ?></label><br />
<select id="query_type" name="query_builder[query_type]" data-placeholder="<?php _e('Select type of query to create', 'wp-query-factory'); ?>" class="chzn-select" tabindex="3">
	<?php foreach($query_types as $query_type ) : ?>
	<option value="<?php echo $query_type; ?>" <?php selected( $query_type, $post->post_mime_type ); ?>><?php echo $query_type; ?></option>
	<?php endforeach; ?>
</select>
<br class="clear" />
<div id="WP_Query" class="query_type">
	<label><?php _e('User', 'wp-query-factory'); ?></label><br />
	<div class="left_half">
		<label><?php _e('Include', 'wp-query-factory'); ?></label><br />
		<select name="query_builder[include_author][]" data-placeholder="<?php _e('Select users to include', 'wp-query-factory'); ?>" class="chzn-select" multiple tabindex="3">
			<?php foreach($users as $user ) : ?>
			<option value="<?php echo $user->ID; ?>" <?php selected( in_array($user->ID, $saved_arguments['author']) ); ?>><?php echo $user->display_name; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<div class="right_half">
		<label><?php _e('Exclude', 'wp-query-factory'); ?></label><br />
		<select name="query_builder[exclude_author][]" data-placeholder="<?php _e('Select users to exclude', 'wp-query-factory'); ?>" class="chzn-select" multiple tabindex="3">
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
		<select name="query_builder[post_type][]" data-placeholder="<?php _e('Select types to query', 'wp-query-factory'); ?>" class="chzn-select" multiple tabindex="3">
			<?php foreach($post_types as $post_type ) : ?>
			<option value="<?php echo $post_type; ?>" <?php selected( in_array($post_type, $saved_arguments['post_type']) ); ?>><?php echo ucwords(str_replace('-', ' ', str_replace('_', ' ', $post_type))); ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<div class="right_half">
		<label><?php _e('Status', 'wp-query-factory'); ?></label><br />
		<select name="query_builder[post_status][]" data-placeholder="<?php _e('Select status to query', 'wp-query-factory'); ?>" class="chzn-select" multiple tabindex="3">
		<?php foreach($post_status as $status ) : ?>
			<option value="<?php echo $status; ?>" <?php selected( in_array($status, $saved_arguments['post_status']) ); ?>><?php echo ucwords(str_replace('-', ' ', str_replace('_', ' ', $status))); ?></option>
		<?php endforeach; ?>
		</select>
		<p class="description"><?php _e("NOTE: 'Any' - retrieves any status except those from post types with 'exclude_from_search' set to true.", 'wp-query-factory'); ?></p>
	</div>
	<br class="clear" />
	<label><?php _e('Pagination', 'wp-query-factory'); ?></label><br />
	<label><?php _e('Offset', 'wp-query-factory'); ?></label><br />
	<input type="text" name="query_builder[offset]" value="<?php echo $offset; ?>" />
	<br class="clear" />
	<div class="left_half">
		<label><?php _e('Order', 'wp-query-factory'); ?></label><br />
		<select name="query_builder[order]" data-placeholder="<?php _e('Order query', 'wp-query-factory'); ?>" class="chzn-select" tabindex="3">
			<?php foreach($order as $order_type ) : ?>
			<option value="<?php echo $order_type; ?>" <?php selected( in_array($order_type, $saved_arguments['order']) ); ?>><?php echo ucwords(str_replace('-', ' ', str_replace('_', ' ', $order_type))); ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<div class="right_half">
		<label><?php _e('Order By', 'wp-query-factory'); ?></label><br />
		<select name="query_builder[orderby]" data-placeholder="<?php _e('Select status to query', 'wp-query-factory'); ?>" class="chzn-select" tabindex="3">
		<?php foreach($orderby as $orderby_type ) : ?>
			<option value="<?php echo $orderby_type; ?>" <?php selected( in_array($orderby_type, $saved_arguments['orderby']) ); ?>><?php echo ucwords(str_replace('-', ' ', str_replace('_', ' ', $orderby_type))); ?></option>
		<?php endforeach; ?>
		</select>
	</div>
	<br class="clear" />
	<label><?php _e('Sticky Post', 'wp-query-factory'); ?></label><br />
	<label><input type="checkbox" name="query_builder[ignore_sticky_posts]" value="1" <?php checked(1, $saved_arguments['ignore_sticky_posts']); ?> /> <?php _e('Ignore sticky posts','wp-query-factory'); ?></label>
	<p class="description"><?php _e("Return ALL posts within the set parameters of this query, but don't show sticky posts at the top. The 'sticky posts' will still show in their natural position (e.g. by date).", 'wp-query-factory'); ?></p>
	<label><?php _e('Time', 'wp-query-factory'); ?></label><br />
	<label><?php _e('Year', 'wp-query-factory'); ?></label> <input type="text" name="query_builder[year]" value="<?php echo $year; ?>" />
	<br class="clear" />
	<div class="left_half">
		<select name="query_builder[monthnum]" data-placeholder="<?php _e('Select month', 'wp-query-factory'); ?>" class="chzn-select-deselect">
			<option value></option>
			<?php for($monthnum=1;$monthnum<13;$monthnum++) : ?>
			<option value="<?php echo $monthnum; ?>" <?php selected($monthnum,$saved_arguments['monthnum']); ?>><?php echo date( 'F', mktime(0, 0, 0, $monthnum) ); ?></option>
			<?php endfor; ?>
		</select>
		<select name="query_builder[day]" data-placeholder="<?php _e('Select day of the month', 'wp-query-factory'); ?>" class="chzn-select-deselect">
			<option value=""></option>
			<?php for($day=1;$day<32;$day++) : ?>
			<option value="<?php echo $day; ?>" <?php selected($day,$saved_arguments['day']); ?>><?php printf( __('%s day of the month','wp-query-factory'), $this->ordinal($day)); ?></option>
			<?php endfor; ?>
		</select>
		<select name="query_builder[hour]" data-placeholder="<?php _e('Select hour', 'wp-query-factory'); ?>" class="chzn-select-deselect">
			<option value=""></option>
			<?php for($hour=1;$hour<25;$hour++) : ?>
			<option value="<?php echo $hour; ?>" <?php selected($hour,$saved_arguments['hour']); ?>><?php printf( __('%s hour','wp-query-factory'), $this->ordinal($hour)); ?></option>
			<?php endfor; ?>
		</select>
		<select name="query_builder[minute]" data-placeholder="<?php _e('Select minute', 'wp-query-factory'); ?>" class="chzn-select-deselect">
			<option value=""></option>
			<?php for($minute=1;$minute<61;$minute++) : ?>
			<option value="<?php echo $minute; ?>" <?php selected($minute,$saved_arguments['minute']); ?>><?php printf( __('%s minute','wp-query-factory'), $this->ordinal($minute)); ?></option>
			<?php endfor; ?>
		</select>
		<select name="query_builder[second]" data-placeholder="<?php _e('Select second', 'wp-query-factory'); ?>" class="chzn-select-deselect">
			<option value=""></option>
			<?php for($second=1;$second<61;$second++) : ?>
			<option value="<?php echo $second; ?>" <?php selected($second,$saved_arguments['second']); ?>><?php printf( __('%s second','wp-query-factory'), $this->ordinal($second)); ?></option>
			<?php endfor; ?>
		</select>
	</div>
	<div class="right_half">
		<label><?php _e('OR', 'wp-query-factory'); ?></label>
		<select name="query_builder[w]" data-placeholder="<?php _e('Select week of the year', 'wp-query-factory'); ?>" class="chzn-select-deselect">
			<option value=""></option>
			<?php for($w=1;$w<53;$w++) : ?>
			<option value="<?php echo $w; ?>" <?php selected($w,$saved_arguments['w']); ?>><?php printf( __('%s week of the Year','wp-query-factory'), $this->ordinal($w)); ?></option>
			<?php endfor; ?>
		</select>
		<p class="description"><?php _e('If this parameter is selected it will override (and unselect) regular date options in relation to filtering.','wp-query-factory'); ?></p>
	</div>
	<br class="clear" />
	<p class="decription"><?php _e('Note: This parameter will return posts for a specific date period in history, i.e. "Posts from X year, X month, X day". They are unable to fetch posts from a timespan relative to the present, so queries like "Posts from the last 30 days" or "Posts from the last year" are not part of this query option.', 'wp-query-factory'); ?></p>
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
