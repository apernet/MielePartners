<?php $this->load->view('frontend/layout/header');?>
<div class="row mb20 noMargin">
	<div class="row mt10">
		<div class="front_subtituloSeccion mb20">
			<p>
				<?php echo $titulo;?>
				<br>
			</p>
		</div>
	</div>

	<div class=" mt20 ">
		<div id="container mb60" class="js-masonry">
			<?php if(!empty($tipos_accesorios)):?>
				<?php foreach ($tipos_accesorios as $r):?>
				<div class="col-lg-4 col-md-4 col-sm-6 item">
					<div class="front-cat-producto thumbnail heightAcc" style="position: relative;">
						<a href="<?php echo site_url('frontends/cotizacion_agregar_producto/0/0/'.$r->id); ?>" class="link_detalle_cat">
							<table>
								<tr>
									<td class="img-producto">
										<?php $orden = ($r->imagen_orden)?'_'.$r->imagen_orden:'';?>
										<?php $path=$this->config->item('cloudfiles')?$this->cloud_files->url_publica("files/tipos_accesorios/{$r->id}{$orden}.jpg"):"files/tipos_accesorios/{$r->id}.jpg";?>
										<img src="<?php echo site_url("/thumbs/timthumb.php?src={$path}&mw=250&mh=250&t=".time());?>">
									</td>
								</tr>
								<tr>
									<td class="info-producto">
										<p class="producto" title="<?php echo $r->nombre;?>"><b><?php echo strlen($r->nombre)>28?substr($r->nombre,0,25).'...':$r->nombre; ?></b></p>
										<p class="modelo"><?php echo $r->descripcion;?></p>
									</td>
								</tr>
							</table>
						</a>
					</div>
				</div>
				<?php endforeach;?>
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