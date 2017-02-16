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
    <link href="<?php echo base_url() ?>resources/css/public/animate.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/firstStart.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/content.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/filter.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/admin/ctf.css" rel="stylesheet" type="text/css">

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
                    <h3 class="filterTitle">C P U ：</h3>
                    <div class="filterList">
                        <a title="全部" href="<?php echo get_url($page_url,'cpu');?>" class="<?php if ($cpu == ''): ?>filterCur<?php endif;?>">全部</a>
                        <?php foreach ($cpu_type as $k=>$v):?>
                            <a title="<?php echo $v;?>" href="<?php echo get_url($page_url,'cpu',$k);?>" class="<?php if ($k == $cpu): ?>filterCur<?php endif;?>"><?php echo $v;?></a>
                        <?php endforeach;?>
                    </div>
                </div>
                <div class="filter clearfix ">
                    <h3 class="filterTitle">内　　存：</h3>
                    <div class="filterList">
                        <a title="全部" href="<?php echo get_url($page_url,'memory');?>" class="<?php if ($memory == ''): ?>filterCur<?php endif;?>">全部</a>
                        <?php foreach ($memory_type as $k=>$v):?>
                            <a title="<?php echo $v;?>" href="<?php echo get_url($page_url,'memory',$k);?>" class="<?php if ($k == $memory): ?>filterCur<?php endif;?>"><?php echo $v;?></a>
                        <?php endforeach;?>
                    </div>
                </div>
                <div class="filter clearfix ">
                    <h3 class="filterTitle">操作系统：</h3>
                    <div class="filterList">
                        <a title="全部" href="<?php echo get_url($page_url,'os');?>" class="<?php if ($os == ''): ?>filterCur<?php endif;?>">全部</a>
                        <?php foreach ($os_type as $v):?>
                            <a title="<?php echo $v['os_name'];?>" href="<?php echo get_url($page_url,'os',$v['id']);?>" class="<?php if ($v['id'] == $os): ?>filterCur<?php endif;?>"><?php echo $v['os_name'];?></a>
                        <?php endforeach;?>
                    </div>
                </div>
            </div>
            <div class="total clearfix">
                <h3>共计：<?php echo $total_rows;?>个</h3>
                <a href="<?php echo site_url('Train/add_vm');?>" class="btnNew" id="addBtn"><span>+</span>新增虚拟机</a>
                <div class="search-a">
                    <input class="iptSearch-a" value="<?php echo $search;?>" name="Search" placeholder="请输入虚拟模板名" type="text">
                    <i class="fa fa-search"></i>
                </div>
            </div>
            <table class="vmtemplatelistTable" id="vmtemplatelistTable">
                <thead>
                <tr class="table-title">
                    <td  width="150">虚拟机模板名</td>
                    <td  width="42">cpu</td>
                    <td  width="70">内存</td>
                    <td  width="160">操作系统</td>
                    <td width="210">漏洞信息</td>
                    <td >操作</td>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($vm_list as $row): ?>
                <tr>
                    <td title="<?php echo $row['vm_display_name'];?>"><?php echo $row['vm_display_name'];?></td>
                    <td><?php echo $row['cpu'];?></td>
                    <td title="<?php echo $row['memory_size'].$row['memory_size_unit'];?>"><?php echo $row['memory_size'].$row['memory_size_unit'];?></td>
                    <td title="<?php echo $row['os_type_name'];?>"><?php echo $row['os_type_name'];?></td>
                    <td title="<?php echo $row['description']; ?>"><?php echo $row['description']; ?></td>
                    <td>

                        <a href="javascript:;" class="forYellow" code="<?php echo $row['vm_tpl_uuid']; ?>" host_id="<?php echo $row['host_id']; ?>">
                            <i class="fa fa-search-plus" ></i>详情</a>
                        <a  target="_blank" href="<?php echo site_url('Train/edit_vm').'?code='.$row['vm_tpl_uuid'].'&host_id='.$row['host_id'];?>" class=" forBlue">
                            <i class="fa fa-edit"></i>编辑</a>
                        <?php if ($row['author']):?>
                        <a href="javascript:;" class=" forRed" code="<?php echo $row['vm_tpl_uuid']; ?>" host_id="<?php echo $row['host_id']; ?>">
                            <i class="fa fa-trash-o"></i>删除</a>
                        <?php endif;?>
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
<!--确认提示-->
<div class="popUpset animated " id="okBox"  >
    <div class="popTitle">
        <p>提示操作</p>
        <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
    </div>
    <div class="infoBox">
        <p class="promptNews">提示信息</p><!--调用promptUp类大型提示框-->
        <!--<div class="btnBox">
            <a href="javascript:;" class="publicOk" id="">确定</a>
            <a href="javascript:;" class="publicNo hidePop-1" id="">取消</a><!--如果是子层弹窗，调用hidePop-2
        </div>-->

    </div>
</div>
<!--操作确认-->
<div class="popUpset animated " id="vmtemplatelistTablePopBox" >
    <form action="" method="post">
        <div class="popTitle">
            <p>确认操作</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox">
            <p class="promptNews">确定要删除该模板吗？</p><!--调用promptUp类大型提示框-->
            <input type="hidden" name="" id="" value="">
            <input type="hidden" name="" id="" value="">
            <div class="btnBox">
                <a href="javascript:;" class="publicOk okBtn" id="">确定</a>
                <a href="javascript:;" class="publicNo hidePop-1" id="">取消</a><!--如果是子层弹窗，调用hidePop-2-->
            </div>
        </div>
    </form>
</div>
<!--虚拟机模板详情-->
<div class="popUpset animated " id="vmTemplateDetail">
    <form action="" method="post">
        <div class="popTitle">
            <p>虚拟机模板详情</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox onCanBg">
            <div class=" box-input-cen ctfPopNews">
                <table>


                    <tr>
                        <td width="25%">虚拟机名称：</td>
                        <td class="vmName"></td>
                    </tr>
                    <tr>
                        <td>cpu：</td>
                        <td class="vmCpu"></td>
                    </tr>
                    <tr>
                        <td>硬盘：</td>
                        <td class="vmDisk"></td>
                    </tr>
                    <tr>
                        <td>内存：</td>
                        <td class="vmMemory"></td>
                    </tr>
                    <tr>
                        <td>操作系统：</td>
                        <td class="vmSystem"></td>
                    </tr>
                    <tr>
                        <td>登录账号：</td>
                        <td class="username"></td>
                    </tr>
                    <tr>
                        <td>登录密码：</td>
                        <td class="userpassword"></td>
                    </tr>
                    <tr>
                        <td>漏洞信息：</td>
                        <td class="vmbug"></td>
                    </tr>



                </table>


            </div>
            <div class="btnBox">
                <a href="javascript:;" class="publicOk hidePop-1" id="">确定</a><!--如果是子层弹窗，调用hidePop-2-->
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    var site_url = '<?php echo site_url();?>';
    var search = "<?php echo $search; ?>";
    var cpu = "<?php echo $cpu; ?>";
    var memory = "<?php echo $memory; ?>";
    var os = "<?php echo $os; ?>";
    var cputype = [];
    <?php foreach ($cpu_type as $k => $v): ?>
    cputype['<?php echo $k;?>'] = "<?php echo $v;?>";
    <?php endforeach;?>
</script>
<script src="<?php echo base_url() ?>resources/js/public/prompt.js" type='text/javascript'></script>
<script src="<?php echo base_url(); ?>resources/js/teacher/train_vmlist.js" type='text/javascript'></script>
</body>
</html>