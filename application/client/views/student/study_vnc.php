<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo $sectionname; ?></title>
    <link rel="shortcut icon" href="<?php echo base_url() ?>resources/imgs/public/title.ico">
    <link href="<?php echo base_url() ?>resources/css/public/animate.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/reset.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>resources/include/base.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/include/vnc_base.css"  rel="stylesheet" type="text/css">

    <script src="<?php echo base_url(); ?>resources/js/public/com.red.serial.js"></script>
    <script src="<?php echo base_url(); ?>resources/include/jquery1.8.3.min.js"></script>
    <script src="<?php echo base_url(); ?>resources/include/util.js"></script>
    <script src="<?php echo base_url(); ?>resources/include/vnc_base.js"></script>
</head>
<script type="text/javascript">
    var site_url = '<?php echo site_url(); ?>';
    var base_url = '<?php echo base_url(); ?>';
    var instanceuuid = '<?php echo $uuid; ?>';
    var sid = '<?php echo $sid; ?>';
    var host_id = "<?php echo $host_id; ?>";
    var scene_time = "<?php echo $scene_time; ?>";
    
    var host = "<?php echo $ip; ?>";
    var port = "<?php echo $port; ?>";
    var token = "<?php echo $token ?>";
    var user = "<?php echo $loguser; ?>";
    var pass = "<?php echo $logpwd; ?>";
</script>
<body>
<div id="noVNC_screen">
    <div class="header-content" id="top-toolbar">
        <div class="header" >
            <a class="exp-logo" href="#">
                <img src="<?php echo base_url() ?>resources/imgs/public/logo.png">
            </a>
            <div id="noVNC_status_bar" class="noVNC_status_normal" >
                <table width="100%"><tbody><tr>
                        <td><div id="noVNC_status"></div></td>
                        <td><div id="noVNC_buttons" class="noVNC_buttons">
                                <input class="sendCtrlAltDelButton"  value="快捷键(Ctrl + Alt + Del)" id="sendCtrlAltDelButton" type="button">
                            <span id="noVNC_xvp_buttons">
                                <input value="Shutdown" id="xvpShutdownButton" type="button" style="display:none;">
                                <input class="exp-btn btn-retvm" value="重启" id="xvpRebootButton" type="button">
                                <input class="exp-btn btn-retexp" value="重新实验" id="xvpResetButton" type="button">
                            </span>
                                <input class="gameover" value="结束实验" id="gameover" style=";" type="button">
                                <input class="gameReload" value="刷新" id="gameReload" style=";" type="button">
                                <input class="gameHelp" value="帮助" id="gameHelp" type="button">
                            </div></td>
                    </tr></tbody></table>
            </div>
            <p class='sneceTime'>
                <span>剩余时间：</span>
                <span id="numberXs" class="timeBg"></span>
                <span>时</span>
                <span id="numberFs" class="timeBg"></span>
                <span>分</span>
                <span id="numberMs" class="timeBg"></span>
                <span>秒</span>
            </p>
            <div id="btn-minify-top xvpResetButton" class="btn-minify-top" title="全屏"></div>
            <div class="vnc-msg"></div>
            <div class="vnc_help" id="helpBox" >
                <span class="helpTil">用户名：</span>
                <span id="helpName" class="mar20"><?php echo $loguser;?></span>
                <span class="helpTil">密码：</span>
                <span id="helpKey" class="mar20"><?php echo $logpwd;?></span>
                <span class="helpTil">工具库IP：</span>
                <span id="helpIP" >http://172.16.4.2/tools</span>

            </div>





        </div>
    </div>
    <div class="header-content-back" id="top-toolbar-back"></div>
    <canvas class="canvas" style="width: 1024px; height: 768px;" id="noVNC_canvas" width="1024" height="768" >
        Canvas not supported.
    </canvas>
    <div class="header-content-hand" id="top-toolbar-hand">::::</div>

</div>
<!-- 提示信息 -->
<div class="maskbox"></div>
<!--结束提示弹窗-->
<div class="popUpset animated " id="endBox">
    <form action="" method="post">
        <div class="popTitle">
            <p>提示操作</p>
            <a href="javascript:;" class="close close-1"></a>
        </div>
        <div class="infoBox">
            <p class="promptNews">确定要结束该实验吗?</p>
            <div class="btnBox">
                <a href="javascript:;" class="publicOk " id="endBtn">确定</a>
                <a href="javascript:;" class="publicNo hidePop-1">取消</a>
            </div>
        </div>
    </form>
</div>

<!-- 提示信息 -->
<div class="popUpset animated" id="okBox" >
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
<!-- 不存在提示信息 -->
<div class="popUpset animated" id="noBox" >
    <form action="" method="post">
        <div class="popTitle">
            <p>提示操作</p>
            <a href="javascript:;" class="close" id="noBtn"></a>
        </div>
        <div class="infoBox">
            <p class="promptNews promptUp"></p>
        </div>
    </form>
</div>
</body>
</html>
<script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/prompt.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>resources/js/student/vnc.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>resources/js/student/study_vnc.js"></script>