/**
 * Created by qirupeng on 2016/8/23.
 */
//多选删除确定框
function delAllTeacher() {
    fnShow("#delAll","fadeOutUp","fadeInDown");
}

//下拉列表选中
function typesel(){
    var typeid = $('#typeSel option:selected').val();

    window.location.href=site_url+"Subject/toollist/type/"+ typeid;
}

$(function(){
    //搜索工具
    $(".subsearch").click(function(){
        var search = translate($.trim($(".entsearch").val()));
        var str = '';
            str += search ? '/search/'+ encodeURI(search) : '';
            str += typeid ? '/type/'+typeid : '';
            
        window.location.href=site_url+'Subject/toollist' + str;
    });

    //搜索工具
    $('.entsearch').keydown(function(e){
        if(e.keyCode==13){
            var search = translate($.trim($(".entsearch").val()));
            var str = '';
            str += search ? '/search/'+ encodeURI(search) : '';
            str += typeid ? '/type/'+typeid : '';

            window.location.href=site_url+'Subject/toollist' + str;
        }
    });

    //排序
    $('#updateTime').click(function(){
        var field = $(this).attr("id");
        var code = $(this).attr('code');
        var str = '';
        if (search != '')str += '/search/'+translate(search);
        if(code == 'DESC'){
            location.href = site_url+'Subject/toollist' + str + '/sort/'+field+' ASC';
        }else if(code == 'ASC'){
            location.href = site_url+'Subject/toollist' + str + '/sort/'+field+' DESC';
        }else{
            location.href = site_url+'Subject/toollist' + str + '/sort/'+field+' DESC';
        }
    });
    
    //搜索分类
    $(".ccate").click(function(){
        var search = translate($.trim($(".scate").val()));
        window.location.href=site_url+'Subject/toolcate' + "/search/"+encodeURI(search);
    });

    //搜索分类
    $('.scate').keydown(function(e){
        if(e.keyCode==13){
            var search = translate($.trim($(".scate").val()));
            window.location.href=site_url+'Subject/toolcate' + "/search/"+encodeURI(search);
        }
    });

    
    //关闭管理层
    $(document).click(function(){
        $(".dropdown-menu").slideUp();


    });
    
    
    // $('.editBtn').click(function () {
    //     var cname = $(this).attr('cname');
    //     var cid = $(this).attr('cid');

    //     $('#catename').attr('placeholder', cname);
    //     $('#catename').attr('cid', cid);
    //     fnShow('#editname',"fadeOutUp","fadeInDown" );
        
    // });

    //修改类名操作
    $('#savecate').click(function () {
        var cname = $('#catename').val();

        var cid = $('#catename').attr('cid');
        if(cname== ''){
            $('#adderrormsg').html("未做修改");

        }else{
            $.ajax({
                url:site_url+"Subject/modtype",
                type:'POST',
                data:{typeName:cname, typeId: cid},
                dataType:"json",
                success:function(msg){
                    if(msg.code == '0000'){
                        $("#adderrormsg").html("修改成功");
                        window.location.reload();


                    }else {
                        $("#adderrormsg").html("类名已存在");
                    }
                }
            });
        }
    });
    
    //新增分类
    $('#addcate').click(function () {
        fnShow('#catebox',"fadeOutUp","fadeInDown" );
    });

    //删除分类
    
    //删除分类操作
    $('#deltype').click(function () {
        var cid = $('#cateid').val();

        $.ajax({
            url:site_url+"Subject/deltype",
            type:'POST',
            data:{typeId: cid},
            dataType:"json",
            success:function(msg){
                window.location.reload();
            }
        });

    });

    //单记录删除
    $(".delOne").click(function(){
        var code=$(this).attr('code');
        $('#okBtn').attr('code',code);
        fnShow("#one_del","fadeOutUp","fadeInDown");
    });
    //确定删除单记录
    $('#okBtn').click(function(){
        var toolcode = $(this).attr('code');
        fnHide("#one_del","fadeInDown","fadeOutUp",1);
        $.ajax({
            url:site_url+"Subject/del_tool",
            type:'post',
            data:{'toolcode':toolcode},
            dataType:'json',
            success:function(message){

                if(message.code=='0000'){

                    fnShow("#okBox","fadeOutUp","fadeInDown");
                    $('#okBox p.promptNews').html('删除成功');
                    setTimeout(function () {
                        window.location = site_url+"Subject/toollist";
                    },2000);
                }else{

                    $('#okBox p.promptNews').html('删除失败');
                    fnShow("#okBox","fadeOutUp","fadeInDown");
                    setTimeout("location.reload()",2000);
                }
            }

        })
    });

    //详情
    $(".detail").click(function(){
        $('#errorinfo').html('');
        fnShow("#detailinfo","fadeOutUp","fadeInDown");
        var code=$(this).attr('code');
        $.ajax({
            url:site_url+"Subject/get_detail",
            type:'post',
            data:{'code':code},
            dataType:'json',
            success:function(message){
                if(message.code=='0000'){
                    if(message.data['classifyName'] == null)
                    {
                        var cname = '';
                    } else {
                        var cname = message.data['classifyName'];
                    }
                    $('#ToolType').text(cname);
                    $('#ToolName').text(message.data['toolName']);
                    $('#description').text(message.data['description']);
                    $('#ToolUrl').text("在虚拟机内访问：http://172.16.4.2/tools");
                }
            },
            error:function(message){
            }

        })
    });

    //新增分类
    $('#addOk').click(function () {
        var typePid = $('#typePid option:selected').val();
        var typeName = $.trim($('#newType').val());

        if(typeName == '')
        {
            $('#adderror').html('请填写分类名称');
            return false;
        }

        $.ajax({
            url: site_url+ 'Subject/addtype',
            type: 'post',
            data: {TypeName: typeName, Pid: typePid},
            dataType: 'json',
            success: function(msg){
                if(msg.code == '0000')
                {
                    $('#adderror').html('分类新增成功');
                    fnHide("#catebox","fadeInDown","fadeOutUp");
                    setTimeout("location.reload()",1000);
                }else{
                    $('#adderror').html('分类已存在');
                }
            },
        });
    });
    
    //获取子分类
    $(".firstOnce .tableUp").click(function()
    {
        $(this).toggleClass("tableDown");
        var pid = $(this).attr('cid'); 
        if($("#tool"+pid+" .tableDown").height()>0){
            $.ajax({
                url: site_url+ 'Subject/getchild',
                type: 'post',
                data: {pid: pid},
                dataType: 'json',
                success: function(msg){
                    if(msg.code == '0000')
                    {
                        $.each(msg.data, function (i,item) {

                            var str = '';
                            str += '<tr class="secondOnce" >';
                            str += '<td><a href="#" class="queClass">'+item["classifyName"]+'</a></td>';
                            str += '<td><div class="dropdown">';
                            str += '<a class="treetable" >管理▼</a><ul class="dropdown-menu">';
                            str += '<li><a class="editBtn" cid="'+item["ID"]+'" cname="'+item["classifyName"]+'">修改类名</a></li>';
                            str += '<li><a class="deleteBtn" cid="'+item["ID"]+'">删除分类</a></li>';
                            str += '</ul></div></td></tr>';
                            $(str).insertAfter('#tool'+pid)                          
                             });

                        }
                     }

                });

        }
        else{
            var alPleng = $(".forQuestion tbody").children().length
            var ppre =$(this).parent().parent().siblings();
            var att = $(this).parent().parent().attr("lastGO");
            var pLengh = $(this).parent().parent().index()+1;
            for(i=pLengh;i<alPleng;i++){
                    if(ppre.eq(i).hasClass("firstOnce")&&att!=1){
                        ppre.slice(pLengh-1,i).remove()
                        break;

                    }
                    if(ppre.eq(alPleng-2).hasClass("secondOnce")&&att==1){
                        ppre.slice(pLengh-1,alPleng-1).remove()
                        break;
                    }
                }
        }

    })
   
    //控制分类管理
    $(function(){
        $(".firstOnce:odd").css({"background-color":'#fbfafa'})
        $(".firstOnce:last").attr("lastGo","1");
        var alPleng = $(".forQuestion tbody").children().length
        if($(".tableUp").length==0){
            for(i=0;i<alPleng;i++){
                $(".forQuestion tbody").children().eq(i).children().eq(0).addClass("noSecoend")
            }
        }
        else{
             $(".tableUp").css({"margin-left":'45%'})
        }
        

    })




});

