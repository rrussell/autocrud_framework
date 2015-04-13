<?php

/**
 * Carga la clase solicitada
 * @param object $class
 * @return 
 */
function cargar_class($class) {
	require_once(CLASS_DIR . $class . '.class.php');
}

/**
 * Carga la seccion solicitada
 * @param object $sec
 * @param object $subsec
 * @return 
 */
function cargar_seccion($sec, $subsec, $id_var = NULL) {
	cargar_class('functions');
	cargar_class('session');
	cargar_class('validador');
	cargar_class('permisos');
	cargar_class('paginador');
	require_once(MODULES_DIR . $sec . '.php');
	if(function_exists($subsec))
		if($id_var)
			$subsec($id_var);
		else
			$subsec();
	else
		cargar_seccion('errors', 'e404');
}

/**
 * Carga simple de la vista solicitada
 * @param object $view
 * @return 
 */
function cargar_vista($view) {
	require_once(VIEWS_DIR . $view . '.php');
}

?>