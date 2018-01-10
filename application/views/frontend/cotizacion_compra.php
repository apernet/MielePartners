<?php $this->load->view('frontend/layout/header');?>
<div class="mb60">
	<form action="<?php echo current_url(); ?>" id="form" method="post" enctype="multipart/form-data" class="text-uppercase">
		<div class="panel-group front_panel" id="accordion">
		  	<div class="panel panel-default">
		    	<div class="panel-heading">
		      		<h4 class="panel-title">
			        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
						Informaci&oacute;n del Cliente
			        </a>
		      		</h4>
		    	</div>
		    	<div id="collapseOne" class="panel-collapse collapse in">
		      		<div class="panel-body">
		        		<div class="row">
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				        		<label><span class="req">*</span>Nombre:</label>
								<input class="form-control <?php echo form_error('nombre_comprador')?'rojo':'';?>" id="nombre_comprador" name="nombre_comprador" value="<?php echo set_value('nombre_comprador',@$r['nombre_comprador']); ?>" <?php echo in_array($status_id,array(2,3,4,5))?'readonly':'';?>/>
								<?php echo form_error('nombre_comprador');?>
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				        		<label><span class="req">*</span>Apellido paterno:</label>
								<input class="form-control <?php echo form_error('paterno_comprador')?'rojo':'';?>" id="paterno_comprador"  name="paterno_comprador"  value="<?php echo set_value('paterno_comprador',@$r['paterno_comprador']); ?>" <?php echo in_array($status_id,array(2,3,4,5))?'readonly':'';?>/>
								<?php echo form_error('paterno_comprador');?>
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				        		<label>Apellido materno:</label>
								<input class="form-control <?php echo form_error('materno_comprador')?'rojo':'';?>" id="materno_comprador"  name="materno_comprador"  value="<?php echo set_value('materno_comprador',@$r['materno_comprador']); ?>" <?php echo in_array($status_id,array(2,3,4,5))?'readonly':'';?>/>
								<?php echo form_error('materno_comprador');?>
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				        		<label><span class="req">*</span>Correo electr&oacute;nico:</label>
				        		<!--<?php if($venta_directa)://$this->session->userdata('cuentas_id') == 2): ?>
			        		        <div class="input-group ">
			    	        		   <input class="form-control" id="email_comprador"  name="email_comprador"  value="<?php echo set_value('email_comprador',@$r['email_comprador']); ?>" <?php echo in_array($status_id,array(2,3,4,5))?'readonly':'';?>/>
			    	        		   <span class="input-group-addon">
			    					       <a  class="buscar_ref icono_info" title="Buscar referencia." href="#"><i class="fa fa-search"></i></a>
			    					   </span>
			    	        		</div>
								<?php else: ?>
								<?php endif; ?>-->
								   <input class="form-control <?php echo form_error('email_comprador')?'rojo':'';?>" id="email_comprador"  name="email_comprador"  value="<?php echo set_value('email_comprador',@$r['email_comprador']); ?>" <?php echo in_array($status_id,array(2,3,4,5))?'readonly':'';?>/>
								<?php echo form_error('email_comprador');?>
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				        		<label><span class="req">*</span>Tel&eacute;fono:</label>
								<input class="form-control <?php echo form_error('telefono_comprador')?'rojo':'';?>" id="telefono_comprador"  name="telefono_comprador"  value="<?php echo set_value('telefono_comprador',@$r['telefono_comprador']); ?>" <?php echo in_array($status_id,array(2,3,4,5))?'readonly':'';?>/>
								<?php echo form_error('telefono_comprador');?>
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				        		<label>Fecha nacimiento(DD/MM):</label>
								<input class="form-control <?php echo form_error('fecha_nacimiento_comprador')?'rojo':'';?>" id="fecha_nacimiento_comprador"  name="fecha_nacimiento_comprador"  value="<?php echo set_value('fecha_nacimiento_comprador',@$r['fecha_nacimiento_comprador']); ?>" <?php echo in_array($status_id,array(2,3,4,5))?'readonly':'';?>/>
								<?php echo form_error('fecha_nacimiento_comprador');?>
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				        		<label>A&ntilde;o de nacimiento(YYYY):</label>
								<input class="form-control <?php echo form_error('anio_nacimiento')?'rojo':'';?>" id="anio_nacimiento_comprador"  name="anio_nacimiento_comprador"  value="<?php echo set_value('anio_nacimiento_comprador',@$r['anio_nacimiento_comprador']); ?>" <?php echo in_array($status_id,array(2,3,4,5))?'readonly':'';?>/>
								<?php echo form_error('anio_nacimiento');?>
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				        		<label>Fecha de aniversario(DD/MM):</label>
								<input class="form-control <?php echo form_error('fecha_aniversario_comprador')?'rojo':'';?>" id="fecha_aniversario_comprador"  name="fecha_aniversario_comprador"  value="<?php echo set_value('fecha_aniversario_comprador',@$r['fecha_aniversario_comprador']); ?>" <?php echo in_array($status_id,array(2,3,4,5))?'readonly':'';?>/>
								<?php echo form_error('fecha_aniversario_comprador');?>
								<?php if(!in_array($status_id,array(2,3,4,5))):?>
									<a href="#" id="copiar_datos_cliente" title="De click sobre este enlace para copiar los datos del cliente a datos de envio." >Copiar datos del cliente a datos de envio</a>
								<?php endif;?>
				        	</div>
		        		</div>
		      		</div>
		    	</div>
		  	</div>
		</div>
		<?php if($venta_directa):?>
		<div class="panel-group front_panel" id="accordion2">
		  	<div class="panel panel-default">
		    	<div class="panel-heading">
			      	<h4 class="panel-title">
			        	<a data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">
			          	Referencia
			        	</a>
			      	</h4>
		    	</div>
			    <div id="collapseTwo" class="panel-collapse collapse in">
			      	<div class="panel-body">
						<div class="row">
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
								<label>Buscar por:</label>
								<select class="form-control" id="referido_buscar_id">
									<option value=""><?php echo $this->config->item('empty_select'); ?></option>
									<option value="1">Nombre del Cliente</option>
									<option value="2">E-mail del Cliente</option>
									<option value="3">Direcci&oacute;n de Instalaci&oacute;n</option>
								</select>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
								<input type="hidden" id="referido_id" name="referido_id"/>
								<label>Distribuidor:</label>
								<select class="form-control <?php echo form_error('distribuidores_id')?'rojo':'';?>" name="referido_distribuidor_id" id="distribuidores_id">
									<option value=""><?php echo $this->config->item('empty_select'); ?></option>
									<?php foreach($distribuidores as $k=>$v):?>
										<option value="<?php echo $k; ?>" <?php echo ($k == @$r['referido_distribuidor_id'])?'selected="selected"':''; ?>> <?php echo $v; ?></option>
									<?php endforeach;?>
								</select>
								<?php echo form_error('distribuidores_id');?>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
								<label><span class="req">*</span>Nombre del Vendedor:</label>
								<input class="form-control <?php echo form_error('referido_vendedor')?'rojo':'';?>" type="text" id="referido_vendedor_nombre" name="referido_vendedor_nombre" value="<?php echo set_value('referido_vendedor_nombre',@$r['referido_vendedor_nombre']); ?>" />
								<?php echo form_error('referido_vendedor_nombre');?>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
								<label><span class="req">*</span>Apellido Paterno del Vendedor:</label>
								<input class="form-control <?php echo form_error('referido_vendedor')?'rojo':'';?>" type="text" id="referido_vendedor_paterno" name="referido_vendedor_paterno" value="<?php echo set_value('referido_vendedor_paterno',@$r['referido_vendedor_paterno']); ?>" />
								<?php echo form_error('referido_vendedor_paterno');?>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
								<label><span class="req">*</span>Apellido Materno del Vendedor:</label>
								<input class="form-control <?php echo form_error('referido_vendedor')?'rojo':'';?>" type="text" id="referido_vendedor_materno" name="referido_vendedor_materno" value="<?php echo set_value('referido_vendedor_materno',@$r['referido_vendedor_materno']); ?>" />
								<?php echo form_error('referido_vendedor_materno');?>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
								<div class="checkbox">
									<input name="referido_porcentaje_comision" value="0" type="hidden" class=" <?php echo form_error('referido_porcentaje_comision')?'rojo':'';?>"/>
									<label>
									<input type="checkbox" name="referido_porcentaje_comision" value="1" <?php echo @$r['referido_porcentaje_comision'] == 1?'checked':'' ?> class="referido_porcentaje_comision" id="referido_porcentaje_comision"/>
									Porcentaje Comisi&oacute;n 100 %</label>
									<?php echo form_error('referido_porcentaje_comision');?>
								</div>
							</div>
						</div>
			      	</div>
			    </div>
		  	</div>
		</div>
		<?php endif; ?>

		<?php if(!empty($evento_estado)): ?>
			<div class="panel-group front_panel" id="accordionEventoEstado">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordionEventoEstado" href="#collapseEventoEstado">
								Estado donde se impartir&aacute; el servicio
							</a>
						</h4>
					</div>
					<div id="collapseEventoEstado" class="panel-collapse collapse in">
						<div class="panel-body">
							<div class="row">
								<div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 form-group">
									<label>Estado:</label>
									<?php if(in_array(@$status_id,array(1,6))):?>
										<select class="form-control evento_estado" name="evento_estado" id="evento_estado">
											<?php foreach($estados as $e):?>
												<option value="<?php echo $e; ?>" <?php echo set_select('evento_estado', $e, $e==$evento_estado); ?>><?php echo $e; ?></option>
											<?php endforeach;?>
										</select>
									<?php else:?>
										<input type="hidden" name="evento_estado" value="<?php echo @$r['evento_estado'];?>">
										<div class="field-info <?php echo form_error('evento_estado')?'rojo':'';?>"><?php echo @$r['evento_estado'];?></div>
									<?php endif;?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<div class="panel-group front_panel" id="accordion3">
			<div class="panel panel-default">
		    	<div class="panel-heading">
		      		<h4 class="panel-title">
				        <a data-toggle="collapse" data-parent="#accordion3" href="#collapseThree">
				          Productos
				        </a>
				    </h4>
		    	</div>
				<div id="collapseThree" class="panel-collapse collapse in">
					<div class="panel-body">
						<?php if(!empty($promociones) && @$promociones_id):?>
						<div class="panel-group front_panel" id="promociones">
							<div class="panel panel-default" id="productos_div">
								<div id="collapsePromociones" class="panel-collapse collapse in">
									<a class="btn-front-primary bc_fancybox" href="<?php echo site_url('frontends/promocion_view'); ?>">
										<img src="<?php echo site_url('img/promociones/promocion_disponible.png')?>" style="width: 100%"/>
									</a>
								</div>
							</div>
						</div>
						<?php endif;?>
						<div class="table-responsive front_table mt20" style="overflow-y: auto">
							<table class="table table-hover cotizacion">
								<?php if(empty($productos_sesion) && empty($accesorios_sesion)):?>
									<div class="msg sin_resultados"><?php echo 'No existen art&iacute;culos en la cotización.';?></div>
								<?php else:?>
								<thead>
									<tr>
										<td>Producto</td>
										<td>Modelo</td>
										<td>Nombre</td>
										<td>Cantidad</td>
										<td>Precio unitario</td>
										<td>Descuento Cliente</td>
										<?php if(in_array($status_id,array(2,3,4,5)) && !$venta_directa):?>
										<td>Descuento Distribuidor</td>
										<?php endif;?>
										<td>Importe Cliente</td>
										<?php if(in_array($status_id,array(2,3,4,5)) && !$venta_directa):?>
										<td>Importe Distribuidor</td>
										<?php endif;?>
										<td>Acciones</td>
									</tr>
								</thead>
								<tbody>
								<?php $i=1;?>
								<?php foreach($productos_sesion as $k => $p):?>
									<tr>
										<td class="text-center">
											<?php $path=$this->config->item('cloudfiles')?$this->cloud_files->url_publica("files/productos/{$p->id}/{$p->img_id}.jpg"):"files/productos/{$p->id}/{$p->img_id}.jpg";?>
											<a class='imagen_fancybox' href="<?php echo site_url('/thumbs/timthumb.php?src='.$path.'&zc=0&q=85&s=500'.'&t='.time());?>">
												<img class="img-thumbnail front_imgTabla" src="<?php echo site_url('/thumbs/timthumb.php?src='.$path.'&s=400&t='.time());?>" />
											</a>
										</td>
										<td class="text-center"><?php echo $p->modelo;?></td>
										<td><?php echo $p->nombre;?></td>
										<td class="text-center"><span class="p_cantidad"><?php echo $carrito[$p->id]['cantidad'];?></span><?php echo ' '.$p->unidad;?></td>
										<td class="text-center"><?php echo $status_id==1?moneda($carrito[$p->id]['precio']):moneda($p->precio);//@$calculo['importe_cliente'][$p->id]?></td>
										<td class="text-center"><?php echo $status_id==1 && @$calculo['descuentos_cliente'][$p->id]['cliente']?ver_num(@$calculo['descuentos_cliente'][$p->id]['cliente']).' %':($p->descuento_cliente?ver_num($p->descuento_cliente*100).' %':ver_num(0).' %');?></td>

										<?php if(in_array($status_id,array(2,3,4,5)) && !$venta_directa)://$this->session->userdata('cuentas_id') != 2):?>
										<td class="text-center"><?php echo $p->descuento_distribuidor?ver_num($p->descuento_distribuidor*100).' %':ver_num(0).' %';//isset($calculo['descuentos_distribuidor'][$p->id]['total'])?ver_num($calculo['descuentos_distribuidor'][$p->id]['total']).' %':ver_num(0).' %';?></td>
										<?php endif;?>
										<td class="text-center">
											<span class="bc_precio_sub">
											   <?php echo $status_id==1?moneda(@$calculo['importe_cliente'][$p->id]):moneda($p->importe_cliente);//isset($calculo['importe_cliente'][$p->id])?moneda($calculo['importe_cliente'][$p->id]):'';?>
											</span>
										</td>
										<?php if(in_array($status_id,array(2,3,4,5)) && !$venta_directa):// && $this->session->userdata('cuentas_id') != 2):?>
										<td class="text-center">
											<span class="bc_precio_sub">
											   <?php echo moneda($p->importe_distribuidor);//isset($calculo['importe_distribuidor'][$p->id])?moneda($calculo['importe_distribuidor'][$p->id]):'';?>
											</span>
										</td>
										<?php endif;?>
										<?php if(!in_array($status_id,array(2,3,4,5))):?>
											<td class="text-center">
												<a class="accion accion3" href="<?php echo site_url('frontends/producto_eliminar/'.$p->id.'/'.@$cotizaciones_id); ?>">Eliminar</a>
											</td>
										<?php else:?>
											<td>&nbsp;</td>
										<?php endif;?>
									</tr>
									<?php if(isset($p->accesorios)):?>
										<?php foreach($p->accesorios as $acc):?>
											<tr>
												<td class="text-center">
													<?php $orden = $acc->imagen_orden?'_'.$acc->imagen_orden:'';?>
													<?php $path_acc=$this->config->item('cloudfiles')?$this->cloud_files->url_publica("files/accesorios/{$acc->id}{$orden}.jpg"):"files/accesorios/{$acc->id}.jpg";?>
													<a class='imagen_fancybox' href="<?php echo site_url('/thumbs/timthumb.php?src='.$path_acc.'&zc=0&q=85&s=500'.'&t='.time());?>">
														<img class="img-thumbnail front_imgTabla" src="<?php echo site_url('/thumbs/timthumb.php?src='.$path_acc.'&s=400&t='.time());?>" />
													</a>
												</td>
												<td class="nowrap text-center"><?php echo $acc->modelo;?></td>
												<td><?php echo $acc->nombre;?></td>
												<td class="text-center acc_cantidad"><?php echo $acc->cantidad.' '.$acc->unidad;?></td>
												<td class="text-center"><?php echo $status_id==1?moneda(@$carrito[$p->id]['accesorios'][$acc->id]['precio']):moneda($acc->precio);?></td>
												<td class="text-center">
													<?php
													if($status_id==1):
														if(isset($calculo['descuentos_cliente'][$p->id]['accesorios'][$acc->id]['cliente'])):
															$descuento_cupon_acc_cliente = $acc->consumible && @$calculo['descuentos_cliente'][$p->id]['accesorios'][$acc->id]['cliente_cupon']?$calculo['descuentos_cliente'][$p->id]['accesorios'][$acc->id]['cliente_cupon']:0;
															echo ver_num($calculo['descuentos_cliente'][$p->id]['accesorios'][$acc->id]['cliente']+$descuento_cupon_acc_cliente).' %';
														else:
															echo ver_num(0).' %';
														endif;
													else:
														echo $acc->descuento_cliente?ver_num($acc->descuento_cliente*100).' %':ver_num(0).' %';
													endif;?>
												</td>
												<?php if(in_array($status_id,array(2,3,4,5)) && !$venta_directa):// && $this->session->userdata('cuentas_id') != 2):?>
													<td class="text-center"><?php echo $acc->descuento_distribuidor && !$acc->consumible?ver_num($acc->descuento_distribuidor*100).' %':ver_num(0).' %';?></td>
												<?php endif;?>
												<td class="text-center">
													<span class="bc_precio_sub">
													   <?php echo $status_id==1?moneda(@$calculo['importe_cliente_acc'][$acc->id]):moneda($acc->importe_cliente);//isset($calculo['importe_cliente_acc'][$acc->id])?moneda($calculo['importe_cliente_acc'][$acc->id]):'';?>
													</span>
												</td>
												<?php if(in_array($status_id,array(2,3,4,5)) && !$venta_directa):// && $this->session->userdata('cuentas_id') != 2):?>
												<td class="text-center">
													<span class="bc_precio_sub">
													   <?php echo moneda($acc->importe_distribuidor);//isset($calculo['importe_distribuidor_acc'][$acc->id])?moneda($calculo['importe_distribuidor_acc'][$acc->id]):'';?>
													</span>
												</td>
												<?php endif;?>
												<?php if(!$acc->obligatorio && !in_array($status_id,array(2,3,4,5))):?>
													<?php $accesorio_consumible = (@$acc->consumible)?'Consumible':'Accesorio';?>
													<td class="text-center">
													   <a class="accion accion3" href="<?php echo site_url('frontends/producto_eliminar/0/'.@$cotizaciones_id.'/'.$acc->id); ?>">Eliminar <?php echo $accesorio_consumible;?></a>
													</td>
												<?php else:?>
													<td>&nbsp;</td>
												<?php endif;?>
											</tr>
										<?php endforeach;?>
									<?php endif;?>
									<?php $i++;?>
									<?php endforeach;?>
								<?php endif;?>
								<?php if($accesorios_individuales):
									foreach($accesorios_individuales as $ai):?>
										<tr>
											<td class="text-center">
												<?php $orden = $ai->imagen_orden?'_'.$ai->imagen_orden:'';?>
												<?php $path_acc=$this->config->item('cloudfiles')?$this->cloud_files->url_publica("files/accesorios/{$ai->accesorios_id}{$orden}.jpg"):"files/accesorios/{$ai->accesorios_id}.jpg";?>
												<a class='imagen_fancybox' href="<?php echo site_url('/thumbs/timthumb.php?src='.$path_acc.'&zc=0&q=85&s=500'.'&t='.time());?>">
													<img class="img-thumbnail front_imgTabla" src="<?php echo site_url('/thumbs/timthumb.php?src='.$path_acc.'&s=400&t='.time());?>" />
												</a>
											</td>
											<td class="nowrap text-center"><?php echo $ai->modelo;?></td>
											<td><?php echo $ai->nombre;?></td>
											<td class="text-center acc_individual_cantidad<?php //echo @$status_posterior?'acc_individual_cantidad':'';?>"><?php echo $ai->cantidad.' '.$ai->unidad;?></td>
											<td class="text-center"><?php echo moneda($ai->precio);?></td>
											<?php if(INTERNO):?>
												<td class="text-center">
													<?php echo $ai->descuento_cliente && (!$ai->consumible || ($ai->consumible && @$r['cupones_id'] && @$r['opcion_cupon_id']==1))?ver_num($ai->descuento_cliente).' %':ver_num(0).' %';//echo @$calculo['descuento_cliente_acc_individual'][$accesorio_id]?ver_num($calculo['descuento_cliente_acc_individual'][$accesorio_id]).' %':ver_num(0).' %'; ?>
												</td>
											<?php endif;?>
											<?php if(isset($status_id) && in_array($status_id,array(2,3,4,5)) && INTERNO && !$venta_directa):?>
												<td class="text-center">
													<?php echo $ai->descuento_distribuidor?ver_num($ai->descuento_distribuidor).' %':ver_num(0).' %'; //echo @$calculo['descuento_distribuidor_acc_individual'][$accesorio_id]?ver_num($calculo['descuento_distribuidor_acc_individual'][$accesorio_id]).' %':ver_num(0).' %'; ?>
												</td>
											<?php endif;?>
											<td class="text-center">
												<span class="bc_precio_sub">
													<?php
													if(isset($calculo['importe_cliente_acc_individual'][$ai->accesorios_id]))
														echo moneda($calculo['importe_cliente_acc_individual'][$ai->accesorios_id]);
													else
														echo '';
													?>
												</span>
											</td>
											<?php if(isset($status_id) && in_array($status_id,array(2,3,4,5)) && INTERNO && !$venta_directa):?>
												<td class="text-center">
													<span class="bc_precio_sub">
														<?php
														if(isset($calculo['importe_distribuidor_acc_individual'][$ai->accesorios_id]))
															echo moneda($calculo['importe_distribuidor_acc_individual'][$ai->accesorios_id]);
														else
															echo '';
														?>
													</span>
												</td>
											<?php endif;?>
											<?php if(!in_array($status_id,array(2,3,4,5))):?>
												<?php $accesorio_consumible = (@$ai->consumible)?'Consumible':'Accesorio';?>
												<td class="text-center">
													<a class="accion accion3" href="<?php echo site_url('frontends/accesorio_individual_eliminar/'.@$cot_id.'/'.$ai->accesorios_id); ?>">Eliminar <?php echo $accesorio_consumible;?></a>
												</td>
											<?php else:?>
												<td>&nbsp;</td>
											<?php endif;?>
										</tr>
									<?php endforeach;
								endif;?>
								<?php if($promociones):?>
									<input type="hidden" id="promociones_id" name="promociones_id" value="<?php echo @$r['promociones_id']?$r['promociones_id']:@$promociones_id;?>"/>
									<?php if($promociones_productos):
										foreach($promociones_productos as $k => $p):?>
											<tr>
												<td class="text-center" style="background-color: #f0f0f0">
													<?php $path=$p['path'];?>
													<div style="position: relative;">
														<a class='imagen_fancybox' href="<?php echo site_url('/thumbs/timthumb.php?src='.$path.'&zc=0&q=85&s=500'.'&t='.time());?>">
															<img class="img-thumbnail front_imgTabla" src="<?php echo site_url('/thumbs/timthumb.php?src='.$path.'&s=400&t='.time());?>" />
															<?php $path_regalo=site_url('img/promociones/regalo.png');?>
															<img style="height:50px !important;width:75px !important;position: absolute;z-index: 1;left:0;margin:-8px;top:0;"  src="<?php echo site_url('/thumbs/timthumb.php?src='.$path_regalo.'&s=400&t='.time());?>" />
														</a>
													</div>
												</td>
												<td class="text-center"><?php echo $p['modelo'];?></td>
												<td><?php echo $p['nombre'];?></td>
												<td class="text-center <?php //echo @$status_posterior?'p_cantidad':'';?>">
													<?php echo $p['cantidad'];?>
												</td>
												<td class="text-center"><?php echo @$p['precio']?moneda($p['precio']):moneda(0);?></td>
												<?php if(INTERNO):?>
													<td class="text-center"><?php echo @$p['descuento']?ver_num($p['descuento']).' %':ver_num(0).' %';?></td>
												<?php endif;?>
												<?php if(isset($status_id) && in_array($status_id,array(2,3,4,5)) && INTERNO && !$venta_directa):?>
													<td class="text-center"><?php echo @$p['descuento']?ver_num($p['descuento']).' %':ver_num(0).' %';?></td>
												<?php endif;?>
												<td class="text-center">
													<span class="bc_precio_sub"><?php echo @$p['importe']?moneda($p['importe']):moneda(0);?></span>
												</td>
												<?php if(isset($status_id) && in_array($status_id,array(2,3,4,5)) && INTERNO && !$venta_directa):?>
													<td class="text-center">
														<span class="bc_precio_sub"><?php echo moneda(0);?></span>
													</td>
												<?php endif;?>
												<td class="text-center">&nbsp;</td>
											</tr>
										<?php endforeach;
									endif;
								endif;?>
								<?php if(!empty($producto_regalo)):?>
									<tr>
										<td class="text-center" style="background-color: #f0f0f0">
											<div style="position: relative;">
												<input type="hidden" id="producto_regalo_id" name="producto_regalo_id" value="<?php echo $producto_regalo->id;?>">
												<?php $path=$this->config->item('cloudfiles')?$this->cloud_files->url_publica("files/productos/{$producto_regalo->id}/{$producto_regalo->img_id}.jpg"):"files/productos/{$producto_regalo->id}/{$producto_regalo->img_id}.jpg";?>
												<a class='imagen_fancybox' href="<?php echo site_url('/thumbs/timthumb.php?src='.$path.'&zc=0&q=85&s=500'.'&t='.time());?>">
													<img class="img-thumbnail front_imgTabla" src="<?php echo site_url('/thumbs/timthumb.php?src='.$path.'&s=400&t='.time());?>" />
													<?php $path_regalo=site_url('img/promociones/regalo.png');?>
													<img style="height:50px !important;width:75px !important;position: absolute;z-index: 1;left:0;margin:-8px;top:0;"  src="<?php echo site_url('/thumbs/timthumb.php?src='.$path_regalo.'&s=400&t='.time());?>" />
												</a>
											</div>
										</td>
										<td class="text-center"><?php echo $producto_regalo->modelo;?></td>
										<td><?php echo $producto_regalo->nombre;?></td>
										<td class="text-center"><?php echo $producto_regalo->cantidad.' '.$producto_regalo->unidad;?></td>
										<td class="text-center"><?php echo moneda($producto_regalo->precio);?></td>
										<?php if(INTERNO):?>
											<td class="text-center"><?php echo '100 %'; ?></td>
										<?php endif;?>
										<?php if(isset($status_id) && in_array($status_id,array(2,3,4,5)) && INTERNO && !$venta_directa):?>
											<td class="text-center"><?php echo '100 %'; ?></td>
										<?php endif;?>
										<td class="text-center"><?php echo moneda(0);?></td>
										<?php if(isset($status_id) && in_array($status_id,array(2,3,4,5)) && INTERNO && !$venta_directa):?>
											<td class="text-center"><?php echo moneda(0);?></td>
										<?php endif;?>
										<td class="text-center">&nbsp;</td>
									</tr>
								<?php endif;?>
								</tbody>
								<tfoot>
									<tr>
										<td class="tr front_datoImportante nowrap" colspan="<?php echo (in_array($status_id,array(2,3,4,5)) && !$venta_directa)?'7':'6';?>">Importe Total:</td>
										<td class="front_datoImportante nowrap bc_total"><?php echo $status_id==1?moneda(@$calculo['importe_venta']):moneda(@$r['importe_total']); ?></td>
										<?php if(in_array($status_id,array(2,3,4,5)) && !$venta_directa):?>
											<td class="front_datoImportante nowrap bc_total"><?php echo moneda(@$r['importe_total']);?></td>
										<?php endif;?>
										<td <?php echo (in_array($status_id,array(2,3,4,5)) && !$venta_directa)?'':'colspan="2"';?>>&nbsp;</td>
									</tr>
									<tr>
										<td class="tr front_datoImportante nowrap" colspan="<?php echo (in_array($status_id,array(2,3,4,5)) && !$venta_directa)?'7':'6';?>">Descuento:</td>
										<td class="front_datoImportante nowrap bc_total">
											<?php $descuento_comercial = $status_id==1?@$calculo['descuento_comercial_cliente']-@$calculo['descuento_cliente_cupon']-@$calculo['promocion_opcional_descuento']:@$r['descuento_cliente']-@$r['descuento_cliente_cupon']-@$r['promocion_opcional_descuento'];
												  $descuento_comercial = $descuento_comercial < 0 ? 0 : $descuento_comercial;
											      $descuento_comercial = @$calculo['descuento_cliente_cupon'] && $descuento_comercial>@$calculo['descuento_cliente_cupon']?$descuento_comercial-@$calculo['descuento_cliente_cupon']:$descuento_comercial;?>

											<?php echo moneda($descuento_comercial);?>
										</td>
										<?php if(in_array($status_id,array(2,3,4,5)) && !$venta_directa):?>
											<td class="front_datoImportante nowrap bc_total">
												<?php $descuento_comercial_distribuidor = @$r['descuento_distribuidor']+@$r['envio'];
													  $descuento_comercial_distribuidor = @$r['descuento_distribuidor_cupon'] && $descuento_comercial_distribuidor>@$r['descuento_distribuidor_cupon']?$descuento_comercial_distribuidor-@$r['descuento_distribuidor_cupon']-@$r['promocion_opcional_descuento']:$descuento_comercial_distribuidor;?>
												<?php echo moneda($descuento_comercial_distribuidor);?>
											</td>
										<?php endif;?>
										<td <?php echo (in_array($status_id,array(2,3,4,5)) && !$venta_directa)?'':'colspan="2"';?>>&nbsp;</td>
									</tr>
									<?php if(@$calculo['promocion_descuento_porcentaje']):?>
										<tr>
											<td class="tr front_datoImportante nowrap" colspan="<?php echo (in_array($status_id,array(2,3,4,5)) && !$venta_directa)?'7':'6';?>">Descuento de <b><?php echo @ver_num($calculo['promocion_descuento_porcentaje']).'%';?></b> por promoci&oacute;n</td>
											<td class="front_datoImportante nowrap"><?php echo moneda(@$calculo['promocion_descuento_porcentaje_monto'])?></td>
											<?php if(in_array($status_id,array(2,3,4,5)) && !$venta_directa):?>
												<td class="front_datoImportante nowrap"><?php echo moneda(@$calculo['promocion_descuento_porcentaje_monto'])?></td>
											<?php endif;?>
											<td <?php echo (in_array($status_id,array(2,3,4,5)) && !$venta_directa)?'':'colspan="2"';?>>&nbsp;</td>
										</tr>
									<?php endif;?>
									<?php if(@$calculo['promocion_descuento_fijo']):?>
										<tr>
											<td class="tr front_datoImportante nowrap" colspan="<?php echo (in_array($status_id,array(2,3,4,5)) && !$venta_directa)?'7':'6';?>">Descuento fijo por promoci&oacute;n</td>
											<td class="front_datoImportante nowrap"><?php echo moneda(@$calculo['promocion_descuento_fijo'])?></td>
											<?php if(in_array($status_id,array(2,3,4,5)) && !$venta_directa):?>
												<td class="front_datoImportante nowrap"><?php echo moneda(@$calculo['promocion_descuento_fijo'])?></td>
											<?php endif;?>
											<td <?php echo (in_array($status_id,array(2,3,4,5)) && !$venta_directa)?'':'colspan="2"';?>>&nbsp;</td>
										</tr>
									<?php endif;?>
									<?php if(@$calculo['promocion_opcional_descuento']):?>
										<tr>
											<td class="tr front_datoImportante nowrap" colspan="<?php echo (in_array($status_id,array(2,3,4,5)) && !$venta_directa)?'7':'6';?>">Descuento por promoci&oacute;n</td>
											<td class="front_datoImportante nowrap"><?php echo moneda(@$calculo['promocion_opcional_descuento'])?></td>
											<?php if(in_array($status_id,array(2,3,4,5)) && !$venta_directa):?>
												<td class="front_datoImportante nowrap"><?php echo moneda(@$calculo['promocion_opcional_descuento'])?></td>
											<?php endif;?>
											<td <?php echo (in_array($status_id,array(2,3,4,5)) && !$venta_directa)?'':'colspan="2"';?>>&nbsp;</td>
										</tr>
									<?php endif;?>
									<?php if(isset($calculo['folio_cupon']) || @$r['descuento_cupon'] || @$r['descuento_valor_cupon']):?>
										<tr>
											<td class="tr front_datoImportante nowrap" colspan="<?php echo (in_array($status_id,array(2,3,4,5)) && !$venta_directa)?'7':'6';?>">Descuento por Cup&oacute;n:</td>
											<td class="front_datoImportante nowrap bc_total">
												<?php echo @$calculo['descuento_cliente_cupon']?moneda(@$calculo['descuento_cliente_cupon']):moneda(@$r['descuento_cliente_cupon']);?>
											</td>
											<?php if(in_array($status_id,array(2,3,4,5)) && !$venta_directa):?>
												<td class="front_datoImportante nowrap bc_total">
													<?php echo @$calculo['descuento_distribuidor_cupon']?moneda(@$calculo['descuento_distribuidor_cupon']):moneda(@$r['descuento_distribuidor_cupon']);?>
												</td>
											<?php endif;?>
											<td <?php echo (in_array($status_id,array(2,3,4,5)) && !$venta_directa)?'':'colspan="2"';?>>&nbsp;</td>
										</tr>
									<?php endif;?>
									<?php if(in_array($status_id,array(2,3,4,5)) && @$r['descuento_paquete_distribuidor'] && !$venta_directa):?>
									<tr>
										<td class="tr front_datoImportante nowrap" colspan="<?php echo (in_array($status_id,array(2,3,4,5)) && !$venta_directa)?'7':'6';?>">
											Descuento (<?php echo (@$r['descuento_paquete_distribuidor']*100).' %';?>) - <?php echo $calculo['paquete_adquirido']['nombre'];?>
										</td>
										<td class="front_datoImportante nowrap bc_total">&nbsp;</td>
										<?php if(in_array($status_id,array(2,3,4,5)) && !$venta_directa):?>
											<td class="front_datoImportante nowrap bc_total"><?php echo moneda(@$r['descuento_paquete_distribuidor']*$r['importe_total']);?></td>
										<?php endif;?>
										<td <?php echo (in_array($status_id,array(2,3,4,5)) && !$venta_directa)?'':'colspan="2"';?>>&nbsp;</td>
									</tr>
									<?php endif;?>

									<?php if(@$r['envio']): ?>
										<tr>
											<td class="tr front_datoImportante nowrap" colspan="<?php echo (in_array($status_id,array(2,3,4,5)) && !$venta_directa)?'7':'6'?>">Cargo de recuperaci&oacute;n (incluye env&iacute;o e instalaci&oacute;n):</td>
											<td class="front_datoImportante nowrap bc_total"><?php echo $status_id==1?moneda(@$calculo['envio']):moneda(@$r['envio']); ?></td>
											<td>&nbsp;</td>
											<?php if(!$venta_directa && !in_array($status_id,array(1))):?>
												<td>&nbsp;</td>
											<?php endif;?>
										</tr>
									<?php endif;?>

									<tr>
										<td class="tr front_datoImportante nowrap" colspan="<?php echo (in_array($status_id,array(2,3,4,5)) && !$venta_directa)?'7':'6';?>">Subtotal:</td>
										<td class="front_datoImportante nowrap bc_total"><?php echo $status_id==1?moneda(@$calculo['subtotal_cliente']):moneda(@$r['subtotal_cliente']); ?></td>
										<?php if(in_array($status_id,array(2,3,4,5)) && !$venta_directa):?>
										<td class="front_datoImportante nowrap bc_total"><?php echo moneda(@$r['subtotal_distribuidor']); ?></td>
										<?php endif;?>
										<td <?php echo (in_array($status_id,array(2,3,4,5)) && !$venta_directa)?'':'colspan="2"';?>>&nbsp;</td>
									</tr>
									<tr>
										<td class="tr front_datoImportante nowrap" colspan="<?php echo (in_array($status_id,array(2,3,4,5)) && !$venta_directa)?'7':'6'?>">IVA ( 16 % ):</td>
										<td class="front_datoImportante nowrap"><?php echo $status_id==1?moneda(@$calculo['iva_cliente']):moneda(@$r['iva_cliente']);?></td>
										<?php if(in_array($status_id,array(2,3,4,5)) && !$venta_directa):?>
										<td class="front_datoImportante nowrap"><?php echo moneda(@$r['iva_distribuidor']);?></td>
										<?php endif;?>
										<td <?php echo (in_array($status_id,array(2,3,4,5)) && !$venta_directa)?'':'colspan="2"';?>>&nbsp;</td>
									</tr>
									<tr>
										<td class="tr front_datoImportante nowrap" colspan="<?php echo (in_array($status_id,array(2,3,4,5)) && !$venta_directa)?'7':'6'?>">TOTAL:</td>
										<td class="front_datoImportante nowrap"><?php echo $status_id==1?moneda(@$calculo['total_cliente']):moneda(@$r['total_cliente']); ?></td>
										<?php if(in_array($status_id,array(2,3,4,5)) && !$venta_directa):?>
										<td class="front_datoImportante nowrap"><?php echo moneda(@$r['total_distribuidor']); ?></td>
										<?php endif;?>
										<td <?php echo (in_array($status_id,array(2,3,4,5)) && !$venta_directa)?'':'colspan="2"';?>>&nbsp;</td>
									</tr>
									<?php if($aplicar_cupon && @$r['opcion_cupon_id']==2):?>
										<tr>
											<td class="tr front_datoImportante nowrap" colspan="<?php echo (in_array($status_id,array(2,3,4,5)) && !$venta_directa)?'7':'6'?>">Su pago a 12 meses sin intereses por Cup&oacute;n:</td>
											<?php if(isset($status_id) && in_array($status_id,array(2,3,4,5)) && !$venta_directa):?>
												<td class="front_datoImportante nowrap"><?php echo moneda(@$r['mensualidad_cliente_cupon'])?></td>
												<td class="front_datoImportante nowrap"><?php echo moneda(@$r['mensualidad_distribuidor_cupon'])?></td>
												<td>&nbsp;</td>
											<?php else:?>
												<td class="front_datoImportante nowrap"><?php echo moneda(@$r['mensualidad_cliente_cupon'])?></td>
												<td <?php echo (in_array($status_id,array(2,3,4,5)) && !$venta_directa)?'':'colspan="2"';?>>&nbsp;</td>
											<?php endif;?>
										</tr>
									<?php endif;?>
									<?php if(@$calculo['promocion_msi'] && INTERNO):?>
										<tr>
											<td class="tr front_datoImportante nowrap" colspan="<?php echo (in_array($status_id,array(2,3,4,5)) && !$venta_directa)?'7':'6'?>">Su pago a <?php echo $calculo['promocion_msi'];?> meses sin intereses por promoci&oacute;n:</td>
											<td class="front_datoImportante nowrap"><?php echo moneda(@$calculo['promocion_msi_cliente'])?></td>
											<?php if(isset($status_id) && in_array($status_id,array(2,3,4,5)) && !$venta_directa):?>
												<td class="front_datoImportante nowrap">&nbsp;<?php //echo moneda(@$calculo['promocion_msi_distribuidor'])?></td>
											<?php endif;?>
											<td <?php echo (in_array($status_id,array(2,3,4,5)) && !$venta_directa)?'':'colspan="2"';?>>&nbsp;</td>
										</tr>
									<?php endif;?>
									<tr><td class="tr front_datoImportante_negro nowrap" colspan="<?php echo (isset($status_id) && in_array($status_id,array(2,3,4,5)) && !$venta_directa)?'11':'10'?>">*Los precios de la cotizaci&oacute;n pudieran
											variar durante el proceso de pre-compra, se considerarán los precios finales en el momento de la autorización de la orden de compra.</td></tr>
									<tr><td class="tr front_datoImportante_negro nowrap" colspan="<?php echo (isset($status_id) && in_array($status_id,array(2,3,4,5)) && !$venta_directa)?'11':'10'?>">*Los precios est&aacute;n sujetos a cambios o modificaciones de acuerdo a la pol&iacute;tica comercial vigente.</td></tr>
									<?php if(@$promociones_alianzas):foreach($promociones_alianzas as $a):?>
										<tr><td class="tr front_datoImportante_negro nowrap" colspan="<?php echo (isset($status_id) && in_array($status_id,array(2,3,4,5)) && !$venta_directa)?'11':'10'?>">*Regalo adquirido por ALIANZA <?php echo $a['nombre'];?>.
												<?php if(@empty($r['recibo_pago_cdn'])):?>Para adquirir su códgio de reclamación debe realizar el pago de su compra.<?php else:?>
													Para reclamar su regalo, deberá presentar el siguiente CÓDIGO: <?php echo $a['codigo'];?> directamente con el proveedor.<?php endif;?>
											</td></tr>
									<?php endforeach;endif;?>
								</tfoot>
							</table>
						</div>
						<div class="row">
							<div class="col-lg-2 form-group">
								<div class="checkbox">
									<input name="descuento_opcional" value="0" type="hidden" />
									<label style="opacity:0.4;">
									<?php $descuento_opcional= isset($calculo['descuento_opcional'])?$calculo['descuento_opcional']:@$r['descuento_opcional'];?>
									<input class="form-control-1" type="checkbox" name="descuento_opcional" value="1" <?php echo $descuento_opcional == 1?'checked':'' ?> class="descuento_opcional" id="descuento_opcional" readonly />
									Descuento Opcional</label>
									<?php echo form_error('descuento_opcional');?>
								</div>
							</div>
							<div class="col-lg-2 form-group">
								<div class="checkbox">
									<input name="descuento_paquete" value="0" type="hidden" />
									<label style="opacity:0.4;">
									<?php $descuento_paquete= isset($calculo['descuento_paquete'])?$calculo['descuento_paquete']:@$r['descuento_paquete'];?>
									<input class="form-control-1" type="checkbox" name="descuento_paquete" value="1" <?php echo $descuento_paquete == 1?'checked':'' ?> class="descuento_paquete opacity" id="descuento_paquete" readonly />
									Descuento Paquete</label>
									<?php echo form_error('descuento_paquete');?>
								</div>
							</div>
							<?php if($venta_directa && INTERNO):?>
								<div class="col-lg-2 form-group">
									<div class="checkbox">
										<input name="promocion_msi_amex" value="0" type="hidden" id="promocion_mensualidades"/>
										<label style="opacity:0.4;">
											<?php $promocion_msi_amex = isset($calculo['promocion_msi']) && $calculo['promocion_msi']==12?$calculo['promocion_msi']:(@$r['promocion_msi']==12?$r['promocion_msi']:0);?>
											<input class="form-control-1" type="checkbox" name="promocion_msi_amex" value="12" <?php echo $promocion_msi_amex?'checked':'' ?> class="promocion_msi form-control-1" id="promocion_msi_amex" readonly />
											12 MSI American Express</label>
										<?php echo form_error('promocion_msi_amex');?>
									</div>
								</div>
							<?php endif;?>
							<?php if($venta_directa && INTERNO && @$banamex_msi_vigente):?>
								<div class="col-lg-2 form-group">
									<div class="checkbox">
										<input name="promocion_msi_banamex" value="0" type="hidden" id="promocion_mensualidades_banamex"/>
										<label style="opacity:0.4;">
											<?php $promocion_msi_banamex = isset($calculo['promocion_msi']) && $calculo['promocion_msi']==18?$calculo['promocion_msi']:(@$r['promocion_msi']==18?$r['promocion_msi']:0);?>
											<input class="form-control-1" type="checkbox" name="promocion_msi_banamex" value="18" <?php echo $promocion_msi_banamex?'checked':'' ?> class="promocion_msi" id="promocion_msi_banamex" readonly />
											18 MSI Banamex</label>
										<?php echo form_error('promocion_msi_banamex');?>
									</div>
								</div>
							<?php endif;?>

							<?php if($venta_directa && INTERNO):?>
								<div class="col-lg-2 form-group">
									<div class="checkbox">
										<input name="rescate_sucursal" value="0" type="hidden"/>
										<label style="opacity:0.4;">
											<?php $rescate_sucursal = isset($calculo['rescate_sucursal'])?$calculo['rescate_sucursal']:@$r['rescate_sucursal'];?>
											<input class="form-control-1" type="checkbox" name="rescate_sucursal" value="1" <?php echo $rescate_sucursal?'checked':'' ?> id="rescate" readonly />
											Sin env&iacute;o (venta en tienda) </label>
										<?php echo form_error('rescate_sucursal');?>
									</div>
								</div>
							<?php endif;?>

							<?php if($aplicar_cupon):?>
							<div class="col-lg-2 form-group">
								<div class="checkbox">
									<input name="descuento_cupon" value="0" type="hidden"/>
									<label style="opacity:0.4;">
										<?php $cupon = isset($calculo['descuento_cupon'])?$calculo['descuento_cupon']:@$r['descuento_cupon'];?>
										<input class="form-control-1" type="checkbox" name="descuento_cupon" value="1" <?php echo $cupon == 1?'checked':'' ?> id="cupon" readonly />
										Aplicar Cup&oacute;n</label>
									<?php echo form_error('descuento_cupon');?>
								</div>
							</div>
							<?php endif;?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php if($aplicar_cupon && @$r['cupones_id']):?>
		<div class="panel-group front_panel" id="cupones">
			<div id="cupon_loader" class="ajax-loading" style="display: none;"></div>
			<div class="cupon" id="panel_cupones" <?php echo (isset($calculo['cupon']) && isset($calculo['cupones_id'])) || (isset($r['descuento_cupon']) && isset($r['cupones_id']))?'':'style="display: none;"'?>>
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordionCupon" href="#collapseCupon">
								Datos del Cup&oacute;n
							</a>
						</h4>
					</div>
					<div id="collapseCupon" class="panel-collapse collapse in">
						<div class="panel-body">
							<div class="row">
								<p class="msg cupon_done" title="De click para cerrar" style="display: none;">
									<span class="fa-stack fa-2x">
									  <i class="fa fa-circle fa-stack-2x text-color-blanco"></i>
									  <i class="fa fa-check fa-stack-1x text-color-done"></i>
									</span>
									El cup&oacute;n es v&aacute;lido. Por favor elija la opci&oacute;n que desea aplicar a la cotizaci&oacute;n.
								</p>
								<p class="msg cupon_error" style="display: none;">
									<span class="fa-stack fa-2x">
									  <i class="fa fa-circle fa-stack-2x text-color-blanco"></i>
									  <i class="fa fa-exclamation fa-stack-1x text-color-danger"></i>
									</span>
									El cup&oacute;n que ingres&oacute; no es v&aacute;lido. Verifique el folio e int&eacute;ntelo nuevamente.
								</p>
								<div class="col-lg-3 form-group">
									<fieldset class="scheduler-border">
										<legend class="scheduler-border">Folio del Cup&oacute;n:</legend>
										<label <?php echo @$status_posterior?'style="opacity:0.4;"':'';?>>Folio</label>
										<?php $folio_cupon= isset($calculo['folio_cupon'])?$calculo['folio_cupon']:@$r['folio_cupon'];?>
										<input name="folio_cupon" value="<?php echo $folio_cupon;?>" type="text" id="folio_cupon" class="form-control" readonly /></br></br>
										<?php echo form_error('folio_cupon');?>
										<!--a class="btn front-btn-autorizar pull-right" href="<?php //echo site_url('cupones/validar'); ?>" id="usar_cupon">Validar</a>-->
									</fieldset>
								</div>
								<div class="col-lg-5" <?php echo (isset($calculo['descuento_cupon']) && isset($calculo['cupones_id']) && isset($calculo['opcion_cupon_id'])) || (isset($r['descuento_cupon']) && isset($r['cupones_id']) && isset($r['opcion_cupon_id']))?'':'style="display: none;"'?> id="opciones">
									<fieldset class="scheduler-border" id="opcion">
										<?php if(@$cupones_opciones):?>
											<legend class="scheduler-border">Opciones:</legend>
												<?php foreach($cupones_opciones as $cp=>$label):?>
											<?php $opcion_cupon_id = isset($calculo['opcion_cupon_id'])?@$calculo['opcion_cupon_id']:@$r['opcion_cupon_id'];?>
													<input class="alinear_radio opciones_id form-control-1" type="radio" name="opcion_cupon_id" value="<?php echo $cp;?>" <?php echo $opcion_cupon_id==$cp?'checked="checked"':'' ?> readonly/><label>&nbsp;<?php echo $label['label'];?></label></br>
												<?php endforeach; ?>
											<!--<a class="btn front-btn-autorizar pull-right" href="#" id="aplicar_cupon">Aplicar</a>-->
											<input type="hidden" id="cupones_id" name="cupones_id" value="<?php echo isset($calculo['cupones_id'])?$calculo['cupones_id']:@$r['cupones_id']?>"/>
										<?php endif;?>
									</fieldset>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php endif;?>
		<?php echo $this->load->view('frontend/direccion_envio');?>
	    <?php echo $this->load->view('frontend/direccion_instalacion');?>
	    	<div class="panel-group front_panel" id="accordion5">
		  		<div class="panel panel-default">
				    <div class="panel-heading">
				      <h4 class="panel-title">
				        <a data-toggle="collapse" data-parent="#accordion5" href="#collapseFive">
				          Datos de facturaci&oacute;n
				        </a>
				      </h4>
				    </div>
				    <div id="collapseFive" class="panel-collapse collapse in">
				      	<div class="panel-body">
					        <div class="row">
					            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					        		<label><span class="req">*</span>Facturaci&oacute;n para:</label>
					        		<?php if($venta_directa):?>
					        			<?php if(in_array(@$status_id,array(1,6))):?>
							        		<select name="tipo_persona_id" id="tipo_persona_id" class="form-control <?php echo form_error('tipo_persona_id')?'rojo':'';?>">
				                				<option value=""><?php echo $this->config->item('empty_select');?></option>
				                				<?php foreach(catalogo('tipo_persona_fiscal',FALSE) as $k=>$v):?>
				                				<option value="<?php echo $k; ?>" <?php echo $k == @$r['tipo_persona_id']?'selected':''; ?> ><?php echo $v; ?></option>
				                				<?php endforeach;?>
				                			</select>
			                			<?php else:?>
				                			<input type="hidden" name="tipo_persona_id" value="<?php echo @$r['tipo_persona_id'];?>">
			                				<div class="field-info <?php echo form_error('tipo_persona_id')?'rojo':'';?>"><?php echo elemento('tipo_persona_fiscal',@$r['tipo_persona_id']);?></div>
			                			<?php endif;?>
		                			<?php else:?>
		                				<input type="hidden" name="tipo_persona_id" value="<?php echo @$facturacion['tipo_persona_id'];?>">
		                				<div class="field-info <?php echo form_error('tipo_persona_id')?'rojo':'';?>"><?php echo elemento('tipo_persona_fiscal',@$facturacion['tipo_persona_id']);?></div>
		                			<?php endif;?>
		                			<?php echo form_error('tipo_persona_id');?>
					        	</div>
					            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					        		<label><span class="req">*</span>Nombre o Raz&oacute;n social:</label>
					        		<input class="form-control <?php echo form_error('razon_social')?'rojo':'';?>" type="text" id="razon_social"  name="razon_social" <?php echo (!$venta_directa || ($venta_directa && !in_array(@$status_id,array(1,6))))?'readonly':'';?> 
					        			value="<?php echo !$venta_directa?@$facturacion['razon_social']:set_value('razon_social',@$r['razon_social']); ?>"/>
									<?php echo form_error('razon_social');?>
					        	</div>
					        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					        		<label><span class="req">*</span>RFC:</label>
									<input class="form-control <?php echo form_error('rfc')?'rojo':'';?>" type="text" id="rfc" name="rfc" <?php echo (!$venta_directa || ($venta_directa && !in_array(@$status_id,array(1,6))))?'readonly':'';?> 
										value="<?php echo !$venta_directa?@$facturacion['rfc']:set_value('rfc',@$r['rfc']); ?>" />
									<?php echo form_error('rfc');?>
					        	</div>
					        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
		        	        		<label><span class="req">*</span>Correo electr&oacute;nico:</label>
		    					    <input class="form-control <?php echo form_error('email')?'rojo':'';?>" id="email" <?php echo (!$venta_directa || ($venta_directa && !in_array(@$status_id,array(1,6))))?'readonly':'';?> name="email"  
		    					    	value="<?php echo !$venta_directa?@$facturacion['email']:set_value('email',@$r['email']); ?>"/>
		        					<?php echo form_error('email');?>
		        	        	</div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					        		<label><span class="req">*</span>Estado:</label>
					        		<?php if($venta_directa):?>
					        			<?php if(in_array(@$status_id,array(1,6))):?>
											<select class="form-control <?php echo form_error('estado')?'rojo':'';?>" name="estado" id="estado" >
												<option value=""><?php echo $this->config->item('empty_select');?></option>
												<?php foreach($estados as $e):?>
												<option value="<?php echo $e; ?>" <?php echo ($e==@$r['estado'])?'selected="selected"':''; ?>><?php echo $e; ?></option>
												<?php endforeach;?>
											</select>
										<?php else:?>
				                			<input type="hidden" name="estado" value="<?php echo @$r['estado'];?>">
		                					<div class="field-info <?php echo form_error('estado')?'rojo':'';?>"><?php echo @$r['estado'];?></div>
			                			<?php endif;?>
									<?php else:?>
		                				<input type="hidden" name="estado" value="<?php echo @$facturacion['estado'];?>">
		                				<div class="field-info <?php echo form_error('estado')?'rojo':'';?>"><?php echo @$facturacion['estado'];?></div>
		                			<?php endif;?>
									<?php echo form_error('estado');?>
					        	</div>
					        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					        		<label><span class="req">*</span>Delegaci&oacute;n o municipio:</label>
					        		<?php if($venta_directa):?>
					        			<?php if(in_array(@$status_id,array(1,6))):?>
											<select class="form-control <?php echo form_error('municipio')?'rojo':'';?>" name="municipio" id="municipio" >
												<option value=""><?php echo $this->config->item('empty_select');?></option>
												<?php foreach($municipios as $m):?>
												<option value="<?php echo $m; ?>" <?php echo ($m == @$r['municipio'])?'selected="selected"':''; ?>><?php echo $m; ?></option>
												<?php endforeach;?>
											</select>
										<?php else:?>
				                			<input type="hidden" name="municipio" value="<?php echo @$r['municipio'];?>">
		                					<div class="field-info <?php echo form_error('municipio')?'rojo':'';?>"><?php echo @$r['municipio'];?></div>
			                			<?php endif;?>
									<?php else:?>
		                				<input type="hidden" name="municipio" value="<?php echo @$facturacion['municipio'];?>">
		                				<div class="field-info <?php echo form_error('municipio')?'rojo':'';?>"><?php echo @$facturacion['municipio'];?></div>
		                			<?php endif;?>
									<?php echo form_error('municipio');?>
					        	</div>
					        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					        		<label><span class="req">*</span>C&oacute;digo postal:</label>
		        		       		<div class="input-group">
						        		<input class="form-control <?php echo form_error('codigo_postal')?'rojo':'';?>" type="text" id="codigo_postal" <?php echo (!$venta_directa || ($venta_directa && !in_array(@$status_id,array(1,6))))?'readonly':'';?> name="codigo_postal" 
						        			value="<?php echo !$venta_directa?@$facturacion['codigo_postal']:set_value('codigo_postal',@$r['codigo_postal']); ?>"/>
						        		<span class="input-group-addon">
						        		<?php if(in_array(@$status_id,array(2,3,4,5))):?>
						        			<i class="fa fa-search"></i>
					        			<?php else:?>
			    					       <a  class="buscar_ref icono_info" title="Buscar direcci&oacute;n en base al c&oacute;digo postal." href="#" id="entrega_dir_search"><i class="fa fa-search"></i></a>
			    					    <?php endif;?>
			    					   	</span>
									</div>
									<?php echo form_error('codigo_postal');?>
					        	</div>
					        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					        		<label><span class="req">*</span>Colonia:</label>
					        		<div class="input-group">
						        		<input class="form-control <?php echo form_error('asentamiento')?'rojo':'';?>" type="text" id="asentamiento" <?php echo (!$venta_directa || ($venta_directa && !in_array(@$status_id,array(1,6))))?'readonly':'';?> name="asentamiento" 
						        		value="<?php echo !$venta_directa?@$facturacion['asentamiento']:set_value('asentamiento',@$r['asentamiento']); ?>"/>
						        		<span class="input-group-addon">
						        		<?php if(in_array(@$status_id,array(2,3,4,5))):?>
						        			<i class="fa fa-search"></i>
					        			<?php else:?>
			    					       <a  class="buscar_ref icono_info" title="Buscar c&oacute;digo postal en base a la colonia" href="#" id="cp_search"><i class="fa fa-search"></i></a>
			    					      <?php endif;?>
			    					   	</span>
		    					   	</div>
									<?php echo form_error('asentamiento');?>
					        	</div>
					        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					        		<label><span class="req">*</span>Calle:</label>
					        		<input class="form-control <?php echo form_error('calle')?'rojo':'';?>" type="text" id="calle" name="calle" <?php echo (!$venta_directa || ($venta_directa && !in_array(@$status_id,array(1,6))))?'readonly':'';?> 
					        			value="<?php echo !$venta_directa?@$facturacion['calle']:set_value('calle',@$r['calle']); ?>"/>
									<?php echo form_error('calle');?>
					        	</div>
					        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					        		<label><span class="req">*</span>N&uacute;mero exterior:</label>
					        		<input class="form-control <?php echo form_error('numero_exterior')?'rojo':'';?>" type="text" id="numero_exterior" name="numero_exterior" <?php echo (!$venta_directa || ($venta_directa && !in_array(@$status_id,array(1,6))))?'readonly':'';?> 
					        			value="<?php echo !$venta_directa?@$facturacion['numero_exterior']:set_value('numero_exterior',@$r['numero_exterior']); ?>"/>
									<?php echo form_error('numero_exterior');?>
					        	</div>
					        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					        		<label>N&uacute;mero interior:</label>
					        		<input class="form-control <?php echo form_error('numero_interior')?'rojo':'';?>" type="text" id="numero_interior" name="numero_interior" <?php echo (!$venta_directa || ($venta_directa && !in_array(@$status_id,array(1,6))))?'readonly':'';?> 
					        		value="<?php echo !$venta_directa?@$facturacion['numero_interior']:set_value('numero_interior',@$r['numero_interior']); ?>"/>
									<?php echo form_error('numero_interior');?>
					        	</div>
					        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
		        	        		<label>Tel&eacute;fono:</label>
		        					<input class="form-control <?php echo form_error('telefono')?'rojo':'';?>" id="telefono" <?php echo (!$venta_directa || ($venta_directa && !in_array(@$status_id,array(1,6))))?'readonly':'';?> name="telefono"  
		        						value="<?php echo !$venta_directa?@$facturacion['telefono']:set_value('telefono',@$r['telefono']); ?>"/>
		        					<?php echo form_error('telefono');?>
		        	        	</div>
					        </div>
			        	</div>
			        </div>
				</div>
	     	</div>
	    <div class="panel-group front_panel" id="accordion6">
		  	<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
				        <a data-toggle="collapse" data-parent="#accordion6" href="#collapseSix">
				          Condicones de Pago
				        </a>
				    </h4>
				</div>
				<div id="collapseSix" class="panel-collapse collapse in">
					<div class="panel-body">
				        <div class="row">
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
								<label><span class="req">*</span>Forma de Pago:</label>
								<?php if($venta_directa):?>
				        			<?php if(in_array($status_id,array(1,6))):?>
										<select class="form-control <?php echo form_error('forma_pago_id')?'rojo':'';?>" name="forma_pago_id" id="forma_pago_id">
											<option value=""><?php echo $this->config->item('empty_select'); ?></option>
											<?php foreach($formas_pago as $k=>$v):?>
												<option value="<?php echo $k?>" <?php echo ($k == @$r['forma_pago_id'])?'selected="selected"':''; ?>><?php echo $v;?></option>
											<?php endforeach;?>
										</select>
									<?php else:?>
			                			<input type="hidden" name="forma_pago_id" value="<?php echo @$r['forma_pago_id']?>">
	                					<div class="field-info <?php echo form_error('forma_pago_id')?'rojo':'';?>"><?php echo @$formas_pago[$r['forma_pago_id']];?></div>
		                			<?php endif;?>
								<?php else:?>
	                				<input type="hidden" name="forma_pago_id" value="1">
	                				<div class="field-info <?php echo form_error('forma_pago_id')?'rojo':'';?>"><?php echo @$formas_pago[1];?></div>
	                			<?php endif;?>
								<?php echo form_error('forma_pago_id');?>
							</div>
