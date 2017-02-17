$(function() {
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
    //全选 反选
    $("#checkAll").click(function(){
        arrTask = [];//统计之前清空防止重复
        if(this.checked){
            $("input[name='checkTask']").each(function(){
                this.checked=true;
                var code=$(this).attr("data-code");
                     arrTask.push(code);
            });

        }else{
            $("input[name='checkTask']").each(function(){
                this.checked=false;
            });

        }

    });
    //删除选中 弹出框
    $("#delAllBtn").click(function(){
        fnShow("#examBox","fadeOutUp","fadeInDown");
    });
    //删除选中 弹出框 确定按钮
    $("#delExamBtn").click(function(){
        if(arrTask.length <= 0){
            $('#examBox').hide();
            $('#okBox .promptNews').html('请选中要删除的考试任务');
            fnShow("#okBox","fadeOutUp","fadeInDown");
            setTimeout(function(){
                fnHide("#okBox","fadeInDown","fadeOutUp");
            },2000);

            return false;
        }
        del_exam(JSON.stringify(arrTask),'');

    });

    //删除单个 弹框 确定按钮
    $('#delBtn').click(function () {
        var taskcode = $('#delTaskCode').val();
        var scenetaskid = $('#delSceneTaskID').val();
        del_exam(taskcode,scenetaskid);
    });

    //删除方法
    function del_exam(code,scenetaskid) {
        $.ajax({
            url:site_url+'Education/del_exam',
            type:'post',
            data:{'code':code,'scenetaskid':scenetaskid},
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
    }


    //结束 弹出框 确定按钮
    $('#endBtn').click(function () {
        var taskcode = $('#endTaskCode').val();
        var scenetaskid = $('#endSceneTaskID').val();
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

});

var arrTask = new Array();//统计页面被选中的考试任务
function checkThis(isme,all){
    var inputNumbers =  $(isme+" tr input").length;
    arrTask = [];//统计之前清空防止重复
    $("input[name='checkTask']").each(function(){
        if(this.checked == true){
            var code=$(this).attr("data-code");
            arrTask.push(code);
        }
    });
    if(arrTask.length == inputNumbers){
        document.getElementById(all).checked = true;
    } else {
        document.getElementById(all).checked = false;
    }

}


//获取参数
function GetQueryString(name) {
    var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");

    var r = decodeURI(window.location.search.substr(1)).match(reg);
    if(r!=null)return  unescape(r[2]); return null;
}
var diff = ['初级','中级','高级'];
//查看任务是否结束
function ajax_exam_task() {
    $.ajax({
        url: site_url + 'Education/ajax_exam_task',
        type: 'post',
        data: {"search": GetQueryString("search"),"diff": GetQueryString("diff"),"sort": GetQueryString("sort"), "per_page": GetQueryString("per_page")},
        dataType: 'json',
        async: false,
        success: function (message) {
            var total = message.data.length;
            if(total > 0) {
                var studyinfo = '';
                for (var i = 0; i < total; i++) {
                    studyinfo += '<tr><td>';
                    var check_str = '';
                    if(message.data[i].TaskTypeJudge == 2){
                        var click = "'#exam_task','checkAll'";
                        var is_checked = '';
                        if($.inArray(message.data[i].TaskCode, arrTask) != -1){
                            is_checked = 'checked';
                        }
                        check_str = '<input type="checkbox" '+ is_checked +' data-code="'+ message.data[i].TaskCode +'" onclick="checkThis(click)" name="checkTask">';
                    }

                    studyinfo += check_str +'</td>';
                    studyinfo += '<td title="'+ message.data[i].TaskName +'"><a class="forRed" title="'+ message.data[i].TaskName +'" href="'+ site_url +'Education/examquestion?examid='+ message.data[i].ExamID+'" target="_blank">'+ message.data[i].TaskName+'</a> </td>';
                    var TaskTargetType = '';
                    if(message.data[i].TaskTargetType == 1){
                        TaskTargetType = '学员任务';
                    }else if(message.data[i].TaskTargetType == 2){
                        TaskTargetType = '班级任务';
                    }else{
                        TaskTargetType = '混合任务';
                    }
                    studyinfo += '<td>'+ TaskTargetType +'</td><td>'+ diff[message.data[i].ExamDiff] +'</td> <td >'+ getLocalTime(message.data[i].CreateTime) +'</td>';
                    studyinfo += '<td ><a title="'+ message.data[i].Progress +'%" href="'+ site_url +'Education/examdetail?taskcode='+ message.data[i].TaskCode+'"> <p class="tongji forBlue">统计详情</p><div class="proDiv"><div class="pro" style="width:'+ message.data[i].Progress +'%;"></div> </div></a></td>';
                    var end = '<a class="forRed endExam" onclick="endExam(this)" data-code="'+ message.data[i].TaskCode +'" href="javascript:;" ><i class="fa fa-stop-circle-o"></i>结束</a>';
                    if(message.data[i].TaskTypeJudge == 2){
                        end = '<a class="endspan">已结束</a>';
                    }
                    studyinfo += '<td>'+ end +'<a href="javascript:;" onclick="delExam(this)" data-code="'+ message.data[i].TaskCode +'" class="forRed delExam"><i class="fa fa-trash"></i>删除</a></td></tr>';
                    //到结束时间
                    if(message.data[i].Etime < 0 && message.data[i].TaskTypeJudge != 2){
                        time_end_exam(message.data[i].TaskCode,message.data[i].SceneTaskID);
                    }
                }

                $('#exam_task').html(studyinfo);

            }
        }
    })
}

function timeChange(m){return m<10?'0'+m:m }
function getLocalTime(nS) {
    var tt = new Date(parseInt(nS) * 1000)
    var y = tt.getFullYear();
    var m = tt.getMonth()+1;
    var d = tt.getDate();
    return y+'-'+timeChange(m)+'-'+timeChange(d);
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

$(function() {
    //更新列表
    setInterval(function () {
        ajax_exam_task();
    }, 5000)
});
    //更新列表后 类名触发事件不可用 解决办法
    //删除单个弹出框
    function delExam(element) {
    
        var taskcode = $(element).attr("data-code");
        var scenetaskid = $(element).attr("scenetaskid");
        $('#delTaskCode').val(taskcode);
        $('#delSceneTaskID').val(scenetaskid);
        fnShow("#delBox","fadeOutUp","fadeInDown");
    
    }
    //结束 弹出框
    function endExam(element) {
        var taskcode = $(element).attr("data-code");
        var scenetaskid = $(element).attr("scenetaskid");
        $('#endTaskCode').val(taskcode);
        $('#endSceneTaskID').val(scenetaskid);
        fnShow("#endBox","fadeOutUp","fadeInDown");
    }