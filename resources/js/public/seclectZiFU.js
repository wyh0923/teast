function macziFu(){   
//计算字节长度
var selectw = $("select").width(),
	zifuShu = Math.round(selectw/20)*3;
String.getBlength = function (str) {
for (var i = str.length, n = 0; i--; ) {
n += str.charCodeAt(i) > 255 ? 2 : 1;
}
return n;

}
//按指定字节截取字符串
String.cutByte = function (str, len, endstr) {
    var len = +len,
    endstr = typeof(endstr) == 'undefined' ? "..." : endstr.toString(),
    endstrBl = this.getBlength(endstr);
    function n2(a) {var n = a / 2 | 0; return (n > 0 ? n : 1)}//用于二分法查找
    if (!(str + "").length || !len || len <= 0) {
        return "";
    }
    if(len<endstrBl){
        endstr = "";
        endstrBl = 0;
    }
    var lenS = len - endstrBl,
    _lenS = 0,
    _strl = 0;
    while (_strl <= lenS) {
        var _lenS1 = n2(lenS - _strl),
        addn = this.getBlength(str.substr(_lenS, _lenS1));
        if (addn == 0) {return str;}
        _strl += addn
        _lenS += _lenS1
    }
    if(str.length - _lenS > endstrBl || this.getBlength(str.substring(_lenS-1))>endstrBl){
        $("select option").eq(i).attr("title",str)
        $("select option").eq(i).text(str.substr(0, _lenS - 1) + endstr)
        
    }else{
        return str;
    }    
}
var childLength = $("select").children().length;
for (var i = 0;i<=childLength-1;i++) {
      var str = $("select option").eq(i).text();
	  	if ( navigator.userAgent.indexOf("Chrome") > -1){
			String.cutByte(str,zifuShu,'...');
			

 }else{String.cutByte(str,zifuShu-3,'...');}
      
	  
  }
 }      
$(document).ready(function(){
	 macziFu();
	
	
	})

//防止选择项目字符过长