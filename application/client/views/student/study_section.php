<!DOCTYPE html>
<html>
<head>
    <title>我的学习-小节学习</title>
    <meta charset="utf-8">
    <link rel="shortcut icon" href="<?php echo base_url() ?>resources/imgs/public/title.ico">
    <script type='text/javascript' src="<?php echo base_url() ?>resources/js/public/jquery-1.11.0.js"></script>
     <script type='text/javascript' src="<?php echo base_url() ?>resources/js/public/judgment.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/template.js"></script>
    <link href="<?php echo base_url() ?>resources/css/public/reset.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/animate.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/markdown.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/thirdparty/font-awesome-4.5.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/firstStart.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/content.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/filter.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/thirdparty/videojs/video-js.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/admin/sectiondetail.css" rel="stylesheet" type="text/css">

    <script type="text/javascript" src="<?php echo base_url() ?>resources/js/student/study_section.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>resources/js/student/study_scene.js"></script>
    <script src="<?php echo base_url() ?>resources/thirdparty/videojs/ie8/videojs-ie8.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url() ?>resources/thirdparty/videojs/video.js" type="text/javascript"></script>

    <script type="text/javascript" src="<?php echo base_url() ?>resources/thirdparty/marked/marked.min2.js"></script>
</head>
<script type="text/javascript">
    var site_url = "<?php echo site_url();?>";
    var taskid = "<?php echo $section[0]['TaskID'];?>";
    var sectioninsid = "<?php echo $section[0]['SectionInsID'];?>";
    var sectionname = "<?php echo $section[0]['SectionName'];?>";
    var sceneuuid = "<?php echo $section[0]['SceneUUID'];?>";
    var sectiontype = "<?php if($section[0]['TaskType'] == 2){ echo '';}else{ echo $section[0]['SectionType']; }?>";
    var finished = "<?php echo $section[0]['Finished'];?>";
    var SectionVideoFinished = "<?php echo $section[0]['SectionVideoFinished'];?>";
    var bool = true;
    var base_url = "<?php echo base_url() ?>"

    var sceneinstanceuuid = "<?php echo $section[0]['SceneInstanceUUID'];?>";
    var taskuuid = "<?php echo $section[0]['TaskUUID'];?>"
