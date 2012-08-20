jQuery(document).ready(function($) {
	$(".chzn-select").chosen();
	$('#' + $("#query_type").val() + '.query_type').show();
	$("#query_type").chosen().change(function(){
		// alert('#'+ $(this).val());
		// $('#' + $(this).val() + '.query_type').show();
		$('.query_type').hide().siblings('#' + $(this).val() + '.query_type').show();
	});
});