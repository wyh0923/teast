$(function(){ 

	var lzq_js = {};
	lzq_js.formyz = function(){
	 $('form :input').focus(function(){
      	var $parent = $(this).parent();
      	var $val = $(this).val();
      	if($(this).is('#username')){
			$parent.removeClass('error');
			$(this).next().removeClass("block").addClass("outHide");
			$parent.addClass('focus');
      	}
      	if($(this).is('#userPassword')){
      		$parent.removeClass('error');
			$(this).next().removeClass("block").addClass("outHide");
			$parent.addClass('focus');
      	}
      	/*if($(this).is('#vcode')){
      		$parent.removeClass('error');
      		$parent.addClass('focus');
      		$(this).next().removeClass("block").addClass("outHide");
      	}*/
            // $(".errorMsgs").removeClass("block").addClass("outHide")
      });
      $('form :input').blur(function(){
      	var $parent = $(this).parent();
      	var $val = $(this).val().trim();
      	if($(this).is('#username')){
      		if($val == ""){
      			$parent.addClass('error');
      			$(this).next().removeClass("outHide").addClass("block");
      		}else{
                        $parent.removeClass("focus");
                  }

      	}
      	if($(this).is('#userPassword')){
      		if($val == ""){
      			$parent.addClass('error');
      			$(this).next().removeClass("outHide").addClass("block");
      		}else{
                        $parent.removeClass("focus");
                  }
      	}
      	/*if($(this).is('#vcode')){
      		if($val == ""){
      			$parent.addClass('error');
      			$(this).next().removeClass("outHide").addClass("block");
      		}else{
                        $parent.removeClass("focus");
                  }
      	}*/
      });
      
      $("#loginbtn").click(function(){
      	var usr = $("#username").val().trim();
            var pwd = $("#userPassword").val().trim();
            //var vcode = $("#vcode").val().trim();
            if(usr==''){
                  $('#username').parent().addClass('error');
                  $('#username').next().removeClass("outHide").addClass("block");
                  $(this).css("disabled","disabled");
                  return false;
            }
            if(pwd==''){
                  $('#userPassword').parent().addClass('error');
                  $('#userPassword').next().removeClass("outHide").addClass("block");
                  $(this).css("disabled","disabled");
                  return false;
            }
            /*if(vcode==''){
                  $('#vcode').parent().addClass('error');
                  $('#vcode').next().removeClass("outHide").addClass("block");
                  $(this).css("disabled","disabled");
                  return false;//验证码
            }*/
	});


}
	lzq_js.formyz();
});
