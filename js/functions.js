/**
 * @author Rodrigo Russell G.
 */

/**
 * Prepara el Ready (onLoad) de la pagina cargada
 * @param {Object} section
 * @param {Object} subsection
 */
function iniciar(section, subsection) {
	$().ready(function(){
		$('html').noContext();//deja inutilizado el click derecho
		setTimeout('ocultar_mensaje(\'ok\')',    3000);
		setTimeout('ocultar_mensaje(\'error\')', 4000);
		if(section != 'panel')
			if(section == 'login' && subsection == 'read')
				$('#login').validate();
			else
				if(subsection == 'create' || subsection == 'update' || subsection == 'update_messages' || subsection == 'import_xls')
					$('#' + subsection + '_' + section).validate();
	});
}

/**
 * Oculta el mensaje
 * @param {Object} tipo
 */
function ocultar_mensaje(tipo) {
	$('.mensaje_' + tipo).slideUp('slow');
}

var last_loaded = 0;
/**
 * Muestra mensaje de Loading
 */
function start_loading() {
	last_loaded = new Date().getTime();
	$('#loading').slideDown('fast');
}

/**
 * Oculta mensaje de Loading
 */
function stop_loading() {
	var now = new Date().getTime();
	if (now - last_loaded < 500) {
		setTimeout(stop_loading, 500);
		return;
	}
	$('#loading').slideUp(500);
}

/**
 * Chequea todo en modificar Perfiles_Modulos
 * @param {Object} id
 * @param {Object} pID
 */
function check_todo(id, pID) {
	$('#' + pID + ' :checkbox').attr('checked', $('#' + id).is(':checked'));
}

/**
 * Chequea toda la filo en modificar Perfiles_Modulos
 * @param {Object} id
 * @param {Object} pID
 */
function check_fila(id, pID) {
	$('#C' + pID).attr('checked', $('#' + id).is(':checked'));
	$('#R' + pID).attr('checked', $('#' + id).is(':checked'));
	$('#U' + pID).attr('checked', $('#' + id).is(':checked'));
	$('#D' + pID).attr('checked', $('#' + id).is(':checked'));
}

/**
 * Chequea toda la columna en modificar Perfiles_Modulos
 * @param {Object} id
 * @param {Object} pID
 */
function check_columna(id, pID) {
	$('#' + pID + 'biografias').attr('checked', $('#' + id).is(':checked'));
	$('#' + pID + 'canciones').attr('checked', $('#' + id).is(':checked'));
	$('#' + pID + 'imagenes').attr('checked', $('#' + id).is(':checked'));
	$('#' + pID + 'modulos').attr('checked', $('#' + id).is(':checked'));
	$('#' + pID + 'noticias').attr('checked', $('#' + id).is(':checked'));
	$('#' + pID + 'perfiles_modulos').attr('checked', $('#' + id).is(':checked'));
	$('#' + pID + 'perfiles').attr('checked', $('#' + id).is(':checked'));
	$('#' + pID + 'prensas').attr('checked', $('#' + id).is(':checked'));
	$('#' + pID + 'usuarios').attr('checked', $('#' + id).is(':checked'));
	$('#' + pID + 'videos').attr('checked', $('#' + id).is(':checked'));
}

/**
 * Si esta activo aparece el div requerido
 * @param {Object} id
 * @param {Object} pID
 */
function ver(id, pID) {
	if($('#' + id).is(':checked'))
		$('#' + pID).fadeIn(500);
	else
		$('#' + pID).fadeOut(500);
}

/**
 * 
 * @param {Object} ID_CHECKBOX
 * @param {Object} ID_DIV
 */
function check_var_message(ID_CHECKBOX, ID_DIV) {
	var id_checkbox = $('#' + ID_CHECKBOX);
	var inital = id_checkbox.is(':checked');
	var id_div = $('#' + ID_DIV)[inital ? 'removeClass' : 'addClass']('disabled');
	var id_div_inputs = id_div.find('input').attr('disabled', !inital);
	var id_div_textarea = id_div.find('textarea').attr('disabled', !inital);
	id_checkbox.click(function() {
		id_div[this.checked ? 'removeClass' : 'addClass']('disabled');
		id_div_inputs.attr('disabled', !this.checked);
		id_div_textarea.attr('disabled', !this.checked);
	});
}

function close() {
	start_loading();
	$('.window').fadeOut(500);
	$('#box').fadeOut(500);
	setTimeout('$(\'#capa\').fadeOut(500)', 500);
	setTimeout('$(\'#box\').remove()', 800);
	stop_loading();
}

