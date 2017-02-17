//初始化 数据
var calssList = new Array();
var studentList = new Array();
//统计被选中的input和未被选中的
function countCheck(idname,arr){
    var dangqian = new Array();
    var idnle = $(idname).children().length;

    for(i=0;i<idnle;i++){
        var hh = $(idname).children().eq(i).children("input").attr("id");
        var deName = $(idname).children().eq(i).children("input").val();
        if( document.getElementById(hh).checked == false){

            if($.inArray(deName,dangqian) == -1){
                dangqian.push(deName)
            }
        }else{
            if($.inArray(deName,arr) == -1){
                arr.push(deName)
            }
        }
    }
    //得到真正被选中的input数量；
    for(i=0;i<arr.length;i++){
        if($.inArray(arr[i],dangqian) != -1){
            arr.splice(jQuery.inArray(arr[i],arr),1);
        }
    }

}
// 单个点击 单选
function checkInfo(element) {
    var id = $(element).parent().parent().attr("id");//ul 上的id
    var check = $(element).parent().parent().siblings('p').children('input').attr("id");//全选的id
    var page_total = 0;
    var number = $('#'+ id + ' li').length;//获取当前li的个数
    $('#'+ id + ' input').each(function () {

        var childrenID = $(this).attr('id');
        if($("#"+childrenID).is(':checked')){

            $('#'+childrenID).parent().addClass("xuanzhong"); //样式
            page_total+=1;
        }else{
            $('#'+childrenID).parent().removeClass("xuanzhong"); //移除样式
        }
    });
    document.getElementById(check).checked = false;
    if(page_total == number&&number!=0){
        document.getElementById(check).checked = true;
    }

}
//本页 单选和全选关系
function homepage(id,check) {
    var page_total = 0;
    var number = $('#'+ id + ' li').length;//获取当前li的个数
    $('#'+ id + ' input').each(function () {
        if((this).checked == true){
            page_total++;
        }
    });
    document.getElementById(check).checked = false;

    if(page_total == number&&number!=0){
        document.getElementById(check).checked = true;
    }
}

// 页面显示班级
function sapSucClass(data) {
    var calss_str = '';
    //统计
    countCheck("#classList",calssList);
    $.each(data,function(i,v){
        var check = '';var lei ='';
        if($.inArray(v.ClassID, calssList) != -1){
            check = 'checked';
            lei = 'xuanzhong';
        }
        calss_str += '<li class="'+ lei +'"><label for="c'+ v.ClassID+'"></label>';
        calss_str += '<input type="checkbox" onclick="checkInfo(this)" value="'+ v.ClassID +'" '+ check +' id="c'+ v.ClassID+'" />'+v.ClassName +'</li>';
    });
    $('#classList').html(calss_str);
    //本页 全选是否都选中
    homepage('classList','checkClass');
}

// 页面显示学员
function sapSucStudent(data) {
    var student_str = '';
    //统计
    countCheck("#studentList",studentList);
    $.each(data,function(i,v){
        var check = '';var lei ='';
        if($.inArray(v.UserID, studentList) != -1){
            check = 'checked';
            lei = 'xuanzhong';
        }
        student_str += '<li class="'+ lei +'"><label for="s'+ v.UserID+'"></label>';
        student_str += '<input type="checkbox" onclick="checkInfo(this)" value="'+ v.UserID +'" '+ check +' id="s'+ v.UserID+'" />'+v.UserName +'</li>';
    });
    $('#studentList').html(student_str);
    //本页 全选是否都选中
    homepage('studentList','checkStudent');
}

//班级  加载数据
function class_list() {
    sapGetData(site_url + 'Education/class_list', sapSucClass, "pageContainerC");
}

//学员 加载数据
function student_list() {
    sapGetData(site_url + 'Education/student_list', sapSucStudent, "pageContainerS");
}

