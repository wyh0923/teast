/**
 * Created by qirupeng on 2016/8/24.
 */
var host_id = '';
var task_uuid = '';
var del_host_id = '';
var del_task_uuid = '';



$(function(){

    $(".btnNew").click(function(){
        $('input[name=description]').val('');
        $('input[name=ip]').val('');
        $('input[name=netmask]').val('');
        fnShow("#addPopBoxadduser","fadeOutUp","fadeInDown");
    });

    /*$("#add_okClose").click(function(){
        $('input[name=description]').val('');
        $('input[name=ip]').val('');
        $('input[name=netmask]').val('');
        $('#errors').html('');
        $('#hostProgress').html('');

        fnHide("addPopBoxadduser","fadeInDown","fadeOutUp");
    });*/

    $("#closeBtn_1").click(function(){
        fnHide("HintBox","fadeInDown","fadeOutUp");
    });

    $("#delclose").click(function(){
        $('#errors_del').html('');
        fnHide("delBox","fadeInDown","fadeOutUp");
    });
    $("#delBtn").click(function(){
        fnHide("delBox","fadeInDown","fadeOutUp");
    });

    //重启
    $(".restart").click(function(){
        var id = $(this).attr('nid');
        $('#restart_id').val(id);
        fnShow("#restartBox","fadeOutUp","fadeInDown");
    });

    //重启点击确定事件
    $("#restartBtn").click(function(){
        var hostId = $('#restart_id').val();
        $.ajax({
            url : site_url+'/System/node_operate',
            type : 'post',
            data : {"host_id":hostId,"handle":'reboot'},
            dataType : 'json',
            success : function(msg){
                if(msg.code == '0000'){

                    fnHide('#restartBox',"fadeInDown","fadeOutUp");
                    $('#okBox p.promptNews').html('该节点重启成功');
                    fnShow("#okBox","fadeOutUp","fadeInDown");
                    setTimeout(function(){
                        location.href = '';
                    },2000);
                }else{
                    fnHide('#restartBox',"fadeInDown","fadeOutUp");
                    $('#okBox p.promptNews').html('该节点重启失败 原因：'+msg.msg);
                    fnShow("#okBox","fadeOutUp","fadeInDown");

                }

            }
        });

        $('#okBox p.promptNews').html('<span><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>正在重启</span>');
        fnShow("#okBox","fadeOutUp","fadeInDown");
        fnHide('#restartBox',"fadeInDown","fadeOutUp");

    });

    //关机
    $(".shutdown").click(function(){
        var id = $(this).attr('nid');
        $('#shutdown_id').val(id);
        fnShow("#shutdownBox","fadeOutUp","fadeInDown");
    });

    $("#close2").click(function(){
        fnHide("shutdownBox","fadeInDown","fadeOutUp");
    });
    //关机点击确定事件
    $("#shutdownBtn").click(function(){
        var hostId = $('#shutdown_id').val();
        $.ajax({
            url : site_url+'/System/node_operate',
            type : 'post',
            data : {"host_id":hostId,"handle":'shutdown'},
            dataType : 'json',
            success : function(msg){
                if(msg.code == '0000'){

                    fnHide('#shutdownBox',"fadeInDown","fadeOutUp");
                    $('#okBox p.promptNews').html('该节点重启成功');
                    fnShow("#okBox","fadeOutUp","fadeInDown");
                    setTimeout(function(){
                        location.href = '';
                    },2000);
                }else{
                    fnHide('#shutdownBox',"fadeInDown","fadeOutUp");
                    $('#okBox p.promptNews').html('该节点关机失败 原因：'+msg.msg);
                    fnShow("#okBox","fadeOutUp","fadeInDown");

                }

            }
        });

        $('#okBox p.promptNews').html('<span><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>正在关机</span>');
        fnShow("#okBox","fadeOutUp","fadeInDown");
        fnHide('#shutdownBox',"fadeInDown","fadeOutUp");

    });
    //搜索
    $(".fa-search").click(function(){
        var search = $(".iptSearch-a").val();
        window.location.href= site_url + "/System/server?Search="+search+"&per_page=1";
    });
    $('.iptSearch-a').keydown(function(e){
        if(e.keyCode==13){
            var search = $(".iptSearch-a").val();
            window.location.href=site_url + "/System/server?Search="+search+"&per_page=1";
        }
    });

    var disclick = false;
    //添加节点 确定按钮操作
    $("#addHost").click(function(){
       // $('#addHost').css('background','#cccccc');
        //$('#nodeIp').attr('disabled',"true");

        var description = $('input[name=description]').val();
        var ip = $('input[name=ip]').val();
        var netmask = $('input[name=netmask]').val();
        var interface_port = $('input[name=interface_port]').val();
        var vnc_server_port = $('input[name=vnc_server_port]').val();
        var reg =  /^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$/

        if (description == ''){
            $('#errors').html('请填写节点名称');
            return;
        }
        if(ip == ''){
            $('#errors').html('请填写节点IP');
            $('#nodeIp').removeAttr('disabled');
            return ;
        }else if(!reg.test(ip)){
            $('#errors').html('IP填写不正确');
            $('#nodeIp').removeAttr('disabled');
            return ;
        }

        if(netmask != '' && !reg.test(netmask)){
            $('#errors').html('子网掩码填写不正确');
            return ;
        }else{
            $('#errors').html('');
        }

        if(interface_port == ''){
            $('#errors').html('请填写服务端口');
            return ;
        }else if(!(/^[0-9]*$/g.test(interface_port))){
            $('#errors').html('服务端口只能填写数字');
            return ;
        }else{
            $('#errors').html('');
        }
        if(vnc_server_port == ''){
            $('#errors').html('请填写远程桌面开放端口');
            return ;
        }else if(!(/^[0-9]*$/g.test(vnc_server_port))){
            $('#errors').html('远程桌面开放端口只能填写数字');
            return ;
        }else{
            $('#errors').html('');
        }

        if(disclick){
            return ;
        }else{
            disclick = true;
        }

        $.ajax({
            url : site_url+'/System/add_node',
            type : 'post',
            data : {"description":description,"ip":ip,"netmask":netmask,"interface_port":interface_port,"vnc_server_port":vnc_server_port},
            dataType : 'json',
            success : function(msg){

                if(msg.code == '0000'){
                    $('#addHost').css('background','#cccccc');
                    disclick= true;
                    task_uuid = msg.data.task_uuid;
                    host_id = msg.data.host_id;

                }else{
                    disclick= false;
                    $('#errors').html(msg.msg);
                }

            }
        });
        //按钮不可点击
        /*$('#addHost').css('background','#cccccc');
        $('#addHost').unbind("click");
        $('#addHost').css('cursor','default');*/

    });

    //删除节点
    $(".break").click(function(){
        var host_id = $(this).attr('nid');
        $('#host_id').val(host_id);
        fnShow("#delBox","fadeOutUp","fadeInDown");
    });
    //确认删除节点
    $("#offBtn").click(function(){
        $('.deleteNodeBtn').css('background','#cccccc');
        $('.deleteNodeBtn').removeAttr('id');
        $('.deleteNodeBtn').attr('disabled',"true");

        var host_id = $('#host_id').val();
        $.ajax({
            url : site_url+'/System/del_node',
            type : 'post',
            data : {"host_id":host_id},
            dataType : 'json',
            success : function(msg){
                if(msg.code == '0000'){

                    /*del_task_uuid = msg.data.task_uuid;
                    del_host_id = msg.data.host_id;*/
                    $('#errors_del').html('请耐心等待，节点正在删除...');
                    setTimeout(function(){
                        window.location.reload();
                    },10000);

                }else {
                    $('#errors_del').html(msg.msg);
                }

            },
        })

    })

});


