<?php if(count($r)<1): ?>
<div class="sin_resultados">No se ha encontrado ning&uacute;n registro.</div>
<?php else: ?>
<table class="data tr_over">
<thead>
	<tr>
		<td>#Item</td>
		<td>Imagen</td>
		<td>Modelo</td>
		<td>Categor&iacute;a</td>
		<td>Precio</td>
		<td>Moneda</td>
		<td>Acciones</td>
	</tr>
</thead>
<tfoot>
	<tr>
		<td colspan="4"><?php //echo $paginador; ?></td>
		<td colspan="3" class="p0">
			<!-- <div class="total fr">Se encontraron <?php //echo $total; ?> resultados.</div> -->
		</td>
		
	</tr>
</tfoot>
<tbody>
	<?php $i=1; foreach($r as $ro):?>
	<tr <?php echo ($i%2==0)?'class="altrow"':''?>>	
		<td align="center"><?php echo $ro->item; ?></td>
		<?php if(!file_exists(FCPATH.'files/productos/'.$ro->id.'/'.$ro->img_id.'.jpg')):?>
			<td align="center">NO DISPONIBLE</td>
		<?php else:?>		
			<td align="center"><a class='imagen_fancybox' href="<?php echo site_url('frontends/productos/'.$ro->id.'/'.$ro->img_id.'.jpg&zc=0&q=85&w=500&h=350'.'&t='.time());?>"><img src="<?php echo site_url('/thumbs/timthumb.php?src=files/productos/'.$ro->id.'/'.$ro->img_id.'.jpg&s=70&t='.time());?>" class="img_thumb" /></a></td>
		<?php endif;?>
		<td align="center"><?php echo $ro->modelo; ?></td>
		<td align="center"><?php echo $categorias[$ro->categorias_id]; ?></td>		
		<td nowrap="nowrap" align="right"><?php echo moneda($ro->precio); ?></td>
		<td nowrap="nowrap" align="right"><?php echo elemento('tipo_moneda',$ro->tipo_moneda_id); ?></td>
		<td nowrap="nowrap" align="center">
		 <a class="agregar_p" href="<?php echo site_url("cotizaciones/item/{$ro->id}"); ?>" class="">Agregar</a>
		</td>
	</tr>
	<?php $i++; endforeach;?>
</tbody>
</table>
<?php endif;?>
