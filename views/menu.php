<h2>Panel de Control</h2>
<table width="100%" cellspacing="20px" class="align_center">
	<?php if(TRUE) : ?>
	<tr>
		<td colspan="3" class="panel_grupo">Sistema</td>
	</tr>
	<?php endif ?>
	<tr>
		<td>
			<?php if($f->mostrar('categorias', 'R')) : ?>
			<div class="apanel"><a class="panel" href="<?= BASE_URL ?>categorias">CATEGORIAS</a></div>
			<?php endif ?>
			
			<?php if($f->mostrar('clientes', 'R')) : ?>
			<div class="apanel"><a class="panel" href="<?= BASE_URL ?>clientes">CLIENTES</a></div>
			<?php endif ?>
			
			<?php if($f->mostrar('marcas', 'R')) : ?>
			<div class="apanel"><a class="panel" href="<?= BASE_URL ?>marcas">MARCAS</a></div>
			<?php endif ?>
			
			<?php if($f->mostrar('productos', 'R')) : ?>
			<div class="apanel"><a class="panel" href="<?= BASE_URL ?>productos">PRODUCTOS</a></div>
			<?php endif ?>
			
			<?php if($f->mostrar('subcategorias', 'R')) : ?>
			<div class="apanel"><a class="panel" href="<?= BASE_URL ?>subcategorias">SUBCATEGORIAS</a></div>
			<?php endif ?>
			
			<?php if($f->mostrar('ventas', 'R')) : ?>
			<div class="apanel"><a class="panel" href="<?= BASE_URL ?>ventas">VENTAS</a></div>
			<?php endif ?>
		</td>
	</tr>
</table>
<table width="100%" cellspacing="20px" class="align_center">
	<?php if($f->mostrar('modulos', 'R') || $f->mostrar('perfiles', 'R') || $f->mostrar('usuarios', 'R')) : ?>
	<tr>
		<td colspan="4" class="panel_grupo">Administración</td>
	</tr>
	<?php endif ?>
	<tr>
		<?php if($f->mostrar('modulos', 'R')) : ?>
		<td>
			<a href="<?= BASE_URL ?>modulos"><?= $f->cargar_icono('modulos', 'Módulos', 'Módulos') ?></a>
			<br />
			<a href="<?= BASE_URL ?>modulos">Módulos</a>
		</td>
		<?php endif ?>
		<?php if($f->mostrar('perfiles', 'R')) : ?>
		<td>
			<a href="<?= BASE_URL ?>perfiles"><?= $f->cargar_icono('perfiles', 'Perfiles', 'Perfiles') ?></a>
			<br />
			<a href="<?= BASE_URL ?>perfiles">Perfiles</a>
		</td>
		<?php endif ?>
		<?php if($f->mostrar('usuarios', 'R')) : ?>
		<td>
			<a href="<?= BASE_URL ?>usuarios"><?= $f->cargar_icono('usuarios', 'Usuarios', 'Usuarios') ?></a>
			<br />
			<a href="<?= BASE_URL ?>usuarios">Usuarios</a>
		</td>
		<?php endif ?>
	</tr>
</table>