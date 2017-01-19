<ul id="tab_menu">
	<!--  <li><a class="btn_1" href="#">Descripci&oacute;n</a></li>-->
	<li><a class="btn_2" href="#">Ficha T&eacute;cnica</a></li>
</ul>

<div id="tabs">
	<!-- <div id="tab_1" class="table_grid">
		<div style="padding: 10px; margin: auto;">
			<p>Descripci&oacute;n:</p>
			<br>
			<!--  <p><?php echo $r->measurements;?></p>
			<hr/>
		</div> 
		<div class="w100 no_float auto text_center">
		<?php echo $r->descripcion;?>
		</div><br/>
	</div>-->
		<div id="tab_2" class="table_grid">
		<div style="padding: 10px; margin: auto;">
			<p>Ficha T&eacute;cnica</p>
			<br>
			<!-- <p><?php echo $r->materials;?></p>-->
			<hr/>
		</div>
		<div class="w100 no_float auto text_center">
		<p> <h3>Para consultar la Ficha T&eacute;cnica de este producto haz clic en el siguiente enlace:</p><br/><br/>
		<p><h1><a href="<?php echo site_url("files/productos/{$r->id}/{$documentacion[0]['id']}.pdf");?>"> Ficha T&eacute;cnica</a></p>
		</div><br/>
	</div>
<!--<div id="tab_3" class="table_grid">
		<div style="padding: 10px; margin: auto;">
			<p>Mas informacion:</p>
			<br>
			<p><?php echo $r->more_information;?></p>
			<?php if(!empty($documentacion)):?>
			<a href="<?php echo site_url("files/productos/{$r->id}/{$documentacion[0]['id']}.pdf");?>"><p>Descargar documentacion</p></a>
			<?php endif;?>
			<hr/>
		</div>
		<div class="w100 no_float auto text_center">
		<?php foreach($fotografias as $img):?>
			<?php if($img['tipos_id'] == 6 ):?>
			<img src="<?php echo site_url('/thumbs/timthumb.php?src=../files/productos/'.$r->id.'/'.$img['id'].'.jpg&s=240&t='.time())?>"/>
			<?php endif;?>
		<?php endforeach; ?>
		</div><br/>
	</div>
	<div id="tab_4" class="table_grid">
		<div style="padding: 10px; margin: auto;">
			<p>Caracteristicas:</p>
			<br>
			<p><?php echo $r->features;?></p>
			<hr/>
		</div>
		<div class="w100 no_float auto text_center">
		<?php foreach($fotografias as $img):?>
			<?php if($img['tipos_id'] == 7 ):?>
			<img src="<?php echo site_url('/thumbs/timthumb.php?src=../files/productos/'.$r->id.'/'.$img['id'].'.jpg&s=240&t='.time())?>"/>
			<?php endif;?>
		<?php endforeach; ?>
		</div><br/>
	</div>
	<div id="tab_5" class="table_grid">
		<div style="padding: 10px; margin: auto;">
			<p>Cuidado y ensamble:</p>
			<br>
			<p><?php echo $r->care_assembly;?></p>
			<hr/>
		</div>
		<div class="w100 no_float auto text_center">
		<?php foreach($fotografias as $img):?>
			<?php if($img['tipos_id'] == 8 ):?>
			<img src="<?php echo site_url('/thumbs/timthumb.php?src=../files/productos/'.$r->id.'/'.$img['id'].'.jpg&s=240&t='.time())?>"/>
			<?php endif;?>
		<?php endforeach; ?>
		</div><br/>
	</div>-->
</div>
		

<script type="text/javascript">
<!--//
	$(function() {

		$('.btn_1').click(function(event){
			event.preventDefault();
		
			$('#tab_2,#tab_3,#tab_4,#tab_5').hide();
			$('#tab_1').show();
		});
		$('.btn_2').click(function(event){
			event.preventDefault();
		
			$('#tab_1,#tab_3,#tab_4,#tab_5').hide();
			$('#tab_2').show();
		});
		$('.btn_3').click(function(event){
			event.preventDefault();
		
			$('#tab_1,#tab_2,#tab_4,#tab_5').hide();
			$('#tab_3').show();
		});
		$('.btn_4').click(function(event){
			event.preventDefault();
		
			$('#tab_1,#tab_2,#tab_3,#tab_5').hide();
			$('#tab_4').show();
		});
		$('.btn_5').click(function(event){
			event.preventDefault();
		
			$('#tab_1,#tab_2,#tab_3,#tab_4').hide();
			$('#tab_5').show();
		});
	});
//-->
</script>