<!-- WARNING -->
<?php if($this->session->flashdata('warning')):?>
<p class="msg warning" title="De click para cerrar">
	
	<?php echo $this->session->flashdata('warning'); ?>
</p>
<?php endif; ?>
<?php if(isset($flashdata['warning'])):?>
<p class="msg warning"><?php echo $flashdata['warning']; ?></p>
<?php endif; ?>
<!-- ENDWARNING -->

<!-- INFO -->
<?php if($this->session->flashdata('info')):?>
<!-- 
	<div class="col-lg-12">
		<p class="msg info">
		<span class="fa-stack fa-2x">
		  <i class="fa fa-circle fa-stack-2x text-color-blanco"></i>
		  <i class="fa fa-info fa-stack-1x text-color-info"></i>
		</span>
		Los campos requeridos est&aacute;n marcados con <span class="req">*</span></p>
	</div>
 -->
<p class="msg info">
	<span class="fa-stack fa-2x">
	  <i class="fa fa-circle fa-stack-2x text-color-blanco"></i>
	  <i class="fa fa-info fa-stack-1x text-color-info"></i>
	</span>
	<?php echo $this->session->flashdata('info'); ?>
</p>
<?php endif; ?>
<?php if(isset($flashdata['info'])):?>
<p class="msg info"><?php echo $flashdata['info']; ?></p>
<?php endif; ?>
<!-- ENDINFO -->

<!-- DONE -->
<?php if($this->session->flashdata('done')):?>
<p class="msg done" title="De click para cerrar">
	<span class="fa-stack fa-2x">
	  <i class="fa fa-circle fa-stack-2x text-color-blanco"></i>
	  <i class="fa fa-check fa-stack-1x text-color-done"></i>
	</span>	
	<?php echo $this->session->flashdata('done'); ?>
</p>
<?php endif; ?>
<?php if(isset($flashdata['done'])):?>
<p class="msg done" title="De click para cerrar"><?php echo $flashdata['done']; ?></p>
<?php endif; ?>
<!-- ENDDONE -->

<!-- ERROR -->
<?php if($this->session->flashdata('error')):?>
<p class="msg error">
	<span class="fa-stack fa-2x">
	  <i class="fa fa-circle fa-stack-2x text-color-blanco"></i>
	  <i class="fa fa-exclamation fa-stack-1x text-color-danger"></i>
	</span>
	<?php echo $this->session->flashdata('error'); ?>
</p>
<?php endif; ?>
<?php if(isset($flashdata['error'])):?>
<p class="msg error">
	<span class="fa-stack fa-2x">
	  <i class="fa fa-circle fa-stack-2x text-color-blanco"></i>
	  <i class="fa fa-exclamation fa-stack-1x text-color-danger"></i>
	</span>
	<?php echo $flashdata['error']; ?>
</p>
<?php endif; ?>
<!-- ENDERROR -->


<!-- ERROR -->
<?php if($this->session->flashdata('div_error')):?>
<div class="msg error">
	<?php echo $this->session->flashdata('div_error'); ?>
</div>
<?php endif; ?>
<?php if(isset($flashdata['div_error'])):?>
<div class="msg error">
	<?php echo $flashdata['div_error']; ?>
</div>
<?php endif; ?>
<!-- ENDERROR -->