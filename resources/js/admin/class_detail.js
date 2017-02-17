/**
 * Created by qirupeng on 2016/8/25.
 */
$(function(){
    $(".fa-search").click(function(){
        var search = $.trim($(".iptSearch-a").val());
        window.location.href=site_url +'/User/classdetail' + "/classid/"+classcode+ "/search/"+encodeURI(translate(search));
    });
    $('.iptSearch-a').keydown(function(e){
        if(e.keyCode==13){
            var search = $.trim($(".iptSearch-a").val());
            window.location.href=site_url +'/User/classdetail' + "/classid/"+classcode+ "/search/"+encodeURI(translate(search));
        }
    });
    $("#classTable").find(".forRed").on({
        click: function() {
            var code = $(this).attr('code');
            $('.okBtn').attr('code', code);
            fnShow("#delPopBox", "fadeOutUp", "fadeInDown");
        }
    });
    $('.okBtn').click(function() {
        var usercode = $(this).attr('code');
        console.log(usercode);
        $.ajax({
            url: site_url + "/User/del_class_user",
            type: 'post',
            data: {'classcode': classcode, 'usercode': usercode},
            dataType: 'json',
            success: function(message) {
                if (message.code == '0000') {
                    setTimeout("location.reload()", 500);
                    fnHide("#delPopBox", "fadeInDown", "fadeOutUp");
                }
            }

        })
    });
    function fnajax1(url, obj) {
        $.ajax({
            type: "post",
            url: '<?php echo site_url() ?>' + url,
            data: obj,
            dataType: "json",
            success: function(msg) {
                if (msg.status == 0) {
                    console.log("失败");
                } else if (msg.status == 1) {
                    console.log("成功");
                    window.location.href = window.location.href;
                }
            }
        });
    }

});