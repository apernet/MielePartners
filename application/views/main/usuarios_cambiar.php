<?php if(!is_ajax()){ $this->load->view('layout/head'); } ?>
<div class="login">
	<div class="row no-margin">
		<div class="col-lg-4 col-lg-offset-4 admin-login">
			<div class="col-lg-11 col-sm-10 col-xs-9">
				<h4><?php echo $titulo?></h4>
			</div>
			<div class="row">
				<div class="col-lg-12 admin-login-body">
					<br>
					<p class="msg info mb40">
					<span class="fa-stack fa-2x">
						  <i class="fa fa-circle fa-stack-2x text-color-blanco"></i>
						  <i class="fa fa-info fa-stack-1x text-color-info"></i>
					</span>
					Seleccione el usuario al que desea cambiar</p>
					<form action="<?php echo site_url(uri_string()); ?>" id="form" method="post">
						<div class="row">
							<div class="col-lg-12 mb20">
								<select class="form-control" name="usuarios_id" id="usuarios_id">
									<?php foreach($usuarios as $k=>$v):?>
									<option value="<?php echo $k;?>" <?php echo $this->session->userdata('id')==$k?'selected="selected"':'';?> ><?php echo $v; ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						<div class="col-lg-12 barra-btn">		
							<input class=" btn btn-primary pull-right" type="submit" value="Cambiar" id="cambiar" />
							<a href="#" id="cancelar_cu" class=" btn btn-default pull-right  ">Cancelar</a>
							<div class="clearblock">&nbsp;</div>
						</div>	
						</div>
					</form>
				</div>	
			</div>
		</div>
	</div>
</div>
