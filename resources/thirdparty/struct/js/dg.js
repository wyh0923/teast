
function generateStruct(divId, data) {
	var str = "";
	var openStr = document.createElement("div");
	openStr.id = "structurBody";
	$("#"+divId).append(openStr);
	$("#structurBody").addClass("structurBody");
	var openUl = document.createElement("ul");
	openUl.id = "lessonList";
	$(".structurBody").append(openUl);
	$("#lessonList").addClass("lessonList");
	
	str = fn(data,1);

	$("#lessonList").append(str);
	$("#lessonList").on('mouseenter', 'li', function() { //就改这一行就可以了
		$(this).addClass("cur").siblings("li").removeClass("cur");
		$(this).children(".itemActions ").show();
	})
	$("#lessonList").on('mouseleave', 'li', function() { //就改这一行就可以了
		$(this).removeClass("cur");
		$(this).children(".itemActions ").hide();
	});
	$(".adddBtn").on('click', function() {
		$(this).siblings(".addBox").show();
	})
	$(".adddBtn").on({
		click: function() {
			$(this).siblings(".addBox").show();
		}
	});
}
	
function fn(res, level) {
	
	var data = res.sonPack;
	var strFn = "";
	for (var i = 0; i < data.length; i++) {
		if (level == 1) {
			strFn += "<li class='itemChaper clearfix cur'><div class='itemContent'>" + data[i].title + "</div><div class='itemActions padRight5'>";
			var datalen = data[i].func.length;
			if(datalen<=3){
				for (var j = 0; j < data[i].func.length; j++) {
					strFn += "<span class='adddBtn' " + data[i].func[j].funcType + "=\"" + data[i].func[j].func + "\"><i class='"+data[i].func[j].icon+"'></i>" + data[i].func[j].title+ "</span>"
				}
			}else if(datalen>3){
				strFn += "<span class='adddBtn' " + data[i].func[0].funcType + "=\"" + data[i].func[0].func + "\"><i class='"+data[i].func[0].icon+"'></i>" + data[i].func[0].title + "</span>";
				strFn += "<span class='adddBtn' " + data[i].func[1].funcType + "=\"" + data[i].func[1].func + "\"><i class='"+data[i].func[1].icon+"'></i>" + data[i].func[1].title + "</span>";
				strFn += "<span class='moreBtn'><i class='fa fa-angle-down fa-lg'></i>更多<div class='gdxx'>";
				for (var j = 2; j < data[i].func.length; j++) {
					strFn += "<span class='adddBtn' " + data[i].func[j].funcType + "=\"" + data[i].func[j].func + "\"><i class='fa fa-plus-circle fa-lg'></i>" + data[i].func[j].title + "</span>";
				}
				strFn += "</div></span></div></li>";
			}
		} else if (level == 2) {
			strFn += "<li class='itemChaper marginLeft15 clearfix'><div class='itemContent'>" + data[i].title + "<p>";
			for (var j = 0; j < data[i].statistic.length; j++) {
				strFn += data[i].statistic[j].title + ":" + data[i].statistic[j].count + "&nbsp;";
			}
			strFn += "</p></div><div class='itemActions padRight5'>";
			var datalen = data[i].func.length;
			if(datalen<=3){
				for (var j = 0; j < data[i].func.length; j++) {
					strFn += "<span class='adddBtn' " + data[i].func[j].funcType + "=\"" + data[i].func[j].func + "\"><i class='"+data[i].func[j].icon+"'></i>" + data[i].func[j].title + "</span>"
				}
			}else if(datalen>3){
				strFn += "<span class='adddBtn' " + data[i].func[0].funcType + "=\"" + data[i].func[0].func + "\"><i class='"+data[i].func[0].icon+"'></i>" + data[i].func[0].title + "</span>";
				strFn += "<span class='adddBtn' " + data[i].func[1].funcType + "=\"" + data[i].func[1].func + "\"><i class='"+data[i].func[1].icon+"'></i>" + data[i].func[1].title + "</span>";
				strFn += "<span class='moreBtn'><i class='fa fa-angle-down fa-lg'></i>更多<div class='gdxx'>";
				for (var j = 2; j < data[i].func.length; j++) {
					strFn += "<span class='adddBtn' " + data[i].func[j].funcType + "=\"" + data[i].func[j].func + "\"><i class='"+data[i].func[j].icon+"'></i>" + data[i].func[j].title + "</span>";
				}
				strFn += "</div></span></div></li>";
			}
		} else if (level == 3) {
			strFn += "<li class='itemChaper itemLesson clearfix'><div class='itemLine'></div>";
			strFn += "<div class='itemContent'>" + data[i].title + "<p>";
			for (var j = 0; j < data[i].statistic.length; j++) {
				strFn += data[i].statistic[j].title + ":<span class='number'>" + data[i].statistic[j].count + "</span>&nbsp;";
			}
			strFn += "</p></div><div class='itemActions padRight5'>";
			var datalen = data[i].func.length;
			if(datalen<=3){
				for (var j = 0; j < data[i].func.length; j++) {
					strFn += "<span class='adddBtn' " + data[i].func[j].funcType + "=\"" + data[i].func[j].func + "\"><i class='"+data[i].func[j].icon+"'></i>" + data[i].func[j].title + "</span>"
				}
			}else if(datalen>3){
				strFn += "<span class='adddBtn' " + data[i].func[0].funcType + "=\"" + data[i].func[0].func + "\"><i class='"+data[i].func[0].icon+"'></i>" + data[i].func[0].title + "</span>";
				strFn += "<span class='adddBtn' " + data[i].func[1].funcType + "=\"" + data[i].func[1].func + "\"><i class='"+data[i].func[1].icon+"'></i>" + data[i].func[1].title + "</span>";
				strFn += "<span class='moreBtn'><i class='fa fa-angle-down fa-lg'></i>更多<div class='gdxx'>";
				for (var j = 2; j < data[i].func.length; j++) {
					strFn += "<span class='adddBtn' " + data[i].func[j].funcType + "=\"" + data[i].func[j].func + "\"><i class='"+data[i].func[j].icon+"'></i>" + data[i].func[j].title + "</span>";
				}
				strFn += "</div></span></div></li>";
			}
		}
		strFn += fn(data[i].grand, level + 1)
			//alert(strFn);
	}
	return strFn;
}
$(".moreBtn").hover(function() {
			alert(0);
					$(this).find(".gdxx").stop(0).slideDown();
				}, function() {
					$(".gdxx").stop(0).slideUp();
				});	
	$(".moreBtn").on({
					mouseover:function(){
						$(this).next(".gdxx").slideDown();
						$(this).parent(".itemContent").show();
					},
					mouseleave:function(){
						$(this).next(".gdxx").sleep(2000).slideUp();
						//$(this).parent(".itemContent").hide();
					},
				});