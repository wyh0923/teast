/**
 * Created by Administrator on 2016/9/7.
 */
$(function(){
   //小节新增图片
    $('.uploadBtn').click(function(){
        $('#upload').click();
    })

        //难度等级变化
	$(".iptBox label").click(function(){
		$(this).addClass("cur").siblings("label").removeClass("cur")
	})
	//上传类型切换
	$("#SectionType label").click(function(){
		var thisId = $(this).attr("type");
        $(".SectionTypediv").addClass("outHide");
        var showId = $(".SectionTypediv"+thisId);

        showId.removeClass("outHide");

	})
    var timestamp = Date.parse(new Date());

	//上传视屏
    var upadd = $('#videoUploadBox').Huploadify({
        auto: true,//当选择文件后就直接上传了
        fileTypeExts: '*.mp4;*.flv;',//上传文件类型
        multi: true, //上传多个文件
        fileSizeLimit: 999999999999,
        breakPoints: true,
        saveInfoLocal: true,
        showUploadedPercent: true,//是否实时显示上传的百分比，如20%
        showUploadedSize: true,
        removeTimeout: 1,//上传完成后多久删除队列中的进度条
        fileSplitSize: 2048 * 2048,
        buttonText: '上传资源',
        formData: {key1: timestamp, key2: 'video_',videoDir:videoDir},
        uploader: site_url + 'Subject/uploadvideo',//服务器端脚本文件路径
        onUploadComplete: function (messfileObj, info, responseage) {

            var data = JSON.parse(info);
            if(data.success == false){
                $('#adderrormsg').html('上传视频失败');
            }

            //alert(info)
            $('#SectionVideo').val(data.filename);
            $('#file_info_show_box_1').val(data.filename);
        },
        onUploadStart: function (file) {//上传开始时触发（每个文件触发一次）

            $(".file_info_show_box").val('');//此处为临时解决自动清除url问题

            upadd.settings("formData", {key1: timestamp, key2: 'video_',videoDir:videoDir});
        }
    });

    //新增图片的粘贴复制
    var clipboard = new Clipboard('.copyImage'); 

    clipboard.on('success', function(e) {
        alert("复制成功");
    });

    var tokenystrtool  =  Date.parse(new Date());

    //新增资料里面的上传
    var uploadTool = $('#addDatas').Huploadify({
        auto:true,
        fileTypeExts:'*.jpg;*.jpeg;*.gif;*.png;*.bmp;*.mp4;*.flv;*.rm;*.rmvb;*.qcow2;*.rar;*.tar;*.gz;*.zip;*.gzip;*.doc;*.docx;*.xls;*.xlsx',
        multi:true,
        formData:{key:tokenystrtool,key2:'data_'},
        fileSizeLimit:999999999999,
        breakPoints:true,
        saveInfoLocal:true,
        showUploadedPercent:true,// 是否实时显示上传的百分比，如20%  style="width: 100px;" <div style="width: 50px;word-break:break-all;" ></div>
        showUploadedSize:true,
        removeTimeout:1,
        fileSplitSize:2048*2048,
        buttonText:'上传资料',
        uploader:site_url+'Subject/uploadtoolRes',
        onUploadSuccess:function(file, data, response){
            var resdata = JSON.parse(data);

            $('#SectionDataNews').val(resdata.filename);
            $('#file_info_show_box_2').val(resdata.filename);
        },
        onUploadStart: function(file) {//上传开始时触发（每个文件触发一次） 
            uploadTool.settings("formData",{key:tokenystrtool,key2:'data_'});
        }
    });
    //新增题目弹窗的上传
    var timestamp = Date.parse(new Date());

    var upquestion = $('#uploadQuestion').Huploadify({
        formData: {key: timestamp, key2: ''},
        auto: true,//当选择文件后就直接上传了
        fileTypeExts: '*.jpg;*.png;*.gif;*.jpeg;*.zip;*.gzip;*.rar;*.doc;*.docx;*.xls;*.xlsx;*.qcow2;',//上传文件类型
        multi: true, //上传多个文件
        fileSizeLimit: 999999999999,
        breakPoints: true,
        saveInfoLocal: true,
        showUploadedPercent: true,//是否实时显示上传的百分比，如20%
        showUploadedSize: true,
        removeTimeout: 1,//上传完成后多久删除队列中的进度条
        fileSplitSize: 2048 * 2048,
        buttonText: '上传资源',
        uploader: site_url + 'Subject/accessory',//服务器端脚本文件路径
        onUploadComplete: function (messfileObj, info, responseage) {

            var data = JSON.parse(info);
            var url = "../../resources/files/question/" + data.filename;
            var url2 ="!["+messfileObj.name+"]("+url+")"
            var content = '';


           content += '<tr urla = "../../resources/files/question/'+data.filename+'" namea = "'+messfileObj.name+'"><td class="resourcename">' + messfileObj.name + '</td><td class="resourceurl"><a  id=img' + messfileObj.lastModified + ' href="javascript:;" >' + url2 + '</a>' + url + '</td><td><a href="javascript:;" class="btncopy" code=' + url + ' data-clipboard-action="copy" data-clipboard-target=#img' + messfileObj.lastModified + '><i class="fa fa-copy" style="color:#C45F46" ></i>&nbsp;复制</a>&nbsp;&nbsp;<a href="javascript:;" onclick="delres(this)" class="upDel"><i class="fa fa-trash-o "></i>删除</a></td></tr>';
            $('#addquesTable').append(content);

        },
        onUploadStart: function (file) {//上传开始时触发（每个文件触发一次）

            $(".file_info_show_box").val('');//此处为临时解决自动清除url问题
            var timestamp = Date.parse(new Date());
            var updatetype = "";
            upquestion.settings("formData", {key: timestamp, key2: updatetype});
        }
    });

    //复制与黏贴
    var clipboard = new Clipboard('.btncopy');
    clipboard.on('success', function(e) {

        alert("复制成功");
        e.clearSelection();
    });

    clipboard.on('error', function(e) {
        console.error('Action:', e.action);
        console.error('Trigger:', e.trigger);curForBlue
    });
     //新增题目弹窗类型切换
    $("#questiontype a").click(function(){
        var thisId = $(this).index();
        if(thisId>2){
            $("#quesTxtT").html("答案：")
        }
        else{
            $("#quesTxtT").html("选项：")
        }
        var showId = $("#xuantiBox").children().eq(thisId-1);
        showId.removeClass("outHide").siblings().addClass('outHide')

    })

    //ctf弹窗
    $(".selectCtfButton").click(function(){
      $(".goSearch input").val('');//初始化弹窗搜索
        sapGetData(site_url+'Subject/ctflist',sapSucCtf,"ctfPage");

         if($("#addquestionBox").css("display")!="none"){
            $("#addquestionBox").css({"z-index":"1"});

         }
         setPosi("#ctfListBox");//由于是异步ajax取数据，会造成对弹框的定位出现高度问题，所以等待加载结束重新对弹框定位
        fnShow("#ctfListBox","fadeOutUp","fadeInDown")
    })

    $(".ctfsearch").click(function(){
        sapGetData(site_url+'Subject/ctflist',sapSucCtf,"ctfPage");
    });

    $('#sapSearch_ctfPage').keydown(function(e){
        if(e.keyCode==13){
            sapGetData(site_url+'Subject/ctflist',sapSucCtf,"ctfPage");
        }
    });

    //sece弹窗
   $(".selectSceneButton").click(function(){
        $(".goSearch input").val('');//初始化弹窗搜索
        sapGetData(site_url+'Subject/scenelist',sapSucScene,"secePage");
        
        if($("#addquestionBox").css("display")!="none"){
            $("#addquestionBox").css({"z-index":"1"});
         }
        setPosi("#scenListBox");//由于是异步ajax取数据，会造成对弹框的定位出现高度问题，所以等待加载结束重新对弹框定位

        fnShow("#seceListBox","fadeOutUp","fadeInDown")
   })

    $(".sceneSearch").click(function(){
        sapGetData(site_url+'Subject/scenelist',sapSucScene,"secePage");
    });

    $('#sapSearch_secePage').keydown(function(e){
        if(e.keyCode==13){
            sapGetData(site_url+'Subject/scenelist',sapSucScene,"secePage");
        }
    });


   //改变新加题目弹窗的渐隐状态和ctf，sece弹窗的隐藏
   $(".newClose1").click(function(){

   	 	$("#addquestionBox").css({"z-index":"99999"})
      if($("#addquestionBox").css("display")!="none"){
      	fnHide("#ctfListBox","fadeInDown","fadeOutUp",2)
   	 	
   	   }
   	   else{
   	   	fnHide("#ctfListBox","fadeInDown","fadeOutUp")
   	   }

   })
   $(".newClose2").click(function(){

   	 	$("#addquestionBox").css({"z-index":"99999"})
      if($("#addquestionBox").css("display")!="none"){
      	fnHide("#seceListBox","fadeInDown","fadeOutUp",2)
   	 	
   	   }
   	   else{
   	   	fnHide("#seceListBox","fadeInDown","fadeOutUp")
   	   }

   })
   //点击关闭输入实验操作手册的输入方式提醒
    $(".daoNews i").click(function(){
        $(this).parent().slideUp();
    })
    //点击打开输入实验操作手册的输入方式提醒
    $(".newStarBtn").click(function(){
        $(this).siblings(".daoNews").slideDown();
    })
   //点击新增图片
    $('#addPicture').click(function(){
        $("#imgresUploadBox .uploadify-button").click();
    });
    //点击新增资料
    $("#addDataBtn").click(function(){
    	//初始化新增资料弹窗
      $(".adderrormsg").html('');
    	uploadTool.clearSelf();
    	$("#SectionDataName").val('');
    	$("#SectionDataDesc").val('');
    	$("#SectionDataNews").val('');
        tongjidata();
    	fnShow("#addDataBox","fadeOutUp","fadeInDown")
    })
    //点击新增资料里面的确定按钮
    $("#addDataOK").click(function(){
    	var SectionDataName = $.trim($('#SectionDataName').val());//资料名称
        var SectionDataDesc = $('#SectionDataDesc').val();//资料描述
        var SectionDataNews = $('#SectionDataNews').val();//上传的资料信息
        var reg = /[<>*~!@#$^&*()=|{}]/;
        if(SectionDataName == '') {
            $("#addDataBox .adderrormsg").html("请输入资料名称"); 
            return false;
        }
       
        else if(reg.test(SectionDataName)){
            $("#addDataBox .adderrormsg").html("输入资料名称里有不合法字符");
            return false;
        }

        if(SectionDataDesc =='') {
            $("#addDataBox .adderrormsg").html("请输入资料描述");
            return;
        }
        else if(reg.test(SectionDataDesc)){
            $("#addDataBox .adderrormsg").html("输入资料描述里有不合法字符");
            return;
        }

        if(SectionDataNews =='') {
            $("#addDataBox .adderrormsg").html("请先上传一个资料");
            return;
        }

        $.ajax({
            url: site_url + "Subject/addmaterial",
            type: 'post',
            async: false,
            data: {
             'ToololdName': SectionDataName,
             'tooldesc': SectionDataDesc,
             'Toolurl': SectionDataNews,
            },
            dataType: 'json',
            success: function (message) {
                var surl = 'resources/files/data/'+SectionDataNews;
                if(message.code == '0000'){
                    var tt = message.data +'@@@@'+ surl +'@@@@'+SectionDataName+'@@@@'+SectionDataDesc;
                    toolChecked.push(message.data);
                    toolContents.push(tt);

                    $("#addDataBox .adderrormsg").html("创建成功");

                    $('#SectionDataDesc').val('');
                    $('#SectionDataName').val('');
                    $('#SectionDataNews').val('');
                    uploadTool.clearSelf();
                    assemblingData();//把信心拼装到页面表格中
                    fnHide("#addDataBox","fadeInDown","fadeOutUp")
                }else{
                    $("#addDataBox .adderrormsg").html(message.msg);
                }
            }
        })

    	
    });
    //点击新增题目弹窗里面的保存按钮
     $("#saveQueList").click(function(){
        var type = $("#questiontype .checkNow").attr("type");//获取题目类型----1单选题；2多选题，3判断题，4填空题，5夺旗题
        var grade = $("#timunandu .checkNow").text();//获取题目难度等级
        var xuangXiang = new Array();//选项值
         var choose = '';//选项值
         var answer = ''//正确答案
        var tigan = $("#PackageName").val();
        var biaozhi = 1;
        var chongfu = 1;
        //判断题干是否存在
        if (tigan.match(/^\s*$/) != null) {
             $('#addquestionBox .adderrormsg').html('题干不能为空');
             return false;
         }
        if(type==1){//单选题
            
             $('input[name=danxuan]').each(function () {
                var danAnser = $.trim($(this).val());
                  if(danAnser == ''){
                     biaozhi =2
                      return;
                  }else{
                       if(jQuery.inArray(danAnser,xuangXiang) == -1){
                       xuangXiang.push(danAnser);
                       choose = xuangXiang.join("|||"); 
                    }
                    else{
                       //判断重复答案
                        chongfu = 2;
                        return false;
                    }
                  }
                   
              });
              if(biaozhi == 2){
                $('#addquestionBox .adderrormsg').html('请输入单选选项值');
                 return false;

             }
              //判断重复答案
             if(chongfu == 2){
                $('#addquestionBox .adderrormsg').html('选项不可重复');
                 return false;

             }
             //单选题至少两个选项
              if (xuangXiang.length < 2) {
                  $('#addquestionBox .adderrormsg').html('单选题至少两个选项');
                  $('#addquestionBox .adderrormsg').css('display', 'block');

                  return false;
              }
             
              
              answer = $('input[name=radiodan]:checked').next().val();
              //判断是否有正确答案
              if ($('input[name=radiodan]').is(':checked')) {
                  if (answer == "") {
                       $('#addquestionBox .adderrormsg').html('请输入正确答案');
                       return false;
                  }

              } else {
                  $('#addquestionBox .adderrormsg').html('请选中正确答案');
                  return false;
              }
         
          $('#addquestionBox .adderrormsg').html('')
          //type ='单选题';
          //alert(type);
         // alert(answer)

        }//单选结束

        if(type==2){//多选题
            //duoarr 多选题的所有选项值
              //var duoarr=new Array();
              $('input[name=duoxuan]').each(function () {
                  var duoAnser = $.trim($(this).val());
                  if(duoAnser==''){
                      biaozhi = 2;
                      return false;
                  } else {
                       if(jQuery.inArray(duoAnser,xuangXiang) == -1){
                       xuangXiang.push(duoAnser);
                       choose = xuangXiang.join("|||"); 
                        }
                        else{
                           //判断重复答案
                            chongfu = 2;
                            return false;
                        }
                  }
              });

              if(biaozhi == 2){
                  $('#addquestionBox .adderrormsg').html('请输入多选选项值');
                  return false;

              }
               if(chongfu == 2){
                $('#addquestionBox .adderrormsg').html('选项不可重复');
                 return false;

                }
              if (xuangXiang == "") {
                  $('#addquestionBox .adderrormsg').html('请输入多选的选项值');
                  return false;
              }
              
              //duoansArr   多选题的正确答案
              //duoanString  将多选题的答案分割成aaa|||bbb的形式的字符串
              var duoansArr = [];

              duoansArr = []//每次统计之前清空
              $("input[name=checkboxduo]").each(function () {
                  if ($(this).is(":checked")) {
                      duoansArr.push($(this).siblings("input[name=duoxuan]").val());
                  }

              })

              if (!($('input[name=checkboxduo]').is(':checked'))) {
                  $('#addquestionBox .adderrormsg').html('请选中正确答案');
                  return false;
              }

            if (duoansArr.length<1) {
                $('#addquestionBox .adderrormsg').html('请输入正确答案');

                return false;
            }

              //判断多选的答案如果只有一个的时候
              if (duoansArr.length < 2) {
                  $('#addquestionBox .adderrormsg').html('多选题至少选择两个正确答案');
                  return false;
              }
              if (duoansArr.length > 1) {
                  answer = duoansArr.join("|||");
              }
               $('#addquestionBox .adderrormsg').html('');
               //type ='多选题';
               //alert(type);
            // alert("2多选题")

        }//多选结束

        if(type==3){//判断题
             answer = $('input[name=radiopanduan]:checked').val();
             if(!answer){
                $('#addquestionBox .adderrormsg').html('请选中判断题目的正确答案');
                      return false;

             }
             $('#addquestionBox .adderrormsg').html('');
             //type ='判断题'
             //alert("3判断题")

        }
        //判断题结束
        if(type==4){//填空题
            var tiankongarr = new Array();
               $('input[name=tiankong]').each(function () {
                   tiankongarr.push($.trim($(this).val()));
               });
              if (tiankongarr =='') {
                  $('#addquestionBox .adderrormsg').html('请输入答案');
                  return false;
              }
               answer = tiankongarr.join("|||");
                $('#addquestionBox .adderrormsg').html('');
                //type ='填空题'
            // alert("4填空题")

        }
        //填空题结束
        if(type==5){//夺旗题
            var changjing = $('#changjingcode').val();
              if(changjing == ''){
                  $('#addquestionBox .adderrormsg').html('请选择关联CTF场景或关联实验场景');
                  return false;
              }

               answer = $('input[name=flag]').val();

              if (answer == "") {
                  $('#addquestionBox .adderrormsg').html('请输入答案');
                  return false;
              }
               $('#addquestionBox .adderrormsg').html('');
               //type ='夺旗题'
              
            // alert("5夺旗题")

        }

        var linktype = $("#relationscene").find(".checkNow").attr("typect")//获取所关联的场景类型
        if(linktype==1&&$("#changjingname").val()==''){
          $('#addquestionBox .adderrormsg').html('请选择关联一个ctf场景或者将题目不关联场景');
          return false;
        }
        if(linktype==2&&$("#changjingname").val()==''){
          $('#addquestionBox .adderrormsg').html('请选择关联一个实验场景或者将题目不关联场景');
          return false;
        }
        var link = $("#changjingcode").val();//获取具体场景信息
        var ulrArr = new Array();
        var nameArr = new Array();
        $('#addquesTable tr').each(function () {
            var urla = $(this).attr("urla");//附件地址
            var namea =$(this).attr("namea");//附件名字
            ulrArr.push(urla);
            nameArr.push(namea);

        })

         $.ajax({
               url: site_url + "Subject/doaddquestion",
               type: 'post',
               data: {
                   'QuestionDesc': tigan,
                   'QuestionType': type,
                   'QuestionDiff': grade,
                   'QuestionAnswer': answer,
                   'choosearray': choose,
                   'QuestionLink': link,
                   'ResourceUrl': ulrArr,
                   'ResourceName': nameArr,
                   'QuestionLinkType': linktype
 
               },
               dataType: 'json',
               success: function (message) {
                   //console.log("true");
                  if(message.code != 0){
                      $('#addquestionBox .adderrormsg').html('已存在相同题干');
                  }else{
                      $('#addquestionBox .adderrormsg').html('保存成功');

                      //alert(message.data);
                      parent.savequestionsuccess(message,type,tigan);//开始把新加的题目信息加入到存放选择题目的具体信息的数组中
                      fnHide("#addquestionBox","fadeInDown","fadeOutUp");
                      $(".daoNews2").show();
                      $('#addquestionBox .adderrormsg').html('');

                  }
               }
           })
     })
    //点击选择资料
    $("#selectDataBtn").click(function(){
        tongjidata();
        $(".goSearch input").val('');//初始化弹窗搜索
        sapGetData(site_url+'Subject/datumlist', sapSucData, "dataPage");
    	fnShow("#selectDataBox","fadeOutUp","fadeInDown")
    })
    //点击选择资料确定按钮
    $("#goToData").click(function(){
    	 assemblingData();
    	 fnHide("#selectDataBox","fadeInDown","fadeOutUp")
    })
    //点击页面新增题目按钮
    $("#addQuesBtn").click(function(){
    	tongji();
     fnShow("#addquestionBox","fadeOutUp","fadeInDown")

    })
    //点击页面选择题目按钮
    $("#selectQuesBtn").click(function(){
      tongji();
      $("#sapSearch_choseQuesPage").val('')
    	sapGetData(site_url+'Subject/all_question', sapSucQuesList, "choseQuesPage");

        fnShow("#selectQuesBox","fadeOutUp","fadeInDown")
    })

    $(".quesearch").click(function(){
        sapGetData(site_url+'Subject/all_question', sapSucQuesList, "choseQuesPage");

    });

    $('#sapSearch_choseQuesPage').keydown(function(e){
        if(e.keyCode==13){
            sapGetData(site_url+'Subject/all_question', sapSucQuesList, "choseQuesPage");
        }
    });

    $(".toolsearch").click(function(){
        sapGetData(site_url+'Subject/datumlist', sapSucData, "dataPage");

    });

    $('#sapSearch_dataPage').keydown(function(e){
        if(e.keyCode==13){
            sapGetData(site_url+'Subject/datumlist', sapSucData, "dataPage");
        }
    });




    //新增题目弹窗a标签颜色切换
     $("#addquestionBox .box-input-cen .bQian").click(function(){
        $(this).addClass("checkNow").siblings().removeClass("checkNow")
    });
    //点击新增弹窗的关联场景按钮
    //不关联场景
    $("#noCJbtn").click(function(){
        $("#ctfOrSec").addClass("outHide");
        $("#ctfOrSec input").val('')
    })
    //点击选择题目弹窗按钮
    $("#questionOk").click(function(){
    	quesasData();
    	fnHide("#selectQuesBox","fadeInDown","fadeOutUp");
    	
    })
    //新增页面上的提交按钮
    $("#saveAllNews").click(function(){

        var reg = /[<>*~!@#$^&*()=|{}]/;
    	var sectionname = $('#SectionName').val().trim();//获取节的名称
    	if(sectionname == ''){
    		$("#allEroar").html("请输入节名称");

    		return false;
    	}
        var sectiondesc = $('#SectionDesc').val().trim();//获取内容简介
        if(sectiondesc.length > 225 ){
            $('#allEroar').html("小节描述不能超过225个字符");
            return false;            
        }
        if(reg.test(sectionname) || reg.test(sectiondesc)){
            $('#allEroar').html("输入的小节名或描述里有不合法字符");
            return false;
        }
        var grade =$("#SectionGrade .cur").attr("grade")//获取小节难度等级
        var SectionType = $("#SectionType .cur").attr("type")//获取节类型

        var VideoName = '';
        var ctfcode = '';
        var SceneUUID = '';
        var VideoTime ='';
        var re = /^[0-9]*[1-9][0-9]*$/ ;
         switch(SectionType){//判断属于哪一个类型
            case '0': 
                VideoName = $("#SectionVideo").val();
                VideoTime = $.trim($("#VideoTime").val());
                if (VideoName == "") {
                    $('#allEroar').html("请上传一个视频");
                    return false;
                }
                if(VideoTime==''){
                    $('#allEroar').html("请填写视频时长");
                    return false;
                }
                if(!re.test(VideoTime)){
                    $('#allEroar').html("请填写有效时长，时长应为正整数");
                    return false;
                }
                break;
            case '1': 
                ctfcode = $("#SectionCtfcode").val();
                if (ctfcode == "") {
                    $('#allEroar').html("CTF实验小节必须关联一个CTF场景");
                    return false;
                }
                break;
            case '2':
                SceneUUID = $("#SectionScenecode").val();
                if (SceneUUID == "") {
                    $('#allEroar').html("网络实验小节必须关联一个网络场景");
                    return false;
                }
                break;
            default:
        }
         var SectionDoc = $("#SectionDoc").val();//获取实验操作手册内容
         var gothis = true;
         var quesLast = new Array();
         quesLast = []//统计之前清空防止重复；
         var sum = 0;
         var queslengs = $("#selectedQuestionTable").children().length
         if(queslengs>0){//当页面的题目列表存在题目的时候才判断
         //操作quesContents数组，获得页面题目的ID，题目类型，分数，并且验证分数总和是不是100；不能为0
           $.each(quesContents,function(i,n){
        		var arr = n.split('@@@@');
        		var code = arr[0];
        		var typeQues = arr[2];//题目类型
        		var checkIsNo = arr[5];//是否关联场景，选中关联场景存储为2，不选中关联场景存储为1；
        		var numbers = parseInt(arr[4]);//题目分数
        		
        		//var tt =code+'@@@'+typeQues+'@@@'+numbers+'@@@'+checkIsNo;
        		var tt =code+'@@@'+numbers+'@@@'+checkIsNo;
        		
           if(numbers!=arr[4]||numbers ==0){
                $('#allEroar').html('请输入分数，且分数只能为正整数');
                gothis=false
                return false;
            }
        		else{
              
        			sum = sum + numbers
        			if(numbers==100&&queslengs>1){
        				$('#allEroar').html("存在多条题目时，单条题目分数不能大于或者等于100");
        				gothis = false;
        				return false;
        			}
        			quesLast.push(tt);
        		}
    	})
        if(gothis!=true){
        	return false;
        };
        if(sum!=100){
			$('#allEroar').html("分数总和不等于100");
			return false;
		};
       }

       $('#allEroar').html("");

        var fals = $(this).attr('falg');
        if(fals==1)
        {
            $.ajax({
                url:site_url+"Subject/dosection",
                type:'post',
                data:{
                    'cid':PackageCode,
                    'uniid':UnitCode,//单元IDID
                    //'SectionCode':SectionCode,//新增节的ID
                    'SectionPoint':0,
                    'SectionDocType':0,
                    'SectionType':SectionType,//节的类型，0>>理论节，1>>ctf实验，2>>网络实验
                    'SceneUUID':SceneUUID,//选择的试验场ID
                    'CtfCode':ctfcode,//选择的ctf场景ID
                    'toolChecked':toolChecked,//选择的资料ID数组
                    'quesLast':quesLast,//选择的题目信息数组
                    'SectionName':sectionname,//小节名字
                    'SectionDesc':sectiondesc,//小节描述
                    'grade':grade,//小节难度等级
                    'SectionDoc':SectionDoc,//实验手册内容
                    'VideoName':VideoName,//视屏信息
                    'VideoTime':VideoTime//时长
                },
                dataType:'json',
                success:function(msg){
                    //console.log(msg);
                    if(msg.code != 0){
                        //$('#allEroar').text(msg.msg);
                    }else {

                        $("#saveAllNews").attr('falg', '2');
                        $("#saveAllNews").css({"background-color":"#eeeeee"});
                        $('#allEroar').text('新增成功');
                        setTimeout(function(){
                            window.location.href =site_url+"Subject/courseframe/cid/"+PackageCode;
                        },2000)
                    }
                }
            });
        }




    })
    //点击新增叉叉关闭键
    $("#closeSelectQues").click(function(){
      fnHide("#addquestionBox","fadeInDown","fadeOutUp");
      $(".daoNews2").show();
      clearQueOld();

    })
    //编辑页面上的提交按钮
    $("#editAllNews").click(function(){
    	var reg = /[<>*~!@#$^&*()=|{}]/;
    	var sectionname = $('#SectionName').val().trim();//获取节的名称
    	if(sectionname == ''){
    		$("#allEroar").html("请输入节名称");

    		return false;
    	}
        var sectiondesc = $('#SectionDesc').val().trim();//获取内容简介
        if(sectiondesc.length > 225 ){
            $('#allEroar').html("小节描述不能超过225个字符");
            return false;
        }
        if(reg.test(sectionname) || reg.test(sectiondesc)){
            $('#allEroar').html("输入的小节名或描述里有不合法字符");
            return false;
        }
        var grade =$("#SectionGrade .cur").attr("grade")//获取小节难度等级
        var SectionType = $("#SectionType .cur").attr("type")//获取节类型
        var oldtype = $("#oldtype").val()//获取节类型

        var VideoName = '';
        var ctfcode = '';
        var SceneUUID = '';
        var VideoTime ='';
        var re = /^[0-9]*[1-9][0-9]*$/ ;
         switch(SectionType){//判断属于哪一个类型
            case '0':
                VideoName = $("#SectionVideo").val();
                VideoTime = $.trim($("#VideoTime").val());
                if (VideoName == "") {
                    $('#allEroar').html("请上传一个视频");
                    return false;
                }
                if(VideoTime==''){
                    $('#allEroar').html("请填写视频时长");
                    return false;
                }
                if(!re.test(VideoTime)){
                    $('#allEroar').html("请填写有效时长，时长应为正整数");
                    return false;
                }
                break;
            case '1':
                ctfcode = $("#SectionCtfcode").val();
                if (ctfcode == "") {
                    $('#allEroar').html("CTF实验小节必须关联一个CTF场景");
                    return false;
                }
                break;
            case '2':
                SceneUUID = $("#SectionScenecode").val();
                if (SceneUUID == "") {
                    $('#allEroar').html("网络实验小节必须关联一个网络场景");
                    return false;
                }
                break;
            default:
        }
         var SectionDoc = $("#SectionDoc").val();//获取实验操作手册内容
         var gothis = true;
         var quesLast = new Array();
         quesLast = []//统计之前清空防止重复；
         var sum = 0;
         var queslengs = $("#selectedQuestionTable").children().length
         if(queslengs>0){//当页面的题目列表存在题目的时候才判断
         //操作quesContents数组，获得页面题目的ID，题目类型，分数，并且验证分数总和是不是100；不能为0

             //读取过来的题目
             tongji();
           $.each(quesContents,function(i,n){
        		var arr = n.split('@@@@');
        		var code = arr[0];
        		var typeQues = arr[2];//题目类型
        		var checkIsNo = arr[5];//是否关联场景，选中关联场景存储为2，不选中关联场景存储为1；
        		var numbers = parseInt(arr[4]);//题目分数

        		//var tt =code+'@@@'+typeQues+'@@@'+numbers+'@@@'+checkIsNo;
        		var tt =code+'@@@'+numbers+'@@@'+checkIsNo;
        		 if(numbers!=arr[4]||numbers ==0){
                $('#allEroar').html('请输入分数，且分数只能为正整数');
                gothis=false
                return false;
            }
        		else{
        			sum = sum + numbers
        			if(numbers==100&&queslengs>1){
        				$('#allEroar').html("存在多条题目时，单条题目分数不能大于或者等于100");
        				gothis = false;
        				return false;
        			}
        			quesLast.push(tt);
        		}
    	})
        if(gothis!=true){
        	return false;
        };
        if(sum!=100){
			$('#allEroar').html("分数总和不等于100");
			return false;
		};
       }

        tongjidata();

       $('#allEroar').html("");
		 $.ajax({
            url:site_url+"Subject/modsection",
            type:'post',
            data:{
                'cid':PackageCode,
                'secid':SectionCode,//单元IDID
                'oldtype':oldtype,
                'SectionPoint':0,
                'SectionDocType':0,
                'SectionType':SectionType,//节的类型，0>>理论节，1>>ctf实验，2>>网络实验
                'SceneUUID':SceneUUID,//选择的试验场ID
                'CtfCode':ctfcode,//选择的ctf场景ID
                'toolChecked':toolChecked,//选择的资料ID数组
                'quesLast':quesLast,//选择的题目信息数组
                'SectionName':sectionname,//小节名字
                'SectionDesc':sectiondesc,//小节描述
                'grade':grade,//小节难度等级
                'SectionDoc':SectionDoc,//实验手册内容
                'VideoName':VideoName, //视屏信息
                'VideoTime':VideoTime//时长
            },
            dataType:'json',
            success:function(msg){
                //console.log(msg);
                if(msg.code != 0){
                    //$('#allEroar').text(msg.msg);
                }else {
                    $('#allEroar').text('编辑成功');
                    setTimeout(function(){
                        window.location.href =site_url+"Subject/courseframe/cid/"+PackageCode;
                    },2000)
                }
            }
        });



    })




})

//删除
function delres(ee) {
    $(ee).parent().parent().remove();

}

// 上传图片方法
function uploadpic(){
    $.ajaxFileUpload({
        url:site_url+'Subject/upimg',
        secureuri:false,
        fileElementId:'upload',
        dataType:'json',
        success:function(message){
          console.log(message)
            if(message.status==1){
                var quescktxt = '';
                var name = message['filenames']
                quescktxt += '<tr>';
                quescktxt += '<td><a target="_blank" href= "'+base_url+'resources/files/img/course/'+name+'" >../..'+'/resources/files/img/course/'+message['filenames']+'</a> </td>';
                quescktxt += '<td><a href="javascript:;" data-clipboard-text="!['+name+'](../../resources/files/img/course/'+name+')" class="forRed copyImage"  ><i class="fa fa-copy"></i>复制</a></td>';
                quescktxt += '</tr>';
                $('#imgresTable').append(quescktxt);
            }
        }
    })
}


//选中ctf或者sece后填入页面
function selectThis(isme){
	
    var parName = $(isme).attr("parName"),
    	chiName =$(isme).attr("chiName");
    //通过判断新增题目弹窗是否显示，来把选择的值放在正确的位置
    if($("#addquestionBox").css("display")=="none"){
        $("#"+chiName+"code").val(isme.name);
    	$("#"+chiName).val(isme.title);  
    	fnHide("#"+parName, "fadeInDown", "fadeOutUp");             
                       }
     else{
     	$("#ctfOrSec").removeClass('outHide')
     	$("#changjingname").val(isme.title);
    	$("#changjingcode").val(isme.name); 
    	fnHide("#"+parName, "fadeInDown", "fadeOutUp",1);
    	$("#addquestionBox").css({"z-index":"99999"})  
     }
    
    
}

//试验资料--选中///选择题目弹窗--选中
var toolContents = new Array();//统计被选中的实验资料具体信息
var toolChecked = new Array();//统计被选中的实验资料ID信息
var quesContents = new Array();//统计被选中的题目具体信息
var quesChecked = new Array();//统计被选中的题目ID信息

function checkedData(isme,moreArr,idArr,popId){
	var code = $(isme).attr('value');
    var author = $(isme).attr('author');
    var qstype = $(isme).attr('qstype');
    var desc = $(isme).attr('desc');
    var score = $(isme).attr('score'); //0表示此题目在数据库没有分数
    var checkNow = 1;//表示默认没被选中

    var tt = ''
    if(!score){
     tt= code+'@@@@'+author+'@@@@'+qstype+'@@@@'+desc;
    }
    else{
    	//1，表示在页面的题目列表中的是否选择场景没有被选中，2表示选中
    	 //点击不被选中后，去除moreArr中的数据，在选择题目时，
		//很可能存在用户输入分数，或者点击了是否关联场景选项，
		//这样quesContents的sore可能不为0，最后一项表示是否选中可能不为1，
		//会导致删除quesContents的数据有错误,导致每次删除都是删除最后一项
		var scoreNew = $.trim($("#selectedQuestionTable #id"+code).val())
		var chekisno = $("#selectedQuestionTable #id"+code).parent().siblings().children(".chekisNo")
		if(chekisno.is(':checked')){
			checkNow = 2;
		}
		 if(scoreNew){
		 	score = scoreNew
		 }

		tt= code+'@@@@'+author+'@@@@'+qstype+'@@@@'+desc+'@@@@'+score+'@@@@'+checkNow;
    }
    if ($(isme).is(':checked')){

        if(jQuery.inArray(code,idArr) == -1){
            idArr.push(code);
            moreArr.push(tt);
        }
        
       $("#"+popId+" .titleLook span").html(idArr.length);
        
    } else {
        moreArr.splice($.inArray(tt,moreArr),1)

        $.each(idArr,function(n,m){
            if(m == code){
                idArr.splice($.inArray(code,idArr),1);
            }
        })
        $("#"+popId+" .titleLook span").html(idArr.length);
    }
	
}

//把选中的资料拼装在页面
function assemblingData(){
    var toolTableHtml = '';
    $.each(toolContents,function(i,n){
        var arr = n.split('@@@@');
        toolTableHtml += '<tr>';
        toolTableHtml += '<td title="'+arr[2]+'">'+arr[2]+'</td>';
        toolTableHtml += '<td>'+arr[1]+'</td>';
        toolTableHtml += '<td><a code="'+arr[0]+'" author="'+arr[1]+'" qstype="'+arr[2]+'" desc="'+arr[3]+'" href="javascript:;" class="forRed" code="'+arr[0]+'" onclick="delthisData(this)"><i class="fa fa-trash-o"></i>删除</a></td>';
        toolTableHtml += '</tr>';

    })

    $("#youChoseData").html(toolTableHtml);
}

//把选中的题目拼装在页面
function quesasData(){
    //console.log(quesContents);
	var questionTableHtml = '';
    $.each(quesContents,function(i,n){
        var arr = n.split('@@@@');
	// console.log("剩下的："+arr)
        var score = '';
        if(arr[4] !=0){//0表示数据库没有分数
            score = arr[4]
        }

        var chebox = '';

        if(arr[5]==2)
        { chebox = 'checked'; }

        questionTableHtml += '<tr>';
        questionTableHtml += '<td title="'+decodeURI(arr[3])+'">'+decodeURI(arr[3])+'</td>';
        questionTableHtml += '<td>'+arr[2]+'</td>';
        questionTableHtml += '<td>'+arr[1]+'</td>';
        questionTableHtml += '<td ><input  type="text" maxlength="3" typestatus="'+arr[2]+'" placeholder="请输入分数" class="score" code="'+arr[0]+'" author="'+arr[1]+'" qstype="'+arr[2]+'" desc="'+arr[3]+'"  value = "'+score+'"  onchange = tongji() id="id'+arr[0]+'"  ></td>';
        questionTableHtml += '<td><a questioncode="'+arr[0]+'" href="javascript:;" class="forRed" onclick=delSelectedQuestion(this)><i class="fa fa-trash-o"></i>删除</a></td>';
        questionTableHtml += '<td><input class="chekisNo" type="checkbox"'+' qu-code="'+ arr[0] +'" name="chscene"'+ chebox +' data-code="'+ arr[0] +'" onclick=tongji()></td>';
        questionTableHtml += '</tr>';
    })
    
    $("#selectedQuestionTable").html(questionTableHtml);

}

//实验资料单条删除记录
function delthisData(isme){
	var code = $(isme).attr('code');//资料的ID
	var author = $(isme).attr('author');
    var qstype = $(isme).attr('qstype');
    var desc = $(isme).attr('desc');
    var tt = code+'@@@@'+author+'@@@@'+qstype+'@@@@'+desc;
    //alert(tt)

    toolChecked.splice(jQuery.inArray(code,toolChecked),1);
    toolContents.splice(jQuery.inArray(tt,toolContents),1);
    assemblingData();

    // alert(jQuery.inArray(tt,toolContents))

}

//统计列表信息，存在用户输入或者页面有默认的信息
function tongji(){
	quesContents =[];
  quesChecked = [];
    var thisIsme = $("#selectedQuestionTable").children()
    for(i=0;i<thisIsme.length;i++){
		var code = thisIsme.eq(i).children().children(".score").attr('code');
        var author = thisIsme.eq(i).children().children(".score").attr('author');
        var qstype = thisIsme.eq(i).children().children(".score").attr('qstype');
        var desc = thisIsme.eq(i).children().children(".score").attr('desc');
        var score = thisIsme.eq(i).children().children(".score").val();
        if(score ==""){
        	score=0
        }
        // if(isNaN(score)||score<0||score>100){
        // 	alert("请输入正确的分数,分数不能为负数，单个题目分数不能超过总分100")
        // }
        var checkNow = 1;
        if(thisIsme.eq(i).children().children(".chekisNo").is(':checked')){
        	checkNow = 2;//表示选中场景 

        }
       
        var tt = code+'@@@@'+author+'@@@@'+qstype+'@@@@'+desc+'@@@@'+score+'@@@@'+checkNow;
         quesContents.push(tt);
         quesChecked.push(code);

	}
	// alert("统计：--------"+quesContents)
}

//统计资料
function tongjidata(){
    toolChecked =[];
    toolContents = [];
    var thisIsme = $("#youChoseData").children()
    for(i=0;i<thisIsme.length;i++){
		var code = thisIsme.eq(i).children().children(".forRed").attr('code');
        var author = thisIsme.eq(i).children().children(".forRed").attr('author');
        var qstype = thisIsme.eq(i).children().children(".forRed").attr('qstype');
        var desc = thisIsme.eq(i).children().children(".forRed").attr('desc');

        var tt = code+'@@@@'+author+'@@@@'+qstype+'@@@@'+desc;
        toolContents.push(tt);
        toolChecked.push(code);

	}
	//alert("统计：--------"+toolContents)
}



//题目单条删除
function delSelectedQuestion(isme){
	var delcode = $(isme).attr('questioncode');
	quesChecked.splice(jQuery.inArray(delcode,quesChecked),1);
	$(isme).parent().parent().remove();
	tongji();
	quesasData();
}

//加载弹窗ctf数据
function sapSucCtf(data){
	var questxt = '';
    if(data.length == 0){
         $("#ctfListBox").find("#ctTble").hide();
        $("#ctfListBox").find(".noNews").show();

    }else{
        $.each(data,function(i,v){
            questxt += '<tr>';
            questxt += '<td title="'+ v['CtfName']+'">'+ v['CtfName']+'</td>';
            questxt += '<td title="'+ v['CtfContent']+'">'+ v['CtfContent']+'</td>';
            questxt += '<td><a href="javascript:void(0)" name="'+ v['CtfID']+'"  title="'+ v['CtfName']+'" onclick="selectThis(this)" class="forBlue" chiName = "SectionCtf" parName = "ctfListBox">选择</a></td>';
            questxt += '</tr>';
        });
        $('#ctTble').html('');
        $('#ctTble').append(questxt);
        $("#ctfListBox").find("#ctTble").show();
        $("#ctfListBox").find(".noNews").hide();
    }

}

//加载弹窗sece数据
function sapSucScene(data){
    //alert(data.length);
	var questxt = '';
  // alert(data)
    if(data.length == 0){
          $("#seceListBox").find("#seTable").hide();
          $("#seceListBox").find("#secePage").hide();
         $("#seceListBox").find(".noNews").show();

    }else{
        $.each(data,function(i,v){
            questxt += '<tr>';
            questxt += '<td  title="'+ v['scene_name']+'">'+ v['scene_name']+'</td>';
            questxt += '<td  title="'+ v['description']+'">'+ v['description']+'</td>';
            questxt += '<td ><a href="javascript:void(0)" name="'+ v['scene_tpl_uuid']+'"  title="'+ v['scene_name']+'" onclick="selectThis(this)" class="forBlue" chiName = "SectionScene" parName = "seceListBox">选择</a></td>';
            questxt += '</tr>';
        });
        $('#seTable').html('');
        $('#seTable').append(questxt);

        $("#seceListBox").find("#seTable").show();
        $("#seceListBox").find("#secePage").show();
        $("#seceListBox").find(".noNews").hide();
    }

}

//加载选择资料的弹窗数据
function sapSucData(data){
	 var questxt = '';
    $.each(data,function(i,v){
        questxt += '<tr>';
        questxt += '<td ><input class="quescode" type="checkbox" onclick=checkedData(this,toolContents,toolChecked,"selectDataBox") name="quescode[]" desc="'+v['ToolName']+'" qstype="'+v['ToolName']+'" author="'+v['ToolUrl']+'" value="'+v['ID']+'"></td>';
        questxt += '<td title="'+v['ToolName']+'">'+v['ToolName']+'</td>';
        questxt += '<td title="'+v['ToolUrl']+'">'+v['ToolUrl']+'</td>'; 
        questxt += '</tr>';
    });
    $('#dataTable').html(questxt);

    $('#dataTable input[class=quescode]').each(function(){
        if(jQuery.inArray($(this).val(),toolChecked) != -1){
            $(this).prop('checked',true)
        }
        else{
        	 $(this).prop('checked',false)
        }
    })

    $('#selectDataBox .titleLook span').html(toolChecked.length);

}

//加载选择题目弹窗数据
function sapSucQuesList(data){
    var questxt = '';
    if(data.length == 0){
        $("#selectQuesBox").find("#questionTable").hide();
        $("#selectQuesBox").find(".noNews").show();

    }else{
        var questionTableHtml = '';
        var mymame = '';
        $.each(data,function(i,v){
            v['QuestionDesc'] = encodeURI(v['QuestionDesc']);
            if(v['QuestionType'] == 1){v['QuestionType']='单选题'}else
            if(v['QuestionType'] == 2){v['QuestionType']='多选题'}else
            if(v['QuestionType'] == 3){v['QuestionType']='判断题'}else
            if(v['QuestionType'] == 4){v['QuestionType']='填空题'}else
            if(v['QuestionType'] == 5){v['QuestionType']='夺旗题'}
            questionTableHtml += '<tr>';
            questionTableHtml += '<td><input class="quescode" type="checkbox"  onclick=checkedData(this,quesContents,quesChecked,"selectQuesBox") name="quescode[]" desc="'+v['QuestionDesc']+'" qstype="'+v['QuestionType']+'" author="'+v['QuestionAuthor']+'" choose="'+v['ischeck']+'" value="'+v['QuestionID']+'" score="0"></td>';//由于加载的是原始题目，所以很可能没有分数，所以给了默认值，如果数据库有请加上，否则有bug
            questionTableHtml += '<td title="'+v['QuestionDesc']+'">'+decodeURI(v['QuestionDesc'])+'</td>';
            questionTableHtml += '<td>'+v['QuestionAuthor']+'</td>';

            questionTableHtml += '<td>'+v['QuestionType']+'</td>';
            questionTableHtml += '</tr>';
        });
        $('#questionTable').html(questionTableHtml);

        $('#questionTable input[class=quescode]').each(function(){
            if(jQuery.inArray($(this).val(),quesChecked) != -1){
                $(this).prop('checked',true);

            }
        })

        $('#selectQuesBox .titleLook span').html(quesChecked.length);
        $("#selectQuesBox").find("#questionTable").show();
        $("#selectQuesBox").find(".noNews").hide();

    }

}

//新增题目弹窗，选项减少与增加以及删除单条附件记录
function delthis(isme){
        $(isme).parent().remove();
    }

function addthis(isme){
    var thisMe = $(isme).siblings("input");
    var addOPend = thisMe.clone();
    var jian =  '<a href ="javascript:;" onclick="delthis(this)"><i class="fa fa-minus"></i></a>'
    $(isme).parent().append("<p></p>");
    var pAddresnumber = $(isme).parent().children().length;
    var pAddres = $(isme).parent().children().eq(pAddresnumber-1);
    pAddres.append(addOPend.val('').attr("checked",false));
    pAddres.append(jian)
    
}

//新增题目表格上床信息的单条删除
function delthisques(isme){
	$(isme).parent().parent().remove();
}

//初始化新增题目的弹窗
function clearQueOld(){
	$("#addquestionBox .clearAcheck1").children().eq(1).addClass("checkNow").siblings().removeClass("checkNow");//初始化选项
	$("#addquestionBox .clearAcheck2").children().eq(1).addClass("checkNow").siblings().removeClass("checkNow");//初始化选项
	$("#addquestionBox .clearAcheck3").children().eq(1).addClass("checkNow").siblings().removeClass("checkNow");//初始化选项
	$("#addquestionBox .anserBox").eq(0).removeClass("outHide").siblings().addClass("outHide");//题目答案类型
	$("#addquestionBox input[type=text]").val('');
	$("#addquestionBox input[type=checkbox]").attr("checked",false);
	$("#addquestionBox input[type=radio]").attr("checked",false);
	$("#addquestionBox textarea").val('');
	$("#addquestionBox #xuantiBox p").remove();
	$("#addquestionBox #addquesTable tr").remove();
	$("#addquestionBox #ctfOrSec").addClass("outHide");
	//uploadTool.clearSelf();
}

//把通过新增题目弹窗得到的新题目填入页面
function savequestionsuccess(message,type,tigan){
    var tarr = ['单选题','多选题','判断题','填空题','夺旗题'];

    if(message.code=='0000'){
         var tt = message.data+'@@@@'+ author +'@@@@'+ tarr[type-1] +'@@@@'+ tigan +'@@@@'+0+'@@@@'+1;//0，表示题目在数据库没有存分数，1，表示在页面的题目列表中的是否选择场景没有被选中，2表示选中;
         quesContents.push(tt);
         quesChecked.push(message.data);
         quesasData();
         clearQueOld();//初始化弹窗
     }else{
         alert(message.msg);
     }
}

