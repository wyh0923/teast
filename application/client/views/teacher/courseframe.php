<!DOCTYPE html>
<html>
<head>
	<title>课程结构</title>

<meta charset="utf-8">
<link rel="shortcut icon" href="<?php echo base_url() ?>resources/imgs/public/title.ico">
<link href="<?php echo base_url() ?>resources/css/public/animate.min.css" rel="stylesheet" type="text/css">

<script type='text/javascript' src="<?php echo base_url() ?>resources/js/public/jquery-1.11.0.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/template.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>resources/js/teacher/book.js"></script>
<link href="<?php echo base_url() ?>resources/css/public/reset.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/thirdparty/font-awesome-4.5.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/css/public/animate.min.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/css/public/firstStart.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/css/public/content.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/css/public/filter.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/css/admin/architecturelist.css" rel="stylesheet" type="text/css">



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
                    <a href="<?php echo site_url('Subject/mybook')?>" title="知识体系管理" class="for_lable">知识体系管理</a>&gt;
                    <a href="<?php echo site_url('Subject/mybook')?>" title="知识体系管理" class="for_lable">我的课程</a>&gt;
                    <a>课程结构</a>
                        
               
                </div> 
            	 <a href="javascript:;" id="addcha" class="addBtn">+章</a>
             </div> 
          
                <!--面包屑导航  end-->
                <div id="structContainer">
                    <div id="structurBody" class="structurBody">
                        <ul id="lessonList" class="lessonList">
                            <?php foreach ($chapters as $kcha => $cha):?>
                                <li class="itemChaper clearfix">
                                    <div class="itemContent"><span title=""><?php echo $kcha?>  <?php echo $cha['PackageName']?></span></div>
                                    <?php if($status==1): ?>
                                        <div class="itemActions">
                                            <span class="adddBtn" onclick=""><i class="fa fa-exclamation"></i>有正在学习的学生</span>
                                        </div>
                                    <?php else: ?>
                                        <div class="itemActions forByYellow"  chaid="<?php echo $cha['PackageID']?>">
                                            <span class="adddBtn adduni"><i class="fa fa-plus-circle fa-lg"></i>新增单元</span>
                                            <span class="adddBtn editcha" name="<?php echo $cha['PackageName']?>" desc="<?php echo $cha['PackageDesc']?>" ><i class="fa fa-edit"></i>编辑</span>
                                            <span class="adddBtn delcha"><i class="fa fa-trash-o"></i>删除</span>
                                        </div>
                                    <?php endif; ?>
                                </li>
                                <?php if(!empty($cha['units'])):?>
                                    <?php foreach ($cha['units'] as $kuni => $uni):?>
                                        <li class="itemChaper marginLeft15 clearfix">
                                            <div class="itemContent"><span title=""><?php echo $kuni?>  <?php echo $uni['CourseName']?></span>
                                                <p></p>
                                            </div>
                                        <?php if($status==1): ?>
                                            <div class="itemActions">
                                                <span class="adddBtn" onclick=""><i class="fa fa-exclamation"></i>有正在学习的学生</span>
                                            </div>
                                        <?php else: ?>
                                                <div class="itemActions forByYellow"  uniid="<?php echo $uni['CourseID']?>">
                                                    <a href="<?php echo site_url('Subject/addsection').'/cid/'. $cid . '/uniid/'. $uni['CourseID']?>" target="_blank"><span class="adddBtn"><i class="fa fa-plus-circle fa-lg"></i>新增小节</span></a>
                                                <span class="adddBtn edituni" name="<?php echo $uni['CourseName']?>" desc="<?php echo $uni['CourseDesc']?>" ><i class="fa fa-edit"></i>编辑</span>
                                                <span class="adddBtn deluni"><i class="fa fa-trash-o"></i>删除</span>
                                            </div>
                                        <?php endif; ?>
                                        </li>
                                        <?php if(!empty($uni['sections'])):?>
                                            <?php foreach ($uni['sections'] as $ksec => $sec):?>
                                                <li class="itemChaper itemLesson clearfix">
                                                    <div class="itemLine"></div>
                                                    <div class="itemContent"><span title=""><?php echo ($kuni+1).'.'.($ksec+1)?>  <?php echo $sec['SectionName']?></span>
                                                        <p></p>
                                                    </div>
                                                <?php if($status==1): ?>
                                                    <div class="itemActions">
                                                        <span class="adddBtn" onclick=""><i class="fa fa-exclamation"></i>有正在学习的学生</span>
                                                    </div>
                                                <?php else: ?>
                                                        <div class="itemActions forByYellow" secid="<?php echo $sec['SectionID']?>" sectype="<?php echo $sec['SectionType']?>" >
                                                            <a href="<?php echo site_url('Education/sectiondetail').'?packageid='.$cid.'&sectionid='.$sec['SectionID']?>" target="_blank"><span class="adddBtn" ><i class="fa fa-eye"></i>预览</span></a>
                                                            <a href="<?php echo site_url('Subject/editsection').'/cid/'. $cid . '/secid/'. $sec['SectionID']?>" target="_blank"><span class="adddBtn"><i class="fa fa-edit"></i>编辑</span></a>
                                                        <span class="adddBtn delsec" ><i class="fa fa-trash-o"></i>删除</span>
                                                    </div>
                                                <?php endif; ?>
                                                </li>
                                            <?php endforeach;?>
                                        <?php endif;?>
                                    <?php endforeach;?>
                                <?php endif;?>
                            <?php endforeach;?>
                        </ul>
                  </div>
              </div>
						
        </div>
    </div>
	<!--right stop-->

    <!--center stop-->
    <!--footer start-->
