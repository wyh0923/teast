/**
 * Created by Administrator on 2016/8/29.
 */


$(function () {

    //题目
    $(".cquestion").click(function(){
        var search = translate($.trim($(".equestion").val()));
        var str = '';
        if(type != '') str += '/qtype/' + type;
        if(author != '') str += '/uname/' + author;

        window.location.href=site_url + 'Subject/questionlist'+ str + "/search/"+encodeURI(search);
    });
    $('.equestion').keydown(function(e){
        if(e.keyCode==13){
            var search = translate($.trim($(".equestion").val()));
            var str = '';
            if(type != '') str += '/qtype/' + type;
            if(author != '') str += '/uname/' + author;

            window.location.href=site_url + 'Subject/questionlist'+ str + "/search/"+encodeURI(search);
        }
    });

    //作者
    $('.author').click(function () {
        $(this).addClass('filterCur').siblings('a').removeClass('filterCur');
        var uname = $(this).attr('code');
        var str = '';
        if(type != '') str += '/qtype/' + type;
        
        if(uname != '') {
            location.href = site_url+'Subject/questionlist' + str + '/uname/'+ uname;
        } else {
            location.href = site_url+'Subject/questionlist' + str ;
        }
    });

    //类型
    $('.mold').click(function () {
        $(this).addClass('filterCur').siblings('a').removeClass('filterCur');
        var qtype = $(this).attr('type');
        var str = '';
        if(author != '') str += '/uname/' + author;

        if(qtype != '') {
            location.href = site_url+'Subject/questionlist' + str + '/qtype/'+ qtype;
        } else {
            location.href = site_url+'Subject/questionlist' + str ;
        }
    });

    //排序
    $('#QuestionAuthor,#QuestionType').click(function(){
        var field = $(this).attr("id");
        var code = $(this).attr('code');
        var str = '';
        if(type != '') str += '/qtype/' + type;
        if(author != '') str += '/uname/' + author;
        if (hunt != '')str += '/search/'+ translate(hunt);
        if(code == 'DESC'){
            location.href = site_url+'Subject/questionlist' + str + '/sort/'+field+' ASC';
        }else if(code == 'ASC'){
            location.href = site_url+'Subject/questionlist' + str + '/sort/'+field+' DESC';
        }else{
            location.href = site_url+'Subject/questionlist' + str + '/sort/'+field+' DESC';
        }
    });
    
    //显示题目详情
    $(".qdetail").click(function(){
        $('#qdetail .accessory').html('');
        var qname=$(this).attr('qname');//题干
        var qtype=$(this).attr('qtype');//类型
        var qdiff=$(this).attr('qdiff');//难度
        var ctf=$(this).attr('ctf');//场景
        var qchoose = $(this).attr('qchoose');//选项
        var qanswer = $(this).attr('qanswer');//答案
        var accessory = $(this).attr('accessory');//附件名 '1.png,2.jpg'
        var url = $(this).attr('url');//附件url 'r/f/234.png,r/f/342.jpg'
        var nameArr = new Array();
            nameArr=accessory.split("," );
        var urlArr = new Array();
            urlArr=url.split("," );


        if(accessory==''){
            $('#qdetail .accessory').text('暂无');

        } else {
            for(i=0;i<nameArr.length;i++){
                var str ='';
                str += '<a href="'+base_url+urlArr[i].substr(6)+'" target="_blank">'+nameArr[i]+'</a>';
                $('#qdetail .accessory').append(str);
            }

        }


        $('#qdetail .type').text(qtype);
        $('#qdetail .level').text(qdiff);
        $('#qdetail .ctf').text(ctf);
        $('#qdetail .qname').text(qname);

        if(qtype == '夺旗题' || qtype == '填空题')
        {
            $('#qdetail .qanswer').text(qanswer);
        }
        else
        {
            $('#qdetail .qanswer').text('');
        }

        if(qtype == '单选题')
        {
            var arr = qchoose.replace(/\^/g," ").split('|||');
            // console.log(arr);
            var str = '';
            $.each(arr, function (i) {
                if(qanswer == $.trim(arr[i])){
                    var check = 'checked';
                }else{
                    var check = '';
                }
                str += '<li><input disabled type="radio"'+ check +' value="'+ arr[i] +'">'+ arr[i] +'</li>';
            });
            $('#qdetail .qanswer').html(str);
            // console.log(str);
        }

        if(qtype == '判断题')
        {
            if(qanswer == '对'){
                var right = 'checked';
            }else{
                var right = '';
            }
            if(qanswer == '错'){
                var wrong = 'checked';
            }else{
                var wrong = '';
            }

            var str = '';
            str += '<li><input disabled type="radio"'+ right +' value="对">对</li>';
            str += '<li><input disabled type="radio"'+ wrong +' value="错">错</li>';
            $('#qdetail .qanswer').html(str);

        }


        if(qtype == '多选题')
        {
            var arr = qchoose.replace(/\^/g," ").split('|||');
            var arr_answer = qanswer.replace(/\^/g," ").split('|||');

            // console.log(arr);
            // console.log(arr_answer);
            var str = '';
            $.each(arr, function (i) {
                 var val=$.inArray($.trim(arr[i]), arr_answer);
                if(val != '-1'){
                    var check = 'checked';
                }else{
                    var check = '';
                }
                str += '<li><input disabled type="checkbox"'+ check +' value="'+ arr[i] +'">'+ arr[i] +'</li>';
            });
            $('#qdetail .qanswer').html(str);
        }

        fnShow("#qdetail","fadeOutUp","fadeInDown");
    });
    
    //是否可以编辑
    $('.ismod').click(function () {
        //site_url('Subject/editquestion'). '/qid/'.
        var qid = $(this).attr('code');
        $.ajax({
            url: site_url + 'Subject/isrelation',
            data: {'qid': qid},
            type: 'post',
            dataType: 'json',
            success: function (msg) {
                if (msg.code == '0000') {
                    $('#qdOk p.promptNews').html('已关联试卷，不可编辑');
                    fnShow("#qdOk", "fadeOutUp", "fadeInDown");
                    setTimeout(function () {
                        fnHide("#qdOk", "fadeInDown", "fadeOutUp");
                    }, 2000);
                }
                else {
                    window.location.href = site_url +'Subject/editquestion/qid/'+ qid;
                }
            }
        });
    });

    //单记录删除题目
    $(".qdel").click(function(){
        var code=$(this).attr('code');
        $.ajax({
            url: site_url + 'Subject/isrelation',
            data: {'qid': code},
            type: 'post',
            dataType: 'json',
            success: function (msg) {
                if (msg.code == '0000') {
                    $('#qdOk p.promptNews').html('已关联试卷，不可删除');
                    fnShow("#qdOk", "fadeOutUp", "fadeInDown");
                    setTimeout(function () {
                        fnHide("#qdOk", "fadeInDown", "fadeOutUp");
                    }, 2000);
                }
                else {
                    $('#qOk').attr('code',code);
                    fnShow("#qdelOk","fadeOutUp","fadeInDown");
                }
            }
        });

    });

    //确定删除单记录题目
    $('#qOk').click(function(){
        var cid = $(this).attr('code');

        fnHide("#qdelOk","fadeInDown","fadeOutUp");
        $.ajax({
            url:site_url+"Subject/delquestion",
            type:'post',
            data:{'cid':cid},
            dataType:'json',
            success:function(message){

                if(message.code=='0000'){

                    fnShow("#qdOk","fadeOutUp","fadeInDown");
                    $('#qdOk p.promptNews').html('删除成功');
                    setTimeout("location.reload()",2000);
                }else{

                    $('#qdOk p.promptNews').html('删除失败');
                    fnShow("#qdOk","fadeOutUp","fadeInDown");
                    setTimeout("location.reload()",2000);
                }
            }

        })
    });

    //场景
    $('#relationscene label a').click(function () {
        $(this).addClass('curForBlue').parents('label').siblings('label').children('a').removeClass('curForBlue');
        $('#addQues').attr('ctf', $(this).attr('code'));
        // alert($(this).attr('code'));
    });

    //类型
    $('#questiontype label a').click(function () {
        $(this).addClass('curForBlue').parents('label').siblings('label').children('a').removeClass('curForBlue');
        $('#addQues').attr('qtype', $(this).attr('code'));
        // alert($(this).attr('code'));
    });

    //难度
    $('#timunandu label a').click(function () {
        $(this).addClass('curForBlue').parents('label').siblings('label').children('a').removeClass('curForBlue');
        $('#addQues').attr('diff', $(this).attr('code'));
        // alert($(this).attr('code'));
    });

    //新增题目
    $('#addQues').click(function(){
        var ctf = $(this).attr('ctf');
        var qtype = $(this).attr('qtype');
        var diff = $(this).attr('diff');
        var name = $('#PackageName').val();
        var choose_name = [];
        var choose_id = [];
        var values = '';

        $.ajax({
            url:site_url+"Subject/addquestion",
            type:'post',
            data:{'cid':cid},
            dataType:'json',
            success:function(message){
                if(message.code=='0000'){

                    $('#qdOk p.promptNews').html('删除成功');

                }else{

                    $('#qdOk p.promptNews').html('删除失败');

                }
            }

        })
    });


});
