<?php $this->load->view('frontend/layout/header');?>
<div class="row mt80 mb60 minHeight">	
	<div class="col-lg-12">
		<form action="<?php echo site_url(uri_string()); ?>" id="from_accesorios" method="post">
		<input type="hidden" name="accesorio" value="1" />
        <input type="hidden" name="sin_agregar" value="0" id="sin_agregar"/>
		<div class="row">
			<div class="col-lg-12 col-md-6 col-sm-12 mb20">
				<div class="front_subtituloSeccion mt20">
					<p><?php echo $titulo;?></p>
				</div>
			</div>
		</div>
        <div class="row noMargin">
            <p class="msg info">
            <span class="fa-stack fa-2x">
              <i class="fa fa-circle fa-stack-2x text-color-blanco"></i>
              <i class="fa fa-info fa-stack-1x text-color-info"></i>
            </span>
                A continuaci&oacute;n se muestran los <?php echo @$consumibles?'consumibles':'accesorios';?> que puede agregar <?php echo @$productos_id?' a su equipo':'al carrito';?> seleccionando la casilla.
            </p>
        </div>
        <div class="row">
            <div class="col-lg-12 front_btnsWrapper">
                <input class="btn btn-front-primary pull-right sin_agregar" type="button" value="Continuar sin agregar">
                <input class="btn btn-front-primary pull-right" id="agregar" type="submit" value="Agregar">
            </div>
        </div>
        <br/>
		<div class="row">
			<?php $i=0;foreach($accesorios as $a=>$v):?>
			<div class="col-lg-4 col-md-4 col-sm-6 item ">
				<div class="front-acc-producto thumbnail">
					<div class="checkbox pull-right">
						<label>
							<input type="checkbox" id="<?php echo $v->id;?>" name="accesorios_ids[]" value="<?php echo $v->id;?>" <?php echo set_checkbox("accesorios_ids[]",'1',(@$r->activo)?TRUE:FALSE); ?>/>
						</label>
						<?php echo  form_error($v->id);?>
					</div>
					<table>
						<tr>
							<td colspan="2" class="img-producto">
								<?php $orden = $v->imagen_orden?'_'.$v->imagen_orden:'';?>
								<?php $path=$this->config->item('cloudfiles')?$this->cloud_files->url_publica("files/accesorios/{$v->id}{$orden}.jpg"):"files/accesorios/{$v->id}.jpg";?>
								<img src="<?php echo site_url('/thumbs/timthumb.php?src='.$path.'&mw=250&mh=200&t='.time());?>" class="img_thumb" />
							</td>
						</tr>
						<tr>
							<td class="info-producto">
								<tr>
									<td width="70%">
										<p><span class="front_tituloProducto" title="<?php echo $v->nombre; ?>"><?php echo strlen($v->nombre)>28?substr($v->nombre,0,25).'...':$v->nombre; ?></span></p>
									</td>
									<td width="30%">
										<div style="float: right; width: 100px; margin-right: 5px; margin-top: 10px;" id="<?php echo $v->id?>" class="rate_widget" data-table="accesorios">
											<div class="ratings_stars" data-value="1"></div>
											<div class="ratings_stars" data-value="2"></div>
											<div class="ratings_stars" data-value="3"></div>
											<div class="ratings_stars" data-value="4"></div>
											<div class="ratings_stars" data-value="5"></div>
											&nbsp;
											<a href="<?php echo site_url("/calificaciones/comentarios/{$v->id}/accesorios");?>" class="bc_comentarios">
												<span style="margin-top: -10px; float: right; margin-right: 8px;" class="<?php echo $v->id;?>"></span>
											</a>
										</div>
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<p style="margin:0 0 2.5px; width: 75%" class="front_cuerpoTexto">Modelo: <?php echo $v->modelo;?> &nbsp;&nbsp;&nbsp;(<?php echo (!empty($v->obligatorio_id))?'Obligatorio':'Optativo'?>)</p>
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<p style="margin:0 0 2.5px;" class="front_cuerpoTexto">#Item: <?php echo $v->item;?></p>
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<p style="margin:0 0 2.5px;" class="front_precio">Precio: <?php echo precio_con_iva($v->precio);?></p>
									</td>
								</tr>

								<tr>
									<td colspan="2">
										<p style="margin:0 0 8.5px;" class="front_cuerpoTexto">
											<?php if($this->config->item('cloudfiles')):
												if(!empty($v->manual_extension)):
													$orden = $v->manual_orden?'_'.$v->manual_orden:'';
													$path=$this->cloud_files->url_publica("files/accesorios/{$v->id}/manual{$orden}.{$v->manual_extension}"); ?>
													<a class="view pull-right" target="_blank" style="margin-right:12px;" href="<?php echo $path; ?>">|&nbsp;&nbsp;&nbsp;Manual</a>
												<?php endif;
												if(!empty($v->guia_mecanica_extension)):
													$orden = $v->guia_mecanica_orden?'_'.$v->guia_mecanica_orden:'';
													$path=$this->cloud_files->url_publica("files/accesorios/{$v->id}/guia_mecanica{$orden}.{$v->guia_mecanica_extension}"); ?>
													<a class="view pull-right" target="_blank" style="margin-right:12px;" href="<?php echo $path; ?>">Gu&iacute;a Mec&aacute;nica</a>
												<?php endif;?>
											<?php else: ?>
												<?php if(!empty($v->guia_mecanica_extension)):?>
													<a class="btn btn-front-default pull-right" target="_blank" style="margin-right:12px;" href="<?php echo site_url("files/accesorios/{$v->id}/guia_mecanica.{$v->guia_mecanica_extension}"); ?>">Gu&iacute;a Mec&aacute;nica</a>
												<?php endif;?>
												<?php if(!empty($v->manual_extension)):?>
													<a class="btn btn-front-default pull-right" target="_blank" style="margin-right:12px;" href="<?php echo site_url("files/accesorios/{$v->id}/manual.{$v->manual_extension}"); ?>">Manual</a>
												<?php endif;?>
											<?php endif;?>

										</p>
									</td>
								</tr>
							</td>
						</tr>
					</table>
				</div>
			</div>
			<?php $i++; endforeach;?>
		</div>
		<div class="row">
			<div class="col-lg-12 front_btnsWrapper">
				<input class="btn btn-front-primary pull-right" id="agregar" type="submit" value="Agregar">
