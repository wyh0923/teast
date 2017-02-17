/**
 * Created by qirupeng on 2016/8/22.
 */
var filename =  $('#uploadctf').val();
var quescontents = new Array();
var inporttype = true;
var totalcheck = new Array();
$(function(){
    $(".listBox").find("label").click(function(){
        $(this).addClass("cur").siblings().removeClass("cur");
    });
    //选择学员
    $(".selectuser").click(function(){
        if(!inporttype){
            inporttype = true;
            totalcheck = new Array();
            quescontents = new Array();
            $("#quesList").html('');
        }
        controlData();
        //搜索做初始化
        $('#sapSearch_pageContainer').val('');
        var codeArr = new Array();
        $('.score').each(function(){
            var code = $(this).attr('code');
            codeArr.push(code);
        });

        for (var i = totalcheck.length - 1; i >= 0; i--) {
            if($.inArray(totalcheck[i],codeArr) ==  -1){
                totalcheck.splice($.inArray(totalcheck[i],totalcheck),1);
                number --;
            }
        }
        $('#addclassuserBox .titleLook span').html(number);

        sapGetData(site_url+'Classstaff/all_user', sapSuc, "pageContainer");
        setPosi("#addclassuserBox")
        fnShow("#addclassuserBox","fadeOutUp","fadeInDown");
    });
    //导入学员
    $(".importuser").click(function(){
        if(inporttype){
            inporttype = false;
            totalcheck = new Array();
            quescontents = new Array();
            $("#quesList").html('');

        }
        controlData();
        number=0;
        $('#importclassuserBox .titleLook span').html('0');
        $("#sapSearch_pageContainer1").val('');
        //每次上传后清空。
        filename= "";
        $("#importques").html('');
        showSelfAjaxPagination('pageContainer1', site_url+'Classstaff/resolve_csv?filename='+filename, "ajaximport");
        //每次上传后清空。end

        sapGetData(site_url+'Classstaff/resolve_csv?filename='+filename, ajaximport, "pageContainer1");
        fnShow("#importclassuserBox","fadeOutUp","fadeInDown");
    });

    //shangchuan
    var timestamp = Date.parse(new Date());
    var upcsv=  $('#edituploadIcon').Huploadify({
        formData:{key:timestamp,key2:'csv'},
        auto:true,//当选择文件后就直接上传了
        fileTypeExts:'*.csv',//上传文件类型
        multi:false, //上传多个文件
        fileSizeLimit:2048,
        breakPoints:false,
        saveInfoLocal:true,
        showUploadedPercent:true,//是否实时显示上传的百分比，如20%
        showUploadedSize:true,
        removeTimeout:1,//上传完成后多久删除队列中的进度条
        fileSplitSize:2048*2048,
        uploader:site_url+'Classstaff/uploadcsv/student',//服务器端脚本文件路径
        buttonText:'上传',

        onUploadComplete:function(fileObj, info, response){

            var data = JSON.parse(info);
            console.log(data)
            if (data.code=='0000') {
                data = data.data;
                $("#uploadIpt").css("visibility", "visible");
                $(".uploadBox").css("border", "1px solid #ccc");


                $("#uploadctf").val(data.filename);
                //默认选中

                $('#importclassuserBox .titleLook span').html(data.contents.length);
                for (var i = 0; i < data.contents.length ; i++) {
                    totalcheck.push(data.contents[i].UserAccount);
                }

                for (i = totalcheck.length - 1; i >= 0; i--) {
                    var emal = data.contents[i].UserEmail;
                    var danwei = data.contents[i].UserDepartment;
                    var phone = data.contents[i].UserPhone;
                    var tt = data.contents[i].StuId + '@@@@' + data.contents[i].UserAccount + '@@@@' + data.contents[i].UserPass + '@@@@' + data.contents[i].UserName + '@@@@' + data.contents[i].UserSex + '@@@@' + emal + '@@@@' + danwei + '@@@@' +phone ;
                    quescontents.push(tt);
                }
                //console.log(quescontents);
                showSelfAjaxPagination('pageContainer1', site_url + 'Classstaff/resolve_csv?filename=' + data.filename, "ajaximport");
                sapGetData(site_url + 'Classstaff/resolve_csv?filename=' + data.filename, ajaximport, "pageContainer1");
            }else {
                $('#errortip p.promptNews').html('文件上传失败！');
                fnShow("#errortip","fadeOutUp","fadeInDown");
                setTimeout(function(){fnHide("#errortip","fadeInDown","fadeOutUp",1);},2000);
            }
        },
        onUploadStart: function(file) {//上传开始时触发（每个文件触发一次）

            var timestamp = Date.parse(new Date());
            upcsv.settings("formData", {key:timestamp,key2:'csv'});
            totalcheck = new Array();
            quescontents = new Array();
            $("#uploadIpt").css("visibility","hidden");
            $(".uploadBox clearfix").css("border","none");
            $(".uploadify-queue").find(".delfilebtn").hide();
            //$("#uploadctf").hide();
        }
    });


    $('.inputAddBtn').click(function(){
        var formobj = document.getElementById('addform');
        var formData = new FormData(formobj);
        var  filecsv = $("#uploadctf").val();
        var classcode = $("#classCode").val();
        if(filecsv!=''){
            $.ajax({
                url : site+'AdminUserCtl/importusers?type=1&classcode='+classcode,
                type : 'post',
                data : $("#addform").serialize(),
                dataType : 'json',
                success : function(msg){
                    console.log(msg);
                    if (msg.status ==1){
                        if(msg.count>0){
                            $('#okBox p').html('导入学员成功'+msg.countSu+'条,重复数据有'+msg.count+'条');
                            fnShow("okBox","fadeOutUp","fadeInDown");
                            setTimeout(function(){fnHide("okBox","fadeInDown","fadeOutUp");},2000);
                        }else{
                            $('#okBox p').html('导入学员成功');
                            fnShow("okBox","fadeOutUp","fadeInDown");
                            setTimeout(function(){fnHide("okBox","fadeInDown","fadeOutUp");},2000);
                        }

                    }
                    if (msg.status == 0) {
                        $('#okBox p').html('导入失败!'+msg.count+'条重复');
                        fnShow("okBox","fadeOutUp","fadeInDown");
                        setTimeout(function(){fnHide("okBox","fadeInDown","fadeOutUp");},2000);
                    }

                },
            })
        }
    })


});


