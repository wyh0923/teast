/**
 * Created by Administrator on 2016/9/17.
 */

$(function () {
    //上传插件
    var timestamp = Date.parse(new Date());

    var upadd = $('#file_upload_1-button').Huploadify({
        auto: true,//当选择文件后就直接上传了
        fileTypeExts: '*.jpg;*.png;*.gif;*.jpeg;*.zip;*.gzip;*.rar;*.doc;*.docx;*.xls;*.xlsx;*.qcow2;',//上传文件类型
        multi: true, //上传多个文件
        fileSizeLimit: 999999999999,
        breakPoints: true,
        saveInfoLocal: true,
        showUploadedPercent: true,//是否实时显示上传的百分比，如20%
        showUploadedSize: true,
        removeTimeout: 1,//上传完成后多久删除队列中的进度条
        fileSplitSize: 2048 * 2048,
        buttonText: '上传工具',
        formData: {key: '', key2: 'tool_',toolDir:toolDir},
        uploader: site_url + '/Adminsubject/upload_tool',//服务器端脚本文件路径
        onUploadComplete: function (messfileObj, info, responseage) {

            var data = JSON.parse(info);

            if(data.success == false){
                $('#adderrormsg').html('上传工具失败');
            }

            $('.file_info_show_box').val(data.filename);
            $('#toolUrl').val(data.filename);

        },
        onUploadStart: function (file) {//上传开始时触发（每个文件触发一次）

            upadd.settings("formData", {key: timestamp, key2: 'tool_',toolDir:toolDir});
        }
    });

    //添加工具
    $('#savetool').click(function () {
        var toolType = $('#typeSel option:selected').val();
        var toolName = $.trim($('#toolName').val());
        var toolDesc = $.trim($('#toolDesc').val());
        var toolUrl = $('#toolUrl').val();

        if (toolType == "0"){
            $('#adderrormsg').html('请先选择一个工具子类');
            return false;
        }

        if (toolName == ""){
            $('#adderrormsg').html('请填写工具名称');
            return false;
        }

        if (toolDesc == ""){
            $('#adderrormsg').html('请填写工具描述');
            return false;
        }

        if (toolUrl == ""){
         $('#adderrormsg').html('请先上传一个工具');
         return false;
         }

        var data = {'ToolType': toolType, 'ToolName': toolName, 'ToolDesc': toolDesc, 'ToolUrl': toolUrl, 'toolCode': toolcode};

        $.ajax({
            url: site_url+'/Adminsubject/addtool',
            type: 'post',
            data: data,
            dataType: 'json',
            success: function (msg) {
                if (msg.code == '0000') {
                    $('#adderrormsg').html('保存成功');
                    location.href = site_url + '/Adminsubject/toollist';
                } else {
                    $('#adderrormsg').html('工具名称已存在');
                    //location.href = siteurl + 'TeaArchCtl/toollist';
                }

            },
            error: function (msg) {

            }
        });
    });


});
