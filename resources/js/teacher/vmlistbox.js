var quescontents = new Array();
var totalcheck = new Array();
var vmTranPack = null;
var disknum = 0;
var memorynum = 0;
var hostIdStr  = '';

$(function(){

    // vmdetail  popox
    $("#vmTemplatelist").find("#detailBtn").on({
        click:function(){
            //判断host_id要同一个
            $.each(quescontents,function(i,n){
                
                var arr = n.split('@@@@');
                hostIdStr += arr[9]+',';
                if (vmTranPack.itemIndex <= 7) {
                    var singleData = {
                          "memory_size_unit": 'MB'
                        , "user_pwd": ""
                        , "data_store_path": arr[3]
                        , "vns_pwd": ""
                        , "disk_offering": ""
                        , "vm_tpl_type": ""
                        , "vm_tpl_snp_name": ""
                        , "func_type": ""
                        , "disk_size_unit": "G"
                        , "cpu": '', "compute_offering": "Small Instance"
                        , "disk_size": arr[7]
                        , "docker_cmd": arr[10]
                        , "create_time": ""
                        , "memory_size": arr[8]
                        , "vm_tpl_uuid": arr[0]
                        , "clone_for_test": ""
                        , "vm_tpl_name": arr[1]
                        , "user_name": "ichunqiu"
                        , "id": ''
                        , "vm_type": arr[6]
                        , "os_type": arr[2]
                        , "os_version": arr[4]
                        , "vm_ip": "172.16.12.2"
                        , "vm_leak_info": arr[5]
                    };
                    vmTranPack.append(singleData);
                    vmTranPack.itemIndex++;
                }

                $.each(arr,function(k,m){
                    // console.log(m);
                })  
            });
            quescontents = new Array();
            downArr = new Array();
            totalcheck = new Array();
            vmTranPack = null; 
            $('#vmTemplatelist .total span').html(totalcheck.length);
            fnHide("#vmTemplatelist","fadeInDown","fadeOutUp");
        }
    });

    $(".fa-search").click(function(){
        sapGetData(site_url+'Train/get_vm_list', sapSuc, "pageContainer");
    });
    $('.question-exam').keydown(function(e){
        if(e.keyCode==13){
            sapGetData(site_url+'Train/get_vm_list', sapSuc, "pageContainer");
        }
    });
    //cpu
    $('.cpukur').click(function () {
        $('.cpukur').attr('class', 'cpukur');
        $(this).attr('class', 'cpukur filterCur');
        sapGetData(site_url+'Train/get_vm_list', sapSuc, "pageContainer");
    });
    //memery
    $('.memorykur').click(function () {
        $('.memorykur').attr('class', 'memorykur');
        $(this).attr('class', 'memorykur memorycur');
        sapGetData(siteurl+'TeaTrainCtl/vmlistajax', sapSuc, "pageContainer");
    });
    //disk
    $('.diskkur').click(function () {
        $('.diskkur').attr('class', 'diskkur');
        $(this).attr('class', 'diskkur diskcur');
        sapGetData(siteurl+'TeaTrainCtl/vmlistajax', sapSuc, "pageContainer");
    });
    //system
    $('.ostypekur').click(function () {
        $('.ostypekur').attr('class', 'ostypekur');
        $(this).attr('class', 'ostypekur filterCur');
        sapGetData(site_url+'Train/get_vm_list', sapSuc, "pageContainer");
    });
});

function popVmList(pack) {
    sapGetData(site_url+'Train/get_vm_list', sapSuc, "pageContainer");
    vmTranPack = pack;
    fnShow("#vmTemplatelist","fadeOutUp","fadeInDown");
}

