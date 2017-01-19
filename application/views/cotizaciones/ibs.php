<?php if(!is_ajax()){ $this->load->view('layout/header'); } ?>
<div id="cotizacion_panel" style="background-color: #FFFFFF; margin: 10px; border-radius: 10%">

	<div class="col-lg-12 formulario-head">
		<div class="row">
			<div class="col-lg-6 col-sm-10 col-xs-9">
				<h4><?php echo $titulo;?></h4>
			</div>
		</div>
	</div>
	<?php $this->load->view('layout/flash'); ?>
	<div class="col-lg-12">
		<form action="<?php echo site_url('/cotizaciones/ibs_agregar/'.$cotizaciones_id); ?>" id="form_ibs_agregar" method="post" enctype="multipart/form-data" role="form">
			<p class="msg info">Por favor ingrese la Orden de Venta IBS correspondiente a la orden de compra. Los campos requeridos estan marcados con <span class="req">*</span></p>
			<input id="id" name="id" value="<?php echo set_value('id',$cotizaciones_id); ?>" type="hidden" />

			<div class=" col-lg-12 backgroundGeneric panelBancos">
				<div class="row">
					<div class="col-lg-6 form-group">
						<label><span class="req">*</span>Orden de Venta IBS:</label>
						<input type="text" class="form-control" id="ibs" name="ibs" value="<?php echo set_value('ibs',@$ibs); ?>" />
						<?php echo  form_error('ibs');?>
					</div>
				</div>
				<?php if(!empty($cupones_disponibles) && empty($cupon_folio_enviado_id)): ?>
				<div class="row">
					<div class="col-lg-3 form-group">
						<label>Enviar cup&oacute;n por email:</label>
						<input type="checkbox" class="" id="enviar_mail" name="enviar_mail" <?php echo (@$_POST['enviar_mail'])?'checked':''; ?> />
					</div>
					<div id="email" class="col-lg-6 form-group" style="display:none;">
						<label>Email del cliente para envío de cupón:</label>
						<input type="text" class="form-control" id="cupon_cliente_email" name="cupon_cliente_email" value="<?php echo set_value('cupon_cliente_email',@$cupon_cliente_email); ?>" />
						<?php echo  form_error('cupon_cliente_email');?>
					</div>
				</div>
				<?php elseif(!empty($cupon_folio_enviado_id)): ?>
					<div class="row">
						<div class="col-lg-3 form-group">
							<label>Enviar cup&oacute;n por email:</label>
							<input type="checkbox" class="" id="enviar_mail" name="enviar_mail" checked readonly />
						</div>
						<div id="email" class="col-lg-6 form-group" style="display:none;">
							<label>Email del cliente para envío de cupón:</label>
							<input type="text" class="form-control" id="cupon_cliente_email" name="cupon_cliente_email" value="<?php echo set_value('cupon_cliente_email',@$cupon_cliente_email); ?>" readonly />
							<?php echo  form_error('cupon_cliente_email');?>
						</div>
					</div>
				<?php endif; ?>
				<div class="row">
					<div class="col-lg-3 form-group">
						<label>Enviar gu&iacute;as mec&aacute;nicas por email:</label>
						<input type="checkbox" class="" id="guia_mecanica_mail" name="guia_mecanica_mail" <?php echo (@$_POST['guia_mecanica_mail'])?'checked':''; ?> />
					</div>
					<div id="guia_email" class="col-lg-6 form-group" style="display:none;">
						<label>Email del cliente para envío de guías mecánicas y carta bienvenida:</label>
						<input type="text" class="form-control" id="guia_mecanica_cliente_email" name="guia_mecanica_cliente_email" value="<?php echo set_value('guia_mecanica_cliente_email',@$guia_mecanica_cliente_email); ?>" />
						<?php echo  form_error('guia_mecanica_cliente_email');?>
					</div>
				</div>
			</div>

			<div class="col-lg-12 barra-btn">
				<div class="row">
					<div class="col-lg-12 ">
						<button id="guardar" type="submit" class="btn btn-primary pull-right">Guardar</button>
						<a href="#" id="cancelar" class="btn btn-default pull-right">Cancelar</a>
					</div>
				</div>
			</div>
		</form>
	</div>

</div>
<script type="text/javascript">
<!--
	$('#cancelar').click(function(){
		$.colorbox.close();
	});

	$(document).ready(function($) {
		if ($('#enviar_mail').is(':checked')) {
			$('#email').show();
		}

		if ($('#guia_mecanica_mail').is(':checked')) {
			$('#guia_email').show();
		}

		$("#cboxContent").css('background','#FFFFFF');
		$("#cboxMiddleLeft").css('width','17px');
		$("#cboxMiddleRight").css('width','17px');

	});

	$('#enviar_mail').click(function(){
		if($(this).is( ":checked" ))
			$('#email').show();
		else
			$('#email').hide();
	});

	$('#guia_mecanica_mail').click(function(){
		if($(this).is( ":checked" ))
			$('#guia_email').show();
		else
			$('#guia_email').hide();
	});

	$('#form_ibs_agregar').submit(function(e){
		e.preventDefault();
		var form = $(this);
		var data = form.serialize();
		var input_submit = $('#guardar');
		input_submit.attr('disabled','disabled');
		input_submit.html('').css('background-color','#E60000');
		input_submit.html("<i style='color:#fffafe; font-size: 15px;' class='fa fa-spinner fa-spin'/>");
		$.post(form.attr('action'), data, function (result) {
			var entrega = $(result).find('#form_ibs_agregar').length;
			if(entrega)
			{
				$('#cotizacion_panel').html(result);
				$.colorbox.resize();
			}
			else {
				$.colorbox.close();
				window.location.reload();
			}
		});
	});

//-->
</script>
<?php if(!is_ajax()){ $this->load->view('layout/footer'); } ?>