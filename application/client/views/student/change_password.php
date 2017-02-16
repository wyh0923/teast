<!DOCTYPE html>
<html>
<head>
    <title>个人统计中心-修改密码</title>
    <meta charset="utf-8">
    <link rel="shortcut icon" href="<?php echo base_url() ?>resources/imgs/public/title.ico">
    <script type='text/javascript' src="<?php echo base_url() ?>resources/js/public/jquery-1.11.0.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/template.js"></script>
    <link href="<?php echo base_url() ?>resources/css/public/reset.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/thirdparty/font-awesome-4.5.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/animate.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/firstStart.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/content.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/personaldetails.css" rel="stylesheet" type="text/css">
    <script type='text/javascript' src="<?php echo base_url() ?>resources/js/student/changepassword.js"></script>
</head>
<script type="text/javascript">
    var site_url = '<?php echo site_url();?>';
</script>
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
            <div class="personcontent">
                <h3 class="courseName">修改密码</h3>
                <div class="personforms keyMargin">
                    <div class="personinput">

                        <input type="password" class="outHide">
                        <span>当前密码：</span>
                        <input class="ipt" value="" name="nowpassword" type="password">
                    </div>
                    <div class="personinput">
                        <span>新密码：</span>
                        <input class="ipt" value="" name="newpasswordnoe" type="password">
                    </div>
                    <div class="personinput">
                        <span>确认密码：</span>
                        <input class="ipt" value="" name="newpasswordtwo" type="password">
                    </div>
                    <div class="adderrormsg" id="errorinfo"></div>
                    <div class="btnBox" id="">
                        <a class="publicOk" id="saveBtn" href="javascript:;">保存</a>
                        <a class="publicNo" id="clearBtn" href="javascript:;">清除</a>
                    </div>
                </div>
            </div>

        </div>
        <!--right stop-->
    </div>


    <!--center stop-->
    <!--footer start-->
    <?php $this->load->view('public/footer.php')?>
    <!--footer stop-->
</div>
<!--  提示  -->
<div class="maskbox"></div>
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