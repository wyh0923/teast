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
    <link href="<?php echo base_url() ?>resources/css/public/animate.min.css" type="text/css" rel="stylesheet"/>
    <link href="<?php echo base_url() ?>resources/css/public/firstStart.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/content.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/admin/systemconfig.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="<?php echo base_url() ?>resources/thirdparty/huploadify/js/jquery.Huploadify.js"></script>
    <link href="<?php echo base_url() ?>resources/thirdparty/huploadify/css/Huploadify.css" rel="stylesheet" type="text/css">

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
        <div class="content" >
            <!-- 自己的内容放在此处 -->
            <div class="sysRight">
                <div >
                    <h5>配置平台IP</h5>
                    <p>
                        <span>平台IP :</span>
                        <input id="MainNodeIp" host_type="1" host_id="<?php echo $main_node['id'];?>" type="text" value="<?php echo $sysinfo_ip;?>">
                    </p>
                    <p>
                        <span>平台网关 :</span>
                        <input id="MainNodeGateway" type="text" value="<?php echo $main_node['host_gateway'];?>">
                    </p>
                    <p>
                        <span>平台掩码 :</span>
                        <input type="text" id="MainNodeNetmask" value="<?php echo $main_node['host_netmask'];?>">
                    </p>
                    <p>
                        <span>验证用户名 :</span>
                        <input type="text" id="username">
                    </p>
                    <p>
                        <span>验证密码 :</span>
                        <input type="password" id="password">
                    </p>
                    <h6>警告：修改IP可能会导致系统不能连接，请谨慎操作。</h6>
                    <p id="ModifyIpInfo" class="adderrormsg"></p>
                    <h1 id="modifyPlantformIp" host_id="<?php echo $main_node['id'];?>">配置</h1>
                </div>
                <div>
                    <h5>配置靶机入口IP</h5>
                    <p>
                        <span>入口IP :</span>
                        <input type="text" id="routerIp" host_value="<?php echo $sub_node['root_router_ip'];?>" value="<?php echo $sub_node['root_router_ip'];?>">
                    </p>
                    <p>
                        <span>靶机网关 :</span>
                        <input type="text" id="routerGateway" host_value="<?php echo $sub_node['host_gateway'];?>" value="<?php echo $sub_node['host_gateway'];?>">
                    </p>
                    <p>
                        <span>子网掩码 :</span>
                        <input type="text" id="routerNetMask" host_value="<?php echo $sub_node['host_netmask'];?>" value="<?php echo $sub_node['host_netmask'];?>">
                    </p>
                    <p>
                        <span>验证用户名 :</span>
                        <input type="text" id="BJusername">
                    </p>
                    <p>
                        <span>验证密码 :</span>
                        <input type="password" id="BJpassword">
                    </p>
                    <h6>* 本IP修改后会影响所有CTF的靶机的访问地址</h6>
                    <p id="msgbox" class="adderrormsg"></p>
                    <h1 id="changeRootRouterIP" host_id="<?php echo $sub_node['id'];?>">配置</h1>
                </div>
                <div>
                    <h5>平台数据备份</h5>
                    <h3>将平台已有的数据内容备份到本地</h3>
                    <h1 class="backups">备份</h1>
                </div>
                <div>
                    <h5>恢复出厂设置</h5>
                    <h3>将平台已有的数据内容清空并恢复出厂设置</h3>
                    <h1 class="recovers">恢复</h1>
                </div>
                <div>
                    <h5>平台系统升级</h5>
                    <h3 class="height60">导入平台的升级包，对平台进行升级</h3>
                    <h6>版本号：<?php echo config_item ( 'platformSystem');?></h6>
                    <h1 class="plantform">升级</h1>
                </div>
                <div>
                    <h5>课件及实验升级</h5>
                    <h3 class="height60">导入课件的升级包，对课件和实验进行升级</h3>
                    <h6>版本号：<?php echo  "V3.".date("YmdHis",strtotime($NewCourseVersion));?></h6>
                    <h1 class="course">升级</h1>
                    <a class="bookLink" href="<?php echo site_url('System/upgrade_log');?>">查看升级日志</a>
                </div>
            </div>
            <!-- 如果需要分页，在此处引入 -->
        </div>

        <!--right stop-->
    </div>


    <!--center stop-->
    <!--footer start-->
    <?php $this->load->view('public/footer.php')?>
    <!--footer stop-->
</div>
<div class="maskbox"></div>
<!--确认提示-->
<div class="popUpset animated " id="okBox">
    <form action="" method="post">
        <div class="popTitle">
            <p>提示信息</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox">
            <p class="promptNews"></p><!--调用promptUp类大型提示框-->

        </div>
    </form>
</div>
<!--备份确认-->
<div class="popUpset animated " id="backupsBox">
    <form action="" method="post">
        <div class="popTitle">
            <p>确认操作</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox">
            <p class="promptNews">确定把已有的数据内容备份到本地吗？</p><!--调用promptUp类大型提示框-->
            <div class="btnBox">
                <a href="javascript:;" class="publicOk" id="backups">确定</a>
                <a href="javascript:;" class="publicNo hidePop-1" id="">取消</a><!--如果是子层弹窗，调用hidePop-2-->
            </div>
        </div>
    </form>
