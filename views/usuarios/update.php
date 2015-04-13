<div class="clear_both"></div>
<h2>Modificar Usuario</h2>
<p class="mensaje_<?= (isset($tipo_mensaje)) ? $tipo_mensaje : '' ?>"><?= (isset($mensaje)) ? $mensaje : '' ?></p>
<?php if(count($result) > 0): ?>
<form id="update_usuarios" name="update_usuarios" class="formulario" action="" method="post">
	<table>
		<tr>
			<td><label id="lnombre" for="nombre">Nombre</label></td>
			<td> : </td>
			<td><input type="text" id="nombre" name="nombre" value="<?= $result['nombre'] ?>" class="required" maxlength="20" /></td>
		</tr>
		<tr>
			<td><label id="lapellido" for="apellido">Apellido</label></td>
			<td> : </td>
			<td><input type="text" id="apellido" name="apellido" value="<?= $result['apellido'] ?>" class="required" maxlength="20" /></td>
		</tr>
		<tr>
			<td><label id="lmail" for="mail">Mail</label></td>
			<td> : </td>
			<td><input type="text" id="mail" name="mail" value="<?= $result['mail'] ?>" class="required" maxlength="50" /></td>
		</tr>
		<tr>
			<td><label id="lpassword" for="password">Contraseña</label></td>
			<td> : </td>
			<td><input type="password" id="password" name="password" value="" maxlength="32" onchange="if($('#password').val() != '') {$('#cpassword').rules('add',{required: true});$('#cpassword').rules('add',{required: true,equalTo:'#password'});}" /></td>
		</tr>
		<tr>
			<td><label id="lcpassword" for="cpassword">Confirmar Contraseña</label></td>
			<td> : </td>
			<td><input type="password" id="cpassword" name="cpassword" value="" maxlength="32" /></td>
		</tr>
		<tr>
			<td><label id="lactivo" for="activo">Activo</label></td>
			<td> : </td>
			<td>
				<select id="activo" name="activo" class="required">
					<option value="1" <?= ($result['activo'] == 1) ? 'selected="selected"' : '' ?>>Si</option>
					<option value="0" <?= ($result['activo'] == 0) ? 'selected="selected"' : '' ?>>No</option>
				</select>
			</td>
		</tr>
		<tr>
			<td><label id="lperfil_id" for="perfil_id">Perfil</label></td>
			<td> : </td>
			<td>
				<select id="perfil_id" name="perfil_id" class="required">
					<?php foreach($perfiles as $perfil): ?>
					<option value="<?= $perfil['id_perfil'] ?>" <?= ($result['perfil_id'] == $perfil['id_perfil']) ? 'selected="selected"' : '' ?>><?= $perfil['descripcion'] ?></option>
					<?php endforeach ?>
				</select>
			</td>
		</tr>
	</table>
	<div class="botones">
		<input type="button" value="CANCELAR" class="boton" onclick="location.href='<?= BASE_URL ?>usuarios/read'" />
		<input type="submit" value="MODIFICAR" class="boton" />
	</div>
</form>
<?php else : ?>
<h3>No se encontraron resultados</h3>
<?php endif ?>