/**
 * Created by liuqi on 2016/8/22.
 */

$(function () {
    $('#submit').click(function () {
            
            var name = $.trim($('input[name=UserName]').val());
            var sex = $('input[name=UserSex]:checked').val();
            var email = $.trim($('input[name=UserEmail]').val());
            var phone = $.trim($('input[name=UserPhone]').val());
            var nameReg = /^[\u0391-\uFFE5A-Za-z]{2,12}$/;
            if (name == ''){
                $('#errorinfo').html('姓名不能为空');
                return false;
            } else if(!nameReg.test(name)){
                $('#errorinfo').html('姓名由2-12位的中文或字母组成');
                return false;
            }

            var reg = /^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/;
            if(email.match(/^\s*$/)){
                email='';
            }else{
                if(email.match(/^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/)==null){
                $('#errorinfo').html('邮箱格式有误！应如：123456789@163.com');
                return false;
                }
            }

            if(phone.match(/^\s*$/)){
                phone='';
            }else{
                var telReg = /^[1][3-9][0-9]{9}$/;
                var phoneReg = /^([0-9]{3,4}-)?[0-9]{7,8}$/;
                if(phone != '' && !telReg.test(phone) && !phoneReg.test(phone)){
                    $('#errorinfo').html('手机或电话号码格式不正确！');
                    return false;
                }
            }
            
            
            var data = {'UserName': name, 'UserEmail': email, 'UserPhone': phone, 'UserSex': sex};
            // console.log(data);return;
             $('#errorinfo').html('');
            $.ajax({
                url: site_url + 'Teacount/personaldetails',
                type: 'post',
                data: data,
                dataType: 'json',
                success: function (msg) {
                    if(msg.code=='0000'){
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
                },

            })
        }
    )
    //清除页面用户信息
    $("#removeInfos").click(function(){
        $("#UserName").val("");
        $("#UserEmail").val("");
        $("#UserPhone").val("");
    })

    $('#delBtn').click(function(){
        $('input[name="oldpass"]').val('');
        $('input[name="newpass"]').val('');
        $('input[name="repass"]').val('');
    })
    $('#modpass').click( function () {
        var oldpass = $.trim($('input[name=oldpass]').val());
        var newpass = $.trim($('input[name=newpass]').val());
        var renewpass = $.trim($('input[name=repass]').val());

        if(newpass != renewpass)
        {
            fnShow("#okBox","fadeOutUp","fadeInDown");
            $(".promptNews").html("两次密码不一致");
            return false;
        }

        var data = {'oldpass':oldpass,  'newpass':newpass, 'repeat_newpass':renewpass};
        //console.log(data);return;

        $.ajax({
                url: site_url + 'Teacount/modifypassword',
                type: 'post',
                data: data,
                dataType: 'json',
                success: function (msg) {
                    if(msg.code=='0000'){
                        fnShow("#okBox","fadeOutUp","fadeInDown");
                        $(".promptNews").html("密码修改成功");
                        setTimeout(function(){
                            window.location.href = site_url + 'Login/logout';
                        },2000)
                    }else{
                        fnShow("#okBox","fadeOutUp","fadeInDown");
                        $(".promptNews").html(msg.msg);

                    }
                },
                error: function (msg) {
                    console.log('error');
                }
            })
        }
    )

    /*
     * upload userlcon
     */
    var uploadFiles =  function(){
        var uploader = new plupload.Uploader(
            {
                browse_button: 'browse',
                url: site_url+'Teacount/avatar',
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
        if(ext !='png' && ext != 'jpg' && ext != 'gif'){
            showerrmsgforadd('文件格式为png,jpg,gif');
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

