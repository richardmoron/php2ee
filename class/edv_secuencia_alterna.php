<?php require_once dirname(dirname(dirname(__FILE__)))."/xajax/edv_secuencia_alterna.xajax.php"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link rel="stylesheet" href="../css/default.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="../css/table.css" type="text/css" media="screen" />
		<title>edv_secuencia_alterna</title>
		<?php $xajax->printJavascript(MY_URI."/lib/xajax/"); ?>
		<script type="text/javascript">
			xajax.callback.global.onRequest = function() {xajax.$('loading').style.display = 'block';}
			xajax.callback.global.beforeResponseProcessing = function() {xajax.$('loading').style.display='none';}
		</script>
		<script type="text/javascript">
			function readonly(form,field,state){
				document.forms[form].elements[field].readOnly=state;
			}
			function hide(elem){
				document.getElementById(elem).style.visibility = "hidden";
				document.getElementById(elem).style.height = "0px";
			}
		</script>
	</head>
	<body onload="xajax_preload();">
	<span class="formuser">Bienvenido: <span id="logger_user" name="logger_user"></span></span>
	<div class="formtitle" style="border-bottom: #336699 solid thin;font-weight: normal;font-family: Verdana;margin-bottom: 10px;">TITULO FORMULARIO</div>
		<div id="loading"><img src="../img/actions/loading6.gif" alt="Loading..." id="loadingimg"/><br /> Cargando...</div>
		<form action="#" method="post" id="formulario">
			<div class="toolbar">
				<a href=<?php echo "/".MENU_URL?> target="_self" id="homebutton" ><img src="../img/actions//home.png" alt="Inicio" title="Inicio"></a>
				<a href="#" target="_self" onclick="xajax_add();" id="addbutton" ><img src="../img/actions//new.png" alt="Nuevo" title="Nuevo"></a>
				<a href="#" target="_self" onclick="xajax_searchfields(xajax.getFormValues('formulario'),0);" id="searchbutton" ><img src="../img/actions//search.png" alt="Buscar" title="Buscar"></a>
				<a href="#" target="_self" onclick="xajax_save(xajax.getFormValues('formulario'));" id="savebutton" style="visibility:hidden"><img src="../img/actions//save.png" alt="Guardar" title="Guardar"></a>
				<a href="#" target="_self" onclick="xajax_cancel(xajax.getFormValues('formulario'));" id="cancelbutton" style="visibility:hidden"><img src="../img/actions//cancel.png" alt="Cancelar" title="Cancelar"></a>
			</div>
			<fieldset id="searchfields" style="visibility:visible;height:0px;">
			<legend>Buscar Por</legend>
				<span>
				</span>
			</fieldset>
			<div id="tablebox"></div>
			<!--//-- IBRAC -->
			<input id="ibrac" name="ibrac" type="hidden"/>
			<!--//-- IBRAC -->
			<div class="fields" id="fields" style="visibility:hidden">
				<div class="inputs">
					<span class="labels">id</span>
					<input id="txt_id" name="txt_id" type="text" readonly="readonly" maxlength="20"/>
					<span id="valid_id" name="valid_id"></span>
				</div>
				<div class="inputs">
					<span class="labels">secuencia</span>
					<input id="txt_secuencia" name="txt_secuencia" type="text" maxlength="20"/>
					<span id="valid_secuencia" name="valid_secuencia"></span>
				</div>
				<div class="inputs">
					<span class="labels">estado</span>
					<input id="txt_estado" name="txt_estado" type="text" maxlength="1"/>
					<span id="valid_estado" name="valid_estado"></span>
				</div>
				<div class="inputs">
					<span class="labels">fecha</span>
					<input id="txt_fecha" name="txt_fecha" type="text" maxlength="16"/>
					<span id="valid_fecha" name="valid_fecha"></span>
				</div>
				<div class="inputs">
					<span class="labels">usuario</span>
					<input id="txt_usuario" name="txt_usuario" type="text" maxlength="15"/>
					<span id="valid_usuario" name="valid_usuario"></span>
				</div>
				<div class="inputs">
					<span class="labels">justificacion</span>
					<input id="txt_justificacion" name="txt_justificacion" type="text" maxlength="255"/>
					<span id="valid_justificacion" name="valid_justificacion"></span>
				</div>
			</div>
		</form>
	</body>
</html>