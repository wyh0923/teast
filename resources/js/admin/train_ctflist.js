/**
 * Created by qirupeng on 2016/8/29.
 */
$(function () {
    $(".fa-search").click(function () {
        var search = $.trim($(".iptSearch-a").val());
        var str = '';
        if (time != '')str += '/time/' + time;
        window.location.href = site_url + '/Admintrain/ctflist' + str + "/search/" + encodeURI(translate(search));
    });
    $('.iptSearch-a').keydown(function (e) {
        if (e.keyCode == 13) {
            var search = $.trim($(".iptSearch-a").val());
            var str = '';
            if (time != '')str += '/time/' + time;
            window.location.href = site_url + '/Admintrain/ctflist' + str + "/search/" + encodeURI(translate(search));
        }
    });
});

$(function () {

    $('#addBtn').click(function () {
        $("#uploadctfadd").val('');
        $('.fileupname').html('');
        $("#name").val('');
        $("#desc").val('');
        $('.file_info_show_box').val('');
        $("#addCtfUrl").val('');
        $("#CtfUrldesc").val('');
        $('.multilineIpt').val('');
        $('#errors').html('');
        $('#ctfMoreList').html('');
        $(".filterctf").removeClass("filterCur");
        $('.addfilterList').children(":first").addClass('filterCur');
        $(".typectf").removeClass("filterCur");
        $('.typectf').eq(0).addClass('filterCur');
        //上传资源出错后显示
        $("#adduploadBox").removeAttr("style");
        fnShow("#addCtfTemplate", "fadeOutUp", "fadeInDown");
    });

    $('.editBtn').click(function () {
        $('.fileeditname').html('');
        fnShow("#editCtfTemplate", "fadeOutUp", "fadeInDown");
    });
    //delete
    $('.forRed').click(function () {
        var code = $(this).attr('code');
        $('.delBtn').attr('code', code);
        fnShow("#deleteOperation", "fadeOutUp", "fadeInDown");
    });


    // detail
    $('.forYellow').click(function () {
        var code = $(this).attr('code');
        $.ajax({
            url: site_url + "/Admintrain/get_ctf_info",
            type: 'post',
            data: {'code': code},
            dataType: 'json',
            success: function (message) {
                if (message.code == '0000') {
                    $('.CtfName').text(message.data.CtfName);
                    $('.CtfType').text(ctftype[message.data.CtfType]);
                    $('.CtfContent').text(message.data.CtfContent);
                    $('.CtfDiff').text(CtfDiff[message.data.CtfDiff]);
                    $('.CtfResources').html("<a  target='_blank' href='" + base_url + "resources/files/ctf/" + message.data.CtfResources + "'>" + message.data.CtfResources + "</a>");
                    if($('.CtfContent').text().length>60){
                        $('.CtfContent').css({"text-align":'left'});
                    }
                    fnShow("#ctfTemplateDetail", "fadeOutUp", "fadeInDown");
                }
            }
        })

    });
    $('#detailclose').click(function () {
        fnHide("ctfTemplateDetail", "fadeInDown", "fadeOutUp");
    });
    $('#detailBtn').click(function () {
        fnHide("#ctfTemplateDetail", "fadeInDown", "fadeOutUp");
    });

    $('#editclose').click(function () {
        $('#errorsInfo').html('');
        fnHide("#editCtfTemplate", "fadeInDown", "fadeOutUp");
    });


    $('.filterctf').click(function () {
        $('.filterctf').attr('class', 'filterctf');
        $(this).attr('class', 'filterctf filterCur');

    });

    $('.typectf').click(function () {
        $('.typectf').attr('class', 'typectf');
        $(this).attr('class', 'typectf filterCur');
    });

    $('.efilterctf').click(function () {
        $('.efilterctf').attr('class', 'efilterctf');
        $('.ecurctf').attr('class', 'efilterctf');
        $(this).attr('class', 'efilterctf filterCur');
    });
    $('.etypectf').click(function () {
        $('.etypectf').attr('class', 'etypectf');
        $('.etypecurctf').attr('class', 'etypectf');
        $(this).attr('class', 'etypectf filterCur');
    });
    var timestamp = Date.parse(new Date());

    var upedit = $('#uploadTool').Huploadify({
        formData: {key: timestamp, key2: 'ctf'},
        auto: true,//当选择文件后就直接上传了
        fileTypeExts: '*.jpg;*.png;*.gif;*.jpeg;*.zip;*.gzip;*.rar;*.doc;*.docx;*.xls;*.xlsx',//上传文件类型
        multi: false, //上传多个文件
        fileSizeLimit: 999999999999,
        breakPoints: true,
        saveInfoLocal: true,
        showUploadedPercent: true,//是否实时显示上传的百分比，如20%
        showUploadedSize: true,
        removeTimeout: 100,//上传完成后多久删除队列中的进度条
        fileSplitSize: 2048 * 2048,
        buttonText: '上传资源',
        uploader: site_url + '/Admintrain/upload_ctf',//服务器端脚本文件路径
        onUploadComplete: function (fileObj, info, response) {
            $(".uploadBox").show();
            $("#uploadIpt").css("visibility", "visible");
            $(".uploadBox").css("border", "1px solid #ccc");
            var data = JSON.parse(info);
           
            if(data.success==true){
                $("#uploadctf").val(data.filename);
                $('.file_info_show_box').val(data.filename);
                var url = base_url + "resources/files/ctf/" + data.filename;
                var content = '';
                content += '<tr><td class="resourcename">' + data.filename + '</td><td class="resourceurl"><a  id=img' + data.filename + ' href="'+url+'" target="_blank">' + url + '</a></td></tr>';
                $('#edit_ctfMoreList').html(content);
            }
            else{
                $('.fileeditname').html('上传失败，请重新上传');
            }
            

        },
        onUploadStart: function (file) {//上传开始时触发（每个文件触发一次）
            $(".file_info_show_box").val('');//此处为临时解决自动清除url问题
            var timestamp = Date.parse(new Date());
            var updatetype = "ctf";
            upadd.settings("formData", {key: timestamp, key2: updatetype});
        }
    });
    var upadd = $('#adduploadBox').Huploadify({
        formData: {key: timestamp, key2: 'ctf'},
        auto: true,//当选择文件后就直接上传了
        fileTypeExts: '*.jpg;*.png;*.gif;*.jpeg;*.zip;*.gzip;*.rar;*.doc;*.docx;*.xls;*.xlsx',//上传文件类型
        multi: false, //上传多个文件
        fileSizeLimit: 999999999999,
        breakPoints: true,
        saveInfoLocal: true,
        showUploadedPercent: true,//是否实时显示上传的百分比，如20%
        showUploadedSize: true,
        removeTimeout: 100,//上传完成后多久删除队列中的进度条
        fileSplitSize: 2048 * 2048,
        buttonText: '上传资源',
        uploader: site_url + '/Admintrain/upload_ctf',//服务器端脚本文件路径
        onUploadComplete: function (messfileObj, info, responseage) {

            var data = JSON.parse(info);
            if(data.success==true){ 
                $("#uploadctfadd").val(data.filename);
                $('.file_info_show_box').val(data.filename);

                var url = base_url + "resources/files/ctf/" + data.filename;

                var content = '';
                content += '<tr><td class="resourcename">' + messfileObj.name + '</td><td class="resourceurl"><a  id=img' + messfileObj.lastModified + ' href="' + url + '" target="view_window">' + url + '</a></td><td><a href="javascript:;" class="btncopy" code=' + url + ' data-clipboard-action="copy" data-clipboard-target=#img' + messfileObj.lastModified + '><i class="fa fa-copy" style="color:#C45F46" ></i>&nbsp;复制</a>&nbsp;&nbsp;<a href="javascript:;" onclick="delres(this)" class="upDel" dataName = "'+data.filename+'"><i class="fa fa-trash-o " ></i>删除</a></td></tr>';
                $('#ctfMoreList').html(content);
            }
           else{
               $("#errors").html("上传失败，请重新上传") 
            }

        },
        onUploadStart: function (file) {//上传开始时触发（每个文件触发一次）
            
            $(".file_info_show_box").val('');//此处为临时解决自动清除url问题
            var timestamp = Date.parse(new Date());
            var updatetype = "ctf";
            upadd.settings("formData", {key: timestamp, key2: updatetype});
        }
    });
    //复制到剪切板-------------+
    var clipboard = new Clipboard('.btncopy');
    clipboard.on('success', function (e) {
        alert("复制成功");
        e.clearSelection();
    });

    //$("#file_upload_1-button").text("");

    $('.delBtn').click(function () {
        var code = $(this).attr('code');
        $.ajax({
            url: site_url + "/Admintrain/del_ctf",
            type: 'post',
            data: {'codes': code},
            dataType: 'json',
            success: function (message) {
                $('#deleteOperation').hide();
                if (message.code == '0000') {
                    $('#okBox p.promptNews').html('删除成功！');
                    //fnHide("#deleteOperation","fadeInDown","fadeOutUp");
                    fnShow("#okBox", "fadeOutUp", "fadeInDown");
                    setTimeout(function () {
                        //fnHide("#okBox","fadeInDown","fadeOutUp");
                        location.href = site_url + '/Admintrain/ctflist';
                    }, 2000);
                }
            }
        })
    });

    $('#inputaddBtn').click(function () {
        var type = $('.filterctf.filterCur').attr('type');
        var diff = $('.typectf.filterCur').attr('diff');
        var name = $.trim($('#name').val());
        //var ctfServerId = $('#ctfServerId').val();
        //var addCtfUrl = $('#addCtfUrl').val();
        var content = $.trim($('#desc').val());
        var resources = $('.uploadIpt').val();
        //var ctfurldesc = $('#CtfUrldesc').val();
        if (name == '') {
            $('#errors').html('请填写模板名');
            return;
        } else if(name.length<2 || name.length>16){
            $('#errors').html('模板名称由2-16位字符组成');
            return ;
        }else {
            $('#errors').html('');
        }

        if (content == '') {
            $('#errors').html('请填写场景内容');
            return;
        } else if(content.length>2048){
            $('#errors').html('场景内容不能超过2048个字符');
            return;
        }else {
            $('#errors').html('');
        }
        if (resources == '') {
            $('#errors').html('请上传资料');
            return;
        } else {
            $('#errors').html('');
        }

        $.ajax({
            url: site_url + "/Admintrain/addctf",
            type: 'post',
            data: {'ctfname': name, 'ctfdiff': diff, 'ctftype': type, 'ctfcontent': content, 'ctfresources': resources},
            dataType: 'json',
            success: function (message) {
                if (message.code == '0000') {
                    $('#addCtfTemplate').hide();
                    $('#okBox p.promptNews').html(message.msg);
                    fnShow("#okBox", "fadeOutUp", "fadeInDown");
                    setTimeout(function () {
                        location.href = site_url + '/Admintrain/ctflist';
                    }, 2000);
                }else {
                    $('#errors').html(message.msg);
                }
            }
        })
    });

    $('.forBlue').click(function () {
        var code = $(this).attr('code');
        $('#errorsInfo').html('');
        $('.file_info_show_box').val('');
        $('#edit_ctfMoreList').html('');
        fnShow("#editCtfTemplate", "fadeOutUp", "fadeInDown");
        $.ajax({
            url: site_url + "/Admintrain/get_ctf_info",
            type: 'post',
            data: {'code': code},
            dataType: 'json',
            success: function (message) {
                if (message.code == '0000') {
                    $('#ctfcode').val(message.data['CtfID']);
                    $('#ctfname').val(message.data['CtfName']);

                    $('.efilterctf').each(function () {
                        var type = $(this).attr('type');
                        if (type == message.data['CtfType']) {
                            $(this).attr('class', 'efilterctf filterCur');
                        } else {
                            $(this).attr('class', 'efilterctf');
                        }
                    });
                    $('.etypectf').each(function () {
                        var diff = $(this).attr('diff');
                        if (diff == message.data['CtfDiff']) {
                            $(this).attr('class', 'etypectf filterCur');
                        } else {
                            $(this).attr('class', 'etypectf');
                        }
                    });

                    $('#ctfcontent').val(message.data['CtfContent']);
                    $('#file_info_show_box_1').val(message.data['CtfResources']);
                    $('#uploadctf').val(message.data['CtfResources']);
                    if(message.data['CtfResources']) {
                        var url = base_url + "resources/files/ctf/" + message.data['CtfResources'];
                        var content = '';
                        content += '<tr><td class="resourcename">' + message.data['CtfResources'] + '</td><td class="resourceurl"><a  id=img' + message.data['CtfResources'] + ' href="'+url+'" target="_blank">' + url + '</a></td></tr>';
                        $('#edit_ctfMoreList').html(content);
                    }
                }
            }
        })
    });

    //点击取消隐藏删除框
    $(".b-clear").click(function () {
        fnHide("deleteOperation", "fadeInDown", "fadeOutUp");
    });
    $('#inputeditBtn').click(function () {
        var code = $('#ctfcode').val();
        var type = $('.efilterctf.filterCur').attr('type');

        var diff = $('.etypectf.filterCur').attr('diff');

        var name = $.trim($('#ctfname').val());
        var editCtfUrl = $('#editCtfUrl').val();
        var content = $.trim($('#ctfcontent').val());
        var resources = $('#uploadctf').val();
        var editCtfUrldesc = $('#editCtfUrldesc').val();

        if (name == '') {
            $('#errorsInfo').html('请填写模板名');
            return;
        } else if(name.length<2 || name.length>16){
            $('#errorsInfo').html('模板名称由2-16位字符组成');
            return ;
        }else {
            $('#errorsInfo').html('');
        }



        if (content == '') {
            $('#errorsInfo').html('请填写场景内容');
            return;
        } else if(content.length>2048){
            $('#errorsInfo').html('场景内容不能超过2048个字符');
            return;
        }else {
            $('#errorsInfo').html('');
        }
        if (resources == '') {
            $('#errorsInfo').html('请上传资料');
            return;
        } else {
            $('#errorsInfo').html('');
        }

        $.ajax({
            url: site_url + "/Admintrain/edit_ctf",
            type: 'post',
            data: {
                'CtfID': code,
                'ctfname': name,
                'ctfurl': editCtfUrl,
                'ctfdiff': diff,
                'ctftype': type,
                'ctfcontent': content,
                'ctfresources': resources,
                'ctfurldesc': editCtfUrldesc
            },
            dataType: 'json',
            success: function (message) {
                if (message.code == '0000' || message.code == '0308') {
                    $('#editCtfTemplate').hide();
                    $('#okBox p.promptNews').html('编辑成功');
                    fnShow("#okBox", "fadeOutUp", "fadeInDown");
                    setTimeout(function () {
                        location.href = site_url + '/Admintrain/ctflist';
                    }, 2000);
                } else {
                    $('#errorsInfo').html(message.msg);
                }
            }
        })
    });

});
//添加ctf 详情url链接
$('.CtfUrl').css('cursor', 'pointer');
$('.CtfUrl').click(function () {
    var ctfUrl = $('.CtfUrl').text();

    var a = document.createElement("a");
    a.setAttribute("href", ctfUrl);
    a.setAttribute("target", "_blank");
    document.body.appendChild(a);
    a.click();
});

function delres(ee) {
    // var dataName = $(ee).attr("dataname");
    // var inputName = $("#uploadctfadd").val();
    // if(dataName==inputName){}
        $("#uploadctfadd").val('');
        $('.file_info_show_box').val('');
    
    $(ee).parent().parent().remove();

}
function searchForTime() {
    if ($("#stime").val() != "" && $("#etime").val() != "") {
        if ($("#stime").val() >= $("#etime").val()) {
            $("#okBox p.promptNews").html('开始时间不能大于等于结束时间');
            fnShow("#okBox", "fadeOutUp", "fadeInDown");
            setTimeout(function () {
                window.location.reload();
            }, 2000)
        } else {
            var str = '';
            if (search != '')str += '/search/' + encodeURI(translate(search));
            window.location.href = site_url + '/Admintrain/ctflist' + str + '/time/' + $("#stime").val() + "_" + $("#etime").val()
        }
    }

}
function clearTime() {
    if ($("#stime").val() == "" && $("#etime").val() == "") {
        var str = '';
        if (search != '')str += '/search/' + encodeURI(translate(search));
        window.location.href = site_url + '/Admintrain/ctflist' + str;
    }
}