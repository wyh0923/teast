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
    <link href="<?php echo base_url() ?>resources/css/public/filter.css" rel="stylesheet" type="text/css">

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
            <div class="total clearfix">
                <h3 >共计:&nbsp;<?php echo $total_node;?>个</h3>
                <a href="javascript:;" id="addBtn" class="btnNew"><span>+</span>添加节点</a>
                <div class="search-a">
                    <input id="hostIp" type="text" class="iptSearch-a"  placeholder="请输入节点IP"
                           value="<?php echo $search;?>">
                    <i class="fa fa-search" ></i>
                </div>
            </div>
            <div class="cLass h_cLass">
                <div class="sever_table">
                    <table>
                        <tr class="h-bggreen table-title">
                            <th style="width:12%">节点名称</th>
                            <th style="width:16%">IP</th>
                            <th style="width:7%">CPU</th>
                            <th style="width:10%">内存</th>
                            <th style="width:10%">硬盘</th>
                            <th style="width:10%">当前状态</th>
                            <th class="h-norbder" style="width:25%">操作</th>
                        </tr>

                        <?php foreach ($sub_node as $node): ?>
                        <tr>
                            <td>
                                    <span>
                                    <?php echo $node['host_description'];?>
                                    </span>
                            </td>
                            <td>
                                    <span>
                                        <?php echo $node['host_ip'];?>
                                    </span>
                            </td>
                            <td>
                                    <span>
                                        <?php echo $node['cpu_count'];?>核
                                    </span>
                            </td>
                            <td>
                                    <span>
                                        <?php echo $node['memory_total'];?>MB
                                    </span>
                            </td>
                            <td>
                                    <span>
                                        <?php echo round($node['capacity_total']/1.0,1);?>GB
                                    </span>
                            </td>
                            <td class="module-state" >
                                        <?php if ($node['host_state'] == 1):?>已连接<?php else:?><span style='color:red'>超时</span><?php endif;?>

                            </td>
                            <td class="h-norbder">
                                <?php if($node['host_type'] != 1):?>
                                <span class='reconnectbtn host-reboot'></span>
                                <span class="h-resbtn break" nid="<?php echo $node['id'];?>">
                                        断开
                                    </span>
                                <?php endif;?>
                                <span class="restartbtn host-reboot"></span>
                                <span class="h-resbtn restart" nid="<?php echo $node['id'];?>">重启</span>
                                <span class="shutdownbtn host-reboot" ></span>
                                <span class="h-resbtn shutdown" nid="<?php echo $node['id'];?>">关机</span>
                            </td>
                        </tr>
                        <?php endforeach; ?>

                    </table>
                    <?php if ($total_node == 0):?>
                    <div class="noNews block">
                        <i class="fa fa-file-text" aria-hidden="true"></i><span style="margin-left:10px;">没有找到数据......</span>
                    </div>
                    <?php endif;?>

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
<!--删除确认-->
<div class="popUpset animated " id="delBox">
    <form action="" method="post">
        <div class="popTitle">
            <p>确认操作</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <input type="hidden" id="host_id" value="">
        <div class="infoBox">
            <p id="errors_del" class="promptNews">确定要删除该节点吗？</p>
            <div class="btnBox">
                <a href="javascript:;" class="publicOk deleteNodeBtn" id="offBtn">确定</a>
                <a href="javascript:;" class="publicNo hidePop-1" id="">取消</a><!--如果是子层弹窗，调用hidePop-2-->

            </div>
        </div>
    </form>
</div>
<!--提示-->
<div class="popUpset animated " id="okBox">
    <form action="" method="post">
        <div class="popTitle">
            <p>操作提示</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox">
            <p class="promptNews">正在添加节点</p>
        </div>
    </form>
</div>
<!--重启确认-->
<div class="popUpset animated " id="restartBox" >
    <form action="" method="post">
        <div class="popTitle">
            <p>确认操作</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <input type="hidden" id="restart_id" value="">
        <div class="infoBox">
            <p class="promptNews">确定要重启该节点吗？</p>
            <div class="btnBox">
                <a href="javascript:;" class="publicOk " id="restartBtn">确定</a>
                <a href="javascript:;" class="publicNo hidePop-1" id="">取消</a><!--如果是子层弹窗，调用hidePop-2-->

            </div>
        </div>
    </form>
</div>
<!--关机确认-->
<div class="popUpset animated " id="shutdownBox"  >
    <form action="" method="post">
        <div class="popTitle">
            <p>确认操作</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <input type="hidden" id="shutdown_id" value="">
        <div class="infoBox">
            <p class="promptNews">确定要关机该节点吗？</p>
            <div class="btnBox">
                <a href="javascript:;" class="publicOk " id="shutdownBtn">确定</a>
                <a href="javascript:;" class="publicNo hidePop-1" id="">取消</a><!--如果是子层弹窗，调用hidePop-2-->

            </div>
        </div>
    </form>
</div>
<!--添加节点-->
<div class="popUpset animated " id="addPopBoxadduser">
    <form action="" method="post">
        <div class="popTitle">
            <p>添加节点</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果只有一层弹窗，调用close-1-->
        </div>
        <div class="infoBox">
            <div class="inputPop inputPop470 clearfix">
                <span class="secongTitle bigInput160">节点名称：</span>
                <input id="" name="description"  value="" class="iptext" type="text">
            </div>
            <div class="inputPop inputPop470 clearfix">
                <span class="secongTitle bigInput160"><nobr>*</nobr>节点IP：</span>
                <input id="nodeIp" name="ip" value="" class="iptext" type="text">
            </div>
            <div class="inputPop inputPop470 clearfix">
                <span class="secongTitle bigInput160">子网掩码：</span>
                <input name="netmask" value="" class="iptext" type="text">
            </div>
            <div class="inputPop inputPop470 clearfix">
                <span class="secongTitle bigInput160"><nobr>*</nobr>服务端口：</span>
                <input class="iptext" name="interface_port"   value="5009" type="text" readonly disabled>
            </div>
            <div class="inputPop inputPop470 clearfix">
                <span class="secongTitle bigInput160"><nobr>*</nobr>远程桌面开放端口：</span>
                <input class="iptext"  name="vnc_server_port" value="6099" type="text" readonly disabled>
            </div>
            <p id="errors" class="adderrormsg"></p>
            <div class="btnBox">
                <a href="javascript:;" class="publicOk publicOk2" id="addHost" >确定</a>
                <a href="javascript:;" class="publicNo hidePop-1" id="">取消</a><!--如果只有一层弹窗，调用hidePop-1-->
            </div>
        </div>
    </form>
</div>
<script src="<?php echo base_url() ?>resources/js/public/prompt.js" type='text/javascript'></script>
<script type="text/javascript">
    var site_url = '<?php echo site_url() ?>';
</script>
<script src="<?php echo base_url() ?>resources/js/admin/system_server.js" type='text/javascript'></script>
</body>
</html>