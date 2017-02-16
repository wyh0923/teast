<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>个人统计中心-个人统计</title>
<link rel="shortcut icon" href="<?php echo base_url() ?>resources/imgs/public/title.ico">
<!--公用样式-->
<link href="<?php echo base_url() ?>resources/css/public/reset.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url() ?>resources/css/public/filter.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url() ?>resources/css/public/firstStart.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url() ?>resources/css/public/content.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url() ?>resources/thirdparty/font-awesome-4.5.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url() ?>resources/css/public/personaldetails.css" rel="stylesheet" type="text/css" />
<!--第三方样式-->

<!--header框架js-->
<script src="<?php echo base_url() ?>resources/js/public/jquery-1.11.0.js"></script>
<script src="<?php echo base_url() ?>resources/js/public/template.js"></script>
<script src="<?php echo base_url() ?>resources/thirdparty/statistic/vendor/echarts/echarts.js"></script>
<script src="<?php echo base_url() ?>resources/thirdparty/statistic/vendor/echarts/dist/chart/line.js"></script>
<script src="<?php echo base_url() ?>resources/thirdparty/statistic/vendor/echarts/dist/chart/bar.js"></script>
<script src="<?php echo base_url() ?>resources/thirdparty/statistic/vendor/echarts/dist/chart/radar.js"></script>
    <script src="<?php echo base_url() ?>resources/js/student/statistic.js"></script>
</head>
<script type="text/javascript">
    var site_url = '<?php echo site_url();?>';
</script>

