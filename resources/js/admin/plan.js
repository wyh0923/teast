/**
 * Created by liuqi on 2016/8/30.
 */


$(function () {
    $(".csys").click(function(){
        var search = translate($.trim($(".esys").val()));
        window.location.href=site_url + '/Adminsubject/mysystem' + "/search/"+encodeURI(search);
    });
    $('.esys').keydown(function(e){
        if(e.keyCode==13){
            var search = translate($.trim($(".esys").val()));
            window.location.href=site_url + '/Adminsubject/mysystem' + "/search/"+encodeURI(search);
        }
    });


    //排序
    $('#PackageCount,#SectionNum,#TestNum').click(function(){
        var field = $(this).attr("id");
        var code = $(this).attr('code');
        var str = '';
        if (search != '')str += '/search/'+translate(search);
        if(code == 'DESC'){
            location.href = site_url+'/Adminsubject/mysystem' + str + '/sort/'+field+' ASC';
        }else if(code == 'ASC'){
            location.href = site_url+'/Adminsubject/mysystem' + str + '/sort/'+field+' DESC';
        }else{
            location.href = site_url+'/Adminsubject/mysystem' + str + '/sort/'+field+' DESC';
        }
    });

    //显示新增方案
    $('#addPlan').click(function () {
        fnShow("#showsys","fadeOutUp","fadeInDown");
    });

    //新增方案
    $('#addOk').click(function () {
        var name = $.trim($('#planName').val());
        // alert(name);

        if(name == '')
        {
            $('#adderrormsg').text('请填写方案名称');
            return false;
        }
        
        $.ajax({
            url: site_url + '/Adminsubject/addplan',
            type: 'post',
            dataType: 'json',
            data: {name: name},
            success: function (msg) {
                if(msg.code == '0000')
                {
                    $('#adderrormsg').text('新增方案成功');
                    setTimeout("window.location.reload()", 2000);

                }else{
                    $('#adderrormsg').text('已存在方案名称');
                }
            }
            
        });
    });

    //单记录删除题目
    $(".pdel").click(function(){
        var code=$(this).attr('code');
        $('#pOk').attr('code',code);

        fnShow("#pdelOk","fadeOutUp","fadeInDown");
    });
    
    //确定删除单记录题目
    $('#pOk').click(function(){
        var cid = $(this).attr('code');

        fnHide("#pdelOk","fadeInDown","fadeOutUp", 1);
        $.ajax({
            url:site_url+"/Adminsubject/delplan",
            type:'post',
            data:{'code':cid},
            dataType:'json',
            success:function(message){

                if(message.code=='0000'){

                    fnShow("#pdOk","fadeOutUp","fadeInDown");
                    setTimeout('fnHide("#pdOk","fadeInDown","fadeOutUp", 1);',1200);
                    $('#pdOk p.promptNews').html('删除成功');
                    setTimeout("location.reload()",2000);
                }else{

                    $('#pdOk p.promptNews').html('删除失败');
                    fnShow("#pdOk","fadeOutUp","fadeInDown");
                    setTimeout("location.reload()",2000);
                }
            }

        })
    });

});



