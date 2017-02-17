/**
 * Created by qirupeng on 2016/8/25.
 */
var quescontents = new Array();
var number = 0;
var totalcheck = new Array();
function checkeds(ppo){
    var code = $(ppo).attr('value');
    var UserName = $(ppo).attr('UserName');
    var UserSex = $(ppo).attr('UserSex');
    var UserDepartment = $(ppo).attr('UserDepartment');
    var uclass = $(ppo).attr('uclass');
    var UserPoint = $(ppo).attr('UserPoint');
    var tt = code+'@@@@'+UserName+'@@@@'+UserSex+'@@@@'+UserDepartment+'@@@@'+uclass+'@@@@'+UserPoint;


    if ($(ppo).is(':checked')){

        if(jQuery.inArray(code,totalcheck) == -1){
            totalcheck.push(code);
        }

        quescontents.push(tt);
        var obj = $('#ques input[class=quescode]');
        number++;
        $('#addclassuserBox .titleLook span').html(number);
        $('#ques input[class=quescode]').each(function(){
            if ($(this).val() == code){
                $(this).prop('checked',true)
            }
        })
    }else{
        number--;
        $('#addclassuserBox .titleLook span').html(number);
        $('#ques input[class=quescode]').each(function(){
            if ($(this).val() == code){
                $(this).prop('checked',false)
            }
        })
        quescontents.splice($.inArray(tt,quescontents),1)

        $.each(totalcheck,function(n,m){
            if(m == code){
                totalcheck.splice($.inArray(code,totalcheck),1);
            }
        })
    }
}
function sapSuc(data) {
    var questxt = '';
    $.each(data,function(i,v){
        questxt += '<tr>';
        questxt += '<td class="fuck"><input class="quescode" type="checkbox" onclick=checkeds(this) name="quescode[]" UserName="'+v['UserName']+'" UserSex="'+v['UserSex']+'" UserDepartment="'+v['UserDepartment']+'" uclass="'+v['class']+'" UserPoint="'+v['UserPoint']+'"  StuId="'+v['StuId']+'"  value="'+v['UserID']+'"></td>';
        questxt += '<td class="fuck">'+v['UserName']+'</td>';
        questxt += '<td class="fuck">'+v['UserSex']+'</td>';
        questxt += '<td title="'+v['UserDepartment']+'">'+v['UserDepartment']+'</td>';
        questxt += '<td title="'+v['class']+'">'+v['class']+'</td>';
        questxt += '<td>'+v['UserPoint']+'</td>';
        questxt += '</tr>';
    });
    $('#ques').html('');
    $('#ques').append(questxt);

    $('#ques input[class=quescode]').each(function(){
        if(jQuery.inArray($(this).val(),totalcheck) != -1){
            $(this).prop('checked',true)
        }
    })
}


