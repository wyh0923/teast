<!DOCTYPE html>
<html>
<head>
	<title>我的试卷</title>

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
<script src="<?php echo base_url() ?>resources/thirdparty/WdatePicker/js/DateJs/WdatePicker.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/page.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>resources/js/teacher/exam.js"></script>



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
                    <a href="" title="" class="for_lable">知识体系管理</a>&gt;
                    <a>全部试卷</a>
              </div>
            <!--面包屑导航  end-->
                <div class="Filter">

                    <div class="filter clearfix ">
                        <h3 class="filterTitle">时间范围：</h3>
                        <div class="filterList">
                            <input id="stime" onfocus="WdatePicker({oncleared: function(){clearTime();},onpicked: function(){searchForTime();},dateFmt:'yyyy-MM-dd HH:mm:ss'})" class="Wdate" name="starttime" value="<?php if ($time):?><?php echo $time['starttime'];?><?php endif;?>" type="text"><span class="marAuto">至</span>
                            <input id="etime" onfocus="WdatePicker({oncleared: function(){clearTime();},onpicked: function(){searchForTime();},dateFmt:'yyyy-MM-dd HH:mm:ss'})" class="Wdate" name="endtime" value="<?php if ($time):?><?php echo $time['endtime'];?><?php endif;?>" type="text">
                        </div>
                    </div>
                </div>
                <div class="total clearfix">
                    <h3>共计：<?php echo $total_rows;?>套</h3>
                    <a href="<?php echo site_url('Subject/addexam')?>" class="btnNew" id="addBtn"><span>+</span>新增试卷</a>
                    <div class="search-a">
                        <input type="text" class="iptSearch-a esear" value="<?php echo $search;?>" name="Search" placeholder="请输入关键字搜索">
                        <i class="fa fa-search csear"></i>
                    </div>
                </div>
                <table class="testPaperList">
                    <thead>
                            <tr class="table-title">
                                <td width="320" id="ExamName" code="<?php if ($sort && $sort['field']=='ExamName'):?><?php echo $sort['order'];?><?php endif;?>">
                                    <a>试卷名<i class="fa
                                    <?php if ($sort && $sort['field']=='ExamName' && $sort['order']=='DESC'):?>fa-sort-alpha-desc
                                    <?php elseif ($sort && $sort['field']=='ExamName' && $sort['order']=='ASC'):?>fa-sort-alpha-asc
                                    <?php else:?>fa-sort<?php endif;?>"
                                    ></i></a></td>
                                <td width="180" id="TeacherID" code="<?php if ($sort && $sort['field']=='TeacherID'):?><?php echo $sort['order'];?><?php endif;?>">
                                    <a>制作人<i class="fa
                                    <?php if ($sort && $sort['field']=='TeacherID' && $sort['order']=='DESC'):?>fa-sort-alpha-desc
                                    <?php elseif ($sort && $sort['field']=='TeacherID' && $sort['order']=='ASC'):?>fa-sort-alpha-asc
                                    <?php else:?>fa-sort<?php endif;?>
                                    "></i></a></td>
                                <td width="160" id="CreateTime" code="<?php if ($sort && $sort['field']=='CreateTime'):?><?php echo $sort['order'];?><?php endif;?>">
                                    <a>制作时间<i class="fa
                                    <?php if ($sort && $sort['field']=='CreateTime' && $sort['order']=='DESC'):?>fa-sort-alpha-desc
                                    <?php elseif ($sort && $sort['field']=='CreateTime' && $sort['order']=='ASC'):?>fa-sort-alpha-asc
                                    <?php else:?>fa-sort<?php endif;?>
                                "></i></a></td>
                                <td >操作</td>
                            </tr>   
                    </thead>
                    <tbody>
                    <?php foreach ($exams as $e):?>
                            <tr>
                                <td ><a href="<?php echo site_url('Education/examquestion').'?examid='.$e['ExamID']?>" target="_blank" class="forRed" title="<?php echo $e['ExamName']?>"><?php echo $e['ExamName']?></a></td>
                                <td title=""><?php echo $this->session->userdata('UserName');?></td>
                                <td title=""><?php echo date("Y-m-d",$e['CreateTime']);?></td>
                                <td>
                                    <a href="javascript:;" code="<?php echo $e['ExamID'];?>" diff="<?php echo $e['ExamDiff'];?>" name="<?php echo $e['ExamName'];?>" class="forBlue ismod" >
                                        <i class="fa fa-edit" ></i>编辑
                                    </a>
                                    <a href="javascript:;" class="forRed edel" code="<?php echo $e['ExamID'];?>">
                                        <i class="fa fa-trash-o cBrown"></i>删除
                                    </a>
                                </td>
                            </tr>
                    <?php endforeach;?>
        
                    </tbody>
                </table>
                <!--page-->
                <?php if ($total_rows > 0):?>

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
                <?php else: ?>
                    <div class="noNews block">
                        <i class="fa fa-file-text" aria-hidden="true"></i><span>没有找到数据......</span>
                    </div>
                <?php endif; ?>
                <!--page end-->
         </div>
	<!--right stop-->
	</div>


    <!--center stop-->
    <!--footer start-->
    <?php $this->load->view('public/footer.php')?>
    <!--footer stop-->
</div>

<div class="maskbox"></div>
<!--删除成功-->
<div class="popUpset animated " id="edOk"  >
    <form action="" method="post">
        <div class="popTitle">
            <p>提示操作</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox">
            <p class="promptNews promptUp">删除成功</p>

        </div>
    </form>
</div>
<!--删除确认-->
<div class="popUpset animated " id="edelOk">
    <form action="" method="post">
        <div class="popTitle">
            <p>确认操作</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox">
            <p class="promptNews">确认删除该试卷吗</p>
            <div class="btnBox">
                <a href="javascript:;" class="publicOk" id="eOk">确定</a>
                <a href="javascript:;" class="publicNo hidePop-1" id="">取消</a><!--如果是子层弹窗，调用hidePop-2-->
            </div>

        </div>
    </form>
</div>

<script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/prompt.js"></script>

<script type="text/javascript">
    var site_url = '<?php echo site_url() ?>';
    var search = "<?php echo $search; ?>";
    var time = "<?php if ($time):?><?php echo $time['starttime'].'_'.$time['endtime'];?><?php endif;?>";
</script>

</body>
</html>