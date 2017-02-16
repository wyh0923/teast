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
    <link href="<?php echo base_url() ?>resources/css/admin/license.css" rel="stylesheet" type="text/css">

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
            <div class="select_right">
                <h5 class="lable_title">
                    <a href="" class="for_lable">系统管理</a>&gt;
                    <a>授权查询</a>
                </h5>
                <div class="select_info">
                    <div class="form">
                        <p><span>平台版本 :</span><input type="text" disabled="false" value="<?php echo $PlantformVersion;?>"></p>
                        <p><span>授权时间 :</span><input type="text" disabled="false" value="<?php echo $AuthorizeTime;?>"></p>
                        <p><span>授权期限 :</span><input type="text" disabled="false" value="<?php echo $LicenseTime;?>"></p>
                    </div>
                    <div class="select_table">
                        <h4>授权功能列表</h4>
                        <table>
                            <tr class="table-title">
                                <td width="350">功能模块</td>
                                <td width="150">是否授权</td>
                            </tr>
                            <tr>
                                <td>ctf实验模块</td>
                                <td>是</td>
                            </tr>
                            <tr>
                                <td>网络实验模块</td>
                                <td>是</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--right stop-->
    </div>
    <?php $this->load->view('public/footer.php')?>
</div>
<!--center stop-->
<!--footer start-->

<!--footer stop-->
</body>
</html>