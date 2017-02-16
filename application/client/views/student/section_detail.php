<!DOCTYPE html>
<html>
<head>
    <title>知识体系-小节详情</title>
    <meta charset="utf-8">
    <link rel="shortcut icon" href="<?php echo base_url() ?>resources/imgs/public/title.ico">
    <script type='text/javascript' src="<?php echo base_url() ?>resources/js/public/jquery-1.11.0.js"></script>
    <script type='text/javascript' src="<?php echo base_url() ?>resources/js/public/judgment.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>resources/js/public/template.js"></script>
    <link href="<?php echo base_url() ?>resources/css/public/reset.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/markdown.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/thirdparty/font-awesome-4.5.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/firstStart.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/content.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/public/filter.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/thirdparty/videojs/video-js.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>resources/css/admin/sectiondetail.css" rel="stylesheet" type="text/css">

    <script src="<?php echo base_url() ?>resources/thirdparty/videojs/video.js" type="text/javascript"></script>
    <script src="<?php echo base_url() ?>resources/thirdparty/videojs/ie8/videojs-ie8.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>resources/thirdparty/marked/marked.min2.js"></script>
    <script>
        var base_url = "<?php echo base_url();?>";
    </script>
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
            <!--面包屑导航 start -->
            <div class="lable_title">
                <a href="<?php echo site_url().'Book/lists'?>" title="知识体系" class="for_lable">知识体系</a>&gt;
                <a href="<?php echo site_url().'Book/lists'?>" title="全部课程" class="for_lable">全部课程</a>&gt;
                <a href="<?php echo site_url().'Book/bookdetail?packageid='.$packageid;?>" title="<?php echo $packagename;?>" class="for_lable"><?php echo $packagename;?></a>&gt;
                <a><?php echo $section[0]['SectionName'];?></a>
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
                    <video  id="videoplay" class="video-js vjs-default-skin vjs-big-play-centered marCenter" controls preload="meta" width="788" height="443" data-setup='{"example_option":true,"techOrder":<?php echo $type;?>}'>
                        <source src="<?php echo $section[0]['VideoUrl']; ?>" type="video/mp4" />
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

                                if(vTime < 1){
                                    this.pause();
                                }
                            })
                        });
                    </script>
                <?php } else if($section[0]['SectionType'] == 2) { ?>
                    <div class="requestBtn marginBt0">
                        <button class="resBtn noCanBg">申请场景</button>
                    </div>
                    <p class="remindNews">* 您尚未开始本门课程的学习</p>
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
                <div class="czsc">随堂练习题<div class="czsca"></div></div>
                <div class="czsc_con">
                    <?php
                    if(count($question) > 0) {
                        $pointMap = array(5, 10, 20); //初难度 中难度 高难度
                        foreach ($question as $key => $val) {
                            //附件处理 [显示附件]
                            $dataUrl = 0;$dataName = 0;
                            if($val['ResourceUrl'] != ''){
                                $dataUrl = json_decode($val['ResourceUrl']);
                                $dataName = json_decode($val['ResourceName']);
                            }
                            //分数显示
                            $que_score = ($val["score"] / 100.0) * $pointMap[$section[0]["SectionDiff"]] * 1;
                            if($section[0]['VideoUrl'] != null){
                                $que_score = ($val["score"] / 100.0) * $pointMap[$section[0]["SectionDiff"]] * 0.4;
                            }

                            if($val['QuestionType'] == 1){
                                $QuestionChoose = explode('|||',$val['QuestionChoose']);shuffle($QuestionChoose);
                            ?>
                                <div class="Right">
                                    <div class="questions">
                                        <span class="mark_span"><?php echo ($key+1).'. ('.$que_score.'分)';?></span>
                                        <div id="question<?php echo $key?>" class="queTxt markdown-body" dataurl="<?php echo $dataUrl;?>" dataname="<?php echo $dataName;?>"></div>
                                        <script>
                                            document.getElementById('question<?php echo $key?>').innerHTML = marked("<?php echo $val['QuestionDesc'];?>");
                                        </script>
                                    </div>
                                    <?php ?>
                                    <ul>
                                        <?php foreach($QuestionChoose as $key=>$value){ ?>
                                            <li class=""><input disabled type="radio" name="<?php echo $val['QuestionID']; ?>" value="<?php echo $value ?>"><label><?php echo $value; ?></label></li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            <?php }else if($val['QuestionType'] == 2){
                                $QuestionChoose = explode('|||',$val['QuestionChoose']);shuffle($QuestionChoose);
                            ?>
                                <div class="Right">
                                    <div class="questions">
                                        <span class="mark_span"><?php echo ($key+1).'. ('.$que_score.'分)';?></span>
                                        <div id="question<?php echo $key?>" class="queTxt markdown-body" dataurl="<?php echo $dataUrl;?>" dataname="<?php echo $dataName;?>"></div>
                                        <script>
                                            document.getElementById('question<?php echo $key?>').innerHTML = marked("<?php echo $val['QuestionDesc'];?>");
                                        </script>
                                    </div>
                                    <?php ?>
                                    <ul>
                                        <?php foreach($QuestionChoose as $key=>$value){ ?>
                                            <li class=""><input disabled type="checkbox" name="<?php echo $val['QuestionID']; ?>" value="<?php echo $value ?>"><label><?php echo $value; ?></label></li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            <?php }else if($val['QuestionType'] == 3){ ?>
                                <div class="Right">
                                    <div class="questions">
                                        <span class="mark_span"><?php echo ($key+1).'. ('.$que_score.'分)';?></span>
                                        <div id="question<?php echo $key?>" class="queTxt markdown-body" dataurl="<?php echo $dataUrl;?>" dataname="<?php echo $dataName;?>"></div>
                                        <script>
                                            document.getElementById('question<?php echo $key?>').innerHTML = marked("<?php echo $val['QuestionDesc'];?>");
                                        </script>
                                    </div>
                                    <?php ?>
                                    <ul>
                                        <input disabled class="answer" type="radio" name="<?php echo $val['QuestionID']; ?>" value="对"><label>对</label>
                                        <input disabled class="answer" type="radio" name="<?php echo $val['QuestionID']; ?>" value="错"><label>错</label>
                                    </ul>
                                </div>
                            <?php }else if($val['QuestionType'] == 4){ ?>
                                <div class="Right">
                                    <div class="questions">
                                        <span class="mark_span"><?php echo ($key+1).'. ('.$que_score.'分)';?></span>
                                        <div id="question<?php echo $key?>" class="queTxt markdown-body" dataurl="<?php echo $dataUrl;?>" dataname="<?php echo $dataName;?>"></div>
                                        <script>
                                            document.getElementById('question<?php echo $key?>').innerHTML = marked("<?php echo $val['QuestionDesc'];?>");
                                        </script>
                                    </div>
                                    <?php ?>
                                    <input disabled class="answer" type="text" name="<?php echo $val['QuestionID']; ?>">
                                </div>
                            <?php }else if($val['QuestionType'] == 5){ ?>
                                <div class="Right">
                                    <div class="questions">
                                        <span class="mark_span"><?php echo ($key+1).'. ('.$que_score.'分)';?></span>
                                        <div id="question<?php echo $key?>" class="queTxt markdown-body" dataurl="<?php echo $dataUrl;?>" dataname="<?php echo $dataName;?>"></div>
                                        <script>
                                            document.getElementById('question<?php echo $key?>').innerHTML = marked("<?php echo $val['QuestionDesc'];?>");
                                        </script>
                                    </div>
                                    <?php ?>
                                    <input disabled class="answer" type="text" name="<?php echo $val['QuestionID']; ?>">
                                </div>
                            <?php }?>
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

<a class="goTop" href="#backTop"><i class="fa fa-angle-up"></i></a>
</body>
</html>
<script src="<?php echo base_url() ?>resources/js/teacher/exam_question.js"></script>