setIntervalProgress();
//定时器
setInterval(function(){
    //添加进度
    setIntervalProgress();
    //删除进度
    DelsetIntervalProgress();
},1000)

//查看删除进度
function DelsetIntervalProgress(){
    if(del_task_uuid !=''){
        $.ajax({
            url : site_url+'/System/get_task_progress',
            type : 'post',
            data : {"task_uuid":del_task_uuid},
            dataType : 'json',
            success : function(msg){
                if( msg.code == '0000' ) {

                    if( msg.data.task_status == 6 ) { // 成功
                        $('#errors_del').html('节点删除成功');

                        setTimeout(function(){
                            window.location.reload();
                        },2000);

                    } else if( msg.data.task_status == 4 || msg.data.task_status == 7 || msg.data.task_status == 8) { // 失败
                        $('#errors_del').html('节点删除失败&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;原因：'+msg.data.percent_description);


                    }else{
                        var percent=msg.data.task_percent;

                        $('#errors_del').html('节点删除进度：'+percent+'%&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;进度描述：'+msg.data.percent_description);
                    }

                } else {

                    $('#errors_del').html('请求失败,请检查网络！');
                }

            },
        })
    }
}

//查看添加进度
function setIntervalProgress(){
    if(task_uuid !=''){
        $.ajax({
            url : site_url+'/System/get_task_progress',
            type : 'post',
            data : {"task_uuid":task_uuid},
            dataType : 'json',
            success : function(msg){
                if( msg.code == '0000' ) {
                    if( msg.data.task_status == 6 ) { // 成功
                        $('#errors').html('节点添加成功');

                        setTimeout(function(){
                            window.location.reload();
                        },2000);

                    } else if(msg.data.task_status == 4 || msg.data.task_status == 7 || msg.data.task_status == 8) { // 失败
                        $('#errors').html('节点添加失败&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;原因：'+msg.data.percent_description);

                    }else{
                        var percent=msg.data.task_percent;

                        $('#errors').html('节点添加进度：'+percent+'%&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;进度描述：'+msg.data.percent_description);
                    }

                } else {

                    $('#errors').html('请求失败,请检查网络！');
                }

            }
        })
    }

}