$(function(){
    $(".selectsearch").click(function(){
        sapGetData(site_url+'Classstaff/all_user', sapSuc, "pageContainer");
    });
    $('.question-exam').keydown(function(e){
        if(e.keyCode==13){
            sapGetData(site_url+'Classstaff/all_user', sapSuc, "pageContainer");
        }
    });
    $(".imseach").click(function(){
        var filename = $.trim($('#uploadctf').val());
        sapGetData(site_url+'Classstaff/resolve_csv?filename='+filename, ajaximport, "pageContainer1");
    });
    $('.imexam').keydown(function(e){
        var filename = $.trim($('#uploadctf').val());
        if(e.keyCode==13){
            sapGetData(site_url+'Classstaff/resolve_csv?filename='+filename, ajaximport, "pageContainer1");
        }
    });
    //点击添加保存班级
    $('#trueaddexam').click(function(){
        var trLength = $("#quesList").find("tr").length;

        var classname = $.trim($('input[name=examname]').val());
        if (classname == ''){
            $('#adderrormsg').html('班级名称不能为空');
            return;
        } else if (classname.length<3 || classname.length>16){
            $('#adderrormsg').html('班级的名称应该为3-16位字符');
            return;
        }

        var type;
        //type值 1 选择学员  2 导入学院
        var selectuserClass = $(".selectuser").attr("class");
        if(selectuserClass == "selectuser"){
            type = 2;
        }else{
            type = 1;
        }

        var filename = $('#uploadctf').val();
        if(type == 2 && filename == ''){
            $('#adderrormsg').html('请上传文件！');
            return;
        }


        var isstop = true;
        var infos = new Array();

        $('.score').each(function(){
            var code = $(this).attr('code');
            infos.push(code);
        });


        $.ajax({
            url : site_url+'Classstaff/addclass',
            beforeSend : function(){
                $('#trueaddexam').attr({'disabled':'disabled'});
            },
            data : {'classname':classname,'infos':infos,'type':type,'filename':filename,'trLength':trLength},
            type : 'post',
            dataType : 'json',
            success : function(msg){
                // console.log(msg);
                if (msg['code'] == 'error'){
                    //弹框显示
                    var str = "<a href="+base_url+"resources/files/csv/"+msg.data+" title='下载' style='color:#666666;'>导入数据失败"+"，请点击"+"<span style = 'color:red; border-bottom:1px solid #000;'>下载</span>"+"查看</a>";
                    $('#errortip_down p.promptNews').html(str);
                    fnShow("#errortip_down","fadeOutUp","fadeInDown");
                }else if(msg['code'] == '0000'){
                    //console.log(msg);return;
                    $('#adderrormsg').html('班级新建成功');
                    setTimeout(function(){
                        window.location.href=site_url+'Classstaff/myclass';

                    },1000);
                }else if(msg['code'] == 'repeat'){
                    $('#adderrormsg').html(msg.msg+': '+msg.data);

                }else if(msg['code'] == 'import'){
                    console.log(msg.data);
                    //弹框显示
                    var str = "导入学员成功"+msg.data.success_count+'条，重复数据有<a href="'+base_url+'resources/files/csv/'+msg.data.file+'" title="下载" style="color:#666666;">'+msg.data.count+'条，'+"<span style = 'color:red; border-bottom:1px solid #000;'>下载</span>"+"查看</a>";
                    $('#errortip_down p.promptNews').html(str);
                    fnShow("#errortip_down","fadeOutUp","fadeInDown");

                }else{
                    //console.log(msg);return;
                    $('#adderrormsg').html('添加失败，'+msg.msg);
                }
            },
            complete : function(){
                $('#trueaddexam').removeAttr('disabled');
            },
            error : function(msg){
                console.log('no');
            }
        })
        $('#trueaddexam').attr({'disabled':'disabled'});
    })
})

