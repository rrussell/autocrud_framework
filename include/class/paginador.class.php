<?php

/**
 * @author Rodrigo Russell G.
 * Created on 03-06-2010
 */

if(count(get_included_files()) == 1)
	die('The file ' . basename(__FILE__) . ' cannot be accessed directly, use include instead');

/**
 * Clase que maneja las paginas de una consulta sql
 */
class Paginador {
	
	var $_sql = NULL;
	var $_sql_limit = NULL;
	var $_url = NULL;
	var $_pag_actual = NULL;
	var $_reg_por_pag = NULL;
	var $_mostrar_errores = NULL;
	var $_separador = NULL;
	var $_nav_anterior = NULL;
	var $_nav_siguiente = NULL;
	var $_nav_primera = NULL;
	var $_nav_ultima = NULL;
	var $_nav_num_enlaces = NULL;
	var $_total_paginas = NULL;
	var $_total_registros = NULL;
	var $_enlace = NULL;
	var $_navegacion = NULL;
	var $_result = NULL;
	var $_info = NULL;
	var $_mensaje = NULL;
    
    /**
     * Returns $_mensaje.
     *
     * @see Paginador::$_mensaje
     */
    public function get_mensaje() {
        return $this->_mensaje;
    }
    
    /**
     * Sets $_mensaje.
     *
     * @param object $_mensaje
     * @see Paginador::$_mensaje
     */
    public function set_mensaje($_mensaje) {
        $this->_mensaje = $_mensaje;
    }
    
    /**
     * Returns $_info.
     *
     * @see Paginador::$_info
     */
    public function get_info() {
        return $this->_info;
    }
    
    /**
     * Sets $_info.
     *
     * @param object $_info
     * @see Paginador::$_info
     */
    public function set_info($_info) {
        $this->_info = $_info;
    }
	
    /**
     * Returns $_mostrar_errores.
     *
     * @see Paginador::$_mostrar_errores
     */
    public function get_mostrar_errores() {
        return $this->_mostrar_errores;
    }
    
    /**
     * Sets $_mostrar_errores.
     *
     * @param object $_mostrar_errores
     * @see Paginador::$_mostrar_errores
     */
    public function set_mostrar_errores($_mostrar_errores) {
        $this->_mostrar_errores = $_mostrar_errores;
    }
    
    /**
     * Returns $_nav_anterior.
     *
     * @see Paginador::$_nav_anterior
     */
    public function get_nav_anterior() {
        return $this->_nav_anterior;
    }
    
    /**
     * Sets $_nav_anterior.
     *
     * @param object $_nav_anterior
     * @see Paginador::$_nav_anterior
     */
    public function set_nav_anterior($_nav_anterior) {
        $this->_nav_anterior = $_nav_anterior;
    }
    
    /**
     * Returns $_nav_primera.
     *
     * @see Paginador::$_nav_primera
     */
    public function get_nav_primera() {
        return $this->_nav_primera;
    }
    
    /**
     * Sets $_nav_primera.
     *
     * @param object $_nav_primera
     * @see Paginador::$_nav_primera
     */
    public function set_nav_primera($_nav_primera) {
        $this->_nav_primera = $_nav_primera;
    }
    
    /**
     * Returns $_nav_siguiente.
     *
     * @see Paginador::$_nav_siguiente
     */
    public function get_nav_siguiente() {
        return $this->_nav_siguiente;
    }
    
    /**
     * Sets $_nav_siguiente.
     *
     * @param object $_nav_siguiente
     * @see Paginador::$_nav_siguiente
     */
    public function set_nav_siguiente($_nav_siguiente) {
        $this->_nav_siguiente = $_nav_siguiente;
    }
    
    /**
     * Returns $_nav_ultima.
     *
     * @see Paginador::$_nav_ultima
     */
    public function get_nav_ultima() {
        return $this->_nav_ultima;
    }
    
    /**
     * Sets $_nav_ultima.
     *
     * @param object $_nav_ultima
     * @see Paginador::$_nav_ultima
     */
    public function set_nav_ultima($_nav_ultima) {
        $this->_nav_ultima = $_nav_ultima;
    }
    
