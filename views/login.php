<h2>Login</h2>
<p class="mensaje_<?= (isset($tipo_mensaje)) ? $tipo_mensaje : '' ?>"><?= (isset($mensaje)) ? $mensaje : '' ?></p>
<form id="login" name="login" class="formulario" action="" method="post">
	<table>
		<tr>
			<td><label id="lmail" for="mail">Mail</label></td>
			<td> : </td>
			<td><input type="text" id="mail" name="mail" class="required email" /></td>
		</tr>
		<tr>
			<td><label id="lpasswd" for="passwd">Contraseña</label></td>
			<td> : </td>
			<td><input type="password" id="passwd" name="passwd" class="required" /></td>
		</tr>
	</table>
	<div class="botones"><input type="submit" value="INGRESAR" class="boton" /></div>
</form>