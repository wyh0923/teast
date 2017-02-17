$(function() {
    //结束 弹出框
    $("#endStudy").click(function(){
        fnShow("#endBox","fadeOutUp","fadeInDown");
    });
    //删除 弹出框
    $("#delStudy").click(function(){
        fnShow("#delBox","fadeOutUp","fadeInDown");
    });
    //删除 弹框 确定按钮
    $('#delBtn').click(function () {
        $.ajax({
            url:site_url+'Education/del_study',
            type:'post',
            data:{'code':taskcode},
            async: false,
            dataType: 'json',
            success : function(message){
                $('#examBox').hide();
                $('#delBox').hide();
                $('#okBox .promptNews').html(message.msg);
                fnShow("#okBox","fadeOutUp","fadeInDown");

                setTimeout(function(){
                    fnHide("#okBox","fadeInDown","fadeOutUp");
                },2000);
                if(message.code == '0000'){
                    setTimeout(function(){
                        window.location.reload();
                    },2000);
                }
            }
        });
    });

    //结束 弹出框 确定按钮
    $('#endBtn').click(function () {
        $.ajax({
            url:site_url+'Education/end_study',
            type:'post',
            data:{'taskcode':taskcode},
            async: false,
            dataType: 'json',
            success : function(message){
                $('#endBox').hide();
                $('#okBox .promptNews').html(message.msg);
                fnShow("#okBox","fadeOutUp","fadeInDown");

                setTimeout(function(){
                    fnHide("#okBox","fadeInDown","fadeOutUp");
                },2000);
                if(message.code == '0000'){
                    setTimeout(function(){
                        window.location.reload();
                    },2000);
                }
            }
        });
    });


    //实时更新任务是否结束列表
    var setIntervalTask = setInterval(function () {
        ajax_study_list();
    }, 5000);


    //查看任务是否结束
    function ajax_study_list() {
        $.ajax({
            url: site_url + 'Education/ajax_study_list',
            type: 'post',
            data: {'taskcode':taskcode},
            dataType: 'json',
            async: false,
            success: function (message) {
                var total = message.data.length;
                if(total > 0) {
                    //到结束时间
                    if(message.data[0].Etime < 0 && message.data[0].TaskTypeJudge != 2){
                        time_end_study(message.data[0].TaskCode);
                    }

                    if(message.data[0].TaskTypeJudge == 2){

                        window.clearInterval(setIntervalTask); //任务已结束 清除定时器
                        
                        $('#endStudy').removeClass('publicNo').addClass('noCanBg');
                        $('#endStudy').unbind("click");
                    }

                }
            }
        })
    }


    //学习结束时间到 更改数据库
    function time_end_study(taskcode) {
        $.ajax({
            url: site_url + 'Education/end_study',
            type: 'post',
            data: {'taskcode': taskcode},
            dataType: 'json',
            success: function (message) {

            }
        })
    }
});

