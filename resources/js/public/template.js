//header
	$(".header .headernavbox .headernav").click(function(){
		$(this).addClass("navact").siblings().removeClass("navact");	
		
	});
		
	function showmainpage(){
	var url = "";
	window.location.href = url;
}
    $(function(){
	var txtitle=$("#txtitle"),
		loginlist=$("#loginlist");
		
		txtitle.click(function(e){
			if(!loginlist.is(":animated")){
				loginlist.slideToggle();		
			}
			e.stopPropagation();
		});
		$(document).click(function(){
			loginlist.slideUp();
			
		});
		
	$(".headernavbox").find("a.headernav:last").css({
		marginRight:0
	})	
	
});
$("#headerlogo").click(function(){
	location.reload();
});
$(document).ready(function(){var forNoStudent = $("#loginlist #no_student a").text();
if(!forNoStudent){
	$("#loginlist").addClass("onStuBox")

}});
$(function() {
	//小节详情页  上下
	$(".czsca").click(function(){
		$(this).toggleClass("up");
		var theight =$(this).parent().next().height();
		if($(this).hasClass("up")){
			if($(this).parent().next().height()>300){
				$(this).parent().next().slideUp(1500)}
			else{
				$(this).parent().next().slideUp(600)}

		} else {
			if($(this).parent().next().height()>300){
				$(this).parent().next().slideDown(1500)
			}
			else{
				$(this).parent().next().slideDown(600)
			}

		}
	});

	//小节详情页  返回顶部
	$(window).scroll(function () {
		if ($(window).scrollTop() > 100) {
			$(".goTop").fadeIn(500);

		}
		else {
			$(".goTop").fadeOut(500);
		}
	});
	$(".goTop").click(function () {
		$('body,html').animate({scrollTop: 0}, 0);
		return false;
	});
});

	

