$(function() {
    //页面加载 - 算分
    //针对的类型为无视频且无题目[有吗？]
    //sectionScore(1);


    //随堂练习提交 弹出框
    $('#practiceHint').click(function () {
        fnShow("#practiceBox","fadeOutUp","fadeInDown");
    });
    //提交随堂练习
    $('#practiceSub').click(function () {
        fnHide("#practiceBox","fadeInDown","fadeOutUp",1);
        $.ajax({
            type: "POST",
            url: site_url + "Study/practice_Answer",
            data:$('#Practice').serialize(),
            async: false,
            dataType: 'json',
            success: function(message) {
                $('#practiceBox').hide();
                var total = message.msg;
                if(message.code == '0000'){
                    for (var i=0; i<total; i++) {
                        if(message.data[i]['judge'] == 1){
                            $('#ques'+message.data[i]['QuestionID']).find('i').addClass('fa-check');
                        }else{
                            $('#ques'+message.data[i]['QuestionID']).find('i').addClass('fa-close');
                        }
                    }

                }else{
                    $('#okBox .promptNews').html(message.msg);
                    fnShow("#okBox","fadeOutUp","fadeInDown");
                    setTimeout(function(){
                        fnHide("#okBox","fadeInDown","fadeOutUp");
                    },2000);
                }
                $('input').attr('disabled','disabled');
                $('#practiceHint').addClass('noCanBg');
                $('#practiceHint').unbind("click");
                //小节得分
                sectionScore(3);

            }
        });
    });

    //随堂练习弹出框-关闭按钮
    $('#cancelBtn').click(function () {
        fnHide("#practiceBox","fadeInDown","fadeOutUp");
    });
    
    //进入下一节
    $('#nextSection').click(function () {
        $.ajax({
            url:site_url+"Study/next_section/",
            data:{'sectioninsid':sectioninsid,'taskid':taskid},
            type:'post',
            async: false,
            dataType: 'json',
            success:function(message){
                if(message.code == '0000'){
                    window.location.href = site_url+'Study/studysection?taskid='+ taskid + '&sectioninsid=' +message.msg;
                }else{
                    $('#scoreBox').hide();
                    $('#okBox .promptNews').html(message.msg);
                    fnShow("#okBox","fadeOutUp","fadeInDown");
                    setTimeout(function(){
                        fnHide("#okBox","fadeInDown","fadeOutUp");
                    },2000);
                }
            }
        })
    });

    //留在本小节
    $('.thisSection').click(function () {
        $("#scoreBox").hide();
        fnHide("#scoreBox","fadeInDown","fadeOutUp");
        window.location.reload();
    })

});

//小节得分   1--页面加载完成  2--视频播放完成  3--题目完成
function sectionScore(type){
        $.ajax({
            url:site_url+"Study/sectionScore/",
            data:{'type':type,'sectioninsid':sectioninsid,'taskid':taskid},
            type:'post',
            async: false,
            dataType: 'json',
            success:function(message){
                if(message.code == '0000'){
                    $('#scoreBox .promptNews').html('恭喜！您已学完本小节，得分：'+message.data.sectionScore+'分');
                    fnShow("#scoreBox","fadeOutUp","fadeInDown");
                }else if(message.code != '0001'){
                    $('#okBox .promptNews').html(message.msg);
                    fnShow("#okBox","fadeOutUp","fadeInDown");
                    setTimeout(function(){
                        fnHide("#okBox","fadeInDown","fadeOutUp");
                    },2000);
                }
            }
        })
}

//视频播放完成
function videoPlayEnd() {
    if(bool) {
        bool = false;
        sectionScore(2);
    }
    $("#scoreBox").addClass("popVideoGo");
    $("#okBox").addClass("popVideoGo")
}
