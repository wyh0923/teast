<!DOCTYPE html>
<html>
<head>
    <title>新增题目</title>

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
    <link href="<?php echo base_url() ?>resources/css/admin/addCourse_Exam.css" rel="stylesheet" type="text/css">
    <script src="<?php echo base_url() ?>resources/thirdparty/huploadify/js/jquery.Huploadify.js"></script>
    <link href="<?php echo base_url() ?>resources/thirdparty/self-ajax-pagination/css/self-ajax-pagination.css" type="text/css" rel="stylesheet"/>
    <script type="text/javascript" src="<?php echo base_url() ?>resources/thirdparty/self-ajax-pagination/js/self-ajax-pagination.js"></script>
    <link href="<?php echo base_url() ?>resources/thirdparty/huploadify/css/Huploadify.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="<?php echo base_url() ?>resources/js/teacher/question_add_mod.js"></script>
    <script src="<?php echo base_url() ?>resources/thirdparty/clipboard/clipboard.min.js"></script>
    <script type="text/javascript">
        var site_url = '<?php echo site_url() ?>';
        var base_url = '<?php echo base_url() ?>';
    </script>

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
                <a href="<?php echo site_url('Subject/questionlist')?>" title="知识体系管理" class="for_lable">知识体系管理</a>&gt;
                <a href="<?php echo site_url('Subject/questionlist')?>" title="题目管理" class="for_lable">题目管理</a>&gt;
                <a>新增题目</a>
            </div>
            <!--面包屑导航 end-->
            <!--title-->
            <div>
                <h3 class="lable_h3">新增题目</h3>
            </div><!-- end title -->

            <div class="addQuesInner addCourseInner">
                <!-- <input type="hidden" id="PackageCode" name="PackageCode" value=""/> -->

                <!--关联场景-->
                <div class="addItem clearfix" id="relationscene">
                    <span class="addTit fl">关联场景：</span>
                    <label>
                        <a href="javascript:;" class="curForBlue" id = 'noCJbtn' typect="0">不关联场景</a>
                    </label>
                    <label>
                        <a href="javascript:;" class="" id = 'ctfBtn' typect="1">关联CTF场景</a>
                    </label>
                    <label>
                        <a href="javascript:;" class="" id = 'secnBtn' typect="2">关联实验场景</a>
                    </label>
                </div>

                <!--已经关联-->
                <div class="addItem clearfix outHide" id="ctfOrSec">
                    <span class="addTit fl">已关联场景：</span>
                    <input id="changjingname" disabled="true"  class="changJingName" type="text" value="">
                    <input id="changjingcode" value="" type="hidden">
                </div>

                <!--题目类型-->
                <div class="addItem clearfix" id="questiontype">
                    <span class="addTit fl">题目类型：</span>
                    <label><a href="javascript:;" class="curForBlue" type="1">单选题</a></label>
                    <label><a href="javascript:;" class="" type="2">多选题</a></label>
                    <label><a href="javascript:;" class="" type="3">判断题</a></label>
                    <label><a href="javascript:;" class="" type="4">填空题</a></label>
                    <label><a href="javascript:;" class="" type="5">夺旗题</a></label>
                </div>

                <!--题目难度-->
                <div class="addItem clearfix" id="timunandu">
                    <span class="addTit fl">题目难度：</span>
                    <label><a href="javascript:;" class="curForBlue">初级</a></label>
                    <label><a href="javascript:;" class="">中级</a></label>
                    <label><a href="javascript:;" class="">高级</a></label>
                </div>

                <!--题干-->
                <div class="addItem clearfix">
                    <span class="addTit fl">题干：</span>
                    <textarea type="text" id="PackageName" name="PackageName" value="" placeholder="" class="addTxt fl"> </textarea>
                    <p class="daoNews"><nobr>*</nobr>&nbsp;题干若插入资料，请保证资料输入的格式：![资料名字](资料链接)。<br/>&nbsp;&nbsp;例子：![1.png](../../resources/files/question/1476335694000.png)<i class="fa fa-close"></i></p>
                    <i class="fa fa-question newStarBtn"></i>
                    
                </div>

                <!--隐藏域-->
                <input id="rescode" type="hidden">

                <!--选项-->
                <div class="addItem clearfix danxuanxuanxiang">
                    <span class="addTit fl" id="answertitle">选项：</span>
                    <div class="xuantiBox" id="xuantiBox">
                        <div class="xuanTiIn" id="danxuan" >
                            <div class="parent_11">
                                <input name="radiodan" type="radio">
                                <input name="danxuan" type="text">
                                <span onclick="addthis(this)"> + </span>
                                <p>
                                    <input name="radiodan" type="radio">
                                    <input name="danxuan" type="text">
                                    <span><a href="javascript:;" onclick="delthis(this)"> - </a></span>
                                </p>
                            </div>
                        </div>
                        <div class="tab outHide" id="duoxuan">

                            <div class="parent_11">
                                <input name="checkboxduo" type="checkbox">
                                <input name="duoxuan" type="text">
                                <span onclick="addthis(this)"> + </span>
                                <p>
                                    <input type="checkbox" name="checkboxduo">
                                    <input name="duoxuan" type="text">
                                    <span ><a href="javascript:;" onclick="delthis(this)"> - </a></span>
                                </p>
                            </div>
                        </div>
                        <div class="tab outHide" id="panduan">
                            <div>
                                <input name="radiopanduan" class="rad" value="对" type="radio"><span>对</span>
                                <input name="radiopanduan" class="rad" value="错" type="radio"><span>错</span>
                            </div>
                        </div>
                        <div class="tab outHide" id="tiankong">
                            <div class="parent_11">
                                <input name="tiankong" type="text"><!-- <span onclick="addthis(this)"> + </span> -->

                            </div>
                        </div>
                        <div class="tab outHide" id="flag">
                            <div>
                                <input name="flag" type="text">
                            </div>
                        </div>
                    </div>
                </div>


                <!--上传附件-->
                <div class="addItem upDownBox clearfix" id="scfj">
                    <span class="addTit fl">上传附件：</span>
                    <input type="hidden" value="" class="uploadIpt" id="uploadfile" disabled="true"　readOnly="true"/>
                    <div id="adduploadIcon" class="startUpBox bigInput"></div>
                </div>


                <!--附件列表-->
                <div class="addItem clearfix" id="sceneresdiv">
                    <span class="addTit fl">附件列表：</span>
                    <div id="fujian" class="clearfix">
                        <table border="1" cellpadding="0" cellspacing="1" class="addQuesListBox">
                            <thead>
                            <tr class="table-title" id="">
                                <th width="120">附件名称</th>
                                <th>URL</th>
                                <th width="130">操作</th>
                            </tr>
                            </thead>
                            <tbody id="tableneirong">

                            </tbody>

                        </table>
                    </div>
                </div>
                <p id="adderrormsg"></p>

                <div class="clearfix btnBox">
                    <a href="javascript:;" id="saveQueList" class=" publicOk">保存</a>
                    <a href="<?php echo site_url('Subject/questionlist')?>" class="publicNo" >返回</a>
                </div>

            </div> <!-- contentInners -->
        </div>
        <!--right stop-->
    </div>


    <!--center stop-->
    <!--footer start-->
    <?php $this->load->view('public/footer.php')?>

    <!--footer stop-->
