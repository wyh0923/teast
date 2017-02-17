function gotourl(url){
	url  = site_url +url;
	 window.location.href=url;
}



//填充班级能力统计表格
function set_containerrose(ary,arx){
    $('#containerrose').highcharts({
        data: {
            table: 'freq',
            startRow: 1,
            endRow: 100,
            endColumn: 7
        },

        chart: {
            polar: true,
            type: 'column'
        },

        title: {
            text: ''
        },

        subtitle: {
            text: ' '
        },

        pane: {
            size: '85%'
        },

        legend: {
            align: 'right',
            verticalAlign: 'top',
            y: 100,
            layout: 'vertical'
        },

        xAxis: {
            tickmarkPlacement: 'on'
        },

        yAxis: {
            min: 0,
            endOnTick: false,
            showLastLabel: true,
            title: {
                text: '积分'//Frequency ()
            },
            labels: {
                formatter: function () {
                    return this.value + '';
                }
            },
            reversedStacks: false
        },

        tooltip: {
            valueSuffix: ''
        },

        plotOptions: {
            series: {
                stacking: 'normal',
                shadow: false,
                groupPadding: cou,
                pointPlacement: 'on'
            }
        },
        credits: {
          enabled:false
        },
        exporting: {
            enabled:false
        }
    });
}

//学生积分top10
function set_studentscoretop5(ary,arx){
	$('#student_chart').highcharts({
        chart: {
            type: 'column',
            height:268
        },
        title: {
            text:''
        },
        colors: [
				'#2fd3c7',
				'#574cb6',
				'#00c6ff',
				'#ab802e',
				'#e57172',
				'#95c737',
				'#fca00b',
				'#ef7fdb',
				'#fce80b',
				'#68db62'
        ],
        xAxis: {
            categories: ary,
            labels:{
                style:{
                    'fontSize':13,
                    'font-family':'微软雅黑'
                }
            },
            gridLineColor: '#ccc',//横向网格线颜色
            gridLieWidth: 0 ,//横向网格线宽度
            title:'',
            gridLineWidth: 1,
            tickInterval:0,
            lineWidth:1
        },
        credits: {
            enabled: false
        },
        yAxis: {
            title: {
                text: '学生个人积分'
            },
            min: 0, // 这个用来控制y轴的开始刻度（最小刻度），另外还有一个表示最大刻度的max属性
            startOnTick: false ,// y轴坐标是否从某一刻度开始（这个设定与标题无关）
            labels:{
                style:{
                    'fontSize':13,
                    'font-family':'微软雅黑'
                }
            },
            gridLineColor: '#ccc',//横向网格线颜色
            gridLineWidth: 1,
            tickInterval:1,
            lineWidth:1,
            // tickPositions: [0, 25, 75, 500]
        },
        tooltip: {
            valueSuffix: '',
            title:''
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top'
        },
        series:arx,
        credits: {
          enabled:false
        },
        exporting: {
            enabled:false
        }
    });
}

//班级积分top10
function set_classscoretop5(ary,arx){
    $('#class_chart').highcharts({
        chart: {
            height:268,
            title:''
        },
        title: {
            text:''
        },
        colors: [
            '#2fd3c7',
            '#574cb6',
            '#00c6ff',
            '#ab802e',
            '#e57172',
            '#95c737',
            '#fca00b',
            '#ef7fdb',
            '#fce80b',
            '#68db62'
        ],
        xAxis: {
            categories: ary,
            labels:{
                style:{
                    'fontSize':12,
                    'font-family':'微软雅黑'
                }
            },
            gridLineColor: '#ccc',//横向网格线颜色
            gridLieWidth: 0 ,//横向网格线宽度
            title:'',
            gridLineWidth: 1,
            tickInterval:0,
            lineWidth:1
            
        },
        credits: {
            enabled: false
        },
        yAxis: {
            title: {
                text: "班级学习积分"
            },
            min: 0, // 这个用来控制y轴的开始刻度（最小刻度），另外还有一个表示最大刻度的max属性
            startOnTick: false ,// y轴坐标是否从某一刻度开始（这个设定与标题无关）
            labels:{
                style:{
                    'fontSize':13,
                    'font-family':'微软雅黑'
                }
            },
            gridLineColor: '#ccc',//横向网格线颜色
            gridLineWidth: 1,
            tickInterval:1,
            lineWidth:1,
           // tickPositions: [0, 25, 75, 500]
        },
        tooltip: {
            valueSuffix: '',
            title:''
        },
        credits: {
          enabled:false
        },
        exporting: {
            enabled:false
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top'
        },
        series:arx
    });
}

