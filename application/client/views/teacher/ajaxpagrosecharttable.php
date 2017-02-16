<tr nowrap bgcolor="#CCCCFF">
	<th colspan="9" class="hdr"> </th>
</tr>
<tr nowrap bgcolor="#CCCCFF">
	<th class="freq">Direction</th>
	<th class="freq">积分</th>

</tr>

<?php foreach ($sonnamelist as  $value): ?>
	<tr nowrap>
		<td class="dir"><?php echo $value['ArchitectureName']?></td>
		<td class="data"><?php echo $value['Score']?></td>

	</tr>
<?php endforeach ?>


<script>
	var len = '<?php echo count($sonnamelist); ?>';
	if(len == 1)
	{
		cou = -2;
	} else {
		cou = 0;
	}
	console.log(cou);
</script>