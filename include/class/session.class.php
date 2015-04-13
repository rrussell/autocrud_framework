<?php

/**
 * @author Rodrigo Russell G.
 * Created on 27-04-2010
 */

if(count(get_included_files()) == 1)
	die('The file ' . basename(__FILE__) . ' cannot be accessed directly, use include instead');

/**
 * Clase para le manejo de sesiones
 */
class Session {
	var $_mensaje = '';    
    
    /**
     * Returns $_mensaje.
     *
     * @see Session::$_mensaje
     */
    public function get_mensaje() {
        return $this->_mensaje;
    }
    
    /**
     * Sets $_mensaje.
     *
     * @param object $_mensaje
     * @see Session::$_mensaje
     */
    public function set_mensaje($_mensaje) {
        $this->_mensaje = $_mensaje;
    }
	
	/**
	 * Constructor
	 * @return 
	 */
	function Session($start = FALSE) {
		if($start) {
			session_start();
		}
	}
	
	/**
	 * Setea una variable de sesion
	 * @param object $nameSession
	 * @param object $data
	 * @return 
	 */
	public function set_var($nameSession, $data) {
		$_SESSION[$nameSession] = $data;
	}
	
	/**
	 * Obtiene el valor de una variable de sesion
	 * @param object $name
	 * @return 
	 */
	public function get_var($name) {
		if(isset($_SESSION[$name])) {
			return $_SESSION[$name];
		} else {
			return false;
		}
	}
	
	/**
	 * Elimina una variable de session
	 * @param object $name
	 * @return 
	 */
	public function destroy_var($name) {
		$_SESSION[$name] = array();
		unset($_SESSION[$name]);
	}
	
	/**
	 * Destructor
	 * @return 
	 */
	public function destroy_session() {
		session_unset();
		$_SESSION = array();
		session_destroy();
	}
	
	/**
	 * Inicia session al usuario y carga sus datos en variables session
	 * @param object $mail
	 * @param object $passwd
	 * @return 
	 */
	public function iniciar_login($mail, $passwd) {
		$dbcxn = new Conexion();
		$validar = new Validador();
		
		if(empty($mail) || empty($passwd)) {
			$this->set_mensaje('Mail y/o Contraseña vacíos');
			return FALSE;
		} 
		if(!$validar->mail($mail)) {
			$this->set_mensaje('No es un mail');
			return FALSE;
		}
		$query = 'SELECT usuarios.id_usuario AS id_usuario,
					     usuarios.nombre AS nombre,
					     usuarios.apellido AS apellido,
					     usuarios.activo AS activo,
					     usuarios.perfil_id AS id_perfil,
					     usuarios.mail AS mail,
					     perfiles.descripcion AS perfil
				  FROM usuarios
				  JOIN perfiles ON (usuarios.perfil_id = perfiles.id_perfil)
				  WHERE usuarios.mail = \'' . $mail . '\' AND usuarios.password= \'' . md5($passwd) . '\'';
		$result = $dbcxn->select_query($query);
		$result = (isset($result[0])) ? $result[0] : FALSE;
		if(!$result) {
			$this->set_mensaje('Mail y/o Contraseña incorrectas');
			return FALSE;
		}
		if($result['activo'] == 0) {
			$this->set_mensaje('Su cuenta ha sido desactivada, no puede iniciar sesión');
			return FALSE;
		}
		foreach($result as $campo => $valor)
			$this->set_var($campo, $valor);
		$this->set_var('login', TRUE);
		$this->cargar_permisos($result['id_perfil']);
		$this->set_var('id_ci_session', $this->set_var_security_login());
		
		return TRUE;
	}
	
	/**
	 * Verifica si esta iniciada la session de usuario y redirecciona si es indicado
	 * @return 
	 */
	public function login($redireccionar = FALSE) {
		$functions = new Functions();
		if($this->get_var('login'))
			return TRUE;
		else {
			if($redireccionar)
				$functions->redireccionar('');
			return FALSE;
		}		
	}
	
	/**
	 * Carga los permisos del perfil dado
	 * @param object $id_perfil
	 * @return 
	 */
	public function cargar_permisos($id_perfil) {
		$dbcxn = new Conexion();
		$campos = 'perfiles_modulos.valor as permiso,modulos.descripcion as modulo';
		$query  = 'SELECT ' . $campos . ' FROM perfiles_modulos ';
		$query .= 'JOIN modulos ON (perfiles_modulos.modulo_id = modulos.id_modulo) WHERE perfil_id = \'' . $id_perfil . '\'';
		$results = $dbcxn->select_query($query);
		foreach($results as $row)
			$this->set_var($row['modulo'], $row['permiso']);
	}
	
