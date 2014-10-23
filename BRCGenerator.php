<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BRCGenerator
 *
 * @author Richard
 * @param String $table
 * @param String $database
 */
class BRCGenerator {
    //put your code here
    private $table;
    private $database;
    private $strParamSigns = "";
    private $strFieldNames;
    private $strFieldTypes;
    /**
     *
     * @param String $table
     * @param String $database
     */
    function  __construct($database,$table) {
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
            //Include Definitions
            $strClass .= "\t".'include_once (dirname(dirname(dirname(__FILE__)))."\\cnx\\connection.php");'."\n";
            $strClass .= "\t".'include_once (dirname(dirname(__FILE__))."\\dto\\'.$this->table.'.dto.php");'."\n";
            $strClass .= "\t".'include_once (dirname(dirname(dirname(__FILE__)))."\lib\logs.php");'."\n";
            $strClass .= "\t".'include_once (dirname(dirname(dirname(__FILE__)))."\sistema\dao\pa_secuencias.dao.php");'."\n";
            $strClass .= "\n";
            //Class Definition
            $strClass .= "\t".'class '.$this->table."DAO {\n";

            //Atributes Definition
            $strClass .= "\t\t".'private $connection;'."\n \n";

            //Constructor Definition
            $strClass .= "\t\t".'function '.$this->table."DAO(){\n"."\t\t"."}\n\n";

            //Insert Method Definition
            $strClass .= "\t\t".'/**'."\n";
            $strClass .= "\t\t".'* Añade un Registro'."\n";;
            $strClass .= "\t\t".'*'."\n";
            $strClass .= "\t\t".'* @param '.$this->table.'TO $elem'."\n";;
            $strClass .= "\t\t".'* @return int Filas Afectadas'."\n";;
            $strClass .= "\t\t".'*/'."\n";
            $strClass .= "\t\t".'function insert'.$this->table.'('.$this->table.'TO $elem){'."\n";
            $strClass .= "\t\t\t".'$this->connection = Connection::getinstance()->getConn();'."\n";
            
            $strClass .= "\t\t\t".'$PreparedStatement = "INSERT INTO '.$this->table.' (';
            foreach ($this->strFieldNames as $field){
                $strClass .= strtolower($field).',';
            }
            $strClass[strlen($strClass)-1] = ' ';
            $strClass .= ') VALUES ('."\n";
            //$strClass .= "\n";
            //$strClass .= "\t\t\t".'$PreparedStatement->bind_param("'.$this->strFieldTypes.'", '."\n";
            $count = 0;
            foreach ($this->strFieldNames as $field){
                if($this->strFieldTypes[$count] == "s")
                    $strClass .= "\t\t\t\t".'\'".$elem->get'.$field.'()."\','."\n";
                else
                    $strClass .= "\t\t\t\t".'".$elem->get'.$field.'().",'."\n";

                $count++;
            }
            $strClass[strlen($strClass)-1] = ' ';
            $strClass[strlen($strClass)-2] = ' ';
            $strClass .= ');"';
            $strClass .= ';';
            $strClass .= "\n\n";
            $strClass .= "\t\t\t".'$ResultSet = odbc_exec($this->connection, $PreparedStatement);'."\n";
            $strClass .= "\t\t\t".'logs::set_log(__FILE__,__CLASS__,__METHOD__, $PreparedStatement);'."\n";
            $strClass .= "\t\t\t".'return odbc_num_rows($ResultSet);'."\n";
            $strClass .= "\n";
            $strClass .= "\t\t"."}"."\n";
            $strClass .= "\n";

            //Update Method Definition
            $strClass .= "\t\t".'/**'."\n";;
            $strClass .= "\t\t".'* Actualiza un Registro'."\n";;
            $strClass .= "\t\t".'*'."\n";;
            $strClass .= "\t\t".'* @param '.$this->table.'TO $elem'."\n";;
            $strClass .= "\t\t".'* @return int Filas Afectadas'."\n";;
            $strClass .= "\t\t".'*/'."\n";
            $strClass .= "\t\t".'function update'.$this->table.'('.$this->table.'TO $elem){'."\n";
			$strClass .= "\t\t\t".'$this->connection = Connection::getinstance()->getConn();'."\n";
			$strClass .= "\t\t\t".'$PreparedStatement = "UPDATE '.$this->table.' SET  '."\n";
            for($i = 0; $i < sizeof($this->strFieldNames); ++$i){
                if($i > 0 ){
                    if($this->strFieldTypes[$i] == "s")
                        $strClass .= "\t\t\t\t".strtolower($this->strFieldNames[$i]).' = \'".$elem->get'.$this->strFieldNames[$i].'()."\','."\n";
                    else
                        $strClass .= "\t\t\t\t".strtolower($this->strFieldNames[$i]).' = ".$elem->get'.$this->strFieldNames[$i].'().",'."\n";
                }
            }
            $strClass[strlen($strClass)-2] = ' ';
            $strClass .= "\t\t\t".'WHERE '.strtolower($this->strFieldNames[0]).' = ". $elem->get'.$this->strFieldNames[0].'()'. '.";"';
            $strClass .= "\n\n";
            $aux = $this->strFieldTypes;
            $pchar = $this->strFieldTypes[0];
            $this->strFieldTypes[0] = '';
            $this->strFieldTypes[strlen($this->strFieldTypes)] = $pchar;
            $this->strFieldTypes = $aux;
            $strClass[strlen($strClass)-2] = ' ';
            $strClass[strlen($strClass)-1] = ';';
            $strClass .= "\n\n";
            $strClass .= "\t\t\t".'$ResultSet = odbc_exec($this->connection, $PreparedStatement);'."\n";
            $strClass .= "\t\t\t".'logs::set_log(__FILE__,__CLASS__,__METHOD__, $PreparedStatement);'."\n";
            $strClass .= "\t\t\t".'return odbc_num_rows($ResultSet);'."\n";
            $strClass .= "\n";
            $strClass .= "\t\t"."}\n";
            $strClass .= "\n";

            //Delete Method Definition
            $strClass .= "\t\t".'/**'."\n";;
            $strClass .= "\t\t".'* Elimina un Registro'."\n";;
            $strClass .= "\t\t".'*'."\n";;
            $strClass .= "\t\t".'* @param '.$this->table.'TO $elem'."\n";;
            $strClass .= "\t\t".'* @return int Filas Afectadas'."\n";;
            $strClass .= "\t\t".'*/'."\n";
            $strClass .= "\t\t".'function delete'.$this->table.'('.$this->table.'TO $elem){'."\n";
			$strClass .= "\t\t\t".'$this->connection = Connection::getinstance()->getConn();'."\n";
			$strClass .= "\t\t\t".'$PreparedStatement = "DELETE FROM '.$this->table.' ';
            $strClass .= 'WHERE '.strtolower($this->strFieldNames[0]).' = ". $elem->get'.$this->strFieldNames[0].'().";";';
            $strClass .= "\n\n";
            $strClass .= "\t\t\t".'$ResultSet = odbc_exec($this->connection, $PreparedStatement);'."\n";
            $strClass .= "\t\t\t".'logs::set_log(__FILE__,__CLASS__,__METHOD__, $PreparedStatement);'."\n";
            $strClass .= "\t\t\t".'return odbc_num_rows($ResultSet);'."\n";
            $strClass .= "\n";
            $strClass .= "\t\t"."}\n";
            $strClass .= "\n";
            
            //Select All Method Definition
            $strClass .= "\t\t".'/**'."\n";;
            $strClass .= "\t\t".'* Obtiene una lista Completa'."\n";;
            $strClass .= "\t\t".'*'."\n";;
            $strClass .= "\t\t".'* @return ArrayObject '.$this->table.'TO'."\n";
            $strClass .= "\t\t".'* @param int $page_number'."\n";
            $strClass .= "\t\t".'*/'."\n";
            $strClass .= "\t\t".'function selectAll'.$this->table.'($page_number){'."\n";
			$strClass .= "\t\t\t".'$this->connection = Connection::getinstance()->getConn();'."\n";
			$strClass .= "\t\t\t".'$PreparedStatement = "SELECT ';
                        foreach ($this->strFieldNames as $field){
                            $strClass .= strtolower($field).", ";
                        }
                        $strClass[strlen($strClass)-2] = ' ';
            $strClass .= "\n";
            $strClass .= "\t\t\t".'FROM ( SELECT *, ROW_NUMBER() OVER (ORDER BY '.strtolower($this->strFieldNames[1]).') AS row FROM '.$this->table.' WHERE '.strtolower($this->strFieldNames[0]).' = '.strtolower($this->strFieldNames[0]).' ) AS '.$this->table.'_tmp '."\n";
            $strClass .= "\t\t\t".'WHERE row > ".($page_number*TABLE_ROW_VIEW)." and row <= ". ($page_number+1)*TABLE_ROW_VIEW." ORDER BY '.strtolower($this->strFieldNames[1]).';";'."\n\n";
            $strClass .= "\t\t\t".'$ResultSet = odbc_exec($this->connection, $PreparedStatement);'."\n";
            $strClass .= "\t\t\t".'logs::set_log(__FILE__,__CLASS__,__METHOD__, $PreparedStatement);'."\n";
            $strClass .= "\n";
            $strClass .= "\t\t\t".'$arrayList = new ArrayObject();'."\n";
            $strClass .= "\t\t\t".' while ($row = odbc_fetch_array($ResultSet)) {'."\n";
			$strClass .= "\t\t\t\t".'$elem = new '.$this->table.'TO();'."\n";
            for($i = 0; $i < sizeof($this->strFieldNames); ++$i){
                $strClass .= "\t\t\t\t".'$elem->set'.$this->strFieldNames[$i].'($row[\''.strtolower($this->strFieldNames[$i]).'\']);'."\n";
            }
            $strClass .= "\n";
            $strClass .= "\t\t\t\t".'$arrayList->append($elem);'."\n";
            $strClass .= "\t\t\t".'}';
            $strClass .= "\n";
            $strClass .= "\t\t\t".'odbc_free_result($ResultSet);'."\n";
            $strClass .= "\t\t\t".'return $arrayList;'."\n";
            $strClass .= "\t\t"."}\n";
            $strClass .= "\n";

            //Select ID Method Definition
            $strClass .= "\t\t".'/**'."\n";;
            $strClass .= "\t\t".'* Obtiene un objeto '.$this->table.'TO'."\n";;
            $strClass .= "\t\t".'*'."\n";;
            $strClass .= "\t\t".'* @return '.$this->table.'TO elem'."\n";;
            $strClass .= "\t\t".'*/'."\n";
            $strClass .= "\t\t".'function selectById'.$this->table.'($'.strtolower($this->strFieldNames[0]).'){'."\n";
			$strClass .= "\t\t\t".'$this->connection = Connection::getinstance()->getConn();'."\n";
//			$strClass .= "\t\t\t".'$PreparedStatement = "SELECT * FROM '.$this->table.' ';
//            $strClass .= 'WHERE '.$this->strFieldNames[0].' = '.$this->strFieldTypes[0].' ;";';
//            $strClass .= "\n";
            $strClass .= "\t\t\t".'$PreparedStatement = "SELECT ';
            foreach ($this->strFieldNames as $field){
                $strClass .= strtolower($field).", ";
            }
            $strClass[strlen($strClass)-2] = ' ';
            $strClass .=' FROM '.$this->table;
            $strClass .= ' WHERE '.strtolower($this->strFieldNames[0]).' = $'.strtolower($this->strFieldNames[0]).' ;";';
            $strClass .= "\n";
//            $strClass .= "\t\t\t".'$PreparedStatement->bind_param("'.$this->strFieldTypes[0].'", ';
//            $strClass .= '$'.$this->strFieldNames[0].');'."\n";
            $strClass .= "\t\t\t".'$ResultSet = odbc_exec($this->connection, $PreparedStatement);'."\n";
            $strClass .= "\t\t\t".'logs::set_log(__FILE__,__CLASS__,__METHOD__, $PreparedStatement);'."\n";
            $strClass .= "\n";
//            $strClass .= "\t\t\t".'$PreparedStatement->bind_result(';
//            for($i = 0; $i < sizeof($this->strFieldNames); ++$i){
//                $strClass .= '$'.$this->strFieldNames[$i].', ';
//            }
//            $strClass[strlen($strClass)-2] = ')';
//            $strClass[strlen($strClass)-1] = ';';
//            $strClass .= "\n";
            $strClass .= "\t\t\t".'$elem = new '.$this->table.'TO();'."\n";
            $strClass .= "\t\t\t".'while($row = odbc_fetch_array($ResultSet)){'."\n";
			$strClass .= "\t\t\t\t".'$elem = new '.$this->table.'TO();'."\n";
            for($i = 0; $i < sizeof($this->strFieldNames); ++$i){
                $strClass .= "\t\t\t\t".'$elem->set'.$this->strFieldNames[$i].'($row[\''.strtolower($this->strFieldNames[$i]).'\']);'."\n";
            }
            $strClass .= "\n";
            $strClass .= "\t\t\t".'}'."\n";
            $strClass .= "\t\t\t".'odbc_free_result($ResultSet);'."\n";
			$strClass .= "\t\t\t".'return $elem;'."\n";
            $strClass .= "\t\t".'}'."\n";
            $strClass .= "\n";
            $strClass .= "\t\t".'/**'."\n";;
            $strClass .= "\t\t".'* Obtiene la cantidad de filas de la tabla'."\n";;
            $strClass .= "\t\t".'*'."\n";;
            $strClass .= "\t\t".'* @return int $rows'."\n";;
            $strClass .= "\t\t".'*/'."\n";
            $strClass .= "\t\t".'function selectCount'.$this->table.'($criterio){'."\n";
            $strClass .= "\t\t\t".'$this->connection = Connection::getinstance()->getConn();'."\n";
            $strClass .= "\t\t\t".'$PreparedStatement = "SELECT COUNT(*) AS count FROM '.$this->table.' WHERE '.strtolower($this->strFieldNames[0]).' = '.strtolower($this->strFieldNames[0]).' ";'."\n";

            foreach ($this->strFieldNames as $field){
                $strClass .= "\t\t\t".'if(isset ($criterio["'.strtolower($field).'"]) && trim($criterio["'.strtolower($field).'"]) != "0"){'."\n";
                    $strClass .= "\t\t\t\t".'$PreparedStatement .=" AND '.strtolower($field).' = ".$criterio["'.strtolower($field).'"];'."\n";
                $strClass .= "\t\t\t".'}'."\n";
            }
            $strClass .= "\t\t\t".'$ResultSet = odbc_exec($this->connection, $PreparedStatement);'."\n";
            $strClass .= "\t\t\t".'logs::set_log(__FILE__,__CLASS__,__METHOD__, $PreparedStatement);'."\n";
            $strClass .= "\n";
            $strClass .= "\t\t\t".'$rows = 0;'."\n";
            $strClass .= "\t\t\t".'while ($row = odbc_fetch_array($ResultSet)) {'."\n";
            $strClass .= "\t\t\t\t".'$rows = ceil($row[\'count\'] / TABLE_ROW_VIEW);'."\n";
            $strClass .= "\t\t\t".'}'."\n";
            $strClass .= "\t\t\t".'odbc_free_result($ResultSet);'."\n";
            $strClass .= "\t\t\t".'return $rows;'."\n";
            $strClass .= "\t\t"."}\n\n";

            $strClass .= "\n";
            $strClass .= "\t\t".'/**'."\n";
            $strClass .= "\t\t".'* Obtiene una coleccion de filas de la tabla'."\n";
            $strClass .= "\t\t".'*'."\n";
            $strClass .= "\t\t".'* @return ArrayObject '.$this->table.'TO'."\n";
            $strClass .= "\t\t".'* @param array $criterio'."\n";
            $strClass .= "\t\t".'*/'."\n";
            $strClass .= "\t\t".'function selectByCriteria_'.$this->table.'($criterio,$page_number){'."\n";
            $strClass .= "\t\t\t".'$this->connection = Connection::getinstance()->getConn();'."\n";
            $strClass .= "\t\t\t".'$PreparedStatement = "SELECT ';
            foreach ($this->strFieldNames as $field){
                $strClass .= strtolower($field).", ";
            }
            $strClass[strlen($strClass)-2] = ' ';
            $strClass .= "\n\t\t\t\t\t\t";
            $strClass .=' FROM ( SELECT *, ROW_NUMBER() OVER (ORDER BY '.strtolower($this->strFieldNames[1]).') AS row FROM '.$this->table.' WHERE '.strtolower($this->strFieldNames[0]).' = '.strtolower($this->strFieldNames[0]).' ";'."\n\n";
            foreach ($this->strFieldNames as $field){
                $strClass .= "\t\t\t".'if(isset ($criterio["'.strtolower($field).'"]) && trim($criterio["'.strtolower($field).'"]) != "0"){'."\n";
                    $strClass .= "\t\t\t\t".'$PreparedStatement .=" AND '.strtolower($field).' = ".$criterio["'.strtolower($field).'"];'."\n";
                $strClass .= "\t\t\t".'}'."\n";
            }
            $strClass .= "\t\t\t".'$PreparedStatement .= " ) AS '.$this->table.'_tmp WHERE row > ".($page_number*TABLE_ROW_VIEW)." and row <= ". ($page_number+1)*TABLE_ROW_VIEW." ORDER BY '.strtolower($this->strFieldNames[0]).'";'."\n";

            $strClass .= "\t\t\t".'$ResultSet = odbc_exec($this->connection, $PreparedStatement);'."\n";
            $strClass .= "\t\t\t".'logs::set_log(__FILE__,__CLASS__,__METHOD__, $PreparedStatement);'."\n";
            $strClass .= "\n";
            $strClass .= "\t\t\t".'$arrayList = new ArrayObject();'."\n";
            $strClass .= "\t\t\t".' while ($row = odbc_fetch_array($ResultSet)) {'."\n";
			$strClass .= "\t\t\t\t".'$elem = new '.$this->table.'TO();'."\n";
            for($i = 0; $i < sizeof($this->strFieldNames); ++$i){
                $strClass .= "\t\t\t\t".'$elem->set'.$this->strFieldNames[$i].'($row[\''.strtolower($this->strFieldNames[$i]).'\']);'."\n";
            }
            $strClass .= "\n";
            $strClass .= "\t\t\t\t".'$arrayList->append($elem);'."\n";
            $strClass .= "\t\t\t".'}';
            $strClass .= "\n";
            $strClass .= "\t\t\t".'odbc_free_result($ResultSet);'."\n";
            $strClass .= "\t\t\t".'return $arrayList;'."\n";
            $strClass .= "\t\t"."}\n";
            $strClass .= "\n";
            //End Class Definition
            $strClass .= "\t".'}';
            $strClass .= "\n";
            $strClass .= '?>'."\n";
            return $strClass;
        }catch(Exception $ex){
            throw new Exception($ex);
        }
    }

    private function setTableNameUpper(){
        $pchar = $this->table[0];
        $this->table[0] = strtoupper($pchar);
        //--
        $this->table = strtolower($this->table);
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
                    case "n":
                        $this->strFieldTypes .= "i";
                        break;
                    case "v":
                        $this->strFieldTypes .= "s";
                        break;
                    case "c":
                        $this->strFieldTypes .= "s";
                        break;
                    case "d":
                        $this->strFieldTypes .= "s";
                        break;
                }
                $count++;
            }
        }
        //$fields->close();
    }
}
?>

