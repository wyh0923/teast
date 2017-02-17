/**
 * Created by Administrator on 2016/9/9.
 */

$(function () {
    sapGetData(site_url+'Teacount/get_classes', sapSuc, "pageContainer");
});

function sapSuc(data) {
    var questxt = '';
    $.each(data,function(i,v){
        var c = '';
        if(i == 0){ c='clicked' } else { c='' }
        questxt += '<tr  class="'+ c +'" code="'+v['ClassID']+'">';
        questxt += '<td>'+v['ClassName']+'</td>';
        questxt += '</tr>';
    });

    $('#allClass').html('');
    $('#allClass').append(questxt);

}