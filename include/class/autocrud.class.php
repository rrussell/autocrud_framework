<?php

/**
 * @author Rodrigo Russell G.
 * Created on 26-04-2010
 */

if(count(get_included_files()) == 1)
	die('The file ' . basename(__FILE__) . ' cannot be accessed directly, use include instead');

/**
 * Clase que genera un CRUD automáticamente
 */
class Autocrud {
	var $_tablas = array();
    var $_conexionClass = NULL;
	var $_mensaje = '';
    
    /**
     * Returns $_conexionClass.
     *
     * @see AutoCrud::$_conexionClass
     */
    public function get_conexionClass() {
        return $this->_conexionClass;
    }
    
    /**
     * Sets $_conexionClass.
     *
     * @param object $_conexionClass
     * @see AutoCrud::$_conexionClass
     */
    public function set_conexionClass($_conexionClass) {
        $this->_conexionClass = $_conexionClass;
    }
    
    /**
     * Returns $_tablas.
     *
     * @see AutoCrud::$_tablas
     */
    public function get_tablas() {
        return $this->_tablas;
    }
    
    /**
     * Sets $_tablas.
     *
     * @param object $_tablas
     * @see AutoCrud::$_tablas
     */
    public function set_tablas($_tablas) {
        $this->_tablas = $_tablas;
    }
    
    /**
     * Returns $_mensaje.
     *
     * @see Autocrud::$_mensaje
     */
    public function get_mensaje() {
        return $this->_mensaje;
    }
    
    /**
     * Sets $_mensaje.
     *
     * @param object $_mensaje
     * @see Autocrud::$_mensaje
     */
    public function set_mensaje($_mensaje) {
        $this->_mensaje .= $_mensaje;
    }
    
	/**
	 * Obtiene los modulos insertados en el sistema
	 * @return 
	 */
	public function get_modulos() {
		$array_modulos = array();
		$modulos = $this->_conexionClass->select_query('SELECT * FROM modulos');
		if(isset($modulos[0])) {
			foreach($modulos as $modulo) {
				$array_modulos[] = $modulo['descripcion'];
			}
		}
		return $array_modulos;
	}
	
	/**
	 * Constructor
	 * @return 
	 */
	function Autocrud() {
		$this->_conexionClass = new Conexion();
		$data = $this->_conexionClass->select_query('SHOW TABLES FROM ' . DB_NAME);
		foreach($data as $row)
			$this->_tablas[] = $row[0];
	}
	
	/**
	 * Obtiene los campos de la tabla dada
	 * @param object $tabla
	 * @return 
	 */
	private function get_campos($tabla, $detalles = FALSE) {
		$this->_conexionClass = new Conexion();
		$campos = array();
		
		$data = $this->_conexionClass->select_query('DESCRIBE ' . $tabla);
		if($detalles) {
			return $data;
		} else {
			foreach($data as $row)
				$campos[] = $row['Field'];
			return $campos;
		}
	}
	
	/**
	 * Obtiene el tamaño de un campo
	 * @param object $tipo
	 * @return 
	 */
	private function length_campo($tipo) {		
		$cadena = '';
		for($i=0; $i < strlen($tipo); $i++)
			if(is_numeric($tipo[$i]))
				$cadena .= $tipo[$i];
		return $cadena;
	}
	
	/**
	 * Crea los archivos de header y footer según tablas dadas
	 * @return 
	 */
	private function header_footer($tablas) {		
		$this->set_mensaje('<li> creando header.php');
		
		$archivo = fopen(VIEWS_DIR . 'templates/header.php', 'r');
		$contenido = '';
		while (!feof($archivo))
			$contenido = $contenido . fgets($archivo);
		fclose($archivo);		
		$nuevo_archivo = fopen(VIEWS_DIR . 'header.php', 'w');
		fwrite($nuevo_archivo, $contenido);
		fclose($nuevo_archivo);
		
		$this->set_mensaje(' OK </li>');
		
		$this->set_mensaje('<li> creando footer.php');
		
		$archivo = fopen(VIEWS_DIR . 'templates/footer.php', 'r');
		$contenido = '';
		while (!feof($archivo))
			$contenido = $contenido . fgets($archivo);
		fclose($archivo);
		
		$nuevo_archivo = fopen(VIEWS_DIR . 'footer.php', 'w');
		fwrite($nuevo_archivo, $contenido);
		fclose($nuevo_archivo);
		
		$this->set_mensaje(' OK </li>');
	}
	
