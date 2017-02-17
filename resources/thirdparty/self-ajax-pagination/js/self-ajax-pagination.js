//搜索框的id必须为sapSearch
//php端返回的格式必须为。。。

function sapGetData(url,fnSuc,buttonId,jumpBtn){
	var pageNum = 1;
	if(jumpBtn!=null){
		pageNum = parseInt($('#'+jumpBtn+buttonId).attr('value'));
	}
	//pageNum = parseInt($('#'+jumpBtn+buttonId).attr('value'));
	 window['my_fnpageNum'+buttonId] = pageNum; 
	var cpu = $('.cpukur.filterCur').attr('type');
	var memory = $('.memorycur.filterCur').attr('type');
	var disk = $('.diskcur.filterCur').attr('type');
	var ostype = $('.ostypekur.filterCur').attr('os');
	var ctype = $('.typeFilterBtn.filterCur').attr('code');
	var author = $('.authorFilterBtn.filterCur').attr('code');
	//20161115新增题目类型（选择题目筛选）
	var question_type = $('#question_type').val();
	
	var obj={
		"keyword":$.trim($("#sapSearch"+'_'+buttonId).val()),
		"percount":5,
		"cpu":cpu,
		"diskSize":memory,
		"memorySize":disk,
		"osType":ostype,
		"ctype":ctype,
		"author":author,
		"page":pageNum,
		"question_type":question_type//20161115新增题目类型
	};
	
	
	$.ajax({
		type:'post',
		url:url,
		async:false,
		dataType:"json",
		data:obj,
		success:function(data){
			updateSelfAjaxPagination(data, fnSuc, buttonId);

			//eval(fnSuc + "(" + data["data"] + ")");
		}
	})
}
var myfnSuc ;
var myfnSucpageNum = 1 ;
function updateSelfAjaxPagination(data, fnSuc, buttonId){
	//console.log(data);
	//myfnSuc = fnSuc;
	myfnSucpageNum = window['my_fnpageNum'+buttonId];
	fnClass(data,myfnSucpageNum,buttonId,'','');
	fnSuc(data["data"]);
	//sapSuc1(data["data"]);
	var page = parseInt(data['currentpage']);
	var privPageNum = parseInt(data['currentpage']) - 1;
	var nextPageNum = parseInt(data['currentpage']) + 1;

	if (privPageNum < 1) {
		privPageNum = 1;
	}

	if (nextPageNum > parseInt(data['pagecount'])) {
		nextPageNum = parseInt(data['pagecount']);
	}

	$('#sap_prevBtn_'+buttonId).attr('value', privPageNum);
	$('#sap_nextBtn_'+buttonId).attr('value', nextPageNum);

	$('#sap_pageRange_'+buttonId).html("当前显示："+(page*5-4)+'-'+(page*5)+"，共"+ ( typeof(data['count'])=="number"?data['count']:0 ) +"条");
	$("#sap_currentPage_"+buttonId).html("当前页："+data["currentpage"] + "&nbsp;");


}

