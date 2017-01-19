<?php if(!is_ajax()){ $this->load->view('frontend/layout/header'); } ?>
<div class="col-lg-12 formulario-head">
	<div class="row">
		<div class="col-lg-11 col-sm-10 col-xs-9">
			<h4><?php echo $titulo;?> <?php if(@$numero_autorizacion):?></br></br>No. Autorizaci&oacute;n <?php echo @$numero_autorizacion; endif;?></h4>
		</div>
	</div>
</div>

<div class="table-responsive front_table mt20">
	<table class="table table-hover cotizacion">
		<thead>
			<tr>
				<td style="width: 8%;">Producto</td>
				<td style="width: 6%;">Modelo</td>
				<td style="width: 10%;">Nombre</td>
				<td style="width: 2%;">Cantidad</td>
				<td style="width: 10%;">Precio unitario (SIN IVA)</td>
				<?php if(INTERNO):?>
				<td style="width: 8%;">Descuento Cliente</td>
				<?php endif;?>
				<td style="width: 10%;">Importe</td>
			</tr>
		</thead>
		<tbody>
			<?php foreach($productos as $p):?>
			<tr>
				<td class="text-center">
					<?php $path=$this->config->item('cloudfiles')?$this->cloud_files->url_publica("files/productos/{$p['id']}/{$p['img_id']}.jpg"):"files/productos/{$p['id']}/{$p['img_id']}.jpg";?>
					<a class='imagen_fancybox' href="<?php echo site_url('/thumbs/timthumb.php?src='.$path.'&zc=0&q=85&s=500'.'&t='.time());?>">
						<img class="img-thumbnail front_imgTabla" src="<?php echo site_url('/thumbs/timthumb.php?src='.$path.'&s=400&t='.time());?>" />
					</a>
				</td>
				<td class="text-center"><?php echo $p['modelo'];?></td>

				<?php
				if(!empty($evento_estado) && $p['unidad_id']==2)
					$p['nombre'] = $p['nombre'] . ' este servicio se realizará en el estado de ' . $evento_estado;
				?>

				<td><?php echo $p['nombre'];?></td>
				<td class="text-center"><?php echo $p['cantidad'].' '.$p['unidad'];?></td>
				<td class="text-center"><?php echo moneda($p['precio']);?></td>
				<td class="text-center"><?php echo moneda($p['importe_cliente']);?></td>
			</tr>
			<?php if(isset($p['accesorios'])):
			foreach($p['accesorios'] as $acc):?>
			<tr>
				<td class="text-center">
					<?php $orden = $acc['imagen_orden']?'_'.$acc['imagen_orden']:'';?>
					<?php $path_acc=$this->config->item('cloudfiles')?$this->cloud_files->url_publica("files/accesorios/{$acc['accesorios_id']}{$orden}.jpg"):"files/accesorios/{$acc['accesorios_id']}.jpg";?>
					<a class='imagen_fancybox' href="<?php echo site_url('/thumbs/timthumb.php?src='.$path_acc.'&zc=0&q=85&s=500'.'&t='.time());?>">
						<img class="img-thumbnail front_imgTabla" src="<?php echo site_url('/thumbs/timthumb.php?src='.$path_acc.'&s=400&t='.time());?>" />
					</a>
				</td>
				<td class="text-center"><?php echo $acc['modelo'];?></td>
				<td><?php echo $acc['nombre'];?></td>
				<td class="text-center"><?php echo $acc['cantidad'].' '.$acc['unidad'];?></td>
				<td class="text-center"><?php echo moneda($acc['precio']);?></td>
				<td class="text-center"><?php echo moneda($acc['importe_cliente']);?></td>
			</tr>
			<?php endforeach;
			endif;?>
			<?php endforeach;?>
            <?php if(isset($accesorios_individuales)):
                foreach($accesorios_individuales as $acc):?>
                    <tr>
                        <td class="text-center">
                            <?php $orden = $acc->imagen_orden?'_'.$acc->imagen_orden:'';?>
                            <?php $path_acc=$this->config->item('cloudfiles')?$this->cloud_files->url_publica("files/accesorios/{$acc->accesorios_id}{$orden}.jpg"):"files/productos/{$acc->accesorios_id}.jpg";?>
                            <a class='imagen_fancybox' href="<?php echo site_url('/thumbs/timthumb.php?src='.$path_acc.'&zc=0&q=85&s=500'.'&t='.time());?>">
                                <img class="img-thumbnail front_imgTabla" src="<?php echo site_url('/thumbs/timthumb.php?src='.$path_acc.'&s=400&t='.time());?>" />
                            </a>
                        </td>
                        <td class="text-center"><?php echo $acc->modelo;?></td>
                        <td><?php echo $acc->nombre;?></td>
                        <td class="text-center"><?php echo $acc->cantidad.' '.$acc->unidad;?></td>
                        <td class="text-center"><?php echo moneda($acc->precio);?></td>
                        <td class="text-center"><?php echo moneda($acc->importe_cliente);?></td>
                    </tr>
                <?php endforeach;
            endif;?>
			<?php if(isset($promociones['promociones']) && !empty($promociones['promociones'])):
				foreach($promociones['promociones'] as $k=>$v):?>
					<tr>
						<td class="text-center" style="background-color: #f0f0f0">
							<div style="position: relative;">
							<a class='imagen_fancybox' href="<?php echo site_url('/thumbs/timthumb.php?src='.$v['path'].'&zc=0&q=85&s=500'.'&t='.time());?>">
								<img class="img-thumbnail front_imgTabla" src="<?php echo site_url('/thumbs/timthumb.php?src='.$v['path'].'&s=400&t='.time());?>" />
								<?php $path_regalo=$v['descuento_cliente']?site_url('img/promociones/promocion.png'):site_url('img/promociones/regalo.png');?>
								<img style="height:50px !important;width:75px !important;position: absolute;z-index: 1;left:0;margin:-8px;top:0;"  src="<?php echo site_url('/thumbs/timthumb.php?src='.$path_regalo.'&s=400&t='.time());?>" />
							</a>
							</div>
						</td>
						<td class="text-center"><?php echo $v['modelo'];?></td>
						<td><?php echo $v['nombre'];?></td>
						<td class="text-center"><?php echo $v['cantidad'];?></td>
						<td class="text-center"><?php echo moneda($v['precio']);?></td>
						<td class="text-center"><?php echo moneda($v['importe_cliente']);?></td>
					</tr>
				<?php endforeach;
			endif;?>
			<?php if(!@empty($producto_regalo)):?>
				<tr>
					<td class="text-center" style="background-color: #f0f0f0">
						<div style="position: relative;">
							<input type="hidden" id="producto_regalo_id" name="producto_regalo_id" value="<?php echo $producto_regalo->id;?>">
							<?php $path=$this->config->item('cloudfiles')?$this->cloud_files->url_publica("files/productos/{$producto_regalo->id}/{$producto_regalo->img_id}.jpg"):"files/productos/{$producto_regalo->id}/{$producto_regalo->img_id}.jpg";?>
							<a class='imagen_fancybox' href="<?php echo site_url('/thumbs/timthumb.php?src='.$path.'&zc=0&q=85&s=500'.'&t='.time());?>">
								<img class="img-thumbnail front_imgTabla" src="<?php echo site_url('/thumbs/timthumb.php?src='.$path.'&s=400&t='.time());?>" />
								<?php $path_regalo=site_url('img/promociones/regalo.png');?>
								<img style="height:50px !important;width:75px !important;position: absolute;z-index: 1;left:0;margin:-8px;top:0;"  src="<?php echo site_url('/thumbs/timthumb.php?src='.$path_regalo.'&s=400&t='.time());?>" />
							</a>
						</div>
					</td>
					<td class="text-center"><?php echo $producto_regalo->modelo;?></td>
					<td><?php echo $producto_regalo->nombre;?></td>
					<td class="text-center"><?php echo '1 Pieza(s)';?></td>
					<td class="text-center"><?php echo moneda(0);?></td>
					<td class="text-center"><?php echo moneda(0);?></td>
				</tr>
			<?php endif;?>
		</tbody>
		<tfoot>
			<tr>
    			<td class="tr front_datoImportante nowrap" colspan="5">Importe Total:</td>
    			<td id="importe_total" class="front_datoImportante nowrap bc_total"><?php echo moneda(@$r['importe_total']); ?></td>
    		</tr>
    		<?php if(@$r['descuento_cliente']):?>
			<tr>
				<td class="tr front_datoImportante nowrap" colspan="5">Descuento:</td>
				<td id="descuento_cliente" class="front_datoImportante nowrap bc_total">
					<?php $descuento_comercial = @$r['descuento_cliente']?$r['descuento_cliente']-@$r['descuento_cliente_cupon']-@$r['promocion_opcional_descuento']:0;
					$descuento_comercial = $descuento_comercial < 0 ? 0: $descuento_comercial;
					$descuento_comercial = @$r['descuento_cliente_cupon'] && $descuento_comercial>@$r['descuento_cliente_cupon']?$descuento_comercial-@$r['descuento_cliente_cupon']:$descuento_comercial;
					echo moneda($descuento_comercial);?>
				</td>
			</tr>
			<?php endif;?>
			<?php if(@$promociones && @$promociones['promocion_porcentaje'] && @$promociones['promocion_porcentaje_monto']):?>
				<tr>
					<td class="tr front_datoImportante nowrap" colspan="5">Descuento de <b><?php echo @ver_num($promociones['promocion_porcentaje']).'%';?></b> por promoci&oacute;n</td>
					<td id="promocion_porcentaje" class="front_datoImportante nowrap bc_total"><?php echo moneda(@$promociones['promocion_porcentaje_monto']);?></td>
				</tr>
			<?php endif;?>
			<?php if(@$promociones && @$promociones['promocion_fija']):?>
				<tr>
					<td class="tr front_datoImportante nowrap" colspan="5">Descuento fijo por promoci&oacute;n</td>
					<td id="promocion_fija" class="front_datoImportante nowrap bc_total"><?php echo moneda(@$promociones['promocion_fija']);?></td>
				</tr>
			<?php endif;?>
			<?php if(@$r['promocion_opcional_descuento']):?>
				<tr>
					<td class="tr front_datoImportante nowrap" colspan="5">Descuento por promoci&oacute;n</td>
					<td id="promocion_opcional_descuento" class="front_datoImportante nowrap bc_total"><?php echo moneda(@$r['promocion_opcional_descuento']);?></td>
				</tr>
			<?php endif;?>
			<?php if(@$r['descuento_cupon'] || @$r['descuento_cliente_cupon']):?>
				<tr>
					<td class="tr front_datoImportante nowrap" colspan="5">Descuento por Cup&oacute;n:</td>
					<td class="front_datoImportante nowrap bc_total">
						<?php echo moneda(@$r['descuento_cliente_cupon']);?>
					</td>
				</tr>
			<?php endif;?>
			<?php if(@$r['envio']):?>
				<tr>
					<td class="tr front_datoImportante nowrap" colspan="5">Cargo de recuperaci&oacute;n (incluye env&iacute;o e instalaci&oacute;n):</td>
					<td id="envio" class="front_datoImportante nowrap bc_total"><?php echo moneda(@$r['envio']); ?></td>
				</tr>
			<?php endif;?>
    		<tr>
    			<td class="tr front_datoImportante nowrap" colspan="5">Subtotal:</td>
    			<td id="subtotal_cliente" class="front_datoImportante nowrap bc_total"><?php echo moneda(@$r['subtotal_cliente']);?></td>
   			</tr>
    		<tr>
    			<td class="tr front_datoImportante nowrap" colspan="5">IVA ( 16 % ):</td>
    			<td id="iva_cliente" class="front_datoImportante nowrap"><?php echo moneda(@$r['iva_cliente']);?></td>
   			</tr>
    		<tr>
    			<td class="tr front_datoImportante nowrap" colspan="5">TOTAL:</td>
    			<td id="total_cliente" class="front_datoImportante nowrap"><?php echo moneda(@$r['total_cliente']);?></td>
   			</tr>
			<?php if(@$r['descuento_cupon'] && @$r['opcion_cupon_id']==2):?>
				<tr>
					<td class="tr front_datoImportante nowrap" colspan="5">Su pago a 12 meses sin intereses por Cup&oacute;n:</td>
					<td class="front_datoImportante nowrap"><?php echo moneda(@$r['mensualidad_cliente_cupon'])?></td>
				</tr>
			<?php endif;?>
            <?php if(isset($mensualidades) && $mensualidades!==false):?>
                <tr id="rw_pago_mensual">
                    <td class="tr front_datoImportante nowrap" colspan="5">Su pago a <?php echo $mensualidades['mensualidades'];?> meses sin intereses con <?php echo $mensualidades['mensualidades']==18?'Banamex':'American Express';?>:</td>
                    <td class="front_datoImportante nowrap"><?php echo moneda($mensualidades['monto_mensual']);?></td>
                </tr>
            <?php endif;?>
			<?php if(@$promociones && @$promociones['alianzas']):foreach($promociones['alianzas'] as $a):?>
				<?php if($a['codigo']):?>
					<tr>
						<td class="tr front_datoImportante_negro nowrap" colspan="<?php echo (isset($status_id) && in_array($status_id,array(2,3,4,5)) && !$venta_directa)?'11':'10'?>">*Regalo adquirido por ALIANZA <?php echo $a['nombre'];?>.
							Para reclamar su regalo, deberá presentar el siguiente CÓDIGO: <?php echo $a['codigo'];?> directamente con el proveedor.
						</td>
					</tr>
				<?php endif;?>
			<?php endforeach;endif;?>
            <tr>
                <td id="total_cliente" class="front_datoImportante_negro nowrap" colspan="6">*Para más información de este pedido contacte a <?php echo $this->config->item('nombre_venta_cliente_externo');?>: 01800 MIELE 00</td>
            </tr>
		</tfoot>
	</table>
