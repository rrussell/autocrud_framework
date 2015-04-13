<?php

/**
 * @author Rodrigo Russell G.
 * Created on 14-04-2010
 */

if(count(get_included_files()) == 1)
	die('The file ' . basename(__FILE__) . ' cannot be accessed directly, use include instead');

/**
 * Clase que maneja los permisos para los usuarios
 */
class Permisos {
	
	/**
	 * Constructor
	 * @return 
	 */
	function Permisos() {
		
	}
	
	/**
	 * Revisa si el usuario tiene permiso en el modulo y seccion dado si no redirecciona
	 * @param object $modulo
	 * @param object $seccion
	 * @return 
	 */
	public function permiso($modulo, $seccion) {
		$session = new Session();
		$functions = new Functions();
		$seccion = strtoupper($seccion);
		if(!($seccion == 'C' || $seccion == 'R' || $seccion == 'U' || $seccion == 'D'))
			$functions->redireccionar('errors/e403');
		if(!preg_match('/' . $seccion . '/i', $session->get_var($modulo)))
			$functions->redireccionar('errors/e403');
	}
	
	/**
	 * Revisa si el usuario tiene permiso en el modulo y seccion dado si no devuelve false
	 * @param object $modulo
	 * @param object $seccion
	 * @return 
	 */
	public function mostrar($modulo, $seccion) {
		$session = new Session();
		$seccion = strtoupper($seccion);
		if(!($seccion == 'C' || $seccion == 'R' || $seccion == 'U' || $seccion == 'D'))
			return FALSE;
		if(preg_match('/' . $seccion . '/i', $session->get_var($modulo)))
			return TRUE;
		else
			return FALSE;
	}
	
	/**
	 * Filtra el acceso a los datos de la tabla segun el tipo_acceso del usuario
	 * @param object $tabla
	 * @return 
	 */
	public function tipo_acceso($tabla, $where_cond = 1, $where_valor = 1, $comp = '=') {
		$session = new Session();
		$dbcxn = new Conexion();
		$query = '';
		$campos = $dbcxn->get_campos($tabla);
		if($session->get_var('tipo_acceso') == 3 && $tabla == 'horarios')//Total
			return 'SELECT ' . $tabla . '.* FROM ' . $tabla . ' WHERE ' . $where_cond . ' '.$comp.' \'' . $where_valor . '\' ORDER BY ' . $tabla . '.hora_inicio ASC';
		if(!in_array('usuario_id_creador', $campos, TRUE))
			return 'SELECT ' . $tabla . '.* FROM ' . $tabla . ' WHERE ' . $where_cond . ' '.$comp.' \'' . $where_valor . '\' ORDER BY ' . $tabla . '.' . $campos[0] . ' ASC';
		elseif($session->get_var('tipo_acceso') == 3 && $tabla == 'notificaciones')//Total
			return 'SELECT ' . $tabla . '.* FROM ' . $tabla . ' WHERE ' . $where_cond . ' '.$comp.' \'' . $where_valor . '\' ORDER BY ' . $tabla . '.fecha_creacion DESC';
		elseif($session->get_var('tipo_acceso') == 3)//Total
			return 'SELECT ' . $tabla . '.* FROM ' . $tabla . ' WHERE ' . $where_cond . ' '.$comp.' \'' . $where_valor . '\' ORDER BY ' . $tabla . '.' . $campos[0] . ' ASC';
		elseif($session->get_var('tipo_acceso') == 2)//Sede
			return 'SELECT ' . $tabla . '.* FROM usuarios '
				  .'JOIN ' . $tabla . ' ON (usuarios.id_usuario = ' . $tabla . '.usuario_id_creador AND '
				  . $where_cond . ' '.$comp.' \'' . $where_valor . '\') '
				  .'WHERE usuarios.sede_id = ' . $session->get_var('id_sede') . ' '
				  .'ORDER BY ' . $tabla . '.' . $campos[0] . ' ASC';
		elseif($session->get_var('tipo_acceso') == 1)//Sede y creados por él
			return 'SELECT ' . $tabla . '.* FROM usuarios '
				  .'JOIN ' . $tabla . ' ON (usuarios.id_usuario = ' . $tabla . '.usuario_id_creador AND '
				  . $where_cond . ' '.$comp.' \'' . $where_valor . '\') '
				  .'WHERE usuarios.sede_id = ' . $session->get_var('id_sede') . ' AND '
				  .'usuarios.id_usuario = ' . $session->get_var('id_usuario') . ' '
				  .'ORDER BY ' . $tabla . '.' . $campos[0] . ' ASC';
	}
}
?>