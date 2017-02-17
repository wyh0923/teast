/**
 * Created by Administrator on 2016/8/23.
 */

//切换tab
function tab(tabTitle,tabCon,Class,siblingClass){
    $(tabTitle).click(function(){
        var index=$(this).index();
        $(this).addClass(Class).siblings().removeClass(Class);
        $(tabCon).eq(index).addClass('block').siblings(siblingClass).removeClass('block').addClass('outHide');
    })
}
tab(".tabTitle",".tabCon","cur",".tabCon");

var totalcheck = new Array();
//选择导入用户
function importcheckeds(ppo){
    var UserAccount = $(ppo).attr('value');
    if ($(ppo).is(':checked')){
        if(jQuery.inArray(UserAccount,totalcheck) == -1){
            totalcheck.push(UserAccount);
        }
    }else{
        $.each(totalcheck,function(n,m){
            if(m == UserAccount){
                totalcheck.splice($.inArray(UserAccount,totalcheck),1);
            }
        })
    }
}

var timestamp = Date.parse(new Date());
var upadd = $('#edituploadIcon').Huploadify({
    auto: true,//当选择文件后就直接上传了
    fileTypeExts: '*.csv',//上传文件类型
    multi: false, //上传多个文件
    fileSizeLimit: 2048,
    breakPoints: false,
    saveInfoLocal: true,
    showUploadedPercent: true,//是否实时显示上传的百分比，如20%
    showUploadedSize: true,
    removeTimeout: 100,//上传完成后多久删除队列中的进度条
    //buttonText:'上传附件',
    formData:{key:timestamp,key2:'csv'},
    uploader:siteurl+'Classstaff/uploadcsv/student',//服务器端脚本文件路径
    onUploadComplete: function (messfileObj, info, responseage) {

        console.log(info);
        var data = JSON.parse(info);
        if (data.code=='0000'){
            data = data.data;

            $("#uploadIpt").css("visibility","visible");
            $(".uploadBox").css("border","1px solid #ccc");

            $("#uploadctf").val(data.filename);
            var content='';
            //默认选中
            //在此时就应该返回那些数据不合格使其无法选中
            for (var i = 0; i < data.contents.length ; i++) {
                content += '<tr>';
                content += '<td>'+'<input class="quescode" type="checkbox" onclick=importcheckeds(this) name="quescode[]"  UserName="'+data.contents[i].UserName+'" UserSex="'+data.contents[i].UserSex+'" UserDepartment="'+data.contents[i].UserDepartment+'" value="'+data.contents[i].UserAccount+'" checked>'+'</td>';
                content += '<td>'+data.contents[i].StuId+'<input type="hidden" name="StuId[]" value="'+data.contents[i].StuId+'"/>'+'</td>';
                content += '<td>'+data.contents[i].UserAccount+'<input type="hidden" name="UserAccount[]" value="'+data.contents[i].UserAccount+'"/><input type="hidden" name="UserPass[]" value="'+data.contents[i].UserPass+'"/>'+'</td>';
                content += '<td>'+data.contents[i].UserName+'<input type="hidden" name="UserName[]" value="'+data.contents[i].UserName+'"/>'+'</td>';
                content += '<td>'+data.contents[i].UserSex+'<input type="hidden" name="UserSex[]" value="'+data.contents[i].UserSex+'"/>'+'</td>';
                content += '<td>'+data.contents[i].UserEmail+'<input type="hidden" name="UserEmail[]" value="'+data.contents[i].UserEmail+'"/>'+'</td>';
                content += '<td>'+data.contents[i].UserDepartment+'<input type="hidden" name="UserDepartment[]" value="'+data.contents[i].UserDepartment+'"/>'+'</td>';
                content += '<td>'+data.contents[i].UserPhone+'<input type="hidden" name="UserPhone[]" value="'+data.contents[i].UserPhone+'"/>'+'</td>';
                content += '</tr>';
                totalcheck.push(data.contents[i].UserAccount);
            }
            $('#ajaxusers').html('');
            $('#ajaxusers').html(content);
            controlData();
        }else {
            $('#errortip p.promptNews').html('文件上传失败！');
            fnShow("#errortip","fadeOutUp","fadeInDown");
            setTimeout(function(){fnHide("#errortip","fadeInDown","fadeOutUp");},2000);
        }


    },
    onUploadStart: function (file) {//上传开始时触发（每个文件触发一次）
        var timestamp = Date.parse(new Date());
        upadd.settings("formData", {key:timestamp,key2:'csv'});
    }
});

