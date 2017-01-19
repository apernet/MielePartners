<div class="panel-group front_panel" id="accordion9">
  		<div class="panel panel-default">
		    <div class="panel-heading">
		      <h4 class="panel-title">
		        <a data-toggle="collapse" data-parent="#accordion9" href="#collapseNine">
		          Direcci&oacute;n de Instalaci&oacute;n
		        </a>
		      </h4>
		    </div>
		    <div id="collapseNine" class="panel-collapse collapse in">
		      	<div class="panel-body">
			        <div class="row">
			            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
			        		<label><span class="req">*</span>Nombre contacto:</label>
			        		<input class="form-control <?php echo form_error('instalacion_nombre_contacto')?'rojo':'';?>" type="text" id="instalacion_nombre_contacto"  name="instalacion_nombre_contacto"  value="<?php echo set_value('instalacion_nombre_contacto',@$r['instalacion_nombre_contacto']); ?>" />
							<?php echo form_error('instalacion_nombre_contacto');?>
			        	</div>
			        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
			        		<label>Tel&eacute;fono particular:</label>
			        		<input class="form-control <?php echo form_error('instalacion_telefono_particular')?'rojo':'';?>" type="text" id="instalacion_telefono_particular"  name="instalacion_telefono_particular"  value="<?php echo set_value('instalacion_telefono_particular',@$r['instalacion_telefono_particular']); ?>" />
							<?php echo form_error('instalacion_telefono_particular');?>
			        	</div>
			        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
			        		<label>Tel&eacute;fono celular:</label>
			        		<input class="form-control <?php echo form_error('instalacion_telefono_celular')?'rojo':'';?>" type="text" id="instalacion_telefono_celular"  name="instalacion_telefono_celular"  value="<?php echo set_value('instalacion_telefono_celular',@$r['instalacion_telefono_celular']); ?>" />
							<?php echo form_error('instalacion_telefono_celular');?>
			        	</div>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
			        		<label><span class="req">*</span>Estado:</label>
							<select class="form-control envio <?php echo form_error('instalacion_estado')?'rojo':'';?>" name="instalacion_estado" id="instalacion_estado" >
								<option value=""><?php echo $this->config->item('empty_select');?></option>
								<?php foreach($estados as $e):?>
								<option value="<?php echo $e; ?>" <?php echo ($e==@$r['instalacion_estado'])?'selected="selected"':''; ?> ><?php echo $e; ?></option>
								<?php endforeach;?>
							</select>
							<?php echo form_error('instalacion_estado');?>
			        	</div>
			        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
			        		<label><span class="req">*</span>Delegaci&oacute;n o municipio:</label>
							<select class="form-control <?php echo form_error('instalacion_municipio')?'rojo':'';?>" name="instalacion_municipio" id="instalacion_municipio" >
								<option value=""><?php echo $this->config->item('empty_select');?></option>
								<?php foreach($instalacion_municipios as $mpio):?>
								<option value="<?php echo $mpio; ?>" <?php echo ($mpio==@$r['instalacion_municipio'])?'selected="selected"':''; ?>><?php echo $mpio; ?></option>
								<?php endforeach;?>
							</select>
							<?php echo form_error('instalacion_municipio');?>
				        </div>
				        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
			        		<label><span class="req">*</span>C&oacute;digo postal:</label>
        		       		<div class="input-group">
				        		<input class="form-control <?php echo form_error('instalacion_codigo_postal')?'rojo':'';?>" type="text" id="instalacion_codigo_postal"  name="instalacion_codigo_postal"  value="<?php echo set_value('instalacion_codigo_postal',@$r['instalacion_codigo_postal']); ?>" />
				        		<span class="input-group-addon">
	    					       <a  class="buscar_ref icono_info" title="Buscar direcci&oacute;n en base al c&oacute;digo postal." href="#" id="instalacion_dir_search"><i class="fa fa-search"></i></a>
	    					   	</span>
							</div>
							<?php echo form_error('instalacion_codigo_postal');?>
			        	</div>
			        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
			        		<label><span class="req">*</span>Colonia:</label>
			        		<div class="input-group">
				        		<input class="form-control <?php echo form_error('instalacion_asentamiento')?'rojo':'';?>" type="text" id="instalacion_asentamiento"  name="instalacion_asentamiento"  value="<?php echo set_value('instalacion_asentamiento',@$r['instalacion_asentamiento']); ?>" />
				        		<span class="input-group-addon">
	    					       <a  class="buscar_ref icono_info" title="Buscar c&oacute;digo postal en base a la colonia" href="#" id="instalacion_cp_search"><i class="fa fa-search"></i></a>
	    					   	</span>
    					   	</div>
							<?php echo form_error('instalacion_asentamiento');?>
			        	</div>
			        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
			        		<label><span class="req">*</span>Calle:</label>
			        		<input class="form-control <?php echo form_error('instalacion_calle')?'rojo':'';?>" type="text" id="instalacion_calle"  name="instalacion_calle"  value="<?php echo set_value('instalacion_calle',@$r['instalacion_calle']); ?>" />
							<?php echo form_error('instalacion_calle');?>
			        	</div>
			        	<div class="clearfix"></div>
			        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
			        		<label><span class="req">*</span>N&uacute;mero exterior:</label>
			        		<input class="form-control <?php echo form_error('instalacion_numero_exterior')?'rojo':'';?>" type="text" id="instalacion_numero_exterior"  name="instalacion_numero_exterior"  value="<?php echo set_value('instalacion_numero_exterior',@$r['instalacion_numero_exterior']); ?>" />
							<?php echo form_error('instalacion_numero_exterior');?>
			        	</div>
			        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
			        		<label>N&uacute;mero interior:</label>
			        		<input class="form-control <?php echo form_error('instalacion_numero_interior')?'rojo':'';?>" type="text" id="instalacion_numero_interior"  name="instalacion_numero_interior"  value="<?php echo set_value('instalacion_numero_interior',@$r['instalacion_numero_interior']); ?>" />
							<?php echo form_error('instalacion_numero_interior');?>
				        </div>
				        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
			        		<label>Fecha Tentativa de Instalaci&oacute;n:</label>
			        		<input id="entrega_fecha_instalacion_alt" type="hidden" name="entrega_fecha_instalacion" value="<?php echo @$r['entrega_fecha_instalacion']; ?>" readonly="readonly"/>
			        		<input class="form-control fecha <?php echo form_error('entrega_fecha_instalacion')?'rojo':'';?>" type="text" id="entrega_fecha_instalacion" value="<?php echo get_fecha(@$r['entrega_fecha_instalacion']); ?>" />
							<?php echo form_error('entrega_fecha_instalacion');?>
				        </div>
				        </div>
		        	</div>
		        </div>
			</div>
     	</div>