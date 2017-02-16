<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>课程详情</title>
    <!--公用样式-->
    <link rel="shortcut icon" href="<?php echo base_url() ?>resources/imgs/public/title.ico">
    <link href="<?php echo base_url() ?>resources/css/public/reset.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>resources/css/public/animate.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/thirdparty/font-awesome-4.5.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>resources/css/public/firstStart.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/filter.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>resources/css/public/content.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>resources/css/student/startstudy.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>resources/css/teacher/xiafaPop.css" rel="stylesheet" type="text/css" />
    <!--第三方样式-->

    <!--header框架js-->
    <script src="<?php echo base_url() ?>resources/js/public/jquery-1.11.0.js"></script>
    <script src="<?php echo base_url() ?>resources/js/public/template.js"></script>
    <script src="<?php echo base_url() ?>resources/thirdparty/self-ajax-pagination/js/self-ajax-pagination.js"></script>
    <script src="<?php echo base_url() ?>resources/thirdparty/WdatePicker/js/DateJs/WdatePicker.js"></script>

</head>
<script type="text/javascript">
    var site_url = '<?php echo site_url();?>';
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
            <!--面包屑导航 start-->
            <div class="lable_title">
                <a href="<?php echo site_url('Adminsubject/myexam')?>" title="知识体系管理" class="for_lable">知识体系管理</a>&gt;
                <a href="<?php echo site_url('Adminsubject/mybook')?>" title="全部课程" class="for_lable">全部课程</a>&gt;
                <a class="label_local"><?php echo $data['packagename'];?></a>
            </div>
            <!--面包屑导航 end-->
            <div class="courseInfo">
                <!--课程的介绍 讲师 课时之类的 start-->
                <div class="courseInfoTop clearfix">
                    <div class="course_img">
                        <?php $img = $data['packageimg'] ? $data['packageimg']:'logo.png';?>
                        <img src="<?php echo base_url().'resources/files/img/course/'.$img;?>" alt="<?php echo $data['packagename'];?>" title="<?php echo $data['packagename'];?>" onerror="javascript:this.src='<?php echo base_url() ?>resources/files/img/course/logo.png'">

                    </div>
                    <div class="courseInfoText">
                        <div class="courseName" title="<?php echo $data['packagename'];?>"><?php echo $data['packagename'];?></div>
                        <div class="courseInfoAbout clearfix">
                            <ul class="ulLeft">
                                <li class="clearfix"><span class="spanTiltle">主讲：</span><span><?php echo $data['packageauthor'];?></span></li>
                                <li class="clearfix"><span class="spanTiltle">课时：</span><span>共<?php echo $data['packnum'].'章/'.$data['sectionnum'].'节';?></span></li>
                            </ul>
                            <ul class="ulRight">
                                <li id = 'ifFuceng' class="clearfix opOver"><span class="spanTiltle">类别：</span>
                                    <?php foreach ($data['ArchitectureName'] as $val) {
                                        if($val != ''){
                                            echo '<span class="spanBlue">'.$val.'</span>';
                                        }
                                    }?>
                                    <i class="fa fa-reorder" id="moreNewsLei">
                                        <span >详情</span>
                                    </i>
                                    <div  class="fuCeng" >
                                        <?php foreach ($data['ArchitectureName'] as $val) {
                                            if($val != ''){
                                                echo '<p><span class="spanBlue">'.$val.'</span></p>';
                                            }
                                        }?>
                                    </div>
                                </li>
                                <li class="clearfix"><span class="spanTiltle">内容：</span><?php if($data['practicesectionNum'] > 0){echo '<span class="spanGreen">实践</span>';} if($data['theorynum'] > 0){echo '<span class="spanYellow">理论</span>';}?></li>
                            </ul>

                        </div>
                        <?php $book_diff = array('初级','中级','高级');?>
                        <div class="courseInfoMore clearfix">
                            <ul>
                                <li class="w250"><i class="fa fa-calendar fa-w"></i>创建时间：<?php echo $data['createtime'];?></li>
                                <li class="bgspks"><i class="fa fa-book fa-w"></i>理论课时：<?php echo $data['theorynum'];?>节</li>
                                <li class="w250 bgkcnd"><i class="fa fa-h-square fa-w"></i>课程难度：<?php echo $book_diff[$data['packagediff']];?></li>
                                <li class="bgks"><i class="fa fa-object-ungroup fa-w"></i>实践课时：<?php echo $data['practicesectionNum'];?>节</li>
                            </ul>
                        </div>
                        <!--<div class="courseStudy">
                            <button class="btnRelease" id="<?php /*echo $data['packageid'];*/?>" name="<?php /*echo $data['packagename'];*/?>">下发本课程</button>
                        </div>-->

                    </div>
                </div>
                <!--课程的介绍 讲师 课时之类的 end-->
                <!--课程分章介绍-->
                <div class="curseInfoMsg">
                    <div class="classStep">
                        <div class="courseInt">课程简介</div>
                        <p class="curseIntMsg"><?php if(!empty($data['packagedesc'])){echo $data['packagedesc'];}else{echo '无';}?></p>
                    </div>
                    <div class="classStep">
                        <?php
                        if(isset($data['packlist']) && count($data['packlist']) > 0) {
                            $p = 1;
                            foreach ($data['packlist'] as $key=>$value){
                                ?>
                                <div class="courseStep">第<?php echo $p;?>章：<?php echo $value['PackageName'];?></div>
                                <div class="courseInner">
                                    <p class="curseIntMsg"><?php echo $value['PackageDesc'];?></p>
                                    <?php
                                    if(isset($value['courselist']) && count($value['courselist']) > 0) {
                                        $u = 1;
                                        foreach ($value['courselist'] as $k=>$val) {
                                            ?>
                                            <div class="courseStepItem">
                                                <h3 class="step"><b><?php echo $u;?></b><?php echo $val['CourseName'];?><em></em></h3>
                                                <?php
                                                if(isset($val['sectionlist']) && count($val['sectionlist']) > 0) {
                                                    $s = 1;
                                                    foreach ($val['sectionlist'] as $kk => $vv) {
                                                        ?>
                                                        <div class="item clearfix">
                                                            <p class="itemTitle">
                                                                <a href="<?php echo site_url().'/Adminsubject/sectiondetail?packageid='.$data['packageid'].'&sectionid='.$vv['SectionID'];?>"><?php echo $p.'.'.$u.'.'.$s.':'.$vv['SectionName'];?></a>
                                                            </p>
                                                            <div class="couseTime">
                                                                <span class="spsc"><i class="fa fa-play-circle-o fa-w"></i>视频时长：<?php echo $vv['VideoTime'];?>分钟</span>
                                                                <span class="skt"><i class="fa fa-question-circle"></i>思考题：<?php if(isset($vv['qusetion'])){ echo count($vv['qusetion']);}else{ echo 0;}?>道</span>
                                                            </div>
                                                            <?php
                                                            if ($vv['VideoTime'] > 0){
                                                                $biao = 'fa fa-play-circle';
                                                            }  else if($vv['SectionType'] == 0){
                                                                $biao = 'fa fa-book fa-fw';
                                                            } else {
                                                                $biao = 'fa fa-wrench';
                                                            }
                                                            ?>
                                                            <a href="<?php echo site_url().'/Adminsubject/sectiondetail?packageid='.$data['packageid'].'&sectionid='.$vv['SectionID'];?>" class="starment courseState"><i class="<?php echo $biao;?>"></i><span>课程详情</span></a>
                                                        </div>
                                                        <?php
                                                        $s++;
                                                    }
                                                }
                                                ?>
                                            </div>
                                    <?php
                                            $u++;
                                        }
                                    }
                                    ?>
                                </div>
                        <?php
                                $p++;
                            }
                        }
                        ?>

                        <div class="courseStep">完结</div>
                    </div>
                </div>
            </div>
        </div>

        <!--公用centent框架结束-->
    </div>

    <!--公用fotter框架开始-->
    <?php $this->load->view('public/footer.php')?>
    <!--公用fotter框架结束-->
