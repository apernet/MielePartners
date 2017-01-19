<?php foreach($p as $ts=>$r):?>
	<tr>
		<td colspan="5">
			<div class="altrowP p5">
				<?php echo $r['producto']->nombre;?>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<div class="div_info w100p" >
				<input name="p[<?php echo $ts; ?>][productos_id]" type="hidden" value="<?php echo  $r['producto']->id;?>" class="seleccionado"/>	 
				<div class="fl p5">
					<?php if(!empty($r['producto']->img_id)):?>
						<a class='imagen_fancybox' href="<?php echo site_url('/thumbs/timthumb.php?src=files/productos/'.$r['producto']->id.'/'.$r['producto']->img_id.'.jpg&zc=0&q=85&w=500&h=350'.'&t='.time());?>">
							<img align="left" src="<?php echo site_url('/thumbs/timthumb.php?src=files/productos/'.$r['producto']->id.'/'.$r['producto']->img_id.'.jpg&s=90&t='.time());?>" class="img_thumb" />
						</a>
					<?php else:?>
						NO DISPONIBLE
					<?php endif;?>
					<span class="clearblock"></span>
				</div>
				<div class="fl p5">
					<b>Nombre:</b> <?php echo $r['producto']->nombre; ?><br/>
					<b>Modelo:</b> <?php echo $r['producto']->modelo; ?><br/>
					<b>SKU:</b> <?php echo $r['producto']->item; ?><br/>
					<b>Precio:</b><span class="item_total_p"> <?php echo moneda($r['producto']->precio); ?></span>
					<span class="clearblock"></span>
				</div>
			</div>	
		</td>	
		<td align="right">			
			<div class="item_unitario div_info"><?php echo moneda($r['producto']->precio); ?></div>	
		</td>
		<td align="center">
			<input name="p[<?php echo $ts; ?>][cantidad]"  value="<?php echo  (@$r['producto']->cantidad)?$r['producto']->cantidad:'1';?>" style="width: 20px;" class="item_el item_dato item_cantidad"/>
		</td>
		<td align="right">			
			<div class="item_precio div_info suma_precio"><?php echo moneda($r['producto']->precio); ?></div>	
		</td>
		
		<td nowrap="nowrap" align="center">
			<a onclick="return confirm('&iquest;Seguro que desea eliminar el producto?');" href="#" class="delete del_item">Eliminar</a>
		</td>
	</tr>
	<?php if(!empty($r['tipos_accesorios'])): ?>
		<?php foreach($r['tipos_accesorios'] as $a):?>
		<tr>
			<td colspan="5">
				<div class="altrow p5">
						<?php echo $a->nombre;?>
						<?php echo $a->obligatorio_id?'(Obligatorio)':'(Opcional)';?>
					</div>
			</td>
		</tr>
		<tr>
			<td>
				<div class="tipo_accesorio">
					
					<div class="actual div_info w100p">
				    	<input name="p[<?php echo $ts; ?>][tipo_accesorios_id][]" type="hidden" value="<?php echo  $a->tipos_accesorios_id;?>" />	
				    	<input name="p[<?php echo $ts; ?>][accesorio_seleccionado_id][]" type="hidden" value="<?php echo  @$a->seleccionado->id;?>" class="seleccionado"/>	    	
				    	<div class="actual_info">		
				    		<?php if(!empty($a->seleccionado)):?>
				    		<?php $b=$a->seleccionado; ?>
								<div id="accesorio_<?php echo $b->id;?>" class="accesorio div_info2 w100p" style="cursor: pointer;" >			
									<div class="fl p5">				
										<?php if(file_exists(dirname(FCPATH).'/html/files/accesorios/'.$b->id.'.jpg')):?>
										<a class='imagen_fancybox' href="<?php echo site_url('frontends/accesorios/'.$b->id.'.jpg&zc=0&q=85&w=500&h=350'.'&t='.time());?>">
										<img align="left" src="<?php echo site_url('/thumbs/timthumb.php?src=files/accesorios/'.$b->id.'.jpg&s=90&t='.time());?>" class="img_thumb" />
										</a>
										<?php else:?>
											 NO DISPONIBLE  
										<?php endif;?>
										<span class="clearblock"></span>
									</div>
									<div class="fl p5 item_detalle">
										<b>Nombre:</b> <?php echo $b->nombre; ?><br/>
										<b>Modelo:</b> <?php echo $b->modelo; ?><br/>
										<b>SKU:</b> <?php echo $b->item; ?><br/>
										<b>Precio:</b><span class="item_total_p "> <?php echo moneda($b->precio); ?></span><br/>
										<span class="clearblock"></span>	
									</div>
						    	</div>
						    <?php else: ?>
						    	Elija un accesorio	
							<?php endif;?>
							<span class="clearblock"></span>
				        </div>
				        <div style="clear:both;">
				        	<a href="#" class="buscar fr">Seleccionar accesorio &raquo;</a>
				        	<span class="clearblock"></span>
				        </div>
				    </div>
			
				    <div class="lista" style="display: none; ">		    	
						<?php foreach($a->accesorios as $ab): ?>
						<div id="accesorio_<?php echo $ab->id;?>" class="accesorio div_info2 w100p" style="cursor: pointer;" >			
							<div class="fl p5">				
								<?php if(file_exists(dirname(FCPATH).'/html/files/accesorios/'.$ab->id.'.jpg')):?>
								<a class='imagen_fancybox' href="<?php echo site_url('frontends/accesorios/'.$ab->id.'.jpg&zc=0&q=85&w=500&h=350'.'&t='.time());?>">
									<img align="left" src="<?php echo site_url('/thumbs/timthumb.php?src=files/accesorios/'.$ab->id.'.jpg&s=90&t='.time());?>" class="img_thumb" />
								</a>
								<?php else:?>
									 NO DISPONIBLE  
								<?php endif;?>
								<span class="clearblock"></span>
							</div>
							<div class="fl p5 item_detalle">
								<b>Nombre:</b> <?php echo $ab->nombre; ?><br/>
								<b>Modelo:</b> <?php echo $ab->modelo; ?><br/>
								<b>SKU:</b> <?php echo $ab->item; ?><br/>
								<b>Precio:</b><span class="item_total_p "> <?php echo moneda($ab->precio); ?></span><br/>
								<span class="clearblock"></span>	
							</div>
							<span class="clearblock"></span>
				    	</div>
				    	<hr class="hr_line w90p"/>
				      <?php endforeach; ?>			        
					</div> 
		  		</div>
			</td>
			<td align="right">			
				<div class="item_unitario div_info"><?php echo (!empty($b->precio))?moneda($b->precio):'$ 0'; ?></div>		
			</td>
			<td align="center">
				<input name="p[<?php echo $ts; ?>][accesorio_cantidad][]"  value="<?php echo (@$a->cantidad)?$a->cantidad:1?>" style="width: 20px;" class="item_el item_dato item_cantidad"/>
			</td>
			<td align="right">			
				<div class="item_precio suma_precio div_info">$ 0</div>	
			</td>
			<td>&nbsp;</td>
		 </tr>		 	
	<?php endforeach; ?>
<?php endif; ?>
<?php endforeach; ?>
<script type="text/javascript">
<!--
$(function(){
	$('.buscar').click(function(){
		$(this).parent('div').parent('div').next('div.lista').show();
		// OBTENER LA POSICION PARA COLOCARLA EN LA LISTA
	});
	$('.accesorio').click(function(){
		var id= this.id.split('_');
		$(this).parent('div').prev('div').children('.seleccionado').val(id[1]);
		$('.lista').hide();
		var elemento = $(this).html();
		$(this).parent('div').prev('div').children('.actual_info').html(elemento);
		var unitario = v_num($(this).children('div.item_detalle').children('span.item_total_p').html());
		// PONE PRECIO UNITARIO EN LA TABLA
		$(this).parent('div').parent('div').parent('td').next('td').children('div.item_unitario').html(moneda(unitario));
		$('.item_cantidad').change();
	});
});
//-->
</script>	