//鼠标点击选中按钮触发的事件函数(理论题)

var number = 0;
var totalcheck = new Array();
function checkeds(ppo){
    var code = $(ppo).attr('value');
    var UserName = $(ppo).attr('UserName');
    var UserSex = $(ppo).attr('UserSex');
    var UserDepartment = $(ppo).attr('UserDepartment');
    var uclass = $(ppo).attr('uclass');
    var UserPoint = $(ppo).attr('UserPoint');
    var StuId = $(ppo).attr('StuId');
    var tt = code+'@@@@'+UserName+'@@@@'+UserSex+'@@@@'+UserDepartment+'@@@@'+uclass+'@@@@'+UserPoint+'@@@@'+StuId;


    if ($(ppo).is(':checked')){

        if(jQuery.inArray(code,totalcheck) == -1){
            totalcheck.push(code);
        }

        quescontents.push(tt);
        var obj = $('#ques input[class=quescode]');
        number++;
        $('#addclassuserBox .titleLook span').html(number);
        
    }else{
        number--;
        $('#addclassuserBox .titleLook span').html(number);
        
        quescontents.splice($.inArray(tt,quescontents),1)

        $.each(totalcheck,function(n,m){
            if(m == code){
                totalcheck.splice($.inArray(code,totalcheck),1);
            }
        })
    }
}
//导入学生点确定
function okchecked(isid,scoretype){
    fnHide("#addclassuserBox","fadeInDown","fadeOutUp");
    var quescktxt = '';
    $.each(quescontents,function(i,n){
        var arr = n.split('@@@@');
        quescktxt += '<tr>';
        quescktxt += '<td title="'+arr[6]+'">'+arr[6]+'</td>';
        quescktxt += '<td>'+arr[1]+'</td>';
        quescktxt += '<td title="'+arr[2]+'">'+arr[2]+'</td>';
        quescktxt += '<td title="'+arr[3]+'">'+arr[3]+'</td>';
        quescktxt += '<td title="'+arr[4]+'">'+arr[4]+'</td>';
        quescktxt += '<td><span code="'+arr[0]+'" UserName="'+arr[1]+'" UserSex="'+arr[2]+'" uclass="'+arr[4]+'" UserPoint="'+arr[5]+'" UserDepartment="'+arr[3]+'" class="score">'+arr[5]+'</span></td>';

        quescktxt += '<td><a UserCode="'+arr[0]+'"href="javascript:;" class="btn dels" onclick=delques(this)><i class="fa fa-trash g bgBrown"></i>删除</a></td>';
        quescktxt += '</tr>';
    });

    $(isid).html(quescktxt);
    controlData();
}
//点击删除题目操作
function delques(This){
    //$(This).parent().parent().remove();
    var delcode = $(This).attr('UserCode');

    if(jQuery.inArray(delcode,totalcheck) != -1){
        $('#quesList span[class=score]').each(function(){
            var code = $(this).attr('code');
            var UserName = $(this).attr('UserName');
            var UserSex = $(this).attr('UserSex');
            var uclass = $(this).attr('uclass');
            var UserPoint = $(this).attr('UserPoint');
            var UserDepartment = $(this).attr('UserDepartment');
            if (code == delcode) {
                totalcheck.splice(jQuery.inArray(code,totalcheck),1);
                var tt = code+'@@@@'+UserName+'@@@@'+UserSex+'@@@@'+UserDepartment+'@@@@'+uclass+'@@@@'+UserPoint;
                quescontents.splice($.inArray(tt,quescontents),1);
                number--;
                $(This).parent().parent().remove();
                $('#addclassuserBox .titleLook span').html(number);

                $('#ques input[class=quescode]').each(function(){
                    if ($(this).val() == code){
                        $(this).prop('checked',false);
                    }
                })
            }
        })
    }
    controlData();
}
function sapSuc(data) {
    if(data==''){
        $(".nostudentList").show();
    }
    else{
        $(".nostudentList").hide();
    }
    var questxt = '';
    $.each(data,function(i,v){
        questxt += '<tr>';
        questxt += '<td ><input class="quescode" type="checkbox" onclick=checkeds(this) name="quescode[]" UserName="'+v['UserName']+'" UserSex="'+v['UserSex']+'" UserDepartment="'+v['UserDepartment']+'" uclass="'+v['class']+'" UserPoint="'+v['UserPoint']+'"  StuId="'+v['StuId']+'"  value="'+v['UserID']+'"></td>';
        questxt += '<td >'+v['UserName']+'</td>';
        questxt += '<td >'+v['UserSex']+'</td>';
        questxt += '<td title="'+v['UserDepartment']+'">'+v['UserDepartment']+'</td>';
        questxt += '<td title="'+v['class']+'">'+v['class']+'</td>';
        questxt += '<td>'+v['UserPoint']+'</td>';
        questxt += '</tr>';
    });
    $('#ques').html('');
    $('#ques').append(questxt);

    $('#ques input[class=quescode]').each(function(){
        if(jQuery.inArray($(this).val(),totalcheck) != -1){
            $(this).prop('checked',true)
        }
    })
}

