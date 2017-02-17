$(function(){
    $('#saveBtn').click(function(){
        var nowpass = $('input[name="nowpassword"]').val().trim();
        var newpassone = $('input[name="newpasswordnoe"]').val().trim();
        var newpasstwo = $('input[name="newpasswordtwo"]').val().trim();
        var reg = /^[\\~!@#$%^&*()-_=+|{},.?\/:;\'\"\d\w]{6,16}$/;
        
        if(nowpass == ''){
            $('#errorinfo').html('当前密码不能为空');
            return false;
        }
        if(newpassone== ''){
            $('#errorinfo').html('新密码不能为空');
            return false;
        }else if(!reg.test(newpassone)){
            $('#errorinfo').html('新密码必须是6到16位的字符!');
            return false;
        }
        if(nowpass == newpassone){
            $('#errorinfo').html('新密码与当前密码不能相同!');
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
        $.ajax({
            url:site_url+'/Personal/updatepassword',
            type:'post',
            data:{nowpass:nowpass,newpassone:newpassone,newpasstwo:newpasstwo},
            dataType:'json',
            success:function(message){
                if(message.code == '0000'){
                    $('#errorinfo').html('');
                    $('#okBox .infoBox p').html('密码修改成功!');
                    fnShow("#okBox","fadeOutUp","fadeInDown");
                    setTimeout(function(){
                        window.location.href=site_url + 'Login/logout';
                    },2000)
                }else{
                    $('#okBox .infoBox p').html(message.msg);
                    fnShow("#okBox","fadeOutUp","fadeInDown");
                    setTimeout(function(){
                        fnHide("#okBox","fadeInDown","fadeOutUp");
                    },2000)
                }
            }
        })
    });
    //清除点击事件
    $('#clearBtn').click(function(){
        $('input[name="nowpassword"]').val('');
        $('input[name="newpasswordnoe"]').val('');
        $('input[name="newpasswordtwo"]').val('');
    })
});