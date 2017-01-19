<?php if(!is_ajax()){ $this->load->view('layout/header'); } ?>
	<div class="col-lg-12 formulario-head">
		<div class="row">
			<div class="col-lg-11 col-sm-10 col-xs-9">
				<h4><?php echo $titulo;?></h4>
			</div>
			<div class="col-lg-1 col-sm-2 col-xs-3">
				<?php if($puede_agregar):?>
					<a role="button" href="<?php echo site_url($this->uri->segment(1).'/agregar');?>" class="btn_default_admin btn btn-xs">Agregar</a>
				<?php endif;?>
			</div>
		</div>
	</div>
<form action="<?php echo site_url(uri_string());?>" method="post" id="form">
	<div class="col-lg-12">
		<div class="row">
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				<label>Nombre del Cliente</label>
				<input id="cliente" name="cliente" class="form-control" value="<?php echo @$cond['cliente']; ?>" />
			</div>
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				<label>Email</label>
				<input id="email" name="email" class="form-control" value="<?php echo @$cond['email']; ?>" />
			</div>
			<?php if($mostrar_por_distribuidor):?>
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				<label>Distribuidor:</label>
				<select class="form-control" name="distribuidores_id" id="distribuidores_id">
					<option value=""><?php echo $this->config->item('empty_select'); ?></option>
					<?php foreach($distribuidores as $k=>$v):?>
						<option value="<?php echo $k; ?>" <?php echo ($k == @$cond['distribuidores_id'])?'selected="selected"':''; ?>> <?php echo $v; ?></option>
					<?php endforeach;?>
				</select>
			</div>
			<?php endif;?>
		    <?php if(!$mostrar_propios || ($mostrar_por_distribuidor || $mostrar_todos)):?>
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				<label>Vendedor:</label>
				<input id="vendedor" name="vendedor" class="form-control" value="<?php echo @$cond['vendedor']; ?>" />
			</div>
			<?php endif;?>
		
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
	        	<label> Estado:</label>
				<select class="form-control" name="instalacion_estado" id="instalacion_estado">
					<option value=""><?php echo $this->config->item('empty_select');?></option>
				<?php foreach($estados as $e):?>
					<option value="<?php echo $e; ?>" <?php echo set_select('instalacion_estado', $e, ($e == @$cond['instalacion_estado'])); ?> ><?php echo $e; ?></option>
				<?php endforeach;?>
				</select>
				<?php echo form_error('instalacion_estado');?>
	        </div>
	        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
	       		<label> Delegaci&oacute;n o municipio:</label>
				<select class="form-control" name="instalacion_municipio" id="instalacion_municipio" >
					<option value=""><?php echo $this->config->item('empty_select');?></option>
					<?php foreach($municipios_instalacion as $mpio):?>
						<option value="<?php echo $mpio; ?>" <?php echo set_select('instalacion_municipio', $mpio, ($mpio == @$cond['instalacion_municipio'])); ?>><?php echo $mpio; ?></option>
					<?php endforeach;?>
				</select>
				<?php echo form_error('instalacion_municipio');?>
	        </div>
	        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
	        	<label> C&oacute;digo postal:</label>
	        	<div class="input-group">
					<input class="form-control" type="text" id="instalacion_codigo_postal"  name="instalacion_codigo_postal"  value="<?php echo set_value('instalacion_codigo_postal',@$cond['instalacion_codigo_postal']); ?>"/>
	        		<span class="input-group-addon">
	    				<a  class="buscar_ref icono_info" title="Buscar direcci&oacute;n en base al c&oacute;digo postal." href="#" id="instalacion_dir_search"><i class="fa fa-search"></i></a>
	    			</span>
				</div>
				<?php echo form_error('instalacion_codigo_postal');?>
	       	</div>
	        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
	        	<label> Colonia:</label>
	        	<div class="input-group">
		       		<input class="form-control" type="text" id="instalacion_asentamiento"  name="instalacion_asentamiento"  value="<?php echo set_value('instalacion_asentamiento',@$cond['instalacion_asentamiento']); ?>"/>
			   		<span class="input-group-addon">
		    	       <a  class="buscar_ref icono_info" title="Buscar c&oacute;digo postal en base a la colonia" href="#" id="instalacion_cp_search"><i class="fa fa-search"></i></a>
		    	   	</span>
	    	   	</div>
				<?php echo form_error('instalacion_asentamiento');?>
	        </div>
	        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
	        	<label> Calle:</label>
	        	<input class="form-control" type="text" id="instalacion_calle"  name="instalacion_calle"  value="<?php echo set_value('instalacion_calle',@$cond['instalacion_calle']); ?>"/>
				<?php echo form_error('instalacion_calle');?>
	        </div>
	       	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
	       		<label> N&uacute;mero exterior:</label>
	       		<input class="form-control" type="text" id="instalacion_numero_exterior"  name="instalacion_numero_exterior"  value="<?php echo set_value('instalacion_numero_exterior',@$cond['instalacion_numero_exterior']); ?>"/>
				<?php echo form_error('instalacion_numero_exterior');?>
	       	</div>
	       	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
	       		<label>N&uacute;mero interior:</label>
	       		<input class="form-control" type="text" id="instalacion_numero_interior"  name="instalacion_numero_interior"  value="<?php echo set_value('instalacion_numero_interior',@$cond['instalacion_numero_interior']); ?>"/>
				<?php echo form_error('instalacion_numero_interior');?>
	        </div>
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				<label>Vigente:</label>
				<select class="form-control" name="vigente" id="vigente" >
					<option value=""><?php echo $this->config->item('empty_select');?></option>
					<?php foreach($si_no as $k=>$v):?>
						<option value="<?php echo $k; ?>" <?php echo set_select('instalacion_municipio', $k, ($k == @$cond['vigente'])); ?>><?php echo $v; ?></option>
					<?php endforeach;?>
				</select>
				<?php echo form_error('vigente');?>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				<label>Fecha inicial vigencia:</label>
				<input type="hidden" id="fecha_inicial_alt" name="fecha_inicial" value="<?php echo @$cond['fecha_inicial']; ?>"/>
				<input type="text" class="form-control fecha" id="fecha_inicial" value="<?php echo get_fecha(@$cond['fecha_inicial']); ?>"/>
				<?php echo form_error('fecha_inicial');?>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				<label>Fecha final vigencia:</label>
				<input type="hidden" id="fecha_final_alt" name="fecha_final" value="<?php echo @$cond['fecha_final']; ?>"/>
				<input type="text" class="form-control fecha" id="fecha_final" value="<?php echo get_fecha(@$cond['fecha_final']); ?>"/>
				<?php echo form_error('fecha_final');?>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				<label>Fecha inicial creaci&oacute;n:</label>
				<input type="hidden" id="fecha_inicial_creacion_alt" name="fecha_inicial_creacion" value="<?php echo @$cond['fecha_inicial_creacion']; ?>"/>
				<input type="text" class="form-control fecha" id="fecha_inicial_creacion" value="<?php echo get_fecha(@$cond['fecha_inicial_creacion']); ?>"/>
				<?php echo form_error('fecha_inicial_creacion');?>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				<label>Fecha final creaci&oacute;n:</label>
				<input type="hidden" id="fecha_final_creacion_alt" name="fecha_final_creacion" value="<?php echo @$cond['fecha_final_creacion']; ?>"/>
				<input type="text" class="form-control fecha" id="fecha_final_creacion" value="<?php echo get_fecha(@$cond['fecha_final_creacion']); ?>"/>
				<?php echo form_error('fecha_final_creacion');?>
			</div>
		</div>
	</div>
	<div class="col-lg-12 barra-btn">
		<div class="row">
			<div class="col-lg-12">
				<input type="hidden" name="exp" id="exp" value="0" />
				<button type="submit" class="btn btn-primary pull-right" onclick="exp.value='0';">Buscar</button>
				<?php if ($puede_exportar): ?>
					<button type="submit" class="btn btn-primary pull-left" onclick="exp.value='1';">Exportar</button>
				<?php endif; ?>
		  		<button type="reset" class="btn btn-default pull-right bc_clear">Limpiar</button>
			</div>
		</div>
	</div>	
