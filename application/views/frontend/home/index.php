<?php $this->load->view('frontend/layout/header');?>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div id="myCarousel" class="carousel slide borderCarrousel" data-interval="5000">

			<!-- Indicators -->
			<ol class="carousel-indicators">
				<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
				<?php $i=1;foreach($banners as $banner):?>
					<li data-target="#myCarousel" data-slide-to="<?php echo $i; ?>" class=""></li>
				<?php $i++; endforeach; ?>
			</ol>

			<!-- Wrapper for slides -->
			<div class="carousel-inner" role="listbox">

				<div class="item active"> <!-- contendedor de cada slide -->
					<div class="front-banner-home">
						<div class="row" align="center">
							<video id="videoBanner" preload="metadata" height="348px" controls poster="<?php echo site_url("/files/banners/imagenes/160418-Miele-BannerPoster.jpg") ?>">
								<source src="<?php echo site_url("/files/banners/videos/160418-Miele-BannerVideo.mp4") ?>" type="video/mp4"/>
							</video>
						</div>
					</div>
				</div>

				<?php foreach($banners as $banner):?>
					<div class="item"> <!-- contendedor de cada slide -->
						<?php if(@$banner->url):?><a href="<?php echo $banner->url; ?>"><?php endif;?>
							<?php $orden = $banner->imagen_orden?'_'.$banner->imagen_orden:'';?>
							<?php $path=$this->config->item('cloudfiles')?$this->cloud_files->url_publica("files/banners/{$banner->id}{$orden}.jpg"):"files/banners/{$banner->id}.jpg";?>
							<div style="background-image:url('<?php echo site_url("/thumbs/timthumb.php?src={$path}&mw=1140&mh=350&t=".time());?>');"  class="front-banner-home">
								<div class="row">
									<?php if (!empty($banner->accesorios)):?>
										<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6 col-xs-offset-4 col-sm-offset-5 col-md-offset-6 col-lg-offset-7 front-banner-accesorios">
											<div class="front_subtituloSeccion">
												<p class="">Accesorios</p>
											</div>
											<div class="row">
												<?php foreach($banner->accesorios as $ba):?>
													<?php if(!empty($ba)):?>
														<div class="col-lg-12">
															<div class="row front-side-accesorio">
																<div class="col-lg-6 col-md-5 col-sm-4">
																	<?php $orden = $ba->imagen_orden?'_'.$ba->imagen_orden:'';?>
																	<?php $path_accesorio=$this->config->item('cloudfiles')?$this->cloud_files->url_publica("files/accesorios/{$ba->id}{$orden}.jpg"):"files/accesorios/{$ba->id}.jpg";?>
																	<img alt="" src="<?php echo site_url("/thumbs/timthumb.php?src={$path}&s=100&t=".time());?>">
																</div>
																<div class="col-lg-6 col-md-7 col-sm-8">
																	<p class="producto"><?php echo $ba->nombre;?></p>
																	<p class="modelo"><?php echo $ba->modelo;?></p>
																</div>
															</div>
														</div>
													<?php endif;?>
												<?php endforeach;?>
											</div>
										</div>
									<?php endif;?>
								</div>
							</div>
							<?php if(@$banner->url):?></a><?php endif;?>
					</div>
				<?php endforeach;?>
			</div>

			<!-- Controls -->
			<a class="left carousel-control" href="#myCarousel" data-slide="prev" role="button">
				<i class="fa fa-angle-left fa-4x mt120"></i>
			</a>
			<a class="right carousel-control" href="#myCarousel" data-slide="next" role="button">
				<i class="fa fa-angle-right fa-4x mt120"></i>
			</a>
		</div>
	</div>
