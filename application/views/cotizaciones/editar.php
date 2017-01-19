<?php if(!is_ajax()){ $this->load->view('layout/header'); } ?>
<h1><?php echo $titulo; ?></h1>
<p class="msg info">Los campos requeridos est&aacute;n marcados con <span class="req">*</span></p>
<form action="<?php echo site_url(uri_string()); ?>" id="form" method="post" enctype="multipart/form-data">
<input name="id" value="<?php echo set_value('id',@$r['cotizacion']->id);?>" type="hidden" />
<div class="panel panel_up" title="De click para mostrar u ocultar la secci&oacute;n.">Informaci&oacute;n general</div>
<div>
<table class="table_form">
<tbody>
	<tr class="altrow" >
		<td>
			<label><span class="req">*</span>Fecha:</label>
				<input id="fecha_alt" type="hidden" name="fecha" value="<?php echo set_value('fecha',@$r['cotizacion']->fecha); ?>" readonly="readonly"/>
				<input id="fecha"  class="fecha"  value="<?php ver_fecha(set_value('fecha',@$r['cotizacion']->fecha)); ?>" />
			<?php echo form_error('fecha');?>
		</td>
		<td>&nbsp;</td>
	</tr>
	</tbody>
</table>
</div>
<div class="panel panel_up" title="De click para mostrar u ocultar la secci&oacute;n.">Datos fiscales</div>
<div>
<table class="table_form">
<tbody>
	<tr>
		<td>
			<label><span class="req">*</span>Nombre o Raz&oacute;n social:</label>
			<input id="razon_social"  name="razon_social"  value="<?php echo set_value('razon_social',@$r['cotizacion']->razon_social); ?>"/>
			<?php echo form_error('razon_social');?>
		</td>
		<td>&nbsp;</td>	
	</tr>
	<tr class="altrow">
		<td>
			<label>Apellido paterno:</label>
			<input id="apellido_paterno"  name="apellido_paterno"  value="<?php echo set_value('apellido_paterno',@$r['cotizacion']->apellido_paterno); ?>"/>
			<?php echo form_error('apellido_paterno');?>
		</td>
		<td>
			<label>Apellido materno:</label>
			<input id="apellido_materno"  name="apellido_materno"  value="<?php echo set_value('apellido_materno',@$r['cotizacion']->apellido_materno); ?>"/>
			<?php echo form_error('apellido_materno');?>
		</td>	 
	</tr>
	<tr>
		<td>
			<label>Correo electr&oacute;nico:</label>
			<input id="email"  name="email"  value="<?php echo set_value('email',@$r['cotizacion']->email); ?>"/>
			<?php echo form_error('email');?>
		</td>
		<td>
			<label>Tel&eacute;fono:</label>
			<input id="telefono"  name="telefono"  value="<?php echo set_value('telefono',@$r['cotizacion']->telefono); ?>"/>
			<?php echo form_error('telefono');?>
		</td>
	</tr>
	</tbody>
</table>
</div>
<div class="panel panel_up" title="De click para mostrar u ocultar la secci&oacute;n.">Productos <?php echo form_error('productos[]');?></div>
<div>
	<a class="boton_a_azul fr" href="<?php echo site_url('cotizaciones/agregar_productos');?>" id="productos_add">Agregar producto(s)</a>
	<br/>
	<br/>
	<table class="data tr_over" id="t_productos">
	<thead>
		<tr>
			<td class="data_first">Concepto</td>						
			<td>Precio unitario ($)</td>
			<td>Cantidad</td>
			<td>Total ($)</td>
			<td>Acciones</td>
		</tr>
	</thead>
		<tbody>	
		<?php if(!empty($productos_recuperados)):?>
			<?php $data2['p']=$productos_recuperados; ?>
			<?php $this->load->view('cotizaciones/item',$data2);?>
			<?php else:?>
				<tr style="display: none">
					<td colspan="4">&nbsp;</td>
				</tr>	
			<?php endif;?>		
		</tbody>
	</table>
</div>
<div class="panel panel_up" title="De click para mostrar u ocultar la secci&oacute;n.">Monto</div>
<div>
<table class="table_form">
<tbody>	
	<tr class="altrow">
		<td class="altrow" >
			<label>Subtotal M.N:</label>
				<div class="div_info" id="subtotal" ></div>
		</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>
			<label><span class="req">*</span>I.V.A. ($):</label>
			<div class="div_info" id="iva"><?php echo moneda(set_value('iva',@$r['iva'])); ?></div>
			<?php echo form_error('iva');?>	
		</td>	
		<td>
			<label><span class="req">*</span>Porcentaje de I.V.A. (%):</label>
			<input id="iva_porcentaje"  name="iva_porcentaje" readonly="readonly"  value="<?php echo set_value('iva_porcentaje',@$r['cotizacion']->iva_porcentaje); ?>"/>
			<?php echo form_error('iva_porcentaje');?>
		</td>
	</tr>	
	<tr class="altrow">
		<td>
			<label>Total M.N:</label>
				<div class="div_info" id="total" ></div>
		</td>
		<td>&nbsp;</td>
	</tr>	
</tbody>
</table>
</div>

