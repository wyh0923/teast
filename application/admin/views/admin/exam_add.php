<!DOCTYPE html>
<html>
<head>
	<title>新增试卷</title>

<meta charset="utf-8">
<link rel="shortcut icon" href="<?php echo base_url() ?>resources/imgs/public/title.ico">
<script type='text/javascript' src="<?php echo base_url() ?>resources/js/public/jquery-1.11.0.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/template.js"></script>
<link href="<?php echo base_url() ?>resources/css/public/reset.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/thirdparty/font-awesome-4.5.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/css/public/animate.min.css" rel="stylesheet" type="text/css">

<link href="<?php echo base_url() ?>resources/css/public/firstStart.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/css/public/content.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/css/public/filter.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/css/admin/addCourse_Exam.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/page.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>resources/js/admin/exam.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>resources/thirdparty/self-ajax-pagination/js/self-ajax-pagination.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>resources/thirdparty/marked/marked.min2.js"></script>
<link href="<?php echo base_url() ?>resources/thirdparty/self-ajax-pagination/css/self-ajax-pagination.css" type="text/css" rel="stylesheet"/>

<script type="text/javascript">
    var site_url = '<?php echo site_url();?>';
</script>


</head>
<body>
<!--header start-->
<?php $this->load->view('public/header.php')?>

<!--header stop-->

<div class="frame">
	<div class="main clearfix">
        <!--leftbar start-->
        <?php $this->load->view('public/left.php')?>
        <!--leftbar stop-->

       <!--right start-->
        <div class="content">
            <!--面包屑导航 start-->
                 <div class="lable_title">
                      <a href="<?php echo site_url('Adminsubject/myexam')?>" title="知识体系管理" class="for_lable">知识体系管理</a>&gt;
                      <a href="<?php echo site_url('Adminsubject/myexam')?>" title="全部试卷" class="for_lable">全部试卷</a>&gt;
                      <a>新增试卷</a>
                 </div>  
            <!--面包屑导航 end-->
                <form action="" method="post">
              
                <h3 class="lable_h3">新增试卷</h3>
                <div class="addPaperBox addCourseInner" style="border:none;">
                    <div class="addItem">
                        <span class="addTit">试卷名称：</span>
                        <input name="examname" id="examname" value="" class="addIpt" type="text">
                    </div>
                    
                    <div class="addItem" id="level" style="width:710px;">
                        <span class="addTit">难度等级：</span>
                        <label class="cur" code="0">初级</label>
                        <label code="1">中级</label>
                        <label code="2">高级</label>
                    </div>
                   
                    <div class="addItem clearfix">
                        <p class="addTit">题目列表：</p>
                        <a href="javascript:;" class="onekeyaverage" id="onekeyaverage">一键均分</a>
                        <a href="javascript:;" class="changeBtn"  id="addquestion">选择题目</a>
                    </div>
                    <table class="testPaperSubjectTable" >
                        <thead>
                            <tr class="table-title">
                                
                                <td width="250">题目</td>
                                <td width="130">类型</td>
                                <td width="100">出题人</td>
                                <td width="100">分数</td>
                                <td width="120">关联类型</td>
                                <td width="">操作</td>
                            </tr>                   
                        </thead>
                        <tbody id="queBox"  class="quesListTable">

                        </tbody>
                    </table>
                    
                    <div id="adderrormsg"></div>
                    <div class="btnBox">
                        <a href="javascript:;" class="publicOk" id="addOk">确定</a>
                        <a href="<?php echo site_url('Adminsubject/myexam')?>" class="publicNo" >取消</a>
                    </div>
                </div>
            </form>
                
        </div>
	<!--right stop-->
	</div>


    <!--center stop-->
    <!--footer start-->
    <?php $this->load->view('public/footer.php')?>

    <!--footer stop-->
</div>
<div class="maskbox"></div>

<!--新加试卷-选择题目-->
<div class="popUpset animated" id="selexam" >
        <div class="popTitle">
            <p>选择题目</p>
            <a href="javascript:;" id="" class="close close-3"></a><!--如果只有一层弹窗，调用close-1-->
        </div>
        <div class="infoBox">
            <div class="box-margin-cen popSearch clearfix">
                <h3 class="titleLook">已选：<span>0</span>道</h3>
                <div class="search_type">
                	<span>题目类型：</span>
                	<select class="sel" name="question_type" id="question_type">
                		<option value="">全部</option>
                		<option value="1">单选题</option>
                		<option value="2">多选题</option>
                		<option value="3">判断题</option>
                		<option value="4">填空题</option>
                		<option value="5">夺旗题</option>
                	</select>
                </div>
                <div class="goSearch">
                    <input type="text" id="sapSearch_pageContainer" class="" value="" name="Search" placeholder="请输入关键字搜索">
                    <i class="fa fa-search clickSear"></i>
                </div>
            </div>
            <div class="box-margin-cen popTable">
                <table class="tdLeft ">
                    <thead>
                    <tr class="table-title">
                        <td width="40">选中</td>
                        <td >题目</td>
                        <td width="90">出题人</td>
                        <td width="70">类型</td>
                    </tr>
                    </thead>
                    <tbody id=""></tbody>
                    <tbody id="quesList">

                    </tbody>
                </table>

            </div>
            <div id="pageContainer" class="page popPage"></div>
            <script type="text/javascript">
                showSelfAjaxPagination('pageContainer', site_url+'/Adminsubject/all_question', "sapSuc");
            </script>
            <div class="noNews nostudentList" >
                <i class="fa fa-file-text" aria-hidden="true"></i><span>没有找到数据......</span>
            </div>
            <div class="btnBox popBtn">
                <a href="javascript:;" class="publicOk " id="selOk" onclick="qokchecked('#queBox')">确定</a>
                <a href="javascript:;" class="publicNo hidePop-3" id="">取消</a>

            </div>
        </div>
</div>


<div class="popUpset animated " id="newsNo">
    <form action="" method="post">
        <div class="popTitle">
            <p>提示信息</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox">
            <p class="promptNews promptUp">您选择的题目中有部分题目在本试卷中存在，已将不重复的题目加入试卷中</p>

        </div>
    </form>
</div>
<script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/prompt.js"></script>


</body>
</html>