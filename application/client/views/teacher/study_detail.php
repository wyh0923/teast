<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>教学任务管理-学习任务统计详情</title>
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
    <script src="<?php echo base_url() ?>resources/js/public/page.js"></script>
    <script src="<?php echo base_url() ?>resources/js/public/template.js"></script>
    <script src="<?php echo base_url() ?>resources/js/teacher/study_detail.js"></script>

</head>
<script>
    var site_url = "<?php echo site_url();?>";
    var taskcode = "<?php echo $task['TaskCode'];?>";
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
                <a href="<?php echo site_url().'Education/studylist'?>" title="已下发的学习任务" class="for_lable">已下发的学习任务</a>&gt;
                <a>统计详情</a>
            </div>
            <div class="infoTask clearfix">
                <div class="infotaskImg">
                    <?php $img = $task['PackageImg'] ? $task['PackageImg']:'logo.png';?>
                    <img src="<?php echo base_url() . 'resources/files/img/course/'.$img;?>" alt="<?php echo $task['TaskName'];?>" onerror="javascript:this.src='<?php echo base_url() ?>resources/files/img/course/logo.png'">
                </div>
                <div class="infotaskPoint">
                    <h3 class="infoTitle">
                        <a href="<?php echo site_url().'Education/bookdetail?packageid='.$task['PackageID'];?>"><?php echo $task['TaskName'];?></a>
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
                    <div class="taskbtn btnBox" id="taskbtn">
                        <?php if($task['TaskTypeJudge'] == 2){?>
                            <a class="noCanBg">已结束</a>
                        <?php }else{ ?>
                            <a class="publicNo" id="endStudy" href="javascript:;">结束任务</a>
                        <?php }?>
                        <a class="publicOk" id="delStudy" href="javascript:;">删除任务</a>
                    </div>
                </div>
            </div>
            <?php if($total > 0){ ?>
            <div class="toolTask clearfix">
                <p>学员学习情况</p>
            </div>
            <table class="studytaskList colorChange">
                <thead>
                <tr class="table-title">
                    <td width="120"> 学员姓名 </td>
                    <td width="120">已完成小节</td>
                    <td width="120">未完成小节</td>
                    <td width="120">总共小节</td>
                    <td width="220"><a href="<?php echo $Sort["TaskProcess"]["url"]; ?>">任务进度<i class="<?php echo $Sort["TaskProcess"]["icon"]; ?>"></i></a></td>
                    <td width=""><a href="<?php echo $Sort["TaskScore"]["url"]; ?>">积分<i class="<?php echo $Sort["TaskScore"]["icon"]; ?>"></i></a></td>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($student as $val){ ?>
                <tr>
                    <td title="testing4"><?php echo $val['UserName'];?></td>
                    <td><?php echo $val['finished'];?></td>
                    <td><?php echo $val['underway'];?></td>
                    <td><?php echo $val['allsection'];?></td>
                    <td>
                        <div class="prBox">
                            <div class="pro" style="width:<?php echo $val['TaskProcess'];?>%;"></div>
                            <p><?php echo $val['TaskProcess'];?>%</p>
                        </div>
                    </td>
                    <td><?php echo $val['TaskScore'];?>分</td>
                </tr>
                <?php } ?>
                </tbody>
            </table>
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
            <p class="promptNews">确定要删除该学习任务吗?</p>
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
            <p class="promptNews">确定要结束该学习任务吗?</p>
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