function sapSuc(data) {
    var questxt = '';
    //console.log(data.length);
    if(data.length == 0){
        $('#vmTemplatelist .noNews').show();
        $('#vmtemplatelistTable').hide();
        $('#pageContainer').hide();
        return ;
    }
    $.each(data,function(i,v){
        questxt += '<tr class="firstNext">';
        /*questxt += '</tr>';*/
        
        questxt += '<td ><input class="quescode" type="checkbox" onclick=checkeds(this) name="quescode[]" uuid="'+v['vm_tpl_uuid']+'" hostId="'+v['host_id']+'" vpname="'+v['vm_tpl_name']+'" ostype="'+v['os_type_name']+'" path="'+v['data_store_path']+'" disk_size="'+v['disk_size']+'" memory_size="'+v['memory_size']+'" vm_tpl_type="'+v['vm_tpl_type']+'" description="'+v['description']+'" osver="'+v['os_ver']+'" docker_cmd="'+v['docker_cmd']+'"></td>';
        questxt += '<td title="'+v['vm_display_name']+'" >'+v['vm_display_name']+'</td>';
        questxt += '<td>'+v['user_name']+'</td>';
        questxt += '<td>'+v['create_time']+'</td>';
        questxt += '<td>'+v['os_type_name']+'</td>';
        questxt += '<td>'+v['os_ver']+'</td>';
        questxt += '<td>'+v['host_ip']+'</td>';
        questxt += '<td><a class="downMy" onclick=downMy(this) uuid="'+v['vm_tpl_uuid']+'">详细 <i class="fa fa-angle-double-right updown"></i></a></td>';
        // questxt += '<td code="'+v['vm_tpl_uuid']+'" num="2" onclick="buginfoClick(this)" class="buginfo">详细  <span class="buginfospan buginfospan'+v['vm_tpl_uuid']+'"><i class="fa updown fa-angle-double-right"></i></span></td>';
        questxt += '</tr>';
        questxt += '<tr class="bugvminfo outHide" id="buginfo'+v['vm_tpl_uuid']+'"><td  colspan=8>'+v['description']+'　IP：'+v['docker_cmd']+'</td></tr>';
        //questxt += '<tr class="bugvminfo outHide" id="buginfo'+v['vm_tpl_uuid']+'"<td colspan="8">'+v['description']+'　IP：'+v['docker_cmd']+'</td></tr>';
    });
    $('#vmTemplatelist .noNews').hide();
    $('#ques').html('');
    $('#ques').append(questxt);
    $('#vmtemplatelistTable').show();
    $('#pageContainer').show();

    $('#ques input[class=quescode]').each(function(){
        if(jQuery.inArray($(this).attr("uuid"),totalcheck) != -1){
            $(this).prop('checked',true)
        }
    })
    $('#ques .downMy').each(function(){
        if(jQuery.inArray($(this).attr("uuid"),downArr) != -1){
            $(this).children('i').removeClass("fa-angle-double-right").addClass("fa-angle-double-down");
            $(this).parent().parent().next().removeClass("outHide")
        }
    })
    openall();
}
function checkeds(ppo){
    var code = $(ppo).attr('uuid');
    var vpname = $(ppo).attr('vpname');
    var ostype = $(ppo).attr('ostype');
    var path = $(ppo).attr('path');
    var osver = $(ppo).attr('osver');
    var desc = $(ppo).attr('description');
    var vmtype = $(ppo).attr('vm_tpl_type');
    var dksize = $(ppo).attr('disk_size');
    var mysize = $(ppo).attr('memory_size');
    var hostId = $(ppo).attr('hostId');
    var docker_cmd = $(ppo).attr('docker_cmd');
    var tt = code+'@@@@'+vpname+'@@@@'+ostype+'@@@@'+path+'@@@@'+osver+'@@@@'+desc+'@@@@'+vmtype+'@@@@'+dksize+'@@@@'+mysize+'@@@@'+hostId+'@@@@'+docker_cmd;

    if ($(ppo).is(':checked')){
        //console.log(vmTranPack);//--debug
         //对于端口1做处理只允许添加一个模板
        if (vmTranPack.areaIndex == 0 && totalcheck.length == 1){
            $(ppo).prop('checked',false);
            alert('操作区只能添加一个虚拟机模板！');
            return true;
        }

        if(jQuery.inArray(code,totalcheck) == -1){
            totalcheck.push(code);
        }
        quescontents.push(tt);
        var obj = $('#ques input[class=quescode]');
        $('#vmTemplatelist .total span').html(totalcheck.length);
        $('#ques input[class=quescode]').each(function(){
            if ($(this).val() == code){
                $(this).prop('checked',true)
            }
        })
    }else{
        $('#vmTemplatelist .total span').html(totalcheck.length);
        $('#ques input[class=quescode]').each(function(){
            if ($(this).val() == code){
                $(this).prop('checked',false)
            }
        });
        quescontents.splice($.inArray(tt,quescontents),1)

        $.each(totalcheck,function(n,m){
            if(m == code){
                totalcheck.splice($.inArray(code,totalcheck),1);
            }
        })
        $('#vmTemplatelist .total span').html(totalcheck.length);
    }
}