<?php

/**
 * @author Rodrigo Russell G.
 * Created on 14-04-2010
 */

if(count(get_included_files()) == 1)
	die('The file ' . basename(__FILE__) . ' cannot be accessed directly, use include instead');

/**
 * Clase con funciones practicas
 */
class Functions {
	
	var $_headers = array(
		'sec_title' => '',
		'meta_keywords' => '',
		'meta_description' => '',
		'current' => ''
	);
	var $_stylesheet = '';
	var $_javascript = '';
	var $_mensaje = '';
    
    /**
     * Returns $_headers.
     *
     * @see Functions::$_headers
     */
    public function get_headers($var) {
        return $this->_headers[$var];
    }
    
    /**
     * Sets $_headers.
     *
     * @param object $_headers
     * @see Functions::$_headers
     */
    public function set_headers($sec_title = '', $meta_keywords = '', $meta_description = '', $current = '') {
        $this->_headers = array(
			'sec_title' => $sec_title,
			'meta_keywords' => $meta_keywords,
			'meta_description' => $meta_description,
			'current' => $current
		);
    }
    
    /**
     * Returns $_javascript.
     *
     * @see Functions::$_javascript
     */
    public function get_javascript() {
        return $this->_javascript;
    }
    
    /**
     * Sets $_javascript.
     *
     * @param object $_javascript
     * @see Functions::$_javascript
     */
    public function add_javascript($_javascript) {
    	if(file_exists(JAVASCRIPT_DIR.$_javascript.'.js')) {
    		$this->_javascript .= '<script type="text/javascript" src="'.BASE_URL.JAVASCRIPT_DIR.$_javascript.'.js"></script>'."\n";
			return TRUE;
    	} else {
    		$this->set_mensaje('No existe el archivo ' . $_javascript . '.js');
			return FALSE;
    	}
    }
    
	/**
	 * Agrega un script de Javascript al header
	 * @param object $script
	 * @return 
	 */
	public function add_script($script) {
    	$this->_javascript .= '<script type="text/javascript">'."\n";
		$this->_javascript .= $script . "\n";
		$this->_javascript .= '</script>'."\n";
		return TRUE;
    }
	
    /**
     * Returns $_stylesheet.
     *
     * @see Functions::$_stylesheet
     */
    public function get_stylesheet() {
        return $this->_stylesheet;
    }
    
    /**
     * Sets $_stylesheet.
     *
     * @param object $_stylesheet
     * @see Functions::$_stylesheet
     */
    public function add_stylesheet($_stylesheet) {
    	if(file_exists(STYLESHEET_DIR.$_stylesheet.'.css')) {
    		$this->_stylesheet .= '<link rel="stylesheet" href="'.BASE_URL.STYLESHEET_DIR.$_stylesheet.'.css"  type="text/css" media="screen" />'."\n";
			return TRUE;
    	} else {
    		$this->set_mensaje('No existe el archivo ' . $_stylesheet . '.css');
			return FALSE;
    	}
    }
    
    /**
     * Returns $_mensaje.
     *
     * @see Functions::$_mensaje
     */
    public function get_mensaje() {
        return $this->_mensaje;
    }
    
    /**
     * Sets $_mensaje.
     *
     * @param object $_mensaje
     * @see Functions::$_mensaje
     */
    public function set_mensaje($_mensaje) {
        $this->_mensaje = $_mensaje;
    }
	
	/**
	 * Constructor
	 * @return 
	 */
	function Functions() {
		
	}
	
	/**
	 * Carga la vista y le pasa las variables a utilizar en ella
	 * @param object $view [optional]
	 * @param object $variables [optional]
	 * @return 
	 */
	public function cargar_view($view = '', $variables = array()) {
		if(!isset($sec_title)) $sec_title = ($this->get_headers('sec_title') != '') ? ' | ' . $this->get_headers('sec_title') : '';
		if(!isset($meta_keywords)) $meta_keywords = $this->get_headers('meta_keywords');
		if(!isset($meta_description)) $meta_description = $this->get_headers('meta_description');
		if(!isset($javascript)) $javascript = $this->get_javascript();
		if(!isset($stylesheet)) $stylesheet = $this->get_stylesheet();
		if(!isset($current)) $current = $this->get_headers('current');
		
		if(is_array($variables)) {
			foreach($variables as $variable => $data) {
				$nombreVariable = $variable;
				$$nombreVariable = $data;
			}
		}
		
		if(file_exists(VIEWS_DIR . $view . '.php')) {
			cargar_class('view_functions');
			$f = new View_functions();
			require_once(VIEWS_DIR. 'header.php');
			require_once(VIEWS_DIR. $view . '.php');
			require_once(VIEWS_DIR. 'footer.php');
		} else {
			$this->redireccionar('errors');
		}
	}
	
