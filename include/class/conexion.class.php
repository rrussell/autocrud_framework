<?php

/**
 * @author Rodrigo Russell G.
 * Created on 14-04-2010
 */

if(count(get_included_files()) == 1)
	die('The file ' . basename(__FILE__) . ' cannot be accessed directly, use include instead');

define('DB_CONNECT_ERROR', 'Error conectando a la base de datos');
define('DB_SELECT_ERROR', 'Error seleccionando la base de datos');
define('QUERY_ERROR', 'Error ejecutando la siguiente consulta: ');
define('DB_SET_CODING_ERROR', 'Error intentando configurar ' . SITE_CODING);

/**
 * Clase que maneja la coneccion a la Base de Datos
 */
class Conexion {	
	var $_link = NULL;
	var $_result_query = NULL;
	var $_mensaje = NULL;
    
    /**
     * Returns $_link.
     *
     * @see Conexion::$_link
     */
    public function get_link() {
        return $this->_link;
    }
    
    /**
     * Sets $_link.
     *
     * @param object $_link
     * @see Conexion::$_link
     */
    public function set_link($_link) {
        $this->_link = $_link;
    }
    
    /**
     * Returns $_mensaje.
     *
     * @see Conexion::$_mensaje
     */
    public function get_mensaje() {
        return $this->_mensaje;
    }
    
    /**
     * Sets $_mensaje.
     *
     * @param object $_mensaje
     * @see Conexion::$_mensaje
     */
    public function set_mensaje($_mensaje) {
        $this->_mensaje = $_mensaje;
    }
    
    /**
     * Returns $_result_query.
     *
     * @see Conexion::$_result_query
     */
    public function get_result_query() {
        return $this->_result_query;
    }
    
    /**
     * Sets $_result_query.
     *
     * @param object $_result_query
     * @see Conexion::$_result_query
     */
    public function set_result_query($_result_query) {
        $this->_result_query = $_result_query;
    }
	
	/**
	 * Constructor
	 * @return 
	 */
	function Conexion() {
		if($this->reconectar()) {
			$this->_link = mysql_connect(DB_HOST, DB_USER, DB_PASS) OR $this->error(DB_CONNECT_ERROR);
			mysql_select_db(DB_NAME, $this->get_link()) OR $this->error(DB_SELECT_ERROR);
			mysql_query('SET NAMES "UTF8"') OR $this->error(DB_SET_CODING_ERROR);
		}
	}
	
	/**
	 * Destructor
	 * @return 
	 */
	function Conexion_Destroy() {
		if(!mysql_close($this->get_link()))
			$this->error('Error al cerrar conexion');
	}
	
	/**
	 * 
	 * @param object $mensaje
	 */
	public function error($mensaje = '') {
		$this->set_mensaje(mysql_error() . '<br />' . $mensaje);
	}
	
	/**
	 * Verifica si la coneccion hay que  reconectarla o no
	 * @return 
	 */
	private function reconectar() {
		if($this->get_link() != NULL)
			if(mysql_ping($this->get_link()))
				return FALSE;
		
		return TRUE;
	}
	
	/**
	 * Obtiene los campos de la tabla dada
	 * @param object $tabla
	 * @return 
	 */
	public function get_campos($tabla) {
		$campos = array();
		
		$data = $this->select_query('DESCRIBE ' . $tabla);
		foreach($data as $row)
			//$row['Field'].','.$row['Type'].','.$row['Null'].','.$row['Key'].','.$row['Default'].','.$row['Extra'];
			$campos[] = $row['Field'];
		return $campos;
	}
	
	/**
	 * Ejecuta mysql_free_result para el resultado de la consulta dada
	 * @param object $result
	 * @return 
	 */
	private function free_result($result) {
		return @mysql_free_result($result);
	}
	
	/**
	 * Ejecuta una consulta SQL
	 * @param object $sql
	 * @return 
	 */
	public function query($sql) {
		$this->set_result_query(mysql_query($sql, $this->get_link()));
		$this->error(QUERY_ERROR . $sql);
		return $this->get_result_query();
	}
	
