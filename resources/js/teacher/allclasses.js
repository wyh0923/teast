/**
 * Created by qirupeng on 2016/8/23.
 */
//多选删除确定框
function delAllTeacher() {
    if(arrTeacher.length < 1){
            $('#okBox p.promptNews').html('请选中要删除的班级');
            fnShow("#okBox","fadeOutUp","fadeInDown");
            setTimeout(function(){
                fnHide("#okBox","fadeInDown","fadeOutUp");
            },2000)

    }
    else{
      fnShow("#delAll","fadeOutUp","fadeInDown");  
    }
}
$(function(){
    $(".csear").click(function(){
        var search = translate($.trim($(".esear").val()));
        var str = '';
        if (time != '')str += '/time/'+ time;
        window.location.href=site_url + 'Classstaff/myclass' +str+ "/search/"+encodeURI(search);
    });
    $('.esear').keydown(function(e){
        if(e.keyCode==13){
            var search = translate($.trim($(".esear").val()));
            var str = '';
            if (time != '')str += '/time/'+ time;
            window.location.href=site_url + 'Classstaff/myclass' +str+ "/search/"+encodeURI(search);
        }
    });

    //排序
    $('#CreateTime,#StudentNum,#TaskNum,#TaskScore').click(function(){
        var field = $(this).attr("id");
        var code = $(this).attr('code');
        var str = '';
        if (time != '')str += '/time/'+ time;
        if (search != '')str += '/search/'+translate(search);
        if(code == 'DESC'){
            location.href = site_url+'Classstaff/myclass' + str + '/sort/'+field+' ASC';
        }else if(code == 'ASC'){
            location.href = site_url+'Classstaff/myclass' + str + '/sort/'+field+' DESC';
        }else{
            location.href = site_url+'Classstaff/myclass' + str + '/sort/'+field+' DESC';
        }
    });
    //全选
    $("#checkAll").click(function(){
        arrTeacher = [];//统计之前清空防止重复
        if(this.checked){

            $("input[name='checkTeacher']").each(function(){
                this.checked=true;
                var code=$(this).attr("data-code");
                arrTeacher.push(code);
            });

        }else{
            $("input[name='checkTeacher']").each(function(){
                this.checked=false;
            });

        }
    });
    //单记录删除
    $(".delOne").click(function(){
        var code=$(this).attr('code');
        $.ajax({
            url:site_url+"Classstaff/classtask",
            type:'post',
            data:{'cid':code},
            dataType:'json',
            success:function(message){
                if(message.code=='0000'){

                    fnShow("#okBox","fadeOutUp","fadeInDown");
                    $('#okBox p.promptNews').html('该班级有未完成任务，不可删除');
                    setTimeout(function () {
                        fnHide("#okBox","fadeInDown","fadeOutUp");
                    },2000);
                }else{
                    $('#okBtn').attr('code',code);
                    fnShow("#one_del","fadeOutUp","fadeInDown");
                }
            }

        })

    });
    //确定删除单记录
    $('#okBtn').click(function(){
        var usercode = $(this).attr('code');
        fnHide("#one_del","fadeInDown","fadeOutUp",1);
        $.ajax({
            url:site_url+"Classstaff/del_classes",
            type:'post',
            data:{'classcode':usercode},
            dataType:'json',
            success:function(message){

                if(message.code=='0000'){

                    fnShow("#okBox","fadeOutUp","fadeInDown");
                    $('#okBox p.promptNews').html('删除成功');
                    setTimeout(function () {
                        window.location = site_url+"Classstaff/myclass";
                    },2000);
                }else{

                    $('#okBox p.promptNews').html('删除失败');
                    fnShow("#okBox","fadeOutUp","fadeInDown");
                    setTimeout("location.reload()",2000);
                }
            }

        })
    });
    //多选删除 确定按钮
    $('#delAllTeacherBtn').click(function(){
            fnHide("#delAll","fadeInDown","fadeOutUp",1);
            $.ajax({
                url:site_url+"Classstaff/del_classes",
                type:'post',
                data:{'classcode':JSON.stringify(arrTeacher)},
                dataType:'json',
                success:function(message){
                    if(message.code=='0000'){

                        fnShow("#okBox","fadeOutUp","fadeInDown");
                        $('#okBox p.promptNews').html('删除成功');
                        setTimeout("location.reload()",2000);
                        // window.location.href=site_url+'Classstaff/myclass'

                    }else{

                        $('#okBox p.promptNews').html('删除失败');
                        fnShow("#okBox","fadeOutUp","fadeInDown");
                        setTimeout("location.reload()",2000);

                    }
                }

            })
        
    });

});


function searchForTime(){
    if($("#stime").val() != "" && $("#etime").val() != ""){
        if($("#stime").val() >= $("#etime").val()){
            $("#okBox p.promptNews").html('开始时间不能大于等于结束时间');
            fnShow("#okBox","fadeOutUp","fadeInDown");
            setTimeout(function(){
                window.location.reload();
            },2000)
        } else {
            var str = '';
            if (search != '')str += '/search/'+translate(search);
            window.location.href =  site_url+'Classstaff/myclass' + str +'/time/'+ $("#stime").val() + "_" + $("#etime").val()
        }
    }

}
var arrTeacher = new Array();//统计页面被选中的班级
function checkThis(isme,all){
   var inputNumbers =  $(isme+" tr input").length;
   arrTeacher = [];//统计之前清空防止重复
    $("input[name='checkTeacher']").each(function(){
            if(this.checked == true){
                var code=$(this).attr("data-code");
                arrTeacher.push(code);
            }
        });
    if(arrTeacher.length==inputNumbers){
      document.getElementById(all).checked = true;
    }
    else{
        document.getElementById(all).checked = false;  
    }
   
}