function page( pagepre ,pagecount, pageurl , numsize){
	var lastpage = null;
	var firstpage = null;
	var size = parseInt((numsize-1)/2);

	if(pagepre <= size ){
		firstpage = 1;
		lastpage = numsize;
	}else{
		firstpage = pagepre-size;
		lastpage =  pagepre+size;
	}
	if( lastpage>pagecount ){
		lastpage = pagecount;
	}
	if( pagecount-pagepre<=size ){
		firstpage = pagecount-numsize+1;
		lastpage = pagecount;
	}
	if( firstpage<1 ){
		firstpage = 1;
	}
	//var pre_page = pageurl +  parseInt(pagepre-1);
	var pre_page = parseInt(pagepre-1);
	//var nex_page = pageurl +  parseInt(pagepre+1);
	var nex_page = parseInt(pagepre+1);

	pagetext = (pagepre!=1) ? "<ul><li class='back'><a href='" +pageurl+ "1" + "'><i class='fa fa-angle-double-left'></i>首页</a></li>&nbsp;" : "<ul><li class='back'><a href='" +'javascript:;' + "'><i class='fa fa-angle-double-left'></i>首页</a></li>&nbsp;";
	pagetext +=(pre_page!=0) ? "<li class='back'><a href='"+pageurl+pre_page+"'><i class='fa fa-angle-left'></i>上一页</a></li>&nbsp;":"<li class='back'><a href='"+'javascript:;'+"'><i class='fa fa-angle-left'></i>上一页</a></li>&nbsp;";
	for(i=firstpage;i<=lastpage;i++){
		if( i==pagepre ){
			pagetext += "<li class='pagenum act' ><a href='"+pageurl+i+"'>" + i + "</a></li>&nbsp;";
		}else{
			pagetext += "<li class='pagenum'><a href='"+pageurl+i+"'>"+i+"</a></li>&nbsp;";
		}

	}

	pagetext += (nex_page<=pagecount)?"<li class='next'><a href='"+pageurl+nex_page+"'>下一页<i class='fa fa-angle-right'></i></a></li>&nbsp;":"<li class='next'><a href='"+'javascript:;'+"'>下一页<i class='fa fa-angle-right'></i></a></li>&nbsp;";
	pagetext += (pagepre!=pagecount) ? "<li class='next'><a href='" +pageurl + pagecount + "' >尾页<i class='fa fa-angle-double-right'></i></a></li>&nbsp;</ul>" : "<li class='next'><a href='" +'javascript:;' + "' >尾页<i class='fa fa-angle-double-right'></i></a></li>&nbsp;</ul>";
	return pagetext;
}
$(function(){


})