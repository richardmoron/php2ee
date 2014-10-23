<?php
include_once("configure.php");
//include_once (dirname((dirname(__FILE__))).'\conf\configure.php');
class Connection{
    private static $instance = NULL;
    private $connection = null;

    public function  __construct() {
        $this->connection = odbc_connect(DB_DSN, DB_SERVER_USERNAME,DB_SERVER_PASSWORD);
    }

    //public create instance function to create singleton
    public function getinstance(){
        if(self::$instance === null){
            $c = __CLASS__;
            self::$instance = new $c;
        }
        return self::$instance;
    }

    //get the open connection
    public function getConn(){
        if(isset ($this->connection)){
            $this->connection = odbc_connect(DB_DSN, DB_SERVER_USERNAME,DB_SERVER_PASSWORD);
        }
        return $this->connection;
    }

    //prevent clone
    public function __clone(){
        throw new Exception("Cannot clone ".__CLASS__." class");
    }

    /**
     *
     * @param String $query
     * @return ResultSet
     *
     *  $sql = new MySQLi('localhost','user','pass','db');
        $res = $sql->Query("SELECT foo,bar from table");
        while($k = $res->fetch_object()) {
           printf("%s - %s",$k->foo,$k->bar);
        }
        printf("Se encontraron %s registro(s).",$res->num_rows);
        $res->close(); $sql->close();
     */
    public function executeSQL($conn,$query){
        return odbc_exec($conn, $query);
    }
}
?>