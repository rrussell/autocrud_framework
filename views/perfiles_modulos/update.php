<h2>Modificar Accesos de Perfil '<?= $f->carga_dato('perfiles', 'descripcion', 'id_perfil', $id_perfil)?>' por Módulos</h2>
<br />
<p class="mensaje_<?= (isset($tipo_mensaje)) ? $tipo_mensaje : '' ?>"><?= (isset($mensaje)) ? $mensaje : '' ?></p>
<form id="update_perfiles_modulos" name="update_perfiles_modulos" action="" method="post">
	<table class="tabla">
		<tr>
			<th>Módulos</th>
			<th>Create</th>
			<th>Read</th>
			<th>Update</th>
			<th>Delete</th>
			<th>Todo</th>
		</tr>
		<?php foreach($modulos as $modulo): ?>
		<tr>
			<td><?= $modulo['descripcion'] ?></td>
			<td><input type="checkbox" id="C<?= $modulo['descripcion'] ?>" name="C<?= $modulo['descripcion'] ?>" <?= $f->check($id_perfil, $modulo['id_modulo'], 'C') ?> /></td>
			<td><input type="checkbox" id="R<?= $modulo['descripcion'] ?>" name="R<?= $modulo['descripcion'] ?>" <?= $f->check($id_perfil, $modulo['id_modulo'], 'R') ?> /></td>
			<td><input type="checkbox" id="U<?= $modulo['descripcion'] ?>" name="U<?= $modulo['descripcion'] ?>" <?= $f->check($id_perfil, $modulo['id_modulo'], 'U') ?> /></td>
			<td><input type="checkbox" id="D<?= $modulo['descripcion'] ?>" name="D<?= $modulo['descripcion'] ?>" <?= $f->check($id_perfil, $modulo['id_modulo'], 'D') ?> /></td>
			<td style="background-color:#FFD9D9;"><input type="checkbox" id="T<?= $modulo['descripcion'] ?>" name="T<?= $modulo['descripcion'] ?>" onclick="check_fila('T<?= $modulo['descripcion'] ?>', '<?= $modulo['descripcion'] ?>')" /></td>
		</tr>
		<?php endforeach ?>
		<tr style="background-color:#FFD9D9;">
			<td class="font_bold">Todo</td>
			<td><input type="checkbox" id="TC" name="TC" onclick="check_columna('TC', 'C')" /></td>
			<td><input type="checkbox" id="TR" name="TR" onclick="check_columna('TR', 'R')" /></td>
			<td><input type="checkbox" id="TU" name="TU" onclick="check_columna('TU', 'U')" /></td>
			<td><input type="checkbox" id="TD" name="TD" onclick="check_columna('TD', 'D')" /></td>
			<td><input type="checkbox" id="TT" name="TT" onclick="check_todo('TT', 'update_perfiles_modulos')" /></td>
		</tr>
	</table>
	<div class="botones">
		<input type="button" value="CANCELAR" class="boton" onclick="location.href='<?= BASE_URL ?>perfiles/read'" />
		<input type="submit" value="MODIFICAR" class="boton" />
	</div>
</form>