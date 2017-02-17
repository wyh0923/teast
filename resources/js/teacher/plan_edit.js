/**
 * Created by liuqi on 2016/8/30.
 */


$(function () {

    $(".clsearch").click(function(){
        sapGetData(site_url+'Subject/ajax_course', sapSuc, "pageContainer");
    });

    $('.ensearch').keydown(function(e){
        if(e.keyCode==13){
            sapGetData(site_url+'Subject/ajax_course', sapSuc, "pageContainer");

        }
    });

    //显示新增课程体系
    $('#addsys').click(function () {
        var cid = $(this).parent().attr('code');
        $('#addsBox').attr('code', cid);
        fnShow("#addsBox","fadeOutUp","fadeInDown");
    });

    //新增课程体系
    $('#addsBox #addOk').click(function () {
        var pid = $('#addsBox').attr('code');
        var name = $.trim($('#addsBox #name').val());

        if(name == '')
        {
            $('#adderrormsg').text('体系名称不能为空');
            return false;
        }

        $.ajax({
            url: site_url+ 'Subject/addsys',
            type: 'post',
            dataType: 'json',
            data: {name: name, pid: pid},
            success: function (msg) {
                if(msg.code == '0000')
                {
                    $('#adderrormsg').text('新增体系成功');
                    setTimeout("window.location.reload()", 1000);

                }else{
                    $('#adderrormsg').text('体系名称已存在');
                }
            },
        });
    });

    //显示编辑课程体系
    $('#editsys').click(function () {
        var cid = $(this).parent().attr('code');
        $('#modsys').attr('code', cid);
        fnShow("#modsys","fadeOutUp","fadeInDown");
    });
    //编辑课程体系
    $('#modsys #ok').click(function () {
        var pid = $('#modsys').attr('code');
        var name = $.trim($('#modsys .iptext').val());

        if(name == '')
        {
            $('#modsys .adderrormsg').text('方案名称不能为空');
            return false;
        }

        $.ajax({
            url: site_url+ 'Subject/modplan',
            type: 'post',
            dataType: 'json',
            data: {name: name, pid: pid},
            success: function (msg) {
                if(msg.code == '0000')
                {
                    $('#modsys .adderrormsg').text('编辑方案名称成功');
                    setTimeout("window.location.reload()", 2000);

                }else{
                    $('#modsys .adderrormsg').text('方案名称已存在');
                }
            },
        });
    });

    //显示选择课程
    $('.selcour').click(function () {
        var aid = $(this).parent().attr('code');
        $('#selcourse').attr('code', aid);
        $(".iptSearch-a").val('')
        sapGetData(site_url+'Subject/all_course', sapSuc, "pageContainer");
        fnShow("#selcourse","fadeOutUp","fadeInDown");
    });

    //编辑选择课程
    $('#selcourse #ok').click(function () {
        var aid = $('#selcourse').attr('code');
        var cid = [];
        $('#tbody input:checked').each(function () {
              return cid.push($(this).val());
        });
        // console.log(cid);return;

        if(cid.length == 0)
        {
            fnHide("#selcourse","fadeInDown","fadeOutUp");
        }

        $.ajax({
            url: site_url+ 'Subject/optcourse',
            type: 'post',
            dataType: 'json',
            data: {aid: aid, cid: cid},
            success: function (msg) {

                fnHide("#selcourse","fadeInDown","fadeOutUp");
                window.location.reload();
            },
        });

    });

    //显示所属方案
    $('.modsys').click(function () {
        var aid = $(this).parent().attr('code');
        var aname = $(this).parent().attr('name');
        $('#editsBox').attr('code', aid);
        $('#sysname').val(aname);
        fnShow("#editsBox","fadeOutUp","fadeInDown");
    });

    //编辑所属方案
    $('#editsBox #ok').click(function () {
        var aname = $.trim($('#sysname').val());
        var pid = $('#onearchite option:selected').val();
        var aid = $('#editsBox').attr('code');
        
        if(aname == '')
        {
            $('#editsBox .adderrormsg').text('体系名称不能为空');
            return false;
        }

        $.ajax({
            url: site_url+ 'Subject/modsysname',
            type: 'post',
            dataType: 'json',
            data: {aid: aid, pid: pid, aname: aname},
            success: function (msg) {
                if(msg.code == '0000')
                {
                    $('#editsBox .adderrormsg').text('编辑成功');
                    setTimeout("window.location.reload()", 2000);
                }else {
                    $('#editsBox .adderrormsg').text('未修改');
                    setTimeout("window.location.reload()", 2000);
                }
            },
        });

    });

    //显示删除体系
    $('.delsys').click(function () {
        var aid = $(this).parent().attr('code');
        $('#delsys').attr('code', aid);

        fnShow("#delsys","fadeOutUp","fadeInDown");
    });

    //删除体系
    $('#delsys #ok').click(function () {
        var aid = $('#delsys').attr('code');
        // alert(aid);return;

        $.ajax({
            url: site_url+ 'Subject/delsys',
            type: 'post',
            dataType: 'json',
            data: {aid: aid},
            success: function (msg) {
                if(msg.code == '0000')
                {
                    fnHide("#delsys","fadeInDown","fadeOutUp");
                    window.location.reload();
                }
            },
        });

    });

    //显示删除课程
    $('.delcourse').click(function () {
        var aid = $(this).parent().attr('code');
        $('#delcourse').attr('code', aid);

        fnShow("#delcourse","fadeOutUp","fadeInDown");
    });

    //删除课程
    $('#delcourse #ok').click(function () {
        var aid = $('#delcourse').attr('code');
        // alert(aid);return;

        $.ajax({
            url: site_url+ 'Subject/delcourse',
            type: 'post',
            dataType: 'json',
            data: {cid: aid},
            success: function (msg) {
                if(msg.code == '0000')
                {
                    fnHide("#delcourse","fadeInDown","fadeOutUp");
                    window.location.reload();
                }
            },
        });

    });

    $("#SortForDiff").click(function(){
        if ($(this).find("i").hasClass("fa-sort"))
        {
            $(this).find("i").removeClass("fa-sort");
            $(this).find("i").addClass("fa-sort-amount-desc");
            $('.memorycur.filterCur').attr('type', 'fa-sort-amount-desc');
            $('.diskcur.filterCur').attr('type', '');
            //alert($('.memorycur.filterCur').attr('type'));
        }
        else if($(this).find("i").hasClass("fa-sort-amount-desc"))
        {
            $(this).find("i").removeClass("fa-sort-amount-desc");
            $(this).find("i").addClass("fa-sort-amount-asc");
            $('.memorycur.filterCur').attr('type', 'fa-sort-amount-asc');
            $('.diskcur.filterCur').attr('type', '');
            //alert($('.memorycur.filterCur').attr('type'));
        }
        else
        {
            $(this).find("i").removeClass("fa-sort-amount-asc");
            $(this).find("i").addClass("fa-sort-amount-desc");
            $('.memorycur.filterCur').attr('type', 'fa-sort-amount-desc');
            $('.diskcur.filterCur').attr('type', '');
            //alert($('.memorycur.filterCur').attr('type'));

        }
        sapGetData(site_url+'Subject/ajax_course', sapSuc, "pageContainer");

    });

    $("#SortForTime").click(function(){
        if ($(this).find("i").hasClass("fa-sort"))
        {
            $(this).find("i").removeClass("fa-sort");
            $(this).find("i").addClass("fa-sort-amount-desc");
            $('.diskcur.filterCur').attr('type', 'fa-sort-amount-desc');
            $('.memorycur.filterCur').attr('type', '');
            //alert($('.diskcur.filterCur').attr('type'));
        }
        else if($(this).find("i").hasClass("fa-sort-amount-desc"))
        {
            $(this).find("i").removeClass("fa-sort-amount-desc");
            $(this).find("i").addClass("fa-sort-amount-asc");
            $('.diskcur.filterCur').attr('type', 'fa-sort-amount-asc');
            $('.memorycur.filterCur').attr('type', '');
            //alert($('.diskcur.filterCur').attr('type'));
        }
        else
        {
            $(this).find("i").removeClass("fa-sort-amount-asc");
            $(this).find("i").addClass("fa-sort-amount-desc");
            $('.diskcur.filterCur').attr('type', 'fa-sort-amount-desc');
            $('.memorycur.filterCur').attr('type', '');
            //alert($('.diskcur.filterCur').attr('type'));
        }
        sapGetData(site_url+'Subject/ajax_course', sapSuc, "pageContainer");

    });

    
});

