 
                        <div>
						<h3>考试任务进度统计</h3>
						<table>
							<tr>
								<th>任务名</th>
								<th>已完成人数</th>
								<th>任务人数</th>
								<th>任务完成比率</th>
							</tr>
							<?php foreach ($examtaskinfo as $key => $value): ?>
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
						echo @str_replace('href',' class="ajax_fpage_examTask " hrefa',$linksa); ?>
						
						
						<!-- <li class="clicked">1</li>
						<li>2</li>
						<li>3</li> 
						<p class="prev fa fa-angle-left fa-2x"></p>
					<p class="next fa fa-angle-right fa-2x"></p>
						-->
					</ul>
					
					<script>
        $(".ajax_fpage_examTask").click(function(e){
        var url = $(this).attr("hrefa");
        $.ajax({
            type: 'get',
            url: url,
            async: false,
            dataType: 'html', 
            success: function (msg) {
                $('#setexamrTask').html(msg);
            }
        });
    	 
        event.preventDefault();
        });
        </script>
 
 