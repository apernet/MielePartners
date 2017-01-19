<?php if($num_pages>0): ?>
<div class="both no_float">
	<div class="paginador text_right p10">
		<span class="left f13p" style="margin: 3px 0 0 0;"><?php echo $nombre_categoria; ?></span>
		<?php if($page>1):?>
		<a id="p_prev" href="#">&laquo; Anterior</a><span style="color: #CCC;">&nbsp;|&nbsp;</span>
		<?php endif; ?>
		<?php if($num_pages>1):?>
		<select id="p_pager">
		<?php for($i=0;$i<$num_pages;$i++):?>
			<option value="<?php echo $i+1; ?>" <?php echo ($i+1)==$page?'selected="selected"':''; ?>>P&aacute;gina <?php echo $i+1;?></option>
		<?php endfor;?>
		</select>
		<?php endif; ?>
		<?php if($page<$num_pages):?>
		<span style="color: #CCC;">&nbsp;|&nbsp;</span><a id="p_next" href="#">Siguiente &raquo;</a>
		<?php endif; ?>
		<div class="clearblock"></div>
	</div>
	<div class="wrapper"> 
	<?php foreach($productos as $p):?>
		<div class="grid3">
			<div class="img3">
				<a href="<?php echo site_url('frontends/productos/'.$p->id); ?>">
				<?php $file=FCPATH.'files/productos/'.$p->id.'/'.$p->img_id.'.jpg';?>
				<?php if(file_exists($file)):?>
					<img src="<?php echo site_url('/thumbs/timthumb.php?src=../files/productos/'.$p->id.'/'.$p->img_id.'.jpg&s=171&t='.time())?>" />&nbsp;
				<?php else:?>
					<img align="left" src="<?php echo site_url('/thumbs/timthumb.php?src=img/layout/imagen_no_disponible.jpg&s=171&t='.time());?>" class="img_thumb" />
				<?php endif;?>
				</a>
			</div>
			<div class="descripcion">
				<span class="letra_gris bold uppercase f10p pad-top-7px block"><?php echo $p->nombre;?></span>
				
			</div>
			
		</div>
	<?php endforeach;?>
	</div>
	<span class="clearblock"></span>
</div>

<script type="text/javascript">
<!--//
$(function(){
	$('#p_pager').change(function(){
		$('#grid_productos').load(
			'<?php echo site_url('frontends/productos_grid');?>/<?php echo $categorias_id; ?>/0/'+this.value
		);
	});
	$('#p_prev').click(function(e){
		e.preventDefault();
		$('#grid_productos').load(
			'<?php echo site_url('frontends/productos_grid');?>/<?php echo $categorias_id; ?>/0/'+<?php echo $page-1; ?>
		);
	});
	$('#p_next').click(function(e){
		e.preventDefault();
		$('#grid_productos').load(
			'<?php echo site_url('frontends/productos_grid');?>/<?php echo $categorias_id; ?>/0/'+<?php echo $page+1; ?>
		);
	});
});
//-->
</script>
<?php else: ?>
<div class="sin_resultados" style="display:none;">Lo sentimos, no se ha encontrado ning&uacute;n resultado.</div>
<?php endif; ?>