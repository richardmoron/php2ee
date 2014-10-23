<?php
  $conn = Connection::getinstance()->getConn();
  //-- Databases
  echo "\t".'<select onchange="goto(this)">'."\n";
  //$databases = $conn->executeSQL($conn,"SELECT dbs.name AS Databases FROM sys.databases AS dbs;");
  $databases = odbc_exec($conn, SHOW_DATABASES);
  if($databases){
        while ($row = odbc_fetch_array($databases)) {
            $selected = "";
            if(isset($_SESSION['database']) && $row['Databases'] == $_SESSION['database'])
                $selected = "selected";

            echo "\t\t".'<option value="left_side.php?database='.$row['Databases'].'" '.$selected.'>';
            echo "\t\t\t".$row['Databases']."\n";
            echo "\t\t".'</option>'."\n";
        }
        odbc_free_result($databases);
  }
  echo "\t".'</select>'."\n";
  //--Tables
  if(isset($_GET['database'])){
      $_SESSION['database'] = $_GET['database'];
      $database = $_SESSION['database'];
      if($database){
            echo "\t".'<div class="menubar">'."\n";
            echo "\t\t".'<ul class="menuV">'."\n";
            odbc_exec($conn, "USE ".$_GET['database']);
            $tablas = odbc_exec($conn, SHOW_TABLES);
            if($tablas){
                while ($row = odbc_fetch_array($tablas)) {
                    echo "\t\t\t".'<li class="drop">'."\n";
                    echo '<img src="img/table.png" class="imgicon">';
                    echo "\t\t\t\t".'<a target="rightside" href="right_side.php?table='.$row['mytable'].'" class="menuitem">'.$row['mytable'].'</a>'."\n";
//                    $campos = $conn->executeSQL("DESC ".$resultados['Tables_in_'.$database].";");
//                    if($campos){
//                        /*echo "\t\t\t".'<ul>'."\n";
//                        while($resultadosx = $campos->fetch_object()){
//                             echo '<img src="img/asterisk_orange.png" class="imgicon">';
//                            echo "\t\t\t\t".'<li><a target="_self" href="#">'.$resultadosx->Field.'</a></li>'."\n";
//                        }
//                        echo "\t\t\t".'</ul>'."\n";*/
//                    }
                    echo "\t\t\t".'</li>'."\n";
                }
                odbc_free_result($tablas);
            }
            echo "\t\t".'</ul>'."\n";
            echo "\t".'</div>'."\n";
      }
  }
?>