$(function(){
    $('#typing').click(function () 
    {
        var stuId = $.trim($('input[name=StuId]').val());
        var name = $.trim($('input[name=name]').val());
        var account = $.trim($('input[name=account]').val());
        var sex = $('input[name=sex]:checked').val();
        var department = $.trim($('input[name=department]').val());
        var email = $.trim($('input[name=email]').val());
        var phone = $.trim($('input[name=phone]').val());
        var password = $.trim($('input[name=password]').val());
        var repassword = $.trim($('input[name=repassword]').val());

        var checkId = /^[a-zA-Z0-9]{1,16}$/;
        if(stuId != '' && !checkId.test(stuId)){
            $('#error').html('学号由1-16位的字母数字组成');
            return;
        }
        
        var nameReg = /^[\u0391-\uFFE5A-Za-z]{2,12}$/;
        if (name == ''){
            $('#error').html('姓名不能为空');
            return;
        } else if(!nameReg.test(name)){
            $('#error').html('姓名由2-12位的中文、字母组成');
            return;
        }

        var chkaccount = /^[a-zA-Z0-9\_]{6,16}$/;
        if (account == '') {
            $('#error').html('用户名不能为空');
            return;
        } else if (!chkaccount.test(account)){
            $('#error').html('用户名由6-16位的字母数字下滑线组成');
            return;
        }
        if(password.length<6 || password.length>15){
            $('#error').html('密码长度应该在6-15位之间');
            return ;
        }
        if(password !== repassword){
            $('#error').html('两次输入的密码不一致，请重新输入');
            return;
        }
        var reg = /^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/;
        if(!reg.test(email) && email != ''){
            $('#error').html('邮箱格式有误！应如：123456789@163.com');
            return false;
        }
        var telReg = /^[1][3-9][0-9]{9}$/;
        var phoneReg = /^([0-9]{3,4}-)?[0-9]{7,8}$/;
        if(phone != '' && !telReg.test(phone) && !phoneReg.test(phone)){
            $('#error').html('手机或电话号码格式不正确！');
            return false;
        }
        var departmentReg = /^[\u0391-\uFFE5A-Za-z]+$/;
        if(department != '' && !departmentReg.test(department)){
            $('#error').html('工作单位只能是中文、字母');
            return;
        }

        var data = {'StuId':stuId,'UserPass':password,'UserAccount':account, 'UserName':name, 'UserDepartment':department, 'UserEmail':email, 'UserPhone':phone,'UserSex':sex};

        $.ajax({
            url: siteurl+ 'Classstaff/addstudent',
            type: 'post',
            data: data,
            dataType: 'json',
            success: function(msg){
                if(msg.code == '0000'){
                    fnShow("#okBox","fadeOutUp","fadeInDown");
                    $('#okBox p.promptNews').html('录入成功');
                    //setTimeout("location.reload()",2000);
                    setTimeout(function(){
                        window.location.href = siteurl+'Classstaff/allstudents';
                    },2000);
                } else {
                    $('#okBox p.promptNews').html(msg.msg);
                    fnShow("#okBox","fadeOutUp","fadeInDown");
                    setTimeout(function(){fnHide("#okBox","fadeInDown","fadeOutUp");},2000);
                }
            },
            error: function(msg){
                console.log('error');
            }

        });


    });

    $('#continueluru').click(function () {
        var stuId = $.trim($('input[name=StuId]').val());
        var name = $.trim($('input[name=name]').val());
        var account = $.trim($('input[name=account]').val());
        var sex = $('input[name=sex]:checked').val();
        var department = $.trim($('input[name=department]').val());
        var email = $.trim($('input[name=email]').val());
        var phone = $.trim($('input[name=phone]').val());
        var password = $.trim($('input[name=password]').val());
        var repassword = $.trim($('input[name=repassword]').val());
        var checkId = /^[a-zA-Z0-9]{1,16}$/;
        if(stuId != '' && !checkId.test(stuId)){
            $('#error').html('学号由1-16位的字母数字组成');
            return;
        }

        var nameReg = /^[\u0391-\uFFE5A-Za-z]{2,12}$/;
        if (name == ''){
            $('#error').html('姓名不能为空');
            return;
        } else if(!nameReg.test(name)){
            $('#error').html('姓名由2-12位的中文字母组成');
            return;
        }

        var chkaccount = /^[a-zA-Z0-9\_]{6,16}$/;
        if (account == '') {
            $('#error').html('用户名不能为空');
            return;
        } else if (!chkaccount.test(account)){
            $('#error').html('用户名由6-16位的字母数字下滑线组成');
            return;
        }
        if(password.length<6 || password.length>15){
            $('#error').html('密码长度应该在6-15位之间');
            return ;
        }
        if(password !== repassword){
            $('#error').html('两次输入的密码不一致，请重新输入');
            return;
        }
        var reg = /^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/;
        if(!reg.test(email) && email != ''){
            $('#error').html('邮箱格式有误！应如：123456789@163.com');
            return false;
        }
        var telReg = /^[1][3-9][0-9]{9}$/;
        var phoneReg = /^([0-9]{3,4}-)?[0-9]{7,8}$/;
        if(phone != '' && !telReg.test(phone) && !phoneReg.test(phone)){
            $('#error').html('手机或电话号码格式不正确！');
            return false;
        }
        var departmentReg = /^[\u0391-\uFFE5A-Za-z]+$/;
        if(department != '' && !departmentReg.test(department)){
            $('#error').html('工作单位只能是中文字母');
            return;
        }
        var data = {'StuId':stuId,'UserPass':password,'UserAccount':account, 'UserName':name, 'UserDepartment':department, 'UserEmail':email, 'UserPhone':phone,'UserSex':sex};

        $.ajax({
            url: siteurl + 'Classstaff/addstudent',
            type: 'post',
            data: data,
            dataType: 'json',
            success: function (msg) {
                if (msg.code == '0000') {
                    $('#okBox p.promptNews').html('录入成功');
                    fnShow("#okBox","fadeOutUp","fadeInDown");
                    setTimeout(function(){
                        window.location.reload();
                    },2000);
                } else {
                    $('#okBox p.promptNews').html(msg.msg);
                    fnShow("#okBox","fadeOutUp","fadeInDown");
                    setTimeout(function(){fnHide("#okBox","fadeInDown","fadeOutUp");},2000);
                }
            },
            error: function (msg) {
                console.log('error');
            }
        })
    });

});