$(function() {

    //默认初始化 班级
    class_list();

    //下发按钮  弹出框
    $(".btnRelease").click(function() {
        //初始化
        calssList = [];
        studentList = [];
        $('#errorMsg').html('');
        $('#xiafaBtn').attr("onclick","xiafaBtn()");
        $('#xiafaBtn').removeClass("noCanBg");
        $(".sapSearch").val("");
        
        $('#xiafaBox form input[type="checkbox"]').attr("checked",false);
        $("#xiafaBox form textarea").val("");
        $('#xiafaBox form li').removeClass("xuanzhong");
        $('#xiafaBox form .titleTab h3').eq(0).addClass("activeYellow").siblings().removeClass("activeYellow");
        $("#xiafaBox .biao:first").show().siblings(".biao").hide();

        var id = $(this).attr('id');
        var name = $(this).attr('name');
        $('#starttime').val(starttime);
        $('#endtime').val(endtime);
        $('#taskname').val(name);
        $('#taskname').attr('data_id',id);

        //场景试卷--开始时间 结束时间
        var scene = $(this).attr('scene');
        if(scene == 1){
            exam_time();//时间处理
        }

        class_list();//班级

        fnShow("#xiafaBox","fadeOutUp","fadeInDown");
    });
    //场景题的开始时间和结束时间
    function exam_time(){
        var date = new Date();
        var year = date.getFullYear();
        var month = date.getMonth() + 1;
        var day = date.getDate();
        var end_day = day;
        var hour = date.getHours();
        var end_hour =  hour+1; //结束 小时
        var minute = date.getMinutes()+20;
        if(minute>=60){
            minute = minute-60;
            end_hour = end_hour+1;
            hour = hour+1;
            if(hour>=24){
                day = day+1;
                hour = hour-24;
            }
            if(end_hour>=24){
                end_day = end_day+1;
                end_hour = end_hour-24;
            }
        }
        var second = date.getSeconds();

        $('#starttime').val(year+'-'+month+'-'+day+' '+hour+':'+minute+':'+second);
        $('#endtime').val(year+'-'+month+'-'+end_day+' '+end_hour+':'+minute+':'+second);

    }
    //下发 切换 [班级 学员]
    $('.titleTab h3' ).click(function(){
        $(this).addClass("activeYellow").siblings().removeClass("activeYellow");
        var showHide =$(this).parent().siblings().eq( $(this).index());

        showHide.siblings(".biao").hide();
        showHide.show().removeClass("outHide");
        //类型
        var type=$(this).attr("data-type");
        if(type == 1){
            class_list();//班级
        }
        if(type == 2){
            student_list();//学员
        }
    });


    // 班级 全选 反选
    $("#checkClass").click(function(){
        if($(this).is(':checked')){
            $("#classList input[type='checkbox']").each(function(){
                this.checked = true;
                $(this).parent().addClass("xuanzhong");
            });
        }else{
            $("#classList input[type='checkbox']").each(function(){
                this.checked = false;
                $(this).parent().removeClass("xuanzhong"); //移除属性
            });
        }
    });
    //学员 全选 反选
    $("#checkStudent").click(function(){
        if($(this).is(':checked')){
            $("#studentList input[type='checkbox']").each(function(){
                this.checked = true;
                $(this).parent().addClass("xuanzhong");
            });
        }else{
            $("#studentList input[type='checkbox']").each(function(){
                this.checked = false;
                $(this).parent().removeClass("xuanzhong"); //移除属性
            });
        }
    });


    // 弹框搜索 方法
    function searchList() {
        var type = '';
        // 全选  不选中
        $("#checkClass").prop("checked",false);
        $("#checkStudent").prop("checked",false);

        $(".titleTab").find("h3").each(function(){
            if($(this).hasClass("activeYellow")){
                type = $(this).attr("data-type");
            }
        });
        if(type == 1){
            $('.sapSearch').attr('id','sapSearch_pageContainerC'); //改变id值
            class_list();
        }
        if(type == 2){
            $('.sapSearch').attr('id','sapSearch_pageContainerS'); //改变id值
            student_list();
        }
    }
    // 弹框搜索 触发事件
    $('.searchBtn').click(function () {
        searchList();
    });
    // 弹框搜索 回车事件
    $('.sapSearch').keydown(function (e) {
        if (e.keyCode == 13) {
            searchList();
        }
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
    //获取参数
    function GetQueryString(name) {
        var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");

        var r = decodeURI(window.location.search.substr(1)).match(reg);
        if(r!=null)return  unescape(r[2]); return null;
    }
    //试卷类型的搜索
    $('.examtype').change(function(){
        var search = encodeURIComponent($(".iptSearch-a").val());
        var diff = GetQueryString("diff");
        var check_val = '';

        $('input[name="examtype"]:checked').each(function(){
            check_val += $(this).val()+',';
        });
        var url = site_url+"Education/eduexam?examtype="+ check_val;
        if(diff != null){
            url += '&diff='+ diff;
        }
        if(search != ''){
            url += '&search='+search;
        }
        window.location.href = url;
    });

});


//下发触发事件
function xiafaBtn() {
    countCheck("#classList",calssList); //统计选中的班级
    countCheck("#studentList",studentList); //统计选中的学员

    //alert('b：'+calssList.length+'+++s：'+studentList.length);return false;

    if(calssList.length == 0 && studentList.length == 0){
        $('#errorMsg').html('请选择学员或班级');
        return false;
    }
    var id =  $('#taskname').attr('data_id');
    var Taskstart = $('#starttime').val();
    var Taskend = $('#endtime').val();
    var taskname = $("#taskname").val().trim();
    var taskdesc = $('#taskDesc').val().trim();

    if(taskname.length < 1){
        $('#errorMsg').html('请输入任务名称');
        return false;
    }
    if(Taskstart.length < 1 || Taskend.length < 1){
        $('#errorMsg').html('请选择开始或结束时间');
        return false;
    }
    if(Taskstart >= Taskend){
        $('#errorMsg').html('开始时间不能大于等于结束时间');
        return false;
    }
    if(Taskstart < starttime || Taskend< starttime){
        $('#errorMsg').html('考试开始时间或结束时间不能小于当前时间');
        return false;
    }

    $('#xiafaBtn').removeAttr("onclick");
    $('#xiafaBtn').addClass("noCanBg");

    var infos = {'id':id,'taskname':taskname,'taskdesc':taskdesc,'starttime':Taskstart,'endtime':Taskend,'nowtime':starttime};
    $.ajax({
        url:site_url+'Education/create_exam',
        type:'post',
        data:{'infos':infos,'calssList':calssList,'studentList':studentList},
        async: false,
        dataType: 'json',
        success : function(message){
            if(message.code == '0000'){
                $('#errorMsg').html('');
                $('#okBox .promptNews').addClass('promptUp');
                $('#okBox .promptNews').addClass('colorYe');

                $('#okBox .promptNews').html('<i class="fa fa-check-circle-o"></i>'+message.msg);
                fnShow("#okBox","fadeOutUp","fadeInDown");
                setTimeout(function(){
                    fnHide("#okBox","fadeInDown","fadeOutUp");
                    window.location.href=site_url + "Education/examtask";
                },1000);
            }else if(message.code == '0001'){
                $('#errorMsg').html('');
                $('#okBox .promptNews').addClass('promptUp');
                $('#okBox .promptNews').addClass('colorYe');
                $('#okBox .promptNews').html('<i class="fa fa-exclamation-circle"></i>下发失败');
                fnShow("#okBox","fadeOutUp","fadeInDown");
                setTimeout(function(){
                    fnHide("#okBox","fadeInDown","fadeOutUp");
                },1000);
            }else{
                //错误信息弹窗
                $('#okBox .promptNews').html(message.msg);
                fnShow("#okBox","fadeOutUp","fadeInDown");
                setTimeout(function(){
                    fnHide("#okBox","fadeInDown","fadeOutUp",'1');
                    fnHide("#xiafaBox","fadeInDown","fadeOutUp");
                },2000);
            }
        }
    });

}