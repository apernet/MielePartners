<?php if(!is_ajax()){ $this->load->view('layout/header'); } ?>
	<div class="col-lg-12 formulario-head">
		<div class="row">
			<div class="col-lg-12 col-sm-10 col-xs-9">
				<h4><?php echo $titulo;?></h4>
			</div>
		</div>
	</div>
	<form action="<?php echo site_url(uri_string());?>" method="post" id="form">
		<div class="col-lg-12">
			<div class="row">
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					<label>Tipo:</label>
					<select name="tipos_id" id="tipos_id" class="form-control">
						<option value=""><?php echo $this->config->item('empty_select');?></option>
						<?php foreach($tipos as $k=>$v):?>
						<option value="<?php echo $k;?>"  <?php echo ($k == @$cond['tipos_id'])?'selected="selected"':''; ?>><?php echo $v; ?></option>
						<?php endforeach;?>
					</select>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					<label>Usuario:</label>
					<select name="usuarios_id" id="usuarios_id" class="form-control">
						<option value=""><?php echo $this->config->item('empty_select');?></option>
						<?php foreach($usuarios as $k=>$v):?>
						<option value="<?php echo $k;?>"  <?php echo ($k == @$cond['usuarios_id'])?'selected="selected"':''; ?>><?php echo $v; ?></option>
						<?php endforeach;?>
					</select>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					<label>Fecha inicial:</label>
					<input id="fecha_inicial_alt" name="fecha_inicial" value="<?php echo @$cond['fecha_inicial']; ?>" type="hidden" />
					<input class="form-control" id="fecha_inicial" value="<?php echo get_fecha(@$cond['fecha_inicial']); ?>" />
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					<label>Fecha final:</label>
					<input id="fecha_final_alt" name="fecha_final" value="<?php echo @$cond['fecha_final']; ?>" type="hidden" />
					<input class="form-control" id="fecha_final" value="<?php echo get_fecha(@$cond['fecha_final']); ?>" />
				</div>	
			</div>
		</div>
		<div class="col-lg-12">
			<div class="row">
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					<label>Mensaje:</label>
					<input id="mensaje" name="mensaje" class="form-control" value="<?php echo @$cond['mensaje'];?>" />
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					<label>Datos:</label>
					<input id="datos" name="datos" class="form-control" value="<?php echo @$cond['datos'];?>" />
				</div>
			</div>
		</div>
		<div class="col-lg-12 barra-btn">
			<a class="btn btn-default pull-left" href="<?php echo site_url('main/mostrar_detalles');?>"><?php echo $this->session->userdata('mostrar_detalles')?'Ocultar datos':'Mostrar datos';?></a>
			<button  type="submit" class="btn btn-primary pull-right">Buscar</button>
		  	<input type="reset" class="btn btn-default pull-right bc_clear" value="Limpiar"/>
		  	<div class="clear"></div>
		</div>
	</form>
<?php if($total==0): ?>
	<div class="msg sin_resultados">No se ha encontrado ning&uacute;n registro.</div>
<?php else: ?>
<div class="col-lg-12">
	<div class="table-responsive">
		<table class="table table-hover">
			<tfoot>
				<tr>
					<td colspan="<?php if($this->session->userdata('mostrar_detalles')): ?>6<?php else:?>5<?php endif;?>" class="p0">
						<div class="col-lg-4 pull-left">
							<?php echo $paginador; ?>
						</div>
						<div class="col-lg-4 pull-right">
							<p class="pull-right">Se encontraron <?php echo $total; ?> resultados.</p>
						</div>
					</td>
				</tr>
			</tfoot>
			
			<thead>
				<tr>
					<td>Id</td>
					<td>Tipo</td>
					<td>Usuario</td>
					<td>Mensaje</td>
					<?php if($this->session->userdata('mostrar_detalles')): ?>
						<td>Datos</td>
					<?php endif;?>
					<td>Fecha</td>
				</tr>
			</thead>
			<tbody>
				<?php $i=1; foreach($r->result_object() as $ro):?>
				<tr <?php echo ($i%2==0)?'class="altrow"':''?>>
					<td><?php echo $ro->id; ?></td>
					<td><?php echo $tipos[$ro->tipos_id];?></td>
					<td><?php echo ($ro->usuarios_id)?$usuarios[$ro->usuarios_id]:'No aplica'; ?></td>
					<td><?php echo $ro->mensaje; ?></td>
					<?php if($this->session->userdata('mostrar_detalles')): ?>

						<td><?php echo $ro->datos;?></td>
					<?php endif; ?>
					<td><?php ver_fecha_hora($ro->created); ?></td>
				</tr>
				<?php $i++; endforeach;?>	
			</tbody>
		</table>
	</div>
</div>
<?php endif;?>
<?php if(!is_ajax()){ $this->load->view('layout/footer'); } ?>