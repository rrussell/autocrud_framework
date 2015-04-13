<?php

/**
 * @author Rodrigo Russell G.
 * Created on 25-04-2010
 */

if(count(get_included_files()) == 1)
	die('The file ' . basename(__FILE__) . ' cannot be accessed directly, use include instead');

/**
 * Clase con funciones para las vistas
 */
class View_functions {
	var $cont;
	
	/**
	 * Constructor
	 * @return 
	 */
	function View_functions() {
		$this->cont = 0;
	}
	
	/**
	 * Revisa si se han logueado o no
	 * @return 
	 */
	public function menu() {
		$session = new Session();
		if($session->get_var('login') && $session->get_var('section') != 'panel')
			return TRUE;
		return FALSE;
	}
	
	/**
	 * Alterna entre dos clases para iteraciones
	 * @param object $clase1
	 * @param object $clase2
	 * @return 
	 */
	public function alternador($clase1, $clase2) {
		if($this->cont % 2 == 0) {
			$this->cont++;
			return $clase1;
		} else {
			$this->cont++;
			return $clase2;
		}
	}
	
	/**
	 * Carga un icono en la vista
	 * @param object $icono [optional]
	 * @param object $alt [optional]
	 * @param object $title [optional]
	 * @return 
	 */
	public function cargar_icono($icono = '', $alt = '', $title = '') {
		return '<img src="'. BASE_URL . ICONS_DIR . $icono . '.png" alt="' . $alt . '" title="' . $title .  '" border="0" />';
	}
	
	/**
	 * Muestra la fecha y hora
	 * @param object $time [optional]
	 * @param object $tipo [optional]
	 * @return 
	 */
	public function fechahora($time = '', $tipo = TRUE) {
		if($tipo)
			return date('d/m/Y h:i:s a', $time);
		else
			return date('d/m/Y H:i:s', $time);
	}
	
	/**
	 * Muestra fecha
	 * @param object $time
	 * @return 
	 */
	public function fecha($time) {
		return date('d/m/Y', $time);
	}
	
	/**
	 * Muestra hora
	 * @param object $time [optional]
	 * @param object $tipo [optional]
	 * @return 
	 */
	public function hora($time = '', $tipo = TRUE) {
		if($tipo)
			return date('h:i:s a', $time);
		else
			return date('H:i', $time);
	}
	
	public function dia($dia) {
		switch($dia) {
			case 1:
				return 'Lunes';
			case 2:
				return 'Martes';
			case 3:
				return 'Miércoles';
			case 4:
				return 'Jueves';
			case 5:
				return 'Viernes';
			case 6:
				return 'Sábado';
			case 7:
				return 'Domingo';
		}
		
	}
	
	/**
	 * Obtiene el valor de una variable de sesion
	 * @return 
	 */
	public function get_var_session($name) {
		$session = new Session();
		return $session->get_var($name);
	}
	
	/**
	 * Muestra o no el contenido según permiso usuario
	 * @param object $modulo
	 * @param object $seccion
	 * @return 
	 */
	public function mostrar($modulo, $seccion) {
		$permisos = new Permisos();
		return $permisos->mostrar($modulo, $seccion);
	}
	
	/**
	 * Devuelve el dato solicitado según condiciones
	 * @param object $tabla
	 * @param object $campo
	 * @param object $cond
	 * @param object $condicion
	 * @return 
	 */
	public function carga_dato($tabla, $campo, $cond, $condicion) {
		$dbcxn = new Conexion();
		$dato = '';
		$query = 'SELECT ' . $campo . ' as dato FROM ' . $tabla . ' WHERE ' . $cond . ' = ' . $condicion . ' LIMIT 0,1';
		$result = $dbcxn->select_query($query);
		foreach($result as $row)
			$dato = $row['dato'];
		return $dato;
	}
	
	/**
	 * Si cumple condicion Perfil-Módulo chequea el Checkbox
	 * @param object $id_perfil
	 * @param object $id_modulo
	 * @param object $valor
	 * @return 
	 */
	public function check($id_perfil, $id_modulo, $valor) {
		$dbcxn = new Conexion();
		$result = $dbcxn->select_query('SELECT valor FROM perfiles_modulos WHERE perfil_id = ' . $id_perfil . ' AND modulo_id = ' . $id_modulo . ' LIMIT 0,1');
		foreach($result as $row) {
			return (preg_match('/' . $valor . '/i', $row['valor'])) ? 'checked="checked"' : '';
		}
		
	}
	
