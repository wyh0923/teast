<!DOCTYPE html>
<html>
<head>
    <title>登录</title>

    <meta charset="utf-8">
    <link rel="shortcut icon" href="<?php echo base_url() ?>resources/imgs/public/title.ico">
    <link href="<?php echo base_url() ?>resources/css/public/reset.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/thirdparty/font-awesome-4.5.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/firstStart.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/content.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/login.css" rel="stylesheet" type="text/css">
    <script src="<?php echo base_url() ?>resources/js/public/jquery-1.11.0.js"></script>
    <script src="<?php echo base_url() ?>resources/js/public/login.js"></script>
	<meta name="renderer" content="webkit">
</head>
<body>
<!--header start-->
<div class="header">
    <div class="headerbox clearfix">
        <div class="headerlogobox clearfix"  onclick="">
            <a class="headerlogo" href="<?php echo base_url() ?>" id="headerlogo"><img src="<?php echo base_url() ?>resources/imgs/public/logo.png" ></a>
            <p><?php echo config_item ( 'webtitle');?></p>
        </div>


    </div>
</div>
<!--header stop-->

<div class="frame">
    <div class="main clearfix">
        <div id="loginDiv" class="login-div">
            <div class="contents">
                <div class="logo-div-b">
                    <img class="logo" src="<?php echo base_url() ?>resources/imgs/public/login_logo.png">
                    <span class="title">网络安全实验室<br>培训系统</span>
                </div>
                <span class="userlogin"></span>
                <form id="loginform" action="<?php echo site_url('Login/login');?>" method="post">
                    <div class="usernameBox">
                        <span class="fa fa-user"></span>
                        <em class="SX"></em>
                        <input id="username" class="username" type="text" placeholder="请输入登录账号" name="username" maxlength="60" autocomplete="off">
                        <div class="errorMsg" align="left"><i class="fa fa-exclamation-circle"></i>请您输入账号</div>
                    </div>
                    <div class="userPasswordBox focusBor">
                        <i class="fa fa-lock tu"></i>
                        <em class="SX"></em>
                        <input id="userPassword" class="userPassword" type="password" placeholder="请输入登录密码" name="password" maxlength="60" autocomplete="off">
                        <div class="errorMsg" align="left"><i class="fa fa-exclamation-circle"></i>请您输入密码</div>
                    </div>

                    <div class="loginfoot">
                        <div class="auto"><input type="checkbox" name="AutoLogin" class="AutoLogin" value="1" id="AutoLogin"><label for="AutoLogin">自动登录</label></div>
                        <a href="" class="gpwd"></a>
                    </div>
                    <div class="errorMsgs"><?php
                        if(isset($msg)){ echo $msg;}
                        ?></div>
                    <button id="loginbtn" class="login-btn" type="submit">立即登录</button>

                </form>
            </div>
        </div>
    </div>


    <!--center stop-->
    <!--footer start-->
    <?php $this->load->view('public/footer.php')?>
    <!--footer stop-->
</div>
<div class="popUpset">


</div>
</body>
</html>