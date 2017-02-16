<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>个人统计中心-学习日志</title>
    <link rel="shortcut icon" href="<?php echo base_url() ?>resources/imgs/public/title.ico">
    <!--公用样式-->
    <link href="<?php echo base_url() ?>resources/css/public/reset.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>resources/css/public/filter.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>resources/css/public/firstStart.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/content.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>resources/thirdparty/font-awesome-4.5.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!--<link href="<?php echo base_url() ?>resources/css/public/personaldetails.css" rel="stylesheet" type="text/css" />-->
    <!--第三方样式-->

    <!--header框架js-->
    <script src="<?php echo base_url() ?>resources/js/public/jquery-1.11.0.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/page.js"></script>
    <script src="<?php echo base_url() ?>resources/js/public/template.js"></script>

</head>

<body>
<!--公用header框架开始-->
<?php $this->load->view('public/header.php')?>
<!--公用header框架结束-->
<div class="frame">
    <div class="main clearfix">
        <!--公用menu框架开始-->
        <?php $this->load->view('public/left.php')?>
        <!--公用menu框架结束-->
        <!--公用centent框架开始-->
        <div class="content">
            <div class="total clearfix">
                <h3>共计：<?php echo $total;?>条</h3>
                <div class="search-a">
                    <input id="stuName" class="iptSearch-a" value="<?php echo (isset($search) ? $search: "") ?>" placeholder="请输入关键字搜索" type="text">
                    <i class="fa fa-search"></i>
                </div>
            </div>

            <table class="logList_student" id="">
                <thead>
                <tr class="table-title">
                    <td width="200">任务名</td>
                    <td width="340">日志内容</td>
                    <td width="100">日志类型</td>
                    <td>日志时间</td>
                </tr>
                </thead>

                <tbody>
                <?php
                if($total > 0) {
                    foreach ($result as $val) {
                        ?>
                        <tr>
                            <td title="<?php echo $val['LogTaskName'];?>"><?php echo $val['LogTaskName'];?></td>
                            <td title="<?php echo $val['LogContent'];?>"><?php echo $val['LogContent'];?></td>
                            <td><?php echo $log_type[$val['LogTypeID']]; ?></td>
                            <td><?php echo date('Y-m-d H:i:s',$val['CreateTime']);?></td>
                        </tr>
                        <?php
                    }
                }?>
                </tbody>
            </table>
            <?php if($total > 0) { ?>
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
            <?php } else { ?>
            <div class="noNews block">
                <i class="fa fa-file-text" aria-hidden="true"></i><span>没有找到数据......</span>
            </div>
            <?php } ?>

        </div>


        <!--公用centent框架结束-->
    </div>

    <!--公用fotter框架开始-->
    <?php $this->load->view('public/footer.php')?>
    <!--公用fotter框架结束-->
</div>

</body>
</html>
<script type="text/javascript">
    $(function() {
        var site_url = '<?php echo site_url();?>';
        $(".fa-search").click(function () {
            var search = encodeURIComponent($(".iptSearch-a").val().trim());
            window.location.href = site_url + "Personal/log?search=" + search;
        });
        $('.iptSearch-a').keydown(function (e) {
            if (e.keyCode == 13) {
                var search = encodeURIComponent($(".iptSearch-a").val().trim());
                window.location.href = site_url + "Personal/log?search=" + search;
            }
        });
    })
</script>
