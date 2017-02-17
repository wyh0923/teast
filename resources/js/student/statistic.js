$(function(){
    function get_personal_ajax(){

        $.ajax({
            type: 'POST',
            url: site_url+'Personal/get_personal_ajax',
            data: '',
            dataType: "json",
            success: function(message){
                if(message.code == '0000'){
                    $('#StudyTotal').html(message.data.study);
                    $('#ExamTotal').html(message.data.exam);
                    $('#ScoreTotal').html(message.data.total_score);
                    $('#SectionTotal').html(message.data.section);
                }
            }
        });

    }
    get_personal_ajax();
    setInterval(function(){
        get_personal_ajax();
    },10000)


});
//跳转
function gotourl(url) {
    window.location.href = site_url + url;
}
// function setLeRight(){
//         var t=($(window).height()-42)/2-80
//         $(".goToThere").css({"top":t})
//     };
    function showAndHide(){
        var zonghe = $(".mapsBox").length;

        if($(window).scrollTop()>120){
            if(zonghe>2){
                 $(".goToThere").fadeIn().addClass("goToThere2");
            }
           else{
           $(".left_icon").removeClass("left_btn").fadeIn();
            $(".right_icon").removeClass("right_btn").fadeIn();  
        }
          
      } 
      else{
        $(".goToThere").fadeOut()
      } 
    }
function goToThere(){
    var winWidth = $(window).width();//;
    var  t = ($(".main").height()-42)/2+100
    var left = (winWidth-1100)/2
    console.log(winWidth)
    if(winWidth>1280){
        $(".goToThere").css({"bottom":t}) 
        $(".left_icon").css({"left":left+190});
        $(".right_icon").css({"right":left-80});
    }
    else{

        $(".goToThere").css({"bottom":"170px"})
       $(".left_icon").css({"left":'51%'});
        $(".right_icon").css({"right":'32%'}); 
    }

}
    $(window).resize(function(){           

        //setLeRight();
        goToThere();
    })
    $(window).scroll(function(){

        //setLeRight();
        showAndHide();
    })
    $(function(){
        //setLeRight();
        showAndHide();
        goToThere();

    })
    $(document).ready(function(){
        //$(".mapsPars").eq(1).css({"height":900})测试高度问题
        
        //
       
       
        var mappage=1;//初始化当前的版面为1
        var $show=$(".flyTo");//找到活动框
         var map_count=$show.find(".mapsPars").length;
        var maxHeight = 0//找出最大的高度
        
        var flyToWidth= map_count*830
        // if($(".mapsPars").eq(map_count-1).height()<190){
        //     flyToWidth= flyToWidth-830;
        //     map_count = map_count-1;
        //     $(".mapsPars").eq(map_count-1).remove();
        // }
        for(i=0;i<map_count;i++){
            if($(".mapsPars").eq(i).height()+40>maxHeight){
            maxHeight=$(".mapsPars").eq(i).height()+40
            }
        }
        $(".lookHere").css({"height":maxHeight})
        $(".flyTo").css("width",flyToWidth)
        function mapgo(){
            if(!$show.is(":animated")&&mappage<map_count){
                $show.animate({left:'-='+830},"normal");
                // var again = $(".mapsPars").eq(mappage).height()+40;
                // if(again>parHeight){
                //     $(".lookHere").css({"height":again})
                // }
                // else{
                //     $(".lookHere").css({"height":parHeight})
                // }
                mappage++;


            }
            else if (!$show.is(":animated")&&mappage==map_count){
            $show.animate({left:0},"normal");
            mappage=1;
            return;
                }
            
            }
        $(".right_btn").click(function(){
            mapgo();
            
        })
        $(".left_btn").click(function(){
        if(!$show.is(":animated")&&mappage>1){
            $show.animate({left:'+='+830},"normal");
            mappage--;


            }
        else if (!$show.is(":animated")&&mappage==1){
            var fff = -(map_count -1)*830;
            $show.animate({left: fff },"normal");
            mappage=map_count;
            return;
            
                }
            
        })
    })