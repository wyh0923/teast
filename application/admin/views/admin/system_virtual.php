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
    <link href="<?php echo base_url()?>resources/css/public/animate.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/firstStart.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/content.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/filter.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/admin/virtualmanage.css" rel="stylesheet" type="text/css">

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

            <!-- 自己的内容放在此处 -->
            <div class="virtualRight">
                <!--dl-->
                <div class="xuangxiang_two xuanxiang noCursor">
                    <dl>
                        <dt><img src="<?php echo base_url() ?>resources/imgs/admin/LAYERS.png" alt=""></dt>
                        <dd>计算节点：<span><?php echo $resource['node_count'];?></span></dd>
                    </dl>
                    <dl>
                        <dt><img src="<?php echo base_url() ?>resources/imgs/admin/PEN-3.png" alt=""></dt>
                        <dd>虚拟机模板数量：<span><?php echo $resource['vm_tpl_count'];?></span></dd>
                    </dl>
                    <dl>
                        <dt><img src="<?php echo base_url() ?>resources/imgs/admin/USER.png" alt=""></dt>
                        <dd>活动场景：<span><?php echo $resource['vm_run_count'];?></span></dd>
                    </dl>
                    <dl>
                        <dt><img src="<?php echo base_url() ?>resources/imgs/admin/USERS.png" alt=""></dt>
                        <dd>历史使用场景：<span><?php echo $resource['history_vm_count'];?></span></dd>
                    </dl>
                </div>
                <!--table-->
                <div>
                    <div class="search clearfix">
                        <h5>当前平台中的活动虚拟机</h5>
                        <div class="virJiLu" >
                                <span class="">共<span class="max-page-count">0</span><span class="marCf">页</span>共<span class="max-row-count">0</span>条记录
                                </span>
                        </div>
                        <div class="virPages">
                            <div class="juBuPage">
                                <p class="prev"></p>
                                <ul>
                                    <!--<li class="clicked">1</li>-->
                                </ul>
                                <p class="next"></p>
                            </div>
                        </div>
                        <p class="searchGo"><input type="text" class="search_input" placeholder="请输入关键字搜索"><a class="search_btn" href="javascript:;"><i class="fa fa-search "></i></a></p>
                    </div>

                    <table class="virtuaTable" >
                        <thead>
                        <tr class="table-title">
                            <th width="170">虚拟机名</th>
                            <th width="160">所属场景</th>
                            <th width="170">创建时间</th>
                            <th width="140">状态</th>
                            <th >操作</th>
                        </tr>
                        </thead>
                        <tbody id="vmList">
                        <tr hid="{%host_id%}" uuid="{%vm_ins_uuid%}" status="{%vm_ins_status%}">
                            <td title="{%vm-name-title%}">{%vm_name%}</td>
                            <td title="{%scene_name_title%}">{%scene_name%}</td>
                            <td>{%create-date%}<br/>{%create-time%}</td>
                            <td>{%vm_ins_status_C%}</td>
                            <td>
                                <span class="vm-manage start" ><img src="<?php echo base_url() ?>resources/imgs/admin/CIRCLE---PLAY.png" title="开启"></span>
                                <span class="vm-manage resume" ><img src="<?php echo base_url() ?>resources/imgs/admin/CIRCLE.png" title="恢复"></span>
                                <span class="vm-manage reboot reboot{%host_id%}" ><img src="<?php echo base_url() ?>resources/imgs/admin/godown.png" title="重启"></span>
                                <span class="vm-manage suspend" ><img src="<?php echo base_url() ?>resources/imgs/admin/CIRCLE---PAUSE.png" title="暂停"></span>
                                <!--<span class="vm-manage shutdown" ><img src="<?php /*echo base_url() */?>resources/imgs/admin/zj_03.png" title="关机"></span>-->
                            </td>
                        </tr>
                        </tbody>
                    </table>


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
<!--操作确认-->
<div class="popUpset animated " id="HintBox" >
    <form action="" method="post">
        <div class="popTitle">
            <p>确认操作</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox">
            <p class="promptNews">信息</p><!--调用promptUp类大型提示框-->
            <div class="btnBox">
                <a href="javascript:;" class="publicOk" id="hintBtn">确定</a>
                <a href="javascript:;" class="publicNo hidePop-1" id="">取消</a><!--如果是子层弹窗，调用hidePop-2-->
            </div>
        </div>
    </form>
</div>
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
<script src="<?php echo base_url() ?>resources/js/public/prompt.js" type='text/javascript'></script>
<script type="text/javascript">
    var site_url = '<?php echo site_url() ?>';
</script>
<script src="<?php echo base_url() ?>resources/js/admin/HelpTemplate.js"></script>
<script src="<?php echo base_url() ?>resources/js/admin/system_virtual.js" type='text/javascript'></script>
</body>
</html>