</div>
<script type="text/javascript">
$(function(){
	<?php if(!$ecommerce_send):?>
	$.ajax({
		url: '<?php echo site_url('frontends/ecommerce_send') ?>',
		dataType: 'json',
		data: {cotizaciones_id : <?php echo $r['id']?>},
		type: 'post',
		success: function(data) {
			if(data)
			{
//				ga('require', 'ecommerce', '//https://www.google-analytics.com/plugins/ua/ecommerce.js');
				ga('require', 'ecommerce');
				ga('ecommerce:addTransaction', {
					'id': '<?php echo $numero_autorizacion;?>', 	// Transaction ID. Required.
					'affiliation': 'Miele Shop Mexico', 			// Affiliation or store name.
					'revenue': '<?php echo $r['total_cliente']?>',  // Grand Total.
					'shipping': '<?php echo @$r['envio'];?>',		// Shipping.
					'tax': '<?php echo @$r['iva_cliente'];?>',       // Tax.
					'currency': 'MXN'
				});
				<?php if(@$productos):foreach($productos as $id=>$p):?>
				ga('ecommerce:addItem', {
					'id': '<?php echo $numero_autorizacion;?>', // Transaction ID. Required.
					'name': '<?php echo $p['nombre'];?>',    	// Product name. Required.
					'sku': '<?php echo $p['item'];?>',          // SKU/code.
					'category': '<?php echo @$categorias[$p['categorias_id']];?>',// Category or variation.
					'price': '<?php echo $p['precio'];?>',      // Unit price.
					'quantity': '<?php echo $p['cantidad'];?>',  // Quantity.
					'currency': 'MXN'
				});
				<?php if(@$p['accesorios']):foreach($p['accesorios'] as $id_acc=>$acc):?>
				ga('ecommerce:addItem', {
					'id': '<?php echo $numero_autorizacion;?>', // Transaction ID. Required.
					'name': '<?php echo $acc['nombre'];?>',    	// Product name. Required.
					'sku': '<?php echo $acc['item'];?>',          // SKU/code.
					'category': '<?php echo @$tipos_accesorios[$acc['tipos_accesorios_id']];?>',// Category or variation.
					'price': '<?php echo $acc['precio'];?>',      // Unit price.
					'quantity': '<?php echo $acc['cantidad'];?>',  // Quantity.
					'currency': 'MXN'
				});
				<?php endforeach;endif;?>
				<?php endforeach;endif;?>
				<?php if(@$accesorios_individuales):foreach($accesorios_individuales as $id_ai=>$ai):?>
				ga('ecommerce:addItem', {
					'id': '<?php echo $numero_autorizacion;?>', // Transaction ID. Required.
					'name': '<?php echo $ai->nombre;?>',    	// Product name. Required.
					'sku': '<?php echo $ai->item;?>',          // SKU/code.
					'category': '<?php echo @$tipos_accesorios[$ai->tipos_accesorios_id];?>',// Category or variation.
					'price': '<?php echo $ai->precio;?>',      // Unit price.
					'quantity': '<?php echo $ai->cantidad;?>',  // Quantity.
					'currency': 'MXN'
				});
				<?php endforeach;endif;?>
				ga('ecommerce:send');
				ga('ecommerce:clear');
			}
		}
	});
	<?php endif;?>
});
</script>
<?php if(!is_ajax()){ $this->load->view('frontend/layout/footer'); } ?>