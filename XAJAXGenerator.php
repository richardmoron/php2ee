<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TOCGenerator
 *
 * @author Richard Moron
 */
class XAJAXGenerator {
    private $table;
    private $database;
    private $strParamSigns = "";
    private $strFieldNames;
    private $strFieldTypes;
    private $strPkField;
    /**
     *
     * @param String $table
     * @param String $database
     */
    function __construct($database,$table) {
        $this->table = $table;
        $this->database = $database;
    }

    /**
     * Genera el script de la clase
     */
    function generateClass(){
        try{
            $this->setTableNameUpper();
            $this->setExtraVariables();
            $strClass = '<?php '."\n";
            //Includes Definition
            $strClass .= "\t".'include_once (dirname(dirname((dirname(__FILE__))))."/lib/xajax/xajax_core/xajax.inc.php");'."\n";
            $strClass .= "\t".'include_once (dirname(dirname(__FILE__))."/dao/'.$this->table.'.dao.php");'."\n";
            $strClass .= "\t".'include_once (dirname(dirname((dirname(__FILE__))))."/lib/parsedate.php");'."\n";
            $strClass .= "\t".'include_once (dirname(dirname(dirname(__FILE__)))."/sistema/dao/usuario.dao.php");'."\n";
            $strClass .= "\t".'//-- IBRAC'."\n";
            $strClass .= "\t".'include_once (dirname(dirname((dirname(__FILE__))))."/lib/permisos.php");'."\n";
            $strClass .= "\t".'//-- IBRAC'."\n";
            $strClass .= "\t".'session_start();'."\n";
            //Object Definition
            $strClass .= "\t".'$xajax = new xajax();'."\n\n";
            //Methods Definition
            $strClass .= "\t".'$xajax->registerFunction("preload"); //Carga las variables iniciales'."\n";
            $strClass .= "\t".'$xajax->registerFunction("loadfields"); //Carga los campos del formulario'."\n";
            $strClass .= "\t".'$xajax->registerFunction("loadtable"); //Carga los campos de la tabla'."\n";
            $strClass .= "\t".'$xajax->registerFunction("save"); //Guarda los campos del formulario'."\n";
            $strClass .= "\t".'$xajax->registerFunction("add"); //Limpia los campos del formulario'."\n";
            $strClass .= "\t".'$xajax->registerFunction("remove"); //Elimina los campos del formulario (Registro)'."\n";
            $strClass .= "\t".'$xajax->registerFunction("valid"); //Valida los campos del formulario'."\n";
            $strClass .= "\t".'$xajax->registerFunction("searchfields"); //Busca los datos segun el criterio'."\n";
            $strClass .= "\t".'$xajax->registerFunction("cancel"); //cancela la edicion de los campos'."\n\n";
            //preload method
            $strClass .= "\t".'function preload(){'."\n";
            $strClass .= "\t\t".'//--Session Control'."\n";
            $strClass .= "\t\t".'if(!isset($_SESSION[SESSION_USER])){'."\n";
            $strClass .= "\t\t\t".'$objResponse = new xajaxResponse();'."\n";
            $strClass .= "\t\t\t".'return $objResponse->redirect(MY_URL);'."\n";
            $strClass .= "\t\t".'}'."\n";
            $strClass .= "\t\t".'//--'."\n";
            $strClass .= "\t\t".'$objResponse = loadtable(null,0);'."\n";
            $strClass .= "\t\t".'$objResponse = loadUserLogged($objResponse);'."\n";
            $strClass .= "\t\t".'//-- IBRAC'."\n";
            $strClass .= "\t\t".'$objResponse = loadPrivileges($objResponse);'."\n";
            $strClass .= "\t\t".'//-- IBRAC'."\n";
            $strClass .= "\t\t".'return $objResponse;'."\n";
            $strClass .= "\t".'}'."\n";
            //load User method
            $strClass .= "\t".'function loadUserLogged($objResponse){'."\n";
            $strClass .= "\t\t".'$objusuarioDAO = new usuarioDAO();'."\n";
            $strClass .= "\t\t".'$usuarioTO = $objusuarioDAO->selectByIdusuarioId($_SESSION[SESSION_USER]);'."\n";
            $strClass .= "\t\t".'$objResponse->assign("logger_user","innerHTML",$usuarioTO->getNombres()." ".$usuarioTO->getApellidos());'."\n";
            $strClass .= "\t\t".'return $objResponse;'."\n";
            $strClass .= "\t".'}'."\n";
            // IBRAC method
            $strClass .= "\t".'//-- IBRAC'."\n";
            $strClass .= "\t".'function loadPrivileges($objResponse,$ibrac = null){'."\n";
            $strClass .= "\t\t".'if($ibrac == null)'."\n";
            $strClass .= "\t\t\t".'$ibrac = permisos::getIBRAC(__FILE__);'."\n";
            $strClass .= "\t\t".'//--Nuevo'."\n";
            $strClass .= "\t\t".'if(strlen(strpos($ibrac,"I"))==0)'."\n";
            $strClass .= "\t\t\t".'$objResponse->assign("addbutton","style.visibility","hidden");'."\n";
            $strClass .= "\t\t".'//--Buscar'."\n";
            $strClass .= "\t\t".'if(strlen(strpos($ibrac,"C"))==0)'."\n";
            $strClass .= "\t\t\t".'$objResponse->assign("searchbutton","style.visibility","hidden");'."\n";
            $strClass .= "\t\t".'//--REPORTE'."\n";
            $strClass .= "\t\t".'if(strlen(strpos($ibrac,"R"))==0)'."\n";
            $strClass .= "\t\t\t".'$objResponse->includeCSS("../css/noprint.css","print");'."\n";
            $strClass .= "\t\t".'$objResponse->assign("ibrac","value",$ibrac);'."\n";
            $strClass .= "\t\t".'return $objResponse;'."\n";
            $strClass .= "\t".'}'."\n";
            $strClass .= "\t".'//-- IBRAC'."\n";
            //loadfields method
            $strClass .= "\t".'function loadfields($key){'."\n";
            $strClass .= "\t\t".'$objResponse = new xajaxResponse();'."\n";
            $strClass .= "\t\t".'$objResponse = clearvalid($objResponse);'."\n";
            $strClass .= "\t\t".'$obj'.$this->table.'DAO = new '.$this->table.'DAO();'."\n";
            $strClass .= "\t\t".'$obj'.$this->table.'TO = '.'$obj'.$this->table.'DAO->selectById'.$this->table.'($key);'."\n\n";
            foreach ($this->strFieldNames as $field){
                $strClass .= "\t\t".'$objResponse->assign("txt_'.strtolower($field).'","value",'.'$obj'.$this->table.'TO->get'.$field.'());'."\n";
            }
            $strClass .= "\t\t".'$objResponse->assign("datatable","style.visibility","hidden");'."\n";
            $strClass .= "\t\t".'$objResponse->assign("fields","style.visibility","visible");'."\n\n";

            $strClass .= "\t\t".'$objResponse->assign("savebutton","style.visibility","visible");'."\n";
            $strClass .= "\t\t".'$objResponse->assign("cancelbutton","style.visibility","visible");'."\n";
            $strClass .= "\t\t".'$objResponse->assign("addbutton","style.visibility","visible");'."\n";
            $strClass .= "\t\t".'$objResponse->assign("searchfields","style.visibility","hidden");'."\n";
            $strClass .= "\t\t".'$objResponse->assign("searchbutton","style.visibility","hidden");'."\n";
            $strClass .= "\t\t".'//-- IBRAC'."\n";
            $strClass .= "\t\t".'$objResponse = loadPrivileges($objResponse);'."\n";
            $strClass .= "\t\t".'//-- IBRAC'."\n";
            $strClass .= "\t\t".'return $objResponse;'."\n";
            $strClass .= "\t".'}'."\n\n";
            //loadtable method
            $strClass .= "\t".'function loadtable($aFormValues,$page_number){'."\n";
//            $strClass .= "\t\t".'$obj'.$this->table.'DAO = new '.$this->table.'DAO();'."\n";
//            $strClass .= "\t\t".'$criterio = array();'."\n";
//            $strClass .= "\t\t".'$table_count = $obj'.$this->table.'DAO->selectCount'.$this->table.'($criterio);'."\n";
//            $strClass .= "\t\t".'$arraylist = $obj'.$this->table.'DAO->selectAll'.$this->table.'($page_number);'."\n";
//            $strClass .= "\t\t".'return loadtable_default($page_number,$arraylist,$table_count,$criterio);'."\n";
                $strClass .= "\t\t".'return searchfields($aFormValues,$page_number);'."\n";
            $strClass .= "\t".'}'."\n\n";
            //loadtable_default method
            $strClass .= "\t".'function loadtable_default($page_number,$arraylist,$table_count,$criterio){'."\n";
            $strClass .= "\t\t".'$objResponse = new xajaxResponse();'."\n";
            $strClass .= "\t\t".'if($page_number >= $table_count){'."\n";
            $strClass .= "\t\t\t".'$page_number = $page_number - 1;'."\n";
            $strClass .= "\t\t".'}'."\n";
            $strClass .= "\t\t".'//-- IBRAC'."\n";
            $strClass .= "\t\t".'if(!isset($criterio["ibrac"]))'."\n";
            $strClass .= "\t\t\t".'$criterio["ibrac"] = permisos::getIBRAC(__FILE__);'."\n";
            $strClass .= "\t\t".'//-- IBRAC'."\n";
            $strClass .= "\t\t".'$iterator = $arraylist->getIterator();'."\n";
            $strClass .= "\t\t".'$strhtml = \'<table id="datatable" summary="Data">\'."\n";'."\n";
            $strClass .= "\t\t".'$strhtml .= \'<thead>\'."\n";'."\n";;
            $strClass .= "\t\t".'$strhtml .= \'<tr>\'."\n";'."\n";
            foreach ($this->strFieldNames as $field){
                $strClass .= "\t\t".'$strhtml .= \'<th scope="col">'.$field.'</th>\'."\n";'."\n";
            }
            $strClass .= "\t\t".'$strhtml .= \'<th scope="col" style="width:5px;"></th>\'."\n";'."\n";
            $strClass .= "\t\t".'$strhtml .= \'<th scope="col" style="width:5px;"></th>\'."\n";'."\n";
            $strClass .= "\t\t".'$strhtml .= \'</tr>\'."\n";'."\n";
            $strClass .= "\t\t".'$strhtml .= \'</thead>\'."\n";'."\n";
            $strClass .= "\t\t".'$strhtml .= \'<tfoot>\'."\n";'."\n";
            $strClass .= "\t\t".'$strhtml .= \'<tr id="footer">\'."\n";'."\n";
            $strClass .= "\t\t".'$strhtml .= \'<th colspan="\'.(count('.$this->table.'TO::$FIELDS)+1).\'" id="footer_right">\'."\n";'."\n";
            $strClass .= "\t\t".'//-- IBRAC'."\n";
            $strClass .= "\t\t".'if(strlen(strpos($criterio["ibrac"],"C"))>0){'."\n";
            $strClass .= "\t\t".'//-- IBRAC'."\n";
            $strClass .= "\t\t\t".'for ($i= 0 ; $i <= $table_count-1 ; $i++) {'."\n";
            $strClass .= "\t\t\t\t".'if($i != $page_number){'."\n";
            $strClass .= "\t\t\t\t\t".'$strhtml .= \'<a href="#" target="_self" onclick="xajax_loadtable(xajax.getFormValues('."\'formulario\'".'),\'.($i).\')">\'.($i+1).\'</a>\'."\n";'."\n";
            $strClass .= "\t\t\t\t".'}else{'."\n";
            $strClass .= "\t\t\t\t\t".'$strhtml .= ($i+1)."\n";'."\n";
            $strClass .= "\t\t\t\t".'}'."\n";
            $strClass .= "\t\t\t".'}'."\n";
            $strClass .= "\t\t".'}'."\n";
            $strClass .= "\t\t".'$strhtml .= \'</th>\'."\n";'."\n";
            $strClass .= "\t\t".'$strhtml .= \'</tr>\'."\n";'."\n";
            $strClass .= "\t\t".'$strhtml .= \'</tfoot>\'."\n";'."\n";
            $strClass .= "\t\t".'$strhtml .= \'<tbody>\'."\n";'."\n";
            $strClass .= "\t\t".'//-- IBRAC'."\n";
            $strClass .= "\t\t".'if(strlen(strpos($criterio["ibrac"],"C"))>0){'."\n";
            $strClass .= "\t\t".'//-- IBRAC'."\n";
            $strClass .= "\t\t\t".'while($iterator->valid()) {'."\n";
            $strClass .= "\t\t\t\t".'if($iterator->key() % 2 == 0){'."\n";
            $strClass .= "\t\t\t\t\t".'$strhtml .= \'<tr class="paintedrow">\'."\n";'."\n";
            $strClass .= "\t\t\t\t".'}else{'."\n";
            $strClass .= "\t\t\t\t\t".'$strhtml .= \'<tr>\'."\n";'."\n";
            $strClass .= "\t\t\t\t".'}'."\n";
            $strClass .= "\t\t\t\t".'$obj'.$this->table.'TO = $iterator->current();'."\n";
            foreach ($this->strFieldNames as $field){
                $strClass .= "\t\t\t\t".'$strhtml .= \'<td>\'.'.'$obj'.$this->table.'TO->get'.$field.'().\'</td>\'."\n";'."\n";
            }
            $strClass .= "\t\t\t\t".'//-- IBRAC'."\n";
            $strClass .= "\t\t\t\t".'if(strlen(strpos($criterio["ibrac"],"A"))>0)'."\n";
            $strClass .= "\t\t\t\t\t".'$strhtml .= \'<td style="width:5px;"><a href="#" target="_self" onclick="xajax_loadfields(\'.'.'$obj'.$this->table.'TO->get'.$this->strFieldNames[0].'().\');"><img src="../img/actions/editrow.png" alt="editar" title="editar" style="border:0"/></a></td>\'."\n";'."\n";
            $strClass .= "\t\t\t\t".'else'."\n";
            $strClass .= "\t\t\t\t\t".'$strhtml .= \'<td>&nbsp;</td>\';'."\n";
            $strClass .= "\t\t\t\t".'if(strlen(strpos($criterio["ibrac"],"B"))>0)'."\n";
            $strClass .= "\t\t\t\t\t".'$strhtml .= \'<td style="width:5px;"><a href="#" target="_self" onclick="if(confirm('."\'Esta Seguro?\'".')){xajax_remove(\'.'.'$obj'.$this->table.'TO->get'.$this->strFieldNames[0].'().\',xajax.getFormValues('."\'formulario\'".'));}"><img src="../img/actions/removerow.png" alt="eliminar" title="eliminar" style="border:0"/></a></td>\'."\n";'."\n";
            $strClass .= "\t\t\t\t".'else'."\n";
            $strClass .= "\t\t\t\t\t".'$strhtml .= \'<td>&nbsp;</td>\';'."\n";
            $strClass .= "\t\t\t\t".'//-- IBRAC'."\n";
            $strClass .= "\t\t\t\t".'$strhtml .= \'</tr>\'."\n";'."\n";
            $strClass .= "\t\t\t\t".'$iterator->next();'."\n";
            $strClass .= "\t\t\t".'}'."\n";
            $strClass .= "\t\t".'}'."\n";
            $strClass .= "\t\t".'$strhtml .= \'</tbody>\'."\n";'."\n";
            $strClass .= "\t\t".'$strhtml .= \'</table>\'."\n";'."\n";

            $strClass .= "\t\t".'$objResponse->assign("tablebox","innerHTML","$strhtml");'."\n";
            $strClass .= "\t\t".'$objResponse->assign("datatable","style.visibility","visible");'."\n";
            $strClass .= "\t\t".'$objResponse->assign("fields","style.visibility","hidden");'."\n\n";
            
            $strClass .= "\t\t".'$objResponse->assign("savebutton","style.visibility","hidden");'."\n";
            $strClass .= "\t\t".'$objResponse->assign("cancelbutton","style.visibility","hidden");'."\n";
            $strClass .= "\t\t".'$objResponse->assign("addbutton","style.visibility","visible");'."\n";
            $strClass .= "\t\t".'$objResponse->assign("searchfields","style.visibility","visible");'."\n";
            $strClass .= "\t\t".'$objResponse->assign("searchbutton","style.visibility","visible");'."\n";
            $strClass .= "\t\t".'//-- IBRAC'."\n";
            $strClass .= "\t\t".'$objResponse = loadPrivileges($objResponse,$criterio["ibrac"]);'."\n";
            $strClass .= "\t\t".'//-- IBRAC'."\n";
            $strClass .= "\t\t".'return $objResponse;'."\n";
            $strClass .= "\t".'}'."\n\n";

            //save method
            $strClass .= "\t".'function save($aFormValues){'."\n";
            $strClass .= "\t\t".'$objResponse = new xajaxResponse();'."\n";
            $strClass .= "\t\t".'$valid = valid($aFormValues,$objResponse);'."\n";
            $strClass .= "\t\t".'if($valid){'."\n";
            $strClass .= "\t\t\t".'$obj'.$this->table.'DAO = new '.$this->table.'DAO();'."\n";
            $strClass .= "\t\t\t".'$obj'.$this->table.'TO = new '.$this->table.'TO();'."\n";
            foreach ($this->strFieldNames as $field){
                $strClass .= "\t\t\t".'$obj'.$this->table.'TO->set'.$field.'($aFormValues[\'txt_'.strtolower($field).'\']);'."\n";
            }
            $strClass .= "\n";
            $strClass .= "\t\t\t".'if($aFormValues[\'txt_'.strtolower($this->strFieldNames[0]).'\'] == \'0\' )'."\n";
            $strClass .= "\t\t\t\t".'$obj'.$this->table.'DAO->insert'.$this->table.'('.'$obj'.$this->table.'TO'.');'."\n";
            $strClass .= "\t\t\t".'else'."\n";
            $strClass .= "\t\t\t\t".'$obj'.$this->table.'DAO->update'.$this->table.'('.'$obj'.$this->table.'TO'.');'."\n\n";
            $strClass .= "\t\t\t".'return loadtable($aFormValues,0);'."\n";
            $strClass .= "\t\t".'}else{'."\n";
            $strClass .= "\t\t\t".'return $objResponse;'."\n";
            $strClass .= "\t\t".'}'."\n";
            $strClass .= "\t".'}'."\n\n";

            //add method
            $strClass .= "\t".'function add(){'."\n";
            $strClass .= "\t\t".'$objResponse = new xajaxResponse();'."\n";
            $strClass .= "\t\t".'$objResponse = clearvalid($objResponse);'."\n";
            foreach ($this->strFieldNames as $field){
                if(strtolower($field) == strtolower($this->strPkField))
                    $strClass .= "\t\t".'$objResponse->assign("txt_'.strtolower($field).'","value","0");'."\n";
                 else
                    $strClass .= "\t\t".'$objResponse->assign("txt_'.strtolower($field).'","value","");'."\n";
            }
            $strClass .= "\t\t".'$objResponse->assign("datatable","style.visibility","hidden");'."\n";
            $strClass .= "\t\t".'$objResponse->assign("fields","style.visibility","visible");'."\n\n";

            $strClass .= "\t\t".'$objResponse->assign("savebutton","style.visibility","visible");'."\n";
            $strClass .= "\t\t".'$objResponse->assign("cancelbutton","style.visibility","visible");'."\n";
            $strClass .= "\t\t".'$objResponse->assign("addbutton","style.visibility","visible");'."\n";
            $strClass .= "\t\t".'$objResponse->assign("searchfields","style.visibility","hidden");'."\n";
            $strClass .= "\t\t".'$objResponse->assign("searchbutton","style.visibility","hidden");'."\n";

            $strClass .= "\t\t".'return $objResponse;'."\n";
            $strClass .= "\t".'}'."\n\n";

            //remove method
            $strClass .= "\t".'function remove($key,$aFormValues){'."\n";
            $strClass .= "\t\t".'$obj'.$this->table.'DAO = new '.$this->table.'DAO();'."\n";
            $strClass .= "\t\t".'$obj'.$this->table.'TO = new '.$this->table.'TO();'."\n";
            $strClass .= "\t\t".'$obj'.$this->table.'TO->set'.$this->strFieldNames[0].'($key);'."\n";
            $strClass .= "\t\t".'$obj'.$this->table.'DAO->delete'.$this->table.'($obj'.$this->table.'TO);'."\n";
            $strClass .= "\t\t".'return loadtable($aFormValues,0);'."\n";
            $strClass .= "\t".'}'."\n\n";

            //valid method
            $strClass .= "\t".'function valid($aFormValues,$objResponse){'."\n";
            $strClass .= "\t\t".'$valid = true;'."\n";
            $strClass .= "\t\t".'$objResponse = clearvalid($objResponse);'."\n";
            foreach ($this->strFieldNames as $field){
                $strClass .= "\t\t\t".'if(trim($aFormValues[\'txt_'.strtolower($field).'\']) == ""){'."\n";
                $strClass .= "\t\t\t\t".'$objResponse->assign("valid_'.strtolower($field).'","innerHTML", \'<img src="../img/actions/alert.png" alt="Requerido" title="Requerido" />\');'."\n";
                $strClass .= "\t\t\t\t".'$valid = false;'."\n";
                $strClass .= "\t\t\t".'}'."\n";
            }
            $strClass .= "\t\t".'return $valid;'."\n";
            $strClass .= "\t".'}'."\n";

            //clearvalid method
            $strClass .= "\t".'function clearvalid($objResponse){'."\n";
            foreach ($this->strFieldNames as $field){
                $strClass .= "\t\t".'$objResponse->assign("valid_'.strtolower($field).'","innerHTML", "");'."\n";
            }
            $strClass .= "\t\t".'return $objResponse;'."\n";
            $strClass .= "\t".'}'."\n\n";

            //showsearchfields method
            $strClass .= "\t".'function showsearchfields(){'."\n";
                $strClass .= "\t\t".'$objResponse = new xajaxResponse();'."\n";
                $strClass .= "\t\t".'$objResponse->assign("searchfields","style.visibility","visible");'."\n";
                $strClass .= "\t\t".'$objResponse->assign("searchfields","style.height","auto");'."\n";
                $strClass .= "\t\t".'$objResponse->assign("searchtext","innerHTML","");'."\n";
                $strClass .= "\t\t".'return $objResponse;'."\n";
            $strClass .= "\t".'}'."\n\n";
            //hidesearchfields method
            $strClass .= "\t".'function hidesearchfields(){'."\n";
                $strClass .= "\t\t".'$objResponse = new xajaxResponse();'."\n";
                $strClass .= "\t\t".'$objResponse->assign("searchfields","style.visibility","hidden");'."\n";
                $strClass .= "\t\t".'$objResponse->assign("searchfields","style.height","0px");'."\n";
                $strClass .= "\t\t".'return $objResponse;'."\n";
             $strClass .= "\t".'}'."\n\n";
            //cancel method
            $strClass .= "\t".'function cancel($aFormValues){'."\n";
            $strClass .= "\t\t".'$objResponse = searchfields($aFormValues,0);'."\n";
            $strClass .= "\t\t".'return $objResponse;'."\n";
            $strClass .= "\t".'}'."\n\n";
            //searchfields method
            $strClass .= "\t".'function searchfields($aFormValues,$page_number){'."\n";
		$strClass .= "\t\t".'$criterio = array();'."\n";
		$strClass .= "\t\t".'$obj'.$this->table.'DAO = new '.$this->table.'DAO();'."\n";

                $strClass .= "\t\t".'//-- IBRAC'."\n";
                $strClass .= "\t\t".'if(isset($aFormValues["ibrac"]))'."\n";
                $strClass .= "\t\t\t".'$criterio["ibrac"] = $aFormValues["ibrac"];'."\n";
                $strClass .= "\t\t".'//-- IBRAC'."\n";
		$strClass .= "\t\t".'$arraylist = $obj'.$this->table.'DAO->selectByCriteria_'.$this->table.'($criterio,$page_number);'."\n";
		$strClass .= "\t\t".'$table_count = $obj'.$this->table.'DAO->selectCount'.$this->table.'($criterio);'."\n";

		$strClass .= "\t\t".'$objResponse = loadtable_default($page_number,$arraylist,$table_count,$criterio);'."\n";
		$strClass .= "\t\t".'return $objResponse;'."\n";
            $strClass .= "\t".'}'."\n";
            //End Definitions
            $strClass .= "\n";
            $strClass .= "\t".'$xajax->processRequest();'."\n";
            $strClass .= "\n";
            $strClass .= '?>';

            return $strClass;
        }catch(Exception $ex){
            throw new Exception($ex);
        }
    }

