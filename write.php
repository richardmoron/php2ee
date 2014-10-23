<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of write
 *
 * @author Richard
 */
//include 'BRCGenerator.php';
//include 'TOGenerator.php';
class write {
    //put your code here

    function __construct($content_id){
        $table = $_SESSION['table'].".".$content_id;
        $this->writePHPFile($table,$content_id);
    }

    private function writePHPFile($filename,$content_id){
        if(!ereg(".php",$filename)){
            $filename = $filename.".php";
        }

        $content = "";
        switch($content_id){
            case "dto":
                $generator = new TOCGenerator($_SESSION['database'],$_SESSION['table']);
                $content = $generator->generateClass();
                break;
            case "dao":
                $generator = new BRCGenerator($_SESSION['database'],$_SESSION['table']);
                $content = $generator->generateClass();
                break;
            case "xajax":
                $generator = new XAJAXGenerator($_SESSION['database'],$_SESSION['table']);
                $content = $generator->generateClass();
                break;
            case "html":
                $generator = new HTMLGenerator($_SESSION['database'],$_SESSION['table']);
                $content = $generator->generateClass();
                $filename = str_ireplace(".HTML", "", $filename);
                break;
        }
        $success =  file_put_contents(CLASS_PATH.$filename, $content,FILE_TEXT);
        return $success;
        /*
        o usa esto si el archivo ya existe y lo que quieres es agregar el contenido.
        file_put_contents($filename, $content,FILE_APPEND|FILE_TEXT)
        */
    }
}
?>
