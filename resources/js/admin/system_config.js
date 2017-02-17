/**
 * Created by qirupeng on 2016/8/23.
 */
var timer;
var bl = false;
var testState;//当前是否存在实验 ajaxz状态标识
//备份
$('#backups').click(function(){
    if(bl){
        return
    }
    bl = true;
    $.ajax({
        url: site_url + "/System/backup",
        type: 'post',
        data: {},
        dataType: 'json',
        success: function(message) {

            if(message.code == '0000'){
                fnHide("#backupsBox", "fadeInDown","fadeOutUp");
                window.location.href = site_url+'/System/download_file_sql?version_file_path='+message.data;
            }else{
                fnHide("#backupsBox", "fadeInDown","fadeOutUp");
                $("#okBox p.promptNews").html("备份失败");
                fnShow("#okBox","fadeOutUp","fadeInDown");
                setTimeout(function () {
                    fnHide("#okBox", "fadeInDown","fadeOutUp");
                },3000);



            }
            bl = false;
        }
    })
});
$('.backups').click(function(){
    $("#recoverfile").val('');
    $(".file_info_show_box").val('');//此处为临时解决自动清除url问题
    fnShow("#backupsBox", "fadeOutUp", "fadeInDown");
});

$('.recovers').click(function(){
    $("#recoverfile").val('');
    $(".file_info_show_box").val('');//此处为临时解决自动清除url问题
    fnShow("#recoversBox", "fadeOutUp", "fadeInDown");
});
$('.plantform').click(function(){
    $("#recoverfile").val('');
    $(".file_info_show_box").val('');//此处为临时解决自动清除url问题
    fnShow("#plantformBox", "fadeOutUp", "fadeInDown");
});