<!-- INICIA DATOS DEL VENDEDOR -->
<div class="panel panel_up" title="De click para mostrar u ocultar la secci&oacute;n.">DATOS DEL VENDEDOR</div>
<div>
<table class="table_form">
	<tbody>	
	<tr class="altrow">
		<td>
			<label><span class="req">*</span>Nombre del Vendedor:</label>
			<select name="vendedor" id="vendedor" disabled="disabled">
				<option value=""><?php echo $this->config->item('empty_select');?></option>
				<?php foreach($vendedores as $v):?>
				<option value="<?php echo $v['id']; ?>"  <?php echo ($v['id'] == @$r['cotizacion']->usuario_id)?'selected="selected"':''; ?>><?php echo $v['nombre'].' '.$v['apellido_paterno'].' '.$v['apellido_materno']; ?></option>
				<?php endforeach;?>
			</select>
			<?php echo form_error('vendedor');?>
		</td>
	</tr>
	</tbody>
</table>			
</div>
<!-- FIN DE DATOS DEL VENDEDOR -->

<div class="panel panel_up" title="De click para mostrar u ocultar la secci&oacute;n.">Observaciones</div>
<div>
<table class="table_form">
	<tbody>	
	<tr class="altrow">
		<td align="center">
			<textarea id="observaciones" name="observaciones" class="w90p" rows="6"><?php echo set_value('observaciones',@$r['cotizacion']->observaciones); ?></textarea>
			<?php echo form_error('observaciones');?>
		</td>
	</tr>
	</tbody>
</table>			
</div>
<div class="footer_form">		
	<span class="boton fl"><input type="submit" value="Guardar" id="guardar" /></span>
	<a href="<?php echo site_url('cotizaciones/index');?>" class="fl p5 ml5">Cancelar</a>
	<div class="clearblock">&nbsp;</div>
</div>
</form>
<script type="text/javascript" src="<?php echo base_url(); ?>js/bc/direccion.js"></script>
<script type="text/javascript">
<!--
$(function(){
	Direccion.set('');
	$('#cuentas_id').change(function(){
		$.ajax({ 
			url: BASE_URL+'main/get_datos_fiscales/'+this.value,
			dataType:'json', 
			success: function(data){
	        	$('#razon_social').val(data.razon_social);
	        	$('#rfc').val(data.rfc);
	        	$('#estado').val(data.estado);
	        	$('#estado').trigger('change');
				Direccion.get_municipios('',data.municipio);
	        	$('#codigo_postal').val(data.codigo_postal);
	        	$('#asentamiento').val(data.asentamiento);
	        	$('#calle').val(data.calle);
	        	$('#numero_exterior').val(data.numero_exterior);
	        	$('#numero_interior').val(data.numero_interior);
	        	$('#email').val(data.email);
	        	$('#telefono').val(data.telefono);
	        	$('#clave').val(data.clave);
			}	
	    });
	});
	$('#cuentas_id').change();
	$('#productos_add').click(function(e){
		e.preventDefault();
		$.fn.colorbox({
			href: this.href,
			width: 600,
			height:600,
			onClosed: function(){
				$('.agregar_p').die('click');
				$('.item_dato').die('change');
				$('.item_dato').live('change',calcular);
				calcular();
			},
			onOpen:function(){
				$('.agregar_p').live('click',function(e,a){
					e.preventDefault();
					$.get(
						this.href,
						function(data){
							$('#t_productos tbody').append(data);	
						}
					);
					$(this).closest('tr').remove();
				});
			}
		});
	});
	
	$('.del_item').live('click',function(e){
		e.preventDefault();
		$(this).closest('tr').remove();
	});

	function calcular()
	{
	
		$('.item_el').each(function(el){
			var fila=get_tr(this);
			var unitario = get_td_value('t_productos', 1, fila);
			var cantidad = get_td_value('t_productos', 2, fila);
			var total=(cantidad*unitario);
			set_td_value('t_productos', 3, fila, moneda(total));
		});

		var gran_total_p=sumar('suma_precio',false);
		$('#subtotal').html(moneda(gran_total_p));
		var descuento=<?php echo $this->config->item('descuento')?$this->config->item('descuento'):0;?>;
		var monto_descuento=<?php echo $this->config->item('monto_descuento')?$this->config->item('monto_descuento'):99999999999999999999999999999999999999999;?>;
		if(gran_total_p>=descuento)
		{	
			cdescuento=(eval(gran_total_p)*eval(monto_descuento)).toFixed(2);
			$('#descuento').html(moneda(cdescuento));
			gran_total_p=(eval(gran_total_p)-eval(cdescuento)).toFixed(2);
		}
		
		$('#subtotal_d').html(moneda(gran_total_p));

		var iva_porcentaje=$('#iva_porcentaje').val();
		if(!isNaN(iva_porcentaje) && iva_porcentaje!='')
		{
			var iva=(eval(gran_total_p)*(eval(iva_porcentaje)/100)).toFixed(2);
			$('#iva').html(moneda(iva));

			var total=(eval(gran_total_p)+eval(iva)).toFixed(2);
			$('#total').html(moneda(total));
		}else
		{
			var iva_porcentaje=<?php echo $this->config->item('iva_porcentaje');?>;
			$('#iva_porcentaje').val(iva_porcentaje);
		}	
		
	}

	$('.item_dato').live('change',calcular);
	$('#iva_porcentaje,#subtotal').change(function(){
		calcular();
	});
	calcular();
});
//-->
</script>
<?php if(!is_ajax()){ $this->load->view('layout/footer'); } 