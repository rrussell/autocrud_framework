<h2>Crear perfil</h2>
<p class="mensaje_<?= (isset($tipo_mensaje)) ? $tipo_mensaje : '' ?>"><?= (isset($mensaje)) ? $mensaje : '' ?></p>
<form id="create_perfiles" name="create_perfiles" class="formulario" action="<?= BASE_URL ?>perfiles/create" method="post">
	<table>
		<tr>
			<td><label id="ldescripcion" for="descripcion">Nombre Perfil</label></td>
			<td> : </td>
			<td><input type="text" id="descripcion" name="descripcion" class="required" maxlength="25" /></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="checkbox" id="permisos" name="permisos" style="width:10px;margin:0px;" /></td>
			<td><p style="margin:0px 0px 0px 10px;">Mismos permisos que Perfil '<?= $f->get_var_session('perfil') ?>'</p></td>
		</tr>
	</table>
	<div class="botones">
		<input type="button" value="CANCELAR" class="boton" onclick="location.href='<?= BASE_URL ?>perfiles/read'" />
		<input type="submit" value="CREAR" class="boton" />
	</div>
</form>