$('.course').click(function(){
    selTest();
});
var bf = false;
//恢复出厂设置
$('#recovers').click(function(){
    if(bf){
        return
    }
    if( confirm('恢复出厂设置将清空数据库中所有新增数据，确认恢复？')){
        $("#div_progress").removeClass('outHide');
        bf = true;
        $('#recover_errorinfo').html('正在恢复，请稍后。。。');
        $.ajax({
            url: site_url+'/System/recover',
            type:'post',
            data:{ },
            dataType:'json',
            success:function(message){
                if(message.code=='0000'){
                    $('#recover_errorinfo').html('恢复成功');
                    fnHide("#recoversBox","fadeInDown","fadeOutUp");
                    bf = false;
                }else if(message.code=='error'){
                    $('#recover_errorinfo').html('恢复失败'+':'+message.msg);
                    bf = false;
                }else{
                    /*$('#recover_errorinfo').html('恢复失败!');
                    bf = false;*/
                    $('#recover_errorinfo').html('恢复成功');
                    fnHide("#recoversBox","fadeInDown","fadeOutUp");
                    bf = false;
                    setTimeout(function(){
                        window.location.reload();
                    },2000);
                }

            },
            error:function () {
                $('#recover_errorinfo').html('恢复成功');
                fnHide("#recoversBox","fadeInDown","fadeOutUp");
                bf = false;
                setTimeout(function(){
                    window.location.reload();
                },2000);
            }
        });

        $.ajax({
            url:site_url+'/System/restore_system_php',
            type:'post',
            data:{ },
            dataType:'json',
            success:function(message){

                if(message.code=='0000'){
                    $('#recover_errorinfo').html('恢复成功');
                    fnHide("#recoversBox","fadeInDown","fadeOutUp");
                    bf = false;
                }else if(message.code=='error'){
                    $('#recover_errorinfo').html('恢复失败'+':'+message.msg);
                    bf = false;
                }else{
                    /*$('#recover_errorinfo').html('恢复失败!');
                    bf = false;*/
                    $('#recover_errorinfo').html('恢复成功');
                    fnHide("#recoversBox","fadeInDown","fadeOutUp");
                    bf = false;
                    setTimeout(function(){
                        window.location.reload();
                    },2000);
                }
            },
            error:function () {
                $('#recover_errorinfo').html('恢复成功');
                fnHide("#recoversBox","fadeInDown","fadeOutUp");
                bf = false;
                setTimeout(function(){
                    window.location.reload();
                },2000);
            }
        })
    }

});
var sj = false;
//升级SQL
$('#savequestion').click(function () {
    var fname = $("#recoverfile").val();//如果是升级必须上传文件
    if(sj){
        return
    }
    if(fname != ''){
        $("#div_progress").removeClass('outHide');
        bf = true;
        $('#recover_errorinfo').html('正在升级，请稍后。。。');
        setprogress();
        $.ajax({
            url:site_url+'/System/update_system_sql',
            type:'post',
            data:{'fname':fname,'ftype':$("input[name='radiodan']:checked").val(),"code":$("input[name='radiodan']:checked").attr("code")},
            dataType:'json',
            success:function(message){
                //if(message.code=='0000'){
                    $('#recover_errorinfo').html('升级成功');
                    fnHide("#recoversBox","fadeInDown","fadeOutUp");
                    //$("#div_progress").addClass('outHide');
                    //刷新页面
                    setTimeout("location.reload()",1000);
                    sj = false;
                /*}else{
                    $('#recover_errorinfo').html(message.msg);
                    sj = false;
                }*/
            }
        })



    }else{
        $('#recover_errorinfo').html('请先上传文件');
        return false;
    }
});
//平台系统升级
$('#plantformBtn').click(function(){
    var fname = $("#recoverfile").val();
    if(bl){
        return
    }
    if(fname != ''){
        $("#div_progress").removeClass('outHide');
        bl = true;
        $('#plantform_errorinfo').html('正在升级，请稍后。。。');
        //按钮不可点击
        $('#plantformBtn').css('background','#cccccc');
        //$('#plantformBtn').unbind("click");
        $('#plantformBtn').css('cursor','default');
        varw = 10;
        setprogress();

        $.ajax({
            url:site_url+'/System/platform_upgrade',
            type:'post',
            data:{'fname':fname,'ftype':$("input[name='radiodan']:checked").val(),"code":$("input[name='radiodan']:checked").attr("code")},
            dataType:'json',
            success:function(message){
                if(message.code=='0000'){
                    $('#plantform_errorinfo').html('升级成功');
                    fnHide("#plantformBox","fadeInDown","fadeOutUp");
                    setTimeout("location.reload()",1000);
                    bl = false;
                }else {
                    $('#plantform_errorinfo').html(message.msg);
                    bl = false;
                }
            }
        })



    }else{
        $('#plantform_errorinfo').html('请先上传文件');
        return false;
    }

});
$('#plantformclose').click(function () {
    fnHide("#plantformBox","fadeInDown","fadeOutUp");
    $('#plantform_errorinfo').html('* 警告：升级可能使系统不稳定，请谨慎操作。');
    $("#plantformBtn").removeAttr("style");
});
//课件及实验升级
var fname_path = "";
$('#courseBtn').click(function(){
    var fname = $("#recoverfile").val();
    if(bl){
        return
    }
    if(fname != ''){
        bl = true;
        fname_path = fname;
        $("#div_progress").show();
        $('#course_errorinfo').html('正在升级，请稍后。。。');
        $.ajax({
            url:site_url+'/System/course_upgrade',
            type:'post',
            data:{'fname':fname},
            dataType:'json',
            success:function(message){
                if(message.code=='0000'){
                    $('#course_errorinfo').html('升级成功');
                    fnHide("#courseBox","fadeInDown","fadeOutUp");
                    setTimeout("location.reload()",1000);
                    bl = false;
                }else{
                    $('#course_errorinfo').html(message.msg);
                    bl = false;
                }
            }
        });

        // onupdateprogress();

    }else{
        $('#course_errorinfo').html('请先上传文件');
        return false;
    }
});
function onupdateprogress() {
    $("#okBox p.promptNews").html("升级开始.");
    fnShow("#okBox","fadeOutUp","fadeInDown");
    window.location.href = site_url+'/System/upgrade_log';
    /*$.ajax({
        url:site_url+'/System/upgrade_progress',
        type:'post',
        data:{'fname':fname_path},
        dataType:'json',
        success:function(message){
            if(message.code=='0000'){
                $('#course_errorinfo').html('升级成功');
                fnHide("#courseBox","fadeInDown","fadeOutUp");
                setTimeout("location.reload()",1000);
                bl = false;
            }else{
                $('#course_errorinfo').html(message.msg);
                bl = false;
            }
        }
    });*/

}
//升级
var varw = 10;
function setprogress() {
    setTimeout(function () {
        setprogress();
    }, 1000);
    if(varw<90){
        varw = varw+4;
    }

    $("#progress").width(varw + '%');
}

