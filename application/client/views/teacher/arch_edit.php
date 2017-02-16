<!DOCTYPE html>
<html>
<head>
	<title>编辑体系</title>

<meta charset="utf-8">
<link rel="shortcut icon" href="<?php echo base_url() ?>resources/imgs/public/title.ico">
<link href="<?php echo base_url() ?>resources/css/public/animate.min.css" rel="stylesheet" type="text/css">

<script type='text/javascript' src="<?php echo base_url() ?>resources/js/public/jquery-1.11.0.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/template.js"></script>
<link href="<?php echo base_url() ?>resources/css/public/reset.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/thirdparty/font-awesome-4.5.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/thirdparty/self-ajax-pagination/css/self-ajax-pagination.css" type="text/css" rel="stylesheet"/>

<link href="<?php echo base_url() ?>resources/css/public/firstStart.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/css/public/content.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/css/public/filter.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/css/admin/architecturelist.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="<?php echo base_url() ?>resources/thirdparty/self-ajax-pagination/js/self-ajax-pagination.js"></script>

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
            <div class="structureHead clearfix">
                <!--面包屑导航 start-->
                <div class="lable_title">
                    <a href="<?php echo site_url('Subject/mysystem')?>" title="知识体系管理" class="for_lable">知识体系管理</a>&gt;
                    <a href="<?php echo site_url('Subject/mysystem')?>" title="知识体系管理" class="for_lable">我的体系</a>&gt;
                    <a>编辑体系</a>
                </div>

                <!--面包屑导航  end-->
                <div id="structContainer">
                    <div id="structurBody" class="structurBody">
                        <ul id="lessonList" class="lessonList">
                            <li class="itemChaper clearfix">
                                <div class="itemContent"><span title="">培训方案：<?php echo $plan[0]['ArchitectureName']?></span></div>
                                <div class="itemActions forByYellow" code="<?php echo $pid ?>">
                                    <span class="adddBtn" id="addsys"><i class="fa fa-plus-circle fa-lg"></i>新增课程体系</span>
                                    <span class="adddBtn" id="editsys"><i class="fa fa-edit"></i>编辑</span>
                                </div>
                            </li>
                            <?php foreach ($sys as $s):?>
                                <li class="itemChaper marginLeft15 clearfix">
                                    <div class="itemContent">
                                        <span title="">课程体系：<?php echo $s['ArchitectureName'] ?></span><p>课程数: <?php echo $s['counts'] ?></p>
                                    </div>
                                    <div class="itemActions forByYellow" code="<?php echo $s['ArchitectureID'] ?>" name="<?php echo $s['ArchitectureName'] ?>">
                                        <span class="adddBtn selcour"><i class="fa fa-plus-circle fa-lg"></i>选择课程</span>
                                        <span class="adddBtn modsys"><i class="fa fa-edit"></i>编辑</span>
                                        <span class="adddBtn delsys" ><i class="fa fa-trash-o"></i>删除</span>
                                    </div>
                                </li>
                                <?php if(!empty($s['courses'])):?>
                                    <?php foreach ($s['courses'] as $c):?>
                                        <li class="itemChaper itemLesson clearfix">
                                            <div class="itemLine"></div>
                                            <div class="itemContent"><span title="">课程：<?php echo $c['PackageName'] ?></span><p>理论节:<span class="number"><?php echo $c['TheorySectionNum']?></span>实践节:<span class="number"><?php echo $c['PracticeSectionNum']?></span></p>
                                            </div>
                                            <div class="itemActions forByYellow" code="<?php echo $c['PackageID'] ?>">
                                                <span class="adddBtn delcourse"><i class="fa fa-trash-o"></i>删除</span>
                                            </div>
                                        </li>
                                    <?php endforeach;?>
                                <?php endif;?>
                            <?php endforeach;?>

                        </ul>
                    </div>
                </div>

            </div>
        </div>
	<!--right stop-->
	</div>


    <!--center stop-->
    <!--footer start-->
    <?php $this->load->view('public/footer.php')?>

    <!--footer stop-->
</div>

<div class="maskbox"></div>
<!--删除子体系确认-->
<div class="popUpset animated " id="delsys" >
    <form >
        <div class="popTitle">
            <p>确认操作</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox">
            <p class="promptNews">确定要删除该体系吗？</p>
            <input type="hidden" name="archcode" id="" value="">
            <input type="hidden" name="ArchitectureParent" id="" value="">
            <div class="btnBox">
                <a href="javascript:;" class="publicOk " id="ok">确定</a>
                <a href="javascript:;" class="publicNo hidePop-1" id="">取消</a><!--如果是子层弹窗，调用hidePop-2-->

            </div>
        </div>
    </form>
