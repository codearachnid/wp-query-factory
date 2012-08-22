<?php

if ( !defined('ABSPATH') )
	die('-1');

wp_nonce_field( parent::instance()->base_name, parent::DOMAIN );

?>
<label><?php _e('Query ID', 'wp-query-factory'); ?></label>
<input type="text" name="query_builder[post_name]" value="<?php echo $post->post_name; ?>" />

<br />
<label><?php _e('Query Type', 'wp-query-factory'); ?></label><br />
<?php WP_Query_Factory_Template_Tags::select($query_types, $post->post_mime_type, array('query_type'=>'query_builder[query_type]'), __('Select type of query to create', 'wp-query-factory'), false ); ?>
<br class="clear" />
<div id="WP_Query" class="query_type">
	<fieldset>
		<legend><?php _e('User', 'wp-query-factory'); ?></legend>
		<div class="left_half">
			<?php WP_Query_Factory_Template_Tags::select($users, $saved_arguments['author'], array('query_builder[include_author][]'), __('Select users to include', 'wp-query-factory'), true, true ); ?>
		</div>
		<div class="right_half">
			<?php WP_Query_Factory_Template_Tags::select($exclude_users, $saved_arguments['author'], array('query_builder[exclude_author][]'), __('Select users to exclude', 'wp-query-factory'), true, true ); ?>
		</div>
	</fieldset>
	<br class="clear" />
	<label><?php _e('Category', 'wp-query-factory'); ?></label><br />
	<label><?php _e('Tag', 'wp-query-factory'); ?></label><br />
	<label><?php _e('Taxonomy', 'wp-query-factory'); ?></label><br />
	<label><?php _e('Post & Page', 'wp-query-factory'); ?></label><br />
	<br class="clear" />
	<div class="left_half">
		<label><?php _e('Type', 'wp-query-factory'); ?></label><br />
		<?php WP_Query_Factory_Template_Tags::select($post_types, $saved_arguments['post_type'], array('query_builder[post_type][]'), __('Select types to query', 'wp-query-factory')); ?>
	</div>
	<div class="right_half">
		<label><?php _e('Status', 'wp-query-factory'); ?></label><br />
		<?php WP_Query_Factory_Template_Tags::select($post_status, $saved_arguments['post_status'], array('query_builder[post_type][]'), __('Select status to query', 'wp-query-factory')); ?>
		<p class="description"><?php _e("NOTE: 'Any' - retrieves any status except those from post types with 'exclude_from_search' set to true.", 'wp-query-factory'); ?></p>
	</div>
	<br class="clear" />
	<label><?php _e('Pagination', 'wp-query-factory'); ?></label><br />
	<label><?php _e('Offset', 'wp-query-factory'); ?></label><br />
	<input type="text" name="query_builder[offset]" value="<?php echo $offset; ?>" />
	<br class="clear" />
	<div class="left_half">
		<label><?php _e('Order', 'wp-query-factory'); ?></label><br />
		<?php WP_Query_Factory_Template_Tags::select($order, $saved_arguments['order'], array('query_builder[order]'), __('Order query', 'wp-query-factory')); ?>
	</div>
	<div class="right_half">
		<label><?php _e('Order By', 'wp-query-factory'); ?></label><br />
		<?php WP_Query_Factory_Template_Tags::select($orderby, $saved_arguments['orderby'], array('query_builder[orderby]'), __('Order by field', 'wp-query-factory')); ?>
	</div>
	<br class="clear" />
	<label><?php _e('Sticky Post', 'wp-query-factory'); ?></label><br />
	<label><input type="checkbox" name="query_builder[ignore_sticky_posts]" value="1" <?php checked(1, $saved_arguments['ignore_sticky_posts']); ?> /> <?php _e('Ignore sticky posts','wp-query-factory'); ?></label>
	<p class="description"><?php _e("Return ALL posts within the set parameters of this query, but don't show sticky posts at the top. The 'sticky posts' will still show in their natural position (e.g. by date).", 'wp-query-factory'); ?></p>
	<label><?php _e('Time', 'wp-query-factory'); ?></label><br />
	<label><?php _e('Year', 'wp-query-factory'); ?></label> <input type="text" name="query_builder[year]" value="<?php echo $year; ?>" />
	<br class="clear" />
	<div class="left_twothird_half">
		<?php WP_Query_Factory_Template_Tags::select($monthnum, $saved_arguments['monthnum'], array('query_builder[monthnum]'), __('Month', 'wp-query-factory'), true, true, 'month'); ?>
		<span class="timespacer">|</span>
		<?php WP_Query_Factory_Template_Tags::select($day, $saved_arguments['day'], array('query_builder[day]'), __('Day', 'wp-query-factory'), true, true, 'day'); ?>
		<span class="timespacer">|</span>
		<?php WP_Query_Factory_Template_Tags::select($hour, $saved_arguments['hour'], array('query_builder[hour]'), __('Hour', 'wp-query-factory'), true, true, 'time'); ?>
		<span class="timespacer">:</span>
		<?php WP_Query_Factory_Template_Tags::select($minute, $saved_arguments['minute'], array('query_builder[minute]'), __('Minute', 'wp-query-factory'), true, true, 'time'); ?>
		<span class="timespacer">:</span>
		<?php WP_Query_Factory_Template_Tags::select($second, $saved_arguments['second'], array('query_builder[second]'), __('Second', 'wp-query-factory'), true, true, 'time'); ?>
	</div>
	<div class="right_third_half">
		<label><?php _e('OR', 'wp-query-factory'); ?></label>
		<?php WP_Query_Factory_Template_Tags::select($w, $saved_arguments['w'], array('query_builder[w]'), __('Select week of the year', 'wp-query-factory'), true, true, 'week'); ?>
		<p class="description"><?php _e('If this parameter is selected it will override (and unselect) regular date options in relation to filtering.','wp-query-factory'); ?></p>
	</div>
	<br class="clear" />
	<p class="description"><?php _e('Note: This parameter will return posts for a specific date period in history, i.e. "Posts from X year, X month, X day". They are unable to fetch posts from a timespan relative to the present, so queries like "Posts from the last 30 days" or "Posts from the last year" are not part of this query option.', 'wp-query-factory'); ?></p>
	<label><?php _e('Custom Fields', 'wp-query-factory'); ?></label><br />
	<label><?php _e('Permission', 'wp-query-factory'); ?></label><br />
	<label><?php _e('Caching', 'wp-query-factory'); ?></label><br />
	<label><?php _e('Field Parameters', 'wp-query-factory'); ?></label><br />
	<label><?php _e('Search Term', 'wp-query-factory'); ?></label><br />
	<input type="text" name="query_builder[s]" value="<?php echo $s; ?>" />
	<p class="description"><?php _e('This uses keyword matching against the title and content fields (no custom field lookups).','wp-query-factory'); ?></p>
	<label><?php _e('Filters', 'wp-query-factory'); ?></label><br />
</div>
<div id="WP_User_Query" class="query_type">
	WP_User_Query Form
</div>