	/**
	 * 
	 * @param object $tablas
	 * @return 
	 */
	private function insert_modulos($tablas) {
		$dbcxn = new Conexion();
		$cont = 0;
		
		$modulos = $this->get_modulos();
		
		if(!in_array('modulos', $modulos, true)) {
			$datos = array('descripcion' => 'modulos');
			$dbcxn->insert_query('modulos', $datos);
			$cont++;
		}
		if(!in_array('perfiles', $modulos, true)) {
			$datos = array('descripcion' => 'perfiles');
			$dbcxn->insert_query('modulos', $datos);
			$cont++;
		}
		if(!in_array('perfiles_modulos', $modulos, true)) {
			$datos = array('descripcion' => 'perfiles_modulos');
			$dbcxn->insert_query('modulos', $datos);
			$cont++;
		}
		if(!in_array('sedes', $modulos, true)) {
			$datos = array('descripcion' => 'sedes');
			$dbcxn->insert_query('modulos', $datos);
			$cont++;
		}
		if(!in_array('usuarios', $modulos, true)) {
			$datos = array('descripcion' => 'usuarios');
			$dbcxn->insert_query('modulos', $datos);
			$cont++;
		}
		
		foreach($tablas as $tabla => $value) {
			if(in_array($tabla, $this->get_tablas(), true)) {
				if(!in_array($tabla, $modulos, true)) {
					$datos = array(
						'descripcion' => $tabla
					);
					$dbcxn->insert_query('modulos', $datos);
					if($cont == 0) {
						$modulo = $this->_conexionClass->select_query('SELECT id_modulo FROM modulos ORDER BY id_modulo DESC LIMIT 0,1');
						$modulo = (isset($modulo[0])) ? $modulo[0] : FALSE;
						$perfiles = $this->_conexionClass->select_query('SELECT * FROM perfiles');
						foreach($perfiles as $perfil) {
							$datos = array(
								'perfil_id' => $perfil['id_perfil'],
								'modulo_id' => $modulo['id_modulo'],
								'valor ' => ''
							);
							$dbcxn->insert_query('perfiles_modulos', $datos);
						}
					}
				}
			}
		}
	}
	
	/**
	 * Crea datos iniciales para ingresar al sistema
	 * @return 
	 */
	private function insert_datos_prueba() {
		$this->set_mensaje('<li> creando <b>DATOS DE PRUEBA</b>');
		$dbcxn = new Conexion();
		$cont = 1;
		
		//Crear usuario WEBMASTER
		$datos = array(
			'id_usuario' => 1,
			'nombre' => 'Rodrigo',
			'apellido' => 'Russell',
			'mail' => 'admin@admin.cl',
			'password' => md5('1'),
			'fecha_creacion' => 0,
			'activo' => 1
		);
		$dbcxn->insert_query('usuarios', $datos);
		
		//Crear perfil WEBMASTER
		$datos = array(
			'id_perfil' => 1,
			'descripcion' => 'Webmaster',
			'fecha_creacion' => 0,
			'usuario_id_creador' => 1
		);
		$dbcxn->insert_query('perfiles', $datos);
		
		//Asignando a perfil WEBMASTER todos los permisos
		$modulos = $dbcxn->select_query('SELECT descripcion FROM modulos ORDER BY id_modulo ASC');
		foreach($modulos as $modulo)
			$cont++;
		for($i = 1; $i < $cont; $i++) {
			$datos = array(
				'perfil_id' => 1,
				'modulo_id' => $i,
				'valor ' => 'CRUD'
			);
			$dbcxn->insert_query('perfiles_modulos', $datos);
		}
		
		//Crear sede CENTRAL
		$datos = array(
			'id_sede' => 1,
			'descripcion' => 'Central',
			'mail' => 'central@mi_empresa.cl'
		);
		$dbcxn->insert_query('sedes', $datos);
		
		//Asignar usuario a perfil WEBMASTER y a sede CENTRAL
		$campo_set_valor = array(
			'perfil_id' => 1,
			'sede_id' => 1
		);
		$campos_where = array(
			'usuarios.id_usuario' => 1
		);
		$dbcxn->update_query('usuarios', $campo_set_valor, $campos_where);
		
		$this->set_mensaje(' OK </li>');
	}
	
