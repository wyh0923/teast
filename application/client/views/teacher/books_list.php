<!DOCTYPE html>
<html>
<head>
	<title>我的课程</title>

<meta charset="utf-8">
<link rel="shortcut icon" href="<?php echo base_url() ?>resources/imgs/public/title.ico">
<script type='text/javascript' src="<?php echo base_url() ?>resources/js/public/jquery-1.11.0.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/template.js"></script>
<link href="<?php echo base_url() ?>resources/css/public/reset.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/thirdparty/font-awesome-4.5.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/css/public/firstStart.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/css/public/content.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/css/public/filter.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/page.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>resources/js/teacher/book.js"></script>
<link href="<?php echo base_url() ?>resources/css/public/animate.min.css" rel="stylesheet" type="text/css">


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
            <div class="Filter">
                <div class="filter clearfix ">
                    <h3 class="filterTitle">培训方案：</h3>
                    <div class="filterList">
                        <a title="全部" href="<?php echo site_url('Subject/mybook')?>" <?php if($pid == ''):?>class="filterCur"<?php endif;?>>全部</a>
                        <?php foreach ($trains as $t):?>
                            <a title="" <?php if($pid == $t['ArchitectureID']):?>class="filterCur"<?php endif;?> href="<?php echo site_url('Subject/mybook'). '/pid/' .$t['ArchitectureID']?>" ><?php echo $t['ArchitectureName']?></a>
                        <?php endforeach;?>
                    </div>
                </div>
                <div class="filter clearfix ">
                    <h3 class="filterTitle">课程体系：</h3>
                    <div class="filterList">
                        <a title="全部" href="<?php echo site_url('Subject/mybook'). '/pid/' .$pid?>" <?php if($aid == ''):?>class="filterCur"<?php endif;?>>全部</a>
                        <?php foreach ($sys as $s):?>
                            <a title="" <?php if($aid == $s['ArchitectureID']):?>class="filterCur"<?php endif;?> href="<?php echo site_url('Subject/mybook'). '/pid/' .$s['ArchitectureParent']. '/aid/'. $s['ArchitectureID']?>" ><?php echo $s['ArchitectureName']?></a>
                        <?php endforeach;?>
                    </div>
                </div>
            </div>				
            <div class="total clearfix">
                <h3>共计：<?php echo $total_rows;?>套</h3>
                <a href="<?php echo site_url('Subject/addbook')?>" id="addBtn" class="btnNew"><span>+</span>新增课程</a>
                <div class="search-a">

                    <input type="text" class="iptSearch-a" id="courSearch" name="Search" value="<?php echo $search;?>" placeholder="请输入关键字搜索">
                    <i class="fa fa-search clsearch"></i>
                
                </div>
                
            </div>
            <table class="studytaskList">
                <tr class="table-title">
                    <td width="260">课程名</td>

                    <td width="60" >被引用</td>
                    <td width="60" id="PackageDiff" code="<?php if ($sort && $sort['field']=='PackageDiff'):?><?php echo $sort['order'];?><?php endif;?>">
                        <a>难度<i class="fa
                            <?php if ($sort && $sort['field']=='PackageDiff' && $sort['order']=='DESC'):?>fa-sort-alpha-desc
                            <?php elseif ($sort && $sort['field']=='PackageDiff' && $sort['order']=='ASC'):?>fa-sort-alpha-asc
                            <?php else:?>fa-sort<?php endif;?>
                        "></i></a>
                    </td>
                    <td width="80" id="SectionNum" code="<?php if ($sort && $sort['field']=='SectionNum'):?><?php echo $sort['order'];?><?php endif;?>">
                        <a>课时总数 <i class="fa
                            <?php if ($sort && $sort['field']=='SectionNum' && $sort['order']=='DESC'):?>fa-sort-alpha-desc
                            <?php elseif ($sort && $sort['field']=='SectionNum' && $sort['order']=='ASC'):?>fa-sort-alpha-asc
                            <?php else:?>fa-sort<?php endif;?>
                            "></i></a>
                    </td>
                    <td width="80" id="PracticeSectionNum" code="<?php if ($sort && $sort['field']=='PracticeSectionNum'):?><?php echo $sort['order'];?><?php endif;?>">
                        <a>实验课时<i class="fa
                            <?php if ($sort && $sort['field']=='PracticeSectionNum' && $sort['order']=='DESC'):?>fa-sort-alpha-desc
                            <?php elseif ($sort && $sort['field']=='PracticeSectionNum' && $sort['order']=='ASC'):?>fa-sort-alpha-asc
                            <?php else:?>fa-sort<?php endif;?>
                            "></i></a>
                    </td>
                    <td width="70">状态</td>
                    <td width="">操作</td>
                </tr>
                <tbody>
                <?php foreach ($courses as $c):?>
                    <tr>
                        <td style="cursor:default" title="<?php echo $c['PackageName']?>"><?php echo $c['PackageName']?></td>
                        <td><a onclick="quotelist(<?php echo $c['PackageID']?>)" class="btnOpen" href="javascript:;"><?php echo $c['quoteNum']?></a></td>
                        <td>
                            <?php if($c['PackageDiff'] == 1):?>中级
                            <?php elseif($c['PackageDiff'] == 2):?>高级
                            <?php else:?>初级
                            <?php endif;?>
                        </td>
                        <td><?php echo $c['SectionNum']?></td>
                        <td><?php echo $c['PracticeSectionNum']?></td>
                        <td><span class="">
                                <?php if($c['PackageStatus'] == 1):?>发布
                                <?php else:?>不发布
                                <?php endif;?>
                            </span></td>
                        <td>
                            <a class="forBlue editcourse" code="<?php echo $c['PackageID']?>" href="javascript:;" ><i class="fa fa-edit"></i>编辑</a>
                            <a class="forYellow"  href="<?php echo site_url('Subject/courseframe'). '/cid/'. $c['PackageID']?>"><i class="fa fa-search-plus "></i>结构 </a>
                            <a class="forRed delcourse" href="javascript:;"  cid="<?php echo $c['PackageID']?>"><i class="fa fa-trash"></i>删除 </a>
                        </td>
                    </tr>
                <?php endforeach;?>
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
            <?php else: ?>
                <div class="noNews block">
                    <i class="fa fa-file-text" aria-hidden="true"></i><span>没有找到数据......</span>
                </div>
            <?php endif; ?>
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
<div class="popUpset animated " id="one_del" >
    <form action="" method="post">
        <div class="popTitle">
            <p>确认操作</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox">
            <p class="promptNews">删除该课程会删除该课程内的小节,确定删除该课程？</p>

            <div class="btnBox">
                <a href="javascript:;" class="publicOk " id="delOk">确定</a>
                <a href="javascript:;" class="publicNo hidePop-1" id="">取消</a><!--如果是子层弹窗，调用hidePop-2-->

            </div>
        </div>
    </form>
