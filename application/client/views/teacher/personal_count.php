<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>教学统计</title>
    <!--公用样式-->
    <link rel="shortcut icon" href="<?php echo base_url() ?>resources/imgs/public/title.ico">
    <link href="<?php echo base_url() ?>resources/css/public/reset.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>resources/thirdparty/font-awesome-4.5.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>resources/css/public/firstStart.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/filter.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>resources/css/public/content.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>resources/css/teacher/personalstatistic.css" rel="stylesheet" type="text/css" />
    <script src="<?php echo base_url() ?>resources/js/public/jquery-1.11.0.js"></script>
    <!--第三方样式-->
    <script src="<?php echo base_url() ?>resources/thirdparty/highcharts/js/highcharts.js" type="text/javascript"></script>
    <script src="<?php echo base_url() ?>resources/thirdparty/highcharts/js/highcharts-more.js" type="text/javascript"></script>
    <script src="<?php echo base_url() ?>resources/js/teacher/person_cont.js" type="text/javascript"></script>
    <script src="<?php echo base_url() ?>resources/thirdparty/highcharts/js/modules/data.js" type="text/javascript"></script>
    <script src="<?php echo base_url() ?>resources/thirdparty/highcharts/js/modules/exporting.js" type="text/javascript"></script>
    <link href="<?php echo base_url() ?>resources/thirdparty/self-ajax-pagination/css/self-ajax-pagination.css" type="text/css" rel="stylesheet"/>
    <script>
        var site_url = "<?php echo site_url()?>";
        var cou = 0;//控制全部班级能力图显示的样式
    </script>

    <!--header框架js-->

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
        <div class="content personShuJu">
            <div class="xuanxiang">
                <dl onclick="gotourl('Education/studylist')">
                    <dt class="fa fa-camera-retro fa-2x"></dt>
                    <dd>学习任务 : <span><?php echo $study_num?></span></dd>
                </dl>
                <dl onclick="gotourl('Education/examtask')">
                    <dt class="fa fa-map-o fa-2x"></dt>
                    <dd>考试任务 : <span><?php echo $exam_num?></span></dd>
                </dl>
                <dl onclick="gotourl('Classstaff/myclass')">
                    <dt class="fa fa-camera-retro fa-2x"></dt>
                    <dd>班级管理 : <span><?php echo $class_num?></span></dd>
                </dl>
                <dl onclick="gotourl('Subject/mybook')">
                    <dt class="fa fa-edit fa-2x"></dt>
                    <dd>创建课程 : <span><?php echo $course_num?></span></dd>
                </dl>
                <dl onclick="gotourl('Subject/myexam')">
                    <dt class="fa fa-camera-retro fa-2x"></dt>
                    <dd>创建试卷 : <span><?php echo $paper_num?></span></dd>
                </dl>
                <dl onclick="gotourl('Subject/questionlist')">
                    <dt class="fa fa-camera-retro fa-2x"></dt>
                    <dd>创建题目 : <span><?php echo $item_num?></span></dd>
                </dl>
            </div>
            <div class="wind-rose clearfix">
                <h4 class="clearfix"><p class="titleName">班级能力统计</p><p id="ID_ClassPtSum">全部班级能力</p></h4>
                <div id="" class="wind-rose-list" >
                    <table>
                        <thead>
                        <tr class="table-title">
                            <td>班级</td>
                        </tr>
                        <tr>
                            <td class=" selectNews clearfix"><input type="search" id="class-score-search" class="" placeholder="请输入班级名" value="" /><button id="class_score_search_all" class="">全部</button></td>
                        </tr>
                        </thead>
                        <tbody id="allClass" class="wind-rose-body">

                        </tbody>
                    </table>

                    <div class="posBottom">
                        <div class="class-score-page juBuPage">
                            <p class="prev"></p>
                            <ul>
                                <li class="clicked">1</li>
                                <li>2</li>
                                <li>3</li>
                            </ul>

                            <p class="next"></p>
                        </div>
                    </div>
                </div>

                <div id="containerrose" class="containerrose"></div>
            </div>
            <!--班级top10-->
            <div class="classTop">
                <h4 class="clearfix">
                    <p class="topName">班级积分统计Top10<p>
                    <p>
                        <span>统计时间</span>
                        <select id="ID_SelTimeType" >
                            <option value="1">按日统计</option>
                            <option value="2" selected="selected">按月统计</option>
                        </select>
                    </p>
                    <p class="ClassSelTime" id="ClassSelTime">
                        <span>最近</span>
                        <input type="number" name="num" min="3" max="24" value="12" >
                        <span class="lastTxt">个月内</span>
                    </p>
                    <p><input id="BTN_ClassSum" class="" type="button"  value="统计"><span style=" opacity:0">不能删除！！！！</span></p>

                </h4>
                <div id="class_chart"></div>

            </div>
            <!--学生top10-->
            <div class="classTop">
                <h4 class="clearfix">
                    <p class="topName">学生积分统计Top10<p>
                    <p>
                        <span>统计时间</span>
                        <select id="ID_SelTimeType_STU" >
                            <option value="1">按日统计</option>
                            <option value="2" selected="selected">按月统计</option>
                        </select>
                    </p>
                    <p class="ClassSelTime" id="StudentSelTime">
                        <span>最近</span>
                        <input type="number" name="num" min="3" max="24" value="12" >
                        <span class="lastTxt">个月内</span>
                    </p>
                    <p><input id="BTN_StudentSum" class="" type="button"  value="统计"><span style=" opacity:0">用于居中，不能删除！！！！</span></p>

                </h4>
                <div id="student_chart"></div>

            </div>
            <!--课程和试卷统计-->
            <div class="clearfix">
                <div class="studyTop">
                    <h4>课程学习次数Top5统计</h4>
                    <div id="course_top5"></div>
                </div>
                <div class="studyTop examTop">
                    <h4>试卷考试次数Top5统计</h4>
                    <div id="exam_top5"></div>
                </div>
            </div>

        </div>

        <!--公用centent框架结束-->
    </div>

    <!--公用fotter框架开始-->
    <?php $this->load->view('public/footer.php')?>
    <!--公用fotter框架结束-->
</div>

<!--shuju-->
<div style="display:none">
    <table id="freq" border="0" cellspacing="0" cellpadding="0">
        <tbody>
        <tr nowrap="" bgcolor="#CCCCFF">
            <th colspan="9" class="hdr"> </th>
        </tr>
        <tr nowrap="" bgcolor="#CCCCFF">
            <th class="freq">Direction</th>
            <th class="freq">积分</th>
        </tr>

        </tbody></table></div>



</body>
</html>


