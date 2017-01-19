<?php if(!is_ajax()){ $this->load->view('layout/head'); } ?>
	<div class="col-lg-12 formulario-head">
		<div class="row">
			<div class="col-lg-11 col-sm-10 col-xs-9">
				<h4><?php echo $titulo;?></h4>
			</div>
		</div>
	</div>
<form action="<?php echo site_url(uri_string());?>" method="post" id="form">
	<div class="col-lg-12">
		<div class="row">
			<input type="hidden" id="buscar_por" name="buscar_por" class="form-control" value="<?php echo @$cond['buscar_por']; ?>" />
			<?php if($cond['buscar_por']==1):?>
			<div class="col-lg-3 form-group">
				<label>Nombre del Cliente</label>
				<input id="cliente" name="cliente" class="form-control" value="<?php echo @$cond['cliente']; ?>" />
			</div>
			<?php elseif($cond['buscar_por']==2):?>
			<div class="col-lg-3 form-group">
				<label>Email</label>
				<input id="email" name="email" class="form-control" value="<?php echo @$cond['email']; ?>" />
			</div>
			<?php elseif($cond['buscar_por']==3):?>
			<div class="col-lg-3 form-group">
	        	<label> Estado:</label>
				<select class="form-control" name="instalacion_estado" id="instalacion_estado">
					<option value=""><?php echo $this->config->item('empty_select');?></option>
				<?php foreach($estados as $e):?>
					<option value="<?php echo $e; ?>" <?php echo set_select('instalacion_estado', $e, ($e == @$cond['instalacion_estado'])); ?> ><?php echo $e; ?></option>
				<?php endforeach;?>
				</select>
				<?php echo  form_error('instalacion_estado');?>
	        </div>
	        <div class="col-lg-3 form-group">
	       		<label> Delegaci&oacute;n o municipio:</label>
				<select class="form-control" name="instalacion_municipio" id="instalacion_municipio" >
					<option value=""><?php echo $this->config->item('empty_select');?></option>
					<?php foreach($municipios_instalacion as $mpio):?>
						<option value="<?php echo $mpio; ?>" <?php echo set_select('instalacion_municipio', $mpio, ($mpio == @$cond['instalacion_municipio'])); ?>><?php echo $mpio; ?></option>
					<?php endforeach;?>
					</select>
					<?php echo  form_error('instalacion_municipio');?>
	        </div>
	        <div class="col-lg-3 col-md-4 col-sm-4 form-group">
	        	<label> C&oacute;digo postal:</label>
	        	<div class="input-group">
					<input class="form-control" type="text" id="instalacion_codigo_postal"  name="instalacion_codigo_postal"  value="<?php echo set_value('instalacion_codigo_postal',@$cond['instalacion_codigo_postal']); ?>"/>
	        		<span class="input-group-addon">
	    				<a  class="buscar_ref icono_info" title="Buscar direcci&oacute;n en base al c&oacute;digo postal." href="#" id="instalacion_dir_search"><i class="fa fa-search"></i></a>
	    			</span>
				</div>
				<?php echo  form_error('instalacion_codigo_postal');?>
	       	</div>
	        <div class="col-lg-3 col-md-4 col-sm-4 form-group">
	        	<label> Colonia:</label>
	        	<div class="input-group">
		       		<input class="form-control" type="text" id="instalacion_asentamiento"  name="instalacion_asentamiento"  value="<?php echo set_value('instalacion_asentamiento',@$cond['instalacion_asentamiento']); ?>"/>
			   		<span class="input-group-addon">
		    	       <a  class="buscar_ref icono_info" title="Buscar c&oacute;digo postal en base a la colonia" href="#" id="instalacion_cp_search"><i class="fa fa-search"></i></a>
		    	   	</span>
	    	   	</div>
				<?php echo  form_error('instalacion_asentamiento');?>
	        </div>	
	        <div class="col-lg-3 col-md-4 col-sm-4 form-group">
	        	<label> Calle:</label>
	        	<input class="form-control" type="text" id="instalacion_calle"  name="instalacion_calle"  value="<?php echo set_value('instalacion_calle',@$cond['instalacion_calle']); ?>"/>
				<?php echo  form_error('instalacion_calle');?>
	        </div>
	       	<div class="col-lg-3 col-md-4 col-sm-4 form-group">
	       		<label> N&uacute;mero exterior:</label>
	       		<input class="form-control" type="text" id="instalacion_numero_exterior"  name="instalacion_numero_exterior"  value="<?php echo set_value('instalacion_numero_exterior',@$cond['instalacion_numero_exterior']); ?>"/>
				<?php echo  form_error('instalacion_numero_exterior');?>
	       	</div>
	       	<div class="col-lg-3 col-md-4 col-sm-4 form-group">
	       		<label>N&uacute;mero interior:</label>
	       		<input class="form-control" type="text" id="instalacion_numero_interior"  name="instalacion_numero_interior"  value="<?php echo set_value('instalacion_numero_interior',@$cond['instalacion_numero_interior']); ?>"/>
				<?php echo  form_error('instalacion_numero_interior');?>
	        </div>
	        <?php endif;?>
		</div>
	</div>
	<div class="col-lg-12 barra-btn">
		<div class="row">
			<div class="col-lg-12">
				<button type="submit" class="btn btn-primary pull-right">Buscar</button>
		  		<!-- <button type="reset" class="btn btn-default pull-right bc_clear">Limpiar</button> -->
			</div>
		</div>
	</div>	