    /**
     * Returns $_pag_actual.
     *
     * @see Paginador::$_pag_actual
     */
    public function get_pag_actual() {
        return $this->_pag_actual;
    }
    
    /**
     * Sets $_pag_actual.
     *
     * @param object $_pag_actual
     * @see Paginador::$_pag_actual
     */
    public function set_pag_actual($_pag_actual) {
        $this->_pag_actual = $_pag_actual;
    }
    
    /**
     * Returns $_reg_por_pag.
     *
     * @see Paginador::$_reg_por_pag
     */
    public function get_reg_por_pag() {
        return $this->_reg_por_pag;
    }
    
    /**
     * Sets $_reg_por_pag.
     *
     * @param object $_reg_por_pag
     * @see Paginador::$_reg_por_pag
     */
    public function set_reg_por_pag($_reg_por_pag) {
        $this->_reg_por_pag = $_reg_por_pag;
    }
    
    /**
     * Returns $_separador.
     *
     * @see Paginador::$_separador
     */
    public function get_separador() {
        return $this->_separador;
    }
    
    /**
     * Sets $_separador.
     *
     * @param object $_separador
     * @see Paginador::$_separador
     */
    public function set_separador($_separador) {
        $this->_separador = $_separador;
    }
    
    /**
     * Returns $_sql.
     *
     * @see Paginador::$_sql
     */
    public function get_sql() {
        return $this->_sql;
    }
    
    /**
     * Sets $_sql.
     *
     * @param object $_sql
     * @see Paginador::$_sql
     */
    public function set_sql($_sql) {
        $this->_sql = $_sql;
    }
    
    /**
     * Returns $_sql_limit.
     *
     * @see Paginador::$_sql_limit
     */
    public function get_sql_limit() {
        return $this->_sql_limit;
    }
    
    /**
     * Sets $_sql_limit.
     *
     * @param object $_sql_limit
     * @see Paginador::$_sql_limit
     */
    public function set_sql_limit($_sql_limit) {
        $this->_sql_limit = $_sql_limit;
    }
	
    /**
     * Returns $_nav_num_enlaces.
     *
     * @see Paginador::$_nav_num_enlaces
     */
    public function get_nav_num_enlaces() {
        return $this->_nav_num_enlaces;
    }
    
    /**
     * Sets $_nav_num_enlaces.
     *
     * @param object $_nav_num_enlaces
     * @see Paginador::$_nav_num_enlaces
     */
    public function set_nav_num_enlaces($_nav_num_enlaces) {
        $this->_nav_num_enlaces = $_nav_num_enlaces;
    }
    
    /**
     * Returns $_total_paginas.
     *
     * @see Paginador::$_total_paginas
     */
    public function get_total_paginas() {
        return $this->_total_paginas;
    }
    
    /**
     * Sets $_total_paginas.
     *
     * @param object $_total_paginas
     * @see Paginador::$_total_paginas
     */
    public function set_total_paginas($_total_paginas) {
        $this->_total_paginas = $_total_paginas;
    }
    
    /**
     * Returns $_total_registros.
     *
     * @see Paginador::$_total_registros
     */
    public function get_total_registros() {
        return $this->_total_registros;
    }
    
    /**
     * Sets $_total_registros.
     *
     * @param object $_total_registros
     * @see Paginador::$_total_registros
     */
    public function set_total_registros($_total_registros) {
        $this->_total_registros = $_total_registros;
    }
    
    /**
     * Returns $_enlace.
     *
     * @see Paginador::$_enlace
     */
    public function get_enlace() {
        return $this->_enlace;
    }
    
    /**
     * Sets $_enlace.
     *
     * @param object $_enlace
     * @see Paginador::$_enlace
     */
    public function set_enlace($_enlace) {
        $this->_enlace = $_enlace;
    }
    
    /**
     * Returns $_navegacion.
     *
     * @see Paginador::$_navegacion
     */
    public function get_navegacion() {
        return $this->_navegacion;
    }
    
    /**
     * Sets $_navegacion.
     *
     * @param object $_navegacion
     * @see Paginador::$_navegacion
     */
    public function set_navegacion($_navegacion) {
        $this->_navegacion = $_navegacion;
    }
    
