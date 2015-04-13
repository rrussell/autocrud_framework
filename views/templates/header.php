<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?= SITE_CODING ?>">
		<meta http-equiv="Content-Language" content="es"/>
		<meta name="author" content="<?= SITE_AUTHOR ?>" />
		<meta name="keywords" content="<?= $meta_keywords ?>" />
		<meta name="description" content="<?= $meta_description ?>" />
		<link rel="shortcut icon" type="image/ico" href="<?= BASE_URL . ICONS_DIR . 'favicon.png' ?>" />
		<title><?= SITE_TITLE . $sec_title ?></title>
		<?= $stylesheet ?>
		<script type="text/javascript" src="<?= BASE_URL . JAVASCRIPT_DIR . 'config.js' ?>"></script>
		<?= $javascript ?>
	</head>
	<body>
		<div id="capa"></div>
		<div id="boxes"></div>
		<div id="loading">
			<img src="<?= BASE_URL . IMAGES_DIR . 'cargando.gif' ?>" alt="Cargando..." />
			Cargando...
		</div>
		<div id="content">
			<div id="header">
				<a href="<?= BASE_URL ?>panel"><?= SITE_TITLE . $sec_title ?></a>
			</div>
			<div id="menu">
				<?php if($f->get_var_session('login')) : ?>
				<table>
					<tr>
						<?php if($f->menu()) : ?>
						<td class="link" onclick="location.href='<?= BASE_URL ?>panel'">
							<a href="<?= BASE_URL ?>panel">Volver al Panel de Control</a>
						</td>
						<?php endif ?>
						<td>
							<?= $f->get_var_session('nombre') . ' ' . $f->get_var_session('apellido') ?>
							[Perfil: <?= $f->get_var_session('perfil') ?>]
						</td>
						<td style="width:25px;">
							<span class="opciones" onclick="box_cambiar_passwd()">
								<?= $f->cargar_icono('passwd', 'Cambiar Contraseña', 'Cambiar Contraseña') ?>
							</span>
						</td>
						<td style="width:25px;">
							<span class="opciones" onclick="location.href='<?= BASE_URL ?>login/delete'">
								<?= $f->cargar_icono('exit', 'Cerrar Sesión', 'Cerrar Sesión') ?>
							</span>
						</td>
					</tr>
				</table>
				<?php endif ?>
			</div>
			<div id="contenido">