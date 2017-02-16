<!DOCTYPE html>
<html>
<head>
	<title>所有学员</title>

<meta charset="utf-8">
<link rel="shortcut icon" href="<?php echo base_url() ?>resources/imgs/public/title.ico">
<script type='text/javascript' src="<?php echo base_url() ?>resources/js/public/jquery-1.11.0.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/template.js"></script>


<link href="<?php echo base_url() ?>resources/css/public/reset.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/thirdparty/font-awesome-4.5.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/css/public/firstStart.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/css/public/content.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/css/public/animate.min.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/css/public/filter.css" rel="stylesheet" type="text/css">
<script src="<?php echo base_url() ?>resources/thirdparty/WdatePicker/js/DateJs/WdatePicker.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/page.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>resources/js/teacher/allstudents.js"></script>


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
                      <h3 class="filterTitle">时间范围：</h3>
                     <div class="filterList">
                         <input id="stime" onfocus="WdatePicker({onpicked: function(){searchForTime();},dateFmt:'yyyy-MM-dd HH:mm:ss'})" class="Wdate" name="starttime" value="<?php if ($time):?><?php echo $time['starttime'];?><?php endif;?>" type="text"><span class="marAuto">至</span>
                         <input id="etime" onfocus="WdatePicker({onpicked: function(){searchForTime();},dateFmt:'yyyy-MM-dd HH:mm:ss'})" class="Wdate" name="endtime" value="<?php if ($time):?><?php echo $time['endtime'];?><?php endif;?>" type="text">
                     </div>
                    </div>
                </div>
                <div class="total clearfix">
                     <h3>共计：<?php echo $total_rows;?>人</h3>
                      <a href="javascript:;" onclick="delAllTeacher()" id="delAllBtn" class="btnNew delyoure">删除选择学员</a>
                      <a href="<?php echo site_url('Classstaff/addstudent');?>" id="addBtn" class="btnNew"><span>+</span>新建学员</a>
                      <div class="search-a">
                            <input id="ArchitectureName" class="iptSearch-a esar" placeholder="请输入学员姓名" value="<?php echo $search;?>" type="text"><i class="fa fa-search csear"></i>
                       </div> 
                 </div>
                <input id="page" value="" type="hidden">
                <table class="personList">
                     <thead>
                            <tr class="table-title">
                            	<td width="60"><input type="checkbox" id="checkAll">全选</td>
                                <td width="100" >姓名</td>
                                <td width="60" id="UserSex" code="<?php if ($sort && $sort['field']=='UserSex'):?><?php echo $sort['order'];?><?php endif;?>" >
                                    <a> 性别<i class="fa <?php if ($sort && $sort['field']=='UserSex' && $sort['order']=='DESC'):?>fa-sort-alpha-desc
                            <?php elseif ($sort && $sort['field']=='UserSex' && $sort['order']=='ASC'):?>fa-sort-alpha-asc
                            <?php else:?>fa-sort<?php endif;?>
                            "></i></a>
                                </td>
                                <td width="200" id="UserDepartment" code="<?php if ($sort && $sort['field']=='UserDepartment'):?><?php echo $sort['order'];?><?php endif;?>">
                                    <a>工作单位<i class="fa <?php if ($sort && $sort['field']=='UserDepartment' && $sort['order']=='DESC'):?>fa-sort-alpha-desc
                            <?php elseif ($sort && $sort['field']=='UserDepartment' && $sort['order']=='ASC'):?>fa-sort-alpha-asc
                            <?php else:?>fa-sort<?php endif;?>
                            "></i></a>
                                </td>
                                <td  width="110" id="CreateTime" code="<?php if ($sort && $sort['field']=='CreateTime'):?><?php echo $sort['order'];?><?php endif;?>">
                                    <a>创建时间<i class="fa <?php if ($sort && $sort['field']=='CreateTime' && $sort['order']=='DESC'):?>fa-sort-alpha-desc
                            <?php elseif ($sort && $sort['field']=='CreateTime' && $sort['order']=='ASC'):?>fa-sort-alpha-asc
                            <?php else:?>fa-sort<?php endif;?>
                            "></i></a>
                                </td>
                                <td width="110" >状态</td>
                                <td >操作</td>
                            </tr>	
                        </thead>
                        <tbody id="allstuList">
                            <?php foreach ($students as $v):?>
                                <tr>
                             	 <td> <input type="checkbox" name="checkTeacher" data-code="<?php echo $v['UserID'];?>" onclick='checkThis("#allstuList","checkAll")'></td>
                                 <td title=""><?php echo $v['UserName']?></td>
                                 <td title=""><?php echo $v['UserSex']?></td>
                                 <td title="<?php echo $v['UserDepartment']?>"><?php echo $v['UserDepartment']?></td>
                                 <td title=""><?php echo date('Y-m-d', $v['CreateTime'])?></td>
                                 <td>
                                     <?php if($v['IsLocked'] != 1): ?>启用/<a code="<?php echo $v['UserID'];?>" href="javascript:;" onclick="disableFun(this)"><span class="btnDisable"> 禁用</span></a><?php else:?><a href="javascript:;" onclick="enableFun(this)""disableFun(this)" code="<?php echo $v['UserID'];?>"><span class="btnDisable"> 启用</span></a>/禁用<?php endif;?>
                                 </td>
                                 <td>
                                    <a href="javascript:;" class="forYellow" code="<?php echo $v['UserID'];?>"><i class="fa fa-search-plus "></i>详情</a>
                                    <a href="javascript:;" class="forBlue" code="<?php echo $v['UserID'];?>"><i class="fa fa-edit"> </i>编辑</a>
                                    <a href="javascript:;" class="forRed" code="<?php echo $v['UserID'];?>"><i class="fa fa-trash"></i>删除 </a>
                                 </td>	
                                </tr>
                            <?php endforeach;?>
        
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
<script type="text/javascript">
    var site_url = '<?php echo site_url() ?>';
    var search = "<?php echo $search; ?>";
    var time = "<?php if ($time):?><?php echo $time['starttime'].'_'.$time['endtime'];?><?php endif;?>";
