<?php if($f->mostrar('usuarios', 'C')) : ?>
<a href="<?= BASE_URL ?>usuarios/create" class="boton">Crear Usuario</a>
<?php endif ?>
<p class="mensaje_<?= (isset($tipo_mensaje)) ? $tipo_mensaje : '' ?>"><?= (isset($mensaje)) ? $mensaje : '' ?></p>
<div class="clear_both"></div>
<h2>Lista de Usuarios</h2>
<?php if(count($results) > 0): ?>
<table class="tabla">
	<tr>
		<th>Nombre</th>
		<th>Apellido</th>
		<th>Mail</th>
		<th>Fecha Creación</th>
		<th>Fecha Modificación</th>
		<th>Perfil</th>
		<th>Activo</th>
		<th style="width:50px;">Opciones</th>
	</tr>
	<?php foreach($results as $row) : ?>
	<tr>
		<td><?= $row['nombre'] ?></td>
		<td><?= $row['apellido'] ?></td>
		<td><?= $row['mail'] ?></td>
		<td><?= $f->fechahora($row['fecha_creacion']) ?></td>
		<td><?= ($row['fecha_modificacion'] == '') ? '-' : $f->fechahora($row['fecha_modificacion']) ?></td>
		<td><?= $f->carga_dato('perfiles', 'descripcion', 'id_perfil', $row['perfil_id']) ?></td>
		<td id="act_<?= $row['id_usuario'] ?>">
			<?php if($row['activo'] == 1) : ?>
			<span class="opciones" onclick="jConfirm('¿Estás seguro que lo quieres desactivar?', '<?= SITE_TITLE ?>',function(r) {if(r)desactivar('usuarios', 'id_usuario', '<?= $row['id_usuario'] ?>')});">
			<?= $f->cargar_icono('accept', 'Si', 'Si') ?>
			</span>
			<?php else : ?>
			<span class="opciones" onclick="jConfirm('¿Estás seguro que lo quieres activar?', '<?= SITE_TITLE ?>',function(r) {if(r)activar('usuarios', 'id_usuario', '<?= $row['id_usuario'] ?>')});">
			<?= $f->cargar_icono('stop', 'No', 'No') ?>
			</span>
			<?php endif ?>
		</td>
		<td>
			<?php if($f->mostrar('usuarios', 'U')) : ?>
			<span class="opciones" onclick="location.href='<?= BASE_URL ?>usuarios/update/var,<?= $row['id_usuario'] ?>'">
				<?= $f->cargar_icono('edit', 'Modificar', 'Modificar') ?>
			</span>
			<?php endif ?>
			<?php if($f->mostrar('usuarios', 'D')) : ?>
			<span class="opciones" onclick="jConfirm('¿Estás seguro que lo quieres eliminar?', '<?= SITE_TITLE ?>',function(r) {if(r)location.href='<?= BASE_URL ?>usuarios/delete/var,<?= $row['id_usuario'] ?>'});">
				<?= $f->cargar_icono('delete', 'Eliminar', 'Eliminar') ?>
			</span>
			<?php endif ?>
		</td>
	</tr>
	<?php endforeach ?>
</table>
<?php else : ?>
<h3>No se encontraron resultados</h3>
<?php endif ?>
<div id="paginacion"><?= $paginacion ?></div>