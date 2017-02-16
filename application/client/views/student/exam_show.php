<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo $data[0]['TaskName'];?></title>
    <link href="<?php echo base_url() ?>resources/css/public/reset.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/answerpaper.css" rel="stylesheet" type="text/css">

    <link href="<?php echo base_url() ?>resources/css/public/markdown.css" rel="stylesheet" type="text/css">
    <script src="<?php echo base_url() ?>resources/js/public/jquery-1.11.0.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>resources/thirdparty/marked/marked.min2.js"></script>
    <script>
        var base_url = "<?php echo base_url();?>";
    </script>
</head>

<body>
<div class="anserBox">
    <div class="anserTitle">
        <p class="title"><span><?php echo $data[0]['TaskName'];?></span></p>
        <?php if(isset($task) && !empty($task) && $task[0]['TaskType']==2){?>
        <p>&nbsp;</p>
        <p align="center">交卷时间：<?php echo date('Y-m-d H:i:s', empty($task[0]['TaskFinishedTime'])?$task[0]['TaskEndTime']:$task[0]['TaskFinishedTime']);?></p>
        <?php }?>
    </div>
    <div class="questionBox">
        <div class="questionBox">
            <?php $right = 0;
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
                    $scene_str = '<div class="changJbtn "><button class="noCanBg">申请场景</button></div>';
                }
                ?>
                <div class="question">
                    <?php if($v['QuestionType'] == 1){ $letter=65; ?>
                        <div class="queTitle <?php if($v['QuestionAnswer'] == $v['Answer']){ $right++; echo 'queTitleRight';}else{ echo 'queTitleError';}?>">
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
                                    <li><input disabled class="submitanswers" status="1" type="radio" questionid="<?php echo $v['QuestionID']; ?>" name="<?php echo $v['QuestionID']; ?>" value="<?php echo $value; ?>" <?php echo $v['Answer'] == $value ? 'checked' : '' ?> ><?php echo chr($letter++); ?>.<?php echo $value; ?></li>
                                <?php } ?>
                            </ul>
                        </div>
                    <?php } ?>
                    <?php if($v['QuestionType'] == 2){ $letter=65;
                        $questionanswer = explode('|||',$v['QuestionAnswer']);
                        $answer = explode('|||',$v['Answer']);
                        $arr_diff = array_diff($questionanswer, $answer);
                        ?>
                        <div class="queTitle <?php if(count($arr_diff) > 0){echo 'queTitleError';}else{ $right++; echo 'queTitleRight';}?>">
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
                                    <li><input disabled class="submitanswers" status="2" type="checkbox" questionid="<?php echo $v['QuestionID']; ?>" name="<?php echo $v['QuestionID']; ?>[]" value="<?php echo $value; ?>" <?php echo @in_array($value,$okanswer) ? 'checked' : ''; ?>><?php echo chr($letter++); ?>.<?php echo $value; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php } ?>
                    <?php if($v['QuestionType'] == 3){ ?>
                        <div class="queTitle <?php if($v['QuestionAnswer'] == $v['Answer']){ $right++; echo 'queTitleRight';}else{ echo 'queTitleError';}?>">
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
                                <input disabled class="submitanswers" status="3" type="radio" questionid="<?php echo $v['QuestionID']; ?>" name="<?php echo $v['QuestionID']; ?>" value="对" <?php if($v['Answer'] == '对'){ echo 'checked';}?>>正确
                                <input disabled class="submitanswers" status="3" type="radio" questionid="<?php echo $v['QuestionID']; ?>" name="<?php echo $v['QuestionID']; ?>" value="错" <?php if($v['Answer'] == '错'){ echo 'checked';}?>>错误
                            </ul>
                        </div>
                    <?php } ?>
                    <?php if($v['QuestionType'] == 4){ ?>
                        <div class="queTitle <?php if($v['QuestionAnswer'] == $v['Answer']){ $right++; echo 'queTitleRight';}else{ echo 'queTitleError';}?>">
                            <div class="queNumber"><span><?php echo $k+1;?>. (本题 <?php echo $v['QuestionScore'];?> 分)</span>
                                <?php echo $scene_str;?>
                            </div>
                            <div class="queTxt markdown-body" id="qus_<?php echo $k+1;?>" dataurl="<?php echo $dataUrl;?>" dataname="<?php echo $dataName;?>"></div>
                            <script>
                                document.getElementById('qus_<?php echo $k+1;?>').innerHTML = marked("<?php echo $v['QuestionDesc'];?>");
                            </script>
                        </div>
                        <div class="anser">
                            <input disabled class="submitanswers" status="4" type="text"  questionid="<?php echo $v['QuestionID']; ?>" name="<?php echo $v['QuestionID']; ?>" value="<?php echo $v['Answer'] ?>" placeholder="请输入答案...">
                        </div>
                    <?php } ?>
                    <?php if($v['QuestionType'] == 5){ ?>
                        <div class="queTitle <?php if($v['QuestionAnswer'] == $v['Answer']){ $right++; echo 'queTitleRight';}else{ echo 'queTitleError';}?>">
                            <div class="queNumber"><span><?php echo $k+1;?>. (本题 <?php echo $v['QuestionScore'];?> 分)</span>
                                <?php echo $scene_str;?>
                            </div>
                            <div class="queTxt markdown-body" id="qus_<?php echo $k+1;?>" dataurl="<?php echo $dataUrl;?>" dataname="<?php echo $dataName;?>"></div>
                            <script>
                                document.getElementById('qus_<?php echo $k+1;?>').innerHTML = marked("<?php echo $v['QuestionDesc'];?>");
                            </script>
                        </div>
                        <div class="anser">
                            <input disabled class="submitanswers" status="4" type="text"  questionid="<?php echo $v['QuestionID']; ?>" name="<?php echo $v['QuestionID']; ?>" value="<?php echo $v['Answer'] ?>" placeholder="请输入答案...">
                        </div>
                    <?php } ?>

                </div>
            <?php } ?>
            <!--        END -->

        </div>

    </div>

    <div class="scoreBox">
        <h2>
            <span>总得分:</span><span><?php echo $data[0]['TaskScore'];?></span>
        </h2>
        <?php $total = count($data);?>
        <p>共<?php echo $total;?>题,正确:<?php echo $right;?>题,错误:<?php echo $total-$right;?>题</p>

    </div>

</div>
</body>
</html>
<script src="<?php echo base_url() ?>resources/js/teacher/exam_question.js"></script>