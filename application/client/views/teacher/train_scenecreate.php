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
    <link href="<?php echo base_url() ?>resources/css/public/animate.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/firstStart.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/content.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/filter.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/admin/vmlayout.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/admin/ctf.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/thirdparty/self-ajax-pagination/css/self-ajax-pagination.css" type="text/css" rel="stylesheet"/>
    <script type="text/javascript">
        var site_url = '<?php echo site_url();?>';
        var base_url = '<?php echo base_url();?>';
        var versions = "<?php echo $versions;?>";
    </script>
    <script type="text/javascript" src="<?php echo base_url(); ?>resources/thirdparty/self-ajax-pagination/js/self-ajax-pagination.js"></script>
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
            <div  class="createCtfName">

                <div class="name clearfix">
                    <span><nobr>*</nobr>场景名称：</span>
                    <input id="scenename"  maxlength="255" type="text">
                </div>

                <div class="descroe clearfix">
                    <span><nobr>*</nobr>场景描述：</span>
                    <textarea id="scenedesc" class="scenedesc" maxlength="250" type="text" ></textarea>
                </div>
            </div>

            <div id="scene_topo_layout" class="scene_create"></div>
            <p class="adderrormsg errors"></p>
            <div class="scenediv"><a href="javascript:;" class="createscene">创建场景</a></div>
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
            <a href="javascript:;" id="" class="close close-3"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox">
            <p class="promptNews">提示信息</p><!--调用promptUp类大型提示框-->
            <!--<div class="btnBox">
                <a href="javascript:;" class="publicOk" id="">确定</a>
                <a href="javascript:;" class="publicNo hidePop-1" id="">取消</a><!--如果是子层弹窗，调用hidePop-2
            </div>-->

        </div>
</div>
<!--选择模板-->
<div class="popUpset animated " id="vmTemplatelist">
        <div class="popTitle ">
            <p class="bigW760">选择模板</p>
            <a href="javascript:;" id="" class="close "></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox height-550 onCanBg">
            <div class="bigInfo">
                <div class="Filter">
                    <div class="filter clearfix ">
                        <h3 class="filterTitle">模板类型：</h3>
                        <div class="filterList">
                            <a title="全部" href="javascript:;" class="cpukur filterCur" type="0">全部</a>
                            <a title="操作机" href="javascript:;" class="cpukur" type="2">操作机 </a>
                            <a title="目标机" href="javascript:;" class="cpukur" type="3">目标机</a>
                        </div>
                    </div>
                    <div class="filter clearfix ">
                        <h3 class="filterTitle">操作系统：</h3>
                        <div class="filterList">
                            <a title="全部" href="javascript:;" class="ostypekur filterCur" os="">全部</a>
                            <?php foreach ($os_type as $row):?>
                            <a title="<?php echo $row['os_name'];?>" href="javascript:;" class="ostypekur" os="<?php echo $row['id'];?>"><?php echo $row['os_name'];?></a>
                            <?php endforeach;?>

                        </div>
                    </div>
                </div>
                <div class="total clearfix">
                    <h3>已选：<span>0</span>&nbsp;台</h3>
                    <div class="search-a">
                        <input type="text" id="sapSearch_pageContainer" class="iptSearch-a question-exam" name="Search" value="" placeholder="请输入关键字搜索">
                        <i class="fa fa-search"></i>
                    </div>

                </div>
                <div class="popTable">
                    <table class="" id="vmtemplatelistTable">
                        <thead>
                        <tr class="table-title">
                            <td width="40">选中</td>
                            <td width="180">虚拟模板名</td>
                            <td width="90">创建者</td>
                            <td width="110">创建时间</td>
                            <td width="110">操作系统</td>
                            <td width="60">OS版本</td>
                            <td width="105">所属服务器</td>
                            <td width="" class="vmbug" num="2"><a class="downAll" onclick="downAll(this)">漏洞信息<i class="fa fa-angle-double-right updown"></i></a></td>
                        </tr>
                        </thead>
                        <tbody id="ques" class="moban"></tbody>
                    </table>
                    <div id="pageContainer"></div>
                    <!--无数据提醒-->
                    <div class="noNews" >
                        <i class="fa fa-file-text" aria-hidden="true"></i><span>没有找到数据......</span>
                    </div>
                    <script type="text/javascript">
                        showSelfAjaxPagination('pageContainer', site_url+'Train/get_vm_list', "sapSuc");
                    </script>

                </div>


            </div>

            <div class="btnBox">
                <a href="javascript:;" class="publicOk" id="detailBtn">确定</a>
                <a href="javascript:;" class="publicNo " id="">取消</a><!--如果是子层弹窗，调用hidePop-2-->
            </div>

        </div>
</div>
<script src="<?php echo base_url() ?>resources/js/public/prompt.js" type='text/javascript'></script>

<script type="text/javascript" src="<?php echo base_url();?>resources/js/teacher/vmlayout/vmlayout.js" ></script>
<script src="<?php echo base_url(); ?>resources/js/teacher/vmlistbox.js" type='text/javascript'></script>
<script src="<?php echo base_url(); ?>resources/js/teacher/train_scenecreate.js" type='text/javascript'></script>
</body>
</html>