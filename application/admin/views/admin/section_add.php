<!DOCTYPE html>
<html>
<head>
    <title>新增小节</title>

    <meta charset="utf-8">
    <link rel="shortcut icon" href="<?php echo base_url() ?>resources/imgs/public/title.ico">
    <script type='text/javascript' src="<?php echo base_url() ?>resources/js/public/jquery-1.11.0.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/template.js"></script>
    <link href="<?php echo base_url() ?>resources/css/public/reset.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/thirdparty/font-awesome-4.5.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/animate.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/thirdparty/huploadify/css/Huploadify.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/firstStart.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/content.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/filter.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/admin/addsection.css" rel="stylesheet" type="text/css">

    <script src="<?php echo base_url() ?>resources/thirdparty/huploadify/js/jquery.Huploadify.js" type="text/javascript"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>resources/js/admin/addsection.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>resources/thirdparty/clipboard/clipboard.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>resources/thirdparty/self-ajax-pagination/js/self-ajax-pagination.js"></script>
    <script type="text/javascript">
        var site_url = '<?php echo site_url() ?>';
         var base_url = "<?php echo base_url() ?>";
         var PackageCode ="<?php echo $cid ?>";
         var UnitCode ="<?php echo $uniid ?>";
         var SectionCode ="";
         var author ="<?php echo $this->session->userdata('Account') ?>";
        //存储视屏路径
        var videoDir = "<?php echo $upload_data['video_dir'];?>";
        
    </script>
    <script src="<?php echo base_url(); ?>resources/js/public/plupload.full.min.js"></script>

    <script src="<?php echo base_url(); ?>resources/js/admin/ajaxfileupload.js"></script>
    <script src="<?php echo base_url(); ?>resources/js/public/bootstrap.min.js"></script>

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
                <a href="<?php echo site_url('Adminsubject/mybook')?>" class="for_lable">知识体系管理</a>&gt;
                <a href="<?php echo site_url('Adminsubject/mybook')?>" class="for_lable">全部课程</a>&gt;
                <a href="<?php echo site_url('Adminsubject/courseframe').'/cid/'.$cid?>" class="for_lable">结构</a>&gt;
                <a>新增节</a>
            </div>

            <!--面包屑导航 end-->
            <h3 class="lable_h3">新增节</h3>

            <div class="addTheoryChapter animated" id="addTheoryChapter">
                <form action="" method="post">
                    <input type="hidden" id="SourseCode" name="SourseCode" value="">
                    <div class="infoBox">

                        <div class="iptBox clearfix">
                            <span class="tit"><nobr>*</nobr>节名称：</span>
                            <input type="text" id="SectionName" value="" class="ipt">
                        </div>
                        <div class="iptBox clearfix">
                            <span class="tit">内容简介：</span>
                            <textarea id="SectionDesc" class="multiTxt"></textarea>
                        </div>
                        <div class="iptBox clearfix" id="SectionGrade">
                            <span class="tit">难度：</span>
                            <label class="cur" grade = '0'>初级</label>
                            <label grade = '1'>中级</label>
                            <label grade = '2'>高级</label>
                        </div>
                        <div class="iptBox clearfix" id="SectionType">
                            <span class="tit">节类型：</span>
                            <label class="cur" type='0'>理论节</label>
                            <label type='1'type='1' >CTF实验</label>
                            <label type='2'>网络实验</label>
                        </div>
                        <div class="iptBox clearfix" id="SectionTypediv">
                            <div class = "SectionTypediv clearfix SectionTypediv0" id="SectionTypediv0">
                                <span class="tit"><nobr>*</nobr>上传视频：</span>
                                <div id="videoUploadBox" class="startUpBox bigInput">
                                </div>
                                <input type="text" id="SectionVideo" hidden disabled="disabled" value="">
                            </div>

                            <div class = "SectionTypediv  SectionTypediv0 clearfix" id="SectionTypediv01">
                                <span class="tit"><nobr>*</nobr>视频时长：</span>

                                <input type="text" class="ipt" id="VideoTime" value="" placeholder="请输入分钟数">分钟
 

                            </div>
                            <div class="SectionTypediv outHide clearfix SectionTypediv1" id="SectionTypediv1" >
                                <span class="tit"><nobr>*</nobr>选择ctf：</span>
                                <div id="ctfSelectBox">
                                    <div class="selectBox">
                                        <input type="text" class="selectInfoBox" id="SectionCtf" disabled="true"  value="">
                                        <a class="selectBtn selectCtfButton" id="selectCtfButton" href="javascript:void(0)" >
                                            <i class="fa fa-upload selectBtnIcon"></i>
                                        </a>
                                        <input type="text" id="SectionCtfcode" class="outHide" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="SectionTypediv outHide clearfix SectionTypediv2" id="SectionTypediv2" >
                                <span class="tit"><nobr>*</nobr>选择场景：</span>
                                <div id="secneSelectBox">
                                    <div class="selectBox">
                                        <input type="text" class="selectInfoBox" id="SectionScene" disabled="true" value="" >
                                        <a class="selectBtn selectSceneButton" id="selectSceneButton" href="javascript:void(0)" >
                                            <i class="fa fa-upload selectBtnIcon"></i>
                                        </a>
                                        <input type="text" id="SectionScenecode" class="outHide" value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="iptBox clearfix">
                            <span class="tit">实验操作手册：</span>
                            <textarea class="multiTxt" id="SectionDoc"></textarea>
                            <p class="daoNews"><nobr>*</nobr>&nbsp;实验操作手册若插入资料，请保证资料输入的格式：![资料名字](资料链接)。<br/>&nbsp;&nbsp;例子：![1.png](../../resources/files/question/1476335694000.png)<i class="fa fa-close"></i></p>
                            <i class="fa fa-question newStarBtn"></i>
                        </div>

                        <div class="listBoxTable clearfix ">
                            <p class="subjectTitle">图片：</p>
                            <!--id="addPicture"-->
                            <input type="file" id="upload" name="upload" accept="image/*" onchange="uploadpic()" style="display: none;" value="" placeholder="" class="addIpt fl"/>
                            <a href="javascript:;" class="changeBtn uploadBtn" id="">新增图片</a>
                        </div>
                        <div class="listBoxTable clearfix">
                            <table class="testPaperSubjectTable12" >
                                <thead>
                                <tr class="table-title">
                                    <td width="600">url</td>
                                    <td>操作</td>
                                </tr>
                                </thead>
                                <tbody id="imgresTable">

                                </tbody>
                            </table>
                        </div>

                        <div class="listBoxTable clearfix ">
                            <p class="subjectTitle">资料列表：</p>
                            <a href="javascript:;" class="changeBtn" id="selectDataBtn">选择资料</a>
                            <a href="javascript:;" class="changeBtn"  id="addDataBtn">新增资料</a>
                        </div>
                        <div class="listBoxTable clearfix">
                            <table class="testPaperSubjectTable1" >
                                <thead>
                                <tr class="table-title">
                                    <td width="295" >名称</td>
                                    <td width="310">url</td>
                                    <td >操作</td>
                                </tr>
                                </thead>
                                <tbody id="youChoseData">
                                </tbody>
                            </table>
                        </div>

                        <div class="listBoxTable clearfix ">
                            <p class="subjectTitle">题目列表：</p>
                            <a href="javascript:;" class="changeBtn"  id="selectQuesBtn">选择题目</a>
                            <a href="javascript:;" class="changeBtn"  id="addQuesBtn">新增题目</a>
                        </div>
                        <div class="listBoxTable clearfix">
                            <table class="testPaperSubjectTable1">
                                <thead>
                                <tr class="table-title">
                                    <td width="145">题目</td>
                                    <td width="145" >类型</td>
                                    <td width="120">出题人</td>
                                    <td width="100" >分数</td>
                                    <td width="80">操作</td>
                                    <td>是否选择场景</td>
                                </tr>
                                </thead>
                                <tbody id="selectedQuestionTable"></tbody>
                            </table>
                        </div>
                        <p class="adderrormsg adderrormsg2" id="allEroar"></p>
                        <div class="listBoxTable clearfix btnBox">
                            <a  href="javascript:;" class=" publicOk" falg="1" id="saveAllNews">保存</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!--right stop-->
    </div>


    <!--center stop-->
    <!--footer start-->
    <?php $this->load->view('public/footer.php')?>

    <!--footer stop-->
