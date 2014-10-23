<?php 
	include_once (dirname(dirname((dirname(__FILE__))))."/lib/xajax/xajax_core/xajax.inc.php");
	include_once (dirname(dirname(__FILE__))."/dao/edv_secuencia_alterna.dao.php");
	include_once (dirname(dirname((dirname(__FILE__))))."/lib/parsedate.php");
	include_once (dirname(dirname(dirname(__FILE__)))."/sistema/dao/usuario.dao.php");
	//-- IBRAC
	include_once (dirname(dirname((dirname(__FILE__))))."/lib/permisos.php");
	//-- IBRAC
	session_start();
	$xajax = new xajax();

	$xajax->registerFunction("preload"); //Carga las variables iniciales
	$xajax->registerFunction("loadfields"); //Carga los campos del formulario
	$xajax->registerFunction("loadtable"); //Carga los campos de la tabla
	$xajax->registerFunction("save"); //Guarda los campos del formulario
	$xajax->registerFunction("add"); //Limpia los campos del formulario
	$xajax->registerFunction("remove"); //Elimina los campos del formulario (Registro)
	$xajax->registerFunction("valid"); //Valida los campos del formulario
	$xajax->registerFunction("searchfields"); //Busca los datos segun el criterio
	$xajax->registerFunction("cancel"); //cancela la edicion de los campos

	function preload(){
		$objResponse = loadtable(null,0);
		$objResponse = loadUserLogged($objResponse);
		//-- IBRAC
		$objResponse = loadPrivileges($objResponse);
		//-- IBRAC
		return $objResponse;
	}
	function loadUserLogged($objResponse){
		$objusuarioDAO = new usuarioDAO();
		$usuarioTO = $objusuarioDAO->selectByIdusuarioId($_SESSION[SESSION_USER]);
		$objResponse->assign("logger_user","innerHTML",$usuarioTO->getNombres()." ".$usuarioTO->getApellidos());
		return $objResponse;
	}
	//-- IBRAC
	function loadPrivileges($objResponse,$ibrac = null){
		if($ibrac == null)
			$ibrac = permisos::getIBRAC(__FILE__);
		//--Nuevo
		if(strlen(strpos($ibrac,"I"))==0)
			$objResponse->assign("addbutton","style.visibility","hidden");
		//--Buscar
		if(strlen(strpos($ibrac,"C"))==0)
			$objResponse->assign("searchbutton","style.visibility","hidden");
		//--REPORTE
		if(strlen(strpos($ibrac,"R"))==0)
			$objResponse->includeCSS("../css/noprint.css","print");
		$objResponse->assign("ibrac","value",$ibrac);
		return $objResponse;
	}
	//-- IBRAC
	function loadfields($key){
		$objResponse = new xajaxResponse();
		$objResponse = clearvalid($objResponse);
		$objedv_secuencia_alternaDAO = new edv_secuencia_alternaDAO();
		$objedv_secuencia_alternaTO = $objedv_secuencia_alternaDAO->selectByIdedv_secuencia_alterna($key);

		$objResponse->assign("txt_id","value",$objedv_secuencia_alternaTO->getId());
		$objResponse->assign("txt_secuencia","value",$objedv_secuencia_alternaTO->getSecuencia());
		$objResponse->assign("txt_estado","value",$objedv_secuencia_alternaTO->getEstado());
		$objResponse->assign("txt_fecha","value",$objedv_secuencia_alternaTO->getFecha());
		$objResponse->assign("txt_usuario","value",$objedv_secuencia_alternaTO->getUsuario());
		$objResponse->assign("txt_justificacion","value",$objedv_secuencia_alternaTO->getJustificacion());
		$objResponse->assign("datatable","style.visibility","hidden");
		$objResponse->assign("fields","style.visibility","visible");

		$objResponse->assign("savebutton","style.visibility","visible");
		$objResponse->assign("cancelbutton","style.visibility","visible");
		$objResponse->assign("addbutton","style.visibility","visible");
		$objResponse->assign("searchfields","style.visibility","hidden");
		$objResponse->assign("searchbutton","style.visibility","hidden");
		//-- IBRAC
		$objResponse = loadPrivileges($objResponse);
		//-- IBRAC
		return $objResponse;
	}

	function loadtable($aFormValues,$page_number){
		return searchfields($aFormValues,$page_number);
	}

	function loadtable_default($page_number,$arraylist,$table_count,$criterio){
		$objResponse = new xajaxResponse();
		if($page_number >= $table_count){
			$page_number = $page_number - 1;
		}
		//-- IBRAC
		if(!isset($criterio["ibrac"]))
			$criterio["ibrac"] = permisos::getIBRAC(__FILE__);
		//-- IBRAC
		$iterator = $arraylist->getIterator();
		$strhtml = '<table id="datatable" summary="Data">'."\n";
		$strhtml .= '<thead>'."\n";
		$strhtml .= '<tr>'."\n";
		$strhtml .= '<th scope="col">Id</th>'."\n";
		$strhtml .= '<th scope="col">Secuencia</th>'."\n";
		$strhtml .= '<th scope="col">Estado</th>'."\n";
		$strhtml .= '<th scope="col">Fecha</th>'."\n";
		$strhtml .= '<th scope="col">Usuario</th>'."\n";
		$strhtml .= '<th scope="col">Justificacion</th>'."\n";
		$strhtml .= '<th scope="col" style="width:5px;"></th>'."\n";
		$strhtml .= '<th scope="col" style="width:5px;"></th>'."\n";
		$strhtml .= '</tr>'."\n";
		$strhtml .= '</thead>'."\n";
		$strhtml .= '<tfoot>'."\n";
		$strhtml .= '<tr id="footer">'."\n";
		$strhtml .= '<th colspan="'.(count(edv_secuencia_alternaTO::$FIELDS)+1).'" id="footer_right">'."\n";
		//-- IBRAC
		if(strlen(strpos($criterio["ibrac"],"C"))>0){
		//-- IBRAC
			for ($i= 0 ; $i <= $table_count-1 ; $i++) {
				if($i != $page_number){
					$strhtml .= '<a href="#" target="_self" onclick="xajax_loadtable(xajax.getFormValues(\'formulario\'),'.($i).')">'.($i+1).'</a>'."\n";
				}else{
					$strhtml .= ($i+1)."\n";
				}
			}
		}
		$strhtml .= '</th>'."\n";
		$strhtml .= '</tr>'."\n";
		$strhtml .= '</tfoot>'."\n";
		$strhtml .= '<tbody>'."\n";
		//-- IBRAC
		if(strlen(strpos($criterio["ibrac"],"C"))>0){
		//-- IBRAC
			while($iterator->valid()) {
				if($iterator->key() % 2 == 0){
					$strhtml .= '<tr class="paintedrow">'."\n";
				}else{
					$strhtml .= '<tr>'."\n";
				}
				$objedv_secuencia_alternaTO = $iterator->current();
				$strhtml .= '<td>'.$objedv_secuencia_alternaTO->getId().'</td>'."\n";
				$strhtml .= '<td>'.$objedv_secuencia_alternaTO->getSecuencia().'</td>'."\n";
				$strhtml .= '<td>'.$objedv_secuencia_alternaTO->getEstado().'</td>'."\n";
				$strhtml .= '<td>'.$objedv_secuencia_alternaTO->getFecha().'</td>'."\n";
				$strhtml .= '<td>'.$objedv_secuencia_alternaTO->getUsuario().'</td>'."\n";
				$strhtml .= '<td>'.$objedv_secuencia_alternaTO->getJustificacion().'</td>'."\n";
				//-- IBRAC
				if(strlen(strpos($criterio["ibrac"],"A"))>0)
					$strhtml .= '<td style="width:5px;"><a href="#" target="_self" onclick="xajax_loadfields('.$objedv_secuencia_alternaTO->getId().');"><img src="../img/actions/editrow.png" alt="editar" title="editar" style="border:0"/></a></td>'."\n";
				else
					$strhtml .= '<td>&nbsp;</td>';
				if(strlen(strpos($criterio["ibrac"],"B"))>0)
					$strhtml .= '<td style="width:5px;"><a href="#" target="_self" onclick="if(confirm(\'Esta Seguro?\')){xajax_remove('.$objedv_secuencia_alternaTO->getId().',xajax.getFormValues(\'formulario\'));}"><img src="../img/actions/removerow.png" alt="eliminar" title="eliminar" style="border:0"/></a></td>'."\n";
				else
					$strhtml .= '<td>&nbsp;</td>';
				//-- IBRAC
				$strhtml .= '</tr>'."\n";
				$iterator->next();
			}
		}
		$strhtml .= '</tbody>'."\n";
		$strhtml .= '</table>'."\n";
		$objResponse->assign("tablebox","innerHTML","$strhtml");
		$objResponse->assign("datatable","style.visibility","visible");
		$objResponse->assign("fields","style.visibility","hidden");

		$objResponse->assign("savebutton","style.visibility","hidden");
		$objResponse->assign("cancelbutton","style.visibility","hidden");
		$objResponse->assign("addbutton","style.visibility","visible");
		$objResponse->assign("searchfields","style.visibility","visible");
		$objResponse->assign("searchbutton","style.visibility","visible");
		//-- IBRAC
		$objResponse = loadPrivileges($objResponse,$criterio["ibrac"]);
		//-- IBRAC
		return $objResponse;
	}

	function save($aFormValues){
		$objResponse = new xajaxResponse();
		$valid = valid($aFormValues,$objResponse);
		if($valid){
			$objedv_secuencia_alternaDAO = new edv_secuencia_alternaDAO();
			$objedv_secuencia_alternaTO = new edv_secuencia_alternaTO();
			$objedv_secuencia_alternaTO->setId($aFormValues['txt_id']);
			$objedv_secuencia_alternaTO->setSecuencia($aFormValues['txt_secuencia']);
			$objedv_secuencia_alternaTO->setEstado($aFormValues['txt_estado']);
			$objedv_secuencia_alternaTO->setFecha($aFormValues['txt_fecha']);
			$objedv_secuencia_alternaTO->setUsuario($aFormValues['txt_usuario']);
			$objedv_secuencia_alternaTO->setJustificacion($aFormValues['txt_justificacion']);

			if($aFormValues['txt_id'] == '0' )
				$objedv_secuencia_alternaDAO->insertedv_secuencia_alterna($objedv_secuencia_alternaTO);
			else
				$objedv_secuencia_alternaDAO->updateedv_secuencia_alterna($objedv_secuencia_alternaTO);

			return loadtable($aFormValues,0);
		}else{
			return $objResponse;
		}
	}

	function add(){
		$objResponse = new xajaxResponse();
		$objResponse = clearvalid($objResponse);
		$objResponse->assign("txt_id","value","0");
		$objResponse->assign("txt_secuencia","value","");
		$objResponse->assign("txt_estado","value","");
		$objResponse->assign("txt_fecha","value","");
		$objResponse->assign("txt_usuario","value","");
		$objResponse->assign("txt_justificacion","value","");
		$objResponse->assign("datatable","style.visibility","hidden");
		$objResponse->assign("fields","style.visibility","visible");

		$objResponse->assign("savebutton","style.visibility","visible");
		$objResponse->assign("cancelbutton","style.visibility","visible");
		$objResponse->assign("addbutton","style.visibility","visible");
		$objResponse->assign("searchfields","style.visibility","hidden");
		$objResponse->assign("searchbutton","style.visibility","hidden");
		return $objResponse;
	}

	function remove($key,$aFormValues){
		$objedv_secuencia_alternaDAO = new edv_secuencia_alternaDAO();
		$objedv_secuencia_alternaTO = new edv_secuencia_alternaTO();
		$objedv_secuencia_alternaTO->setId($key);
		$objedv_secuencia_alternaDAO->deleteedv_secuencia_alterna($objedv_secuencia_alternaTO);
		return loadtable($aFormValues,0);
	}

	function valid($aFormValues,$objResponse){
		$valid = true;
		$objResponse = clearvalid($objResponse);
			if(trim($aFormValues['txt_id']) == ""){
				$objResponse->assign("valid_id","innerHTML", '<img src="../img/actions/alert.png" alt="Requerido" title="Requerido" />');
				$valid = false;
			}
			if(trim($aFormValues['txt_secuencia']) == ""){
				$objResponse->assign("valid_secuencia","innerHTML", '<img src="../img/actions/alert.png" alt="Requerido" title="Requerido" />');
				$valid = false;
			}
			if(trim($aFormValues['txt_estado']) == ""){
				$objResponse->assign("valid_estado","innerHTML", '<img src="../img/actions/alert.png" alt="Requerido" title="Requerido" />');
				$valid = false;
			}
			if(trim($aFormValues['txt_fecha']) == ""){
				$objResponse->assign("valid_fecha","innerHTML", '<img src="../img/actions/alert.png" alt="Requerido" title="Requerido" />');
				$valid = false;
			}
			if(trim($aFormValues['txt_usuario']) == ""){
				$objResponse->assign("valid_usuario","innerHTML", '<img src="../img/actions/alert.png" alt="Requerido" title="Requerido" />');
				$valid = false;
			}
			if(trim($aFormValues['txt_justificacion']) == ""){
				$objResponse->assign("valid_justificacion","innerHTML", '<img src="../img/actions/alert.png" alt="Requerido" title="Requerido" />');
				$valid = false;
			}
		return $valid;
	}
	function clearvalid($objResponse){
		$objResponse->assign("valid_id","innerHTML", "");
		$objResponse->assign("valid_secuencia","innerHTML", "");
		$objResponse->assign("valid_estado","innerHTML", "");
		$objResponse->assign("valid_fecha","innerHTML", "");
		$objResponse->assign("valid_usuario","innerHTML", "");
		$objResponse->assign("valid_justificacion","innerHTML", "");
		return $objResponse;
	}

	function showsearchfields(){
		$objResponse = new xajaxResponse();
		$objResponse->assign("searchfields","style.visibility","visible");
		$objResponse->assign("searchfields","style.height","auto");
		$objResponse->assign("searchtext","innerHTML","");
		return $objResponse;
	}

	function hidesearchfields(){
		$objResponse = new xajaxResponse();
		$objResponse->assign("searchfields","style.visibility","hidden");
		$objResponse->assign("searchfields","style.height","0px");
		return $objResponse;
	}

	function cancel($aFormValues){
		$objResponse = searchfields($aFormValues,0);
		return $objResponse;
	}

	function searchfields($aFormValues,$page_number){
		$criterio = array();
		$objedv_secuencia_alternaDAO = new edv_secuencia_alternaDAO();
		//-- IBRAC
		if(isset($aFormValues["ibrac"]))
			$criterio["ibrac"] = $aFormValues["ibrac"];
		//-- IBRAC
		$arraylist = $objedv_secuencia_alternaDAO->selectByCriteria_edv_secuencia_alterna($criterio,$page_number);
		$table_count = $objedv_secuencia_alternaDAO->selectCountedv_secuencia_alterna($criterio);
		$objResponse = loadtable_default($page_number,$arraylist,$table_count,$criterio);
		return $objResponse;
	}

	$xajax->processRequest();

?>