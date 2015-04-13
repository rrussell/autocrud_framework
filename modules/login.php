<?php
	function read() {
		$functions = new Functions();
		$dbcnx = new Conexion();
		$session = new Session();
		$validador = new Validador();
		
		/* Cabeceras */
		$title = 'Login';
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
		
		$ip_publica = '';
		$hostlocal = '';
		if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			if($pos = strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ' ')) {
				$ip_publica = substr($_SERVER['HTTP_X_FORWARDED_FOR'], $pos + 1);
				$hostlocal =  substr($_SERVER['HTTP_X_FORWARDED_FOR'], $pos + 1);
			}else {
				$ip_publica =  $_SERVER['HTTP_X_FORWARDED_FOR'];
				$hostlocal = $_SERVER['HTTP_X_FORWARDED_FOR'];
			}
		} else {
			$ip_publica = $_SERVER['REMOTE_ADDR'];
			$hostlocal = $_SERVER['REMOTE_ADDR'];
		}
		$filename = TEMP_DIR . $ip_publica . $hostlocal;
		
		if(!$session->login()) {
			if($_POST) {
				if($session->iniciar_login($_POST['mail'], $_POST['passwd']))
					$functions->redireccionar('panel');
				else {
					$data = array(
						'mensaje' => $session->get_mensaje(),
						'tipo_mensaje' => 'error'
					);
					$functions->cargar_view('login', $data);
				}
			} else {
				if(file_exists($filename)) {
					$file = fopen($filename, 'r'); $contenido = fgets($file);fclose($file);
					$array = json_decode($contenido, true);
					$array['mensaje'] = (isset($array['mensaje'])) ? $array['mensaje'] : '';
					unlink($filename);
					$data = array(
						'mensaje' => $array['mensaje'],
						'tipo_mensaje' => 'error'
					);
				} else {
					$data = array(
						'mensaje' => ''
					);
				}
				$functions->cargar_view('login', $data);
			}
		} else {
			$functions->redireccionar('panel');
		}
	}
	
	function delete() {
		$functions = new Functions();
		$session = new Session();
		
		$session->destroy_session();
		$functions->redireccionar('login');
	}
	
	function cambiar_passwd() {
		$session = new Session();
		$validador = new Validador();
		$dbcnx = new Conexion();
		$functions = new Functions();
		$url_self = '';
		
		if($_POST) {
			$inputs = array(
				array(
					'label' => 'passwd',
					'valor' => $_POST['passwd'],
					'regla' => 'requerido'
				),
				array(
					'label' => 'cpasswd',
					'valor' => $_POST['cpasswd'],
					'regla' => 'requerido'
				)
			);
			
			if($validador->validar_form($inputs)) {
				$campo_set_valor = array(
					'password' => md5($_POST['passwd']),
				);
				
				$campos_where = array(
					'usuarios.id_usuario' => $session->get_var('id_usuario')
				);
				
				if($dbcnx->update_query('usuarios', $campo_set_valor, $campos_where)) {
					echo 'ok|Contraseña modificada con éxito';
				} else {
					echo 'error|' . $dbcnx->get_mensaje();
				}
			} else {
				echo 'error|' . $validador->get_label_errors();
			}
		} else {
			echo 'error|No se han enviado datos';
		}
		
	}
?>