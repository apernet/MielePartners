<?php if(!is_ajax()){ $this->load->view('frontend/layout/header'); } ?>
<div class="minHeight">
	<div class="row noMargin">
		<div class=" front_subtituloSeccion">
			<p class=""><?php echo $titulo;?></p>
		</div>
	</div>
	<div class="col-lg-12">
		<p class="msg info">
			<span class="fa-stack fa-2x">
			  <i class="fa fa-circle fa-stack-2x text-color-blanco"></i>
			  <i class="fa fa-info fa-stack-1x text-color-info"></i>
			</span>
		 	Los campos requeridos est&aacute;n marcados con <span class="req">*</span>.
		 </p>
	</div>
	<form action="<?php echo site_url('frontends/registro'); ?>" id="form" method="post" enctype="multipart/form-data">
		<div class="col-lg-12">
			<div class="panel-group">
				<div class="panel panel-default">
				    <div class="panel-heading">
				      	<h4 class="panel-title">
					        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
					          <?php echo $registro_exitoso?'Mis datos':'Por favor, ingrese sus datos.';?>
					        </a>
				      	</h4>
				    </div>
				    <div id="collapseOne" class="panel-collapse collapse in">
				    	<div class="panel-body">
					        <div class="row">
                                <div class="col-lg-3 form-group">
                                    <label><span class="req">*</span>Correo electr&oacute;nico:</label>
                                    <input class="form-control" id="email" name="email" value="<?php echo set_value('email',@$r->email); ?>" />
                                    <?php echo  form_error('email');?>
                                </div>
                                <?php if($registro_exitoso):?>
                                    <div class="col-lg-3 form-group">
                                        <label><span class="req">*</span>Contrase&ntilde;a:</label>
                                        <input  id="cambiar_contrasena" type="checkbox"  />&iquest;Cambiar contrase&ntilde;a?<br/>
                                        <input class="form-control" id="contrasena" name="contrasena" type="password" disabled="disabled" class="opacity"/>
                                        <?php echo  form_error('contrasena');?>
                                    </div>
                                    <div class="col-lg-3 form-group">
                                        <label><span class="req">*</span>Confirmar Contrase&ntilde;a:</label>
                                        <input class="form-control" id="confirmar_contrasena" name="confirmar_contrasena"  type="password" disabled="disabled" class="opacity"/>
                                        <?php echo  form_error('confirmar_contrasena');?>
                                    </div>
                                <?php else:?>
                                    <div class="col-lg-3 form-group">
                                        <label><span class="req">*</span>Contrase&ntilde;a:</label>
                                        <input class="form-control" id="contrasena" name="contrasena" type="password" />
                                        <?php echo  form_error('contrasena');?>
                                    </div>
                                    <div class="col-lg-3 form-group">
                                        <label><span class="req">*</span>Confirmar Contrase&ntilde;a:</label>
                                        <input class="form-control" id="confirmar_contrasena" name="confirmar_contrasena"  type="password" />
                                        <?php echo  form_error('confirmar_contrasena');?>
                                    </div>
                                <?php endif;?>
					        	<div class="col-lg-3 form-group" style="display:none;">
									<input class="form-control" id="id" name="id" value="<?php echo set_value('id',@$r->id); ?>" />
									<?php echo  form_error('id');?>
					        	</div>
					        	<div class="col-lg-3 form-group">
					        		<label><span class="req">*</span>Nombre(s):</label>
									<input class="form-control" id="nombre" name="nombre" value="<?php echo set_value('nombre',@$r->nombre); ?>" />
									<?php echo  form_error('nombre');?>
					        	</div>
					        	<div class="col-lg-3 form-group">
					        		<label><span class="req">*</span>Apellido paterno:</label>
									<input class="form-control" id="apellido_paterno" name="apellido_paterno" value="<?php echo set_value('apellido_paterno',@$r->apellido_paterno); ?>" />
									<?php echo  form_error('apellido_paterno');?>
					        	</div>
					        	<div class="col-lg-3 form-group">
					        		<label><span class="req">*</span>Apellido materno:</label>
									<input class="form-control" id="apellido_materno" name="apellido_materno" value="<?php echo set_value('apellido_materno',@$r->apellido_materno); ?>" />
									<?php echo  form_error('apellido_materno');?>
					        	</div>
					        	<div class="col-lg-3 form-group">
					        		<label>Tel&eacute;fono:</label>
									<input class="form-control" id="telefono" name="telefono" value="<?php echo set_value('telefono',@$r->telefono); ?>" />
									<?php echo  form_error('telefono');?>
					        	</div>
					        	<div class="col-lg-3 form-group">
					        		<label>Celular:</label>
									<input class="form-control" id="celular" name="celular" value="<?php echo set_value('celular',@$r->celular); ?>" />
									<?php echo  form_error('celular');?>
					        	</div>
					        	<div class="clear"></div>
					        </div>
					    </div>
					</div>
				</div>
				<div class="col-lg-12 barra-btn">
					<input  class="btn btn-primary pull-right" type="submit" value="<?php echo $registro_exitoso?'Guardar':'Registrar';?>" id="guardar" />
				</div>
			</div>
		</div>
	</form>
</div>
<script type="text/javascript">
<!--
$(function(){
	$('#cambiar_contrasena').change(function(){
		if(this.checked)
		{
			$('#contrasena').removeAttr('disabled');
			$('#contrasena').toggleClass('opacity');
			$('#confirmar_contrasena').removeAttr('disabled');
			$('#confirmar_contrasena').toggleClass('opacity');
		}			
		else
		{	
			$('#contrasena').attr('disabled','disabled');
			$('#contrasena').toggleClass('opacity');
			$('#confirmar_contrasena').attr('disabled','disabled');
			$('#confirmar_contrasena').toggleClass('opacity');
		}
	});
});
-->
</script>
<?php if(!is_ajax()){ $this->load->view('frontend/layout/footer'); } ?>