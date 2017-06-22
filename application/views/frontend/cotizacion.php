<?php $this->load->view('frontend/layout/header');?>
<div class="mt20 minHeight">
	<form action="<?php echo current_url(); ?>" id="form" method="post" enctype="multipart/form-data" class="text-uppercase">
		<?php if($this->session->userdata('generar_cotizacion') && INTERNO):?>
			<div class="panel-group front_panel" id="accordion">
			  	<div class="panel panel-default">
			    	<div class="panel-heading">
						<h4 class="panel-title">
					        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
					          Informaci&oacute;n del Cliente
					        </a>
					   	</h4>
			    	</div>
			    	<div id="collapseOne" class="panel-collapse collapse in">
			      		<div class="panel-body">
					        <div class="row">
					        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					        		<label><span class="req">*</span>Nombre:</label>
									<input class="form-control <?php echo form_error('nombre_comprador')?'rojo':'';?>" id="nombre_comprador"  name="nombre_comprador"
									value="<?php echo set_value('nombre',@$r['nombre_comprador']); ?>" />
									<?php echo form_error('nombre_comprador');?>
					        	</div>
					        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					        		<label><span class="req">*</span>Apellido paterno:</label>
									<input class="form-control <?php echo form_error('paterno_comprador')?'rojo':'';?>" id="paterno_comprador"  name="paterno_comprador"
									value="<?php echo set_value('paterno_comprador',@$r['paterno_comprador']); ?>"/>
									<?php echo form_error('paterno_comprador');?>
					        	</div>
					        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					        		<label>Apellido materno:</label>
									<input class="form-control <?php echo form_error('materno_comprador')?'rojo':'';?>" id="materno_comprador"  name="materno_comprador"
									value="<?php echo set_value('materno_comprador',@$r['materno_comprador']); ?>"/>
									<?php echo form_error('materno_comprador');?>
					        	</div>
					        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					        		<label>Correo electr&oacute;nico:</label>
					        		<?php if($venta_directa):?>
				        		        <div class="input-group ">
				    	        		   <input class="form-control" id="email_comprador"  name="email_comprador"  value="<?php echo set_value('email_comprador',@$r['email_comprador']); ?>"/>
				    	        		   <span class="input-group-addon">
				    					       <a class="buscar_ref icono_info" title="Buscar referencia." href="#"><i class="fa fa-search"></i></a>
				    					   </span>
				    	        		</div>
									<?php else: ?>
									   <input class="form-control <?php echo form_error('email_comprador')?'rojo':'';?>" id="email_comprador"  name="email_comprador"
									   value="<?php echo set_value('email_comprador',@$r['email_comprador']); ?>"/>
									<?php endif; ?>
									<?php echo form_error('email_comprador');?>
					        	</div>
					        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					        		<label><span class="req">*</span>Tel&eacute;fono:</label>
									<input class="form-control <?php echo form_error('telefono_comprador')?'rojo':'';?>" id="telefono_comprador"  name="telefono_comprador"
									value="<?php echo set_value('telefono_comprador',@$r['telefono_comprador']); ?>"/>
									<?php echo form_error('telefono_comprador');?>
					        	</div>
					        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					        		<label><span class="req">*</span>Entidad Federativa de Entrega:</label>
									<select class="form-control envio <?php echo form_error('entrega_estado')?'rojo':'';?>" name="entrega_estado" id="entrega_estado" >
										<option value=""><?php echo $this->config->item('empty_select');?></option>
										<?php foreach($estados as $e):?>
											<option value="<?php echo $e; ?>" <?php echo $e==@$r['entrega_estado']?'selected':''; ?> ><?php echo $e; ?></option>
										<?php endforeach;?>
									</select>
									<?php echo form_error('entrega_estado');?>
					        	</div>
					        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					        		<label><span class="req">*</span>Entidad Federativa de Instalaci&oacute;n:</label>
									<select class="form-control envio <?php echo form_error('instalacion_estado')?'rojo':'';?>" name="instalacion_estado" id="instalacion_estado" >
										<option value=""><?php echo $this->config->item('empty_select');?></option>
										<?php foreach($estados as $e):?>
											<option value="<?php echo $e; ?>" <?php echo ($e==@$r['instalacion_estado'])?'selected="selected"':''; ?> ><?php echo $e; ?></option>
										<?php endforeach;?>
									</select>
									<?php echo form_error('instalacion_estado');?>
					        	</div>
					        </div>
			      		</div>
			    	</div>
			  	</div>
			</div>
			<?php if($venta_directa):?>
			<br/>
			<div class="panel-group front_panel" id="accordion2">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">
							  Referencia
							</a>
					   </h4>
					</div>
					<div id="collapseTwo" class="panel-collapse collapse in">
						<div class="panel-body">
							<div class="row">
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
									<label>Buscar por:</label>
									<select class="form-control" id="referido_buscar_id">
										<option value=""><?php echo $this->config->item('empty_select'); ?></option>
										<option value="1">Nombre del Cliente</option>
										<option value="2">E-mail del Cliente</option>
										<option value="3">Direcci&oacute;n de Instalaci&oacute;n</option>
									</select>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
									<label>Distribuidor:</label>
									<select class="form-control <?php echo form_error('referido_distribuidor_id')?'rojo':'';?>" name="referido_distribuidor_id" id="distribuidores_id">
										<option value=""><?php echo $this->config->item('empty_select'); ?></option>
										<?php foreach($distribuidores as $k=>$v):?>
											<option value="<?php echo $k; ?>" <?php echo ($k == @$r['referido_distribuidor_id'])?'selected="selected"':''; ?>> <?php echo $v; ?></option>
										<?php endforeach;?>
									</select>
									<?php echo form_error('referido_distribuidor_id');?>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
									<label><span class="req">*</span>Nombre del Vendedor que referenci&oacute;:</label>
										<input class="form-control" type="text" id="referido_vendedor_nombre" name="referido_vendedor_nombre" value="<?php echo set_value('referido_vendedor_nombre',@$r['referido_vendedor_nombre']); ?>" />
									<?php echo form_error('referido_vendedor_nombre');?>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
									<label><span class="req">*</span>Apellido Paterno del Vendedor que referenci&oacute;:</label>
										<input class="form-control" type="text" id="referido_vendedor_paterno" name="referido_vendedor_paterno" value="<?php echo set_value('referido_vendedor_paterno',@$r['referido_vendedor_paterno']); ?>" />
									<?php echo form_error('referido_vendedor_paterno');?>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
										<label><span class="req">*</span>Apellido Materno del Vendedor que referenci&oacute;:</label>
										<input class="form-control" type="text" id="referido_vendedor_materno" name="referido_vendedor_materno" value="<?php echo set_value('referido_vendedor_materno',@$r['referido_vendedor_materno']); ?>" />
									<?php echo form_error('referido_vendedor_materno');?>
								</div>
								<!-- <div class="col-lg-3 form-group">
									<label>Vendedor:</label>
									<input class="form-control <?php echo form_error('referido_vendedor')?'rojo':'';?>" id="referido_vendedor"  name="referido_vendedor"  value="<?php echo set_value('referido_vendedor',@$r['referido_vendedor']); ?>"/>
									<?php echo form_error('referido_vendedor');?>
								</div> -->
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php endif; ?>
			<br/>
		<?php endif;?>

		<?php if(!empty($evento_estado)): ?>
			<div class="panel-group front_panel" id="accordionEventoEstado">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordionEventoEstado" href="#collapseEventoEstado">
								Estado donde se impartir&aacute; el servicio
							</a>
						</h4>
					</div>
					<div id="collapseEventoEstado" class="panel-collapse collapse in">
						<div class="panel-body">
							<div class="row">
								<div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 form-group">
									<label>Estado:</label>
									<select class="form-control evento_estado" name="evento_estado" id="evento_estado">
										<?php foreach($estados as $e):?>
											<option value="<?php echo $e; ?>" <?php echo set_select('evento_estado', $e, $e==$evento_estado); ?>><?php echo $e; ?></option>
										<?php endforeach;?>
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<div class="panel-group front_panel" id="accordion3">
		  	<?php $this->load->view('frontend/cotizacion_tabla',$calculo);?>
		</div>
		<?php if(($aplicar_cupon && $this->session->userdata('generar_cotizacion') && INTERNO) || ($aplicar_cupon && !$this->session->userdata('generar_cotizacion') && !INTERNO)):?>
		</br>
		<div class="panel-group front_panel" id="cupones">
			<div id="cupon_loader" class="ajax-loading" style="display: none;"></div>
			<div class="cupon" id="panel_cupones" <?php echo (isset($calculo['cupon']) && isset($calculo['cupones_id'])) || (isset($r['descuento_cupon']) && isset($r['cupones_id']))?'':'style="display: none;"'?>>
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordionCupon" href="#collapseCupon">
								Datos del Cup&oacute;n
							</a>
						</h4>
					</div>
					<div id="collapseCupon" class="panel-collapse collapse in">
						<div class="panel-body">
							<div class="row">
								<p class="msg cupon_done" title="De click para cerrar" style="display: none;">
									<span class="fa-stack fa-2x">
									  <i class="fa fa-circle fa-stack-2x text-color-blanco"></i>
									  <i class="fa fa-check fa-stack-1x text-color-done"></i>
									</span>
									El cup&oacute;n es v&aacute;lido. Por favor elija la opci&oacute;n que desea aplicar a la cotizaci&oacute;n.
								</p>
								<p class="msg cupon_error" style="display: none;">
									<span class="fa-stack fa-2x">
									  <i class="fa fa-circle fa-stack-2x text-color-blanco"></i>
									  <i class="fa fa-exclamation fa-stack-1x text-color-danger"></i>
									</span>
									El cup&oacute;n que ingres&oacute; no es v&aacute;lido. Verifique el folio e int&eacute;ntelo nuevamente.
								</p>
								<?php $folio_cupon= isset($calculo['folio_cupon'])?$calculo['folio_cupon']:@$r['folio_cupon'];?>
								<?php $opcion_cupon_id = isset($calculo['opcion_cupon_id'])?@$calculo['opcion_cupon_id']:@$r['opcion_cupon_id'];?>
								<div class="col-lg-3 form-group">
									<fieldset class="scheduler-border">
										<legend class="scheduler-border">Folio del Cup&oacute;n:</legend>
										<label <?php echo @$status_posterior?'style="opacity:0.4;"':'';?>>Folio</label>
										<input name="folio_cupon" value="<?php echo $folio_cupon;?>" type="text" id="folio_cupon" <?php echo $folio_cupon && $opcion_cupon_id?'class="form-control" readonly':'';?>/></br></br>
										<?php echo form_error('folio_cupon');?>
										<?php if(!$folio_cupon || ($folio_cupon && !$opcion_cupon_id)):?>
											<a class="btn front-btn-autorizar pull-right" href="<?php echo site_url('frontends/validar_cupon'); ?>" id="usar_cupon">Validar</a>
										<?php endif;?>
									</fieldset>
								</div>
								<div class="col-lg-5" <?php echo (isset($calculo['descuento_cupon']) && isset($calculo['cupones_id']) && isset($calculo['opcion_cupon_id'])) || (isset($r['descuento_cupon']) && isset($r['cupones_id']) && isset($r['opcion_cupon_id']))?'':'style="display: none;"'?> id="opciones">
									<fieldset class="scheduler-border" id="opcion">
										<?php if(@$cupones_opciones):?>
											<legend class="scheduler-border">Opciones:</legend>
												<?php foreach($cupones_opciones as $cp=>$label):?>
													<input class="alinear_radio opciones_id <?php echo $folio_cupon && $opcion_cupon_id?'form-control-1':'';?>" type="radio" name="opcion_cupon_id" <?php echo $folio_cupon && $opcion_cupon_id?'readonly':'';?>
														value="<?php echo $cp;?>" <?php echo $opcion_cupon_id==$cp?'checked="checked"':'' ?>/>
														<label>&nbsp;<?php echo $label['label'];?></label></br>
												<?php endforeach; ?>
											<?php if(!$folio_cupon || ($folio_cupon && !$opcion_cupon_id)):?>
											<a class="btn front-btn-autorizar pull-right" href="#" id="aplicar_cupon">Aplicar</a>
											<?php endif;?>
											<a href="#productos_div" id="ancla"></a>
											<input type="hidden" id="cupones_id" name="cupones_id" value="<?php echo isset($calculo['cupones_id'])?$calculo['cupones_id']:@$r['cupones_id']?>"/>
										<?php endif;?>
									</fieldset>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php endif;?>
	    <?php if($this->session->userdata('generar_cotizacion')):?>
		<br/>
			<?php if(INTERNO):?>
			<div class="panel-group front_panel" id="accordion5">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion5" href="#collapseFive">
							Informaci&oacute;n del Vendedor
							</a>
						</h4>
					</div>
					<div id="collapseFive" class="panel-collapse collapse in">
						<div class="panel-body">
							<div class="row">
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
									<label><span class="req">*</span>Nombre del Vendedor:</label>
									<?php $vendedor_id=$cotizaciones_id?@$r['usuario_id']:$this->session->userdata('id');?>
									<input type="hidden" name="usuario_id" value="<?php echo $vendedor_id;?>"/>
									<input class="form-control <?php echo form_error('vendedor_nombre')?'rojo':'';?>" type="text" id="vendedor_nombre" name="vendedor_nombre" value="<?php echo set_value('vendedor_nombre',@$r['vendedor_nombre']); ?>" />
									<?php echo form_error('vendedor_nombre');?>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
									<label><span class="req">*</span>Apellido Paterno del Vendedor:</label>
									<input class="form-control <?php echo form_error('vendedor_paterno')?'rojo':'';?>" type="text" id="vendedor_paterno" name="vendedor_paterno" value="<?php echo set_value('vendedor_paterno',@$r['vendedor_paterno']); ?>" />
									<?php echo form_error('vendedor_paterno');?>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
									<label><span class="req">*</span>Apellido Materno del Vendedor:</label>
									<input class="form-control <?php echo form_error('vendedor_materno')?'rojo':'';?>" type="text" id="vendedor_materno" name="vendedor_materno" value="<?php echo set_value('vendedor_materno',@$r['vendedor_materno']); ?>" />
									<?php echo form_error('vendedor_materno');?>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
									<label>Observaciones:</label>
									<textarea class="form-control <?php echo form_error('observaciones')?'rojo':'';?>" id="observaciones" name="observaciones" rows="6"><?php echo set_value('observaciones',@$r['observaciones']); ?></textarea>
									<?php echo form_error('observaciones');?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php endif;?>

			<div class="front_btnsWrapper mt20 mb20">
				<?php switch($paso):
					  case 'nueva': ?>
						  <input type="hidden" name="ofrecio_evento" id="ofrecio_evento" />
						  <a class="btn btn-front-default pull-right" href="<?php echo site_url('frontends/cotizacion_nueva/'); ?>">Cancelar</a>
					  <?php break; ?>
					  <?php case 'edicion': ?>
						  <a class="btn btn-front-default pull-right" href="<?php echo site_url('frontends/cancelar_edicion'); ?>">Cancelar</a>
					  <?php break; ?>
				<?php endswitch; ?>
				<?php if(!empty($productos_sesion) || !empty($accesorios_individuales)):?>
				<button class="btn btn-front-primary pull-right" type="submit" id="generar_cotizacion">
					<?php switch($paso):
						  case 'nueva':
							  echo 'Generar cotizaci&oacute;n';
							  break;
						  case 'edicion':
							  echo 'Guardar cambios';
							  break;
					endswitch; ?>
				</button>
				<?php endif;?>
				<div class="clear"></div>
			</div>

		<?php else:?>
		<div class="front_btnsWrapper mt20 mb20">
			<?php if(count($productos_sesion) > 0 || count($accesorios_individuales) > 0):?>
				<?php if(INTERNO):?>
					<a class="btn btn-front-primary pull-right" href="<?php echo site_url('frontends/generar_cotizacion'); ?>"><?php echo 'Generar cotizaci&oacute;n';?></a>
				<?php else:?>
					<?php //$url_cupon = @$r['folio_cupon'] && @$r['cupones_id']?"/{$r['descuento_cupon']}/{$r['cupones_id']}/{$r['folio_cupon']}/{$r['opcion_cupon_id']}":'';?>
					<a class="btn btn-front-primary pull-right"  id="comprar"><?php echo 'Comprar';?></a>
				<?php endif;?>
			<?php endif;?>
			<a class="btn btn-front-default pull-right" href="<?php echo site_url('frontends/index'); ?>"><?php echo INTERNO?'Agregar m&aacute;s productos':'Seguir comprando';?></a>
			<a class="btn btn-front-default pull-right" href="<?php echo site_url('frontends/cotizacion_nueva'); ?>"><?php echo INTERNO?'Cancelar Cotizaci&oacute;n':'Vaciar carrito';?></a>
			<div class="clear"></div>
		</div>
		<?php endif;?>
	</form>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>js/bc/direccion.js"></script>
