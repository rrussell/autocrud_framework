<div class="clear_both"></div>
<h2>Modificar {Nombre}</h2>
<p class="mensaje_<?= (isset($tipo_mensaje)) ? $tipo_mensaje : '' ?>"><?= (isset($mensaje)) ? $mensaje : '' ?></p>
<?php if(count($result) > 0): ?>
<form id="update_{nombre}" name="update_{nombre}" class="formulario" action="" method="post">
	<table>{inputs}
	</table>
	<div class="botones">
		<input type="button" value="CANCELAR" class="boton" onclick="location.href='<?= BASE_URL ?>{nombre}/read'" />
		<input type="submit" value="MODIFICAR" class="boton" />
	</div>
</form>
<?php else : ?>
<h3>No se encontraron resultados</h3>
<?php endif ?>