$(function(){
    //弹出管理层
    $("#example-basic-expandable").on("click",".treetable",function(event){
     $(".dropdown-menu").slideUp();
        if($(this).next().css("display")=="none"){
            $(this).next().slideDown();
        }
        else{
            $(this).next().slideUp();
        }
         event.stopPropagation()
    });
    //修改类名
    $("#example-basic-expandable").on("click",".editBtn",function(){
        var cname = $(this).attr('cname');
        var cid = $(this).attr('cid');
        $("#catename").val('');
        $("#adderrormsg").html('');
        $('#catename').attr('placeholder', cname);
        $('#catename').attr('cid', cid);
        fnShow('#editname',"fadeOutUp","fadeInDown" );
    });
    //删除类名
    $("#example-basic-expandable").on("click",".deleteBtn",function(){
        var cid = $(this).attr('cid');
        $('#cateid').val(cid);

        fnShow('#delcate',"fadeOutUp","fadeInDown" );
    });


    //编辑类名
    $('.modtool').click(function () {
        var cid = $(this).attr('code');
        $('#editsBox #ok').attr('code', cid);
        $('#toolname').val('');
        $(".adderrormsg").html('');
        fnShow('#editsBox',"fadeOutUp","fadeInDown" );

    });
    
    //编辑类名
    $('#editsBox #ok').click(function () {
        var cid = $(this).attr('code');
        var toolname = $.trim($('#toolname').val());

        if(toolname == '')
        {
            $('#editsBox .adderrormsg').html('请填写工具名称');
            return false;
        }

        $.ajax({
            url: site_url+ 'Subject/modtname',
            type: 'post',
            data: {toolname: toolname, cid: cid},
            dataType: 'json',
            success: function(msg){
                if(msg.code == '0000')
                {
                    fnHide("#editsBox","fadeInDown","fadeOutUp");
                    setTimeout("location.reload()",1000);
                }else{
                    $('#editsBox .adderrormsg').html('工具名称已存在');
                }
            },
        });
    });
    
    
    
    
    
})
