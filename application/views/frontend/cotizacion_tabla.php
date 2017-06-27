<div class="panel panel-default" id="productos_div">
    <div class="panel-heading">
		<h4 class="panel-title">
			<a data-toggle="collapse" data-parent="#accordion3" href="#collapseThree">Productos</a>
		</h4>
    </div>
    <div id="collapseThree" class="panel-collapse collapse in">
      	<div class="panel-body">
			<?php if(!empty($promociones) && (@!$status_id || (@$status_id && in_array($status_id,array(2,3,4,5)) && @$promociones_id))):?>
			<div class="panel-group front_panel" id="promociones">
				<div class="panel panel-default" id="productos_div">
					<div id="collapsePromociones" class="panel-collapse collapse in">
						<a class="btn-front-primary bc_fancybox" href="<?php echo site_url('frontends/promocion_view'); ?>">
							<img src="<?php echo @$this->session->userdata('promocion_opcional')?site_url('img/promociones/promocion_descuento.png'):site_url('img/promociones/promocion_disponible.png');?>" style="width: 100%"/>
						</a>
					</div>
				</div>
			</div>
			<?php endif;?>
			<div class="table-responsive front_table mt20" style="overflow-y: auto">
				<table class="table table-hover cotizacion">
					<?php if(empty($productos_sesion) && empty($accesorios_individuales)):?>
						<div class="msg sin_resultados"><?php echo 'No existen art&iacute;culos en la cotización.';?></div>
					<?php else:?>
					<thead>
						<tr>
							<td>Producto</td>
							<td>Modelo</td>
							<td>Nombre</td>
							<td>Cantidad</td>
							<td>Precio unitario (SIN IVA)</td>
							<?php if(INTERNO):?>
							<td>Descuento Cliente</td>
							<?php endif;?>
							<?php if(isset($status_id) && in_array($status_id,array(2,3,4,5)) && INTERNO && !$venta_directa):?>
							<td>Descuento Distribuidor</td>
							<?php endif;?>
							<td>Importe Cliente</td>
							<?php if(isset($status_id) && in_array($status_id,array(2,3,4,5)) && INTERNO && !$venta_directa):?>
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
							<td class="text-center <?php echo @$status_posterior?'p_cantidad':'';?>">
								<?php if(!@$status_posterior && $p->unidad_id!=2):?>
						       		<input value="<?php echo set_value('cantidad_productos[]', $carrito[$p->id]['cantidad']) ?>" name="cantidad_productos[<?php echo $p->id; ?>]" class="form-control text-center p_cantidad"/> <?php echo $p->unidad; ?>
									<?php echo form_error('cantidad_productos['.$p->id.']'); ?>
								<?php elseif(!@$status_posterior && $p->unidad_id==2): ?>
									<select name="cantidad_productos[<?php echo $p->id; ?>]" class="form-control p_cantidad select_text_center">
										<?php for($h=$carrito[$p->id]['horas_minimo']; $h<=$carrito[$p->id]['horas_maximo']; $h++): ?>
											<option value="<?php echo $h; ?>" <?php echo ($h==$carrito[$p->id]['cantidad'])?'selected':''; ?>><?php echo $h; ?></option>
										<?php endfor;?>
									</select> <?php echo $p->unidad; ?>
								<?php else:
								    echo $carrito[$p->id]['cantidad'].' '.$p->unidad;
								endif;?>
							</td>
							<td class="text-center">
								<?php echo moneda($carrito[$p->id]['precio']); ?>
							</td>
							<?php if(INTERNO):?>
							<td class="text-center">
								<?php
									if(isset($calculo['descuentos_cliente'][$p->id]['cliente']))
										echo ver_num($calculo['descuentos_cliente'][$p->id]['cliente']).' %';
									else
										echo ver_num(0).' %';
								?>
							</td>
							<?php endif;?>
							<?php if(isset($status_id) && in_array($status_id,array(2,3,4,5)) && INTERNO && !$venta_directa):?>
								<td class="text-center">
									<?php
										if(@$cotizaciones_id && $status_id!=2 && $status_id!=3)
										{
											if($p->descuento_distribuidor)
												echo ver_num($p->descuento_distribuidor*100).' %';
        									else
        										echo ver_num(0).' %';
										}
										else
										{
											if(isset($calculo['descuentos_distribuidor'][$p->id]['total']))
												echo ver_num($calculo['descuentos_distribuidor'][$p->id]['total']).' %';
											else
												echo ver_num(0).' %';
										}
									?>
								</td>
							<?php endif;?>
							<td class="text-center">
		    					<span class="bc_precio_sub">
		    						<?php
										if(isset($calculo['importe_cliente'][$p->id]))
											echo moneda($calculo['importe_cliente'][$p->id]);
										else
											echo '$ 0.00';
									?>
		    					</span>
							</td>
							<?php if(isset($status_id) && in_array($status_id,array(2,3,4,5)) && INTERNO && !$venta_directa):?>
							<td class="text-center">
		    					<span class="bc_precio_sub">
		    						<?php
										if(@$cotizaciones_id && $status_id!=2 && $status_id!=3)
										{
											if($p->importe_distribuidor)
												echo moneda($p->importe_distribuidor);
        									else
        										echo '';
										}
										else
										{
											if(isset($calculo['importe_distribuidor'][$p->id]))
												echo moneda($calculo['importe_distribuidor'][$p->id]);
											else
												echo '';
										}
									?>
		    					</span>
							</td>
							<?php endif;?>
							<?php if(isset($status_id) && @!$status_id || ( (INTERNO && @$status_id==1) || (!INTERNO && @in_array($status_id,array(1,2))))):?>
							<td class="text-center">
						       	<a class="accion accion3" href="<?php echo site_url('frontends/producto_eliminar/'.$p->id.'/'.@$cot_id); ?>">Eliminar</a>
								<?php if($p->promocion_opcional):?>
									<a class="accion accion2 bc_fancybox" href="<?php echo site_url('frontends/promocion_opcional_elegir/'.$p->id.'/'.@$p->categorias_id.'/'.@$cotizaciones_id); ?>">Aplicar Promoción</a>
								<?php endif;?>
							</td>
							<?php else:?>
							<td class="text-center">&nbsp;</td>
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
									<!-- <td><?php echo $acc->descripcion;?></td> -->
									<td class="text-center <?php echo @$status_posterior?'acc_cantidad':'';?>">
										<?php if(!@$status_posterior):?>
	            					   		<input value="<?php echo set_value('cantidad_accesorios['.$p->id.'][]', $acc->cantidad/*$carrito[$p->id]['accesorios'][$acc->id]['cantidad']*/); ?>" name="cantidad_accesorios[<?php echo $p->id; ?>][<?php echo $acc->id; ?>]" class="form-control text-center acc_cantidad"/> <?php echo $acc->unidad; ?>
	            					   	<?php else: echo $acc->cantidad;endif;?>
	            					</td>
									<td class="text-center"><?php echo moneda($acc->precio/*$carrito[$p->id]['accesorios'][$acc->id]['precio']*/);?></td>
									<?php if(INTERNO):?>
									<td class="text-center">
										<?php
											if(isset($calculo['descuentos_cliente'][$p->id]['accesorios'][$acc->id]['cliente'])):
												$descuento_cupon_acc_cliente = $acc->consumible && @$calculo['descuentos_cliente'][$p->id]['accesorios'][$acc->id]['cliente_cupon']?$calculo['descuentos_cliente'][$p->id]['accesorios'][$acc->id]['cliente_cupon']:0;
												echo ver_num($calculo['descuentos_cliente'][$p->id]['accesorios'][$acc->id]['cliente']+$descuento_cupon_acc_cliente).' %';
											else:
												echo ver_num(0).' %';
											endif;
										?>
									</td>
									<?php endif;?>
									<?php if(isset($status_id) && in_array($status_id,array(2,3,4,5)) && INTERNO && !$venta_directa):?>
										<td class="text-center">
											<?php
												if(@$cotizaciones_id && $status_id!=2 && $status_id!=3)
												{
													if($acc->descuento_distribuidor && !$acc->consumible)
														echo ver_num($acc->descuento_distribuidor*100).' %';
		        									else
		        										echo ver_num(0).' %';
												}
												else
												{
													if(isset($calculo['descuentos_distribuidor'][$p->id]['total']) && !$acc->consumible)
														echo ver_num($calculo['descuentos_distribuidor'][$p->id]['total']).' %';
													else
														echo ver_num(0).' %';
												}
											?>
										</td>
									<?php endif;?>
									<td class="text-center">
				    					<span class="bc_precio_sub">
					    					<?php
												/*if(@$cotizaciones_id)
												{
													if($acc->importe_cliente)
														echo moneda($acc->importe_cliente);
		        									else
		        										echo '';
												}
												else
												{*/
													if(isset($calculo['importe_cliente_acc'][$acc->id]))
														echo moneda($calculo['importe_cliente_acc'][$acc->id]);
													else
														echo '';
												//}
											?>
				    					</span>
									</td>
									<?php if(isset($status_id) && in_array($status_id,array(2,3,4,5)) && INTERNO && !$venta_directa):?>
									<td class="text-center">
				    					<span class="bc_precio_sub">
				    						<?php
												if(@$cotizaciones_id && $status_id!=2 && $status_id!=3)
												{
													if($acc->importe_distribuidor)
														echo moneda($acc->importe_distribuidor);
		        									else
		        										echo '';
												}
												else
												{
													if(isset($calculo['importe_distribuidor_acc'][$acc->id]))
														echo moneda($calculo['importe_distribuidor_acc'][$acc->id]);
													else
														echo '';
												}
											?>
				    					</span>
									</td>
									<?php endif;?>
									<?php if(!$acc->obligatorio && isset($status_id) && (@!$status_id || ( (INTERNO && @$status_id==1) || (!INTERNO && @in_array($status_id,array(1,2)))))):?>
										<?php $accesorio_consumible = (@$acc->consumible)?'Consumible':'Accesorio';?>
										<td class="text-center">
											<a class="accion accion3" href="<?php echo site_url('frontends/producto_eliminar/0/'.@$cot_id.'/'.$acc->id); ?>">Eliminar <?php echo $accesorio_consumible;?></a>
										</td>
									<?php else:?>
										<td class="text-center">&nbsp;</td>
									<?php endif;?>
								</tr>
							<?php endforeach;?>
						<?php endif;?>
						<?php $i++;?>
						<?php endforeach;?>

                        <?php if($accesorios_individuales):
                            foreach($accesorios_individuales as $ai):
                                $accesorio_id=@$cotizaciones_id?$ai->accesorios_id:$ai->id; ?>
                                <tr>
                                    <td class="text-center">
                                        <?php $orden = $ai->imagen_orden?'_'.$ai->imagen_orden:'';?>
                                        <?php $path_acc=$this->config->item('cloudfiles')?$this->cloud_files->url_publica("files/accesorios/{$accesorio_id}{$orden}.jpg"):"files/accesorios/{$accesorio_id}.jpg";?>
                                        <a class='imagen_fancybox' href="<?php echo site_url('/thumbs/timthumb.php?src='.$path_acc.'&zc=0&q=85&s=500'.'&t='.time());?>">
                                            <img class="img-thumbnail front_imgTabla" src="<?php echo site_url('/thumbs/timthumb.php?src='.$path_acc.'&s=400&t='.time());?>" />
                                        </a>
                                    </td>
                                    <td class="nowrap text-center"><?php echo $ai->modelo;?></td>
                                    <td><?php echo $ai->nombre;?></td>
                                    <td class="text-center <?php echo @$status_posterior?'acc_individual_cantidad':'';?>"><?php if(!@$status_posterior):?>
                                            <input value="<?php echo set_value('cantidad_accesorios_individuales['.$accesorio_id.'][]', $ai->cantidad); ?>" name="cantidad_accesorios_individuales[<?php echo $accesorio_id; ?>]" class="form-control text-center acc_individual_cantidad"/> <?php echo $ai->unidad; ?>
                                        <?php else: echo $ai->cantidad.' '.$ai->unidad;endif;?></td>
                                    <td class="text-center"><?php echo moneda($ai->precio);?></td>
                                    <?php if(INTERNO):?>
                                        <td class="text-center">
                                            <?php  echo @$calculo['descuento_cliente_acc_individual'][$accesorio_id]?ver_num($calculo['descuento_cliente_acc_individual'][$accesorio_id]).' %':ver_num(0).' %'; ?>
                                        </td>
                                    <?php endif;?>
                                    <?php if(isset($status_id) && in_array($status_id,array(2,3,4,5)) && INTERNO && !$venta_directa):?>
                                        <td class="text-center">
											<?php  echo @$calculo['descuento_distribuidor_acc_individual'][$accesorio_id]?ver_num($calculo['descuento_distribuidor_acc_individual'][$accesorio_id]).' %':ver_num(0).' %'; ?>
                                        </td>
                                    <?php endif;?>
                                    <td class="text-center">
				    					<span class="bc_precio_sub">
					    					<?php
                                            if(isset($calculo['importe_cliente_acc_individual'][$accesorio_id]))
                                                echo moneda($calculo['importe_cliente_acc_individual'][$accesorio_id]);
                                            else
                                                echo '';
                                            ?>
				    					</span>
                                    </td>
                                    <?php if(isset($status_id) && in_array($status_id,array(2,3,4,5)) && INTERNO && !$venta_directa):?>
                                        <td class="text-center">
				    					<span class="bc_precio_sub">
				    						<?php
                                            if(@$cotizaciones_id)
                                            {
                                                if($ai->importe_distribuidor)
                                                    echo moneda($ai->importe_distribuidor);
                                                else
                                                    echo '';
                                            }
                                            else
                                            {
                                                if(isset($calculo['importe_distribuidor_acc_individual'][$accesorio_id]))
                                                    echo moneda($calculo['importe_distribuidor_acc_individual'][$accesorio_id]);
                                                else
                                                    echo '';
                                            }
                                            ?>
				    					</span>
                                        </td>
                                    <?php endif;?>
									<?php if(!$ai->obligatorio && isset($status_id) && (@!$status_id || ( (INTERNO && @$status_id==1) || (!INTERNO && @in_array($status_id,array(1,2)))))):?>
										<?php $accesorio_consumible = (@$ai->consumible)?'Consumible':'Accesorio';?>
										<td class="text-center">
											<a class="accion accion3" href="<?php echo site_url('frontends/accesorio_individual_eliminar/'.@$cot_id.'/'.$accesorio_id); ?>">Eliminar <?php echo $accesorio_consumible;?></a>
										</td>
									<?php else:?>
										<td class="text-center">&nbsp;</td>
									<?php endif;?>
                                </tr>
                        <?php endforeach;
					endif;?>
					<input type="hidden" id="promociones_id" name="promociones_id" value="<?php echo @$promociones_id?$promociones_id:@$r['promociones_id'];?>"/>
					<?php if($promociones):?>
						<?php if($promociones_productos):
							foreach($promociones_productos as $k => $p):?>
							<tr>
								<td class="text-center" style="background-color: #f0f0f0">
									<?php $path=$p['path'];?>
									<div style="position: relative;">
									<a class='imagen_fancybox' href="<?php echo site_url('/thumbs/timthumb.php?src='.$path.'&zc=0&q=85&s=500'.'&t='.time());?>">
										<img class="img-thumbnail front_imgTabla" src="<?php echo site_url('/thumbs/timthumb.php?src='.$path.'&s=400&t='.time());?>" />
										<?php $path_regalo=@$p['descuento']?site_url('img/promociones/promocion.png'):site_url('img/promociones/regalo.png');?>
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
										<span class="bc_precio_sub"><?php echo @$p['importe']?moneda($p['importe']):moneda(0);?></span>
									</td>
								<?php endif;?>
								<?php if(@$status_id && ((INTERNO && @$status_id!=1) || (!INTERNO && !in_array($status_id,array(1,2))))):?>
									<td class="text-center">&nbsp;</td>
								<?php elseif(count($promociones)==1 && !@$calculo['promocion_opcional_descuento']):?>
									<td class="text-center">&nbsp;</td>
								<?php elseif(@$calculo['promocion_opcional_descuento']):?>
									<td class="text-center">&nbsp;</td>
								<?php else:?>
									<td class="text-center" colspan="<?php echo count($promociones);?>">
										<a class="accion accion3 bc_fancybox" href="<?php echo site_url('frontends/promocion_elegir/'.@$cotizaciones_id);?>">Cambiar Promoción</a>
									</td>
								<?php endif;?>
							</tr>

							<?php endforeach;?>
						<?php endif;
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
							<?php $colspan_titulo = (isset($status_id) && in_array($status_id,array(2,3,4,5)) && INTERNO)?($venta_directa?'6':'7'):(INTERNO && (!@$status_id || @$status_id==1)?'6':'5');?>
							<?php $colspan_valores = (isset($status_id) && in_array($status_id,array(2,3,4,5)) && INTERNO)?($venta_directa?'':'colspan="2"'):'';?>
		    				<td class="tr front_datoImportante nowrap" colspan="<?php echo $colspan_titulo;?>">Importe Total:</td>
		    				<td class="front_datoImportante nowrap bc_total"><?php echo moneda(@$calculo['importe_venta']); ?></td>
							<?php if(isset($status_id) && in_array($status_id,array(2,3,4,5)) && !$venta_directa):?>
								<td class="front_datoImportante nowrap bc_total"><?php echo moneda(@$calculo['importe_venta']);?></td>
							<?php endif;?>
							<td <?php echo $colspan_valores;?>>&nbsp;</td>
		    			</tr>
		    			<tr>
		    				<td class="tr front_datoImportante nowrap" colspan="<?php echo $colspan_titulo;?>">Descuento:</td>
		    				<td class="front_datoImportante nowrap bc_total">
		    					<?php $descuento_comercial = (@$calculo['importe_venta'] > (@$calculo['subtotal_cliente']-@$calculo['envio']-@$calculo['promocion_descuento_porcentaje_monto']-@$calculo['promocion_descuento_fijo']-@$calculo['promocion_opcional_descuento']))?@$calculo['importe_venta'] - (@$calculo['subtotal_cliente']-@$calculo['envio']) - @$calculo['descuento_cliente_cupon']-@$calculo['promocion_descuento_porcentaje_monto']-@$calculo['promocion_descuento_fijo']-@$calculo['promocion_opcional_descuento']:0;
								 	  $descuento_comercial = $descuento_comercial < 0?0:$descuento_comercial;
									  $descuento_comercial = @$calculo['descuento_cliente_cupon'] && $descuento_comercial>@$calculo['descuento_cliente_cupon']?$descuento_comercial-@$calculo['descuento_cliente_cupon']:$descuento_comercial;
									  echo moneda($descuento_comercial);?>
		    				</td>
							<?php if(isset($status_id) && in_array($status_id,array(2,3,4,5)) && !$venta_directa):?>
							<td class="front_datoImportante nowrap bc_total">
								<?php $descuento_comercial_distribuidor = (@$calculo['importe_venta'] > (@$calculo['subtotal_distribuidor']-@$calculo['envio']-@$calculo['promocion_descuento_porcentaje_monto_distribuidor']-@$calculo['promocion_descuento_fijo']))?@$calculo['importe_venta'] - @$calculo['subtotal_distribuidor']-@$calculo['promocion_descuento_porcentaje_monto_distribuidor']-@$calculo['promocion_descuento_fijo']-@$calculo['promocion_opcional_descuento']+@$calculo['envio']:0;
									  $descuento_comercial_distribuidor = @$calculo['descuento_distribuidor_cupon'] && $descuento_comercial_distribuidor>@$calculo['descuento_distribuidor_cupon']?$descuento_comercial_distribuidor-@$calculo['descuento_distribuidor_cupon']:$descuento_comercial_distribuidor;?>
								<?php echo moneda($descuento_comercial_distribuidor);?>
							</td>
							<?php endif;?>
		    				<td <?php $colspan_valores;?>>&nbsp;</td>
		    			</tr>
						<?php if(@$calculo['promocion_descuento_porcentaje']):?>
							<tr>
								<td class="tr front_datoImportante nowrap" colspan="<?php echo $colspan_titulo;?>">Descuento de <b><?php echo @ver_num($calculo['promocion_descuento_porcentaje']).'%';?></b> por promoci&oacute;n</td>
								<td class="front_datoImportante nowrap"><?php echo moneda(@$calculo['promocion_descuento_porcentaje_monto'])?></td>
								<?php if(isset($status_id) && in_array($status_id,array(2,3,4,5)) && !$venta_directa):?>
									<td class="front_datoImportante nowrap"><?php echo moneda(@$calculo['promocion_descuento_porcentaje_monto_distribuidor'])?></td>
								<?php endif;?>
								<td>&nbsp;</td>
							</tr>
						<?php endif;?>
						<?php if(@$calculo['promocion_descuento_fijo']):?>
							<tr>
								<td class="tr front_datoImportante nowrap" colspan="<?php echo $colspan_titulo;?>">Descuento fijo por promoci&oacute;n</td>
								<td class="front_datoImportante nowrap"><?php echo moneda(@$calculo['promocion_descuento_fijo'])?></td>
								<?php if(isset($status_id) && in_array($status_id,array(2,3,4,5)) && !$venta_directa):?>
									<td class="front_datoImportante nowrap"><?php echo moneda(@$calculo['promocion_descuento_fijo'])?></td>
								<?php endif;?>
								<td>&nbsp;</td>

							</tr>
						<?php endif;?>
						<?php if(@$calculo['promocion_opcional_descuento']):?>
							<tr>
								<td class="tr front_datoImportante nowrap" colspan="<?php echo $colspan_titulo;?>">Descuento por promoci&oacute;n</td>
								<td class="front_datoImportante nowrap"><?php echo moneda(@$calculo['promocion_opcional_descuento'])?></td>
								<?php if(isset($status_id) && in_array($status_id,array(2,3,4,5)) && !$venta_directa):?>
									<td class="front_datoImportante nowrap"><?php echo moneda(@$calculo['promocion_opcional_descuento'])?></td>
								<?php endif;?>
								<td>&nbsp;</td>
							</tr>
						<?php endif;?>
						<?php if(isset($calculo['folio_cupon']) || @$r['descuento_cupon'] || @$r['descuento_valor_cupon']):?>
						<tr>
		    				<td class="tr front_datoImportante nowrap" colspan="<?php echo $colspan_titulo;?>">Descuento por Cup&oacute;n:</td>
		    				<td class="front_datoImportante nowrap bc_total">
		    					<?php echo @$calculo['descuento_cliente_cupon']?moneda(@$calculo['descuento_cliente_cupon']):moneda(@$calculo['descuento_valor_cupon']);?>
		    				</td>
							<?php if(isset($status_id) && in_array($status_id,array(2,3,4,5)) && !$venta_directa):?>
								<td class="front_datoImportante nowrap bc_total">
									<?php echo @$calculo['descuento_distribuidor_cupon']?moneda(@$calculo['descuento_distribuidor_cupon']):moneda(@$calculo['descuento_distribuidor_cupon']);?>
								</td>
							<?php endif;?>
							<td <?php echo $colspan_valores;?>>&nbsp;</td>
		    			</tr>
						<?php endif;?>
		    			<?php if(@$calculo['envio']):?>
						<tr>
		    				<td class="tr front_datoImportante nowrap" colspan="<?php echo $colspan_titulo;?>">Cargo de recuperaci&oacute;n (incluye env&iacute;o e instalaci&oacute;n):</td>
		    				<td class="front_datoImportante nowrap bc_total"><?php echo moneda(@$calculo['envio']);?></td>
							<?php if(isset($status_id) && in_array($status_id,array(2,3,4,5)) && !$venta_directa):?>
		    					<td class="front_datoImportante nowrap bc_total"><?php echo moneda(@$calculo['envio']);?></td>
							<?php endif;?>
							<td <?php echo $colspan_valores;?>>&nbsp;</td>
		    			</tr>
		    			<?php endif;?>
						<tr>
							<td class="tr front_datoImportante nowrap" colspan="<?php echo $colspan_titulo;?>">Subtotal:</td>
							<td class="front_datoImportante nowrap bc_total"><?php echo moneda(@$calculo['subtotal_cliente']);?></td>
							<?php if(isset($status_id) && in_array($status_id,array(2,3,4,5)) && !$venta_directa):?>
								<td class="front_datoImportante nowrap bc_total"><?php echo moneda(@$calculo['subtotal_distribuidor']); ?></td>
							<?php endif;?>
							<td <?php echo $colspan_valores;?>>&nbsp;</td>
						</tr>
		    			<tr>
		    				<td class="tr front_datoImportante nowrap" colspan="<?php echo $colspan_titulo;?>">IVA ( 16 % ):</td>
		    				<td class="front_datoImportante nowrap"><?php echo moneda(@$calculo['iva_cliente']);?></td>
							<?php if(isset($status_id) && in_array($status_id,array(2,3,4,5)) && !$venta_directa):?>
								<td class="front_datoImportante nowrap"><?php echo moneda(@$calculo['iva_distribuidor']);?></td>
							<?php endif;?>
							<td <?php echo $colspan_valores;?>>&nbsp;</td>
		    			</tr>
		    			<tr>
		    				<td class="tr front_datoImportante nowrap" colspan="<?php echo $colspan_titulo;?>">TOTAL:</td>
		    				<td class="front_datoImportante nowrap"><?php echo moneda(@$calculo['total_cliente']); ?></td>
							<?php if(isset($status_id) && in_array($status_id,array(2,3,4,5)) && !$venta_directa):?>
								<td class="front_datoImportante nowrap"><?php echo moneda(@$calculo['total_distribuidor']); ?></td>
							<?php endif;?>
							<td <?php echo $colspan_valores;?>>&nbsp;</td>
		    			</tr>
						<?php if(@$calculo['promocion_msi'] && INTERNO):?>
							<tr>
								<td class="tr front_datoImportante nowrap" colspan="<?php echo $colspan_titulo;?>">Su pago a <?php echo $calculo['promocion_msi'];?> meses sin intereses por promoci&oacute;n:</td>
								<td class="front_datoImportante nowrap"><?php echo moneda(@$calculo['promocion_msi_cliente'])?></td>
								<?php if(isset($status_id) && in_array($status_id,array(2,3,4,5)) && !$venta_directa):?>
									<td class="front_datoImportante nowrap">&nbsp;<?php //echo moneda(@$calculo['promocion_msi_distribuidor'])?></td>
								<?php endif;?>
								<td>&nbsp;</td>
							</tr>
						<?php endif;?>
						<?php if($aplicar_cupon && @$calculo['opcion_cupon_id']==2 && @$calculo['descuento_cupon']):?>
							<tr>
								<td class="tr front_datoImportante nowrap" colspan="<?php echo $colspan_titulo;?>">Su pago a 12 meses sin intereses por Cup&oacute;n:</td>
								<?php if(isset($status_id) && in_array($status_id,array(2,3,4,5)) && !$venta_directa):?>
									<td class="front_datoImportante nowrap"><?php echo moneda(@$calculo['mensualidad_cliente_cupon'])?></td>
									<td class="front_datoImportante nowrap"><?php echo moneda(@$calculo['mensualidad_distribuidor_cupon'])?></td>
									<td>&nbsp;</td>
								<?php else:?>
									<td class="front_datoImportante nowrap"><?php echo moneda(@$calculo['mensualidad_cliente_cupon'])?></td>
									<td <?php echo $colspan_valores;?>>&nbsp;</td>
								<?php endif;?>
							</tr>
						<?php endif;?>
						<tr><td class="tr front_datoImportante_negro nowrap" colspan="<?php echo (isset($status_id) && in_array($status_id,array(2,3,4,5)) && !$venta_directa)?'11':'10'?>">*Los precios de la cotizaci&oacute;n pudieran
								variar durante el proceso de pre-compra, se considerarán los precios finales en el momento de la autorización de la orden de compra.</td></tr>
						<tr><td class="tr front_datoImportante_negro nowrap" colspan="<?php echo (isset($status_id) && in_array($status_id,array(2,3,4,5)) && !$venta_directa)?'11':'10'?>">*Los precios est&aacute;n sujetos a cambios o modificaciones de acuerdo a la pol&iacute;tica comercial vigente.</td></tr>
						<?php if(@$promociones_alianzas):foreach($promociones_alianzas as $a):?>
							<tr><td class="tr front_datoImportante_negro nowrap" colspan="<?php echo (isset($status_id) && in_array($status_id,array(2,3,4,5)) && !$venta_directa)?'11':'10'?>">*Regalo adquirido por ALIANZA <?php echo $a['nombre'];?>.
								<?php if(@empty($recibo_pago_cdn)):?>Para adquirir su códgio de reclamación debe realizar el pago de su compra.<?php else:?>
								Para reclamar su regalo, deberá presentar el siguiente CÓDIGO: <?php echo $a['codigo'];?> directamente con el proveedor.<?php endif;?>
							</td></tr>
						<?php endforeach;endif;?>
					</tfoot>
					<?php endif;?>
				</table>
			</div>
			<div class="row">
				<?php if(INTERNO && ($productos_sesion || $accesorios_sesion)):?>
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 form-group">
						<div class="checkbox">
							<input name="descuento_opcional" value="0" type="hidden" />
							<label <?php echo @$status_posterior?'style="opacity:0.4;"':'';?>>
							<?php $descuento_opcional= isset($calculo['descuento_opcional'])?$calculo['descuento_opcional']:@$r['descuento_opcional'];?>
							<input type="checkbox" name="descuento_opcional" value="1" <?php echo $descuento_opcional == 1?'checked':'' ?> class="descuento_opcional" id="descuento_opcional" <?php echo @$status_posterior?'readonly="readonly" style="opacity:0.4;"':'';?>/>
							Descuento Opcional</label>
							<?php echo form_error('descuento_opcional');?>
						</div>
					</div>
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 form-group">
						<div class="checkbox">
							<input name="descuento_paquete" value="0" type="hidden" />
							<label <?php echo @$status_posterior?'style="opacity:0.4;"':'';?>>
							<?php $descuento_paquete= isset($calculo['descuento_paquete'])?$calculo['descuento_paquete']:@$r['descuento_paquete'];?>
							<input type="checkbox" name="descuento_paquete" value="1" <?php echo $descuento_paquete == 1?'checked':'' ?> class="descuento_paquete" id="descuento_paquete" <?php echo @$status_posterior?'readonly="readonly" style="opacity:0.4;"':'';?>/>
							Descuento Paquete</label>
							<?php echo form_error('descuento_paquete');?>
						</div>
					</div>
					<?php if($venta_directa && INTERNO):?>
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 form-group">
						<div class="checkbox">
							<input name="promocion_msi_amex" value="0" type="hidden" id="promocion_mensualidades"/>
							<label <?php echo @$status_posterior?'style="opacity:0.4;"':'';?>>
								<?php $promocion_msi_amex= isset($calculo['promocion_msi']) && $calculo['promocion_msi']==12?$calculo['promocion_msi']:(@$r['promocion_msi']==12?$r['promocion_msi']:0);?>
								<input type="checkbox" name="promocion_msi_amex" value="12" <?php echo $promocion_msi_amex?'checked':'' ?> class="promocion_msi" id="promocion_msi_amex" <?php echo @$status_posterior?'readonly="readonly" style="opacity:0.4;"':'';?>/>
								12 MSI American Express</label>
							<?php echo form_error('promocion_msi_amex');?>
						</div>
					</div>
					<?php endif;?>

					<?php if($venta_directa && INTERNO && @$banamex_msi_vigente):?>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 form-group">
							<div class="checkbox">
								<input name="promocion_msi_banamex" value="0" type="hidden" id="promocion_mensualidades_banamex"/>
								<label <?php echo @$status_posterior?'style="opacity:0.4;"':'';?>>
									<?php $promocion_msi_banamex= isset($calculo['promocion_msi']) && $calculo['promocion_msi']==18?$calculo['promocion_msi']:(@$r['promocion_msi']==18?$r['promocion_msi']:0);?>
									<input type="checkbox" name="promocion_msi_banamex" value="18" <?php echo $promocion_msi_banamex?'checked':'' ?> class="promocion_msi" id="promocion_msi_banamex" <?php echo @$status_posterior?'readonly="readonly" style="opacity:0.4;"':'';?>/>
									18 MSI Banamex</label>
								<?php echo form_error('promocion_msi_banamex');?>
							</div>
						</div>
					<?php endif;?>
				<?php endif;?>

				<?php if($venta_directa && INTERNO && ($this->session->userdata('generar_cotizacion') || @$status_id )):?>
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 form-group">
						<div class="checkbox">
							<input name="rescate_sucursal" value="0" type="hidden" id="rescate_sucursal"/>
							<label <?php echo @$status_posterior?'style="opacity:0.4;"':'';?>>
								<?php $rescate_sucursal = isset($calculo['rescate_sucursal'])?$calculo['rescate_sucursal']:@$r['rescate_sucursal'];?>
								<input type="checkbox" name="rescate_sucursal" value="1" <?php echo $rescate_sucursal?'checked':'' ?> class="promocion_msi form-control-1" id="rescate" <?php echo @$status_posterior?'readonly="readonly" style="opacity:0.4;"':'';?>/>
								Sin env&iacute;o (venta en tienda)</label>
							<?php echo form_error('rescate_sucursal');?>
						</div>
					</div>
				<?php endif;?>

				<?php if(($aplicar_cupon && $this->session->userdata('generar_cotizacion') && ($productos_sesion || $accesorios_sesion) || ($aplicar_cupon && @$status_id) || ($aplicar_cupon && !INTERNO))):?>
					<?php $folio_cupon= isset($calculo['folio_cupon'])?$calculo['folio_cupon']:@$r['folio_cupon'];?>
					<?php $opcion_cupon_id = isset($calculo['opcion_cupon_id'])?@$calculo['opcion_cupon_id']:@$r['opcion_cupon_id'];?>
				<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 form-group">
					<div class="checkbox">
						<input name="descuento_cupon" value="0" type="hidden" />
						<label <?php echo @$status_posterior?'style="opacity:0.4;"':'';?>>
							<?php $cupon= isset($calculo['descuento_cupon'])?$calculo['descuento_cupon']:@$r['descuento_cupon'];?>
							<input type="checkbox" name="descuento_cupon" value="1" <?php echo $cupon == 1?'checked':'' ?> id="cupon" <?php echo @$status_posterior?'readonly="readonly" style="opacity:0.4;"':'';?>
								<?php echo $folio_cupon && $opcion_cupon_id?'class="form-control-1" readonly':'';?>/>
							Aplicar Cup&oacute;n</label>
						<?php echo form_error('descuento_cupon');?>
					</div>
				</div>
				<?php endif;?>
				<input type="hidden" id="promocion_change" value="<?php echo @$promocion_change;?>"/>
	        </div>
		</div>
	</div>
</div>