/**
 * Created by qirupeng on 2016/8/23.
 */
$(function () {
    var urls = {
        "status_cpu"	: "/System/get_cpu_use"
        ,"login_log"	: "/System/loginlog"
        , "reboot_host"	: "/AdminCtl/reboot_host"
        , "node_info"	: "/System/node_info"
    };
    var TICK_STEP		= 5;
    var PAGE_NUM_MAX	= 3;

    function HighchartsBase(){
        var myself = {series:null};
        return {
            myself:myself,
            chart: {
                type: 'area',
                width: 200,
                height:80,
                events: {
                    load: function() {
                        myself.series = this.series[0];
                    }
                }
            },
            title: {
                text: ''
            },
            subtitle: {
                text: ''
            },
            colors: [
                '#b7d772',
                '#e9f3d8'
            ],
            exporting:{
                enabled:false
            },
            credits: {
                enabled: false
            },
            legend: {
                enabled: false,
                symbolHeight: 0
            },

            xAxis: {
                labels: {
                    formatter: function() {
                        return ""; // clean, unformatted number for year
                    }
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: ''
                },
                labels: {
                    formatter: function() {
                        return this.value ;
                    }
                }
            },
            tooltip: {
                pointFormat: '利用率 <b>{point.y:,.0f}%</b>'
            },
            plotOptions: {
                area: {
                    pointStart: 0,
                    marker: {
                        enabled: false,
                        symbol: 'circle',
                        radius: 3,
                        states: {
                            hover: {
                                enabled: true
                            }
                        }
                    }
                }
            }
        };

    }

    var restTask = [];

    function CpuCharts(in_ids){
        var cpuCharts	= $(".h-height-charts");
        var datas		= [];
        var cpuDatas	= [];
        var ids			= in_ids||"";

        var charts = [];

        cpuCharts.each(function(idx){
            var hdata = HighchartsBase();
            hdata.series = [
                {data: [0,0,0,0, 0,0,0,0, 0,0,0,0]}
            ];
            datas.push(hdata);
            $(this).highcharts(datas[idx]);
        });

        var jqXhr	= null;

        var jqRest	= null;
        function tick(){
            if( jqXhr != null ){
                jqXhr.abort();
            }
            jqXhr = $.ajax({
                url: site_url+urls["status_cpu"]
                , data: {ids:ids}
                , success: function(data){
                    timeid = 0;
                    jqXhr = null;
                    if( data != null ){
                        for( var idx in datas ){
                            datas[idx].myself.series.addPoint(data[idx],true,true);
                        }
                    }
                }
                , dataType: "json"
            });


            if(jqRest != null ){
                jqRest.abort();
            }
            jqRest = $.ajax({
                url : site_url+urls["node_info"]
                , dataType : "json"
                , success:function(data){
                    $(".node-state,.module-state").each(function(idx){
                        var box = $(this);
                        void function(){
                            var id = box.attr("nid");
                            var host = data.data.host;
                            //console.log(host);
                            host.forEach(function(el,idx){
                                //console.log(el);
                                if( el.id == id ){
                                    var txt = "";
                                    if( box.is("td.node-state") ){
                                        txt = (el.host_state==1?"已连接":"超时");
                                    } else {
                                        txt = (el.host_state==1?"开机":"超时");
                                    }
                                    box.text(txt);
                                    return false;
                                }
                            });
                        }();
                    });
                }
            });

        }
        var timeid = 0;
        this.run = function(){
            if(!timeid){
                clearInterval(timeid);
                timeid = setInterval(tick,TICK_STEP*1000);
            }
        }

    }


        if( cpuids.length > 0 ){
            var cpuCharts = new CpuCharts(cpuids.join(":"));
            cpuCharts.run();
        }


    var logTemplate = $(
        '<tr>\
            <td class="h-user">user</td>\
            <td class="h-status">status</td>\
            <td class="h-content">content</td>\
            <td class="h-date">YYYY-mm-dd HH:MM:SS</td>\
        </tr>'
    );
    var logBody	 = $(".event .h-etable tbody").first();
    var logPageNumBox = $(".newsPage ul").first();
    var logPrev	= $(".newsPage .prev").first();
    var logNext	= $(".newsPage .next").first();
    var pageHalfNum = Math.floor(PAGE_NUM_MAX/2);
    function refreshLog(data){
        data = data.data;

        if(typeof(data.Result)!="undefined"){
            logBody.html("");
            data.Result.forEach(function(ele){
                var tr = logTemplate.clone();
                if(ele.UserName == null){
                    ele.UserName = '未知';
                }
                tr.find(".h-user").text(ele.UserName);
                tr.find(".h-status").text(logtype[ele.LogTypeID]);
                tr.find(".h-content").text(ele.LogContent);
                tr.find(".h-content").attr("title",ele.LogContent);
                tr.find(".h-date").text(getLocalTime(ele.CreateTime));//截取 ele.datetime.substring(0,10)
                logBody.append(tr);
            });
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
            logPageNumBox.html("");
            for(var i=start;i<=end;i++){
                var li = $('<li >'+i+'</li>');
                if( i == data.Page ){
                    li.addClass("clicked");
                }
                void function(){
                    var pg = i;
                    li.click(function(){
                        $.getJSON(site_url+urls["login_log"]+"?p="+pg+"&s="+data.Size,refreshLog);
                    });
                }();
                logPageNumBox.append(li);
            }
            void function(){
                //var nextNum = data.PageCount;
                var nextNum = (data.Page==data.PageCount)?data.PageCount:data.Page+1;
                var prevNum = (data.Page==1)?1:data.Page-1;
                logPrev.unbind('click').click(function(){
                    $.getJSON(site_url+urls["login_log"]+"?p="+prevNum+"&s="+data.Size,refreshLog);
                });
                logNext.unbind('click').click(function(){
                    $.getJSON(site_url+urls["login_log"]+"?p="+nextNum+"&s="+data.Size,refreshLog);
                });
            }();
        }
    }

    //节点重启
    //重启
    $(".restart").click(function(){
        var id = $(this).attr('nid');
        $('#restart_id').val(id);
        fnShow("#restartBox","fadeOutUp","fadeInDown");
    });

    //重启点击确定事件
    $("#restartBtn").click(function(){
        var hostId = $('#restart_id').val();
        $.ajax({
            url : site_url+"/System/node_operate",
            type : 'post',
            data : {"host_id":hostId,"handle":'reboot'},
            dataType : 'json',
            success : function(msg){
                if(msg.code == '0000'){
                    fnHide("#restartBox","fadeInDown","fadeOutUp");
                    $('#okBox p.promptNews').html('该节点重启成功');
                    fnShow("#okBox","fadeOutUp","fadeInDown");
                    setTimeout(function(){
                        location.href = '';
                    },2000);
                }else{
                    fnHide("#restartBox","fadeInDown","fadeOutUp");
                    $('#okBox p.promptNews').html('该节点重启失败 原因：'+msg.msg);
                    fnShow("#okBox","fadeOutUp","fadeInDown");

                }

            }
        });

        $('#okBox p.promptNews').html('<span><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>正在重启</span>');
        fnShow("#okBox","fadeOutUp","fadeInDown");
        fnHide("#restartBox","fadeInDown","fadeOutUp");

    })


    $.getJSON(site_url+urls["login_log"],refreshLog);

});
function getLocalTime(nS) {
    return  new Date(parseInt(nS) * 1000).Format("yyyy-MM-dd hh:mm");
}
Date.prototype.Format = function (fmt) {
    var o = {
        "M+": this.getMonth() + 1, //月份
        "d+": this.getDate(), //日
        "h+": this.getHours(), //小时
        "m+": this.getMinutes(), //分
        "s+": this.getSeconds(), //秒
        "q+": Math.floor((this.getMonth() + 3) / 3), //季度
        "S": this.getMilliseconds() //毫秒
    };
    if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    for (var k in o)
        if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
    return fmt;
};
