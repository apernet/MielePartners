<?php $this->load->view('email/header'); ?>
<p>Estimad@ Usuario,</p>

<?php if(@$cambio_status):?>
    <p>Le informamos que la orden de compra con folio <b><?php echo $folio_compra; ?></b> ha cambiado de status a <b><?php echo @$status;?></b>
    </p>
<?php else:?>
    <p>Le informamos que se ha generado una nueva orden de compra del partner
        <b><?php echo $cuenta->razon_social; ?></b> con folio <b><?php echo $folio_compra; ?></b>
    </p>
<?php endif;?>
<?php $this->load->view('email/footer'); ?>