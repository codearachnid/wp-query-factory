<?php

if ( !defined('ABSPATH') )
	die('-1');

wp_nonce_field( parent::instance()->base_name, parent::DOMAIN );

?>
<label><?php _e('Template ID', 'wp-query-factory'); ?></label>
<input type="text" name="template_tools[post_name]" value="<?php echo $post->post_name; ?>" />
<p class="description"><?php _e('Note: If this template is deleted then display of any associated queries will disappear until you select a new template. However if the template ID is changed - your display will continue until the cache is cleaned.','wp-query-factory'); ?></p>