	public function set_var_security_login($sitio = FALSE) {
		$dbcnx = new Conexion();
		$ip_publica = '';
		$ip_local = '';
		$hostlocal = '';
		$hostname = '';
		$proxy = '';
		
		if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			if($pos = strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ' ')) {
				$ip_local =   substr($_SERVER['HTTP_X_FORWARDED_FOR'], 0, $pos);
				$ip_publica = substr($_SERVER['HTTP_X_FORWARDED_FOR'], $pos + 1);
				$hostlocal =  substr($_SERVER['HTTP_X_FORWARDED_FOR'], $pos + 1);
			}else {
				$ip_publica =  $_SERVER['HTTP_X_FORWARDED_FOR'];
				$hostlocal = $_SERVER['HTTP_X_FORWARDED_FOR'];
			}
			if($_SERVER['REMOTE_ADDR'])
				$proxy = $_SERVER['REMOTE_ADDR'];
		} else {
			$ip_publica = $_SERVER['REMOTE_ADDR'];
			$hostlocal = $_SERVER['REMOTE_ADDR'];
		}
		$hostname = gethostbyaddr($hostlocal);
		
		$username = ($sitio) ? $this->get_var('sitio_mail') : $this->get_var('mail');
		
		$var = ($sitio) ? 'sitio_mail' : 'mail';
		$result = $dbcnx->select_query('SELECT id_ci_session FROM ci_sessions WHERE login = \''.$this->get_var($var).'\' LIMIT 0,1');
		$result = (isset($result[0])) ? $result[0] : FALSE;
		if($result) {
			$campos_where = array(
				'ci_sessions.id_ci_session' => $result['id_ci_session']
			);
			$dbcnx->delete_query('ci_sessions', $campos_where);
		}
		
		$datos = array(
			'session_id' => session_id(),
			'ip_publica' => $ip_publica,
			'ip_local' => $ip_local,
			'hostlocal' => $hostlocal,
			'hostname' => $hostname,
			'proxy' => $proxy,
			'navegador' => $_SERVER['HTTP_USER_AGENT'],
			'inicio_sesion' => time(),
			'ultima_actividad' => time(),
			'login' => $username
		);
		if(!$dbcnx->insert_query('ci_sessions', $datos)) {
			$this->set_mensaje('ERROR: Ocurrio un error al tratar de insertar datos de session de DB.');
			return FALSE;
		} else {
			$result = $dbcnx->select_query('SELECT id_ci_session FROM ci_sessions ORDER BY id_ci_session DESC LIMIT 0,1');
			$result = (isset($result[0])) ? $result[0]['id_ci_session'] : FALSE;
			return $result;
		}
	}
	
	/**
	 * Verifica en la base de datos la ip que inicio session, si no es la misma
	 * destruye la session del usuario.
	 */
	public function verificar_seguridad($sitio = FALSE) {
		$dbcnx = new Conexion();
		$ip_publica = '';
		$ip_local = '';
		$hostlocal = '';
		$hostname = '';
		$proxy = '';
		$session_id = session_id();
		$navegador = $_SERVER['HTTP_USER_AGENT'];
		
		if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			if($pos = strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ' ')) {
				$ip_local =   substr($_SERVER['HTTP_X_FORWARDED_FOR'], 0, $pos);
				$ip_publica = substr($_SERVER['HTTP_X_FORWARDED_FOR'], $pos + 1);
				$hostlocal =  substr($_SERVER['HTTP_X_FORWARDED_FOR'], $pos + 1);
			}else {
				$ip_publica =  $_SERVER['HTTP_X_FORWARDED_FOR'];
				$hostlocal = $_SERVER['HTTP_X_FORWARDED_FOR'];
			}
			if($_SERVER['REMOTE_ADDR'])
				$proxy = $_SERVER['REMOTE_ADDR'];
		} else {
			$ip_publica = $_SERVER['REMOTE_ADDR'];
			$hostlocal = $_SERVER['REMOTE_ADDR'];
		}
		$hostname = gethostbyaddr($hostlocal);
		
		$var = ($sitio) ? 'sitio_' : '';
		$result = $dbcnx->select_query('SELECT * FROM ci_sessions WHERE login = \''.$this->get_var($var.'mail').'\' LIMIT 0,1');
		$result = (isset($result[0])) ? $result[0] : FALSE;
		if(!$result) {
			$array['mensaje'] = 'No se encontró login de usuario';
			$file = fopen(TEMP_DIR . $ip_publica . $hostlocal, 'w');fwrite($file, json_encode($array));fclose($file);
			$this->destroy_session();
			return FALSE;
		}
		
		if(!($ip_publica == $result['ip_publica'] &&
			 $ip_local == $result['ip_local'] &&
			 $hostlocal == $result['hostlocal'] &&
			 $hostname == $result['hostname'] &&
			 $proxy == $result['proxy'] &&
			 $navegador == $result['navegador'] &&
			 $session_id == $result['session_id'])
		) {
			$array['mensaje'] = 'Se ha iniciado sesión desde otra máquina';
			$file = fopen(TEMP_DIR . $ip_publica . $hostlocal, 'w');fwrite($file, json_encode($array));fclose($file);
			$this->destroy_session();
			return FALSE;
		}
		
		//1200 son 20 minutos
		if((time() - $result['ultima_actividad']) > 5) {
			$array['mensaje'] = 'El sitio ha estado inactivo por 5 segundos';
			$file = fopen(TEMP_DIR . $ip_publica . $hostlocal, 'w');fwrite($file, json_encode($array));fclose($file);
			$this->destroy_session();
			return FALSE;
		}
		
		$campo_set_valor = array('ultima_actividad' => time());
		$campos_where = array('ci_sessions.id_ci_session' => $this->get_var($var.'id_ci_session'));
		if(!$dbcnx->update_query('ci_sessions', $campo_set_valor, $campos_where)) {
			$array['mensaje'] = 'Ocurrió un error actualizando datos de seguridad de sesión';
			$file = fopen(TEMP_DIR . $ip_publica . $hostlocal, 'w');fwrite($file, json_encode($array));fclose($file);
			$this->destroy_session();
			return FALSE;
		}
		return TRUE;
	}
}
?>