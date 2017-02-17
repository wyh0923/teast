/**
 * Created by Administrator on 2016/8/22.
 */

$(function () {
    //上传文件

    $('.uploadBtn').click(function(){
        $('#upload').click();
    })

})

// 上传图片方法
function uploadpic(){
    $.ajaxFileUpload({
        url:site_url+'/Adminsubject/upimg',
        secureuri:false,
        fileElementId:'upload',
        dataType:'json',
        success:function(message){
            if(message.status==1){
                $('#PackageImg').val(message['filenames']);
                $('.showPic').html('<img src="'+base_url+'/resources/files/img/course/'+message['filenames']+'" />');
            }else{
                $('#errorinfo').html('上传失败');
            }
        }
    })
}
