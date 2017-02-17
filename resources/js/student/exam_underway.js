//开始考试
function gotoExam(element){
    var taskid = $(element).attr('taskid');
    $.ajax({
        url: site_url + 'Exam/is_start',
        type: 'post',
        data: {'taskid': taskid},
        dataType: 'json',
        success: function (message) {
            if(message.code == '0000'){
                //新窗口打开
                var url = site_url + 'Exam/exampaper?taskid='+ taskid;
                window.open(url);
            }else{
                $('#okBox .promptNews').html(message.msg);
                fnShow("#okBox","fadeOutUp","fadeInDown");
                setTimeout(function(){
                    fnHide("#okBox","fadeInDown","fadeOutUp");
                    window.location.reload();
                },2000);
            }
        }
    })
}
//获取参数
function GetQueryString(name) {
    var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");

    var r = decodeURI(window.location.search.substr(1)).match(reg);
    if(r!=null)return  unescape(r[2]); return null;
}

//实时更新未开始考试列表
function get_exam_list(element) {
    /*
    * 更新未开始考试列表
    * 获取url参数 也要加上排序和搜索的参数
    * 没有数据和有数据的时候样式
    * */
    var total = 0;
    var examinfo = '';
    $.ajax({
        url: site_url + 'Exam/get_exam_list',
        type: 'post',
        data: {"sort":GetQueryString("sort"),"search":GetQueryString("search"),"per_page":GetQueryString("per_page")},
        dataType: 'json',
        async : false,
        success: function (message) {
            total = message.msg;//总数
            var page_total = message.data.length;//该页的条数
            $('.noNews').removeClass('block').addClass('outHide');
            if(page_total > 0){
                examinfo = '';
                for (var i=0; i<page_total; i++) {
                    var lists = message.data[i];
                    examinfo += '<tr><td><a class="operater forRed" onclick="gotoExam(this)" taskid="'+ lists.TaskID +'" title="'+ lists.TaskName +'">'+ lists.TaskName +'</a></td>';
                    examinfo += '<td title="'+ frienddate(lists.TaskTime) +'">'+ frienddate(lists.TaskTime) +'</td>';
                    var str = '';
                    if(lists.ExamType&1){
                        str += "单选题 ";
                    }
                    if(lists.ExamType&2){
                        str += "多选 ";
                    }
                    if(lists.ExamType&4){
                        str += "判断 ";
                    }
                    if(lists.ExamType&8){
                        str += "填空 ";
                    }
                    if(lists.ExamType&16){
                        str += "夺旗题 ";
                    }
                    if(lists.ExamType&32){
                        str += "场景题 ";
                    }
                    examinfo += '<td title="'+ str +'">'+ str +'</td>';
                    examinfo += '<td title="'+ lists.UserName +'">'+ lists.UserName +'</td>';

                    if(lists.Stime < 0 && lists.Etime > 0){
                        examinfo += '<td>已开始</td>';
                    }else if(lists.Etime < 0){
                        examinfo += '<td>已结束</td>';
                        //考试时间结束
                        time_end_exam(lists.TaskID);
                    }else{
                        var hour = 60*60;
                        if(lists.Stime >= hour){
                            examinfo += '<td>' + parseInt(lists.Stime/hour) +'小时'+parseInt(lists.Stime % (hour)/60) +'分钟</td>';
                        }else if(lists.Stime < 60){
                            examinfo += '<td>' + parseInt(lists.Stime%60) +'秒 后开始</td>';
                        }else{
                            examinfo += '<td>' + parseInt(lists.Stime/60) +'分钟 后开始</td>';
                        }
                    }
                    examinfo += '<td><a class="forOrange" onclick="gotoExam(this)" taskid="'+ lists.TaskID +'"><i class="fa fa-play-circle-o bgGreen"></i>开始考试</a>';
                    examinfo += '<a class=" forRed" onclick="examBox(this)" taskid="'+ lists.TaskID +'"><i class="fa fa-stop-circle-o" aria-hidden="true"></i>结束考试</a></td>';
                    examinfo += '</tr>';
                }

                $('#examlist').removeClass('outHide');
                $('#examinfo').html(examinfo);

            } else {
                $('#examinfo').html('');
                $('#examlist').removeClass('block').addClass('outHide');
                $('#noNews').removeClass('outHide').addClass('block');
                $('#selfPage').hide();
            }

        }
    });

    $('#totalNum').html('共计：'+ total +'套');

}
//时间处理
function frienddate(dTime) {
    var taskTime = '';
    if (dTime < 60) {
        taskTime = dTime + '秒';
    } else if (dTime < 3600) {
        var minute = parseInt(Math.floor(dTime % 60)) ? parseInt(Math.floor(dTime % 60)) + '秒' : '';//三目运算符
        taskTime = parseInt(Math.floor(dTime / 60)) + '分钟' + minute;
    } else if (dTime < 86400) {
        var hour = parseInt(Math.floor(dTime % 3600/60)) ? parseInt(Math.floor(dTime % 3600/60)) + '分钟' : '';//三目运算符
        taskTime = parseInt(Math.floor(dTime / 3600)) + '小时' + hour;
    } else if (dTime < 86400*30) {
        var day = parseInt(Math.floor(dTime % 86400/3600)) ? parseInt(Math.floor(dTime % 86400/3600)) + '小时' : '';//三目运算符
        taskTime = parseInt(Math.floor(dTime / 86400)) + '天'+ day;
    } else{
        var month = parseInt(Math.floor(dTime % (86400*30)/86400)) ? parseInt(Math.floor(dTime % (86400*30)/86400)) + '天' : '';//三目运算符
        taskTime = parseInt(Math.floor(dTime / (86400*30))) + '月'+ month;
    }
    return taskTime;

}
//考试结束时间到 更改数据库
function time_end_exam(taskid) {
    $.ajax({
        url: site_url + 'Exam/endexam',
        type: 'post',
        data: {'taskid': taskid},
        dataType: 'json',
        success: function (message) {

        }
    })
}

//结束考试提示框
function examBox(element){
    var taskid = $(element).attr('taskid');
    $('#taskid').val(taskid);
    fnShow("#examBox","fadeOutUp","fadeInDown");
}

$(function() {
    //结束考试 确定按钮
    $("#endExam").click(function(){
        var taskid = $('#taskid').val();
        $.ajax({
            url: site_url + 'Exam/endexam',
            type: 'post',
            data: {'taskid': taskid},
            dataType: 'json',
            success: function (message) {
                $('#examBox').hide();
                
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
        })
    });
    
    //搜索
    $(".fa-search").click(function () {
        var search = encodeURIComponent($(".iptSearch-a").val().trim());
        window.location.href = search_url + "search=" + search;
    });
    $('.iptSearch-a').keydown(function (e) {
        if (e.keyCode == 13) {
            var search = encodeURIComponent($(".iptSearch-a").val().trim());
            window.location.href = search_url + "search=" + search;
        }
    });
    
    get_exam_list();
    //更新列表
    setInterval(function () {
        get_exam_list();
    }, 5000)
});