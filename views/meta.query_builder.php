<?php

if ( !defined('ABSPATH') )
	die('-1');
wp_nonce_field( parent::instance()->base_name, parent::DOMAIN );

?>
<fieldset class="clear">
	<legend></legend>
	<div class="left_half">
		<label><?php _e('Query ID', 'wp-query-factory'); ?></label>
		<input type="text" name="query_builder[post_name]" value="<?php echo $wp_query_factory->field_list['post_name']['value']; ?>" />
	</div>
	<div class="right_half">
		<label><?php _e('Query Type', 'wp-query-factory'); ?></label>
		<?php wpqf_tag_select( $wp_query_factory->field_list['query_type'] ); ?>
	</div>
</fieldset>
<div id="WP_Query" class="query_type">
	<fieldset class="clear">
		<legend><?php _e('User', 'wp-query-factory'); ?></legend>
		<div class="left_half">
			<?php /* WP_Query_Factory_Template_Tags::select($users, $saved_arguments['author'], array('query_builder[include_author][]'), __('Select users to include', 'wp-query-factory'), true, true ); */ ?>
		</div>
		<div class="right_half">
			<?php /* WP_Query_Factory_Template_Tags::select($exclude_users, $saved_arguments['author'], array('query_builder[exclude_author][]'), __('Select users to exclude', 'wp-query-factory'), true, true ); */ ?>
		</div>
	</fieldset>
	<fieldset class="clear">
		<legend><?php _e('Category', 'wp-query-factory'); ?></legend>
		<?php wpqf_tag_select( $wp_query_factory->field_list['cat'] ); ?>
		<?php wpqf_tag_select( $wp_query_factory->field_list['category_type'] ); ?>
	</fieldset>
	<fieldset class="clear">
		<legend><?php _e('Tag', 'wp-query-factory'); ?></legend>
		<br />
	</fieldset>
	<fieldset class="clear">
		<legend><?php _e('Taxonomy', 'wp-query-factory'); ?></legend>
		<br />
	</fieldset>
	<fieldset class="clear">
		<legend><?php _e('Post & Page', 'wp-query-factory'); ?></legend>
		<br />
	</fieldset>
	<fieldset class="clear">
		<legend><?php _e('Type & Status', 'wp-query-factory'); ?></legend>
		<div class="left_half">
			<?php wpqf_tag_select( $wp_query_factory->field_list['post_type'] ); ?>
		</div>
		<div class="right_half">
			<?php wpqf_tag_select( $wp_query_factory->field_list['post_status'] ); ?>
			<p class="description"><?php _e("NOTE: 'Any' - retrieves any status except those from post types with 'exclude_from_search' set to true.", 'wp-query-factory'); ?></p>
		</div>
	</fieldset>
	<fieldset class="clear">
		<legend></legend>
		<label><?php _e('Pagination', 'wp-query-factory'); ?></label><br />
		<label><?php _e('Offset', 'wp-query-factory'); ?></label><br />
		<input type="text" name="query_builder[offset]" value="<?php echo $wp_query_factory->field_list['offset']['value']; ?>" />
	</fieldset>
	<fieldset class="clear">
		<legend><?php _e('Order & Order By', 'wp-query-factory'); ?></legend>
		<div class="left_half">
			<?php wpqf_tag_select( $wp_query_factory->field_list['order'] ); ?>
		</div>
		<div class="right_half">
			<?php wpqf_tag_select( $wp_query_factory->field_list['orderby'] ); ?>
		</div>
	</fieldset>
	<fieldset class="clear">
		<legend><?php _e('Sticky Post', 'wp-query-factory'); ?></legend>
		<label><input type="checkbox" name="query_builder[ignore_sticky_posts]" value="1" <?php checked(1, $wp_query_factory->field_list['ignore_sticky_posts']['value']); ?> /> <?php _e('Ignore sticky posts','wp-query-factory'); ?></label>
		<p class="description"><?php _e("Return ALL posts within the set parameters of this query, but don't show sticky posts at the top. The 'sticky posts' will still show in their natural position (e.g. by date).", 'wp-query-factory'); ?></p>
	</fieldset>
	<fieldset class="clear">
		<legend><?php _e('Filter by time in history','wp-query-factory'); ?></legend>
		<label><?php _e('Year', 'wp-query-factory'); ?></label> <input type="text" name="query_builder[year]" value="<?php echo $wp_query_factory->field_list['year']['value']; ?>" />
		<br class="clear" />
		<div class="left_twothird_half">
			<?php wpqf_tag_select( $wp_query_factory->field_list['monthnum'] ); ?>
			<span class="timespacer">|</span>
			<?php wpqf_tag_select( $wp_query_factory->field_list['day'] ); ?>
			<span class="timespacer">|</span>
			<?php wpqf_tag_select( $wp_query_factory->field_list['hour'] ); ?>
			<span class="timespacer">:</span>
			<?php wpqf_tag_select( $wp_query_factory->field_list['minute'] ); ?>
			<span class="timespacer">:</span>
			<?php wpqf_tag_select( $wp_query_factory->field_list['second'] ); ?>
		</div>
		<div class="right_third_half">
			<label><?php _e('OR', 'wp-query-factory'); ?></label>
			<?php wpqf_tag_select( $wp_query_factory->field_list['w'] ); ?>
			<p class="description"><?php _e('If this parameter is selected it will override (and unselect) regular date options in relation to filtering.','wp-query-factory'); ?></p>
		</div>
		<br class="clear" />
		<p class="description"><?php _e('Note: This parameter will return posts for a specific date period in history, i.e. "Posts from X year, X month, X day". They are unable to fetch posts from a timespan relative to the present, so queries like "Posts from the last 30 days" or "Posts from the last year" are not part of this query option.', 'wp-query-factory'); ?></p>
	</fieldset>
	<fieldset class="clear">
		<legend><?php _e('Custom Fields', 'wp-query-factory'); ?></legend>
	</fieldset>
	<fieldset class="clear">
		<legend><?php _e('Permission', 'wp-query-factory'); ?></legend>
	</fieldset>
	<fieldset class="clear">
		<legend><?php _e('Caching', 'wp-query-factory'); ?></legend>
	</fieldset>
	<fieldset class="clear">
		<legend><?php _e('Field Parameters', 'wp-query-factory'); ?></legend>
	</fieldset>
	<fieldset class="clear">
		<legend><?php _e('Search Term', 'wp-query-factory'); ?></legend>
		<input type="text" name="query_builder[s]" value="<?php echo $wp_query_factory->field_list['s']['value']; ?>" />
		<p class="description"><?php _e('This uses keyword matching against the title and content fields (no custom field lookups).','wp-query-factory'); ?></p>
	</fieldset>
	<fieldset class="clear">
		<legend><label><?php _e('Filters', 'wp-query-factory'); ?></legend>
	</fieldset>
</div>
<div id="WP_User_Query" class="query_type">
	WP_User_Query Form
</div>