//课程学习次数Top5统计
function set_booktop5(ary,arx){
	$('#course_top5').highcharts({
		 chart: {
	       type: 'bar'
	   },
	   colors: [
	            '#2ED3C7'
	        ],
        credits: {
            enabled: false
        },
	   title: {
	       text: ''
	   },
	   xAxis: {
	       categories:ary//['Apples', 'Oranges', 'Pears', 'Grapes', 'Bananas']// ary
	   },
	   yAxis: {
	       min: 0,
	       title: {
	           text: ''
	       }
	       
	   },
	   legend: {
	       reversed: true
	   },
	   plotOptions: {
	       series: {
	           stacking: 'normal'
	       }
	   },
        credits: {
          enabled:false
        },
        exporting: {
            enabled:false
        },
	   series: [{
	       name: '课程学习次数',
	       data: arx//[5, 3, 4, 7, 2]//arx
	   } ]
	});
}

//试卷考试次数Top5统计
function set_examtop5(ary,arx){
	$('#exam_top5').highcharts({
		 chart: {
	       type: 'bar'
	   },
	   colors: [
	            '#2ED3C7'
	             
	        ],
	   title: {
	       text: ''
	   },
	   xAxis: {
	       categories:ary//['Apples', 'Oranges', 'Pears', 'Grapes', 'Bananas']// ary
	   },
	   yAxis: {
	       min: 0,
	       title: {
	           text: ''
	       }
	   },
        credits: {
          enabled:false
        },
        exporting: {
            enabled:false
        },
	   legend: {
	       reversed: true
	   },
	   plotOptions: {
	       series: {
	           stacking: 'normal'
	       }
	   },
	   series: [{
	       name: '试卷使用次数',
	       data: arx//[5, 3, 4, 7, 2]//arx
	   } ]
	});
}

$(document).ready(function(){
	$.ajax({
        type: 'get',
        url: site_url+'Teacount/studytaskpageajax',
        async: false,
        dataType: 'html',
        success: function (msg) {
            $('#studytaskpag').html(msg);

        }
    });

	$.ajax({
        type: 'get',
        url: site_url+'Teacount/ajaxpagExamTask',
        async: false,
        dataType: 'html',
        success: function (msg) {
            $('#setexamrTask').html(msg);

        }
    });

	$.ajax({
        type: 'post',
        url: site_url+'Teacount/ajaxpagbookstatistics',
        async: false,
        dataType: 'json',
        success: function (msg) {
			//console.log(msg);
            set_booktop5(msg.arry,msg.arrx);

        }
    });

	$.ajax({
        type: 'post',
        url: site_url+'Teacount/ajaxpagexamstatistics',
        async: false,
        dataType: 'json',
        success: function (msg) {
        	set_examtop5(msg.arry,msg.arrx);
        }
    });

});

