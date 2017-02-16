<!DOCTYPE html>
<html>
<head>
	<title>详情</title>

<meta charset="utf-8">
<link rel="shortcut icon" href="<?php echo base_url() ?>resources/imgs/public/title.ico">
<script type='text/javascript' src="<?php echo base_url() ?>resources/js/public/jquery-1.11.0.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/template.js"></script>
<link href="<?php echo base_url() ?>resources/css/public/reset.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/thirdparty/font-awesome-4.5.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/css/public/firstStart.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/css/public/content.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/css/public/filter.css" rel="stylesheet" type="text/css">
<script src="<?php echo base_url() ?>resources/thirdparty/WdatePicker/js/DateJs/WdatePicker.js" type="text/javascript"></script>
<link href="<?php echo base_url() ?>resources/css/public/animate.min.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/page.js"></script>
<script src="<?php echo base_url() ?>resources/js/teacher/class_edit.js" type='text/javascript'></script>


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
            <!--面包屑导航 start -->
            <div class="lable_title">
                <a href="<?php echo site_url('Classstaff/myclass');?>" title="班级管理" class="for_lable">班级管理</a>&gt;
                <a href="<?php echo site_url('Classstaff/myclass');?>" title="所有班级" class="for_lable">我的班级</a>&gt;
                <a>详情</a>
            </div>
            <!--面包屑导航 end-->
            <div class="classNews">
                <h3 class="lable_h3">班级：<?php echo $classname;?></h3>
                <div class="total clearfix ">
                    <a style="display:none;" href="javascript:;" id="addBtn" class="addBtn" onclick=""><span>+</span>新增学员</a>
                    <div class="search-a">
                        <input id="detailName" class="iptSearch-a" value="<?php echo $search;?>" placeholder="请输入学员姓名" type="text">
                        <i class="fa fa-search detailsear"></i>
                    </div>
                </div>
            </div>
            <table class="myarchlistTable" id="classTable">
                <thead>
                <tr class="table-title">
                    <td width="110">姓名</td>
                    <td width="50">性别</td>
                    <td width="240">工作单位</td>
                    <td width="140">电话</td>
                    <td width="210">邮箱</td>
                    <td >操作</td>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($student_list as $row): ?>
                    <tr>
                        <td title=""><?php echo $row['UserName']; ?></td>
                        <td title=""><?php echo $row['UserSex']; ?></td>
                        <td title=""><?php echo $row['UserDepartment']; ?></td>
                        <td title=""><?php echo $row['UserPhone']; ?></td>
                        <td title=""><?php echo $row['UserEmail']; ?></td>
                        <td>
                            <a href="javascript:;" class=" forRed delOne" code="<?php echo $row['UserID'];?>"><i class="fa fa-trash-o" ></i> 删除</a>
                        </td>
                    </tr>
                <?php endforeach; ?>

                </tbody>
            </table>
            <!--page.php start-->
            <?php if ($total_rows > 0): ?>
            <div id="selfPage" class="page">
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
                <?php endif;?>

            <!--page.php end-->
        </div>
		<!--right stop-->
	</div>

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

<!--提示框-->
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

<script type="text/javascript">
    var site_url = '<?php echo site_url() ?>';
    var classcode = "<?php echo $classid; ?>";

</script>
<script src="<?php echo base_url() ?>resources/js/public/prompt.js" type='text/javascript'></script>

</body>
</html>