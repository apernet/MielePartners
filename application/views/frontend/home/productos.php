<?php $this->load->view('frontend/layout/header');?>
<div class="container" id="navigator">
	<?php $this->load->view('frontend/sidebar');?>
<div id="content">
	<div class="auto box">Direcci&oacute;n actual del usuario.</div>

	<div class="w100 auto img_container">
<!--	<div class="img_secondary">
	
		<div class="img_information">
				<h3>titulo del producto principal</h3>
				<h2>descripcion del producto principal</h2>
				<p>Descripcion del producto cuando una persona agrega el contenido la decripcion del producto mediante</p>
				<a href="">LINK hacia el producto u otra parte</a>
			</div> 
			<img src="<?php echo site_url('/files/productos/'.$p['id'].'/'.$p['id'].'.jpg');?>"/>
		</div> -->
	</div>

	<div class="w100 auto box">
		<p>Caja de b&uacute;squeda y paginador</p>

	<div class="w100 auto img_container">
		<div class="img_table">
		<table>
		<?php $r=0;?>
		<?php foreach($productos as $p):?>		
			<?php if($r==3):?>
				<tr>
			<?php endif;?>
					<td>
					<a href="<?php echo site_url('files/productos/detalle/'.$p['id']); ?>">
						<img src="<?php echo site_url('/files/productos/'.$p['id'].'/'.$p['foto_id'].'.jpg');?>"/>
					</a>
						<div class="w100 auto referencias">
							<br/>
							<h4 class="gris_center"><?php echo $p['descripcion'];?></h4>
							<br/>
							<h4 class="gris_center"><?php echo $p['precio'];?></h4>
						</div>
					</td>	
			<?php if($r==3):?>
				</tr>
				<?php $r=0;?>
			<?php endif;?>
			<?php $r++;?>
		<?php endforeach;?>
		</table>
		</div>
	</div>
	<div class="w100 auto box">
		<p>Caja de b&uacute;squeda y paginador</p>
	</div>
</div>
</div>
</div>
<?php $this->load->view('frontend/layout/footer');?>