	/**
	 * Verifica si se han creado los datos de prueba
	 * @return 
	 */
	public function verificar_datos_prueba() {
		$cont = 0;
		$result = $this->_conexionClass->select_query('SELECT * FROM usuarios');
		if(isset($result[0])) $cont++;
		$result = $this->_conexionClass->select_query('SELECT * FROM perfiles');
		if(isset($result[0])) $cont++;
		$result = $this->_conexionClass->select_query('SELECT * FROM perfiles_modulos');
		if(isset($result[0])) $cont++;
		$result = $this->_conexionClass->select_query('SELECT * FROM modulos');
		if(isset($result[0])) $cont++;
		if($cont == 4)
			return TRUE;
		else
			return FALSE;
	}
	
	/**
	 * Crea archivos para modulos (lógica de paginas)
	 * @param object $tabla
	 * @return 
	 */
	private function modulos($tabla) {		
		$Nombre = ucfirst($tabla);
		$nombre = strtolower($tabla);
		$nombre_archivo = strtolower($tabla) . '.php';
		$validaciones = '';
		$campos_post = '';
		$datos = '';
		$clave_primaria = '';
		
		$this->set_mensaje('<li> <b>creando modulo ' . $nombre_archivo . '</b>');
		
		$campos = $this->get_campos($tabla);
		$clave_primaria = $campos[0];
		
		$campos_post = '$_POST[\'' . implode('\'],$_POST[\'', $campos) . '\']';
		
		foreach($campos as $campo) {
			$validaciones .= "\n\t\t\t\t" . 'array(' . "\n\t\t\t\t\t"
						   . '\'label\' => \'' . $campo . '\',' . "\n\t\t\t\t\t"
						   . '\'valor\' => $_POST[\'' . $campo . '\'],' . "\n\t\t\t\t\t"
						   . '\'regla\' => \'requerido\'' . "\n\t\t\t\t"
						   . '),';
		}
		$campo_post = explode(',', $campos_post);
		
		for($i = 0; $i < count($campos); $i++) {
			$datos .= "\n\t\t\t\t\t'" . $campos[$i] . '\' => ' . $campo_post[$i] . ',';
		}
		
		$archivo = fopen(MODULES_DIR . 'template.php', 'r');
		$contenido = '';
		while (!feof($archivo))
			$contenido = $contenido . fgets($archivo);
		fclose($archivo);
		
		$contenido = str_replace('{Nombre}', $Nombre, $contenido);
		$contenido = str_replace('{nombre}', $nombre, $contenido);
		$contenido = str_replace('{create_datos}', $datos, $contenido);
		$contenido = str_replace('{validaciones}', $validaciones, $contenido);
		$contenido = str_replace('{update_datos}', $datos, $contenido);
		$contenido = str_replace('{clave_primaria}', $clave_primaria, $contenido);
		
		$nuevo_archivo = fopen(MODULES_DIR . $nombre_archivo, 'w');
		fwrite($nuevo_archivo, $contenido);
		fclose($nuevo_archivo);
		
		$this->set_mensaje(' OK </li>');
	}
	