//修改节点ip
var bl_modifyPlantformIpclick= false;
$('#modifyPlantformIp').click(function(){
    var host_type = $("#MainNodeIp").attr("host_type");
    var id = $("#MainNodeIp").attr("host_id");
    var ip = $("#MainNodeIp").val();
    var netmask = $("#MainNodeNetmask").val();
    var gateway = $("#MainNodeGateway").val();

    //验证用户名密码
    var username = $.trim($('#username').val());
    var password = $.trim($('#password').val());
    var flag = 0;
    var flagIP = 0;
    var reg =  /^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$/;
    if(ip == ''){
        $("#ModifyIpInfo").html('平台IP不能为空');
        return false;
    }else if(!reg.test(ip)){
        $("#ModifyIpInfo").html('平台IP填写不正确');
        return false;
    }else if(ip == bajiIP){
        $("#ModifyIpInfo").html('平台IP不能和靶机IP相同');
        return false;
    }else{
        $("#ModifyIpInfo").html('');
    }

    if(netmask == ''){
        $("#ModifyIpInfo").html('平台掩码不能为空');
        return false;
    }else if(!reg.test(netmask)){
        $("#ModifyIpInfo").html('平台掩码填写不正确');
        return false;
    }else{
        $("#ModifyIpInfo").html('');
    }

    if(gateway == ''){
        $("#ModifyIpInfo").html('平台网关不能为空');
        return false;
    }else if(!reg.test(gateway)){
        $("#ModifyIpInfo").html('平台网关填写不正确');
        return false;
    }else{
        $("#ModifyIpInfo").html('');
    }

    if(username == ''){
        $("#ModifyIpInfo").html('请输入用户名');
        return false;
    }else{
        $("#ModifyIpInfo").html('');
    }
    if(password == ''){
        $("#ModifyIpInfo").html('请输入密码');
        return false;
    }else{
        $("#ModifyIpInfo").html('');
    }
    var msg = "";
    if(bl_modifyPlantformIpclick){
        return false
    }
    bl_modifyPlantformIpclick = true;

    $("#modifyPlantformIp").css("disabled", "true");
    $.ajax({
        url: site_url+ "/System/verify_user",
        type: 'post',
        data: {'username':username,'password':password},
        dataType: 'json',
        async: false,
        success: function(message) {
            if(message.code != '0000'){
                flag = 1;
            }
        }
    });
    if(flag == 1){
        $("#ModifyIpInfo").html('用户名密码验证失败');
        bl_modifyPlantformIpclick = false;
        return false;
    }

    msg = "请耐心等待，正在更新数据...<br/>";
    $("#ModifyIpInfo").html(msg);
    //按钮不可点击
    $('#modifyPlantformIp').css('background','#cccccc');
    $('#modifyPlantformIp').unbind("click");
    $('#modifyPlantformIp').css('cursor','default');

    $.ajax({
        url: site_url + "/System/modify_platform",
        type: 'post',
        data: {"host_type":host_type,"id":id,"ip":ip,"netmask":netmask,"gateway":gateway},
        dataType: 'json',
        async : true,
        success: function(message) {
            if(message.code == '0000'){
                task_uuid = message.data.task_uuid;
                //定时器
                timer = setInterval(function(){
                    //配置进度
                    setIntervalProgress_web();
                },1000);
            }else{
                $('#ModifyIpInfo').html(message.msg);
            }
        }

    });
    /*$("#okBox p.promptNews").html("修改成功");
    fnShow("#okBox","fadeOutUp","fadeInDown");
    setTimeout(function(){
        fnHide("#okBox","fadeInDown","fadeOutUp");
        window.location.href='http://'+ip;
    },2000);*/

});


