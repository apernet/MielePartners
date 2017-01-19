	<div class="panel-group front_panel" id="accordion4">
  		<div class="panel panel-default">
		    <div class="panel-heading">
		      	<h4 class="panel-title">
		        	<a data-toggle="collapse" data-parent="#accordion4" href="#collapseFour">
			          Direcci&oacute;n de Env&iacute;o
			        </a>
		      	</h4>
		    </div>
		    <div id="collapseFour" class="panel-collapse collapse in">
		      	<div class="panel-body">
			        <div class="row">
			            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
			        		<label><span class="req">*</span>Nombre contacto:</label>
			        		<input class="form-control <?php echo form_error('nombre_contacto')?'rojo':'';?>" type="text" id="nombre_contacto"  name="nombre_contacto"  value="<?php echo set_value('nombre_contacto',@$r['nombre_contacto']); ?>" <?php echo in_array(@@$status_id,array(2,3,4,5))?'readonly':'';?>/>
						<?php echo form_error('nombre_contacto');?>
		        		</div>
			        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
			        		<label><span class="req">*</span>Tel&eacute;fono particular:</label>
			        		<input class="form-control <?php echo form_error('telefono_particular')?'rojo':'';?>" type="text" id="telefono_particular"  name="telefono_particular"  value="<?php echo set_value('telefono_particular',@$r['telefono_particular']); ?>" <?php echo in_array(@@$status_id,array(2,3,4,5))?'readonly':'';?>/>
							<?php echo form_error('telefono_particular');?>
			        	</div>
			        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
			        		<label>Tel&eacute;fono celular:</label>
			        		<input class="form-control <?php echo form_error('telefono_celular')?'rojo':'';?>" type="text" id="telefono_celular"  name="telefono_celular"  value="<?php echo set_value('telefono_celular',@$r['telefono_celular']); ?>" <?php echo in_array(@@$status_id,array(2,3,4,5))?'readonly':'';?>/>
							<?php echo form_error('telefono_celular');?>
			        	</div>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
			        		<label><span class="req">*</span>Estado:</label>
							<input type="hidden" class="recalcular" value=""/>
			        		<?php if(!in_array(@$status_id,array(2,3,4,5))):?>
								<select class="form-control envio <?php echo form_error('entrega_estado')?'rojo':'';?>" name="entrega_estado" id="entrega_estado" >
									<option value=""><?php echo $this->config->item('empty_select');?></option>
									<?php foreach($estados as $e):?>
									<option value="<?php echo $e; ?>" <?php echo ($e==@$r['entrega_estado'])?'selected="selected"':''; ?> ><?php echo $e; ?></option>
									<?php endforeach;?>
								</select>
							<?php else:?>
	                				<input type="hidden" name="entrega_estado" value="<?php echo @$r['entrega_estado'];?>" class="envio" id="entrega_estado">
	                				<div class="field-info <?php echo form_error('entrega_estado')?'rojo':'';?>"><?php echo @$r['entrega_estado'];?></div>
	                			<?php endif;?>
							<?php echo form_error('entrega_estado');?>
			        	</div>
			        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
			        		<label><span class="req">*</span>Delegaci&oacute;n o municipio:</label>
			        		<?php if(!in_array(@$status_id,array(2,3,4,5))):?>
								<select class="form-control <?php echo form_error('entrega_municipio')?'rojo':'';?>" name="entrega_municipio" id="entrega_municipio" >
									<option value=""><?php echo $this->config->item('empty_select');?></option>
									<?php foreach($municipios_entrega as $mpio):?>
									<option value="<?php echo $mpio; ?>" <?php echo ($mpio==@$r['entrega_municipio'])?'selected="selected"':''; ?>><?php echo $mpio; ?></option>
									<?php endforeach;?>
								</select>
							<?php else:?>
	                				<input type="hidden" name="entrega_municipio" value="<?php echo @$r['entrega_municipio'];?>" id="entrega_municipio">
	                				<div class="field-info <?php echo form_error('entrega_municipio')?'rojo':'';?>"><?php echo @$r['entrega_municipio'];?></div>
	                			<?php endif;?>
							<?php echo form_error('entrega_municipio');?>
					        </div>
					        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				        		<label><span class="req">*</span>C&oacute;digo postal:</label>
	        		       		<div class="input-group">
					        		<input class="form-control <?php echo form_error('entrega_codigo_postal')?'rojo':'';?>" type="text" id="entrega_codigo_postal"  name="entrega_codigo_postal"  value="<?php echo set_value('entrega_codigo_postal',@$r['entrega_codigo_postal']); ?>" <?php echo in_array(@$status_id,array(2,3,4,5))?'readonly':'';?>/>
				        		<span class="input-group-addon">
				        		<?php if(in_array(@$status_id,array(2,3,4,5))):?>
				        			<i class="fa fa-search"></i>
			        			<?php else:?>
	    					       <a class="buscar_ref icono_info" title="Buscar direcci&oacute;n en base al c&oacute;digo postal." href="#" id="entrega_dir_search"><i class="fa fa-search"></i></a>
	    					   	</span>
	    					   	<?php endif;?>
							</div>
							<?php echo form_error('entrega_codigo_postal');?>
			        	</div>
			        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
			        		<label><span class="req">*</span>Colonia:</label>
			        		<div class="input-group">
				        		<input class="form-control <?php echo form_error('entrega_asentamiento')?'rojo':'';?>" type="text" id="entrega_asentamiento"  name="entrega_asentamiento"  value="<?php echo set_value('entrega_asentamiento',@$r['entrega_asentamiento']); ?>" <?php echo in_array(@$status_id,array(2,3,4,5))?'readonly':'';?>/>
				        		<span class="input-group-addon">
				        		<?php if(in_array(@$status_id,array(2,3,4,5))):?>
				        			<i class="fa fa-search"></i>
			        			<?php else:?>
	    					       <a class="buscar_ref icono_info" title="Buscar c&oacute;digo postal en base a la colonia" href="#" id="entrega_cp_search"><i class="fa fa-search"></i></a>
	    					   	<?php endif;?>
		    					   	</span>
	    					   	</div>
								<?php echo form_error('entrega_asentamiento');?>
			        	</div>
			        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
			        		<label><span class="req">*</span>Calle:</label>
			        		<input class="form-control <?php echo form_error('entrega_calle')?'rojo':'';?>" type="text" id="entrega_calle"  name="entrega_calle"  value="<?php echo set_value('entrega_calle',@$r['entrega_calle']); ?>" <?php echo in_array(@$status_id,array(2,3,4,5))?'readonly':'';?>/>
							<?php echo form_error('entrega_calle');?>
			        	</div>
			        	<div class="clearfix"></div>
			        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
			        		<label><span class="req">*</span>N&uacute;mero exterior:</label>
			        		<input class="form-control <?php echo form_error('entrega_numero_exterior')?'rojo':'';?>" type="text" id="entrega_numero_exterior"  name="entrega_numero_exterior"  value="<?php echo set_value('entrega_numero_exterior',@$r['entrega_numero_exterior']); ?>" <?php echo in_array(@$status_id,array(2,3,4,5))?'readonly':'';?>/>
							<?php echo form_error('entrega_numero_exterior');?>
			        	</div>
			        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
			        		<label>N&uacute;mero interior:</label>
			        		<input class="form-control <?php echo form_error('entrega_numero_interior')?'rojo':'';?>" type="text" id="entrega_numero_interior"  name="entrega_numero_interior"  value="<?php echo set_value('entrega_numero_interior',@$r['entrega_numero_interior']); ?>" <?php echo in_array(@$status_id,array(2,3,4,5))?'readonly':'';?>/>
							<?php echo form_error('entrega_numero_interior');?>
							<?php if($venta_directa && !in_array(@$status_id,array(2,3,4,5))):?>
								<a href="#" id="datos_envio" title="De click sobre este enlace para copiar los datos de env&iacute;o a datos de facturaci&oacute;n" >Copiar datos de env&iacute;o a datos de Facturaci&oacute;n</a>
							<?php endif;?>
				        </div>
				        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
			        		<label><span class="req">*</span>Fecha Tentativa de Entrega:</label>
			        		<input id="entrega_fecha_tentativa_alt" type="hidden" name="entrega_fecha_tentativa" value="<?php echo @$r['entrega_fecha_tentativa']; ?>" readonly="readonly"/>
			        		<input class="form-control fecha <?php echo form_error('entrega_fecha_tentativa')?'rojo':'';?>" type="text" id="entrega_fecha_tentativa" value="<?php echo get_fecha(@$r['entrega_fecha_tentativa']); ?>" <?php echo in_array(@$status_id,array(2,3,4,5))?'readonly':'';?>/>
							<?php echo form_error('entrega_fecha_tentativa');?>
							<?php if(@$status_id==1):// && @$r['entrega_estado']==@$r['instalacion_estado']?>
							    <a href="#" id="datos_instalacion" title="De click sobre este enlace para copiar los datos de env&iacute;o a datos de instalaci&oacute;n" >Copiar datos de env&iacute;o a datos de Instalaci&oacute;n</a>
							<?php endif;?>
				        </div>
				    </div>
		        </div>
			</div>
		</div>
     </div>