<!--							--><?php //if($venta_directa):?>
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
								<label><span class="req">*</span>Condiciones de Pago:</label>
				        			<?php if(in_array($status_id,array(1,6))):?>
										<select class="form-control <?php echo form_error('condiciones_pago_id')?'rojo':'';?>" name="condiciones_pago_id" id="condiciones_pago_id">
											<option value=""><?php echo $this->config->item('empty_select'); ?></option>
											<?php foreach($condiciones_pago as $k=>$v):?>
												<option value="<?php echo $k?>" <?php echo ($k == @$r['condiciones_pago_id'])?'selected="selected"':''; ?>><?php echo $v;?></option>
											<?php endforeach;?>
										</select>
									<?php else:?>
			                			<input type="hidden" name="condiciones_pago_id" value="<?php echo @$r['condiciones_pago_id'];?>">
	                					<div class="field-info <?php echo form_error('condiciones_pago_id')?'rojo':'';?>"><?php echo @$condiciones_pago[$r['condiciones_pago_id']];?></div>
		                			<?php endif;?>
								<?php //else:?>
	                				<!-- <input type="hidden" name="municipio" value="<?php echo @$r['condiciones_pago_id'];?>">
	                				<div class="field-info"><?php echo @$condiciones_pago[$r['condiciones_pago_id']];?></div> -->
								<?php echo form_error('condiciones_pago_id');?>
							</div>
