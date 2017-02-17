/**
 * Created by liuqi on 2016/9/7.
 */

$(function(){
    //label中字体颜色变化

    $(".addItem label a").click(function(){
        $(this).addClass("curForBlue").parent().siblings().children().removeClass("curForBlue")
    });
    //不关联场景
    $("#noCJbtn").click(function(){
        $("#ctfOrSec").addClass("outHide");
        $("#ctfOrSec input").val('')
    })
    //弹窗
    $("#ctfBtn").click(function(){
        $(".goSearch input").val('');

        sapGetData(site_url+'Subject/ctflist', ctfLists, "ctfPage");
         setPosi("#ctfListBox");//由于是异步ajax取数据，会造成对弹框的定位出现高度问题，所以等待加载结束重新对弹框定位
        fnShow("#ctfListBox","fadeOutUp","fadeInDown");

    });

    $(".ctfSearch").click(function(){
        sapGetData(site_url+'Subject/ctflist', ctfLists, "ctfPage");
    });

    $('#sapSearch_ctfPage').keydown(function(e){
        if(e.keyCode==13){
            sapGetData(site_url+'Subject/ctflist', ctfLists, "ctfPage");
        }
    });
    
    //场景
    $("#secnBtn").click(function(){
        $("#sapSearch_secePage").val('')
        sapGetData(site_url+'Subject/scenelist', seceLists, "secePage");
        setPosi("#scenListBox");//由于是异步ajax取数据，会造成对弹框的定位出现高度问题，所以等待加载结束重新对弹框定位
        fnShow("#scenListBox","fadeOutUp","fadeInDown");

    })

    $(".sceneSearch").click(function(){
        sapGetData(site_url+'Subject/scenelist',seceLists,"secePage");
    });

    $('#sapSearch_secePage').keydown(function(e){
        if(e.keyCode==13){
            sapGetData(site_url+'Subject/scenelist',seceLists,"secePage");
        }
    });
    
    //题目类型切换
    $("#questiontype label a").click(function(){
        var thisId = $(this).parent().index();
        // console.log(thisId);
        if(thisId>2){
            $("#answertitle").html("答案：")
        }
        else{
            $("#answertitle").html("选项：")
        }

        var showId = $("#xuantiBox").children().eq(thisId-1);
        // console.log(showId);
        showId.removeClass("outHide").siblings().addClass('outHide')

    })
    //上传插件
    var timestamp = Date.parse(new Date());

    var upadd = $('#adduploadIcon').Huploadify({
        formData: {key: timestamp, key2: ''},
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
        buttonText: '上传资源',
        uploader: site_url + 'Subject/accessory',//服务器端脚本文件路径
        onUploadComplete: function (messfileObj, info, responseage) {

            var data = JSON.parse(info);

            if(data.success == true){

            var url = "../../resources/files/question/" + data.filename;
            // var url2 ='"!["+data.filename+"]("+url+")"'
             var url2 ="!["+messfileObj.name+"]("+url+")"
            var content = '';
            content += '<tr urla = "../../resources/files/question/'+data.filename+'" namea = "'+messfileObj.name+'"><td class="resourcename">' + messfileObj.name + '</td><td class="resourceurl" ><a  id=img' + messfileObj.lastModified + ' href="javascript:;"  >' + url2 + '</a>' + url + '</td><td><a href="javascript:;" class="btncopy forRed" code=' + url + ' data-clipboard-action="copy" data-clipboard-target=#img' + messfileObj.lastModified + '><i class="fa fa-copy"  ></i>复制</a><a href="javascript:;" onclick="delres(this)" class="forRed"><i class="fa fa-trash-o"></i>删除</a></td></tr>';
            $('#tableneirong').append(content);
            }

        },
        onUploadStart: function (file) {//上传开始时触发（每个文件触发一次）
            $(".file_info_show_box").val('');//此处为临时解决自动清除url问题
            var timestamp = Date.parse(new Date());
            var updatetype = "";
            upadd.settings("formData", {key: timestamp, key2: updatetype});
        }
    });

    //复制与黏贴
    var clipboard = new Clipboard('.btncopy');
    clipboard.on('success', function(e) {

        alert("复制成功");
        e.clearSelection();
    });

    clipboard.on('error', function(e) {
        console.error('Action:', e.action);
        console.error('Trigger:', e.trigger);curForBlue
    });
    //点击关闭输入题干的输入方式提醒
    $(".daoNews i").click(function(){
        $(".daoNews").slideUp();
    })
    //点击打开输入题干的输入方式提醒
    $(".newStarBtn").click(function(){
        $(".daoNews").slideDown();
    })
    //点击保存新增
    $("#saveQueList").click(function(){
        var type = $("#questiontype .curForBlue").attr("type");//获取题目类型----1单选题；2多选题，3判断题，4填空题，5夺旗题
        // console.log(type);
        var grade = $("#timunandu .curForBlue").text();//获取题目难度等级
        var xuangXiang = new Array();//选项值
        var choose = '';//选项值
        var answer = ''//正确答案
        var tigan = $.trim($("#PackageName").val());
        var biaozhi = 1;
        var chongfu = 1 ;//用来判断单选和多选选项是否有重复
        if(grade == '初级'){ grade = '0';  }
        if(grade == '中级'){ grade = '1';  }
        if(grade == '高级'){ grade = '2';  }
        //判断题干是否存在
        if (tigan.match(/^\s*$/) != null) {
            $('#adderrormsg').html('题干不能为空');
            return false;
        }
        if(type==1){//单选题

            $('input[name=danxuan]').each(function () {
                var danAnser = $.trim($(this).val());
                if(danAnser == ''){
                    // $('#adderrormsg').html('请输入单选选项值');
                    biaozhi = 2;
                    return false;
                }else{
                    if(jQuery.inArray(danAnser,xuangXiang) == -1){
                       xuangXiang.push(danAnser);
                       choose = xuangXiang.join("|||"); 
                    }
                    else{
                       //判断重复答案
                        chongfu = 2;
                        return false;
                    }
                    
                    

                }
            });

            if(biaozhi == 2){
                $('#adderrormsg').html('请输入单选选项值');
                return false;

            }
            if(chongfu==2){
                 $('#adderrormsg').html('选项答案不可重复');
                 return false;
            }
            //单选题至少两个选项
            if (xuangXiang.length < 2) {
                $('#adderrormsg').html('单选题至少两个选项');
                $('#adderrormsg').css('display', 'block');
                return false;
            }
            answer = $.trim($('input[name=radiodan]:checked').next().val());
            //判断是否有正确答案
            if ($('input[name=radiodan]').is(':checked')) {
                if (answer == "") {
                    $('#adderrormsg').html('请输入正确答案');
                    return false;
                }

            } else {
                $('#adderrormsg').html('请选中正确答案');
                return false;
            }

            $('#adderrormsg').html('')
            // type ='单选题';
            //alert(type);
            // alert(answer)

        }//单选结束

        if(type==2){//多选题
            //duoarr 多选题的所有选项值
            //var duoarr=new Array();
            $('input[name=duoxuan]').each(function () {
                var duoAnser = $.trim($(this).val())
                if(duoAnser==''){
                    biaozhi = 2;
                    return false;
                } else {
                    if(jQuery.inArray(duoAnser,xuangXiang) == -1){
                       xuangXiang.push(duoAnser);
                       choose = xuangXiang.join("|||"); 
                    }
                    else{
                       //判断重复答案
                        chongfu = 2;
                        return false;
                    }
                   
                }
            });
          
            if(biaozhi == 2){
                $('#adderrormsg').html('请输入多选选项值');
                return false;

            }
             if(chongfu==2){
                 $('#adderrormsg').html('选项答案不可重复');
                 return false;
                        }
            if (xuangXiang == "") {
                $('#adderrormsg').html('请输入多选的选项值');
                return false;
            }

            //duoansArr   多选题的正确答案
            //duoanString  将多选题的答案分割成aaa|||bbb的形式的字符串
            var duoansArr = [];

            duoansArr = []//每次统计之前清空
            $("input[name=checkboxduo]").each(function () {
                var duoZ = $.trim($(this).siblings("input[name=duoxuan]").val())
                if ($(this).is(":checked")) {
                    duoansArr.push(duoZ);
                }

            })
            if (!($('input[name=checkboxduo]').is(':checked'))) {
                $('#adderrormsg').html('请选中正确答案');
                return false;
            }

            if (duoansArr.length<1) {
                $('#adderrormsg').html('请输入正确答案');

                return false;
            }
            
            //判断多选的答案如果只有一个的时候
            if (duoansArr.length < 2) {
                $('#adderrormsg').html('多选题至少选择两个正确答案');
                return false;
            }

            if (duoansArr.length >= 2) {
                answer = duoansArr.join("|||");
            }
            $('#adderrormsg').html('');
            // type ='多选题';
            //alert(type);
            // alert("2多选题")

        }//多选结束

        if(type==3){//判断题
            answer = $('input[name=radiopanduan]:checked').val();
            if(!answer){
                $('#adderrormsg').html('请选中判断题目的正确答案');
                return false;

            }
            $('#adderrormsg').html('');
            // type ='判断题'
            //alert("3判断题")

        }
        //判断题结束
        if(type==4){//填空题
            var tiankongarr = new Array();
            $('input[name=tiankong]').each(function () {
                var daan = $.trim($(this).val());
                if(daan=='')
                {
                    $('#adderrormsg').html('请输入答案');
                    return false;
                }
                tiankongarr.push(daan);
            });
            if (tiankongarr.length<1) {
                $('#adderrormsg').html('请输入答案');
                return false;
            }
            answer = tiankongarr.join("|||");
            $('#adderrormsg').html('');
            // type ='填空题'
            // alert("4填空题")

        }
        //填空题结束
        if(type==5){//夺旗题
            var changjing = $('#changjingcode').val();
            if(changjing == ''){
                $('#adderrormsg').html('请选择关联CTF场景或关联实验场景');
                return false;
            }

            answer = $.trim($('input[name=flag]').val());

            if (answer == "") {
                $('#adderrormsg').html('请输入答案');
                return false;
            }
            $('#adderrormsg').html('');
            // type ='夺旗题'

            // alert("5夺旗题")

        }

        var linktype = $("#relationscene").find(".curForBlue").attr("typect")//获取所关联的场景类型
        var link = $("#changjingcode").val();//获取具体场景信息
        if(linktype==1&&$("#changjingname").val()==''){
          $('#adderrormsg').html('请选择关联一个ctf场景或者将题目不关联场景');
          return false;
        }
        if(linktype==2&&$("#changjingname").val()==''){
          $('#adderrormsg').html('请选择关联一个实验场景或者将题目不关联场景');
          return false;
        }
        // var resname = $('#uploadfile').val();
        // var url = "resources/files/tool/" + resname;
        //获取附件列表信息
        var ulrArr = new Array();
        var nameArr = new Array();
        $('#tableneirong tr').each(function () {
            var urla = $(this).attr("urla");//附件地址
            var namea =$(this).attr("namea");//附件名字
            ulrArr.push(urla);
            nameArr.push(namea);

        })

        $.ajax({
            url: site_url + "Subject/doaddquestion",
            type: 'post',
            data: {
                'QuestionDesc': tigan,
                'QuestionType': type,
                'QuestionDiff': grade,
                'QuestionAnswer': answer,
                'choosearray': choose,
                'QuestionLink': link,
                'ResourceUrl': ulrArr,
                'ResourceName': nameArr,
                'QuestionLinkType': linktype
                //'QuestionClassCode': quesClassCode
            },
            dataType: 'json',
            success: function (message) {
                //console.log("true");
                if(message.code != '0000'){
                    $('#adderrormsg').html('已存在相同类型，相同题干的题目');
                }else{
                    $('#adderrormsg').html('保存成功');
                    setTimeout(function(){
                        window.location.href = site_url + 'Subject/questionlist';
                    },  1000);
                }

            }
        })

    })

    //点击保存编辑
    $("#editQueList").click(function(){
        var type = $("#questiontype .curForBlue").attr("type");//获取题目类型----1单选题；2多选题，3判断题，4填空题，5夺旗题
        // console.log(type);
        var grade = $("#timunandu .curForBlue").text();//获取题目难度等级
        var xuangXiang = new Array();//选项值
        var choose = '';//选项值
        var answer = ''//正确答案
        var tigan = $.trim($("#PackageName").val());
        var chongfu = 1;
        var biaozhi = 1;
        if(grade == '初级'){ grade = '0';  }
        if(grade == '中级'){ grade = '1';  }
        if(grade == '高级'){ grade = '2';  }
        //判断题干是否存在
        if (tigan.match(/^\s*$/) != null) {
            $('#adderrormsg').html('题干不能为空');
            return false;
        }
        if(type==1){//单选题

            $('input[name=danxuan]').each(function () {
                var danAnser = $.trim($(this).val())
                if(danAnser==''){
                    biaozhi = 2;
                    return false;
                } else {
                    if(jQuery.inArray(danAnser,xuangXiang) == -1){
                       xuangXiang.push(danAnser);
                       choose = xuangXiang.join("|||"); 
                    }
                    else{
                       //判断重复答案
                        chongfu = 2;
                        return false;
                    }

                }

            });

            if(biaozhi == 2){
                $('#adderrormsg').html('请输入多选选项值');
                return false;

            }
            if(chongfu == 2){
                $('#adderrormsg').html('选项答案不可重复');
                    return false;

            }
            //单选题至少两个选项
            if (xuangXiang.length < 2) {
                $('#adderrormsg').html('单选题至少两个选项');
                $('#adderrormsg').css('display', 'block');
                return false;
            }
            //判断重复答案
            
                    
          
            answer = $.trim($('input[name=radiodan]:checked').next().val());
            //判断是否有正确答案
            if ($('input[name=radiodan]').is(':checked')) {
                if (answer == "") {
                    $('#adderrormsg').html('请输入正确答案');
                    return false;
                }

            } else {
                $('#adderrormsg').html('请选中正确答案');
                return false;
            }

            $('#adderrormsg').html('')
            // type ='单选题';
            //alert(type);
            // alert(answer)

        }
        //单选结束
        //多选题
        if(type==2){
            //duoarr 多选题的所有选项值
            //var duoarr=new Array();
            $('input[name=duoxuan]').each(function () {
                var duoAnser =$.trim($(this).val()) 
                if(duoAnser==''){
                    biaozhi = 2;
                    return false;
                } else {
                    if(jQuery.inArray(duoAnser,xuangXiang) == -1){
                       xuangXiang.push(duoAnser);
                       choose = xuangXiang.join("|||"); 
                    }
                    else{
                       //判断重复答案
                        chongfu = 2;
                        return false;
                    }
                }

            });

            if(biaozhi == 2){
                $('#adderrormsg').html('请输入多选选项值');
                return false;

            }
            if(chongfu==2){
                $('#adderrormsg').html('选项答案不可重复');
                    return false;
            }

            if (xuangXiang == "") {
                $('#adderrormsg').html('请输入多选的选项值');
                return false;
            }

            //duoansArr   多选题的正确答案
            //duoanString  将多选题的答案分割成aaa|||bbb的形式的字符串
            var duoansArr = [];

            duoansArr = []//每次统计之前清空
            $("input[name=checkboxduo]").each(function () {
                var duoZ = $.trim($(this).siblings("input[name=duoxuan]").val())
                if ($(this).is(":checked")) {
                    duoansArr.push(duoZ);
                }

            })
            if (!($('input[name=checkboxduo]').is(':checked'))) {
                $('#adderrormsg').html('请选中正确答案');
                return false;
            }

            if (duoansArr.length<1) {
                $('#adderrormsg').html('请输入正确答案');

                return false;
            }
           
                    
              
           
            //判断多选的答案如果只有一个的时候
            if (duoansArr.length < 2) {
                $('#adderrormsg').html('多选题至少选择两个正确答案');
                return false;
            }
            if (duoansArr.length > 1) {
                answer = duoansArr.join("|||");
            }
            $('#adderrormsg').html('');
            // type ='多选题';
            //alert(type);
            // alert("2多选题")

        }
        //多选结束
        //判断题
        if(type==3){
            answer = $('input[name=radiopanduan]:checked').val();
            if(!answer){
                $('#adderrormsg').html('请选中判断题目的正确答案');
                return false;

            }
            $('#adderrormsg').html('');
            // type ='判断题'
            //alert("3判断题")

        }
        //判断题结束
        //填空题
        if(type==4){
            var tiankongarr = new Array();
            $('input[name=tiankong]').each(function () {
                var daan = $.trim($(this).val());
                if(daan=='')
                {
                    $('#adderrormsg').html('请输入答案');
                    return false;
                }
                tiankongarr.push(daan);
            });
            if (tiankongarr.length<1) {
                $('#adderrormsg').html('请输入答案');
                return false;
            }
            answer = tiankongarr.join("|||");
            $('#adderrormsg').html('');
            // type ='填空题'
            // alert("4填空题")

        }
        //填空题结束
        //夺旗题
        if(type==5){
            var changjing = $('#changjingcode').val();
            if(changjing == ''){
                $('#adderrormsg').html('请选择关联CTF场景或关联实验场景');
                return false;
            }

            answer = $.trim($('input[name=flag]').val());

            if (answer == "") {
                $('#adderrormsg').html('请输入答案');
                return false;
            }
            $('#adderrormsg').html('');
            // type ='夺旗题'

            // alert("5夺旗题")

        }

        var linktype = $("#relationscene").find(".curForBlue").attr("typect")//获取所关联的场景类型
        var link = $("#changjingcode").val();//获取具体场景信息
        if(linktype==1&&$("#changjingname").val()==''){
          $('#adderrormsg').html('请选择关联一个ctf场景或者将题目不关联场景');
          return false;
        }
        if(linktype==2&&$("#changjingname").val()==''){
          $('#adderrormsg').html('请选择关联一个实验场景或者将题目不关联场景');
          return false;
        }
        // var resname = $('#uploadfile').val();
        // var url = $('#uploadfileurl').val();;
        var ulrArr = new Array();
        var nameArr = new Array();
        $('#tableneirong tr').each(function () {
            var urla = $(this).attr("urla");//附件地址
            var namea =$(this).attr("namea");//附件名字
            ulrArr.push(urla);
            nameArr.push(namea);

        })

        $.ajax({
            url: site_url + "Subject/modquestion",
            type: 'post',
            data: {
                'qid': qid,
                'QuestionDesc': tigan,
                'QuestionType': type,
                'QuestionDiff': grade,
                'QuestionAnswer': answer,
                'choosearray': choose,
                'QuestionLink': link,
                'ResourceUrl': ulrArr,
                'ResourceName': nameArr,
                'QuestionLinkType': linktype
                //'QuestionClassCode': quesClassCode
            },
            dataType: 'json',
            success: function (message) {
                //console.log("true");
                if(message.code != '0000'){
                    $('#adderrormsg').html('保存成功');
                    setTimeout(function(){
                        window.location.href = site_url + 'Subject/questionlist';
                    },  1000);
                }else{
                    $('#adderrormsg').html('保存成功');
                    setTimeout(function(){
                        window.location.href = site_url + 'Subject/questionlist';
                    },  1000);
                }

            }
        })

    })


});


