<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>教学任务管理-考试任务统计详情</title>
    <!--公用样式-->
    <link rel="shortcut icon" href="<?php echo base_url() ?>resources/imgs/public/title.ico">
    <link href="<?php echo base_url() ?>resources/css/public/reset.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>resources/css/public/animate.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/thirdparty/font-awesome-4.5.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>resources/css/public/firstStart.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/filter.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>resources/css/public/content.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>resources/css/teacher/study_exam.css" rel="stylesheet" type="text/css" />
    <!--第三方样式-->

    <!--header框架js-->
    <script src="<?php echo base_url() ?>resources/js/public/jquery-1.11.0.js"></script>
    <script src="<?php echo base_url() ?>resources/js/public/template.js"></script>
    <script src="<?php echo base_url() ?>resources/js/public/page.js"></script>
    <script src="<?php echo base_url() ?>resources/thirdparty/highcharts/js/highcharts.js" type="text/javascript"></script>
    <script src="<?php echo base_url() ?>resources/js/teacher/exam_detail.js" type="text/javascript"></script>
</head>
<script>
    var site_url = "<?php echo site_url();?>";
    var taskcode = "<?php echo $task['TaskCode'];?>";
    var scenetaskid = "<?php echo $task['SceneTaskID'];?>";
</script>
<body>
<!--公用header框架开始-->
<?php $this->load->view('public/header.php')?>
<!--公用header框架结束-->
<div class="frame">
    <div class="main clearfix">
        <!--公用menu框架开始-->
        <?php $this->load->view('public/left.php')?>
        <!--公用menu框架结束-->
        <!--公用centent框架开始-->
        <div class="content">
            <div class="lable_title">
                <a href="<?php echo site_url().'Education/edubook'?>" title="教学任务管理" class="for_lable">教学任务管理</a>&gt;
                <a href="<?php echo site_url().'Education/examtask'?>" title="已下发的考试任务" class="for_lable">已下发的考试任务</a>&gt;
                <a>统计详情</a>
            </div>
            <div class="infoTask clearfix">
                <div class="infotaskImg" id="examsNews">

                </div>
                <div class="infotaskPoint exInfoPoint">
                    <h3 class="infoTitle">
                        <a href="<?php echo site_url().'Education/examquestion?examid='.$task['ExamID'];?>" target="_blank"><?php echo $task['TaskName'];?></a>
                    </h3>
                    <p>开始时间：<span><?php echo date('Y-m-d H:i:s',$task['TaskStartTime']);?></span></p>
                    <p>结束时间：<span><?php echo date('Y-m-d H:i:s',$task['TaskEndTime']);?></span></p>
                    <p>下发老师：<span><?php echo $task['UserName'];?></span></p>
                    <p>任务类型：<span><?php if($task['TaskTargetType'] == 1){
                                echo '学员任务';
                            }else if($task['TaskTargetType'] == 2){
                                echo '班级任务';
                            }else{
                                echo '混合任务';
                            }?></span></p>
                    <p>任务描述：<span title="<?php echo $task['TaskDesc'];?>"><?php
                            if (mb_strlen($task['TaskDesc']) > 39 ){
                                echo mb_substr($task['TaskDesc'], 0, 39, 'UTF-8')."...";
                            }else{
                                echo $task['TaskDesc'];
                            }
                            ?></span></p>
                    <div class="taskbtn btnBox" id="taskbtn">
                        <?php if($task['TaskTypeJudge'] == 2){?>
                            <a class="noCanBg">已结束</a>
                        <?php }else{ ?>
                            <a class="publicNo" id="endExam" href="javascript:;">结束任务</a>
                        <?php }?>
                        <a class="publicOk" id="delExam" href="javascript:;">删除任务</a>
                    </div>
                </div>
            </div>
            <?php if($total > 0){ ?>
            <div class="toolTask clearfix">
                <p class="changetop"><a id="tableMore2" name="">排名详情</a>||<a id="topMore" name="">实时排名top10</a></p>
            </div>
            <table class="studytaskList colorChange" id="studyTable">
                <thead>
                <tr class="table-title">
                    <td width="180">姓名</td>
                    <td>进度</td>
                    <td>交卷时间</td>
                    <td width="180">分数</td>
                </tr>
                </thead>
                <tbody id="tableList">
                <?php
                $type = array('未考试','考试中','已交卷');
                foreach ($student as $val){ ?>
                <tr>
                    <td title="">
                        <a class="studentExam" taskid="<?php echo $val['TaskID'];?>" tasktype="<?php echo $val['TaskType'];?>"><?php echo $val['UserName'];?></a>
                    </td>
                    <td>
                        <div>
                            <p><?php echo $type[$val['TaskType']];?></p>
                        </div>
                    </td>
                    <td>
                    <?php 
                    $finishTime='';
                    if($val['TaskType']==2){//结束考试才显示
	                    if(empty($val['TaskFinishedTime'])){
							$finishTime=date('Y-m-d H:i:s',$val['TaskEndTime']);
						}else{
							$finishTime=date('Y-m-d H:i:s',$val['TaskFinishedTime']);
						}
					}
                    ?>
                    <?php echo $finishTime;?>
                    </td>
                    <td><?php echo $val['TaskScore'];?>分</td>
                </tr>
                <?php } ?>
                </tbody>
            </table>
            <!--top10-->
            <div id="topTen" class="outHide">
                <?php foreach($top_student as $val){ ?>
                <div class="rankdiv">
                    <div id="" class="showdiv">
                        <div class="desc">
                            <span><?php echo $val['UserName'];?></span>
                        </div>
                        <div class="progress clearfix">
                            <div class="wholeBg">
                                <div class="progress-bar" role="progressbar" style="width:<?php echo $val['TaskScore'];?>%;">
                                </div>
                            </div>
                            <div class="percent"><?php echo $val['TaskScore']; ?>分/共100分</div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>

                <div id="selfPage" class="page">
                    <script>
                        var pageurl = '<?=$page_url?>';
                        var pagepre = parseInt('<?=$page_pre?>');
                        var pagecount  = parseInt('<?=$page_count?>');
                        var numsize = 10;
                        pagetext = page(pagepre,pagecount,pageurl,numsize);
                        document.write(pagetext);
                    </script>
                </div>
            <?php } ?>

        </div>

        <!--公用centent框架结束-->
    </div>

    <!--公用fotter框架开始-->
    <?php $this->load->view('public/footer.php')?>
    <!--公用fotter框架结束-->
</div>

<!-- 提示 -->
<div class="maskbox"></div>
<div class="popUpset animated " id="delBox">
    <form action="" method="post">
        <div class="popTitle">
            <p>确认操作</p>
            <a href="javascript:;" class="close close-1"></a>
        </div>
        <div class="infoBox">
            <p class="promptNews">确定要删除该考试任务吗?</p>
            <div class="btnBox">
                <a href="javascript:;" class="publicOk " id="delBtn">确定</a>
                <a href="javascript:;" class="publicNo  hidePop-1">取消</a>
            </div>
        </div>
    </form>
</div>
<div class="popUpset animated " id="endBox">
    <form action="" method="post">
        <div class="popTitle">
            <p>确认操作</p>
            <a href="javascript:;" class="close close-1"></a>
        </div>
        <div class="infoBox">
            <p class="promptNews">确定要结束该考试任务吗?</p>
            <div class="btnBox">
                <a href="javascript:;" class="publicOk " id="endBtn">确定</a>
                <a href="javascript:;" class="publicNo  hidePop-1">取消</a>
            </div>
        </div>
    </form>
</div>
<!-- 提示信息 -->
<div class="popUpset animated " id="okBox" >
    <form action="" method="post">
        <div class="popTitle">
            <p>提示操作</p>
            <a href="javascript:;" class="close close-1"></a>
        </div>
        <div class="infoBox">
            <p class="promptNews promptUp"></p>
        </div>
    </form>
</div>

</body>
</html>
<script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/prompt.js"></script>
