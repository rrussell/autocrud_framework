<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?= SITE_CODING ?>">
		<meta http-equiv="Content-Language" content="es"/>
		<title><?= SITE_TITLE ?> - CRUD</title>
		<link rel="shortcut icon" type="image/ico" href="<?= BASE_URL . ICONS_DIR . 'favicon.png' ?>" />
	</head>
	<body>
<?php
	function read() {
		cargar_class('autocrud');
		$crud = new Autocrud();
		
		$tablas = $crud->get_tablas();
		$modulos = $crud->get_modulos();
		$tablas_sistema = array('login', 'modulos', 'perfiles_modulos', 'perfiles', 'sedes', 'usuarios', 'ci_sessions');
		
		echo '<form id="crud" name="crud" action="crud" method="post" onsubmit="return confirm(\'¿Estás seguro para CREAR?\')">';
		echo '<table>';
		foreach($tablas as $tabla) {
			if(!in_array($tabla, $tablas_sistema, true)) {
				$checked = (in_array($tabla, $modulos, true)) ? '' : 'checked="checked"';
				$checked_msg = (in_array($tabla, $modulos, true)) ? '<span style="font-size:12px;">(Ya está creado, <b>sobrescribirás los archivos si lo creas de nuevo</b>)</span>' : '';
				echo '<tr>';
				echo '<td><input type="checkbox" id="' . $tabla . '" name="' . $tabla . '" ' . $checked . ' /></td>';
				echo '<td><label id="l' . $tabla . '" for="' . $tabla . '">' . $tabla . '</label>' . $checked_msg . '</td>';
				echo '</tr>';
			}
		}
		if(!$crud->verificar_datos_prueba()) {
			echo '<tr>';
			echo '<td><input type="checkbox" id="datos_prueba" name="datos_prueba" checked="checked" /></td>';
			echo '<td><label id="ldatos_prueba" for="datos_prueba"><b>CREAR DATOS DE PRUEBA</b></label></td>';
			echo '</tr>';
		}
		echo '</table>';
		echo '<input type="submit" value="CREAR" />';
		echo '</form>';
		
		if($_POST) {			
			$crud->make($_POST);
			echo $crud->get_mensaje();
		}
	}
?>
	</body>
</html>