</form>
<?php if($total==0): ?>
	<p class="msg sin_resultados">No se ha encontrado ning&uacute;n registro.</p>
<?php else: ?>
	<div class="col-lg-12">
		<div class="table-responsive">
			<table class="table table-hover">
				<thead>
					<tr>
						<td class="data_first">Id</td>
						<td>Distribuidor</td>
						<td>Nombre del Cliente</td>
						<td>E-mail del Cliente</td>
						<td>Vendedor</td>
						<td>Fecha referido</td>
						<td>Fecha vigencia</td>
						<td>Vigente</td>
						<td>Acciones</td>
					</tr>
				</thead>
				<tfoot>
					<td colspan="11" class="p0">
						<div class="col-lg-4 pull-left">
							<?php echo $paginador; ?>
						</div>
						<div class="col-lg-4 pull-right">
							<p class="pull-right"><?php echo $total>1?'Se encontraron '.$total.' resultados.':'Se encontr&oacute; 1 resultado.'?></p>
						</div>
					</td>
				</tfoot>
				<tbody>
					<?php $i=1; foreach($r as $ro):?>
						<?php $vigente = ($ro->vigente==2)?'style="color: #FF8080;"':''; ?>
					<tr <?php echo ($i%2==0)?'class="altrow"':''; ?>>
						<td><?php echo $ro->id; ?></td>
						<td align="center"><?php echo $ro->distribuidor_nombre; ?></td>
						<td><?php echo $ro->cliente_nombre_completo; ?></td>
						<td><?php echo $ro->email; ?></td>
						<td><?php echo $ro->vendedor_nombre_completo; ?></td>
						<td><?php ver_fecha_hora($ro->fecha_referido); ?></td>
						<td <?php echo $vigente; ?>><?php ver_fecha_hora($ro->fecha_vigencia); ?></td>
						<td <?php echo $vigente; ?>><?php echo ($ro->vigente==1)?'Sí':'No'; ?></td>
						<td align="center">
							<?php if($puede_editar):?>
								<a href="<?php echo site_url('referidos/editar/'.$ro->id); ?>" class="accion accion1">Editar</a>
							<?php endif;?>
						</td>
					</tr>
					<?php $i++; endforeach;?>
				</tbody>
			</table>
		</div>
	</div>
<?php endif;?>
<script type="text/javascript" src="<?php echo base_url(); ?>js/bc/direccion.js"></script>
<script type="text/javascript">
<!--
$(function(){
	Direccion.set('instalacion_');
	convertir_campos();
});
//-->
</script>
<?php if(!is_ajax()){ $this->load->view('layout/footer'); } ?>