</div>
<!--删除课程确认-->
<div class="popUpset animated " id="delcourse">
    <form action="" method="post">
        <div class="popTitle">
            <p>确认操作</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox">
            <p class="promptNews">确定要删除该课程吗？</p>
            <input type="hidden" name="bookcode" id="" value="">
            <input type="hidden" name="bookcode" id="" value="">
            <input type="hidden" name="" value="">
            <div class="btnBox">
                <a href="javascript:;" class="publicOk " id="ok">确定</a>
                <a href="javascript:;" class="publicNo hidePop-1" id="">取消</a><!--如果是子层弹窗，调用hidePop-2-->

            </div>
        </div>
    </form>
</div>
<!--提示框-->
<div class="popUpset animated " id="okBox" >
    <form>
        <div class="popTitle">
            <p>确认操作</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox">
            <p class="promptNews"></p>

        </div>
    </form>
</div>
<!--编辑培训方案-->
<div class="popUpset animated " id="modsys">
    <form>
        <div class="popTitle">
            <p>编辑培训方案</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox">
            <div class="inputPop clearfix">
                <span class="secongTitle "><nobr>*</nobr>方案名称：</span>
                <input id=""  value="<?php echo $plan[0]['ArchitectureName']?>" class="iptext" type="text"  >
            </div>
            <p class="adderrormsg"></p>
            <div class="btnBox">
                <a href="javascript:;" class="publicOk" id="ok">确定</a><!--如果是子层弹窗，调用hidePop-2-->

            </div>
        </div>
    </form>
</div>
<!--新增课程体系-->
<div class="popUpset animated " id="addsBox" >
    <form >
        <div class="popTitle">
            <p>新增课程体系</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox">
            <div class="inputPop clearfix">
                <span class="secongTitle"><nobr>*</nobr>体系名称：</span>
                <input id="name"  value="" class="iptext" type="text"  >
            </div>
            <div class="inputPop clearfix">
                <span class="secongTitle"><nobr>*</nobr>所属培训方案：</span>
                <input id=""  value="<?php echo $plan[0]['ArchitectureName']?>" class="iptext noBorderInt" type="text" maxlength="30">
            </div>
            <p id="adderrormsg"></p>
            <div class="btnBox">
                <a href="javascript:;" class="publicOk" id="addOk">确定</a><!--如果是子层弹窗，调用hidePop-2-->

            </div>
        </div>
    </form>
</div>
<!--编辑课程体系信息-->
<div class="popUpset animated " id="editsBox" >
    <form>
        <div class="popTitle">
            <p>编辑课程体系信息</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox">
            <div class="inputPop clearfix">
                <span class="secongTitle"><nobr>*</nobr>体系名称：</span>
                <input id="sysname"  value="" class="iptext" type="text" >
            </div>
            <div class="inputPop clearfix">
                <span class="secongTitle"><nobr>*</nobr>所属方案：</span>
                <select id="onearchite" class="iptext">
                    <?php foreach ($train as $t):?>
                        <option <?php if($pid == $t['ArchitectureID']) echo'selected'?> value="<?php echo $t['ArchitectureID']?>"><?php echo $t['ArchitectureName']?></option>
                    <?php endforeach;?>
                </select>
            </div>
            <p class="adderrormsg"></p>
            <div class="btnBox">
                <a href="javascript:;" class="publicOk" id="ok">确定</a><!--如果是子层弹窗，调用hidePop-2-->

            </div>
        </div>
    </form>
