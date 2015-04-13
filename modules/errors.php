<?php
    
	/**
	 * Carga la vista de error 404
	 * @return 
	 */
	function e404() {
		$functions = new Functions();
		
		/* Cabeceras */
		$title = '404 Not Found';
		$keywords = '';
		$description = '';
		$current = '';		
		$functions->set_headers($title, $keywords, $description, $current);
		$functions->add_stylesheet('general');
		/* Cabeceras */
		
		$functions->cargar_view_error('errors/404');
	}
	
	/**
	 * Carga la vista de error 403
	 * @return 
	 */
	function e403() {
		$functions = new Functions();
		
		/* Cabeceras */
		$title = '403 Forbidden';
		$keywords = '';
		$description = '';
		$current = '';		
		$functions->set_headers($title, $keywords, $description, $current);
		$functions->add_stylesheet('general');
		/* Cabeceras */
		
		$functions->cargar_view_error('errors/403');
	}
	
	/**
	 * Carga la vista de cuando la pagina esta down
	 * @return 
	 */
	function eDown() {
		$functions = new Functions();
		
		/* Cabeceras */
		$title = 'En Mantenimiento';
		$keywords = '';
		$description = '';
		$current = '';		
		$functions->set_headers($title, $keywords, $description, $current);
		$functions->add_stylesheet('general');
		/* Cabeceras */
		
		$functions->cargar_view_error('errors/down');
	}
	
?>