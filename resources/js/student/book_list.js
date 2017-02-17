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
    
    //开始学习
    $('.btnRelease').click(function(){
        var packageid = $(this).attr('packageid');
        $.ajax({
            url:site_url+'Book/checkstudy',
            type:'post',
            data:{packageid:packageid},
            dataType:'json',
            success:function(message){
                if(message.code == '0000'){
                    $('#staskid').val(message.data.taskid);
                    $('#spackageid').val(packageid);
                    fnShow("#studyBox","fadeOutUp","fadeInDown");
                }else if(message.code == '0001'){
                    //下发新任务
                    createstudy(packageid);
                }else{
                    $('#okBox .promptNews').html(message.msg);
                    fnShow("#okBox","fadeOutUp","fadeInDown");
                    setTimeout(function(){
                        fnHide("#okBox","fadeInDown","fadeOutUp");
                    },2000)
                }
            }
        })
    });
    //继续学习
    $('#gostudy').click(function(){
        var taskid = $('#staskid').val();
        window.location.href = site_url + "Study/studydetail?taskid=" + taskid;
    });
    //下发新任务
    $('#newstudy').click(function(){
        var packageid = $('#spackageid').val();

        createstudy(packageid);
    });
    //下发新任务
    function createstudy(packageid){
        $.ajax({
            url:site_url+'Book/createstudy',
            type:'post',
            data:{packageid:packageid},
            dataType:'json',
            success:function(message){
                if(message.code == '0000'){
                    window.location.href = site_url + "Study/studydetail?taskid=" + message.data.taskid;
                }else{
                    $('#studyBox').hide();
                    $('#okBox .promptNews').html(message.msg);
                    fnShow("#okBox","fadeOutUp","fadeInDown");
                    setTimeout(function(){
                        fnHide("#okBox","fadeInDown","fadeOutUp");
                    },2000)
                }
            }
        })
    }
});

//课程详情页 [类别所需]
$(document).ready(function(){
    if($("#ifFuceng").height()>30){
        $("#moreNewsLei").show();
        $("#ifFuceng").children(".spanBlue").hide();
    }
    $("#ifFuceng").removeClass("opOver");
    $("#moreNewsLei").mouseenter(function(){
        $(".fuCeng").slideDown();
        $(this).hide();
    });
    $(".fuCeng").mouseleave(function(){

        $(this).slideUp();
        $("#moreNewsLei").show();
    })

});