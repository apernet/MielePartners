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
				<form action="<?php echo site_url(uri_string()); ?>" id="form" method="post" enctype="multipart/form-data">
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
				        		<label><span class="req">*</span>Clave:</label>
								<input class="form-control" id="clave" name="clave" value="<?php echo set_value('clave'); ?>" />
								<?php echo  form_error('clave');?>
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				        		<label><span class="req">*</span>Nombre:</label>
								<input class="form-control" id="nombre" name="nombre" value="<?php echo set_value('nombre'); ?>" />
								<?php echo  form_error('nombre');?>
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				        		<label><span class="req">*</span>Descripci&oacute;n:</label>
								<input class="form-control" id="descripcion" name="descripcion" value="<?php echo set_value('descripcion'); ?>" />
								<?php echo  form_error('descripcion');?>
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				        		<label>Categor&iacute;a Padre:</label>
								<select class="form-control" name="parent" id="parent">
									<option value=""><?php echo $this->config->item('empty_select'); ?></option>
									<?php foreach($categorias as $k=>$v):?>
										<option value="<?php echo $k; ?>" <?php echo set_select('parent', $k); ?>> <?php echo $v; ?></option>
									<?php endforeach;?>
								</select>
								<?php echo  form_error('parent');?>
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				        		<label>Descuento Base:</label>
								<div class="input-group">
				        		  <input class="form-control text-right" id="descuento_base" name="descuento_base" value="<?php echo set_value('descuento_base',@($r->descuento_base? $r->descuento_base : 0)); ?>" />
				        		  <span class="input-group-addon">&#37;</span>
				        		</div>
				        		<?php echo  form_error('descuento_base');?>
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				        		<label>Descuento Exhibici&oacute;n:</label>
				        		<div class="input-group">
				        		  <input class="form-control text-right" id="descuento_exhibicion" name="descuento_exhibicion" value="<?php echo set_value('descuento_exhibicion',@($r->descuento_exhibicion? $r->descuento_exhibicion : 0)); ?>" />
				        		  <span class="input-group-addon">&#37;</span>
				        		</div>
				        		<?php echo  form_error('descuento_exhibicion');?>
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				        		<label>Descuento Opcional:</label>
				        		<div class="input-group">
				        		  <input class="form-control text-right" id="descuento_opcional" name="descuento_opcional" value="<?php echo set_value('descuento_opcional',@($r->descuento_opcional? $r->descuento_opcional : 0)); ?>" />
				        		  <span class="input-group-addon">&#37;</span>
				        		</div>
				        		<?php echo  form_error('descuento_opcional');?>
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				        		<label>Comisi&oacute;n Vendedor:</label>
				        		<div class="input-group">
				        		  <input class="form-control text-right" id="comision_vendedor" name="comision_vendedor" value="<?php echo set_value('comision_vendedor',@($r->comision_vendedor? $r->comision_vendedor : 0)); ?>" />
				        		  <span class="input-group-addon">&#37;</span>
				        		</div>
				        		<?php echo  form_error('comision_vendedor');?>
				        	</div>
				        </div>
						  <div class="row">
							  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
								  <label>Cargo Por Recuperaci&oacute;n:</label>
								  <div class="input-group">
									  <input class="form-control text-right" id="cargo_recuperacion" name="cargo_recuperacion" value="<?php echo set_value('cargo_recuperacion',@$r->cargo_recuperacion); ?>" />
									  <span class="input-group-addon">&#37;</span>
								  </div>
								  <?php echo  form_error('cargo_recuperacion');?>
							  </div>
						  </div>
                          <div class="row">
                              <div class="col-lg-12 form-group">
                                  <label>Informaci&oacute;n General</label>
                                  <textarea id="informacion_general" name="informacion_general" class="w90p form-control" " rows="6"><?php echo set_value('informacion_general',@$r->informacion_general); ?></textarea>
                                  <?php echo  form_error('informacion_general');?>
                              </div>
                          </div>
                          <div class="row">
                              <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
                                  <div class="checkbox">
                                      <input name="activo" value="0" type="hidden" />
                                      <label>
                                          <input type="checkbox" name="activo" value="1" <?php echo set_checkbox('activo','1',TRUE); ?> />
                                          Activo
                                      </label>
                                      <?php echo  form_error('activo');?>
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
				          Imagen 
				        </a>
				      </h4>
				    </div>
				    <div id="collapseTwo" class="panel-collapse collapse in">
				   	<div class="col-lg-12">
						<p class="msg info">
						<span class="fa-stack fa-2x">
						  <i class="fa fa-circle fa-stack-2x text-color-blanco"></i>
						  <i class="fa fa-info fa-stack-1x text-color-info"></i>
						</span>
						Seleccione un archivo de imagen para agregarla a destacados.</p>
					</div>
					<div class="panel-body">
				        <div class="row">
				        	<div class="col-lg-2 col-md-3 col-sm-3 col-xs-6">
				        		<label>Cargar imagen:</label>
				        		<div class="fileinputs">
								<input class="file" id="imagen_rubrica" name="imagen" value="<?php echo set_value('imagen'); ?>" type="file"/>
								<div class="fakefile">
									<a href="" class="btn btn-front-primary"><i class="fa fa-upload"></i>  Subir Archivo</a>
								</div>
								</div>
								<span class="ml80"><?php echo form_error('imagen');?></span>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
							V&iacute;deo
						</a>
					</h4>
				</div>
				<div id="collapseThree" class="panel-collapse collapse in">
					<div class="col-lg-12">
						<p class="msg info">
				<span class="fa-stack fa-2x">
				  <i class="fa fa-circle fa-stack-2x text-color-blanco"></i>
				  <i class="fa fa-info fa-stack-1x text-color-info"></i>
				</span>
							Seleccione un archivo de v&iacute;deo para agregarlo a la categor&iacute;a.</p>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-2 col-md-3 col-sm-3 col-xs-6">
								<label>Cargar v&iacute;deo:</label>
								<div class="fileinputs">
									<input class="file" id="video" name="video" value="<?php echo set_value('video'); ?>" type="file"/>
									<div class="fakefile">
										<a href="" class="btn btn-front-primary"><i class="fa fa-upload"></i>  Subir Archivo</a>
									</div>
								</div>
								<span class="ml80"><?php echo form_error('video');?></span>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-lg-12 barra-btn">
				<input class="btn btn-primary pull-right" type="submit" value="Guardar" id="guardar" />
				<a href="<?php echo site_url('categorias/index');?>" class="btn btn-default pull-right">Cancelar</a>
				<div class="clearblock">&nbsp;</div>
			</div>
		</div>
	</form>
	</div>
<?php if(!is_ajax()){ $this->load->view('layout/footer'); } ?>