$(function(){
    //上传插件
    var upadd = $('#adduploadIcon').Huploadify({
        auto: true,//当选择文件后就直接上传了
        fileTypeExts: '*.zip',//上传文件类型
        multi: false, //上传多个文件
        fileSizeLimit: 999999999999,
        breakPoints: true,
        saveInfoLocal: true,
        showUploadedPercent: true,//是否实时显示上传的百分比，如20%
        showUploadedSize: true,
        removeTimeout: 1,//上传完成后多久删除队列中的进度条
        fileSplitSize:2048*2048,
        buttonText:'上传附件',
        formData:{key:'',key2:'system_'},
        uploader: site_url + '/System/upload',//服务器端脚本文件路径
        onUploadComplete: function (messfileObj, info, responseage) {
            var data = JSON.parse(info);
            if (data.success == false){
                alert('上传失败');
            }else{
                var url =   "/resources/files/system_update/" + data.filename;
                $("#recoverfile").val(url);

                $(".file_info_show_box").val(url);//此处为临时解决自动清除url问题
            }

        },
        onUploadStart: function (file) {//上传开始时触发（每个文件触发一次）
            //清空上传上传的值
            $("#recoverfile").val('');
            $(".file_info_show_box").val('');//此处为临时解决自动清除url问题
            var timestamp = Date.parse(new Date());
            var updatetype = "system_";
            upadd.settings("formData", {key:timestamp,key2:updatetype});
        }
    });
    var upadd_pantform = $('#plantform_uploadIcon').Huploadify({
        auto: true,//当选择文件后就直接上传了
        fileTypeExts: '*.zip',//上传文件类型
        multi: false, //上传多个文件
        fileSizeLimit: 999999999999,
        breakPoints: true,
        saveInfoLocal: true,
        showUploadedPercent: true,//是否实时显示上传的百分比，如20%
        showUploadedSize: true,
        removeTimeout: 1,//上传完成后多久删除队列中的进度条
        fileSplitSize:2048*2048,
        buttonText:'上传附件',
        formData:{key:'',key2:'system_'},
        uploader: site_url + '/System/upload',//服务器端脚本文件路径
        onUploadComplete: function (messfileObj, info, responseage) {
            var data = JSON.parse(info);
            if (data.success == false){
                alert('上传失败');
            }else{
                var url =   "/resources/files/system_update/" + data.filename;
                $("#recoverfile").val(url);

                $(".file_info_show_box").val(url);//此处为临时解决自动清除url问题
            }

        },
        onUploadStart: function (file) {//上传开始时触发（每个文件触发一次）
            //清空上传上传的值
            $("#recoverfile").val('');
            $(".file_info_show_box").val('');//此处为临时解决自动清除url问题
            var timestamp = Date.parse(new Date());
            var updatetype = "system_";
            upadd_pantform.settings("formData", {key:timestamp,key2:updatetype});
        }
    });
    var course_upload = $('#course_uploadIcon').Huploadify({
        auto: true,//当选择文件后就直接上传了
        fileTypeExts: '*.zip',//上传文件类型
        multi: false, //上传多个文件
        fileSizeLimit: 999999999999,
        breakPoints: true,
        saveInfoLocal: true,
        showUploadedPercent: true,//是否实时显示上传的百分比，如20%
        showUploadedSize: true,
        removeTimeout: 1,//上传完成后多久删除队列中的进度条
        fileSplitSize:2048*2048,
        buttonText:'上传附件',
        formData:{key:'',key2:'system_'},
        uploader: site_url + '/System/upload',//服务器端脚本文件路径
        onUploadComplete: function (messfileObj, info, responseage) {
            var data = JSON.parse(info);
            if (data.success == false){
                alert('上传失败');
            }else{
                var url =   "/resources/files/system_update/" + data.filename;
                $("#recoverfile").val(url);

                $(".file_info_show_box").val(url);//此处为临时解决自动清除url问题
            }

        },
        onUploadStart: function (file) {//上传开始时触发（每个文件触发一次）
            //清空上传上传的值
            $("#recoverfile").val('');
            $(".file_info_show_box").val('');//此处为临时解决自动清除url问题
            var timestamp = Date.parse(new Date());
            var updatetype = "system_";
            course_upload.settings("formData", {key:timestamp,key2:updatetype});
        }
    });

    //存在实验提示框确定按钮点击事件
    $("#testNotice .testBtn").on("click",function(){
        $("#recoverfile").val('');
        $(".file_info_show_box").val('');//此处为临时解决自动清除url问题
        fnHide("#testNotice","fadeInDown","fadeOutUp");
        setTimeout('fnShow("#courseBox", "fadeOutUp", "fadeInDown")',1000);
    });

    //查询是否存在实验时 网络连接失败 确定按钮点击事件
    $("#noticeBox .noticeBtn").on("click",function(){
        $("#noticeBox .close-1").click();
    });
});

