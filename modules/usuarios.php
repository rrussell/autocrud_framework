<?php
	
	/**
	 * Lógica y carga de vista para Crear Usuarios
	 * @return 
	 */
	function create() {
		$functions = new Functions();
		$dbcnx = new Conexion();
		$session = new Session();
		$validador = new Validador();
		$permisos = new Permisos();
		
		if($session->login(TRUE))
			$permisos->permiso('usuarios', 'C');
		
		/* Cabeceras */
		$title = 'Crear Usuarios';
		$keywords = '';
		$description = '';
		$current = '';
		$functions->set_headers($title, $keywords, $description, $current);
		$functions->add_stylesheet('general');
		$functions->add_javascript('jquery-1.4.2.min');
		$functions->add_javascript('jquery.validate');
		$functions->add_javascript('functions');
		$functions->add_script('iniciar(\'' . $session->get_var('section') .'\', \'' . $session->get_var('subsection') . '\');');
		/* Cabeceras */
		
		if($_POST) {
			
			$inputs = array(
				array(
					'label' => 'nombre',
					'valor' => $_POST['nombre'],
					'regla' => 'requerido'
				),
				array(
					'label' => 'apellido',
					'valor' => $_POST['apellido'],
					'regla' => 'requerido'
				),
				array(
					'label' => 'mail',
					'valor' => $_POST['mail'],
					'regla' => 'requerido'
				),
				array(
					'label' => 'password',
					'valor' => $_POST['password'],
					'regla' => 'requerido'
				),
				array(
					'label' => 'perfil_id',
					'valor' => $_POST['perfil_id'],
					'regla' => 'requerido'
				)
			);
			
			if($validador->validar_form($inputs)) {
				$datos = array(
					'nombre' => $_POST['nombre'],
					'apellido' => $_POST['apellido'],
					'mail' => $_POST['mail'],
					'password' => md5($_POST['password']),
					'fecha_creacion' => time(),
					'activo' => ($_POST['activo'] == 0) ? 0 : 1,
					'perfil_id' => $_POST['perfil_id']
				);
				if($dbcnx->insert_query('usuarios', $datos)) {
					$session->set_var('mensaje', array('ok' => 'Insertado con éxito'));
					$functions->redireccionar('usuarios/read');
				} else {
					$data = array(
						'mensaje' => $dbcnx->get_mensaje(),
						'tipo_mensaje' => 'error'
					);
					$functions->cargar_view('usuarios/create', $data);
				}
			} else {
				$data = array(
					'mensaje' => $validador->get_label_errors(),
					'tipo_mensaje' => 'error'
				);
				$functions->cargar_view('usuarios/create', $data);
			}
		} else {
			$perfiles = $dbcnx->select_query('SELECT id_perfil,descripcion FROM perfiles ORDER BY descripcion ASC');
			$sedes = $dbcnx->select_query('SELECT id_sede,descripcion FROM sedes ORDER BY descripcion ASC');
			$data = array(
				'perfiles' => $perfiles,
				'sedes' => $sedes
			);
			$functions->cargar_view('usuarios/create', $data);
		}
	}
	
	/**
	 * Lógica y carga de vista para Leer Usuarios
	 * @return 
	 */
	function read() {
		$functions = new Functions();
		$dbcnx = new Conexion();
		$session = new Session();
		$validador = new Validador();
		$permisos = new Permisos();
		$paginar = new Paginador();
		
		if($session->login(TRUE))
			$permisos->permiso('usuarios', 'R');
		
		/* Cabeceras */
		$title = 'Lista de Usuarios';
		$keywords = '';
		$description = '';
		$current = '';		
		$functions->set_headers($title, $keywords, $description, $current);
		$functions->add_stylesheet('general');
		$functions->add_stylesheet('alerts');
		$functions->add_javascript('jquery-1.4.2.min');
		$functions->add_javascript('jquery.validate');
		$functions->add_javascript('ui.min');
		$functions->add_javascript('jquery.alerts');
		$functions->add_javascript('functions');
		$functions->add_script('iniciar(\'' . $session->get_var('section') .'\', \'' . $session->get_var('subsection') . '\');');
		/* Cabeceras */
		
		if(isset($_GET['pg'])) $paginar->set_pag_actual($_GET['pg']);
		$pagina = $paginar->ejecutar('SELECT usuarios.* FROM usuarios ORDER BY usuarios.id_usuario ASC', BASE_URL . 'usuarios/read', 5);
		$results = $dbcnx->select_query($pagina['query']);
		$tipo_mensaje = '';
		$mensaje = '';
		if($session->get_var('mensaje')) {
			if(is_string($session->get_var('mensaje'))) {
				$mensaje = $session->get_var('mensaje');
			} elseif(is_array($session->get_var('mensaje'))) {
				$tipo_mensaje = implode('', array_keys($session->get_var('mensaje')));
				$mensaje = implode('', $session->get_var('mensaje'));
			}
		}
		
		$data = array(
			'results' => $results,
			'tipo_mensaje' => $tipo_mensaje,
			'mensaje' => $mensaje,
			'paginacion' => $pagina['navegacion']
		);
		
		$session->destroy_var('mensaje');
		$functions->cargar_view('usuarios/read', $data);
	}
	
	/**
	 * Lógica y carga de vista para Modificar Usuarios
	 * @return 
	 */
	function update($id = NULL) {
		$functions = new Functions();
		$dbcnx = new Conexion();
		$session = new Session();
		$validador = new Validador();
		$permisos = new Permisos();
		
		if($session->login(TRUE))
			$permisos->permiso('usuarios', 'U');
		
		/* Cabeceras */
		$title = 'Modificar Usuarios';
		$keywords = '';
		$description = '';
		$current = '';		
		$functions->set_headers($title, $keywords, $description, $current);
		$functions->add_stylesheet('general');
		$functions->add_javascript('jquery-1.4.2.min');
		$functions->add_javascript('jquery.validate');
		$functions->add_javascript('functions');
		$functions->add_script('iniciar(\'' . $session->get_var('section') .'\', \'' . $session->get_var('subsection') . '\');');
		/* Cabeceras */
		
		if($_POST) {
			$inputs = array(
				array(
					'label' => 'nombre',
					'valor' => $_POST['nombre'],
					'regla' => 'requerido'
				),
				array(
					'label' => 'apellido',
					'valor' => $_POST['apellido'],
					'regla' => 'requerido'
				),
				array(
					'label' => 'mail',
					'valor' => $_POST['mail'],
					'regla' => 'requerido'
				),
				array(
					'label' => 'perfil_id',
					'valor' => $_POST['perfil_id'],
					'regla' => 'requerido'
				)
			);
			
			if($validador->validar_form($inputs)) {
				if($_POST['password'] != '')
					$campo_set_valor = array(
						'nombre' => $_POST['nombre'],
						'apellido' => $_POST['apellido'],
						'mail' => $_POST['mail'],
						'password' => md5($_POST['password']),
						'fecha_modificacion' => time(),
						'activo' => ($_POST['activo'] == 0) ? 0 : 1,
						'perfil_id' => $_POST['perfil_id']
					);
				else
					$campo_set_valor = array(
						'nombre' => $_POST['nombre'],
						'apellido' => $_POST['apellido'],
						'mail' => $_POST['mail'],
						'fecha_modificacion' => time(),
						'activo' => (isset($_POST['activo'])) ? 1 : 0,
						'perfil_id' => $_POST['perfil_id']
					);
				
				$campos_where = array(
					'usuarios.id_usuario' => $id
				);
				
				if($dbcnx->update_query('usuarios', $campo_set_valor, $campos_where)) {
					$session->set_var('mensaje', array('ok' => 'Modificado con éxito'));
					$functions->redireccionar('usuarios/read');
				} else {				
					$data = array(
						'mensaje' => $dbcnx->get_mensaje(),
						'tipo_mensaje' => 'error'
					);
					$functions->cargar_view('usuarios/update/' . $id, $data);
				}
			} else {
				$session->set_var('mensaje', array('error' => $validador->get_label_errors()));
				$functions->redireccionar('usuarios/update/' . $id);
			}
		} else {
			$result = $dbcnx->select_query('SELECT usuarios.* FROM usuarios WHERE usuarios.id_usuario = ' . $id);
			$result = (isset($result[0])) ? $result[0] : FALSE;
			
			if(!$result) {
				$functions->cargar_view_error('errors/404');
			} else {
				foreach($result as $key => $value) {
					$result[$key] = htmlspecialchars($value);
				}
				$tipo_mensaje = '';
				$mensaje = '';
				if($session->get_var('mensaje')) {
					if(is_string($session->get_var('mensaje'))) {
						$mensaje = $session->get_var('mensaje');
					} elseif(is_array($session->get_var('mensaje'))) {
						$tipo_mensaje = implode('', array_keys($session->get_var('mensaje')));
						$mensaje = implode('', $session->get_var('mensaje'));
					}
				}
				$perfiles = $dbcnx->select_query('SELECT id_perfil,descripcion FROM perfiles ORDER BY descripcion ASC');
				$sedes = $dbcnx->select_query('SELECT id_sede,descripcion FROM sedes ORDER BY descripcion ASC');
				$data = array(
					'result' => $result,
					'mensaje' => $mensaje,
					'tipo_mensaje' => $tipo_mensaje,
					'perfiles' => $perfiles,
					'sedes' => $sedes
				);
				$session->destroy_var('mensaje');
				$functions->cargar_view('usuarios/update', $data);
			}
		}
	}
	
	/**
	 * Lógica y carga de vista para Eliminar Usuarios
	 * @return 
	 */
	function delete($id = NULL) {
		$functions = new Functions();
		$dbcnx = new Conexion();
		$session = new Session();
		$permisos = new Permisos();
		
		if($session->login(TRUE))
			$permisos->permiso('usuarios', 'D');
		
		/* Cabeceras */
		$title = 'Lista Usuarios';
		$keywords = '';
		$description = '';
		$current = '';		
		$functions->set_headers($title, $keywords, $description, $current);
		$functions->add_stylesheet('general');
		/* Cabeceras */
		
		$campos_where = array(
			'usuarios.id_usuario' => $id
		);
		
		//TODO Revisar la eliminacion de usuarios (tablas de cada proyecto)
		$cont = 0;
		$result = $dbcnx->select_query('SELECT * FROM perfiles WHERE usuario_id_creador = ' . $id . ' OR usuario_id_modificador = ' . $id);
		if(isset($result[0])) $cont++;
		
		if($cont > 0) {
			$session->set_var('mensaje', array('error' => 'No lo puedes eliminar ya que el usuario ha creado elementos en el sistema'));
			$functions->redireccionar('usuarios/read');
		} else {
			if($dbcnx->delete_query('usuarios', $campos_where)) {
				$session->set_var('mensaje', array('ok' => 'Eliminado con éxito'));
				$functions->redireccionar('usuarios/read');
			} else {
				$data = array(
					'mensaje' => $dbcnx->get_mensaje(),
					'tipo_mensaje' => 'error'
				);
				$functions->cargar_view('usuarios/read', $data);
			}
		}
	}
?>