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
    <link href="<?php echo base_url() ?>resources/css/public/animate.min.css" rel="stylesheet" type="text/css">

    <script src="<?php echo base_url() ?>resources/thirdparty/WdatePicker/js/DateJs/WdatePicker.js" type="text/javascript"></script>

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
                    <h3 class="filterTitle">时间范围：</h3>
                    <div class="filterList">
                        <input id="stime" onfocus="WdatePicker({oncleared: function(){clearTime();},onpicked: function(){searchForTime();},dateFmt:'yyyy-MM-dd HH:mm:ss'})" class="Wdate" name="starttime" value="<?php if ($time):?><?php echo $time['starttime'];?><?php endif;?>" type="text"><span class="marAuto">至</span>
                        <input id="etime" onfocus="WdatePicker({oncleared: function(){clearTime();},onpicked: function(){searchForTime();},dateFmt:'yyyy-MM-dd HH:mm:ss'})" class="Wdate" name="endtime" value="<?php if ($time):?><?php echo $time['endtime'];?><?php endif;?>" type="text">
                    </div>
                </div>
            </div>
            <div class="total clearfix">
                <h3>共计：<?php echo $total_rows;?>班</h3>
                <a href="javascript:;" onclick="delAllTeacher()" id="delAllBtn" class="btnNew delyoure">删除选中班级</a>
                <a href="<?php echo site_url('/User/addclass');?>" id="addBtn" class="btnNew"><span>+</span>新建班级</a>


                <div class="search-a">
                    <input id="stuName" class="iptSearch-a" value="<?php echo $search;?>" placeholder="请输入班级名称" type="text">
                    <i class="fa fa-search"></i>
                </div>
            </div>
            <table class="classList addClasName" id="classTable" >
                <thead>
                <tr class="table-title">
                    <td width="60"><input type="checkbox" id="checkAll">全选</td>
                    <td width="200">班级名称</td>
                    <td  width="110" id="CreateTime" code="<?php if ($sort && $sort['field']=='CreateTime'):?><?php echo $sort['order'];?><?php endif;?>">
                        <a>创建时间<i class="fa <?php if ($sort && $sort['field']=='CreateTime' && $sort['order']=='DESC'):?>fa-sort-alpha-desc
                            <?php elseif ($sort && $sort['field']=='CreateTime' && $sort['order']=='ASC'):?>fa-sort-alpha-asc
                            <?php else:?>fa-sort<?php endif;?>
                            "></i></a>
                    </td>
                    <td width="85" id="StudentNum" code="<?php if ($sort && $sort['field']=='StudentNum'):?><?php echo $sort['order'];?><?php endif;?>">
                        <a>班级人数<i class="fa <?php if ($sort && $sort['field']=='StudentNum' && $sort['order']=='DESC'):?>fa-sort-alpha-desc
                            <?php elseif ($sort && $sort['field']=='StudentNum' && $sort['order']=='ASC'):?>fa-sort-alpha-asc
                            <?php else:?>fa-sort<?php endif;?>
                            "></i></a>
                    </td>
                    <td width="93" id="TaskNum" code="<?php if ($sort && $sort['field']=='TaskNum'):?><?php echo $sort['order'];?><?php endif;?>">
                        <a>下发任务数<i class="fa <?php if ($sort && $sort['field']=='TaskNum' && $sort['order']=='DESC'):?>fa-sort-alpha-desc
                            <?php elseif ($sort && $sort['field']=='TaskNum' && $sort['order']=='ASC'):?>fa-sort-alpha-asc
                            <?php else:?>fa-sort<?php endif;?>
                            "></i></a>
                    </td>
                    <td  width="95" id="TaskScore" code="<?php if ($sort && $sort['field']=='TaskScore'):?><?php echo $sort['order'];?><?php endif;?>">
                        <a>班级总积分<i class="fa <?php if ($sort && $sort['field']=='TaskScore' && $sort['order']=='DESC'):?>fa-sort-alpha-desc
                            <?php elseif ($sort && $sort['field']=='TaskScore' && $sort['order']=='ASC'):?>fa-sort-alpha-asc
                            <?php else:?>fa-sort<?php endif;?>
                            "></i></a>
                    </td>
                    <td width="">操作</td>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($classes_list as $row): ?>
                <tr>
                    <td> <input type="checkbox" name="checkTeacher" data-code="<?php echo $row['ClassID'];?>"></td>
                    <td title="<?php echo $row['ClassName']; ?>"><?php echo $row['ClassName']; ?></td>
                    <td><?php echo date("Y-m-d",$row['CreateTime']); ?></td>
                    <td><?php echo $row['StudentNum']; ?></td>
                    <td><?php echo $row['TaskNum']; ?></td>
                    <td><?php echo $row['TaskScore']; ?></td>

                    <td>
                        <a href="<?php echo site_url('User/classdetail').'/classid/'.$row['ClassID'];?>" class="forYellow"><i class="fa fa-search-plus" ></i>详情</a>
                        <a href="<?php echo site_url('User/editclass').'/classid/'.$row['ClassID'];?>" class="forBlue" code="<?php echo $row['ClassID'];?>"><i class="fa fa-edit"> </i>编辑</a>
                        <a href="javascript:;" class="forRed" code="<?php echo $row['ClassID'];?>"><i class="fa fa-trash " ></i>删除 </a>
                    </td>
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
<!--多选删除确认-->
<div class="popUpset animated " id="delAll">
    <form action="" method="post">
        <div class="popTitle">
            <p>确认操作</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox">
            <p class="promptNews">请确认是否删除所有选中的班级？</p>

            <div class="btnBox">
                <a href="javascript:;" class="publicOk " id="delAllTeacherBtn">确定</a>
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
<script src="<?php echo base_url() ?>resources/js/public/prompt.js" type='text/javascript'></script>
<script type="text/javascript">
    var site_url = '<?php echo site_url() ?>';
    var search = "<?php echo $search; ?>";
    var time = "<?php if ($time):?><?php echo $time['starttime'].'_'.$time['endtime'];?><?php endif;?>";
</script>
<script src="<?php echo base_url() ?>resources/js/admin/user_classes.js" type='text/javascript'></script>
</body>
</html>