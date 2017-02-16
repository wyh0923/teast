<!DOCTYPE html>
<html>
<head>
	<title>添加工具</title>

<meta charset="utf-8">
<link rel="shortcut icon" href="<?php echo base_url() ?>resources/imgs/public/title.ico">
<script type='text/javascript' src="<?php echo base_url() ?>resources/js/public/jquery-1.11.0.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/template.js"></script>
<link href="<?php echo base_url() ?>resources/css/public/reset.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/thirdparty/font-awesome-4.5.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/thirdparty/huploadify/css/Huploadify.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/css/public/firstStart.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/css/public/content.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/css/public/filter.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/css/admin/addCourse_Exam.css" rel="stylesheet" type="text/css">
<script src="<?php echo base_url() ?>resources/thirdparty/huploadify/js/jquery.Huploadify.js"></script>

<script src="<?php echo base_url() ?>resources/js/admin/tool_add.js"></script>

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
                <a href="" title="知识体系管理" class="for_lable">知识体系管理</a>&gt;
                <a>添加工具</a>
            </div> 
            <!--面包屑导航  end-->
            <!--title-->
            <div class="myarchlist">
                <h3 class="lable_h3">添加工具</h3>
            </div>
            
            <div class="addToolBox">
                <!--题目类型-->
                <div class="addITool clearfix" id="sonType">
                    <span class="addTit fl"><nobr>*</nobr>工具分类：</span>
                    <select id="typeSel" class="toolWidth">
                        <option value="0">请选择</option>
                        <?php foreach ($tool_types as $type):?>
                            <option value="<?php echo $type['ID']?>"><?php echo $type['html'].$type['ClassifyName']?></option>
                        <?php endforeach;?>
                    </select>
                </div> 
                <input id="toolCode" value="" type="hidden">
            
                <div class="addITool clearfix" id="scfj">
                    <span class="addTit fl"><nobr>*</nobr>工具名称：</span>
                    <input id="toolName" value="" class="toolWidth">
                </div>
            
                <div class="addITool clearfix" id="scfj">
                    <span class="addTit fl"><nobr>*</nobr>工具描述：</span>
                    <input id="toolDesc" value="" class="toolWidth">
                </div>
            
                <!--上传附件-->
                <div class=" upDownBox addItem clearfix" id="">
                    <span class="label addTit fl" ><nobr>*</nobr>上传工具：</span>

                    <div id="uploadTool" class="startUpBox bigInput">
                        <div class="huploadifyBox">
                            <a class="uploadIcon" id="file_upload_1-button" href="javascript:;"></a>
                        </div>
                    </div>
                   
                </div>
                <input id="toolUrl" value="" type="hidden">
                 <p class="uploadTip">支持zip|gzip|rar|qcow2|doc|docx|xls|xlsx|jpg|png|gif|jpeg文件</p>
                <p id="adderrormsg"></p>
                <div class="btnBox" >
                    <a href="javascript:;" id="savetool" class="publicOk">保存</a>
                    <a href="<?php echo site_url().'/Adminsubject/toollist'?>" class="publicNo" id="back">返回</a>
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


<script type="text/javascript">
    var site_url = '<?php echo site_url() ?>';
    var base_url = '<?php echo base_url() ?>';
    var toolDir = "<?php echo $upload_data['tool_dir'];?>";
    var nodeToolid = "<?php echo $upload_data['node_tool_id'];?>";
    var toolcode = "<?php echo $toolcode ?>";

</script>

</body>
</html>