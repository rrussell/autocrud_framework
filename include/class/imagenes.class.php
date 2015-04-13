<?php

/**
 * @author Rodrigo Russell G.
 * Created on 03-06-2010
 */

if(count(get_included_files()) == 1)
	die('The file ' . basename(__FILE__) . ' cannot be accessed directly, use include instead');

/**
 * Clase para el manejo y guardado de imágenes
 */
class Imagenes {
	
	var $_file = NULL;
	var $_mensaje = NULL;
    
    /**
     * Returns $_file.
     *
     * @see Imagenes::$_file
     */
    public function get_file() {
        return $this->_file;
    }
    
    /**
     * Sets $_file.
     *
     * @param object $_file
     * @see Imagenes::$_file
     */
    public function set_file($_file) {
        $this->_file = $_file;
    }
    
    /**
     * Returns $_mensaje.
     *
     * @see Imagenes::$_mensaje
     */
    public function get_mensaje() {
        return $this->_mensaje;
    }
    
    /**
     * Sets $_mensaje.
     *
     * @param object $_mensaje
     * @see Imagenes::$_mensaje
     */
    public function set_mensaje($_mensaje) {
        $this->_mensaje = $_mensaje;
    }
	
	/**
	 * Constructor
	 * @return 
	 */
	function Imagenes() {		
		
	}
	
	public function cargar($archivo) {
		$imagen = $archivo;
		
		if(!empty($imagen['error'])) {
			switch($imagen['error']) {
				case '1':
					$this->set_mensaje('The uploaded file exceeds the upload_max_filesize directive in php.ini');
					return FALSE;
				case '2':
					$this->set_mensaje('The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form');
					return FALSE;
				case '3':
					$this->set_mensaje('The uploaded file was only partially uploaded');
					return FALSE;
				case '4':
					$this->set_mensaje('No file was uploaded');
					return FALSE;
				case '6':
					$this->set_mensaje('Missing a temporary folder');
					return FALSE;
				case '7':
					$this->set_mensaje('Failed to write file to disk');
					return FALSE;
				case '8':
					$this->set_mensaje('File upload stopped by extension');
					return FALSE;
				case '999':
					$this->set_mensaje('Error desconocido');
					return FALSE;
				default:
					$this->set_mensaje('No error code avaiable');
					return FALSE;
			}
		}
		elseif(empty($imagen['tmp_name']) || $imagen['tmp_name'] == 'none') {
			$this->set_mensaje('No file was uploaded..');
			return FALSE;
		}
		else  {
			$this->set_file($imagen);
			return TRUE;	
		}
	}
	
	/**
	 * Obtiene los datos de la imagen y los retorna en un arreglo
	 * @return 
	 */
	public function info_imagen() {
		$file = $this->get_file();
		$data = @getimagesize($file['tmp_name']);
		$datos_imagen = array(
			'tmp_name' => $file['tmp_name'],
			'nombre' => $file['name'],
			'tamaño' => @filesize($file['tmp_name']),
			'ancho' => $data[0],
			'alto' => $data[1],
			'tipo' => $data['mime']
		);
		return $datos_imagen;
	}
	
	/**
	 * Redimenciona la imagen y la almacena en un directorio temporal
	 * @param object $anchura
	 * @param object $hmax
	 * @param object $calidad 0-9 [optional]
	 * @return 
	 */
	function redimencionar($anchura, $hmax, $calidad = 100) {
		$functions = new Functions();
		$file = $this->get_file();
		$datos = @getimagesize($file['tmp_name']);
		$savename = $functions->keygen() . '.jpg';
		
		if($calidad > 100)
			$calidad = 100;
		if($calidad < 0)
			$calidad = 0;
		
		if($datos[2] == 1)
			$img = @imagecreatefromgif($file['tmp_name']);
		if($datos[2] == 2)
			$img = @imagecreatefromjpeg($file['tmp_name']);
		if($datos[2] == 3)
			$img = @imagecreatefrompng($file['tmp_name']);
		
		if($datos[0] < $anchura && $datos[1] < $hmax) {
			$anchura = $datos[0];
			$altura = $datos[1];
		} else {
			$ratio = ($datos[0] / $anchura);
			$altura = ($datos[1] / $ratio);
			if($altura > $hmax) {
				$anchura2 = $hmax * $anchura / $altura;
				$altura = $hmax;
				$anchura = $anchura2;
			}
		}
		
		$imagen = imagecreatetruecolor($anchura, $altura);
		imagecopyresampled($imagen, $img, 0, 0, 0, 0, $anchura, $altura, $datos[0], $datos[1]);
		imagejpeg($imagen, TEMP_DIR . $savename, $calidad);
		imagedestroy($imagen);
		$arreglo = array(
			'ruta' => TEMP_DIR,
			'nombre' => $savename,
			'ancho' => $anchura,
			'alto' => $anchura
		);
		return $arreglo;
	}
}
?>