	/**
	 * Devuelve el numero de filas devueltas por el último SELECT
	 * @return 
	 */
	public function num_rows() {
		return mysql_num_rows($this->get_result_query());
	}
	
	/**
	 * Ejecuta una consulta SQL SELECT y devuelve el array con los datos
	 * @param object $sql [optional]
	 * @return 
	 */
	//($fields, $where = null, $order = null, $limit = null, $offset = null, $join = null)
	public function select_query($sql) {
		if(is_string($sql)) {
			$this->query($sql);
			$array = array();
			while($row = @mysql_fetch_array($this->get_result_query()))
				$array[] = $row;  
			foreach($array as $key => $value) {
				foreach($value as $campos => $dato)
					$value[$campos] = mysql_real_escape_string($dato);//addslashes($dato);
			}
			$this->free_result($this->get_result_query());
			return $array;
		}
		return FALSE;		
	} 
	
	/**
	 * Ejecuta una consulta SQL INSERT y devuelve TRUE si es exitosa, FALSE si no lo es
	 * @param object $tabla [optional]
	 * @param object $campo_valor [optional]
	 * @return 
	 */
	public function insert_query($tabla = '', $campo_valor = array(), $filtro = FALSE, $filtroOBJECT = FALSE) {
		$sanitize = new Sanitizer();
		if($filtro)
			$sanitize->_allowedTags = TRUE;
		if($filtroOBJECT)
			$sanitize->_allowObjects = TRUE;
		
		foreach($campo_valor as $key => $value) {
			$campo_valor[$key] = $sanitize->sanitize($value);
		}
		$campos = implode(',', array_keys($campo_valor));
		$valores = '\'' . implode('\',\'', $campo_valor) . '\'';
		
		$query = 'INSERT INTO ' . $tabla . ' (' . $campos . ') VALUES (' . $valores . ')';
		if($this->query($query))
			return TRUE;
		else
			return FALSE;
	}
	
	/**
	 * Ejecuta una consulta SQL UPDATE y devuelve TRUE si es exitosa, FALSE si no lo es
	 * @param object $tabla [optional]
	 * @param object $campo_set_valor [optional]
	 * @param object $campos_where [optional]
	 * @return 
	 */
	public function update_query($tabla = '', $campo_set_valor = array(), $campos_where = array(), $filtroHTML = FALSE, $filtroOBJECT = FALSE) {
		$sanitize = new Sanitizer();
		if($filtroHTML)
			$sanitize->_allowedTags = TRUE;
		if($filtroOBJECT)
			$sanitize->_allowObjects = TRUE;
		$cambios = '';
		$condiciones = '';
		
		foreach($campo_set_valor as $campo => $valor) {
			$cambios .= $campo . ' = \'' . $sanitize->sanitize($valor) . '\', ';
		}
		$cambios = substr($cambios, 0, strlen($cambios) - 2);
		
		foreach($campos_where as $campo => $valor) {
			$condiciones .= $campo . ' = \'' . $valor . '\' AND ';
		}
		$condiciones = substr($condiciones, 0, strlen($condiciones) - 4);
		
		$query = 'UPDATE ' . $tabla . ' SET ' . $cambios . ' WHERE ' . $condiciones; 
		if($this->query($query))
			return TRUE;
		else
			return FALSE;
	}
	
	/**
	 * Ejecuta una consulta SQL DELETE y devuelve TRUE si es exitosa, FALSE si no lo es
	 * @param object $tabla [optional]
	 * @param object $where [optional]
	 * @return 
	 */
	public function delete_query($tabla = '', $campos_where = array()) {
		$condiciones = '';
		
		foreach($campos_where as $campo => $valor) {
			$condiciones .= $campo . ' = \'' . $valor . '\' AND ';
		}
		$condiciones = substr($condiciones, 0, strlen($condiciones) - 4);		
		
		$query = 'DELETE FROM ' . $tabla . ' WHERE ' . $condiciones;
		if($this->query($query))
			return TRUE;
		else
			return FALSE;
	}
}
?>