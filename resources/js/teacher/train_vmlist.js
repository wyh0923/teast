/**
 * Created by qirupeng on 2016/9/1.
 */
$(function(){
    $(".fa-search").click(function(){
        var search = $.trim($(".iptSearch-a").val());
        var str = '';
        if (cpu != '')str += '/cpu/'+ cpu;
        if (memory != '')str += '/memory/'+ memory;
        if (os != '')str += '/os/'+ os;
        window.location.href= site_url + 'Train/vmlist' +str+ "/search/"+encodeURI(translate(search));
    });
    $('.iptSearch-a').keydown(function(e){
        if(e.keyCode==13){
            var search = $.trim($(".iptSearch-a").val());
            var str = '';
            if (cpu != '')str += '/cpu/'+ cpu;
            if (memory != '')str += '/memory/'+ memory;
            if (os != '')str += '/os/'+ os;
            window.location.href= site_url + 'Train/vmlist' +str+ "/search/"+encodeURI(translate(search));
        }
    });
});
$(function(){
    /*删除弹出框*/
    $('.forRed').click(function(){
        var code = $(this).attr('code');
        $('.okBtn').attr('code',code);
        var host_id = $(this).attr('host_id');
        $('.okBtn').attr('host_id',host_id);
        fnShow("#vmtemplatelistTablePopBox","fadeOutUp","fadeInDown")
    });

    $('.forYellow').click(function(){
        $('.vmbug').css({"text-align":'center'});
        var code = $(this).attr('code');
        var host_id = $(this).attr('host_id');
        $.ajax({
            url:site_url+"Train/vminfo",
            type:'post',
            data:{'code':code,'host_id':host_id},
            dataType:'json',
            success:function(message){
                if(message.code=='0000'){
                    $('.vmName').text(message.data.vm_tpl_name);
                    $('.vmCpu').text(cputype[message.data.cpu]);
                    $('.vmDisk').text(message.data.disk_size + message.data.disk_size_unit);
                    $('.vmMemory').text(message.data.memory_size + message.data.memory_size_unit);
                    $('.vmSystem').text(message.data.os_type_name);
                    $('.vmbug').text(message.data.description);
                    $('.username').text(message.data.user_name);
                    $('.userpassword').text(message.data.user_pwd);
                    //处理样式问题
                    //var newChang = $(".ctfPopNews table tr:last").children().eq(1);
                    if($('.vmbug').text().length>28){
                        $('.vmbug').css({"text-align":'left'});
                    }
                    fnShow("#vmTemplateDetail","fadeOutUp","fadeInDown");
                }
            }
        })
    });


    $('.okBtn').click(function(){
        var code = $(this).attr('code');
        var host_id = $(this).attr('host_id');
        $.ajax({
            url:site_url+"Train/del_vm",
            type:'post',
            data:{'code':code,'host_id':host_id},
            dataType:'json',
            success:function(message){
                $('#vmtemplatelistTablePopBox').hide();
                if(message.code=='0000'){
                    //fnHide("#vmtemplatelistTablePopBox","fadeInDown","fadeOutUp");
                    $('#okBox p.promptNews').html('删除成功');
                    fnShow("#okBox","fadeOutUp","fadeInDown");
                    setTimeout(function(){
                        //fnHide("#okBox","fadeInDown","fadeOutUp");
                        location.href = site_url+'Train/vmlist';
                    },2000);

                }else{
                    //fnHide("#vmtemplatelistTablePopBox","fadeInDown","fadeOutUp");
                    $('#okBox p.promptNews').html(message.msg);
                    fnShow("#okBox","fadeOutUp","fadeInDown");
                    setTimeout(function(){
                        location.href = site_url+'Train/vmlist';
                        //fnHide("#okBox","fadeInDown","fadeOutUp");
                    },2000);

                }
            }
        })
    });

});
