//开始学习
function gotostudy(element){
    var taskid = $(element).attr('taskid');
    $.ajax({
        url: site_url + 'Study/is_start',
        type: 'post',
        data: {'taskid': taskid},
        dataType: 'json',
        success: function (message) {
            if(message.code == '0000'){
                window.location.href = site_url + 'Study/studydetail?taskid='+ taskid;
            }else{
                $('#okBox .promptNews').html(message.msg);
                fnShow("#okBox","fadeOutUp","fadeInDown");
                setTimeout(function(){
                    fnHide("#okBox","fadeInDown","fadeOutUp");
                    window.location.reload();
                },2000)
            }
        }
    })
}
//结束学习提示框
function studyBox(element){
    var taskid = $(element).attr('taskid');
    $('#taskid').val(taskid);
    fnShow("#studyBox","fadeOutUp","fadeInDown");
}

//获取参数
function GetQueryString(name) {
    var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");

    var r = decodeURI(window.location.search.substr(1)).match(reg);
    if(r!=null)return  unescape(r[2]); return null;
}
var diff = ['初级','中级','高级'];
//实时更新学习进度
var ajax_num = 0;
function get_study_ajax() {
    var total = 0;
    var studyinfo = '';
    $.ajax({
        url: site_url + 'Study/get_study_ajax',
        type: 'post',
        data: {"search":GetQueryString("search"),"per_page":GetQueryString("per_page")},
        dataType: 'json',
        async : false,
        success: function (message) {
            total = message.msg;//总数
            var page_total = message.data.result.length;//该页的条数
            $('.noNews').removeClass('block').addClass('outHide');
            if(page_total > 0) {
                var errorImg = "'"+base_url+"resources/files/img/course/logo.png"+"'";
                for (var i=0; i<page_total; i++) {
                    var lists = message.data.result[i];
                    var img = lists.PackageImg ? base_url+"resources/files/img/course/"+lists.PackageImg:base_url+"resources/files/img/course/logo.png";

                    studyinfo += '<div class="tasklist clearfix" data-TaskID="'+ lists.TaskID +'"> <div class="taskimg">';
                    //解决：找不到这个图片  默认显示的时候  默认图片会闪下
                    var imgAttr = $('#img'+lists.TaskID).attr('src');

                    if(ajax_num == 0){//第一次加载
                        //self-picture
                        studyinfo += '<a title="'+ lists.TaskName +'" href="javascript:;" onclick="gotostudy(this)" taskid="'+ lists.TaskID +'"> <img alt="'+ lists.TaskName +'" id="img'+ lists.TaskID +'" src="'+ img +'" onerror="avascript:this.src='+ errorImg +'"> </a></div>';
                    }else if(img != imgAttr && imgAttr){
                        //默认图片
                        studyinfo += '<a title="'+ lists.TaskName +'" href="javascript:;" onclick="gotostudy(this)" taskid="'+ lists.TaskID +'"> <img alt="'+ lists.TaskName +'" id="img'+ lists.TaskID +'" src="'+ base_url+"resources/files/img/course/logo.png" +'"> </a></div>';
                    }else{
                        //self-picture
                        studyinfo += '<a title="'+ lists.TaskName +'" href="javascript:;" onclick="gotostudy(this)" taskid="'+ lists.TaskID +'"> <img alt="'+ lists.TaskName +'" id="img'+ lists.TaskID +'" src="'+ img +'" onerror="avascript:this.src='+ errorImg +'"> </a></div>';
                    }

                    studyinfo += '<div class="taskinfo"><div class="taskName"><span class="TaskName"><a class="move" title="'+ lists.TaskName +'" href="javascript:;" onclick="gotostudy(this)" taskid="'+ lists.TaskID +'" >'+ lists.TaskName +'</a> </span></div>';
                    var day =  (lists.TaskEndTime - message.data.time)/86400;
                    var hour = (lists.TaskEndTime - message.data.time)/3600;
                    var min = (lists.TaskEndTime - message.data.time)/60;
                    if(day >= 1){
                        var time = '本任务将于'+ parseInt(day) +"天后结束";
                    }else if(hour >= 1){
                        var time  = '本任务将于'+ parseInt(hour) +"小时后结束";
                    }else if(min >= 1){
                        var time  = '本任务将于'+ parseInt(min) +"分钟后结束";
                    }else{
                        var time  = '已结束';
                    }

                    //简介
                    var PackageDesc = lists.PackageDesc;
                    if(lists.PackageDesc.length > 93){
                        var PackageDesc = lists.PackageDesc.substring(0,93);
                    }

                    var xiafa =  '班级：'+lists.ClassName;
                    if(lists.ClassID == null){
                        var xiafa = message.data.username;
                    }

                    studyinfo += '<div class="taskinfoabout"> '+ lists.UserName +' 于 '+ getLocalTime(lists.TaskStartTime) + ' 下发  给'+ xiafa +'<span class="finshTime">'+ time +'</span> </div> <p title="'+ lists.PackageDesc +'">'+ PackageDesc +'</p>';
                    studyinfo += '<div class="taskmore clearfix"><span class="jiBie"><i class="fa fa-star" title="课程难度"></i>'+ diff[lists.PackageDiff] +'</span>';
                    studyinfo += '<span class="tasktotal">]<i class="fa fa-navicon" title="课程小节总数"></i>共'+ lists.SectionNum +'节</span><span class="tasktime"><i class="fa fa-calendar" title="当前任务结束日期"></i>'+ getLocalTime(lists.TaskEndTime) +'</span>';
                    studyinfo += '<div class="nums"><span class="ctaskprogr" ><span class="taskpro" style="width:'+ lists.TaskProcess +'%;"></span></span><span class="percentNum">'+ lists.TaskProcess +'%</span></div>';
                    studyinfo += '<a class="stopBtnDe" href="javascript:;" onclick="studyBox(this)" taskid="'+ lists.TaskID +'"><i class="fa fa-stop-circle-o"></i>结束学习</a>';
                    studyinfo += '<a class="btnRelease" href="javascript:;" onclick="gotostudy(this)" taskid="'+ lists.TaskID +'"><i class="fa fa-bookmark-o"></i>开始学习</a>';
                    studyinfo += '</div></div></div>';

                    //到结束时间
                    if(lists.Etime < 0){
                        time_end_study(lists.TaskID);
                    }

                    ajax_num++;  //图片问题 [勿删]
                }
                $('#studyinfo').html(studyinfo);

            } else {
                $('#studyinfo').html('');
                $('#noNews').removeClass('outHide').addClass('block');
                $('#selfPage').hide();
            }
        }
    });
    $('#totalNum').html('共计：'+ total +'套');

}

//中国标准时间转换成标准格式
function timeChange(m){return m<10?'0'+m:m }
function getLocalTime(nS) {
    var tt = new Date(parseInt(nS) * 1000)
    var y = tt.getFullYear();
    var m = tt.getMonth()+1;
    var d = tt.getDate();
    return y+'-'+timeChange(m)+'-'+timeChange(d);
}

//学习结束时间到 更改数据库
function time_end_study(taskid) {
    $.ajax({
        url: site_url + 'Study/endstudy',
        type: 'post',
        data: {'taskid': taskid},
        dataType: 'json',
        success: function (message) {

        }
    })
}

$(function() {
    //结束学习
    $("#endStudy").click(function(){
        var taskid =$('#taskid').val();
        $.ajax({
            url: site_url + 'Study/endstudy',
            type: 'post',
            data: {'taskid': taskid},
            dataType: 'json',
            success: function (message) {
                $('#studyBox').hide();

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

    get_study_ajax();
    //更新列表
    setInterval(function () {
       get_study_ajax();
    }, 5000)
  
});

