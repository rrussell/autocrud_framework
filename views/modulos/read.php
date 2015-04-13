<?php if($f->mostrar('modulos', 'C')) : ?>
<a href="<?= BASE_URL ?>modulos/create" class="boton">Crear Módulo</a>
<?php endif ?>
<p class="mensaje_<?= (isset($tipo_mensaje)) ? $tipo_mensaje : '' ?>"><?= (isset($mensaje)) ? $mensaje : '' ?></p>
<div class="clear_both"></div>
<h2>Lista de Módulos</h2>
<?php if(count($results) > 0): ?>
<table class="tabla">
	<tr>
		<th>Nombre</th>
		<th style="width:50px;">Opciones</th>
	</tr>
	<?php foreach($results as $row) : ?>
	<tr>
		<td><?= $row['descripcion'] ?></td>
		<td>
			<?php if($f->mostrar('modulos', 'U')) : ?>
			<span class="opciones" onclick="location.href='<?= BASE_URL ?>modulos/update/var,<?= $row['id_modulo'] ?>'">
				<?= $f->cargar_icono('edit', 'Modificar', 'Modificar') ?>
			</span>
			<?php endif ?>
			<?php if($f->mostrar('modulos', 'D')) : ?>
			<span class="opciones" onclick="jConfirm('¿Estás seguro que lo quieres eliminar?', '<?= SITE_TITLE ?>',function(r) {if(r)location.href='<?= BASE_URL ?>modulos/delete/var,<?= $row['id_modulo'] ?>'});">
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