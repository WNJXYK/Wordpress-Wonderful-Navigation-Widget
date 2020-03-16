jQuery(document).ready(function($) {
	$(".wnav-widget-icon").click(function(){
		if($(this).parent().hasClass("active")){
			$(this).parent().removeClass("active");
		}else $(this).parent().addClass("active");
	});
});
