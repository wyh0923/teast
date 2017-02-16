<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>我的学习-已经完成的学习</title>
    <!--公用样式-->
    <link rel="shortcut icon" href="<?php echo base_url() ?>resources/imgs/public/title.ico">
    <link href="<?php echo base_url() ?>resources/css/public/reset.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>resources/thirdparty/font-awesome-4.5.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>resources/css/public/firstStart.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/filter.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>resources/css/public/content.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>resources/css/teacher/booklist.css" rel="stylesheet" type="text/css" />
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

            <!--total开始-->
            <div class="total clearfix">
                <h3>共计：<?php echo $total;?>套</h3>

                <div class="search-a">
                    <input type="text" class="iptSearch-a" value="<?php echo (isset($search) ? $search : "") ?>" name="Search" placeholder="请输入关键字搜索">
                    <i class="fa fa-search"></i>
                </div>
            </div>

            <!--total结束-->
            <!--TaskList开始-->
            <?php
            if($total > 0){
                ?>
                <div class="learningTaskList">
                    <?php
                    foreach ($data as $val){
                        $img = $val['PackageImg'] ? $val['PackageImg']:'logo.png';
                        ?>
                        <div class="tasklist clearfix">
                            <div class="taskimg">
                                <a title="<?php echo $val['TaskName']?>" target="_blank" href="<?php echo site_url().'Study/studydetail?taskid='.$val['TaskID'];?>">
                                    <img alt="<?php echo $val['TaskName']?>" src="<?php echo base_url().'resources/files/img/course/'.$img;?>" onerror="javascript:this.src='<?php echo base_url() ?>resources/files/img/course/logo.png'">
                                </a>
                            </div>
                            <div class="taskinfo">
                                <div class="taskName">
								<span class="TaskName">
									<a class="move" title="<?php echo $val['TaskName']?>" target="_blank" href="<?php echo site_url().'Study/studydetail?taskid='.$val['TaskID'];?>"><?php echo $val['TaskName']?></a>
								</span>
                                </div>
                                <div class="taskinfoabout"> <?php echo $val['UserName'].''; ?> 于 <?php echo date('Y-m-d',$val['TaskStartTime']);?> 下发  给<?php echo empty($val['ClassID'])? ' 给'.$UserName:' 给班级：'.$val['ClassName'] ;?><span class="finshTime">本任务已结束</span> </div>
                                <p title="<?php echo $val['PackageDesc']?>">
                                    <?php
                                    if (mb_strlen($val['PackageDesc']) > 93 ){
                                        echo mb_substr($val['PackageDesc'], 0, 93, 'UTF-8')."...";
                                    }else{
                                        echo $val['PackageDesc'];
                                    }
                                    ?></p>
                                <div class="taskmore clearfix">
                                <span class="jiBie">
                                    <i class="fa fa-star" title="课程难度"></i><?php
                                    if($val['PackageDiff']==0){
                                        echo '初级';
                                    }elseif($val['PackageDiff']==1){
                                        echo '中级';
                                    }else{
                                        echo '高级';
                                    }
                                    ?>
                                 </span>
                                <span class="tasktotal">
                                    <i class="fa fa-navicon" title="课程小节总数"></i>共<?php echo $val['SectionNum']?>节
                                </span>
                                <span class="tasktime">
                                    <i class="fa fa-calendar" title="当前任务结束日期"></i><?php echo date('Y-m-d',$val['TaskEndTime']);?>
                                </span>
                                    <div class="nums">
                                		<span class="ctaskprogr" >
                                        	<span class="taskpro" style="width:<?php echo $val['TaskProcess'];?>px;"></span>
                                        </span>
                                        <span class="percentNum"><?php echo $val['TaskProcess']?>%</span>

                                    </div>

                                    <a href="<?php echo site_url().'Study/studydetail?taskid='.$val['TaskID'];?>" target="_blank" class="btnRelease"><i class="fa fa-map-o" aria-hidden="true"></i>浏览任务</a>


                                </div>
                            </div>
                        </div>
                    <?php } ?>

                </div>
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
                <!--page结束-->
            <?php } else { ?>
                <!--TaskList结束-->
                <!--page开始-->
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
<script>
    var site_url = "<?php echo site_url();?>";
    var search_url = '<?php echo $search_url; ?>';
    //搜索
    $(".fa-search").click(function () {
        var search = encodeURIComponent($(".iptSearch-a").val().trim());
        window.location.href = search_url + "search=" + search;
    });
    $('.iptSearch-a').keydown(function (e) {
        if (e.keyCode == 13) {
            var search = encodeURIComponent($(".iptSearch-a").val().trim());
            window.location.href = search_url + "search=" + search;
        }
    });
</script>