</form>

<?php if($total==0 || count($cond)<=1): ?>
	<p class="msg sin_resultados">No se ha encontrado ning&uacute;n registro.</p>
<?php else: ?>
	<div class="col-lg-12">
		<div class="table-responsive">
			<table class="table table-hover" id="referidos">
				<thead>
					<tr>
						<td class="data_first">Id</td>
						<td>Distribuidor</td>
						<td>Nombre del Cliente</td>
						<td>E-mail</td>
						<td>Vendedor</td>
<!-- 						<td>Fecha vigencia</td> -->
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
					<tr <?php echo ($i%2==0)?'class="altrow"':''?>>
						<td><?php echo $ro->id; ?></td>
						<td align="center"><?php echo $ro->distribuidores_id?$distribuidores[$ro->distribuidores_id]:''; ?></td>
						<td><?php echo $ro->nombre.' '.$ro->apellido_paterno.' '.$ro->apellido_materno; ?></td>
						<td><?php echo $ro->email; ?></td>
						<td><?php echo $ro->vendedor_nombre.' '.$ro->vendedor_paterno.' '.$ro->vendedor_materno; ?></td>
						<!-- <td><?php echo $ro->vigencia; ?></td> -->
						<td align="center">
							<a href="#" class="accion accion1 agregar" id="<?php echo $ro->id;?>">Agregar</a>
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
	$('#referidos').on('click','.agregar',function(e){
		e.preventDefault();
		var id = $(this).attr('id');
	    $.ajax({
	        url: '<?php echo site_url('referidos/referidos_agregar') ?>',
	        dataType: 'json',
	        data: {id : id},
	        type: 'post',
	        success: function(data) {
		        if(data)
			    {
		        	parent.$('#referido_vendedor_nombre').val(data.vendedor_nombre);
		        	parent.$('#referido_vendedor_paterno').val(data.vendedor_paterno);
		        	parent.$('#referido_vendedor_materno').val(data.vendedor_materno);
		        	parent.$('#distribuidores_id').val(data.distribuidores_id);
		        	parent.$('#referido_buscar_id').val('');
		        	parent.$.fancybox.close();
				}
		    }
		});
	});
});
//-->
</script>
</body>
</html>
<?php //if(!is_ajax()){ $this->load->view('layout/foot'); } ?>