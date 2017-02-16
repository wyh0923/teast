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
    <link href="<?php echo base_url() ?>resources/css/public/firstStart.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/content.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/filter.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/admin/virtual.css" rel="stylesheet" type="text/css">

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
                <a>
                    编辑虚拟机模板
                </a>
            </div>
            <!--面包屑导航  end-->
            <!--title-->

            <h3 class="lable_h3">编辑虚拟机模板</h3>


            <div class="contentInner">

                <!--内存-->
                <div class="addItem clearfix" id="memery">
                    <span class="addTit">内存大小：</span>
                    <?php foreach ($memory_type as $k=>$v):?>
                        <label><span class="mkur <?php if ($k==$vm['memory_size']):?>tcur<?php endif;?>" value="<?php echo $k;?>"><?php echo $v;?></span></label>
                    <?php endforeach;?>
                </div>
                <!--操作系统-->
                <div class="addItem clearfix" id="system">
                    <span class="addTit">操作系统：</span>
                    <div class="filterList">
                        <?php foreach ($os_type as $row):?>
                            <label><span class="skur <?php if ($row['id']==$vm['os_type_id']):?>tcur<?php endif;?>" value="<?php echo $row['id'];?>"><?php echo $row['os_name'];?></span></label>
                        <?php endforeach;?>
                    </div>
                </div>
                <!--cpu-->
                <div class="addItem clearfix" id="cpu">
                    <span class="addTit">cpu：</span>
                    <?php foreach ($cpu_type as $k=>$v):?>
                        <label><span class="ckur <?php if ($k==$vm['cpu']):?>tcur<?php endif;?>" value="<?php echo $k;?>"><?php echo $v;?></span></label>
                    <?php endforeach;?>
                </div>
                <div class="addItem clearfix">
                    <span class="addTit"><nobr>*</nobr>模板名称：</span>
                    <input id="vmTplName" name="vmtplname" value="<?php echo $vm['vm_display_name']; ?>" class="vmtplname" type="text">
                </div>
                <div class="addItem clearfix">
                    <span class="addTit">登录账号：</span>
                    <input id="vmTplUserName" name="user_name" value="<?php echo $vm['user_name']; ?>" class="vmtplname" type="text">
                </div>
                <div class="addItem clearfix">
                    <span class="addTit">登录密码：</span>
                    <input id="vmTplPassword" name="vmtplname" value="<?php echo $vm['user_pwd']; ?>" class="vmtplname" type="text">
                </div>
                <div class="addItem clearfix">
                    <span class="addTit">快照名称：</span>
                    <input id="vmTplSnapName" name="vmtplname" value="<?php echo $vm['vm_tpl_snp_name']; ?>" class="vmtplname" type="text">
                </div>
                <div class="addItem clearfix">
                    <span class="addTit fl"><nobr>*</nobr>漏洞信息：</span>
                    <textarea id="vmTplLeak" name="" class="addTxt"><?php  echo $vm['description']; ?></textarea>
                </div>

                <!--隐藏域-->
                <input id="vmTplFileName" value="<?php  echo $vm['data_store_path']; ?>" type="hidden">
                <div class="addItem clearfix">
                    <span class="addTit fl">IP：</span>
                    <input type="text" id="docker_cmd" name="" value="<?php  echo $vm['docker_cmd']; ?>" class="vmtplname">
                </div>

                <!--外部显示-->
                <P class="adderrormsg" id="errorinfo"></P>
                <input type="hidden" id="vmTplDisk" value="<?php echo $vm['disk_size'];?>">

                <div class="addItem clearfix btnBox">
                    <a href="javascript:;" id="savequestion" class="publicOk">保存</a>
                    <a href="javascript:;"  class=" publicNo" id="back">返回</a>
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
    var vm_tpl_uuid = '<?php echo $vm['vm_tpl_uuid'];?>';
    var host_id = '<?php echo $vm['host_id'];?>';
</script>
<script src="<?php echo base_url() ?>resources/js/public/prompt.js" type='text/javascript'></script>
<script src="<?php echo base_url(); ?>resources/js/teacher/train_edit_vm.js" type='text/javascript'></script>
</body>
</html>