</div>

<!-- 提示 -->
<div class="maskbox"></div>
<!--下发学习任务-->
<div class="popUpset animated" id="xiafaBox">
    <form action="" method="post">
        <div class="popTitle">
            <p>下发学习任务</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox onCanBg height-550" id="addBoxinfo">
            <div class="xiafaBox">
                <div class="box-xiafa-cen">
                    <div class="xiafa">
                        <div class="fisrtC">
                            <span>任务名称：</span>
                            <input class="classTitle" id="taskname" type="text" value="" />
                        </div>
                        <div class="fisrtC">
                            <span>开始时间：</span>
                            <input type="text" id="starttime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" class="Wdate ipt starttime" />
                            <span class="moreMagrn">结束时间：</span>
                            <input type="text" id="endtime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" class="Wdate ipt endtime" />
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
                                //showSelfAjaxPagination('pageContainerC',site_url +'Education/class_list', "sapSucClass");
                            </script>
                        </div>
                        <div class="biao">
                            <p class="allChec"><label for="checkStudent"></label><input type="checkbox" id="checkStudent"/>全选</p>
                            <ul class="txtBoxLi" id="studentList"></ul>
                            <div id="pageContainerS" class="page popPage" >
                            </div>
                            <script type="text/javascript">
                                //showSelfAjaxPagination('pageContainerS',site_url +'Education/student_list', "sapSucStudent");
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

<!-- 下发提示信息 -->
<div class="popUpset animated" id="hintBox" >
    <form action="" method="post">
        <div class="popTitle">
            <p>提示操作</p>
        </div>
        <div class="infoBox">
            <p class="promptNews promptUp"></p>
        </div>
    </form>
</div>
</body>
</html>
<script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/prompt.js"></script>
<script src="<?php echo base_url() ?>resources/js/admin/edu_book.js"></script>