</script>

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
            <!--面包屑导航 start -->
            <div class="lable_title">
                <a href="<?php echo site_url().'Study/listunderway';?>" title="我的学习" class="for_lable">我的学习</a>&gt;
                <?php if($section[0]['TaskType'] == 2){ ?>
                    <a href="<?php echo site_url().'Study/listfinished';?>" title="已经完成的学习" class="for_lable">已经完成的学习</a>&gt;
                <?php }else{ ?>
                    <a href="<?php echo site_url().'Study/listunderway';?>" title="正在进行的学习" class="for_lable">正在进行的学习</a>&gt;
                <?php } ?>
                <a href="<?php echo site_url().'Study/studydetail?taskid='.$section[0]['TaskID'];?>" title="<?php echo $section[0]['TaskName'];?>" class="for_lable"><?php echo $section[0]['TaskName'];?></a>&gt;
                <a><?php echo  $section[0]['SectionName'];?></a>
            </div>
            <!--面包屑导航  end-->
            <div class="txtOrVideo">
                <div class="czsc"><?php $book_diff = array('初级','中级','高级'); echo  $section[0]['SectionName'];?>（难度：<?php echo $book_diff[$section[0]['SectionDiff']];?>）</div>
                <!-- ctf 不显示内容简介  -->
                <?php
                if($section[0]['SectionType'] != 1){
                    echo "<div class='title'>内容简介：</div>";
                    echo "<div class='smallTxt'>";
                    if(!empty($section[0]['SectionDesc'])){
                        echo $section[0]['SectionDesc'];
                    }else{
                        echo "暂无简介";
                    }
                    echo "</div>";
                }
                ?>
                <?php if($section[0]['SectionType'] == 1){ ?>
                    <div class='title'>ctf简介：</div>
                    <div class='smallTxt'>
                        <?php
                        if(!empty($section[0]['CtfContent'])){
                            echo $section[0]['CtfContent'];
                        }else{
                            echo "暂无简介";
                        }
                        ?>
                    </div>
                    <div class="changjing">
                        <?php
                        $picture = array('jpg','png','gif','jpeg');
                        //ctf 附件跟场景
                        $ctf_str = '<a target="_Blank" href="'.$section[0]['CtfUrl'].'"> '.$section[0]['CtfName'].'</a> <br>';
                        if($section[0]['CtfUrl'] == ''){

                            $file_type = explode('.',$section[0]['CtfResources']);
                            if(in_array($file_type[1],$picture)){ // 图片
                                $ctf_str = '<a href="'.base_url().'resources/files/ctf/'.$section[0]['CtfResources'].'" target="_blank"> '.$section[0]['CtfName'].'</a>';
                            }else{ // 附件
                                $ctf_str = '<a href="'.base_url().'resources/files/ctf/'.$section[0]['CtfResources'].'"> '.$section[0]['CtfName'].'</a>';
                            }
                        }
                        echo $ctf_str;
                        ?>
                    </div>
                <?php }else if($section[0]['SectionType'] == 0 && $section[0]['VideoUrl'] != null){
                    $type = '["flash","html5"]';
                    //不包含flv
                    if(strpos($section[0]['VideoUrl'],'flv') === false){
                        $type = '["html5","flash"]';
                    }
                ?>

                    <video  id="videoplay" class="video-js vjs-default-skin vjs-big-play-centered marCenter" controls preload="none" width="788" height="443" data-setup='{"example_option":true,"techOrder":<?php echo $type;?>}' >
                        <source src="<?php echo $section[0]['VideoUrl']; ?>" type="video/mp4"/>

                        <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
                    </video>
                    <script type="text/javascript">
                    //视屏兼容IE9，IE10,edag，部分兼容IE8
                        var ie = getBrowser();
                        if(ie=="ie"){
                            $("#videoplay").attr("data-setup",'{"example_option":true,"techOrder":["flash","html5"]}')
                        }
                        var myPlayer = videojs("videoplay");
                        myPlayer.ready(function(){
                            myPlayer.on('timeupdate', function() {
                                var vTime= this.duration()-this.currentTime();
                                var popZhi =$(".popVideoGo").height();
                                if(!popZhi){
                                    //finished==1 未学习本小节
                                    if(vTime!=0&&vTime <= 1 && SectionVideoFinished == 0){
                                        this.pause();
                                        videoPlayEnd();
                                        myPlayer.currentTime(1);

                                    }
                                }
                            })
                        });
                    </script>
                    
                <?php } else if($section[0]['SectionType'] == 2){ ?>
                    <div class="requestBtn">
                        <?php if($section[0]['TaskType'] == 2){ ?>
                        <button class="resBtn noCanBg">申请场景</button>
                        <?php }else{ ?>
                        <div class="taskprogr outHide">
                            <div class="taskpro">
                            </div>
                            <div class="resTxt" id="proTxt"></div>
                            <div class="stopBtn" title="取消">
                                <i class=" fa fa-ban"></i>
                            </div>
                        </div>
                        <button class="resBtn" id="successScene" sceneinstanceuuid="<?php echo $section[0]['SceneInstanceUUID'];?>">进入场景</button>
                        <button class="resBtn" id="createscene">申请场景</button>
                        <?php } ?>
                    </div>
                <?php }?>
                <div class="czsc">实验资料<div class="czsca"></div></div>
                <div class="czsc_con">
                    <?php
                    if(count($tool) > 0){
                        foreach ($tool as $key=>$val){
                            echo '<p class="tlist">'.($key+1).'. <a href="'.base_url().$val['ToolUrl'].'" onclick="">'.$val['ToolName'].' <i class="fa fa-cloud-download"></i></a></p>';
                        }
                    } else {
                        echo '<p class="smallTxt">*无工具</p>';
                    }
                    ?>
                </div>
                <div class="czsc">随堂练习题<span id="secScore" class="secScore"><?php if($section[0]['SectionAnswerFinished'] == 1){echo '当前小节总得分：'.$section[0]['SectionInsPoint'].'分';}?></span><div class="czsca"></div></div>
                <div class="czsc_con">
                    <?php
                    if($count_question > 0) {
                        $pointMap = array(5, 10, 20); //初难度 中难度 高难度
                         if($section[0]['SectionAnswerFinished'] == 1 || $section[0]['TaskType'] == 2){
                            foreach ($question as $key => $val) {
                                //附件处理 [显示附件]
                                $dataUrl = 0;$dataName = 0;
                                if($val['ResourceUrl'] != ''){
                                    $dataUrl = json_decode($val['ResourceUrl']);
                                    $dataName = json_decode($val['ResourceName']);
                                }
                                //分数显示
                                $que_score = ($val['QuestionScore'] / 100.0) * $pointMap[$section[0]["SectionDiff"]] * 1;
                                if($section[0]['VideoUrl'] != null){
                                    $que_score = ($val["QuestionScore"] / 100.0) * $pointMap[$section[0]["SectionDiff"]] * 0.4;
                                }

                                $judge = 'fa-close';
                                if($val['judge'] == 1){
                                    $judge = 'fa-check';
                                }
                                if($section[0]['TaskType'] == 2){
                                    $judge = '';
                                }
                            if ($val['QuestionType'] == 1) {
                                $QuestionChoose = explode('|||', $val['QuestionChoose']);
                                shuffle($QuestionChoose);
                                ?>
                                <div class="Right">
                                    <div class="questions">
                                        <span class="mark_span" id="ques<?php echo $val['QuestionID']; ?>"><i class="fa <?php echo $judge;?>"></i><?php echo ($key + 1) . '. (' . $que_score . '分)'; ?></span>
                                        <div id="question<?php echo $key?>" class="queTxt markdown-body" dataurl="<?php echo $dataUrl;?>" dataname="<?php echo $dataName;?>"></div>
                                        <script>
                                            document.getElementById('question<?php echo $key?>').innerHTML = marked("<?php echo $val['QuestionDesc'];?>");
                                        </script>
                                    </div>
                                    <?php ?>
                                    <ul>
                                        <?php foreach ($QuestionChoose as $key => $value) { ?>
                                            <li class=""><input type="radio" disabled="disabled" name="info[<?php echo $val['QuestionID']; ?>]" value="<?php echo $value ?>" <?php echo $val['Answer'] == $value ? 'checked' : '' ?>><label><?php echo $value; ?></label>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            <?php } else if ($val['QuestionType'] == 2) {
                                $QuestionChoose = explode('|||', $val['QuestionChoose']);
                                shuffle($QuestionChoose);
                                ?>
                                <div class="Right">
                                    <div class="questions">
                                        <span class="mark_span" id="ques<?php echo $val['QuestionID']; ?>"><i class="fa <?php echo $judge;?>"></i><?php echo ($key + 1) . '. (' . $que_score . '分)'; ?></span>
                                        <div id="question<?php echo $key?>" class="queTxt markdown-body" dataurl="<?php echo $dataUrl;?>" dataname="<?php echo $dataName;?>"></div>
                                        <script>
                                            document.getElementById('question<?php echo $key?>').innerHTML = marked("<?php echo $val['QuestionDesc'];?>");
                                        </script>
                                    </div>
                                    <?php ?>
                                    <ul>
                                        <?php
                                        if(strlen($val['Answer'])>1){
                                            $okanswer = explode('|||',$val['Answer']);
                                        }else{
                                            $okanswer=$val['Answer'];
                                        }
                                        foreach ($QuestionChoose as $key => $value) { ?>
                                            <li class=""><input type="checkbox" disabled="disabled" name="info[<?php echo $val['QuestionID']; ?>][]" value="<?php echo $value ?>" <?php echo @in_array($value,$okanswer) ? 'checked' : ''; ?>><label><?php echo $value; ?></label>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            <?php } else if ($val['QuestionType'] == 3) { ?>
                                <div class="Right">
                                    <div class="questions">
                                        <span class="mark_span" id="ques<?php echo $val['QuestionID']; ?>"><i class="fa <?php echo $judge;?>"></i><?php echo ($key + 1) . '. (' . $que_score . '分)'; ?></span>
                                        <div id="question<?php echo $key?>" class="queTxt markdown-body" dataurl="<?php echo $dataUrl;?>" dataname="<?php echo $dataName;?>"></div>
                                        <script>
                                            document.getElementById('question<?php echo $key?>').innerHTML = marked("<?php echo $val['QuestionDesc'];?>");
                                        </script>
                                    </div>
                                    <?php ?>
                                    <ul>
                                        <input class="answer" type="radio" disabled="disabled" name="info[<?php echo $val['QuestionID']; ?>]" value="对" <?php if($val['Answer'] == '对'){ echo 'checked';}?>><label>对</label>
                                        <input class="answer" type="radio" disabled="disabled" name="info[<?php echo $val['QuestionID']; ?>]" value="错" <?php if($val['Answer'] == '错'){ echo 'checked';}?>><label>错</label>
                                    </ul>
                                </div>
                            <?php } else if ($val['QuestionType'] == 4) { ?>
                                <div class="Right">
                                    <div class="questions">
                                        <span class="mark_span" id="ques<?php echo $val['QuestionID']; ?>"><i class="fa <?php echo $judge;?>"></i><?php echo ($key + 1) . '. (' . $que_score . '分)'; ?></span>
                                        <div id="question<?php echo $key?>" class="queTxt markdown-body" dataurl="<?php echo $dataUrl;?>" dataname="<?php echo $dataName;?>"></div>
                                        <script>
                                            document.getElementById('question<?php echo $key?>').innerHTML = marked("<?php echo $val['QuestionDesc'];?>");
                                        </script>
                                    </div>
                                    <?php if(empty($val['Answer'])){ echo '为空';} else{ ?>
                                    <input class="answer" type="text" name="info[<?php echo $val['QuestionID']; ?>]" vlaue="<?php echo $val['Answer'];?>" placeholder="<?php echo $val['Answer'];?>" disabled="disabled" >
                                    <?php } ?>
                                </div>
                            <?php } else if ($val['QuestionType'] == 5) { ?>
                                <div class="Right">
                                    <div class="questions">
                                        <span class="mark_span" id="ques<?php echo $val['QuestionID']; ?>"><i class="fa <?php echo $judge;?>"></i><?php echo ($key + 1) . '. (' . $que_score . '分)'; ?></span>
                                        <div id="question<?php echo $key?>" class="queTxt markdown-body" dataurl="<?php echo $dataUrl;?>" dataname="<?php echo $dataName;?>"></div>
                                        <script>
                                            document.getElementById('question<?php echo $key?>').innerHTML = marked("<?php echo $val['QuestionDesc'];?>");
                                        </script>
                                    </div>
                                    <?php ?>
                                    <?php if(empty($val['Answer'])){ echo '为空';} else{ ?>
                                        <input class="answer" type="text" name="info[<?php echo $val['QuestionID']; ?>]" vlaue="<?php echo $val['Answer'];?>" placeholder="<?php echo $val['Answer'];?>" disabled="disabled" >
                                    <?php } ?>
                                </div>
                            <?php
                                }
                            } ?>
                    <div class="btnBox">
                        <a href="javascript:;" class="publicOk noCanBg" id="">提交答案</a>
                    </div>
                    <?php
                        }else if($section[0]['SectionAnswerFinished'] == 0) {
                             //回答问题
                            ?>
                            <form id="Practice" action="#" method="post" onsubmit='return false;'>
                                <input type="hidden" name="TaskID" value="<?php echo $section[0]['TaskID']; ?>">
                                <input type="hidden" name="SectionInsID"
                                       value="<?php echo $section[0]['SectionInsID']; ?>">
                                <?php foreach ($question as $key => $val) {
                                    //附件处理 [显示附件]
                                    $dataUrl = 0;$dataName = 0;
                                    if($val['ResourceUrl'] != ''){
                                        $dataUrl = json_decode($val['ResourceUrl']);
                                        $dataName = json_decode($val['ResourceName']);
                                    }
                                    //分数显示
                                    $que_score = ($val["QuestionScore"] / 100.0) * $pointMap[$section[0]["SectionDiff"]] * 1;
                                    if($section[0]['VideoUrl'] != null){
                                        $que_score = ($val["QuestionScore"] / 100.0) * $pointMap[$section[0]["SectionDiff"]] * 0.4;
                                    }

                                    if ($val['QuestionType'] == 1) {
                                        $QuestionChoose = explode('|||', $val['QuestionChoose']);
                                        shuffle($QuestionChoose);
                                        ?>
                                        <div class="Right">
                                            <div class="questions">
                                                <span class="mark_span" id="ques<?php echo $val['QuestionID']; ?>"><i class="fa"></i><?php echo ($key + 1) . '. (' . $que_score . '分)'; ?></span>
                                                <div id="question<?php echo $key?>" class="queTxt markdown-body" dataurl="<?php echo $dataUrl;?>" dataname="<?php echo $dataName;?>"></div>
                                                <script>
                                                    document.getElementById('question<?php echo $key?>').innerHTML = marked("<?php echo $val['QuestionDesc'];?>");
                                                </script>
                                            </div>
                                            <?php ?>
                                            <ul>
                                                <?php foreach ($QuestionChoose as $key => $value) { ?>
                                                    <li class=""><input type="radio" name="info[<?php echo $val['QuestionID']; ?>]" value="<?php echo $value ?>" id="<?php echo $key.$val['QuestionID']; ?>"><label for="<?php echo $key.$val['QuestionID']; ?>"><?php echo $value; ?></label>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    <?php } else if ($val['QuestionType'] == 2) {
                                        $QuestionChoose = explode('|||', $val['QuestionChoose']);
                                        shuffle($QuestionChoose);
                                        ?>
                                        <div class="Right">
                                            <div class="questions">
                                                <span class="mark_span" id="ques<?php echo $val['QuestionID']; ?>"><i class="fa"></i><?php echo ($key + 1) . '. (' . $que_score . '分)'; ?></span>
                                                <div id="question<?php echo $key?>" class="queTxt markdown-body" dataurl="<?php echo $dataUrl;?>" dataname="<?php echo $dataName;?>"></div>
                                                <script>
                                                    document.getElementById('question<?php echo $key?>').innerHTML = marked("<?php echo $val['QuestionDesc'];?>");
                                                </script>
                                            </div>
                                            <?php ?>
                                            <ul>
                                                <?php foreach ($QuestionChoose as $key => $value) { ?>
                                                    <li class=""><input type="checkbox" name="info[<?php echo $val['QuestionID']; ?>][]" value="<?php echo $value ?>" id="<?php echo $key.$val['QuestionID']; ?>"><label for="<?php echo $key.$val['QuestionID']; ?>"><?php echo $value; ?></label>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    <?php } else if ($val['QuestionType'] == 3) { ?>
                                        <div class="Right">
                                            <div class="questions">
                                                <span class="mark_span" id="ques<?php echo $val['QuestionID']; ?>"><i class="fa"></i><?php echo ($key + 1) . '. (' . $que_score . '分)'; ?></span>
                                                <div id="question<?php echo $key?>" class="queTxt markdown-body" dataurl="<?php echo $dataUrl;?>" dataname="<?php echo $dataName;?>"></div>
                                                <script>
                                                    document.getElementById('question<?php echo $key?>').innerHTML = marked("<?php echo $val['QuestionDesc'];?>");
                                                </script>
                                            </div>
                                            <?php ?>
                                            <ul>
                                                <input class="answer" type="radio" name="info[<?php echo $val['QuestionID']; ?>]" value="对" id="<?php echo '1'.$val['QuestionID']; ?>"><label for="<?php echo '1'.$val['QuestionID']; ?>">对</label>
                                                <input class="answer" type="radio" name="info[<?php echo $val['QuestionID']; ?>]" value="错" id="<?php echo '0'.$val['QuestionID']; ?>"><label for="<?php echo '0'.$val['QuestionID']; ?>">错</label>
                                            </ul>
                                        </div>
                                    <?php } else if ($val['QuestionType'] == 4) { ?>
                                        <div class="Right">
                                            <div class="questions">
                                                <span class="mark_span" id="ques<?php echo $val['QuestionID']; ?>"><i class="fa"></i><?php echo ($key + 1) . '. (' . $que_score . '分)'; ?></span>
                                                <div id="question<?php echo $key?>" class="queTxt markdown-body" dataurl="<?php echo $dataUrl;?>" dataname="<?php echo $dataName;?>"></div>
                                                <script>
                                                    document.getElementById('question<?php echo $key?>').innerHTML = marked("<?php echo $val['QuestionDesc'];?>");
                                                </script>
                                            </div>
                                            <?php ?>
                                            <input class="answer" type="text" name="info[<?php echo $val['QuestionID']; ?>]">
                                        </div>
                                    <?php } else if ($val['QuestionType'] == 5) { ?>
                                        <div class="Right">
                                            <div class="questions">
                                                <span class="mark_span" id="ques<?php echo $val['QuestionID']; ?>"><i class="fa"></i><?php echo ($key + 1) . '. (' . $que_score . '分)'; ?></span>
                                                <div id="question<?php echo $key?>" class="queTxt markdown-body" dataurl="<?php echo $dataUrl;?>" dataname="<?php echo $dataName;?>"></div>
                                                <script>
                                                    document.getElementById('question<?php echo $key?>').innerHTML = marked("<?php echo $val['QuestionDesc'];?>");
                                                </script>
                                            </div>
                                            <?php ?>
                                            <input class="answer" type="text" name="info[<?php echo $val['QuestionID']; ?>]">
                                        </div>
                                        <?php
                                    }
                                } ?>
                            </form>
                            <div class="btnBox">
                                <a href="javascript:;" class="publicOk " id="practiceHint">提交答案</a>
                            </div>
                            <?php
                        }
                    } else {
                        echo '<p class="smallTxt">*无练习题</p>';
                    }
                    ?>
                </div>
                <div class="czsc" id="">实验操作手册 <div class="czsca"></div></div>
                <div id="sectionDoc" class="markdown-body czsc_con smallTxt">
                    <?php
                    if(!empty($section[0]['SectionDoc'])){
                        $SectionDoc = $section[0]['SectionDoc'];
                    }else{
                        $SectionDoc = '*无操作手册';
                    } ?>
                </div>
                <script>
                    document.getElementById('sectionDoc').innerHTML = marked("<?php echo $SectionDoc; ?>");
                </script>


            </div>

        </div>
        <!--right stop-->
    </div>


    <!--center stop-->
    <!--footer start-->
    <?php $this->load->view('public/footer.php')?>
    <!--footer stop-->
</div>
<!-- 提示信息 -->
<div class="maskbox"></div>
<!--结束提示弹窗-->
<div class="popUpset animated " id="practiceBox">
    <form action="" method="post">
        <div class="popTitle">
            <p>提示操作</p>
            <a href="javascript:;" class="close close-1"></a>
        </div>
        <input type="hidden" name="taskid" id="taskid"/>
        <input type="hidden" name="scenetaskid" id="taskid"/>
        <div class="infoBox">
            <p class="promptNews">确定现在提交随堂练习题?</p>
            <div class="btnBox">
                <a href="javascript:;" class="publicOk " id="practiceSub">确定</a>
                <a href="javascript:;" class="publicNo hidePop-1">取消</a>
            </div>
        </div>
    </form>
</div>
<!-- 学习完毕提示-->
<div class="popUpset animated " id="scoreBox">
    <form action="" method="post">
        <div class="popTitle">
            <p>学习完毕</p>
            <a href="javascript:;" class="close thisSection"></a>
        </div>
        <div class="infoBox">
            <p class="promptNews">恭喜！您已学完本小节</p>
            <div class="btnBox">
                <a href="javascript:;" class="publicOk" id="nextSection">进入下一节</a>
                <a href="javascript:;" class="publicNo thisSection">留在本节</a>
            </div>
        </div>
    </form>
</div>
<!-- 删除场景提示-->
<div class="popUpset animated " id="delSceneBox">
    <form action="" method="post">
        <div class="popTitle">
            <p>提示框</p>
            <a href="javascript:;" class="close close-1"></a>
        </div>
        <div class="infoBox">
            <p class="promptNews">确定结束正在下发的场景?</p>
            <div class="btnBox">
                <a href="javascript:;" class="publicOk " id="delBtn">确定</a>
                <a href="javascript:;" class="publicNo hidePop-1">取消</a>
            </div>
        </div>
    </form>
</div>
<!-- 场景下发提示提示-->
<div class="popUpset animated " id="sceneBox">
    <form>
        <div class="popTitle">
            <p>存在未结束的场景</p>
            <a href="javascript:;" class="close close-1"></a>
        </div>
        <div class="infoBox">
            <p class="promptNews">有一个场景还未结束!</p>
            <div class="btnBox">
                <a href="javascript:;" class="publicOk" id="accessBtn">进入</a>
                <a href="javascript:;" class="publicOk" id="endBtn">结束，并下发场景</a>
                <a href="javascript:;" class="publicOk hidePop-1">取消</a>
            </div>
        </div>
    </form>
</div>
<!-- 提示信息 -->
<div class="popUpset animated " id="okBox" >
    <form>
        <div class="popTitle">
            <p>提示操作</p>
            <a href="javascript:;" class="close close-1"></a>
        </div>
        <div class="infoBox">
            <p class="promptNews promptUp"></p>
        </div>
    </form>
</div>

<a class="goTop" href="#backTop"><i class="fa fa-angle-up"></i></a>
<script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/prompt.js"></script>
</body>
</html>
<script src="<?php echo base_url() ?>resources/js/teacher/exam_question.js"></script>
