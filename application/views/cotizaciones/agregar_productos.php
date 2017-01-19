<?php if(!is_ajax()){ $this->load->view('layout/header'); } ?>
<h1><?php echo $titulo?></h1>
<form id="producto_filtro">
<table class="data_search">
<tbody>
	<tr>
		<td>
			<label>Categor&iacute;a:</label>
			<select name="categorias_id" id="categorias_id">
				<option value=""><?php echo $this->config->item('empty_select'); ?></option>
				<?php foreach($categorias as $k=>$v):?>
				<option value="<?php echo $k; ?>" <?php echo ($k == @$cond['categorias_id'])?'selected="selected"':''; ?>> <?php echo $v; ?></option>
				<?php endforeach;?>
			</select>
		</td>	
		<td>
			<label>Item #</label>
			<input id="item" name="item" value="<?php echo set_value('item',@$cond['item']); ?>" />
		</td> 
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<span class="boton fr"><input type="submit" value="Buscar" id="buscar_p" /></span>
			<span class="boton gris fr mr5"><input type="reset" value="Limpiar" id="clear" class="clear" /></span>
		</td>
		
	</tr>
</tbody>
</table>
</form>
<div id="lista_productos"></div>
<script type="text/javascript">
<!--
$(function(){
	
	$('#buscar_p').click(function(e){
		
		e.preventDefault();
		
		$.post(
			"<?php echo site_url('cotizaciones/lista_productos'); ?>", 
			$("#producto_filtro").serialize(),
			function(data) {
				$('#lista_productos').html(data);
			}
		);
	});
	$('#buscar_p').click();
});
//-->
</script>
<?php if(!is_ajax()){ $this->load->view('layout/footer'); } ?>