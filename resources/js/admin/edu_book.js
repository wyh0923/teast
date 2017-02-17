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
    //sapGetData(site_url + 'Education/class_list', sapSucClass, "pageContainerC");
}

//学员 加载数据
function student_list() {
    //sapGetData(site_url + 'Education/student_list', sapSucStudent, "pageContainerS");
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
        
        class_list();//班级

        fnShow("#xiafaBox","fadeOutUp","fadeInDown");
    });

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


    // 搜索
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
        $('#errorMsg').html('学习开始时间，结束时间不能小于当前时间');
        return false;
    }
    $('#xiafaBtn').removeAttr("onclick");
    $('#xiafaBtn').addClass("noCanBg");

    var infos = {'id':id,'taskname':taskname,'starttime':Taskstart,'endtime':Taskend,'nowtime':starttime};
    $.ajax({
        url:site_url+'Education/create_study',
        type:'post',
        data:{'infos':infos,'calssList':calssList,'studentList':studentList},
        dataType: 'json',
        success : function(message){
            if(message.code == '0000'){
                $('#errorMsg').html('');
                $('#hintBox .promptNews').addClass('promptUp');
                $('#hintBox .promptNews').addClass('colorYe');
                $('#hintBox .promptNews').html('<i class="fa fa-check-circle-o"></i>'+message.msg);
                fnShow("#hintBox","fadeOutUp","fadeInDown");
                setTimeout(function(){
                    fnHide("#hintBox","fadeInDown","fadeOutUp");
                    window.location.href=site_url + "Education/studylist";
                },1000);
            }else if(message.code == '0001'){
                $('#errorMsg').html('');
                $('#hintBox .promptNews').addClass('promptUp');
                $('#hintBox .promptNews').addClass('colorYe');
                $('#hintBox .promptNews').html('<i class="fa fa-exclamation-circle"></i>下发失败');
                fnShow("#hintBox","fadeOutUp","fadeInDown");
                setTimeout(function(){
                    fnHide("#hintBox","fadeInDown","fadeOutUp");
                },1000);
            }else{
                //错误信息弹出框
                $("#hintBox .popTitle p").html("提示信息");
                $('#hintBox .promptNews').html(message.msg);
                $('#hintBox .promptNews').removeClass('promptUp');
                $('#hintBox .promptNews').removeClass('colorYe');

                setTimeout(function(){
                    fnHide("#hintBox","fadeInDown","fadeOutUp");
                },2000);
            }
        }
    });

    $('#hintBox .promptNews').addClass('promptUp');
    $('#hintBox .promptNews').addClass('colorYe');
    $('#hintBox .promptNews').html('<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>正在下发');
    fnShow("#hintBox","fadeOutUp","fadeInDown");
    $('#xiafaBox').hide();

}
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