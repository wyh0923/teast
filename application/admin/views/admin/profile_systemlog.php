<!DOCTYPE html>
<html>
<head>
    <title><?php echo $this->title;?></title>

    <meta charset="utf-8">
    <link rel="shortcut icon" href="<?php echo base_url() ?>resources/imgs/public/title.ico">
    <script type='text/javascript' src="<?php echo base_url() ?>resources/js/public/jquery-1.11.0.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/template.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/page.js"></script>
    <link href="<?php echo base_url() ?>resources/css/public/reset.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/thirdparty/font-awesome-4.5.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
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
                <h3>共计：<?php echo $total_rows; ?>条</h3>
                <div class="search-a">
                    <input id="stuName" class="iptSearch-a" value="<?php echo $search;?>" placeholder="请输入关键字搜索" type="text">
                    <i class="fa fa-search"></i>
                </div>
            </div>
            <table class="logList" id="logTable">
                <thead>
                <tr class="table-title">
                    <td width="70">姓名</td>
                    <td width="115">任务名</td>
                    <td width="115">日志内容</td>
                    <td width="70">日志类型</td>
                    <td  width="150">日志时间</td>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($log_list as $row): ?>
                <tr>
                    <td><?php echo $row['UserName']; ?></td>
                    <td title="<?php echo $row['LogTaskName']; ?>"><?php echo $row['LogTaskName']; ?></td>
                    <td title="<?php echo $row['LogContent']; ?>"><?php echo $row['LogContent']; ?></td>
                    <td><?php echo $log_type[$row['LogTypeID']]; ?></td>
                    <td><?php echo date('Y-m-d H:i:s',$row['CreateTime']); ?></td>
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
<script src="<?php echo base_url() ?>resources/js/public/prompt.js" type='text/javascript'></script>
<script type="text/javascript">
    var site_url = '<?php echo site_url(); ?>';
</script>
<script type="text/javascript">
    $(function(){
        $(".fa-search").click(function(){
            var search = $.trim($(".iptSearch-a").val());
            window.location.href="<?php echo site_url('/Profile/systemlog');?>" + "/search/"+encodeURI(translate(search));
        });
        $('.iptSearch-a').keydown(function(e){
            if(e.keyCode==13){
                var search = $.trim($(".iptSearch-a").val());
                window.location.href="<?php echo site_url('/Profile/systemlog');?>" + "/search/"+encodeURI(translate(search));
            }
        });

    });
</script>
</body>
</html>