<?php if($f->mostrar('{nombre}', 'C')) : ?>
<a href="<?= BASE_URL ?>{nombre}/create" class="boton">Crear {Nombre}</a>
<?php endif ?>
<p class="mensaje_<?= (isset($tipo_mensaje)) ? $tipo_mensaje : '' ?>"><?= (isset($mensaje)) ? $mensaje : '' ?></p>
<div class="clear_both"></div>
<h2>Lista de {Nombre}</h2>
<?php if(count($results) > 0): ?>
<table class="tabla">
	<tr>{cabecera}
		<th>Opciones</th>
	</tr>
	<?php foreach($results as $row) : ?>
	<tr>{valores}
		<td>
			<?php if($f->mostrar('{nombre}', 'U')) : ?>
			<span class="opciones" onclick="location.href='<?= BASE_URL ?>{nombre}/update/var,<?= $row['{clave_primaria}'] ?>'">
				<?= $f->cargar_icono('edit', 'Modificar', 'Modificar') ?>
			</span>
			<?php endif ?>
			<?php if($f->mostrar('{nombre}', 'D')) : ?>
			<span class="opciones" onclick="jConfirm('¿Estás seguro que lo quieres eliminar?', '<?= SITE_TITLE ?>',function(r) {if(r)location.href='<?= BASE_URL ?>{nombre}/delete/var,<?= $row['{clave_primaria}'] ?>'});">
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