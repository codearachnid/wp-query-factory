jQuery(document).ready(function($) {
	$("#meta_query_builder .inside select,#meta_query_template .inside select").chosen({ allow_single_deselect: true });
	$('#' + $("#query_type").val() + '.query_type').show();
	$("#query_type").chosen().change(function(){
		// alert('#'+ $(this).val());
		// $('#' + $(this).val() + '.query_type').show();
		$('.query_type').hide().siblings('#' + $(this).val() + '.query_type').show();
	});
});