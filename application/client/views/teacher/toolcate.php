<!DOCTYPE html>
<html>
<head>
	<title>分类管理</title>

<meta charset="utf-8">
<link rel="shortcut icon" href="<?php echo base_url() ?>resources/imgs/public/title.ico">
<link href="<?php echo base_url() ?>resources/css/public/animate.min.css" rel="stylesheet" type="text/css">
<script type='text/javascript' src="<?php echo base_url() ?>resources/js/public/jquery-1.11.0.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/template.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>resources/js/teacher/tool_list.js"></script>
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
             
            <div class="myarchlist">
                <h3 class="lable_h3">分类管理</h3>
               
            </div>
            <div class="total clearfix ">
                    <h3>共计：<?php echo $total_rows;?>类</h3>
                    <a href="javascript:;" id="addcate" class="btnNew" onclick=""><span>+</span>新增分类</a>
                    <div class="search-a">
                        <input id="typeName" class="iptSearch-a scate" value="" placeholder="请输入分类名称" type="text">
                        <i class="fa fa-search ccate"></i>
                    </div>
            </div>
            <table id="example-basic-expandable" class=" forQuestion forToolist">
                <thead>
                   <tr class="table-title">  
                      <th width="70%">分类名</th>
                      <th width="30%">操作</th>
                   </tr>
                </thead>
               </tbody>
                <?php foreach ($type_list as $v):?>
                    <tr class="firstOnce "  id="tool<?php echo $v['ID']?>">
                        <td><a href="javascript:;" class="queClass <?php if($v['mark']==1) echo ' tableUp downShow'?>" cid="<?php echo $v['ID']?>"><?php echo $v['classifyName']?></a></td>
                        <td>
                            <div class="dropdown">
                                <a class="treetable">管理▼</a>
                                <ul class="dropdown-menu">
                                    <li><a class="editBtn" cname="<?php echo $v['classifyName']?>" cid="<?php echo $v['ID']?>">修改类名</a></li>
                                    <li><a class="deleteBtn" cid="<?php echo $v['ID']?>">删除分类</a></li>
                                </ul>
                            </div>
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

<!--新增分类-->
<div class="popUpset animated " id="catebox" >
    <form action="" method="post">
        <div class="popTitle">
            <p>新增分类</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox">
            <div class="inputPop clearfix">
                <span class="secongTitle">分类名称：</span>
                <input id="newType"  value="" class="iptext" type="text" >
            </div>
            <div class="inputPop clearfix">
                <span class="secongTitle">父级分类：</span>
                <select id="typePid" class="iptext">
                    <option class="" value="0" selected="true">----添加一级分类----</option>
                    <?php foreach ($first_list as $v):?>
                        <option class="" value="<?php echo $v['ID']?>"><?php echo $v['classifyName']?></option>
                    <?php endforeach;?>
                </select>
            </div>
            <p id="adderror" class="adderrormsg" ></p>
            <div class="btnBox">
                <a href="javascript:;" class="publicOk" id="addOk">确定</a><!--如果是子层弹窗，调用hidePop-2-->

            </div>
        </div>
    </form>
</div>

<!--删除分类确认-->
<div class="popUpset animated " id="delcate" >
    <form action="" method="post">
        <div class="popTitle">
            <p>确认操作</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox">
            <p class="promptNews">确定要删除该分类吗？</p><!--调用promptUp类大型提示框-->
            <input type="hidden" name="" id="cateid" value="">

            <div class="btnBox">
                <a href="javascript:;" class="publicOk" id="deltype">确定</a>
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

<!--修改类名-->
<div class="popUpset animated " id="editname" >
    <form action="" method="post">
        <div class="popTitle">
            <p>修改类名</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox">
            <div class="inputPop clearfix">
                <span class="secongTitle">分类名称：</span>
                <input id="catename" placeholder="" cid="" class="iptext" type="text" >
            </div>
            <p id="adderrormsg" ></p>
            <div class="btnBox">
                <a href="javascript:;" class="publicOk" id="savecate">确定</a><!--如果是子层弹窗，调用hidePop-2-->

            </div>
        </div>
    </form>
</div>

<script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/prompt.js"></script>
<script type="text/javascript">
    var site_url = '<?php echo site_url() ?>';
</script>

</body>
</html>