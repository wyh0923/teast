<!DOCTYPE html>
<html>
<head>
    <title>系统升级日志</title>

    <meta charset="utf-8">
    <link rel="shortcut icon" href="<?php echo base_url() ?>resources/imgs/public/title.ico">
    <script type='text/javascript' src="<?php echo base_url() ?>resources/js/public/jquery-1.11.0.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/template.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/page.js"></script>
    <link href="<?php echo base_url() ?>resources/css/public/reset.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/thirdparty/font-awesome-4.5.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/firstStart.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/content.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/admin/systemconfig.css" rel="stylesheet" type="text/css">
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
                <h3> 升级日志</h3>

            </div>


            <table class="logList" id="logTable"><!--style="table-layout:fixed"-->
                <thead>
                <tr class="table-title">
                    <td width="160">时间</td>
                    <td width="180">旧版本号</td>
                    <td width="180">新版本号</td>
                    <td width="100">类型</td>
                    <td width="120">升级内容</td>
                    <td>进度情况</td>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($log_list as $val): ?>
                <tr>
                    <td><?php echo $val['created_time'];?></td>
                    <td ><?php echo  "V3.".date("YmdHis",strtotime($val['old_version']));?></td>
                    <td title=" "><?php echo  "V3.".date("YmdHis",strtotime($val['new_version']));?></td>
                    <td title=" "><?php echo $val['version_type']==2 ? '课件升级': '平台升级';?></td>
                    <td title="<?php echo $val['description'];?>"><?php echo $val['description'];?></td>
                    <td><?php if ($val['progress']!=100):?>
                            <a title="<?php echo $val['progress'];?>%"><span> <div class="proDiv"><div  class="pro" style="width:<?php echo $val['progress'];?>%"></div></div></span></a>
                        <?php else:?>
                            <span>完成</span>
                        <?php endif;?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>


            <?php if ($total_rows > 0):?>
                <div id="selfPage" class="page">

                    <script>
                        var pageurl = '<?=$page_url?>';
                        var pagepre = parseInt('<?=$page_pre?>');
                        var pagecount  = parseInt('<?=$page_count?>');
                        var numsize = 10;
                        pagetext = page(pagepre,pagecount,pageurl,numsize);
                        document.write(pagetext);
                    </script>

                </div>
            <?php else:?>
                <div class="noNews block">
                    <i class="fa fa-file-text" aria-hidden="true"></i><span>没有找到数据......</span>
                </div>
            <?php endif;?>
        </div>

        <!--right stop-->
    </div>


    <!--center stop-->
    <!--footer start-->
    <?php $this->load->view('public/footer.php')?>
    <!--footer stop-->
</div>
</body>
</html>