$("#changeRootRouterIP").click(function(){
    var hostid = $(this).attr("host_id");
    var routerIp = $("#routerIp").val();
    var routerNetMask = $("#routerNetMask").val();
    var routerGateway = $("#routerGateway").val();
    var oldrouteIp = $("#routerIp").attr("host_value");
    var oldrouteNetMask = $("#routerNetMask").attr("host_value");
    var oldrouteGateway = $("#routerGateway").attr("host_value");
    var reg =  /^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$/;
    if(routerIp == ''){
        $("#msgbox").html('入口IP不能为空');
        return false;
    }else if(!reg.test(routerIp)){
        $("#msgbox").html('入口IP填写不正确');
        return false;
    }else if(routerIp == ptIP){
        $("#msgbox").html('靶机IP不能和平台IP相同');
        return false;
    }else{
        $("#msgbox").html('');
    }

    if(routerNetMask == ''){
        $("#msgbox").html('子网掩码不能为空');
        return false;
    }else if(!reg.test(routerNetMask)){
        $("#msgbox").html('子网掩码填写不正确');
        return false;
    }else{
        $("#msgbox").html('');
    }

    if(routerGateway == ''){
        $("#msgbox").html('靶机网关不能为空');
        return false;
    }else if(!reg.test(routerGateway)){
        $("#msgbox").html('靶机网关填写不正确');
        return false;
    }else{
        $("#msgbox").html('');
    }
    //验证用户名密码
    var username = $.trim($('#BJusername').val());
    var password = $.trim($('#BJpassword').val());
    var BJflag = 0;
    if(username == ''){
        $("#msgbox").html('请输入用户名');
        return false;
    }else{
        $("#msgbox").html('');
    }
    if(password == ''){
        $("#msgbox").html('请输入密码');
        return false;
    }else{
        $("#msgbox").html('');
    }

    if (routerIp == oldrouteIp && routerNetMask == oldrouteNetMask  && routerGateway == oldrouteGateway){
        $("#msgbox").html('请填入修改后的入口IP、子网掩码、靶机网关');
        return false;
    }


    $.ajax({
        url: site_url + "/System/verify_user",
        type: 'post',
        data: {'username':username,'password':password},
        dataType: 'json',
        async: false,
        success: function(message) {
            if(message.code != '0000'){
                BJflag = 1;
            }
        }
    });

    if(BJflag == 1){
        $("#msgbox").html('用户名密码验证失败');
        return false;
    }

    //按钮不可点击
    $('#changeRootRouterIP').css('background','#cccccc');
    $('#changeRootRouterIP').unbind("click");
    $('#changeRootRouterIP').css('cursor','default');
    $('#msgbox').html('正在修改...');
    $.ajax({
        url: site_url + "/System/modify_router",
        type: 'post',
        data: {'hostid':hostid, 'routerIp':routerIp, 'routerGateway':routerGateway, 'routerNetMask':routerNetMask},
        dataType: 'json',
        success: function(message) {
            if(message.code == '0000'){
                task_uuid = message.data.task_uuid;
                //定时器
                timer = setInterval(function(){
                    //靶机进度
                    setIntervalProgress();
                },1000);
            }else{
                $('#msgbox').html(message.msg);
            }
        }
    });
});