$(function(){
	var urls = {
		  "class_names"		: site_url + "Teacount/class_by_teacher"
		, "class_score"		: site_url + "Teacount/class_course_sum_rose"
		, "class_topten"	: site_url + "Teacount/class_sum_topten"
	};
	
	//班级能力统计
	void function(){
		var firstPlay		= 0;
		var PAGE_NUM_MAX	= 3;
		var wrTemplate		= $('<tr><td></td></tr>');
		var wrBody			= $("tbody.wind-rose-body").first();
		var wrPageNumBox	= $(".class-score-page ul").first();
		var wrPrev			= $(".class-score-page .prev").first();
		var wrNext			= $(".class-score-page .next").first();
		var pageHalfNum		= Math.floor(PAGE_NUM_MAX/2);
		var se	= "";
		var clickedCode		= "";
		$("#class-score-search").first()
		.keydown(function(){
			$(this).css("background-color","#E6F0FB");
		})
		.keyup(function(event){
			$(this).css("background-color","#FFF");
			if(event.which==13){
				//send
				firstPlay = 1;
				$.getJSON(urls["class_names"]+"?se="+se,refreshClassNames);
			}
		})
		.change(function(){
			se = $(this).val();
		});

		$("#class_score_search_all").click(function(){
			if(clickedCode=="all"){
				return;
			}
			$("#ID_ClassPtSum").html("全部班级能力");
			se="";
			$("#class-score-search").val("");
			clickedCode="all";
			$.getJSON(urls["class_names"],refreshClassNames);
			refreshSumScore();
		});
		//切换班级
		function refreshSumScore(in_code){
            //console.log(in_code);
			var code = in_code || "";
			// alert(code);return;
			$('#containerrose').hide('speed',function(){
				$.ajax({
				  	type: 'post',
					url: urls['class_score'],
					data: {'code': code},
					async: false,
					dataType: 'html',
					success: function (msg) {
						//console.log(msg);
						//alert(msg)
			        	$('#freq').html(msg);
			        	set_containerrose('','');
			        	$('#containerrose').show('speed');
			        }
			    });
			});
		}
		
		function refreshClassNames(data){
			//向班级表格添加数据
            //console.log(data);
			se = data.Search;
			if(typeof(data.Result)!="undefined"){
				wrBody.html("");
				data.Result.forEach(function(ele){
					var tr = wrTemplate.clone();
						tr.find("td").text(ele.ClassName);
					void function(){
						var code = ele.ClassID;
						if(clickedCode==code){
							$("#class_score_search_all").removeClass("clicked");
							$("#ID_ClassPtSum").html("班级:"+ tr.find("td").text());
							tr.addClass("clicked");
						}
						tr.unbind().click(function(){
							$("#class_score_search_all").removeClass("clicked");
							$("#ID_ClassPtSum").html("班级:"+ tr.find("td").text());
							if(clickedCode!=code){
								wrBody.find("tr").removeClass("clicked");
								$(this).addClass("clicked");
								clickedCode=code;
								refreshSumScore(code);
							}
						});
					}();
					wrBody.append(tr);
				});
				if(firstPlay){
					wrBody.find("tr").first().click();
					firstPlay=0;
				}
				var start	= 1;
				var end		= PAGE_NUM_MAX;
				if( data.Page>pageHalfNum){
					start	= data.Page -pageHalfNum;
				}
				if( data.Page <= data.PageCount-pageHalfNum){
					var ts =(start+PAGE_NUM_MAX-1);
					if( ts < data.PageCount ){
						end = ts;
					} else {
						end = data.PageCount;
					}
				} else {
					start = data.PageCount-(PAGE_NUM_MAX-1);
					end = data.PageCount;
				}
				if( start<=0 ){
					start = 1;
				}
				if( end<0 ){
					end = 0;
				}
				wrPageNumBox.html("");
				for(var i=start;i<=end;i++){
					var li = $('<li >'+i+'</li>');
					if( i == data.Page ){
						li.addClass("clicked");
					}
					void function(){
						var pg = i;
						li.click(function(){
							$.getJSON(urls["class_names"]+"?p="+pg+"&s="+data.Size+"&se="+se,refreshClassNames);
						});
					}();
					wrPageNumBox.append(li);
				}
				void function(){
					var nextNum = data.PageCount;
					if( data.PageCount > 1 ){
						if( data.Page > 1 ){
							wrPrev.unbind('click').click(function(){
								$.getJSON(urls["class_names"]+"?p=1&s="+data.Size+"&se="+se,refreshClassNames);
							});
						}
						if( data.PageCount > data.Page ){
							wrNext.unbind('click').click(function(){
								$.getJSON(urls["class_names"]+"?p="+nextNum+"&s="+data.Size+"&se="+se,refreshClassNames);
							});
						}
					}
				}();
			}
		}
		$("#class_score_search_all").click();
	}();
	
	
	//班级积分Top10
	//学生积分Top10
	void function(){		
		var timeNum = {
			  month	:{min:3,max:24,default:12}
			, day	:{min:7,max:30,default:7}
		};
		
		function validation(inpt){
			if ( inpt.val() > 1*inpt.attr("max") ){
				num = 1*inpt.attr("max");
				inpt.val(num);
			} else if ( inpt.val() < 1*inpt.attr("min") ){
				num = 1*inpt.attr("min");
				inpt.val(num);
			} else {
				num = inpt.val();
			}
			return num;
		}
		
		function getClassSum(){
			var timeType	= $("#ID_SelTimeType").val();//2
			var inpt		= $('#ClassSelTime').find("input[type='number']");
			var num			= validation(inpt);//12
			// alert(timeType);
			// alert(num);
			$.ajax({
	        	type: 'post',
				data: {'su':2, 't':timeType, 'num':num},
	        	url: urls['class_topten'],
		        async: false,
		        dataType: 'json',
		        success: function(msg){
		        	try{
						//console.log(msg);
		            	set_classscoretop5(msg.categories,msg.series);
		            } catch(e){
			        	console.log("err:[script.js]getClassSum");
			        }
		        }
		    });
		}
		function getStudentSum(){
			var timeType = $("#ID_SelTimeType_STU").val();
			var inpt			= $('#StudentSelTime').find("input[type='number']");
			var num			= validation(inpt);
			//alert(timeType);
			//alert(num);
			$.ajax({
	        	type: 'post',
				data: {'su':1, 't':timeType, 'num':num},
	        	url: urls['class_topten'],
		        async: false,
		        dataType: 'json',
		        success: function (msg) {
		        	try{
		        		//console.log(msg);
			            set_studentscoretop5(msg.categories,msg.series);
			        } catch(e){
			        	console.log("err:[script.js]getStudentSum");
			        }
		        }
		    });
		}
		
		function switchPanel(sel,box){
			switch( 1*sel.val() ){
				case 1:
					var inpt = box.find("input[type='number']");
					inpt.attr("min",timeNum.day.min);
					inpt.attr("max",timeNum.day.max);
					inpt.val(timeNum.day.default);
					box.find(".lastTxt").text("天内");
					break;
				case 2:
				default:
					var inpt = box.find("input[type='number']");
					inpt.attr("min",timeNum.month.min);
					inpt.attr("max",timeNum.month.max);
					inpt.val(timeNum.month.default);
					box.find(".lastTxt").text("个月内");
			}
		}
		
		
		
		$("#ID_SelTimeType").change(function(){

			switchPanel($(this),$('#ClassSelTime'));
		});
		
		$("#BTN_ClassSum").click(function(){
			getClassSum();
		});
		
		
		$("#ID_SelTimeType_STU").change(function(){
			switchPanel($(this),$('#StudentSelTime'));
		});
		$("#BTN_StudentSum").click(function(){
			getStudentSum();
		});
		switchPanel($("#ID_SelTimeType"),$('#ClassSelTime'));
		switchPanel($("#ID_SelTimeType_STU"),$('#StudentSelTime'));
		getStudentSum();
		getClassSum();
	}();
	
	
});