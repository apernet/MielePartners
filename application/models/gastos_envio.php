<?php
require_once('base.php');
class Gastos_envio extends Base {

    public function __construct()
    {
        parent::__construct();
    }

    public function gastos_envio_get()
    {
        $this->db->from('gastos_envios');
        $res=$this->db->get()->result_array();

        return $res;
    }

    /**
     * @param $estado
     * @param $tipo      1.- Productos, 2.- Accesorios, 3.- Consumibles
     * @return mixed
     */
    public function gastos_envio_min_productos($estado, $tipo)
    {
        switch ($tipo)
        {
            case 1:
                $campo ='productos_numero';
                break;
            case 2:
                $campo ='accesorios_numero';

                break;
            case 3:
                // NO APLICA
                break;
        }

        $this->db->select($campo);
        if($estado)
            $this->db->where('estado',$estado);
        $res=$this->db->get('gastos_envios')->row($campo);

        return $res;
    }

    /**
     * @param $estado
     * @param $tipo     1.- Productos, 2.- Accesorios, 3.- Consumibles
     * @return mixed
     */
    public function gastos_envio_costo($estado, $tipo)
    {
        switch ($tipo)
        {
            case 1:
                $this->db->select('productos_porcentaje, productos_monto_fijo');
                break;
            case 2:
                $this->db->select('accesorios_porcentaje, accesorios_monto_fijo');
                break;
            case 3:
                $this->db->select('consumibles_porcentaje, consumibles_monto_fijo, consumibles_monto_minimo_porcentaje');
                break;
        }

        if($estado)
            $this->db->where('estado',$estado);

        $res=$this->db->get('gastos_envios')->row();

        return $res;
    }

    public function validar()
    {
        $reglas = array();

        foreach($_POST['gastos_envios'] as $k=>$v)
        {
            $reglas[]=array(
                'field'   => "gastos_envios[{$k}][productos_porcentaje]",
                'label'   => 'porcentaje de productos',
                'rules'   => "trim|numeric"
            );

            $reglas[]=array(
                'field'   => "gastos_envios[{$k}][productos_monto_fijo]",
                'label'   => 'monto fijo de productos',
                'rules'   => "trim|numeric"
            );

            $reglas[]=array(
                'field'   => "gastos_envios[{$k}][productos_numero]",
                'label'   => 'numero de productos m&iacute;nimos',
                'rules'   => "trim|numeric"
            );

            $reglas[]=array(
                'field'   => "gastos_envios[{$k}][accesorios_porcentaje]",
                'label'   => 'porcentaje de accesorios',
                'rules'   => "trim|numeric"
            );

            $reglas[]=array(
                'field'   => "gastos_envios[{$k}][accesorios_monto_fijo]",
                'label'   => 'monto fijo de accesorios',
                'rules'   => "trim|numeric"
            );

            $reglas[]=array(
                'field'   => "gastos_envios[{$k}][accesorios_numero]",
                'label'   => 'numero de accesorios m&iacute;nimos',
                'rules'   => "trim|numeric"
            );

            $reglas[]=array(
                'field'   => "gastos_envios[{$k}][consumibles_porcentaje]",
                'label'   => 'porcentaje de consumibles',
                'rules'   => "trim|numeric"
            );

            $reglas[]=array(
                'field'   => "gastos_envios[{$k}][consumibles_monto_minimo_porcentaje]",
                'label'   => 'monto m&iacute;nimo para porcentaje de consumibles',
                'rules'   => "trim|numeric"
            );

            $reglas[]=array(
                'field'   => "gastos_envios[{$k}][consumibles_monto_fijo]",
                'label'   => 'monto fijo de consumibles',
                'rules'   => "trim|numeric"
            );

            /*$reglas[]=array(
                'field'   => "gastos_envios[{$k}][consumibles_numero]",
                'label'   => 'numero de consumibles m&iacute;nimos',
                'rules'   => "trim|numeric"
            );*/
        }

        return $reglas;
    }
}