function showSelfAjaxPagination(divId, url, fnSuc){
	
window['my_fn'+divId] = fnSuc;
window['my_fnpageNum'+divId] = 1;
window['my_url'+divId] = 1;
// <div class="pageList">
	
// 	<span id="classpagectrl">1-10</span>
// 	<span id="currentpage"></span>
// 	<div class="nextPageBox" value="0" id="prevbtn"  disabled><i  class="fa fa-caret-left"></i></div>
// 	<div class="prevPageBox" value="2" id="nextbtn" disabled><i  class="fa fa-caret-right"></i></div>
						
// </div>
	/*
	var paginationHtml = "<div class=\"SelfAjaxPagination\">";
	paginationHtml += "<span class=\"sap_pageRange\" id=\"sap_pageRange_" + divId + "\" >1-10</span>";
	paginationHtml += "<span class=\"sap_currentPage\" id=\"sap_currentPage_" + divId + "\" ></span>";
	paginationHtml += "<div value=\"1\" class=\"sap_prevBtn\" id=\"sap_prevBtn_" + divId + "\" onclick=\"sapGotoPrevPage('" + url + "', '" + divId + "', " + fnSuc + ")\"><i class=\"fa fa-caret-left\"></i></div>";
	paginationHtml += "<div value=\"1\" class=\"sap_nextBtn\" id=\"sap_nextBtn_" + divId + "\" onclick=\"sapGotoNextPage('" + url + "', '" + divId + "', " + fnSuc + ")\"><i class=\"fa fa-caret-right\"></i></div>";
*/
	var paginationHtml ='<div class="page">'
	paginationHtml +='<ul>';
	paginationHtml +='<li class="back" id="firstPage'+divId+'" onclick=sapGotofirstPage("'+url+'","'+divId+'",'+fnSuc+') ><a href="javascript:void(0)"><i class="fa fa-angle-double-left fw"></i>&nbsp;首页</a></li>';
	paginationHtml +='<li class="back" value2='+fnSuc+'  value1="'+url+'" value="0" id="prevPage'+divId+'" onclick=sapGotoPrevPage("'+url+'","'+divId+'",'+fnSuc+') ><a href="javascript:void(0)"><i class="fa fa-angle-left fw"></i>&nbsp;上一页</a></li> ';
	paginationHtml +='<li class="next" value="2" id="nextPage'+divId+'" onclick=sapGotoNextPage("'+url+'","'+divId+'",'+fnSuc+') ><a href="javascript:void(0)">下一页&nbsp;<i class="fa fa-angle-right fw"></i></a></li>';
	paginationHtml +='<li class="next" id="lastPage'+divId+'" onclick=sapGotolastPage("'+url+'","'+divId+'",'+fnSuc+') ><a href="javascript:void(0)">尾页&nbsp;<i class="fa fa-angle-double-right fw"></i></a></li>';
	paginationHtml +='</ul>';
	paginationHtml +='</div>';//alert(paginationHtml);
	
	$("#" + divId).html(paginationHtml); 
	//$("#" + divId).append(paginationHtml); 
	//$("#nextPage" + divId).click();
	//$("#prevPage" + divId).click();
	//sapGetData(url,fnSuc,divId, "firstPage");
	//sapGetData(url,fnSuc,divId,null);
	//sapGetData(url, fnSuc, null);

}


function sapGotoNextPage(url, buttonId, fnfunc){
	sapGetData(url,fnfunc,buttonId, "nextPage");
}

function sapGotoPrevPage(url, buttonId, fnfunc){
	sapGetData(url,fnfunc,buttonId, "prevPage");
}


function sapGotofirstPage(url, buttonId, fnfunc){
	sapGetData(url,fnfunc,buttonId, "firstPage");
}

function sapGotolastPage(url, buttonId, fnfunc){
	sapGetData(url,fnfunc,buttonId, "lastPage");
}

//
//function sapGotoNextPage(url, buttonId, fnfunc){
//	sapGetData(url,fnfunc,buttonId, "sap_nextBtn");
//}
//
//function sapGotoPrevPage(url, buttonId, fnfunc){
//	sapGetData(url,fnfunc,buttonId, "sap_prevBtn");
//}


var mytab = true;//标志选择班级或学员列表。

function pageclick(pagenumstr,buttonId){
	var url = $('#prevPage'+buttonId).attr('value1');
	//var fnfunc1 = $('#prevPage'+buttonId).attr('value2');
	var fnfunc1 =window['my_fn'+buttonId]; 
	sapGetData(url,window[fnfunc1+""],buttonId, pagenumstr);
	 
}

 

function diclen(data)
{
  i = 0;
  for(var key in data){
    i++
  }
  return i;
}

 
 

