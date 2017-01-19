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
		<div class="panel-body">
			<div class="row">
				<input type="hidden" name="id" value="<?php echo set_value('id',@$r->id); ?>" />
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					<label><span class="req">*</span>Nombre:</label>
					<input class="form-control" id="nombre" name="nombre" value="<?php echo set_value('nombre',@$r->nombre); ?>" />
					<?php echo  form_error('nombre');?>
				</div>
			</div>
		</div>
		<div class="col-lg-12 barra-btn">
			<input class=" btn btn-primary pull-right " type="submit" value="Guardar" id="guardar" />
			<a href="<?php echo site_url('main/grupos');?>" class="btn btn-default pull-right">Cancelar</a>
			<span class="clearblock">&nbsp;</span>
		</div>
		</form>
	</div>

<?php if(!is_ajax()){ $this->load->view('layout/footer'); } ?>