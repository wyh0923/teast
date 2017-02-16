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
                    <h3 class="filterTitle">区域个数：</h3>
                    <div class="filterList">
                        <a title="全部" href="<?php echo get_url($page_url,'type');?>" class="<?php if ($type == ''): ?>filterCur<?php endif;?>">全部</a>
                        <?php foreach ($zone_type as $k=>$v):?>
                            <a title="<?php echo $v;?>" href="<?php echo get_url($page_url,'type',$k);?>" class="<?php if ($k == $type): ?>filterCur<?php endif;?>"><?php echo $v;?></a>
                        <?php endforeach;?>
                    </div>
                </div>
                <div class="filter clearfix ">
                    <h3 class="filterTitle">作　　者：</h3>
                    <div class="filterList">
                        <a title="全部" href="<?php echo get_url($page_url,'author');?>" class="<?php if ($author == ''): ?>filterCur<?php endif;?>">全部</a>
                        <?php foreach ($author_list as $k=>$v):?>
                            <a title="<?php echo $v['UserName'];?>" href="<?php echo get_url($page_url,'author',$v['UserID']);?>" class="<?php if ($v['UserID'] == $author): ?>filterCur<?php endif;?>"><?php echo $v['UserName'];?></a>
                        <?php endforeach;?>
                    </div>
                </div>
            </div>
            <div class="total clearfix ">
                <h3>共计：<?php echo $total_rows;?>套</h3>
                <a href="<?php echo site_url('Admintrain/scenecreate');?>" id="addBtn" class="btnNew">+新增场景</a>
                <div class="search-a">
                    <input class="iptSearch-a" value="<?php echo $search;?>" name="Search" placeholder="请输入场景名称" type="text">
                    <i class="fa fa-search"></i>
                </div>
            </div>
            <table class="scenetemplatelistTable" id="scenetemplatelistTable">
                <thead>
                <tr class="table-title">
                    <td width="250">场景名称</td>
                    <td class="">场景描述</td>
                    <td  width="60">区域个数</td>
                    <td width="175">操作</td>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($scene_list as $row): ?>
                <tr>
                    <td title="<?php echo $row['scene_name'];?>"><?php echo $row['scene_name'];?></td>
                    <td title="<?php echo $row['description'];?>"><?php echo $row['description'];?></td>
                    <td><?php echo $row['zone_count'];?></td>
                    <td>
                        <a href="javascript:;" class="forYellow" code="<?php echo $row['scene_tpl_uuid'];?>">
                        <i class="fa fa-search-plus"></i>详情</a>
                        <a href="javascript:;" class="forBlue" code="<?php echo $row['scene_tpl_uuid'];?>" tplname="<?php echo $row['scene_name'];?>">
                            <i class="fa fa-play-circle-o"></i>启动</a>
                        <?php if($row['author']):?>
                        <a href="javascript:;" class="forRed" code="<?php echo $row['scene_tpl_uuid'];?>">
                            <i class="fa fa-trash-o" ></i>删除</a>
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
<div class="popUpset animated " id="okBox">
    <form action="" method="post">
        <div class="popTitle">
            <p>提示信息</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox">
            <p class="promptNews">该场景已下发,不能删除</p><!--调用promptUp类大型提示框-->
        </div>
    </form>
</div>
<div class="popUpset animated " id="delSceneBox" style="z-index: 100000">
    <form action="" method="post">
        <div class="popTitle">
            <p>提示框</p>
            <a href="javascript:;" class="close close-1"></a>
        </div>
        <div class="infoBox">
            <p class="promptNews">确定结束正在下发的场景?</p>
            <div class="btnBox">
                <a href="javascript:;" class="publicOk " id="delBtn">确定</a>
                <a href="javascript:;" class="publicNo hidePop-1">取消</a>
            </div>
        </div>
    </form>
</div>
<!--启动测试确认-->
<div class="popUpset animated " id="scene_start" >
    <form action="" method="post">
        <div class="popTitle">
            <p>确认操作</p>
            <a href="javascript:;" id="close_issue" class="close "></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox">
            <p class="promptNews outHide"></p>
            <div class="btnBox requestBtn">
                <a href="javascript:;" class="publicOk createscene" id="scenestart">申请实验环境</a>
                <div class="taskprogr outHide sceneprogress">
                    <div class="taskpro">
                    </div>
                    <div class="resTxt" id="proTxt"></div>
                    <div class="stopBtn stopScene" title="取消">
                        <i class=" fa fa-ban"></i>
                    </div>
                </div>
                <a  href="javascript:;" class="publicOk outHide applysuccess">进入场景</a>
                <a href="javascript:;" class="publicOk" id="scenestop">结束，并下发场景</a>
            </div>

        </div>
    </form>
</div>
<!--删除确认-->
<div class="popUpset animated " id="scenetemplatelistPopBox" >
    <form action="" method="post">
        <div class="popTitle">
            <p>确认操作</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox">
            <p class="promptNews">确定要删除该场景吗？</p><!--调用promptUp类大型提示框-->
            <div class="btnBox">
                <a href="javascript:;" class="publicOk okBtn" id="">确定</a>
                <a href="javascript:;" class="publicNo hidePop-1" id="">取消</a><!--如果是子层弹窗，调用hidePop-2-->
            </div>
        </div>
    </form>
</div>
<!--场景模板详情-->
<div class="popUpset animated " id="sceneTemplateDetail" >
    <form action="" method="post">
        <div class="popTitle">
            <p>场景模板详情</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox onCanBg">
            <div class="box-input-cen ctfPopNews">
                <table>

                    <tr>
                        <td width="25%">场景名称：</td>
                        <td class="scenename"></td>
                    </tr>
                    <tr>
                        <td>区域个数：</td>
                        <td class="zonecount"></td>
                    </tr>
                    <tr>
                        <td>创建时间：</td>
                        <td class="createtime"></td>
                    </tr>
                    <tr>
                        <td>场景描述：</td>
                        <td class="scenedesc"></td>
                    </tr>
                    <tr>
                        <td>操作区：</td>
                        <td class="oper"></td>
                    </tr>
                    <tr class="outHide">
                        <td>LAN1：</td>
                        <td class="LAN1"></td>
                    </tr>
                    <tr class="outHide">
                        <td>LAN2：</td>
                        <td class="LAN2"></td>
                    </tr>
                    <tr class="outHide">
                        <td>LAN3：</td>
                        <td class="LAN3"></td>
                    </tr>


                </table>


            </div>
            <div class="btnBox">
                <a href="javascript:;" class="publicOk hidePop-1" id="">确定</a><!--如果是子层弹窗，调用hidePop-2-->
            </div>

        </div>
    </form>
</div>
<script src="<?php echo base_url() ?>resources/js/public/prompt.js" type='text/javascript'></script>
<script type="text/javascript">
    var site_url = '<?php echo site_url();?>';
    var base_url = '<?php echo base_url();?>';
    var search = "<?php echo $search; ?>";
    var zonetype = [];
    <?php foreach ($zone_type as $k => $v): ?>
    zonetype['<?php echo $k;?>'] = "<?php echo $v;?>";
    <?php endforeach;?>
</script>
<script src="<?php echo base_url() ?>resources/js/admin/train_scenelist.js" type='text/javascript'></script>
</body>
</html>