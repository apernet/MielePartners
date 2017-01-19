<?php if(!is_ajax()){ $this->load->view('layout/header'); } ?>
	<div class="col-lg-12 formulario-head">
		<div class="row">
			<div class="col-lg-12 col-sm-12 col-xs-12">
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
			Los campos requeridos est&aacute;n marcados con <span class="req">*</span>, la promoci&oacute;n tiene que asignarse al menos a una categor&iacute;a, producto, evento, accesorio o consumible.</p>
	</div>

	<div class="col-lg-12">
		<form action="<?php echo site_url(uri_string()); ?>" id="form" method="post" enctype="multipart/form-data">
			<div class="panel-group">

				<!-- Información general -->
				<div class="panel panel-default">

					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Informaci&oacute;n general</a>
						</h4>
					</div>

					<div id="collapseOne" class="panel-collapse collapse in">
						<div class="panel-body">

							<div class="row">
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
									<label><span class="req">*</span>Nombre:</label>
									<input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo @$promocion->nombre; ?>" />
									<?php echo form_error('nombre');?>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
									<label><span class="req">*</span>V&aacute;lida desde:</label>
									<input id="vigencia_desde_alt" name="vigencia_desde" value="<?php echo set_value('vigencia_desde',@$promocion->vigencia_desde); ?>" type="hidden" />
									<input id="vigencia_desde" class="form-control fecha" type="text" value="<?php echo get_fecha(set_value('vigencia_desde',@$promocion->vigencia_desde));?>" />
									<?php echo form_error('vigencia_desde');?>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
									<label><span class="req">*</span>V&aacute;lida hasta:</label>
									<input id="vigencia_hasta_alt" name="vigencia_hasta" value="<?php echo set_value('vigencia_hasta',@$promocion->vigencia_hasta); ?>" type="hidden" />
									<input id="vigencia_hasta" class="form-control fecha" type="text" value="<?php echo get_fecha(set_value('vigencia_hasta',@$promocion->vigencia_hasta));?>" />
									<?php echo form_error('vigencia_hasta');?>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
									<label>Monto de compra:</label>
									<input type="text" class="form-control" id="monto_minimo" name="monto_minimo" value="<?php echo  @$promocion->monto_minimo; ?>" />
									<?php echo form_error('monto_minimo');?>
								</div>
							</div>

						</div>
					</div>

				</div>
				<!-- Fin Información general -->

				<!-- Participantes de la promoción -->
				<div class="panel panel-default">

					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
								<span class="req">*</span>Participantes en la promoci&oacute;n
							</a>
						</h4>
					</div>

					<div id="collapseTwo" class="panel-collapse collapse in">
						<div class="panel-body">

							<div class="row">

								<!-- Categorias -->
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

									<div class="panel-body">
										<fieldset class="scheduler-border">

											<legend class="scheduler-border">Categor&iacute;as:</legend>
											<div class="row">
												<div class="col-lg-5 col-md-5 col-sm-5 col-xs-4">
													<label>Categoria:</label>
												</div>
												<div class="col-lg-5 col-md-5 col-sm-5 col-xs-4">
													<label>Cantidad:</label>
												</div>
												<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
													<input type="button" value="+" class="agregarCategoria" style="width: 25px; height: 25px">
													<span id="participantes" name="participantes"></span>
													<?php echo form_error('participantes');?>
												</div>
											</div>

											<div id="categorias_add">
												<div class="row" style="display:none;">
													<div class="col-lg-5 col-md-5 col-sm-5 col-xs-4">
														<select style="width: 100%" id="categorias[]" name="categorias[]" >
															<option value=""><?php echo $this->config->item('empty_select'); ?></option>
															<?php foreach($categorias as $k=>$v):?>
																<option value="<?php echo $k;?>"><?php echo $v;?></option>
															<?php endforeach;?>
														</select>
													</div>
													<div class="col-lg-5 col-md-5 col-sm-5 col-xs-4">
														<input type="text" class="form-control" id="cantidadCategorias[]" name="cantidadCategorias[]" value="" />
													</div>
													<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
														<input type="button" value="-" class="eliminarCategoria" style="width: 25px; height: 25px">
													</div>
												</div>

												<?php if(@$categorias_promocion): ?>
													<?php foreach($categorias_promocion as $clave=>$valor): ?>
														<div class="row">
															<div class="col-lg-5 col-md-5 col-sm-5 col-xs-4">
																<select style="width: 100%" class="selectsCategoria" id="categorias[]" name="categorias[]" >
																	<option value=""><?php echo $this->config->item('empty_select'); ?></option>
																	<?php foreach($categorias as $k=>$v):?>
																		<option value="<?php echo $k;?>" <?php echo ($k == $clave)?'selected="selected"':''; ?>><?php echo $v;?></option>
																	<?php endforeach;?>
																</select>
															</div>
															<div class="col-lg-5 col-md-5 col-sm-5 col-xs-4">
																<input type="text" class="form-control" id="cantidadCategorias[]" name="cantidadCategorias[]" value="<?php echo $valor;?>" />
															</div>
															<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
																<input type="button" value="-" class="eliminarCategoria" style="width: 25px; height: 25px">
															</div>
														</div>
													<?php endforeach; ?>
												<?php endif; ?>
											</div>

										</fieldset>
									</div>

								</div>
								<!-- Fin Categorias -->

								<!-- Productos/Eventos -->
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

									<div class="panel-body">
										<fieldset class="scheduler-border">

											<legend class="scheduler-border">Productos/Eventos:</legend>
											<div class="row">
												<div class="col-lg-5 col-md-5 col-sm-5 col-xs-4">
													<label>Producto/Evento:</label>
												</div>
												<div class="col-lg-5 col-md-5 col-sm-5 col-xs-4">
													<label>Cantidad:</label>
												</div>
												<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
													<input type="button" value="+" class="agregarProducto" style="width: 25px; height: 25px">
													<span id="participantes" name="participantes"></span>
													<?php echo form_error('participantes');?>
												</div>
											</div>

											<div id="productos_add">
												<div class="row" style="display:none;">
													<div class="col-lg-5 col-md-5 col-sm-5 col-xs-4">
														<select style="width: 100%" id="productos[]" name="productos[]" >
															<option value=""><?php echo $this->config->item('empty_select'); ?></option>
															<?php foreach($productos as $k=>$v):?>
																<option value="<?php echo $k;?>"><?php echo $v;?></option>
															<?php endforeach;?>
														</select>
													</div>
													<div class="col-lg-5 col-md-5 col-sm-5 col-xs-4">
														<input type="text" class="form-control" id="cantidadProductos[]" name="cantidadProductos[]" value="" />
													</div>
													<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
														<input type="button" value="-" class="eliminarProducto" style="width: 25px; height: 25px">
													</div>
												</div>

												<?php if(@$productos_promocion): ?>
													<?php foreach($productos_promocion as $clave=>$valor): ?>
														<div class="row">
															<div class="col-lg-5 col-md-5 col-sm-5 col-xs-4">
																<select style="width: 100%" class="selectsProducto" id="productos[]" name="productos[]" >
																	<option value=""><?php echo $this->config->item('empty_select'); ?></option>
																	<?php foreach($productos as $k=>$v):?>
																		<option value="<?php echo $k;?>" <?php echo ($k == $clave)?'selected="selected"':''; ?>><?php echo $v;?></option>
																	<?php endforeach;?>
																</select>
															</div>
															<div class="col-lg-5 col-md-5 col-sm-5 col-xs-4">
																<input type="text" class="form-control" id="cantidadProductos[]" name="cantidadProductos[]" value="<?php echo @$valor;?>" />
															</div>
															<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
																<input type="button" value="-" class="eliminarProducto" style="width: 25px; height: 25px">
															</div>
														</div>
													<?php endforeach; ?>
												<?php endif; ?>
											</div>

										</fieldset>
									</div>

								</div>
								<!-- Fin Productos/Eventos -->

							</div>


							<div class="row">

								<!-- Accesorios -->
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

									<div class="panel-body">
										<fieldset class="scheduler-border">

											<legend class="scheduler-border">Accesorios:</legend>
											<div class="row">
												<div class="col-lg-5 col-md-5 col-sm-5 col-xs-4">
													<label>Accesorio:</label>
												</div>
												<div class="col-lg-5 col-md-5 col-sm-5 col-xs-4">
													<label>Cantidad:</label>
												</div>
												<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
													<input type="button" value="+" class="agregarAccesorio" style="width: 25px; height: 25px">
													<span id="participantes" name="participantes"></span>
													<?php echo form_error('participantes');?>
												</div>
											</div>

											<div id="accesorios_add">
												<div class="row" style="display:none;">
													<div class="col-lg-5 col-md-5 col-sm-5 col-xs-4">
														<select style="width: 100%" id="accesorios[]" name="accesorios[]" >
															<option value=""><?php echo $this->config->item('empty_select'); ?></option>
															<?php foreach($accesorios as $k=>$v):?>
																<option value="<?php echo $k;?>"><?php echo $v;?></option>
															<?php endforeach;?>
														</select>
													</div>
													<div class="col-lg-5 col-md-5 col-sm-5 col-xs-4">
														<input type="text" class="form-control" id="cantidadAccesorios[]" name="cantidadAccesorios[]" value="" />
													</div>
													<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
														<input type="button" value="-" class="eliminarAccesorio" style="width: 25px; height: 25px">
													</div>
												</div>

												<?php if(@$accesorios_promocion): ?>
													<?php foreach($accesorios_promocion as $clave=>$valor): ?>
														<div class="row">
															<div class="col-lg-5 col-md-5 col-sm-5 col-xs-4">
																<select style="width: 100%" class="selectsAccesorio" id="accesorios[]" name="accesorios[]" >
																	<option value=""><?php echo $this->config->item('empty_select'); ?></option>
																	<?php foreach($accesorios as $k=>$v):?>
																		<option value="<?php echo $k;?>" <?php echo ($k == $clave)?'selected="selected"':''; ?>><?php echo $v;?></option>
																	<?php endforeach;?>
																</select>
															</div>
															<div class="col-lg-5 col-md-5 col-sm-5 col-xs-4">
																<input type="text" class="form-control" id="cantidadAccesorios[]" name="cantidadAccesorios[]" value="<?php echo @$valor;?>" />
															</div>
															<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
																<input type="button" value="-" class="eliminarAccesorio" style="width: 25px; height: 25px">
															</div>
														</div>
													<?php endforeach; ?>
												<?php endif; ?>
											</div>

										</fieldset>
									</div>

								</div>
								<!-- Fin Accesorios -->

								<!-- Consumibles -->
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<div class="panel-body">
										<fieldset class="scheduler-border">
											<legend class="scheduler-border">Consumibles:</legend>
											<div class="row">
												<div class="col-lg-5 col-md-5 col-sm-5 col-xs-4">
													<label>Consumible:</label>
												</div>
												<div class="col-lg-5 col-md-5 col-sm-5 col-xs-4">
													<label>Cantidad:</label>
												</div>
												<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
													<input type="button" value="+" class="agregarConsumible" style="width: 25px; height: 25px">
													<span id="participantes" name="participantes"></span>
													<?php echo form_error('participantes');?>
												</div>
											</div>

											<div id="consumibles_add">
												<div class="row" style="display:none;">
													<div class="col-lg-5 col-md-5 col-sm-5 col-xs-4">
														<select style="width: 100%" id="consumibles[]" name="consumibles[]" >
															<option value=""><?php echo $this->config->item('empty_select'); ?></option>
															<?php foreach($consumibles as $k=>$v):?>
																<option value="<?php echo $k;?>"><?php echo $v;?></option>
															<?php endforeach;?>
														</select>
													</div>
													<div class="col-lg-5 col-md-5 col-sm-5 col-xs-4">
														<input type="text" class="form-control" id="cantidadConsumibles[]" name="cantidadConsumibles[]" value="" />
													</div>
													<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
														<input type="button" value="-" class="eliminarConsumible" style="width: 25px; height: 25px">
													</div>
												</div>

												<?php if(@$consumibles_promocion): ?>
													<?php foreach($consumibles_promocion as $clave=>$valor): ?>
														<div class="row">
															<div class="col-lg-5 col-md-5 col-sm-5 col-xs-4">
																<select style="width: 100%" class="selectsConsumible" id="consumibles[]" name="consumibles[]" >
																	<option value=""><?php echo $this->config->item('empty_select'); ?></option>
																	<?php foreach($consumibles as $k=>$v):?>
																		<option value="<?php echo $k;?>" <?php echo ($k == $clave)?'selected="selected"':''; ?>><?php echo $v;?></option>
																	<?php endforeach;?>
																</select>
															</div>
															<div class="col-lg-5 col-md-5 col-sm-5 col-xs-4">
																<input type="text" class="form-control" id="cantidadConsumibles[]" name="cantidadConsumibles[]" value="<?php echo @$valor;?>" />
															</div>
															<div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
																<input type="button" value="-" class="eliminarConsumible" style="width: 25px; height: 25px">
															</div>
														</div>
													<?php endforeach; ?>
												<?php endif; ?>
											</div>

										</fieldset>
									</div>
								</div>

							</div>

						</div>
					</div>

				</div>
				<!-- Fin Participantes de la promoción -->


				<!-- Regalos -->
				<div class="panel panel-default">

					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion" href="#collapseThree"><span class="req">*</span>Regalos</a>
						</h4>
					</div>

					<div id="collapseThree" class="panel-collapse collapse in">
						<div class="panel-body">

							<!-- Meses sin intereses y descuentos -->
							<div class="row">

								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

									<div class="panel-body">
										<fieldset class="scheduler-border">
											<legend class="scheduler-border">Meses sin intereses:</legend>
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-group">
												<label>Meses sin intereses:</label>
												<input type="text" class="form-control" id="meses_sin_intereses" name="meses_sin_intereses" value="<?php echo@$promocion->meses_sin_intereses; ?>" />
												<?php echo form_error('meses_sin_intereses');?>
												<span id="regalos" name="regalos"></span>
												<?php echo form_error('regalos');?>
											</div>
										</fieldset>
									</div>
								</div>

								<div class="col-lg-6">

									<div class="panel-body">
										<fieldset class="scheduler-border">
											<legend class="scheduler-border">Descuentos:</legend>
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-group">
												<label>Porcentaje de descuento:</label>
												<input type="text" class="form-control" id="porcentaje_descuento" name="porcentaje_descuento" value="<?php echo @$promocion->porcentaje_descuento; ?>" />
												<?php echo form_error('porcentaje_descuento');?>
												<span id="regalos" name="regalos"></span>
												<?php echo form_error('regalos');?>
											</div>

											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-group">
												<label>Monto de descuento:</label>
												<input type="text" class="form-control" id="monto_descuento" name="monto_descuento" value="<?php echo @$promocion->monto_descuento; ?>" />
												<?php echo form_error('monto_descuento');?>
												<span id="regalos" name="regalos"></span>
												<?php echo form_error('regalos');?>
											</div>
										</fieldset>
									</div>
								</div>

							</div>
							<!-- Fin Meses sin intereses y descuentos -->


							<!-- Productos MIELE -->
							<div class="row">
								<div class="col-lg-12">
									<fieldset class="scheduler-border">
										<legend class="scheduler-border">Productos MIELE:</legend>

										<div class="row">
											<!-- Productos/Eventos -->
											<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
												<fieldset class="scheduler-border">
													<legend class="scheduler-border">Productos/Eventos:</legend>
													<div class="row">
														<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
															<label>Producto/Evento:</label>
														</div>
														<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
															<label>Cantidad:</label>
														</div>
														<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
															<label>Porcentaje (%):</label>
														</div>
														<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
															<input type="button" value="+" class="agregarRegaloProducto" style="width: 25px; height: 25px">
															<span id="regalos" name="regalos"></span>
															<?php echo form_error('regalos');?>
														</div>
													</div>

													<div id="regalos_productos_add">
														<div class="row" style="display:none;">
															<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
																<select style="width: 100%" id="regaloProductos[]" name="regaloProductos[]" >
																	<option value=""><?php echo $this->config->item('empty_select'); ?></option>
																	<?php foreach($productos as $k=>$v):?>
																		<option value="<?php echo $k;?>"><?php echo $v;?></option>
																	<?php endforeach;?>
																</select>
															</div>
															<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
																<input type="text" class="form-control" id="cantidadRegalosProductos[]" name="cantidadRegalosProductos[]" value="" />
															</div>
															<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
																<input type="text" class="form-control" id="porcentajeRegalosProductos[]" name="porcentajeRegalosProductos[]" value="" />
															</div>
															<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
																<input type="button" value="-" class="eliminarRegalosProducto" style="width: 25px; height: 25px">
															</div>
														</div>
														<?php if(@$productos_regalo_promocion): ?>
															<?php foreach($productos_regalo_promocion as $clave=>$valor):?>
																<div class="row">
																	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
																		<select style="width: 100%" class="selectsRegaloProductos" id="regaloProductos[]" name="regaloProductos[]" >
																			<option value=""><?php echo $this->config->item('empty_select'); ?></option>
																			<?php foreach($productos as $k=>$v):?>
																				<option value="<?php echo $k;?>" <?php echo ($k == $clave)?'selected="selected"':''; ?>><?php echo $v;?></option>
																			<?php endforeach;?>
																		</select>
																	</div>
																	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
																		<input type="text" class="form-control" id="cantidadRegalosProductos[]" name="cantidadRegalosProductos[]" value="<?php echo @$valor['cantidad'];?>" />
																	</div>
																	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
																		<input type="text" class="form-control" id="porcentajeRegalosProductos[]" name="porcentajeRegalosProductos[]" value="<?php echo @$valor['porcentaje'];?>" />
																	</div>
																	<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
																		<input type="button" value="-" class="eliminarRegalosProducto" style="width: 25px; height: 25px">
																	</div>
																</div>
															<?php endforeach; ?>
														<?php endif; ?>
													</div>
												</fieldset>
											</div>
											<!-- Fin Productos/Eventos -->

											<!-- Accesorios -->
											<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
												<fieldset class="scheduler-border">
													<legend class="scheduler-border">Accesorios:</legend>
													<div class="row">
														<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
															<label>Accesorio:</label>
														</div>
														<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
															<label>Cantidad:</label>
														</div>
														<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
															<label>Porcentaje:</label>
														</div>
														<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
															<input type="button" value="+" class="agregarRegaloAccesorio" style="width: 25px; height: 25px">
															<span id="regalos" name="regalos"></span>
															<?php echo form_error('regalos');?>
														</div>
													</div>

													<div id="regalos_accesorios_add">
														<div class="row" style="display:none;">
															<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
																<select style="width: 100%" id="regaloAccesorios[]" name="regaloAccesorios[]" >
																	<option value=""><?php echo $this->config->item('empty_select'); ?></option>
																	<?php foreach($accesorios as $k=>$v):?>
																		<option value="<?php echo $k;?>"><?php echo $v;?></option>
																	<?php endforeach;?>
																</select>
															</div>
															<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
																<input type="text" class="form-control" id="cantidadRegalosAccesorios[]" name="cantidadRegalosAccesorios[]" value="" />
															</div>
															<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
																<input type="text" class="form-control" id="porcentajeRegalosAccesorios[]" name="porcentajeRegalosAccesorios[]" value="" />
															</div>
															<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
																<input type="button" value="-" class="eliminarRegaloAccesorio" style="width: 25px; height: 25px">
															</div>
														</div>

														<?php if(@$accesorios_regalo_promocion): ?>
															<?php foreach($accesorios_regalo_promocion as $clave=>$valor):?>
																<div class="row">
																	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
																		<select style="width: 100%" class="selectsRegaloAccesorios" id="regaloAccesorios[]" name="regaloAccesorios[]" >
																			<option value=""><?php echo $this->config->item('empty_select'); ?></option>
																			<?php foreach($accesorios as $k=>$v):?>
																				<option value="<?php echo $k;?>" <?php echo ($k == $clave)?'selected="selected"':''; ?>><?php echo $v;?></option>
																			<?php endforeach;?>
																		</select>
																	</div>
																	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
																		<input type="text" class="form-control" id="cantidadRegalosAccesorios[]" name="cantidadRegalosAccesorios[]" value="<?php echo @$valor['cantidad'];?>" />
																	</div>
																	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
																		<input type="text" class="form-control" id="porcentajeRegalosAccesorios[]" name="porcentajeRegalosAccesorios[]" value="<?php echo @$valor['porcentaje'];?>" />
																	</div>
																	<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
																		<input type="button" value="-" class="eliminarRegaloAccesorio" style="width: 25px; height: 25px">
																	</div>
																</div>
															<?php endforeach; ?>
														<?php endif; ?>
													</div>
												</fieldset>
											</div>
											<!-- Fin Accesorios -->
										</div>

										<div class="row">
											<!-- Consumible -->
											<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
												<fieldset class="scheduler-border">
													<legend class="scheduler-border">Consumibles:</legend>
													<div class="row">
														<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
															<label>Consumible:</label>
														</div>
														<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
															<label>Cantidad:</label>
														</div>
														<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
															<input type="button" value="+" class="agregarRegaloConsumible" style="width: 25px; height: 25px">
															<span id="regalos" name="regalos"></span>
															<?php echo form_error('regalos');?>
														</div>
													</div>

													<div id="regalos_consumibles_add">
														<div class="row" style="display:none;">
															<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
																<select style="width: 100%" id="regaloConsumibles[]" name="regaloConsumibles[]" >
																	<option value=""><?php echo $this->config->item('empty_select'); ?></option>
																	<?php foreach($consumibles as $k=>$v):?>
																		<option value="<?php echo $k;?>"><?php echo $v;?></option>
																	<?php endforeach;?>
																</select>
															</div>
															<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
																<input type="text" class="form-control" id="cantidadRegalosConsumibles[]" name="cantidadRegalosConsumibles[]" value="" />
															</div>
															<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
																<input type="button" value="-" class="eliminarRegaloConsumible" style="width: 25px; height: 25px">
															</div>
														</div>

														<?php if(@$consumibles_regalo_promocion): ?>
															<?php foreach($consumibles_regalo_promocion as $clave=>$valor): ?>
																<div class="row">
																	<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
																		<select style="width: 100%" class="selectsRegaloConsumibles" id="regaloConsumibles[]" name="regaloConsumibles[]" >
																			<option value=""><?php echo $this->config->item('empty_select'); ?></option>
																			<?php foreach($consumibles as $k=>$v):?>
																				<option value="<?php echo $k;?>" <?php echo ($k == $clave)?'selected="selected"':''; ?>><?php echo $v;?></option>
																			<?php endforeach;?>
																		</select>
																	</div>
																	<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
																		<input type="text" class="form-control" id="cantidadRegalosConsumibles[]" name="cantidadRegalosConsumibles[]" value="<?php echo @$valor['cantidad'];?>" />
																	</div>
																	<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
																		<input type="button" value="-" class="eliminarRegalosConsumible" style="width: 25px; height: 25px">
																	</div>
																</div>
															<?php endforeach; ?>
														<?php endif; ?>
													</div>
												</fieldset>
											</div>
											<!-- Fin Consumible -->
										</div>

									</fieldset>
								</div>
							</div>
							<!-- Fin Productos MIELE -->

							<!-- Alianzas -->
							<div class="row">
								<div class="col-lg-12">
									<div class="row">
										<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
											<fieldset class="scheduler-border">
												<legend class="scheduler-border">Alianzas:</legend>
												<div class="row">
													<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
														<label>Alianza:</label>
													</div>
													<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
														<label>Cantidad:</label>
													</div>
													<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
														<input type="button" value="+" class="agregarAlianza" style="width: 25px; height: 25px">
														<span id="regalos" name="regalos"></span>
														<?php echo form_error('regalos');?>
														<span id="alianza" name="alianza[]"></span>
														<?php echo form_error('alianza');?>
													</div>
												</div>

												<div id="regalos_alianzas_add">
													<div class="row" style="display:none;">
														<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
															<select style="width: 100%" id="regaloAlianzas[]" name="regaloAlianzas[]" >
																<option value=""><?php echo $this->config->item('empty_select'); ?></option>
																<?php foreach($alianzas as $k):?>
																	<option value="<?php echo $k->id;?>"><?php echo $k->nombre;?></option>
																<?php endforeach;?>
															</select>
														</div>
														<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
															<input type="text" class="form-control" id="cantidadRegalosAlianzas[]" name="cantidadRegalosAlianzas[]" value="" />
														</div>
														<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
															<input type="button" value="-" class="eliminarAlianza" style="width: 25px; height: 25px">
														</div>
													</div>

													<?php if(@$alianzas_regalo_promocion): ?>
														<?php foreach($alianzas_regalo_promocion as $clave=>$valor): ?>
															<div class="row">
																<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
																	<select style="width: 100%" class="selectsRegaloAlianzas" id="regaloAlianzas[]" name="regaloAlianzas[]" >
																		<option value=""><?php echo $this->config->item('empty_select'); ?></option>
																		<?php foreach($alianzas as $k):?>
																			<option value="<?php echo $k->id;?>" <?php echo ($k->id == $clave)?'selected="selected"':''; ?>><?php echo $k->nombre;?></option>
																		<?php endforeach;?>
																	</select>
																</div>
																<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
																	<input type="text" class="form-control" id="cantidadRegalosAlianzas[]" name="cantidadRegalosAlianzas[]" value="<?php echo @$valor;?>" />
																</div>
																<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
																	<input type="button" value="-" class="eliminarAlianza" style="width: 25px; height: 25px">
																</div>
															</div>
														<?php endforeach; ?>
													<?php endif; ?>
												</div>
											</fieldset>
										</div>
									</div>
								</div>
							</div>
							<!-- Fin Alianza -->

						</div>
					</div>
				</div>
				<!-- Fin Regalos -->


				<div class="col-lg-12 barra-btn">
					<input  class="btn btn-primary pull-right" type="submit" value="Guardar" id="guardar" />
					<a href="<?php echo site_url('promociones/index');?>"  class="btn btn-default pull-right">Cancelar</a>
				</div>

			</div>
		</form>
	</div>

	<script type="text/javascript">
		<!--
		$(function(){
			convertir_campos();
		});
		//-->
	</script>

<?php if(!is_ajax()){ $this->load->view('layout/footer'); } ?>