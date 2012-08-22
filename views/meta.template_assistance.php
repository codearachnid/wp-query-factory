<?php

if ( !defined('ABSPATH') )
	die('-1');

wp_nonce_field( parent::instance()->base_name, parent::DOMAIN );

?>
<p class="description"><?php _e('NOTE: use of "the_content()" tag in cases where a result of the query may be equal to the containing page will result in "the_content" filters not being applied to that result.', 'wp-query-factory'); ?></p>