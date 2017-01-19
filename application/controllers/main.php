<?php
class Main extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->base->verifica(null,is_ajax()); // REVISA LA SESION O QUE SEA PUBLICA LA FUNCION								
		//IDIOMA ESPAÑOL DE MEXICO
		setlocale(LC_TIME,"mx", "spanish", "es_MX.UTF-8");
		//ERRORES DE VALIDACION
		$this->form_validation->set_error_delimiters('<span class="form-error-inline" title="', '">&nbsp;</span>');
		if($this->config->item('cache_output'))
			$this->output->cache($this->config->item('cache_time'));
		if($this->config->item('cloudfiles'))
			$this->load->model('cloud_files');
	}
	
	function sesion_ajax()
	{
		$sesion=$this->session->userdata('logged');
		$this->output->set_output("{logged:'".$sesion."'}");
	}
	
	function index()
	{
		$this->load->Model('Noticia');
		$datos['noticias_inicio']=$this->Noticia->get_noticias_inicio();
		$datos['noticias']=$this->Noticia->get_noticias();
		$datos['titulo']='Dashboard';
		$this->load->view('main/index',$datos);		
	}	
	
	function login()
	{	
		if(!empty($_POST))
		{
			if($this->form_validation->run('login'))
			{
				$data['usuario']=$this->input->post('usuario');
				$data['contrasena']=$this->input->post('contrasena');
				$login = $this->base->login($data);
				if($login === TRUE)
				{
					redirect('/main/index');
				}
				elseif($login==='DESACTIVADO')
				{
					$datos['flashdata']['error']='Lo sentimos, su usuario se encuentra desactivado, para mas informaci&oacute;n pongase en contacto con el administrador.';
				}
				elseif($login==='SUSPENDIDO')
				{
					$datos['flashdata']['error']='Lo sentimos, su usuario se encuentra suspendido por falta de pago, para mas informaci&oacute;n pongase en contacto con el administrador.';
				}
				elseif($login==='ERROR')				
				{
					$datos['flashdata']['error']='El usuario y/o contrase&ntilde;a son incorrectos.';
				}
				elseif($login==='MANTENIMIENTO')				
				{
					$datos['flashdata']['error']='El sistema se encuentra en mantenimiento, por favor intente nuevamente mas tarde.';
				}
			}
			else
			{
				$datos['flashdata']['error']='Por favor verifique los datos.';
			}			
		}
		
		$datos['explorer']=$this->base->navegador();
			
		$datos['titulo']="Iniciar Sesi&oacute;n";
		$this->load->view('main/login',$datos);				
	}
	
	function logout()
	{				
		$this->session->sess_destroy();
		redirect('/main/login');
	}		
	
	function grupos()
	{
		$this->base->verifica('grupos');
		$b=bc_buscador();	
		$datos['r']=$this->base->grupos($b['cond'],$this->config->item('por_pagina'),$b['offset']);	
		$paginador = $this->config->item('paginador_config');
		$paginador['base_url'] = site_url($b['base_url']);
		$paginador['total_rows'] = $datos['total'] = $this->base->grupos_count($b['cond']);
		$paginador['uri_segment'] = $b['uri_segment'];		
		$this->load->library('pagination');
		$this->pagination->initialize($paginador);
		$datos['cond']=$b['cond'];
		$datos['paginador'] = $this->pagination->create_links();		
		$datos['titulo']='Grupos';
		$this->load->view('main/grupos',$datos);
	}
	
	function grupos_activar($id,$val)
	{
		$this->base->verifica('grupos');
		$this->base->toggle('grupos','activo',$id,$val);
		$this->session->set_flashdata('done', 'Cambios realizados correctamente.');
		redirect('main/grupos');	
	}	
	
	function grupos_agregar()
	{
		$this->base->verifica('grupos');
		if(!empty($_POST))
		{
			if ($this->form_validation->run('grupos'))
			{
				$data=array(
					'nombre'=>$this->input->post('nombre'),
					'activo'=>1
				);
				$this->base->guarda('grupos',$data);
				$this->session->set_flashdata('done', 'El registro fue creado correctamente.');
				redirect('main/grupos');
			}
			else
			{
				$datos['flashdata']['error']='Por favor verifique los datos.';
			}
		}
		$datos['titulo']='Agregar grupo';
		$this->load->view('main/grupos_agregar',$datos);
	}
	
	function grupos_editar($id)
	{
		$this->base->verifica('grupos');
		if(!empty($_POST))
		{
			if ($this->form_validation->run('grupos'))
			{
				$data=array(
					'id'=>$this->input->post('id'),
					'nombre'=>$this->input->post('nombre')
				);
				$this->base->guarda('grupos',$data);
				$this->session->set_flashdata('done', 'El registro fue guardado correctamente.');
				redirect('main/grupos');
			}
			else
			{
				$datos['flashdata']['error']='Por favor verifique los datos.';
			}
		}
		else
		{
			$datos['r']=$this->base->read('grupos',$id);
		}		
		$datos['titulo']='Editar grupo';
		$this->load->view('main/grupos_editar',$datos);
	}
	
	function usuarios()
	{
		$this->base->verifica('usuarios');
		$b=bc_buscador();	
		$datos['r']=$this->base->usuarios($b['cond'],$this->config->item('por_pagina'),$b['offset']);
		$paginador = $this->config->item('paginador_config');
		$paginador['base_url'] = site_url($b['base_url']);
		$paginador['total_rows'] = $datos['total'] = $this->base->usuarios_count($b['cond']);
		$paginador['uri_segment']=$b['uri_segment'];		
		$this->load->library('pagination');
		$this->pagination->initialize($paginador);
		$datos['cond']=$b['cond'];
		$datos['paginador'] = $this->pagination->create_links();
		$datos['grupos']=$this->base->lista('grupos','id','nombre');
		$datos['cuentas']=$this->base->lista('cuentas','id','nombre', FALSE);		
		$datos['titulo']='Usuarios';
		$this->load->view('main/usuarios',$datos);
	}
	
	function usuarios_activar($id,$val)
	{
		$this->base->verifica('usuarios');
		$this->base->toggle('usuarios','activo',$id,$val);
		$this->session->set_flashdata('done', 'Cambios realizados correctamente.');
		redirect('main/usuarios');	
	}	
	
	function usuarios_agregar()
	{		
		$this->base->verifica('usuarios');
		$this->load->Model('Sepomex_model','SP');
		if(!empty($_POST))
		{
			if ($this->form_validation->run('usuario'))
			{
				$data=array(
					'grupos_id'=>$this->input->post('grupos_id'),
					'usuario'=>$this->input->post('usuario'),
					'cuentas_id'=>$this->input->post('cuentas_id')?$this->input->post('cuentas_id'):NULL,
					'nombre'=>$this->input->post('nombre'),
					'apellido_paterno'=>$this->input->post('apellido_paterno'),
					'apellido_materno'=>$this->input->post('apellido_materno'),
					'email'=>$this->input->post('email'),
					'telefono'=>$this->input->post('telefono'),
					'celular'=>$this->input->post('celular'),
					'activo'=>$this->input->post('activo'),
					'ayuda'=>$this->input->post('ayuda'),
					'admin'=>$this->input->post('admin'),
					'vendedor'=>$this->input->post('vendedor'),
					'eliminado'=>0,
				);
				$id=$this->base->guarda('usuarios',$data);
				
				// SI ES ADMINISTRADOR GUARDA LAS CUENTAS QUE ADMINISTRA SINO LAS BORRA
				if($_POST['admin'])
				{
					$usuarios_cuentas = $_POST['usuarios_cuentas'];
					$this->db->where('usuarios_id', $id);
					$this->db->delete('usuarios_cuentas');
						
					foreach($usuarios_cuentas as $cuentas_id)
					{
						$data = array(
								'usuarios_id' => $id,
								'cuentas_id' => $cuentas_id
						);
						$this->base->guarda('usuarios_cuentas', $data);
					}
				}
				else
				{
					$this->db->where('usuarios_id', $id);
					$this->db->delete('usuarios_cuentas');
				}
				
				$this->__usuarios_enviar_contrasena($id);
				$this->session->set_flashdata('done', 'El registro fue creado correctamente.');
				redirect('main/usuarios');
			}
			else
			{
				$datos['flashdata']['error']='Por favor verifique los datos.';
				$datos['usuarios_cuentas']=@$_POST['usuarios_cuentas'];
				$datos['r']=$_POST;
				$datos['facturacion_municipios']=$this->SP->get_municipios(@$_POST['facturacion_estado']);
				$datos['entrega_municipios']=$this->SP->get_municipios(@$_POST['entrega_estado']);
				$datos['instalacion_municipios']=$this->SP->get_municipios(@$_POST['instalacion_estado']);
			}
		}

		$datos['estados']=$this->SP->get_estados();
		$datos['grupos']=$this->base->lista('grupos','id','nombre');
		$datos['cuentas']=$this->base->lista('cuentas','id','nombre');
		$datos['titulo']='Agregar usuario';
		$this->load->view('main/usuarios_agregar',$datos);
	   
	}
	
	function usuarios_editar($id)
	{
		$this->base->verifica('usuarios');
		$this->load->Model('Sepomex_model','SP');
		if(!empty($_POST))
		{
			if($this->form_validation->run('usuario'))
			{
				$data=array(
					'id'=>$this->input->post('id'),
					'grupos_id'=>$this->input->post('grupos_id'),
					'usuario'=>$this->input->post('usuario'),
					'cuentas_id'=>$this->input->post('cuentas_id')?$this->input->post('cuentas_id'):NULL,
					'nombre'=>$this->input->post('nombre'),
					'apellido_paterno'=>$this->input->post('apellido_paterno'),
					'apellido_materno'=>$this->input->post('apellido_materno'),
					'email'=>$this->input->post('email'),
					'telefono'=>$this->input->post('telefono'),
					'celular'=>$this->input->post('celular'),
					'activo'=>$this->input->post('activo'),
					'ayuda'=>$this->input->post('ayuda'),
					'admin'=>$this->input->post('admin'),
					'vendedor'=>$this->input->post('vendedor'),
					'tipo_persona_id'=>$this->input->post('tipo_persona_id'),
					'razon_social'=>$this->input->post('razon_social'),
					'rfc'=>$this->input->post('rfc'),
					'codigo_postal'=>$this->input->post('codigo_postal'),
					'asentamiento'=>$this->input->post('asentamiento'),
					'municipio'=>$this->input->post('municipio'),
					'estado'=>$this->input->post('estado'),
					'calle'=>$this->input->post('calle'),
					'numero_exterior'=>$this->input->post('numero_exterior'),
					'numero_interior'=>$this->input->post('numero_interior'),
				 );
				
				if(isset($_POST['contrasena']))
				{
					$data['contrasena']=MD5($this->input->post('contrasena').$this->config->item('encryption_key'));
				}	
				
				$id=$this->base->guarda('usuarios',$data);
				
				// SI ES ADMINISTRADOR GUARDA LAS CUENTAS QUE ADMINISTRA SINO LAS BORRA
				if($_POST['admin'])
				{
					$usuarios_cuentas = $_POST['usuarios_cuentas'];
					$this->db->where('usuarios_id', $id);
					$this->db->delete('usuarios_cuentas');
					
					foreach($usuarios_cuentas as $cuentas_id)
					{
						$data = array(
							'usuarios_id' => $id,
							'cuentas_id' => $cuentas_id
						);
						$this->base->guarda('usuarios_cuentas', $data);
					}
				}
				else
				{
					$this->db->where('usuarios_id', $id);
					$this->db->delete('usuarios_cuentas');
				}
					
				$this->base->usuarios_imagenes($usuarios_id,$_FILES);
				$this->session->set_flashdata('done', 'El registro fue guardado correctamente.');
				redirect('main/usuarios');
			}
			else
			{
				$datos['flashdata']['error']='Por favor verifique los datos.';
				$datos['municipios']=$this->SP->get_municipios(@$_POST['estado']);
				$datos['facturacion_municipios']=$this->SP->get_municipios(@$_POST['facturacion_estado']);
				$datos['entrega_municipios']=$this->SP->get_municipios($_POST['entrega_estado']);
				$datos['instalacion_municipios']=$this->SP->get_municipios($_POST['instalacion_estado']);
			}
		}
		else
		{
			$datos['r']=$this->base->read('usuarios',$id);
			if(!empty($datos['r']->facturacion_estado))
				$datos['facturacion_municipios']=$this->SP->get_municipios($datos['r']->facturacion_estado);
		}	

		$ruta = 'files/usuarios/'.$id.'/';
		
		//VERIFICAR SI EXISTE EL ARCHIVO DE RUBRICA PARA EL USUARIO
		foreach($this->base->imagenes_usuario as $i)
		{
			$datos['imagenes'][$i] = FALSE;
			if(file_exists(FCPATH.$ruta.$i.'.jpg'))
				$datos['imagenes'][$i] = $ruta.$i.'.jpg';		
		}	
		$datos['imagenes_usuario'] = $this->base->imagenes_usuario;		
		$datos['grupos']=$this->base->lista('grupos','id','nombre');
		$datos['cuentas']=$this->base->lista('cuentas','id','nombre');
		$datos['usuarios_cuentas']=$this->base->lista('usuarios_cuentas','cuentas_id','usuarios_id',FALSE,NULL,'DESC',array('usuarios_id'=>$id));
		$datos['estados']=$this->SP->get_estados();
		$datos['titulo']='Editar usuario';
		$this->load->view('main/usuarios_editar',$datos);
	}
	
	function usuarios_admin_enviar_contrasena($id){
		$this->base->verifica('usuarios');
		$this->__usuarios_enviar_contrasena($id);
		$this->session->set_flashdata('done', 'Contrase&ntilde;a reenviada correctamente.');
		redirect('/main/usuarios');
	}
	
	function usuarios_cambiar()
	{
		$cuentas_id=$this->session->userdata('cuentas_id');
		if($cuentas_id!=1) // SOLO PARA BLACKCORE
		{
			bc_log(3,"Intento ilegal de cambio de usuario.");
			$this->session->set_flashdata('error', 'Su usuario no cuenta con los permisos necesarios para realizar esta acci&oacute;n.');
			redirect('main/index');
		}
		else
		{
			if(!empty($_POST))
			{
				$this->base->set_user_data($this->input->post('usuarios_id'));
				$this->session->set_flashdata('done', 'Cambio de usuario correcto.');
				redirect('main/index');
			}
			$datos['usuarios']=$this->base->lista('usuarios','id','usuario',FALSE,'usuario','ASC');
			$datos['titulo']='Cambiar de usuario';
			$this->load->view('main/usuarios_cambiar',$datos);
		}
		
		
	}
	
	function recuperar_contrasena()
	{
		if(!empty($_POST))
		{
			if($this->form_validation->run('recuperar_contrasena'))
			{
				$data['usuario']=$this->input->post('usuario');
				$data['email']=$this->input->post('email');
				$id=$this->base->recuperar_contrasena($data);
				if(!empty($id))
				{
					$this->__usuarios_enviar_contrasena($id);
					$this->session->set_flashdata('done', 'Su nueva contrase&ntilde;a ha sido enviada correctamente.');
					$this->output->set_output('<script type="text/javascript">location.href="'.site_url('main/login').'"</script>');
				}
				else
				{
					$datos['flashdata']['error']='Lo sentimos, no se ha encontrado ning&uacute;n usuario con esos datos, por favor intente nuevamente';
					$datos['titulo']='Recuperar contrase&ntilde;a';
					$this->load->view('main/recuperar_contrasena',$datos);
				}
			}
			else
			{
				$datos['flashdata']['error']='Por favor verifique los datos.';
				$datos['titulo']='Recuperar contrase&ntilde;a';
				$this->load->view('main/recuperar_contrasena',$datos);
			}	
		}
		else
		{
			$datos['titulo']='Recuperar contrase&ntilde;a';
			$this->load->view('main/recuperar_contrasena',$datos);
		}
		
	}
	
	function __usuarios_enviar_contrasena($id, $externo=FALSE)
	{
		$usuario=$this->base->read('usuarios',$id);
		$usuario->contrasena=$this->base->usuario_contrasena($id);
		//debug($usuario);
		//debug($this->db->last_query());
		$this->load->library('email');
		$url=$this->config->item('url');
		$email_sax=$this->config->item('email');		
		$this->email->from($email_sax[0], $email_sax[1]);
		$this->email->to($usuario->email);
		$bcc=$this->config->item('mail_bcc');
		$this->email->bcc($bcc);
		if($externo)
			$this->email->subject('Su contraseña para Míele - Shop.');
		else
			$this->email->subject('Su contraseña para el sistema Míele.');
		
		$datos['usuario']=$usuario;
		
		if($externo)
			$mensaje=$this->load->view('frontend/shopline/recuperar_contrasena_email',$datos,TRUE);
		else
			$mensaje=$this->load->view('email/contrasenia',$datos,TRUE);
				
		$this->email->message($mensaje);
		$result= $this->email->send();
		if($this->config->item('debug_mail')) // DEBUG DE MAIL
		{
			debug($this->email->print_debugger(),null,1);
		}	
		return $result;		
	}
	
	function usuario_unico($str)
	{
		$this->base->verifica('usuarios');
		$id=NULL;
		if(isset($_POST['id']))
			$id=$_POST['id'];
		$unico=$this->base->unico('usuarios','usuario',$str,$id);
		if (!$unico)
		{
			$this->form_validation->set_message('usuario_unico', 'El nombre de usuario no se encuentra disponible.');
			return FALSE;
		}
		return TRUE;
	}
	
	function permisos($grupos_id=NULL)
	{
		if($this->session->userdata('grupos_id')!=1)
			$this->base->verifica('permisos');
		if(!empty($_POST)){
			$grupos_id = $this->input->post('grupos_id');
			$permisos = $this->input->post('funciones_id');
			$this->base->permisos_set($grupos_id,$permisos);
			$this->session->set_flashdata('done', 'Cambios guardados correctamente.');
			redirect('/main/permisos/'.$grupos_id);		
		}
		$datos['grupos_id'] = $grupos_id;
		$datos['grupos']=$this->base->lista('grupos','id','nombre');
		$datos['titulo']='Permisos';
		$this->load->view('main/permisos',$datos);
	}
	
	function permisos_view($grupos_id)
	{
		if($this->session->userdata('grupos_id')!=1)
			$this->base->verifica('permisos');
		$datos['grupos_id'] = $grupos_id;
		$datos['funciones'] = $this->base->funciones();
		$datos['permisos'] = $this->base->permisos($grupos_id);
		$datos['categorias'] = $this->base->lista('categorias','id','nombre', FALSE,'nombre', 'ASC');
		$this->load->view('main/permisos_view',$datos);
	}
	
	function catalogos()
	{
		$this->base->verifica('catalogos');
		$b=bc_buscador();		
		$datos['r']=$this->base->catalogos($b['cond'],$this->config->item('por_pagina'),$b['offset']);	
		$paginador = $this->config->item('paginador_config');
		$paginador['base_url'] = site_url($b['base_url']);
		$paginador['total_rows'] = $datos['total'] = $this->base->catalogos_count($b['cond']);
		$paginador['uri_segment']=$b['uri_segment'];				
		$this->load->library('pagination');
		$this->pagination->initialize($paginador);
		$datos['cond']=$b['cond'];
		$datos['paginador'] = $this->pagination->create_links();		
		$datos['titulo']='Cat&aacute;logos';		
		$this->load->view('main/catalogos',$datos);
	}
	
	function catalogos_agregar()
	{
		$this->base->verifica('catalogos');
		if(!empty($_POST))
		{
			if ($this->form_validation->run('catalogos'))
			{
				$data=array(
					'nombre'=>$this->input->post('nombre'),
					'descripcion'=>$this->input->post('descripcion')
				);
				$this->base->guarda('catalogos',$data);
				$this->session->set_flashdata('done', 'El registro fue creado correctamente.');
				redirect('main/catalogos');
			}
			else
			{
				$datos['flashdata']['error']='Por favor verifique los datos.';
			}
		}
		$datos['titulo']='Agregar cat&aacute;logo';
		$this->load->view('main/catalogos_agregar',$datos);
	}
	
	function catalogos_editar($id)
	{
		$this->base->verifica('catalogos');
		if(!empty($_POST))
		{
			if ($this->form_validation->run('catalogos'))
			{
				$data=array(
					'id'=>$this->input->post('id'),
					'nombre'=>$this->input->post('nombre'),
					'descripcion'=>$this->input->post('descripcion')
				);
				$this->base->guarda('catalogos',$data);
				$this->session->set_flashdata('done', 'El registro fue guardado correctamente.');
				redirect('main/catalogos');
			}
			else
			{
				$datos['flashdata']['error']='Por favor verifique los datos.';
			}
		}
		else
		{
			$datos['r']=$this->base->read('catalogos',$id);
		}		
		$datos['titulo']='Editar cat&aacute;logo';
		$this->load->view('main/catalogos_editar',$datos);
	}
	
	function elementos()
	{
		$this->base->verifica('elementos');
		$b=bc_buscador();		
		$datos['r']=$this->base->elementos($b['cond'],$this->config->item('por_pagina'),$b['offset']);	
		$paginador = $this->config->item('paginador_config');
		$paginador['base_url'] = site_url($b['base_url']);
		$paginador['total_rows'] = $datos['total'] = $this->base->elementos_count($b['cond']);
		$paginador['uri_segment']=$b['uri_segment'];				
		$this->load->library('pagination');
		$this->pagination->initialize($paginador);
		$datos['cond']=$b['cond'];
		$datos['paginador'] = $this->pagination->create_links();
		$datos['catalogos']=$this->base->lista('catalogos','id','descripcion');		
		$datos['titulo']='Elementos de los cat&aacute;logos';		
		$this->load->view('main/elementos',$datos);
	}
	
	function elementos_activar($id,$val)
	{
		$this->base->verifica('elementos');
		$this->base->toggle('elementos','activo',$id,$val);
		$this->session->set_flashdata('done', 'Cambios realizados correctamente.');
		redirect('main/elementos');	
	}
	
	function elementos_agregar()
	{
		$this->base->verifica('elementos');
		if(!empty($_POST))
		{
			if ($this->form_validation->run('elementos'))
			{
				$data=array(
					'catalogos_id'=>$this->input->post('catalogos_id'),
					'clave'=>$this->input->post('clave'),
					'valor'=>$this->input->post('valor'),
				);
				$this->base->guarda('elementos',$data);
				$this->session->set_flashdata('done', 'El registro fue creado correctamente.');
				redirect('main/elementos');
			}
			else
			{
				$datos['flashdata']['error']='Por favor verifique los datos.';
			}
		}
		$datos['catalogos']=$this->base->lista('catalogos','id','descripcion');
		$datos['titulo']='Agregar elemento de cat&aacute;logo';
		$this->load->view('main/elementos_agregar',$datos);
	}
	
	function elementos_editar($id)
	{
		$this->base->verifica('elementos');
		if(!empty($_POST))
		{
			if ($this->form_validation->run('elementos'))
			{
				$data=array(
					'id'=>$this->input->post('id'),
					'catalogos_id'=>$this->input->post('catalogos_id'),
					'clave'=>$this->input->post('clave'),
					'valor'=>$this->input->post('valor'),
					'activo'=>$this->input->post('activo'),
				);
				$this->base->guarda('elementos',$data);
				$this->session->set_flashdata('done', 'El registro fue guardado correctamente.');
				redirect('main/elementos');
			}
			else
			{
				$datos['flashdata']['error']='Por favor verifique los datos.';
			}
		}
		else
		{
			$datos['r']=$this->base->read('elementos',$id);
		}		
		$datos['catalogos']=$this->base->lista('catalogos','id','descripcion');
		$datos['titulo']='Editar elemento de cat&aacute;logo';
		$this->load->view('main/elementos_editar',$datos);
	}
	
	function mostrar_detalles()
	{
		$this->session->set_userdata('mostrar_detalles', !$this->session->userdata('mostrar_detalles'));
		$this->load->library('user_agent');
		redirect($this->agent->referrer());
	}
	
	function busqueda_avanzada()
	{
		$this->session->set_userdata('busqueda_avanzada', !$this->session->userdata('busqueda_avanzada'));
		$this->load->library('user_agent');
		redirect($this->agent->referrer());
	}
	
	function refresh()
	{
		$this->load->library('user_agent');
		redirect($this->agent->referrer());
	}
	
	function mi_cuenta()
	{
		if(!empty($_POST))
		{
		if($this->form_validation->run('mi_cuenta'))
			{
				$data=array(
					'id'=>$this->session->userdata('id'),
					'nombre'=>mayuscula($this->input->post('nombre')),
					'apellido_paterno'=>mayuscula($this->input->post('apellido_paterno')),
					'apellido_materno'=>mayuscula($this->input->post('apellido_materno')),
					'email'=>$this->input->post('email'),
					'telefono'=>$this->input->post('telefono'),
					'celular'=>$this->input->post('celular'),
					'ayuda'=>$this->input->post('ayuda'),
				);
				if(isset($_POST['contrasena']))
				{
					$data['contrasena']=MD5($this->input->post('contrasena').$this->config->item('encryption_key'));
				}	
				//CONTRASEÑA					
				$this->base->guarda('usuarios',$data);
				$this->session->set_flashdata('done', 'Los datos fueron guardados correctamente.');
				redirect('main/mi_cuenta');
			}
			else
			{
				$datos['flashdata']['error']='Por favor verifique los datos.';
			}	
		}
		else
		{
			$datos['r']=$this->base->read('usuarios',$this->session->userdata('id'));
		}	
		$datos['titulo']='Mi Cuenta';
		$this->load->view('main/mi_cuenta',$datos);
	}
	
	function logs()
	{
		$this->base->verifica('logs');
		$b=bc_buscador();	
		$this->base->like=array('mensaje','datos');
		$this->base->table='logs';
		$datos['r']=$this->base->logs_find($b['cond'],$this->config->item('por_pagina'),$b['offset']);	
		$paginador = $this->config->item('paginador_config');
		$paginador['uri_segment']=$b['uri_segment'];
		$paginador['base_url'] = site_url($b['base_url']);
		$paginador['total_rows'] = $datos['total'] = $this->base->logs_count($b['cond']);		
		$this->load->library('pagination');
		$this->pagination->initialize($paginador);
		$datos['cond']=$b['cond'];
		$datos['paginador'] = $this->pagination->create_links();		
		$datos['titulo']='Logs';
		$datos['tipos']=$this->base->tipos_log;
		$datos['usuarios']=$this->base->lista('usuarios','id','usuario',FALSE);				
		$this->load->view('main/logs',$datos);
	}
	
	function cuentas()
	{
		$this->base->verifica('cuentas');
		$b=bc_buscador();
		$this->base->table='cuentas';	
		$this->base->like=array('nombre','razon_social');
		$datos['r']=$this->base->find_cuentas($b['cond'],$this->config->item('por_pagina'),$b['offset']);	
		$paginador = $this->config->item('paginador_config');
		$paginador['base_url'] = site_url($b['base_url']);
		$paginador['total_rows'] = $datos['total'] = $this->base->cuentas_count($b['cond']);
		$paginador['uri_segment'] = $b['uri_segment'];
		$this->load->library('pagination');
		$this->pagination->initialize($paginador);
		$datos['cond']=$b['cond'];
		$datos['paginador'] = $this->pagination->create_links();		
		$datos['titulo']='Cuentas';
		$this->load->view('main/cuentas',$datos);
	}
	
	
	function cuentas_agregar()
	{
		$this->base->verifica('cuentas');
		$this->load->Model('Sepomex_model','SP');
		if(!empty($_POST))
		{
			$_POST['rfc'] = rfc_formato(@$_POST['rfc']);
			if ($this->form_validation->run('cuentas'))
			{
				$data=array(
					'nombre'=>$this->input->post('nombre'),
					'tipo_persona_id'=>$this->input->post('tipo_persona_id'),
					'razon_social'=>$this->input->post('razon_social'),
					'clave'=>$this->input->post('clave'),
					'rfc'=>$this->input->post('rfc'),
					'codigo_postal'=>$this->input->post('codigo_postal'),
					'asentamiento'=>$this->input->post('asentamiento'),
					'municipio'=>$this->input->post('municipio'),
					'estado'=>$this->input->post('estado'),
					'calle'=>$this->input->post('calle'),
					'numero_exterior'=>$this->input->post('numero_exterior'),
					'numero_interior'=>$this->input->post('numero_interior'),
					'telefono'=>$this->input->post('telefono'),
					'activo'=>$this->input->post('activo'),
					'email'=>$this->input->post('email'),
					'cuenta_clabe'=>$this->input->post('cuenta_clabe'),
					'cuenta_bancaria'=>$this->input->post('cuenta_bancaria'),
					'sucursal'=>$this->input->post('sucursal'),
					'sucursal_fisica'=>$this->input->post('sucursal_fisica'),
				    'descuento_espacio'=>$this->input->post('descuento_espacio'),
				    'descuento_monto'=>$this->input->post('descuento_monto'),
				    'descuento_cooperacion'=>$this->input->post('descuento_cooperacion'),
				    'descuento_transicion'=>$this->input->post('descuento_transicion'),
				    'credito'=>$this->input->post('credito'),
				    'distribuidor'=>$this->input->post('distribuidor'),
					'venta_directa'=>$this->input->post('venta_directa'),
					'sucursal_estado'=>$this->input->post('sucursal_estado'),
					'sucursal_municipio'=>$this->input->post('sucursal_municipio'),
					'sucursal_asentamiento'=>$this->input->post('sucursal_asentamiento'),
					'sucursal_calle'=>$this->input->post('sucursal_calle'),
					'sucursal_codigo_postal'=>$this->input->post('sucursal_codigo_postal'),
					'sucursal_numero_exterior'=>$this->input->post('sucursal_numero_exterior'),
					'sucursal_numero_interior'=>$this->input->post('sucursal_numero_interior'),
				);
				
				$id=$this->base->guarda('cuentas',$data);
				$categorias = $_POST['categorias_exhibicion'];
				$paquetes = $_POST['paquetes_exhibicion'];
				$this->db->where('cuentas_id', $id);
				$this->db->delete('cuentas_paquetes');
				$this->db->where('cuentas_id', $id);
				$this->db->delete('cuentas_categorias');
				foreach($categorias as $categorias_id) {
				    $data = array(
				        'cuentas_id' => $id,
				        'categorias_id' => $categorias_id
				    );
				    $this->base->guarda('cuentas_categorias', $data);
				}
				foreach($paquetes as $paquetes_id) {
				    $data = array(
				        'cuentas_id' => $id,
				        'paquetes_id' => $paquetes_id
				    );
				    $this->base->guarda('cuentas_paquetes', $data);
				}
				
				if($this->config->item('cloudfiles'))
				{
					$r = $this->base->read('cuentas',$id);
					$orden_data = array('id' => $r->id);
					
					if($_FILES['distribuidor_logo']['size']>0)
					{
						$r->distribuidor_logo_orden++;
						$orden=$r->distribuidor_logo_orden?'_'.$r->distribuidor_logo_orden:'';
						$this->base->guarda_imagen($_FILES['distribuidor_logo']['tmp_name'],"files/cuentas/{$id}","distribuidor_logo{$orden}",NULL,APPPATH);
						$orden_data['distribuidor_logo_orden'] = $r->distribuidor_logo_orden;
					}
					
					$this->base->guarda('cuentas', $orden_data);
				}
				else
				{
					if($_FILES['distribuidor_logo']['size']>0)
						$this->base->guarda_imagen($_FILES['distribuidor_logo']['tmp_name'],"files/cuentas/{$id}","distribuidor_logo",NULL,NULL);
				}
				
				$this->session->set_flashdata('done', 'El registro fue creado correctamente.');
				redirect('main/cuentas');
			}
			else
			{
				$datos['flashdata']['error']='Por favor verifique los datos.';
			}
			
			$datos['municipios']=$this->SP->get_municipios($_POST['estado']);
			$datos['sucursal_municipios']=$this->SP->get_municipios($_POST['sucursal_estado']);
		}
		$datos['categorias']=$this->base->lista('productos_categorias','id', array('descuento_exhibicion', 'nombre'));
		foreach($datos['categorias'] as &$c) {
		    $c = preg_replace('/\s/', ' - ', $c, 1);
		}
		$datos['paquetes']=$this->base->lista('paquetes','id', array('descuento_exhibicion', 'nombre'));
		foreach($datos['paquetes'] as &$p) {
		    $p = preg_replace('/\s/', ' - ', $p, 1);
		}			
		$datos['files']=APPPATH;				
		$datos['estados']=$this->SP->get_estados();
		$datos['titulo']='Agregar Cuenta';
		$this->load->view('main/cuentas_agregar',$datos);
	}
	
	
	function cuentas_editar($id)
	{
		$this->base->verifica('cuentas');
		$this->load->Model('Sepomex_model','SP');
		if(!empty($_POST))
		{
			$_POST['rfc'] = rfc_formato(@$_POST['rfc']);
			if ($this->form_validation->run('cuentas'))
			{
				$data=array(
					'id'=>$this->input->post('id'),
					'nombre'=>$this->input->post('nombre'),
					'tipo_persona_id'=>$this->input->post('tipo_persona_id'),
					'razon_social'=>$this->input->post('razon_social'),
					'clave'=>$this->input->post('clave'),
					'rfc'=>$this->input->post('rfc'),
					'codigo_postal'=>$this->input->post('codigo_postal'),
					'asentamiento'=>$this->input->post('asentamiento'),
					'municipio'=>$this->input->post('municipio'),
					'estado'=>$this->input->post('estado'),
					'calle'=>$this->input->post('calle'),
					'numero_exterior'=>$this->input->post('numero_exterior'),
					'numero_interior'=>$this->input->post('numero_interior'),
					'telefono'=>$this->input->post('telefono'),
					'activo'=>$this->input->post('activo'),
					'email'=>$this->input->post('email'),
					'cuenta_clabe'=>$this->input->post('cuenta_clabe'),
					'cuenta_bancaria'=>$this->input->post('cuenta_bancaria'),
					'sucursal'=>$this->input->post('sucursal'),
					'sucursal_fisica'=>$this->input->post('sucursal_fisica'),
				    'descuento_espacio'=>$this->input->post('descuento_espacio'),
				    'descuento_monto'=>$this->input->post('descuento_monto'),
				    'descuento_cooperacion'=>$this->input->post('descuento_cooperacion'),
				    'descuento_transicion'=>$this->input->post('descuento_transicion'),
				    'credito'=>$this->input->post('credito'),
				    'distribuidor'=>$this->input->post('distribuidor'),
					'venta_directa'=>$this->input->post('venta_directa'),
					'sucursal_estado'=>$this->input->post('sucursal_estado'),
					'sucursal_municipio'=>$this->input->post('sucursal_municipio'),
					'sucursal_asentamiento'=>$this->input->post('sucursal_asentamiento'),
					'sucursal_calle'=>$this->input->post('sucursal_calle'),
					'sucursal_codigo_postal'=>$this->input->post('sucursal_codigo_postal'),
					'sucursal_numero_exterior'=>$this->input->post('sucursal_numero_exterior'),
					'sucursal_numero_interior'=>$this->input->post('sucursal_numero_interior'),
				);
				$id=$this->base->guarda('cuentas',$data);
			    $categorias = @$_POST['categorias_exhibicion'];
				$paquetes = @$_POST['paquetes_exhibicion'];
				$this->db->where('cuentas_id', $id);
				$this->db->delete('cuentas_paquetes');
				$this->db->where('cuentas_id', $id);
				$this->db->delete('cuentas_categorias');
				
				if($categorias)
				{
					foreach($categorias as $categorias_id)
					{
					    $data = array(
					        'cuentas_id' => $id,
					        'categorias_id' => $categorias_id
					    );
					    $this->base->guarda('cuentas_categorias', $data);
					}
				}
				
				if($paquetes)
				{
					foreach($paquetes as $paquetes_id)
					{
					    $data = array(
					        'cuentas_id' => $id,
					        'paquetes_id' => $paquetes_id
					    );
					    $this->base->guarda('cuentas_paquetes', $data);
					}
				}
				
				if($this->config->item('cloudfiles'))
				{
					$r = $this->base->read('cuentas',$id);
					$orden_data = array('id' => $r->id);
					
					if($_FILES['distribuidor_logo']['size']>0)
					{
						$r->distribuidor_logo_orden++;
						$orden=$r->distribuidor_logo_orden?'_'.$r->distribuidor_logo_orden:'';
						$this->base->guarda_imagen($_FILES['distribuidor_logo']['tmp_name'],"files/cuentas/{$id}","distribuidor_logo{$orden}",NULL,APPPATH);
						$orden_data['distribuidor_logo_orden'] = $r->distribuidor_logo_orden;
					}
					
					$this->base->guarda('cuentas', $orden_data);
				}
				else
				{
					if($_FILES['distribuidor_logo']['size']>0)
						$this->base->guarda_imagen($_FILES['distribuidor_logo']['tmp_name'],"files/cuentas/{$id}","distribuidor_logo",NULL,NULL);
				}
				$this->session->set_flashdata('done', 'El registro fue guardado correctamente.');
				redirect('main/cuentas');
			}
			else
			{
				$datos['flashdata']['error']='Por favor verifique los datos.';
				$datos['municipios']=$this->SP->get_municipios($_POST['estado']);
				$datos['sucursal_municipios']=$this->SP->get_municipios($_POST['sucursal_estado']);
			}
		}

		$datos['file']=APPPATH;
		$datos['r']=$this->base->read('cuentas',$id);

		$estado = $datos['r']->estado;
		if(!empty($_POST['estado']))
			$estado = $_POST['estado'];

		$sucursal_estado = $datos['r']->sucursal_estado;
		if(!empty($_POST['sucursal_estado']))
			$sucursal_estado = $_POST['sucursal_estado'];

		$datos['municipios']=$this->SP->get_municipios($estado);
		$datos['sucursal_municipios']=$this->SP->get_municipios($sucursal_estado);
		
		$datos['categorias']=$this->base->lista('productos_categorias','id', array('descuento_exhibicion', 'nombre'));
		foreach($datos['categorias'] as &$c) {
		    $c = preg_replace('/\s/', ' - ', $c, 1);
		}

		$datos['paquetes']=$this->base->lista('paquetes','id', array('descuento_exhibicion', 'nombre'));
		foreach($datos['paquetes'] as &$p) {
		    $p = preg_replace('/\s/', ' - ', $p, 1);
		}

		$this->db->where('cuentas_id',$id);
		$datos['paquetes_exhibicion']=$this->base->lista('cuentas_paquetes','id', array('paquetes_id'));
		$this->db->where('cuentas_id',$id);
		$datos['categorias_exhibicion']=$this->base->lista('cuentas_categorias','id', array('categorias_id'));
		$datos['folios']=$this->config->item('facturacion_electronica');
		$datos['estados']=$this->SP->get_estados();
		$datos['titulo']='Editar cuenta';
		$this->load->view('main/cuentas_editar',$datos);
	}
	
	function cuentas_activar($id,$val)
	{
		$this->base->verifica('cuentas');
		$this->base->toggle('cuentas','activo',$id,$val);
		$this->session->set_flashdata('done', 'Cambios realizados correctamente.');
		redirect('main/cuentas');	
	}
	
	function cuentas_suspender($id,$suspendido)
    {
    	$this->base->verifica('cuentas');
    	$data['id']=$id;
    	$data['suspendido_pago']=!$suspendido;
    	$this->base->guarda('cuentas',$data);
    	$this->session->set_flashdata('done', 'Cambios realizados correctamente.');
		redirect('main/cuentas');
    }
    
    function get_datos_fiscales($cuentas_id)
    {
    	if($this->base->verifica())
    	{
    		$datos=$this->base->get_datos_fiscales($cuentas_id);
    		$msg=json_encode($datos);
    		$this->output->set_output($msg);
    	}
    }
	
	function mostrar_menu($mostrar_menu)
	{
		$this->session->set_userdata('mostrar_menu', $mostrar_menu);
	}
	
	function sugerencias()
	{
    	if(!empty($_POST))
		{
			$datos=$_POST;
			$usuario_id = $this->session->userdata('id');
			$usuario=$this->base->read('usuarios',$usuario_id);
			$datos['usuario']=$usuario;
			$datos['fecha']=date('Y-m-d h:i:s');
			$mensaje=$this->load->view('email/sugerencias',$datos,TRUE);
			$this->load->library('email');
			$email_sax=$this->config->item('email');		
			$this->email->from($usuario->email);
			$this->email->to($email_sax[0],$email_sax[1]);
			$bcc = $this->config->item('mail_bcc');
			if(!empty($bcc))
				$this->email->bcc($bcc);
			$this->email->subject('Sugerencia de usuarios: '.$_POST['titulo']);
			$this->email->message($mensaje);
			$this->email->send();
			$this->session->set_flashdata('done', 'Muchas gracias! hemos recibido su sugerencia correctamente.');
			if($this->config->item('debug_mail'))
				debug($this->email->print_debugger());
			redirect('main/index');
		}else{
		$datos['titulo']='Enviar sugerencia';
		$this->load->view('main/sugerencias',$datos);
		}
	}

	function bootstrap()
	{
		$datos['titulo']='Boostrap';
		$this->load->view('main/bootstrap_prueba',$datos);
	}
	
	public function greater_equal_than($str, $min)
	{
	    if ( ! is_numeric($str))
	    {
	        $this->form_validation->set_message('greater_equal_than', "El campo %s debe ser numerico");
	        return FALSE;
	    }
	    $res = $str >= $min;
	    if(!$res) {
	        $this->form_validation->set_message('greater_equal_than', "El campo %s debe ser mayor ó igual a {$min}");
	    }
	    return $res;
	}
	
	public function less_equal_than($str, $max)
	{
	    if ( ! is_numeric($str))
	    {
	        $this->form_validation->set_message('less_equal_than', "El campo %s debe ser numerico");
	        return FALSE;
	    }
	    $res = $str <= $max;
	    if(!$res) {
	        $this->form_validation->set_message('less_equal_than', "El campo %s debe ser menor ó igual a {$max}");
	    }
	    return $res;
	}

	public function encrypt_string($string)
	{
		$algorithm = MCRYPT_BLOWFISH;
		$key = $this->config->item('clave_codificacion');
		$mode = MCRYPT_MODE_CBC;
		$iv = base64_decode($this->config->item('iv'));

		$encrypt_string = mcrypt_encrypt($algorithm, $key, $string, $mode, $iv);
		$encode_url_string = urlencode($encrypt_string);

		return $encode_url_string;
	}

	public function decrypt_string($string)
	{
		$decode_url_string = urldecode($string);

		$algorithm = MCRYPT_BLOWFISH;
		$key = $this->config->item('clave_codificacion');
		$mode = MCRYPT_MODE_CBC;
		$iv = base64_decode($this->config->item('iv'));

		$decrypt_string = mcrypt_decrypt($algorithm, $key, $decode_url_string, $mode, $iv);

		return $decrypt_string;
	}

}
/* End of file main.php */
/* Location: ./system/application/controllers/main.php */