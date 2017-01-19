<?php if(!is_ajax()){ $this->load->view('layout/header'); } ?>
	<div class="col-lg-12 formulario-head">
		<div class="row">
			<div class="col-lg-11 col-sm-10 col-xs-9">
				<h4><?php echo $titulo;?></h4>
			</div>
		</div>
	</div>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<p class="msg info">
		<span class="fa-stack fa-2x">
			<i class="fa fa-circle fa-stack-2x text-color-blanco"></i>
		 	<i class="fa fa-info fa-stack-1x text-color-info"></i>	
		</span>
		Los campos requeridos est&aacute;n marcados con <span class="req">*</span></p>
	</div>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
									<label><span class="req">*</span>Modelo:</label>
									<input class="form-control" id="modelo" name="modelo" value="<?php echo set_value('modelo',@$r->modelo); ?>" />
									<?php echo form_error('modelo');?>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
									<label><span class="req">*</span>Categor&iacute;a:</label>
									<select class="form-control" name="categorias_id" id="categorias_id">
										<option value=""><?php echo $this->config->item('empty_select'); ?></option>
										<?php foreach($categorias as $k=>$v):?>
										<option value="<?php echo $k; ?>" <?php echo  set_select('categorias_id', $k, ($k == @$r->categorias_id)); ?>> <?php echo $v; ?></option>
										<?php endforeach;?>
									</select>
									<?php echo form_error('categorias_id');?>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
									<label><span class="req">*</span>Sku:</label>
									<input class="form-control" id="item" name="item" value="<?php echo set_value('item',@$r->item); ?>" />
									<?php echo form_error('item');?>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
									<label><span class="req">*</span>Precio:</label>
									<input class="form-control" id="precio" name="precio" value="<?php echo set_value('precio', @$r->precio); ?>" />
									<?php echo form_error('precio');?>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
									<label><span class="req">*</span>Unidad:</label>
									<select class="form-control" name="unidad_id" id="unidad_id">
										<option value=""><?php echo $this->config->item('empty_select'); ?></option>
										<?php foreach($unidades as $k=>$v):?>
											<option value="<?php echo $k; ?>" <?php echo set_select('unidad_id', $k, ($k == @$r->unidad_id)); ?>> <?php echo $v; ?></option>
										<?php endforeach;?>
									</select>
									<?php echo form_error('unidad_id');?>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
									<input name="activo" value="0" type="hidden" />
									<div class="checkbox">
										<label>
											<input type="checkbox" name="activo" value="1" <?php echo set_checkbox('activo','1',(@$r->activo)?TRUE:FALSE); ?>/>  Activo
											<?php echo form_error('activo');?>
										</label>
									</div>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
									<input name="sin_envio" value="0" type="hidden" />
									<div class="checkbox">
										<label>
											<input type="checkbox" name="sin_envio" value="1" <?php echo set_checkbox('sin_envio','1',(@$r->sin_envio)?TRUE:FALSE); ?>/>  Sin env&iacute;o
											<?php echo form_error('sin_envio');?>
										</label>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
									<div class="checkbox">
										<label>
											<input name="ocultar" value="0" type="hidden" />
											<input type="checkbox" name="ocultar" value="1" <?php echo set_checkbox('ocultar','1',(@$r->ocultar)?TRUE:FALSE); ?> />Ocultar en cat&aacute;logo en l&iacute;nea
											<?php echo form_error('ocultar');?>
										</label>
									</div>
								</div>
							</div>

						</div>
					</div>
				</div>

				<div class="panel panel-default estados" style="border:0">
					<div class="panel panel-default">
						<div class="panel-heading" style="position: relative;">
							<span id="filas_estados" name="filas_estados" style="position: relative;"></span><?php echo form_error('filas_estados');?>
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
									Home Care Program
								</a>
							</h4>
						</div>
						<div id="collapseTwo" class="panel-collapse collapse in">
							<div class="panel-body">
								<div class="table-responsive front_table mt20" style="overflow-y: auto">
									<table class="table table-hover">
										<thead>
										<tr>
											<td><b>Estado</b></td>
											<td><b>Precio</b></td>
											<td><b>Horas <br> iniciales</b></td>
											<td><b>Horas <br> m&aacute;ximas</b></td>
											<td><b>Precio <br> hora extra</b></td>
										</tr>
										</thead>

										<tbody>
										<?php foreach($estados as $k=>$v): ?>
											<?php $postfijo = strtolower(str_replace(' ','_',$v)); ?>
											<tr>
												<td style="font-size: 12px; font-weight: bold"><?php echo $v; ?></td>
												<td>
													<input class="form-control" id="precio_inicial_<?php echo $postfijo; ?>" name="precio_inicial_<?php echo $postfijo; ?>" value="<?php echo set_value('precio_inicial_'.$postfijo, @$cursos->{'precio_inicial_'.$postfijo}); ?>" />
													<div style="position: relative;">&nbsp;<?php echo form_error('precio_inicial_'.$postfijo);?></div>
												</td>
												<td>
													<input class="form-control" id="horas_iniciales_<?php echo $postfijo; ?>" name="horas_iniciales_<?php echo $postfijo; ?>" value="<?php echo set_value('horas_iniciales_'.$postfijo, @$cursos->{'horas_iniciales_'.$postfijo}); ?>" />
													<div style="position: relative;">&nbsp;<?php echo form_error('horas_iniciales_'.$postfijo);?></div>
												</td>
												<td>
													<input class="form-control" id="horas_maximas_<?php echo $postfijo; ?>" name="horas_maximas_<?php echo $postfijo; ?>" value="<?php echo set_value('horas_maximas_'.$postfijo, @$cursos->{'horas_maximas_'.$postfijo}); ?>" />
													<div style="position: relative;">&nbsp;<?php echo form_error('horas_maximas_'.$postfijo);?></div>
												</td>
												<td>
													<input class="form-control" id="precio_horas_extra_<?php echo $postfijo; ?>" name="precio_horas_extra_<?php echo $postfijo; ?>" value="<?php echo set_value('precio_horas_extra_'.$postfijo, @$cursos->{'precio_horas_extra_'.$postfijo}); ?>" />
													<div style="position: relative;">&nbsp;<?php echo form_error('precio_horas_extra_'.$postfijo);?></div>
												</td>
											</tr>
										<?php endforeach; ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="panel panel-default">
				  	<div class="panel-heading">
				     	<h4 class="panel-title">
				       		<a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
					        Descripci&oacute;n:
					       	</a>
				     	</h4>
				  	</div>
				  	<div id="collapseThree" class="panel-collapse collapse in">
				     	<div class="panel-body">
				        	<div class="row">
				        		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						        	<label><span class="req">*</span>Contenido</label>
									<textarea id="descripcion" name="descripcion" class="w90p form-control" " rows="6"><?php echo set_value('descripcion',@$r->descripcion); ?></textarea>
									<?php echo form_error('descripcion');?>
								</div>
				        	</div>
				        </div>
				    </div>
				</div>

				<div class="panel panel-default">
				  	<div class="panel-heading">
				     	<div class="row">
					     	<div class="col-lg-11 col-sm-10 col-xs-9">
					     		<h4 class="panel-title">
						       		<a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
							      	Fotograf&iacute;as
							       	</a>
					     		</h4>
					     	</div>
					     	<div class="col-lg-1 col-sm-2 col-xs-3">
					     		<span class="btn btn-default pull-right btn-xs" ><span id="agregar_btn">&nbsp;</span></span>
								<div id="div_progreso" class="fl"></div>
							</div>
						</div>
				  	</div>

					<div id="collapseFour" class="panel-collapse collapse in">
				     	<div class="panel-body">
				        	<div class="row">
				        		<div class="col-lg-12"> 
									<?php if(empty($fotos)): ?>
									<p class="msg sin_resultados">No se ha encontrado ninguna fotograf&iacute;a para este producto.</p>
									<?php else: ?>
									<div class="table-responsive">
										<table class="table table-hover">
											<thead>
												<tr>
													<?php if(!empty($acciones_fotos)):?>		
													<td class="data_first" align="center"><input id="todos" type="checkbox"/></td>
													<?php endif; ?>
													<td>ID</td>
													<td>Foto</td>
													<td>Tipo</td>
													<td>Descripci&oacute;n</td> 
													<td>Acciones</td>
												</tr>
											</thead>
											<tfoot>
												<tr>
													<?php if(!empty($acciones_fotos)):?>
													<td>
														<a class="btn btn-default pull-left btn-xs" href="#" id="eliminar">Eliminar selecci&oacute;n</a>
													</td>
													<?php endif; ?>
													<td colspan="11" class="p0">
														<div class="col-lg-4 pull-left">
															<?php echo $paginador; ?>
														</div>
														<div class="col-lg-4 pull-right">
															<p class="pull-right"><?php echo $total>1?'Se encontraron '.$total.' resultados.':'Se encontr&oacute; 1 resultado.'?></p>
														</div>
													</td>
												</tr>
											</tfoot>
											<tbody>
												<?php $i=0; foreach($fotos as $f): ?>
												<tr class="<?php echo ($i%2!=0)?'bgcw':'altrow'; ?>">
													<?php if(!empty($acciones_fotos)):?>
													<td align="center"><input type="checkbox"   class="fotos_cb" value="<?php echo $f->id; ?>" name="fotos_id[]"/></td>
													<?php endif; ?>
													<td><?php echo $f->id; ?></td>
													<td align="center" style="max-width:120px;">
														<input type="hidden" name="foto_id[]" value="<?php echo $f->id; ?>"/>
														<?php if($this->config->item('cloudfiles')):
															if($f->extension=='jpg'):
																$path=$this->cloud_files->url_publica("files/productos/{$f->productos_id}/{$f->id}.jpg"); ?>
												            	<a href="<?php echo site_url('/thumbs/timthumb.php?src='.$path.'&zc=0&q=85&s=700&t='.time());?>" class="imagen_fancybox">
												                	<img src="<?php echo  site_url('/thumbs/timthumb.php?src='.$path.'&zc=0&q=85&s=500&t='.time());?>" class="img-thumbnail front_imgTabla"/>
												            	</a>
											            	<?php else: 
											            		$path=$this->cloud_files->url_publica("files/productos/{$f->productos_id}/{$f->id}.pdf"); ?>
											            		<a href="<?php echo $path;?>">
											            			<img class="img-thumbnail" src="<?php echo site_url('/thumbs/timthumb.php?src=img/bc/icono_pdf.jpg');?>"/>
											            		</a>
											            	<?php endif;?>
										            	<?php else: 
										            		if($f->extension=='jpg'):?>
									    					<a class="imagen_fancybox" href="<?php echo site_url('/thumbs/timthumb.php?src='.site_url("files/productos/{$f->productos_id}/{$f->id}.jpg&zc=0&q=85&s=700.&t=".time()));?>">
																<img class="img-thumbnail front_imgTabla" src="<?php echo site_url("/thumbs/timthumb.php?src=files/productos/{$f->productos_id}/{$f->id}.jpg&s=500&t=".time());?>"/>
															</a>
															<?php else: ?>
											            		<a href="<?php echo site_url("files/productos/{$productos_id}/{$f->id}.pdf");?>">
											            			<img src="<?php echo site_url('img/bc/icono_pdf.jpg');?>"/>
											            		</a>
											            	<?php endif;?>
									    				<?php endif;?>
															<br/>
													</td>
													<td>
														<select class="form-control" name="foto_tipo_id[]">
															<option value=""><?php echo $this->config->item('empty_select');?></option>
															<?php foreach(catalogo('fotografias',false,true) as $k=>$v): ?>
																<option value="<?php echo $k; ?>" <?php echo ($k == $f->tipos_id)?'selected="selected"':''; ?>><?php echo $v; ?></option>
															<?php endforeach;?>
														</select>
														<?php echo form_error('foto_tipo_id[]');?>
													</td>
													<td>
														<input class="form-control" name="foto_descripcion[]" id="foto_descripcion_<?php echo $i; ?>" value="<?php echo $f->descripcion; ?>"/>
													</td>
													<td><a onclick="return confirm('&iquest;Seguro que desea eliminar la imagen # <?php echo $f->id; ?>?');" href="<?php echo site_url("productos/fotos_eliminar/{$f->id}"); ?>" class="accion accion3">Eliminar</a></td>
												</tr>		
												<?php $i++; endforeach; ?>
											</tbody>
										</table>
									</div>
								</div>
								<?php endif; ?>
								<div id="thumbnails"></div>
				        	</div>
				        </div>
				    </div>
				</div>

				 <div class="panel panel-default">
				  	<div class="panel-heading">
			        	<div class="row">
					     	<div class="col-lg-10 col-md-9 col-sm-9 col-xs-6">
						     	<h4 class="panel-title">
						       		<a data-toggle="collapse" data-parent="#accordion" href="#collapseFive">
							        Accesorios
							       	</a>
						     	</h4>
						    </div>
					     	<div class="col-lg-2 col-md-3 col-sm-3 col-xs-6">
								<a role="button" class="pull-right btn-default btn btn-xs" href="#" id="accesorio_add">Agregar Tipo de Accesorio</a>
							</div>
						</div>
				  	</div>
					<div id="collapseFive" class="panel-collapse collapse in">
						<div class="panel-body">
				        	<div class="row">
				        		<div class="col-lg-12">
									<div class="table-responsive">	
										<table class="table table-hover" id="accesorios">
										<thead>
											<tr style="font-size: 12px">
												<td class="col-lg-8">Tipos de Accesorios</td>
												<td>Obligatorio</td>
												<td>Acciones</td>
											</tr>
										</thead>
										<tbody>
											<tr style="display: none;" class="bgcw">
												<td>
													<select class="acc_select" class="form-control" name="accesorios[tipo_accesorio_id][]">
														<option value=""><?php echo $this->config->item('empty_select');?></option>
														<?php foreach($tipos_accesorios as $k=>$v): ?>
															<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
														<?php endforeach;?>
													</select>
													<?php echo form_error('accesorios[tipo_accesorio_id][]');?>
												</td>
												<td>
												    <input class="acc_check" name="accesorios[obligatorio_id][]" value="0" type="hidden" />
													<input class="acc_check" type="checkbox" name="accesorios[obligatorio_id][]" value="1" <?php echo  set_checkbox('accesorios[obligatorio_id][]','1',(@$c->obligatorio_id)?TRUE:FALSE); ?> />
													<?php echo form_error('accesorios[obligatorio_id][]');?>
												</td>
												<td colspan="2" align="center"><a href="#" class="accion accion3">Eliminar</a></td>
											</tr>
											<?php $i=1; foreach($accesorios as $c=>$acc):?>
											<tr class="bgcw">
												<td>
													<select name="accesorios[tipo_accesorio_id][<?php echo $i ?>]">
														<option value=""><?php echo $this->config->item('empty_select');?></option>
														<?php foreach($tipos_accesorios as $k=>$v): ?>
														<option value="<?php echo $k; ?>" <?php echo ($k == $acc->tipos_accesorios_id)?'selected="selected"':''; ?>><?php echo $v; ?></option>
														<?php endforeach;?>
													</select>
												</td>
												<td>
												    <input name="accesorios[obligatorio_id][<?php echo $i ?>]" value="0" type="hidden" />
													<input type="checkbox" name="accesorios[obligatorio_id][<?php echo $i ?>]" value="1" <?php echo  set_checkbox('accesorios[obligatorio_id][]','1',(@$acc->obligatorio_id)?TRUE:FALSE); ?> />
													<?php echo form_error('accesorios[obligatorio_id][]');?>
												</td>
												<td align="center"><a href="#" class="delete col_d">Eliminar</a></td>
											</tr>
											<?php $i++; endforeach;?>
										</tbody>
										</table>
									</div>
								</div>
				        	</div>
			        	</div>
					</div>
				 </div>

				 <div class="panel panel-default">
					<div class="panel-heading">
				      <h4 class="panel-title">
				        <a data-toggle="collapse" data-parent="#accordion" href="#collapseSix">
				          Gu&iacute;a Mec&aacute;nica
				        </a>
				      </h4>
				    </div>
				    <div id="collapseSix" class="panel-collapse collapse in">
					   	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<p class="msg info">
							<span class="fa-stack fa-2x">
							  <i class="fa fa-circle fa-stack-2x text-color-blanco"></i>
							  <i class="fa fa-info fa-stack-1x text-color-info"></i>
							</span>
							Seleccione un archivo para agregar la gu&iacute;a mec&aacute;nica de este producto. </p>
						</div>
					    <div class="panel-body">
					        <div class="row">
					        	<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
					        		<label>Gu&iacute;a Mec&aacute;nica:</label>
					        		<div class="fileinputs">
										<input id="guia_mecanica" name="guia_mecanica" value="<?php echo set_value('guia_mecanica'); ?>" type="file" accept="image/jpg, application/pdf"/>
										<div class="fakefile">
											<a href="" class="btn btn-front-primary"><i class="fa fa-upload"></i>  Subir Archivo</a>
										</div>
									</div>
									<span><?php echo form_error('guia_mecanica');?></span>
								</div>
								<?php if($this->config->item('cloudfiles') && !empty($r->guia_mecanica_orden)):
									$orden = $r->guia_mecanica_orden?'_'.$r->guia_mecanica_orden:'';
									$path=$this->cloud_files->url_publica("files/productos/{$r->id}/guia_mecanica{$orden}.{$r->guia_mecanica_extension}"); ?>
									<?php if(!empty($r->guia_mecanica_extension) && $r->guia_mecanica_extension=='jpg' && $orden):?>
						            	<a href="<?php echo site_url('/thumbs/timthumb.php?src='.$path.'&zc=0&q=85&s=700&t='.time());?>" class="imagen_fancybox">
						                	<img src="<?php echo  site_url('/thumbs/timthumb.php?src='.$path.'&zc=0&q=85&s=500&t='.time());?>" class="img-thumbnail">
						            	</a>
					            	<?php elseif(!empty($r->guia_mecanica_extension) && $r->guia_mecanica_extension!='jpg' && $orden):?>
						            	<a href="<?php echo $path;?>">
						                	<img src="<?php echo site_url('/thumbs/timthumb.php?src='.site_url("img/bc/icono_pdf.jpg").'&s='.$this->config->item('logo_thumb_size'));?>" class="img_thumb" />
						            	</a>
					            	<?php endif;?>
				            	<?php else: ?>
									<?php if(@$r->guia_mecanica_extension=='jpg'):?>
						            	<a class="imagen_fancybox" href="<?php echo site_url("/thumbs/timthumb.php?src=files/productos/{$r->id}/guia_mecanica.jpg&zc=0&q=85&s=700&t=".time());?>" class="bc_colorbox_photo">
											<img class="img-thumbnail" src="<?php echo site_url("/thumbs/timthumb.php?src=files/productos/{$r->id}/guia_mecanica.jpg&zc=0&q=85&s=500&t=".time());?>" />
										</a>
					            	<?php elseif(@$r->guia_mecanica_extension=='pdf'):?>
						            	<a href="<?php echo site_url("files/productos/{$r->id}/guia_mecanica.{$r->guia_mecanica_extension}");?>">
						                	<img src="<?php echo site_url('/thumbs/timthumb.php?src='.site_url("img/bc/icono_pdf.jpg").'&s='.$this->config->item('logo_thumb_size'));?>" class="img_thumb" />
						            	</a>
					            	<?php endif;?>
			    				<?php endif;?>
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
                    <div id="collapseSeven" class="panel-collapse collapse in">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <p class="msg info">
							<span class="fa-stack fa-2x">
							  <i class="fa fa-circle fa-stack-2x text-color-blanco"></i>
							  <i class="fa fa-info fa-stack-1x text-color-info"></i>
							</span>
                                Seleccione un archivo para agregar el manual de este producto.</p>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
                                    <label>Manual:</label>
                                    <div class="fileinputs">
                                        <input id="manual" name="manual" value="<?php echo set_value('manual'); ?>" type="file" accept="image/jpg, application/pdf"/>
                                        <div class="fakefile">
                                            <a href="" class="btn btn-front-primary"><i class="fa fa-upload"></i>  Subir Archivo</a>
                                        </div>
                                    </div>
                                    <span><?php echo form_error('manual');?></span>
                                </div>
                                <?php if($this->config->item('cloudfiles') && !empty($r->manual_orden)):
                                    $orden = $r->manual_orden?'_'.$r->manual_orden:'';
                                    $path=$this->cloud_files->url_publica("files/productos/{$r->id}/manual{$orden}.{$r->manual_extension}"); ?>
                                    <?php if(!empty($r->manual_extension) && $r->manual_extension=='jpg'):?>
                                    <a href="<?php echo site_url('/thumbs/timthumb.php?src='.$path.'&zc=0&q=85&s=700&t='.time());?>" class="imagen_fancybox">
                                        <img src="<?php echo  site_url('/thumbs/timthumb.php?src='.$path.'&zc=0&q=85&s=500&t='.time());?>" class="img-thumbnail">
                                    </a>
                                <?php elseif(!empty($r->manual_extension) && $r->manual_extension!='jpg'):?>
                                    <a href="<?php echo $path;?>">
                                        <img src="<?php echo site_url('/thumbs/timthumb.php?src='.site_url("img/bc/icono_pdf.jpg").'&s='.$this->config->item('logo_thumb_size'));?>" class="img_thumb" />
                                    </a>
                                <?php endif;?>
                                <?php else: ?>
                                    <?php if(@$r->manual_extension=='jpg'):?>
                                        <a class="imagen_fancybox" href="<?php echo site_url("/thumbs/timthumb.php?src=files/productos/{$r->id}/manual.jpg&zc=0&q=85&s=700&t=".time());?>" class="bc_colorbox_photo">
                                            <img class="img-thumbnail" src="<?php echo site_url("/thumbs/timthumb.php?src=files/productos/{$r->id}/manual.jpg&zc=0&q=85&s=500&t=".time());?>" />
                                        </a>
                                    <?php elseif(@$r->manual_extension=='pdf'):?>
                                        <a href="<?php echo site_url("files/productos/{$r->id}/manual.{$r->manual_extension}");?>">
                                            <img src="<?php echo site_url('/thumbs/timthumb.php?src='.site_url("img/bc/icono_pdf.jpg").'&s='.$this->config->item('logo_thumb_size'));?>" class="img_thumb" />
                                        </a>
                                    <?php endif;?>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseEigth">
                                Autocad
                            </a>
                        </h4>
                    </div>
                    <div id="collapseEigth" class="panel-collapse collapse in">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <p class="msg info">
							<span class="fa-stack fa-2x">
							  <i class="fa fa-circle fa-stack-2x text-color-blanco"></i>
							  <i class="fa fa-info fa-stack-1x text-color-info"></i>
							</span>
                                Seleccione un archivo zip para agregar el archivo autocad de este producto.</p>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
                                    <label>Autocad:</label>
                                    <div class="fileinputs">
                                        <input id="autocad" name="autocad" value="<?php echo set_value('autocad'); ?>" type="file" accept="application/zip"/>
                                        <div class="fakefile">
                                            <a href="" class="btn btn-front-primary"><i class="fa fa-upload"></i>  Subir Archivo</a>
                                        </div>
                                    </div>
                                    <span><?php echo form_error('autocad');?></span>
                                </div>
                                <?php if($this->config->item('cloudfiles') && !empty($r->autocad_orden)):
                                    $orden = $r->autocad_orden?'_'.$r->autocad_orden:'';
                                    $path=$this->cloud_files->url_publica("files/productos/{$r->id}/autocad{$orden}.{$r->autocad_extension}");
                                    ?>
                                    <?php if(!empty($r->autocad_extension) && ($r->autocad_extension=='zip' || $r->autocad_extension=='ZIP')):?>
                                    <a href="<?php echo $path;?>">
                                        <img src="<?php echo site_url('/thumbs/timthumb.php?src='.site_url("img/bc/icono_autocad.png").'&s='.$this->config->item('logo_thumb_size'));?>" class="img_thumb" />
                                    </a>
                                <?php endif;?>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                </div>
                                        
                
				<div class="panel panel-default">
				  	<div class="panel-heading">
			     		<h4 class="panel-title">
				       		<a data-toggle="collapse" data-parent="#accordion" href="#collapseNine">
					      	Debug
					       	</a>
			     		</h4>
				  	</div>
				  	<div id="collapseNine" class="panel-collapse collapse in">
				     	<div class="panel-body">
				        	<div class="row">
				        		<?php if($this->config->item('debug') && $this->session->userdata('grupos_id')==1): ?>
								<div class="panel_content" style="display:none;">
									<textarea class="form-control" id="SWFUpload_Console" style="font-family: monospace; overflow: auto; width: 90%; height: 350px; margin: 5px;"></textarea>
								</div>
								<?php endif; ?>
				        	</div>
				        </div>
				    </div>
				</div>

			</div><!-- panel group -->
			<div class="barra-btn">
				<input  class="btn btn-primary pull-right" type="submit" value="Guardar" id="guardar" />
				<a href="<?php echo site_url('productos/index');?>"  class="btn btn-default pull-right">Cancelar</a>
			</div>
		</form>
	</div>
	<!-- FINACCESORIOS -->
