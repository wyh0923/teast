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
    <link href="<?php echo base_url() ?>resources/thirdparty/huploadify/css/Huploadify.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/animate.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/firstStart.css" rel="stylesheet" type="text/css"> 
    <link href="<?php echo base_url() ?>resources/css/public/filter.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/content.css" rel="stylesheet" type="text/css">
   
    <script src="<?php echo base_url() ?>resources/thirdparty/WdatePicker/js/DateJs/WdatePicker.js" type="text/javascript"></script>
    <script src="<?php echo base_url() ?>resources/thirdparty/clipboard/clipboard.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>resources/thirdparty/huploadify/js/jquery.Huploadify.js"></script>

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
                    <h3 class="filterTitle">类　　型：</h3>
                    <div class="filterList">
                        <a title="全部" href="<?php echo get_url($page_url,'type');?>" class="<?php if ($type == ''): ?>filterCur<?php endif;?>">全部</a>
                        <?php foreach ($ctf_type as $k=>$v):?>
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
                <div class="filter clearfix ">
                    <h3 class="filterTitle">时间范围：</h3>
                    <div class="filterList">
                        <input id="stime" onfocus="WdatePicker({oncleared: function(){clearTime();},onpicked: function(){searchForTime();},dateFmt:'yyyy-MM-dd HH:mm:ss'})" class="Wdate" name="starttime" value="<?php if ($time):?><?php echo $time['starttime'];?><?php endif;?>" type="text"><span class="marAuto">至</span>
                        <input id="etime" onfocus="WdatePicker({oncleared: function(){clearTime();},onpicked: function(){searchForTime();},dateFmt:'yyyy-MM-dd HH:mm:ss'})" class="Wdate" name="endtime" value="<?php if ($time):?><?php echo $time['endtime'];?><?php endif;?>" type="text">
                    </div>
                </div>
            </div>
            <div class="total clearfix">
                <h3>共计：<?php echo $total_rows;?>个</h3>
                <a href="javascript:;" class="btnNew" id="addBtn"><span>+</span>新增ctf模板</a>
                <div class="search-a">
                    <input class="iptSearch-a" value="<?php echo $search;?>" name="Search" placeholder="请输入ctf模板名" type="text">
                    <i class="fa fa-search" ></i>
                </div>
            </div>
            <table class="ctflistTable" id="ctflistTable">
                <thead>
                <tr class="table-title">
                    <td class="" width="180">ctf模板名</td>
                    <td class="">场景内容</td>
                    <td class="" width="100">分类</td>
                    <td class="" width="180">操作</td>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($ctf_list as $row): ?>
                <tr>
                    <td title="<?php echo $row['CtfName']; ?>"><?php if($row['CtfUrl']):?><a href="<?php echo $row['CtfUrl']; ?>" target="_blank"><?php echo $row['CtfName']; ?></a><?php else:?><?php echo $row['CtfName']; ?><?php endif;?></td>
                    <td title="<?php echo $row['CtfContent']; ?>"><?php echo $row['CtfContent']; ?></td>
                    <td><?php echo $ctf_type[$row['CtfType']];?></td>
                    <td >
                        <a href="javascript:;" class="forYellow" code="<?php echo $row['CtfID'];?>"><i class="fa fa-search-plus"></i>详情</a>
                        <?php if($row['AuthorID'] == $this->userinfo['UserID']):?>
                            <a href="javascript:;" class="forBlue" code="<?php echo $row['CtfID'];?>"><i class="fa fa-edit"></i>编辑</a>
                            <a href="javascript:;" class="forRed" code="<?php echo $row['CtfID'];?>"><i class="fa fa-trash-o" ></i>删除</a>
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
<!--删除确认-->
<div class="popUpset animated " id="deleteOperation"  >
    <form action="" method="post">
        <div class="popTitle">
            <p>确认操作</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox">
            <p class="promptNews">确定要删除吗？</p>
            <div class="btnBox">
                <a href="javascript:;" class="publicOk delBtn" id="">确定</a>
                <a href="javascript:;" class="publicNo hidePop-1" id="">取消</a><!--如果是子层弹窗，调用hidePop-2-->

            </div>
        </div>
    </form>
</div>
<!--提示框-->
<div class="popUpset animated " id="okBox"  >
    <form action="" method="post">
        <div class="popTitle">
            <p>信息提示</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox">
            <p class="promptNews">要提示的信息</p>
        </div>
    </form>
