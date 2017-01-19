<?php
require_once('main.php');
class Calificaciones extends Main {

    public function __construct()
    {
        parent::__construct();
        // PONER EN CONSTRUCCION MIELE SHOP
        //if(@$_SERVER['HTTP_HOST']=='shop.miele.com.mx')
        //  redirect('construccion');
        $this->load->model('Calificacion');
        $this->load->model('Base');

        $interno=$this->session->userdata('logged');
        if($this->session->userdata('logged') && $this->session->userdata('cliente_externo'))
            $interno=FALSE;

        define('INTERNO',$interno);
    }

    private function es_contestada($cotizaciones_id)
    {
        return $this->base->get_dato('concluido','calificaciones_email',array('cotizaciones_id'=>$cotizaciones_id));
    }

    private function existe_para_calificar($cotizaciones_id)
    {
        return $this->base->get_dato('id','calificaciones_email',array('cotizaciones_id'=>$cotizaciones_id));
    }

    public function califica($cotizaciones_id, $hash)
    {
        $created = $this->base->get_dato('created','cotizaciones',array('id' => $cotizaciones_id));
        $hash_recovery = sha1($cotizaciones_id.$created);


        $existe =$this->existe_para_calificar($cotizaciones_id);

        if($existe && $hash_recovery == $hash) {

            if (!empty($_POST)) {
                $this->Calificacion->set_concluida($cotizaciones_id);
                $_POST = array();
            }

            $es_concluido = $this->es_contestada($cotizaciones_id);
            $datos['concluido'] = $es_concluido;

            if (!$es_concluido) {
                $datos['elementos'] = $this->Calificacion->get_no_calificados($cotizaciones_id);
                $datos['cotizacion_id'] = $cotizaciones_id;
                $datos['created'] = $created;
                $datos['existe'] = $existe;
            }
        }else{
            $datos['existe']=0;
        }
        $datos['titulo']='Calificacion de productos';
        $this->load->view('calificaciones/califica', $datos);
    }

    public function set_calificacion()
    {
        $ID = $_POST['id'];
        $data = array(
            'calificacion'=>$_POST['calificacion'],
            'comentario'=>$_POST['comentario'],
            'cotizacion_id'=>$_POST['cotizacion_id']
        );
//        $tabla = $_POST['tabla'];

        $this->Calificacion->set_calificacion($ID, $data);

//        $this->output
//            ->set_content_type('application/json')
//            ->set_output(json_encode($data));
    }

    public function mail_calificacion(){

        $rs = $this->Calificacion->mail_calificacion();
    }

    public function reporte_general()
    {
        $this->Base->verifica('reporte_calificaciones_general');
        $b=bc_buscador();
        $tabla = $b['cond']['table'];
        $datos['r'] = $this->Calificacion->reporte_general($b['cond'], $this->config->item('por_pagina'), $b['offset'], $tabla);

        $paginador = $this->config->item('paginador_config');
        $paginador['uri_segment']=$b['uri_segment'];
        $paginador['base_url'] = site_url($b['base_url']);
        $paginador['total_rows'] = $datos['total'] = $this->Calificacion->count_general($b['cond'], $tabla);

        $datos['productos']=$this->base->lista('productos','id','nombre',TRUE,NULL,'ASC',array('ocultar'=>0));
        $datos['accesorios']=$this->base->lista('accesorios','id','nombre',TRUE,'nombre','ASC');
        $datos['categorias']=$this->base->lista('productos_categorias','id','nombre',TRUE,'nombre','ASC');
        $datos['ocultar_comentario'] = $this->Calificacion->ocultar_comentario;

        $this->load->library('pagination');
        $this->pagination->initialize($paginador);
        $datos['cond']=$b['cond'];
        $datos['paginador'] = $this->pagination->create_links();
        $datos['exportar'] = $this->base->tiene_permiso('reporte_general_exportar');
        $datos['puede_eliminar'] = $this->Base->tiene_permiso('ocultar_comentarios');

        $datos['tabla'] = $tabla;
        $datos['titulo'] = ($tabla=='productos')? 'Reporte General de Calificaciones de Productos' : 'Reporte General de Calificaciones de Accesorios';
        $this->load->view('reportes/reporte_calificacion_general', $datos);
    }

    public function reporte()
    {
        $this->Base->verifica('reporte_calificaciones_elementos');

        $b=bc_buscador();
        $datos['r'] = $this->Calificacion->reporte_elementos($b['cond'], $this->config->item('por_pagina'),$b['offset']);

        $paginador = $this->config->item('paginador_config');
        $paginador['uri_segment']=$b['uri_segment'];
        $paginador['base_url'] = site_url($b['base_url']);
        $paginador['total_rows'] = $datos['total'] = $this->Calificacion->count_elementos($b['cond']);

        $datos['productos']=$this->base->lista('productos','id','nombre',TRUE,NULL,'ASC',array('ocultar'=>0));
        $datos['categorias']=$this->base->lista('productos_categorias','id','nombre',TRUE,'nombre','ASC');
        $datos['calificacion_tablas']=$this->Calificacion->tabla;

        $this->load->library('pagination');
        $this->pagination->initialize($paginador);
        $datos['cond']=$b['cond'];
        $datos['paginador'] = $this->pagination->create_links();
        $datos['exportar'] = $this->base->tiene_permiso('reporte_elementos_exportar');

        $datos['titulo'] = 'Reporte Elementos';
        $this->load->view('reportes/reporte_calificacion_elementos', $datos);

    }

    public function reporte_exportar_elementos()
    {
        $this->base->verifica('reporte_elementos_exportar');
        $b=bc_buscador();
        $datos=$this->Calificacion->reporte_elementos($b['cond']);

        $this->Calificacion->exportar_elementos($datos);
    }

    public function reporte_exportar_general()
    {
        $this->base->verifica('reporte_general_exportar');
        $b=bc_buscador();
        $datos=$this->Calificacion->reporte_general($b['cond'], 0, 0, $b['cond']['table']);

        $this->Calificacion->exportar_general($datos, $b['cond']['table']);
    }

    public function get_calificacion()
    {
        $ID = $_POST['id'];
        $tabla = $_POST['tabla'];
        $data = $this->Calificacion->get_calificacion($ID, $tabla);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function comentarios($id, $table)
    {
        $datos['calificados'] = $this->Calificacion->calificados($id, $table);
        $datos['comentarios'] = $this->Calificacion->comentarios($id, $table);
        $datos['tabla'] = $table;
        $this->load->view('calificaciones/comentarios', $datos);
    }

    public function ocultar_comentarios($id, $table, $val)
    {
        $rs['resultado'] = $this->base->toggle('calificaciones', 'ocultar_comentario', $id, $val);
        $this->session->set_flashdata('done', 'Se oculto el comentario correctamente.');
        redirect('calificaciones/reporte_general/table/'.$table);
    }

    public function get_calificacion_comentario()
    {
        $comentario_id = $_POST['id'];
        $tabla = $_POST['tabla'];
        $data = $this->base->get_dato('calificacion', 'calificaciones', array('id' => $comentario_id, 'ocultar_comentario' => 0, 'tabla' => $tabla));
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }
}