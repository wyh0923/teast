<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>知识体系-全部课程</title>
    <!--公用样式-->
    <link rel="shortcut icon" href="<?php echo base_url() ?>resources/imgs/public/title.ico">
    <link href="<?php echo base_url() ?>resources/css/public/reset.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>resources/thirdparty/font-awesome-4.5.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>resources/css/public/animate.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/firstStart.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/filter.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>resources/css/public/content.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>resources/css/teacher/booklist.css" rel="stylesheet" type="text/css" />
    <!--第三方样式-->

    <!--header框架js-->
    <script src="<?php echo base_url() ?>resources/js/public/jquery-1.11.0.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/page.js"></script>
    <script src="<?php echo base_url() ?>resources/js/public/template.js"></script>
    <script src="<?php echo base_url() ?>resources/js/student/book_list.js"></script>

</head>
<script type="text/javascript">
    var site_url = '<?php echo site_url();?>';
    var search_url = "<?php echo $search_url; ?>";
</script>
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
            <div class="Filter">
                <?php foreach($filter as $subFilter): ?>
                    <div class="filter clearfix ">
                        <h3 class="filterTitle"><?php echo $subFilter["subFilterName"]?>：</h3>
                        <div class="filterList">
                            <?php foreach($subFilter["subFilterData"] as $item): ?>
                                <a title = "<?php echo $item["value"] ?>" href="<?php echo $item["url"] ?>" class="<?php echo ($item["active"]?"filterCur":"")?>"><?php $str = $item["value"]; echo  strlen($str)>90?mb_substr($str,0,30).'..':$item["value"]?></a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <!--total开始-->
            <div class="total clearfix">
                <h3>共计：<?php echo $book_num.'门/'.$section_num.'节';?></h3>
                <a href="<?php echo $Sort["diff"]["url"] ?>" id="SortForDiff" class="btnLevel">难度等级&nbsp;<i class="<?php echo $Sort["diff"]["icon"] ?>"></i></a>
                <a href="<?php echo $Sort["time"]["url"] ?>" id="SortForTime" class="btnLevel">课程时长&nbsp;<i class="<?php echo $Sort["time"]["icon"] ?>"></i></a>
                <div class="search-a">
                    <input type="text" class="iptSearch-a" value="<?php echo (isset($search) ? $search : "") ?>" name="Search" placeholder="请输入关键字搜索">
                    <i class="fa fa-search"></i>
                </div>
            </div>

            <!--total结束-->
            <!--TaskList开始-->
            <div class="learningTaskList">
                <?php
                    if(count($book) > 0){
                        foreach ($book as $value){
                            $val = $value['package'];
                            $img = $val['PackageImg'] ? $val['PackageImg']:'logo.png';
                            ?>
                    <div class="tasklist clearfix">
                        <div class="taskimg">
                            <a title="<?php echo $val['PackageName'];?>" href="<?php echo site_url().'Book/bookdetail?packageid='.$val['PackageID'];?>">
                                <img alt="<?php echo $val['PackageName'];?>" src="<?php echo base_url().'resources/files/img/course/'.$img;?>" onerror="javascript:this.src='<?php echo base_url() ?>resources/files/img/course/logo.png'">
                            </a>
                        </div>
                        <div class="taskinfo">
                            <div class="taskName">
                                    <span class="TaskName">
                                        <a class="move" title="<?php echo $val['PackageName'];?>" href="<?php echo site_url().'Book/bookdetail?packageid='.$val['PackageID'];?>"><?php echo $val['PackageName'];?></a>
                                    </span>
                                <?php if(isset($value['architecture'])){ ?>
                                    <span><?php $architecture = '';
                                        foreach ($value['architecture'] as $arc){
                                            $architecture .= $arc.' ';
                                        }
                                        ?>
                                        <a class="ArchTag" title="<?php echo $architecture;?>"> <?php echo $architecture;?> </a>
                                    </span>
                                <?php }?>
                            </div>
                            <div class="taskinfoabout"> <?php echo $val['PackageAuthor'].' 于 '.date('Y-m-d',$val['CreateTime']).' 发布 ';?> </div>
                            <p title="<?php echo $val['PackageDesc'];?>"><?php
                                if (mb_strlen($val['PackageDesc']) > 93 ){
                                    echo mb_substr($val['PackageDesc'], 0, 93, 'UTF-8')."...";
                                }else{
                                    echo $val['PackageDesc'];
                                }
                                ?></p>
                            <div class="taskmore clearfix">
                                <?php
                                if ($val['PackageDiff'] == 0) {
                                    $diff = '初级';
                                } elseif ($val['PackageDiff'] == 1) {
                                    $diff = '中级';
                                }else{
                                    $diff = '高级';
                                }
                                ?>
                                <span class="">
                                        <i class="fa fa-star" title="课程难度"></i><?php echo $diff;?>
                                     </span>
                                    <span class="">
                                        <i class="fa fa-navicon" title="课程小节总数"> </i>共<?php echo $val['SectionNum'];?>节
                                    </span>
                                    <span class="">
                                        <i class="fa fa-video-camera" title="视频"></i><?php echo $val['VideoNum'];?>
                                    </span>
                                    <span class="">
                                        <i class="fa fa-square-o fa-fw" title="单机试验"></i><?php echo $val['SingleSceneNum'];?>
                                    </span>
                                    <span class="">
                                        <i class="fa fa-object-ungroup fa-fw" title="网络实验"></i><?php echo $val['NetSceneNum'];?>
                                    </span>


                                <a class="btnRelease" name="" packageid="<?php echo $val['PackageID'];?>" href="javascript:;">开始学习</a>


                            </div>
                        </div>
                    </div>
                <?php
                        }
                    }
                ?>
            </div>
            <!--TaskList结束-->
            <!--page开始-->
            <?php if(count($book) > 0){ ?>
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
            <?php } else{ ?>
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
<!-- 提示 -->
<div class="maskbox"></div>
<!--结束提示弹窗-->
<div class="popUpset animated " id="studyBox">
    <form action="" method="post">
        <div class="popTitle">
            <p>提示操作</p>
            <a href="javascript:;" id="" class="close close-1"></a>
        </div>
        <div class="infoBox">
            <p class="promptNews">学习任务中已有该课程,确定要重新下发学习任务?</p>
            <input type="hidden" id="spackageid" value="">
            <input type="hidden" id="staskid" value="">
            <div class="btnBox">
                <a href="javascript:;" class="publicOk " id="gostudy">继续学习</a>
                <a href="javascript:;" class="publicNo" id="newstudy">下发新任务</a>
            </div>
        </div>
    </form>
</div>
<!-- 提示信息 -->
<div class="popUpset animated " id="okBox" >
    <form action="" method="post">
        <div class="popTitle">
            <p>提示操作</p>
            <a href="javascript:;" class="close close-1"></a>
        </div>
        <div class="infoBox">
            <p class="promptNews promptUp"></p>
        </div>
    </form>
</div>

</body>
</html>
<script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/prompt.js"></script>