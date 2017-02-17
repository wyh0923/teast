$(function() {

    //点击按钮 进入场景
    $('.successScene').click(function(){
        if(sceneinstanceuuid == ''){
            $('#hintBox .promptNews').html('场景不存在');
            fnShow("#hintBox","fadeOutUp","fadeInDown");
            return false;
        }
        //判断场景
        judge_scene();
    });
    //判断场景[共用小节场景判断]
    function judge_scene(){
        $.ajax({
            url:site_url+'Study/judge_scene',
            type:'post',
            data:{'sceneinstanceuuid':sceneinstanceuuid},
            async: false,
            dataType: 'json',
            success : function(message){
                if(message.code == '0000'){
                    //进入场景
                    enter_scene(sceneinstanceuuid);
                }else{
                    //场景不存在清空数据库的值
                    update_scene();
                }
            }
        })
    }

    //进入场景[共用小节进入场景]
    function enter_scene() {
        $.ajax({
            url:site_url+'Study/enter_scene',
            type:'post',
            data:{'sceneinstanceuuid':sceneinstanceuuid},
            async: false,
            dataType: 'json',
            success : function(message){
                if(message.code == '0000'){
                    var url = site_url +'Exam/vm_vnc?uuid='+ sceneinstanceuuid +'&sectionname='+ taskname +'&token='+ message.data.token +'&port='+ message.data.port +'&ip='+ message.data.ip +'&loguser='+ message.data.loguser +'&vmuuid='+ message.data.vmuuid + '&logpwd=' + message.data.logpwd + '&sid=' + id + '&host_id=' + message.data.host_id + '&scene_end_time=' + message.data.SceneInstance.end_time;
                    window.open(url, 'e春秋',"channelmode=yes,height=800, width=1100, toolbar=no, titlebar=no, menubar=no, scrollbars=no, resizable=no, location=no, status=no");

                }else if(message.code == '1004'){
                    //场景在中间件中 找不到
                    update_scene();
                }else{
                    $('#hintBox .promptNews').html(message.msg);
                    fnShow("#hintBox","fadeOutUp","fadeInDown");
                    setTimeout(function(){
                        fnHide("#hintBox","fadeInDown","fadeOutUp");
                    },2000);
                }
            }
        })
    }

    //场景不存在清空数据库的值
    function update_scene() {
        $.ajax({
            url:site_url+'Exam/update_scene',
            type:'post',
            data:{'id':id},
            async: false,
            dataType: 'json',
            success : function(message){
                if(message.code == '0000'){
                    $('#hintBox .promptNews').html('场景不存在');
                    fnShow("#hintBox","fadeOutUp","fadeInDown");
                }
            }
        })
    }

    //页面加载后  如果是进入场景 判断场景是否存在
    if(sceneinstanceuuid != '' && taskuuid == ''){
        $.ajax({
            url:site_url+'Study/judge_scene',
            type:'post',
            data:{'sceneinstanceuuid':sceneinstanceuuid},
            async: false,
            dataType: 'json',
            success : function(message){
                if(message.code != '0000'){
                    //场景不存在清空数据库的值
                    update_scene();
                }
            }
        })
    }

    //提交答案弹出框
    $('#submitexam').click(function () {
        fnShow("#examBox","fadeOutUp","fadeInDown");
    });

    //提交答案
    $('#handpaper').click(function () {
        window.clearInterval(setIntervalExam); //清除定时器
        $.ajax({
            url: site_url + 'Exam/handpaper',
            type: 'post',
            data: {'taskid': taskid},
            dataType: 'json',
            success: function (message) {
                if(message.code = '0000'){

                    $('#ExamHintBox .promptNews').addClass('promptUp');
                    $('#ExamHintBox .promptNews').addClass('colorYe');
                    $('#ExamHintBox .promptNews').html('<i class="fa fa-check-circle-o"></i>试卷提交成功!');
                    fnShow("#ExamHintBox","fadeOutUp","fadeInDown");
                    setTimeout(function(){
                        fnHide("#ExamHintBox","fadeInDown","fadeOutUp");
                        window.location.href = site_url+"Exam/examshow?taskid="+taskid;
                    },1000);

                }else{
                    setInterval("JudgeexamEnd();",1000);
                    $('#hintBox .promptNews').html(message.msg);
                    fnShow("#hintBox","fadeOutUp","fadeInDown");
                }
            }
        });
        $('#examBox').hide();
        $('#ExamHintBox .promptNews').addClass('promptUp');
        $('#ExamHintBox .promptNews').addClass('colorYe');
        $('#ExamHintBox .promptNews').html('<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>正在提交试卷');
        fnShow("#ExamHintBox","fadeOutUp","fadeInDown");
    });

});

