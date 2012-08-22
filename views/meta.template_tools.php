<?php

if ( !defined('ABSPATH') )
	die('-1');

?>
<?php WP_Query_Factory_Template_Tags::select($templates, $post->to_ping, array('template_tools[default_template]'), __('Select default template', 'wp-query-factory'), false, true); ?>
<p class="description"><?php _e('Note: If this template is deleted then display of the query will disappear until you select a new template. However if the template ID is changed - your display will continue until the cache is cleaned.','wp-query-factory'); ?></p>