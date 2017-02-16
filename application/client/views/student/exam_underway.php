<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>我的考试-正在进行的考试</title>
    <!--公用样式-->
    <link rel="shortcut icon" href="<?php echo base_url() ?>resources/imgs/public/title.ico">
    <link href="<?php echo base_url() ?>resources/css/public/reset.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>resources/css/public/animate.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/thirdparty/font-awesome-4.5.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>resources/css/public/firstStart.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/filter.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>resources/css/public/content.css" rel="stylesheet" type="text/css" />
    <!--第三方样式-->

    <!--header框架js-->
    <script src="<?php echo base_url() ?>resources/js/public/jquery-1.11.0.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/page.js"></script>
    <script src="<?php echo base_url() ?>resources/js/public/template.js"></script>
</head>
<script type="text/javascript">
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
            <div class="total clearfix">
                <h3 id="totalNum">共计：<?php echo $total;?>套</h3>
                <div class="search-a">
                    <input id="stuName" type="text" class="iptSearch-a" value="<?php echo (isset($search) ? $search : "") ?>" placeholder="请输入考试名称">
                    <i class="fa fa-search "></i>
                </div>
            </div>

            <table id="examlist" class="<?php if($total <= 0){ echo 'outHide';}?>">
                <thead>
                <tr class="table-title">
                    <td width="120">考试名称</td>
                    <td width="100"><a href="<?php echo $Sort["TaskTime"]["url"]; ?>">考试时长 <i class="<?php echo $Sort["TaskTime"]["icon"]; ?>"></i></a></td>
                    <td width="160"><a href="<?php echo $Sort["ExamType"]["url"]; ?>">考卷类型<i class="<?php echo $Sort["ExamType"]["icon"]; ?>"></i></a></td>
                    <td width="100"><a href="<?php echo $Sort["UserName"]["url"]; ?>">下发老师<i class="<?php echo $Sort["UserName"]["icon"]; ?>"></i></a></td>
                    <td width="160"><a href="<?php echo $Sort["Stime"]["url"]; ?>">距离考试开始时间<i class="<?php echo $Sort["Stime"]["icon"]; ?>"></i></a></td>
                    <td>操作</td>
                </tr>
                </thead>
                <tbody id="examinfo">
                <?php
                    foreach ($data as $val){
                ?>
                <tr>
                    <td><a class="operater forRed" onclick="gotoExam(this)" taskid="<?php echo $val['TaskID'];?>" title="<?php echo $val['TaskName'];?>"><?php echo $val['TaskName'];?></a></td>
                    <?php
                    $taskTime = '';
                    if ($val['TaskTime'] < 60) {
                        $taskTime = $val['TaskTime'] . '秒';
                    } else if ($val['TaskTime'] < 3600) {

                        $minute = intval(floor($val['TaskTime'] % 60)) ? intval(floor($val['TaskTime'] % 60)). '秒' : '';//三目运算符
                        $taskTime = intval(floor($val['TaskTime'] / 60)) . '分钟' .$minute;

                    } else if ($val['TaskTime'] < 86400) {
                        $hour = intval(floor($val['TaskTime'] % 3600/60)) ? intval(floor($val['TaskTime'] % 3600/60)) . '分钟' : '' ;//三目运算符
                        $taskTime = intval(floor($val['TaskTime'] / 3600)) . '小时' . $hour;

                    } else if ($val['TaskTime'] < 86400*30) {

                        $day = intval(floor($val['TaskTime'] % 86400/3600)) ? intval(floor($val['TaskTime'] % 86400/3600)). '小时':'';//三目运算符
                        $taskTime = intval(floor($val['TaskTime'] / 86400)) . '天'. $day;

                    } else{
                        $month = intval(floor($val['TaskTime'] % (86400*30)/86400)) ? intval(floor($val['TaskTime'] % (86400*30)/86400)) . '天' : '';//三目运算符
                        $taskTime = intval(floor($val['TaskTime'] / (86400*30))) . '月'. $month;
                    }
                    ?>
                    <td title="<?php echo $taskTime;?>"><?php echo $taskTime;?></td>
                    <?php
                    $str = '';
                    if($val['ExamType']&1){
                        $str.="单选题 ";
                    }
                    if($val['ExamType']&2){
                        $str.="多选 ";
                    }
                    if($val['ExamType']&4){
                        $str.="判断 ";
                    }
                    if($val['ExamType']&8){
                        $str.="填空 ";
                    }
                    if($val['ExamType']&16){
                        $str.="夺旗题 ";
                    }
                    if($val['ExamType']&32){
                        $str.="场景题 ";
                    }
                    ?>
                    <td title="<?php echo $str;?>"><?php echo $str;?></td>
                    <td><?php echo $val['UserName'];?></td>
                    <td><?php
                        if($val['Stime'] < 0 && $val['Etime'] > 0){
                            echo "已开始";
                        }else if($val['Etime'] < 0){
                            echo "已结束";
                        }else{
                            $hour = 60*60;
                            if($val['Stime'] >= $hour){
                                echo floor($val['Stime']/$hour).'小时'.floor($val['Stime']%($hour)/60).'分钟';
                            }else if($val['Stime']<60){
                                echo floor($val['Stime']%60).'秒 后开始';
                            }else{
                                echo floor($val['Stime']/60).'分钟 后开始';
                            }
                        }
                        ?> </td>
                    <td>
                        <a class=" forOrange" onclick="gotoExam(this)" taskid="<?php echo $val['TaskID'];?>"><i class="fa fa-play-circle-o bgGreen"></i>开始考试</a>
                        <a class=" forRed" onclick="examBox(this)" scenetaskid="<?php echo $val['SceneTaskID'];?>" taskid="<?php echo $val['TaskID'];?>" taskcode="<?php echo $val['TaskCode'];?>"><i class="fa fa-stop-circle-o" aria-hidden="true"></i>结束考试</a>
                    </td>
                </tr>
                <?php
                    }
                ?>
                </tbody>
            </table>
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
            <?php }else{ ?>
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
<div class="popUpset animated " id="examBox">
    <form action="" method="post">
        <div class="popTitle">
            <p>提示操作</p>
            <a href="javascript:;" id="" class="close close-1"></a>
        </div>
        <input type="hidden" name="taskid" id="taskid"/>
        <div class="infoBox">
            <p class="promptNews">确定要结束该考试吗?</p>
            <div class="btnBox">
                <a href="javascript:;" class="publicOk " id="endExam">确定</a>
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
<script src="<?php echo base_url() ?>resources/js/student/exam_underway.js"></script>