<body>
<!--公用header框架开始-->
<?php $this->load->view('public/header.php')?>
<!--公用header框架结束-->
<div class="frame">
	<div class="main clearfix">
	    <!--公用menu框架开始-->
        <?php $this->load->view('public/left.php')?>
	   <!--公用menu框架结束-->
	   <!--公用centent框架开始-->
       <div class="content">
      		 <div class="xuangxiang_two">
                <dl onclick="gotourl('Study/listfinished')">
                    <dt class="fa fa-camera-retro fa-2x"></dt>
                    <dd>完成学习 : <span id="StudyTotal"><?php echo $study;?></span></dd>
                </dl>
                <dl onclick="gotourl('Exam/listfinished')">
                    <dt class="fa fa-map-o fa-2x"></dt>
                    <dd>完成考试 : <span id="ExamTotal"><?php echo $exam;?></span></dd>
                </dl>
                <dl onclick="gotourl('Personal/log')">
                    <dt class="fa fa-camera-retro fa-2x"></dt>
                    <dd>总得分 : <span id="ScoreTotal"><?php echo $total_score;?></span></dd>
                </dl>
                <dl onclick="gotourl('Study/listfinished')">
                    <dt class="fa fa-edit fa-2x"></dt>
                    <dd>完成课时（节） : <span id="SectionTotal"><?php echo $section;?></span></dd>
                </dl>
            </div>
            <div class="studentTitle">
            个人能力雷达
       		</div>
            <div class="lookHere"><!-- 展示框 -->
                 <div class="flyTo"><!-- 活动框 -->
                       <div class="mapsPars">
                        <?php $count_book = count($book_score);$kk = 1;
                        if(($count_book) > 0) {
                            foreach ($book_score as $key => $val) {
                                if (count($val['list']) != 0) {
                                    ?>
                                    <div class="mapsBox">
                                        <h3 class="echartitle"><?php echo $val['name']; ?></h3>
                                        <div class="maps clearfix">
                                            <div class="posMaps" id="studentcharts<?php echo $key; ?>">

                                            </div>
                                            <script type="text/javascript">
                                                require(
                                                    [
                                                        'echarts',
                                                        'echarts/chart/line', // 按需加载所需图表，如需动态类型切换功能，别忘了同时加载相应图表
                                                        'echarts/chart/bar',
                                                        'echarts/chart/radar',
                                                    ],
                                                    function (ec) {
                                                        var myChart = ec.init(document.getElementById('studentcharts<?php echo $key;?>'));
                                                        option = {
                                                            title: {
                                                                text: '',
                                                                subtext: ''
                                                            },
                                                            tooltip: {
                                                                trigger: 'axis'
                                                            },
                                                            toolbox: {
                                                                show: false,
                                                                feature: {
                                                                    mark: {show: false},
                                                                    dataView: {show: false, readOnly: false},
                                                                    restore: {show: false},
                                                                    saveAsImage: {show: false}
                                                                }
                                                            },
                                                            polar: [
                                                                {
                                                                    indicator: [
                                                                        <?php foreach ($val['list'] as $itemname): ?>
                                                                        {text: '<?php echo $itemname['ArchitectureName'];?>'},
                                                                        <?php endforeach; ?>
                                                                    ]
                                                                }
                                                            ],
                                                            calculable: true,
                                                            series: [
                                                                {
                                                                    type: 'radar',
                                                                    data: [
                                                                        {
                                                                            value: [<?php foreach ($val['list'] as $itemname): ?>
                                                                                <?php echo $itemname['score'] . ','; ?>
                                                                                <?php endforeach; ?>],
                                                                            name: '分数'
                                                                        }
                                                                    ],
                                                                }
                                                            ]
                                                        };
                                                        myChart.setOption(option);
                                                    }
                                                );
                                            </script>
                                            <div class="table_page">
                                                <table class="bcg_table">
                                                    <thead>
                                                    <tr class="table-title">
                                                        <td>体系</td>
                                                        <td>积分</td>
                                                    </tr>
                                                    </thead>
                                                    <tbody class="tableChild">
                                                    <?php foreach ($val['list'] as $v) { ?>
                                                        <tr>
                                                            <td title="<?php echo $v['ArchitectureName']; ?>"><?php echo $v['ArchitectureName']; ?></td>
                                                            <td><?php echo $v['ArchitectureScore']; ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                    </tbody>
                                                </table>
                                                <script type="text/javascript">
                                                    var pNumber = $(".tableChild").length;
                                                    for (i = 0; i < pNumber; i++) {
                                                        var cNumber = $(".tableChild").eq(i).children().length;
                                                        if (cNumber == 1) {

                                                            $(".posMaps").eq(i).css({"margin-top": "0"})
                                                        }
                                                        if (cNumber == 2) {

                                                            $(".posMaps").eq(i).css({"margin-top": "-20px"})
                                                        }
                                                        if (cNumber == 3) {

                                                            $(".posMaps").eq(i).css({"margin-top": "-30px"})
                                                        }
                                                    }
                                                </script>
                                            </div>
                                        </div>
                                    </div>
                                <?php }
                                //只显示2个 2个后的切换显示
                                if ($kk % 2 == 0 && $kk != $count_book) {
                                    echo '</div>';
                                    echo '<div class="mapsPars">';
                                }
                                $kk++;
                            }
                        }else{ ?>
                            <div class="mapsBox">
                                <h3 class="echartitle">暂无</h3>
                                <div class="maps clearfix">
                                    <div class="posMaps" id="studentcharts">

                                    </div>
                                    <script type="text/javascript">
                                        require(
                                            [
                                                'echarts',
                                                'echarts/chart/line', // 按需加载所需图表，如需动态类型切换功能，别忘了同时加载相应图表
                                                'echarts/chart/bar',
                                                'echarts/chart/radar',
                                            ],
                                            function (ec) {
                                                var myChart = ec.init(document.getElementById('studentcharts'));
                                                option = {
                                                    title: {
                                                        text: '',
                                                        subtext: ''
                                                    },
                                                    tooltip: {
                                                        trigger: 'axis'
                                                    },
                                                    toolbox: {
                                                        show: false,
                                                        feature: {
                                                            mark: {show: false},
                                                            dataView: {show: false, readOnly: false},
                                                            restore: {show: false},
                                                            saveAsImage: {show: false}
                                                        }
                                                    },
                                                    polar: [
                                                        {
                                                            indicator: [
                                                                {text: '暂无'},
                                                            ]
                                                        }
                                                    ],
                                                    calculable: true,
                                                    series: [
                                                        {
                                                            type: 'radar',
                                                            data: [
                                                                {
                                                                    value: [0,0],
                                                                    name: '分数'
                                                                }
                                                            ],
                                                        }
                                                    ]
                                                };
                                                myChart.setOption(option);
                                            }
                                        );
                                    </script>
                                    <div class="table_page">
                                        <table class="bcg_table">
                                            <thead>
                                            <tr class="table-title">
                                                <td>体系</td>
                                                <td>积分</td>
                                            </tr>
                                            </thead>
                                            <tbody class="tableChild">
                                                <tr>
                                                    <td title="暂无">暂无</td>
                                                    <td>0</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <script type="text/javascript">
                                            var pNumber = $(".tableChild").length;
                                            for (i = 0; i < pNumber; i++) {
                                                var cNumber = $(".tableChild").eq(i).children().length;
                                                if (cNumber == 1) {

                                                    $(".posMaps").eq(i).css({"margin-top": "0"})
                                                }
                                                if (cNumber == 2) {

                                                    $(".posMaps").eq(i).css({"margin-top": "-20px"})
                                                }
                                                if (cNumber == 3) {

                                                    $(".posMaps").eq(i).css({"margin-top": "-30px"})
                                                }
                                            }
                                        </script>
                                    </div>
                                </div>
                            </div>
                        <?php }?>
                    </div>
                </div>
           </div>
       </div>
		
		<!--公用centent框架结束-->
	</div>
        <div class="goToThere left_icon left_btn"><i class="fa fa-arrow-left"></i></div><!-- left-->
        <div class="goToThere right_icon right_btn"><i class="fa fa-arrow-right"></i></div>
	<!--公用fotter框架开始-->
    <?php $this->load->view('public/footer.php')?>
	<!--公用fotter框架结束-->
</div>

    <script>
    
    </script>
</body>
</html>