//实时判断 教员是否已强制结束考试 是否到了考试结束时间
function JudgeexamEnd(){
    $.ajax({
        url: site_url + 'Exam/is_start',
        type: 'post',
        data: {'taskid': taskid},
        dataType: 'json',
        success: function (message) {
            //判分
            if(message.code == '0001'){
                //提交试卷
                handpaper();
            }
            if(message.code != '0000'){
                $('#okBox .promptNews').html(message.msg);
                fnShow("#okBox","fadeOutUp","fadeInDown");
                //清除定时器
                clearInterval(setIntervalExam);
            }else{
                //时间
                var time_difference = new Date(message.data.endTime) - new Date(message.data.currentTime);
                days = time_difference / 1000 / 60 / 60 / 24;
                daysRound = Math.floor(days);
                hours = time_difference / 1000 / 60 / 60;
                hoursRound = Math.floor(hours);
                minutes = time_difference / 1000 /60 - (60 * hoursRound);
                minutesRound = Math.floor(minutes);
                seconds = time_difference / 1000  - (60 * 60 * hoursRound) - (60 * minutesRound);
                secondsRound = Math.round(seconds);
                $("#timeGo").text(zero(hoursRound) +":" + zero(minutesRound) + ":" + zero(secondsRound));
            }

        }
    })
}
//提交试卷
function handpaper(){
    $.ajax({
        url: site_url + 'Exam/handpaper',
        type: 'post',
        data: {'taskid': taskid},
        dataType: 'json',
        success: function (message) {

        }
    })
}

JudgeexamEnd();
//定时器
var setIntervalExam = setInterval("JudgeexamEnd();",1000);

function zero(st){
    if(Math.ceil(st)<10){
        st = "0" +st;
    }
    return st;
}

//实时更新答案
function saveanswer(element) {
    var status = $(element).attr('status');
    var questionid = $(element).attr('QuestionID');
    var answer = '';
    if(status == 2){
        //多选题
        $('input[name="'+ questionid +'[]"]:checked').each(function(){
            answer += '|||' + $(this).val();
        });
        //截取
        answer = answer.substring(3);
    }else{
        answer = $(element).val();
    }
    //没值 返回
    if(answer == ''){
        return false;
    }

    $.ajax({
        url: site_url + 'Exam/saveanswer',
        type: 'post',
        data: {'taskid': taskid, 'questionid': questionid, 'answer': answer},
        dataType: 'json',
        success: function (message) {

        }
    })
}


//题干图片
function imgLian(){
    $(".queTxt img").each(function(){
        var that = $(this);
        var url = that.attr("src");
        var url2 =url.substr(6);
        var url3 = url.substr(url.length-4).toLocaleLowerCase();
        var name = that.attr("alt");
        var urlArr = [".png",".jpg",".gif"];
        if(jQuery.inArray(url3,urlArr) == -1){
            that.replaceWith('<a  href="'+ base_url + url2 +'">'+ name +'</a>');
        }
        else{
            $(this).attr("src",base_url+url2);
        }


    })
}
//题目带有附件列表
function dataGo(){
    $(".queTxt").each(function(){
        var dataname = $(this).attr("dataname");
        var dataurl  = $(this).attr("dataurl");
        var nameArr = dataname.split("," );
        var urlArr = dataurl.split("," );
        var dataGostr='';
        var urlType = [".png",".jpg",".gif","jpeg"];
        if(parseInt(nameArr[0])!=0){
            for(ss=0;ss<nameArr.length;ss++){
                var urll = urlArr[ss];
                var ifurl = urll.substr(urll.length-4)
                if(jQuery.inArray(ifurl,urlType) != -1){
                    dataGostr += '<a href="'+base_url+urlArr[ss].substr(6)+'" target="_blank">'+nameArr[ss]+'</a>';

                } else{
                    dataGostr += '<a href="'+base_url+urlArr[ss].substr(6)+'" >'+nameArr[ss]+'</a>';

                }

            }
            dataGostr="<p style='margin-top:15px'>附件："+dataGostr+"</p>";
            $(this).append(dataGostr);
        }

    })

}
$(document).ready(function(){
    imgLian();
    dataGo();
});