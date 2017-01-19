<?php
require_once('main.php');
class Gastos_envios extends Main {

    public function __construct()
    {
        parent::__construct();
        $this->base->verifica('gastos_envio');
        $this->load->model('Gastos_envio','GE');
    }

    public function index()
    {
        $this->base->verifica('gastos_envio');

        if(!empty($_POST))
        {
            $validaciones = $this->GE->validar();
            $this->form_validation->set_rules($validaciones);

            if($this->form_validation->run())
            {
                foreach($_POST['gastos_envios'] as $id=>$r)
                    $this->base->guarda('gastos_envios',$r);

                $this->session->set_flashdata('done', 'Los cambios se han guardado correctamente.');
                redirect('gastos_envios/index');
            }
            else
            {
                $datos['flashdata']['error']='Por favor verifique los datos.';
                $datos['gastos_envios']=$_POST['gastos_envios'];
            }

        }
        else
            $datos['gastos_envios']=$this->GE->gastos_envio_get();

        $datos['titulo']='Gastos de Env&iacute;o';

        $this->load->view('gastos_envio/index',$datos);
    }

}