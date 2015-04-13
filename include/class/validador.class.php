<?php

/**
 * @author Rodrigo Russell G.
 * Created on 30-04-2010
 */

if(count(get_included_files()) == 1)
	die('The file ' . basename(__FILE__) . ' cannot be accessed directly, use include instead');

/**
 * Clase para validar datos
 */
class Validador {
	
	var $_label_errors = '';
    
    /**
     * Returns $_label_errors.
     *
     * @see Validador::$_label_errors
     */
    public function get_label_errors() {
        return $this->_label_errors;
    }
    
    /**
     * Sets $_label_errors.
     *
     * @param object $_label_errors
     * @see Validador::$_label_errors
     */
    public function set_label_errors($_label_errors) {
        $this->_label_errors .= $_label_errors;
    }
    
	
	/**
	 * Constructor
	 * @return 
	 */
	function Validador() {
		
	}
	
	/**
	 * Comprueba si la variable es de tipo y largo correctos
	 * @param object $string
	 * @param object $type
	 * @param object $length
	 * @return 
	 */
	public function check_var($string, $type, $length) {
		$type = 'is_' . $type;
		if(!$type($string))
			return FALSE;
		elseif(empty($string))
			return FALSE;
		elseif(strlen($string) > $length)
			return FALSE;
		else
			return TRUE;
	}
	
	/**
	 * Valida si un campo esta vacio
	 * @param object $str
	 * @return 
	 */
	private function requerido($str) {
		if(empty($str))
			return FALSE;
		else
			return TRUE;
	}
	
	/**
	 * Valida si el formato del mail es válido
	 * @param object $mail
	 * @return 
	 */
	public function mail($mail) {
		if(!filter_var($mail, FILTER_VALIDATE_EMAIL))
			return FALSE;
		if(!preg_match('/^[[:alnum:]]+((.|_)[[:alnum:]]+)*@([[:alnum:]]+.)+[[:alnum:]]{2,6}$/', $mail))
			return FALSE;
		if(preg_match('/^([a-zA-Z0-9._]+)@([a-zA-Z0-9.-]+)\.([a-zA-Z]{2,4})$/', $mail))
			return TRUE;
		return FALSE;
	}
	
	/**
	 * Recibe los inputs y sus reglas y valida, si no retorna FALSE más un mensaje
	 * @param object $inputs [optional]
	 * @return 
	 */
	public function validar_form($inputs = array()) {
		$label = '';$reglas = array();$input = '';$cont = 0;
		
		foreach($inputs as $a => $value) {
			foreach($value as $key => $valor) {
				switch($key) {
					case 'label':
						$label = $valor;
						break;
					case 'regla':
						$reglas = explode("|", $valor);
						break;
					case 'valor':
						$input = $valor;
						break;
				}
			}
			foreach($reglas as $regla) {
				if($regla != '')
					if(!$this->$regla($input)) {
						$this->set_label_errors('ERROR: ' . $regla . ' = ' .$label . '<br />');
						$cont++;
					}
			}
		}
		if($cont == 0)
			return TRUE;
		else
			return FALSE;
	}
}
?>