	public function cargar_view_error($view = '') {
		if(!isset($sec_title)) $sec_title = ($this->get_headers('sec_title') != '') ? ' - ' . $this->get_headers('sec_title') : '';
		if(!isset($meta_keywords)) $meta_keywords = $this->get_headers('meta_keywords');
		if(!isset($meta_description)) $meta_description = $this->get_headers('meta_description');
		if(!isset($stylesheet)) $stylesheet = $this->get_stylesheet();
		
		if(file_exists(VIEWS_DIR . $view . '.php'))
			require_once(VIEWS_DIR. $view . '.php');
	}
	
	/**
	 * Carga la vista del sitio y le pasa las variables a utilizar en ella
	 * @param object $view [optional]
	 * @param object $variables [optional]
	 * @return 
	 */
	public function cargar_view_site($view = '', $variables = array()) {
		if(!isset($sec_title)) $sec_title = ($this->get_headers('sec_title') != '') ? ' | ' . $this->get_headers('sec_title') : '';
		if(!isset($meta_keywords)) $meta_keywords = $this->get_headers('meta_keywords');
		if(!isset($meta_description)) $meta_description = $this->get_headers('meta_description');
		if(!isset($javascript)) $javascript = $this->get_javascript();
		if(!isset($stylesheet)) $stylesheet = $this->get_stylesheet();
		if(!isset($current)) $current = $this->get_headers('current');
		
		if(is_array($variables)) {
			foreach($variables as $variable => $data) {
				$nombreVariable = $variable;
				$$nombreVariable = $data;
			}
		}
		
		if(file_exists(VIEWS_DIR . SITE_DIR . $view . '.php')) {
			cargar_class('view_functions');
			$f = new View_functions();
			require_once(VIEWS_DIR . SITE_DIR . 'header.php');
			require_once(VIEWS_DIR . SITE_DIR . $view . '.php');
			require_once(VIEWS_DIR . SITE_DIR . 'footer.php');
		} else {
			$this->redireccionar('errors');
		}
	}
	
	/**
	 * Redirecciona a la url dada dentro del sitio
	 * @param object $url
	 * @return 
	 */
	public function redireccionar($url) {
		return header('Location: ' . BASE_URL . $url);
	}
	
	/**
	 * Genera una clave aleatoria y se le aplica un md5 para mas aleatoriedad y seguridad
	 * @return string
	 */
	public function keygen() {
		$keychars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$length = 25;
		$randkey = '';
		$max = strlen($keychars) - 1;
		for($i = 0 ; $i < $length; $i++) {
			$randkey .= substr($keychars, rand(0, $max), 1);
		}
		return md5($randkey);
	}
	
	/**
	 * Guarda el archivo en la ruta dada con un nombre random
	 * @param object $file
	 * @param object $ruta
	 * @return 
	 */
	public function guardar_archivo($file, $ruta, $dim_img = array(800, 470), $thumb = FALSE, $dim_thumb = array(250, 250)) {
		$flag = TRUE;
		$nombre = '';
		$extension = explode('.', $file['name']);
		$extension = '.' . strtolower($extension[count($extension) - 1]);
		while($flag) {
			$nombre = $this->keygen();
			if(!file_exists($ruta . $nombre . $extension))
				$flag = FALSE;
		}
		
		if($extension == '.bmp' || $extension == '.gif' || $extension == '.jpeg' || $extension == '.jpg' ||
		   $extension == '.png' || $extension == '.tiff') {
			$nombre = $this->resizesave($file['tmp_name'], $dim_img[0], $dim_img[1], $ruta, $nombre . $extension);
			if($thumb)
				//$this->savethumb($file['tmp_name'], $dim_thumb[0], $dim_thumb[1], $ruta, $nombre);
				$this->resizesave($file['tmp_name'], $dim_thumb[0], $dim_thumb[1], $ruta, 't_' . $nombre);
			return $nombre;
		} else {
			if(!move_uploaded_file($file['tmp_name'], $ruta . $nombre . $extension)) {
				$this->set_mensaje('Hubo un error subiendo el archivo');
				return FALSE;
			}
		}
		return $nombre . $extension;
	}
	