function ajaximport(data) {
    var content = '';
    $.each(data, function (i, v) {
        content += '<tr>';
        content += '<td class="fuck"><input class="importcode" type="checkbox" onclick=importcheckeds(this) name="importcode[]" UserAccount="' + v['StuId'] + '" value="' + v['UserAccount'] + '" UserPass="' + v['UserPass'] + '" UserName="' + v['UserName'] + '" UserSex="' + v['UserSex'] + '" UserEmail="' + v['UserEmail'] + '" UserDepartment="' + v['UserDepartment'] + '" UserPhone="' + v['UserPhone'] + '"></td>';
        content += '<td>' + v['StuId'] + '<input type="hidden" name="StuID[]" value="' + v['StuId'] + '"/>' + '</td>';
        content += '<td>' + v['UserAccount'] + '<input type="hidden" name="UserAccount[]" value="' + v['UserAccount'] + '"/><input type="hidden" name="UserPass[]" value="' + v['UserPass'] + '"/>' + '</td>';
        content += '<td>' + v['UserName'] + '<input type="hidden" name="UserName[]" value="' + v['UserName'] + '"/>' + '</td>';
        content += '<td>' + v['UserSex'] + '<input type="hidden" name="UserSex[]" value="' + v['UserSex'] + '"/>' + '</td>';
        content += '<td>' + v['UserEmail'] + '<input type="hidden" name="UserEmail[]" value="' + v['UserEmail'] + '"/>' + '</td>';
        content += '<td>' + v['UserDepartment'] + '<input type="hidden" name="UserDepartment[]" value="' + v['UserDepartment'] + '"/>' + '</td>';
        content += '<td>' + v['UserPhone'] + '<input type="hidden" name="UserPhone[]" value="' + v['UserPhone'] + '"/>' + '</td>';
        content += '</tr>';
    });

    $('#importques').html('');
    $('#importques').append(content);

    $('#importques input[class=importcode]').each(function(){
        if(jQuery.inArray($(this).val(),totalcheck) != -1){
            $(this).prop('checked',true)
        }
    })

}

