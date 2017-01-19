<?php
require_once ('base.php');

class Comentario extends Base
{
    var $table = 'comentarios';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Condiciones de filtros
     */
    private function pconditions($cond)
    {
        if(!empty($cond ['nombre']))
            $this->db->like('nombre', $cond ['nombre']);

        if(!empty($cond ['apellido_paterno']))
            $this->db->like('apellido_paterno', $cond ['apellido_paterno']);

        if(!empty($cond ['apellido_materno']))
            $this->db->like('apellido_materno', $cond ['apellido_materno']);

        if(!empty($cond ['email']))
            $this->db->like('email', $cond ['email']);

        if(!empty($cond ['telefono']))
            $this->db->like('telefono', $cond ['telefono']);

        if(!empty($cond ['celular']))
            $this->db->like('celular', $cond ['celular']);

        if(!empty($cond ['comentario']))
            $this->db->like('comentario', $cond ['comentario']);
    }

    /**
     * Encontrar comentarios
     */
    public function find($cond, $limit, $offset)
    {
        $this->pconditions($cond);
        $this->db->from('comentarios');
        $this->db->where('eliminado', 0);
        $this->db->order_by('id', 'DESC');

        if($limit)
            $this->db->limit($limit, $offset);

        $r = $this->db->get()->result_object();

        return $r;
    }

    /**
     * Contar comentarios
     */
    public function count($cond)
    {
        $this->pconditions($cond);
        $this->db->from('comentarios as c');
        $this->db->where('eliminado', 0);
        $r = $this->db->count_all_results();

        return $r;
    }

    /**
     * Guarda comentario
     */
    public function comentarios_guarda($data)
    {
        $datos=array(
            'nombre'=>$data['nombre'],
            'apellido_paterno'=>$data['apellido_paterno'],
            'apellido_materno'=>$data['apellido_materno'],
            'email'=>$data['email'],
            'telefono'=>$data['telefono'],
            'celular'=>$data['celular'],
            'comentario'=>$data['comentario'],
            'eliminado'=>0
        );

        $comentarios_id = $this->base->guarda('comentarios',$datos);

        // Enviar correo
        $this->comentarios_mail($comentarios_id);

        return $comentarios_id;
    }

    /**
     * Enviar comentario
     */
    public function comentarios_mail($comentarios_id)
    {
        $this->db->where('id', $comentarios_id);
        $datos['comentario'] = $this->db->get('comentarios')->row();

        $this->load->library('email');
        $email_sax = $this->config->item('email');
        $this->email->from($email_sax [0], $email_sax [1]);
        $this->email->to($this->config->item('mail_comentarios'));
        $bcc = $this->config->item('mail_bcc');
        if (! empty($bcc))
            $this->email->bcc($bcc);

        $this->email->subject('Comentario');
        $mensaje = $this->load->view('email/comentarios', $datos, true);
        $this->email->message($mensaje);
        $this->email->send();
        if ($this->config->item('debug_mail')) // DEBUG DE MAIL
        {
            debug($this->email->print_debugger(), null, 1);
        }

        return TRUE;
    }
}