</div>
<!--恢复出厂设置-->
<div class="popUpset animated " id="" >
    <form action="" method="post">
        <div class="popTitle">
            <p>确认操作</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox">
            <p class="promptNews">将平台已有的数据内容清空并恢复出厂设置</p><!--调用promptUp类大型提示框-->
            <div class="btnBox">
                <a href="javascript:;" class="publicOk" id="">确定</a>
                <a href="javascript:;" class="publicNo hidePop-1" id="">取消</a><!--如果是子层弹窗，调用hidePop-2-->
            </div>
        </div>
    </form>
</div>
<!--添加平台系统升级SQL文件-->
<div class="popUpset animated " id="recoversBox" >
    <form action="" method="post">
        <div class="popTitle">
            <p>添加平台系统升级SQL文件</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox">
            <div class="box-input-cen upDownBox clearfix">
                <span class="label bigTitle">升级文件：</span>
                <div id="adduploadIcon"></div>
                <input type="text" hidden=""  value="" id="recoverfile" disabled="true"　readOnly="true"/>
            </div>
            <p id="recover_errorinfo" class="colorRed">* 警告：升级可能使系统不稳定，请谨慎操作。</p>
            <div class="box-input-cen  clearfix outHide" id = "div_progress">
                <div class="progress">
                    <div class="progress-inner" id="progress">

                    </div>
                    <div id="progress_span" class="txtNumber" ></div>
                </div>
            </div>
            <div class="btnBox">
                <a href="javascript:;" class="publicOk " id="recovers">恢复出厂设置</a>
                <a href="javascript:;" class="publicOk" id="savequestion">确认升级</a>
            </div>
        </div>
    </form>
</div>
<!--添加平台系统升级文件-->
<div class="popUpset animated " id="plantformBox">
    <form action="" method="post">
        <div class="popTitle">
            <p>添加平台系统升级文件</p>
            <a href="javascript:;" id="plantformclose" class="close"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox">
            <div class="box-input-cen clearfix" id="danxuan">
                <input type="radio" name="radiodan" code= 1 value = 'web_' checked="checked" class="xuanLei"><span class="danX">系统升级</span>
                <input type="radio" name='radiodan' code= 2 value = 'middleware_' class="xuanLei" ><span class="danX">场景服务（中间件）升级</span>
                <input type="radio" name='radiodan' code= 3 value = 'childnode_' class="xuanLei" ><span class="danX">虚拟化服务（子节点虚拟化）升级</span>

            </div>
            <div class="box-input-cen clearfix">
                <span class=" bigTitle">升级文件：</span>
                <div id="plantform_uploadIcon" class="bigInput"></div>
            </div>
            <p id="plantform_errorinfo" class="colorRed">* 警告：升级可能使系统不稳定，请谨慎操作。</p>
            <div class="box-input-cen  clearfix outHide" id = "div_progress">
                <div class="progress">
                    <div class="progress-inner" id="progress">

                    </div>
                    <div id="progress_span" class="txtNumber" ></div>
                </div>
            </div>
            <div class="btnBox">
                <a href="javascript:;" class="publicOk" id="plantformBtn">确认升级</a>
            </div>
        </div>
    </form>
</div>
<!--添加课件及实验升级文件-->
<div class="popUpset animated " id="courseBox" >
    <form action="" method="post">
        <div class="popTitle">
            <p>添加课件及实验升级文件</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox">
            <div class="box-input-cen upDownBox clearfix">
                <span class="label bigTitle">升级文件：</span>
                <div id="course_uploadIcon" class="bigInput"></div>
            </div>
            <p id="course_errorinfo" class="colorRed">* 警告：升级可能使系统不稳定，请谨慎操作。</p>
            <div class="box-input-cen  clearfix outHide" id = "div_progress">
                <div class="progress">
                    <div class="progress-inner" id="progress">

                    </div>
                    <div id="progress_span" class="txtNumber" ></div>
                </div>
            </div>

            <div class="btnBox">
                <a href="javascript:;" class="publicOk" id="courseBtn">确认升级</a>
            </div>
        </div>
    </form>
</div>

<!--存在进行中的实验提示框-->
<div class="popUpset animated " id="testNotice" >
    <form action="" method="post">
        <div class="popTitle">
            <p>确认操作</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox">
            <p class="promptNews">存在正在进行中的实验，是否现在导入？</p><!--调用promptUp类大型提示框-->
            <div class="btnBox">
                <a href="javascript:;" class="publicOk testBtn" id="">确定</a>
                <a href="javascript:;" class="publicNo hidePop-1" id="">取消</a><!--如果是子层弹窗，调用hidePop-2-->
            </div>
        </div>
    </form>
</div>

<!--网络连接失败提示框-->
<div class="popUpset animated " id="noticeBox" >
    <form action="" method="post">
        <div class="popTitle">
            <p>警告</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox">
            <p class="promptNews">请求失败,请检查网络！</p><!--调用promptUp类大型提示框-->
            <div class="btnBox">
                <a href="javascript:;" class="publicOk noticeBtn" id="">确定</a>
                <a href="javascript:;" class="publicNo hidePop-1" id="">取消</a><!--如果是子层弹窗，调用hidePop-2-->
            </div>
        </div>
    </form>
</div>
<script src="<?php echo base_url() ?>resources/js/public/prompt.js" type='text/javascript'></script>
<script type="text/javascript">
    var site_url = '<?php echo site_url() ?>';
    var bajiIP = "<?php echo $sub_node['root_router_ip'];?>";
    var ptIP = "<?php echo $sysinfo_ip;?>";
    //修改靶机 查询状态标示
    var task_uuid = '';
</script>
<script src="<?php echo base_url() ?>resources/js/admin/system_config.js" type='text/javascript'></script>
</body>
</html>