</script>
<!--删除确认-->
<div class="maskbox"></div>
<div class="popUpset animated " id="one_del" >
    <form action="" method="post">
        <div class="popTitle">
            <p>确认操作</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox">
            <p class="promptNews">确定要删除该学员吗？</p>

            <div class="btnBox">
                <a href="javascript:;" class="publicOk " id="okBtn">确定</a>
                <a href="javascript:;" class="publicNo hidePop-1" id="">取消</a><!--如果是子层弹窗，调用hidePop-2-->

            </div>
        </div>
    </form>
</div>
<!--多选删除确认-->
<div class="popUpset animated " id="delAll">
    <form action="" method="post">
        <div class="popTitle">
            <p>确认操作</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox">
            <p class="promptNews">请确认是否删除所有选中的学员？</p>

            <div class="btnBox">
                <a href="javascript:;" class="publicOk " id="delAllTeacherBtn">确定</a>
                <a href="javascript:;" class="publicNo hidePop-1" id="">取消</a><!--如果是子层弹窗，调用hidePop-2-->

            </div>
        </div>
    </form>
</div>
<!--编辑学员信息-->
<div class="popUpset animated " id="editinfo">
    <form action="" method="post">
        <div class="popTitle">
            <p>编辑学员信息</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox">
            <input id="usercode" type="hidden" value=""/>
            <div class="inputPop clearfix">
                <span class="secongTitle">学号：</span>
                <input id="stuid"  name="StuId" value="" class="iptext" type="text">

            </div>
            <div class="inputPop clearfix">
                <span class="secongTitle"><nobr>*</nobr>姓名：</span>
                <input id="username"  value="" class="iptext" type="text" maxlength="30">

            </div>
            <div class="inputPop clearfix">
                <span class="secongTitle"><nobr>*</nobr>用户名：</span>
                <input id="useraccount" value="" class="iptext" type="text" readonly disabled>

            </div>
            <div class="inputPop clearfix">
                <span class="secongTitle">性别：</span>

                <input type="radio" name="sex" value="男" id="man" checked="checked"><span class="danX">男</span>
                <input type="radio" name="sex" value="女" id="woman"><span class="danX">女</span>


            </div>
            <div class="inputPop clearfix">
                <span class="secongTitle">单位：</span>
                <input class="iptext" id="userdepartment"  value="" type="text" maxlength="50">

            </div>
            <div class="inputPop clearfix">
                <span class="secongTitle">邮箱：</span>
                <input class="iptext"  id="useremail" value="" maxlength="30" type="email">

            </div>
            <div class="inputPop clearfix">
                <span class="secongTitle">电话：</span>
                <input class="iptext" id="userphone"  value="" type="text" maxlength="20">

            </div>
            <div class="inputPop clearfix">
                <span class="secongTitle">修改密码：</span>
                <input class="iptext"  id="userpassword" value=""  type="password" maxlength="20">

            </div>
            <div class="inputPop clearfix">
                <span class="secongTitle">确认修改密码：</span>
                <input class="iptext"  id="userpasswordTwo"  value="" type="password" maxlength="20">

            </div>
            <p class="adderrormsg" id="errorinfo"></p>
            <div class="btnBox">
                <a href="javascript:;" class="publicOk " id="saveedituser">保存</a>
                <a href="javascript:;" class="publicNo hidePop-1" id="">关闭</a><!--如果是子层弹窗，调用hidePop-2-->

            </div>
        </div>
    </form>