$(function() {
    //提交 添加学员
    $("#okChecked").click(function(){

        if(totalcheck.length == 0){
            $('#okBox p.promptNews').html('请选择学员');
            fnShow("#okBox","fadeOutUp","fadeInDown");
            setTimeout(function(){
                fnHide("#okBox","fadeInDown","fadeOutUp");
            },2000);

        } else {
            $.ajax({
                url: site_url + '/User/edit_class_user',
                data: {'usercode': totalcheck, 'classcode': classcode},
                type: 'post',
                dataType: 'json',
                success: function(msg) {
                    if (msg.code == '0000') {
                        //fnHide("#addclassuserBox","fadeInDown","fadeOutUp");
                        $('#addclassuserBox').hide();
                        $('#okBox p.promptNews').html('班级新增学员成功');
                        fnShow("#okBox","fadeOutUp","fadeInDown");
                        setTimeout(function(){
                            //fnHide("#okBox","fadeInDown","fadeOutUp");
                            window.location.href=site_url+'/User/editclass/classid/'+classcode;
                        },2000);

                    }
                },
                error: function(msg) {
                    //fnHide("#addclassuserBox","fadeInDown","fadeOutUp");
                    $('#addclassuserBox').hide();
                    $('#okBox p.promptNews').html('操作失败!');
                    fnShow("#okBox","fadeOutUp","fadeInDown");
                    setTimeout(function(){
                        //fnHide("#okBox","fadeInDown","fadeOutUp");
                        window.location.href=site_url+'/User/editclass/classid/'+classcode;
                    },2000);
                }
            })
        }

    });
    //添加
    $("#addBtn").click(function(){
        //初始化
        $('#sapSearch_pageContainer').val('');
       
        $('#addclassuserBox .titleLook span').html(totalcheck.length);

        sapGetData(site_url+'/User/all_user_add?classcode='+classcode, sapSuc, "pageContainer");
        fnShow("#addclassuserBox","fadeOutUp","fadeInDown");
    });
    //点击取消选择学员弹框
    $(".closeSectStu").click(function(){
        totalcheck = [];
        quescontents = [];
        number = 0;
        fnHide("#addclassuserBox","fadeInDown","fadeOutUp")
    })
    //搜索
    $(".addseach").click(function(){
        sapGetData(site_url+'/User/all_user_add?classcode='+classcode, sapSuc, "pageContainer");
    });
    $('.addqueexam').keydown(function(e){
        if(e.keyCode==13){
            sapGetData(site_url+'/User/all_user_add?classcode='+classcode, sapSuc, "pageContainer");
        }
    });





    $("#myarchlistTable").find(".forRed").on({
        click: function() {
            var code = $(this).attr('code');
            $('.okBtn').attr('code', code);
            fnShow("#myarchlistPopBox", "fadeOutUp", "fadeInDown");
        }
    });


    $('.okBtn').click(function() {
        var usercode = $(this).attr('code');
        $.ajax({
            url: site_url + "/User/del_class_user",
            type: 'post',
            data: {'classcode': classcode, 'usercode': usercode},
            dataType: 'json',
            success: function(message) {
                if (message.code == '0000') {
                    fnHide("#myarchlistPopBox", "fadeInDown", "fadeOutUp");
                    setTimeout("location.reload()", 500);
                }
            }

        })
    });

    //搜索 开始
    $('.seachar').click(function() {
        var name = $.trim($('#ClassName').val());
        location.href = site_url +'/User/editclass' + "/classid/"+classcode+ "/search/"+encodeURI(translate(name));
    });
    $('.iptSearch-a').keydown(function(e){
        if(e.keyCode==13){

            var name = $.trim($(".iptSearch-a").val());
            location.href = site_url +'/User/editclass' + "/classid/"+classcode+ "/search/"+encodeURI(translate(name));
        }
    });

    //搜索  结束

});



//编辑班级名称


$("#editBtn").click(function() {
    var $text = $("#code").text();
    $("#editIpt").val($text).show();
    $("#code").hide();
    $("#editIpt").show();
    $(this).hide();
    $("#ok").show();
});



$("#ok").click(function() {
    var classname = $("#editIpt").val().trim();
    var classcode = $("#editIpt").attr('code');
    var OldClassName = $("#OldClassName").val();

    if(classname == ''){
        $('#okBox p.promptNews').html('班级名称不能为空');
        fnShow("#okBox","fadeOutUp","fadeInDown");
        setTimeout(function(){
            fnHide("#okBox","fadeInDown","fadeOutUp");
        },2000);
        return ;
    } else if (classname.length<3 || classname.length>16){
        $('#okBox p.promptNews').html('班级的名称应该为3-16位字符');
        fnShow("#okBox","fadeOutUp","fadeInDown");
        setTimeout(function(){
            fnHide("#okBox","fadeInDown","fadeOutUp");
        },2000);
        return;
    }

    $("#code").show().html(classname);
    $("#editIpt").hide();
    $.ajax({
        url: site_url + '/User/edit_class_name',
        data: {'classname': classname, 'OldClassName':OldClassName , 'classcode': classcode},
        type: 'post',
        dataType: 'json',
        success: function(msg) {
            if (msg.code == '0000') {
                $('#okBox p.promptNews').html('修改成功');
                fnShow("#okBox","fadeOutUp","fadeInDown");
                setTimeout(function(){
                    fnHide("#okBox","fadeInDown","fadeOutUp");
                },2000);
            }else{
                $("#code").html(OldClassName);
                $('#okBox p.promptNews').html('该班级名已存在');
                fnShow("#okBox","fadeOutUp","fadeInDown");
                setTimeout(function(){
                    fnHide("#okBox","fadeInDown","fadeOutUp");
                },2000);
            }
            $("#ok").hide();
            $("#editBtn").show();
        },
        error: function(msg) {
            console.log('error');
        }
    })
});

/*点击删除弹出框   end*/
