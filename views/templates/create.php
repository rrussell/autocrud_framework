<div class="clear_both"></div>
<h2>Crear {Nombre}</h2>
<p class="mensaje_<?= (isset($tipo_mensaje)) ? $tipo_mensaje : '' ?>"><?= (isset($mensaje)) ? $mensaje : '' ?></p>
<form id="create_{nombre}" name="create_{nombre}" class="formulario" action="" method="post">
	<table>{inputs}
	</table>
	<div class="botones">
		<input type="button" value="CANCELAR" class="boton" onclick="location.href='<?= BASE_URL ?>{nombre}/read'" />
		<input type="submit" value="CREAR" class="boton" />
	</div>
</form>