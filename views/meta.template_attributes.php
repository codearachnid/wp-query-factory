<?php

if ( !defined('ABSPATH') )
	die('-1');

wp_nonce_field( parent::instance()->base_name, parent::DOMAIN );

?>
<label><?php _e('Template ID', 'wp-query-factory'); ?></label>
<input type="text" name="template_tools[post_name]" value="<?php echo $post->post_name; ?>" />