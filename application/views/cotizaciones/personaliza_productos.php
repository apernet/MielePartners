
<table class="data tr_over">
<tbody>
<?php foreach($r as $ro):?>
	<tr>		
		<td> 
			<label><span class="req">*</span><?php echo $tipos_accesorios[$ro->tipos_accesorios_id];?></label>
			<input name="accesorio[]" value=""  />
		</td>
	</tr>
</tbody>
</table>
<?php endforeach;?>	