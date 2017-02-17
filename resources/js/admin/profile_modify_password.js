/**
 * Created by qirupeng on 2016/8/25.
 */
$(function(){
    $('#saveBtn').click(function(){
        var nowpass = $('input[name="nowpassword"]').val().trim();
        var newpassone = $('input[name="newpasswordnoe"]').val().trim();
        var newpasstwo = $('input[name="newpasswordtwo"]').val().trim();

        if(nowpass == ''){
            $('#errorinfo').html('当前密码不能为空');
            return false;
        }
        if(newpassone== ''){
            $('#errorinfo').html('新密码不能为空');
            return false;
        }
        if(newpasstwo == ''){
            $('#errorinfo').html('确认密码不能为空');
            return false;
        }
        if (newpassone !== newpasstwo){
            $('#errorinfo').html('两次输入的密码不一致！请重新输入!');
            return false;
        }
        if(nowpass == newpassone){
            $('#errorinfo').html('新密码与旧密码不能相同!');
            return false;
        }
        if(newpasstwo.length<6 || newpasstwo.length>16){
            $('#errorinfo').html('密码要为6位字符以上16位字符以下组成!');
            return false;
        }

        re = /^[\\~!@#$%^&*()-_=+|{},.?\/:;\'\"\d\w]+$/;
        if(!re.test(nowpass)){
            $('#errorinfo').html('密码格式错误，仅支持字母、数字下划线组合');
            return false;
        }
        if(!re.test(newpassone)){
            $('#errorinfo').html('密码格式错误，仅支持字母、数字下划线组合');
            return false;
        }
        if(!re.test(newpasstwo)){
            $('#errorinfo').html('密码格式错误，仅支持字母、数字下划线组合');
            return false;
        }

        $.ajax({
            url:site_url+'/Profile/modifypassword',
            type:'post',
            data:{oldpass:nowpass,newpass:newpasstwo},
            dataType:'json',
            success:function(message){
                if(message.code=='0000'){
                    fnShow("#okBox","fadeOutUp","fadeInDown");
                    $(".promptNews").html("密码修改成功");
                    setTimeout(function(){
                        window.location.href= site_url + '/Login/logout';
                    },2000)
                }else{
                    fnShow("#okBox","fadeOutUp","fadeInDown");
                    $(".promptNews").html(message.msg);
                    setTimeout(function(){
                        window.location.reload();
                    },2000)
                }
            },
            error:function(message){
                console.log(message);
            }
        })
    })
    // clear info
    $('#delBtn').click(function(){
        $('input[name="nowpassword"]').val('');
        $('input[name="newpasswordnoe"]').val('');
        $('input[name="newpasswordtwo"]').val('');
    })
})