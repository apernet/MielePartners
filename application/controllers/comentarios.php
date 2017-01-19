<?php
require_once('main.php');
class Comentarios extends Main {

    public function __construct()
    {
        parent::__construct();
        $this->base->verifica('comentarios');
        $this->load->model('Comentario');
    }

    public function index()
    {
        $b=bc_buscador();
        $datos['r']=$this->Comentario->find($b['cond'],$this->config->item('por_pagina'),$b['offset']);
        $paginador = $this->config->item('paginador_config');
        $paginador['base_url'] = site_url($b['base_url']);
        $paginador['total_rows'] = $datos['total'] = $this->Comentario->count($b['cond']);
        $paginador['uri_segment'] = $b['uri_segment'];
        $this->load->library('pagination');
        $this->pagination->initialize($paginador);
        $datos['cond']=$b['cond'];
        $datos['paginador'] = $this->pagination->create_links();

        $datos['titulo']='Comentarios';
        $datos['puede_eliminar']=$this->base->tiene_permiso('comentarios_eliminar');
        $this->load->view('comentarios/index',$datos);
    }

    public function eliminar($comentarios_id)
    {
        $this->base->verifica('comentarios_eliminar');
        $data['id']=$comentarios_id;
        $data['eliminado']=1;
        $this->base->guarda($this->Comentario->table,$data);
        $this->session->set_flashdata('done', 'El registro se ha eliminado correctamente.');
        redirect('comentarios/index');
    }
}