$('#inputAddBtn').click(function(){
    var trLength = $("#ajaxusers").find("tr").length;
    if(trLength>0){
        if(totalcheck.length<1){
             $('#errortip p.promptNews').html('请选择您上传的学员');
                fnShow("#errortip","fadeOutUp","fadeInDown");
                setTimeout(function(){fnHide("#errortip","fadeInDown","fadeOutUp");},2000);
                return false;
        }
        var  filecsv = $("#uploadctf").val();
        if(filecsv!=''){
            $.ajax({
                url : siteurl+'Classstaff/import_student',
                type : 'post',
                data : {"UserAccount":totalcheck,"filename":filecsv,"type":1},
                dataType : 'json',
                success : function(msg){
                    if (msg.code == '0000'){
                        $('#okBox p.promptNews').html('导入学员成功');
                        fnShow("#okBox","fadeOutUp","fadeInDown");
                        setTimeout(function(){fnHide("#okBox","fadeInDown","fadeOutUp");window.location.href = siteurl+'Classstaff/allstudents';},2000);
                    }else if(msg.code = 'error'){
                        $('#errortip p.promptNews').html('导入数据失败'+'，请点击'+'<a href="'+baseurl+'resources/files/csv/'+msg.data+'">下载</a>查看');
                        fnShow("#errortip","fadeOutUp","fadeInDown");
                        //setTimeout(function(){fnHide("#okBox","fadeInDown","fadeOutUp");},2000);
                    }else{
                        $('#okBox p.promptNews').html(msg.msg);
                        fnShow("#okBox","fadeOutUp","fadeInDown");
                        setTimeout(function(){fnHide("#okBox","fadeInDown","fadeOutUp");},2000);
                    }

                }
            })
        }
    }else{
        $('#errortip p.promptNews').html('请上传文件后，添加学员');
        fnShow("#errortip","fadeOutUp","fadeInDown");
        setTimeout(function(){fnHide("#errortip","fadeInDown","fadeOutUp");},2000);
    }

});
//控制数据
function controlData(){
    if($('#ajaxusers tr').length>0){
                $('#noNewsRemind').hide();
            }
            else{
               $('#noNewsRemind').show() 
            } 
}