	/**
	 * Crear archivos para vista create
	 * @param object $tabla
	 * @return 
	 */
	private function vista_create($tabla) {		
		$Nombre = ucfirst($tabla);
		$nombre = strtolower($tabla);
		$inputs = '';
		
		$this->set_mensaje('<li> creando vista ' . $tabla . '/create.php');
		
		$campos = $this->get_campos($tabla, TRUE);
		
		foreach($campos as $campo) {
			$length = $this->length_campo($campo['Type']);
			$inputs .= "\n\t\t<tr>\n\t\t\t";
			$inputs .= '<td><label id="l' . $campo['Field'] . '" for="' . $campo['Field'] . '">' . $campo['Field'] . '</label></td>' . "\n\t\t\t";
			$inputs .= '<td> : </td>' . "\n\t\t\t";
			$inputs .= '<td><input type="text" id="' . $campo['Field'] . '" name="' . $campo['Field'] . '" class="required" maxlength="' . $length . '" /></td>';
			$inputs .= "\n\t\t</tr>";
		}
		
		$archivo = fopen(VIEWS_DIR . 'templates/create.php', 'r');
		$contenido = '';
		while (!feof($archivo))
			$contenido = $contenido . fgets($archivo);
		fclose($archivo);
		
		$contenido = str_replace('{Nombre}', $Nombre, $contenido);
		$contenido = str_replace('{nombre}', $nombre, $contenido);
		$contenido = str_replace('{inputs}', $inputs, $contenido);
		
		if(!is_dir(VIEWS_DIR . $tabla)) {
			@mkdir(VIEWS_DIR . $tabla);
			$archivo = fopen(VIEWS_DIR . 'templates/index.html', 'r');
			$contenido1 = '';
			while (!feof($archivo))
				$contenido1 = $contenido1 . fgets($archivo);
			fclose($archivo);
			$nuevo_archivo = fopen(VIEWS_DIR . $tabla . '/index.html', 'w');      
			fwrite($nuevo_archivo, $contenido1);
			fclose($nuevo_archivo);
		}
		$nuevo_archivo = fopen(VIEWS_DIR . $tabla . '/create.php', 'w');      
		fwrite($nuevo_archivo, $contenido);
		fclose($nuevo_archivo);
		
		$this->set_mensaje(' OK </li>');
	}
	
	/**
	 * Crear archivos para vista update
	 * @param object $tabla
	 * @return 
	 */
	private function vista_update($tabla) {		
		$Nombre = ucfirst($tabla);
		$nombre = strtolower($tabla);
		$inputs = '';
		$clave_primaria = '';
		
		$this->set_mensaje('<li> creando vista ' . $tabla . '/update.php');
		
		$campos = $this->get_campos($tabla, TRUE);
		
		foreach($campos as $campo) {
			if($campo['Key'] == 'PRI')
				$clave_primaria = $campo['Field'];
			$length = $this->length_campo($campo['Type']);
			$inputs .= "\n\t\t<tr>\n\t\t\t";
			$inputs .= '<td><label id="l' . $campo['Field'] . '" for="' . $campo['Field'] . '">' . $campo['Field'] . '</label></td>' . "\n\t\t\t";
			$inputs .= '<td> : </td>' . "\n\t\t\t";
			$inputs .= '<td><input type="text" id="' . $campo['Field'] . '" name="' . $campo['Field'] . '" value="<?= $result[\'' . $campo['Field'] . '\'] ?>" class="required" maxlength="' . $length . '" /></td>';
			$inputs .= "\n\t\t</tr>";
		}
		
		$archivo = fopen(VIEWS_DIR . 'templates/update.php', 'r');
		$contenido = '';
		while (!feof($archivo))
			$contenido = $contenido . fgets($archivo);
		fclose($archivo);
		
		$contenido = str_replace('{Nombre}', $Nombre, $contenido);
		$contenido = str_replace('{nombre}', $nombre, $contenido);
		$contenido = str_replace('{inputs}', $inputs, $contenido);
		$contenido = str_replace('{clave_primaria}', $clave_primaria, $contenido);
		
		if(!is_dir(VIEWS_DIR . $tabla)) {
			@mkdir(VIEWS_DIR . $tabla);
			$archivo = fopen(VIEWS_DIR . 'templates/index.html', 'r');
			$contenido1 = '';
			while (!feof($archivo))
				$contenido1 = $contenido1 . fgets($archivo);
			fclose($archivo);
			$nuevo_archivo = fopen(VIEWS_DIR . $tabla . '/index.html', 'w');      
			fwrite($nuevo_archivo, $contenido1);
			fclose($nuevo_archivo);
		}
		$nuevo_archivo = fopen(VIEWS_DIR . $tabla . '/update.php', 'w');      
		fwrite($nuevo_archivo, $contenido);
		fclose($nuevo_archivo);
		
		$this->set_mensaje(' OK </li>');
	}
	
