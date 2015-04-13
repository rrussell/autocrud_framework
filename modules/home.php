<?php
	
	function read() {
		$functions = new Functions();
		$dbcnx = new Conexion();
		$session = new Session();
		$sanitizar = new Sanitizer();
		
		/* Cabeceras */
		$title = '';
		$keywords = '';
		$description = '';
		$current = '';
		$functions->set_headers($title, $keywords, $description, $current);
		$functions->add_stylesheet('site');
		$functions->add_stylesheet('home');
		$functions->add_javascript('jquery-1.4.2.min');
		$functions->add_javascript('site');
		$functions->add_script('iniciar();');
		/* Cabeceras */
		
		$data = array(
			'' => ''
		);
		
		$functions->cargar_view_site('home', $data);
	}
	
?>