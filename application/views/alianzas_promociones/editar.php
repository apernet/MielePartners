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
		Los campos requeridos est&aacute;n marcados con <span class="req">*</span>.</p>
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
									<label><span class="req">*</span>Nombre:</label>
									<input id="nombre" name="nombre" class="form-control" type="text" value="<?php echo set_value('nombre',@$alianzas_promociones->nombre); ?>" />
									<?php echo  form_error('nombre');?>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
									<label><span class="req">*</span>Prefijo:</label>
									<input type="text" class="form-control" id="prefijo" name="prefijo" value="<?php echo set_value('prefijo',@$alianzas_promociones->prefijo); ?>" readonly/>
									<?php echo  form_error('prefijo');?>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
									<label><span class="req">*</span>N&uacute;mero de folios:</label>
									<input id="numero_folios" name="numero_folios" class="form-control" type="text" value="<?php echo set_value('numero_folios',@$alianzas_promociones->numero_folios); ?>" readonly/>
									<?php echo  form_error('numero_folios');?>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
									<label>Descripci&oacute;n:</label>
									<textarea rows="4" cols="50" id="descripcion" name="descripcion" class="form-control"><?php echo set_value('descripcion',@$alianzas_promociones->descripcion); ?></textarea>
									<?php echo  form_error('descripcion');?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="col-lg-12 barra-btn">
				<input  class="btn btn-primary pull-right" type="submit" value="Guardar" id="guardar" />
				<a href="<?php echo site_url('alianzas_promociones/index');?>"  class="btn btn-default pull-right">Cancelar</a>
			</div>

		</form>
	</div>

<script type="text/javascript">
<!--				-->
</script>

<?php if(!is_ajax()){ $this->load->view('layout/footer'); } ?>