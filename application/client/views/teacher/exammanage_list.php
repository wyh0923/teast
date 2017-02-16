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
<script src="<?php echo base_url() ?>resources/thirdparty/WdatePicker/js/DateJs/WdatePicker.js" type="text/javascript"></script>



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
            <a class="headernav"  href="#">个人统计中心<span></span></a>
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
                               <li  class="secondcatory active"><a href="#" class="secondLinks">全部试卷</a></li>
                      </li>
                               <li class="firstcatory"><p class="clearfix firstcatoryBox"><i class="fa fa-question-circle"></i><a href="#" class="links">题目管理</a></p>
                               <li  class="secondcatory"><a href="#" class="secondLinks">所有题目</a></li>
                       </li>
                        <li class="firstcatory"><p class="clearfix firstcatoryBox"><i class="fa fa-wrench"></i><a href="#" class="links">工具库管理</a></p>
                                <li  class="secondcatory"><a href="#" class="secondLinks">所有工具</a></li>
                                <li  class="secondcatory"><a href="#" class="secondLinks">分类管理</a></li>
                                <li  class="secondcatory"><a href="#" class="secondLinks">添加工具</a></li>
                        </li>
              </ul>
        </div>
        <!--leftbar stop-->


       <!--right start-->
        <div class="content">
            <!--面包屑导航 start-->
            <div class="lable_title">
                    <a href="" title="首页" class="for_lable">知识体系管理</a>&gt;
                    <a>全部试卷</a>
              </div>
            <!--面包屑导航  end-->
                <div class="Filter">
                    <div class="filter clearfix ">
                        <h3 class="filterTitle">作　　者：</h3>
                        <div class="filterList">
                            <a title="全部" href="" class="filterCur">全部</a>
                            <a title="A老师" href="" class="">A老师</a>
                            <a title="B老师" href="" class="">B老师</a>
                            <a title="" href="" class="">C老师</a>
                            <a title="" href="" class="">D老师</a>
                            <a title="" href="" class="">E老师</a>
                            <a title="" href="" class="">F老师</a>
                            <a title="" href="" class="">G老师c</a>
                            <a title="" href="" class="">H老师</a>
                            <a title="" href="" class="">I老师</a>
                            <a title="" href="" class="">J老师</a>
                            <a title="" href="" class="">K老师</a>
                            <a title="" href="" class="">L老师</a>
                            <a title="" href="" class="">M老师</a>
                        </div>
                    </div>
                    <div class="filter clearfix ">
                        <h3 class="filterTitle">时间范围：</h3>
                        <div class="filterList">
                         <!--<script>
                                function searchForTime(){
                                    if ($("#stime").val() != "" && $("#etime").val() != "") {
                                    window.location.href = ""
                        }
                    }
                            </script>-->
                            <input id="stime" onfocus="WdatePicker({onpicked: function(){searchForTime();},dateFmt:'yyyy-MM-dd HH:mm:ss'})" class="Wdate" name="starttime" value="" type="text"><span class="marAuto">至</span>
                            <input id="etime" onfocus="WdatePicker({onpicked: function(){searchForTime();},dateFmt:'yyyy-MM-dd HH:mm:ss'})" class="Wdate" name="endtime" value="" type="text">
                        </div>
                    </div>
                </div>
                <div class="total clearfix">
                    <h3>共计：758套</h3>
                    <a href="" class="btnNew" id="addBtn"><span>+</span>新增试卷</a>
                    <div class="search-a">
                        <input type="text" class="iptSearch-a" value="" name="Search" placeholder="请输入关键字搜索">
                        <i class="fa fa-search"></i>
                    </div>
                </div>
                
                 
                <table class="testPaperList">
                    <thead>
                            <tr class="table-title">
                                <td width="320" id="" ><a>试卷名<i class="fa fa-sort"></i></a></td>
                                <td width="180" id=""><a>制作人<i class="fa fa-sort"></i></a></td>
                                <td width="160" id=""><a>制作时间<i class="fa fa-sort"></i></a></td>
                                <td width="170">操作</td>
                            </tr>   
                    </thead>
                    <tbody>
                            <tr>
                                
                                <td title="企业信息学习期中考试试卷"><a href="" target="_blank">企业信息学习期中考试试卷</a></td>
                                <td title="jack">jack</td>
                                <td title="2015-12-12">2015-12-12</td>
                                <td>
                                    <a href="" class="forBlue" >
                                        <i class="fa fa-edit" ></i>编辑
                                    </a>
                                    <a href="javascript:;" class="forRed" >
                                        <i class="fa fa-trash-o cBrown"></i>删除
                                    </a>
                                </td>
                            </tr>
        
                    </tbody>
                </table>
                <!--page start-->
                 <div class="noNews">
                            <i class="fa fa-file-text" aria-hidden="true"></i><span>没有找到数据......</span>
                </div>
                <div id="selfPage" class="page">
                    <ul>
                        <li class="back"><a href=""><i class="fa fa-angle-double-left"></i>首页</a></li>
                        <li class="back"><a href=""><i class="fa fa-angle-left"></i>上一页</a></li>
                        <li id="pagenum" class="act"><a href="">1</a></li>
                        <li class="next"><a href="">下一页<i class="fa fa-angle-right"></i></a></li>
                        <li class="next"><a href="">尾页<i class="fa fa-angle-double-right"></i></a></li>
                    </ul>
               </div>
                <!--page end-->
         </div>
	<!--right stop-->
	</div>


    <!--center stop-->
    <!--footer start-->
    <div class="footer clearfix">
        <div class="footerbox">
           <div class="copy">
                <p class="cYellow" >网络安全实训系统</p>
                <p >Copyright©2010-2016北京永信至诚科技股份有限公司 All Right Reversed</p>
            </div>
        </div>
    </div>
    <!--footer stop-->
</div>

</body>
</html>