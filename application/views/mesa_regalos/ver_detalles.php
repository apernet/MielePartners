<?php if(!is_ajax()){ $this->load->view('layout/header'); } ?>
<h1><?php echo $titulo?></h1>
<div id="detalles_productos">
<table class="data tr_over">
<thead>
	<tr>
		<!--<td class="data_first">Id</td>-->
		<td class="data_first">Im&aacute;gen</td>
		<td>Item</td>
		<td>Marca</td>
		<td>Nombre</td>
		<td>Categor&iacute;a</td>
		<td>Dise&ntilde;ador</td>
		<td>Precio</td>
		<td>Costo</td>
	</tr>
</thead>
<tfoot>
	<tr>
		<td colspan="8">
			<div class="total fr">Se encontraron <?php echo count($productos); ?> resultados.</div>
		</td>
	</tr>
</tfoot>
<tbody>
<?php $i=1; foreach($productos as $p):?>
	<tr <?php echo ($i%2==0)?'class="altrow"':''?>>
		<!--<td align="center"><?php echo $p->productos_id;?></td>-->
		<td align="center"><img src="<?php echo site_url('/thumbs/timthumb.php?src=../files/productos/'.$p->productos_id.'/'.$p->foto_id.'.jpg&s=96&t='.time())?>" />&nbsp;</td>
		<td align="center"><?php echo $p->item;?></td>
		<td><?php echo !empty($ro->marca_nombre)?"{$ro->marca_nombre}":'Producto s&iacute;n marca';?></td>
		<td><?php echo $p->nombre;?></td>
		<td><?php echo $p->categoria_producto;?></td>
		<td><?php echo $p->designer_nombre;?></td>
		<td align="right"><?php echo moneda($p->precio);?></td>
		<td align="right"><?php echo moneda($p->costo);?></td>
	</tr>
<?php $i++; endforeach;?>
</tbody>
</table>
</div>
<?php if(!is_ajax()){ $this->load->view('layout/footer'); } ?>