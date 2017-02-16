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
    <link href="<?php echo base_url() ?>resources/css/admin/dashboard.css" rel="stylesheet" type="text/css">

    <script src="<?php echo base_url() ?>resources/thirdparty/highcharts/js/highcharts.js"></script>

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

            <div class="right">
                <!--选项图标-->
                <div class="xuanxiang">
                    <dl><a href="<?php echo site_url('User/classes');?>" target="_blank">
                        <dt><img src="<?php echo base_url() ?>resources/imgs/admin/hicon-1.jpg" alt=""></dt>
                        <dd>班级数量 : <span><?php echo $summary['class'];?></span></dd></a>
                    </dl>
                    <dl><a href="<?php echo site_url('User/teacher');?>" target="_blank">
                        <dt><img src="<?php echo base_url() ?>resources/imgs/admin/hicon-2.jpg" alt=""></dt>
                        <dd>教员数量 : <span><?php echo $summary['tea'];?></span></dd></a>
                    </dl>
                    <dl><a href="<?php echo site_url('User/student');?>" target="_blank">
                        <dt><img src="<?php echo base_url() ?>resources/imgs/admin/hicon-3.jpg" alt=""></dt>
                        <dd>学员总数 : <span><?php echo $summary['stu'];?></span></dd></a>
                    </dl>
                    <dl><a href="<?php echo site_url('Adminsubject/mybook');?>" target="_blank">
                        <dt><img src="<?php echo base_url() ?>resources/imgs/admin/hicon-4.jpg" alt=""></dt>
                        <dd>课程总数 : <span><?php echo $summary['package'];?></span></dd></a>
                    </dl>
                    <dl><a href="<?php echo site_url('Adminsubject/mybook');?>" target="_blank">
                        <dt><img src="<?php echo base_url() ?>resources/imgs/admin/hicon-5.jpg" alt=""></dt>
                        <dd>实验总数 : <span><?php echo $summary['exp'];?></span></dd></a>
                    </dl>
                    <dl><a href="<?php echo site_url('Adminsubject/mybook');?>" target="_blank">
                        <dt><img src="<?php echo base_url() ?>resources/imgs/admin/hicon-6.jpg" alt=""></dt>
                        <dd>总课时 : <span><?php echo $summary['section'];?></span></dd></a>
                    </dl>
                </div>
                <!--考试任务进度统计-->
                <div class="table">
                    <div class="h-table">
                        <div>
                            <h3>节点状态</h3>
                            <table>

                                <tr class="h-btmborder">
                                    <th width="160">节点IP</th>
                                    <th width="140">节点状态</th>
                                    <th width="150">内存</th>
                                    <th width="230">CPU</th>
                                    <th width="150">硬盘</th>
                                </tr>
                                <tbody>
                                <?php foreach ($sub_node as $node): ?>
                                    <tr>
                                        <td>
                                            <span><?php echo $node['host_ip'];?></span>
                                        </td>
                                        <td class="node-state" nid="<?php echo $node['id'];?>">
                                            <span><?php if ($node['host_state'] == 1):?>已连接<?php else:?>超时<?php endif;?></span>
                                        </td>
                                        <td>
                                            <?php if ($node['host_state'] == 1):?>
                                                <p class="clearfix">
                                                    <span class="width70"><?php echo round($node['memory_total']/1000.0,1);?>G</span><span class="width30">总共:</span>
                                                </p>
                                                <p class="clearfix">
                                                    <span class="width70"><?php echo round($node['memory_used']/1000.0,1);?>G</span><span class="width30">使用:</span>
                                                </p>
                                                <p class="clearfix">
                                                    <span class="width70"><?php echo round($node['memory_free']/1000.0,1);?>G</span><span class="width30">剩余:</span>
                                                </p>
                                            <?php endif;?>
                                        </td>
                                        <td><span class="h-height-charts"></span></td>
                                        <td>
                                            <?php if ($node['host_state'] == 1):?>
                                                <p class="clearfix">
                                                    <span class="width70"><?php echo round($node['capacity_total']/1.0,1);?>G</span> <span class="width30">总共:</span>
                                                </p>
                                                <p class="clearfix">
                                                    <span class="width70"><?php echo round($node['capacity_used']/1.0,1);?>G</span><span class="width30">使用:</span>
                                                </p>
                                                <p class="clearfix">
                                                    <span class="width70"><?php echo round($node['capacity_free']/1.0,1);?>G</span><span class="width30">剩余:</span>
                                                </p>
                                            <?php endif;?>
                                        </td>
                                        <td></td>
                                    </tr>
                                <?php endforeach; ?>

                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
                <!--平台内各模块状态-->
                <div class="cLass h_cLass">
                    <h4>平台内各模块状态</h4>
                    <div class="table-module">
                        <table>
                            <thead>
                            <tr class="table-title">
                                <th width="20%">节点名称</th>
                                <th width="12%">CPU</th>
                                <th width="12%">内存</th>
                                <th width="36%">当前状态</th>
                                <th width="20%">操作</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php foreach ($sub_node as $node): ?>

                            <tr>
                                <td><?php if ($node['host_state'] == 1):?><?php echo $node['host_description'];?><?php endif;?></td>
                                <td><?php if ($node['host_state'] == 1):?><?php echo $node['cpu_count'];?>核<?php endif;?></td>
                                <td><?php if ($node['host_state'] == 1):?><?php echo $node['memory_total'];?>MB<?php endif;?></td>
                                <td class="module-state" nid="<?php echo $node['id'];?>"><?php if ($node['host_state'] == 1):?>已开机<?php else:?>连接超时<?php endif;?></td>
                                <td>
                                    <span class="h-restart" nid="<?php echo $node['id'];?>"></span>
                                    <span class="h-resbtn restart" nid="<?php echo $node['id'];?>">重启</span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            </tbody>


                        </table>
                    </div>
                </div>
                <!--当前服务器状态-->
                <!--登陆日志-->
                <div class="tongji">
                    <div class="leftTonj">
                        <h4>主节点服务器状态</h4>
                        <?php foreach ($sub_node as $node): ?>
                            <?php if ($node['host_type'] == 1):?>
                        <div class="h-stotal">
                            <span class="h-statusicon"></span>
                            <span class="h-servermemory"><?php echo round($node['capacity_total']/1.0,1);?>G</span>
                        </div>
                        <div class="h-sp">
                            <div class="h-soccupy"></div>
                            <span class="h-srate"><?php echo round(($node['capacity_used']/$node['capacity_total'])*100)?>%</span>
                        </div>
                        <div class="h-splate">已使用硬盘：<span class="h_use"><?php echo round($node['capacity_used']/1.0,1);?>G</span> 剩余：<span class="h-still"><?php echo round($node['capacity_free']/1.0,1);?>G</span> 共：<span class="h-all"><?php echo round($node['capacity_total']/1.0,1);?>G</span></div>
                        <div class="h-smomery">已使用内存：<span class="h-use"><?php echo round($node['memory_used']/1000.0,1);?>G</span> 剩余：<span class="h-still"><?php echo round($node['memory_free']/1000.0,1);?>G</span> 共：<span class="h-all"><?php echo round($node['memory_total']/1000.0,1);?>G</span></div>
                        <div class="h-scpu">已使用硬盘：<span class="h-use"><?php echo round(($node['capacity_used']/$node['capacity_total'])*100);?>%</span></div>
                            <?php endif;?>
                        <?php endforeach; ?>

                    </div>

                    <div class="rightTonj">
                        <h4>系统日志</h4>
                        <div class="event">
                            <table class="h-etable">
                                <thead>
                                <tr>
                                    <th width="20%">名称</th>
                                    <th width="20%">事件</th>
                                    <th width="20%">内容</th>
                                    <th>时间</th>
                                </tr>
                                </thead>
                                <tbody>


                                </tbody>
                            </table>
                        </div>
                        <div class="newsPage">
                            <div class="juBuPage">
                                <p class="prev"></p>
                                <ul>
                                    <li class="clicked">1</li>
                                    <li>2</li>
                                    <li>3</li>
                                </ul>

                                <p class="next"></p>
                            </div>
                        </div>
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
<!--重启确认-->
<div class="popUpset animated " id="restartBox">
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
<!--提示框-->
<div class="popUpset animated " id="okBox">
    <form action="" method="post">
        <div class="popTitle">
            <p>提示操作</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox">
            <p class="promptNews">提示信息</p>
            <!--<div class="btnBox">
                <a href="javascript:;" class="publicOk " id="">确定</a>
                <a href="javascript:;" class="publicNo hidePop-1" id="">取消</a>

            </div>-->
        </div>
    </form>
</div>
<script src="<?php echo base_url() ?>resources/js/public/prompt.js" type='text/javascript'></script>
<script type="text/javascript">
    var site_url = '<?php echo site_url() ?>';
    var cpuids = [];
    <?php foreach ($sub_node as $k => $node): ?>
    cpuids.push(<?php echo $node['id'];?>);
    <?php endforeach;?>
    var logtype = [];
    <?php foreach ($log_type as $k => $v): ?>
    logtype[<?php echo $k;?>] = "<?php echo $v;?>";
    <?php endforeach;?>

</script>
<script src="<?php echo base_url() ?>resources/js/admin/system_info.js" type='text/javascript'></script>
</body>
</html>