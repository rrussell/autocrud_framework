<?php
	
	/**
	 * Lógica y carga de vista para Crear {Nombre}
	 * @return 
	 */
	function create() {
		$functions = new Functions();
		$dbcnx = new Conexion();
		$session = new Session();
		$validador = new Validador();
		$permisos = new Permisos();
		
		$session->verificar_seguridad();
		if($session->login(TRUE))
			$permisos->permiso('{nombre}', 'C');
		
		/* Cabeceras */
		$title = 'Crear {Nombre}';
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
			
			$inputs = array({validaciones}
			);
			
			if($validador->validar_form($inputs)) {
				$datos = array({create_datos}
				);
				if($dbcnx->insert_query('{nombre}', $datos)) {
					$session->set_var('mensaje', array('ok' => 'Insertado con éxito'));
					$functions->redireccionar('{nombre}/read');
				} else {
					$data = array(
						'mensaje' => $dbcnx->get_mensaje(),
						'tipo_mensaje' => 'error'
					);
					$functions->cargar_view('{nombre}/create', $data);
				}
			} else {
				$data = array(
					'mensaje' => $validador->get_label_errors(),
					'tipo_mensaje' => 'error'
				);
				$functions->cargar_view('{nombre}/create', $data);
			}
		} else {
			$data = array(
				'' => ''
			);
			$functions->cargar_view('{nombre}/create', $data);
		}
	}
	
	/**
	 * Lógica y carga de vista para Leer {Nombre}
	 * @return 
	 */
	function read() {
		$functions = new Functions();
		$dbcnx = new Conexion();
		$session = new Session();
		$validador = new Validador();
		$permisos = new Permisos();
		$paginar = new Paginador();
		
		$session->verificar_seguridad();
		if($session->login(TRUE))
			$permisos->permiso('{nombre}', 'R');
		
		/* Cabeceras */
		$title = 'Lista de {Nombre}';
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
		$pagina = $paginar->ejecutar($permisos->tipo_acceso('{nombre}'), BASE_URL . '{nombre}/read', 5);
		//$pagina = $paginar->ejecutar('SELECT {nombre}.* FROM {nombre} ORDER BY {nombre}.{clave_primaria} ASC', BASE_URL . '{nombre}/read', 5);
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
		$functions->cargar_view('{nombre}/read', $data);
	}
	
	/**
	 * Lógica y carga de vista para Modificar {Nombre}
	 * @return 
	 */
	function update($id = NULL) {
		$functions = new Functions();
		$dbcnx = new Conexion();
		$session = new Session();
		$validador = new Validador();
		$permisos = new Permisos();
		
		$session->verificar_seguridad();
		if($session->login(TRUE))
			$permisos->permiso('{nombre}', 'U');
		
		/* Cabeceras */
		$title = 'Modificar {Nombre}';
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
			$inputs = array({validaciones}
			);
			
			if($validador->validar_form($inputs)) {
				$campo_set_valor = array({update_datos}
				);
				
				$campos_where = array(
					'{nombre}.{clave_primaria}' => $id
				);
				
				if($dbcnx->update_query('{nombre}', $campo_set_valor, $campos_where)) {
					$session->set_var('mensaje', array('ok' => 'Modificado con éxito'));
					$functions->redireccionar('{nombre}/read');
				} else {				
					$data = array(
						'mensaje' => $dbcnx->get_mensaje(),
						'tipo_mensaje' => 'error'
					);
					$functions->cargar_view('{nombre}/update/' . $id, $data);
				}
			} else {
				$session->set_var('mensaje', array('error' => $validador->get_label_errors()));
				$functions->redireccionar('{nombre}/update/' . $id);
			}
		} else {
			$result = $dbcnx->select_query('SELECT {nombre}.* FROM {nombre} WHERE {nombre}.{clave_primaria} = \'' . $id . '\'');
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
				$functions->cargar_view('{nombre}/update', $data);
			}
		}
	}
	
	/**
	 * Lógica y carga de vista para Eliminar {Nombre}
	 * @return 
	 */
	function delete($id = NULL) {
		$functions = new Functions();
		$dbcnx = new Conexion();
		$session = new Session();
		$permisos = new Permisos();
		
		$session->verificar_seguridad();
		if($session->login(TRUE))
			$permisos->permiso('{nombre}', 'D');
		
		/* Cabeceras */
		$title = 'Lista {Nombre}';
		$keywords = '';
		$description = '';
		$current = '';		
		$functions->set_headers($title, $keywords, $description, $current);
		$functions->add_stylesheet('general');
		/* Cabeceras */
		
		$campos_where = array(
			'{nombre}.{clave_primaria}' => $id
		);
		
		if($dbcnx->delete_query('{nombre}', $campos_where)) {
			$session->set_var('mensaje', array('ok' => 'Eliminado con éxito'));
			$functions->redireccionar('{nombre}/read');
		} else {
			$data = array(
				'mensaje' => $dbcnx->get_mensaje(),
				'tipo_mensaje' => 'error'
			);
			$functions->cargar_view('{nombre}/read', $data);
		}
	}
?>