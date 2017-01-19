<?php $this->load->view('frontend/layout/header');?>
<div class="mb60 minHeight">
	<form action="<?php echo site_url('frontends/informacion_cliente');?>" id="form" method="post" enctype="multipart/form-data" class="text-uppercase">
		<?php $this->load->view('frontend/shopline/direccion_envio');?>
		<?php $this->load->view('frontend/shopline/direccion_instalacion');?>
		<?php $this->load->view('frontend/shopline/datos_facturacion');?>
		<input type="hidden" name="cupones_id" value="<?php echo @$cupon['cupones_id'];?>"/>
		<input type="hidden" name="folio_cupon" value="<?php echo @$cupon['folio_cupon'];?>"/>
		<input type="hidden" name="opcion_cupon_id" value="<?php echo @$cupon['opcion_cupon_id'];?>"/>
		<input type="hidden" name="descuento_cupon" value="<?php echo @$cupon['descuento_cupon'];?>"/>
		<input type="hidden" name="producto_regalo_id" value="<?php echo @$cupon['producto_regalo_id'];?>"/>
		<div class="front_btnsWrapper mt20 mb100">
			<button class="btn btn-front-primary pull-right" type="submit" id="generar_cotizacion">Guardar y continuar</button>
		</div>
	</form>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>js/bc/direccion.js"></script>
<script type="text/javascript">
$(function(){
	Direccion.set('');
	Direccion.set('entrega_');
	Direccion.set('instalacion_');
	convertir_campos('+3D','+6M');

	$('#datos_envio').click(function(e){/*Solicitante al inmueble*/
		e.preventDefault();
		if(confirm('¿Seguro qué desea copiar la dirección de envío a los datos de Facturación?'))
		{
			$('#razon_social').val($('#nombre_contacto').val());
			$('#telefono').val($('#telefono_particular').val());
			$('#estado').val($('#entrega_estado').val());
			Direccion.get_municipios('',$('#entrega_municipio').val());
			$('#asentamiento').val($('#entrega_asentamiento').val());
			$('#codigo_postal').val($('#entrega_codigo_postal').val());
			$('#calle').val($('#entrega_calle').val());
			$('#numero_exterior').val($('#entrega_numero_exterior').val());
			$('#numero_interior').val($('#entrega_numero_interior').val());
			$('#cambio').val('1');
			//$('#rfc').val('');
			//$('#email').val('');
			
		}
		return false;
	});

	$('#datos_instalacion').click(function(e){
		e.preventDefault();
		if(confirm('¿Seguro qué desea copiar la dirección de envío a los datos de Instalación?'))
		{
			$('#instalacion_nombre_contacto').val($('#nombre_contacto').val());
			$('#instalacion_telefono_particular').val($('#telefono_particular').val());
			$('#instalacion_telefono_celular').val($('#telefono_celular').val());
			$('#instalacion_estado').val($('#entrega_estado').val());
			Direccion.get_municipios('instalacion_',$('#entrega_municipio').val());
			$('#instalacion_asentamiento').val($('#entrega_asentamiento').val());
			$('#instalacion_codigo_postal').val($('#entrega_codigo_postal').val());
			$('#instalacion_calle').val($('#entrega_calle').val());
			$('#instalacion_numero_exterior').val($('#entrega_numero_exterior').val());
			$('#instalacion_numero_interior').val($('#entrega_numero_interior').val());
			$('#cambio').val('1');
			//$('.envio').change();
			$('#instalacion_dir_search').trigger('click');
		}
		return false;
	});
});
</script>
<?php $this->load->view('frontend/layout/footer');?>