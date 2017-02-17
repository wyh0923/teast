/**
 * Created by qirupeng on 2016/8/22.
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
            $('#okBox p.promptNews').html('请选中要删除的教员');
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
        window.location.href= site_url + '/User/teacher' +str+ "/search/"+encodeURI(translate(search));
    });
    $('.iptSearch-a').keydown(function(e){
        if(e.keyCode==13){
            var search = $.trim($(".iptSearch-a").val());
            var str = '';
            if (time != '')str += '/time/'+ time;
            window.location.href= site_url + '/User/teacher' +str+ "/search/"+encodeURI(translate(search));
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
    })
    //详情
    $(".forYellow").click(function(){
        $('#errorinfo').html('');
        fnShow("#detailinfo","fadeOutUp","fadeInDown");
        var code=$(this).attr('code');
        $.ajax({
            url:site_url+"/User/get_userinfo",
            type:'post',
            data:{'code':code},
            dataType:'json',
            success:function(message){
                if(message.code=='0000'){
                    //$('#errorinfo1').html(message.msg);
                    $('#username1').val(message.data['UserName']);
                    $("#useraccount1").val(message.data['UserAccount']);
                    if(message.data['UserSex']=='男'){//alert(1)
                        $('#woman1').prop('checked',false)
                        $('#man1').prop('checked',true)
                    }else if(message.data['UserSex']=='女'){//alert(2)
                        $('#man1').prop('checked',false)
                        $('#woman1').prop('checked',true)
                    }
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
            url:site_url+"/User/get_userinfo",
            type:'post',
            data:{code:code},
            dataType:'json',
            success:function(message){
                if(message.code=='0000'){
                    $('#usercode').val(code);
                    //$('#errorinfo').html(message.msg);
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
            url:site_url+"/User/del_teacher",
            type:'post',
            data:{'codes':usercode},
            dataType:'json',
            success:function(message){
                $('#one_del').hide();

                if(message.code=='0000'){

                    fnShow("#okBox","fadeOutUp","fadeInDown");
                    $('#okBox p.promptNews').html('删除成功');
                    setTimeout(function () {
                        window.location = site_url+"/User/teacher";
                    },2000);
                }else{

                    $('#okBox p.promptNews').html(message.msg);
                    fnShow("#okBox","fadeOutUp","fadeInDown");
                    setTimeout("location.reload()",2000);
                }
            }

        })
    });
    //信息修改
    $("#saveedituser").click(function(){
        var UserCode=$('#usercode').val();
        var UserName=$.trim($('#username').val());
        var UserSex=$('input[name=sex]:checked').val();
        var UserDepartment=$('#userdepartment').val();
        var UserEmail=$('#useremail').val();
        var UserPhone=$('#userphone').val();
        var Userpassword=$('#userpassword').val();
        var UserpasswordTwo=$('#userpasswordTwo').val();

        var checkId = /^[a-zA-Z0-9]{1,16}$/;
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
            url:site_url+"/User/edit_user",
            type:'post',
            data:{UserID:UserCode,UserName:UserName,UserSex:UserSex,UserEmail:UserEmail,UserPhone:UserPhone,UserDepartment:UserDepartment,Userpassword:Userpassword},
            dataType:'json',
            success:function(message){

                if(message.code=='0000'){
                    $('#errorinfo').html('编辑成功');
                    setTimeout(function(){fnHide("#editinfo","fadeInDown","fadeOutUp")},2000);
                    setTimeout("location.reload(true)",2000);
                }else{
                    $('#errorinfo').html(message.msg);
                    //console.log(message.msg);
                }
            }
        })
    });

    //多选删除 确定按钮
    $('#delAllTeacherBtn').click(function(){
        
            //fnHide("#delAll","fadeInDown","fadeOutUp");
            $.ajax({
                url:site_url+"/User/del_teacher",
                type:'post',
                data:{'codes':JSON.stringify(arrTeacher)},
                dataType:'json',
                success:function(message){
                    $('#delAll').hide();
                    if(message.code=='0000'){

                        fnShow("#okBox","fadeOutUp","fadeInDown");
                        $('#okBox p.promptNews').html('删除成功');
                        setTimeout("location.reload()",1000);
                    }else{

                        $('#okBox p.promptNews').html(message.msg);
                        fnShow("#okBox","fadeOutUp","fadeInDown");
                        setTimeout("location.reload()",1000);
                    }
                }

            })
        
    });
    //排序
    $('#CreateTime,#UserSex,#UserDepartment').click(function(){
        var field = $(this).attr("id");
        var code = $(this).attr('code');
        var str = '';
        if (time != '')str += '/time/'+ time;
        if (search != '')str += '/search/'+encodeURI(translate(search));
        if(code == 'DESC'){
            location.href = site_url+'/User/teacher' + str + '/sort/'+field+' ASC';
        }else if(code == 'ASC'){
            location.href = site_url+'/User/teacher' + str + '/sort/'+field+' DESC';
        }else{
            location.href = site_url+'/User/teacher' + str + '/sort/'+field+' DESC';
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
            window.location.href =  site_url+'/User/teacher' + str +'/time/'+ $("#stime").val() + "_" + $("#etime").val()
        }
    }

}
function clearTime() {
    if ($("#stime").val() == "" && $("#etime").val() == "") {
        var str = '';
        if (search != '')str += '/search/' + encodeURI(translate(search));
        window.location.href = site_url + '/User/teacher' + str;
    }
}
