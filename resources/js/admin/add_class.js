/**
 * Created by qirupeng on 2016/8/22.
 */
var filename =  $('#uploadctf').val();
var quescontents = new Array();
var totalcheck = new Array();
$(function(){
    $(".listBox").find("label").click(function(){
        $(this).addClass("cur").siblings().removeClass("cur");
    });
    //选择学员
    $(".selectuser").click(function(){
        //搜索做初始化
        $('#sapSearch_pageContainer').val('');
         var tygo = parseInt($("#quesList").attr("tygo"));
        if(tygo==2){
            totalcheck = [];
             $("#quesList").html('');
              quescontents=[]//清空防止重复和页面被清除
              $("#quesList").attr("tygo",'1')
        }
        $('#addclassuserBox .titleLook span').html(totalcheck.length);
        if($('#quesList tr').length>0){
                $('#noNewsRemind').hide();
            }
            else{
               $('#noNewsRemind').show() 
            }
        sapGetData(site_url+'/User/all_user', sapSuc, "pageContainer");
        setPosi("#addclassuserBox");
        fnShow("#addclassuserBox","fadeOutUp","fadeInDown");
    });
    //导入学员
    $(".importuser").click(function(){
        var tygo = parseInt($("#quesList").attr("tygo"));
        if(tygo==1){
            totalcheck = [];
             $("#quesList").html('');
             $("#quesList").attr("tygo",'2')
        }
         quescontents=[]//清空防止重复和页面被清除
         if($('#quesList tr').length>0){
            $('#noNewsRemind').hide();
        }
        else{
           $('#noNewsRemind').show() 
        }
        $('#importclassuserBox .titleLook span').html('0');
        $("#sapSearch_pageContainer1").val('');
        //每次上传后清空。
        filename= "";
        $("#importques").html('');
        showSelfAjaxPagination('pageContainer1', site_url+'/User/resolve_csv?filename='+filename, "ajaximport");
        //每次上传后清空。end

        sapGetData(site_url+'/User/resolve_csv?filename='+filename, ajaximport, "pageContainer1");
        fnShow("#importclassuserBox","fadeOutUp","fadeInDown");
    });

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
        uploader:site_url+'/User/uploadcsv/student',//服务器端脚本文件路径
        buttonText:'上传',

        onUploadComplete:function(fileObj, info, response){

            var data = JSON.parse(info);
            if (data.code=='0000') {
                data = data.data;
                $("#uploadIpt").css("visibility", "visible");
                $(".uploadBox").css("border", "1px solid #ccc");


                $("#uploadctf").val(data.filename);
                //默认选中

                
                for (var i = 0; i < data.contents.length ; i++) {
                    totalcheck.push(data.contents[i].UserAccount);
                }
                $('#importclassuserBox .titleLook span').html(totalcheck.length);
                for (i = totalcheck.length - 1; i >= 0; i--) {
                    var tt = data.contents[i].StuId + '@@@@' + data.contents[i].UserAccount + '@@@@' + data.contents[i].UserPass + '@@@@' + data.contents[i].UserName + '@@@@' + data.contents[i].UserSex + '@@@@' + data.contents[i].UserEmail + '@@@@' + data.contents[i].UserDepartment + '@@@@' + data.contents[i].UserPhone;
                    quescontents.push(tt);
                }
                showSelfAjaxPagination('pageContainer1', site_url + '/User/resolve_csv?filename=' + data.filename, "ajaximport");
                sapGetData(site_url + '/User/resolve_csv?filename=' + data.filename, ajaximport, "pageContainer1");
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



});


$(function(){
    $(".selectsearch").click(function(){
        sapGetData(site_url+'/User/all_user', sapSuc, "pageContainer");
    });
    $('.question-exam').keydown(function(e){
        if(e.keyCode==13){
            sapGetData(site_url+'/User/all_user', sapSuc, "pageContainer");
        }
    });
    $(".imseach").click(function(){
        var filename = $('#uploadctf').val();
        sapGetData(site_url+'/User/resolve_csv?filename='+filename, ajaximport, "pageContainer1");
    });
    $('.imexam').keydown(function(e){
        var filename = $('#uploadctf').val();
        if(e.keyCode==13){
            sapGetData(site_url+'/User/resolve_csv?filename='+filename, ajaximport, "pageContainer1");
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

        //type值 1 选择学员  2 导入学院
        var type = $("#truetype .cur").attr("type");
        var filename = $('#uploadctf').val();
        if(type == 1&&totalcheck.length<1){
            $('#adderrormsg').html('请选择学员！');
            return;
        }
        if(type == 2 && filename == ''){
            $('#adderrormsg').html('请上传文件！');
            return;
        }
        if(type == 2 && totalcheck.length<1){
            $('#adderrormsg').html('请先选择要导入的学员!');
            return;
        }

        $.ajax({
            url : site_url+'/User/addclass',
            beforeSend : function(){
                $('#trueaddexam').attr({'disabled':'disabled'});
            },
            data : {'classname':classname,'infos':totalcheck,'type':type,'filename':filename,'trLength':trLength},
            type : 'post',
            dataType : 'json',
            success : function(msg){

                if (msg['code'] == 'error'){
                        //弹框显示
                        var str = "<a href="+base_url+"resources/files/csv/"+msg.data+" title='下载' style='color:#666666;'>导入数据失败"+"，请点击"+"<span style = 'color:red; border-bottom:1px solid #000;'>下载</span>"+"查看</a>";
                        $('#errortip_down p.promptNews').html(str);
                        fnShow("#errortip_down","fadeOutUp","fadeInDown");
                }else if(msg['code'] == '0000'){
                    $('#adderrormsg').html('班级添加成功');
                    window.location.href=site_url+'/User/classes';

                }else if(msg['code'] == 'repeat'){
                    $('#adderrormsg').html(msg.msg+': '+msg.data);

                }else if(msg['code'] == 'import'){
                    // console.log(msg.data);
                    //弹框显示
                    var str = "导入学员成功"+msg.data.success_count+'条，重复数据有<a href="'+base_url+'resources/files/csv/'+msg.data.file+'" title="下载" style="color:#666666;">'+msg.data.count+'条，'+"<span style = 'color:red; border-bottom:1px solid #000;'>下载</span>"+"查看</a>";
                    $('#errortip_down p.promptNews').html(str);
                    fnShow("#errortip_down","fadeOutUp","fadeInDown");

                }else{
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

//选择学员弹窗的复选框选择事件

//var number = 0;
var totalcheck = new Array();
function checkeds(ppo){
    var code = $(ppo).attr('value');//id
    var UserName = $(ppo).attr('UserName');//姓名
    var UserSex = $(ppo).attr('UserSex');
    var UserDepartment = $(ppo).attr('UserDepartment');
    var uclass = $(ppo).attr('uclass');//属于别的班级
    var UserPoint = $(ppo).attr('UserPoint');//积分
    var StuId = $(ppo).attr('StuId');//学号
    var tt = code+'@@@@'+UserName+'@@@@'+UserSex+'@@@@'+UserDepartment+'@@@@'+uclass+'@@@@'+UserPoint+'@@@@'+StuId;
    if ($(ppo).is(':checked')){

        if(jQuery.inArray(code,totalcheck) == -1){
            totalcheck.push(code);
        }

        quescontents.push(tt);
       
        
    }else{
        
        quescontents.splice($.inArray(tt,quescontents),1)

        $.each(totalcheck,function(n,m){
            if(m == code){
                totalcheck.splice($.inArray(code,totalcheck),1);
            }
        })
    };
    $('#addclassuserBox .titleLook span').html(totalcheck.length);
}
//选择学生点确定--以学员id为标准
function okchecked(isid,scoretype){
    fnHide("#addclassuserBox","fadeInDown","fadeOutUp");
    var quescktxt = '';
    if(quescontents.length>0){
        $('#noNewsRemind').hide();
    }
    else{
       $('#noNewsRemind').show() 
    }
    $.each(quescontents,function(i,n){
        var arr = n.split('@@@@');
        quescktxt += '<tr>';
        quescktxt += '<td title="'+arr[6]+'">'+arr[6]+'</td>';
        quescktxt += '<td>'+arr[1]+'</td>';
        quescktxt += '<td title="'+arr[2]+'">'+arr[2]+'</td>';
        quescktxt += '<td title="'+arr[3]+'">'+arr[3]+'</td>';
        quescktxt += '<td title="'+arr[4]+'">'+arr[4]+'</td>';
        quescktxt += '<td>'+arr[5]+'</td>';

        quescktxt += '<td><a  code="'+arr[0]+'" UserName="'+arr[1]+'" UserSex="'+arr[2]+'" uclass="'+arr[4]+'" UserPoint="'+arr[5]+'" UserDepartment="'+arr[3]+'" "href="javascript:;" class="btn dels" onclick=delques(this)><i class="fa fa-trash"></i>删除</a></td>';
        quescktxt += '</tr>';
    });

    $(isid).html(quescktxt);
}

function sapSuc(data) {
    var questxt = '';
    $.each(data,function(i,v){
        questxt += '<tr>';
        questxt += '<td class="fuck"><input class="quescode" type="checkbox" onclick=checkeds(this) name="quescode[]" UserName="'+v['UserName']+'" UserSex="'+v['UserSex']+'" UserDepartment="'+v['UserDepartment']+'" uclass="'+v['class']+'" UserPoint="'+v['UserPoint']+'"  StuId="'+v['StuId']+'"  value="'+v['UserID']+'"></td>';
        questxt += '<td class="fuck">'+v['UserName']+'</td>';
        questxt += '<td class="fuck">'+v['UserSex']+'</td>';
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
        content += '<td class="fuck"><input class="importcode" type="checkbox" onclick=importcheckeds(this) name="importcode[]" UserAccount="' + v['UserAccount'] + '" value="' + v['StuId'] + '" UserPass="' + v['UserPass'] + '" UserName="' + v['UserName'] + '" UserSex="' + v['UserSex'] + '" UserEmail="' + v['UserEmail'] + '" UserDepartment="' + v['UserDepartment'] + '" UserPhone="' + v['UserPhone'] + '"></td>';
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
        if(jQuery.inArray($(this).attr("UserAccount"),totalcheck) != -1){
            $(this).prop('checked',true)
        }
    })
}

function importcheckeds(ppo){
    var code = $(ppo).attr('value');//学号
    var UserAccount = $(ppo).attr('UserAccount');
    var UserPass = $(ppo).attr('UserPass');
    var UserName = $(ppo).attr('UserName');
    var UserSex = $(ppo).attr('UserSex');
    var UserEmail = $(ppo).attr('UserEmail');
    var UserDepartment = $(ppo).attr('UserDepartment');
    var UserPhone = $(ppo).attr('UserPhone');
    var tt = code+'@@@@'+UserAccount+'@@@@'+UserPass+'@@@@'+UserName+'@@@@'+UserSex+'@@@@'+UserEmail+'@@@@'+UserDepartment+'@@@@'+UserPhone;


    if ($(ppo).is(':checked')){

        if(jQuery.inArray(UserAccount,totalcheck) == -1){
            totalcheck.push(UserAccount);
        }

        quescontents.push(tt);
      
    }else{
        quescontents.splice($.inArray(tt,quescontents),1)

        $.each(totalcheck,function(n,m){
            if(m == UserAccount){
                totalcheck.splice($.inArray(UserAccount,totalcheck),1);
            }
        });

    }
    $('#importclassuserBox .titleLook span').html(totalcheck.length);
}
//勾选题目保存之后将题目信息拼接到选题的弹框(导入学员)
function okimportchecked(isid,scoretype){
    fnHide("#importclassuserBox","fadeInDown","fadeOutUp");
    var quescktxt = '';
    if(quescontents.length>0){
        $.each(quescontents,function(i,n){
        var arr = n.split('@@@@');
        quescktxt += '<tr>';
        quescktxt += '<td title="'+arr[0]+'">'+arr[0]+'</td>';
        quescktxt += '<td>'+arr[3]+'</td>';
        quescktxt += '<td title="'+arr[4]+'">'+arr[4]+'</td>';
        quescktxt += '<td title="'+arr[6]+'">'+arr[6]+'</td>';
        quescktxt += '<td> </td>';
        quescktxt += '<td><span code="'+arr[0]+'" UserName="'+arr[1]+'" UserSex="'+arr[2]+'" uclass="'+arr[4]+'" UserPoint="'+arr[5]+'" UserDepartment="'+arr[3]+'" class="score"> </span></td>';
        quescktxt += '<td><a UserCode="'+arr[1]+'"href="javascript:;" class="btn dels" onclick=delStu(this)><i class="fa fa-trash g bgBrown"></i>删除</a></td>';
        quescktxt += '</tr>';
    })

    $(isid).html(quescktxt);
    }
    if($('#quesList tr').length>0){
        $('#noNewsRemind').hide();
    }
    else{
       $('#noNewsRemind').show() 
    }
    
}
//点击删除题目操作--通过选择得到的学员
function delques(This){
            var code = $(This).attr('code');
            var UserName = $(This).attr('UserName');
            var UserSex = $(This).attr('UserSex');
            var uclass = $(This).attr('uclass');
            var UserPoint = $(This).attr('UserPoint');
            var UserDepartment = $(This).attr('UserDepartment');
                totalcheck.splice(jQuery.inArray(code,totalcheck),1);
                var tt = code+'@@@@'+UserName+'@@@@'+UserSex+'@@@@'+UserDepartment+'@@@@'+uclass+'@@@@'+UserPoint;
                quescontents.splice($.inArray(tt,quescontents),1);
                $(This).parent().parent().remove();
                if($('#quesList tr').length>0){
                    $('#noNewsRemind').hide();
                }
                else{
                   $('#noNewsRemind').show() 
                }
            
}
//点击删除题目操作--导入学员
function delStu(isme){
    var UserCode = $(isme).attr("UserCode");
    totalcheck.splice(jQuery.inArray(UserCode,totalcheck),1);
    $(isme).parent().parent().remove();
    if($('#quesList tr').length>0){
        $('#noNewsRemind').hide();
    }
    else{
       $('#noNewsRemind').show() 
    }
}


