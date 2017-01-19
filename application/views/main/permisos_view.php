<?php if(!is_ajax()){ $this->load->view('layout/header'); } ?>
<?php if(count($funciones)==0): ?>
<div class="sin_resultados">No se ha encontrado ning&uacute;n registro.</div>
<?php else: ?>
<div class="col-lg-12">
	<form action="<?php echo site_url('main/permisos');?>" method="post" id="form"  enctype="multipart/form-data">
	<input  type="hidden" name="grupos_id" value="<?php echo set_value('grupos_id',@$grupos_id); ?>" />
	<?php foreach($categorias as $categorias_id=>$categoria):?>
	<?php if(isset($funciones[$categorias_id]) && count($funciones[$categorias_id])>0):?>
		<div class="panel-group">
			<div class=" panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $categorias_id ?>">
					<?php echo $categoria; ?>
					</a>
					
					</h4>
				</div>
				<div id="collapse<?php echo $categorias_id ?>" class="panel-collapse collapse in">
					<div class="panel-body">
						<div class="row">
							
							<div class="col-lg-12 ">
								<div class="table-responsive">
									<table class="table table-hover">
										<thead>
											<tr>
												<td class="data_first" width="33%">Permiso</td>
												<td class="data_first" width="33%">Funci&oacute;n</td>
												<td class="data_first" width="33%">Descripci&oacute;n</td>
											</tr>
										</thead>
										<tfoot>
											<tr>
												<td colspan="10" class="p0">			
													<div class="col-lg-12">
														<p>Se encontraron <?php echo count($funciones[$categorias_id]); ?> resultados.
													</div>
												</td>
											</tr>
										</tfoot>
										<tbody>
											<?php $i=1; foreach($funciones[$categorias_id] as $ro):?>
											<tr <?php echo ($i%2==0)?'class="altrow"':''?>>
												<td class="col-chk"><input type="checkbox" name="funciones_id[]" class="accion" value="<?php echo $ro->id; ?>" <?php echo (array_key_exists($ro->id,$permisos))?'checked':'' ?>/></td>
												<td><?php echo $ro->funcion; ?></td>
												<td><?php echo $ro->descripcion; ?></td>
											</tr>
											<?php $i++; endforeach;?>	
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<br>
	<?php endif; ?>
	<?php endforeach; ?>
	<div class="col-lg-12 barra-btn">
		<input class="btn btn-primary pull-right " type="submit" value="Guardar" id="guardar" />
	</div>
	</form>
</div>
<?php endif;?>
<?php if(!is_ajax()){ $this->load->view('layout/footer'); } ?>