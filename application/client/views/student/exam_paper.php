<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo $data[0]['TaskName'];?></title>
    <link href="<?php echo base_url() ?>resources/css/public/reset.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/animate.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/thirdparty/font-awesome-4.5.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>resources/css/public/content.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>resources/css/public/answerpaper.css" rel="stylesheet" type="text/css">

    <link href="<?php echo base_url() ?>resources/css/public/markdown.css" rel="stylesheet" type="text/css">
    <script src="<?php echo base_url() ?>resources/js/public/jquery-1.11.0.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>resources/thirdparty/marked/marked.min2.js"></script>
</head>
<script type="text/javascript">
    var site_url = '<?php echo site_url(); ?>';
    var base_url = '<?php echo base_url(); ?>';
    var taskid = '<?php echo $data[0]['TaskID'];?>';
    var scenetaskid = '<?php echo $data[0]['SceneTaskID'];?>';
    var sceneinstanceuuid = '<?php echo $sceneinstanceuuid;?>';
    var taskuuid = '<?php echo $taskuuid;?>';
    var taskname = '<?php echo $data[0]['TaskName'];?>';
    var id = '<?php echo $id;?>';
</script>
<body>
<div class="anserBox">
    <div class="anserTitle">
        <p class="title"><span><?php echo $data[0]['TaskName'];?></span></p>
        <p class="timeGo"><span>剩余时间：</span><span id="timeGo"></span></p>


    </div>
    <div class="questionBox">
        <?php
        $picture = array('jpg','png','gif','jpeg');
        foreach($data as $k=>$v){
            //附件处理 [显示附件]
            $dataUrl = 0;$dataName = 0;
            if($v['ResourceUrl'] != ''){
                $dataUrl = json_decode($v['ResourceUrl']);
                $dataName = json_decode($v['ResourceName']);
            }
            //题目中 有ctf或实验场景
            $scene_str = '';
            if($v['QuestionLinkType'] == 1){
                //ctf 附件和ctf场景
                $scene_str = '<span class="changjing">ctf场景：</span><a href="'.$v['CtfUrl'].'" target="_blank"> '.$v['CtfName'].'</a>';
                if($v['CtfUrl'] == ''){
                    $scene_str = '<span class="changjing">ctf附件：</span>';
                    $file_type = explode('.',$v['CtfResources']);
                    if(!empty($file_type[1]) && in_array($file_type[1],$picture)){ // 图片
                        $scene_str .= '<a href="'.base_url().'resources/files/ctf/'.$v['CtfResources'].'" target="_blank"> '.$v['CtfName'].'</a>';
                    }else{ // 附件
                        $scene_str .= '<a href="'.base_url().'resources/files/ctf/'.$v['CtfResources'].'"> '.$v['CtfName'].'</a>';
                    }
                }

            } else if($v['QuestionLinkType'] == 2){
                $scene_str = '<div class="changJbtn "><button class="successScene">进入场景</button></div>';
            }
            ?>
        <div class="question">
            <?php if($v['QuestionType'] == 1){ $letter=65; ?>
                <div class="queTitle">
                    <div class="queNumber"><span><?php echo $k+1;?>. (本题 <?php echo $v['QuestionScore'];?> 分)</span>
                        <?php echo $scene_str;?>
                    </div>
                    <div class="queTxt markdown-body" id="qus_<?php echo $k+1;?>" dataurl="<?php echo $dataUrl;?>" dataname="<?php echo $dataName;?>"></div>
                    <script>
                        document.getElementById('qus_<?php echo $k+1;?>').innerHTML = marked("<?php echo $v['QuestionDesc'];?>");
                    </script>
                </div>
                    <?php $QuestionChoose = explode('|||',$v['QuestionChoose']);shuffle($QuestionChoose);?>
                <div class="anser">
                    <ul>
                        <?php foreach($QuestionChoose as $key=>$value){ ?>
                            <li><input onclick="saveanswer(this)" status="1" type="radio" questionid="<?php echo $v['QuestionID']; ?>" name="<?php echo $v['QuestionID']; ?>" value="<?php echo $value; ?>" <?php echo $v['Answer'] == $value ? 'checked' : '' ?> id="<?php echo $key.$v['QuestionID']; ?>"><label for="<?php echo $key.$v['QuestionID']; ?>"><?php echo chr($letter++); ?>.<?php echo $value; ?></label></li>
                        <?php } ?>
                    </ul>
                </div>
            <?php } ?>
            <?php if($v['QuestionType'] == 2){ $letter=65; ?>
                <div class="queTitle">
                    <div class="queNumber"><span><?php echo $k+1;?>. (本题 <?php echo $v['QuestionScore'];?> 分)</span>
                        <?php echo $scene_str;?>
                    </div>
                    <div class="queTxt markdown-body" id="qus_<?php echo $k+1;?>" dataurl="<?php echo $dataUrl;?>" dataname="<?php echo $dataName;?>"></div>
                    <script>
                        document.getElementById('qus_<?php echo $k+1;?>').innerHTML = marked("<?php echo $v['QuestionDesc'];?>");
                    </script>
                </div>
                <?php
                $QuestionChoose = explode('|||',$v['QuestionChoose']);
                shuffle($QuestionChoose);
                if(strlen($v['Answer'])>1){
                    $okanswer = explode('|||',$v['Answer']);
                }else{
                    $okanswer=$v['Answer'];
                }
                ?>
                <div class="anser">
                    <ul>
                        <?php foreach($QuestionChoose as $key=>$value): ?>
                            <li><input onclick="saveanswer(this)" status="2" type="checkbox" questionid="<?php echo $v['QuestionID']; ?>" name="<?php echo $v['QuestionID']; ?>[]" value="<?php echo $value; ?>" <?php echo @in_array($value,$okanswer) ? 'checked' : ''; ?>  id="<?php echo $key.$v['QuestionID']; ?>"><label for="<?php echo $key.$v['QuestionID']; ?>"><?php echo chr($letter++); ?>.<?php echo $value; ?></label></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php } ?>
            <?php if($v['QuestionType'] == 3){ ?>
                <div class="queTitle">
                    <div class="queNumber"><span><?php echo $k+1;?>. (本题 <?php echo $v['QuestionScore'];?> 分)</span>
                        <?php echo $scene_str;?>
                    </div>
                    <div class="queTxt markdown-body" id="qus_<?php echo $k+1;?>" dataurl="<?php echo $dataUrl;?>" dataname="<?php echo $dataName;?>"></div>
                    <script>
                        document.getElementById('qus_<?php echo $k+1;?>').innerHTML = marked("<?php echo $v['QuestionDesc'];?>");
                    </script>
                </div>
                <div class="anser">
                    <ul>
                        <input onclick="saveanswer(this)" status="3" type="radio" questionid="<?php echo $v['QuestionID']; ?>" name="<?php echo $v['QuestionID']; ?>" value="对" <?php if($v['Answer'] == '对'){ echo 'checked';}?>  id="<?php echo '1'.$v['QuestionID']; ?>"><label for="<?php echo '1'.$v['QuestionID']; ?>">正确</label>
                        <input onclick="saveanswer(this)" status="3" type="radio" questionid="<?php echo $v['QuestionID']; ?>" name="<?php echo $v['QuestionID']; ?>" value="错" <?php if($v['Answer'] == '错'){ echo 'checked';}?>  id="<?php echo  '0'.$v['QuestionID']; ?>"><label for="<?php echo  '0'.$v['QuestionID']; ?>">错误</label>
                    </ul>
                </div>
            <?php } ?>
            <?php if($v['QuestionType'] == 4){ ?>
                <div class="queTitle">
                    <div class="queNumber"><span><?php echo $k+1;?>. (本题 <?php echo $v['QuestionScore'];?> 分)</span>
                        <?php echo $scene_str;?>
                    </div>
                    <div class="queTxt markdown-body" id="qus_<?php echo $k+1;?>" dataurl="<?php echo $dataUrl;?>" dataname="<?php echo $dataName;?>"></div>
                    <script>
                        document.getElementById('qus_<?php echo $k+1;?>').innerHTML = marked("<?php echo $v['QuestionDesc'];?>");
                    </script>
                </div>
                <div class="anser">
                    <input onblur="saveanswer(this)" status="4" type="text"  questionid="<?php echo $v['QuestionID']; ?>" name="<?php echo $v['QuestionID']; ?>" value="<?php echo $v['Answer'] ?>" placeholder="请输入答案...">
                </div>
            <?php } ?>
            <?php if($v['QuestionType'] == 5){ ?>
                <div class="queTitle">
                    <div class="queNumber"><span><?php echo $k+1;?>. (本题 <?php echo $v['QuestionScore'];?> 分)</span>
                        <?php echo $scene_str;?>
                    </div>
                    <div class="queTxt markdown-body" id="qus_<?php echo $k+1;?>" dataurl="<?php echo $dataUrl;?>" dataname="<?php echo $dataName;?>"></div>
                    <script>
                        document.getElementById('qus_<?php echo $k+1;?>').innerHTML = marked("<?php echo $v['QuestionDesc'];?>");
                    </script>
                </div>
                <div class="anser">
                    <input onblur="saveanswer(this)" status="4" type="text"  questionid="<?php echo $v['QuestionID']; ?>" name="<?php echo $v['QuestionID']; ?>" value="<?php echo $v['Answer'] ?>" placeholder="请输入答案...">
                </div>
            <?php } ?>

        </div>
        <?php } ?>
        <!--        END -->

    </div>
    <div class="btn">
        <a class="btnYellow" href="javascript:;" id="submitexam">提交答案</a>
        <a class="btnBlue" href="<?php echo site_url().'Exam/listunderway';?>">保存并返回</a>
