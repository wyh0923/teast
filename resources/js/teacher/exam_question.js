
//题干图片
function imgLian(){
    $(".queTxt img").each(function(){
        var that = $(this);
        var url = that.attr("src");
        var url2 =url.substr(6);
        var url3 = url.substr(url.length-4).toLocaleLowerCase();
        var name = that.attr("alt");
        var urlArr = [".png",".jpg",".gif","jpeg"];
        if(jQuery.inArray(url3,urlArr) == -1){
            that.replaceWith('<a  href="'+ base_url + url2 +'">'+ name +'</a>');
        }
        else{
            $(this).attr("src",base_url+url2);
        }


    })
}
//题目带有附件列表
function dataGo(){
    $(".queTxt").each(function(){
            var dataname = $(this).attr("dataname");
            var dataurl  = $(this).attr("dataurl");
            var nameArr = dataname.split("," );
            var urlArr = dataurl.split("," );
            var dataGostr='';
            var urlType = [".png",".jpg",".gif","jpeg"];
            if(parseInt(nameArr[0])!=0){
                for(ss=0;ss<nameArr.length;ss++){
                    var urll = urlArr[ss];
                    var ifurl = urll.substr(urll.length-4)
                        if(jQuery.inArray(ifurl,urlType) != -1){
                            dataGostr += '<a href="'+base_url+urlArr[ss].substr(6)+'" target="_blank">'+nameArr[ss]+'</a>';
                            
                        } else{
                            dataGostr += '<a href="'+base_url+urlArr[ss].substr(6)+'" >'+nameArr[ss]+'</a>';

                        }
                    
                }
                dataGostr="<p style='margin-top:15px'>附件："+dataGostr+"</p>";
                $(this).append(dataGostr);
            }

    })

}
$(document).ready(function(){
    imgLian();
    dataGo();
});