    /**
     * Returns $_result.
     *
     * @see Paginador::$_result
     */
    public function get_result() {
        return $this->_result;
    }
    
    /**
     * Sets $_result.
     *
     * @param object $_result
     * @see Paginador::$_result
     */
    public function set_result($_result) {
        $this->_result = $_result;
    }
    
    /**
     * Returns $_url.
     *
     * @see Paginador::$_url
     */
    public function get_url() {
        return $this->_url;
    }
    
    /**
     * Sets $_url.
     *
     * @param object $_url
     * @see Paginador::$_url
     */
    public function set_url($_url) {
        $this->_url = $_url;
    }
	
	/**
	 * Constructor
	 * @return 
	 */
	function Paginador() {
		
	}
	
	/**
	 * Verifica las variables, si no están seteadas les da los valores por defecto.
	 * @return 
	 */
	private function verificar_variables() {
		
		if($this->get_sql() == NULL) {
			$this->set_mensaje('ERROR PAGINADOR: No se ha definido la consulta a paginar');
			return FALSE;
		}
		if($this->get_pag_actual() == NULL)
			$this->set_pag_actual(1);
		if($this->get_reg_por_pag() == NULL)
			$this->set_reg_por_pag(10);
		if($this->get_mostrar_errores() == NULL)
			$this->set_mostrar_errores(TRUE);
		if($this->get_separador() == NULL)
			$this->set_separador(' ');
		if($this->get_nav_anterior() == NULL)
			$this->set_nav_anterior('&laquo; Anterior');
		if($this->get_nav_siguiente() == NULL)
			$this->set_nav_siguiente('Siguiente &raquo;');
		if($this->get_nav_primera() == NULL)
			$this->set_nav_primera('&laquo;&laquo; Primera');
		if($this->get_nav_ultima() == NULL)
			$this->set_nav_ultima('Ultima &raquo;&raquo;');
		
		return TRUE;
	}
	
	private function totales() {
		
		$result = mysql_query($this->get_sql());
		if($result == FALSE && $_pagi_mostrar_errores == TRUE) {
			$this->set_mensaje('Error en la consulta de conteo de registros: ' . $_pagi_sql . '. Mysql dijo: ' . mysql_error());
			return FALSE;
		}
		$this->set_total_registros(mysql_num_rows($result));
		$this->set_total_paginas(ceil($this->get_total_registros() / $this->get_reg_por_pag()));
		
		return TRUE;
	}
	
	/**
	 * Propaga las variables deseadas por url
	 * @return 
	 */
	private function variables_url() {
		$query_string = '&';
		
		if(isset($_GET['pg']))
			unset($_GET['pg']);
		if(isset($_GET['s']))
			unset($_GET['s']);
		if(isset($_GET['ss']))
			unset($_GET['ss']);
		
		$propagar = array_keys($_GET);
		foreach($propagar as $var){
			if(isset($GLOBALS[$var]))
				$query_string .= $var . '=' . $GLOBALS[$var] . '&';
			elseif(isset($_REQUEST[$var]))
				$query_string .= $var . '=' . $_REQUEST[$var] . '&';
		}
		$query_string = substr($query_string, 0, strlen($query_string) - 1);
		$this->set_enlace($query_string);
	}
	
