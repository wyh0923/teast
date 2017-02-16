<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>我的考试-已经完成的考试</title>
    <!--公用样式-->
    <link rel="shortcut icon" href="<?php echo base_url() ?>resources/imgs/public/title.ico">
    <link href="<?php echo base_url() ?>resources/css/public/reset.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>resources/thirdparty/font-awesome-4.5.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>resources/css/public/firstStart.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/filter.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>resources/css/public/content.css" rel="stylesheet" type="text/css" />
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
                <h3 id="totalNum">共计：<?php echo $total;?>套</h3>
                <div class="search-a">
                    <input id="stuName" type="text" class="iptSearch-a" value="<?php echo (isset($search) ? $search : "") ?>" placeholder="请输入考试名称">
                    <i class="fa fa-search "></i>
                </div>
            </div>
            <?php
            if($total > 0){
                ?>
                <table id="" class="">
                    <thead>
                    <tr class="table-title">
                        <td>考试名称</td>
                        <td width="90"><a href="<?php echo $Sort["TaskTime"]["url"]; ?>">考试时长 <i class="<?php echo $Sort["TaskTime"]["icon"]; ?>"></i></a></td>
                        <td width="100"><a href="<?php echo $Sort["ExamType"]["url"]; ?>">考卷类型<i class="<?php echo $Sort["ExamType"]["icon"]; ?>"></i></a></td>
                        <td width="70"><a href="<?php echo $Sort["UserName"]["url"]; ?>">下发老师<i class="<?php echo $Sort["UserName"]["icon"]; ?>"></i></a></td>
                        <td width="113"><a href="<?php echo $Sort["TaskStartTime"]["url"]; ?>">开始时间<i class="<?php echo $Sort["TaskStartTime"]["icon"]; ?>"></i></a></td>
                        <td width="113"><a href="<?php echo $Sort["TaskEndTime"]["url"]; ?>">结束时间<i class="<?php echo $Sort["TaskEndTime"]["icon"]; ?>"></i></a></td>
                        <td width="113">交卷时间</td>
                        <td width="50">得分</td>
                    </tr>
                    </thead>
                    <tbody id="">
                    <?php
                    foreach ($data as $val){
                        ?>
                        <tr>
                            <td><a class="operater forRed" href="<?php echo site_url().'Exam/examshow?taskid='.$val['TaskID'];?>" target="_blank" title="<?php echo $val['TaskName'];?>"><?php echo $val['TaskName'];?></a></td>
                            <?php
                                $taskTime = '';
                                if ($val['TaskTime'] < 60) {
                                    $taskTime = $val['TaskTime'] . '秒';
                                } else if ($val['TaskTime'] < 3600) {

                                    $minute = intval(floor($val['TaskTime'] % 60)) ? intval(floor($val['TaskTime'] % 60)). '秒' : '';//三目运算符
                                    $taskTime = intval(floor($val['TaskTime'] / 60)) . '分钟' .$minute;

                                } else if ($val['TaskTime'] < 86400) {
                                    $hour = intval(floor($val['TaskTime'] % 3600/60)) ? intval(floor($val['TaskTime'] % 3600/60)) . '分钟' : '' ;//三目运算符
                                    $taskTime = intval(floor($val['TaskTime'] / 3600)) . '小时' . $hour;

                                } else if ($val['TaskTime'] < 86400*30) {

                                    $day = intval(floor($val['TaskTime'] % 86400/3600)) ? intval(floor($val['TaskTime'] % 86400/3600)). '小时':'';//三目运算符
                                    $taskTime = intval(floor($val['TaskTime'] / 86400)) . '天'. $day;

                                } else{
                                    $month = intval(floor($val['TaskTime'] % (86400*30)/86400)) ? intval(floor($val['TaskTime'] % (86400*30)/86400)) . '天' : '';//三目运算符
                                    $taskTime = intval(floor($val['TaskTime'] / (86400*30))) . '月'. $month;
                                }
                                ?>
                            <td title="<?php echo $taskTime;?>"><?php echo $taskTime;?></td>
                            <?php
                            $str = '';
                            if($val['ExamType']&1){
                                $str.="单选题 ";
                            }
                            if($val['ExamType']&2){
                                $str.="多选 ";
                            }
                            if($val['ExamType']&4){
                                $str.="判断 ";
                            }
                            if($val['ExamType']&8){
                                $str.="填空 ";
                            }
                            if($val['ExamType']&16){
                                $str.="夺旗题 ";
                            }
                            if($val['ExamType']&32){
                                $str.="场景题 ";
                            }
                            ?>
                            <td title="<?php echo $str;?>"><?php echo $str;?></td>
                            <td title="<?php echo $val['UserName'];?>"><?php echo $val['UserName'];?></td>
                            <td title="<?php echo date('m-d H:i:s',$val['TaskStartTime']); ?>"><?php echo date('m-d H:i:s',$val['TaskStartTime']); ?></td>
                            <td title="<?php echo date('m-d H:i:s',$val['TaskEndTime']); ?>"><?php echo date('m-d H:i:s',$val['TaskEndTime']); ?></td>
                            <td title="<?php echo date('m-d H:i:s',$val['TaskFinishedTime']);?>"><?php echo date('m-d H:i:s',$val['TaskFinishedTime']);?></td>
                            <td title="<?php echo $val['TaskScore'];?>"><?php echo $val['TaskScore'];?></td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
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
            <?php }else{ ?>
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
        var site_url = '<?php echo site_url(); ?>';
        var search_url = '<?php echo $search_url; ?>';
        $(".fa-search").click(function () {
            var search = encodeURIComponent(($(".iptSearch-a").val().trim()));
            window.location.href = search_url + "search=" + search;
        });
            
        $('.iptSearch-a').keydown(function (e) {
            if (e.keyCode == 13) {
                var search = encodeURIComponent(($(".iptSearch-a").val().trim()));
                window.location.href = search_url + "search=" + search;
            }
        });
    });
</script>