$(function(){

	$(".user-type-selectbox").change(function(){
		if($(this).val() == "ion"){
			$(".ion-user-container").show();
			$(".local-user-container").hide();
		}else{
			$(".ion-user-container").hide();
			$(".local-user-container").show();
		}
	});

});