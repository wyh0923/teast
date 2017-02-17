/**
 * Created by qirupeng on 2016/8/25.
 */
$(function(){
    $('.addinfo').click(function(){
        var UserName = $.trim($('#UserName').val());
        var UserSex = $.trim($('input[name=UserSex]:checked').val());
        var UserEmail = $('#UserEmail').val();
        var UserPhone = $('#UserPhone').val();
        if(UserEmail.match(/^\s*$/)){
            UserEmail='';
        }else{
            var reg = /^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/;
            if(!reg.test(UserEmail)){
                $('#errorinfo').html('邮箱格式有误！应如：123456789@163.com');
                $('#errorinfo').css('display','block');
                return false;
            }
        }
        if(UserPhone.match(/^\s*$/)){
            UserPhone='';
        }else{
            var telReg = /^[1][3-9][0-9]{9}$/;
            var phoneReg = /^([0-9]{3,4}-)?[0-9]{7,8}$/;
            if(UserPhone != '' && !telReg.test(UserPhone) && !phoneReg.test(UserPhone)){
                $('#errorinfo').html('手机或电话号码格式不正确！');
                $('#errorinfo').css('display','block');
                return false;
            }
        }

        var nameReg = /^[\u0391-\uFFE5A-Za-z]{2,12}$/;
        if (UserName == ''){
            $('#errorinfo').html('姓名不能为空');
            $('#errorinfo').css('display','block');
            return;
        } else if(!nameReg.test(UserName)){
            $('#errorinfo').html('姓名由2-12位的中文字母组成');
            $('#errorinfo').css('display','block');
            return;
        }

        if(UserSex !== '男' && UserSex !== '女'){
            $('#errorinfo').html('性别只能有一个男字或者女字组成');
            $('#errorinfo').css('display','block');
            return false;
        }

        $.ajax({
            url:site_url+'/Profile/info',
            type:'post',
            data:{UserName:UserName,UserSex:UserSex,UserEmail:UserEmail,UserPhone:UserPhone},
            dataType:'json',
            success:function(message){
                if(message.code=='0000'){
                    fnShow("#okBox","fadeOutUp","fadeInDown");
                    $(".promptNews").html("信息修改成功");
                    setTimeout(function(){
                        fnHide("#okBox","fadeInDown","fadeOutUp");
                        window.location.reload();
                    },2000)
                }else{
                    fnShow("#okBox","fadeOutUp","fadeInDown");
                    $(".promptNews").html("未更新信息");
                    setTimeout(function(){
                        fnHide("#okBox","fadeInDown","fadeOutUp");
                        window.location.reload();
                    },2000)
                }
            }
        })
    });

    $('.reset').click(function(){
        $('#UserName').val('');
        $('#UserSex').val('');
        $('#UserEmail').val('');
        $('#UserPhone').val('');
    });


    /*
     * upload userlcon
     */
    var uploadFiles =  function(){
        var uploader = new plupload.Uploader(
            {
                browse_button: 'browse',
                url: site_url+'/Profile/avatar',
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
    }

    //判断文件类型
    function checkext(file){
        var fileinfo = file.name.split(".");
        if(!(fileinfo.length > 1)){
            showerrmsgforadd("文件类型不被允许");
            return false;
        }
        var ext = fileinfo[fileinfo.length-1].toLowerCase();
        if(ext !='png' && ext != 'jpg' && ext != 'gif' && ext != 'jpeg'){
            showerrmsgforadd('文件格式为png,jpg,gif,jpeg');
            return false;
        }else if(file.size > 1024*1024){
            showerrmsgforadd('文件大小不得超过1024*1024');
            return false;
        }else{
            $('#adderrormsg').hide();
            return true;
        }
    }
    uploadFiles();
    function showerrmsgforadd(obj){
        $('#errorinfo').html(obj);
        $('#errorinfo').css('display','block');
    }
});