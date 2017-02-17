$(function() {
    //详情和排名切换
    $("#tableMore2").click(function(){
        $("#topTen").addClass('outHide');
        $("#studyTable").show();
        $("#selfPage").show();
    });
    $("#topMore").click(function(){
        $("#topTen").removeClass('outHide');
        $("#studyTable").hide();
        $("#selfPage").hide();
    });
    //结束 弹出框
    $("#endExam").click(function(){
        fnShow("#endBox","fadeOutUp","fadeInDown");
    });
    //删除 弹出框
    $("#delExam").click(function(){
        fnShow("#delBox","fadeOutUp","fadeInDown");
    });
    //删除 弹框 确定按钮
    $('#delBtn').click(function () {
        $.ajax({
            url:site_url+'Education/del_exam',
            type:'post',
            data:{'code':taskcode,'scenetaskid':scenetaskid},
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
            url:site_url+'Education/end_exam',
            type:'post',
            data:{'taskcode':taskcode,'scenetaskid':scenetaskid},
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

    //获取参数
    function GetQueryString(name) {
        var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");

        var r = decodeURI(window.location.search.substr(1)).match(reg);
        if(r!=null)return  unescape(r[2]); return null;
    }

    var type = ['未考试','考试中','已交卷'];
    //实时更新图像 学员数据
    function score_student(){
        var tableList = '';
        var topTen = '';
        var arr_userscore = [];
        for (var i=0 ; i<10;i++){
            arr_userscore[i] = 0;
        }
        $.ajax({
            url:site_url+'Education/score_student',
            type:'post',
            data:{'taskcode':taskcode,'per_page':GetQueryString("per_page")},
            async: false,
            dataType: 'json',
            success : function(message){
                if(message.code == '0000'){
                    total = message.msg;
                    if(total > 0) {
                        var page_student = message.data.page_student;
                        var is_end = 1;
                        //排名详情
                        for (var i = 0; i < total; i++) {
                            if(page_student[i].TaskType != 2){
                                is_end = 0;
                            }
                            
                            tableList += '<tr><td title=""><a class="studentExam"onclick="studentExam(this)" taskid="'+ page_student[i].TaskID +'" tasktype="'+ page_student[i].TaskType +'">'+ page_student[i].UserName +'</a>';
                            var finishtime=0;
                            if(page_student[i].TaskType==2){//结束考试才显示 
                            	 if(page_student[i].TaskFinishedTime=='' || page_student[i].TaskFinishedTime==null){
                                 	finishtime=page_student[i].TaskEndTime;
                                 }else{
                                 	finishtime=page_student[i].TaskFinishedTime;
                                 }                   
                                 finishtime = new Date(finishtime * 1000).Format("yyyy-MM-dd hh:mm:ss"); 
                            }else{
                            	finishtime='';
                            }
                           
                            tableList +='</td><td><div><p>'+ type[page_student[i].TaskType] +'</p></div></td><td>'+finishtime+'</td><td>'+ page_student[i].TaskScore +'分</td> </tr>';

                        }
                        //top10
                        var top_total = message.data.top_student.length;
                        for (var i = 0; i < top_total; i++) {
                            topTen +='<div class="rankdiv"><div id="" class="showdiv"><div class="desc"><span>'+ page_student[i].UserName +'</span> </div> <div class="progress clearfix"> <div class="wholeBg">';
                            topTen +='<div class="progress-bar" role="progressbar" style="width:'+ page_student[i].TaskScore +'%;">';
                            topTen +='</div></div><div class="percent">'+ page_student[i].TaskScore +'分/共100分</div> </div></div></div>';
                        }
                        //图形
                        var all_total = message.data.all_student.length;
                        for (var i = 0; i < all_total; i++) {
                            //图形 属于该分段 加一 [分数除10.1取整作为下标]
                            var index = Math.floor(message.data.all_student[i].TaskScore/10.1);
                            arr_userscore[index] = arr_userscore[index]+1;
                        }

                        //都已交卷
                        if(is_end == 1){
                            window.clearInterval(setIntervalScore); //清除定时器
                            $('#endExam').removeClass('publicNo').addClass('noCanBg');
                            $('#endExam').unbind("click");
                        }
                    }else{
                        //没有学员时
                        window.clearInterval(setIntervalScore); //清除定时器
                    }
                }
            }
        });//alert(arr_userscore);
        $('#tableList').html(tableList);
        $('#topTen').html(topTen);
        graph_exam(arr_userscore);
    }

    //图标
    function graph_exam(arr_userscore) {
        var maxarr = Math.max.apply(Math,arr_userscore);
        //alert(maxarr);
        $('#examsNews').highcharts({
            chart: {
                type: 'column',
                margin: [50, 10, 60, 60],
                width:580,
                hight:200
            },
            title: {
                text: '考试详情',
                style:{
                    fontFamily: 'Microsoft YaHei,微软雅黑',
                    fontSize: '16px'
                }
            },
            credits: {
                text: '',
                href: ''//下表
            },
            xAxis: {
                categories:["0~10","10~20","20~30","30~40","40~50","50~60","60~70","70~80","80~90","90~100"]  ,//categories:arr_username  ,
                labels: {
                    rotation: 0,
                    align: 'center',
                    style: {
                        fontSize: '12px',
                        fontFamily: 'Microsoft YaHei,微软雅黑'
                    }
                }
            },
            yAxis: {
                min: 0,
                max:maxarr+1,
                tickInterval:5,
                title: {
                    text: '人数'
                },
                labels:{
                    step:1,
                    staggerLines:6
                }
            },
            plotOptions: {
                series: {
                    animation: false //false 刷新页面 柱形图不变  true 刷新页面 柱形图变话
                },
                column: {
                    pointPadding: 0.2,
                    pointWidth: 22 //柱子的宽度30px
                }
            },
            legend: {
                enabled: false
            },
            tooltip: {
                pointFormat: '考试人数: <b>{point.y:.0f} 人</b>'
            },
            series: [{
                name: 'studentCourse',
                dataLabels: {
                    enabled: true,
                    rotation: 0,
                    color: '#FFFFFF',
                    align: 'center',
                    x: 0,
                    y: 6,
                    style: {
                        fontSize: '12px',
                        fontFamily: 'Microsoft YaHei,微软雅黑',
                        textShadow: '0 0 3px black'
                    }
                },
                data: arr_userscore
            }]
        });
    }

    score_student();
    //定时器
    var setIntervalScore = setInterval(function(){
        //积分
        score_student();


    },3000);

    var setIntervalTask = setInterval(function(){
        //实时更新任务是否结束
        ajax_exam_task();

    },3000);
    //查看任务是否结束
    function ajax_exam_task() {
        $.ajax({
            url: site_url + 'Education/ajax_exam_task',
            type: 'post',
            data: {'taskcode':taskcode},
            dataType: 'json',
            async: false,
            success: function (message) {
                var total = message.data.length;
                if(total > 0) {
                    //到结束时间
                    if(message.data[0].Etime < 0 && message.data[0].TaskTypeJudge != 2){
                        time_end_exam(message.data[0].TaskCode,message.data[0].SceneTaskID);
                    }
                    if(message.data[0].TaskTypeJudge == 2){

                        window.clearInterval(setIntervalTask); //任务已结束 清除定时器

                        $('#endExam').removeClass('publicNo').addClass('noCanBg');
                        $('#endExam').unbind("click");
                    }

                }
            }
        })
    }

    //学习结束时间到 更改数据库
    function time_end_exam(taskcode,scenetaskid) {
        $.ajax({
            url: site_url + 'Education/end_exam',
            type: 'post',
            data: {'taskcode': taskcode,'scenetaskid':scenetaskid},
            dataType: 'json',
            success: function (message) {

            }
        })
    }
});

//查看学员试卷
function studentExam(element) {
    var taskid = $(element).attr('taskid');
    var tasktype = $(element).attr('tasktype');
    if(tasktype == '2'){
        var stu_url = site_url + "Education/studentexam?taskid=" + taskid;
        window.open(stu_url);
    }else{
        $('#okBox .promptNews').html('尚未交卷，暂不能查看详情');
        fnShow("#okBox","fadeOutUp","fadeInDown");
        setTimeout(function(){
            fnHide("#okBox","fadeInDown","fadeOutUp");
        },2000);
    }
}

Date.prototype.Format = function (fmt) {
    var o = {
        "M+": this.getMonth() + 1, //月份
        "d+": this.getDate(), //日
        "h+": this.getHours(), //小时
        "m+": this.getMinutes(), //分
        "s+": this.getSeconds(), //秒
        "q+": Math.floor((this.getMonth() + 3) / 3), //季度
        "S": this.getMilliseconds() //毫秒
    };
    if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    for (var k in o)
        if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
    return fmt;
};