</div>
<div class="maskbox"></div>

<!--新增资料-->
<div class="popUpset animated " id="addDataBox" >
    <form>
        <div class="popTitle">
            <p>新增资料</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果只有一层弹窗，调用close-1-->
        </div>
        <div class="infoBox">
            <div class="box-input-cen clearfix">
                <span class="bigTitle"><nobr>*</nobr>资料名称：</span>
                <input type="text" id="SectionDataName" class="intBig">

            </div>
            <div class="box-input-cen clearfix">
                <span class="bigTitle"><nobr>*</nobr>资料描述：</span>
                <textarea type="text" id="SectionDataDesc" class="intBig height-120"></textarea>
            </div>
            <div class="box-input-cen upDownBox clearfix">
                <span class="label bigTitle"><nobr>*</nobr>上传资料：</span>
                <div id="addDatas" class="bigInput"></div>
                <input type="text" id="SectionDataNews" hidden disabled="disabled" value="">
            </div>
            <p class="adderrormsg"></p>
            <div class="btnBox">
                <a href="javascript:;" class="publicOk " id="addDataOK">确定</a>
            </div>
        </div>
    </form>
</div>
<!--选择实验资料-->
<div class="popUpset animated" id="selectDataBox">
    <div class="popTitle">
        <p>实验资料</p>
        <a href="javascript:;" id="" class="close close-3"></a><!--如果只有一层弹窗，调用close-1-->
    </div>
    <div class="infoBox">
        <div class="box-margin-cen popSearch clearfix">
            <h3 class="titleLook">已选：<span>0</span>个</h3>
            <div class="goSearch">
                <input type="text" id="sapSearch_dataPage" class="" value="" name="Search" placeholder="请输入关键字搜索">
                <i class="fa fa-search toolsearch"></i>
            </div>
        </div>
        <div class="box-margin-cen popTable">
            <table>
                <thead>
                <tr class="table-title">
                    <td width="60">选中</td>
                    <td width="220">名称</td>
                    <td >路径</td>

                </tr>
                </thead>
                <tbody id=""></tbody>
                <tbody id="dataTable">

                </tbody>
            </table>
        </div>
        <div class="noNews" >
            <i class="fa fa-file-text" aria-hidden="true"></i><span>没有找到数据......</span>
        </div>
        <div id="dataPage" class="page popPage"></div>
        <script type="text/javascript">
            showSelfAjaxPagination('dataPage', site_url+'/Adminsubject/datumlist', "sapSucData");
        </script>

        <div class="btnBox popBtn">
            <a href="javascript:;" class="publicOk " id="goToData">确定</a>
            <a href="javascript:;" class="publicNo hidePop-3" id="">取消</a>
        </div>
    </div>
