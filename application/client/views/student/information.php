<!DOCTYPE html>
<html>
<head>
    <title>个人统计中心-个人信息</title>
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

    <script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/plupload.full.min.js"></script>
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
                <h3 class="courseName">个人信息</h3>
                <div class="personImg">
                    <img class="per-img" src="<?php echo base_url();?>resources/files/picture/<?php echo $data[0]['UserIcon'];?>" onerror="javascript:this.src='<?php echo base_url() ?>resources/imgs/public/<?php echo $this->default_icon;?>'">
                    <div class="fontsize"><?php echo $data[0]['UserAccount'];?></div>
                    <button class="personbtn" id="browse">换头像</button>
                </div>
                <div class="personforms">
                    <div class="personinput">
                        <span>用户名：</span>
                        <input class="ipt" type="text" readonly value="<?php echo $data[0]['UserAccount'];?>" name="">
                    </div>
                    <div class="personinput">
                        <span><nobr>*</nobr>姓名：</span>
                        <input class="ipt" type="text" name="UserName" value="<?php echo $data[0]['UserName'];?>" id="UserName">
                    </div>
                    <div class="personinput">
                        <span>性别：</span>
                        <input type="radio" value="男" <?php if($data[0]['UserSex'] == '男'){echo 'checked';}?> name="UserSex">男
                        <input type="radio" value="女" <?php if($data[0]['UserSex'] == '女'){echo 'checked';}?> name="UserSex">女
                    </div>
                    <div class="personinput">
                        <span>邮箱：</span>
                        <input class="ipt" type="text" name="UserEmail" value="<?php echo $data[0]['UserEmail'];?>" id="UserEmail">
                    </div>
                    <div class="personinput">
                        <span>电话：</span>
                        <input class="ipt" type="text" name="UserPhone" value="<?php echo $data[0]['UserPhone'];?>" id="UserPhone">
                    </div>
                    <div id="errorinfo" class="adderrormsg"></div>
                    <div class="btnBox smallBtn">
                        <a class="publicOk addinfo" href="javascript:;">确定</a>
                        <a class="publicNo reset" href="javascript:;">清除</a>
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
<script type="text/javascript" src="<?php echo base_url() ?>resources/js/student/information.js"></script>