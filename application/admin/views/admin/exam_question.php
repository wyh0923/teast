<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo $data[0]['ExamName'];?></title>
    <link href="<?php echo base_url() ?>resources/css/public/reset.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/animate.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/content.css" rel="stylesheet" type="text/css" />
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
        <p class="title"><span><?php echo $data[0]['ExamName'];?></span></p>
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
                $scene_str = '<div class="changJbtn "><button class="noCanBg">申请场景</button></div>';
            }
            ?>
            <div class="question">
                <?php if($v['QuestionType'] == 1){ $letter=65; ?>
                    <div class="queTitle">
                        <div class="queNumber"><span><?php echo $k+1;?>. (本题 <?php echo $v['Score'];?> 分)</span>
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
                            <?php foreach($QuestionChoose as $key=>$value){
                                $checked ='';$span = "";
                                if($v['QuestionAnswer']==$value){$checked = "checked"; $span = "<span class='rightGo'>正确答案</span>";}
                                ?>
                                <li><input disabled class="submitanswers" status="1" type="radio" questionid="<?php echo $v['QuestionID']; ?>" name="<?php echo $v['QuestionID']; ?>" value="<?php echo $value; ?>" <?php echo $checked; ?> ><?php echo chr($letter++); ?>.<?php echo $value; ?><?php echo $span; ?></li>
                            <?php } ?>
                        </ul>
                    </div>
                <?php } ?>
                <?php if($v['QuestionType'] == 2){ $letter=65; ?>
                    <div class="queTitle">
                        <div class="queNumber"><span><?php echo $k+1;?>. (本题 <?php echo $v['Score'];?> 分)</span>
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
                    if(strlen($v['QuestionAnswer'])>1){
                        $okanswer = explode('|||',$v['QuestionAnswer']);
                    }else{
                        $okanswer = $v['QuestionAnswer'];
                    }
                    ?>
                    <div class="anser">
                        <ul>
                            <?php foreach($QuestionChoose as $key=>$value): ?>
                                <li><input disabled class="submitanswers" status="2" type="checkbox" questionid="<?php echo $v['QuestionID']; ?>" name="<?php echo $v['QuestionID']; ?>[]" value="<?php echo $value; ?>" <?php echo @in_array($value,$okanswer) ? 'checked' : ''; ?>><?php echo chr($letter++); ?>.<?php echo $value; ?><?php echo @in_array($value,$okanswer) ? '<span class="rightGo">正确答案</span>' : ''; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php } ?>
                <?php if($v['QuestionType'] == 3){ ?>
                    <div class="queTitle">
                        <div class="queNumber"><span><?php echo $k+1;?>. (本题 <?php echo $v['Score'];?> 分)</span>
                            <?php echo $scene_str;?>
                        </div>
                        <div class="queTxt markdown-body" id="qus_<?php echo $k+1;?>" dataurl="<?php echo $dataUrl;?>" dataname="<?php echo $dataName;?>"></div>
                        <script>
                            document.getElementById('qus_<?php echo $k+1;?>').innerHTML = marked("<?php echo $v['QuestionDesc'];?>");
                        </script>
                    </div>
                    <div class="anser">
                        <ul>
                            <input disabled class="submitanswers" status="3" type="radio" questionid="<?php echo $v['QuestionID']; ?>" name="<?php echo $v['QuestionID']; ?>" value="对" <?php if($v['QuestionAnswer'] == '对'){ echo 'checked';}?>>正确<?php if($v['QuestionAnswer'] == '对'){ echo '<span class="rightGo">正确答案</span>';}?>
                            <input disabled class="submitanswers" status="3" type="radio" questionid="<?php echo $v['QuestionID']; ?>" name="<?php echo $v['QuestionID']; ?>" value="错" <?php if($v['QuestionAnswer'] == '错'){ echo 'checked';}?>>错误<?php if($v['QuestionAnswer'] == '错'){ echo '<span class="rightGo">正确答案</span>';}?>
                        </ul>
                    </div>
                <?php } ?>
                <?php if($v['QuestionType'] == 4){ ?>
                    <div class="queTitle">
                        <div class="queNumber"><span><?php echo $k+1;?>. (本题 <?php echo $v['Score'];?> 分)</span>
                            <?php echo $scene_str;?>
                        </div>
                        <div class="queTxt markdown-body" id="qus_<?php echo $k+1;?>" dataurl="<?php echo $dataUrl;?>" dataname="<?php echo $dataName;?>"></div>
                        <script>
                            document.getElementById('qus_<?php echo $k+1;?>').innerHTML = marked("<?php echo $v['QuestionDesc'];?>");
                        </script>
                    </div>
                    <div class="anser">
                        <span class="nodoNews"  questionid="<?php echo $v['QuestionID']; ?>"><?php echo $v['QuestionAnswer'] ?></span>
                        <span class='rightGo'>正确答案</span>
                    </div>
                <?php } ?>
                <?php if($v['QuestionType'] == 5){ ?>
                    <div class="queTitle">
                        <div class="queNumber"><span><?php echo $k+1;?>. (本题 <?php echo $v['Score'];?> 分)</span>
                            <?php echo $scene_str;?>
                        </div>
                        <div class="queTxt markdown-body" id="qus_<?php echo $k+1;?>" dataurl="<?php echo $dataUrl;?>" dataname="<?php echo $dataName;?>"></div>
                        <script>
                            document.getElementById('qus_<?php echo $k+1;?>').innerHTML = marked("<?php echo $v['QuestionDesc'];?>");
                        </script>
                    </div>
                    <div class="anser">
                        <span class="nodoNews" questionid="<?php echo $v['QuestionID']; ?>"><?php echo $v['QuestionAnswer'] ?></span>
                        <span class='rightGo'>正确答案</span>
                    </div>
                <?php } ?>

            </div>
        <?php } ?>
        <!--        END -->

    </div>
    <div class="btn">
        <a class="btnBlue" href="javascript:window.close();">关闭</a>
    </div>

</div>

</body>
</html>
<script src="<?php echo base_url() ?>resources/js/admin/exam_question.js"></script>
