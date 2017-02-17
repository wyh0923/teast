/**
 * Created by qirupeng on 2016/8/30.
 */
//弹框table中详情的弹起与收起
var downArr = new Array();
function downMy(isme){
    var parenIndex = $(isme).parent().parent().index(),
        downMy = $(".moban tr").eq(parenIndex+1),
        myChild = $(isme).children("i"),
        downNumber = $("#vmTemplatelist .moban .bugvminfo").length
        ,code = $(isme).attr("uuid");

        if($(downMy).css("display")=="none"){
                       $(downMy).removeClass("outHide");
                       $(myChild).removeClass("fa-angle-double-right").addClass("fa-angle-double-down");
                       downArr.push(code)
                       if(jQuery.inArray(code,downArr) == -1){
                                downArr.push(code);
                            }
                       }
        else{
                       $(downMy).addClass("outHide");
                       $(myChild).removeClass("fa-angle-double-down").addClass("fa-angle-double-right");
                        $.each(downArr,function(n,m){
                                if(m == code){
                                    downArr.splice($.inArray(code,downArr),1);
                                }
                            })
                      }
                       
                openall();
          
            event.stopPropagation()

    }
//遍历本页是否被全部打开
function openall(){
     yanzheng = $("#vmTemplatelist .moban .outHide").length
             if(yanzheng!=0){
               $(".downAll").children("i").removeClass("fa-angle-double-down").addClass("fa-angle-double-right");

            }  

            else{
                $(".downAll").children("i").removeClass("fa-angle-double-right").addClass("fa-angle-double-down");
               
            }
}
function downAll(isme){

        if($(".moban .outHide").length!=0){
            $(".bugvminfo").removeClass("outHide");
            $(".downMy").children("i").removeClass("fa-angle-double-right").addClass("fa-angle-double-down");
            $(isme).children("i").removeClass("fa-angle-double-right").addClass("fa-angle-double-down");
           
            for(i=0;i<$("#vmTemplatelist .moban .firstNext").length;i++){
                var code = $("#vmTemplatelist .moban .firstNext").eq(i).children().find(".downMy").attr("uuid")
                     $('#ques .downMy').each(function(){
                        if(jQuery.inArray(code,downArr) == -1){
                            downArr.push(code);
                        }
                    })
                    
            } 


            }
        else  {
            $(".bugvminfo").addClass("outHide");
            $(".downMy").children("i").removeClass("fa-angle-double-down").addClass("fa-angle-double-right");
            $(isme).children("i").removeClass("fa-angle-double-down").addClass("fa-angle-double-right");
            for(i=0;i<$("#vmTemplatelist .moban .firstNext").length;i++){
                var code = $("#vmTemplatelist .moban .firstNext").eq(i).find(".downMy").attr("uuid")
            $.each(downArr,function(n,m){
                                if(m == code){
                                    downArr.splice($.inArray(code,downArr),1);
                                }
                            })
            
                  }
            }
       
        event.stopPropagation()
    
        
    
    
    }
$(function(){
    var vmnl = new VMNetLayout("#scene_topo_layout",base_url);
    //var hostIdArr = new Array();
    $('.createscene').click(function(){
        var scenename = $('#scenename').val();
        if (scenename == '') {
            $('.errors').html('请填写场景名称!');
            return;
        }else if(scenename.length<2 || scenename.length>255){
            $('.errors').html('场景名称由2-255位字符组成');
            return;
        }
        var scenedesc = $('#scenedesc').val();
        if (scenedesc == '') {
            $('.errors').html('请填写场景描述!');
            return;
        }else if(scenedesc.length>1024){
            $('.errors').html('场景描述不能超过1024个字符');
            return;
        }
        var str = $('.op-container:eq(0)').html();
        if (str == '') {
            $('.errors').html('操作区没有选择虚拟机！');
            return;
        }
        $('.errors').html('');

        $.ajax({
            url:site_url+"Train/add_scene",
            type:'post',
            data:{'code':vmnl.getData(), 'name':scenename, 'desc':scenedesc , 'host_id':hostIdStr},
            dataType:'json',
            success:function(message){
                if(message.code=='0000'){
                    $('.errors').html('创建成功');
                    setTimeout(function(){
                        location.href = site_url + 'Train/scenelist';
                    },1000);

                }else{
                    $('.errors').html(message.msg);
                }
            },

        })
    });
    //关闭提示
    $('.close,.publicNo').click(function(){
        quescontents = new Array();
        totalcheck = new Array();
        downArr = new Array();
        vmTranPack = null;
        $("#sapSearch_pageContainer").val('')
        $('#vmTemplatelist .total span').html(totalcheck.length);
        fnHide("#vmTemplatelist","fadeInDown","fadeOutUp");
    });

});