//查看平台IP进度
function setIntervalProgress_web(){
    if(task_uuid !=''){
        $.ajax({
            url : site_url + '/System/get_task_progress',
            type : 'post',
            data : {"task_uuid":task_uuid},
            dataType : 'json',
            timeout: 10000,
            success : function(msg){
                if( msg.code == '0000' ) {
                    var percent_description= msg.data.percent_description;

                    real_status = msg.data.task_status;
                    real_infos = msg.data.percent_description;
                    if( real_status == 6 ) { // 成功
                        $('#ModifyIpInfo').html('');
                        $("#okBox p").html("修改成功");
                        fnShow("#okBox","fadeOutUp","fadeInDown");
                        //停止查看进度
                        task_uuid = '';
                        var ip = $("#MainNodeIp").val();
                        setTimeout(function(){
                            //fnHide("#okBox","fadeInDown","fadeOutUp");
                            window.location.href='http://'+ip;
                        },2000);

                    } else if( real_status == 4 || real_status == 7 || real_status == 8) { // 失败
                        $('#ModifyIpInfo').html('配置失败&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;原因：'+percent_description);
                    }else{
                        var percent=msg.data.task_percent;
                        //5%卡的问题
                        if(percent == 5){
                            $('#ModifyIpInfo').html('');
                            $("#okBox p").html("修改成功");
                            fnShow("#okBox","fadeOutUp","fadeInDown");
                            //停止查看进度
                            task_uuid = '';
                            var ip = $("#MainNodeIp").val();
                            setTimeout(function(){
                                window.location.href='http://'+ip;
                            },2000);
                        }
                        $('#ModifyIpInfo').html('修改平台进度：'+percent+'%&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;进度描述：'+percent_description);
                        //按钮不可点击
                        $('#modifyPlantformIp').css('background','#cccccc');
                        $('#modifyPlantformIp').unbind("click");
                        $('#modifyPlantformIp').css('cursor','default');
                    }

                } else {
                    $('#ModifyIpInfo').html('');
                    $("#okBox p").html("修改成功，页面将自动跳转");
                    fnShow("#okBox","fadeOutUp","fadeInDown");
                    //停止查看进度
                    task_uuid = '';
                    var ip = $("#MainNodeIp").val();
                    setTimeout(function(){
                        window.location.href='http://'+ip;
                    },5000);
                    //$('#ModifyIpInfo').html('请求失败,请检查网络！');
                }

            },
            error:function () {
                $('#ModifyIpInfo').html('');
                $("#okBox p").html("修改成功，页面将自动跳转");
                fnShow("#okBox","fadeOutUp","fadeInDown");
                //停止查看进度
                task_uuid = '';
                var ip = $("#MainNodeIp").val();
                setTimeout(function(){
                    window.location.href='http://'+ip;
                },2000);
            }
        })
    }

}
//查看靶机进度
function setIntervalProgress(){
    if(task_uuid !=''){
        $.ajax({
            url : site_url + '/System/get_task_progress',
            type : 'post',
            data : {"task_uuid":task_uuid},
            dataType : 'json',
            success : function(msg){
                if( msg.code == '0000' ) {
                    var percent_description= msg.data.percent_description;

                    real_status = msg.data.task_status;
                    real_infos = msg.data.percent_description;
                    if( real_status == 6 ) { // 成功
                        $('#msgbox').html('');
                        $("#okBox p").html("修改成功");
                        fnShow("#okBox","fadeOutUp","fadeInDown");
                        //停止查看进度
                        task_uuid = '';
                        setTimeout(function(){
                            //fnHide("#okBox","fadeInDown","fadeOutUp");
                            window.location.href=window.location.href;
                        },2000);

                    } else if( real_status == 4 || real_status == 7 || real_status == 8) { // 失败
                        $('#msgbox').html('修改靶机失败&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;原因：'+percent_description);
                    }else{
                        var percent=msg.data.task_percent;
                        $('#msgbox').html('修改靶机进度：'+percent+'%&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;进度描述：'+percent_description);
                        //按钮不可点击
                        $('#changeRootRouterIP').css('background','#cccccc');
                        $('#changeRootRouterIP').unbind("click");
                        $('#changeRootRouterIP').css('cursor','default');
                    }

                } else {
                    $('#msgbox').html('请求失败,请检查网络！');
                }

            }
        })
    }

}

//查看当前是否存在正在进行的实验
function selTest(){
    if(testState != null && testState.state() === 'pending'){
        return;
    }
    testState = $.post(site_url+"/System/existenceTest",function(obj){
        try{
            var json = (new Function("return"+obj+";"))();
            if(json.code == "0000"){
                $("#recoverfile").val('');
                $(".file_info_show_box").val('');//此处为临时解决自动清除url问题
                fnShow("#courseBox", "fadeOutUp", "fadeInDown");
            }else if(json.code == "0001"){
                $("#recoverfile").val('');
                $(".file_info_show_box").val('');//此处为临时解决自动清除url问题
                fnShow("#testNotice", "fadeOutUp", "fadeInDown");
            }else if(json.code == "0201"){
                $("#recoverfile").val('');
                $(".file_info_show_box").val('');//此处为临时解决自动清除url问题
                fnShow("#noticeBox", "fadeOutUp", "fadeInDown");
            }
        }catch(e){

        }
    });
}
