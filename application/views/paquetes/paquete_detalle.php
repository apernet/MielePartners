<?php if(!is_ajax()){ $this->load->view('frontend/layout/header'); } ?>
<div class=" mt80 mb30">
	<div class="front_subtituloSeccion">
		<p class="">Paquetes</p>
	</div>
</div>
<div class="row front_detallePaquete backgroundGeneric noMargin">
	<div class="col-lg-3 imgPaquete">
		<?php $orden = $paquete->imagen_orden?'/paquete'.$paquete->imagen_orden:'';?>
		<?php $path=$this->config->item('cloudfiles')?$this->cloud_files->url_publica("files/paquetes/{$paquete->id}{$orden}.jpg"):"files/paquetes/{$paquete->id}.jpg";?>
		<img src="<?php echo site_url("/thumbs/timthumb.php?src={$path}&zc=1&q=100&s=300&t=".time());?>" class="img_thumb" />
	</div>
	<div class="col-lg-7 mt20">
		<p class="front-paquetes-titulo"><?php echo $paquete->nombre; ?></p>
		<p class="front-paquetes-precioDescuento">Descuento: <?php echo ver_num($paquete->descuento).' %'; ?></p>
		<!-- <p class="front-paquetes-precioDescuento"><del><?php echo moneda($subtotal); ?></del></p> 
		<p class="front-paquetes-precio"><?php echo moneda($subtotal-($subtotal * ($paquete->descuento/100))) ?></p>-->
		<p class="front_cuerpoTexto1_5">
		  <?php echo $paquete->descripcion; ?>
		</p>
	</div>
	<!-- <div class="col-lg-2 mt40">
		<a class="btn-front-primary btn" href="<?php echo site_url('frontends/cotizacion_agregar_paquete/'.$paquete->id) ?>"><i class="fa fa-shopping-cart"></i>   Agregar</a>
	</div> -->
</div>
<br>
<div class="row mt40 mb60 noMargin">
		<div id="container" class="js-masonry">
	   <?php foreach($categorias as $c): ?>
    	<div class="col-lg-3 item">
    		<a href="<?php echo site_url('frontends/categorias/'.$c['categorias_id']); ?>">
    			<div class="thumbnail front-producto">
    				<table>
						<tbody>
							<tr>
								<td class="img-producto">
									<?php $orden = $c['imagen_orden']?'_'.$c['imagen_orden']:'';?>
									<?php $path=$this->config->item('cloudfiles')?$this->cloud_files->url_publica("files/categorias/{$c['categorias_id']}{$orden}.jpg"):"files/categorias/{$c['categorias_id']}.jpg";?>
									<img src="<?php echo site_url("/thumbs/timthumb.php?src={$path}&zc=0&q=85&s=150&t=".time());?>" class="img_thumb" />
								</td>
							</tr>
							<tr>
								<td class="info-producto">
									<p class="producto"><?php echo $c['nombre']; ?></p>
    								<p class="modelo"><?php echo $c['descripcion']; ?></p>
				          		</td>
				          	</tr>
						</tbody>
					</table>
    			</div>
    		</a>
	    </div>
		<?php endforeach; ?>
		</div>
</div>
<?php if(!is_ajax()){ $this->load->view('frontend/layout/footer'); } ?>
