<!DOCTYPE html>
<html>
<head>
	<title>所有题目</title>

<meta charset="utf-8">
<link rel="shortcut icon" href="<?php echo base_url() ?>resources/imgs/public/title.ico">
<script type='text/javascript' src="<?php echo base_url() ?>resources/js/public/jquery-1.11.0.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/template.js"></script>
<link href="<?php echo base_url() ?>resources/css/public/reset.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/thirdparty/font-awesome-4.5.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/css/public/firstStart.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/css/public/content.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/css/public/filter.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/css/public/animate.min.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/page.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>resources/js/teacher/question.js"></script>

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
                <a>
                    题目管理
                </a>
            </div> 
            <!--面包屑导航  end-->
            <div class="Filter">
                <div class="filter clearfix ">
                    <h3 class="filterTitle">作　　者：</h3>
                    <div class="filterList">
                        <a title="全部" href="javascript:;" code=" " class="author <?php if(''==$uname) echo  'filterCur'?>">全部</a>
                        <?php foreach ($teachers as $t):?>
                            <a class="author <?php if($t['UserName']==$uname) echo  'filterCur'?>" href="javascript:;" code="<?php echo $t['UserName']?>"><?php echo $t['UserName']?></a>
                        <?php endforeach;?>
                    </div>
                </div>
            <div class="filter clearfix ">
                <h3 class="filterTitle">题目类型：</h3>
                <div class="filterList">
                    <a title="全部" href="javascript:;" class="mold <?php if($qtype == ''):?>filterCur<?php endif;?>" type="">全部</a>
                    <a title="单选题" href="javascript:;" class="mold <?php if($qtype == 1):?>filterCur<?php endif;?>" type="1">单选题</a>
                    <a title="多选题" href="javascript:;" class="mold <?php if($qtype == 2):?>filterCur<?php endif;?>" type="2">多选题</a>
                    <a title="判断题" href="javascript:;" class="mold <?php if($qtype == 3):?>filterCur<?php endif;?>" type="3">判断题</a>
                    <a title="填空题" href="javascript:;" class="mold <?php if($qtype == 4):?>filterCur<?php endif;?>" type="4">填空题</a>
                    <a title="夺旗题" href="javascript:;" class="mold <?php if($qtype == 5):?>filterCur<?php endif;?>" type="5">夺旗题</a>
                </div>
            </div>
            </div>
            <div class="total clearfix">
                <h3>共计：5类/<?php echo $total_rows;?>个</h3>
                <a href="<?php echo site_url('Subject/addquestion')?>" class="btnNew" id=""><span>+</span>新增题目</a>
                <div class="search-a">
                    <input type="text" class="iptSearch-a equestion" value="<?php echo $search;?>" name="Search" placeholder="请输入关键字搜索">
                    <i class="fa fa-search cquestion"></i>
                </div>
            </div>
        
            <table class="topicList">
                <thead>
                    <tr class="table-title">
                        <td width="160" id='QuestionAuthor' code="<?php if ($sort && $sort['field']=='QuestionAuthor'):?><?php echo $sort['order'];?><?php endif;?>">
                            <a>出题人<i class="fa
                                <?php if ($sort && $sort['field']=='QuestionAuthor' && $sort['order']=='DESC'):?>fa-sort-alpha-desc
                                <?php elseif ($sort && $sort['field']=='QuestionAuthor' && $sort['order']=='ASC'):?>fa-sort-alpha-asc
                                <?php else:?>fa-sort<?php endif;?>
                            "></i></a>
                        </td>
                        <td width="285">题目名</td>
                        <td width="160" id='QuestionType' code="<?php if ($sort && $sort['field']=='QuestionType'):?><?php echo $sort['order'];?><?php endif;?>">
                            <a>题目类型<i class="fa
                            <?php if ($sort && $sort['field']=='QuestionType' && $sort['order']=='DESC'):?>fa-sort-alpha-desc
                                <?php elseif ($sort && $sort['field']=='QuestionType' && $sort['order']=='ASC'):?>fa-sort-alpha-asc
                                <?php else:?>fa-sort<?php endif;?>
                            "></i></a>
                        </td>
                        <td width="210"> 操作</td>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($questions as $q):?>
                    <tr>
                        <td title=""><?php echo  $q['QuestionAuthor']?></td>
                        <td title="<?php echo $q['QuestionDesc']?>"><?php echo $q['QuestionDesc']?></td>
                        <td>
                            <?php
                                if($q['QuestionType']==1)echo '单选题';
                                if($q['QuestionType']==2)echo '多选题';
                                if($q['QuestionType']==3)echo '判断题';
                                if($q['QuestionType']==4)echo '填空题';
                                if($q['QuestionType']==5)echo '夺旗题';
                            ?>
                        </td>
                        
                        <td>
                            <a href="javascript:;" class="forYellow qdetail"
                                <?php
                                   if($q['QuestionType']==1){
                                       echo 'qtype=单选题 ';
                                       echo 'qchoose="'. str_replace(' ', '^', $q['QuestionChoose']).'"';
                                       echo ' qanswer="'. str_replace(' ', '^', $q['QuestionAnswer']).'"';
                                   }
                                   if($q['QuestionType']==2){
                                       echo 'qtype=多选题 ';
                                       echo 'qchoose="'. str_replace(' ', '^', $q['QuestionChoose']).'"';
                                       echo ' qanswer="'. str_replace(' ', '^', $q['QuestionAnswer']).'"';
                                   }
                                   if($q['QuestionType']==3){
                                       echo 'qtype=判断题 ';
                                       echo 'qchoose="'. str_replace(' ', '^', $q['QuestionChoose']).'"';
                                       echo ' qanswer="'. str_replace(' ', '^', $q['QuestionAnswer']).'"';
                                   }
                                   if($q['QuestionType']==4){
                                       echo 'qtype=填空题 ';
                                       echo 'qchoose="'.$q['QuestionAnswer'].'"';
                                       echo ' qanswer="'.$q['QuestionAnswer'].'"';
                                   }
                                   if($q['QuestionType']==5){
                                       echo 'qtype=夺旗题 ';
                                       echo 'qchoose="'.$q['QuestionAnswer'].'"';
                                       echo ' qanswer="'.$q['QuestionAnswer'].'"';
                                   }
                               ?>

                               qdiff="
                                    <?php
                                       if($q['QuestionDiff']==1)echo '中级';
                                       if($q['QuestionDiff']==2)echo '高级';
                                       if($q['QuestionDiff']==0)echo '初级';
                                    ?>
                               "
                               ctf="
                                    <?php
                               if(isset($q['changjing'])){echo $q['changjing'];} else{ echo '无';}

                               ?>
                               "
                               qname=" <?php
                                    $s = htmlspecialchars($q['QuestionDesc']);
                                    echo $s;
                               ?> "

                               accessory="<?php echo json_decode($q['ResourceName'])?>"
                               url="<?php echo json_decode($q['ResourceUrl'])?>"