</div>
<!--新增题目-->
<div class="popUpset animated addquestionBox" id="addquestionBox"   >
    <form action="" method="post">
        <div class="popTitle">
            <p>新增题目</p>
            <a href="javascript:;" id="closeSelectQues" class="close"></a><!--如果只有一层弹窗，调用close-1-->
        </div>
        <div class="infoBox height-550">
            <div class="box-input-cen clearfix clearAcheck1" id="relationscene">
                <span class="bigTitle">关联场景：</span>
                <a href="javascript:;" id="noCJbtn" class="bQian checkNow" typect = '0'>不关联场景 </a>
                <a href="javascript:;" id="" class="bQian selectCtfButton" typect = '1'>关联CTF场景 </a>
                <a href="javascript:;" id="" class="bQian selectSceneButton" typect="2">关联实验场景 </a>

            </div>
            <div class="box-input-cen outHide clearfix" id="ctfOrSec">
                <span class="bigTitle">已关联场景：</span>
                <input type="text" id="changjingname" class="intBig" disabled="true">
                <input type="hidden" id="changjingcode" value="">

            </div>
            <div class="box-input-cen clearfix clearAcheck2" id="questiontype">
                <span class="bigTitle">题目类型：</span>
                <a  href="javascript:;" id="" class="bQian checkNow" type="1">单选题</a>
                <a  href="javascript:;" id="" class="bQian" type="2">多选题</a>
                <a  href="javascript:;" id="" class="bQian" type="3">判断题</a>
                <a  href="javascript:;" id="" class="bQian" type="4">填空题</a>
                <a  href="javascript:;" id="" class="bQian" type="5">夺旗题</a>
            </div>
            <div class="box-input-cen clearfix clearAcheck3" id = 'timunandu'>
                <span class="bigTitle">题目难度：</span>
                <a  href="javascript:;" id="" class="bQian checkNow">初级</a>
                <a  href="javascript:;" id="" class="bQian">中级</a>
                <a  href="javascript:;" id="" class="bQian">高级</a>
            </div>
            <div class="box-input-cen clearfix">
                <span class="bigTitle">题干：</span>
                <textarea type="text" id="PackageName" class="intBig height-120"></textarea>
                <p class="daoNews daoNews2"><nobr>*</nobr>&nbsp;题干若插入资料，请保证资料输入的格式：![资料名字](资料链接)。<br/>&nbsp;&nbsp;例子：![1.png](../../resources/files/question/1476335694000.png)<i class="fa fa-close"></i></p>
                    <i class="fa fa-question newStarBtn newStarBtn2"></i> 
            </div>
            <div class="box-input-cen clearfix" >
                <span class="bigTitle" id="quesTxtT">答案：</span>
                <div id ='xuantiBox'>
                    <!--单选-->
                    <div class="anserBox">
                        <div class="clearfix">
                            <input type="radio"  name="radiodan">
                            <input type="text" id="" name="danxuan" class="intSmall">
                            <a href ="javascript:;" onclick="addthis(this)"><i class="fa fa-plus"></i></a>
                        </div>
                    </div>
                    <!--多选-->
                    <div class="anserBox outHide">
                        <div class="clearfix">
                            <input type="checkbox" name="checkboxduo">
                            <input type="text" id="" class="intSmall" name="duoxuan">
                            <a href ="javascript:;" onclick="addthis(this)"><i class="fa fa-plus"></i></a>
                        </div>
                    </div>
                    <!--判断-->
                    <div class="anserBox outHide">
                        <div class="clearfix">
                            <input name="radiopanduan" class="rad" value="对" type="radio"><span class="danX">对</span>
                            <input name="radiopanduan" class="rad" value="错" type="radio"><span class="danX">错</span>
                        </div>
                    </div>
                    <!--填空题-->
                    <div class="anserBox outHide">
                        <div class="clearfix">
                            <input type="text" id="" class="intSmall" name="tiankong" >
                            <!-- <a href ="javascript:;" onclick="addthis(this)" name="tiankong" ><i class="fa fa-plus"></i></a> -->
                        </div>

                    </div>

                    <!--夺旗题-->

                    <div class="anserBox outHide" >
                        <div class="clearfix">
                            <input type="text" id="" name="flag" class="intSmall">
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-input-cen upDownBox clearfix">
                <span class="label bigTitle">上传资料：</span>
                <input type="hidden" value="" class="uploadIpt" id="uploadfile" disabled="true"　readOnly="true"/>
                <div id="uploadQuestion" class="bigInput">
                </div>
            </div>
            <div class="box-input-cen popTable clearfix">
                <span class="label bigTitle">附件列表：</span>
                <table >
                    <thead>
                    <tr class="table-title">
                        <td width="120">附件名称</td>
                        <td >URL</td>
                        <td width="125" >操作</td>

                    </tr>
                    </thead>
                    <tbody id=""></tbody>
                    <tbody id="addquesTable">

                    </tbody>
                </table>

            </div>
            <p class="adderrormsg"></p>
            <div class="btnBox">
                <a href="javascript:;" class="publicOk " id="saveQueList">保存</a>
                <a href="javascript:;" class="publicNo hidePop-1" id="">返回</a>
            </div>
        </div>
    </form>