<!--	                		--><?php //endif;?>
				      	</div>
				    </div>
				</div>
		  	</div>
		</div>
	     	<div class="panel-group front_panel" id="accordion7">
		  		<div class="panel panel-default">
				    <div class="panel-heading">
				      <h4 class="panel-title">
				        <a data-toggle="collapse" data-parent="#accordion7" href="#collapseSeven">
				          Informaci&oacute;n del Vendedor
				        </a>
				      </h4>
				    </div>
				    <div id="collapseSeven" class="panel-collapse collapse in">
				      <div class="panel-body">
				        <div class="row">
					        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
								<label><span class="req">*</span>Nombre del Vendedor:</label>
								<input type="hidden" name="usuario_id" value="<?php echo @$r['usuario_id'];?>"/>
								<input class="form-control <?php echo form_error('vendedor_nombre')?'rojo':'';?>" type="text" id="vendedor_nombre" name="vendedor_nombre" value="<?php echo set_value('vendedor_nombre',@$r['vendedor_nombre']); ?>" readonly="readonly"/>
								<?php echo form_error('vendedor_nombre');?>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
								<label><span class="req">*</span>Apellido Paterno del Vendedor:</label>
								<input class="form-control <?php echo form_error('vendedor_paterno')?'rojo':'';?>" type="text" id="vendedor_paterno" name="vendedor_paterno" value="<?php echo set_value('vendedor_paterno',@$r['vendedor_paterno']); ?>" readonly="readonly"/>
								<?php echo form_error('vendedor_paterno');?>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
								<label><span class="req">*</span>Apellido Materno del Vendedor:</label>
								<input class="form-control <?php echo form_error('vendedor_materno')?'rojo':'';?>" type="text" id="vendedor_materno" name="vendedor_materno" value="<?php echo set_value('vendedor_materno',@$r['vendedor_materno']); ?>" readonly="readonly"/>
								<?php echo form_error('vendedor_materno');?>
							</div>
				        </div>
				        <div class="row">
				        	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
					        	<label>Observaciones:</label>
					        	<textarea class="form-control <?php echo form_error('observaciones')?'rojo':'';?>" id="observaciones" name="observaciones" rows="6"><?php echo set_value('observaciones',@$r['observaciones']); ?></textarea>
								<?php echo form_error('observaciones');?>
							</div>
				        </div>
				      </div>
				    </div>
				</div>
		  	</div>
		  	<?php if(in_array($status_id,array(2,3,4,5))):?>
		  		<?php if((!$venta_directa && $cuenta->credito!=1 ) || $venta_directa):?>
			  	<div class="panel-group front_panel" id="accordion7">
					<div class="panel panel-default">
					    <div class="panel-heading">
					      <h4 class="panel-title">
					        <a data-toggle="collapse" data-parent="#accordion7" href="#collapseSeven">
					          Documentaci&oacute;n
					        </a>
					      </h4>
					    </div>
					    <div id="collapseSeven" class="panel-collapse collapse in">
					      <div class="panel-body">
					        <div class="row">
					        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					        	    <div class="fileinputs">
										<input  class="file"  id="recibo_pago" name="recibo_pago" value="" type="file"/>
										<div class="fakefile">
											<a href="" class="btn btn-front-primary"><i class="fa fa-upload"></i>  Comprobante de pago</a>
										</div>
									</div>
				    				<?php echo form_error('recibo_pago');?>
				    			</div>
				    			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
				    				<?php if(@$r['recibo_pago_cdn'] && substr($r['recibo_pago_cdn'],-3)=='jpg'):?>
				    				<?php $path = $r['recibo_pago_cdn'];?>
				    					<a href="<?php echo site_url('/thumbs/timthumb.php?src='.$path.'&zc=0&q=85&s=700&t='.time());?>" class="imagen_fancybox">
						                	<img src="<?php echo  site_url('/thumbs/timthumb.php?src='.$path.'&s=200&t='.time());?>" class="img-thumbnail">
						            	</a>
				    				<?php endif;?>
				    				<?php if(@$r['recibo_pago_cdn'] && substr($r['recibo_pago_cdn'],-3)=='pdf'):?>
				    					<a href=<?php echo $r['recibo_pago_cdn'];?>>
				    						<img src="<?php echo site_url('/thumbs/timthumb.php?src='.site_url("img/bc/icono_pdf.jpg").'&s='.$this->config->item('logo_thumb_size'));?>" class="img_thumb" />
				    					</a>	
				    				<?php endif;?>
				    			</div>
				    			<?php if($venta_directa):?>
					    			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					    				<div class="fileinputs">
											<input  class="file"  id="orden_firmada" name="orden_firmada" value="" type="file"/>
											<div class="fakefile">
												<a href="" class="btn btn-front-primary"><i class="fa fa-upload"></i>  Orden firmada</a>
											</div>
										</div>
					    				<?php echo form_error('orden_firmada');?>
					    			</div>
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group ">
					        			<?php if($r['orden_firmada_cdn'] && substr($r['orden_firmada_cdn'],-3)=='jpg'):?>
				    					<?php $path = $r['orden_firmada_cdn'];?>
					    					<a href="<?php echo site_url('/thumbs/timthumb.php?src='.$path.'&zc=0&q=85&s=700&t='.time());?>" class="imagen_fancybox">
							                	<img src="<?php echo  site_url('/thumbs/timthumb.php?src='.$path.'&s=200&t='.time());?>" class="img-thumbnail">
							            	</a>	
					    				<?php endif;?>
					    				<?php if($r['orden_firmada_cdn'] && substr($r['orden_firmada_cdn'],-3)=='pdf'):?>
					    					<a href=<?php echo $r['orden_firmada_cdn'];?>>
					    						<img src="<?php echo site_url('/thumbs/timthumb.php?src='.site_url("img/bc/icono_pdf.jpg").'&s='.$this->config->item('logo_thumb_size'));?>" class="img_thumb" />
					    					</a>	
					    				<?php endif;?>
									</div>
								<?php endif;?>
					        </div>
				          </div>
				        </div>
					</div>
			   	</div>
			   <?php endif;?>
		   	<?php endif;?>
	    	<?php if((in_array($status_id,array(2))) && !$venta_directa) : ?>
	    	<div class="panel-group front_panel" id="accordion8">
		  		<div class="panel panel-default">
				    <div class="panel-heading">
					  	<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion8" href="#collapse8">
							  T&eacute;rminos y condiciones
							</a>
						</h4>
				    </div>
				   	<div id="collapse8" class="panel-collapse collapse in">
				      	<div class="panel-body">
				        	<div class="row">
								<div class="col-lg-4 col-lg-offset-4 form-group text-center">
									<?php
										$terminos_path = 'files/terminos/terminos_venta_directa.pdf';
										if(!$venta_directa)
											$terminos_path = 'files/terminos/terminos_distribuidor.pdf';
									?>
									<a href=<?php echo site_url($terminos_path);?>>
										<img src="<?php echo site_url('/thumbs/timthumb.php?src='.site_url("img/bc/icono_pdf.jpg").'&s='.$this->config->item('logo_thumb_size'));?>" class="img_thumb" />
									</a>
								</div>
								<div class="clearfix"></div>
								<div class="col-lg-3 form-group">
									<div class="checkbox">
										<input name="acepta_terminos" value="0" type="hidden" />
										<label>
											<input type="checkbox" name="acepta_terminos" value="1" <?php echo set_checkbox('acepta_terminos','1',(@$r->acepta_terminos)?TRUE:FALSE); ?> class=" <?php echo form_error('observaciones')?'rojo':'';?>"/>
											Acepto los t&eacute;rminos y condiciones
										</label>
										<?php echo form_error('acepta_terminos');?>
									</div>
								</div>
							</div>
						</div>
					</div>
				  </div>
			</div>
	    	<?php else : ?>
	        <input name="acepta_terminos" value="1" type="hidden" />
	    	<?php endif; ?>
		<?php if(!empty($productos_sesion) || !empty($accesorios_sesion)):?>
			<div class="front_btnsWrapper mt20 mb20">
				<a class="btn btn-front-default pull-right" href="<?php echo site_url('cotizaciones/index'); ?>">Cancelar</a>
				<button class="btn btn-front-primary pull-right" type="submit" id="generar_cotizacion">
				  <?php if($status_id == 1) : ?>
		            Generar orden compra
		          <?php else: ?>
		            Guardar
		          <?php endif ?>
				</button>
				<?php if($status_id == 2): ?>
				<button class="btn front-btn-procesar pull-right procesar" type="submit">
		            Procesar orden
				</button>
				<?php endif; ?>
				<?php if($status_id==3):?>
				<a class="btn front-btn-rechazar pull-right" href="<?php echo site_url('cotizaciones/rechazar/'.$cotizaciones_id); ?>">Rechazar</a>
				<button class="btn front-btn-autorizar pull-right autorizar" type="submit">Autorizar</button>
				<?php endif; ?>
				<div class="clear"></div>
			</div>
		<?php endif;?>
	</form>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>js/bc/direccion.js"></script>
