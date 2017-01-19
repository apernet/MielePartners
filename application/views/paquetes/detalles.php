<?php if(!is_ajax()){ $this->load->view('frontend/layout/header'); } ?>
<div class="row mb20 noMargin">
	<div class="row mt10">
		<div class="front_subtituloSeccion">
			<p class=""><?php echo $titulo;?></p>
		</div>
	</div>
	<div class="row mt40 mb40">
		<div id="container" class="js-masonry">
			<?php $i=1; foreach($paquetes as $ro):?>
			<div class="col-lg-4 col-md-4 col-sm-6 item">
				<a href="<?php echo site_url('frontends/paquete_detalle/'.$ro->id) ?>">
					<div class="thumbnail front-producto" style="height: 370px;">
						<table>
							<tbody>
								<tr>
									<td class="img-producto">
										<?php $orden = $ro->imagen_orden?'/paquete'.$ro->imagen_orden:'';?>
										<?php $path=$this->config->item('cloudfiles')?$this->cloud_files->url_publica("files/paquetes/{$ro->id}{$orden}.jpg"):"files/paquetes/{$ro->id}.jpg";?>
										<img src="<?php echo site_url("/thumbs/timthumb.php?src={$path}&zc=1&q=100&s=300&t=".time());?>" class="img_thumb" />
									</td>
								</tr>
								<tr>
									<td class="info-producto">
										<p class="producto"><?php echo $ro->nombre; ?></p>
						          		<p class="precio lineProduct"><?php echo $ro->descripcion;?></p>
						          		<p class="producto">Descuento <?php echo ver_num($ro->descuento);?> %</p>
						          		<!-- <a class="btn btn-front-primary pull-right" href="<?php echo site_url('frontends/cotizacion_agregar_paquete/'.$ro->id) ?>">Agregar</a> -->
					          		</td>
					          	</tr>
							</tbody>
						</table>	
					</div>	
				</a>
			</div>
			<?php $i++; endforeach;?>
		</div>
	</div>
</div>
<?php if(!is_ajax()){ $this->load->view('frontend/layout/footer'); } ?>