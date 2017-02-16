<!DOCTYPE html>
<html>
<head>
	<title>编辑试卷</title>

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
<script type="text/javascript" src="<?php echo base_url() ?>resources/js/teacher/exam.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>resources/thirdparty/self-ajax-pagination/js/self-ajax-pagination.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>resources/thirdparty/marked/marked.min2.js"></script>
<link href="<?php echo base_url() ?>resources/thirdparty/self-ajax-pagination/css/self-ajax-pagination.css" type="text/css" rel="stylesheet"/>

<script type="text/javascript">
    var site_url = '<?php echo site_url();?>';
    var base_url = '<?php echo base_url();?>';
    var time = '';
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
                      <a href="<?php echo site_url('Subject/myexam')?>" title="知识体系管理" class="for_lable">知识体系管理</a>&gt;
                      <a href="<?php echo site_url('Subject/myexam')?>" title="我的试卷" class="for_lable">我的试卷</a>&gt;
                      <a>编辑试卷</a>
                 </div>  
            <!--面包屑导航 end-->
                <form action="" method="post">
                <!-- <h3 class="addTitle">新增试卷</h2> -->
                <h3 class="lable_h3">编辑试卷</h3>
                <div class="addPaperBox addCourseInner">
                    <div class="addItem">
                        <span class="addTit">试卷名称：</span>
                        <input name="examname" value="<?php echo $ExamName?>" class="addIpt" type="text">
                    </div>
                    
                    <div class="addItem" id="truetype">
                        <span class="addTit">难度等级：</span>
                        <label <?php if($ExamDiff == 0):?>class="cur"<?php endif;?> code="0">初级</label>
                        <label <?php if($ExamDiff == 1):?>class="cur"<?php endif;?> code="1">中级</label>
                        <label <?php if($ExamDiff == 2):?>class="cur"<?php endif;?> code="2">高级</label>
                    </div>
                    <!-- <p class="testPaperTitle">试卷内容预览</p> -->
                    <div class="addItem clearfix ">
                        <p class="subjectTitle addTit">题目列表：</p>
                        <a href="javascript:;" class="changeBtn" istype="add" id="addquestion">选择题目</a>
                    </div>
                    <div class="queBox" id="queBox" urlif ='1'>
                        <?php foreach ($exam_question as $kq => $q): ?>
                            <div class="questionitem" code = '<?php echo $q['QuestionID']?>' quesType = "<?php echo $q['QuestionType']?>" qltype="<?php echo $q['QuestionLinkType']?>">
                                <p class="questiontitle">
                                    <span class="quesindex"><?php echo ($kq+1)?>.</span>
                                    <span class="quesClasses">
                                        <?php if($q['QuestionType']==1) echo '单选题'?>
                                        <?php if($q['QuestionType']==2) echo '多选题'?>
                                        <?php if($q['QuestionType']==3) echo '判断题'?>
                                        <?php if($q['QuestionType']==4) echo '填空题'?>
                                        <?php if($q['QuestionType']==5) echo '夺旗题'?>
                                    </span>本题：
                                    <input maxlength="3" type="text" class="score" id="score_<?php echo $q['QuestionID']?>" value="<?php echo $q['Score']?>"> 分
                                    <a qid = "<?php echo $q['QuestionID']?>" qdesc="<?php echo $q['QuestionDesc']?>" qstype="<?php echo $q['QuestionType']?>" qauthor="<?php echo $q['QuestionAuthor']?>" qltype="<?php echo $q['QuestionLinkType']?>"  qlcode="<?php echo $q['Score']?>" qlanswer="<?php echo $q['QuestionAnswer']?>"   qlchoose="<?php echo $q['QuestionChoose']?>" ctfurl="<?php if(isset($q['CtfUrl'])){echo $q['CtfUrl'];} else{echo '';} ?>" queslink ="<?php echo $q['QuestionLink']?>" dataurl="<?php echo json_decode($q['ResourceUrl'])?>" dataname='<?php if(!$q['ResourceName']){echo 0;} else{ echo json_decode($q['ResourceName']); }?>'  href="javascript:;" class="delquestion forRed delate" onclick ='delques(this)'><i class="fa fa-trash-o"></i>删除</a>
                                </p>
                                <div class="questiondesc markdown-body" id="desc_<?php echo $kq?>"></div>
                                    <script>
                                    var ss= $("#desc_<?php echo $kq;?>").siblings().find(".delate").attr("qdesc")
                                    ss=marked(ss);
                                    $("#desc_<?php echo $kq;?>").html(ss);
                                        
                                    </script>
                                <?php if($q['QuestionLinkType']==1): ?>
                                    <div class="questionnet">CTF题目地址：<a target="_blank" href="<?php echo $q['CtfUrl']?>"><?php echo $q['QuestionLink']?></a></div>
                                <?php endif; ?>
                                <?php if($q['QuestionLinkType']==2): ?>
                                    <div class="questionnet">使用场景：<?php echo $q['QuestionLink']?></div>
                                <?php endif; ?>
                                
                                <ul>
                                    <?php $arr = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M','N', 'O', 'P', 'Q','R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
                                    if($q['QuestionType']==1): ?>

                                        <?php $qchoose = explode('|||', $q['QuestionChoose']);
                                        foreach ($qchoose as $kc => $c): ?>
                                            <li><?php echo $arr[$kc]?>. <?php echo $c?>
                                                <?php if($q['QuestionAnswer'] == trim($c)): ?>
                                                    <span class="Qright">正确答案</span>
                                                <?php endif; ?>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php elseif(($q['QuestionType']==2)): ?>

                                        <?php
                                            $qchoose = explode('|||', $q['QuestionChoose']);
                                            $qanswer = explode('|||', $q['QuestionAnswer']);
                                        foreach ($qchoose as $kc => $c): ?>
                                            <li><?php echo $arr[$kc]?>. <?php echo $c?>
                                                <?php if(in_array(trim($c), $qanswer)): ?>
                                                    <span class="Qright">正确答案</span>
                                                <?php endif; ?>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php elseif(($q['QuestionType']==3)): ?>
                                        <li>
                                            A. 对<?php if($q['QuestionAnswer']=='对') :?><span class="Qright">正确答案</span><?php endif; ?></li>
                                            <li>
                                            B. 错<?php if($q['QuestionAnswer']=='错') :?><span class="Qright">正确答案</span><?php endif; ?>
                                        </li>
                                        
                                    <?php else: ?>
                                        <li><?php echo $q['QuestionAnswer']?><span class="Qright">正确答案</span> </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div  id="adderrormsg"></div>
                    <div class="btnBox">
                        <a href="javascript:;" class="publicOk" id="editsave" examid="<?php echo $eid ?>">确定</a>
                        <a href="<?php echo site_url('Subject/myexam')?>" class="publicNo" >取消</a>
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

