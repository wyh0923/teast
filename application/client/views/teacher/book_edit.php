<!DOCTYPE html>
<html>
<head>
	<title>编辑我的课程</title>

<meta charset="utf-8">
<link rel="shortcut icon" href="<?php echo base_url() ?>resources/imgs/public/title.ico">
<script type='text/javascript' src="<?php echo base_url() ?>resources/js/public/jquery-1.11.0.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/template.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>resources/js/teacher/book.js"></script>
<link href="<?php echo base_url() ?>resources/css/public/reset.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/thirdparty/font-awesome-4.5.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/css/public/firstStart.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/css/public/content.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/css/public/filter.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/thirdparty/switch/bootstrap-switch.min.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/css/admin/addCourse_Exam.css" rel="stylesheet" type="text/css">
<script src="<?php echo base_url() ?>resources/thirdparty/switch/bootstrap-switch.min.js" type="text/javascript"></script>

<script type="text/javascript" src="<?php echo base_url() ?>resources/js/teacher/teach_upload.js"></script>

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
            <!--面包屑导航 start-->
                <div class="lable_title">
                    <a href="<?php echo site_url('Subject/mybook')?>" title="知识体系管理" class="for_lable">知识体系管理</a>&gt;
                    <a href="<?php echo site_url('Subject/mybook')?>" title="知识体系管理" class="for_lable">我的课程</a>&gt;
                    <a>
                        编辑课程
                    </a>
                </div>  
            <!--面包屑导航  end-->
            <div class="contentInner addCourseInner">
                <div class="addItem clearfix">
                    <span class="addTit fl"><nobr>*</nobr>课程名称：</span>
                    <input type="text" id="PackageName" name="PackageName" value="<?php echo $course['PackageName']?>" placeholder="" class="addIpt fl">
                </div>
                <input type="hidden" id="PackageCode" name="PackageCode" value="<?php echo $course['PackageID']?>">
                <div class="addItem clearfix">
                    <span class="addTit fl"><nobr>*</nobr>课程描述：</span>
                    <textarea id="PackageDesc" name="PackageDesc" class="addTxt fl"><?php echo $course['PackageDesc']?></textarea>
                </div>
                <div class="addItem clearfix" id="level">
                    <span class="addTit fl">难度：</span>
                    <label <?php if($course['PackageDiff'] == 0):?> class="cur" <?php endif;?> code="0">初级</label>
                    <label <?php if($course['PackageDiff'] == 1):?> class="cur" <?php endif;?> code="1">中级</label>
                    <label <?php if($course['PackageDiff'] == 2):?> class="cur" <?php endif;?> code="2">高级</label>
                </div>
                <div class="addItem clearfix">
								<span class="addTit fl">是否发布：</span>
								<div class="chkbox">
									<input type="checkbox" <?php echo $course['PackageStatus']=='1'?'checked':'';?> id="PackageStatus" class="PackageStatus" >
									<span class="action" >*未发布的课程无法在教学任务管理中显示</span>
								</div>
				</div>

                <div class="addItem clearfix">
                    <span class="addTit fl">课程图片：</span>
                    <input type="file" id="upload" name="upload" accept="image/*" onchange="uploadpic()" style="display: none;" value="" placeholder="" class="addIpt fl"/>
                    <input type="text" readonly id="PackageImg" value="<?php if($course['PackageImg']=='showpic.png'){echo '';}else{echo $course['PackageImg'];} ?>" placeholder="" class="addIpt fl" style="width:298px;"/>
                    <a href="javascript:;" class="uploadBtn">上传</a>
                    <a href="javascript:;" class="disuploadBtn" >重置</a>
                </div>
                <div class="addItem clearfix">
                    <span class="addTit fl">图片预览：</span>
                    <div class="showPic showNoPic" id="preview"><img src="<?php echo base_url().'resources/files/img/course/'.$course['PackageImg']?>"></div>
                </div>
                <p id="adderrormsg"></p>
                <div class="addItem  btnBox">
                    <a href="javascript:;" id="editsave" class="publicOk">保存</a>
                    <a href="javascript:history.back(-1);" class="publicNo">返回</a>
                </div>
          </div>	
	</div>
	<!--right stop-->
	</div>

    <script src="<?php echo base_url(); ?>resources/js/teacher/ajaxfileupload.js"></script>
    <script src="<?php echo base_url(); ?>resources/js/public/bootstrap.min.js"></script>

    <!--center stop-->
    <!--footer start-->
    <?php $this->load->view('public/footer.php')?>
    <!--footer stop-->
</div>
<script>
    $(function(argument) {
      $('#PackageStatus').bootstrapSwitch();
    });

    var site_url = "<?php echo site_url()?>";
    var base_url = "<?php echo base_url()?>";

    var cid = "<?php echo $cid ?>";
    </script>
</body>
</html>