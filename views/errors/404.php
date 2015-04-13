<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?= SITE_CODING ?>">
		<meta http-equiv="Content-Language" content="es"/>
		<meta name="author" content="<?= SITE_AUTHOR ?>" />
		<meta name="keywords" content="<?= $meta_keywords ?>" />
		<meta name="description" content="<?= $meta_description ?>" />
		<link rel="shortcut icon" type="image/ico" href="<?= BASE_URL . ICONS_DIR . FAVICON ?>" />
		<title><?= SITE_TITLE . $sec_title ?></title>
		<?= $stylesheet ?>
	</head>
	<body>
		<div class="align_center">
			<br /><br />
			<img src="<?= BASE_URL . IMAGES_DIR . 'logo.png' ?>" alt="" />
			<h3>The requested file was not found.</h3><br />
			<p><a href="javascript:history.go(-1);">Volver a la página anterior</a></p>
		</div>
	</body>
</html>