<script src="<?php echo base_url(); ?>js/alertify/alertify.min.js"></script>
<script type="text/javascript">
EXTERNO = <?php echo (!INTERNO)?1:0;?>;

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

function condiciones_comerciales()
{
	reset();
	alertify.set({ labels: { ok: 'Cerrar'}});
	alertify.alert('Su orden de compra ha sido actualizada de acuerdo a las condiciones comerciales vigentes.');
}

$(function(){
	var paso = '<?php echo @$paso;?>';

	Direccion.set('');
	Direccion.set('entrega_');
	Direccion.set('instalacion_');
	convertir_campos('+3D','+6M');

	var eventos ='.p_cantidad,.acc_cantidad,.envio,.descuento_opcional,.descuento_paquete,.acc_individual_cantidad,#promociones_id,.promocion_msi,.evento_estado';
	<?php if(@$folio_cupon && @$opcion_cupon_id):?>
	$('#form').on("keyup keypress click", "#folio_cupon, .opciones_id, #cupon", function(e) {
		e.preventDefault();
		return false;
	});
	<?php else:?>
		eventos +=',#cupon';
	<?php endif;?>

	$('.container').on('click','#promocion_msi_banamex', function(){$('#promocion_msi_amex').attr('checked',false);});
	$('.container').on('click','#promocion_msi_amex', function(){$('#promocion_msi_banamex').attr('checked',false);});

	$('.container').on('change',eventos,function(e) {

		$.blockUI({message: '<h1><img src="<?php echo site_url('img/recalculando.gif'); ?>" /></h1>'});

		var productos_ids= new Array();
		var accesorios_ids= new Array();
        var acc_individuales_ids= new Array();
		var productos_cantidades=new Array();
		var accesorios_cantidades=new Array();
        var acc_individuales_cantidades=new Array();
		var entrega_estado=$('#entrega_estado').val();
		var instalacion_estado=$('#instalacion_estado').val();
		var descuento_opcional=$('#descuento_opcional').is(':checked')?1:0;
		var descuento_paquete=$('#descuento_paquete').is(':checked')?1:0;
        var cotizaciones_id = <?php echo @$cotizaciones_id?$cotizaciones_id:0;?>;
		var cupones_id=$('#cupones_id').val();
		var descuento_cupon=$('#cupon').is(':checked')?1:0;
		var rescate_sucursal=$('#rescate').is(':checked')?1:0;
		var folio_cupon=$('#folio_cupon').val();
		var opcion_cupon_id = 0;
		var	promociones_id = $('#promociones_id').val();
		var evento_estado = $('#evento_estado').val();

		var promocion_msi=0;
		if($('#promocion_msi_amex').is(':checked'))
		{
			promocion_msi=12;
			$('#descuento_opcional').attr('checked',false);
			descuento_opcional=0;
			$('#descuento_opcional').attr('disabled');

			$('#descuento_paquete').removeAttr('checked');
			descuento_paquete=0;
			$('#descuento_paquete').attr('disabled');

			$('#cupon').removeAttr('checked');
			descuento_cupon=0;
			$('#cupon').attr('disabled');

			//$('#promocion_msi_banamex').removeAttr('checked');
		}
		if($('#promocion_msi_banamex').is(':checked'))
		{
			promocion_msi=18;
			$('#descuento_opcional').attr('checked',false);
			descuento_opcional=0;
			$('#descuento_opcional').attr('disabled');

			$('#descuento_paquete').removeAttr('checked');
			descuento_paquete=0;
			$('#descuento_paquete').attr('disabled');

			$('#cupon').removeAttr('checked');
			descuento_cupon=0;
			$('#cupon').attr('disabled');


			//$('#promocion_msi_amex').removeAttr('checked');
		}

		$('.opciones_id').each(function(){
			if($(this).is(':checked'))
				opcion_cupon_id=$(this).val();
		});

        if(EXTERNO)
        {
            descuento_opcional =1;
            descuento_paquete =1;
        }

		var status=0;
		<?php if(isset($status)):?>
		status=<?php echo $status;?>;
		<?php endif;?>
		<?php foreach($productos_sesion as $k => $p):?>
			productos_ids.push('<?php echo $p->id;?>');
			<?php foreach($p->accesorios as $acc=>$a):?>
				accesorios_ids[<?php echo $p->id;?>]='<?php echo $a->id;?>';
			<?php endforeach;?>
		<?php endforeach;?>

        <?php foreach($accesorios_individuales as $ai):?>
            acc_individuales_ids.push('<?php echo @$cotizaciones_id?$ai->accesorios_id:$ai->id;?>');
        <?php endforeach;?>

		$('.p_cantidad').each(function(){
			var cantidad = $(this).val();
			if($(this).val()=='')
				cantidad=1;
			productos_cantidades.push(cantidad);
		});

		$('.acc_cantidad').each(function(){
			var cantidad = $(this).val();
			if($(this).val()=='')
				cantidad=1;
			accesorios_cantidades.push(cantidad);
		});

        $('.acc_individual_cantidad').each(function(){
			var cantidad = $(this).val();
			if($(this).val()=='')
				cantidad=1;
            acc_individuales_cantidades.push(cantidad);
        });

		$('#accordion3').load(BASE_URL+'frontends/cotizacion_calculo',
			{ productos_ids : productos_ids, productos_cantidades : productos_cantidades,
			  accesorios_ids : accesorios_ids, accesorios_cantidades : accesorios_cantidades,
              acc_individuales_ids : acc_individuales_ids, acc_individuales_cantidades : acc_individuales_cantidades,
			  entrega_estado: entrega_estado, descuento_opcional : descuento_opcional, status: status,
			  descuento_paquete : descuento_paquete, instalacion_estado : instalacion_estado,
              cotizaciones_id : cotizaciones_id, cupones_id : cupones_id, descuento_cupon : descuento_cupon, rescate_sucursal : rescate_sucursal,
			  folio_cupon : folio_cupon, opcion_cupon_id : opcion_cupon_id, promociones_id : promociones_id, promocion_msi : promocion_msi, evento_estado : evento_estado
			},
			function(){
				$.unblockUI();
				var promocion_change = $('#promocion_change').val();
				if(promocion_change=='1')
				{
					var ruta='<?php echo site_url('frontends/promocion_elegir/'.@$cotizaciones_id)?>';
					$.fancybox(
					{
						autoSize 	: false,
						autoHeight 	: true,
						width       : '80%',
						openEffect	: 'none',
						closeEffect	: 'none',
						href		: ruta,
						type		: 'ajax',
						closeBtn 	: false,
						enableEscapeButton: false
					});
				}
			}
		);
	});

	$('.buscar_ref').on('click', function(e) {
		e.preventDefault();
	    var email = $('input[name=email_comprador]').val();
	    $.ajax({
	        url: '<?php echo site_url('referidos/buscar') ?>',
	        dataType: 'json',
	        data: {email : email},
	        type: 'post',
	        success: function(data) {
		        if(data.distribuidor) {
		            $('#distribuidores_id').empty();
		            $('#distribuidores_id').append('<option value="'+data.distribuidores_id+'" selected="selected">'+data.distribuidor+'</option>');
		            if(data.vendedor) {
    		            $('#referido_vendedor').val('');
    		    		$('#referido_vendedor').val(data.vendedor);
		            }
				}
		    }
		})
	});

	$('#generar_cotizacion').on('click', function(e) {
		var callback = function(error, success){
			if(error){
				return false;
			}

			reset();
			alertify.set({ labels: { ok: 'Aceptar', cancel: 'Rechazar' } });
			var productos_ids= new Array();
			var accesorios_ids= new Array();
			var acc_individuales_ids= new Array();
			var productos_cantidades=new Array();
			var accesorios_cantidades=new Array();
			var acc_individuales_cantidades=new Array();
			var entrega_estado=$('#entrega_estado').val();
			var instalacion_estado=$('#instalacion_estado').val();
			var descuento_opcional=<?php echo $this->session->userdata('promocion_opcional')?0:1;?>;//$('#descuento_opcional').is(':checked')?1:0;
			var descuento_paquete=<?php echo $this->session->userdata('promocion_opcional')?0:1;?>;//$('#descuento_paquete').is(':checked')?1:0;
			var cotizaciones_id = <?php echo @$cotizaciones_id?$cotizaciones_id:0;?>;
			var cupones_id=$('#cupones_id').val();
			var descuento_cupon=$('#cupon').is(':checked')?1:0;
			var rescate_sucursal=$('#rescate').is(':checked')?1:0;
			var folio_cupon=$('#folio_cupon').val();
			var opcion_cupon_id = 0;
			var promociones_id = $('#promociones_id').val();
			var promocion_msi=0;
			var evento_estado = $('#evento_estado').val();
			if($('#promocion_msi_amex').is(':checked'))
			{
				promocion_msi=12;
				$('#descuento_opcional').attr('checked',false);
				descuento_opcional=0;
				$('#descuento_opcional').attr('disabled');

				$('#descuento_paquete').removeAttr('checked');
				descuento_paquete=0;
				$('#descuento_paquete').attr('disabled');

				$('#cupon').removeAttr('checked');
				descuento_cupon=0;
				$('#cupon').attr('disabled');
			}
			if($('#promocion_msi_banamex').is(':checked'))
			{
				promocion_msi=18;
				$('#descuento_opcional').attr('checked',false);
				descuento_opcional=0;
				$('#descuento_opcional').attr('disabled');

				$('#descuento_paquete').removeAttr('checked');
				descuento_paquete=0;
				$('#descuento_paquete').attr('disabled');

				$('#cupon').removeAttr('checked');
				descuento_cupon=0;
				$('#cupon').attr('disabled');
			}

			$('.opciones_id').each(function(){
				if($(this).is(':checked'))
					opcion_cupon_id=$(this).val();
			});


			var status=0;
			<?php if(isset($status)):?>
			status=<?php echo $status;?>;
			<?php endif;?>
			<?php foreach($productos_sesion as $k => $p):?>
			productos_ids.push('<?php echo $p->id;?>');
			<?php foreach($p->accesorios as $acc=>$a):?>
			accesorios_ids[<?php echo $p->id;?>]='<?php echo $a->id;?>';
			<?php endforeach;?>
			<?php endforeach;?>

			<?php foreach($accesorios_individuales as $ai):?>
			acc_individuales_ids.push('<?php echo @$cotizaciones_id?$ai->accesorios_id:$ai->id;?>');
			<?php endforeach;?>

			$('.p_cantidad').each(function(){
				productos_cantidades.push($(this).val());
			});

			$('.acc_cantidad').each(function(){
				accesorios_cantidades.push($(this).val());
			});

			$('.acc_individual_cantidad').each(function(){
				acc_individuales_cantidades.push($(this).val());
			});

			$.post(
				BASE_URL+'frontends/cotizacion_calculo/0/1',
				{	  productos_ids : productos_ids, productos_cantidades : productos_cantidades,
					accesorios_ids : accesorios_ids, accesorios_cantidades : accesorios_cantidades,
					acc_individuales_ids : acc_individuales_ids, acc_individuales_cantidades : acc_individuales_cantidades,
					entrega_estado: entrega_estado, descuento_opcional : descuento_opcional, status: status,
					descuento_paquete : descuento_paquete, instalacion_estado : instalacion_estado,
					cotizaciones_id : cotizaciones_id, cupones_id : cupones_id, descuento_cupon : descuento_cupon, rescate_sucursal : rescate_sucursal,
					folio_cupon : folio_cupon, opcion_cupon_id : opcion_cupon_id, promociones_id : promociones_id, promocion_msi : promocion_msi, evento_estado : evento_estado
				},
				function(data){

					var sigue = false;
					var descuento_cliente = false;
					var descuento_paquete = data.paquete_adquirido?data.paquete_adquirido.descuento:0;
					var descuento_cliente_porcentaje=0;
					if(data.descuentos_cliente)
					{
						$.each(data.descuentos_cliente,function(i,item){
							if(item.cliente)
							{
								descuento_cliente = true;
								if(eval(item.cliente) > descuento_cliente_porcentaje)
									descuento_cliente_porcentaje=item.cliente;
							}
						});
					}
					if(!$('#descuento_opcional').is(':checked') && descuento_cliente)
					{
						if($('#descuento_paquete').is(':checked'))
						{
							if(eval(descuento_cliente_porcentaje) > eval(descuento_paquete))
							{
								alertify.confirm('Esta cotización incluye un producto con descuento autorizado por Miele al cliente final ¿Desea aplicar este descuento?', function(a) {
									if(a) {
										$('#descuento_opcional').attr('checked',true);
									}
									$('#form').submit();
								});
							}
							else
								$('#form').submit();
						}
						else
						{
							if(eval(descuento_cliente_porcentaje) > eval(descuento_paquete))
							{
								alertify.confirm('Esta cotización incluye un producto con descuento autorizado por Miele al cliente final ¿Desea aplicar este descuento?', function(a) {
									if(a) {
										$('#descuento_opcional').attr('checked',true);
									}

									if(!$('#descuento_paquete').is(':checked') && descuento_paquete)
									{
										alertify.confirm('Esta cotización incluye equipos que conforman un paquete con descuento autorizado por Miele al cliente final ¿Desea aplicar este descuento?', function(b) {
											if(b) {
												$('#descuento_paquete').attr('checked',true);
											}
											$('#form').submit();
										});
									} else {
										$('#form').submit();
									}
								});
							}
							else
							{
								if(!$('#descuento_paquete').is(':checked') && descuento_paquete)
								{
									alertify.confirm('Esta cotización incluye equipos que conforman un paquete con descuento autorizado por Miele al cliente final ¿Desea aplicar este descuento?', function(b) {
										if(b) {
											$('#descuento_paquete').attr('checked',true);
										}
										$('#form').submit();
									});
								}
							}
						}
					}
					else if(!$('#descuento_paquete').is(':checked') && descuento_paquete)
					{
						alertify.confirm('Esta cotización incluye equipos que conforman un paquete con descuento autorizado por Miele al cliente final ¿Desea aplicar este descuento?', function(a) {
							if(a) {
								$('#descuento_paquete').attr('checked',true);
							}
							$('#form').submit();
						});
					} else
						$('#form').submit();
				},'json');
		};

		e.preventDefault();
		var input_submit = $(this);
		input_submit.attr('disabled', 'disabled');
		input_submit.html('').css('background-color','#E60000');
		input_submit.html("<i style='color:#fffafe; font-size: 15px;' class='fa fa-spinner fa-spin'/>");
		if(paso == 'nueva')
			ofrecio_evento(callback);
		else
			callback(null, true);
	});

	$('.container').on('change','#referido_buscar_id',function(){
		var id=$(this).val();
		if(id!='')
		{
			var ruta='<?php echo site_url('referidos/referidos_buscar')?>/'+id;
			$.fancybox(
			{
				autoSize 	: false,
				autoHeight 	: true,
				width       : '80%',
				openEffect	: 'none',
				closeEffect	: 'none',
				href		: ruta,
				type		: 'iframe'
			});
		}
	});

	function ver_cupon()
	{
		if($('#cupon').is(':checked'))
			$('#panel_cupones').show();
		else
		{
			$('#panel_cupones').hide();
			$('#opciones').hide();
		}
	}

	<?php if(!@$folio_cupon && !@$opcion_cupon_id):?>
	$('#accordion3').on('change', '#cupon', function(){
		ver_cupon();
	});
	<?php endif;?>

	$('#cupones').on('click', '#usar_cupon', function(e){
		e.preventDefault();
		$('.cupon').hide();
		$('#opcion').html('');
		$('.cupon_done').hide();
		$('.cupon_error').hide();
		$('#cupon_loader').show();
		$('#opciones').hide();
		var folio_cupon = $('#folio_cupon').val();
		$.post(
			BASE_URL+'frontends/validar_cupon', { folio_cupon : folio_cupon },
			function(data)
			{
				data = JSON.parse(data);
				if(data.valido)
				{
					$('#opcion').append('<legend class="scheduler-border">Opciones:</legend>');
					$.each(data.opciones,function(i, item){
						$('#opcion').append('<input class="alinear_radio opciones_id" type="radio" name="opcion_cupon_id" value="'+i+'" /><label for="'+i+'">&nbsp;'+item.label+'</label></br>');
					});
					$('#opcion').append('<a class="btn front-btn-autorizar pull-right" href="#" id="aplicar_cupon">Aplicar</a>');
					$('#opcion').append('<input type="hidden" id="cupones_id" name="cupones_id" value="'+ data.cupones_id +'"/>');
					$('.cupon_done').show();
					$('.cupon').show();
					$('#cupon_loader').hide();
					$('#opciones').show();
				}
				else
				{
					$('#opcion').html('');
					$('#opciones').hide();
					$('.cupon_error').show();
					$('.cupon').show();
				}
				$('.p_cantidad').trigger('change');
			}
		);
		return false;
	});


	$('#cupones').on('click', '#aplicar_cupon', function(e){
		e.preventDefault();
		if($('.p_cantidad').length)
			$('.p_cantidad').trigger('change');
		else
			$('.acc_individual_cantidad').trigger('change');

		$('html, body').animate({
			scrollTop: $('#accordion2').length?$('#accordion2').offset().top:$('#accordion').offset().top
		}, 1000);

		return false;
	});
	<?php if(!@$folio_cupon && !@$opcion_cupon_id):?>
		ver_cupon();
	<?php endif;?>

	condiciones_comerciales();
});

$(document).ready(function(){
    $('#comprar').on("click", function(){
            alertify.set({ labels: { ok: 'Aceptar' } });
            alertify.alert('Estamos trabajando para darte un mejor servicio, por ello la entrega de tu pedido se programara a partir del día 18 de julio, enviándote una confirmación de entrega vía correo electrónico.', function(a) {
                                                location.href = "<?php echo site_url('frontends/informacion_cliente'); ?>";
                                    });
    });
});

</script>
<?php $this->load->view('frontend/layout/footer');?>