	//弹出层的位置

	
	function setPosi(boxId){
		var popNumber = $(boxId).length,
		    $winH=$(window).height();
			
			
		for(i=0;i<popNumber;i++){
			var myheight = $(boxId).eq(i).height()
			var mywin =  $(boxId).eq(i).width()
			l=($(window).width()-mywin)/2,
			t=($winH-myheight)/2+$(window).scrollTop();
			$(boxId).eq(i).css({
			"left":l+"px",
			"top":t-20+"px",
				})
			$(".top175").css({
				"left":l+"px",
				"top":175+"px",
				})
			
			
		}
		 
	}
	
		
		
	function fnShow(boxId,x,y){
		
		$(".maskbox").show().css({"opacity":1});
		$(boxId).show();
		$(boxId).removeClass(x).addClass(y);
		
		
	}
	function fnHide(boxId,x,y,flag){

		$("body").css("overflow-y","scroll");
/*		setWh("maskBox");*/
		if(flag){
			$(boxId).removeClass(x).addClass(y);
			if($(boxId).attr("class").indexOf(y)!=-1){
				$(".maskbox").animate({
						opacity:1
					},"slow",function(){	
						$(".maskbox").show();	
						$(boxId).hide();
						
					})
					
				
				}
			
			}
		else{
			$(boxId).removeClass(x).addClass(y);
			$(boxId).siblings(boxId).removeClass(x).addClass(y);
			if($(boxId).attr("class").indexOf(y)!=-1){
				$(".maskbox").animate({
						opacity:0
					},"slow",function(){	
						$(".maskbox").hide();	
						$(boxId).hide();
						$(boxId).siblings(".popUpset").hide();
					})
					
				
				}
			
			}
		
	}
	
	function pub_error(msg, timeout, callback) {
		var boxHtml =	'<div class="okBoxpub animated" id="pub_okBox" style="width:400px;">\
		    <h3 style="width:400px;height:40px;line-height:40px;background:#000;color:#fff;text-align:center;border-bottom:3px solid rgb(249, 183, 40);border-right:1px solid #000;">提示框\
		        <span id="okClose"></span>\
		    </h3>\
		    <div class="wait">\
		        <!--i class="fa fa-check-circle-o fa-4x"></i-->\
		        <p></p>\
		    </div>\
		</div>';
	    if ($(".okBoxpub").length == 0) {
	        $("body").append(boxHtml);
	    }
		//<i class="fa fa-check-circle-o fa-2x fa-fw" style="color:#46c47a; font-weight: normal;  left: 50%;margin-left: -90px; margin-top: -5px; position: absolute;top: 50%;" ></i>
		 $('#pub_okBox p').html('<span >'+msg+'</span>');
	     
		 fnShow("pub_okBox", "fadeOutUp", "fadeInDown");
		 setTimeout(function () {
	    	 fnHide("pub_okBox", "fadeInDown", "fadeOutUp");
	        //lock = 0;
	    	 window[callback](); 
	        //eval(callback);
	    }, timeout ? timeout : 2000);
	}
	
<!--初始化所有弹窗-->

    

$(window).resize(function(){           

        setPosi(".popUpset");
    })
$(window).scroll(function(){

	setPosi(".popUpset");
})
$(function(){
	setPosi(".popUpset");

})

//公共关闭按钮
$(".close-1").click(function(){
	var closePar=$(this).parent().parent().parent()
		fnHide(closePar,"fadeInDown","fadeOutUp")
	
	})//如果只有一层弹窗，调用close-1类
$(".close-2").click(function(){
	var closePar=$(this).parent().parent().parent()
		fnHide(closePar,"fadeInDown","fadeOutUp",2)
	
	})//如果有子层弹窗，调用close-2类
$(".hidePop-1").click(function(){
		var hidePopBox = $(this).parent().parent().parent().parent()
		fnHide(hidePopBox,"fadeInDown","fadeOutUp")
		})//如果只有一层弹窗，调用hidePop-1类
$(".hidePop-2").click(function(){
		var hidePopBox = $(this).parent().parent().parent().parent()
		fnHide(hidePopBox,"fadeInDown","fadeOutUp",2)
		})//如果只有一层弹窗，调用hidePop-2类
//没有表单的
$(".close-3").click(function(){
	var closePar=$(this).parent().parent()
		fnHide(closePar,"fadeInDown","fadeOutUp")
	
	})//如果只有一层弹窗，调用close-1类
$(".close-4").click(function(){
	var closePar=$(this).parent().parent()
		fnHide(closePar,"fadeInDown","fadeOutUp",2)
	
	})//如果有子层弹窗，调用close-2类
$(".hidePop-3").click(function(){
		var hidePopBox = $(this).parent().parent().parent()
		fnHide(hidePopBox,"fadeInDown","fadeOutUp")
		})//如果只有一层弹窗，调用hidePop-1类
$(".hidePop-4").click(function(){
		var hidePopBox = $(this).parent().parent().parent()
		fnHide(hidePopBox,"fadeInDown","fadeOutUp",2)
		})//如果只有一层弹窗，调用hidePop-2类
function close1(thisup){
	
	var closePar=$(thisup).parent().parent().parent()
		fnHide(closePar,"fadeInDown","fadeOutUp")
	
	}//如果只有一层弹窗，调用close-1函数
function close2(thisup){
	var closePar=$(thisup).parent().parent().parent()
		fnHide(closePar,"fadeInDown","fadeOutUp",2)
	
	}//如果有子层弹窗，调用close-2函数
function hidePop1(thisup){
		var hidePopBox = $(thisup).parent().parent().parent().parent()
		fnHide(hidePopBox,"fadeInDown","fadeOutUp")
		}//如果只有一层弹窗，调用hidePop1函数
function hidePop2(thisup){
		var hidePopBox = $(thisup).parent().parent().parent().parent()
		fnHide(hidePopBox,"fadeInDown","fadeOutUp",2)
		}//如果只有一层弹窗，调用hidePop2函数
	//特殊字符转义
	function translate(str) {
		str = str.replace(/\+/g,"%2B");
		str = str.replace(/!/g,"%21");
		//str = str.replace(/"/g,"%22");
		str = str.replace(/#/g,"%23");
		str = str.replace(/\$/g,"%24");
		str = str.replace(/\&/g,"%26");
		str = str.replace(/'/g,"%27");
		str = str.replace(/\(/g,"%28");
		str = str.replace(/\)/g,"%29");
		str = str.replace(/\*/g,"%2A");
		str = str.replace(/,/g,"%2C");
		str = str.replace(/\./g,"%2E");
		str = str.replace(/;/g,"%3B");
		str = str.replace(/\=/g,"%3D");
		str = str.replace(/\?/g,"%3F");
		str = str.replace(/@/g,"%40");
		str = str.replace(/\\/g,"%5C");
		return str;
	}

