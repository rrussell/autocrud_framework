<?php if($f->mostrar('perfiles', 'C')) : ?>
<a href="<?= BASE_URL ?>perfiles/create" class="boton">Crear Perfil</a>
<?php endif ?>
<p class="mensaje_<?= (isset($tipo_mensaje)) ? $tipo_mensaje : '' ?>"><?= (isset($mensaje)) ? $mensaje : '' ?></p>
<table class="tabla">
	<caption>Lista de Perfiles</caption>
	<tr>
		<th>Nombre</th>
		<th>Fecha Creación</th>
		<th>Usuario Creador</th>
		<th>Fecha Modificación</th>
		<th>Usuario Modificador</th>
		<th style="width:60px;">Opciones</th>
	</tr>
	<?php if(count($results) > 0): ?>
	<?php foreach($results as $row) : ?>
	<tr>
		<td><?= $row['descripcion'] ?></td>
		<td><?= $f->fechahora($row['fecha_creacion']) ?></td>
		<td><?= $f->carga_dato('usuarios', 'CONCAT(nombre,\' \',apellido)', 'id_usuario', $row['usuario_id_creador']) ?></td>
		<td><?= ($row['fecha_modificacion'] == NULL) ? '<span class="italic">No se ha modificado</span>' : $f->fechahora($row['fecha_modificacion']) ?></td>
		<td><?= ($row['usuario_id_modificador'] == NULL) ? '<span class="italic">No se ha modificado</span>' : $f->carga_dato('usuarios', 'CONCAT(nombre,\' \',apellido)', 'id_usuario', $row['usuario_id_modificador']) ?></td>
		<td>
			<?php if($f->mostrar('perfiles_modulos', 'U')) : ?>
			<span class="opciones" onclick="location.href='<?= BASE_URL ?>perfiles_modulos/update/var,<?= $row['id_perfil'] ?>'">
				<?= $f->cargar_icono('acceso', 'Asignar Accesos', 'Asignar Accesos') ?>
			</span>
			<?php endif ?>
			<?php if($f->mostrar('perfiles', 'U')) : ?>
			<span class="opciones" onclick="location.href='<?= BASE_URL ?>perfiles/update/var,<?= $row['id_perfil'] ?>'">
				<?= $f->cargar_icono('edit', 'Modificar', 'Modificar') ?>
			</span>
			<?php endif ?>
			<?php if($f->mostrar('perfiles', 'D')) : ?>
			<span class="opciones" onclick="jConfirm('¿Estás seguro que lo quieres eliminar?', '<?= SITE_TITLE ?>',function(r) {if(r)location.href='<?= BASE_URL ?>perfiles/delete/var,<?= $row['id_perfil'] ?>'});">
				<?= $f->cargar_icono('delete', 'Eliminar', 'Eliminar') ?>
			</span>
			<?php endif ?>
		</td>
	</tr>
	<?php endforeach ?>
	<?php else : ?>
	<tr>
		<td colspan="6"><h3>No se encontraron resultados</h3></td>
	</tr>
	<?php endif ?>
</table>
<div id="paginacion"><?= $paginacion ?></div>