	/**
	 * Genera los enlaces de paginacion
	 * @return 
	 */
	private function paginacion() {
		$navegacion_temporal = array();
		$nav_desde = 1;
		$nav_hasta = 1;
		
		if ($this->get_pag_actual() != 1) {
			$navegacion_temporal[] = '<a href="' . $this->get_url() . '/pg,1' . $this->get_enlace() . '">' . $this->get_nav_primera() . '</a>';
			$navegacion_temporal[] = '<a href="' . $this->get_url() . '/pg,' . ($this->get_pag_actual() - 1) . $this->get_enlace() . '">' . $this->get_nav_anterior() . '</a>';
		}
		if($this->get_nav_num_enlaces() == NULL) {
			$nav_desde = 1;
			$nav_hasta = $this->get_total_paginas();
		} else {
			$nav_intervalo = ceil($this->get_nav_num_enlaces() / 2) - 1;
			
			$nav_desde = $this->get_pag_actual() - $nav_intervalo;
			$nav_hasta = $this->get_pag_actual() + $nav_intervalo;
			
			if($nav_desde < 1) {
				$nav_hasta -= ($nav_desde - 1);
				$nav_desde = 1;
			}
			if($nav_hasta > $this->get_total_paginas()) {
				$nav_desde -= ($nav_hasta - $this->get_total_paginas());
				$nav_hasta = $this->get_total_paginas();
				if($nav_desde < 1)
					$nav_desde = 1;
			}
		}
		for ($i = $nav_desde; $i <= $nav_hasta; $i++) {
			if ($i == $this->get_pag_actual())
				$navegacion_temporal[] = '<span>' . $i . '</span>';
			else
				$navegacion_temporal[] = '<a href="' . $this->get_url() . '/pg,' . $i . $this->get_enlace() . '">' . $i . '</a>';
		}
		if ($this->get_pag_actual() < $this->get_total_paginas()) {
			$navegacion_temporal[] = '<a href="' . $this->get_url() . '/pg,' . ($this->get_pag_actual() + 1) . $this->get_enlace() . '">' . $this->get_nav_siguiente() . '</a>';
			$navegacion_temporal[] = '<a href="' . $this->get_url() . '/pg,' . $this->get_total_paginas() . $this->get_enlace() . '">' . $this->get_nav_ultima() . '</a>';
		}
		if($this->_total_paginas != 1)
			$this->set_navegacion(implode($this->get_separador(), $navegacion_temporal));
	}
	
	/**
	 * Obtiene los registros de la pagina actual y setea la informacion realizada
	 * @return 
	 */
	private function obtener_registros() {
		$inicio = ($this->get_pag_actual() - 1) * $this->get_reg_por_pag();
		
		$sql_lim = $this->get_sql() . ' LIMIT ' . $inicio . ',' . $this->get_reg_por_pag();
		$this->set_result(mysql_query($sql_lim));
		if($this->get_result() == FALSE && $_pagi_mostrar_errores == TRUE) {
			$this->set_mensaje('Error en la consulta limitada: ' . $sql_lim . '. Mysql dijo: ' . mysql_error());
			return FALSE;
		}
		
		$desde = $inicio + 1;
		$hasta = $inicio + $this->get_reg_por_pag();
		if($hasta > $this->get_total_registros())
			$hasta = $this->get_total_registros();
		
		$this->set_info('Desde el '. $desde . ' hasta el '. $hasta . ' de un total de ' . $this->get_total_registros());
		$this->set_sql_limit($sql_lim);
		
		return TRUE;
	}
	
	/**
	 * Pagina la consulta
	 * @return 
	 */
	public function ejecutar($sql, $url_actual, $num_enlaces = NULL) {
		$this->set_sql($sql);
		$this->set_url($url_actual);
		if($num_enlaces)
			$this->set_nav_num_enlaces($num_enlaces);
		
		if(!$this->verificar_variables())
			return FALSE;
		if(!$this->totales())
			return FALSE;
		$this->variables_url();
		$this->paginacion();
		if(!$this->obtener_registros())
			return FALSE;
		
		$data = array(
			//Cadena que contiene la barra de navegación con los enlaces a las diferentes páginas.
			//Ejemplo: "<<primera | <anterior | 1 | 2 | 3 | 4 | siguiente> | última>>".
			'navegacion' => $this->get_navegacion(),
			
			//Identificador del resultado de la consulta a la BD para los registros de la página actual. 
 			//Listo para ser "pasado" por una función como mysql_fetch_row(), mysql_fetch_array(), mysql_fetch_assoc(), etc.
			'result' => $this->get_result(),
			
			//Cadena que contiene información sobre los registros de la página actual.
 			//Ejemplo: "desde el 16 hasta el 30 de un total de 123";
			'info' => $this->get_info(),
			
			//Cadena que contiene la consulta agregando los limits correspondientes por página
			//Ejemplo: SELECT * FROM tabla LIMIT 0,20
			'query' => $this->get_sql_limit()
		);
		
		return $data;
	}
}
?>