</div>
<div class="maskbox"></div>
<!--ctf列表-->
<div class="popUpset animated " id="ctfListBox"  >
    <div class="popTitle">
        <p>CTF列表</p>
        <a href="javascript:;" id="" class="close close-3"></a><!--如果只有一层弹窗，调用close-1-->
    </div>
    <div class="infoBox">
        <div class="box-margin-cen popSearch noBoederS clearfix">

            <div class="goSearch ">
                <input type="text" id="sapSearch_ctfPage" class="" value="" name="Search" placeholder="请输入关键字搜索">
                <i class="fa fa-search ctfSearch"></i>
            </div>
        </div>
        <div class="box-margin-cen popTable downSearch ">
            <table class="">
                <thead>
                <tr class="table-title">
                    <td width="160">CTF模板</td>
                    <td >CTF内容</td>
                    <td width="60">选中</td>
                </tr>
                </thead>
                <tbody id="ctTble">
                
                </tbody>
            </table>
        </div>

        <!--无数据提醒-->
        <div class="noNews" >
            <i class="fa fa-file-text" aria-hidden="true"></i><span>没有找到数据......</span>
        </div>
        <div  class="page popPage" id="ctfPage"> </div>

        <script type="text/javascript">
            showSelfAjaxPagination('ctfPage', site_url+"Subject/ctflist", "ctfLists");
        </script>

        <div class="btnBox">
            <a href="javascript:;" class="publicNo hidePop-3" id="">取消</a>
        </div>
    </div>
</div>
<!--SCENE列表-->
<div class="popUpset animated " id="scenListBox" >
    <div class="popTitle">
        <p>SCENE列表</p>
        <a href="javascript:;" id="" class="close close-3"></a><!--如果只有一层弹窗，调用close-1-->
    </div>
    <div class="infoBox">
        <div class="box-margin-cen popSearch noBoederS clearfix">

            <div class="goSearch ">
                <input type="text" id="sapSearch_secePage" class="" value="" name="Search" placeholder="请输入关键字搜索">
                <i class="fa fa-search sceneSearch"></i>
            </div>
        </div>
        <div class="box-margin-cen popTable downSearch ">
            <table class="">
                <thead>
                <tr class="table-title">
                    <td width="240">SCENE模板</td>
                    <td >SCENE内容</td>
                    <td width="60">选中</td>
                </tr>
                </thead>
                <tbody id="seTable">

                </tbody>
            </table>

        </div>
        <!--无数据提醒-->
        <div class="noNews" >
            <i class="fa fa-file-text" aria-hidden="true"></i><span>没有找到数据......</span>
        </div>
        <div  class="page popPage" id="secePage">  </div>
        <script type="text/javascript">
            showSelfAjaxPagination('secePage', site_url+"Subject/scenelist", "seceLists");
        </script>

        <div class="btnBox">
            <a href="javascript:;" class="publicNo hidePop-3" id="">取消</a>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/prompt.js"></script>

</body>
</html>