</div>

<!--学员详细信息-->
<div class="popUpset animated " id="detailinfo" >
    <form action="" method="post">
        <div class="popTitle">
            <p>学员详细信息</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox onCanBg">
            <div class="inputPop clearfix">
                <span class="secongTitle popWidth80">学号：</span>
                <input id="stu1"  value="" class="iptext" type="text" maxlength="30" readonly>
            </div>
            <div class="inputPop clearfix">
                <span class="secongTitle popWidth80">姓名：</span>
                <input id="username1"  value="" class="iptext" type="text" maxlength="30" readonly>
            </div>
            <div class="inputPop clearfix">
                <span class="secongTitle popWidth80">用户名：</span>
                <input id="useraccount1" value="student" class="iptext" type="text" readonly>
            </div>
            <div class="inputPop clearfix">
                <span class="secongTitle popWidth80">班级：</span>
                <input id="classname1" value="" class="iptext" type="text" readonly>
            </div>
            <div class="inputPop clearfix">
                <span class="secongTitle popWidth80">性别：</span>
                <input type="radio" name="sex" value="男" id="man1" checked="checked" disabled><span class="danX">男</span>
                <input type="radio" name="sex" value="女" id="woman1" disabled><span class="danX">女</span>
            </div>
            <div class="inputPop clearfix">
                <span class="secongTitle popWidth80">单位：</span>
                <input class="iptext"  id="userdepartment1" value="" type="text" maxlength="50" readonly>
            </div>
            <div class="inputPop clearfix">
                <span class="secongTitle popWidth80">邮箱：</span>
                <input class="iptext"  id="useremail1" value="" maxlength="30" type="email" readonly>
            </div>
            <div class="inputPop clearfix">
                <span class="secongTitle popWidth80">电话：</span>
                <input class="iptext"  id="userphone1" value="" type="text" maxlength="20" readonly>
            </div>

            <div class="btnBox">
                <a href="javascript:;" class="publicNo hidePop-1" id="">关闭</a><!--如果是子层弹窗，调用hidePop-2-->

            </div>
        </div>
    </form>
</div>
<!--删除成功-->
<div class="popUpset animated " id="okBox">
    <form action="" method="post">
        <div class="popTitle">
            <p>确认操作</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox">
            <p class="promptNews">删除成功</p>

        </div>
    </form>
</div>
<script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/prompt.js"></script>
</body>
</html>