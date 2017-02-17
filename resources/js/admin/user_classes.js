/**
 * Created by qirupeng on 2016/8/23.
 */
//多选删除确定框 
var arrTeacher = new Array();
function delAllTeacher() {
        arrTeacher = [];//每次统计被选中的教师之前清空防止重复
        $("input[name='checkTeacher']").each(function(){
            if(this.checked == true){
                var code=$(this).attr("data-code");
                arrTeacher.push(code);
            }
        });
    if(arrTeacher.length>0){
        fnShow("#delAll","fadeOutUp","fadeInDown");
    }
    else{
            $('#okBox p.promptNews').html('请选中要删除的班级');
            fnShow("#okBox","fadeOutUp","fadeInDown");
            setTimeout(function(){
                fnHide("#okBox","fadeInDown","fadeOutUp");
            },2000)
        }
}
$(function(){
    $(".fa-search").click(function(){
        var search = $.trim($(".iptSearch-a").val());
        var str = '';
        if (time != '')str += '/time/'+ time;
        window.location.href= site_url + '/User/classes' +str+ "/search/"+encodeURI(translate(search));
    });
    $('.iptSearch-a').keydown(function(e){
        if(e.keyCode==13){
            var search = $.trim($(".iptSearch-a").val());
            var str = '';
            if (time != '')str += '/time/'+ time;
            window.location.href= site_url + '/User/classes' +str+ "/search/"+encodeURI(translate(search));
        }
    });
    //全选
    $("#checkAll").click(function(){
        if(this.checked){
            $("input[name='checkTeacher']").each(function(){
                this.checked=true;
            });

        }else{
            $("input[name='checkTeacher']").each(function(){
                this.checked=false;
            });

        }
    });
    //单记录删除
    $(".forRed").click(function(){
        var code=$(this).attr('code');
        $('#okBtn').attr('code',code);
        fnShow("#one_del","fadeOutUp","fadeInDown");
    });
    //确定删除单记录
    $('#okBtn').click(function(){
        var usercode = $(this).attr('code');
        //fnHide("#one_del","fadeInDown","fadeOutUp");
        $.ajax({
            url:site_url+"/User/del_classes",
            type:'post',
            data:{'classcode':usercode},
            dataType:'json',
            success:function(message){
                $('#one_del').hide();
                if(message.code=='0000'){

                    fnShow("#okBox","fadeOutUp","fadeInDown");
                    $('#okBox p.promptNews').html('删除成功');
                    setTimeout(function () {
                        window.location = site_url+"/User/classes";
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
            //fnHide("#delAll","fadeInDown","fadeOutUp");
            $.ajax({
                url:site_url+"/User/del_classes",
                type:'post',
                data:{'classcode':JSON.stringify(arrTeacher)},
                dataType:'json',
                success:function(message){
                    $('#delAll').hide();
                    if(message.code=='0000'){

                        fnShow("#okBox","fadeOutUp","fadeInDown");
                        $('#okBox p.promptNews').html('删除成功');
                        setTimeout("location.reload()",2000);
                    }else{

                        $('#okBox p.promptNews').html('删除失败');
                        fnShow("#okBox","fadeOutUp","fadeInDown");
                        setTimeout("location.reload()",2000);
                    }
                }

            })
       
    });
    //排序
    $('#CreateTime,#StudentNum,#TaskNum,#TaskScore').click(function(){
        var field = $(this).attr("id");
        var code = $(this).attr('code');
        var str = '';
        if (time != '')str += '/time/'+ time;
        if (search != '')str += '/search/'+encodeURI(translate(search));
        if(code == 'DESC'){
            location.href = site_url+'/User/classes' + str + '/sort/'+field+' ASC';
        }else if(code == 'ASC'){
            location.href = site_url+'/User/classes' + str + '/sort/'+field+' DESC';
        }else{
            location.href = site_url+'/User/classes' + str + '/sort/'+field+' DESC';
        }
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
            if (search != '')str += '/search/'+encodeURI(translate(search));
            window.location.href =  site_url+'/User/classes' + str +'/time/'+ $("#stime").val() + "_" + $("#etime").val()
        }
    }

}
function clearTime() {
    if ($("#stime").val() == "" && $("#etime").val() == "") {
        var str = '';
        if (search != '')str += '/search/' + encodeURI(translate(search));
        window.location.href = site_url + '/User/classes' + str;
    }
}