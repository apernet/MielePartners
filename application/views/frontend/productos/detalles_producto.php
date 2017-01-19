<?php $this->load->view('frontend/layout/header');?>
<div class="minHeight" xmlns="http://www.w3.org/1999/html">
	<div class="row mt80 mb20 noMargin">
		<div class="front_subtituloSeccion">
			<ol class="breadcrumb">
			  <?php foreach($categorias as $c): ?>
			      <li>
			          <a href="<?php echo site_url('frontends/categorias/'.$c->id) ?>">
			              <?php echo $c->nombre; ?>
			          </a>
			      </li>
			  <?php endforeach; ?>
			</ol>
		</div>
	</div>

	<form method="post" id="form" action="<?php site_url('frontends/cotizacion_agregar_producto/'.$r->id); ?>">
		<?php $path_regalo=site_url('img/promociones/promocion.png');?>
		<div class="row backgroundGeneric noMargin"  style="min-height: 550px;">
			<div class="col-sm-4 col-xs-4">
				<?php $first=TRUE;?>
				<div class="row">
					<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
						<ol class="carousel-indicators">
						<?php $i = 0; foreach ($fotografias as $fotos):?>
							<li data-target="#carousel-example-generic" data-slide-to="<?php echo $i ?>" class="<?php echo $i==0? 'active' : '' ?>"></li>
						<?php $i++; endforeach; ?>
						</ol>
						<div class="carousel-inner">
							<?php foreach ($fotografias as $fotos):?>
                                <?php if($first==TRUE && $fotos['tipos_id']==1):?>
                                <div id="imagen_producto_<?php echo $fotos['id']; ?>" class="imagen_producto img item active">
                                    <?php if($this->config->item('cloudfiles')):
                                        $path=$this->cloud_files->url_publica("files/productos/{$r->id}/{$fotos['id']}.jpg"); ?>
                                        <img src="<?php echo  site_url('/thumbs/timthumb.php?src='.$path.'&mw=400&mh=500&t='.time());?>" class="img img-thumbnail">
                                    <?php else: ?>
                                        <img class="img img-thumbnail" src="<?php echo site_url("/thumbs/timthumb.php?src=files/productos/{$r->id}/{$fotos['id']}.jpg&mw=400&mh=500&t=".time());?>" />
                                    <?php endif;?>
                                </div>
                                <?php $first=FALSE;?>
                                    <?php elseif($fotos['tipos_id']==3):?>
                                    <div id="imagen_producto_<?php echo $fotos['id']; ?>" class="img-thumbnail front_imgTabla item <?php echo ($first==FALSE)?'':'active'?>">
                                        <?php if($this->config->item('cloudfiles')):
                                            $path=$this->cloud_files->url_publica("files/productos/{$r->id}/{$fotos['id']}.jpg"); ?>
                                            <img src="<?php echo  site_url('/thumbs/timthumb.php?src='.$path.'&mw=400&mh=500&t='.time());?>" class="img img-thumbnail">
                                        <?php else: ?>
                                            <img class="img img-thumbnail" src="<?php echo site_url("/thumbs/timthumb.php?src=files/productos/{$r->id}/{$fotos['id']}.jpg&mw=400&mh=500&t=".time());?>" />
                                        <?php endif;?>
                                    </div>
                                    <?php $first=FALSE;?>
                                    <?php endif;?>
							<?php endforeach; ?>
						</div>
						<a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
							<i class="fa fa-angle-left fa-2x"></i>
						</a>
						<a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
							<i class="fa fa-angle-right fa-2x "></i>
						</a>
						<?php if(@$r->promocion):?>
							<img style="height:100px !important;width:150px !important;position: absolute;z-index: 1;left:0;margin:-0.5px;top:0;"  src="<?php echo site_url('/thumbs/timthumb.php?src='.$path_regalo.'&s=400&t='.time());?>" />
						<?php endif;?>
					</div>
				</div>
			</div>
			<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
				<div class="front_subtituloSeccion">
					<p class=""><?php echo $r->nombre;?></p>
				</div>
				<div class="row">
					<div class="col-lg-6">
						<p class="mt20" align="justify">
							<?php echo nl2br($r->descripcion);?>
						</p>
						<?php if($r->unidad_id==2): ?>
							<p>
								<span class="req">* El precio que se muestra es únicamente para capacitaciones en la Ciudad de México (CDMX, A.M. y Edo. Mex.).
								El precio de las capacitaciones está sujeto a cambio dependiendo del estado donde se realiza el servicio.</span>
							</p>
						<?php endif; ?>
					</div>
					<div class="col-lg-6">
						<div class="table-responsive front_table mt20">
						<table class="table">
								<thead class="thead">
									<tr>
										<td width="70px">SKU</td>
										<td width="190px">Modelo</td>
										<td width="130px" nowrap>Precio ( Con IVA )</td>
									</tr>
								</thead>
								<tbody id="table_producto">
									<tr height="35px">
										<td class="tc"><?php echo $r->item; ?></td>
										<td class="tc"><div class="w100 auto"><?php echo $r->modelo;?></div></td>
										<td class="tr precio"><strong><?php echo precio_con_iva($r->precio);?></strong></td>
									</tr>
								</tbody>
						</table>
						</div>
					</div>
				</div>

				<div class="row mb10">
					<div style="float: right; width: 200px;margin-right: 10px;" id="<?php echo $r->id?>" class="rate_widget" data-table="productos">
						<div class="ratings_stars" data-value="1"></div>
						<div class="ratings_stars" data-value="2"></div>
						<div class="ratings_stars" data-value="3"></div>
						<div class="ratings_stars" data-value="4"></div>
						<div class="ratings_stars" data-value="5"></div>
						<a href="<?php echo site_url("/calificaciones/comentarios/{$r->id}/productos");?>" class="bc_comentarios">
							<span style="float: right;" class="<?php echo $r->id;?>"></span>
						</a>
					</div>
				</div>

				<div class="row mb10">
					<div class="col-lg-12">
						<button type="submit" class="btn btn-front-primary pull-right" name="agregar" value="Agregar" /><i class="fa fa-shopping-cart"></i> Agregar</button>
						<?php if($this->config->item('cloudfiles')):
								if(!empty($r->guia_mecanica_extension)):
									$orden = $r->guia_mecanica_orden?'_'.$r->guia_mecanica_orden:'';
									$path=$this->cloud_files->url_publica("files/productos/{$r->id}/guia_mecanica{$orden}.{$r->guia_mecanica_extension}"); ?>
									<a class="btn btn-front-default pull-right" target="_blank" style="margin-right:12px;" href="<?php echo $path; ?>">Gu&iacute;a Mec&aacute;nica</a>
								<?php endif;?>
								<?php if(!empty($r->manual_extension)):
									$orden = $r->manual_orden?'_'.$r->manual_orden:'';
									$path=$this->cloud_files->url_publica("files/productos/{$r->id}/manual{$orden}.{$r->manual_extension}"); ?>
									<a class="btn btn-front-default pull-right" target="_blank" style="margin-right:12px;" href="<?php echo $path; ?>">Manual</a>
								<?php endif;?>
								<?php if(!empty($r->autocad_extension)):
									$orden = $r->autocad_orden?'_'.$r->autocad_orden:'';
									$path=$this->cloud_files->url_publica("files/productos/{$r->id}/autocad{$orden}.{$r->autocad_extension}"); ?>
									<a class="btn btn-front-default pull-right" target="_blank" style="margin-right:12px;" href="<?php echo $path; ?>">Autocad</a>
								<?php endif;?>
						<?php else: ?>
							<?php if(!empty($r->guia_mecanica_extension)):?>
								<a class="btn btn-front-default pull-right" target="_blank" style="margin-right:12px;" href="<?php echo site_url("files/productos/{$r->id}/guia_mecanica.{$r->guia_mecanica_extension}"); ?>">Gu&iacute;a Mec&aacute;nica</a>
							<?php endif;?>
							<?php if(!empty($r->manual_extension)):?>
								<a class="btn btn-front-default pull-right" target="_blank" style="margin-right:12px;" href="<?php echo site_url("files/productos/{$r->id}/manual.{$r->manual_extension}"); ?>">Manual</a>
							<?php endif;?>
							<?php if(!empty($r->autocad_extension)):?>
								<a class="btn btn-front-default pull-right" target="_blank" style="margin-right:12px;" href="<?php echo site_url("files/productos/{$r->id}/autocad.{$r->autocad_extension}"); ?>">Autocad</a>
							<?php endif;?>
						<?php endif;?>
						<?php if(@$r->promocion):?>
							<a class="btn btn-front-primary pull-right bc_fancybox" style="margin-right:12px;" href="<?php echo site_url('frontends/promocion_view/'.$r->promocion.'/2/'.$r->categorias_id.'/'.$r->id); ?>" id="ver_promocion"><?php echo 'Ver promoción';?>&nbsp;<i class="fa fa-gift"></i></a>
						<?php endif;?>
						<?php if($r->unidad_id==2): // Unidad de medida es horas = evento?>
							<div class="col-xs-4 pull-right mt_15">
                                <label><span class="req">*</span>Estado donde se impartir&aacute; el servicio:</label>
                                <select class="form-control" name="evento_estado" id="evento_estado">
                                    <option value=""><?php echo $this->config->item('empty_select');?></option>
                                    <?php foreach($estados as $e):?>
                                        <option value="<?php echo $e; ?>" <?php echo set_select('evento_estado', $e); ?>><?php echo $e; ?></option>
                                    <?php endforeach;?>
                                </select>
                                <?php echo form_error('evento_estado');?>
							</div>
						<?php endif;?>
						<div class="clear"></div>
					</div>
				</div>
			</div>
		</div>
	</form>

	<div class="row mb60 noMargin">
		<div class="front_subtituloSeccion mt40">
			<p class="">Productos Similares</p>
		</div>
		<div class="row mt20">
			<div id="container" class="js-masonry">
				<?php foreach($productos_similares as $p):?>
					<?php if($p['id']!=$r->id):?>
						<div class="col-lg-4 col-md-4 col-sm-6 item ">
							<a href="<?php echo site_url('frontends/productos/'.$p['id']); ?>" class="link_detalle_prod">
								<div class="front-cat-producto heightAcc thumbnail <?php echo @$r->promocion?'promocion_producto_similar':''?>" style="position: relative;">
									<table>
										<tr>
											<td class="img-producto">
												<?php if($this->config->item('cloudfiles')): 
													$path=$this->cloud_files->url_publica("files/productos/{$p['id']}/{$p['foto_id']}.jpg"); ?>
								                	<img src="<?php echo  site_url('/thumbs/timthumb.php?src='.$path.'&mw=250&mh=250&t='.time());?>"/>
								            	<?php else: ?>
													<img src="<?php echo site_url("/thumbs/timthumb.php?src=files/productos/{$p['id']}/{$p['foto_id']}.jpg&mw=250&mh=250&t=".time());?>" />
							    				<?php endif;?>
												<?php if(@$p['promocion']):?>
													<img class="banda_promo" style="height:100px !important;width:150px !important;position: absolute;z-index: 1;left:0;margin:-0.5px;top:0;"  src="<?php echo site_url('/thumbs/timthumb.php?src='.$path_regalo.'&s=400&t='.time());?>" />
												<?php endif;?>
											</td>
										</tr>
										<tr>
											<td class="info-producto">
												<div style="float: right;  width: 100px; margin-right: 15px;" id="<?php echo $p['id']?>" class="rate_widget" data-table="productos">
													<div class="ratings_stars" data-value="1"></div>
													<div class="ratings_stars" data-value="2"></div>
													<div class="ratings_stars" data-value="3"></div>
													<div class="ratings_stars" data-value="4"></div>
													<div class="ratings_stars" data-value="5"></div>

													<a href="<?php echo site_url("/calificaciones/comentarios/{$p['id']}/productos");?>" class="bc_comentarios">
														<span style="float: right; margin-top: -6px;" class="<?php echo $p['id'];?>"></span>
													</a>
												</div>

												<p class="producto" title="<?php echo $p['nombre'];?>"><b><?php echo strlen($p['nombre'])>28?substr($p['nombre'],0,25).'...':$p['nombre']; ?></b></p>
												<p class="modelo">Modelo <?php echo $p['modelo'];?></p>
                                                <p class="modelo">Precio <?php echo moneda($p['precio']);?></p>
											</td>
										</tr>
									</table>
									<?php if(@$p['promocion']):?>
										<div class="tr_foot_promocion  tc">
											<div class="">
												<div style="float: right; width: 100px; margin-right: 15px;" id="<?php echo $p['id']?>" class="rate_widget" data-table="productos">
													<div class="ratings_stars" data-value="1"></div>
													<div class="ratings_stars" data-value="2"></div>
													<div class="ratings_stars" data-value="3"></div>
													<div class="ratings_stars" data-value="4"></div>
													<div class="ratings_stars" data-value="5"></div>
													&nbsp;
													<a href="<?php echo site_url("/calificaciones/comentarios/{$p['id']}/productos");?>" class="bc_comentarios">
														<span style="float: right; margin-top: -6px;" class="<?php echo $p['id'];?>"></span>
													</a>
												</div>
											</div>
											<div class="">
                                            <p class="modelo">Modelo <?php echo $p['modelo'];?></p>
                                            <p class="modelo">Precio <?php echo moneda($p['precio']);?></p>
											<a class="btn btn-front-primary pull-center bc_fancybox" href="<?php echo site_url('frontends/promocion_view/'.$p['promocion'].'/2/'.$p['categorias_id'].'/'.$p['id']); ?>" id="ver_promocion"><?php echo 'Ver promoción';?>&nbsp;<i class="fa fa-gift"></i></a>
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											<a class="btn btn-front-primary pull-center" href="<?php echo site_url('frontends/productos/'.$p['id']); ?>" id="ver_promocion"><?php echo 'Ver producto';?>&nbsp;<i class="fa fa-file-text"></i></a>
											</div>	
										</div>
                                        <div class="tr_top_promocion tc">
                                            <p class="producto"  style="color:white;" title="<?php echo $p['nombre'];?>"><b><?php echo strlen($p['nombre'])>28?substr($p['nombre'],0,25).'...':$p['nombre']; ?></b></p>
                                        </div>
									<?php endif;?>
								</div>
							</a>
						</div>
					<?php endif;?>
				<?php endforeach;?>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