function delres(ee) {
    $(ee).parent().parent().remove();

}

//选项减少与增加以及删除单条附件记录
function delthis(isme){
    $(isme).parent().parent().remove();
}
function addthis(isme){
    var thisMe = $(isme).siblings("input");
    var addOPend = thisMe.clone();
    var jian =  '<span><a href="javascript:;" onclick="delthis(this)"> - </a></span>'
    $(isme).parents(".parent_11").append("<p></p>");
    var pAddresnumber = $(isme).parents(".parent_11").children().length;
    var pAddres = $(isme).parents(".parent_11").children().eq(pAddresnumber-1);
    pAddres.append(addOPend.val('').attr("checked",false));
    pAddres.append(jian)

}

//选中场景实验题目
    function selectThis(isme){
        $("#ctfOrSec").removeClass('outHide')
        $("#changjingname").val(isme.title);
        $("#changjingcode").val(isme.name);
        var parName = $(isme).attr("parName")
        fnHide("#"+parName, "fadeInDown", "fadeOutUp");
    }
//加载弹窗ctf数据
    function ctfLists(data){
        var questxt = '';
        if(data.length == 0){
            $("#ctfListBox").find("#ctTble").hide();
            $("#ctfListBox").find(".noNews").show();

        }else{
            $.each(data,function(i,v){
                questxt += '<tr>';
                questxt += '<td title="'+ v['CtfName']+'">'+ v['CtfName']+'</td>';
                questxt += '<td title="'+ v['CtfContent']+'">'+ v['CtfContent']+'</td>';
                questxt += '<td><a href="javascript:void(0)" name="'+ v['CtfID']+'"  title="'+ v['CtfName']+'" onclick="selectThis(this)" class="forBlue" parName = "ctfListBox">选择</a></td>';
                questxt += '</tr>';
            });
            $("#ctfListBox").find("#ctTble").show();
            $("#ctfListBox").find(".noNews").hide();
        }
        $('#ctTble').html('');
        $('#ctTble').append(questxt);
}

//加载弹窗sece数据
function  seceLists(data){
    var questxt = '';
    if(data.length == 0){
        $("#scenListBox").find("#seTable").hide();
        $("#scenListBox").find(".noNews").show();

    }else{
        $.each(data,function(i,v){
            questxt += '<tr>';
            questxt += '<td  title="'+ v['scene_name']+'">'+ v['scene_name']+'</td>';
            questxt += '<td  title="'+ v['description']+'">'+ v['description']+'</td>';
            questxt += '<td ><a href="javascript:void(0)" name="'+ v['scene_tpl_uuid']+'"  title="'+ v['scene_name']+'" onclick="selectThis(this)" class="forBlue" chiName = "SectionScene" parName = "scenListBox">选择</a></td>';
            questxt += '</tr>';
        });
        $("#scenListBox").find("#seTable").show();
        $("#scenListBox").find(".noNews").hide();
    }
    $('#seTable').html('');
    $('#seTable').append(questxt);
}

