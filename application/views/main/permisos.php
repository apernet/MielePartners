<?php if(!is_ajax()){ $this->load->view('layout/header'); } ?>
	<div class="col-lg-12 formulario-head">
		<div class="row">
			<div class="col-lg-11 col-sm-10 col-xs-9">
				<h4><?php echo $titulo?></h4>
			</div>
		</div>
	</div>
	<div class="col-lg-12">
		<p class="msg info">
		<span class="fa-stack fa-2x">
			<i class="fa fa-circle fa-stack-2x text-color-blanco"></i>
		 	<i class="fa fa-info fa-stack-1x text-color-info"></i>	
		</span>			
		Seleccione un grupo para editar los permisos</p>
	</div>
	<div class="col-lg-12">
		<div class="panel-group">
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-3 form-group">
						<label><span class="req">*</span>Grupo:</label>
						<select class="form-control" id="grupos_id" name="grupos_id">
							<option value=""><?php echo $this->config->item('empty_select'); ?></option>
							<?php foreach($grupos as $k=>$v): ?>
							<option value="<?php echo $k; ?>" <?php echo ($grupos_id==$k)?'selected="selected"':''?>><?php echo $v; ?></option>
							<?php endforeach;?>
						</select>
						<?php echo  form_error('grupos_id');?>	
					</div>
				</div>
			</div>
		</div>
	</div>
	<div>
		<div id="loading" class="ajax-loading"></div>  
		<div id="view" style="display:none;"></div>
		<script type="text/javascript">
		
		 $(function(){
			function get()
			{
				// AJAX LOADING
				$('#loading').hide().ajaxStart(function() {$(this).show();}).ajaxStop(function() {$(this).hide();});
				
				var grupo=$('#grupos_id').val();
				if(grupo=='')
				{
					$('#view').hide();
					$("#view").empty();
				}
				else
				{
					$('#view').show();
					$("#view").empty();								
					$("#view").load('<?php echo site_url('main/permisos_view'); ?>/'+grupo+'/'+Math.random());
				}
			}
			$('#grupos_id').change(get);
			get();
		 });
		//
		</script>
	</div>
<?php if(!is_ajax()){ $this->load->view('layout/footer'); } ?>