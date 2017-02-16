 
                        <div>
						<h3>学习任务进度统计</h3>
						<table>
							<tr>
								<th>任务名</th>
								<th>已完成人数</th>
								<th>任务人数</th>
								<th>任务完成比率</th>
							</tr>
							<?php foreach ($taskinfo as $key => $value): ?>
							<tr>
								<td><?php echo $value['TaskName']?></td>
								<td><?php echo empty($value['undown'])?0:$value['undown'];?></td>
								<td><?php echo $value['allstu']?></td>
								<td><div class="progress"><div style="<?php echo $value['per']?>%"><span><?php echo $value['per']?>%</span></div></div></td>
							</tr>
							<?php endforeach ?>
							 
						</table>
					</div>
					<ul>
						<?php 
						
						//$astr = str_replace('<a ','<li ',$linksa);
						//$astr = str_replace('</a> ','</li> ',$astr);
						echo @str_replace('href',' class="ajax_fpage " hrefa',$linksa); ?>
						
						
						<!-- <li class="clicked">1</li>
						<li>2</li>
						<li>3</li> -->
					</ul>
					<p class="prev fa fa-angle-left fa-2x"></p>
					<p class="next fa fa-angle-right fa-2x"></p>
					<script>
        $(".ajax_fpage").click(function(e){
        var url = $(this).attr("hrefa");
        $.ajax({
            type: 'get',
            url: url,
            async: false,
            dataType: 'html',
            data: { "g5": 1 },
            success: function (msg) {
                $('#studytaskpag').html(msg);
            }
        });
    	function test() {
            
        } 
        event.preventDefault();
        });

        $("#ajaxsearch").click(function(e){
            var sword =  $("#searchword").val();
            $.ajax({
                type: 'get',
                url: site_url+'LeaderCtl/crewpageajax?searchcnt='+sword,
                async: false,
                dataType: 'html',
                data: { "g5": 1 },
                success: function (msg) {
                    //alert(msg);
                    
                    $('#loadajaxpage').html(msg);
                    isNameChecked();
                }
            }); 
            
            });
        $("#searchword").keypress(function(e){
            if(e.which == '13'){
                var sword =  $("#searchword").val();
                $.ajax({
                    type: 'get',
                    url: site_url+'LeaderCtl/crewpageajax?searchcnt='+sword,
                    async: false,
                    dataType: 'html',
                    data: { "g5": 1 },
                    success: function (msg) {
                        //alert(msg);

                        $('#loadajaxpage').html(msg);
                        isNameChecked();
                    }
                });
            }
        });
		function isNameChecked() {
			
			 var usernum = $('#userview span').length;
             var test = $('#userview span');
             var userlist ='';
             for (var i=0; i<usernum; i++)
             {
                 var oneuser = $('#userview span')[i];
                 var d=oneuser.getAttribute('name');
                 $('#user'+d).attr('checked','true');
             }
        }

        $(document).ready(function(){
             
        });

$("#checkall").bind('click',function(){
    var isCheck = $("#isShow").is(':checked');
    if(isCheck){
        
        $("#isShow").removeAttr("checked");

    }else{

         $("#isShow").prop("checked",true);
    }

    
    
    var isCheck = $("#isShow").is(':checked');

    if(isCheck){
        $(".isCheck").each(function() {
            var item = $(this).attr("name");
            $(this).prop("checked",true);
            userCheckClick(item);
        });
    }else{
        $(".isCheck").each(function() {
            var item = $(this).attr("name");
            $(this).removeAttr("checked");
            userCheckClick(item);
        });
    }


});