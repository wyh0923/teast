<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>我的学习-学习详情</title>
    <!--公用样式-->
    <link rel="shortcut icon" href="<?php echo base_url() ?>resources/imgs/public/title.ico">
    <link href="<?php echo base_url() ?>resources/css/public/reset.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>resources/thirdparty/font-awesome-4.5.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>resources/css/public/firstStart.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/filter.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>resources/css/public/content.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>resources/css/student/startstudy.css" rel="stylesheet" type="text/css" />
    <!--第三方样式-->

    <!--header框架js-->
    <script src="<?php echo base_url() ?>resources/js/public/jquery-1.11.0.js"></script>
    <script src="<?php echo base_url() ?>resources/js/public/template.js"></script>

</head>

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
                <a href="<?php echo site_url().'Study/listunderway';?>" title="我的学习" class="for_lable">我的学习</a>&gt;
                <?php if($data['TaskType'] == 2){ ?>
                    <a href="<?php echo site_url().'Study/listfinished';?>" title="已经完成的学习" class="for_lable">已经完成的学习</a>&gt;
                <?php }else{ ?>
                    <a href="<?php echo site_url().'Study/listunderway';?>" title="正在进行的学习" class="for_lable">正在进行的学习</a>&gt;
                <?php } ?>
                <a ><?php echo $data['TaskName'];?></a>
            </div>
            <!--面包屑导航 end-->
            <div class="courseInfo">
                <!--课程的介绍 讲师 课时之类的 start-->
                <div class="courseInfoTop clearfix">
                    <div class="course_img">
                        <?php $img = $data['PackageImg'] ? $data['PackageImg']:'logo.png';?>
                        <img src="<?php echo base_url().'resources/files/img/course/'.$img;?>" alt="<?php echo $data['TaskName'];?>" title="<?php echo $data['TaskName'];?>" onerror="javascript:this.src='<?php echo base_url() ?>resources/files/img/course/logo.png'">

                    </div>
                    <div class="courseInfoText">
                        <div class="courseName" title="<?php echo $data['TaskName'];?>"><?php echo $data['TaskName'];?></div>
                        <div class="courseInfoAbout clearfix courseInfoAboutNoF">
                            <ul class="ulLeft">
                                <li class="w250 clearfix"><span class="spanTiltle">主讲：</span><span><?php echo $data['PackageAuthor'];?></span></li>
                                <li class="w250 clearfix"><span class="spanTiltle">课时：</span><span>共<?php echo $data['packnum'].'章/'.$data['SectionNum'].'节';?></span></li>
                            </ul>
                            <?php $book_diff = array('初级','中级','高级');?>
                            <ul class="ulRight">
                                <li class="clearfix"><span class="spanTiltle">课程难度：</span><span><?php echo $book_diff[$data['PackageDiff']];?></span></li>
                                <li class="w250 clearfix"><span class="spanTiltle">内容：</span><?php if($data['PracticeSectionNum'] > 0){echo '<span class="spanGreen">实践</span>';} if($data['TheorySectionNum'] > 0){echo '<span class="spanYellow">理论</span>';}?> </li>
                            </ul>
                        </div>
                        <div class="courseInfoMore clearfix">
                            <ul>

                                <li class="w250" title="开始时间"><i class="fa fa-calendar fa-w st"></i><?php echo date('Y-m-d H:i:s',$data['TaskStartTime']);?></li>
                                <li class="bgspks" title="结束时间"><i class="fa fa-calendar fa-w et"></i><?php echo date('Y-m-d H:i:s',$data['TaskEndTime']);?></li>
                                <li class="w250 bgkcnd"><i class="fa fa-book fa-w"></i>理论节:<?php echo $data['TheorySectionNum'];?></li>
                                <li class="bgks"><i class="fa fa-object-ungroup fa-w"></i>实验节:<?php echo $data['PracticeSectionNum'];?></li>
                            </ul>
                        </div>
                        <div class="courseStudy">
                            <div class="coursePr" style="width:<?php echo $data['TaskProcess'];?>%"></div>
                            <p>已经学习了<?php echo $data['TaskProcess'];?>%</p>
                        </div>
                    </div>
                </div>
                <!--课程的介绍 讲师 课时之类的 end-->
                <!--课程分章介绍-->
                <div class="curseInfoMsg">
                    <div class="classStep">
                        <div class="courseInt">课程简介</div>
                        <p class="curseIntMsg"><?php if(!empty($data['PackageDesc'])){echo $data['PackageDesc'];;}else{echo '无';}?>
                        </p>
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
                                    if(count($value['courselist']) > 0) {
                                        $u = 1;
                                        foreach ($value['courselist'] as $k=>$val) {
                                            ?>
                                            <div class="courseStepItem">
                                                <h3 class="step"><b><?php echo $u;?></b><?php echo $val['CourseName'];?><em></em></h3>
                                                <?php
                                                if(count($val['sectionlist']) > 0) {
                                                    $s = 1;
                                                    foreach ($val['sectionlist'] as $kk => $vv) {
                                                        ?>
                                                        <div class="item clearfix">
                                                            <p class="itemTitle">
                                                                <a href="<?php echo site_url().'Study/studysection?taskid='.$vv['TaskID'].'&sectioninsid='.$vv['SectionInsID'];?>"><?php echo $p.'.'.$u.'.'.$s.':'.$vv['SectionName'];?></a>
                                                            </p>
                                                            <div class="couseTime">
                                                                <span class="spsc"><i class="fa fa-play-circle-o fa-w"></i>视频时长：<?php echo $vv['VideoTime'];?>分钟</span>
                                                                <span class="skt"><i class="fa fa-question-circle"></i>思考题：<?php if(isset($vv['qusetion'])){ echo count($vv['qusetion']);}else{ echo 0;};?>道</span>
                                                            </div>
                                                            <?php 
                                                            if($vv['Finished'] == 2){
                                                                $biao = 'fa fa-check-circle';
                                                                $class = 'starment';
                                                                if($vv['SectionType'] == 0){
                                                                    $class = 'play';
                                                                }
                                                                $str = '获得'.$vv['SectionInsPoint'].'分';
                                                            } else if ($vv['VideoTime'] > 0){
                                                                $biao = 'fa fa-play-circle';
                                                                $class = 'play';
                                                                $str = '播放视频';
                                                            }  else if($vv['SectionType'] == 0){
                                                                $biao = 'fa fa-book fa-fw';
                                                                $class = 'starment';
                                                                $str = '课程详情';
                                                            } else {
                                                                $biao = 'fa fa-wrench';
                                                                $class = 'starment';
                                                                $str = '开始实验';
                                                            }
                                                            ?>
                                                            <a href="<?php echo site_url().'Study/studysection?taskid='.$vv['TaskID'].'&sectioninsid='.$vv['SectionInsID'];?>" class="<?php echo $class;?> courseState"><i class="<?php echo $biao;?>"></i><span><?php echo $str;?></span></a>
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


                        <div class="courseStep" >完结</div>
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
</body>
</html>