	/**
	 * Elimina el archivo actual y lo reemplaza por el nuevo
	 * @param object $file
	 * @param object $ruta
	 * @param object $archivo_viejo
	 * @return 
	 */
	public function modificar_archivo($file, $ruta, $archivo_viejo, $dim_img = array(800, 470), $thumb = FALSE, $dim_thumb = array(250, 250)) {
		unlink($ruta . $archivo_viejo);
		unlink($ruta . 't_' . $archivo_viejo);
		
		return $this->guardar_archivo($file, $ruta, $dim_img, $thumb, $dim_thumb);
	}
	
	/**
	 * Mueve un archivo de $origen a $destino
	 * @param object $origen
	 * @param object $destino
	 * @return 
	 */
	function move($origen, $destino){
		if(!copy($origen, $destino))
			return FALSE;
		if(!unlink($origen))
			return FALSE;
		return TRUE;
	}
	
	/**
	 * Redimenciona y guarda imagen en ruta especificada
	 * @param object $nombre
	 * @param object $anchura
	 * @param object $hmax
	 * @param object $dir
	 * @param object $nombre_final
	 * @return 
	 */
	function resizesave($nombre, $anchura, $hmax, $dir, $nombre_final) {
		$datos = getimagesize($nombre);
		if($datos[2] == 1)
			$img = @imagecreatefromgif($nombre);
		if($datos[2] == 2)
			$img = @imagecreatefromjpeg($nombre);
		if($datos[2] == 3)
			$img = @imagecreatefrompng($nombre);
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
		
		$savename = $nombre_final;
		
		$thumb = imagecreatetruecolor($anchura, $altura);
		if($datos[2] == 3) {
			imagealphablending($thumb, false);
			imagecopyresampled($thumb, $img, 0, 0, 0, 0, $anchura, $altura, $datos[0], $datos[1]);
			imagealphablending($thumb, true);
			imagesavealpha($thumb, true);
			imagepng($thumb, $dir . $savename, 0);
		} else {
			imagecopyresampled($thumb, $img, 0, 0, 0, 0, $anchura, $altura, $datos[0], $datos[1]);
			imagejpeg($thumb, $dir . $savename, 100);
		}
		imagedestroy($thumb);
		
		
		return $savename;
	}
	
	/**
	 * Redimensiona y guarda thumbnail
	 * @param object $nombre nombre de ubicación del archivo (path)
	 * @param object $anchura anchura maxima
	 * @param object $hmax altura maxima
	 * @param object $dir directorio donde se guardará
	 * @param object $filename nombre de archivo
	 */
	function savethumb($nombre, $ancho, $alto, $dir, $filename) {
		$datos = getimagesize($nombre);
		if($datos[2]== 1)
			$img = @imagecreatefromgif($nombre);
		if($datos[2]== 2)
			$img = @imagecreatefromjpeg($nombre);
		if($datos[2]== 3)
			$img = @imagecreatefrompng($nombre);
		
		$savename = 't_' . $filename;
		$altura = $datos[1];
		$anchura = $datos[0];	
		$posx = 0;
		$posy = 0;
		$anchura_origen = $anchura;
		$altura_origen = $altura;
		
		if($datos[1] > $datos[0] && $datos[1] > $alto) {
				//redimensionar proporcionalmente .. dejar ancho en $ancho
				$altura = $ancho * $altura / $anchura;
				if($datos[2] == 3) {
					$thumb = imagecreatetruecolor($ancho, $altura);
					imagealphablending($thumb, false);
					imagecopyresampled($thumb, $img, 0, 0, 0, 0, $ancho, $altura, $datos[0], $datos[1]);
					imagealphablending($thumb, true);
					imagesavealpha($thumb, true);
				} else {
					$thumb = imagecreatetruecolor($ancho, $altura);
					imagecopyresampled($thumb, $img, 0, 0, 0, 0, $ancho, $altura, $datos[0], $datos[1]);
				}
				$anchura_origen = $ancho;
				$altura_origen = $alto;
				$posy = ($altura / 2) - ($alto / 2);
		}
		if($datos[0] > $datos[1] && $datos[0] > $ancho && $datos[1] / $datos[0] != 1 ) {
				//redimensionar proporcionalmente .. dejar altura en $altura
				$anchura = $alto * $anchura / $altura;
				if($datos[2] == 3) {
					$thumb = imagecreatetruecolor($anchura, $alto);
					imagealphablending($thumb, false);
					imagecopyresampled($thumb, $img, 0, 0, 0, 0, $anchura, $alto, $datos[0], $datos[1]);
					imagealphablending($thumb, true);
					imagesavealpha($thumb, true);
				} else {
					$thumb = imagecreatetruecolor($anchura, $alto);
					imagecopyresampled($thumb, $img, 0, 0, 0, 0, $anchura, $alto, $datos[0], $datos[1]);
				}
				$anchura_origen = $ancho;
				$altura_origen = $alto;
				$posx = ($anchura / 2) - ($ancho / 2);
		}
		$thumb2 = imagecreatetruecolor($ancho,$alto);
	
		if(isset($thumb))
			$img = $thumb;
		
		if($datos[2] == 3) {
			imagealphablending($thumb2, false);
			imagecopyresampled($thumb2, $img, 0, 0, $posx, $posy, $ancho, $alto, $anchura_origen, $altura_origen);
			imagealphablending($thumb2, true);
			imagesavealpha($thumb2, true);
			imagepng($thumb2, $dir . $savename, 0);
		} else {
			imagecopyresampled($thumb2, $img, 0, 0, $posx, $posy, $ancho, $alto, $anchura_origen, $altura_origen);
			imagejpeg($thumb2, $dir . $savename, 100);
		}
		imagedestroy($thumb2);
	}
	
