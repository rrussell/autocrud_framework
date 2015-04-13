<?php

require_once('include' . DIRECTORY_SEPARATOR . 'config/config.php');
require_once(INCLUDE_DIR . 'base.functions.php');

if(defined('EUREKA') && EUREKA) {
	cargar_class('sanitizer');
	cargar_class('conexion');
	cargar_class('session');
	
	$session = new Session(TRUE);
	$dbcxn = new Conexion();
	$sanitize = new Sanitizer();
	
	$sec    = (!isset ($_GET['s']))  ? SECTION_INDEX : $sanitize->sanitize($_GET['s']);
	$subsec = (!isset ($_GET['ss'])) ? SUBSECTION_INDEX : $sanitize->sanitize($_GET['ss']);
	$id_var = (!isset ($_GET['var']))? '' : $sanitize->sanitize($_GET['var']);
	$session->set_var('section', $sec);
	$session->set_var('subsection', $subsec);
	
	if(!file_exists(MODULES_DIR . $sec . '.php'))
		cargar_seccion('errors', 'e404');
	else
		cargar_seccion($sec, $subsec, $id_var);
	
	$dbcxn->Conexion_Destroy();	
} elseif(defined('EUREKA') && !EUREKA) {
	cargar_seccion('errors', 'eDown');
} elseif(!defined('EUREKA')) {
	cargar_seccion('errors', 'eDown');
}

?>