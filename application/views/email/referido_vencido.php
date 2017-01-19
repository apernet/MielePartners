<?php $this->load->view('email/header'); ?>
<p>Estimad@ <?php echo $r->vendedor_nombre_completo;?>,</p>

    <p>Le informamos que la referencia con ID: <b><?php echo $r->id; ?></b> del cliente: <b><?php echo $r->cliente_nombre_completo; ?></b> ha vencido.</p>

    <p>Fecha de referido: <b><?php ver_fecha_hora($r->fecha_referido); ?></b></p>
    <p>Fecha de vencimiento: <b><?php ver_fecha_hora($r->fecha_vigencia); ?></b></p>
    <p>Status: <b>Vencido</p>
<?php $this->load->view('email/footer'); ?>