<script type="text/javascript" src="<?php echo base_url(); ?>js/swfUpload/swfupload.js"></script>
<?php if($this->config->item('debug') && $this->session->userdata('grupos_id')==1): ?>
<script type="text/javascript" src="<?php echo base_url(); ?>js/swfUpload/fileprogress.js"></script>
<?php endif; ?>
<script type="text/javascript" src="<?php echo base_url(); ?>js/swfUpload/handlers.js"></script>
<script type="text/javascript">
<!--
$(function(){
		var swfu;
		function set_swfupload() 
		{
			swfu = new SWFUpload({
				// Backend Settings
				upload_url: "<?php echo site_url('/productos/fotos_agregar/'.$r->id); ?>",
				post_params: {"cookie_session": '<?php echo $sesion; ?>'},

				// File Upload Settings
				file_size_limit : "8 MB",
				file_types : "*.jpg;*.pdf",
				file_types_description : "JPG Images",
				file_upload_limit : 0,

				// Event Handler Settings - these functions as defined in Handlers.js
				//  The handlers are not part of SWFUpload but are part of my website and control how
				//  my website reacts to the SWFUpload events.
				swfupload_preload_handler : preLoad,
				swfupload_load_failed_handler : loadFailed,
				file_queue_error_handler : fileQueueError,
				file_dialog_complete_handler : fileDialogComplete,
				upload_progress_handler : uploadProgress,
				upload_error_handler : uploadError,
				upload_success_handler : uploadSuccess,
				upload_complete_handler : uploadComplete,

				// Button Settings
				
				button_placeholder_id : "agregar_btn",
				button_width: 80,
				button_height: "16",
				button_text_style : '.button {color:#FFFFFF;  font-family: sans-serif; font-size: 11px;}',
				button_text : '<span class="button">Agregar foto</span>',
				button_text_top_padding: 0,
				button_text_left_padding: 0,
				
				button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
				button_cursor: SWFUpload.CURSOR.HAND,
				
				// Flash Settings
				flash_url : "<?php echo base_url(); ?>js/swfUpload/swfupload.swf",
				flash9_url : "<?php echo base_url(); ?>js/swfUpload/swfupload_FP9.swf",

				custom_settings : {
					upload_target : "div_progreso"
				},
				
				// Debug Settings
				<?php if($this->config->item('debug') && $this->session->userdata('grupos_id')==1): ?>
				debug: true
				<?php else:?>
				debug: false
				<?php endif; ?>
			});
		};
		set_swfupload();
		//ORDENAR FOTOS
		$("#fotos tbody").sortable({
			change:function(){
				$('#cambio').val('1');
			}
		});

		$('#todos').change(function(){
			$('input:checkbox.fotos_cb').attr('checked',$('#todos').attr('checked'));
		});

		function get_selected()
		{
			var allVals = [];
			$('input:checkbox.fotos_cb:checked').each(function(){
				if($(this).val()!='on')
					allVals.push($(this).val());
			});
			var ids=allVals.join('_');
			return ids;
		} 
		
		$('#eliminar').click(function(e){
			e.preventDefault();
			var ids=get_selected();
			if(!ids)
				alert('Debe seleccionar al menos un registro para procesar.');
			else
			{
			var action=$('#form').attr('action');
			<?php if(isset($acciones_fotos[1])): ?>
			if(confirm('¿Seguro que desea eliminar las imagenes seleccionados? ESTA ACCIÓN NO PUEDE DESHACERSE.'))
			{		
				$('#form').attr('action',BASE_URL+'productos/fotos_eliminar_varios/'+ids);
			}
			<?php endif; ?>				
			$('#form').submit();
			$('#form').attr('action',action);		
			}
		});		
});
//-->		
</script>
<script type="text/javascript">
<!--
$(function(){

	$(document).ready(function(){
		if($('#unidad_id').val() == 2)
			$('.estados').show();
		else
			$('.estados').hide();
	});

	$('#unidad_id').change(function(){
		if($('#unidad_id').val() == 2)
			$('.estados').show();
		else
			$('.estados').hide();
	});

	/* AGREGAR ACCESORIO */
	$('#accesorio_add').click(function(e){
		var row = $('#accesorios tbody>tr:first').clone(true);
		var i = $('#accesorios tbody>tr').length;
		row.find(".acc_select").attr('name', 'accesorios[tipo_accesorio_id]['+i+']');
		row.find(".acc_check").attr('name', 'accesorios[obligatorio_id]['+i+']');
		row.find('input:text').val("");
		row.find('td.c_id').html('&nbsp;');
		row.insertAfter('#accesorios tbody>tr:last');
		row.show();
		$('#cambio').val('1');
		return false;
	});
	/* FIN AGREGAR ACCESORIO */

	/* ELIMINAR ACCESORIO */
	$('.col_d').click(function(){
		if(confirm('¿Seguro que desea eliminar este tipo accesorio?'))
		{
			$('#cambio').val('1');
			$(this).closest('tr').remove();
		}	
		return false;
	});
	/* FIN ELIMINAR ACCESORIO */
});
//-->
</script>
<script language="javascript" type="text/javascript" src="<?php echo base_url(); ?>/js/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo base_url(); ?>/js/tiny_mce/basic_config.js"></script>
<?php if(!is_ajax()){ $this->load->view('layout/footer'); } 