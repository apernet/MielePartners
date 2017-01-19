<?php $this->load->view('email/header'); ?>
    <p>Estimad@, <?php echo $nombre ;?>:</p>
    <p>Su opinión respecto a nuestros productos es muy importante, es por ello que le pedimos nos comparta su experiencia con los equipos Miele adquiridos el día <b><?php echo ver_fecha($fecha_compra)?>.</b></p>
    <br/>
<?php $this->load->view('email/footer_calificacion'); ?>