<!--        <a class="btnBlue" href="javascript:;">关闭</a>-->
    </div>

</div>
<!-- 提示 -->
<div class="maskbox"></div>
<!--结束提示弹窗-->
<div class="popUpset animated" id="examBox">
    <form action="" method="post">
        <div class="popTitle">
            <p>提示操作</p>
            <a href="javascript:;" id="" class="close close-1"></a>
        </div>
        <div class="infoBox">
            <p class="promptNews">您确认结束本场考试吗？提交后试卷不可再答</p>
            <div class="btnBox">
                <a href="javascript:;" class="publicOk " id="handpaper">确定</a>
                <a href="javascript:;" class="publicNo hidePop-1">取消</a>
            </div>
        </div>
    </form>
</div>
<!-- 提示信息 -->
<div class="popUpset animated" id="hintBox" >
    <form action="" method="post">
        <div class="popTitle">
            <p>提示操作</p>
            <a href="javascript:;" class="close close-1"></a>
        </div>
        <div class="infoBox">
            <p class="promptNews promptUp"></p>
        </div>
    </form>
</div>
<!-- 考试已结束 提示信息 -->
<div class="popUpset animated" id="okBox" >
    <form action="" method="post">
        <div class="popTitle">
            <p>提示操作</p>
            <a href="<?php echo site_url()."Exam/examshow?taskid=".$data[0]['TaskID'];?>" class="close"></a>
        </div>
        <div class="infoBox">
            <p class="promptNews promptUp"></p>
        </div>
    </form>
</div>

<!--提交试卷 提示信息 -->
<div class="popUpset animated " id="ExamHintBox" >
    <form action="" method="post">
        <div class="popTitle">
            <p>提示操作</p>
        </div>
        <div class="infoBox">
            <p class="promptNews promptUp"></p>
        </div>
    </form>
</div>
</body>
</html>
<script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/prompt.js"></script>
<script src="<?php echo base_url() ?>resources/js/student/exam_paper.js"></script>

