<?php
require_once('main.php');
class Payments extends Main {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Payment');
		
		$interno=$this->session->userdata('logged');
		if($this->session->userdata('logged') && $this->session->userdata('cliente_externo'))
			$interno=FALSE;
		define('INTERNO',$interno);
	}
	
	function pago($cotizaciones_id,$debug=FALSE)
	{
		$this->load->model('Payment');
		//$datos=$this->_init_miele();
		
		//INICIALIZA VARIABLES GENERALES
		$datos['categorias_menu']=$this->base->lista('productos_categorias','id','nombre',TRUE,'id','ASC',array('parent_id'=>NULL));
		
		// ACTIVAR LA MESA DE REGALOS
		$mesa_logged=$this->session->userdata('mesa');
		$datos['mesa_logged']=FALSE;
		if(!empty($mesa_logged))
		{
			$datos['mesa_logged']=TRUE;
			$this->load->model('Producto');
			$datos['mp_sel']=$this->Producto->get_mesa_productos_id($mesa_logged->id);
		}
		
		$datos['num_productos']=count($this->session->userdata('productos'));// NUMERO EN CARRITO
		$datos['usuario']=$this->session->userdata('usuario');
		$cotizacion_id=$this->session->userdata('cotizaciones_id');
		
		if(isset($cotizacion_id) && $cotizacion_id!=NULL)
		{
			$this->load->model('Cotizacion');
			$status_id=$this->Cotizacion->get_status($this->session->userdata('cotizaciones_id'));
			if(in_array($status_id,array(2,3,4,5,6)) && $this->uri->segment(2)!='enviar_compra' && INTERNO)
			{
				$this->session->set_userdata('productos',array());
				$this->session->set_userdata('cotizaciones_id',NULL);
				$datos['num_productos']=0;
			}
		}

		$datos['cotizaciones_id']=$cotizaciones_id;
		$datos['titulo']='Realizar Pago';
		$this->load->view('frontend/shopline/pago',$datos);
	}

    function ae_pay($cotizaciones_id,$months = FALSE)
    {
        // DATOS DE FORM PARA PAGOS
        $datos['american_express'] = $this->Payment->ae_payment_form($cotizaciones_id, $months);
        $datos['flashdata']['info']='Redireccionando a la pagina de pago, por favor espere...';
        $this->load->view('frontend/shopline/pago_american_express',$datos);
    }

    function banamex_payment_pay($cotizaciones_id, $months =FALSE, $debug = FALSE)
    {
        $this->Payment->banamex_payment_pay($cotizaciones_id, $months, $debug);
    }
	
	function callback()
	{
        if(!empty($_GET))
        {
            $this->Payment->pago_realizado();
        }

        $this->session->set_flashdata('error', 'Error de conexión');
        redirect('frontends/index/');
	}
}