	/**
	 * Crear archivos para vista read
	 * @param object $tabla
	 * @return 
	 */
	private function vista_read($tabla) {		
		$Nombre = ucfirst($tabla);
		$nombre = strtolower($tabla);
		$cabecera = '';
		$valores = '';
		$clave_primaria = '';
		
		$this->set_mensaje('<li> creando vista ' . $tabla . '/read.php');
		
		$campos = $this->get_campos($tabla);
		$clave_primaria = $campos[0];
		
		for($i = 0; $i < count($campos); $i++) {
			$cabecera .= "\n\t\t" . '<th>' . $campos[$i] . '</th>';
			$valores .= "\n\t\t" . '<td><?= $row[\'' . $campos[$i] . '\'] ?></td>';
		}
		
		$archivo = fopen(VIEWS_DIR . 'templates/read.php', 'r');
		$contenido = '';
		while (!feof($archivo))
			$contenido = $contenido . fgets($archivo);
		fclose($archivo);
		
		$contenido = str_replace('{Nombre}', $Nombre, $contenido);
		$contenido = str_replace('{nombre}', $nombre, $contenido);
		$contenido = str_replace('{cabecera}', $cabecera, $contenido);
		$contenido = str_replace('{valores}', $valores, $contenido);
		$contenido = str_replace('{clave_primaria}', $clave_primaria, $contenido);
		
		if(!is_dir(VIEWS_DIR . $tabla)) {
			@mkdir(VIEWS_DIR . $tabla);
			$archivo = fopen(VIEWS_DIR . 'templates/index.html', 'r');
			$contenido1 = '';
			while (!feof($archivo))
				$contenido1 = $contenido1 . fgets($archivo);
			fclose($archivo);
			$nuevo_archivo = fopen(VIEWS_DIR . $tabla . '/index.html', 'w');      
			fwrite($nuevo_archivo, $contenido1);
			fclose($nuevo_archivo);
		}
		$nuevo_archivo = fopen(VIEWS_DIR . $tabla . '/read.php', 'w');      
		fwrite($nuevo_archivo, $contenido);
		fclose($nuevo_archivo);
		
		$this->set_mensaje(' OK </li>');
	}
	