    private function setTableNameUpper(){
        //$pchar = $this->table[0];
        //$this->table[0] = strtoupper($pchar);
    }

    private function setExtraVariables(){
        $conn = Connection::getinstance()->getConn();
        odbc_exec($conn, "USE ".$this->database);

        $this->strPkField = "";
        $this->strFieldTypes = "";
        $this->strFieldNames = array();
        $fields = odbc_exec($conn,DESC_TABLE." ".$this->table);
	if($fields){
        $count = 0;
            while ($ResultSet = odbc_fetch_array($fields)) {
                //Define the Field Names
                $field = $ResultSet[COLUMN_NAME];
                $field[0] = strtoupper($field[0]);
                $this->strFieldNames[$count] = $field;
                //Define the Parameter Sign ?
                if($count < count($fields) -1)
                    $this->strParamSigns .= "?, ";
                else
                    $this->strParamSigns .= "?";

                //Define de Field Types

                $type = $ResultSet[TYPE_NAME];
                switch($type[0]){
                    case "i":
                        $this->strFieldTypes .= "i";
                        break;
                    case "v":
                        $this->strFieldTypes .= "s";
                        break;
                }

                // Obtiene el PK de la tabla
                if($ResultSet[PRIMARY_KEY] == PRIMARY_KEY_VALUE)
                    $this->strPkField = $ResultSet[COLUMN_NAME];

                $count++;
            }
        }
        //$fields->close();
    }
}
?>
