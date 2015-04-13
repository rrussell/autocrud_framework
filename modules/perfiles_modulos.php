<?php
	
	/**
	 * Lógica y carga de vista para Modificar Perfiles_modulos
	 * @return 
	 */
	function update($id = NULL) {
		$functions = new Functions();
		$dbcnx = new Conexion();
		$session = new Session();
		$validador = new Validador();
		$permisos = new Permisos();
		
		if($session->login(TRUE))
			$permisos->permiso('perfiles_modulos', 'U');
		
		/* Cabeceras */
		$title = 'Modificar Perfiles_modulos';
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
			$modulos = $dbcnx->select_query('SELECT * from modulos ORDER BY id_modulo ASC');
			foreach($modulos as $modulo) {
				$valor = '';
				if(isset($_POST['C' . $modulo['descripcion']]))
					$valor .= 'C';
				if(isset($_POST['R' . $modulo['descripcion']]))
					$valor .= 'R';
				if(isset($_POST['U' . $modulo['descripcion']]))
					$valor .= 'U';
				if(isset($_POST['D' . $modulo['descripcion']]))
					$valor .= 'D';
				$campo_set_valor = array(
					'valor' => $valor
				);
				
				$campos_where = array(
					'perfiles_modulos.perfil_id' => $id,
					'perfiles_modulos.modulo_id' => $modulo['id_modulo']
				);
				
				$dbcnx->update_query('perfiles_modulos', $campo_set_valor, $campos_where);
			}
			$session->set_var('mensaje', array('ok' => 'Modificado con éxito'));
			$functions->redireccionar('perfiles/read');

		} else {
			$result = $dbcnx->select_query('SELECT * FROM perfiles WHERE id_perfil = ' . $id);
			$result = (isset($result[0])) ? $result[0] : FALSE;
			
			if(!$result) {
				$functions->cargar_view_error('errors/404');
			} else {
				$modulos = $dbcnx->select_query('SELECT * from modulos ORDER BY id_modulo ASC');
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
					'tipo_mensaje' => $tipo_mensaje,
					'modulos' => $modulos,
					'id_perfil' => $id
				);
				$session->destroy_var('mensaje');
				$functions->cargar_view('perfiles_modulos/update', $data);
			}
		}
	}

?>