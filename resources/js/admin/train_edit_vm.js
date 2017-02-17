/**
 * Created by qirupeng on 2016/9/1.
 */
$(function(){

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


    //方法
    var disclick = false;
    $('#savequestion').click(function () {
        var vmTplName = $('#vmTplName').val();
        var vmTplUserName = $('#vmTplUserName').val();
        var vmTplPassword = $('#vmTplPassword').val();
        var vmTplSnapName = $('#vmTplSnapName').val();
        var vmTplCpu = $(".ckur.tcur").attr('value');
        var vmTplMemory = $(".mkur.tcur").attr('value');
        //var vmTplType = $(".tkur.tcur").attr('value');
        var vmTplDisk = $('#vmTplDisk').val();
        var vmTplOs = $(".skur.tcur").attr('value');
        var vmTplLeak = $("#vmTplLeak").val();
        var vmTplFileName = $("#vmTplFileName").val();
        var docker_cmd = $("#docker_cmd").val();


        if (vmTplName == "") {
            $('#errorinfo').html('模板名称不能为空');
            return false;
        }else if(vmTplName.length<2 || vmTplName.length>255){
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

        if(disclick){
            return ;
        }else{
            disclick = true;
        }
        $.ajax({
            url: site_url+ "/Admintrain/edit_vm",
            type: 'post',
            data: {
                'vm_tpl_uuid': vm_tpl_uuid,
                'host_id': host_id,
                'VmTemplateName': vmTplName,
                'VmTemplateUserName': vmTplUserName,
                'VmTemplatePassword': vmTplPassword,
                'VmTemplateSnapName': vmTplSnapName,
                'VmTemplateCpu': vmTplCpu,
                'VmTemplateMemory': vmTplMemory,
                'VmTemplateDisk':vmTplDisk,
                'VmTemplateOs': vmTplOs,
                'VmTemplateLeak': vmTplLeak,
                'VmTemplateFileName': vmTplFileName,
                'VmTemplateDocker_cmd': docker_cmd
            },
            dataType: 'json',
            success: function (message) {

                $('#errorinfo').html(message.msg);
                if(message.code=='0000'){
                    setTimeout(function(){
                        location.href = site_url + '/Admintrain/vmlist';
                    },1000);
                }

            }
        })
    });
    $('#back').click(function() {
        location.href = site_url+ '/Admintrain/vmlist';
    })

});