</div>
<!--选择题目-->
<div class="popUpset animated" id="selectQuesBox">
    <div class="popTitle">
        <p>选择题目</p>
        <a href="javascript:;" id="" class="close close-3"></a><!--如果只有一层弹窗，调用close-1-->
    </div>
    <div class="infoBox">
        <div class="box-margin-cen popSearch clearfix">
            <h3 class="titleLook">已选：<span>0</span>道</h3>
            <div class="goSearch">
                <input type="text" id="sapSearch_choseQuesPage" class="" value="" name="Search" placeholder="请输入关键字搜索">
                <i class="fa fa-search quesearch"></i>
            </div>
        </div>
        <div class="box-margin-cen popTable">
            <table>
                <thead>
                <tr class="table-title">
                    <td width="60">选中</td>
                    <td width="320">题目</td>
                    <td width="90">出题人</td>
                    <td>类型</td>

                </tr>
                </thead>
                <tbody id=""></tbody>
                <tbody id="questionTable">

                </tbody>
            </table>
        </div>
        <div class="noNews" >
            <i class="fa fa-file-text" aria-hidden="true"></i><span>没有找到数据......</span>
        </div>
        <div id="choseQuesPage" class="page popPage"></div>
        <script type="text/javascript">
            showSelfAjaxPagination('choseQuesPage', site_url+'/Adminsubject/all_question', "sapSucQuesList");
        </script>

        <div class="btnBox popBtn">
            <a href="javascript:;" class="publicOk " id="questionOk">确定</a>
            <a href="javascript:;" class="publicNo hidePop-3" id="">取消</a>
        </div>
    </div>
