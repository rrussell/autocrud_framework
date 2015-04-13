<?php

	function read() {
		$functions = new Functions();
		$dbcnx = new Conexion();
		$session = new Session();
		$validador = new Validador();
		
		$session->verificar_seguridad();
		$session->login(TRUE);
		
		/* Cabeceras */
		$title = 'Panel de Control';
		$keywords = '';
		$description = '';
		$current = '';
		$functions->set_headers($title, $keywords, $description, $current);
		$functions->add_stylesheet('general');
		$functions->add_javascript('jquery-1.4.2.min');
		$functions->add_javascript('jquery.validate');
		$functions->add_javascript('functions');
		/* Cabeceras */
		
		$data = array(
			'' => ''
		);
		
		$functions->cargar_view('menu', $data);
	}
	
	/**
	 * Activar con ajax
	 * @return 
	 */
	function activar() {
		$dbcnx = new Conexion();
		$sanitizar = new Sanitizer();
		$session = new Session();
		
		$tabla = $sanitizar->sanitize($_POST['tabla']);
		$campo = $sanitizar->sanitize($_POST['campo']);
		$valor = $sanitizar->sanitize($_POST['valor']);
        
		$campo_set_valor = array(
			'activo' => 1
		);
		$campos_where = array(
			$tabla . '.' . $campo => $valor
		);
		if($dbcnx->update_query($tabla, $campo_set_valor, $campos_where))
			echo 'ok';
		else
			echo 'error|Ocurrió un error al activar, trata otra vez.';
	}
	
	/**
	 * Desactivar con ajax
	 * @return 
	 */
	function desactivar() {
		$dbcnx = new Conexion();
		$sanitizar = new Sanitizer();
		$session = new Session();
		
		$tabla = $sanitizar->sanitize($_POST['tabla']);
		$campo = $sanitizar->sanitize($_POST['campo']);
		$valor = $sanitizar->sanitize($_POST['valor']);
		
		$campo_set_valor = array(
			'activo' => 0
		);
		$campos_where = array(
			$tabla . '.' . $campo => $valor
		);
		if($dbcnx->update_query($tabla, $campo_set_valor, $campos_where))
			echo 'ok';
		else
			echo 'error|Ocurrió un error al desactivar, trata otra vez.';
	}

	/**
	 * Confirmar con ajax
	 * @return 
	 */
	function confirmar() {
		$dbcnx = new Conexion();
		$sanitizar = new Sanitizer();
		$session = new Session();
		
		$tabla = $sanitizar->sanitize($_POST['tabla']);
		$campo = $sanitizar->sanitize($_POST['campo']);
		$valor = $sanitizar->sanitize($_POST['valor']);
        
		$campo_set_valor = array(
			'confirmada' => 1
		);
		$campos_where = array(
			$tabla . '.' . $campo => $valor
		);
		if($dbcnx->update_query($tabla, $campo_set_valor, $campos_where))
			echo 'ok';
		else
			echo 'error|Ocurrió un error al confirmar, intente otra vez.';
	}
	
	/**
	 * Desconfirmar con ajax
	 * @return 
	 */
	function desconfirmar() {
		$dbcnx = new Conexion();
		$sanitizar = new Sanitizer();
		$session = new Session();
		
		$tabla = $sanitizar->sanitize($_POST['tabla']);
		$campo = $sanitizar->sanitize($_POST['campo']);
		$valor = $sanitizar->sanitize($_POST['valor']);
        
		$campo_set_valor = array(
			'confirmada' => 0
		);
		$campos_where = array(
			$tabla . '.' . $campo => $valor
		);
		if($dbcnx->update_query($tabla, $campo_set_valor, $campos_where))
			echo 'ok';
		else
			echo 'error|Ocurrió un error al desconfirmar, intente otra vez.';
	}
?>