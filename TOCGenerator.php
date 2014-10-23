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
class TOCGenerator {
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
            //Class Definition
            $strClass .= "\t".'class '.strtolower($this->table)."TO {\n";
            $strClass .= "\n";

            //Atributes Definition
            $strfield = "";
            foreach ($this->strFieldNames as $field){
                $strClass .= "\t\t".'private $'.$field.';'."\n";
                $strfield .= "'".$field."',";
            }
            $strfield[strlen($strfield)-1] = ' ';
            $strClass .= "\t\t".'public static $FIELDS = array('.$strfield.');'."\n";
            $strClass .= "\t\t".'public static $PK_FIELD = '."'".$this->strPkField."'".';'."\n";
            $strClass .= "\n";
            
             //Constructor Definition
            $strClass .= "\t\t".'function '.strtolower($this->table)."TO(){\n"."\t\t"."}\n\n";

            //Getters and Setter Definition
            foreach ($this->strFieldNames as $field){
                $strClass .= "\t\t".'public function set'.$field.'($'.$field.'){'."\n";
                $strClass .= "\t\t\t".'$this->'.$field.' = utf8_decode($'.$field.');'."\n";
                $strClass .= "\t\t".'}'."\n";
                $strClass .= "\n";

                $strClass .= "\t\t".'public function get'.$field.'(){'."\n";
                $strClass .= "\t\t\t".'return utf8_encode($this->'.$field.');'."\n";
                $strClass .= "\t\t".'}'."\n";
                $strClass .= "\n";
            }

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
