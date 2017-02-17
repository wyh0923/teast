/**
 * Created by qirupeng on 2016/9/1.
 */
$(function(){
    //初始化
    $('#memery >label:first').children('span').addClass('tcur');
    $('#system div.filterList >label:first').children('span').addClass('tcur');
    $('#cpu >label:first').children('span').addClass('tcur');
    $('.fa-edit').click(function(){
        var vmname = $('.vmtplname').val();
        if (vmname != '') {
            $('.vmtplname').attr('disabled',false);
        };

    });
    //type
    $('.tkur').click(function () {
        $('.tkur').attr('class', 'tkur');
        $(this).attr('class', 'tkur tcur');
    });
    //cpu
    $('.ckur').click(function () {
        $('.ckur').attr('class', 'ckur');
        $(this).attr('class', 'ckur tcur');
    });
    //memery
    $('.mkur').click(function () {
        $('.mkur').attr('class', 'mkur');
        $(this).attr('class', 'mkur tcur');
    });
    //system
    $('.skur').click(function () {
        $('.skur').attr('class', 'skur');
        $(this).attr('class', 'skur tcur');
    });

    //上传插件
    var upadd = $('#uploadVmBox').Huploadify({
        auto: true,//当选择文件后就直接上传了
        fileTypeExts: '*.qcow2;',//上传文件类型
        multi: false, //上传多个文件
        fileSizeLimit: 999999999999,
        breakPoints: true,
        saveInfoLocal: true,
        showUploadedPercent: true,//是否实时显示上传的百分比，如20%
        showUploadedSize: true,
        removeTimeout: 1,//上传完成后多久删除队列中的进度条
        fileSplitSize:2048*2048,
        buttonText:'上传虚拟机',
        formData:{key1:'',key2:'vm_',targetDir:targetDir},
        uploader: site_url + 'Train/upload_vm',//服务器端脚本文件路径
        onUploadSuccess: function (messfileObj, info, responseage) {

            var data = JSON.parse(info);
            if(data.success == false){
                $('#errorinfo').html('上传虚拟机失败');
            }
            $('#vmTplFileName').val(data.filename);
            $('.file_info_show_box').val(data.filename);
        },
        onUploadStart: function (file) {//上传开始时触发（每个文件触发一次）
            var timestamp = Date.parse(new Date());
            upadd.settings("formData", {key1:timestamp,key2:'vm_',targetDir:targetDir});
            //$("#sceneres").css("border", "none");
            //$(".uploadify-queue").find(".delfilebtn").hide();
        }
    });

    //方法
    var disclick = false;
    $('#savequestion').click(function () {
        var vmTplName = ($("#vmTplFileName").val().split("."))[0];
        var vmTplShowName = $('#vmTplName').val();
        var vmTplUserName = $('#vmTplUserName').val();
        var vmTplPassword = $('#vmTplPassword').val();
        var vmTplSnapName = $('#vmTplSnapName').val();
        var vmTplCpu = $(".ckur.tcur").attr('value');
        var vmTplMemory = $(".mkur.tcur").attr('value');
        var vmTplType = $(".tkur.tcur").attr('value');
        var vmTplOs = $(".skur.tcur").attr('value');
       // var vmTplFc = $(".fcur").attr('value');
        var vmTplLeak = $("#vmTplLeak").val();
        var vmTplFileName = $("#vmTplFileName").val();
        var docker_cmd = $("#docker_cmd").val();


        if (vmTplShowName == ''){
            $('#errorinfo').html('模板名称不能为空');
            return false;
        } else if(vmTplShowName.length<2 || vmTplShowName.length>255){
            $('#errorinfo').html('模板名称由2-255位字符组成');
            return false;
        }
        if (vmTplUserName != '' && vmTplUserName.length>16){
            $('#errorinfo').html('登录账号不能超过16个字符');
            return false;
        }
        if (vmTplPassword != '' && vmTplPassword.length>32){
            $('#errorinfo').html('登录密码不能超过32个字符');
            return false;
        }
        if (vmTplSnapName != '' && vmTplSnapName.length>32){
            $('#errorinfo').html('快照名称不能超过32个字符');
            return false;
        }

        if (vmTplLeak == "") {
            $('#errorinfo').html('漏洞信息不能为空');
            return false;
        }else if(vmTplLeak.length>2048){
            $('#errorinfo').html('漏洞信息不能超过2048个字符');
            return false;
        }

        var reg =  /^(172)\.(16)\.(1[0-9]|1)\.(\d{1,3})$/;
        var arrIp = docker_cmd.split('.',4);
        if(docker_cmd != '' && !reg.test(docker_cmd) ){
            $('#errorinfo').html('IP填写不正确,例172.16.1[0-9].[0-254]');
            return false;
        }else if(arrIp[3] > 254){
            $('#errorinfo').html('IP填写不正确,例172.16.1[0-9].[0-254]');
            return false;
        }else{
            $('#errorinfo').html('');
        }

        if (vmTplFileName == "") {
            $('#errorinfo').html('请先上传一个qcow2文件');
            return false;
        }

        if(disclick){
            return ;
        }else{
            disclick = true;
        }
        $.ajax({
            url: site_url + "Train/create_vm",
            type: 'post',
            data: {
                'VmTemplateName': vmTplName,
                'VmTemplateShowName': vmTplShowName,
                'VmTemplateUserName': vmTplUserName,
                'VmTemplatePassword': vmTplPassword,
                'VmTemplateType': vmTplType,
                'VmTemplateCpu': vmTplCpu,
                'VmTemplateMemory': vmTplMemory,
                'VmTemplateOs': vmTplOs,
                'VmTemplateSnapName': vmTplSnapName,
                'VmTemplateLeak': vmTplLeak,
                'VmTemplateFileName': '/vm/' + vmTplFileName,
                'NodeId' : nodeId,
                'VmTemplateDocker_cmd' :docker_cmd
            },
            dataType: 'json',
            success: function (message) {
                if(message.code == '0000'){
                    $('#errorinfo').html('新增成功！');
                    setTimeout(function(){
                        location.href = site_url + 'Train/vmlist';
                    },2000);
                }else{
                    $('#errorinfo').html(message.msg);
                    disclick= false;
                }


            }
        })
    });

});