<?php $this->load->view('public/footer.php')?>

<!--footer stop-->
</div>
<div class="maskbox"></div>

<!--添加章-->
<div class="popUpset animated " id="addchaBox" >
        <form action="" method="post">
            <div class="popTitle">
            <p>新增章</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
            </div>
            <div class="infoBox">
               <div class="box-input-cen clearfix">
                    <span class="bigTitle"><nobr>*</nobr>章名称：</span>
                    <input type="text" id="chaname" class="intBig">
                    
                </div>
               <div class="box-input-cen clearfix">
					<span class="bigTitle"><nobr>*</nobr>章描述：</span>
                    <textarea type="text" id="chadesc" class="intBig height-120"></textarea>
               </div>
                <p class="adderrormsg"></p>
                <div class="btnBox">
                    <a href="javascript:;" class="publicOk" id="ok">保存</a><!--如果是子层弹窗，调用hidePop-2-->
                   
               </div>
            </div>
        </form>
</div>
<!--编辑章-->
<div class="popUpset animated " id="modchaBox">
        <form action="" method="post">
            <div class="popTitle">
            <p>编辑章</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
            </div>
            <div class="infoBox">
               <div class="box-input-cen clearfix">
                    <span class="bigTitle"><nobr>*</nobr>章名称：</span>
                    <input type="text" id="name" class="intBig">
                    
                </div>
               <div class="box-input-cen clearfix">
					<span class="bigTitle"><nobr>*</nobr>章描述：</span>
                    <textarea type="text" id="desc" class="intBig height-120"></textarea>
               </div>
                <p class="adderrormsg"></p>
                <div class="btnBox">
                    <a href="javascript:;" class="publicOk" id="ok">保存</a><!--如果是子层弹窗，调用hidePop-2-->
                   
               </div>
            </div>
        </form>
</div>
<!--添加单元-->
<div class="popUpset animated " id="adduniBox" >
    <form action="" method="post">
        <div class="popTitle">
            <p>新增单元</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox">
            <div class="box-input-cen clearfix">
                <span class="bigTitle"><nobr>*</nobr>单元名称：</span>
                <input type="text" id="name" class="intBig">

            </div>
            <div class="box-input-cen clearfix">
                <span class="bigTitle"><nobr>*</nobr>单元描述：</span>
                <textarea type="text" id="desc" class="intBig height-120"></textarea>
            </div>
            <p class="adderrormsg"></p>
            <div class="btnBox">
                <a href="javascript:;" class="publicOk" id="ok">保存</a><!--如果是子层弹窗，调用hidePop-2-->

            </div>
        </div>
    </form>
</div>
<!--编辑单元-->
<div class="popUpset animated " id="moduniBox" >
        <form action="" method="post">
            <div class="popTitle">
            <p>编辑单元</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
            </div>
            <div class="infoBox">
               <div class="box-input-cen clearfix">
                    <span class="bigTitle"><nobr>*</nobr>单元名称：</span>
                    <input type="text" id="name" class="intBig">
                    
                </div>
               <div class="box-input-cen clearfix">
					<span class="bigTitle"><nobr>*</nobr>单元描述：</span>
                    <textarea type="text" id="desc" class="intBig height-120"></textarea>
               </div>
                <p class="adderrormsg"></p>
                <div class="btnBox">
                    <a href="javascript:;" class="publicOk" id="ok">保存</a><!--如果是子层弹窗，调用hidePop-2-->
                   
               </div>
            </div>
        </form>
</div>
<!--删除章确认-->
<div class="popUpset animated " id="delchaBox">
        <form action="" method="post">
            <div class="popTitle">
            <p>确认操作</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
            </div>
            <div class="infoBox">
                <p class="promptNews">确定要删除该章节吗？</p>
                <div class="btnBox">
                    <a href="javascript:;" class="publicOk " id="ok">确定</a>
                    <a href="javascript:;" class="publicNo hidePop-1" id="">取消</a><!--如果是子层弹窗，调用hidePop-2-->
                   
               </div>
            </div>
        </form>
</div>
<!--删除节确认-->
<div class="popUpset animated " id="delsecBox">
        <form action="" method="post">
            <div class="popTitle">
            <p>删除节</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
            </div>
            <div class="infoBox">
                <p class="promptNews">确定要删除该节吗？</p>
                <input type="hidden" value="" id="">
                <div class="btnBox">
                    <a href="javascript:;" class="publicOk " id="ok">确定</a>
                    <a href="javascript:;" class="publicNo hidePop-1" id="">取消</a><!--如果是子层弹窗，调用hidePop-2-->
                   
               </div>
            </div>
        </form>
</div>
<!--删除单元确认-->
<div class="popUpset animated " id="deluniBox">
        <form action="" method="post">
            <div class="popTitle">
            <p>删除单元</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
            </div>
            <div class="infoBox">
                <p class="promptNews">确定要删除该单元吗？</p>
                <input type="hidden" value="" id="">
                <div class="btnBox">
                    <a href="javascript:;" class="publicOk " id="ok">确定</a>
                    <a href="javascript:;" class="publicNo hidePop-1" id="">取消</a><!--如果是子层弹窗，调用hidePop-2-->
                   
               </div>
            </div>
        </form>
</div>

<script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/prompt.js"></script>
<script type="text/javascript">
    var site_url = '<?php echo site_url() ?>';
    var cid = '<?php echo $cid?>';
</script>
</body>
</html>