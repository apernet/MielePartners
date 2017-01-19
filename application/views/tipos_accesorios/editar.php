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
		<input type="hidden" name="id" value="<?php echo set_value('id',@$r->id); ?>" />
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
								<input class="form-control" id="nombre" name="nombre" value="<?php echo set_value('nombre',@$r->nombre); ?>" />
								<?php echo form_error('nombre');?>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
								<label><span class="req">*</span>Descripci&oacute;n:</label>
								<input class="form-control" id="descripcion" name="descripcion" value="<?php echo set_value('descripcion',@$r->descripcion); ?>" />
								<?php echo form_error('descripcion');?>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 checkbox">
								<input name="activo" value="0" type="hidden" />
								<div class="checkbox">
									<label>
									<input type="checkbox" name="activo" value="1" <?php echo set_checkbox('activo','1',@($r->activo == 1)); ?> />Activo
									</label>
								</div>
								<?php echo form_error('activo');?>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
								<label>Descuento Base ( venta individual ):</label>
								<div class="input-group">
									<input class="form-control text-right" id="descuento_base" name="descuento_base" value="<?php echo set_value('descuento_base',@($r->descuento_base? $r->descuento_base : 0)); ?>" />
									<span class="input-group-addon">&#37;</span>
								</div>
								<?php echo form_error('descuento_base');?>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
								<label>Descuento Opcional ( venta individual ):</label>
								<div class="input-group">
									<input class="form-control text-right" id="descuento_opcional" name="descuento_opcional" value="<?php echo set_value('descuento_opcional',@($r->descuento_opcional? $r->descuento_opcional : 0)); ?>" />
									<span class="input-group-addon">&#37;</span>
								</div>
								<?php echo form_error('descuento_opcional');?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="panel panel-default">
			    <div class="panel-heading">
			      <h4 class="panel-title">
					 <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
		          	Imagen 
			        </a>
			      </h4>
				</div>
				
				<div id="collapseThree" class="panel-collapse collapse in">
				    <div class="panel-body">
				        <div class="row">
				        	<div class="col-lg-2 col-md-3 col-sm-3 col-xs-6 form-group">
								<label>Imagen:</label>
								<div class="fileinputs">
									<input id="imagen_rubrica" name="imagen" value="<?php echo set_value('imagen'); ?>" type="file"/>
									<div class="fakefile">
										<a href="" class="btn btn-front-primary"><i class="fa fa-upload"></i>  Subir Archivo</a>
									</div>
								</div>
							</div>
							<div class="col-lg-2 col-md-3 col-sm-3 col-xs-6">
								<?php if($this->config->item('cloudfiles')): 
									$orden = @$r->imagen_orden?'_'.$r->imagen_orden:'';
									$path=$this->cloud_files->url_publica("files/tipos_accesorios/{$r->id}{$orden}.jpg"); ?>
					            	<a href="<?php echo site_url('/thumbs/timthumb.php?src='.$path.'&zc=0&q=85&s=700&t='.time());?>" class="imagen_fancybox">
					                	<img src="<?php echo  site_url('/thumbs/timthumb.php?src='.$path.'&zc=0&q=85&s=500&t='.time());?>" class="img-thumbnail">
					            	</a>
				            	<?php else: ?>
			    					<a href="<?php echo site_url('/thumbs/timthumb.php?src='.site_url("files/tipos_accesorios/".@$r->id.".jpg&zc=0&q=85&s=700&t=".time()));?>" class="imagen_fancybox">
										<img class="img-thumbnail" src="<?php echo site_url('/thumbs/timthumb.php?src=files/tipos_accesorios/'.@$r->id.'.jpg&zc=0&q=85&s=500&t='.time());?>" class="img_thumb" />
									</a>
			    				<?php endif;?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
			<div class="col-lg-12 barra-btn">
				<input  class="btn btn-primary pull-right" type="submit" value="Guardar" id="guardar" />
				<a href="<?php echo site_url('accesorios/tipos_accesorios');?>"  class="btn btn-default pull-right">Cancelar</a>
			</div>
		</form>
		</div>
	</div>
<?php if(!is_ajax()){ $this->load->view('layout/footer'); } ?>