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

    <link href="<?php echo base_url() ?>resources/css/admin/addTeacher_student.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/animate.min.css" rel="stylesheet" type="text/css">

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
            <div class="stepCon" >
                <div class="stepTab clearfix">
                    <a href="javascript:;" class="tabTitle cur"><i class="fa fa-user bgYellow"></i><span>录入教员</span></a>
                    <a href="javascript:;" class="tabTitle"><i class="fa fa-upload bgYellow"></i><span>导入教员</span></a>
                </div>
                <!--录入teacher strt-->
                <div class="tabCon">
                    <form action="" method="post">
                        <div class="infoBox">
    
                            <div class="addIptBox">
                                <input type="text" class="outHide"  >
                                <input type="password" class="outHide">
                                <span><nobr>*</nobr>姓名:</span>
                                <input type="text" name="name" value="" class="ipt"/>
                            </div>
                            <div class="addIptBox">
                                <span><nobr>*</nobr>用户名:</span>                            
                                <input type="text" name="account" value="" class="ipt"/>
                            </div>
                            <div class="addIptBox">
                                <span><nobr>*</nobr>密码:</span>
                                
                                <input type="password" name="password" value="" class="ipt"/>
                            </div>
                            <div class="addIptBox">
                                <span><nobr>*</nobr>确认密码:</span>
                                <input type="password" name="repassword" value="" class="ipt"/>
                            </div>
                            <div class="addIptBox">
                                <span>性别:</span>
                                <input name="sex" type="radio" value="男" checked="checked">男
                                <input name="sex" type="radio" value="女">女
                            </div>
                            <div class="addIptBox">
                                <span>单位:</span>
                                <input type="text" name="department" value="" class="ipt"/>
                            </div>
                            <div class="addIptBox">
                                <span>邮箱:</span>
                                <input type="text" value="" name="email" class="ipt"/>
                            </div>
                            <div class="addIptBox">
                                <span>电话:</span>
                                <input type="text" value="" name="phone" class="ipt"/>
                            </div>
                            <p class="adderrormsg" id="error"></p>

                            <div class="addIptBox btnBox">
                                <a href="javascript:;"  class="publicOk" id="luru">录入系统</a>
                                <a href="javascript:;" class="publicNo" id="continueluru">继续录入</a>
                            </div>
                        </div>
                    </form>

                </div>
                <!--录入teacher end-->


                <!--导入teacher start-->
                <div class="tabCon outHide" >
                    <form enctype="multipart/form-data" method="POST" id="fileform">
                        <div class="import upDownBox">
                            <span class="label">选择文件：</span>
                            <div id="edituploadIcon" class="startUpBox">
                            </div>
                            <input type="hidden"  value="" id="uploadctf" disabled="true"　readOnly="true"/>
                            <a href="<?php echo base_url(); ?>resources/files/tool/teacher.csv" class="btnImport">下载导入模板</a>
                            <p class="uploadTip">*仅支持csv格式文件</p>
                        </div>
                    </form>
                    <div class="list">
                        <form  method="POST" action="" id="addform">
                            <table class="studentList">
                                <thead>
                                <tr>
                                    <td width="50">选中</td>
                                    <td width="110">用户名</td>
                                    <td width="80">姓名</td>
                                    <td width="50">性别</td>
                                    <td width="200">邮箱</td>
                                    <td width="160">工作单位</td>
                                    <td >电话</td>
                                </tr>
                                </thead>
                                <tbody id="noNewsRemind">
                                    <tr >
                                        <td colspan="7">
                                            <div class="noNews noNewShow">
                                                <i class="fa fa-file-text" aria-hidden="true"></i>
                                                <span>请导入教员数据......</span>
                                            </div>
                                        </td>
                                     </tr>
                                </tbody>
                                <tbody id="Searchresult"></tbody>
                                <tbody id="ajaxusers"></tbody>
                                <tbody id="ajaxuser outHide"></tbody>

                            </table>

                            <div class="addIptBox btnBox">
                                <a href="javascript:;"  class="publicOk" id="inputAddBtn">录入系统</a>
                            </div>

                        </form>
                        <p id="adderrormsg"></p>
                    </div>

                </div>
                <!--导入学员 end-->
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
<div class="popUpset animated " id="errortip">
    <form action="" method="post">
        <div class="popTitle">
            <p>提示操作</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox">
            <p class="promptNews"></p>

            <div class="btnBox">
                <!--<a href="javascript:;" class="publicOk " id="">确定</a>
                <a href="javascript:;" class="publicNo hidePop-1" id="">取消</a>--><!--如果是子层弹窗，调用hidePop-2-->
            </div>
        </div>
    </form>
</div>
<div class="popUpset animated " id="okBox">
    <form action="" method="post">
        <div class="popTitle">
            <p>提示操作</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox">
            <p class="promptNews">在这里写提示信息</p>

           <!--  <div class="btnBox">
                <a href="<?php echo site_url('User/teacher');?>" class="publicOk " id="">确定</a>
                <a href="javascript:;" class="publicNo hidePop-1" id="">取消</a>--><!--如果是子层弹窗，调用hidePop-2
                需要按钮解除注释即可
            </div> -->
        </div>
    </form>
</div>
<script src="<?php echo base_url() ?>resources/js/public/prompt.js" type='text/javascript'></script>
<script>
    var site_url = '<?php echo site_url();?>';
    var base_url = '<?php echo base_url();?>';
</script>
<script src="<?php echo base_url() ?>resources/js/admin/add_teacher.js" type='text/javascript'></script>
</body>
</html>