<?php

if ( !defined('ABSPATH') )
	die('-1');

?>
<?php wpqf_tag_select( $wpqf->field_list['default_template'] ); ?>
<p class="description"><?php _e('Note: If this template is deleted then display of the query will disappear until you select a new template. However if the template ID is changed - your display will continue until the cache is cleaned.','wp-query-factory'); ?></p>