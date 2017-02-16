<!DOCTYPE html>
<html>
<head>
    <title><?php echo $this->title;?></title>

    <meta charset="utf-8">
    <link rel="shortcut icon" href="<?php echo base_url() ?>resources/imgs/public/title.ico">
    <script type='text/javascript' src="<?php echo base_url() ?>resources/js/public/jquery-1.11.0.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/template.js"></script>
    <link href="<?php echo base_url() ?>resources/css/public/reset.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/thirdparty/font-awesome-4.5.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/thirdparty/huploadify/css/Huploadify.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/firstStart.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/content.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/filter.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/admin/person.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/animate.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/thirdparty/self-ajax-pagination/css/self-ajax-pagination.css" type="text/css" rel="stylesheet"/>
    <script type="text/javascript" src="<?php echo base_url() ?>resources/thirdparty/self-ajax-pagination/js/self-ajax-pagination.js"></script>


    <script type="text/javascript" src="<?php echo base_url() ?>resources/thirdparty/huploadify/js/jquery.Huploadify.js "></script>



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
            <form action="" method="post" class="newClass">
                <div class="infoBox">
                    <div class="listBox">
                        <span>班级名称：</span>
                        <input name="examname" value="" class="ipt" type="text">
                    </div>

                    <div class="listBox" id="truetype">
                        <span>学员：</span>
                        <label class="cur selectuser" type='1'><i class="fa fa-user bgYellow"></i>选择学员</label>
                        <label class="importuser" type='2'><i class="fa fa-upload bgYellow"></i>导入学员</label>
                    </div>
                    <div class="listBox clearfix ">
                        <span class="subjectTitle">学员列表：</span>
                    </div>
                    <div class="listBox">

                        <table>
                            <thead>
                            <tr class="table-title">
                                <td class=""  width="100">学号</td>
                                <td class=""  width="100">姓名</td>
                                <td class=""  width="40">性别</td>
                                <td class=""  width="200">工作单位</td>
                                <td class="" width="120" >隶属班级</td>
                                <td class="" width="70">课程积分</td>
                                <td class="" >操作</td>
                            </tr>
                            
                            </thead>
                            <tbody id="noNewsRemind">
                                    <tr >
                                        <td colspan="7">
                                            <div class="noNews noNewShow">
                                                <i class="fa fa-file-text" aria-hidden="true"></i>
                                                <span>请加入学生数据......</span>
                                            </div>
                                        </td>
                            </tr>
                            </tbody>
                            <tbody id="quesList" tygo='1'>
                            </tbody>
                        </table>
                    </div>
                    <p id="adderrormsg"></p>

                    <div class="listBox btnBox">
                        <a class="publicOk" id="trueaddexam" >确定</a>
                        <a href="javascript:;" class="publicNo" id="exambackBtn" onclick="javascript:history.back(-1);">取消</a>
                    </div>
                </div>
            </form>


        </div>
        <!--right stop-->
    </div>


    <!--center stop-->
    <!--footer start-->
    <?php $this->load->view('public/footer.php')?>
    <!--footer stop-->
</div>
<!--弹窗-->
<div class="maskbox"></div>
<!--选择学员弹窗-->
<div class="popUpset animated " id="addclassuserBox">
        <div class="popTitle">
            <p>选择学员</p>
            <a href="javascript:;" id="deladdquestionBtn" class="close close-3"></a><!--如果只有一层弹窗，调用close-1-->
        </div>
        <div class="infoBox">
            <div class="box-margin-cen popSearch clearfix">
                <h3 class="titleLook">已选：<span>0</span>个</h3>
                <div class="goSearch">
                    <input type="text" id="sapSearch_pageContainer" class="question-exam" value="" name="Search" placeholder="请输入关键字搜索">
                    <i class="fa fa-search selectsearch"></i>
                </div>
            </div>
            <div class="box-margin-cen popTable">
                <table>
                    <thead>
                    <tr class="table-title">
                        <td width="60">选中</td>
                        <td width="100">姓名</td>
                        <td width="60">性别</td>
                        <td >工作单位</td>
                        <td width="150">隶属班级</td>
                        <td  width="60">课程积分</td>
                    </tr>
                    </thead>
                    <tbody id="Searchresult"></tbody>
                    <tbody id="ques"></tbody>
                </table>
                <div id="pageContainer">
                </div>
                <script type="text/javascript">
                    showSelfAjaxPagination('pageContainer', '<?php echo site_url() ?>'+'/User/all_user', "sapSuc");
                </script>



            </div>


            <div class="btnBox popBtn">
                <a href="javascript:;" onclick="okchecked('#quesList')" class="publicOk " id="okChecked">确定</a>
                <a href="javascript:;" class="publicNo hidePop-3" id="questionbackBtn">取消</a>
            </div>
        </div>
