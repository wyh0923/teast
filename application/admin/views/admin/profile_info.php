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
    <link href="<?php echo base_url() ?>resources/css/public/animate.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/firstStart.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/content.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/personaldetails.css" rel="stylesheet" type="text/css">
    <script src="<?php echo base_url(); ?>resources/js/public/plupload.full.min.js"></script>
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
            <div class="personcontent">
                <h3 class="courseName">个人信息</h3>
                <div class="personImg">
                    <img class="per-img" src="<?php echo base_url();?>resources/files/picture/<?php echo $member['UserIcon'];?>" onerror="javascript:this.src='<?php echo base_url() ?>resources/imgs/public/<?php echo $this->default_icon;?>'">
                    <div class="fontsize"><?php echo $member['UserAccount'];?></div>
                    <button class="personbtn" id="browse">换头像</button>
                </div>
                <div class="personforms">
                    <div class="personinput">
                        <span>用户名：</span>
                        <input type="hidden" name="UserID" value="<?php echo $member['UserID'];?>">
                        <input class="ipt" type="text" readonly value="<?php echo $member['UserAccount'];?>" name="">
                    </div>
                    <div class="personinput">
                        <span>姓名：</span>
                        <input class="ipt" type="text" name="UserName" value="<?php echo $member['UserName'];?>" id="UserName">
                    </div>
                    <div class="personinput">
                        <span>性别：</span>
                        <input type="radio" <?php if($member['UserSex'] == '男'):?>checked<?php endif;?> value="男" name="UserSex">男
                        <input type="radio" <?php if($member['UserSex'] == '女'):?>checked<?php endif;?> value="女" name="UserSex">女
                    </div>
                    <div class="personinput">
                        <span>邮箱：</span>
                        <input class="ipt" type="text" name="UserEmail" value="<?php echo $member['UserEmail'];?>" id="UserEmail">
                    </div>
                    <div class="personinput">
                        <span>电话：</span>
                        <input class="ipt" type="text" name="UserPhone" value="<?php echo $member['UserPhone'];?>" id="UserPhone">
                    </div>
                    <p id="errorinfo" class="adderrormsg redinfo"></p>
                    <div class="btnBox smallBtn">
                        <a class="publicOk addinfo" href="javascript:;" id="luru">确定</a>
                        <a class="publicNo reset" href="javascript:;" id="">清除</a>
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
<div class="maskbox"></div>
<div class="popUpset animated " id="okBox"  >
    <form action="" method="post">
        <div class="popTitle">
            <p>提示操作</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox">
            <p class="promptNews promptUp"></p>

        </div>
    </form>
</div>
<script src="<?php echo base_url() ?>resources/js/public/prompt.js" type='text/javascript'></script>
<script type="text/javascript">
    var site_url = '<?php echo site_url(); ?>';
</script>
<script src="<?php echo base_url() ?>resources/js/admin/profile_info.js" type='text/javascript'></script>
</body>
</html>