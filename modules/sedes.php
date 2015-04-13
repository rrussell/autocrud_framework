<?php
	
	/**
	 * Lógica y carga de vista para Crear Sedes
	 * @return 
	 */
	function create() {
		$functions = new Functions();
		$dbcnx = new Conexion();
		$session = new Session();
		$validador = new Validador();
		$permisos = new Permisos();
		
		if($session->login(TRUE))
			$permisos->permiso('sedes', 'C');
		
		/* Cabeceras */
		$title = 'Crear Sedes';
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
					'label' => 'descripcion',
					'valor' => $_POST['descripcion'],
					'regla' => 'requerido'
				),
				array(
					'label' => 'mail',
					'valor' => $_POST['mail'],
					'regla' => 'requerido'
				)
			);
			
			if($validador->validar_form($inputs)) {
				$datos = array(
					'descripcion' => $_POST['descripcion'],
					'mail' => $_POST['mail'],
					'direccion' => (isset($_POST['direccion'])) ? $_POST['direccion'] : NULL,
					'telefono' => (isset($_POST['telefono'])) ? $_POST['telefono'] : NULL
				);
				if($dbcnx->insert_query('sedes', $datos)) {
					$session->set_var('mensaje', array('ok' => 'Insertado con éxito'));
					$functions->redireccionar('sedes/read');
				} else {
					$data = array(
						'mensaje' => $dbcnx->get_mensaje(),
						'tipo_mensaje' => 'error'
					);
					$functions->cargar_view('sedes/create', $data);
				}
			} else {
				$data = array(
					'mensaje' => $validador->get_label_errors(),
					'tipo_mensaje' => 'error'
				);
				$functions->cargar_view('sedes/create', $data);
			}
		} else {
			$data = array(
				'' => ''
			);
			$functions->cargar_view('sedes/create', $data);
		}
	}
	
	/**
	 * Lógica y carga de vista para Leer Sedes
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
			$permisos->permiso('sedes', 'R');
		
		/* Cabeceras */
		$title = 'Lista de Sedes';
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
		$pagina = $paginar->ejecutar('SELECT sedes.* FROM sedes ORDER BY sedes.id_sede ASC', BASE_URL . 'sedes/read', 5);
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
		$functions->cargar_view('sedes/read', $data);
	}
	
	/**
	 * Lógica y carga de vista para Modificar Sedes
	 * @return 
	 */
	function update($id = NULL) {
		$functions = new Functions();
		$dbcnx = new Conexion();
		$session = new Session();
		$validador = new Validador();
		$permisos = new Permisos();
		
		if($session->login(TRUE))
			$permisos->permiso('sedes', 'U');
		
		/* Cabeceras */
		$title = 'Modificar Sedes';
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
					'label' => 'descripcion',
					'valor' => $_POST['descripcion'],
					'regla' => 'requerido'
				),
				array(
					'label' => 'mail',
					'valor' => $_POST['mail'],
					'regla' => 'requerido'
				)
			);
			
			if($validador->validar_form($inputs)) {
				$campo_set_valor = array(
					'descripcion' => $_POST['descripcion'],
					'mail' => $_POST['mail'],
					'direccion' => (isset($_POST['direccion'])) ? $_POST['direccion'] : NULL,
					'telefono' => (isset($_POST['telefono'])) ? $_POST['telefono'] : NULL
				);
				
				$campos_where = array(
					'sedes.id_sede' => $id
				);
				
				if($dbcnx->update_query('sedes', $campo_set_valor, $campos_where)) {
					$session->set_var('mensaje', array('ok' => 'Modificado con éxito'));
					$functions->redireccionar('sedes/read');
				} else {				
					$data = array(
						'mensaje' => $dbcnx->get_mensaje(),
						'tipo_mensaje' => 'error'
					);
					$functions->cargar_view('sedes/update/' . $id, $data);
				}
			} else {
				$session->set_var('mensaje', array('error' => $validador->get_label_errors()));
				$functions->redireccionar('sedes/update/' . $id);
			}
		} else {
			$result = $dbcnx->select_query('SELECT sedes.* FROM sedes WHERE sedes.id_sede = \'' . $id . '\'');
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
				$data = array(
					'result' => $result,
					'mensaje' => $mensaje,
					'tipo_mensaje' => $tipo_mensaje
				);
				$session->destroy_var('mensaje');
				$functions->cargar_view('sedes/update', $data);
			}
		}
	}
	
	/**
	 * Lógica y carga de vista para Eliminar Sedes
	 * @return 
	 */
	function delete($id = NULL) {
		$functions = new Functions();
		$dbcnx = new Conexion();
		$session = new Session();
		$permisos = new Permisos();
		
		if($session->login(TRUE))
			$permisos->permiso('sedes', 'D');
		
		/* Cabeceras */
		$title = 'Lista Sedes';
		$keywords = '';
		$description = '';
		$current = '';		
		$functions->set_headers($title, $keywords, $description, $current);
		$functions->add_stylesheet('general');
		/* Cabeceras */
		
		$campos_where = array(
			'sedes.id_sede' => $id
		);
		
		$result = $dbcnx->select_query('SELECT * FROM usuarios WHERE sede_id = ' . $id);
		if(isset($result[0])) $cont++;
		
		if($cont > 0) {
			$session->set_var('mensaje', array('error' => 'No lo puedes eliminar ya que hay un Usuario perteneciendo a la Sede'));
			$functions->redireccionar('sedes/read');
		} else {
			if($dbcnx->delete_query('sedes', $campos_where)) {
				$session->set_var('mensaje', array('ok' => 'Eliminado con éxito'));
				$functions->redireccionar('sedes/read');
			} else {
				$data = array(
					'mensaje' => $dbcnx->get_mensaje(),
					'tipo_mensaje' => 'error'
				);
				$functions->cargar_view('sedes/read', $data);
			}
		}
	}
?>