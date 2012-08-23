<?php

if ( !defined('ABSPATH') )
	die('-1');

?>
<div id="<?php echo self::FACTORY_TYPE; ?>-mce-select-query" class="dialog">
	<div class="header"><?php _e('WP Query Factory', 'wp-query-factory'); ?><span class="close">X</span></div>
	<div class="inside">
		<?php WP_Query_Factory_Template_Tags::select($queries, '', array('query_factory_id'=>'query_factory_id'), __('Select query', 'wp-query-factory'), false, true ); ?>
		<div class="insert">Insert</div>
	</div>
</div>
<script type="text/javascript">
jQuery(document).ready(function($) {
	$("select#query_factory_id").chosen({"search_enabled" : "false"});
});
</script>