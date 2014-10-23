<?php 
	include_once (dirname(dirname(dirname(__FILE__)))."\cnx\connection.php");
	include_once (dirname(dirname(__FILE__))."\dto\edv_secuencia_alterna.dto.php");
	include_once (dirname(dirname(dirname(__FILE__)))."\lib\logs.php");
	include_once (dirname(dirname(dirname(__FILE__)))."\sistema\dao\pa_secuencias.dao.php");

	class edv_secuencia_alternaDAO {
		private $connection;
 
		function edv_secuencia_alternaDAO(){
		}

		/**
		* Añade un Registro
		*
		* @param edv_secuencia_alternaTO $elem
		* @return int Filas Afectadas
		*/
		function insertedv_secuencia_alterna(edv_secuencia_alternaTO $elem){
			$this->connection = Connection::getinstance()->getConn();
			$PreparedStatement = "INSERT INTO edv_secuencia_alterna (id,secuencia,estado,fecha,usuario,justificacion ) VALUES (
				".$elem->getId().",
				".$elem->getSecuencia().",
				'".$elem->getEstado()."',
				'".$elem->getFecha()."',
				'".$elem->getUsuario()."',
				'".$elem->getJustificacion()."'  );";

			$ResultSet = odbc_exec($this->connection, $PreparedStatement);
			logs::set_log(__FILE__,__CLASS__,__METHOD__, $PreparedStatement);
			return odbc_num_rows($ResultSet);

		}

		/**
		* Actualiza un Registro
		*
		* @param edv_secuencia_alternaTO $elem
		* @return int Filas Afectadas
		*/
		function updateedv_secuencia_alterna(edv_secuencia_alternaTO $elem){
			$this->connection = Connection::getinstance()->getConn();
			$PreparedStatement = "UPDATE edv_secuencia_alterna SET  
				secuencia = ".$elem->getSecuencia().",
				estado = '".$elem->getEstado()."',
				fecha = '".$elem->getFecha()."',
				usuario = '".$elem->getUsuario()."',
				justificacion = '".$elem->getJustificacion()."' 
			WHERE id = ". $elem->getId().";" ;

			$ResultSet = odbc_exec($this->connection, $PreparedStatement);
			logs::set_log(__FILE__,__CLASS__,__METHOD__, $PreparedStatement);
			return odbc_num_rows($ResultSet);

		}

		/**
		* Elimina un Registro
		*
		* @param edv_secuencia_alternaTO $elem
		* @return int Filas Afectadas
		*/
		function deleteedv_secuencia_alterna(edv_secuencia_alternaTO $elem){
			$this->connection = Connection::getinstance()->getConn();
			$PreparedStatement = "DELETE FROM edv_secuencia_alterna WHERE id = ". $elem->getId().";";

			$ResultSet = odbc_exec($this->connection, $PreparedStatement);
			logs::set_log(__FILE__,__CLASS__,__METHOD__, $PreparedStatement);
			return odbc_num_rows($ResultSet);

		}

		/**
		* Obtiene una lista Completa
		*
		* @return ArrayObject edv_secuencia_alternaTO
		* @param int $page_number
		*/
		function selectAlledv_secuencia_alterna($page_number){
			$this->connection = Connection::getinstance()->getConn();
			$PreparedStatement = "SELECT id, secuencia, estado, fecha, usuario, justificacion  
			FROM ( SELECT *, ROW_NUMBER() OVER (ORDER BY secuencia) AS row FROM edv_secuencia_alterna WHERE id = id ) AS edv_secuencia_alterna_tmp 
			WHERE row > ".($page_number*TABLE_ROW_VIEW)." and row <= ". ($page_number+1)*TABLE_ROW_VIEW." ORDER BY secuencia;";

			$ResultSet = odbc_exec($this->connection, $PreparedStatement);
			logs::set_log(__FILE__,__CLASS__,__METHOD__, $PreparedStatement);

			$arrayList = new ArrayObject();
			 while ($row = odbc_fetch_array($ResultSet)) {
				$elem = new edv_secuencia_alternaTO();
				$elem->setId($row['id']);
				$elem->setSecuencia($row['secuencia']);
				$elem->setEstado($row['estado']);
				$elem->setFecha($row['fecha']);
				$elem->setUsuario($row['usuario']);
				$elem->setJustificacion($row['justificacion']);

				$arrayList->append($elem);
			}
			odbc_free_result($ResultSet);
			return $arrayList;
		}

		/**
		* Obtiene un objeto edv_secuencia_alternaTO
		*
		* @return edv_secuencia_alternaTO elem
		*/
		function selectByIdedv_secuencia_alterna($id){
			$this->connection = Connection::getinstance()->getConn();
			$PreparedStatement = "SELECT id, secuencia, estado, fecha, usuario, justificacion   FROM edv_secuencia_alterna WHERE id = $id ;";
			$ResultSet = odbc_exec($this->connection, $PreparedStatement);
			logs::set_log(__FILE__,__CLASS__,__METHOD__, $PreparedStatement);

			$elem = new edv_secuencia_alternaTO();
			while($row = odbc_fetch_array($ResultSet)){
				$elem = new edv_secuencia_alternaTO();
				$elem->setId($row['id']);
				$elem->setSecuencia($row['secuencia']);
				$elem->setEstado($row['estado']);
				$elem->setFecha($row['fecha']);
				$elem->setUsuario($row['usuario']);
				$elem->setJustificacion($row['justificacion']);

			}
			odbc_free_result($ResultSet);
			return $elem;
		}

		/**
		* Obtiene la cantidad de filas de la tabla
		*
		* @return int $rows
		*/
		function selectCountedv_secuencia_alterna($criterio){
			$this->connection = Connection::getinstance()->getConn();
			$PreparedStatement = "SELECT COUNT(*) AS count FROM edv_secuencia_alterna WHERE id = id ";
			if(isset ($criterio["id"]) && trim($criterio["id"]) != "0"){
				$PreparedStatement .=" AND id = ".$criterio["id"];
			}
			if(isset ($criterio["secuencia"]) && trim($criterio["secuencia"]) != "0"){
				$PreparedStatement .=" AND secuencia = ".$criterio["secuencia"];
			}
			if(isset ($criterio["estado"]) && trim($criterio["estado"]) != "0"){
				$PreparedStatement .=" AND estado = ".$criterio["estado"];
			}
			if(isset ($criterio["fecha"]) && trim($criterio["fecha"]) != "0"){
				$PreparedStatement .=" AND fecha = ".$criterio["fecha"];
			}
			if(isset ($criterio["usuario"]) && trim($criterio["usuario"]) != "0"){
				$PreparedStatement .=" AND usuario = ".$criterio["usuario"];
			}
			if(isset ($criterio["justificacion"]) && trim($criterio["justificacion"]) != "0"){
				$PreparedStatement .=" AND justificacion = ".$criterio["justificacion"];
			}
			$ResultSet = odbc_exec($this->connection, $PreparedStatement);
			logs::set_log(__FILE__,__CLASS__,__METHOD__, $PreparedStatement);

			$rows = 0;
			while ($row = odbc_fetch_array($ResultSet)) {
				$rows = ceil($row['count'] / TABLE_ROW_VIEW);
			}
			odbc_free_result($ResultSet);
			return $rows;
		}


		/**
		* Obtiene una coleccion de filas de la tabla
		*
		* @return ArrayObject edv_secuencia_alternaTO
		* @param array $criterio
		*/
		function selectByCriteria_edv_secuencia_alterna($criterio,$page_number){
			$this->connection = Connection::getinstance()->getConn();
			$PreparedStatement = "SELECT id, secuencia, estado, fecha, usuario, justificacion  
						 FROM ( SELECT *, ROW_NUMBER() OVER (ORDER BY secuencia) AS row FROM edv_secuencia_alterna WHERE id = id ";

			if(isset ($criterio["id"]) && trim($criterio["id"]) != "0"){
				$PreparedStatement .=" AND id = ".$criterio["id"];
			}
			if(isset ($criterio["secuencia"]) && trim($criterio["secuencia"]) != "0"){
				$PreparedStatement .=" AND secuencia = ".$criterio["secuencia"];
			}
			if(isset ($criterio["estado"]) && trim($criterio["estado"]) != "0"){
				$PreparedStatement .=" AND estado = ".$criterio["estado"];
			}
			if(isset ($criterio["fecha"]) && trim($criterio["fecha"]) != "0"){
				$PreparedStatement .=" AND fecha = ".$criterio["fecha"];
			}
			if(isset ($criterio["usuario"]) && trim($criterio["usuario"]) != "0"){
				$PreparedStatement .=" AND usuario = ".$criterio["usuario"];
			}
			if(isset ($criterio["justificacion"]) && trim($criterio["justificacion"]) != "0"){
				$PreparedStatement .=" AND justificacion = ".$criterio["justificacion"];
			}
			$PreparedStatement .= " ) AS edv_secuencia_alterna_tmp WHERE row > ".($page_number*TABLE_ROW_VIEW)." and row <= ". ($page_number+1)*TABLE_ROW_VIEW." ORDER BY id";
			$ResultSet = odbc_exec($this->connection, $PreparedStatement);
			logs::set_log(__FILE__,__CLASS__,__METHOD__, $PreparedStatement);

			$arrayList = new ArrayObject();
			 while ($row = odbc_fetch_array($ResultSet)) {
				$elem = new edv_secuencia_alternaTO();
				$elem->setId($row['id']);
				$elem->setSecuencia($row['secuencia']);
				$elem->setEstado($row['estado']);
				$elem->setFecha($row['fecha']);
				$elem->setUsuario($row['usuario']);
				$elem->setJustificacion($row['justificacion']);

				$arrayList->append($elem);
			}
			odbc_free_result($ResultSet);
			return $arrayList;
		}

	}
?>
