<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>教学任务管理-新建考试任务</title>
    <!--公用样式-->
    <link rel="shortcut icon" href="<?php echo base_url() ?>resources/imgs/public/title.ico">
    <link href="<?php echo base_url() ?>resources/css/public/reset.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>resources/css/public/animate.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/thirdparty/font-awesome-4.5.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>resources/css/public/firstStart.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/filter.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>resources/css/public/content.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>resources/css/teacher/study_exam.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>resources/css/teacher/xiafaPop.css" rel="stylesheet" type="text/css" />
    <!--第三方样式-->

    <!--header框架js-->
    <script src="<?php echo base_url() ?>resources/js/public/jquery-1.11.0.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/page.js"></script>
    <script src="<?php echo base_url() ?>resources/thirdparty/self-ajax-pagination/js/self-ajax-pagination.js"></script>
    <script src="<?php echo base_url() ?>resources/js/public/template.js"></script>
    <script src="<?php echo base_url() ?>resources/thirdparty/WdatePicker/js/DateJs/WdatePicker.js"></script>

</head>
<script>
    var search_url = "<?php echo $search_url;?>";
    var site_url = "<?php echo site_url();?>";
    var starttime = "<?php echo $starttime;?>";
    var endtime = "<?php echo $endtime?>";
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
            <div class="Filter">
                <div class="filter clearfix ">
                    <h3 class="filterTitle">题目类型：</h3>
                    <div class="filterList">
                        <a href="<?php echo site_url().'Education/eduexam?examtype=1,2,4,8,16,';?>" <?php if(count($typeArr) == 5 || count($typeArr) == 0){ echo 'class="filterCur"'; } ?>>全部</a>
                        <a><input class="examtype" name="examtype" type="checkbox" <?php if(in_array('1',$typeArr)){echo "checked='checked'"; } ?> value='1' id="t1" /><label for="t1">单选题</label></a>
                        <a><input class="examtype" name="examtype" type="checkbox" <?php if(in_array('2',$typeArr)){echo "checked='checked'";} ?> value='2' id="t2" /><label for="t2">多选题</label></a>
                        <a><input class="examtype" name="examtype" type="checkbox" <?php if(in_array('4',$typeArr)){echo "checked='checked'";} ?> value='4' id="t3" /><label for="t3">判断</label></a>
                        <a><input class="examtype" name="examtype" type="checkbox" <?php if(in_array('8',$typeArr)){echo "checked='checked'";} ?> value='8' id="t4" /><label for="t4">填空题</label></a>
                        <a><input class="examtype" name="examtype" type="checkbox" <?php if(in_array('16',$typeArr)){echo "checked='checked'";} ?> value='16' id="t5" /><label for="t5">夺旗题</label></a>
                    </div>
                </div>
                <div class="filter clearfix ">
                    <h3 class="filterTitle">难度等级：</h3>
                    <div class="filterList">
                        <?php foreach($filter[0]["subFilterData"] as $item): ?>
                            <a title = "<?php echo $item["value"] ?>" href="<?php echo $item["url"] ?>" class="<?php echo ($item["active"]?"filterCur":"")?>"><?php $str = $item["value"]; echo  strlen($str)>90?mb_substr($str,0,30).'..':$item["value"]?></a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="total clearfix">
                <h3>共计：<?php echo $total;?>套</h3>
                <div class="search-a">
                    <input type="text" class="iptSearch-a" value="<?php echo (isset($search) ? $search : "") ?>" name="Search" placeholder="请输入试卷名称">
                    <i class="fa fa-search"></i>
                </div>
            </div>
            <?php
            $diff = array('初级','中级','高级');
            if($total > 0){ ?>
                <table class="testPaperList color_block">
                    <thead>
                    <tr class="table-title">
                        <td id="" width="280">试卷名称</td>
                        <td id="" width="100"><a href="<?php echo $Sort["UserName"]["url"]; ?>">制作老师<i class="<?php echo $Sort["UserName"]["icon"]; ?>"></i></a></td>
                        <td width="160">题目类型</td>
                        <td id="" width="60"><a href="<?php echo $Sort["ExamDiff"]["url"]; ?>">难度<i class="<?php echo $Sort["ExamDiff"]["icon"]; ?>"></i></a></td>
                        <td id="" width="120"><a href="<?php echo $Sort["CreateTime"]["url"]; ?>">发布时间<i class="<?php echo $Sort["CreateTime"]["icon"]; ?>"></i></a></td>
                        <td >操作</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($data as $val){ ?>
                    <tr>
                        <td title="<?php echo $val['ExamName'];?>"><a class="forRed" href="<?php echo site_url().'Education/examquestion?examid='.$val['ExamID'];?>" target="_blank"><?php echo $val['ExamName'];?></a></td>
                        <td title="<?php echo $val['UserName'];?>"><?php echo $val['UserName'];?></td>
                        <td><?php
                            $type = '';$scene = 0;
                            if($val['ExamType']&1){
                                $type.="<span>单选题</span>";
                            }
                            if($val['ExamType']&2){
                                $type.="<span>多选</span>";
                            }
                            if($val['ExamType']&4){
                                $type.="<span>判断</span>";
                            }
                            if($val['ExamType']&8){
                                $type.="<span>填空</span>";
                            }
                            if($val['ExamType']&16){
                                $type.="<span>夺旗题</span>";
                            }
                            if($val['ExamType']&32){
                                $type.="<span class='littleRed'>场景题</span>";
                                $scene = 1;
                            }
                            echo $type;
                            ?></td>
                        <td><?php echo $diff[$val['ExamDiff']];?></td>
                        <td><?php echo date('Y-m-d',$val['CreateTime']);?></td>
                        <td>
                            <a href="javascript:;" scene="<?php echo $scene;?>" id="<?php echo $val['ExamID'];?>" name="<?php echo $val['ExamName'];?>" class="btnRelease forYellow"><i class="fa fa-share"></i>下发</a>
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
            <?php }else { ?>
                <div class="noNews block">
                    <i class="fa fa-file-text" aria-hidden="true"></i>没有找到数据......
                </div>
            <?php } ?>

        </div>

        <!--公用centent框架结束-->
    </div>

    <!--公用fotter框架开始-->
    <?php $this->load->view('public/footer.php')?>
    <!--公用fotter框架结束-->
</div>
<div class="maskbox"></div>

<!--下发考试任务-->
<div class="popUpset animated" id="xiafaBox">
    <form action="" method="post">
        <div class="popTitle">
            <p>下发考试任务</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox onCanBg height-550">
            <div class="xiafaBox">
                <div class="box-xiafa-cen">
                    <div class="xiafa clearfix">
                        <div class="fisrtC">
                            <span><nobr>*</nobr>任务名称：</span>
                            <input class="classTitle" id="taskname" type="text" value="" />
                        </div>
                        <div class="fisrtC">
                            <span><nobr>*</nobr>开始时间：</span>
                            <input type="text" id="starttime"  onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" class="Wdate ipt starttime" />
                            <span class="moreMagrn"><nobr>*</nobr>结束时间：</span>
                            <input type="text" id="endtime"  onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" class="Wdate ipt endtime" />
                        </div>
                        <div class="fisrtC">
                            <span>任务描述：</span>
                            <textarea class="miaoShu" id="taskDesc"></textarea>
                        </div>
                    </div>
                    <div class="xiafa popSearch-2 clearfix">
                        <input class="btn searchBtn" type="button" value="搜索"/>
                        <input type="text" id="sapSearch" class="classTitle sapSearch" value="" name="Search" placeholder="请输入班级名称或者个人名称...">

                    </div>
                    <div class="xiafa clearfix">
                        <div class="titleTab clearfix">
                            <h3 class="activeYellow" data-type="1">班级<i class="fa fa-caret-down"></i></h3>
                            <h3 data-type="2">个人<i class="fa fa-caret-down"></i></h3>
                        </div>
                        <div class="biao">
                            <p class="allChec"><label for="checkClass"></label><input type="checkbox" id="checkClass"/>全选</p>
                            <ul class="txtBoxLi" id="classList"></ul>
                            <div id="pageContainerC" class="page popPage" >
                            </div>
                            <script type="text/javascript">
                                showSelfAjaxPagination('pageContainerC',site_url +'Education/class_list', "sapSucClass");
                            </script>
                        </div>
                        <div class="biao">
                            <p class="allChec"><label for="checkStudent"></label><input type="checkbox" id="checkStudent"/>全选</p>
                            <ul class="txtBoxLi" id="studentList"></ul>
                            <div id="pageContainerS" class="page popPage" >
                            </div>
                            <script type="text/javascript">
                                showSelfAjaxPagination('pageContainerS',site_url +'Education/student_list', "sapSucStudent");
                            </script>
                        </div>

                    </div>
                </div>
                <p class="remindNews" id="errorMsg"></p>
            </div>
            <div class="btnBox">
                <a href="javascript:;" class="publicOk xiafaBtn" id="xiafaBtn" onclick="xiafaBtn()">下发</a>
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
<script src="<?php echo base_url() ?>resources/js/teacher/edu_exam.js"></script>