</div>
<?php $path_regalo=site_url('img/promociones/promocion.png');?>
<div class="row mt40 noMargin mb80">
	<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
		<div class="front_subtituloSeccion mb20">
			<p class="">Categor&iacute;as</p>
		</div>
		<div class="row">
			<div id="container" class="js-masonry">
			<?php foreach ($categorias as $p):?>
				<div class="col-lg-6 col-md-6 col-sm-6 item ">				
					<a href="<?php echo site_url('frontends/categorias/'.$p->id); ?>" class="link_categoria">
						<div class="front-cat-producto thumbnail heightAcc <?php echo @$p->promocion?'promocion_categoria':''?>" style="position: relative;"> <!--front-cat- producto -->
							<table>
								<tr>
									<?php
									if($this->config->item('cloudfiles')):
										$orden = $p->imagen_orden?'_'.$p->imagen_orden:'';
										$path = $this->cloud_files->url_publica("files/categorias/{$p->id}{$orden}.jpg");

										$orden_video = $p->video_orden?'_'.$p->video_orden:'';
										$path_video = (!empty($orden_video))?$this->cloud_files->url_publica("files/categorias/{$p->id}{$orden_video}"):'';
										$path_play = site_url('img/categorias/play_icon.png');
										$path_play_hover = site_url('img/categorias/play_icon_over.png');
										$video = (!empty($path_video))?TRUE:FALSE;
									else:
										$orden = $p->imagen_orden?'_'.$p->imagen_orden:'';
										$path = site_url("files/categorias/{$p->id}.jpg");
										$orden_video = $p->video_orden?'_'.$p->video_orden:'';
										$path_video = (!empty($orden_video))?site_url("files/categorias/{$p->id}"):'';
										$path_play = site_url('img/categorias/play_icon.png');
										$path_play_hover = site_url('img/categorias/play_icon_over.png');
										$video = (!empty($path_video))?TRUE:FALSE;
									endif;
									?>
									<td class="img-producto<?php echo ($video)?' categorias_video':''; ?>">
										<img src="<?php echo site_url("/thumbs/timthumb.php?src={$path}&mw=250&mh=250&t=".time());?>" class="img_thumb imgMinWidth"/>
										<?php if($video): ?>
											<img class="categorias_play_button" src="<?php echo $path_play;?>">
                                            <a class="bc_colorbox_player" href="<?php echo site_url('frontends/reproductor/'.$p->id); ?>">
												<img class="categorias_play_button_hover" src="<?php echo $path_play_hover;?>">
											</a>
										<?php endif; ?>

										<?php if(@$p->promocion):?>
											<img class="banda_promo" style="height:100px !important;width:150px !important;position: absolute;z-index: 1;left:0;margin:-0.5px;top:0;"  src="<?php echo site_url('/thumbs/timthumb.php?src='.$path_regalo.'&s=400&t='.time());?>" />
										<?php endif;?>
									</td>
								</tr>
								<tr>
									<td class="info-producto">
										<p class="producto" title="<?php echo $p->nombre;?>"><?php echo strlen($p->nombre)>26?substr($p->nombre,0,23).'...':$p->nombre; ?></p>
										<p class="modelo"><?php echo $p->descripcion;?></p>
									</td>
								</tr>
							</table>
							<?php if(@$p->promocion):?>
								<div class="tr_foot_promocion tc">
                                    <p class="modelo"><?php echo $p->descripcion;?></p>
                                    <br>
									<a class="btn btn-front-primary pull-center bc_fancybox" href="<?php echo site_url('frontends/promocion_view/'.$p->promocion); ?>" id="ver_promocion"><?php echo 'Ver promoción';?>&nbsp;<i class="fa fa-gift"></i></a>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<a class="btn btn-front-primary pull-center" href="<?php echo site_url('frontends/categorias/'.$p->id); ?>" id="ver_promocion"><?php echo 'Ver categoría';?>&nbsp;<i class="fa fa-file-text"></i></a>
								</div>
                                <div class="tr_top_promocion tc">
                                    <p title="<?php echo $p->nombre;?>"><?php echo strlen($p->nombre)>26?substr($p->nombre,0,23).'...':$p->nombre; ?></p>
                                </div>
							<?php endif;?>
						</div>
					</a>
				</div>
			<?php endforeach;?>
			</div>
		</div>
	</div>
	<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
		<div class="front_subtituloSeccion mb20">
			<p class=""> Paquetes</p>
		</div>
		<?php foreach ($paquetes as $pkg):?>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 front-side-producto fright" >
			<a href="<?php echo site_url('frontends/paquete_detalle/'.$pkg->id) ?>">
				<div class="row">
                    <div class="col-lg-12 ">
                        <p class="titulo-producto" ><?php echo $pkg->nombre;?></p>
                        <?php $orden = $pkg->imagen_orden?'/paquete'.$pkg->imagen_orden:'';?>
                        <?php $path=$this->config->item('cloudfiles')?$this->cloud_files->url_publica("files/paquetes/{$pkg->id}{$orden}.jpg"):"files/paquetes/{$pkg->id}.jpg";?>
						<img src="<?php echo site_url("/thumbs/timthumb.php?src={$path}&zc=1&q=100&s=600&t=".time());?>" class="img_thumb" />
                    </div>
					<div class="col-lg-12">
						<p class="detalle-producto "><?php echo $pkg->descripcion;?></p>
						<!-- Productos del  paquete 
						<?php foreach($pkg->productos[$pkg->id] as $pk=>$pv):?>
							<p class="detalle-producto"> <strong><?php echo $pv['cantidad'];?></strong> - <?php echo $productos_paquetes[$pv['productos_id']];?></p>
						<?php endforeach;?>-->
						<!--Fin productos del paquete -->
					</div>
				</div>
			</a>
		</div>
		<?php endforeach;?>
	</div>
</div>
<?php $this->load->view('frontend/layout/footer');?>
<script>
	var videoBanner = $('#videoBanner');
	var video = document.getElementById("videoBanner");
	var elemento_actual = -1;

	$(function(){
		carrusel = $("#myCarousel");

		carrusel.carousel("pause");
		video.volume = 0;
		video.play();

		carrusel.on('slide.bs.carousel', function ()
		{
			elemento_actual = $('#myCarousel .active').index('#myCarousel .item');

			if (elemento_actual==3)
			{
				carrusel.carousel("pause");
				video.currentTime = 0;
				video.play();
			}else
			{
				setTimeout(function(){
						video.pause();
						carrusel.carousel("next");
					},
					5000);
			}
		});

		video.onended = function() {
			video.currentTime = 0;
			carrusel.carousel("next");
		};

	});

	videoBanner.mouseover(function(){
		video.volume = 0.5;
	});

	videoBanner.mouseout(function(){
		video.volume = 0.0;
	});

	videoBanner.click(function(){
		video.play();
		if (video.requestFullscreen) {
			video.requestFullscreen();
		} else if (video.mozRequestFullScreen) {
			video.mozRequestFullScreen();
		} else if (video.webkitRequestFullscreen) {
			video.webkitRequestFullscreen();
		}
	});
</script>