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
		<div class="panel-body">
			<div class="row">
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					<label><span class="req">*</span>Cat&aacute;logo:</label>
					<select  class="form-control" id="catalogos_id" name="catalogos_id">
						<option value=""><?php echo $this->config->item('empty_select'); ?></option>
						<?php foreach($catalogos as $k=>$v): ?>
						<option value="<?php echo $k; ?>" <?php echo set_select('catalogos_id', $k,($k == @$r->catalogos_id)); ?>><?php echo $v; ?></option>
						<?php endforeach;?>
					</select>
					<?php echo  form_error('catalogos_id');?>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					<label><span class="req">*</span>Clave:</label>
					<input  class="form-control" id="clave" name="clave" value="<?php echo set_value('clave',@$r->clave); ?>" />
					<?php echo  form_error('clave');?>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					<label><span class="req">*</span>Valor:</label>
					<input   class="form-control"id="valor" name="valor" value="<?php echo set_value('valor',@$r->valor); ?>" />
					<?php echo  form_error('valor');?>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					<div class="checkbox">
						<input name="activo" value="0" type="hidden" />
						<label>Activo</label>
							<input type="checkbox" name="activo" value="1" <?php echo set_checkbox('activo','1',(@$r->activo)?TRUE:FALSE); ?> />
						<?php echo  form_error('activo');?>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-12 barra-btn">
			<input class="  btn btn-primary pull-right " type="submit" value="Guardar" id="guardar" />
			<a href="<?php echo site_url('main/elementos');?>" class="btn btn-default pull-right">Cancelar</a>
			<span class="clearblock">&nbsp;</span>
		</div>
	</form>
	</div>
<?php if(!is_ajax()){ $this->load->view('layout/footer'); } ?>