</div>
<!--选择CTF列表-->
<div class="popUpset animated" id="ctfListBox" >
    <div class="popTitle">
        <p>选择CTF列表</p>
        <a href="javascript:;" id="" class="close newClose1"></a><!--如果只有一层弹窗，调用close-1-->
    </div>
    <div class="infoBox">
        <div class="box-margin-cen popSearch noBoederS clearfix">
            <div class="goSearch">
                <input type="text" id="sapSearch_ctfPage" name="Search" placeholder="请输入关键字搜索">
                <i class="fa fa-search ctfsearch"></i>
            </div>
        </div>
        <div class="box-margin-cen popTable downSearch">
            <table>
                <thead>
                <tr class="table-title">
                    <td width="220">CTF模板</td>
                    <td >CTF内容</td>
                    <td width="60">选中</td>
                </tr>
                </thead>
                <tbody id=""></tbody>
                <tbody id="ctTble">

                </tbody>
            </table>

        </div>
        <!--无数据提醒-->
        <div class="noNews" >
            <i class="fa fa-file-text" aria-hidden="true"></i><span>没有找到数据......</span>
        </div>
        <div id="ctfPage" class="page popPage"></div>
        <script type="text/javascript">
            showSelfAjaxPagination('ctfPage', site_url+'/Adminsubject/ctflist', "sapSucCtf");
        </script>

        <div class="btnBox popBtn">
            <a href="javascript:;" class="publicNo  newClose1" id="">取消</a>
        </div>
    </div>
</div>
<!--选择场景列表-->
<div class="popUpset animated" id="seceListBox">
    <div class="popTitle">
        <p>选择场景列表</p>
        <a href="javascript:;" id="" class="close newClose2"></a><!--如果只有一层弹窗，调用close-1-->
    </div>
    <div class="infoBox">
        <div class="box-margin-cen popSearch noBoederS clearfix">
            <div class="goSearch">
                <input type="text" id="sapSearch_secePage" class="" value="" name="Search" placeholder="请输入关键字搜索">
                <i class="fa fa-search sceneSearch"></i>
            </div>
        </div>
        <div class="box-margin-cen popTable downSearch">
            <table>
                <thead>
                <tr class="table-title">
                    <td width="220">场景模板</td>
                    <td >场景内容</td>
                    <td width="60">选中</td>
                </tr>
                </thead>
                <tbody id=""></tbody>
                <tbody id="seTable">

                </tbody>
            </table>
        </div>

        <div class="noNews">
            <i class="fa fa-file-text" aria-hidden="true"></i><span>没有找到数据......</span>
        </div>

        <div id="secePage" class="page popPage"></div>
        <script type="text/javascript">
            showSelfAjaxPagination('secePage', site_url+'/Adminsubject/scenelist', "sapSucScene");
        </script>

        <div class="btnBox popBtn">
            <a href="javascript:;" class="publicNo newClose2" id="">取消</a>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/prompt.js"></script>
<script type="text/javascript">

</script>
</body>
</html>