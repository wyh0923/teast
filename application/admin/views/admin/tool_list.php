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
<link href="<?php echo base_url() ?>resources/css/admin/toolist.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/page.js"></script>
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
             
                <div class="total clearfix">
                <h3>共计：<?php echo $total_rows;?>个</h3>
                <a href="<?php echo site_url('Adminsubject/addtool');?>" class="btnNew" id="addBtn"><span>+</span>添加工具</a>
                <div class="search-a">
                    <input class="iptSearch-a entsearch" value="<?php echo $search?>" name="Search" placeholder="请输入关键字搜索" type="text" >
                    <i class="fa fa-search subsearch"></i>
                </div>
                <div class="selectCss">
                    <label>工具分类：</label>
                    <label>
                         <select onchange="typesel()" id="typeSel">

                                <option value=" ">请选择</option>
                             <?php foreach ($tool_types as $type):?>

                                <option <?php if($ts == $type['ID']) echo 'selected'?> value="<?php echo $type['ID']?>"><?php echo $type['html']. $type['ClassifyName']?></option>
                             <?php endforeach;?>
                          </select>
                    </label>
                    
                </div>
            </div>
            <p class="zhuJie">注：场景内请使用 172.16.4.2/tools访问工具库</p>
            <table class="ctflistTable" id="ctflistTable">
                <thead>
                        <tr class="table-title">
                            <td width="180">工具名</td>
                            <td width="100">工具分类 </td>
                            <td  width="120" id="updateTime" code="<?php if ($sort && $sort['field']=='updateTime'):?><?php echo $sort['order'];?><?php endif;?>">
                                <a>创建时间<i class="fa <?php if ($sort && $sort['field']=='updateTime' && $sort['order']=='DESC'):?>fa-sort-alpha-desc
                            <?php elseif ($sort && $sort['field']=='updateTime' && $sort['order']=='ASC'):?>fa-sort-alpha-asc
                            <?php else:?>fa-sort<?php endif;?>
                            "></i></a>
                            </td>
                            <td width="120">操作</td>
                        </tr>   
                </thead>
                <tbody>
                    <?php foreach ($tool_list as $v):?>
                         <tr>
                            <td title=""><?php echo $v['toolName']?></td>
                            <td title="<?php if(empty($v['classifyName'])){echo '无';} else{echo $v['classifyName'];} ?>"><?php if(empty($v['classifyName'])){echo '无';} else{echo $v['classifyName'];} ?></td>
                            <td title=""><?php echo date('Y-m-d',strtotime($v['updateTime']))?></td>

                            <td>
                                <a class="forYellow detail" code="<?php echo $v['ID'];?>"> <i class="fa fa-search-plus"></i>详情</a>
                                <a href="javascript:;" code="<?php echo $v['ID'] ?>" class="forBlue modtool" >
                                    <i class="fa fa-edit" ></i>编辑
                                </a>
                                 <a href="javascript:;" class=" forRed delOne" code="<?php echo $v['ID'];?>" ><i class="fa fa-trash-o"></i>删除</a>
                            </td>
                         </tr>
                    <?php endforeach;?>
                                            
                </tbody>
            </table>
            <!--page.php start-->
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
           <!--page.php end-->
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
            <p class="promptNews">确定要删除吗？</p>

            <div class="btnBox">
                <a href="javascript:;" class="publicOk " id="okBtn">确定</a>
                <a href="javascript:;" class="publicNo hidePop-1" id="">取消</a><!--如果是子层弹窗，调用hidePop-2-->

            </div>
        </div>
    </form>
</div>
<!--编辑工具名称-->
<div class="popUpset animated " id="editsBox" >
    <form>
        <div class="popTitle">
            <p>编辑工具名称</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox">
            <div class="inputPop clearfix">
                <span class="secongTitle"><nobr>*</nobr>工具名称：</span>
                <input id="toolname"  value="" class="iptext" type="text" >
            </div>

            <p class="adderrormsg"></p>
            <div class="btnBox">
                <a href="javascript:;" class="publicOk" id="ok">确定</a><!--如果是子层弹窗，调用hidePop-2-->

            </div>
        </div>
    </form>
</div>
<!--工具详情-->
<div class="popUpset animated " id="detailinfo">
    <form action="" method="post">
        <div class="popTitle">
            <p>工具详情</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox">
            <div class="inputPop clearfix">
                <span class="secongTitle">工具类型：</span>
                <span id="ToolType" class="iptext noBorderInt2"></span>
            </div>
            <div class="inputPop clearfix">
                <span class="secongTitle">工具名称：</span>
                <span id="ToolName" class="iptext noBorderInt2"></span>
            </div>
            <div class="inputPop clearfix">
                <span class="secongTitle">工具描述：</span>
                <span id="description" class="iptext noBorderInt2"></span>
            </div>
            <div class="inputPop clearfix">
                <span class="secongTitle">工具地址：</span>
                <span id="ToolUrl" class="iptext noBorderInt2"></span>
            </div>
            <div class="btnBox">
                <a href="javascript:;" class="publicNo hidePop-1" id="">关闭</a><!--如果是子层弹窗，调用hidePop-2-->

            </div>
        </div>
    </form>
</div>


<script src="<?php echo base_url() ?>resources/js/public/prompt.js" type='text/javascript'></script>
<script src="<?php echo base_url() ?>resources/js/admin/tool_list.js" type='text/javascript'></script>
<script type="text/javascript">
    var site_url = '<?php echo site_url() ?>';
    var typeid = '<?php echo $ts ?>';
    var search = "<?php echo $search; ?>";

</script>

</body>
</html>