	/**
	 * Transforma un entero a Rut (xx.xxx.xxx-x)
	 * @param object $rut
	 * @param object $type [optional]
	 * @return 
	 */
	function int_to_rut($rut, $update = FALSE) {
		$r = strtoupper(preg_replace('/\.|,|-/', '', $rut));
		$sub_rut = $r;
		$x = 2;
		$s = 0;
		for($i = strlen($sub_rut) - 1; $i >= 0; $i--) {
	  		if($x > 7)
	  			$x = 2;
	  		$s += $sub_rut[$i] * $x;
	  		$x++;
		}
		$dv = 11 - ($s % 11);
		if($dv == 10)
			$dv = 'K';
		if($dv == 11)
			$dv = '0';
		
		if($update)
			return $rut . '-' . $dv;
		else
			return number_format($rut, 0, ',', '.') . '-' . $dv;
	}
	
	/**
	 * Transforma un Rut (xx.xxx.xxx-x) en un entero
	 * @param object $rut
	 * @return 
	 */
	function rut_to_int($rut) {
		$rut = explode('-', $rut);
		$rut = $rut[0];
		$rut = str_replace(array(',', '.'), '', $rut);
		return intval($rut);
	}
	
	/**
	 * Devuelve todas las marcas
	 * @param 
	 * @return 
	 */
	public function obtener_marcas(){
		$dbcnx = new Conexion();
		return $dbcnx->select_query('SELECT * FROM marcas WHERE activo = 1 ORDER BY nombre ASC');
	}
	
	/**
	 * Devuelve todas las categorias
	 * @param 
	 * @return 
	 */
	public function obtener_categorias(){
		$dbcnx = new Conexion();
		return $dbcnx->select_query('SELECT * FROM categorias WHERE activo = 1 ORDER BY nombre ASC');
	}
	
	/**
	 * Obtiene las imagenes del producto
	 * @param object $id_producto
	 * @param object $flag
	 * @return 
	 */
	public function obtener_imagenes($id_producto, $flag){
		$dbcnx = new Conexion();
		$data = '';
		$no_imagen = array(
			'imagen' => 'no_imagen.jpg',
			'nombre' => 'No Disponible'
		);
		/*IF flag == TRUE devolverá uno ELSE varios resultados*/
		if($flag) {
			$data = $dbcnx->select_query('SELECT * FROM img_productos WHERE producto_id = ' . $id_producto . ' AND activo = 1 LIMIT 0,1');
			$data = (isset($data[0])) ? $data[0] : $no_imagen;
		} else {
			$data = $dbcnx->select_query('SELECT * FROM img_productos WHERE producto_id = ' . $id_producto . ' AND activo = 1');
			if(count($data) < 1)
				$data = $no_imagen;
		}
		return $data;
	}
	
	/**
	 * Devuelve true o false dependiendo si el campo elegido esta vacio o no
	 * @param object $numero_producto
	 * @param object $campo
	 * @return 
	 */
	public function esta_vacio($numero_producto, $campo){
		$dbcnx = new Conexion();
		$producto = $dbcnx->select_query('SELECT ' . $campo . ' FROM productos WHERE id_producto = ' . $numero_producto . ' LIMIT 0,1');
		$producto = (isset($producto[0])) ? $producto[0] : FALSE;
		if($producto[$campo] == '' || $producto[$campo] == '-')
			return false;
		else
			return true;
	}
	
	/**
	 * Utilizada para ver los productos comprados y sus nombres
	 * @param object $productos
	 * @return
	 */
	public function ver_productos_venta($productos) {
		$dbcnx = new Conexion();
		
		$return = '<table>';
		$producto = explode(',', $productos);
		for($i = 0; $i < count($producto); $i++) {
			$dato = explode('=', $producto[$i]);
			$nombre = $dbcnx->select_query('SELECT nombre
											FROM productos
											WHERE id_producto = \''.$dato[0].'\'
											LIMIT 0,1');
											
			$nombre = (isset($nombre[0])) ? (strlen($nombre[0]['nombre']) < 30) ? $nombre[0]['nombre'] : substr($nombre[0]['nombre'], 0, 30) . '...' : FALSE;
			$return .= '<tr><td>'.$dato[1].'</td><td>'.$nombre.'</td></tr>';
		}
		$return .= '</table>';
		return $return;
	}
}
?>