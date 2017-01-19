<?php 
require_once('main.php');
class Pagos extends Main {
		
	public function __construct()
	{
		parent::__construct();
		//$this->base->verifica('pagos');
		$this->load->model('pago');    
	}

    public function ae_payment_session_create() {

    }
}