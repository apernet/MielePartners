<?php
$config=array(
	'login'=>array(
			array(
				'field'=>'usuario',
				'label'=>'',
				'rules'=>'trim|required'
			),
			array(
				'field'=>'contrasena',
				'label'=>'contrase&ntilde;a',
				'rules'=>'trim|required'
			)
	),	
	'grupos' => array(
		array(
			'field' => 'id',
			'label' => '',
			'rules' => 'trim'
		),
		array(
			'field' => 'nombre',
			'label' => 'nombre',
			'rules' => 'trim|required'
		),
	),
	'usuario' => array(
		array(
			'field' => 'id',
			'label' => '',
			'rules' => 'trim'
		),
		array(
			'field' => 'grupos_id',
			'label' => 'grupo',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'usuario',
			'label' => 'nombre de usuario',
			'rules' => 'trim|required|max_length[40]|callback_usuario_unico'//alphanumeric_dot
		),
		array(
			'field' => 'contrasena',
			'label' => 'contrase&ntilde;a',
			'rules' => 'trim'
		),
		array(
			'field' => 'cuentas_id',
			'label' => 'cuenta',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'nombre',
			'label' => 'nombre',
			'rules' => 'trim|required|max_length[100]'
		),
		array(
			'field' => 'apellido_paterno',
			'label' => 'apellido paterno',
			'rules' => 'trim|required|max_length[100]'
		),
		array(
			'field' => 'apellido_materno',
			'label' => 'apellido materno',
			'rules' => 'trim|required|max_length[100]'
		),
		array(
			'field' => 'email',
			'label' => 'correo electr&oacute;nico',
			'rules' => 'trim|required|valid_email|max_length[100]'
		),
		array(
			'field' => 'telefono',
			'label' => 'tel&eacute;fono',
			'rules' => 'trim'
		),
		array(
			'field' => 'celular',
			'label' => 'celular',
			'rules' => 'trim'
		),
		array(
			'field' => 'admin',
			'label' => 'Administrador',
			'rules' => 'trim'
		),
		array(
			'field' => 'vendedor',
			'label' => 'Vendedor',
			'rules' => 'trim'
		),
		array(
			'field' => 'usuarios_cuentas[]',
			'label' => 'cuentas que administra',
			'rules' => 'trim'
		),
		array(
			'field' => 'activo',
			'label' => 'activo',
			'rules' => 'trim'
		),
		array(
			'field' => 'ayuda',
			'label' => 'ayuda',
			'rules' => 'trim'
		),
		array(
			'field' => 'cliente_externo',
			'label' => 'cliente externo',
			'rules' => 'trim'
		),
		array(
			'field'=>'tipo_persona_id',
			'label'=>'Facturaci&oacute;n para',
			'rules'=>'trim'//|required_if[cliente_externo,1,cliente externo,seleccionado]
		),
		array(
			'field'=>'razon_social',
			'label'=>'raz&oacute;n social',
			'rules'=>'trim'//|required_if[cliente_externo,1,cliente externo,seleccionado]'
		),
		array(
			'field'=>'rfc',
			'label'=>'RFC',
			'rules'=>'trim'//|required_if[cliente_externo,1,cliente externo,seleccionado]'
		),
		array(
			'field'=>'estado',
			'label'=>'estado',
			'rules'=>'trim'//|required_if[cliente_externo,1,cliente externo,seleccionado]'
		),
		array(
			'field'=>'municipio',
			'label'=>'delegaci&oacute;n o municipio',
			'rules'=>'trim'//|required_if[cliente_externo,1,cliente externo,seleccionado]'
		),
		array(
			'field'=>'codigo_postal',
			'label'=>'c&oacute;digo postal',
			'rules'=>'trim'//|required_if[cliente_externo,1,cliente externo,seleccionado]'
		),
		array(
			'field'=>'asentamiento',
			'label'=>'colonia',
			'rules'=>'trim'//|required_if[cliente_externo,1,cliente externo,seleccionado]'
		),
		array(
			'field'=>'calle',
			'label'=>'calle',
			'rules'=>'trim'//|required_if[cliente_externo,1,cliente externo,seleccionado]'
		),
		array(
			'field'=>'numero_exterior',
			'label'=>'n&uacute;mero exterior',
			'rules'=>'trim'//|required_if[cliente_externo,1,cliente externo,seleccionado]'
		),
		array(
			'field'=>'numero_interior',
			'label'=>'n&uacute;mero interior',
			'rules'=>'trim'
		),
		array(
			'field'=>'entrega_estado',
			'label'=>'estado',
			'rules'=>'trim'
		),
		array(
			'field'=>'entrega_municipio',
			'label'=>'delegaci&oacute;n o municipio',
			'rules'=>'trim'
		),
		array(
			'field'=>'entrega_codigo_postal',
			'label'=>'c&oacute;digo postal',
			'rules'=>'trim'
		),
		array(
			'field'=>'entrega_asentamiento',
			'label'=>'colonia',
			'rules'=>'trim'
		),
		array(
			'field'=>'entrega_calle',
			'label'=>'calle',
			'rules'=>'trim'
		),
		array(
			'field'=>'entrega_numero_exterior',
			'label'=>'n&uacute;mero exterior',
			'rules'=>'trim'
		),
		array(
			'field'=>'entrega_numero_interior',
			'label'=>'n&uacute;mero interior',
			'rules'=>'trim'
		),
		array(
			'field'=>'instalacion_estado',
			'label'=>'estado',
			'rules'=>'trim'
		),
		array(
			'field'=>'instalacion_municipio',
			'label'=>'delegaci&oacute;n o municipio',
			'rules'=>'trim'
		),
		array(
			'field'=>'instalacion_codigo_postal',
			'label'=>'c&oacute;digo postal',
			'rules'=>'trim'
		),
		array(
			'field'=>'instalacion_asentamiento',
			'label'=>'colonia',
			'rules'=>'trim'
		),
		array(
			'field'=>'instalacion_calle',
			'label'=>'calle',
			'rules'=>'trim'
		),
		array(
			'field'=>'instalacion_numero_exterior',
			'label'=>'n&uacute;mero exterior',
			'rules'=>'trim'
		),
		array(
			'field'=>'instalacion_numero_interior',
			'label'=>'n&uacute;mero interior',
			'rules'=>'trim'
		),
		array(
			'field'=>'instalacion_contacto_nombre',
			'label'=>'nombre de contacto',
			'rules'=>'trim'
		),
		array(
			'field'=>'instalacion_telefono_particular',
			'label'=>'tel&eacute;fono particular',
			'rules'=>'trim'
		),
		array(
			'field'=>'instalacion_telefono_celular',
			'label'=>'tel&eacute;fono celular',
			'rules'=>'trim'
		)
	),
	'catalogos' => array(
		array(
			'field' => 'id',
			'label' => '',
			'rules' => 'trim'
		),
		array(
			'field' => 'nombre',
			'label' => 'nombre',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'descripcion',
			'label' => 'descripci&oacute;n',
			'rules' => 'trim|required'
		)
	),
	'elementos' => array(
		array(
			'field' => 'id',
			'label' => '',
			'rules' => 'trim'
		),
		array(
			'field' => 'catalogos_id',
			'label' => 'cat&aacute;logo',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'clave',
			'label' => 'clave',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'valor',
			'label' => 'valor',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'activo',
			'label' => 'activo',
			'rules' => 'trim'
		),
		
	),	
	'recuperar_contrasena'=>array(
		array(
			'field' => 'email',
			'label' => 'correo electr&oacute;nico',
			'rules' => 'trim|required|valid_email'
		),
		array(
			'field' => 'usuario',
			'label' => 'usuario',
			'rules' => 'trim|required'
		),
	),
	'mi_cuenta' => array(
		array(
			'field' => 'usuario',
			'label' => 'nombre de usuario',
			'rules' => 'trim'
		),
		array(
			'field' => 'contrasena',
			'label' => 'contrase&ntilde;a',
			'rules' => 'trim'
		),		
		array(
			'field' => 'nombre',
			'label' => 'nombre',
			'rules' => 'trim|required|max_length[100]'
		),
		array(
			'field' => 'apellido_paterno',
			'label' => 'apellido paterno',
			'rules' => 'trim|required|max_length[100]'
		),
		array(
			'field' => 'apellido_materno',
			'label' => 'apellido materno',
			'rules' => 'trim|required|max_length[100]'
		),
		array(
			'field' => 'email',
			'label' => 'correo electr&oacute;nico',
			'rules' => 'trim|required|valid_email|max_length[100]'
		),
		array(
			'field' => 'telefono',
			'label' => 'tel&eacute;fono',
			'rules' => 'trim'
		),
		array(
			'field' => 'celular',
			'label' => 'celular',
			'rules' => 'trim'
		),
		array(
			'field' => 'ayuda',
			'label' => 'ayuda',
			'rules' => 'trim'
		),		
	),	
	'cuentas'=>array(
		array(
			'field'=>'id',
			'label'=>'id',
			'rules'=>'trim'
		),
		array(
			'field'=>'nombre',
			'label'=>'nombre',
			'rules'=>'trim|required'
		),
		array(
			'field'=>'tipo_persona_id',
			'label'=>'Facturaci&oacute;n para',
			'rules'=>'trim|required'
		),
		array(
			'field'=>'razon_social',
			'label'=>'raz&oacute;n social',
			'rules'=>'trim|required'
		),
		array(
			'field'=>'rfc',
			'label'=>'RFC',
			'rules'=>'trim|validarRFC'
		),
		array(
			'field'=>'estado',
			'label'=>'estado',
			'rules'=>'trim|required'
		),
		array(
			'field'=>'municipio',
			'label'=>'delegaci&oacute;n o municipio',
			'rules'=>'trim|required'
		),
		array(
			'field'=>'codigo_postal',
			'label'=>'c&oacute;digo postal',
			'rules'=>'trim|required'
		),
		array(
			'field'=>'asentamiento',
			'label'=>'colonia',
			'rules'=>'trim|required'
		),
		array(
			'field'=>'calle',
			'label'=>'calle',
			'rules'=>'trim|required'
		),
		array(
			'field'=>'numero_exterior',
			'label'=>'n&uacute;mero exterior',
			'rules'=>'trim|required'
		),
		array(
			'field'=>'numero_interior',
			'label'=>'n&uacute;mero interior',
			'rules'=>'trim'
		),
		array(
			'field'=>'email',
			'label'=>'email',
			'rules'=>'trim|valid_email'
		),
		array(
			'field'=>'telefono',
			'label'=>'tel&eacute;fono',
			'rules'=>'trim'
		),
		array(
			'field'=>'activo',
			'label'=>'activo',
			'rules'=>'trim'
		),
		array(
			'field'=>'clave',
			'label'=>'clave',
			'rules'=>'trim|required'
		),
		array(
			'field'=>'sucursal_fisica',
			'label'=>'sucursal_fisica',
			'rules'=>'trim'
		),
		array(
			'field'=>'cuenta_bancaria',
			'label'=>'cuenta_bancaria',
			'rules'=>'numeric|trim|required'
		),
		array(
			'field'=>'cuenta_clabe',
			'label'=>'cuenta_clabe',
			'rules'=>'trim|required'
		),
		array(
			'field'=>'sucursal',
			'label'=>'sucursal',
			'rules'=>'trim|required'
		),
	    
	    array(
	        'field'=>'descuento_espacio',
	        'label'=>'descuento_espacio',
	        'rules'=>'callback_less_equal_than[100]|callback_greater_equal_than[0]|trim|numeric'
	    ),
	    array(
	        'field'=>'descuento_monto',
	        'label'=>'descuento_monto',
	        'rules'=>'callback_less_equal_than[100]|callback_greater_equal_than[0]|trim|numeric'
	    ),
	    array(
	        'field'=>'descuento_cooperacion',
	        'label'=>'descuento_cooperacion',
	        'rules'=>'callback_less_equal_than[100]|callback_greater_equal_than[0]|trim|numeric'
	    ),
	    array(
	        'field'=>'descuento_transicion',
	        'label'=>'descuento_transicion',
	        'rules'=>'callback_less_equal_than[100]|callback_greater_equal_than[0]|trim|numeric'
	    ),
	    array(
	        'field'=>'credito',
	        'label'=>'credito',
	        'rules'=>'trim|required'
	    ),
	    array(
	        'field'=>'distribuidor',
	        'label'=>'distribuidor',
	        'rules'=>'trim|required'
	    ),
	    array(
	        'field'=>'categorias_exhibicion[]',
	        'label'=>'Categorias exhibicion',
	        'rules'=>'trim'
	    ),
	    array(
	        'field'=>'paquetes_exhibicion[]',
	        'label'=>'Paquetes exhibicion',
	        'rules'=>'trim'
	    ),
		array(
				'field'=>'sucursal_estado',
				'label'=>'estado',
				'rules'=>'trim|required'
		),
		array(
				'field'=>'sucursal_municipio',
				'label'=>'delegaci&oacute;n o municipio',
				'rules'=>'trim|required'
		),
		array(
				'field'=>'sucursal_codigo_postal',
				'label'=>'c&oacute;digo postal',
				'rules'=>'trim|required'
		),
		array(
				'field'=>'sucursal_asentamiento',
				'label'=>'colonia',
				'rules'=>'trim|required'
		),
		array(
				'field'=>'sucursal_calle',
				'label'=>'calle',
				'rules'=>'trim|required'
		),
		array(
				'field'=>'sucursal_numero_exterior',
				'label'=>'n&uacute;mero exterior',
				'rules'=>'trim|required'
		),
		array(
				'field'=>'sucursal_numero_interior',
				'label'=>'n&uacute;mero interior',
				'rules'=>'trim'
		),
	),	
	'noticias' => array(
		array(
			'field' => 'id',
			'label' => '',
			'rules' => 'trim'
		),
		array(
			'field' => 'titulo',
			'label' => 'titulo',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'fecha',
			'label' => 'fecha',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'contenido',
			'label' => 'contenido',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'activo',
			'label' => 'activo',
			'rules' => 'trim'
		),
		array(
			'field' => 'inicio',
			'label' => 'inicio',
			'rules' => 'trim'
		),
	),	
	'productos' => array(
		array(
			'field' => 'id',
			'label' => '',
			'rules' => 'trim'
		),
		array(
			'field' => 'modelo',
			'label' => 'Modelo',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'nombre',
			'label' => 'Nombre',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'categorias_id',
			'label' => 'Categorias',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'descripcion',
			'label' => 'Descripcion',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'item',
			'label' => 'Item',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'activo',
			'label' => 'Activo',
			'rules' => 'trim'
		),
		array(
			'field' => 'precio',
			'label' => 'Precio',
			'rules' => 'numeric|trim|required'
		),
		array(
			'field' => 'unidad_id',
			'label' => 'Unidad',
			'rules' => 'trim|integer|required'
		),
		array(
			'field' => 'sin_envio',
			'label' => 'Sin env&iacute;o',
			'rules' => 'trim'
		),

	),	
	'categorias' => array(
		array(
			'field' => 'id',
			'label' => '',
			'rules' => 'trim'
		),
		array(
			'field' => 'descripcion',
			'label' => 'Descripci&oacute;n',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'parent',
			'label' => 'Categor&iacute;a Padre',
			'rules' => 'trim'
		),
		array(
			'field' => 'descuento_exhibicion',
			'label' => 'Descuento Exhibici&oacute;n',
			'rules' => 'callback_less_equal_than[100]|callback_greater_equal_than[0]|trim|numeric'
		),
		array(
			'field' => 'descuento_base',
			'label' => 'Descuento Base',
			'rules' => 'callback_less_equal_than[100]|callback_greater_equal_than[0]|trim|numeric'
		),
		array(
			'field' => 'nombre',
			'label' => 'Nombre',
			'rules' => 'trim|required'
		),
	    array(
	        'field' => 'clave',
	        'label' => 'Clave',
	        'rules' => 'trim|required'
	    ),
        array(
            'field' => 'informacion_general',
            'label' => 'Informacion General',
            'rules' => 'trim'
        ),
	),		
	'banners' => array(
		array(
			'field' => 'id',
			'label' => '',
			'rules' => 'trim'
		),
		array(
			'field' => 'productos_id',
			'label' => 'Productos',
			'rules' => 'trim'
		),
		array(
			'field' => 'categorias_id',
			'label' => 'Categorias',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'activo',
			'label' => 'Activo',
			'rules' => 'trim'
		),
		array(
			'field' => 'url',
			'label' => 'Url',
			'rules' => 'trim'
		),
	),		
	
    'cotizaciones' => array(
		array(
			'field' => 'id',
			'label' => '',
			'rules' => 'trim'
		),
		array(
			'field' => 'fecha',
			'label' => 'Fecha',
			'rules' => 'trim'
		),
		array(
			'field'=>'nombre_comprador',
			'label'=>'nombre',
			'rules'=>'trim|required'
		),
		array(
			'field'=>'paterno_comprador',
			'label'=>'apellido paterno',
			'rules'=>'trim|required'
		),
		array(
			'field'=>'materno_comprador',
			'label'=>'apellido Materno',
			'rules'=>'trim'
		),
		array(
			'field'=>'observaciones',
			'label'=>'observaciones',
			'rules'=>'trim'
		),
		array(
			'field'=>'email_comprador',
			'label'=>'correo electr&oacute;nico',
			'rules'=>'trim'
		),
		array(
			'field'=>'telefono_comprador',
			'label'=>'tel&eacute;fono',
			'rules'=>'trim|required'
		),
	    array(
	        'field'=>'entrega_estado',
	        'label'=>'estado',
	        'rules'=>'trim|required'
	    ),
		array(
			'field'=>'entrega_municipio',
			'label'=>'municipio',
			'rules'=>'trim'
		),
		array(
			'field'=>'entrega_codigo_postal',
			'label'=>'codigo postal',
			'rules'=>'trim'
		),
		array(
			'field'=>'entrega_asentamiento',
			'label'=>'colonia',
			'rules'=>'trim'
		),
		array(
			'field'=>'entrega_calle',
			'label'=>'calle',
			'rules'=>'trim'
		),
		array(
			'field'=>'entrega_numero_exterior',
			'label'=>'n&uacute;mero exterior',
			'rules'=>'trim'
		),
		array(
			'field'=>'entrega_numero_interior',
			'label'=>'n&uacute;mero interior',
			'rules'=>'trim'
		),
		array(
				'field'=>'usuario_id',
				'label'=>'nombre del vendedor',
				'rules'=>'trim|required'
		),
    	array(
    			'field'=>'instalacion_estado',
    			'label'=>'estado de instalaci&oacute;n',
    			'rules'=>'trim|required'
    	),
    	array(
    		'field'=>'vendedor_nombre',
    		'label'=>'nombre del vendedor',
    		'rules'=>'trim|required'
    		),
    	array(
  			'field'=>'vendedor_paterno',
  			'label'=>'apellido paterno del vendedor',
   			'rules'=>'trim|required'
   		),
   		array(
   			'field'=>'vendedor_materno',
    		'label'=>'apellido_paterno del vendedor',
    		'rules'=>'trim|required'
    	),
		array(
			'field'=>'evento_estado',
			'label'=>'Evento estado',
			'rules'=>'trim'
		),
	),
	'accesorios' => array(
		array(
			'field' => 'id',
			'label' => '',
			'rules' => 'trim'
		),
		array(
			'field' => 'modelo',
			'label' => 'Modelo',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'nombre',
			'label' => 'Nombre',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'descripcion',
			'label' => 'Descripcion',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'item',
			'label' => 'Item',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'precio',
			'label' => 'Precio',
			'rules' => 'numeric|trim|required'
		),
		array(
			'field' => 'tipos_accesorios_id',
			'label' => 'Tipo de accesorio',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'activo',
			'label' => 'Activo',
			'rules' => 'trim'
		),
	),	
	'tipos_accesorios' => array(
		array(
			'field' => 'id',
			'label' => '',
			'rules' => 'trim'
		),
		array(
			'field' => 'nombre',
			'label' => 'Nombre',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'descripcion',
			'label' => 'Descripcion',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'activo',
			'label' => 'Activo',
			'rules' => 'trim'
		),
		array(
			'field' => 'descuento_base',
			'label' => 'Descuento base',
			'rules' => 'trim'
		),
		array(
			'field' => 'descuento_opcional',
			'label' => 'Descuento opcional',
			'rules' => 'trim'
		),
	),	
	'paquetes'	=> array(
		array(
			'field' => 'nombre',
			'label' => 'nombre',
			'rules' => 'trim|required'
		),
		array(
				'field' => 'productos_id',
				'label' => 'producto',
				'rules' => 'trim'
		),
		array(
				'field' => 'descuento',
				'label' => 'descuento',
				'rules' => 'trim|required'
		),
		array(
				'field' => 'descuento_distribuidor',
				'label' => 'descuento distribuidor',
				'rules' => 'trim|required'
		),
		array(
				'field' => 'comision_vendedor',
				'label' => 'comision vendedor',
				'rules' => 'trim|required'
		),
		array(
				'field' => 'descuento_exhibicion',
				'label' => 'descuento por exhibicion',
				'rules' => 'trim'
		),
		array(
				'field' => 'descripcion',
				'label' => 'descripcion',
				'rules' => 'trim|required'
		),
	),
		
	'referidos'	=> array(
			array(
					'field' => 'nombre',
					'label' => 'nombre',
					'rules' => 'trim|required'
			),
			array(
					'field' => 'apellido_paterno',
					'label' => 'apellido paterno',
					'rules' => 'trim|required'
			),
			array(
					'field' => 'apellido_materno',
					'label' => 'apellido materno',
					'rules' => 'trim'
			),
			array(
					'field' => 'email',
					'label' => 'email',
					'rules' => 'trim|valid_email|max_length[100]'
			),
			array(
					'field' => 'distribuidores_id',
					'label' => 'distribuidor',
					'rules' => 'trim|required'
			),
			array(
					'field' => 'vendedor_nombre',
					'label' => 'nombre del vendedor',
					'rules' => 'trim|required'
			),
			array(
					'field' => 'vendedor_paterno',
					'label' => 'apellido paterno del vendedor',
					'rules' => 'trim|required'
			),
			array(
					'field' => 'vendedor_materno',
					'label' => 'apellido materno del vendedor',
					'rules' => 'trim|required'
			),
			array(
				'field' => 'vendedor_email',
				'label' => 'vendedor_email',
				'rules' => 'trim|valid_email|max_length[100]'
			),
			array(
					'field'=>'instalacion_estado',
					'label'=>'estado',
					'rules'=>'trim'
			),
			array(
					'field'=>'instalacion_municipio',
					'label'=>'municipio',
					'rules'=>'trim'
			),
			array(
					'field'=>'instalacion_codigo_postal',
					'label'=>'codigo postal',
					'rules'=>'trim'
			),
			array(
					'field'=>'instalacion_asentamiento',
					'label'=>'colonia',
					'rules'=>'trim'
			),
			array(
					'field'=>'instalacion_calle',
					'label'=>'calle',
					'rules'=>'trim'
			),
			array(
					'field'=>'instalacion_numero_exterior',
					'label'=>'n&uacute;mero exterior',
					'rules'=>'trim'
			),
			array(
					'field'=>'instalacion_numero_interior',
					'label'=>'n&uacute;mero interior',
					'rules'=>'trim'
			),
			array(
					'field' => 'vigencia',
					'label' => 'vigencia',
					'rules' => 'trim'
			),
	),
    'cotizaciones_compra' => array(
        array(
            'field' => 'id',
            'label' => '',
            'rules' => 'trim'
        ),
        array(
            'field' => 'fecha',
            'label' => 'Fecha',
            'rules' => 'trim'
        ),
        array(
            'field'=>'nombre_comprador',
            'label'=>'nombre',
            'rules'=>'trim|required'
        ),
        array(
            'field'=>'paterno_comprador',
            'label'=>'apellido paterno',
            'rules'=>'trim|required'
        ),
        array(
            'field'=>'materno_comprador',
            'label'=>'apellido Materno',
            'rules'=>'trim'
        ),
        array(
            'field'=>'email_comprador',
            'label'=>'correo electr&oacute;nico',
            'rules' => 'trim|required|valid_email|max_length[100]'
        ),
        array(
            'field'=>'telefono_comprador',
            'label'=>'tel&eacute;fono',
            'rules'=>'trim|required'
        ),
       array(
    		'field'=>'fecha_nacimiento_comprador',
    		'label'=>'fecha nacimiento(DD/MM)',
    		'rules' => 'trim'
    	),
        array(
            'field'=>'anio_nacimiento_comprador',
            'label'=>'a&ntilde;io nacimiento(YYYY)',
            'rules' => 'trim'
        ),
        array(
            'field'=>'fecha_aniversario_comprador',
            'label'=>'Fecha de aniversario(DD/MM)',
            'rules' => 'trim'
        ),
        array(
            'field'=>'nombre_contacto',
            'label'=>'Nombre contacto',
            'rules' =>'trim|required'
        ),
    	array(
    		'field'=>'telefono_particular',
    		'label'=>'telefono_particular',
    		'rules' =>'trim|required'
    	),
    	array(
    		'field'=>'telefono_celular',
    		'label'=>'telefono_celular',
    		'rules' =>'trim'
    		),
        array(
            'field'=>'entrega_estado',
            'label'=>'estado',
            'rules'=>'trim|required'
        ),
        array(
            'field'=>'entrega_municipio',
            'label'=>'municipio',
            'rules'=>'trim|required'
        ),
        array(
            'field'=>'entrega_codigo_postal',
            'label'=>'codigo postal',
            'rules'=>'trim|required'
        ),
        array(
            'field'=>'entrega_asentamiento',
            'label'=>'colonia',
            'rules'=>'trim|required'
        ),
        array(
            'field'=>'entrega_calle',
            'label'=>'calle',
            'rules'=>'trim|required'
        ),
        array(
            'field'=>'entrega_numero_exterior',
            'label'=>'n&uacute;mero exterior',
            'rules'=>'trim|required'
        ),
        array(
            'field'=>'entrega_numero_interior',
            'label'=>'n&uacute;mero interior',
            'rules'=>'trim'
        ),
		array(
			'field'=>'entrega_fecha_instalacion',
			'label'=>'fecha tentativa de instalaci&oacute;n',
			'rules'=>'trim|fecha_instalacion'
		),
        array(
            'field'=>'tipo_persona_id',
            'label'=>'facturaci&oacute;n para',
            'rules'=>'trim|required'
        ),
        array(
            'field'=>'razon_social',
            'label'=>'nombre &oacute; raz&oacute;n social',
            'rules'=>'trim|required'
        ),
        array(
            'field'=>'rfc',
            'label'=>'rfc',
            'rules'=>'trim|required'
        ),
        array(
            'field'=>'email',
            'label'=>'correo  el&eacute;ctronico',
            'rules'=>'trim|required'
        ),
        array(
            'field'=>'estado',
            'label'=>'estado',
            'rules'=>'trim|required'
        ),
        array(
            'field'=>'municipio',
            'label'=>'municipio',
            'rules'=>'trim|required'
        ),
        array(
            'field'=>'codigo_postal',
            'label'=>'codigo postal',
            'rules'=>'trim|required'
        ),
        array(
            'field'=>'asentamiento',
            'label'=>'colonia',
            'rules'=>'trim|required'
        ),
        array(
            'field'=>'calle',
            'label'=>'calle',
            'rules'=>'trim|required'
        ),
        array(
            'field'=>'numero_exterior',
            'label'=>'n&uacute;mero exterior',
            'rules'=>'trim|required'
        ),
        array(
            'field'=>'numero_interior',
            'label'=>'n&uacute;mero interior',
            'rules'=>'trim'
        ),
        array(
            'field'=>'usuario_id',
            'label'=>'nombre del vendedor',
            'rules'=>'trim|required'
        ),
        array(
            'field'=>'observaciones',
            'label'=>'observaciones',
            'rules'=>'trim'
        ),
        array(
            'field'=>'acepta_terminos',
            'label'=>'Terminos y condiciones',
            'rules'=>'trim'
        ),
    	array(
    		'field'=>'instalacion_nombre_contacto',
   			'label'=>'Nombre contacto',
   			'rules' =>'trim|required'
    	),
    	array(
   			'field'=>'instalacion_telefono_particular',
   			'label'=>'telefono_particular',
   			'rules' =>'trim'
   		),
    	array(
    		'field'=>'instalacion_telefono_celular',
   			'label'=>'telefono_celular',
   			'rules' =>'trim'
   		),
   		array(
    		'field'=>'instalacion_estado',
    		'label'=>'estado',
    		'rules'=>'trim|required'
    	),
   		array(
   			'field'=>'instalacion_municipio',
   			'label'=>'municipio',
   			'rules'=>'trim|required'
   		),
   		array(
 			'field'=>'instalacion_codigo_postal',
 			'label'=>'codigo postal',
 			'rules'=>'trim|required'
 		),
   		array(
 			'field'=>'instalacion_asentamiento',
 			'label'=>'colonia',
 			'rules'=>'trim|required'
  		),
   		array(
 			'field'=>'instalacion_calle',
 			'label'=>'calle',
 			'rules'=>'trim|required'
   		),
   		array(
 			'field'=>'instalacion_numero_exterior',
 			'label'=>'n&uacute;mero exterior',
 			'rules'=>'trim|required'
   		),
   		array(
 			'field'=>'instalacion_numero_interior',
 			'label'=>'n&uacute;mero interior',
 			'rules'=>'trim'
   		),
    	array(
    		'field'=>'forma_pago_id',
    		'label'=>'Forma de Pago',
    		'rules'=>'trim|required'
    	),
		array(
			'field'=>'evento_estado',
			'label'=>'Evento estado',
			'rules'=>'trim'
		),
    ),
	'registro' => array(
		array(
			'field' => 'id',
			'label' => '',
			'rules' => 'trim'
		),
		array(
			'field' => 'nombre',
			'label' => 'nombre',
			'rules' => 'trim|required|max_length[100]'
		),
		array(
			'field' => 'apellido_paterno',
			'label' => 'apellido paterno',
			'rules' => 'trim|required|max_length[100]'
		),
		array(
			'field' => 'apellido_materno',
			'label' => 'apellido materno',
			'rules' => 'trim|required|max_length[100]'
		),
		array(
			'field' => 'email_registro',
			'label' => 'correo electr&oacute;nico',
			'rules' => 'trim|required|valid_email|max_length[100]|callback_email_unico'
		),
		array(
			'field' => 'telefono',
			'label' => 'tel&eacute;fono',
			'rules' => 'trim'
		),
		array(
			'field' => 'celular',
			'label' => 'celular',
			'rules' => 'trim'
		),
	),
	'informacion_cliente'=>array(
		array(
			'field'=>'tipo_persona_id',
			'label'=>'Facturaci&oacute;n para',
			'rules'=>'trim|required'//|required_if[cliente_externo,1,cliente externo,seleccionado]
		),
		array(
			'field'=>'razon_social',
			'label'=>'raz&oacute;n social',
			'rules'=>'trim|required_if[tipo_persona_id,3,tipo de persona,moral]'
		),
		array(
			'field'=>'rfc',
			'label'=>'RFC',
			'rules'=>'trim|required'//|required_if[cliente_externo,1,cliente externo,seleccionado]'
		),
		array(
			'field'=>'estado',
			'label'=>'estado',
			'rules'=>'trim|required'//|required_if[cliente_externo,1,cliente externo,seleccionado]'
		),
		array(
			'field'=>'municipio',
			'label'=>'delegaci&oacute;n o municipio',
			'rules'=>'trim|required'//|required_if[cliente_externo,1,cliente externo,seleccionado]'
		),
		array(
			'field'=>'codigo_postal',
			'label'=>'c&oacute;digo postal',
			'rules'=>'trim|required'//|required_if[cliente_externo,1,cliente externo,seleccionado]'
		),
		array(
			'field'=>'asentamiento',
			'label'=>'colonia',
			'rules'=>'trim|required'//|required_if[cliente_externo,1,cliente externo,seleccionado]'
		),
		array(
			'field'=>'calle',
			'label'=>'calle',
			'rules'=>'trim|required'//|required_if[cliente_externo,1,cliente externo,seleccionado]'
		),
		array(
			'field'=>'numero_exterior',
			'label'=>'n&uacute;mero exterior',
			'rules'=>'trim|required'//|required_if[cliente_externo,1,cliente externo,seleccionado]'
		),
		array(
			'field'=>'numero_interior',
			'label'=>'n&uacute;mero interior',
			'rules'=>'trim'
		),
		array(
			'field'=>'telefono',
			'label'=>'n&uacute;mero interior',
			'rules'=>'trim'
		),
		array(
			'field'=>'email',
			'label'=>'n&uacute;mero interior',
			'rules'=>'trim|required'
		),
		array(
			'field'=>'nombre_contacto',
			'label'=>'nombre de contacto',
			'rules'=>'trim|required'
		),
		array(
			'field'=>'telefono_particular',
			'label'=>'tel&eacute;fono particular',
			'rules'=>'trim|required'
		),
		array(
			'field'=>'telefono_celular',
			'label'=>'tel&eacute;fono celular',
			'rules'=>'trim'
		),
		array(
			'field'=>'entrega_estado',
			'label'=>'estado',
			'rules'=>'trim|required'
		),
		array(
			'field'=>'entrega_municipio',
			'label'=>'delegaci&oacute;n o municipio',
			'rules'=>'trim|required'
		),
		array(
			'field'=>'entrega_codigo_postal',
			'label'=>'c&oacute;digo postal',
			'rules'=>'trim|required'
		),
		array(
			'field'=>'entrega_asentamiento',
			'label'=>'colonia',
			'rules'=>'trim|required'
		),
		array(
			'field'=>'entrega_calle',
			'label'=>'calle',
			'rules'=>'trim|required'
		),
		array(
			'field'=>'entrega_numero_exterior',
			'label'=>'n&uacute;mero exterior',
			'rules'=>'trim|required'
		),
		array(
			'field'=>'entrega_numero_interior',
			'label'=>'n&uacute;mero interior',
			'rules'=>'trim'
		),
		array(
			'field'=>'entrega_fecha_tentativa',
			'label'=>'fecha tentativa de entrega',
			'rules'=>'trim|required|fecha_entrega'
		),
		array(
			'field'=>'entrega_fecha_instalacion',
			'label'=>'fecha tentativa de instalaci&oacute;n',
			'rules'=>'trim|fecha_instalacion'
		),
		array(
			'field'=>'instalacion_estado',
			'label'=>'estado',
			'rules'=>'trim|required'
		),
		array(
			'field'=>'instalacion_municipio',
			'label'=>'delegaci&oacute;n o municipio',
			'rules'=>'trim|required'
		),
		array(
			'field'=>'instalacion_codigo_postal',
			'label'=>'c&oacute;digo postal',
			'rules'=>'trim|required'
		),
		array(
			'field'=>'instalacion_asentamiento',
			'label'=>'colonia',
			'rules'=>'trim|required'
		),
		array(
			'field'=>'instalacion_calle',
			'label'=>'calle',
			'rules'=>'trim|required'
		),
		array(
			'field'=>'instalacion_numero_exterior',
			'label'=>'n&uacute;mero exterior',
			'rules'=>'trim|required'
		),
		array(
			'field'=>'instalacion_numero_interior',
			'label'=>'n&uacute;mero interior',
			'rules'=>'trim'
		),
		array(
			'field'=>'instalacion_nombre_contacto',
			'label'=>'nombre de contacto',
			'rules'=>'trim|required'
		),
		array(
			'field'=>'instalacion_telefono_particular',
			'label'=>'tel&eacute;fono particular',
			'rules'=>'trim'
		),
		array(
			'field'=>'instalacion_telefono_celular',
			'label'=>'tel&eacute;fono celular',
			'rules'=>'trim'
		)
	),
    'contacto'=>array(
        array(
            'field' => 'nombre',
            'label' => 'nombre',
            'rules' => 'trim|required|max_length[100]'
        ),
        array(
            'field' => 'apellido_paterno',
            'label' => 'apellido paterno',
            'rules' => 'trim|required|max_length[100]'
        ),
        array(
            'field' => 'apellido_materno',
            'label' => 'apellido materno',
            'rules' => 'trim|max_length[100]'
        ),

        array(
            'field' => 'email',
            'label' => 'correo electr&oacute;nico',
            'rules' => 'trim|required|valid_email|max_length[100]'
        ),
        array(
            'field' => 'telefono',
            'label' => 'tel&eacute;fono',
            'rules' => 'trim|max_length[25]'
        ),
        array(
            'field' => 'celular',
            'label' => 'celular',
            'rules' => 'trim|max_length[25]'
        ),
        array(
            'field' => 'comentario',
            'label' => 'comentario',
            'rules' => 'trim|required'
        ),
    ),
	'cupones' => array(
		array(
			'field' => 'alianza_id',
			'label' => 'Alianzas',
			'rules'=>'trim|required|integer'
		),
		array(
			'field' => 'vigencia_desde',
			'label' => 'Vigencia desde',
			'rules'=>'trim|required'
		),
		array(
			'field' => 'vigencia_hasta',
			'label' => 'Vigencia hasta',
			'rules'=>'trim|required'
		),
		array(
			'field' => 'porcentaje_descuento',
			'label' => 'Porcentaje de descuento',
			'rules'=>'trim|required|numeric'
		),
		array(
			'field' => 'descuento_distribuidor',
			'label' => 'Porcentaje de descuento a distribuidores',
			'rules'=>'trim|required|numeric'
		),
		array(
			'field' => 'meses_sin_intereses',
			'label' => 'Meses sin intereses',
			'rules'=>'trim|integer'
		),
		array(
			'field' => 'numero_folios',
			'label' => 'Número de folios',
			'rules'=>'trim|required|integer'
		),
	),
	'promociones' => array(
		array(
			'field' => 'nombre',
			'label' => 'Nombre',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'vigencia_desde',
			'label' => 'Vigencia desde',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'vigencia_hasta',
			'label' => 'Vigencia hasta',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'monto_minimo',
			'label' => 'Monto mínimo de compra',
			'rules' => 'trim|numeric|max_length[11]'
		),
		array(
			'field' => 'meses_sin_intereses',
			'label' => 'Meses sin intereses',
			'rules' => 'trim|integer'
		),
		array(
			'field' => 'porcentaje_descuento',
			'label' => 'Porcentaje de descuento',
			'rules' => 'trim|numeric'
		),
		array(
			'field' => 'monto_descuento',
			'label' => 'Monto de descuento',
			'rules' => 'trim|numeric|max_length[10]'
		),
	),
	'alianzas_promociones' => array(
		array(
			'field' => 'nombre',
			'label' => 'Nombre',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'descripcion',
			'label' => 'Descripción',
			'rules' => 'trim'
		),
		array(
			'field' => 'numero_folios',
			'label' => 'Número de folios',
			'rules' => 'trim|required|integer'
		),
	),
	'cotizaciones_ibs' => array(
		array(
			'field' => 'ibs',
			'label' => 'Orden de Venta IBS',
			'rules' => 'trim|integer|required|max_length[10]'
		),
	),
	'productos_detalle' => array(
		array(
			'field' => 'evento_estado',
			'label' => 'evento estado',
			'rules' => 'trim|required'
		),
	),
	'referidos_exportar' => array(
		array(
			'field' => 'fecha_inicial',
			'label' => 'Fecha inicial',
			'rules' => 'trim|required|date_equal_or_less_than[fecha_inicial,fecha_final,12]'
		),
		array(
			'field' => 'fecha_final',
			'label' => 'Fecha final',
			'rules' => 'trim|required|date_equal_or_less_than[fecha_inicial,fecha_final,12]'
		),
	),
	'cotizaciones_exportar' => array(
		array(
			'field' => 'fecha_inicial',
			'label' => 'Fecha inicial',
			'rules' => 'trim|required|date_equal_or_less_than[fecha_inicial,fecha_final,12]'
		),
		array(
			'field' => 'fecha_final',
			'label' => 'Fecha final',
			'rules' => 'trim|required|date_equal_or_less_than[fecha_inicial,fecha_final,12]'
		),
	),
	'comisiones_exportar' => array(
		array(
			'field' => 'fecha_inicial',
			'label' => 'Fecha inicial',
			'rules' => 'trim|required|date_equal_or_less_than[fecha_inicial,fecha_final,12]'
		),
		array(
			'field' => 'fecha_final',
			'label' => 'Fecha final',
			'rules' => 'trim|required|date_equal_or_less_than[fecha_inicial,fecha_final,12]'
		),
	),
);