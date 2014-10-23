<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TOCGenerator
 *
 * @author Richard
 */
class HTMLGenerator {
    private $table;
    private $database;
    private $strParamSigns = "";
    private $strFieldNames;
    private $strFieldTypes;
    private $strFieldLength;
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
            //Class Definition
            $strClass = '<?php require_once dirname(dirname(dirname(__FILE__)))."/xajax/'.strtolower($this->table).".xajax".'.php"; ?>'."\n";
            $strClass .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">'."\n";
            $strClass .= '<html>'."\n";
            $strClass .= "\t".'<head>'."\n";
            $strClass .= "\t\t".'<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">'."\n";
            $strClass .= "\t\t".'<link rel="stylesheet" href="../css/default.css" type="text/css" media="screen" />'."\n";
            $strClass .= "\t\t".'<link rel="stylesheet" href="../css/table.css" type="text/css" media="screen" />'."\n";
            $strClass .= "\t\t".'<title>'.strtolower($this->table).'</title>'."\n";
            $strClass .= "\t\t".'<?php $xajax->printJavascript(MY_URI."/lib/xajax/"); ?>'."\n";
            $strClass .= "\t\t".'<script type="text/javascript">'."\n";
            $strClass .= "\t\t\t".'xajax.callback.global.onRequest = function() {xajax.$(\'loading\').style.display = \'block\';}'."\n";
            $strClass .= "\t\t\t".'xajax.callback.global.beforeResponseProcessing = function() {xajax.$(\'loading\').style.display=\'none\';}'."\n";
            $strClass .= "\t\t".'</script>'."\n";
            $strClass .= "\t\t".'<script type="text/javascript">'."\n";
            $strClass .= "\t\t\t".'function readonly(form,field,state){'."\n";
            $strClass .= "\t\t\t\t".'document.forms[form].elements[field].readOnly=state;'."\n";
            $strClass .= "\t\t\t".'}'."\n";
            $strClass .= "\t\t\t".'function hide(elem){'."\n";
            $strClass .= "\t\t\t\t".'document.getElementById(elem).style.visibility = "hidden";'."\n";
            $strClass .= "\t\t\t\t".'document.getElementById(elem).style.height = "0px";'."\n";
            $strClass .= "\t\t\t".'}'."\n";
            $strClass .= "\t\t".'</script>'."\n";
            $strClass .= "\t".'</head>'."\n";
            $strClass .= "\t".'<body onload="xajax_preload();">'."\n";
            $strClass .= "\t".'<span class="formuser">Bienvenido: <span id="logger_user" name="logger_user"></span></span>'."\n";
            $strClass .= "\t".'<div class="formtitle" style="border-bottom: #336699 solid thin;font-weight: normal;font-family: Verdana;margin-bottom: 10px;">TITULO FORMULARIO</div>'."\n";
            $strClass .= "\t\t".'<div id="loading"><img src="../img/actions/loading6.gif" alt="Loading..." id="loadingimg"/><br /> Cargando...</div>'."\n";
            $strClass .= "\t\t".'<form action="#" method="post" id="formulario">'."\n";
            $strClass .= "\t\t\t".'<div class="toolbar">'."\n";
            $strClass .= "\t\t\t\t".'<a href=<?php echo "/".MENU_URL?> target="_self" id="homebutton" ><img src="../img/actions//home.png" alt="Inicio" title="Inicio"></a>'."\n";
            $strClass .= "\t\t\t\t".'<a href="#" target="_self" onclick="xajax_add();" id="addbutton" ><img src="../img/actions//new.png" alt="Nuevo" title="Nuevo"></a>'."\n";
            $strClass .= "\t\t\t\t".'<a href="#" target="_self" onclick="xajax_searchfields(xajax.getFormValues(\'formulario\'),0);" id="searchbutton" ><img src="../img/actions//search.png" alt="Buscar" title="Buscar"></a>'."\n";
            $strClass .= "\t\t\t\t".'<a href="#" target="_self" onclick="xajax_save(xajax.getFormValues(\'formulario\'));" id="savebutton" style="visibility:hidden"><img src="../img/actions//save.png" alt="Guardar" title="Guardar"></a>'."\n";
            $strClass .= "\t\t\t\t".'<a href="#" target="_self" onclick="xajax_cancel(xajax.getFormValues(\'formulario\'));" id="cancelbutton" style="visibility:hidden"><img src="../img/actions//cancel.png" alt="Cancelar" title="Cancelar"></a>'."\n";
            $strClass .= "\t\t\t".'</div>'."\n";
            $strClass .= "\t\t\t".'<fieldset id="searchfields" style="visibility:visible;height:0px;">'."\n";
            $strClass .= "\t\t\t".'<legend>Buscar Por</legend>'."\n";
            $strClass .= "\t\t\t\t".'<span>'."\n";
            $strClass .= "\t\t\t\t".'</span>'."\n";
            $strClass .= "\t\t\t".'</fieldset>'."\n";
            $strClass .= "\t\t\t".'<div id="tablebox"></div>'."\n";
            $strClass .= "\t\t\t".'<!--//-- IBRAC -->'."\n";
            $strClass .= "\t\t\t".'<input id="ibrac" name="ibrac" type="hidden"/>'."\n";
            $strClass .= "\t\t\t".'<!--//-- IBRAC -->'."\n";
            $strClass .= "\t\t\t".'<div class="fields" id="fields" style="visibility:hidden">'."\n";
            $i = 0;
            foreach ($this->strFieldNames as $field){
                
                $strClass .= "\t\t\t\t".'<div class="inputs">'."\n";
                $strClass .= "\t\t\t\t\t".'<span class="labels">'.strtolower($field).'</span>'."\n";
                if(strtolower($field) == strtolower($this->strPkField))
                    $strClass .= "\t\t\t\t\t".'<input id="txt_'.strtolower($field).'" name="txt_'.strtolower($field).'" type="text" readonly="readonly" maxlength="'.$this->strFieldLength[$i].'"/>'."\n";
                else
                    $strClass .= "\t\t\t\t\t".'<input id="txt_'.strtolower($field).'" name="txt_'.strtolower($field).'" type="text" maxlength="'.$this->strFieldLength[$i].'"/>'."\n";
                $strClass .= "\t\t\t\t\t".'<span id="valid_'.strtolower($field).'" name="valid_'.strtolower($field).'"></span>'."\n";
                $strClass .= "\t\t\t\t".'</div>'."\n";

                $i++;
            }
            $strClass .= "\t\t\t".'</div>'."\n";
            $strClass .= "\t\t".'</form>'."\n";
            $strClass .= "\t".'</body>'."\n";

            $strClass .= '</html>';
            //End Class Definition

            return $strClass;
        }catch(Exception $ex){
            throw new Exception($ex);
        }
    }

    private function setTableNameUpper(){
        $pchar = $this->table[0];
        $this->table[0] = strtoupper($pchar);
    }

    private function setExtraVariables(){
        $conn = Connection::getinstance()->getConn();
        odbc_exec($conn, "USE ".$this->database);

        $this->strPkField = "";
        $this->strFieldTypes = "";
        $this->strFieldNames = array();
        $this->strFieldLength = array();
        $fields = odbc_exec($conn,DESC_TABLE." ".$this->table);
	if($fields){
        $count = 0;
            while ($ResultSet = odbc_fetch_array($fields)) {
                //Define the Field Names
                $field = $ResultSet[COLUMN_NAME];
                $field[0] = strtoupper($field[0]);
                $this->strFieldNames[$count] = $field;
                $this->strFieldLength[$count] = $ResultSet[COLUMN_LENGTH];
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
