<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery/jquery.min.js"></script>

<form method="post" enctype="application/x-www-form-urlencoded" action="https://gateway-na.americanexpress.com/page/pay" id="american_express_form">
    <div class="row">
        <div class="col-lg-3 form-group">
            <input type="hidden" name="order.description" value="<?php echo @$american_express['description'];?>"/>
            <input type="hidden" name="paymentPage.merchant.name" value="<?php echo @$american_express['name'];?>"/>
            <input type="hidden" name="session.id" value="<?php echo @$american_express['session'];?>"/>
        </div>
    </div>
</form>

<script>
    // ENVIAR AUTOMATICAMENTE
    $( document ).ready(function() {
        //setTimeout(function() {
                $('#american_express_form').submit();
        //}, 3000);

    });
</script>

