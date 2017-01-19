<?php $this->load->view('layout/header');?>
<div class="container" id="navigator">
	<?php $this->load->view('frontend/sidebar');?>

	<div id="content">
	<div class="w100 auto img_container">
		<div class="img_secondary">
			<img src="<?php echo site_url('/files/categorias/'.$datos_categoria->id.'.jpg');?>"/>
		</div>
	</div> 
	<div class="w100 auto img_container">
		<div class="img_table">
		<table>
		<?php $r=0;?>
		<?php foreach($subcategorias as $sub):?>
			<?php if($r==3):?>
				<tr>
			<?php endif;?>
					<td>
					<a href="<?php echo site_url('frontends/subcategorias/'.$sub['id']); ?>">
						<img src="<?php echo site_url('/files/categorias/'.$sub['id'].'.jpg');?>"/>
					</a>
						<div class="w100 auto referencias">
							<br/>
							<h4 class="gris_center"><?php echo $sub['nombre'];?></h4>
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
<?php $this->load->view('frontend/layout/footer');?>