<?php $this->load->view('frontend/layout/header');?>
<div class="row mb20 noMargin">
	<div class=" mt10">
		<div class="front_subtituloSeccion mb20">
			<p>
				<?php echo $titulo;?>
				<br>
			</p>

			<div>
				<?php if(empty($subcategorias)):?>
					<a class="btn front-btn-detalle pull-right" href="<?php echo site_url('frontends/mostrar_detalle'); ?>"><?php echo $this->session->userdata('mostrar_detalles')?'Ocultar detalles':'Mas detalles';?></a>
				<?php endif;?>
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
            <div>
                <?php echo @$informacion_general?>
            </div>
		</div>
	</div>
	<?php if($video): ?>
		<div class="col-lg-12 col-md-12 col-sm-12 cols-xs-6 front-cat-producto heightAcc embed-responsive embed-responsive-16by9">
			<video class="col-lg-12 col-md-12 col-sm-12 cols-xs-6 front-cat-producto heightAcc categorias_video" preload="metadata">
				<source type="video/mp4" src="<?php echo $path_video;?>">
			</video>

			<img class="categorias_play_button" src="<?php echo $path_play;?>">
            <a class="bc_colorbox_player" href="<?php echo site_url('frontends/reproductor/'.$categorias_id); ?>">
				<img class="categorias_play_button_hover" src="<?php echo $path_play_hover;?>">
			</a>
		</div>
	<?php endif; ?>

	<div class=" mt20 ">
		<div id="container mb60" class="js-masonry">
			<?php $path_regalo=site_url('img/promociones/promocion.png');?>
			<?php if(!empty($subcategorias)):?>
				<?php foreach ($subcategorias as $s):?>
				<div class="col-lg-4 col-md-4 col-sm-6 item">
					<div class="front-cat-producto thumbnail heightAcc <?php echo @$categoria_promocion?'promocion_categoria':''?>" style="position: relative;">
						<a href="<?php echo site_url('frontends/categorias/'.$s->id); ?>" class="link_detalle_cat">
							<table>
								<tr>
									<?php
										$path_play = site_url('img/categorias/play_icon.png');
										$path_play_hover = site_url('img/categorias/play_icon_over.png');
									if($this->config->item('cloudfiles')):
										$orden_video = $s->video_orden?'_'.$s->video_orden:'';
										$path_video = (!empty($orden_video))?$this->cloud_files->url_publica("files/categorias/{$s->id}{$orden_video}"):'';
										$video = (!empty($path_video))?TRUE:FALSE;
									else:
										$path_video = site_url("files/categorias/{$s->id}.mp4");
									endif;
									?>
									<td class="img-producto <?php echo ($video)?' categorias_video':''; ?>">
										<?php $orden = $s->imagen_orden?'_'.$s->imagen_orden:'';?>
										<?php $path=$this->config->item('cloudfiles')?$this->cloud_files->url_publica("files/categorias/{$s->id}{$orden}.jpg"):"files/categorias/{$s->id}.jpg";?>
										<img src="<?php echo site_url("/thumbs/timthumb.php?src={$path}&mw=250&mh=250&t=".time());?>">

										<?php if($video): ?>
											<img class="categorias_play_button" src="<?php echo $path_play;?>">
                                            <a class="bc_colorbox_player" href="<?php echo site_url('frontends/reproductor/'.$s->id); ?>">
												<img class="categorias_play_button_hover" src="<?php echo $path_play_hover;?>">
											</a>
										<?php endif; ?>

										<?php if(@$categoria_promocion):?>
										<img class="banda_promo" style="height:100px !important;width:150px !important;position: absolute;z-index: 1;left:0;margin:-0.5px;top:0;"  src="<?php echo site_url('/thumbs/timthumb.php?src='.$path_regalo.'&s=400&t='.time());?>" />
										<?php endif;?>
									</td>
								</tr>
								<tr>
									<td class="info-producto">
										<p class="producto" title="<?php echo $s->nombre;?>"><b><?php echo strlen($s->nombre)>28?substr($s->nombre,0,25).'...':$s->nombre; ?></b></p>
										<p class="modelo"><?php echo $s->descripcion;?></p>
									</td>
								</tr>
							</table>
							<?php if(@$categoria_promocion):?>
								<div class="tr_foot_promocion tc">
                                    <p class="modelo"><?php echo $s->descripcion;?></p>
                                    <br>
                                    <a class="btn btn-front-primary pull-center bc_fancybox" href="<?php echo site_url('frontends/promocion_view/'.$categoria_promocion.'/1'); ?>" id="ver_promocion"><?php echo 'Ver promoción';?>&nbsp;<i class="fa fa-gift"></i></a>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<a class="btn btn-front-primary pull-center" href="<?php echo site_url('frontends/categorias/'.$s->id); ?>" id="ver_promocion"><?php echo 'Ver categoría';?>&nbsp;<i class="fa fa-file-text"></i></a>
								    <br>
								    <br>
                                </div>
                                <div class="tr_top_promocion tc">
                                    <p title="<?php echo $s->nombre;?>"><b><?php echo strlen($s->nombre)>28?substr($s->nombre,0,25).'...':$s->nombre; ?></b></p>
                                </div>
							<?php endif;?>
						</a>
					</div>
				</div>
				<?php endforeach;?>
			<?php else:?>
			<div class="mb60 js-masonry">
				<?php if(!$this->session->userdata('mostrar_detalles')):?>
					<?php foreach ($r as $ro):?>
						<?php if(empty($ro['ocultar'])): ?>
							<div class="col-lg-4 col-md-4 col-sm-6 item ">
								<a href="<?php echo site_url('frontends/productos/'.$ro['id']); ?>" class="link_detalle_prod">
									<div class="front-cat-producto thumbnail heightAcc <?php echo @$ro['promocion']?'promocion_producto':''?>" style="position: relative;">
										<table>
											<tr>
												<td colspan="2" style class="img-producto">
													<?php $path=$this->config->item('cloudfiles')?$this->cloud_files->url_publica("files/productos/{$ro['id']}/{$ro['foto_id']}.jpg"):"files/productos/{$ro['id']}/{$ro['foto_id']}.jpg";?>
													<img src="<?php echo site_url("/thumbs/timthumb.php?src={$path}&mw=250&mh=250&t=".time());?>">
													<?php if(@$ro['promocion']):?>
														<img class="banda_promo" style="height:100px !important;width:150px !important;position: absolute;z-index: 1;left:0;margin:-0.5px;top:0;"  src="<?php echo site_url('/thumbs/timthumb.php?src='.$path_regalo.'&s=400&t='.time());?>" />
													<?php endif;?>
												</td>
											</tr>
											<tr>
												<td class="info-producto">
													<tr>
														<td width="50%"><p class="producto" style="color:#757575!important; margin:0 0 8.5px;" title="<?php echo $ro['nombre'];?>"><b><?php echo strlen($ro['nombre'])>28?substr($ro['nombre'],0,25).'...':$ro['nombre']; ?></b></p></td>
														<td width="50%">
															<div style="float: right;width: 100px; margin-right: 15px;" id="<?php echo $ro['id']?>" class="rate_widget" data-table="productos">
																<div class="ratings_stars" data-value="1"></div>
																<div class="ratings_stars" data-value="2"></div>
																<div class="ratings_stars" data-value="3"></div>
																<div class="ratings_stars" data-value="4"></div>
																<div class="ratings_stars" data-value="5"></div>
															</div>
														</td>
													</tr>
													<tr>
														<td><p style="margin:0 0 1.5px;" class="modelo">Modelo: <?php echo $ro['modelo'];?></p></td>
														<td>
															<a href="<?php echo site_url("/calificaciones/comentarios/{$ro['id']}/productos");?>" class="bc_comentarios">
																<div style="margin-top: -20px;float: right;margin-right: 15px;" class="<?php echo $ro['id'];?>"></div>
															</a>
														</td>
													</tr>
													<tr>
														<td colspan="2"><p style="margin:0 0 8.5px;" class="front_precio">Precio: <?php echo precio_con_iva($ro['precio']);?></p></td>
													</tr>
												</td>
											</tr>
										</table>
										<?php if(@$ro['promocion']):?>
											<div class="tr_foot_promocion tc">
												<p style="width: 50%;">
												<div style="float: right; padding: 0px; !important; width: 100px;     margin-right: 15px;" id="<?php echo $ro['id']?>" class="rate_widget" data-table="productos">
													<div class="ratings_stars" data-value="1"></div>
													<div class="ratings_stars" data-value="2"></div>
													<div class="ratings_stars" data-value="3"></div>
													<div class="ratings_stars" data-value="4"></div>
													<div class="ratings_stars" data-value="5"></div>
													<a href="<?php echo site_url("/calificaciones/comentarios/{$ro['id']}/productos");?>" class="bc_comentarios">
														<span style="margin-top: -6px; float: right;" class="<?php echo $ro['id'];?>"></span>
													</a>
												</div>
												</p>
												<p style="width:50%;" class="modelo">Modelo: <?php echo $ro['modelo'];?></p>
												<p style="width:50%;" class="front_precio">Precio: <?php echo precio_con_iva($ro['precio']);?></p>
												<a class="btn btn-front-primary pull-center bc_fancybox" href="<?php echo site_url('frontends/promocion_view/'.$ro['promocion'].'/2/'.$ro['categorias_id'].'/'.$ro['id']); ?>" id="ver_promocion"><?php echo 'Ver promoción';?>&nbsp;<i class="fa fa-gift"></i></a>
												&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
												<a class="btn btn-front-primary pull-center" href="<?php echo site_url('frontends/productos/'.$ro['id']); ?>" id="ver_promocion"><?php echo 'Ver producto';?>&nbsp;<i class="fa fa-file-text"></i></a>
											</div>
											<div class="tr_top_promocion tc">
												<p title="<?php echo $ro['nombre'];?>"><b><?php echo strlen($ro['nombre'])>28?substr($ro['nombre'],0,25).'...':$ro['nombre']; ?></b></p>
											</div>
										<?php endif;?>
									</div>
								</a>
							</div>
						<?php endif; ?>
					<?php endforeach;
					else: ?>
						<div class="table-responsive" style="width: 100%;">
							<table class="table table-striped">
								<thead>
									<tr>
										<td>&nbsp;</td>
										<td>Modelo</td>
										<td>Producto</td>
										<td>Precio</td>
										<td>Calificaci&oacute;n</td>
										<td>Comentarios</td>
									</tr>
								</thead>
								<tbody>
								<?php foreach ($r as $ro):?>
									<tr>
										<td style="position: relative;">
											<a href="<?php echo site_url('frontends/productos/'.$ro['id']); ?>" class="link_tr_detalle_prod">
												<?php $path=$this->config->item('cloudfiles')?$this->cloud_files->url_publica("files/productos/{$ro['id']}/{$ro['foto_id']}.jpg"):"files/productos/{$ro['id']}/{$ro['foto_id']}.jpg";?>
												<img src="<?php echo site_url("/thumbs/timthumb.php?src={$path}&s=100t=".time());?>">
												<?php if(@$ro['promocion']):?>
													<img class="banda_promo" style="height:50px !important;width:75px !important;position: absolute;z-index: 1;left:0;margin:-0.5px;top:0;"  src="<?php echo site_url('/thumbs/timthumb.php?src='.$path_regalo.'&s=400&t='.time());?>" />
												<?php endif;?>
											</a>
										</td>
										<td><a href="<?php echo site_url('frontends/productos/'.$ro['id']); ?>" class="link_tr_detalle_prod"><p class="front_cuerpoTexto"><?php echo $ro['modelo'];?></p></a></td>
										<td><a href="<?php echo site_url('frontends/productos/'.$ro['id']); ?>" class="link_tr_detalle_prod"><p class="front_tituloProducto"><?php echo $ro['nombre']; ?></p></a></td>
										<td><a href="<?php echo site_url('frontends/productos/'.$ro['id']); ?>" class="link_tr_detalle_prod"><p class="front_tituloProducto text-danger"><?php echo precio_con_iva($ro['precio']);?></p></a></td>
										<?php if(@$ro['promocion']):?>
										<td align="center" class="tr_promocion_producto">
											<a class="btn btn-front-primary pull-center bc_fancybox" href="<?php echo site_url('frontends/promocion_view/'.$ro['promocion'].'/2'); ?>" id="ver_promocion"><?php echo 'Ver promoción';?>&nbsp;<i class="fa fa-gift"></i></a>
											&nbsp;&nbsp;&nbsp;&nbsp;
											<a class="btn btn-front-primary pull-center" href="<?php echo site_url('frontends/productos/'.$ro['id']); ?>" id="ver_promocion"><?php echo 'Ver producto';?>&nbsp;<i class="fa fa-file-text"></i></a>
										</td>
										<?php endif;?>
										<td align="center">
											<div style="float: right;" id="<?php echo $ro['id']?>" class="rate_widget" data-table="productos">
												<div class="ratings_stars" data-value="1"></div>
												<div class="ratings_stars" data-value="2"></div>
												<div class="ratings_stars" data-value="3"></div>
												<div class="ratings_stars" data-value="4"></div>
												<div class="ratings_stars" data-value="5"></div>
											</div>
										</td>
										<td align="center">
											<a href="<?php echo site_url("/calificaciones/comentarios/{$ro['id']}/productos");?>" class="bc_comentarios">
												<div class="<?php echo $ro['id'];?> detalle"></div>
											</a>
										</td>
									</tr>
								<?php endforeach;?>
								</tbody>
							</table>
						</div>
					<?php endif;?>
				</div>
			<?php endif;?>
		</div>
	</div>
