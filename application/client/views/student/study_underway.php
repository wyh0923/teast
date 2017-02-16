<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>我的学习-正在进行的学习</title>
    <!--公用样式-->
    <link rel="shortcut icon" href="<?php echo base_url() ?>resources/imgs/public/title.ico">
    <link href="<?php echo base_url() ?>resources/css/public/reset.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>resources/css/public/animate.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/thirdparty/font-awesome-4.5.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>resources/css/public/firstStart.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/filter.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>resources/css/public/content.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>resources/css/teacher/booklist.css" rel="stylesheet" type="text/css" />
    <!--第三方样式-->
    
    <!--header框架js-->
    <script src="<?php echo base_url() ?>resources/js/public/jquery-1.11.0.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/page.js"></script>
    <script src="<?php echo base_url() ?>resources/js/public/template.js"></script>

</head>
<script>
    var base_url = '<?php echo base_url(); ?>';
    var site_url = '<?php echo site_url(); ?>';
    var search_url = '<?php echo $search_url; ?>';
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

            <!--total开始-->
            <div class="total clearfix">
                <h3 id="totalNum">共计：<?php echo $total;?>套</h3>

                <div class="search-a">
                    <input type="text" class="iptSearch-a" value="<?php echo (isset($search) ? $search : "") ?>" name="Search" placeholder="请输入关键字搜索">
                    <i class="fa fa-search"></i>
                </div>
            </div>

            <!--total结束-->
            <!--TaskList开始-->
            
            <div class="learningTaskList" id="studyinfo">
                <?php
                foreach ($data as $val){
                    $img = $val['PackageImg'] ? $val['PackageImg']:'logo.png';
                ?>
                <div class="tasklist clearfix" data-TaskID="<?php echo $val['TaskID']; ?>">
                    <div class="taskimg">
                        <a title="<?php echo $val['TaskName']?>" href="javascript:;" onclick="gotostudy(this)" taskid="<?php echo $val['TaskID'];?>">
                            <img alt="<?php echo $val['TaskName']?>" src="<?php echo base_url().'resources/files/img/course/'.$img;?>" id="img<?php echo $val['TaskID']; ?>"  onerror="javascript:this.src='<?php echo base_url() ?>resources/files/img/course/logo.png'">
                        </a>
                    </div>
                    <div class="taskinfo">
                        <div class="taskName">
								<span class="TaskName">
									<a class="move" title="<?php echo $val['TaskName']?>" href="javascript:;" onclick="gotostudy(this)" taskid="<?php echo $val['TaskID'];?>" ><?php echo $val['TaskName'];?></a>
								</span>
                        </div>
                        <?php
                        $day =  ($val['TaskEndTime'] - time())/86400;
                        $hour = ($val['TaskEndTime'] - time())/3600;
                        $min = ($val['TaskEndTime'] - time())/60;
                        if($day >= 1){
                            $time = '本任务将于'.floor($day)."天后结束";
                        }else if($hour >= 1){
                            $time = '本任务将于'.floor($hour)."小时后结束";
                        }else if($min >= 1){
                            $time = '本任务将于'.floor($min)."分钟后结束";
                        }else{
                            $time = '已结束';
                        }
                        ?>
                        <div class="taskinfoabout"> <?php echo $val['UserName']; ?> 于 <?php echo date('Y-m-d',$val['TaskStartTime']);?> 下发  给<?php echo empty($val['ClassID'])? $UserName:' 班级：'.$val['ClassName'] ;?><span class="finshTime"><?php echo $time;?></span> </div>
                        <p title="<?php echo $val['PackageDesc']?>">
                            <?php
                            if (mb_strlen($val['PackageDesc']) > 93 ){
                                echo mb_substr($val['PackageDesc'], 0, 93, 'UTF-8')."...";
                            }else{
                                echo $val['PackageDesc'];
                            }
                            ?></p>
                        <div class="taskmore clearfix">
                                <span class="jiBie">
                                    <i class="fa fa-star" title="课程难度"></i><?php
                                    if($val['PackageDiff']==0){
                                        echo '初级';
                                    }elseif($val['PackageDiff']==1){
                                        echo '中级';
                                    }else{
                                        echo '高级';
                                    }
                                    ?>
                                 </span>
                                <span class="tasktotal">
                                    <i class="fa fa-navicon" title="课程小节总数"></i>共<?php echo $val['SectionNum']?>节
                                </span>
                                <span class="tasktime">
                                    <i class="fa fa-calendar" title="当前任务结束日期"></i><?php echo date('Y-m-d',$val['TaskEndTime']);?>
                                </span>
                            <div class="nums">
                                		<span class="ctaskprogr" >
                                        	<span class="taskpro" style="width:<?php echo $val['TaskProcess'];?>%;"></span>
                                        </span>
                                <span class="percentNum"><?php echo $val['TaskProcess'];?>%</span>

                            </div>

                            <a class="stopBtnDe" href="javascript:;" onclick="studyBox(this)" taskid="<?php echo $val['TaskID'];?>"><i class="fa fa-stop-circle-o"></i>结束学习</a>
                            <a class="btnRelease" href="javascript:;" onclick="gotostudy(this)" taskid="<?php echo $val['TaskID'];?>"><i class="fa fa-bookmark-o"></i>开始学习</a>

                        </div>
                    </div>
                </div>
                <?php } ?>

            </div>
            <?php if($total > 0){ ?>
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
                <!--page结束-->
            <?php } else { ?>
            <!--TaskList结束-->
            <!--page开始-->
            <div class="noNews block">
                <i class="fa fa-file-text" aria-hidden="true"></i><span>没有找到数据......</span>
            </div>
            <?php } ?>
            <div class="noNews outHide" id="noNews">
                <i class="fa fa-file-text" aria-hidden="true"></i><span>没有找到数据......</span>
            </div>

        </div>

        <!--公用centent框架结束-->
    </div>

    <!--公用fotter框架开始-->
    <?php $this->load->view('public/footer.php')?>
    <!--公用fotter框架结束-->
</div>
<!-- 提示信息 -->
<div class="maskbox"></div>
<!--结束提示弹窗-->
<div class="popUpset animated " id="studyBox">
    <form action="" method="post">
        <div class="popTitle">
            <p>提示操作</p>
            <a href="javascript:;" id="" class="close close-1"></a>
        </div>
        <input type="hidden" name="taskid" id="taskid"/>
        <div class="infoBox">
            <p class="promptNews">您确定要结束本课程的学习吗?</p>
            <div class="btnBox">
                <a href="javascript:;" class="publicOk " id="endStudy">确定</a>
                <a href="javascript:;" class="publicNo hidePop-1">取消</a>
            </div>
        </div>
    </form>
</div>
<div class="popUpset animated " id="okBox" >
    <form action="" method="post">
        <div class="popTitle">
            <p>提示操作</p>
            <a href="javascript:;>" class="close close-1"></a>
        </div>
        <div class="infoBox">
            <p class="promptNews promptUp"></p>
        </div>
    </form>
</div>
</body>
</html>
<script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/prompt.js"></script>
<script src="<?php echo base_url() ?>resources/js/student/study_underway.js"></script>