	public function guardar_corte($dir, $nombre, $nombre_salida, $ancho, $alto, $x, $y, $w, $h) {
		$targ_w = $ancho;
		$targ_h = $alto;
		$src = $dir . $nombre;
		$img_r = imagecreatefromjpeg($src);
		$dst_r = imagecreatetruecolor($targ_w, $targ_h);
		
		if(!imagecopyresampled($dst_r, $img_r, 0, 0, $x, $y, $targ_w, $targ_h, $w, $h))
			return FALSE;
		if(!imagejpeg($dst_r, $dir . $nombre_salida, 100))
			return FALSE;
		if(!imagedestroy($dst_r))
			return FALSE;
		return TRUE;
	}
	
	/**
	 * Transforma un entero a Rut (xx.xxx.xxx-x)
	 * @param object $rut
	 * @param object $type [optional]
	 * @return 
	 */
	public function int_to_rut($rut, $update = FALSE) {
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
	public function rut_to_int($rut) {
		$rut = explode('-', $rut);
		$rut = $rut[0];
		$rut = str_replace(array(',', '.'), '', $rut);
		return intval($rut);
	}
	
	public function hora($time = '', $tipo = TRUE) {
		if($tipo)
			return date('h:i:s a', $time);
		else
			return date('H:i', $time);
	}
	
	public function carga_dato($tabla, $campo, $cond, $condicion) {
		$dbcxn = new Conexion();
		$dato = '';
		$query = 'SELECT ' . $campo . ' as dato FROM ' . $tabla . ' WHERE ' . $cond . ' = ' . $condicion . ' LIMIT 0,1';
		$result = $dbcxn->select_query($query);
		foreach($result as $row)
			$dato = $row['dato'];
		return $dato;
	}
	
	public function notificar($id, $tabla, $campo, $mensaje) {
		$dbcnx = new Conexion();
		$session = new Session();
		
		$datos = array(
			'activo' => 1,
			'id' => $id,
			'tabla' => $tabla,
			'usuario_id_creador' => $session->get_var('id_usuario'),
			'fecha_creacion' => time(),
			'mensaje' => $mensaje,
			'campo' => $campo
		);
		if($dbcnx->insert_query('notificaciones', $datos))
			return TRUE;
		else {
			$this->set_mensaje($dbcnx->get_mensaje());
			return FALSE;
		}
	}
	
	public function body_mail($mensaje) {
		return '<div style="width:100%;text-align:center;">
					<div style="margin:auto;width:90%;text-align:left;">
						<img src="'.BASE_URL.IMAGES_DIR.'logo.gif" alt="Universal Nutrition">
					</div>
					<div style="margin:auto;width:90%;font-size:12px;font-family:arial;text-align:justify;">
						<hr style="border:medium none;background-color:rgb(221,221,221);height:1px;" />
						'.$mensaje.'
						
						<br /><br /><br />Sitio Web <a href="'.BASE_URL.'">'.SITE_TITLE.'</a><br />
						<hr style="border:medium none;background-color:rgb(221,221,221);height:1px;" />
					</div>
					<div style="width:100%;text-align:center;font-size:12px;font-family:arial,verdana;">
						&copy; 2009 Universal Nutrition<br>
						Tel&eacute;fono: (02) 2114412 / E-mail: <a href="mailto:info@universalnutrition.cl">info@universalnutrition.cl</a><br />
						Apoquindo 6415 - Las Condes.<br />
						<a href="http://www.universalnutrition.cl" style="text-decoration:none;color:rgb(0,0,0);font-weight:bold;">www.universalnutrition.cl</a>
					</div>
					<br />
				</div>';
	}
}
?>