<!--                <input class="btn btn-front-primary pull-right" id="agregar" type="submit" value="Continuar sin accesorios">-->
				<a class="btn btn-front-default pull-right" href="<?php echo site_url('frontends/index'); ?>">Cancelar</a>
			</div>
		</div>
		</form>
	</div>
</div>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script src="<?php echo base_url(); ?>js/alertify/alertify.min.js"></script>
<script type="text/javascript">

function reset () {
	$("#toggleCSS").attr("href", "<?php echo base_url(); ?>js/alertify/themes/alertify.default.css");
	alertify.set({
		labels : {
			ok     : "Aceptar",
			cancel : "Rechazar"
		},
		delay : 5000,
		buttonReverse : false,
		buttonFocus   : "ok"
	});
}

$(function(){
	$('.sin_agregar').on('click', function(e){
		e.preventDefault();
		alertify.set({ labels: { ok: 'Aceptar', cancel: 'Cancelar' } });
		alertify.confirm('¿Está seguro que desea continuar sin agregar los accesorios seleccionados?', function(a) {
			if(a)
			{
				$('#sin_agregar').val(1);
				$('#from_accesorios').submit();
			}
		});
	});
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
		$('span.'+$(widget).attr('id')).text(cmt+" comentarios");
	}

	$('.bc_comentarios').fancybox({
		type: 'ajax'
	});

});
</script>
<?php $this->load->view('frontend/layout/footer');?>