function planFilterBtnClk(item)
{
    $(".planFilterBtn").each(function(){
        $(this).removeClass("filterCur");
    });
    $("#"+item).addClass("filterCur");

    getSysFilter($("#"+item).attr("value"));
    sapGetData(site_url+'Subject/ajax_course', sapSuc, "pageContainer");

}

function sysFilterBtnClk(item)
{
    $(".sysFilterBtn").each(function(){
        $(this).removeClass("filterCur");
    });
    $("#"+item).addClass("filterCur");

    sapGetData(site_url+'Subject/ajax_course', sapSuc, "pageContainer");

}

function typeBtn(item)
{
    $(".typeFilterBtn").each(function(){
        $(this).removeClass("filterCur");
    });
    $("#"+item).addClass("filterCur");

    sapGetData(site_url+'Subject/ajax_course', sapSuc, "pageContainer");

}

function authorItem(item)
{
    $(".authorFilterBtn").each(function(){
        $(this).removeClass("filterCur");
    });
    $("#"+item).addClass("filterCur");

    sapGetData(site_url+'Subject/ajax_course', sapSuc, "pageContainer");

}



function getSysFilter(pid)
{
    data = null;
    if(pid != null)
    {
        data = {"pid":pid};
    }
    $.ajax({
        type:"post",
        url:site_url+"Subject/ajaxSysFilter",
        data:data,
        dataType:"json",
        success:function(msg){
            // console.log(msg);
            $(".sysFilterBtn").each(function(){
                if($(this).attr("value")!="")
                {
                    $(this).remove();
                }
            });

            if((msg != null)&&(msg.length>0))
            {
                for(var i =0;i<msg.length;i++)
                    $("#sysFilterPlaceHolder").before('<a href="javascript:void(0)" id="sysFilterItem_'+msg[i]['ArchitectureID']+'" onclick="sysFilterBtnClk(\'sysFilterItem_'+msg[i]['ArchitectureID']+'\')" os="'+msg[i]['ArchitectureID']+'" value="'+msg[i]['ArchitectureID']+'" class="sysFilterBtn ostypekur">'+msg[i]['ArchitectureName']+'</a> ');
            }

            $(".sysFilterBtn").each(function(){
                $(this).removeClass("filterCur");
            });
            $("#sysFilterItem_all").addClass("filterCur");
        }
    });
}

//所有课程
function sapSuc(data) {
    // console.log(data['count']);
    if(data.length == 0){
        $("#tbody").hide();
        $("#selcourse").find(".noNews").show();

    } else {
        var courtxt = '';
        $.each(data,function(i,v){
            courtxt += '<tr>';
            courtxt += '<td class=""><input type="checkbox" name="courid[]" value="'+v['PackageID']+'"></td>';
            courtxt += '<td>'+v['PackageName']+'</td>';
            courtxt += '<td>'+v['PackageAuthor']+'</td>';
            courtxt += '<td>'+v['SectionNum']+'</td>';
            courtxt += '</tr>';
        });
        $('#tbody').html('');
        $('#tbody').append(courtxt);
        $("#tbody").show();
        $("#selcourse").find(".noNews").hide();
    }
}