</div>
<!--添加ctf模板-->
<div class="popUpset animated " id="addCtfTemplate"  >
    <form action="" method="post">
        <div class="popTitle">
            <p>新增ctf模板</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果只有一层弹窗，调用close-1-->
        </div>

        <div class="infoBox height-550">
            <p class="adderrormsg">* 当前版本只支持附件类型的ctf模板</p>
            <div class="box-input-cen clearfix">
                <span class="bigTitle"><nobr>*</nobr>模板名：</span>
                <input type="text" id="name" class="intBig" value="">

            </div>
            <div class="box-input-cen clearfix filterNobg">
                <div class="Filter">
                    <div class="filter clearfix ">
                        <h3 class="filterTitle">场景类型：</h3>
                        <div class="filterList addfilterList">
                            <?php foreach ($ctf_type as $k=>$v):?>
                            <a title="<?php echo $v;?>" href="javascript:;" class="filterctf" type="<?php echo $k;?>"><?php echo $v;?></a>
                            <?php endforeach;?>
                        </div>
                    </div>
                    <div class=" box-input-cen filter clearfix ">
                        <h3 class="filterTitle">难度：</h3>
                        <div class="filterList">
                            <a title="初级" href="javascript:;" class="typectf filterCur" diff="0">初级</a>
                            <a title="中级" href="javascript:;" class="typectf" diff="1">中级</a>
                            <a title="高级" href="javascript:;" class="typectf" diff="2">高级</a>

                        </div>
                    </div>
                </div>
            </div>
            <div class="box-input-cen clearfix">
                <span class="bigTitle"><nobr>*</nobr>场景内容：</span>
                <textarea type="text" id="desc" class="intBig height-120"></textarea>
            </div>
            <div class="box-input-cen upDownBox clearfix">
                <span class="label bigTitle"><nobr>*</nobr>上传资料：</span>
                <input type="hidden" value="" class="uploadIpt" id="uploadctfadd" disabled="true"　readOnly="true"/>
                <div id="adduploadBox" class="bigInput"></div>
            </div>
            <div class="box-input-cen popTable clearfix">
                <span class="label bigTitle">附件列表：</span>
                <table >
                    <thead>
                    <tr class="table-title" id="tableneirong">
                        <td width="120">附件名称</td>
                        <td >URL</td>
                        <td width="125" >操作</td>

                    </tr>
                    </thead>
                    <tbody id="ctfMoreList"></tbody>

                </table>

            </div>
            <p class="adderrormsg" id="errors"></p>
            <div class="btnBox">
                <a href="javascript:;" class="publicOk " id="inputaddBtn">确定</a>
            </div>
        </div>
    </form>
</div>
<!--编辑ctf模板-->
<div class="popUpset animated " id="editCtfTemplate" >
    <form action="" method="post">
        <div class="popTitle">
            <p>编辑ctf模板</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果只有一层弹窗，调用close-1-->
        </div>

        <div class="infoBox height-550">
            <div class="box-input-cen clearfix">
                <span class="bigTitle"><nobr>*</nobr>模板名：</span>
                <input type="text" id="ctfname" class="intBig" value="">
                <input type="hidden" value=""  id="ctfcode">

            </div>
            <div class="box-input-cen clearfix filterNobg">
                <div class="Filter">
                    <div class="filter clearfix ">
                        <h3 class="filterTitle">场景类型：</h3>
                        <div class="filterList">
                            <?php foreach ($ctf_type as $k=>$v):?>
                                <a title="<?php echo $v;?>" href="javascript:;" class="efilterctf" type="<?php echo $k;?>"><?php echo $v;?></a>
                            <?php endforeach;?>
                        </div>
                    </div>
                    <div class="filter clearfix ">
                        <h3 class="filterTitle">难度：</h3>
                        <div class="filterList">
                            <a title="初级" href="javascript:;" class="etypectf " diff="0">初级</a>
                            <a title="中级" href="javascript:;" class="etypectf" diff="1">中级</a>
                            <a title="高级" href="javascript:;" class="etypectf" diff="2">高级</a>

                        </div>
                    </div>
                </div>
            </div>
            <div class="box-input-cen clearfix">
                <span class="bigTitle"><nobr>*</nobr>场景内容：</span>
                <textarea type="text" id="ctfcontent" class="intBig height-120"></textarea>
            </div>
            <div class="box-input-cen upDownBox clearfix">
                <span class="label bigTitle"><nobr>*</nobr>上传资料：</span>
                <input type="hidden" value="" class="uploadIpt" id="uploadctf" disabled="true"　readOnly="true"/>
                <div id="uploadTool" class="bigInput"></div>
            </div>
            <div class="box-input-cen popTable clearfix">
                <span class="label bigTitle">附件列表：</span>
                <table >
                    <thead>
                    <tr class="table-title" id="tableneirong">
                        <td width="120">附件名称</td>
                        <td >URL</td>

                    </tr>
                    </thead>
                    <tbody  id="edit_ctfMoreList"></tbody>

                </table>

            </div>
            <div style="float: left;margin-left: 90px; margin-top: 15px;">
                <span class="fileeditname"></span>
            </div>
            <p class="adderrormsg" id="errorsInfo"></p>
            <div class="btnBox">
                <a href="javascript:;" class="publicOk " id="inputeditBtn">确定</a>
            </div>
        </div>
    </form>
</div>
<!--CTF模板详情-->
<div class="popUpset animated " id="ctfTemplateDetail"   >
    <form action="" method="post">
        <div class="popTitle">
            <p>ctf模板详情信息</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox">
            <div class="box-input-cen">
                <table class="noFl noSpace">
                    <tbody>
                    <tr>
                        <td width="120">名称：</td>
                        <td class="text CtfName"></td>
                    </tr>
                    <tr>
                        <td width="120">场景类型：</td>
                        <td class="text CtfType"></td>
                    </tr>
                    <tr>
                        <td width="120">难度：</td>
                        <td class="text CtfDiff"></td>
                    </tr>
                    <tr>
                        <td width="120">场景内容：</td>
                        <td class="text CtfContent"></td>
                    </tr>
                    <tr>
                        <td width="120">资源文件：</td>
                        <td class="text CtfResources"></td>
                    </tr>
                    </tbody>
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
    var time = "<?php if ($time):?><?php echo $time['starttime'].'_'.$time['endtime'];?><?php endif;?>";
    var ctftype = [];
    <?php foreach ($ctf_type as $k => $v): ?>
    ctftype['<?php echo $k;?>'] = "<?php echo $v;?>";
    <?php endforeach;?>
    var CtfDiff = {'0':'初级','1':'中级','2':'高级'};
</script>
<script src="<?php echo base_url() ?>resources/js/teacher/train_ctflist.js" type='text/javascript'></script>
</body>
</html>