	private function login() {		
		$this->set_mensaje('<li> creando login.php');
		
		$archivo = fopen(VIEWS_DIR . 'templates/login.php', 'r');
		$contenido = '';
		while (!feof($archivo))
			$contenido = $contenido . fgets($archivo);
		fclose($archivo);
		
		$nuevo_archivo = fopen(VIEWS_DIR . 'login.php', 'w');
		fwrite($nuevo_archivo, $contenido);
		fclose($nuevo_archivo);
		
		$this->set_mensaje(' OK </li>');
		
		$this->set_mensaje('<li> creando modulo login.php');
		
		$contenido = 	
		'<?php'
		. "\n\t" . 'function read() {'
		. "\n\t\t" . '$functions = new Functions();'
		. "\n\t\t" . '$dbcnx = new Conexion();'
		. "\n\t\t" . '$session = new Session();'
		. "\n\t\t" . '$validador = new Validador();'
		. "\n\t\t"
		. "\n\t\t" . '/* Cabeceras */'
		. "\n\t\t" . '$title = \'Login\';'
		. "\n\t\t" . '$keywords = \'\';'
		. "\n\t\t" . '$description = \'\';'
		. "\n\t\t" . '$current = \'\';'	
		. "\n\t\t" . '$functions->set_headers($title, $keywords, $description, $current);'
		. "\n\t\t" . '$functions->add_stylesheet(\'general\');'
		. "\n\t\t" . '$functions->add_javascript(\'jquery-1.4.2.min\');'
		. "\n\t\t" . '$functions->add_javascript(\'jquery.validate\');'
		. "\n\t\t" . '$functions->add_javascript(\'functions\');'
		. "\n\t\t" . '/* Cabeceras */'
		. "\n\t\t"
		. "\n\t\t" . 'if(!$session->login()) {'
		. "\n\t\t\t" . 	'if($_POST) {'
		. "\n\t\t\t\t" . 		'if($session->iniciar_login($_POST[\'mail\'], $_POST[\'passwd\']))'
		. "\n\t\t\t\t\t" . 			'$functions->redireccionar(\'usuarios/read\');'
		. "\n\t\t\t\t" . 		'else {'
		. "\n\t\t\t\t\t" . 			'$data = array('
		. "\n\t\t\t\t\t" . 			'	\'mensaje\' => $session->get_mensaje(),'
		. "\n\t\t\t\t\t" . 			'	\'tipo_mensaje\' => \'error\''
		. "\n\t\t\t\t\t" . 			');'
		. "\n\t\t\t\t\t" . 			'$functions->cargar_view(\'login\', $data);'
		. "\n\t\t\t\t" . 		'}'
		. "\n\t\t\t" . 	'} else {'
		. "\n\t\t\t\t" . 		'$data = array('
		. "\n\t\t\t\t\t" . 			'\'mensaje\' => \'\''
		. "\n\t\t\t\t" . 		');'
		. "\n\t\t\t\t" . 		'$functions->cargar_view(\'login\', $data);'
		. "\n\t\t\t" . 	'}'
		. "\n\t\t" . '} else {'
		. "\n\t\t\t" . 	'$functions->redireccionar(\'usuarios/read\');'
		. "\n\t\t" . '}'
		. "\n\t" . '}'
		. "\n\t"
		. "\n\t" .'function delete() {'
		. "\n\t\t" .	'$functions = new Functions();'
		. "\n\t\t" .	'$session = new Session();'
		. "\n\t\t" 
		. "\n\t\t" .	'$session->destroy_session();'
		. "\n\t\t" .	'$functions->redireccionar(\'\');'
		. "\n\t" .'}'
		. "\n" .'?>';
		
		$nuevo_archivo = fopen(MODULES_DIR . 'login.php', 'w');
		fwrite($nuevo_archivo, $contenido);
		fclose($nuevo_archivo);
		
		$this->set_mensaje(' OK </li>');
	}
	
	/**
	 * Reune las funciones y las ejecuta para crear los archivos y carpetas
	 * @param object $datos
	 * @return 
	 */
	public function make($datos) {
		$dbcxn = new Conexion();
		$cont = 0;
		$this->set_mensaje('<ul>');
		foreach($this->get_tablas() as $tabla) {
			if(isset($datos[$tabla])) {
				if(!($cont > 0) && !file_exists(VIEWS_DIR . 'footer.php') && !file_exists(VIEWS_DIR . 'header.php')) $this->header_footer($datos);
				if(!($cont > 0)) $this->insert_modulos($datos);
				$this->modulos($tabla);
				$this->vista_create($tabla);
				$this->vista_update($tabla);
				$this->vista_read($tabla);
				$cont++;
			}
		}
		if(isset($datos['datos_prueba']))
			$this->insert_datos_prueba();
		$this->set_mensaje('</ul>');
	}
}
?>