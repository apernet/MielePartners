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
				        		<label><span class="req">*</span>Nombre:</label>
								<input class="form-control" id="nombre" name="nombre" value="<?php echo set_value('nombre',@$r->nombre); ?>" />
								<?php echo form_error('nombre');?>
				        	</div>
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				        		<label><span class="req">*</span>Modelo:</label>
								<input class="form-control" id="modelo" name="modelo" value="<?php echo set_value('modelo',@$r->modelo); ?>" />
								<?php echo form_error('modelo');?>
				        	
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					        	<label><span class="req">*</span>SKU:</label>
								<input class="form-control" id="item" name="item" value="<?php echo set_value('item',@$r->item); ?>" />
								<?php echo form_error('item');?>
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				        		<label><span class="req">*</span>Tipo de accesorio:</label>
								<select class="form-control" name="tipos_accesorios_id" id="tipos_accesorios_id">
									<option value=""><?php echo $this->config->item('empty_select'); ?></option>
									<?php foreach($tipos_accesorios as $k=>$v):?>
									<option value="<?php echo $k; ?>" <?php echo  set_select('tipos_accesorios_id', $k, ($k == @$r->tipos_accesorios_id)); ?>> <?php echo $v; ?></option>
									<?php endforeach;?>
								</select>
								<?php echo form_error('tipos_accesorios_id');?>
				        	</div>
				        </div>
				        <div class="row">
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				        		<label><span class="req">*</span>Precio:</label>
								<input class="form-control" id="precio" name="precio" value="<?php echo set_value('precio'); ?>" />
								<?php echo form_error('precio');?>
				        	</div>
							<!--<div class="col-lg-3 form-group">
								<label>Descuento Base ( venta individual ):</label>
								<div class="input-group">
									<input class="form-control text-right" id="descuento_base" name="descuento_base" value="<?php //echo set_value('descuento_base'); ?>" />
									<span class="input-group-addon">&#37;</span>
								</div>
								<?php //echo form_error('descuento_base');?>
							</div>
							<div class="col-lg-4 form-group">
								<label>Descuento Opcional ( venta individual ):</label>
								<div class="input-group">
									<input class="form-control text-right" id="descuento_opcional" name="descuento_opcional" value="<?php //echo set_value('descuento_opcional'); ?>" />
									<span class="input-group-addon">&#37;</span>
								</div>
								<?php //echo form_error('descuento_opcional');?>
							</div>-->
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
								<div class="checkbox">
									<input  name="consumible" value="0" type="hidden" />
									<label>Consumible</label>
									<input type="checkbox" name="consumible" value="1" <?php echo set_checkbox('consumible','1',@($r->consumible == 1)); ?> id="consumible"/>
									<?php echo form_error('consumible');?>
				     			</div>
				     		</div>
							<!--</div>
					  		<div class="row">-->
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
								<div class="checkbox">
									<input name="activo" value="0" type="hidden" />
									<label>Activo</label>
									<input type="checkbox" name="activo" value="1" <?php echo set_checkbox('activo','1',TRUE); ?> />
									<?php echo form_error('activo');?>
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
				          Descripción 
				        </a>
				      </h4>
				    </div>
				    <div id="collapseTwo" class="panel-collapse collapse in">
				    <div class="panel-body">
				        <div class="row">
				        	<div class="col-lg-12">	  
		      					<label><span class="req">*</span>Contenido</label>	
		      					<textarea id="descripcion" name="descripcion" class="form-control" rows="10"><?php echo set_value('descripcion',@$r->descripcion); ?></textarea>
								<?php echo form_error('descripcion');?>
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
				        	<div class="col-lg-2 ">
								<label>Imagen:</label>
								<div class="fileinputs">
									<input class="file" id="imagen_rubrica" name="imagen" value="<?php echo set_value('imagen'); ?>" type="file"/>
									<div class="fakefile">
										<a href="" class="btn btn-front-primary"><i class="fa fa-upload"></i>  Subir Archivo</a>
									</div>
								</div>
								<span class="ml80"></span>	
							</div>
						</div>
					</div>
				</div>
			</div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseFive">
                                    Gu&iacute;a Mec&aacute;nica
                                </a>
                            </h4>
                        </div>
                        <div id="#collapseFive" class="panel-collapse collapse in">
                            <div class="col-lg-12">
                                <p class="msg info">
							<span class="fa-stack fa-2x">
							  <i class="fa fa-circle fa-stack-2x text-color-blanco"></i>
							  <i class="fa fa-info fa-stack-1x text-color-info"></i>
							</span>
                                    Seleccione un archivo para agregar la gu&iacute;a mec&aacute;nica de este accesorio. </p>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-2 col-xs-4">
                                        <label>Gu&iacute;a Mec&aacute;nica:</label>
                                        <div class="fileinputs">
                                            <input id="guia_mecanica" name="guia_mecanica" value="<?php echo set_value('guia_mecanica'); ?>" type="file"/>
                                            <div class="fakefile">
                                                <a href="" class="btn btn-front-primary"><i class="fa fa-upload"></i>  Subir Archivo</a>
                                            </div>
                                        </div>
                                        <span><?php echo form_error('guia_mecanica');?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseSeven">
                                    Manual
                                </a>
                            </h4>
                        </div>
                        <div id="#collapseSeven" class="panel-collapse collapse in">
                            <div class="col-lg-12">
                                <p class="msg info">
                                <span class="fa-stack fa-2x">
                                  <i class="fa fa-circle fa-stack-2x text-color-blanco"></i>
                                  <i class="fa fa-info fa-stack-1x text-color-info"></i>
                                </span>
                                    Seleccione un archivo para agregar el manual de este accesorio.</p>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-2 col-xs-4">
                                        <label>Manual:</label>
                                        <div class="fileinputs">
                                            <input id="manual" name="manual" value="<?php echo set_value('manual'); ?>" type="file"/>
                                            <div class="fakefile">
                                                <a href="" class="btn btn-front-primary"><i class="fa fa-upload"></i>  Subir Archivo</a>
                                            </div>
                                        </div>
                                        <span><?php echo form_error('manual');?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
			<div class="col-lg-12 barra-btn">
				<input class="btn btn-primary pull-right" type="submit" value="Guardar" id="guardar" />
				<a href="<?php echo site_url('accesorios/index');?>" class="btn btn-default pull-right">Cancelar</a>
				<div class="clearblock">&nbsp;</div>
			</div>
		</div>
	</form>
	</div>
	<!--<script type="text/javascript">
		$(function(){
			habilitar_si('consumible',0,'descuento_base');
			habilitar_si('consumible',0,'descuento_opcional');
			$('#consumible').trigger('change');
		});
	</script>-->
<?php if(!is_ajax()){ $this->load->view('layout/footer'); } ?>