function box_cambiar_passwd() {
	start_loading();
	$('#box').remove();
	$('#boxes').prepend('<div id="box" class="window"></div>');
	$('#box').css('text-align', 'left');
	$('#box').css('width', 530);
	$('#box').html('<h2>Cambiar Contraseña</h2><br /><form id="cambiar" name="cambiar" class="formulario" action="" onsubmit="return cambiar_passwd(this)" method="post"><table><tr><td><label id="lpasswd" for="passwd">Nueva Contraseña</label></td><td> : </td><td><input type="password" id="passwd" name="passwd" class="required" maxlength="32" /></td></tr><tr><td><label id="lcpasswd" for="cpasswd">Confirmar Contraseña</label></td><td> : </td><td><input type="password" id="cpasswd" name="cpasswd" class="required" maxlength="32" /></td></tr></table><div class="botones"><input type="button" value="CANCELAR" class="boton close" /> <input type="submit" id="submit_btn" value="MODIFICAR" class="boton" /></div></form>');
	$('#cambiar').validate();
	$('#cpasswd').rules('add',{equalTo:'#passwd'});
	setTimeout('ver_logo(\'box\')', 500);
	stop_loading();
}

function cambiar_passwd(form) {
	if($('#passwd').val() == '') return false;
	if($('#cpasswd').val() == '') return false;
	if($('#passwd').val() != $('#cpasswd').val()) return false;
	start_loading();
	$.ajax({
		type: 'POST',
		data: $(form).serialize(),
		url: BASE_URL + 'login/cambiar_passwd',
		success: function (data, status) {			
			var mensaje = data.split('|');
			if(mensaje[0] != 'error') {
				$('#box').html('<p>' + mensaje[1] + '</p>');
				setTimeout('close()', 2000);
			} else {
				$('#box').html('<p><b>ERROR:</b> ' + mensaje[1] + '</p><div class="botones"><input type="button" value="CANCELAR" class="boton close" /></div>');
				setTimeout('close()', 2000);
			}
			stop_loading();
			return false;
		},
		error: function (data, status, e) {
			$('#box').html('<p><b>ERROR:</b> ' + e + '</p><div class="botones"><input type="button" value="CANCELAR" class="boton close" /></div>');
			setTimeout('close()', 2000);
			stop_loading();
			return false;
		}
	});
	return false;
}

function box_ver_texto(ID, seccion, funcion) {
	if(ID == '') return false;	
	start_loading();
	$('#box').remove();
	$('#boxes').prepend('<div id="box" class="window"></div>');
	$('#box').css('width', '600px');
	$.ajax({
		type: 'POST',
		data: 'id=' + ID,
		url: BASE_URL + seccion + '/' + funcion,
		success: function (data, status) {			
			var mensaje = data.split('|');
			if(mensaje[0] != 'error') {
				$('#box').html('<div style="max-height:350px;overflow:auto;text-align:left;">' + mensaje[1] + '</div><div class="botones"><input type="button" value="CERRAR" class="boton close" /></div>');
				setTimeout('ver_logo(\'box\')', 500);
			} else {
				$('#box').html('<p><b>ERROR:</b> ' + mensaje[1] + '</p><div class="botones"><input type="button" value="CERRAR" class="boton close" /></div>');
				setTimeout('ver_logo(\'box\')', 500);
			}
			stop_loading();
			return false;
		},
		error: function (data, status, e) {
			$('#box').html('<p><b>ERROR:</b> ' + e + '</p><div class="botones"><input type="button" value="CERRAR" class="boton close" /></div>');
			setTimeout('ver_logo(\'box\')', 500);
			stop_loading();
			return false;
		}
	});
}

function box_ver_imagen(ID, seccion, funcion, carpeta) {
	if(ID == '') return false;
	start_loading();
	$('#box').remove();
	$('#boxes').prepend('<div id="box" class="window"></div>');
	$.ajax({
		type: 'POST',
		data: 'id=' + ID,
		url: BASE_URL + seccion + '/' + funcion,
		success: function (data, status) {			
			var mensaje = data.split('|');
			if(mensaje[0] != 'error') {
				$('#box').html('<img src="'+ BASE_URL + 'uploads/' + carpeta + '/' + mensaje[1] + '" alt="" /><div class="botones"><input type="button" value="CERRAR" class="boton close" /></div>');
				setTimeout('ver_logo(\'box\')', 500);
			} else {
				$('#box').html('<p><b>ERROR:</b> ' + mensaje[1] + '</p><div class="botones"><input type="button" value="CERRAR" class="boton close" /></div>');
				setTimeout('ver_logo(\'box\')', 500);
			}
			stop_loading();
			return false;
		},
		error: function (data, status, e) {
			$('#box').html('<p><b>ERROR:</b> ' + e + '</p><div class="botones"><input type="button" value="CERRAR" class="boton close" /></div>');
			setTimeout('ver_logo(\'box\')', 500);
			stop_loading();
			return false;
		}
	});
}

function ver_logo(ID_DIV) {
	var id = '#' + ID_DIV;
	var maskHeight = $(document).height();
	var maskWidth = $(window).width();
	var winH = $(window).height();
	var winW = $(window).width();
	
	$('#capa').css({'width':maskWidth,'height':maskHeight});
	$('#capa').fadeIn(1000);
	$('#capa').fadeTo('slow', 0.9);
	
	$(id).css('top',  winH / 2 - $(id).height() / 2);
	$(id).css('left', winW / 2 - $(id).width() / 2);
	$(id).fadeIn(2000);
	
	$('#boxes .window .close').click(function () {
		close();
	});
	$('#capa').click(function () {
		close();
	});
}

