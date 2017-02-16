<!DOCTYPE html>
<html>
<head>
	<title>我的体系</title>

<meta charset="utf-8">
<link rel="shortcut icon" href="<?php echo base_url() ?>resources/imgs/public/title.ico">
<link href="<?php echo base_url() ?>resources/css/public/animate.min.css" rel="stylesheet" type="text/css">

<script type='text/javascript' src="<?php echo base_url() ?>resources/js/public/jquery-1.11.0.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/template.js"></script>
<link href="<?php echo base_url() ?>resources/css/public/reset.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/thirdparty/font-awesome-4.5.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/css/public/firstStart.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/css/public/content.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/css/public/filter.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/page.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>resources/js/teacher/plan.js"></script>


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
                <div class="myarchlist">
                    <h3 class="myarchlistTitle lable_h3">我的知识体系</h3>
                    <div class="total clearfix ">
                        <h3 class="arnum" >共计：<?php echo $total_rows;?>套</h3>
                        <a href="javascript:;" id="addPlan" class="btnNew" onclick="" ><span>+</span>新增培训方案</a>
                        <div class="search-a">
                            <input type="text" class="iptSearch-a esys" value="<?php echo $search;?>" placeholder="请输入体系名称">
                            <i class="fa fa-search csys" ></i>
                        </div>
                    </div>
                </div>
        
                        
              <table class="myarchlistTable color_block colorChange" id="">
                    <thead>
                        <tr class="table-title">
                            <td width="170">培训方案</td>
                            <td width="260">课程体系</td>
                            <td width="80" id="PackageCount" code="<?php if ($sort && $sort['field']=='PackageCount'):?><?php echo $sort['order'];?><?php endif;?>">
                                <a>课程门数<i class="fa
                                <?php if ($sort && $sort['field']=='PackageCount' && $sort['order']=='DESC'):?>fa-sort-alpha-desc
                            <?php elseif ($sort && $sort['field']=='PackageCount' && $sort['order']=='ASC'):?>fa-sort-alpha-asc
                            <?php else:?>fa-sort<?php endif;?>
                             "></i></a></td>
                            <td width="80" id="SectionNum" code="<?php if ($sort && $sort['field']=='SectionNum'):?><?php echo $sort['order'];?><?php endif;?>">
                                <a>课时总数<i class="fa
                                <?php if ($sort && $sort['field']=='SectionNum' && $sort['order']=='DESC'):?>fa-sort-alpha-desc
                            <?php elseif ($sort && $sort['field']=='SectionNum' && $sort['order']=='ASC'):?>fa-sort-alpha-asc
                            <?php else:?>fa-sort<?php endif;?>
                                "></i></a></td>
                            <td width="95" id="TestNum" code="<?php if ($sort && $sort['field']=='TestNum'):?><?php echo $sort['order'];?><?php endif;?>">
                                <a>实验课时数 <i class="fa
                                <?php if ($sort && $sort['field']=='TestNum' && $sort['order']=='DESC'):?>fa-sort-alpha-desc
                            <?php elseif ($sort && $sort['field']=='TestNum' && $sort['order']=='ASC'):?>fa-sort-alpha-asc
                            <?php else:?>fa-sort<?php endif;?>
                                "></i></a></td>
                            <td>操作</td>
                        </tr>	
                    </thead>
                    <tbody class="">
                    <?php foreach ($plans as $p):?>
                        <tr>
                            <td >
                                <a title="<?php echo $p['ArchitectureName']?>" href="<?php echo site_url('Subject/editplan'). '/pid/'. $p['ArchitectureID']?>" class="forYellow"><?php echo $p['ArchitectureName']?></a>
                            </td>
                            <td >
                                <?php if(!empty($p['books'])):?>
                                    <?php $sys = explode(',', $p['books']);
                                    foreach ($sys as $s):?>
                                        <span title="<?php echo $s ?>"><?php echo $s?></span>
                                    <?php endforeach;?>
                                <?php endif;?>

                            </td>
                            <td title=""><?php echo $p['PackageCount']?></td>
                            <td title=""><?php echo $p['SectionNum']?></td>
                            <td title="999"><?php echo $p['TestNum']?></td>
                            <td>
                                <a href="<?php echo site_url('Subject/editplan'). '/pid/'. $p['ArchitectureID']?>"   class=" forBlue"><i class="fa fa-edit"></i>编辑</a>
                                <a href="javascript:;" class=" forRed pdel" code="<?php echo $p['ArchitectureID']?>" >
                                    <i class="fa fa-trash-o"></i>
                                    删除
                                </a>
                            </td>
                        </tr>
                    <?php endforeach;?>

                    </tbody>
        
                </table>

                <!--分页 start-->
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

<div class="maskbox"></div>

<!--删除成功-->
<div class="popUpset animated " id="pdOk"  >
    <form action="" method="post">
        <div class="popTitle">
            <p>提示操作</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox">
            <p class="promptNews promptUp">删除成功</p>

        </div>
    </form>
</div>

<!--删除确认-->
<div class="popUpset animated " id="pdelOk">
    <form action="" method="post">
        <div class="popTitle">
            <p>确认操作</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox">
            <p class="promptNews">确认删除该培训方案吗?</p>
            <div class="btnBox">
                <a href="javascript:;" class="publicOk" id="pOk">确定</a>
                <a href="javascript:;" class="publicNo hidePop-1" id="">取消</a><!--如果是子层弹窗，调用hidePop-2-->
            </div>

        </div>
    </form>
</div>

<!--新增培训方案-->
<div class="popUpset animated " id="showsys">
    <form action="" method="post">
        <div class="popTitle">
            <p>新增培训方案</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox">
            <div class=" inputPop clearfix">
                <span class=" secongTitle"><nobr>*</nobr>培训方案名称：</span>
                <input type="text" id="planName" class="iptext">

            </div>
            <p id="adderrormsg"></p>
            <div class="btnBox">
                <a href="javascript:;" class="publicOk " id="addOk">确定</a>
                <a href="javascript:;" class="publicNo hidePop-1" id="">取消</a><!--如果是子层弹窗，调用hidePop-2-->

            </div>
        </div>
    </form>
</div>



<script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/prompt.js"></script>
<script type="text/javascript">
    var site_url = '<?php echo site_url() ?>';
    var search = "<?php echo $search; ?>";
</script>
</body>
</html>