<?php $this->load->view('frontend/layout/header');?>
<div class="row">	
	<div class="col-lg-12">
		<form action="<?php echo site_url(uri_string()); ?>" id="from_accesorios" method="post">
		<input type="hidden" name="accesorio" value="1" />
		<div class="row">
			<div style="background-image:url('<?php echo site_url('/thumbs/timthumb.php?src=files/paquetes/'.$paquetes_id.'.jpg&s=400&t='.time());?>'); " class="front-banner-categorias">
				<div class="front_tituloBannerCategorias mt20 mb10">
					<p>
						<?php echo $titulo;?>
					</p>
				</div>
			</div>
		</div>
		<?php foreach($productos as $p): ?>
    		<div class="row">
    			<div class="col-lg-12 col-md-6 col-sm-12 mb20">
    				<div class="front_subtituloSeccion mt20">
    					<p>Accesorios <?php echo $p->nombre;?></p>
    				</div>
    			</div>
    		</div>
    		<div class="row">
    			<?php $i=0;foreach($p->accesorios as $a=>$v):?>
    			<div class="col-lg-3">
    				<div class="front-producto thumbnail">
    					<div class="checkbox pull-right">
    						<!-- <input name="accesorios_ids[<?php echo $i?>]" value="0" type="hidden" /> -->
    						<label>
    						<input type="checkbox" id="<?php echo $v->id;?>" name="accesorios_ids[<?php echo $p->id ?>][]" value="<?php echo $v->id;?>" <?php echo set_checkbox("accesorios_ids[]",'1',(@$r->activo)?TRUE:FALSE); ?>/>
    						</label>
    						<?php echo  form_error($v->id);?>
    					</div>
    					<?php $path = 'img/layout/imagen_no_disponible.jpg';?>
    					<?php if(file_exists(FCPATH."files/accesorios/{$v->id}.jpg"))
    							$path='files/accesorios/'.$v->id.'.jpg';
    					?>
    					<img src="<?php echo site_url('/thumbs/timthumb.php?src='.$path.'&s=400&t='.time());?>" class="img_thumb" />
    					<div class="caption">
    						<a href="">
    							<span class="front_tituloProducto"><?php echo $v->nombre; ?></span>
    							<span class="front_cuerpoTexto">(<?php echo (!empty($v->obligatorio_id))?'Obligatorio':'Optativo'?>)</span>
    							<p class="front_cuerpoTexto">Modelo: <?php echo $v->modelo;?></p>
    							<p class="front_cuerpoTexto">#Item: <?php echo $v->item;?></p>
    							<p class="front_precio">Precio: <?php echo precio_con_iva($v->precio);?></p>
    						</a>
    					</div>
    				</div>
    			</div>
			<?php $i++; endforeach;?>
		</div>
		<?php endforeach; ?>
		<div class="row">
			<div class="col-lg-12 front_btnsWrapper">
				<input class="btn btn-front-primary pull-right" name="agregar" id="agregar" type="submit" value="Agregar">
				<a class="btn btn-front-default pull-right" href="<?php echo site_url('frontends/index/'); ?>">Cancelar</a>
			</div>
		</div>
		</form>
	</div>
</div>




<?php $this->load->view('frontend/layout/footer');?>
<script>
$(function(){
	
	   $('.color').click(function(e){e.preventDefault();
	   		$(this).parent('div.wrapper').children('div.color').removeClass('bordeRojo');
	   		$(this).parent('div.wrapper').children('div.color').removeClass('con_borde');
			$(this).parent('div.wrapper').children('div.color').addClass('con_borde');
			$(this).addClass('bordeRojo');
	   		$('.bordeRojo').removeClass('con_borde');
			
			var accesorio_id = $(this).children('input.acc_id').val();
			$(this).parent('div.wrapper').prev('div.tipos_div').children('input.acc_sel').val(accesorio_id);			
	   });

		function validar()
		{
			var valido=true;
			$('.obligatorio').each(function(e){
				var valor=$(this).val();
				
				if(isNaN(valor) || valor=='')
				{	
					valido=false;
					return valido;
				}
				
			});
			return valido;	
		}
		
		$('#agregar').click(function(e){e.preventDefault();
		var valido=validar();

		if(valido==false)
			alert('Debe seleccionar todos los accesorios obligatorios');
		else
			$('#from_accesorios').submit();

		});
		
});

</script>