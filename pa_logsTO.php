<?php 
	class pa_logsTO {

		private $Nom_table;
		private $Nom_usuario;
		private $Fecha_hora;
		private $Accion;
		private $Det_accion;
		private $Det_error;
		public static $FIELDS = array('Nom_table','Nom_usuario','Fecha_hora','Accion','Det_accion','Det_error' );
		public static $PK_FIELD = 'nom_table';

		function pa_logsTO(){
		}

		public function setNom_table($Nom_table){
			$this->Nom_table = $Nom_table;
		}

		public function getNom_table(){
			return $this->Nom_table;
		}

		public function setNom_usuario($Nom_usuario){
			$this->Nom_usuario = $Nom_usuario;
		}

		public function getNom_usuario(){
			return $this->Nom_usuario;
		}

		public function setFecha_hora($Fecha_hora){
			$this->Fecha_hora = $Fecha_hora;
		}

		public function getFecha_hora(){
			return $this->Fecha_hora;
		}

		public function setAccion($Accion){
			$this->Accion = $Accion;
		}

		public function getAccion(){
			return $this->Accion;
		}

		public function setDet_accion($Det_accion){
			$this->Det_accion = $Det_accion;
		}

		public function getDet_accion(){
			return $this->Det_accion;
		}

		public function setDet_error($Det_error){
			$this->Det_error = $Det_error;
		}

		public function getDet_error(){
			return $this->Det_error;
		}

	}
?>
