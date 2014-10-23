<?php
  require_once("TreeMenuXL.php");
  $conn = Connection::getinstance();
  $basedatos = $conn->executeSQL("SHOW DATABASES;");

  $menu  = new HTML_TreeMenuXL();
  $nodeProperties = array("icon"=>"folder.gif","linkSelectKey"=>"#");

  if($basedatos){
  	while($result = $basedatos->fetch_object()){
  		$node1 = &new HTML_TreeNodeXL($result->Database, "#", $nodeProperties);
  		
  		$use = $conn->executeSQL("USE ".$result->Database.";");
  		$tablas = $conn->executeSQL("SHOW TABLES;");
  		
  		if($tablas){
		  	while($resultados = $tablas->fetch_assoc()){
		  		$node2 = &$node1->addItem(new HTML_TreeNodeXL($resultados['Tables_in_'.$result->Database], "#", $nodeProperties));
		  		
		  		$campos = $conn->executeSQL("DESC ".$resultados['Tables_in_'.$result->Database].";");
		  		if($campos){
		  			$nodo3 = &$node2->addItem(new HTML_TreeNodeXL("Campos", "#", $nodeProperties));
		  			while($resultadosx = $campos->fetch_object()){
		  				$nodo3->addItem(new HTML_TreeNodeXL($resultadosx->Field, "#", $nodeProperties));
		  			}
		  		}
		  	}
  		}
  		$menu->addItem($node1);
  	}
  }

  // Menu 1.0
  // Create the presentation object.
  // It will generate HTML and JavaScript for the menu
  // These statements must occur in your HTML exactly where you want the menu to appear.
  $example010 = &new HTML_TreeMenu_DHTMLXL($menu, array("images"=>"menu/TMimages"));
  $example010->printMenu();
  
?>

