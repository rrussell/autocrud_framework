<div class="clear_both"></div>
<h2>Crear usuario</h2>
<p class="mensaje_<?= (isset($tipo_mensaje)) ? $tipo_mensaje : '' ?>"><?= (isset($mensaje)) ? $mensaje : '' ?></p>
<form id="create_usuarios" name="create_usuarios" class="formulario" action="" method="post">
	<table>
		<tr>
			<td><label id="lnombre" for="nombre">Nombre</label></td>
			<td> : </td>
			<td><input type="text" id="nombre" name="nombre" class="required" maxlength="20" onchange="$('#cpassword').rules('add',{equalTo:'#password'});" /></td>
		</tr>
		<tr>
			<td><label id="lapellido" for="apellido">Apellido</label></td>
			<td> : </td>
			<td><input type="text" id="apellido" name="apellido" class="required" maxlength="20" onchange="$('#cpassword').rules('add',{equalTo:'#password'});" /></td>
		</tr>
		<tr>
			<td><label id="lmail" for="mail">Mail</label></td>
			<td> : </td>
			<td><input type="text" id="mail" name="mail" class="required email" maxlength="50" onchange="$('#cpassword').rules('add',{equalTo:'#password'});" /></td>
		</tr>
		<tr>
			<td><label id="lpassword" for="password">Contraseña</label></td>
			<td> : </td>
			<td><input type="password" id="password" name="password" class="required" maxlength="32" onchange="$('#cpassword').rules('add',{equalTo:'#password'});" /></td>
		</tr>
		<tr>
			<td><label id="lcpassword" for="cpassword">Confirmar Contraseña</label></td>
			<td> : </td>
			<td><input type="password" id="cpassword" name="cpassword" class="required" maxlength="32" onchange="$('#cpassword').rules('add',{equalTo:'#password'});" /></td>
		</tr>
		<tr>
			<td><label id="lactivo" for="activo">Activo</label></td>
			<td> : </td>
			<td>
				<select id="activo" name="activo" class="required">
					<option value="">Elija una opción...</option>
					<option value="1">Si</option>
					<option value="0">No</option>
				</select>
			</td>
		</tr>
		<tr>
			<td><label id="lperfil_id" for="perfil_id">Perfil</label></td>
			<td> : </td>
			<td>
				<select id="perfil_id" name="perfil_id" class="required">
					<option value="">Elija una opción...</option>
					<?php foreach($perfiles as $perfil): ?>
					<option value="<?= $perfil['id_perfil'] ?>"><?= $perfil['descripcion'] ?></option>
					<?php endforeach ?>
				</select>
			</td>
		</tr>
	</table>
	<div class="botones">
		<input type="button" value="CANCELAR" class="boton" onclick="location.href='<?= BASE_URL ?>usuarios/read'" />
		<input type="submit" value="CREAR" class="boton" />
	</div>
</form>