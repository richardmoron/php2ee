<?php 
	class edv_secuencia_alternaTO {

		private $Id;
		private $Secuencia;
		private $Estado;
		private $Fecha;
		private $Usuario;
		private $Justificacion;
		public static $FIELDS = array('Id','Secuencia','Estado','Fecha','Usuario','Justificacion' );
		public static $PK_FIELD = 'id';

		function edv_secuencia_alternaTO(){
		}

		public function setId($Id){
			$this->Id = utf8_decode($Id);
		}

		public function getId(){
			return utf8_encode($this->Id);
		}

		public function setSecuencia($Secuencia){
			$this->Secuencia = utf8_decode($Secuencia);
		}

		public function getSecuencia(){
			return utf8_encode($this->Secuencia);
		}

		public function setEstado($Estado){
			$this->Estado = utf8_decode($Estado);
		}

		public function getEstado(){
			return utf8_encode($this->Estado);
		}

		public function setFecha($Fecha){
			$this->Fecha = utf8_decode($Fecha);
		}

		public function getFecha(){
			return utf8_encode($this->Fecha);
		}

		public function setUsuario($Usuario){
			$this->Usuario = utf8_decode($Usuario);
		}

		public function getUsuario(){
			return utf8_encode($this->Usuario);
		}

		public function setJustificacion($Justificacion){
			$this->Justificacion = utf8_decode($Justificacion);
		}

		public function getJustificacion(){
			return utf8_encode($this->Justificacion);
		}

	}
?>
