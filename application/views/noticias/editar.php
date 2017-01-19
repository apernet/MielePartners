<?php if(!is_ajax()){ $this->load->view('layout/header'); } ?>
	<div class="col-lg-12 formulario-head">
		<div class="row">
			<div class="col-lg-11 col-sm-10 col-xs-9">
				<h4><?php echo $titulo?></h4>
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
		<form action="<?php echo site_url(uri_string()); ?>" id="form" method="post">
		<input type="hidden" name="id" value="<?php echo set_value('id',@$r->id); ?>" />
			<div class="panel panel-group">
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
				        		<label><span class="req">*</span>T&iacute;tulo:</label>
								<input class="form-control" type="text" id="titulo" name="titulo" value="<?php echo set_value('titulo',@$r->titulo); ?>" />
								<?php echo  form_error('titulo');?>	
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				        		<label ><span class="req">*</span>Fecha:</label>
				        		<input  id="fecha_alt" name="fecha" value="<?php echo set_value('fecha',@$r->fecha); ?>" type="hidden" />
								<input class="form-control fecha" type="text"  id="fecha" value="<?php echo get_fecha(set_value('fecha',@$r->fecha));?>" />
								<?php echo  form_error('fecha');?>
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				        		<div class="checkbox">
									<input name="activo" value="0" type="hidden" />
				        			<label>Activo</label>
									<input type="checkbox" name="activo" value="1" <?php echo set_checkbox('activo','1',(@$r->activo)?TRUE:FALSE); ?> />
									<?php echo  form_error('activo');?>
				        		</div>
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					        	<div class="checkbox">
									<input name="inicio" value="0" type="hidden" />
					        		<label>Mostrar al inicio</label>
									<input type="checkbox" name="inicio" value="1" <?php echo set_checkbox('inicio','1',(@$r->inicio)?TRUE:FALSE); ?> />
									<?php echo  form_error('inicio');?>
					        	</div>
							</div>
				        </div>
			        </div>
		        </div>
	        </div>
	        <div class="panel panel-default">
			    <div class="panel-heading">
			      <h4 class="panel-title">
			        <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
			          Contenido 
			        </a>
			      </h4>
			    </div>
			    <div id="collapseTwo" class="panel-collapse collapse in">
			    <div class="panel-body">
			        <div class="row">
			        	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			        		<label><span class="req">*</span>Contenido</label>	
		        			<textarea id="contenido" name="contenido" class="form-control" rows="10"><?php echo set_value('contenido',@$r->contenido); ?></textarea>
							<?php echo  form_error('contenido');?>
						</div>  
		        	</div>
		        </div>
		        </div>
        	</div>
        	<div class="col-lg-12 barra-btn">
        		<input class="btn btn-primary pull-right" type="submit" value="Guardar" id="guardar" />
				<a href="<?php echo site_url('noticias/index');?>" class="btn btn-default pull-right ">Cancelar</a>
				<div class="clearblock">&nbsp;</div>
			</div>
			</div>
		</form>
	</div>
<script language="javascript" type="text/javascript" src="<?php echo base_url(); ?>/js/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo base_url(); ?>/js/tiny_mce/basic_config.js"></script>
<script type="text/javascript">
<!--
$(function(){
	convertir_campos();
});
//-->
</script>
<?php if(!is_ajax()){ $this->load->view('layout/footer'); } 