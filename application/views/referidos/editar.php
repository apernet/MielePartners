<?php if(!is_ajax()){ $this->load->view('layout/header'); } ?>
	<div class="col-lg-12 formulario-head">
		<div class="row">
			<div class="col-lg-11 col-sm-10 col-xs-9">
				<h4><?php echo $titulo;?></h4>
			</div>
		</div>
	</div>
	<div class="col-lg-12">
		<p class="msg info">
		<span class="fa-stack fa-2x">
			<i class="fa fa-circle fa-stack-2x text-color-blanco"></i>
		 	<i class="fa fa-info fa-stack-1x text-color-info"></i>	
		</span>
		Los campos requeridos est&aacute;n marcados con <span class="req">*</span></p>
	</div>
	<div class="col-lg-12">
		<form action="<?php echo site_url(uri_string()); ?>" id="form" method="post" enctype="multipart/form-data">
		<div class="panel-group">
				<div class="panel panel-default">
				    <div class="panel-heading">
				      <h4 class="panel-title">
				        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
				          Informaci&oacute;n general
				        </a>
				      </h4>
				    </div>
				    <div id="collapseOne" class="panel-collapse collapse in">
						<div class="panel-body">
							<div class="row">
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
									<label><span class="req">*</span>Nombre (s):</label>
									<input class="form-control" id="nombre" name="nombre" value="<?php echo set_value('nombre',@$r->nombre); ?>" />
									<?php echo form_error('nombre');?>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
									<label><span class="req">*</span>Apellido paterno:</label>
									<input class="form-control" id="apellido_paterno" name="apellido_paterno" value="<?php echo set_value('apellido_paterno',@$r->apellido_paterno); ?>" />
									<?php echo form_error('apellido_paterno');?>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
									<label>Apellido materno:</label>
									<input class="form-control" id="apellido_materno" name="apellido_materno" value="<?php echo set_value('apellido_materno',@$r->apellido_materno); ?>" />
									<?php echo form_error('apellido_materno');?>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
									<label>E-mail cliente:</label>
									<input class="form-control" id="email" name="email" value="<?php echo set_value('email',@$r->email); ?>" />
									<?php echo form_error('email');?>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
									<label>Distribuidor:</label>
									<?php if($mostrar_distribuidores):?>
										<select class="form-control" name="distribuidores_id" id="distribuidores_id">
											<option value=""><?php echo $this->config->item('empty_select'); ?></option>
											<?php foreach($distribuidores as $k=>$v):?>
												<option value="<?php echo $k; ?>" <?php echo  set_select('distribuidores_id', $k, ($k == @$r->distribuidores_id)); ?>> <?php echo $v; ?></option>
											<?php endforeach;?>
										</select>
										<?php echo form_error('distribuidores_id');?>
									<?php else:?>
										<input class="form-control" type="hidden" id="distribuidores_id" name="distribuidores_id" value="<?php echo $this->session->userdata('cuentas_id'); ?>" />
										<input class="form-control" type="text" value="<?php echo $distribuidores[$this->session->userdata('cuentas_id')]; ?>" disabled="disabled"/>
									<?php endif;?>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
									<label><span class="req">*</span>Nombre del Vendedor:</label>
										<input class="form-control" type="text" id="vendedor_nombre" name="vendedor_nombre" value="<?php echo set_value('vendedor_nombre',@$r->vendedor_nombre); ?>" />
									<?php echo form_error('vendedor_nombre');?>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
									<label><span class="req">*</span>Apellido Paterno del Vendedor:</label>
										<input class="form-control" type="text" id="vendedor_paterno" name="vendedor_paterno" value="<?php echo set_value('vendedor_paterno',@$r->vendedor_paterno); ?>" />
									<?php echo form_error('vendedor_paterno');?>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
									<label><span class="req">*</span>Apellido Materno del Vendedor:</label>
										<input class="form-control" type="text" id="vendedor_materno" name="vendedor_materno" value="<?php echo set_value('vendedor_materno',@$r->vendedor_materno); ?>" />
									<?php echo form_error('vendedor_materno');?>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
									<label>E-mail vendedor:</label>
									<input class="form-control" id="vendedor_email" name="vendedor_email" value="<?php echo set_value('vendedor_email',@$r->vendedor_email); ?>" />
									<?php echo form_error('vendedor_email');?>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
									<label>Vigencia:</label>
									<input class="form-control" id="vigencia" name="vigencia" value="<?php echo set_value('vigencia',@$r->vigencia); ?>" disabled="disabled"/>
									<?php echo form_error('item');?>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="panel panel-default">
				    <div class="panel-heading">
				      <h4 class="panel-title">
				        <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
				          Direcci&oacute;n de Instalaci&oacute;n
				        </a>
				      </h4>
				    </div>
				    <div id="collapseTwo" class="panel-collapse collapse in">
						<div class="panel-body">
							<p class="msg info">
							<span class="fa-stack fa-2x">
								<i class="fa fa-circle fa-stack-2x text-color-blanco"></i>
							 	<i class="fa fa-info fa-stack-1x text-color-info"></i>	
							</span>
							“Es altamente recomendable poner la direcci&oacute;n de instalaci&oacute;n, para dar seguimiento al cliente referenciado en Miele.”</p>
							<div class="row">
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					        		<label>Estado:</label>
									<select class="form-control" name="instalacion_estado" id="instalacion_estado">
										<option value=""><?php echo $this->config->item('empty_select');?></option>
										<?php foreach($estados as $e):?>
										<option value="<?php echo $e; ?>" <?php echo set_select('instalacion_estado', $e, ($e == @$r->instalacion_estado)); ?> ><?php echo $e; ?></option>
										<?php endforeach;?>
									</select>
									<?php echo form_error('instalacion_estado');?>
					        	</div>
					        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					        		<label>Delegaci&oacute;n o municipio:</label>
									<select class="form-control" name="instalacion_municipio" id="instalacion_municipio" >
										<option value=""><?php echo $this->config->item('empty_select');?></option>
										<?php foreach($municipios_instalacion as $mpio):?>
										<option value="<?php echo $mpio; ?>" <?php echo set_select('instalacion_municipio', $mpio, ($mpio == @$r->instalacion_municipio)); ?>><?php echo $mpio; ?></option>
										<?php endforeach;?>
									</select>
									<?php echo form_error('instalacion_municipio');?>
						        </div>
						        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					        		<label>C&oacute;digo postal:</label>
		        		       		<div class="input-group">
						        		<input class="form-control" type="text" id="instalacion_codigo_postal"  name="instalacion_codigo_postal"  value="<?php echo set_value('instalacion_codigo_postal',@$r->instalacion_codigo_postal); ?>"/>
						        		<span class="input-group-addon">
			    					       <a  class="buscar_ref icono_info" title="Buscar direcci&oacute;n en base al c&oacute;digo postal." href="#" id="instalacion_dir_search"><i class="fa fa-search"></i></a>
			    					   	</span>
									</div>
									<?php echo form_error('instalacion_codigo_postal');?>
					        	</div>
					        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					        		<label>Colonia:</label>
					        		<div class="input-group">
						        		<input class="form-control" type="text" id="instalacion_asentamiento"  name="instalacion_asentamiento"  value="<?php echo set_value('instalacion_asentamiento',@$r->instalacion_asentamiento); ?>"/>
						        		<span class="input-group-addon">
			    					       <a  class="buscar_ref icono_info" title="Buscar c&oacute;digo postal en base a la colonia" href="#" id="instalacion_cp_search"><i class="fa fa-search"></i></a>
			    					   	</span>
		    					   	</div>
									<?php echo form_error('instalacion_asentamiento');?>
					        	</div>
					        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					        		<label>Calle:</label>
					        		<input class="form-control" type="text" id="instalacion_calle"  name="instalacion_calle"  value="<?php echo set_value('instalacion_calle',@$r->instalacion_calle); ?>"/>
									<?php echo form_error('instalacion_calle');?>
					        	</div>
					        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					        		<label>N&uacute;mero exterior:</label>
					        		<input class="form-control" type="text" id="instalacion_numero_exterior"  name="instalacion_numero_exterior"  value="<?php echo set_value('instalacion_numero_exterior',@$r->instalacion_numero_exterior); ?>"/>
									<?php echo form_error('instalacion_numero_exterior');?>
					        	</div>
					        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					        		<label>N&uacute;mero interior:</label>
					        		<input class="form-control" type="text" id="instalacion_numero_interior"  name="instalacion_numero_interior"  value="<?php echo set_value('instalacion_numero_interior',@$r->instalacion_numero_interior); ?>"/>
									<?php echo form_error('instalacion_numero_interior');?>
						        </div>
				        	</div>
						</div>
					</div>
				</div>
				
				<div class="col-lg-12 barra-btn">
					<input  class="btn btn-primary pull-right" type="submit" value="Guardar" id="guardar" />
					<a href="<?php echo site_url('referidos/index');?>"  class="btn btn-default pull-right">Cancelar</a>
				</div>
		</form>
	</div>
<script type="text/javascript" src="<?php echo base_url(); ?>js/bc/direccion.js"></script>
<script type="text/javascript">
<!--
$(function(){
	Direccion.set('instalacion_');
});
//-->
</script>	
<?php if(!is_ajax()){ $this->load->view('layout/footer'); } ?>