function activar(tabla, campo, valor) {
	if(tabla == '' || campo == '' || valor == '') return false;	
	start_loading();
	$.ajax({
		type: 'POST',
		data: 'tabla=' + tabla + '&campo=' + campo + '&valor=' + valor,
		url: BASE_URL + 'panel/activar',
		success: function (data, status) {			
			var mensaje = data.split('|');
			if(mensaje[0] == 'ok') {
				nuevo = '<span class="opciones" onclick="jConfirm(\'¿Estás seguro que lo quieres desactivar?\', \'' + SITE_TITLE + '\',function(r) {if(r)desactivar(\'' + tabla + '\', \'' + campo + '\', \'' + valor + '\')});">';
				$('#act_' + valor).html(nuevo + '<img border="0" title="Si" alt="Si" src="' + BASE_URL + 'images/iconos/accept.png"></span>');
			} else {
				jAlert('<p><b>ERROR:</b> ' + mensaje[1] + '</p>', SITE_TITLE);
			}
			stop_loading();
			return false;
		},
		error: function (data, status, e) {
			jAlert('<p><b>ERROR:</b> ' + e + '</p>', SITE_TITLE);
			stop_loading();
			return false;
		}
	});
}

function desactivar(tabla, campo, valor) {
	if(tabla == '' || campo == '' || valor == '') return false;	
	start_loading();
	$.ajax({
		type: 'POST',
		data: 'tabla=' + tabla + '&campo=' + campo + '&valor=' + valor,
		url: BASE_URL + 'panel/desactivar',
		success: function (data, status) {			
			var mensaje = data.split('|');
			if(mensaje[0] == 'ok') {
				nuevo = '<span class="opciones" onclick="jConfirm(\'¿Estás seguro que lo quieres activar?\', \'' + SITE_TITLE + '\',function(r) {if(r)activar(\'' + tabla + '\', \'' + campo + '\', \'' + valor + '\')});">';
				$('#act_' + valor).html(nuevo + '<img border="0" title="No" alt="No" src="' + BASE_URL + 'images/iconos/stop.png"></span>');
			} else {
				jAlert('<p><b>ERROR:</b> ' + mensaje[1] + '</p>', SITE_TITLE);
			}
			stop_loading();
			return false;
		},
		error: function (data, status, e) {
			jAlert('<p><b>ERROR:</b> ' + e + '</p>', SITE_TITLE);
			stop_loading();
			return false;
		}
	});
}

function confirmar(tabla, campo, valor) {
	if(tabla == '' || campo == '' || valor == '') return false;	
	start_loading();
	$.ajax({
		type: 'POST',
		data: 'tabla=' + tabla + '&campo=' + campo + '&valor=' + valor,
		url: BASE_URL + 'panel/confirmar',
		success: function (data, status) {			
			var mensaje = data.split('|');
			if(mensaje[0] == 'ok') {
				nuevo = '<span class="opciones" onclick="jConfirm(\'¿El pago no se ha llevado a cabo?\', \'' + SITE_TITLE + '\',function(r) {if(r)desconfirmar(\'' + tabla + '\', \'' + campo + '\', \'' + valor + '\')});">';
				$('#act_' + valor).html(nuevo + '<img border="0" title="Si" alt="Si" src="' + BASE_URL + 'images/iconos/accept.png"></span>');
			} else {
				jAlert('<p><b>ERROR:</b> ' + mensaje[1] + '</p>', SITE_TITLE);
			}
			stop_loading();
			return false;
		},
		error: function (data, status, e) {
			jAlert('<p><b>ERROR:</b> ' + e + '</p>', SITE_TITLE);
			stop_loading();
			return false;
		}
	});
}

function desconfirmar(tabla, campo, valor) {
	if(tabla == '' || campo == '' || valor == '') return false;	
	start_loading();
	$.ajax({
		type: 'POST',
		data: 'tabla=' + tabla + '&campo=' + campo + '&valor=' + valor,
		url: BASE_URL + 'panel/desconfirmar',
		success: function (data, status) {			
			var mensaje = data.split('|');
			if(mensaje[0] == 'ok') {
				nuevo = '<span class="opciones" onclick="jConfirm(\'¿El pago se ha llevado a cabo?\', \'' + SITE_TITLE + '\',function(r) {if(r)confirmar(\'' + tabla + '\', \'' + campo + '\', \'' + valor + '\')});">';
				$('#act_' + valor).html(nuevo + '<img border="0" title="No" alt="No" src="' + BASE_URL + 'images/iconos/stop.png"></span>');
			} else {
				jAlert('<p><b>ERROR:</b> ' + mensaje[1] + '</p>', SITE_TITLE);
			}
			stop_loading();
			return false;
		},
		error: function (data, status, e) {
			jAlert('<p><b>ERROR:</b> ' + e + '</p>', SITE_TITLE);
			stop_loading();
			return false;
		}
	});
}