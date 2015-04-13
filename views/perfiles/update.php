<h2>Modificar Perfil</h2>
<p class="mensaje_<?= (isset($tipo_mensaje)) ? $tipo_mensaje : '' ?>"><?= (isset($mensaje)) ? $mensaje : '' ?></p>
<?php if(count($result) > 0): ?>
<form id="update_perfiles" name="update_perfiles" class="formulario" action="" method="post">
	<table>
		<tr>
			<td><label id="ldescripcion" for="descripcion">Nombre Perfil</label></td>
			<td> : </td>
			<td><input type="text" id="descripcion" name="descripcion" value="<?= $result['descripcion'] ?>" class="required" maxlength="25" /></td>
		</tr>
	</table>
	<div class="botones">
		<input type="button" value="CANCELAR" class="boton" onclick="location.href='<?= BASE_URL ?>perfiles/read'" />
		<input type="submit" value="MODIFICAR" />
	</div>
</form>
<?php else : ?>
<h3>No se encontraron resultados</h3>
<?php endif ?>