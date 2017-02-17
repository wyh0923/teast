/**
 * Created by liuqi on 2016/8/23.
 */



//多选删除确定框
function delAllTeacher() {
    if(arrstudent.length > 0){
        $.ajax({
            url:site_url+"Classstaff/isstudys",
            type:'post',
            data:{'codes':JSON.stringify(arrstudent)},
            dataType:'json',
            success:function(message){
                if(message.code=='0000'){
                    fnShow("#okBox","fadeOutUp","fadeInDown");
                    $('#okBox p.promptNews').html('学员有未完成任务，不可删除');
                    setTimeout(function () {
                        fnHide("#okBox","fadeInDown","fadeOutUp");
                    },2000);
                }else{
                    fnShow("#delAll","fadeOutUp","fadeInDown");

                }
            }

        })
    }else{
        fnHide("#delAll","fadeInDown","fadeOutUp",1);
        $('#okBox p.promptNews').html('请选中要删除的学员');
        fnShow("#okBox","fadeOutUp","fadeInDown");
        setTimeout(function(){
            fnHide("#okBox","fadeInDown","fadeOutUp");
        },2000)
    }
    
}

$(function () {
    $(".csear").click(function(){
        var search = translate($.trim($(".esar").val()));
        var str = '';
        if (time != '')str += '/time/'+ time;
        window.location.href= site_url + 'Classstaff/allstudents' +str+ "/search/"+encodeURI(search);
    });
    $('.esar').keydown(function(e){
        if(e.keyCode==13){
            var search = translate($.trim($(".esar").val()));
            var str = '';
            if (time != '')str += '/time/'+ time;
            window.location.href= site_url + 'Classstaff/allstudents' +str+ "/search/"+encodeURI(search);
        }
    });

    //排序
    $('#CreateTime,#UserSex,#UserDepartment').click(function(){
        var field = $(this).attr("id");
        var code = $(this).attr('code');
        var str = '';
        if (time != '')str += '/time/'+ time;
        if (search != '')str += '/search/'+translate(search);
        if(code == 'DESC'){
            location.href = site_url+'Classstaff/allstudents' + str + '/sort/'+field+' ASC';
        }else if(code == 'ASC'){
            location.href = site_url+'Classstaff/allstudents' + str + '/sort/'+field+' DESC';
        }else{
            location.href = site_url+'Classstaff/allstudents' + str + '/sort/'+field+' DESC';
        }
    });

    //全选
    $("#checkAll").click(function(){
         arrstudent = [];//统计之前清空防止重复
        if(this.checked){
            $("input[name='checkTeacher']").each(function(){
                this.checked=true;
                var code=$(this).attr("data-code");
                arrstudent.push(code);
            });

        }else{
            $("input[name='checkTeacher']").each(function(){
                this.checked=false;
            });

        }
        
    });

    //详情
    $(".forYellow").click(function(){
        $('#errorinfo').html('');
        fnShow("#detailinfo","fadeOutUp","fadeInDown");
        var code=$(this).attr('code');
        $.ajax({
            url:site_url+"Classstaff/get_userinfo",
            type:'post',
            data:{'code':code},
            dataType:'json',
            success:function(message){
                if(message.code=='0000'){
                    $('#stu1').val(message.data['StuId']);
                    $('#username1').val(message.data['UserName']);
                    $("#useraccount1").val(message.data['UserAccount']);
                    if(message.data['UserSex']=='男'){
                        $('#woman1').prop('checked',false)
                        $('#man1').prop('checked',true)
                    }else if(message.data['UserSex']=='女'){
                        $('#man1').prop('checked',false)
                        $('#woman1').prop('checked',true)
                    }

                    $("#classname1").val(message.data['ClassName']);
                    $("#classname1").attr('title',message.data['ClassName']);

                    $('#userdepartment1').val(message.data['UserDepartment']);
                    $('#useremail1').val(message.data['UserEmail']);
                    $('#userphone1').val(message.data['UserPhone']);
                }
            },
            error:function(message){
            }

        })
    });

    //编辑
    $(".forBlue").click(function(){
        $('#errorinfo').html('');
        fnShow("#editinfo","fadeOutUp","fadeInDown");
        var code=$(this).attr('code');
        $.ajax({
            url:site_url+"Classstaff/get_userinfo",
            type:'post',
            data:{code:code},
            dataType:'json',
            success:function(message){
                if(message.code=='0000'){
                    $('#usercode').val(code);
                    $('#stuid').val(message.data['StuId']);
                    $('#username').val(message.data['UserName']);
                    $("#useraccount").val(message.data['UserAccount']);
                    if(message.data['UserSex']=='男'){
                        $('#man').attr('checked','checked');
                    }else if(message.data['UserSex']=='女'){
                        $('#woman').attr('checked','checked');
                    }
                    $('#userdepartment').val(message.data['UserDepartment']);
                    $('#useremail').val(message.data['UserEmail']);
                    $('#userphone').val(message.data['UserPhone']);
                }
            }
        })

    });

    //信息修改
    $("#saveedituser").click(function(){
        var stuId = $.trim($('input[name=StuId]').val());
        var UserCode=$('#usercode').val();
        var UserName=$.trim($('#username').val());
        var UserSex=$('input[name=sex]:checked').val();
        var UserDepartment=$('#userdepartment').val();
        var UserEmail=$('#useremail').val();
        var UserPhone=$('#userphone').val();
        var Userpassword=$('#userpassword').val();
        var UserpasswordTwo=$('#userpasswordTwo').val();

        var checkId = /^[a-zA-Z0-9]{1,16}$/;
        if(stuId != '' && !checkId.test(stuId)){
            $('#errorinfo').html('学号由1-16位的字母数字组成');
            return false;
        }
        var reg = /^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/;
        if(!reg.test(UserEmail) && UserEmail != ""){
            $('#errorinfo').html('邮箱格式有误！应如：123456789@163.com');
            return false;
        }
        var telReg = /^[1][3-9][0-9]{9}$/;
        var phoneReg = /^([0-9]{3,4}-)?[0-9]{7,8}$/;
        if(UserPhone != "" && !telReg.test(UserPhone) && !phoneReg.test(UserPhone)){
            $('#errorinfo').html('手机或电话号码格式不正确！');
            return false;
        }
        var nameReg = /^[\u0391-\uFFE5A-Za-z]{2,12}$/;
        if (UserName == ''){
            $('#errorinfo').html('姓名不能为空');
            return;
        } else if(!nameReg.test(UserName)){
            $('#errorinfo').html('姓名由2-12位的中文字母组成');
            return;
        }
        if(Userpassword != ''){
            if(Userpassword.length<6 || Userpassword.length>15){
                $('#Userpassword').html('密码长度应该在6-15位之间');
                return ;
            }
            if (Userpassword !== UserpasswordTwo){
                $('#errorinfo').html('两次输入的密码不一致！请重新输入!');
                return ;
            }
        }
        var departmentReg = /^[\u0391-\uFFE5A-Za-z]+$/;
        if(UserDepartment != '' && !departmentReg.test(UserDepartment)){
            $('#errorinfo').html('工作单位只能是中文字母');
            return;
        }
        $.ajax({
            url:site_url+"Classstaff/edit_user",
            type:'post',
            data:{UserID:UserCode,'StuId':stuId,UserName:UserName,UserSex:UserSex,UserEmail:UserEmail,UserPhone:UserPhone,UserDepartment:UserDepartment,Userpassword:Userpassword},
            dataType:'json',
            success:function(message){
                
                $('#errorinfo').html('保存成功');
                setTimeout("location.reload(true)",2000);
            }
        })
    });

    //单记录删除
    $(".forRed").click(function(){
        var code=$(this).attr('code');
        $.ajax({
            url:site_url+"Classstaff/isstudy",
            type:'post',
            data:{'sid':code},
            dataType:'json',
            success:function(message){
                if(message.code=='0000'){
                    fnShow("#okBox","fadeOutUp","fadeInDown");
                    $('#okBox p.promptNews').html('学员有未完成任务，不可删除');
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
        fnHide("#one_del","fadeInDown","fadeOutUp");
        $.ajax({
            url:site_url+"Classstaff/deluser",
            type:'post',
            data:{'codes':usercode},
            dataType:'json',
            success:function(message){

                if(message.code=='0000'){

                    fnShow("#okBox","fadeOutUp","fadeInDown");
                    $('#okBox p.promptNews').html('删除成功');
                    setTimeout(function () {
                        window.location = site_url+"Classstaff/allstudents";
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
        if(arrstudent.length > 0){
            fnHide("#delAll","fadeInDown","fadeOutUp",1);
            $.ajax({
                url:site_url+"Classstaff/deluser",
                type:'post',
                data:{'codes':JSON.stringify(arrstudent)},
                dataType:'json',
                success:function(message){

                    if(message.code=='0000'){

                        fnShow("#okBox","fadeOutUp","fadeInDown");
                        $('#okBox p.promptNews').html('删除成功');
                        setTimeout("location.reload()",2000);
                        // window.location.href=site_url+'Classstaff/allstudents'

                    }else{

                        $('#okBox p.promptNews').html('删除失败');
                        fnShow("#okBox","fadeOutUp","fadeInDown");
                        setTimeout("location.reload()",2000);
                    }
                }

            })
        }else{
            fnHide("#delAll","fadeInDown","fadeOutUp",1);
            $('#okBox p.promptNews').html('请选中要删除的学员');
            fnShow("#okBox","fadeOutUp","fadeInDown");
            setTimeout(function(){
                fnHide("#okBox","fadeInDown","fadeOutUp");
            },2000)
        }
    })
});

//启用
function enableFun(obj)
{
    var stuid = $(obj).attr('code');
    var cid = $(obj).parent().attr('id');
    $.ajax({
        url:site_url+"Classstaff/en_disable",
        type:'post',
        async: false,
        data:{'stuid':stuid, 'is_lock':0},
        dataType:'json',
        
        success:function(message)
        {   
            if(message.code=='0000')
            {
               
                $(obj).parent().html('启用/<a href="javascript:;" code="'+stuid+'" onclick="disableFun(this)"><span class="btnDisable"> 禁用</span></a>');
               

            }
           
        }
    });
    
}

//禁用
function disableFun(obj)
{
    var stuid = $(obj).attr('code');
    
  
    $.ajax({
        url:site_url+"Classstaff/en_disable",
        type:'post',
        async: false,
        data:{'stuid':stuid, 'is_lock':1},
        dataType:'json',
       
        success:function(message)
        {   
            if(message.code=='0000')
            {
              
                 $(obj).parent().html('<a href="javascript:;" code="'+stuid+'" onclick="enableFun(this)"><span class="btnDisable"> 启用</span></a>/禁用');
            }
            
        }
    });

}


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
            window.location.href =  site_url+'Classstaff/allstudents' + str +'/time/'+ $("#stime").val() + "_" + $("#etime").val()
        }
    }

}
var arrstudent = new Array();//统计页面被选中的学生
function checkThis(isme,all){
   var inputNumbers =  $(isme+" tr input").length;
   arrstudent = [];//统计之前清空防止重复
    $("input[name='checkTeacher']").each(function(){
            if(this.checked == true){
                var code=$(this).attr("data-code");
                arrstudent.push(code);
            }
        });
    if(arrstudent.length==inputNumbers){
      document.getElementById(all).checked = true;
    }
    else{
        document.getElementById(all).checked = false;  
    }
   
}