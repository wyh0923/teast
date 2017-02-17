/**
 * Created by Administrator on 2016/8/29.
 */

//获取体系
function getsys() {
    var pid = $('#onearchite option:selected').val();
    if(pid==0){
        $('#twoarchiteDiv').css('display', 'none');
    }
    else{
        $.ajax({
            url: site_url + '/Adminsubject/getsys',
            type: 'post',
            data: {pid: pid},
            dataType: 'json',
            success: function (msg)
            {
                //console.log(msg);
                if(msg.code == '0000')
                {
                    $('#twoarchiteDiv').css('display', 'block');
                    var text = '';
                    $.each(msg.data, function (i, item) {
                        text += "<option value='"+ item['ArchitectureID'] +"'>"+ item['ArchitectureName'] +"</option>";
                    });
                    $('#twoarchite').html(text);
                }
                else
                {
                    $('#twoarchiteDiv').css('display', 'none');
                }
            },
        });
    }
}

$(function () {
    //课程
    $(".clsearch").click(function(){
        var search = translate($.trim($("#courSearch").val()));
        if(pid == '' && aid == ''){
            window.location.href=site_url + '/Adminsubject/mybook' + "/search/"+encodeURI(search);
        }
        if(pid != '' && aid == ''){
            window.location.href=site_url + '/Adminsubject/mybook' + "/search/"+encodeURI(search)+ '/pid/'+ pid;
        }
        if(pid != '' && aid != ''){
            window.location.href=site_url + '/Adminsubject/mybook' + "/search/"+encodeURI(search)+ '/pid/'+ pid+ '/aid/'+ aid;
        }

    });

    $('#courSearch').keydown(function(e){
        if(e.keyCode==13){
            var search = translate($.trim($("#courSearch").val()));
            if(pid == '' && aid == ''){
                window.location.href=site_url + '/Adminsubject/mybook' + "/search/"+encodeURI(search);
            }
            if(pid != '' && aid == ''){
                window.location.href=site_url + '/Adminsubject/mybook' + "/search/"+encodeURI(search)+ '/pid/'+ pid;
            }
            if(pid != '' && aid != ''){
                window.location.href=site_url + '/Adminsubject/mybook' + "/search/"+encodeURI(search)+ '/pid/'+ pid+ '/aid/'+ aid;
            }
        }
    });

    $('#level label').click(function () {
        $(this).addClass('cur').siblings('label').removeClass('cur');

        //alert($(this).attr('code'));
    });

    //排序
    $('#PackageDiff,#SectionNum,#PracticeSectionNum').click(function(){
        var field = $(this).attr("id");
        var code = $(this).attr('code');
        var str = '';
        if (pid != '')str += '/pid/'+pid;
        if (aid != '')str += '/aid/'+aid;
        if (search != '')str += '/search/'+translate(search);
        if(code == 'DESC'){
            location.href = site_url+'/Adminsubject/mybook' + str + '/sort/'+field+' ASC';
        }else if(code == 'ASC'){
            location.href = site_url+'/Adminsubject/mybook' + str + '/sort/'+field+' DESC';
        }else{
            location.href = site_url+'/Adminsubject/mybook' + str + '/sort/'+field+' DESC';
        }
    });

    //新增课程
    $('#addbook').click(function () {
        var level = $('#level .cur').attr('code');
        var status = $("#PackageStatus").bootstrapSwitch("state");
        var aid = $('#twoarchite option:selected').val();
        var name = $.trim($('#PackageName').val());
        var desc = $.trim($('#PackageDesc').val());
        var img = $('#PackageImg').val();

        if(aid == undefined){
            $('#adderrormsg').html('请选择课程所属培训方案');
            return false;
        }

        if(name.length==0){
            $('#adderrormsg').html('课程名称不能为空');
            return false;
        }

        if(desc.length==0){
            $('#adderrormsg').html('课程描述不能为空');
            return false;
        }

        if(status == true){
            status = 1;
        }else {
            status = 2;
        }

        var data = {'level': level, 'status': status, 'aid': aid, 'name': name, 'desc': desc, 'img':img};

        $.ajax({
           url: site_url + '/Adminsubject/doaddbook',
            type: 'post',
            data: data,
            dataType: 'json',
            success: function (msg) {
                if(msg.code == '0000')
                {
                    $('#adderrormsg').html('新增成功');
                    setTimeout(function(){
                        window.location.href = site_url+ '/Adminsubject/mybook';
                    },1000);
                } else {
                    $('#adderrormsg').html('课程名称已存在');

                }
            },
        });

    });

    //编辑课程
    $('#editsave').click(function () {
        var level = $('#level .cur').attr('code');
        var status = $("#PackageStatus").bootstrapSwitch("state");
        var name = $.trim($('#PackageName').val());
        var desc = $.trim($('#PackageDesc').val());
        var img = $('#PackageImg').val();

        if(name.length==0){
            $('#adderrormsg').html('课程名称不能为空');
            return false;
        }

        if(desc.length==0){
            $('#adderrormsg').html('课程描述不能为空');
            return false;
        }

        if(status == true){
            status = 1;
        }else {
            status = 0;
        }

        var data = {cid: cid, 'level': level, 'status': status, 'name': name, 'desc': desc, 'img':img};

        $.ajax({
            url: site_url + '/Adminsubject/modbook',
            type: 'post',
            data: data,
            dataType: 'json',
            success: function (msg) {
                $('#adderrormsg').text('编辑成功');
                
                setTimeout(function () {
                    window.location.href = site_url + '/Adminsubject/mybook';
                }, 1000);

            },
        });

    });

    //单记录删除
    $(".delcourse").click(function(){
        var cid=$(this).attr('cid');

        $.ajax({
            url: site_url + '/Adminsubject/isstudy',
            data: {'cid': cid},
            type: 'post',
            dataType: 'json',
            success: function (msg) {
                if (msg.code == '0000') {
                    $('#okBox p.promptNews').html('课程关联学习任务，不可删除');
                    fnShow("#okBox", "fadeOutUp", "fadeInDown");
                    setTimeout(function () {
                        fnHide("#okBox", "fadeInDown", "fadeOutUp");
                    }, 2000);
                }
                else {
                    $('#delOk').attr('code', cid);
                    fnShow("#one_del", "fadeOutUp", "fadeInDown");
                }
            }
        });

    });

    //确定删除单记录
    $('#delOk').click(function(){
        var cid = $(this).attr('code');

        fnHide("#one_del","fadeInDown","fadeOutUp");
        $.ajax({
            url:site_url+"/Adminsubject/delbook",
            type:'post',
            data:{'codes':cid},
            dataType:'json',
            success:function(message){

                if(message.code=='0000'){

                    fnShow("#okBox","fadeOutUp","fadeInDown");
                    $('#okBox p.promptNews').html('删除成功');
                    setTimeout("location.reload()",1000);
                }else{

                    $('#okBox p.promptNews').html('删除失败');
                    fnShow("#okBox","fadeOutUp","fadeInDown");
                    setTimeout("location.reload()",1000);
                }
            }

        })
    });

    //显示新增章
    $('#addcha').click(function () {
        $('.adderrormsg').html('')
        fnShow("#addchaBox","fadeOutUp","fadeInDown");
    });

    //确认新增章
    $('#addchaBox #ok').click(function () {
        var chaname = $.trim($('#chaname').val());
        var chadesc = $.trim($('#chadesc').val());
        
        if(chaname == '')
        {
            $('#addchaBox .adderrormsg').text('章名称不能为空');
            return false;
        }

        if(chadesc == '')
        {
            $('#addchaBox .adderrormsg').text('章描述不能为空');
            return false;
        }

        $.ajax({
            url:site_url+"/Adminsubject/addchapter",
            type:'post',
            data:{'cid': cid, 'name': chaname, 'desc': chadesc},
            dataType:'json',
            success:function(message){

                if(message.code=='0000'){

                    $('#addchaBox .adderrormsg').html('新增章成功');
                    setTimeout("location.reload()",2000);
                }else{
                    $('#addchaBox .adderrormsg').html('章名称已存在');
                }
            }

        });
    });
    
    //显示编辑章
    $('.editcha').click(function () {
        $('#modchaBox #name').val($(this).attr('name'));
        $('#modchaBox #desc').val($(this).attr('desc'));
        $(".adderrormsg").html('');
        $('#modchaBox').attr('chaid', $(this).parent().attr('chaid'));
        fnShow("#modchaBox","fadeOutUp","fadeInDown");
    });

    //确认编辑章
    $('#modchaBox #ok').click(function () {
        var chaid = $('#modchaBox').attr('chaid');
        var name = $.trim($('#modchaBox #name').val());
        var desc = $.trim($('#modchaBox #desc').val());

        if(name == '')
        {
            $('#modchaBox .adderrormsg').text('章名称不能为空');
            return false;
        }
        if(desc == '')
        {
            $('#modchaBox .adderrormsg').text('章描述不能为空');
            return false;
        }

        $.ajax({
            url:site_url+"/Adminsubject/modchapter",
            type:'post',
            data:{'chaid': chaid, 'name': name, 'desc': desc},
            dataType:'json',
            success:function(message){
                if(message.code=='0000'){

                    $('#modchaBox .adderrormsg').html('编辑章成功');
                    setTimeout("location.reload()",2000);
                }
            }

        });
    });

    //显示删除章
    $('.delcha').click(function () {
        $('#delchaBox').attr('chaid', $(this).parent().attr('chaid'));
        fnShow("#delchaBox","fadeOutUp","fadeInDown");
    });

    //确认删除章
    $('#delchaBox #ok').click(function () {
        var chaid = $('#delchaBox').attr('chaid');

        $.ajax({
            url:site_url+"/Adminsubject/delchapter",
            type:'post',
            data:{'chaid': chaid},
            dataType:'json',
            success:function(message){
                if(message.code=='0000'){

                    window.location.reload();
                }
            }

        });
    });

    //显示新增单元
    $('.adduni').click(function () {
        $('.adderrormsg').html('');
        $('#adduniBox').attr('chaid', $(this).parent().attr('chaid'));
        fnShow("#adduniBox","fadeOutUp","fadeInDown");
    });

    //确认新增单元
    $('#adduniBox #ok').click(function () {
        var chaid = $('#adduniBox').attr('chaid');
        var name = $.trim($('#adduniBox #name').val());
        var desc = $.trim($('#adduniBox #desc').val());

        if(name == '')
        {
            $('#adduniBox .adderrormsg').text('单元名称不能为空');
            return false;
        }
        if(desc == '')
        {
            $('#adduniBox .adderrormsg').text('单元描述不能为空');
            return false;
        }

        $.ajax({
            url:site_url+"/Adminsubject/addunit",
            type:'post',
            data:{'chaid': chaid, 'name': name, 'desc': desc},
            dataType:'json',
            success:function(message){
                if(message.code=='0000'){

                    $('#adduniBox .adderrormsg').html('新增单元成功');
                    setTimeout("location.reload()",2000);
                }else{
                    $('#adduniBox .adderrormsg').html('单元名称已存在');
                }
            }

        });

    });

    //显示编辑单元
    $('.edituni').click(function () {
        $('#moduniBox #name').val($(this).attr('name'));
        $('#moduniBox #desc').val($(this).attr('desc'));
        $(".adderrormsg").html('');
        $('#moduniBox').attr('uniid', $(this).parent().attr('uniid'));
        fnShow("#moduniBox","fadeOutUp","fadeInDown");
    });

    //确认编辑单元
    $('#moduniBox #ok').click(function () {
        var uniid = $('#moduniBox').attr('uniid');
        var name = $.trim($('#moduniBox #name').val());
        var desc = $.trim($('#moduniBox #desc').val());

        if(name == '')
        {
            $('#moduniBox .adderrormsg').text('单元名称不能为空');
            return false;
        }
        if(desc == '')
        {
            $('#moduniBox .adderrormsg').text('单元描述不能为空');
            return false;
        }

        $.ajax({
            url:site_url+"/Adminsubject/modunit",
            type:'post',
            data:{'uniid': uniid, 'name': name, 'desc': desc},
            dataType:'json',
            success:function(message){
                if(message.code=='0000'){

                    $('#moduniBox .adderrormsg').html('编辑成功');
                    setTimeout("location.reload()",2000);
                }
            }

        });
    });

    //显示删除单元
    $('.deluni').click(function () {
        $('#deluniBox').attr('uniid', $(this).parent().attr('uniid'));
        fnShow("#deluniBox","fadeOutUp","fadeInDown");
    });

    //确认删除单元
    $('#deluniBox #ok').click(function () {
        var uniid = $('#deluniBox').attr('uniid');

        $.ajax({
            url:site_url+"/Adminsubject/delunit",
            type:'post',
            data:{'uniid': uniid},
            dataType:'json',
            success:function(message){
                if(message.code=='0000'){

                    window.location.reload();
                }
            }

        });
    });

    //显示删除小节
    $('.delsec').click(function () {
        $('#delsecBox').attr('secid', $(this).parent().attr('secid'));
        $('#delsecBox').attr('sectype', $(this).parent().attr('sectype'));
        fnShow("#delsecBox","fadeOutUp","fadeInDown");
    });

    //确认删除小节
    $('#delsecBox #ok').click(function () {
        var secid = $('#delsecBox').attr('secid');
        var sectype = $('#delsecBox').attr('sectype');

        $.ajax({
            url:site_url+"/Adminsubject/delsection",
            type:'post',
            data:{'secid': secid, 'sectype': sectype, 'cid': cid},
            dataType:'json',
            success:function(message){
                if(message.code=='0000'){

                    window.location.reload();
                }
            }

        });
    });

    //新增课程--重置按钮
    $(".disuploadBtn").click(function(e){
        // var value = $('#PackageImg').val();
        // if(value!=''){
        //         $('#PackageImg').val('')
        //         $('.showPic img').attr('src',base_url+"resources/files/picture/logo.png");
        //         $('.showPic').addClass("showNoPic")
         
        // }
        $('.showPic').addClass("showNoPic")
         $('#PackageImg').val('');
        $('#preview').html('<img src= "'+base_url+'resources/files/img/course/logo.png" />');


    });

    //编辑课程，查看是否正在学习
    $('.editcourse').click(function () {
        var cid = $(this).attr('code');

        $.ajax({
            url: site_url+'/Adminsubject/isstudy',
            data : {'cid':cid},
            type : 'post',
            dataType : 'json',
            success: function (msg) {
                if(msg.code == '0000')
                {
                    $('#okBox p.promptNews').html('有正在学习的学生');
                    fnShow("#okBox","fadeOutUp","fadeInDown");
                    setTimeout(function(){
                        fnHide("#okBox","fadeInDown","fadeOutUp");
                    },2000);
                }
                else
                {
                    window.location.href = site_url+"/Adminsubject/editbook/cid/"+cid;
                }
            }
        });

    });

    
});


//引用
function quotelist(cid){
    //alert(pid);return;
    $.ajax({
        url:site_url+'/Adminsubject/quote_list',
        type:'post',
        data:{cid:cid},
        dataType:'json',
        success:function(message){
            $('#quotelist').html('');
            if(message.code=='0000'){
                var str = ''
                for(i in message.data){
                    str +='<tr><td>'+message.data[i]['PackageName']+'</td><td>'+message.data[i]['ArchitectureName']+'</td></tr>';
                }
                $('#quotelist').html(''+str+'');
                fnShow("#quoteBox","fadeOutUp","fadeInDown");

            }
        }
    })
}


