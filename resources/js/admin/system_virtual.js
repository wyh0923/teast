/**
 * Created by qirupeng on 2016/8/25.
 */
$(function(){
    //定时器
    /*setInterval(function(){
        //查看子节点是否存在
        setIntervalVm();
    },1000);*/
});
// 查看子节点是否存在
function setIntervalVm(){
    var trLength = $("#vmList").find("tr").length;
    if(trLength>0){
        //当前页的数据
        var currentPage = $('.clicked').html();
        var search = $('.search_input').val();
        $.ajax({
            url:site_url+'/System/node_list',
            type:'POST',
            async : true,
            data:{"per_page":currentPage,"Search":search},
            dataType : 'json',
            success:function(message){
                $('.reboot').hide();
                if(message.code == '0000'){
                    for (var node in message.data)
                    {
                        $('.reboot'+message.data[node].id).show();
                    }
                }
            }
        })
    }
}
$(function(){
    function TableVMC(){
        var urls = {
            "nop":null
            , "search"		: site_url+"/System/vm_search"
            , "start_vm"	: site_url+"/System/manage_vm/start"
            , "resume_vm"	: site_url+"/System/manage_vm/resume"
            , "reboot_vm"	: site_url+"/System/manage_vm/reboot"
            , "suspend_vm"	: site_url+"/System/manage_vm/suspend"
            , "shutdown_vm"	: site_url+"/System/manage_vm/shutdown"
            , "destroy_vm"	: site_url+"/System/manage_vm/destroy"

        };

        var PAGE_NUM_MAX = 5;

        var pageHalfNum = Math.floor(PAGE_NUM_MAX/2);
        var pagecMaxNum	= $( ".virJiLu .max-page-count" ).first();
        var pagecMaxRow	= $( ".virJiLu .max-row-count" ).first();
        var pagecPrev	= $( ".juBuPage .prev" ).first();
        var pagecNext	= $( ".juBuPage .next" ).first();
        var pagecNums	= $( ".juBuPage ul" ).first();

        var searchInput = $( ".search_input" ).first();
        var searchButton= $( ".fa-search").first();

        var tableBody	= $( ".virtuaTable tbody" ).first();

        var table_template_txt = tableBody.html();
        tableBody.html("");

        var currSearchText	= "";
        searchButton.click(function(){
            if(searchInput.val() != currSearchText ){
                currSearchText = searchInput.val();
                searchVM(1);
            }
        });

        $('.search_input').keydown(function(e){
            if(e.keyCode==13){
                if(searchInput.val() != currSearchText ){
                    currSearchText = searchInput.val();
                    searchVM(1);
                }
            }
        });

        function fillPage(in_data){
            var data = null;
            try{
                data = in_data;
            } catch(e){}

            if( data == null ){
                return;
            }
            pagecMaxNum.text(data.PageCount);
            pagecMaxRow.text(data.Count);
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
            pagecNums.html("");
            for(var i=start;i<=end;i++){
                var li = $('<li >'+i+'</li>');
                if( i == data.Page ){
                    li.addClass("clicked");
                }
                void function(){
                    var pg = i;
                    li.click(function(){
                        searchVM(pg);
                    });
                }();
                pagecNums.append(li);
            }
            void function(){
                pagecPrev.unbind('click').click(function(){
                    searchVM(1);
                });
                pagecNext.unbind('click').click(function(){
                    searchVM(data.PageCount);
                });
            }();

        }

        function fillData(data){
            var VM_NAME_LENGTH		= 24;
            var VM_NAME_CT_LENGTH	= VM_NAME_LENGTH+2;

            var result = null;
            var vmlist = [];
            try{
                result = data.Result;
                vmlist = result.VmInstance;
            } catch(e){}

            tableBody.html("");
            var HT = HelpTemplate;
            HT.Begin({
                templateText		: table_template_txt
                , templateData		: vmlist
                , outCall	: function(outText){//boxObj = null，占位不处理
                    $obj = $(outText);
                    var myStatus = parseInt($obj.attr("status"));


                    $obj.find(".vm-manage.start").show().unbind('click').click(function(){
                        magageVM("start_vm",$obj.attr("hid"),$(this).parent().parent().attr("uuid"),this);
                    });
                    $obj.find(".vm-manage.resume").show().unbind('click').click(function(){
                        magageVM("resume_vm",$obj.attr("hid"),$(this).parent().parent().attr("uuid"));
                    });
                    $obj.find(".vm-manage.reboot").show().unbind('click').click(function(){
                        magageVM("reboot_vm",$obj.attr("hid"),$(this).parent().parent().attr("uuid"));
                    });
                    $obj.find(".vm-manage.suspend").show().unbind('click').click(function(){
                        magageVM("suspend_vm",$obj.attr("hid"),$(this).parent().parent().attr("uuid"));
                    });
                    $obj.find(".vm-manage.shutdown").show().unbind('click').click(function(){
                        magageVM("shutdown_vm",$obj.attr("hid"),$(this).parent().parent().attr("uuid"));
                    });

                    tableBody.append($obj);
                }
            }).dataEach(function(ele,idx){
                var datetime = ele.create_time.split(" ");
                ele["create-date"] = datetime[0];
                ele["create-time"] = datetime[1];
                ele["vm-name-title"] = ele.vm_name;
                ele['scene_name_title'] = ele.scene_name;
                if( ele.vm_name.length > VM_NAME_CT_LENGTH ){
                    //ele["vm-name-title"] = ele.vm_name;
                    ele.vm_name = ele.vm_name.substr(0,VM_NAME_LENGTH) + "...";
                } else {
                    ele.vm_name = ele.vm_name.substr(0,VM_NAME_LENGTH);
                }
                if(ele.vm_ins_status == 1){
                    ele.vm_ins_status_C = '运行';
                }else if(ele.vm_ins_status == 2){
                    ele.vm_ins_status_C = '关机';
                }else if(ele.vm_ins_status == 3){
                    ele.vm_ins_status_C = '暂停';
                }else if(ele.vm_ins_status == 4){
                    ele.vm_ins_status_C = '恢复';
                }
                HT.ParseFor(ele).Out();
            }).End();
        }

        function magageVM(act,hid,uuid,btn){

            $('#HintBox p.promptNews').attr('url',urls[act]);
            $('#HintBox p.promptNews').attr('hid',hid);
            $('#HintBox p.promptNews').attr('uuid',uuid);
            var hint = '';
            if( act == "start_vm" ){
                hint = '启动';
            }else if( act == "resume_vm" ){
                hint = '恢复';
            }else if( act == "reboot_vm" ){
                hint = '重启';
            }else if( act == "suspend_vm" ){
                hint = '暂停';
            }else if( act == "shutdown_vm" ){
                hint = '关机';
            }
            $('#HintBox p.promptNews').attr('hint',hint);
            if( act == "start_vm" ){
                $('#HintBox p.promptNews').html('确定要开启该虚拟机吗？');
            }else{
                $('#HintBox p.promptNews').html('警告:当前操作属于危险操作，请再次确认是否真的要'+hint+'？');
            }

            fnShow("#HintBox","fadeOutUp","fadeInDown");
        }

        $("#hintBtn").click(function(){
            var url = $('#HintBox p.promptNews').attr('url');
            var hid = $('#HintBox p.promptNews').attr('hid');
            var uuid = $('#HintBox p.promptNews').attr('uuid');
            var hint = $('#HintBox p.promptNews').attr('hint');
            $.ajax( {
                url		: url
                , data		: {hid:hid,uuid:uuid}
                , dataType	: "json"
                , success	: function(data){
                    console.log(data);
                    fnHide("#HintBox","fadeInDown","fadeOutUp");
                    if( data.code != '0000' ){
                        $('#okBox p.promptNews').html(hint+'失败');
                        fnShow("#okBox","fadeOutUp","fadeInDown");
                        setTimeout(function(){
                            location.href = '';
                        },2000);
                    } else {
                        fnHide("#HintBox","fadeInDown","fadeOutUp");
                        $('#okBox p.promptNews').html(hint+'成功');
                        fnShow("#okBox","fadeOutUp","fadeInDown");
                        setTimeout(function(){
                            fnHide("#okBox","fadeInDown","fadeOutUp");
                            //当前页的数据
                            var currentPage = $('.clicked').html();
                            searchVM(currentPage);
                        },2000);
                    }
                }
            });
        });

        function searchVM(p){

            var searchData = {
                "p"	: p
                , "s"	: 8
                , "vm"	: currSearchText
            };
            $.ajax( {
                url		: urls["search"]
                , data		: searchData
                , dataType	: "json"
                , success	: function(data){
                    fillData(data);
                    fillPage(data);
                }
            });
        }

        this.init = function(){
            searchVM(1);
            setInterval(function(){
                //实时更新虚拟机状态
                var currentPage = $('.clicked').html();
                searchVM(currentPage);
            },1000);
        }

    }

    var tableVMC = new TableVMC();
    tableVMC.init();


});