<!--选择题目-->
<div class="popUpset animated" id="selexam">

        <div class="popTitle">
            <p>选择题目</p>
            <a href="javascript:;" id="" class="close close-3"></a><!--如果只有一层弹窗，调用close-1-->
        </div>
        <div class="infoBox">
            <div class="box-margin-cen popSearch clearfix">
                <h3 class="titleLook">已选：<span>0</span>&nbsp;道</h3>
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
                <table>
                    <thead>
                    <tr class="table-title">
                        <td width="60">选中</td>
                        <td >题目</td>
                        <td width="90">出题人</td>
                        <td  width="80">类型</td>
                    </tr>
                    </thead>
                    <tbody id=""></tbody>
                    <tbody id="quesList">

                    </tbody>
                </table>
            </div>
            <div id="pageContainer" class="page popPage"></div>
            <script type="text/javascript">
                showSelfAjaxPagination('pageContainer', site_url+'Subject/all_question', "sapSuc");
            </script>
            <div class="noNews nostudentList" >
                <i class="fa fa-file-text" aria-hidden="true"></i><span>没有找到数据......</span>
            </div>
            <div class="btnBox popBtn">
                <a href="javascript:;" class="publicOk " id="selOk" onclick="okchecked('#quesList')">确定</a>
                <a href="javascript:;" class="publicNo hidePop-3" id="">取消</a>

            </div>
        </div>

</div>


<!--<div class="popUpset animated " id="newsNo">
    <form action="" method="post">
        <div class="popTitle">
            <p>提示信息</p>
            <a href="javascript:;" id="" class="close close-1"></a>如果是子层弹窗，调用close-2
        </div>
        <div class="infoBox">
            <p class="promptNews promptUp">您选择的题目中有部分题目在本试卷中存在，已将不重复的题目加入试卷中</p>

        </div>
    </form>
</div>-->
<script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/prompt.js"></script>

</body>
</html>