function fnClass(message,item,buttonId,url,fnfunc){
	var str = '',
    classes = message['data'];
	
	console.log(classes);
	$("#" + buttonId +" .page").show();
	
//console.log(classes);
	var page ;
	if(item==null){
		page=1;
	}else{
		 page = parseInt($('#'+item).attr('value'));
	}
	 page  = item;
	var obj={
		"keyword":$("#searchIpt").val(),
		"page":page	
	}
    pagecount = message['pagecount'];

    fivepagestart = 1;
	
	if (page >5)
	{
		fivepagestart = Math.floor((page-1)/5)*5+1;
	}

    fivepageend = fivepagestart +4;

	if (fivepageend>=pagecount )
	{
		fivepageend = pagecount;
	}

	var nextobj = $('#nextPage'+buttonId);

	$("li").remove(".ispage");

	for(var i=fivepagestart;i<=fivepageend;i++)
	{
		if (page == i)
		{
			pageclass = "act";
		}
		else
		{
			pageclass = "";
		}
		nextobj = nextobj.before("<li id=\"pagenum"+i+buttonId+"\" onclick=\"pageclick('pagenum"+i+"','"+buttonId+"')\" value='"+i+"' class=\"ispage "+pageclass+"\"><a href='javascript:void(0)'>"+i+"</a></li>");
		
		
		//nextobj = nextobj.before("<li id=\"pagenum"+i+"\" onclick=\"pageclick('pagenum"+i+"')\" value='"+i+"' class=\"ispage "+pageclass+"\"><a href='javascript:void(0)'>"+i+"</a></li>");
	}


	if(parseInt(message['pagecount'])>page){
		$('#nextPage'+buttonId).removeAttr('disabled');
		$('#nextPage'+buttonId).attr('value',page+1);
		$('#nextPage'+buttonId).css("background","#FFF");
		$('#lastPage'+buttonId).css("background","#FFF");
		$('#lastPage'+buttonId).removeAttr('disabled');
		
		$('#lastPage'+buttonId+' a').removeClass('firstPage_a_dis');
		$('#nextPage'+buttonId+' a').removeClass('firstPage_a_dis');
	}else{
		$('#nextPage'+buttonId).attr('value',page);
		$('#nextPage'+buttonId).css("background","#ccc");
		$('#nextPage'+buttonId).attr('disabled','disabled'); 
		$('#lastPage'+buttonId).css("background","#ccc");
		$('#lastPage'+buttonId).attr('disabled','disabled');
		
		$('#lastPage'+buttonId+' a').addClass('firstPage_a_dis');
		$('#nextPage'+buttonId+' a').addClass('firstPage_a_dis');
	}
	if(page>1){
		$('#prevPage'+buttonId).removeAttr('disabled');
		$('#prevPage'+buttonId).attr('value',page-1);
		$('#prevPage'+buttonId).css("background","#FFF");
		$('#firstPage'+buttonId).css("background","#FFF");
		$('#firstPage'+buttonId).removeAttr('disabled');
		
		$('#prevPage'+buttonId+' a').removeClass("firstPage_a_dis"); 
		$('#firstPage'+buttonId+' a').removeClass("firstPage_a_dis"); 
	}else{
		$('#prevPage'+buttonId).attr('value',page);
		$('#prevPage'+buttonId).css("background","#ccc");
		$('#prevPage'+buttonId).attr('disabled','disabled');
		$('#firstPage'+buttonId).css("background","#ccc");
		$('#firstPage'+buttonId).attr('disabled','disabled');
		
		$('#prevPage'+buttonId+' a').addClass('firstPage_a_dis');
		$('#firstPage'+buttonId+' a').addClass('firstPage_a_dis');
		
	}
	$("#firstPage"+buttonId).attr('value',1);
	$("#lastPage"+buttonId).attr('value',pagecount);//console.log( window['my_fnpageNum'+buttonId]);
	if(classes.length == 0){
		$("#" + buttonId +" .page").hide();return;  
	}else{
		//$("#" + buttonId +" .page").show();
	}
}
 