<!--//
$(function(){
	var producto_id = <?php echo $r->id; ?>;

	$(document).ready(function() {
		if($('#evento_estado')!='')
		{
			$.post("<?php echo site_url('frontends/evento_precio_base'); ?>/"+producto_id+'/'+$('#evento_estado').val(),
				function(data){
					$('.precio').html('<strong>'+data.precio+'</strong>');
				},'json');
		}else
			$('.precio').html('<strong>$ 0.00</strong>');
	});

	$('#evento_estado').on('change',function(){
		if($('#evento_estado')!='')
		{
			$.post("<?php echo site_url('frontends/evento_precio_base'); ?>/"+producto_id+'/'+$('#evento_estado').val(),
			function(data){
				$('.precio').html('<strong>'+data.precio+'</strong>');
			},'json');
		}else
			$('.precio').html('<strong>$ 0.00</strong>');
	});

	$('#print_page').click(function(event){ event.preventDefault(); });

	$('.grid_img').click(function(e){
		e.preventDefault();
		$('.imagen_producto').hide();
		var aux=this.id.split('_');
		$('#imagen_producto_'+aux[1]).fadeIn();
	});

	$('div.promocion_producto_similar').mouseenter(function(){
		//href = $(this).find('a.link_detalle_prod').attr('href');
		//$(this).find('a.link_detalle_prod').attr('href','#');
		$(this).find('a.link_detalle_prod').click(function(e){e.preventDefault();});

        $(this).find('.banda_promo, .info-producto .modelo, .info-producto .producto').addClass('trasintion_5');
        $(this).find('.banda_promo, .info-producto .modelo, .info-producto .producto').addClass('fade');
        $(this).find('.tr_foot_promocion').addClass('foot_promocion');
        $(this).find('.tr_top_promocion').addClass('top_promocion');
	});

	$('div.promocion_producto_similar').mouseleave(function(){
		$(this).removeClass('heightAcc-large');
		//$(this).find('a.link_detalle_prod').attr('href',href);

        $(this).find('.banda_promo, .info-producto .modelo, .info-producto .producto').removeClass('fade');
        $(this).find('.tr_foot_promocion').removeClass('foot_promocion');
        $(this).find('.tr_top_promocion').removeClass('top_promocion');

	});


	//Estrellas de la muerte....

	// trae la calificacion en el ready
	$('.rate_widget').each(function(i) {
		var widget = this;
		var out_data = {
			id : $(widget).attr('id'),
			tabla:$(widget).attr('data-table')
		};

		$.post(
			"<?php echo site_url('calificaciones/get_calificacion'); ?>",
			out_data,
			function(data) {;
				$(widget).data( 'fsr', data);
				set_votes(widget);
			},
			'json'
		);
	});

	function set_votes(widget) {

		var avg = $(widget).data('fsr')?$(widget).data('fsr').promedio:null;
		var cmt = $(widget).data('fsr').comentarios?$(widget).data('fsr').comentarios:'0';

		if(avg){
			$(widget).find("[data-value='" + avg + "']").prevAll().andSelf().addClass('ratings_vote');
			$(widget).find("[data-value='" + avg + "']").nextAll().removeClass('ratings_vote');
		}
		$('span.'+$(widget).attr('id')).text(cmt+" comentarios");
	}

	$('.bc_comentarios').fancybox({
		type: 'ajax'
	});

});
//-->
</script>
<?php $this->load->view('frontend/layout/footer');?>