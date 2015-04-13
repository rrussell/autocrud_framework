<?php
	
	/**
	 * Lógica y carga de vista para Crear Perfiles
	 * @return 
	 */
	function create() {
		$functions = new Functions();
		$dbcnx = new Conexion();
		$session = new Session();
		$validador = new Validador();
		$permisos = new Permisos();
		
		if($session->login(TRUE))
			$permisos->permiso('perfiles', 'C');
		
		/* Cabeceras */
		$title = 'Crear Perfiles';
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
			);
			
			if($validador->validar_form($inputs)) {
				$datos = array(
					'descripcion' => $_POST['descripcion'],
					'fecha_creacion' => time(),
					'usuario_id_creador' => $session->get_var('id_usuario')
				);
				if($dbcnx->insert_query('perfiles', $datos)) {
					$perfil = $dbcnx->select_query('SELECT id_perfil FROM perfiles ORDER BY id_perfil DESC LIMIT 0,1');
					$modulos = $dbcnx->select_query('SELECT * FROM modulos ORDER BY id_modulo');
					foreach($modulos as $modulo) {
						$valor = '';
						if(isset($_POST['permisos'])) {
							$valor = $session->get_var($modulo['descripcion']);
						} else {
							$valor = '';
						}
						$datos = array(
							'perfil_id' => $perfil[0]['id_perfil'],
							'modulo_id' => $modulo['id_modulo'],
							'valor' => $valor
						);
						$dbcnx->insert_query('perfiles_modulos', $datos);
					}
					if(isset($_POST['permisos'])) {
						$session->set_var('mensaje', array('ok' => 'Insertado con éxito con mismos permisos que ' . $session->get_var('perfil')));
						$functions->redireccionar('perfiles/read');
					} else {
						$session->set_var('mensaje', array('ok' => 'Elija los permisos del usuario creado'));
						$functions->redireccionar('perfiles_modulos/update/var,' . $perfil[0]['id_perfil']);
					}
				} else {
					$data = array(
						'mensaje' => $dbcnx->get_mensaje(),
						'tipo_mensaje' => 'error'
					);
					$functions->cargar_view('perfiles/create', $data);
				}
			} else {
				$data = array(
					'mensaje' => $validador->get_label_errors(),
					'tipo_mensaje' => 'error'
				);
				$functions->cargar_view('perfiles/create', $data);
			}
		} else {
			$data = array(
				'' => ''
			);
			$functions->cargar_view('perfiles/create', $data);
		}
	}
	
	/**
	 * Lógica y carga de vista para Leer Perfiles
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
			$permisos->permiso('perfiles', 'R');
		
		/* Cabeceras */
		$title = 'Lista de Perfiles';
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
		$pagina = $paginar->ejecutar('SELECT perfiles.* FROM perfiles ORDER BY perfiles.id_perfil ASC', BASE_URL . 'perfiles/read', 5);
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
		$functions->cargar_view('perfiles/read', $data);
	}
	
	/**
	 * Lógica y carga de vista para Modificar Perfiles
	 * @return 
	 */
	function update($id = NULL) {
		$functions = new Functions();
		$dbcnx = new Conexion();
		$session = new Session();
		$validador = new Validador();
		$permisos = new Permisos();
		
		if($session->login(TRUE))
			$permisos->permiso('perfiles', 'U');
		
		/* Cabeceras */
		$title = 'Modificar Perfiles';
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
				)
			);
			
			if($validador->validar_form($inputs)) {
				$campo_set_valor = array(
					'descripcion' => $_POST['descripcion'],
					'fecha_modificacion' => time(),
					'usuario_id_modificador' => $session->get_var('id_usuario')
				);
				
				$campos_where = array(
					'perfiles.id_perfil' => $id
				);
				
				if($dbcnx->update_query('perfiles', $campo_set_valor, $campos_where)) {
					$session->set_var('mensaje', array('ok' => 'Modificado con éxito'));
					$functions->redireccionar('perfiles/read');
				} else {				
					$data = array(
						'mensaje' => $dbcnx->get_mensaje(),
						'tipo_mensaje' => 'error'
					);
					$functions->cargar_view('perfiles/update/' . $id, $data);
				}
			} else {
				$session->set_var('mensaje', array('error' => $validador->get_label_errors()));
				$functions->redireccionar('perfiles/update/' . $id);
			}
		} else {
			$result = $dbcnx->select_query('SELECT perfiles.* FROM perfiles WHERE perfiles.id_perfil = ' . $id);
			$result = (isset($result[0])) ? $result[0] : FALSE;
			
			if(!$result) {
				$functions->cargar_view('errors/404');
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
				$functions->cargar_view('perfiles/update', $data);
			}
		}
	}
	
	/**
	 * Lógica y carga de vista para Eliminar Perfiles
	 * @return 
	 */
	function delete($id = NULL) {
		$functions = new Functions();
		$dbcnx = new Conexion();
		$session = new Session();
		$permisos = new Permisos();
		
		if($session->login(TRUE))
			$permisos->permiso('perfiles', 'D');
		
		/* Cabeceras */
		$title = 'Lista Perfiles';
		$keywords = '';
		$description = '';
		$current = '';		
		$functions->set_headers($title, $keywords, $description, $current);
		$functions->add_stylesheet('general');
		/* Cabeceras */
		
		$cont = 0;
		$result = $dbcnx->select_query('SELECT * FROM usuarios WHERE perfil_id = ' . $id);
		if(isset($result[0])) $cont++;
		
		if($cont > 0) {
			$session->set_var('mensaje', array('error' => 'No lo puedes eliminar ya que un usuario esta usando el perfil'));
			$functions->redireccionar('perfiles/read');
		} else {
			$campos_where = array(
				'perfiles_modulos.perfil_id' => $id
			);
			if($dbcnx->delete_query('perfiles_modulos', $campos_where)) {
				$campos_where = array(
					'perfiles.id_perfil' => $id
				);
				if($dbcnx->delete_query('perfiles', $campos_where)) {
					$session->set_var('mensaje', array('ok' => 'Eliminado con éxito'));
					$functions->redireccionar('perfiles/read');
				} else {
					$data = array(
						'mensaje' => $dbcnx->get_mensaje(),
						'tipo_mensaje' => 'error'
					);
					$functions->cargar_view('perfiles/read', $data);
				}
			} else {
				$data = array(
					'mensaje' => $dbcnx->get_mensaje(),
					'tipo_mensaje' => 'error'
				);
				$functions->cargar_view('perfiles/read', $data);
			}
		}
	}
?>