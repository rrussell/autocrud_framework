<div class="clear_both"></div>
<h2>Crear módulo</h2>
<p class="mensaje_<?= (isset($tipo_mensaje)) ? $tipo_mensaje : '' ?>"><?= (isset($mensaje)) ? $mensaje : '' ?></p>
<form id="create_modulos" name="create_modulos" class="formulario" action="" method="post">
	<table>
		<tr>
			<td><label id="ldescripcion" for="descripcion">Nombre</label></td>
			<td> : </td>
			<td><input type="text" id="descripcion" name="descripcion" class="required" maxlength="25" /></td>
		</tr>
	</table>
	<div class="botones">
		<input type="button" value="CANCELAR" class="boton" onclick="location.href='<?= BASE_URL ?>modulos/read'" />
		<input type="submit" value="CREAR" class="boton" />
	</div>
</form>