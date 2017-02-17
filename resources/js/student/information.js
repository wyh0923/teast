$(function(){
    $('.addinfo').click(function(){
        var userName = $.trim($('#UserName').val());
        var userSex = $('input[name=UserSex]:checked').val();
        var userEmail = $.trim($('#UserEmail').val());
        var userPhone = $.trim($('#UserPhone').val());

        if(userEmail.match(/^\s*$/)){
            userEmail='';
        }else{
            var reg = /^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/;
            if(!reg.test(userEmail)){
                $('#errorinfo').html('邮箱格式有误！应如：123456789@163.com');
                return false;
            }
        }
        if(userPhone.match(/^\s*$/)){
            userPhone='';
        }else{
            var telReg = /^[1][3-9][0-9]{9}$/;
            var phoneReg = /^([0-9]{3,4}-)?[0-9]{7,8}$/;
            if(userPhone != '' && !telReg.test(userPhone) && !phoneReg.test(userPhone)){
                $('#errorinfo').html('手机或电话号码格式不正确！');
                return false;
            }
        }

        var nameReg = /^[\u0391-\uFFE5A-Za-z]{2,12}$/;
        if (userName == ''){
            $('#errorinfo').html('姓名不能为空');
            return;
        } else if(!nameReg.test(userName)){
            $('#errorinfo').html('姓名由2-12位的中文字母组成');
            return;
        }

        if(userSex !== '男' && userSex !== '女'){
            $('#errorinfo').html('性别只能有一个男字或者女字组成');
            return false;
        }
        $.ajax({
            url:site_url+'/Personal/updateinfor',
            type:'post',
            data:{UserName:userName,UserSex:userSex,UserEmail:userEmail,UserPhone:userPhone},
            dataType:'json',
            success:function(message){
                if(message.code == '0000'){
                    $('#okBox .infoBox p').html(message.msg);
                    fnShow("#okBox","fadeOutUp","fadeInDown");
                    setTimeout(function(){
                        fnHide("#okBox","fadeInDown","fadeOutUp");
                        window.location.reload();
                    },2000)
                }else{
                    $("#errorinfo").html(message.msg);
                }
            }
        })
    });

    //清除
    $('.reset').click(function(){
        $('#UserName').val('');
        $('#UserEmail').val('');
        $('#UserPhone').val('');
    });

    //更换头像
    var uploadFiles =  function(){
        var uploader = new plupload.Uploader(
            {
                browse_button: 'browse',
                url: site_url+'/Personal/avatar',
                runtimes: 'html5',
                chunk_size: '1mb',
                unique_names: true,
                auto: true
            }
        );
        uploader.init();
        uploader.bind('FilesAdded', function(up, files) {
            file = files[0];
            if( checkext(file) ){
                uploader.settings.multipart_params = {
                    filename : file.name
                };
                var checkOnce = true;
                if(checkOnce){
                    checkOnce = false;
                    $('#errorinfo').hide();
                    uploader.start();
                }
            }else {
                this.splice(0,this.files.length);
                return false;
            }
        });

        uploader.bind('UploadProgress', function(up, file) {
            $('#errorinfo').removeClass('redinfo').addClass('greeninfo').show().html(file.percent + '%');
            if(file.percent == 100){
                $('#errorinfo').removeClass('redinfo').addClass('greeninfo').show().html('正在检测文件,请稍等..');
            }
        });
        uploader.bind('FileUploaded', function(up, file,res) {
            ret = JSON.parse(res.response);
            if(ret['code'] != '0000') {
                showerrmsgforadd('上传失败');
                return false;
            }
            if(ret['code']=='0000'){
                $('#errorinfo').html('');
                setTimeout("location.reload(true)",500);

            }
        });
        uploader.bind('QueueChanged', function(up) {
            if (uploader.files.length > 1) {
                uploader.splice(1, 1);
            }
        });
        uploader.bind('Error', function(up, err) {
            showerrmsgforadd("\nError #" + err.code);
            return false;
        });
    };

    //判断文件类型
    function checkext(file){
        var fileinfo = file.name.split(".");
        if(!(fileinfo.length > 1)){
            showerrmsgforadd("文件类型不被允许");
            return false;
        }
        var ext = fileinfo[fileinfo.length-1].toLowerCase();
        if(ext !='png' && ext != 'jpg' && ext != 'gif'){
            showerrmsgforadd('文件格式为png,jpg,gif,jpeg');
            return false;
        }else if(file.size > 1024*1024){
            showerrmsgforadd('文件大小不得超过1024*1024');
            return false;
        }else{
            $('#errorinfo').hide();
            return true;
        }
    }
    uploadFiles();

    function showerrmsgforadd(obj){
        $('#errorinfo').html(obj);
        $('#errorinfo').css('display','block');
    }
});
