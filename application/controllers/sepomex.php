<?php
class Sepomex extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->Model('Sepomex_model','SP');	
	}

	function get_municipios()
	{
		$this->SP->get_municipios($_POST['estado'],TRUE);
	}
	
	function get_colonias()
	{
		$this->SP->get_colonias($_POST['estado'],$_POST['municipio'],$_POST['codigo_postal'],TRUE);
	}
	
	function get_codigo_postal()
	{
		$this->SP->get_codigo_postal($_POST['estado'],$_POST['municipio'],$_POST['asentamiento'],TRUE);
	}
	
	function get_direccion()
	{
		$this->SP->get_direccion($_POST['codigo_postal'],TRUE);
	}
}

/* End of file avaluos.php */
/* Location: ./system/application/controllers/avaluos.php */