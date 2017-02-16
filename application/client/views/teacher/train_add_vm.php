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
    <link href="<?php echo base_url() ?>resources/css/admin/virtual.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="<?php echo base_url() ?>resources/thirdparty/huploadify/js/jquery.Huploadify.js"></script>

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
                <a href="<?php echo site_url('Train/ctflist');?>" title="实训内容管理" class="for_lable">实训内容管理</a>&gt;<a href="<?php echo site_url('Train/vmlist');?>" title="虚拟机模板管理" class="for_lable">虚拟机模板管理</a>&gt;
                <a>新增虚拟机模板</a>
            </div>
            <!--面包屑导航  end-->
            <!--title-->

            <h3 class="lable_h3">新增虚拟机模板</h3>


            <div class="contentInner">
                <p class="remindNews addItem">*如果虚拟机是linux系统,请删除 /etc/udev/rules.d/70-persistent-net.rules 文件</p>
                <!--虚拟机类型-->
                <div class="addItem clearfix" id="disk">
                    <span class="addTit width_20">虚拟机类型：</span>
                    <label><span class="tkur tcur" value="2">操作机</span></label>
                    <label><span class="tkur" value="3">目标机</span></label>
                </div>


                <!--内存-->
                <div class="addItem clearfix" id="memery">
                    <span class="addTit width_20">内存大小：</span>
                    <?php foreach ($memory_type as $k=>$v):?>
                        <label><span class="mkur" value="<?php echo $k;?>"><?php echo $v;?></span></label>
                    <?php endforeach;?>
                </div>
                <!--操作系统-->
                <div class="addItem clearfix" id="system">
                    <span class="addTit width_20">操作系统：</span>
                    <div class="filterList">
                        <?php foreach ($os_type as $row):?>
                            <label><span class="skur" value="<?php echo $row['id'];?>"><?php echo $row['os_name'];?></span></label>
                        <?php endforeach;?>
                    </div>
                </div>
                <!--cpu-->
                <div class="addItem clearfix" id="cpu">
                    <span class="addTit width_20">cpu：</span>
                    <?php foreach ($cpu_type as $k=>$v):?>
                        <label><span class="ckur" value="<?php echo $k;?>"><?php echo $v;?></span></label>
                    <?php endforeach;?>
                </div>
                <div class="addItem clearfix">
                    <span class="addTit width_20"><nobr>*</nobr>模板名称：</span>
                    <input id="vmTplName" name="vmtplname" value="" class="vmtplname" type="text">
                </div>
                <div class="addItem clearfix">
                    <span class="addTit width_20">登录账号：</span>
                    <input id="vmTplUserName" name="user_name" value="" class="vmtplname" type="text">
                </div>
                <div class="addItem clearfix">
                    <span class="addTit width_20">登录密码：</span>
                    <input id="vmTplPassword" name="vmtplname" value="" class="vmtplname" type="text">
                </div>
                <div class="addItem clearfix">
                    <span class="addTit width_20">快照名称：</span>
                    <input id="vmTplSnapName" name="vmsnap" value="" class="vmtplname" type="text">
                </div>
                <div class="addItem clearfix">
                    <span class="addTit width_20"><nobr>*</nobr>漏洞信息：</span>
                    <textarea id="vmTplLeak" name="vmtplleak" class="addTxt"></textarea>
                </div>

                <!--隐藏域-->
                <input id="vmTplFileName" value="" hidden="">
                <div class="addItem clearfix">
                    <span class="addTit width_20">IP：</span>
                    <input type="text" id="docker_cmd" name="docker_cmd" value="" class="vmtplname">
                </div>
                <!--上传附件-->
                <div class="addItem  clearfix" id="scfjdiv">
                    <span class="addTit width_20"><nobr>*</nobr>上传虚拟机(*.qcow2)：</span>
                    <div id="uploadVmBox" class="startUpBox bigInput">
                    </div>
                </div>
                <p class="adderrormsg" id="errorinfo"></p>

                <div class="addItem clearfix btnBox">
                    <a href="javascript:;" id="savequestion" class=" publicOk">保存</a>
                    <a href="javascript:;" onclick="javascript:history.back(-1);" class=" publicNo" id="">返回</a>
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
<script type="text/javascript">
    var site_url = '<?php echo site_url();?>';
    var targetDir = "<?php echo $upload_data['target_dir'];?>";
    var nodeId = "<?php echo $upload_data['node_id'];?>";
</script>
<script src="<?php echo base_url() ?>resources/js/public/prompt.js" type='text/javascript'></script>
<script src="<?php echo base_url(); ?>resources/js/teacher/train_add_vm.js" type='text/javascript'></script>
</body>
</html>