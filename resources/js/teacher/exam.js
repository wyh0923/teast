/**
 * Created by Administrator on 2016/8/29.
 */

$(function () {

    //一键均分按钮
	$(".onekeyaverage").click(function(){
		//获取题目数量
		var childLength = $("#queBox").children().length;
		if(childLength ==0 ){
            $('#adderrormsg').html('您还未选择题目，请为您的试卷选择题目');
            return false;
        }
       
        //总分
		var totalScore = 100;
		//平均分
		var averageScore = 0;
		//剩余分
		var leftover = 0;
		if(totalScore % childLength==0){
			averageScore=totalScore / childLength;
		}else{
			averageScore =Math.floor(totalScore / childLength);
			leftover = totalScore - averageScore * childLength;
		}
        
		//循环判断分数
		for(i=0;i<childLength;i++){
            if((i+1)<=leftover){
            	$("#queBox").children().eq(i).find(".score").val(averageScore+1);
            }else{
            	$("#queBox").children().eq(i).find(".score").val(averageScore);
            }
        }
	});
	
	
	//试卷
    $(".csear").click(function(){
        var search = translate($.trim($(".esear").val()));

        var str = '';
        if (time != '')str += '/time/'+ time;
        window.location.href=site_url + 'Subject/myexam' +str+ "/search/"+encodeURI(search);
    });
    $('.esear').keydown(function(e){
        if(e.keyCode==13){
            var search = translate($.trim($(".esear").val()));
            var str = '';
            if (time != '')str += '/time/'+ time;
            window.location.href=site_url + 'Subject/myexam' +str+ "/search/"+encodeURI(search);
        }
    });

    //是否可编辑
    $(".ismod").click(function(){
        var code=$(this).attr('code');
        var name = $(this).attr('name');
        var diff = $(this).attr('diff');
        $.ajax({
            url: site_url + 'Subject/isfinish',
            data: {'qid': code},
            type: 'post',
            dataType: 'json',
            success: function (msg) {

                if (msg.code == '0000') {
                    $('#edOk p.promptNews').html('试卷任务未完成，不可编辑');
                    fnShow("#edOk", "fadeOutUp", "fadeInDown");
                    setTimeout(function () {
                        fnHide("#edOk", "fadeInDown", "fadeOutUp");
                    }, 2000);
                }
                else {

                    window.location.href= site_url +'Subject/editexam/eid/'+ code +'/name/'+ name +'/diff/'+ diff;
                }
            }
        });

    });

    //我的试卷列表页单记录删除题目

    $(".edel").click(function(){
        var code=$(this).attr('code');

        $.ajax({
            url: site_url + 'Subject/isfinish',
            data: {'qid': code},
            type: 'post',
            dataType: 'json',
            success: function (msg) {

                if (msg.code == '0000') {
                    $('#edOk p.promptNews').html('试卷关联考试任务，不可删除');
                    fnShow("#edOk", "fadeOutUp", "fadeInDown");
                    setTimeout(function () {
                        fnHide("#edOk", "fadeInDown", "fadeOutUp");
                    }, 2000);
                }
                else {
                    $('#eOk').attr('code',code);

                    fnShow("#edelOk","fadeOutUp","fadeInDown");
                }
            }
        });

    });

    //排序
    $('#CreateTime,#ExamName,#TeacherID').click(function(){
        var field = $(this).attr("id");
        var code = $(this).attr('code');
        var str = '';
        if (time != '')str += '/time/'+ time;
        if (search != '')str += '/search/'+translate(search);
        if(code == 'DESC'){
            location.href = site_url+'Subject/myexam' + str + '/sort/'+field+' ASC';
        }else if(code == 'ASC'){
            location.href = site_url+'Subject/myexam' + str + '/sort/'+field+' DESC';
        }else{
            location.href = site_url+'Subject/myexam' + str + '/sort/'+field+' DESC';
        }
    });

    //我的试卷列表页确定删除单记录题目
    $('#eOk').click(function(){
        var cid = $(this).attr('code');
        //alert(cid);return;

        fnHide("#edelOk","fadeInDown","fadeOutUp");
        $.ajax({
            url:site_url+"Subject/delexam",
            type:'post',
            data:{'code':cid},
            dataType:'json',
            success:function(message){

                if(message.code=='0000'){

                    fnShow("#edOk","fadeOutUp","fadeInDown");
                    $('#edOk p.promptNews').html('删除成功');
                    setTimeout("location.reload()",2000);
                }else{

                    $('#edOk p.promptNews').html('删除失败');
                    fnShow("#edOk","fadeOutUp","fadeInDown");
                    setTimeout("location.reload()",2000);
                }
            }

        });
    });

    $('#level label').click(function () {
        $(this).addClass('cur').siblings('label').removeClass('cur');

        //alert($(this).attr('code'));
    });

    //新增试卷
    $('#addOk').click(function () {
        var reg = /[<>*~!@#$^&*()=|{}]/;
        var name = $.trim($('#examname').val());//获得试卷名字
        if(name == ''){
            $("#adderrormsg").html("试卷名称不能为空，请输入试卷名称")
            return false;
        }else if (reg.test(name)){
            $('#adderrormsg').html('试卷名称里有不合法字符');
            return false;
        } else if (name.length<3 || name.length>16){
            $('#adderrormsg').html('试卷名称应该为3-16位字符');
            return false;
        }
        var level = $('#level .cur').attr('code');//获得试卷难度等级
        var sum = 0//分数总和
        var childLength = $("#queBox").children().length;
        if(childLength ==0 ){
            $('#adderrormsg').html('您还未选择题目，请为您的试卷选择题目');
            return false;
        }
        lastArr =[];//清空数组防止分数加入冲突
        var re = /^[0-9]*[1-9][0-9]*$/ ;
        var flag = 0;
        for(i=0;i<childLength;i++){
            var qid = $("#queBox").children().eq(i).find(".delate").attr('qid');//questionid
            var tvalue = $("#queBox").children().eq(i).find(".delate").attr('tvalue');//
            var cvalue = $("#queBox").children().eq(i).find(".delate").attr('cvalue');//
            var qlcode = $("#queBox").children().eq(i).find(".score").val();//分数
            var qltype = $("#queBox").children().eq(i).find(".delate").attr('qltype');//questionlinktype

            if(qltype == 2){
                flag ++;
            }

            if(qlcode == ''){
                $('#adderrormsg').html('请填写分数');
                return false;
            }
            if(qlcode <= 0){

                $('#adderrormsg').html('分数不能为0，或者负数');
                return false;
            }
            if(childLength>1&&qlcode==100){
                $('#adderrormsg').html('多条题目时，单条题目分数不能大于或者等于100');
                return false;
            }
            if(!re.test(qlcode)){
                $('#adderrormsg').html('分数不能为小数');
                return false;
            }
            sum = sum + parseInt(qlcode);
            
            var tt =qid+'@@@@'+qlcode+'@@@@'+tvalue+'@@@@'+cvalue;
            lastArr.push(tt);

        }
        if(sum!=100){
            //alert(sum)
            $('#adderrormsg').html('题目分数总和不等于100');
            return false;
        }

        if(flag > 1)
        {
            $('#adderrormsg').html('一个试卷只能存在一个关联场景的题目');
            return false;
        }
        $.ajax({
            url:site_url+"Subject/doaddexam",
            type:'post',
            dataType:'json',
            data:{'examname': name, 'level': level, 'data': lastArr},
            success:function(message)
            {
                if(message.code=='0000'){

                    $('#adderrormsg').html('新增成功');
                    setTimeout(function(){
                        window.location.href=site_url + "Subject/myexam";
                    },1000);
                } else {
                    $('#adderrormsg').html('试卷名称已存在');

                }
            }
        })
    });
    
    var lastArr = new Array();
    //点击页面确定提交修改后的试卷
    $('#editsave').click(function () {
        lastArr =[];
        var examcode =$(this).attr("examid");//试卷ID
        var level = $('#truetype .cur').attr('code');//获取等级
        var examname = $.trim($('input[name=examname]').val());//获取试卷名字
        var desc =  $("#queBox").children().length;//题目个数
        var sum = 0;//分数总和
        var type = 0;//实验题目数量
        var re = /^[0-9]*[1-9][0-9]*$/ ;
        for(i=0;i<desc;i++){
            var typeLink = $(".questionitem").eq(i).attr('qltype');
            var quesType = $(".questionitem").eq(i).attr("quesType");//题目类型
            var shuju = $(".questionitem").eq(i).attr("code");//题目id
            var shujuNumber =$.trim($(".questionitem").eq(i).find(".score").val());//题目分数
            var shu_shu = shuju+'@@@@'+shujuNumber+'@@@@'+quesType+'@@@@'+typeLink;
            if(shujuNumber == 0){
                $('#adderrormsg').html('题目分数不能为空');
                lastArr = []//清空重新统计
                return false;

            }
            else if(shujuNumber < 0){
                $('#adderrormsg').html('题目分数不能为负数');
                lastArr = []//清空重新统计
                return false;
            }
            else if(!re.test(shujuNumber)){
                $('#adderrormsg').html('分数不能为小数');
                return false;
            }
            else{
                lastArr.push(shu_shu)

            }


        }
        //统计实验题目数量
        $(".delate").each(function(){
            if($(this).attr('qltype') == 2){
                type ++
            }

        });
        if(type>1){
            $('#adderrormsg').html('您只能选择一个场景实验题目');
            type =0;
            lastArr = [];//清空重新统计;
            return;
        }
        //加分数综合
        $(".questiontitle input").each(function(){
            sum += parseInt($(this).val());
        });
        if(sum!=100){
            $('#adderrormsg').html('分数总和不为100');
            sum =0;
            lastArr = [];//清空重新统计;
            return;

        }

        if (examname == ''){
            $('#adderrormsg').html('试卷名称不能为空');
            lastArr = []//清空重新统计
            return;
        } else if (examname.length<3 || examname.length>16){
            $('#adderrormsg').html('试卷的名称应该为3-16位字符');
            lastArr = []//清空重新统计
            return;
        }
        if ($('.questionitem').length == 0){
            $('#adderrormsg').html('您的试卷还没有选题');
            lastArr = []//清空重新统计
            return;
        }

        //console.log(lastArr);return;
        //提交更改的数据
        $.ajax({
            url : site_url+'Subject/modexam',
            beforeSend : function(){
                $('#editsave').attr({'disabled':'disabled'});
            },
            data : {'level':level,'examname':examname,'examid':examcode,'lastArr':lastArr},
            type : 'post',
            dataType : 'json',
            success : function(msg){
                if (msg.code == '0000'){
                    $('#adderrormsg').html('试卷编辑成功');
                    setTimeout(function(){
                        window.location.href=site_url + "Subject/myexam";
                    },1000);
                }
            },
            complete : function(){
                $('#editsave').removeAttr('disabled');
            },
            error : function(msg){

            }
        })
    });

    //选择题目弹窗
    $("#addquestion").click(function(){
        //初始化弹窗,统计页面存在的题目信息
        totalcheck = [];

        quesNumber = $("#queBox").children().length;
        for(i=0;i<quesNumber;i++){
            var iteam = $(".delate").eq(i).attr("qid")        
                totalcheck.push(iteam)
        }
        tongji();
        $("#sapSearch_pageContainer").val('')
        $('#selexam .titleLook span').html(totalcheck.length);

        var url = site_url+'Subject/all_question';
        sapGetData(url, sapSuc, "pageContainer");
        setPosi("#selexam");//由于是异步ajax取数据，会造成对弹框的定位出现高度问题，所以等待加载结束重新对弹框定位
        fnShow("#selexam","fadeOutUp","fadeInDown")
    });

    //选择题目搜索
    $(".clickSear").click(function(){
        sapGetData(site_url+'Subject/all_question', sapSuc, "pageContainer");

    });
    //回车搜索
    $('#sapSearch_pageContainer').keydown(function(e){
        if(e.keyCode==13){
            sapGetData(site_url+'Subject/all_question', sapSuc, "pageContainer");

        }
    });
    //按照题目类型搜索
    $('#question_type').change(function(){
    	sapGetData(site_url+'Subject/all_question', sapSuc, "pageContainer");
    });

    //难度等级变化
    $("#truetype label").click(function(){
        $(this).addClass("cur").siblings().removeClass("cur")
    })

});

var quescontents = new Array();
var totalcheck = new Array();
// var yemian = new Array();
var quesNumber = 0;
var quesShu = 0;
//获取页面存在的题目信息
//选择题目
function checkeds(ppo){

    var code = $(ppo).attr('value');//id 0
    var author = $(ppo).attr('author');//zuozhe 1
    var qstype = $(ppo).attr('qstype');//tixing 2
    var qdesc = decodeURI($(ppo).attr('desc'));//miaoshu 3
    var qltype = $(ppo).attr('qltype');//changjing 4
    var queslink = $(ppo).attr('queslink');//guanlian 8
    var ctfurl = $(ppo).attr('CtfUrl');//ctfurl 9
    var qlcode = $("#score_"+code).val();//fenshu 5
    if(qlcode==undefined){
        qlcode="null"
    }

    var qlanswer = $(ppo).attr('qlanswer');//daan 6
    var qlchoose = $(ppo).attr('qlchoose');//xuxiang 7
    var dataurl = $(ppo).attr('dataurl');
    var dataname = $(ppo).attr('dataname');
    if($("#queBox").attr("urlif")){//编辑试卷时候
        var tt =code+'@@@@'+author+'@@@@'+qstype+'@@@@'+qdesc+'@@@@'+qltype+'@@@@'+qlcode+'@@@@'+qlanswer+'@@@@'+qlchoose+'@@@@'+ctfurl+'@@@@'+queslink+'@@@@'+dataurl+'@@@@'+dataname;   
        }
    else{
        var tt =code+'@@@@'+author+'@@@@'+qstype+'@@@@'+qdesc+'@@@@'+qltype+'@@@@'+qlcode+'@@@@'+qlanswer+'@@@@'+qlchoose+'@@@@'+ctfurl+'@@@@'+queslink;   
        }
    

    //alert(tt);die;
    if ($(ppo).is(':checked')){
        if(jQuery.inArray(code,totalcheck) == -1){
            //对比在弹窗添加的题目信息，去除与页面相同的信息题目
            totalcheck.push(code);
            quescontents.push(tt);
            
        }

        $('#selexam .titleLook span').html(totalcheck.length);
        
    }else{
        quescontents.splice($.inArray(tt,quescontents),1)

        $.each(totalcheck,function(n,m){
            if(m == code){
                totalcheck.splice($.inArray(code,totalcheck),1);
            }
        })
        $('#selexam .titleLook span').html(totalcheck.length);

    }
}

//勾选题目保存之后将题目信息拼接到选题的弹框(理论题)
//想页面添加弹窗中被选中的题目信息
function html_encode(s)
{
    var s = "";
    if (s.length == 0) return "";
    s = s.replace(/&/g, "&gt;");
    s = s.replace(/</g, "&lt;");
    s = s.replace(/>/g, "&gt;");
    s = s.replace(/ /g, "&nbsp;");
    s = s.replace(/\'/g, "&#39;");
    s = s.replace(/\"/g, "&quot;");
    s = s.replace(/\n/g, "<br>");
    return s;

}

function addQuesList(arr,tarr){
    var questionType = ["","单选题","多选题","判断题","填空题","夺旗题"];
    var count = 1;
    $("#queBox").children().remove();
    console.log(arr);
    for(var i in arr)
    {   
       
        //生成单一题目的主容器
                    $("#queBox").append("<div class='questionitem' id='question_"+arr[i].QuestionCode+"' code ="+arr[i].QuestionCode+" quesType ="+arr[i].QuestionType+" qltype = "+arr[i].QuestionLinkType+" ></div>");

                    var thisDiv = $("#question_"+arr[i].QuestionCode);

                    var strinput = "";
                    var strdelbtn = "";
                    var dataGo = ""; //存资料链接和名字
                    var nameArr = new Array();
                         nameArr=arr[i].dataname.split("," );
                    var urlArr = new Array();
                         urlArr=arr[i].dataurl.split("," );
                    var urlType = [".png",".jpg",".gif","jpeg"];
                    if(parseInt(nameArr[0])!=0){

                        for(ss=0;ss<nameArr.length;ss++){
                            var urll = urlArr[ss];
                            var ifurl = urll.substr(urll.length-4)
                            if(jQuery.inArray(ifurl,urlType) != -1){
                                 dataGo += '<a href="'+base_url+urlArr[ss].substr(6)+'" target="_blank">'+nameArr[ss]+'</a>';

                                } else{
                            dataGo += '<a href="'+base_url+urlArr[ss].substr(6)+'" >'+nameArr[ss]+'</a>';

                                 }
                                //dataGo += '<a href="'+base_url+urlArr[ss].substr(6)+'" target="_blank">'+nameArr[ss]+'</a>';
                              
                        }
                        dataGo="<p style='padding-top:15px;float:none;clear:both'>附件："+dataGo+"</p>"

                    }
                    strinput = '<span class="quesClasses">'+questionType[arr[i].QuestionType]+'</span> '+ " 本题：<input maxlength=3 questioncode='"+arr[i].QuestionCode+"' type='text' class='score' id='score_"+arr[i].QuestionCode+"' value='"+arr[i].QuestionScore+"'/> 分";
                    strdelbtn = '<a questioncode="'+arr[i].QuestionCode+'" href="javascript:;" class="delquestion forRed delate" onclick ="delques(this)" '+tarr[i]+'><i class="fa fa-trash-o"></i>删除</a>';
                    thisDiv.append("<p class='questiontitle'><span class='quesindex'>"+count+"</span>. "+strinput+"&nbsp;&nbsp;&nbsp;&nbsp;"+strdelbtn+"</p>");
                    thisDiv.append("<div class='questiondesc'>"+marked(arr[i].QuestionDesc)+dataGo+"</div>");

                    if(arr[i].QuestionLinkType == 1){   //连接一个CTF场景
                        thisDiv.append("<div class='questionnet'>CTF题目地址：<a target='_blank' href='"+arr[i].CtfUrl+"'>"+arr[i].QuestionLink+"</a></div>");
                    }

                    if(arr[i].QuestionLinkType == 2){ //连接一个实操场景
                        thisDiv.append("<div class='questionnet'>使用场景："+arr[i].QuestionLink+"</div>");
                    }
                    thisDiv.append("<ul class='anser_"+arr[i].QuestionCode+"'></ul>");
                    var thisUl = $('.anser_'+arr[i].QuestionCode)

                    if((arr[i].QuestionType==1)||(arr[i].QuestionType==2)||(arr[i].QuestionType==3))
                    {
                        var answers = arr[i].QuestionChooseList;
                        // console.log(answers);

                        var index = 0;

                        for(var j in answers)
                        {
                            var chooseclass = "";
                            if(answers[j].ChooseIsAnswer)
                            {
                                chooseclass = " <span class='Qright'>正确答案</span>";
                            }
                            thisUl.append("<li >"+String.fromCharCode(65+index)+". "+answers[j].ChooseContent+" "+chooseclass+"</li>");
                            index ++;
                        }
                    }
                    else
                    {
                        var answers = arr[i].QuestionChooseList;
                        var index = 0;
                        for(var j in answers)
                        {
                            var chooseclass = "";
                            if(answers[j].ChooseIsAnswer)
                            {
                                chooseclass = " <span class='Qright'>正确答案</span>";
                            }
                            thisUl.append("<li >"+answers[j].ChooseContent+""+chooseclass+"</li>");
                            index ++;
                        }
                    }
                    count ++;
                }
            
 
}

function okchecked(isid,scoretype){//编辑试卷时候
        fnHide("#selexam","fadeInDown","fadeOutUp");

    var quescktxt = '';
    var examQuestionList = new Array();
    var foraaArry = new Array();
    console.log(quescontents);
    $.each(quescontents,function(i,n){
        var arr = n.split('@@@@');

        var qlCode = 0;
  
        if (arr[5] != 0&&arr[5] != 'null') {
            qlCode = arr[5];//题目分数
        }
       
        var ttst = 'qid="'+arr[0]+'" qauthor="'+arr[1]+'" qstype="'+arr[2]+'" qdesc="'+arr[3]+'" qltype="'+arr[4]+'" qlcode="'+arr[5]+'" qlanswer="'+arr[6]+'" qlchoose="'+arr[7]+'" queslink="'+ arr[9] +'" ctfurl="'+arr[8]+'" dataurl="'+arr[10]+'" dataname="'+arr[11]+'"' 
        foraaArry.push(ttst);

        var questionAnswerArray = new Array();
        switch (arr[2]) {
            case "1":
            case "2":
                var questionAnswerList = arr[7].split('|||');
                var questionAnswers = arr[6].split('|||');
                for (var j=0;j<questionAnswerList.length;j++) {
                    var answer = false;
                    if ($.inArray(questionAnswerList[j], questionAnswers) != -1) {
                        answer = true;
                    }
                    var questionAnswerItem = {
                        "ChooseContent":questionAnswerList[j],
                        "ChooseIsAnswer":answer
                    }
                    questionAnswerArray.push(questionAnswerItem);
                }
                break;
            case "3":
                var questionAnswerItem1 = {
                    "ChooseContent":"对",
                    "ChooseIsAnswer":("对"==arr[6])
                }
                var questionAnswerItem2 = {
                    "ChooseContent":"错",
                    "ChooseIsAnswer":("错"==arr[6])
                }
                questionAnswerArray.push(questionAnswerItem1);
                questionAnswerArray.push(questionAnswerItem2);
                break;
            case "4":
                var questionAnswerList = arr[6].split('|||');
                for (var j=0;j<questionAnswerList.length;j++) {
                    var answer = true;
                    var questionAnswerItem = {
                        "ChooseContent":questionAnswerList[j],
                        "ChooseIsAnswer":answer
                    }
                    questionAnswerArray.push(questionAnswerItem);
                }
                break;
            case "5":
                var questionAnswerItem = {
                    "ChooseContent":arr[6],
                    "ChooseIsAnswer":true
                }
                questionAnswerArray.push(questionAnswerItem);
                break;
        }

        var questionItem = {
            "QuestionCode":arr[0],
            "QuestionDesc":arr[3],
            "QuestionType":arr[2],
            "QuestionChooseList":questionAnswerArray,
            "QuestionLinkType":arr[4],
            "QuestionLink":arr[9],
            "QuestionScore":qlCode,
            "QuestionAuthor":arr[1],
            "QuestionIndex":1,
            'CtfUrl':arr[8],
            'dataurl':arr[10],
            'dataname':arr[11],
        }
        //console.log(questionItem);return;

        examQuestionList.push(questionItem);
        console.log(questionItem)

    });

    //防止页面出现重复的题目

    var jsonSrt = JSON.stringify(examQuestionList);

    addQuesList(examQuestionList,foraaArry);
    imgLian()
}



//加载弹窗中表的数据
function sapSuc(data) {
    if(data==''){
        $(".nostudentList").show();
    }
    else{
        $(".nostudentList").hide();
    }
    var questxt = '';
    $.each(data,function(i,v){
        var ResourceName = v['ResourceName']
        if(!ResourceName){
            ResourceName=0
        }
        v['QuestionDesc'] = encodeURI(v['QuestionDesc']);//如果不转码 ，当描述中含有双引号等特殊字符时，参数被截断，
        questxt += '<tr>';
        questxt += '<td><input class="quescode" type="checkbox" onclick=checkeds(this) name="quescode[]" desc="'+v['QuestionDesc']+'" qstype="'+v['QuestionType']+'" author="'+v['QuestionAuthor']+'" value="'+v['QuestionID']+'" qltype="'+v['QuestionLinkType']+'" qlcode="0" qlanswer="'+v['QuestionAnswer']+'" qlchoose="'+v['QuestionChoose']+'" ctfurl="'+v['CtfUrl']+'" queslink="'+v['QuestionLink']+'" dataurl="'+v['ResourceUrl']+'" dataname="'+ResourceName+'"></td>';

        questxt += '<td title="'+decodeURI(v['QuestionDesc'])+'" class="topicDesc">'+decodeURI(v['QuestionDesc'])+'</td>';
        questxt += '<td>'+v['QuestionAuthor']+'</td>';
        if(v['QuestionType'] == 1){v['QuestionType']='单选题'}else
        if(v['QuestionType'] == 2){v['QuestionType']='多选题'}else
        if(v['QuestionType'] == 3){v['QuestionType']='判断题'}else
        if(v['QuestionType'] == 4){v['QuestionType']='填空题'}else
        if(v['QuestionType'] == 5){v['QuestionType']='夺旗题'}
        questxt += '<td>'+v['QuestionType']+'</td>';
        questxt += '</tr>';
    });
    $('#quesList').html('');
    $('#quesList').append(questxt);

    $('#quesList input[class=quescode]').each(function(){
        if(jQuery.inArray($(this).val(),totalcheck) != -1){
            $(this).prop('checked',true);
        }
    })
}

function searchForTime(){
    if($("#stime").val() != "" && $("#etime").val() != ""){
        if($("#stime").val() >= $("#etime").val()){
            $("#edOk p.promptNews").html('开始时间不能大于等于结束时间');
            fnShow("#edOk","fadeOutUp","fadeInDown");
            setTimeout(function(){
                window.location.reload();
            },2000)
        } else {
            var str = '';
            if (search != '')str += '/search/'+encodeURI(translate(search));
            window.location.href =  site_url+'Subject/myexam' + str +'/time/'+ $("#stime").val() + "_" + $("#etime").val()
        }
    }

}

//点确定选择题目
function qokchecked(isid,scoretype){
    fnHide("#selexam","fadeInDown","fadeOutUp");
    var quescktxt = '';
    $.each(quescontents,function(i,n){
        var arr = n.split('@@@@');
        //console.log(arr);
        var tpp='';
        var changjing = '';
        var tvalue = '';
        var cvalue = '';
        quescktxt += '<tr>';
        quescktxt += '<td>'+decodeURI(arr[3])+'</td>';
        if(arr[2] == 1){tpp='单选题'; tvalue=1}else
        if(arr[2] == 2){tpp='多选题'; tvalue=2}else
        if(arr[2] == 3){tpp='判断题'; tvalue=4}else
        if(arr[2] == 4){tpp='填空题'; tvalue=8}else
        if(arr[2] == 5){tpp='夺旗题'; tvalue=16}
        quescktxt += '<td>'+tpp+'</td>';
        quescktxt += '<td>'+arr[1]+'</td>';
        var score =''
        if(arr[5]!=0&&arr[5]!="null"){
            score = arr[5]
        }
        quescktxt += '<td><input type="text" value="'+score+'" id="score_'+arr[0]+'"  class="score" placeholder="请输入分数"/ onchange ="tongji()"></td>';
        if(arr[4]==0){changjing='无'; cvalue=0}
        if(arr[4]==1){changjing='CTF'; cvalue=0}
        if(arr[4]==2){changjing='场景'; cvalue=32}
        quescktxt += '<td>'+changjing+'</td>';

        quescktxt += '<td><a cvalue="'+cvalue+'" tvalue="'+tvalue+'" qid="'+arr[0]+'" qauthor="'+arr[1]+'" qstype="'+arr[2]+'" qdesc="'+arr[3]+'" qltype="'+arr[4]+'" qlcode="'+arr[5]+'" qlanswer="'+arr[6]+'" qlchoose="'+arr[7]+'" ctfurl="'+arr[8]+'" queslink="'+arr[9]+'"  href="javascript:;" class="btn delate" onclick=delques(this)><i class="fa fa-trash"></i>删除</a></td>';
        quescktxt += '</tr>';
    });

    $(isid).html(quescktxt);
    
    //题目选择完成后，触发显示一键均分的按钮
    oneKeyAvgIsView();
}

//新增试卷，由于存在用户输入，会导致quescontents数组信息不是最新的，删除的时候导致一直从最后一项开始删除
function tongji(){
    var childLength = $("#queBox").children().length;
    quescontents =[];//清空数组防止分数加入冲突
    for(i=0;i<childLength;i++){
        var qid = $("#queBox").children().eq(i).find(".delate").attr('qid');
        var qauthor = $("#queBox").children().eq(i).find(".delate").attr('qauthor');
        var qstype = $("#queBox").children().eq(i).find(".delate").attr('qstype');
        var qdesc = $("#queBox").children().eq(i).find(".delate").attr('qdesc');
        var qtype = $("#queBox").children().eq(i).find(".delate").attr('qltype');
        var qlcode = $("#queBox").children().eq(i).find(".score").val();//分数

        var ctfurl = $("#queBox").children().eq(i).find(".delate").attr('ctfurl')//ctf链接
        var queslink = $("#queBox").children().eq(i).find(".delate").attr('queslink')//ctf名字或者场景名字
        if(qlcode==''){
            qlcode ="null"
        }
        var qlanswer = $("#queBox").children().eq(i).find(".delate").attr('qlanswer');
        var qlchoose = $("#queBox").children().eq(i).find(".delate").attr('qlchoose');
        var dataurl = $("#queBox").children().eq(i).find(".delate").attr('dataurl');
        var dataname = $("#queBox").children().eq(i).find(".delate").attr('dataname');
        if($("#queBox").attr("urlif")){//编辑试卷时候
        var tt =qid+'@@@@'+qauthor+'@@@@'+qstype+'@@@@'+qdesc+'@@@@'+qtype+'@@@@'+qlcode+'@@@@'+qlanswer+'@@@@'+qlchoose+'@@@@'+ctfurl+'@@@@'+queslink+'@@@@'+dataurl+'@@@@'+dataname;   
        }
        else{
        var tt =qid+'@@@@'+qauthor+'@@@@'+qstype+'@@@@'+qdesc+'@@@@'+qtype+'@@@@'+qlcode+'@@@@'+qlanswer+'@@@@'+qlchoose+'@@@@'+ctfurl+'@@@@'+queslink;   
        }
        
        quescontents.push(tt);
    }

}

//点击删除题目操作
function delques(This,ques){
 
    var qid= $(This).attr('qid');
    var qauthor = $(This).attr('qauthor');
    var qstype = $(This).attr('qstype');
    var qdesc = $(This).attr('qdesc');
    var qtype = $(This).attr('qtype');
    var qlcode = $(This).parent().siblings().children(".score").val()
    var qlanswer = $(This).attr('qlanswer');
    var qlchoose = $(This).attr('qlchoose');
    var ctfurl = $(This).attr('ctfurl');
    var queslink = $(This).attr('queslink');
    var dataurl = $("#queBox").children().eq(i).find(".delate").attr('dataurl');
        var dataname = $("#queBox").children().eq(i).find(".delate").attr('dataname');
        if($("#queBox").attr("urlif")){//编辑试卷时候
        var tt =qid+'@@@@'+qauthor+'@@@@'+qstype+'@@@@'+qdesc+'@@@@'+qtype+'@@@@'+qlcode+'@@@@'+qlanswer+'@@@@'+qlchoose+'@@@@'+ctfurl+'@@@@'+queslink+'@@@@'+dataurl+'@@@@'+dataname;   
        }
        else{
        var tt =qid+'@@@@'+qauthor+'@@@@'+qstype+'@@@@'+qdesc+'@@@@'+qtype+'@@@@'+qlcode+'@@@@'+qlanswer+'@@@@'+qlchoose+'@@@@'+ctfurl+'@@@@'+queslink;   
        }
    //var tt =qid+'@@@@'+qauthor+'@@@@'+qstype+'@@@@'+qdesc+'@@@@'+qtype+'@@@@'+qlcode+'@@@@'+qlanswer+'@@@@'+qlchoose
    quescontents.splice($.inArray(tt,quescontents),1);
    totalcheck.splice($.inArray(qid,totalcheck),1);
    $(This).parent().parent().remove();
    var parentss = $(".questionitem").length;
    for(i=0;i<parentss;i++){
        $(".questionitem").eq(i).find(".quesindex").html(i+1)

    }

}

//题目中出现资料的情况，全部转换成链接查看
function imgLian(){
    $(".questiondesc img").each(function(){
        var url = $(this).attr("src").substr(6);
        var name = $(this).attr("alt");
        if(name==""){
            name="资料"
        }
        // $(this).attr("src",base_url+url)
        $(this).replaceWith('<a target="_blank" href="'+base_url+url+'">'+name+'</a>')
        
    })
}
//题目带有附件列表

function dataGo(){
    $(".questiontitle .delate").each(function(){
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
            dataGostr="<p style='padding-top:15px;float:none;clear:both'>附件："+dataGostr+"</p>"
            $(this).parent().siblings(".questiondesc").append(dataGostr)
        }

    })

}

$(document).ready(function(){
    imgLian();
    dataGo();
    
    //调用方法，判断一键均分按钮是否显示
    oneKeyAvgIsView();
})

/**
 * 一键均分按钮是否显示
 */
function oneKeyAvgIsView(){
	var childLength = $("#queBox").children().length;
	if(childLength>0){
		$('#onekeyaverage').show();
	}else{
		$('#onekeyaverage').hide();
	}
}

function clearTime() {
    if ($("#stime").val() == "" && $("#etime").val() == "") {
        var str = '';
        if (search != '')str += '/search/' + encodeURI(translate(search));
        window.location.href = site_url + 'Subject/myexam' + str;
    }
}