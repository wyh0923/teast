<!DOCTYPE html>
<html>
<head>
	<title>Admin</title>

<meta charset="utf-8">
<link rel="shortcut icon" href="<?php echo base_url() ?>resources/imgs/public/title.ico">
<script type='text/javascript' src="<?php echo base_url() ?>resources/js/public/jquery-1.11.0.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/template.js"></script>
<link href="<?php echo base_url() ?>resources/css/public/reset.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/thirdparty/font-awesome-4.5.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/css/public/firstStart.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/css/public/content.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/css/public/filter.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/css/admin/addCourse_Exam.css" rel="stylesheet" type="text/css">
<script src="<?php echo base_url() ?>resources/thirdparty/huploadify/js/jquery.Huploadify.js"></script>
<link href="<?php echo base_url() ?>resources/thirdparty/huploadify/css/Huploadify.css" rel="stylesheet" type="text/css">
<script src="<?php echo base_url() ?>resources/thirdparty/addtools.js"></script>

</head>
<body>
<!--header start-->
<div class="header">
	<div class="headerbox clearfix">
		<div class="headerlogobox clearfix"  onclick="">
			<a class="headerlogo" href="#" id="headerlogo"><img src="<?php echo base_url() ?>resources/imgs/public/logo.png" ></a>
			<p>网络安全实训系统</p>
		</div>
		<div class="headernavbox">
		    <a class="headernav " href="#">平台管理<span></span></a>
		    <a class="headernav navact" href="#">知识体系管理<span></span></a>
		    <a class="headernav" href="#">实训内容管理<span></span></a>
            <a class="headernav" href="#">人员管理<span></span></a>
            <a class="headernav" href="#">个人统计中心<span></span></a>
		</div>
		<div class="loginbox">
			<div class="tx"> <img class="photoImg" src=""></div>
			<p class="txtitle" id="txtitle">admin<em></em></p>
			<div class="loginlist" id="loginlist">
				<em></em>
				<p class="logout"><a href=""><i class="fa  fa-power-off"></i>退出登录</a><p>
			</div>
		</div>	
	</div>
</div>
<!--header stop-->

<div class="frame">
	<div class="main clearfix">
        <!--leftbar start-->
        <div class="sidebar">
              <div class="allcatagory"><a href="javascript:;">分类</a></div>
              <ul id="sidebarUl" class="sidebar1">
                    <li class="firstcatory"><p class="clearfix firstcatoryBox"><i class="fa fa-archive"></i><a href="#" class="links">体系管理</a></p>
                             <li  class="secondcatory"><a href="#" class="secondLinks">全部体系</a></li> 
                     </li>
                      <li class="firstcatory"><p class="clearfix firstcatoryBox"><i class="fa fa-book"></i><a href="#" class="links">课程管理</a></p>
                              <li  class="secondcatory"><a href="#" class="secondLinks">全部课程</a></li> 
                      </li>
                      <li class="firstcatory"><p class="clearfix firstcatoryBox"><i class="fa fa-copy"></i><a href="#" class="links">试卷管理</a></p>
                               <li  class="secondcatory"><a href="#" class="secondLinks">全部试卷</a></li>
                      </li>
                               <li class="firstcatory"><p class="clearfix firstcatoryBox"><i class="fa fa-question-circle"></i><a href="#" class="links">题目管理</a></p>
                               <li  class="secondcatory"><a href="#" class="secondLinks">所有题目</a></li>
                       </li>
                        <li class="firstcatory"><p class="clearfix firstcatoryBox"><i class="fa fa-wrench"></i><a href="#" class="links">工具库管理</a></p>
                                <li  class="secondcatory"><a href="#" class="secondLinks">所有工具</a></li>
                                <li  class="secondcatory"><a href="#" class="secondLinks">分类管理</a></li>
                                <li  class="secondcatory active"><a href="#" class="secondLinks">添加工具</a></li>
                        </li>
              </ul>
        </div>
        <!--leftbar stop-->


       <!--right start-->
        <div class="content">
              <!--面包屑导航 start-->
             <div class="lable_title">
                <a href="" title="知识体系管理" class="for_lable">知识体系管理</a>&gt;
                <a>
                    添加工具
                </a>
            </div> 
            <!--面包屑导航  end-->
            <!--title-->
            <div class="myarchlist">
                <h3 class="lable_h3">新增工具</h3>
            </div>
            
            <div class="addToolBox">
        
                <!--题目类型-->
                <div class="addITool clearfix" id="sonType">
                    <span class="addTit fl">工具分类：</span>
                    <select id="typeSel" class="toolWidth">
                        <option value="0">请选择</option>
                        <option value="65">逆向分析</option>
                        <option value="68">渗透分析</option>
                        <option value="69">&gt;&gt;测试12</option>
                        <option value="78">&gt;&gt;fgdgfdgfd</option>
                        <option value="74">内网扫描手感好图一糊涂好如何涂于</option>
                        <option value="75">提权</option>                             
                    </select>
                </div> 
                <input id="toolCode" value="" type="hidden">
            
                <div class="addITool clearfix" id="scfj">
                    <span class="addTit fl">工具名称：</span>
                    <input id="toolName" value="" class="toolWidth">
                </div>
            
                <div class="addITool clearfix" id="scfj">
                    <span class="addTit fl">工具描述：</span>
                    <input id="toolDesc" value="gfhghityjuiyuii和粗放回顾会提及季节" class="toolWidth">
                </div>
            
                <!--上传附件-->
                <div class="addItem clearfix" id="">
                            <span class="addTit fl" >上传工具：</span>
                            <div id="uploadTool"></div>
                 </div>
                <input id="toolFileName" value="" type="hidden">
            
                
                <div class="btnBox" >
                    <a href="javascript:;" id="" class="publicOk">保存</a>
                    <a href="" class="publicNo" id="back">返回</a>
                </div>
            
            
                
                 <p id="adderrormsg"></p>
                
            
           </div> 
        </div>
	<!--right stop-->
	</div>


    <!--center stop-->
    <!--footer start-->
    <div class="footer clearfix">
        <div class="footerbox">
            <div class="copy">
                <p class="cYellow">网络安全实训系统</p>
                <p >Copyright©2010-2016北京永信至诚科技股份有限公司 All Right Reversed</p>
            </div>

        </div>
    </div>
    <!--footer stop-->
</div>

</body>
</html>