</div>
<!--选择课程-->
<div class="popUpset animated " id="selcourse" >
        <div class="popTitle">
            <p>选择课程</p>
            <a href="javascript:;" id="" class="close close-3"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox height-550">
            <div class="box-margin-cen">
                <div class="Filter">
                    <div class="filter clearfix ">
                        <h3 class="filterTitle">培训方案：</h3>
                        <div class="filterList">
                            <a href="javascript:void(0)" id="planFilterItem_all" value="" onclick="planFilterBtnClk('planFilterItem_all')" class="planFilterBtn cpukur filterCur" type="">全部</a>
                            <?php foreach ($trains as $t):?>
                                <a title="" href="javascript:;" class="planFilterBtn cpukur" id="planFilterItem_<?php echo $t['ArchitectureID']?>" onclick="planFilterBtnClk('planFilterItem_<?php echo $t['ArchitectureID']?>')" value="<?php echo $t['ArchitectureID']?>" type="<?php echo $t['ArchitectureID']?>" ><?php echo $t['ArchitectureName']?></a>
                            <?php endforeach;?>
                            <div style="display:none" id="planFilterPlaceHolder"></div>
                        </div>
                    </div>
                    <div class="filter clearfix ">
                        <h3 class="filterTitle">课程体系：</h3>
                        <div class="filterList">
                            <a href="javascript:void(0)" id="sysFilterItem_all" value="" class="sysFilterBtn ostypekur filterCur" onclick="sysFilterBtnClk('sysFilterItem_all')" os="">全部</a>
                            <?php foreach ($csys as $s):?>
                                <a title="" href="javascript:;" onclick="sysFilterBtnClk('sysFilterItem_<?php echo $s['ArchitectureID']?>')" id="sysFilterItem_<?php echo $s['ArchitectureID']?>" class="sysFilterBtn ostypekur" value="<?php echo $s['ArchitectureName']?>" os="<?php echo $s['ArchitectureID']?>"><?php echo $s['ArchitectureName']?></a>
                            <?php endforeach;?>
                            <div style="display:none" id="sysFilterPlaceHolder"></div>
                        </div>
                    </div>
                    <div class="filter clearfix ">
                        <h3 class="filterTitle">类　　型：</h3>
                        <div class="filterList">
                            <a href="javascript:void(0)" id="typeItem_all" onclick="typeBtn('typeItem_all')" value="" class="typeFilterBtn filterCur" code="">全部</a>
                            <a href="javascript:void(0)" id="typeItem_1" onclick="typeBtn('typeItem_1')" value="1" class="typeFilterBtn" code="1">理论课程</a>
                            <a href="javascript:void(0)" id="typeItem_2" onclick="typeBtn('typeItem_2')" value="2" class="typeFilterBtn" code="2">实践课程</a>

                        </div>
                    </div>
                    <div class="filter clearfix ">
                        <h3 class="filterTitle">作　　者：</h3>
                        <div class="filterList">
                            <a href="javascript:void(0)" id="author_all" onclick="authorItem('author_all')" class="authorFilterBtn filterCur" code="">全部</a>
                            <?php foreach ($author as $a): ?>
                                <a href="javascript:void(0)" id="author_<?php echo $a['UserID']?>" onclick="authorItem('author_<?php echo $a['UserID']?>')" class="authorFilterBtn" code="<?php echo $a['UserName']?>"><?php echo $a['UserName']?></a>
                            <?php endforeach; ?>
                            <div style="display:none" id="authorFilterPlaceHolder"></div>
                        </div>
                    </div>
                </div>
                <div class="total clearfix">
                    <h3>共计：<span id="cnum"><?php echo $coursenum ?></span>门/<span id="snum"><?php echo $sectionnum ?></span>节</h3>
                    <a href="javascript:void(0)" id="SortForDiff" class="memorycur filterCur"  type="fa-sort">难度等级<i class="fa fa-sort"></i></a>
                    <a href="javascript:void(0)" id="SortForTime" class="diskcur filterCur"  type="fa-sort">课程时长<i class="fa fa-sort"></i></a>
                    <div class="search-a">
                        <input type="text" id="sapSearch_pageContainer" class="iptSearch-a ensearch" value="" name="Search" placeholder="请输入关键字搜索">
                        <i class="fa fa-search clsearch" onclick=""></i>
                    </div>
                </div>
            </div>
            <div class="box-margin-cen popTable clearfix">
                <table >
                    <thead>
                    <tr class="table-title">
                        <td width="60">选中</td>
                        <td >课程名称</td>
                        <td width="120">作者</td>
                        <td width="80">小节数量</td>

                    </tr>
                    </thead>
                    <tbody id=""></tbody>
                    <tbody id="tbody"></tbody>
                </table>
            </div>
            <div class="noNews" >
                <i class="fa fa-file-text" aria-hidden="true"></i><span>没有找到数据......</span>
            </div>
            <div id="pageContainer"></div>
            <script type="text/javascript">
                showSelfAjaxPagination('pageContainer', '<?php echo site_url() ?>'+'Subject/ajax_course', "sapSuc");
            </script>
            <div class="btnBox">
                <a href="javascript:;" class="publicOk" id="ok">确定</a><!--如果是子层弹窗，调用hidePop-2-->

            </div>
        </div>
</div>


<script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/prompt.js"></script>
<script type="text/javascript">
    var site_url = '<?php echo site_url() ?>';
    var apid = "<?php echo $pid ?>";
</script>
<script type="text/javascript" src="<?php echo base_url() ?>resources/js/teacher/plan_edit.js"></script>

</body>
</html>