function importcheckeds(ppo){
    var code = $(ppo).attr('value');
    var UserAccount = $(ppo).attr('UserAccount');
    var UserPass = $(ppo).attr('UserPass');
    var UserName = $(ppo).attr('UserName');
    var UserSex = $(ppo).attr('UserSex');

    var UserEmail = $(ppo).attr('UserEmail');
    var UserDepartment = $(ppo).attr('UserDepartment');
    var UserPhone = $(ppo).attr('UserPhone');
   
    var tt = UserAccount+'@@@@'+code+'@@@@'+UserPass+'@@@@'+UserName+'@@@@'+UserSex+'@@@@'+UserEmail+'@@@@'+UserDepartment+'@@@@'+UserPhone;

    var number = totalcheck.length;

    if ($(ppo).is(':checked')){

        if(jQuery.inArray(code,totalcheck) == -1){
            totalcheck.push(code);
        }

        quescontents.push(tt);
        var obj = $('#importques input[class=quescode]');
        number++;
         $('#importclassuserBox .titleLook span').html(number);
       

    }else{
        number--;
        $('#importclassuserBox .titleLook span').html(number);
        
        quescontents.splice($.inArray(tt,quescontents),1)

        $.each(totalcheck,function(n,m){
            if(m == code){
                totalcheck.splice($.inArray(code,totalcheck),1);
            }
        });

    }
}
//勾选题目保存之后将题目信息拼接到选题的弹框(理论题)
function okimportchecked(isid,scoretype){
    fnHide("#importclassuserBox","fadeInDown","fadeOutUp");
    var quescktxt = '';
    $.each(quescontents,function(i,n){
        var arr = n.split('@@@@');
        quescktxt += '<tr>';
        quescktxt += '<td title="'+arr[0]+'">'+arr[0]+'</td>';
        quescktxt += '<td>'+arr[3]+'</td>';
        quescktxt += '<td title="'+arr[4]+'">'+arr[4]+'</td>';
        quescktxt += '<td title="'+arr[6]+'">'+arr[6]+'</td>';
        quescktxt += '<td> </td>';
        quescktxt += '<td><span code="'+arr[1]+'" UserName="'+arr[1]+'" UserSex="'+arr[2]+'" uclass="'+arr[4]+'" UserPoint="'+arr[5]+'" UserDepartment="'+arr[3]+'" class="score"> </span></td>';
        quescktxt += '<td><a UserCode="'+arr[1]+'"href="javascript:;" class="btn dels" onclick=delques(this)><i class="fa fa-trash g bgBrown"></i>删除</a></td>';
        quescktxt += '</tr>';
    })

    $(isid).html(quescktxt);
    controlData();
}


//勾选题目保存之后将题目信息
function importchecked(isid,scoretype){
    //alert(classcode);
    //console.log(quescontents);return;
    //提交 添加学员
    var nameArr = new Array();
    if(quescontents.length == 0){
        fnHide("#importclassuserBox","fadeInDown","fadeOutUp");

    } else {
        var filename = $('#uploadctf').val();

        $.each(quescontents,function(i,n){
            var arr = n.split('@@@@');
            nameArr.push(arr[1]);
        })

        $.ajax({
            url : site_url+'Classstaff/stuimport',
            beforeSend : function(){
                $('#trueaddexam').attr({'disabled':'disabled'});
            },
            data : {'cid':classcode,'infos':nameArr,'type':2, 'filename':filename},
            type : 'post',
            dataType : 'json',
            success : function(msg){
                // console.log(msg);
                if (msg['code'] == 'error'){
                    //弹框显示
                    var str = "<a href="+base_url+"resources/files/csv/"+msg.data+" title='下载' style='color:#666666;'>导入数据失败"+"，请点击"+"<span style = 'color:red; border-bottom:1px solid #000;'>下载</span>"+"查看</a>";
                    $('#errortip_down p.promptNews').html(str);
                    fnShow("#errortip_down","fadeOutUp","fadeInDown");
                }else if(msg['code'] == '0000'){
                    //console.log(msg);return;
                    $('#errortip_down p.promptNews').html('添加学员成功');
                    fnHide("#importclassuserBox","fadeInDown","fadeOutUp");
                    fnShow("#errortip_down","fadeOutUp","fadeInDown");

                    setTimeout(function(){
                        window.location.reload();

                    },2000);

                }else if(msg['code'] == 'repeat'){
                    $('#errortip_down p.promptNews').html(msg.msg+': '+msg.data);

                }else if(msg['code'] == 'import'){
                    //console.log(msg.data);
                    //弹框显示
                    var str = "导入学员成功"+msg.data.success_count+'条，重复数据有<a href="'+base_url+'resources/files/csv/'+msg.data.file+'" title="下载" style="color:#666666;">'+msg.data.count+'条，'+"<span style = 'color:red; border-bottom:1px solid #000;'>下载</span>"+"查看</a>";
                    $('#errortip_down p.promptNews').html(str);
                    fnShow("#errortip_down","fadeOutUp","fadeInDown");

                }else{
                    //console.log(msg);return;
                    $('#errortip_down p.promptNews').html('添加失败');
                    fnShow("#errortip_down","fadeOutUp","fadeInDown",1);

                }
            },

            error : function(msg){
                console.log('no');
            }
        })

    }



}
//控制数据
function controlData(){
    if($('#quesList tr').length>0){
                $('#noNewsRemind').hide();
            }
            else{
               $('#noNewsRemind').show() 
            } 
}