</div>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
$(function() {

	<?php if($categoria_promocion):?>
	var timeout;
	var href = '';
	$('div.promocion_categoria').mouseenter(function () {
		href = $(this).find('a.link_detalle_cat').attr('href');
		$(this).find('a.link_detalle_cat').attr('href', '#');
		$(this).find('a.link_detalle_cat').click(function (e) {
			e.preventDefault();
		});

		$(this).find('.banda_promo, .info-producto .modelo, .info-producto .producto').addClass('trasintion_5');
		$(this).find('.banda_promo, .info-producto .modelo, .info-producto .producto').addClass('fade');
		$(this).find('.tr_foot_promocion').addClass('foot_promocion');
		$(this).find('.tr_top_promocion').addClass('top_promocion');
	});

	$('div.promocion_categoria').mouseleave(function () {
		$(this).find('a.link_detalle_cat').attr('href', href);

		$(this).find('.banda_promo, .info-producto .modelo, .info-producto .producto').removeClass('fade');
		$(this).find('.tr_foot_promocion').removeClass('foot_promocion');
		$(this).find('.tr_top_promocion').removeClass('top_promocion');
	});
	<?php endif;?>

	$('div.promocion_producto').mouseenter(function () {
		href = $(this).find('a.link_detalle_prod').attr('href');
		$(this).find('a.link_detalle_prod').attr('href', '#');
		$(this).find('a.link_detalle_prod').click(function (e) {
			e.preventDefault();
		});

		$(this).find('.banda_promo, .info-producto .modelo, .info-producto .producto').addClass('trasintion_5');
		$(this).find('.banda_promo, .info-producto .modelo, .info-producto .producto').addClass('fade');
		$(this).find('.tr_foot_promocion').addClass('foot_promocion');
		$(this).find('.tr_top_promocion').addClass('top_promocion');
	});

	$('div.promocion_producto').mouseleave(function () {
		$(this).find('a.link_detalle_prod').attr('href', href);

		$(this).find('.banda_promo, .info-producto .modelo, .info-producto .producto').removeClass('fade');
		$(this).find('.tr_foot_promocion').removeClass('foot_promocion');
		$(this).find('.tr_top_promocion').removeClass('top_promocion');
	});

	<?php if($this->session->userdata('mostrar_detalles')):?>
	$('.link_tr_detalle_prod').click(function (e) {
		e.preventDefault();
	});
	<?php endif;?>



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
			function(data) {
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

		$('div.'+$(widget).attr('id')).text(cmt+" comentarios");
		$('span.'+$(widget).attr('id')).text(cmt+" comentarios");
		$('div.'+$(widget).attr('id')+'.detalle').text(cmt);
	}

	$('.bc_comentarios').fancybox({
		type: 'ajax'
	});

});
</script>
<?php $this->load->view('frontend/layout/footer');?>