<script src="<?php echo base_url(); ?>js/alertify/alertify.min.js"></script>
<script type="text/javascript">
$(function(){

	function reset () {
		$("#toggleCSS").attr("href", "<?php echo base_url(); ?>js/alertify/themes/alertify.default.css");
		alertify.set({
			labels : {
				ok     : "Aceptar",
				cancel : "Rechazar"
			},
			delay : 5000,
			buttonReverse : false,
			buttonFocus   : "ok"
		});
	}

	function condiciones_comerciales()
	{
		reset();
		alertify.set({ labels: { ok: 'Cerrar'}});
		alertify.alert('Su orden de compra ha sido actualizada de acuerdo a las condiciones comerciales vigentes.');
	}

	$('.container').on('click','#promocion_msi_banamex', function(){$('#promocion_msi_amex').attr('checked',false);});
	$('.container').on('click','#promocion_msi_amex', function(){$('#promocion_msi_banamex').attr('checked',false);});

	$('.container').on('change','.evento_estado,.envio,.recalcular,#promociones_id',function(){

		$.blockUI({message: '<h1><img src="<?php echo site_url('img/recalculando.gif'); ?>" /></h1>'});

		var productos_ids= new Array();
		var accesorios_ids= new Array();
        var acc_individuales_ids= new Array();
		var productos_cantidades=new Array();
		var accesorios_cantidades=new Array();
        var acc_individuales_cantidades=new Array();
		var entrega_estado=$('#entrega_estado').val();
		var instalacion_estado=$('#instalacion_estado').val();
		var descuento_opcional=$('#descuento_opcional').is(':checked')?1:0;
		var descuento_paquete=$('#descuento_paquete').is(':checked')?1:0;
        var cotizaciones_id = <?php echo @$cotizaciones_id?$cotizaciones_id:0;?>;
		var cupones_id=$('#cupones_id').val();
		var descuento_cupon=$('#cupon').is(':checked')?1:0;
		var folio_cupon=$('#folio_cupon').val();
		var opcion_cupon_id = 0;
		var promociones_id = $('#promociones_id').val();
		var promocion_msi=0;
		var rescate_sucursal=$('#rescate').is(':checked')?1:0;
		var evento_estado = $('#evento_estado').val();

		if($('#promocion_msi_amex').is(':checked'))
		{
			promocion_msi=12;
			$('#descuento_opcional').attr('checked',false);
			descuento_opcional=0;
			$('#descuento_opcional').attr('disabled');

			$('#descuento_paquete').removeAttr('checked');
			descuento_paquete=0;
			$('#descuento_paquete').attr('disabled');

			$('#cupon').removeAttr('checked');
			descuento_cupon=0;
			$('#cupon').attr('disabled');
		}
		if($('#promocion_msi_banamex').is(':checked'))
		{
			promocion_msi=18;
			$('#descuento_opcional').attr('checked',false);
			descuento_opcional=0;
			$('#descuento_opcional').attr('disabled');

			$('#descuento_paquete').removeAttr('checked');
			descuento_paquete=0;
			$('#descuento_paquete').attr('disabled');

			$('#cupon').removeAttr('checked');
			descuento_cupon=0;
			$('#cupon').attr('disabled');
		}

		$('.opciones_id').each(function(){
			if($(this).is(':checked'))
				opcion_cupon_id=$(this).val();
		});

		var status=0;
		<?php if(isset($status_id)):?>
		status=<?php echo $status_id;?>;
		<?php endif;?> 
		<?php foreach($productos_sesion as $k => $p):?>
			productos_ids.push('<?php echo $p->id;?>');
			<?php foreach($p->accesorios as $acc=>$a):?>
				accesorios_ids[<?php echo $p->id;?>]='<?php echo $a->id;?>';
			<?php endforeach;?>
		<?php endforeach;?>

        <?php foreach($accesorios_individuales as $ai):?>
            acc_individuales_ids.push('<?php echo $ai->accesorios_id;?>');
        <?php endforeach;?>

		$('.p_cantidad').each(function(){
			if($(this).is(':input'))
				productos_cantidades.push($(this).val());
			else
				productos_cantidades.push($(this).html());
		});
		
		$('.acc_cantidad').each(function(){
			if($(this).is(':input'))
				accesorios_cantidades.push($(this).val());
			else
				accesorios_cantidades.push($(this).html());
		});

        $('.acc_individual_cantidad').each(function(){
            if($(this).is(':input'))
                acc_individuales_cantidades.push($(this).val());
            else
                acc_individuales_cantidades.push($(this).html());
        });

		$('#accordion3').load(BASE_URL+'frontends/cotizacion_calculo/1',
				{ productos_ids : productos_ids, productos_cantidades : productos_cantidades,
				  accesorios_ids : accesorios_ids, accesorios_cantidades : accesorios_cantidades,
                  acc_individuales_ids : acc_individuales_ids, acc_individuales_cantidades : acc_individuales_cantidades,
				  entrega_estado: entrega_estado, descuento_opcional : descuento_opcional, status: status,
				  descuento_paquete : descuento_paquete, instalacion_estado : instalacion_estado,
                  cotizaciones_id : cotizaciones_id, cupones_id : cupones_id, descuento_cupon : descuento_cupon, rescate_sucursal : rescate_sucursal,
				  folio_cupon : folio_cupon, opcion_cupon_id : opcion_cupon_id, promociones_id : promociones_id, promocion_msi : promocion_msi, evento_estado : evento_estado
                },
				function(){
					$.unblockUI();
				//exito
		});
	});
	
    $('.procesar').on('click', function(e) {
        e.preventDefault();
        var form = $('#form');//$(this).closest('form');
        form.attr('action', '<?php echo current_url() . '/1'; ?>');
        form.submit();    
    });

    $('.autorizar').on('click', function(e) {
        e.preventDefault();
		var form = $(this).closest('form');

		reset();
		alertify.set({ labels: { ok: 'Aceptar', cancel: 'Rechazar' } });

		alertify.confirm('Su orden de compra ha sido actualizada de acuerdo a las condiciones comerciales vigentes. Si no está de acuerdo de click en RECHAZAR para revisar su orden de compra.', function(a) {
			if(a)
			{
				form.attr('action', '<?php echo site_url('frontends/enviar_compra/'.$cotizaciones_id.'/0/1');?>');
				form.submit();
			}
			else
			{
				form.attr('action', '<?php echo site_url('cotizaciones/rechazar/'.$cotizaciones_id);?>');
				form.submit();
			}
		});
    });
    
	Direccion.set('');
	Direccion.set('entrega_');
	Direccion.set('instalacion_');

	//Copiar datos clientes a datos de envio
	$('#copiar_datos_cliente').click(function(e){/*Solicitante al inmueble*/
		e.preventDefault();
		if(confirm('¿Seguro que desea copiar los datos del cliente?'))
		{
			var nombre_apellidos = $('#nombre_comprador').val()+' '+$('#paterno_comprador').val()+' '+$('#materno_comprador').val();
			$('#nombre_contacto').val(nombre_apellidos);
			$('#telefono_particular').val($('#telefono_comprador').val());
			$('#cambio').val('1');
		}
		return false;
	});
	//Cipiar informacion de envio a facturacion
	$('#datos_envio').click(function(e){/*Solicitante al inmueble*/
		e.preventDefault();
		if(confirm('¿Seguro qué desea copiar la dirección de envío a los datos de Facturación?'))
        {
            $('#razon_social').val($('#nombre_contacto').val());
            $('#telefono').val($('#telefono_particular').val());
            $('#estado').val($('#entrega_estado').val());
            Direccion.get_municipios('',$('#entrega_municipio').val());
            $('#asentamiento').val($('#entrega_asentamiento').val());
            $('#codigo_postal').val($('#entrega_codigo_postal').val());
            $('#calle').val($('#entrega_calle').val());
            $('#numero_exterior').val($('#entrega_numero_exterior').val());
            $('#numero_interior').val($('#entrega_numero_interior').val());
            $('#cambio').val('1');
            $('#rfc').val('');
            $('#email').val('');
        }
		return false;
	});

	$('#datos_instalacion').click(function(e){
		e.preventDefault();
		if(confirm('¿Seguro qué desea copiar la dirección de envío a los datos de Instalación?'))
		{
			$('#instalacion_nombre_contacto').val($('#nombre_contacto').val());
			$('#instalacion_telefono_particular').val($('#telefono_particular').val());
			$('#instalacion_telefono_celular').val($('#telefono_celular').val());
			$('#instalacion_estado').val($('#entrega_estado').val());
			Direccion.get_municipios('instalacion_',$('#entrega_municipio').val());
			$('#instalacion_asentamiento').val($('#entrega_asentamiento').val());
			$('#instalacion_codigo_postal').val($('#entrega_codigo_postal').val());
			$('#instalacion_calle').val($('#entrega_calle').val());
			$('#instalacion_numero_exterior').val($('#entrega_numero_exterior').val());
			$('#instalacion_numero_interior').val($('#entrega_numero_interior').val());
			$('#cambio').val('1');
			//$('.envio').change();
			$('#instalacion_dir_search').trigger('click');
		}
		return false;
	});		

	$('.container').on('change','#referido_buscar_id',function(){
		var id=$(this).val();
		if(id!='')
		{
			var ruta='<?php echo site_url('referidos/referidos_buscar')?>/'+id;
			$.fancybox(
			{
				autoSize 	: false,
				autoHeight 	: true,
				width       : '80%',
				openEffect	: 'none',
				closeEffect	: 'none',
				href		: ruta,
				type		: 'iframe'
			});
		}
	});
	convertir_campos('+3D','+6M');

	$('#form').on("keyup keypress click", "#folio_cupon, .opciones_id, #cupon, #rescate, #promocion_msi_amex, #descuento_paquete, #descuento_opcional", function(e) {
		 //var code = e.keyCode || e.which;
		 //if (code  == 13) {
		 e.preventDefault();
		 return false;
		 //}
	 });

	<?php if(in_array($status_id,array(2,3))):?>
		$('.recalcular').trigger('change');
	<?php endif;?>

	condiciones_comerciales();

	var promociones_id = <?php echo @$r['promociones_id']?@$r['promociones_id']:0;?>;
	if(promociones_id)
		$('#promociones_aplicadas').show();

	$('#accordion3').on('click', '.promocion_eliminar', function(e){
		e.preventDefault();
		$('#promocion').val(0);
		$('#promociones_aplicadas').hide();
		$('#promocion').change();
	});
});
</script>
<?php $this->load->view('frontend/layout/footer');?>