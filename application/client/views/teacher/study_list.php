<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>教学任务管理-已下发学习任务</title>
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
    <script src="<?php echo base_url() ?>resources/js/teacher/study_list.js"></script>
</head>
<script>
    var search_url = "<?php echo $search_url;?>";
    var site_url = "<?php echo site_url();?>";
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
                <h3>共计：<?php echo $total;?>套</h3>
                <a href="javascript:;" id="delAllBtn" class="btnNew delyoure">删除选中</a>
                <div class="search-a"><input class="iptSearch-a" type="text" placeholder="请输入任务名称" name="Search" value="<?php echo (isset($search) ? $search : "") ?>"><i class="fa fa-search"></i></div>
            </div>
            <?php if($total > 0){ ?>
            <table class="studytaskList">
                <thead>
                <tr class="table-title">
                    <td width="60"><input type="checkbox" id="checkAll">全选</td>
                    <td width="300">任务名称</td>
                    <td width="80">任务类型</td>
                    <td width="60">难度</td>
                    <td id="" width="100"><a href="<?php echo $Sort["TaskStartTime"]["url"]; ?>">下发日期<i class="<?php echo $Sort["TaskStartTime"]["icon"]; ?>"></i></a></td>
                    <td width="100">进展情况</td>
                    <td >操作</td>
                </tr>
                </thead>
                <tbody id="study_task">
                <?php
                $diff = array('初级','中级','高级');
                foreach ($data as $val){ ?>
                <tr>
                    <td><?php if($val['TaskTypeJudge'] == 2){?>
                            <input type="checkbox" data-code="<?php echo $val['TaskCode'];?>" onclick="checkThis('#study_task','checkAll')" name="checkTask">
                        <?php } ?></td>
                    <td title="<?php echo $val['TaskName'];?>">
                        <a class="forRed" title="<?php echo $val['TaskName'];?>" href="<?php echo site_url().'Education/bookdetail?packageid='.$val['PackageID'];?>"><?php echo $val['TaskName'];?></a>
                    </td>
                    <td>
                        <?php if($val['TaskTargetType'] == 1){
                            echo '学员任务';
                        }else if($val['TaskTargetType'] == 2){
                            echo '班级任务';
                        }else{
                            echo '混合任务';
                        }?>
                        </td>
                    <td><?php echo $diff[$val['PackageDiff']];?></td>
                    <td ><?php echo date('Y-m-d',$val['CreateTime']);?></td>
                    <td >
                        <a title="<?php echo $val['Progress'];?>%" href="<?php echo site_url().'Education/studydetail?taskcode='.$val['TaskCode'];?>">
                            <p class="tongji forBlue">统计详情</p>
                            <div class="proDiv">
                                <div class="pro" style="width:<?php echo $val['Progress'];?>%;"></div>
                            </div>
                        </a>
                    </td>
                    <td><?php
                        $end = '<a class="forRed endStudy" onclick="endStudy(this)" data-code="'.$val["TaskCode"].'" href="javascript:;" ><i class="fa fa-stop-circle-o"></i>结束</a>';
                        if($val['TaskTypeJudge'] == 2){
                            $end = '<a class="endspan">已结束</a>';
                        }
                        echo $end;
                        ?>
                        <a href="javascript:;" onclick="delStudy(this)" data-code="<?php echo $val['TaskCode'];?>" class="forRed delStudy"><i class="fa fa-trash"></i>删除</a>
                    </td>
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
            <?php }else{ ?>
                <div class="noNews block">
                    <i class="fa fa-file-text" aria-hidden="true"></i><span>没有找到数据......</span>
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
<!--结束提示弹窗-->
<div class="popUpset animated " id="studyBox">
    <form action="" method="post">
        <div class="popTitle">
            <p>确认操作</p>
            <a href="javascript:;" class="close close-1"></a>
        </div>
        <div class="infoBox">
            <p class="promptNews">请确认是否删除所有选中的学习任务?</p>
            <div class="btnBox">
                <a href="javascript:;" class="publicOk " id="delStudyBtn">确定</a>
                <a href="javascript:;" class="publicNo  hidePop-1">取消</a>
            </div>
        </div>
    </form>
</div>

<div class="popUpset animated " id="delBox">
    <form action="" method="post">
        <div class="popTitle">
            <p>确认操作</p>
            <a href="javascript:;" class="close close-1"></a>
            <input type="hidden" id="delTaskCode" value=""/>
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
            <input type="hidden" id="endTaskCode" value=""/>
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