</div>
<!--导入学员-->
<div class="popUpset animated  clearfix top175" id="importclassuserBox">
    <form action="" method="post">
        <div class="popTitle">
            <p>导入学员</p>
            <a href="javascript:;" id="ttt" class="close" onClick="close1(this)"></a><!--如果只有一层弹窗，调用close-1-->
        </div>
        <div class="infoBox">
            <div class="box-margin-cen upDownBox">
                <span class="label">选择文件：</span>
                <div id="edituploadIcon" class="startUpBox">
                </div>
                <input type="text" hidden value="" id="uploadctf" disabled="true"　readOnly="true"/>
                <span><a href="<?php echo base_url(); ?>resources/files/tool/users.csv" class="btnImport">下载导入模板</a></span>
                <p class="uploadTip">*仅支持csv格式文件</p>


            </div>
            <div class="box-margin-cen popSearch clearfix">
                <h3 class="titleLook">已选：<span>0</span>个</h3>
                <div class="goSearch">
                    <input type="text" id="sapSearch_pageContainer1" class="imexam" value="" name="Search" placeholder="请输入学员姓名">
                    <i class="fa fa-search imseach"></i>
                </div>
            </div>
            <div class="box-margin-cen popTable">
                <table>
                    <thead>
                    <tr class="table-title">
                        <td width="60">选中</td>
                        <td width="60">学号</td>
                        <td width="60">用户名</td>
                        <td width="100">姓名</td>
                        <td width="60">性别</td>
                        <td width="60">邮箱</td>
                        <td >工作单位</td>
                        <td  width="60">电话</td>
                    </tr>
                    </thead>
                    <tbody id="importques"></tbody>
                </table>
                <div id="pageContainer1">
                </div>



            </div>


            <div class="btnBox popBtn">
                <a href="javascript:;" class="publicOk " onclick="okimportchecked('#quesList')" >确定</a>
                <a href="javascript:;" class="publicNo hidePop-1" id="importbackBtn">取消</a>
            </div>
        </div>
    </form>
</div>
<div class="popUpset animated " id="errortip">
    <form action="" method="post">
        <div class="popTitle">
            <p>提示操作</p>
            <a href="javascript:;" id="" class="close close-2"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox">
            <p class="promptNews">请上传文件后，添加学员</p>

            <div class="btnBox">
                <!--<a href="javascript:;" class="publicOk " id="">确定</a>
                <a href="javascript:;" class="publicNo hidePop-1" id="">取消</a>--><!--如果是子层弹窗，调用hidePop-2-->
            </div>
        </div>
    </form>
</div>
<div class="popUpset animated " id="errortip_down">
    <form action="" method="post">
        <div class="popTitle">
            <p>提示操作</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox">
            <p class="promptNews">请上传文件后，添加学员</p>

            <div class="btnBox">
                <!--<a href="javascript:;" class="publicOk " id="">确定</a>
                <a href="javascript:;" class="publicNo hidePop-1" id="">取消</a>--><!--如果是子层弹窗，调用hidePop-2-->
            </div>
        </div>
    </form>
</div>
<script src="<?php echo base_url() ?>resources/js/public/prompt.js" type='text/javascript'></script>
<script type="text/javascript">
    var site_url = '<?php echo site_url() ?>';
    var base_url = '<?php echo base_url() ?>';
</script>
<script src="<?php echo base_url() ?>resources/js/admin/add_class.js" type='text/javascript'></script>
</body>
</html>