>
                                <i class="fa fa-search-plus " ></i>
                                详情
                            </a>
                            <?php if($author==$q['QuestionAuthor']): ?>
                            <a href="javascript:;" code="<?php echo $q['QuestionID']?>" class="forBlue ismod" >
                                <i class="fa fa-edit" ></i>编辑
                                
                            </a>
                            <a href="javascript:;" class="forRed qdel" code="<?php echo $q['QuestionID'];?>">
                                <i class="fa fa-trash-o"></i>
                                删除
                            </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach;?>
                </tbody>
        
            </table>

            <!--page-->
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
            <?php else: ?>
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
<!--删除成功-->
<div class="popUpset animated " id="qdOk"  >
    <form action="" method="post">
        <div class="popTitle">
            <p>提示操作</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox">
            <p class="promptNews promptUp">删除成功</p>

        </div>
    </form>
</div>
<!--删除确认-->
<div class="popUpset animated " id="qdelOk">
    <form action="" method="post">
        <div class="popTitle">
            <p>确认操作</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox">
            <p class="promptNews">确认删除该题目吗</p>
            <div class="btnBox">
                <a href="javascript:;" class="publicOk" id="qOk">确定</a>
                <a href="javascript:;" class="publicNo hidePop-1" id="">取消</a><!--如果是子层弹窗，调用hidePop-2-->
            </div>

        </div>
    </form>
</div>
<!--题目详情-->
<div class="popUpset animated " id="qdetail" >
    <form action="" method="post">
        <div class="popTitle">
            <p>题目详情</p>
            <a href="javascript:;" id="" class="close close-1"></a><!--如果是子层弹窗，调用close-2-->
        </div>
        <div class="infoBox height-550">
            <div class="box-input-cen clearfix">
                <span class="bigTitle liheight27">题目类型：</span>
                <a class="checkBlue type">单1</a>

            </div>
            <div class="box-input-cen clearfix">
                <span class="bigTitle liheight27">题目难度：</span>
                <a class="checkBlue level">高1</a>

            </div>
            <div class="box-input-cen clearfix">
                <span class="bigTitle liheight27">关联场景：</span>
                <span class="liheight27 ctf" ></span>

            </div>
            <div class="box-input-cen clearfix ">
                <span class="bigTitle liheight34">题干：</span>
                <label class="intBig quesT">
                    <p class="qname"></p></label>

            </div>
            <div class="box-input-cen clearfix">
                <span class="bigTitle liheight34">答案：</span><!--选择-->
                <ul class="intBig quesT qanswer">
                    <li></li>

                </ul>
            </div>
            <div class="box-input-cen clearfix">
                <span class="bigTitle liheight27">附件：</span>
                <span class="accessory liheight27"></span>

            </div>
            <div class="btnBox">
                <a href="javascript:;" class="publicOk hidePop-1" id="">确定</a><!--如果是子层弹窗，调用hidePop-2-->

            </div>
        </div>
    </form>
</div>

<script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/prompt.js"></script>
<script type="text/javascript">
    var site_url = '<?php echo site_url() ?>';
    var author = '<?php echo $uname?>';
    var type = '<?php echo $qtype;?>';
    var hunt = '<?php echo $search?>';
    var base_url = '<?php echo base_url() ?>';
</script>
</body>
</html>