</div>
<!--删除成功-->
<div class="popUpset animated " id="okBox">
    <form action="" method="post">
        <div class="popTitle">
            <p>确认操作</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox">
            <p class="promptNews">删除成功</p>

        </div>
    </form>
</div>
<!--课程引用-->
<div class="popUpset animated " id="quoteBox">
    <form action="" method="post">
        <div class="popTitle">
            <p>课程引用</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox">
            <div class=" inputPop popTable">
                <table>
                    <thead>
                    <tr class="table-title">
                        <td width="45%">课程名</td>
                        <td>引用的体系</td>
                    </tr>
                    </thead>
                    <tbody id="quotelist">
                    
                    </tbody>
                </table>
            </div>
            <div class="btnBox">
<!--                <a href="javascript:;" class="publicOk" id="">确定</a>-->
<!--                <a href="javascript:;" class="publicNo hidePop-1" id="">关闭</a>--><!--如果是子层弹窗，调用hidePop-2-->
            </div>
        </div>
    </form>
</div>


<script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/prompt.js"></script>
<script type="text/javascript">
    var site_url = '<?php echo site_url() ?>';
    var pid = '<?php echo $pid?>';
    var aid = '<?php echo $aid?>';
    var search = "<?php echo $search; ?>";

</script>
</body>
</html>