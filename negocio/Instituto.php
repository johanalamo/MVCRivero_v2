<?php

/**
 * Instituto.php - Modulo prueba de MVCRIVERO.
 * 
 * Este archivo contiene la implementación del objeto Instituto,
 *
 * @copyright 2016 - Instituto Universtiario de Tecnología Dr. Federico Rivero Palacio
 * @license GPLv3
 * @license http://www.gnu.org/licenses/gpl-3.0.html
 *
 *
 * 
 * @author GERALDINE CASTILLO (geralcs94@gmail.com)
 * @author JHONNY VIELMA 	  (jhonnyvq1@gmail.com)
 * @author Johan Alamo (lider de proyecto) <johan.alamo@gmail.com>
 * 
 * @package MVC
 */

	class Instituto {
		 /** @var Integer|null Código del instituto */
		private $codigo;
		/** @var String|null Nombre del instituto */
		private $nombre;
		/** @var String|null Nombre corto del instituto */
		private $nombreCorto;
		/** @var String|null Dirección corto del instituto */
		private $direccion;

		/**
		 * Metodo que permite construir el objeto instituto.
		 *
		 * 	Esta funcion contruye el objeto instituto con los valores 
		 *  pasados por parmetros es el contructor de la clase.
		 *
		 * @param String $codigo 		        Codigo del instituto.
		 * @param String $nombre				Nombre del instituto.
		 * @param String $nombreCorto		    Nombre corto del instituto.
		 * @param String $direccion		        Dirección del instituto.
		 * 
		 * @return $this                         Instancia del objeto. 
		 *
		 * @throws Exception 					No lanza exceptiones.
		 */
		function __construct ($codigo=null,$nombre=null,$nombreCorto=null,$direccion=null){
			$this->asignarCodigo($codigo);
			$this->asignarNombre($nombre);
			$this->asignarNombreCorto($nombreCorto);
			$this->asignarDireccion($direccion);
		}
		
		/**
		 * Metodo que permite asignar el codigo del objeto instituto.
		 *
		 * 	Esta funcion permite asignar al objeto instituto el codigo que 
		 * este posee, mediante el parammetro pasado a la función.
		 *
		 * @param Integer $codigo 		        Codigo del instituto.
		 * 
		 * @return $this                         Instancia del objeto. 
		 *
		 * @throws Exception 					No lanza exceptiones.
		 */
		
		public function asignarCodigo($codigo){
			$this->codigo = $codigo;
		}
		/**
		 * Metodo que permite obtener el codigo del objeto instituto.
		 *
		 * 	Esta funcion permite obtener el codigo del objeto instituto.
		 * 
		 * @return Integer                     Codigo del instituto. 
		 *
		 * @throws Exception 					No lanza exceptiones.
		 */
		public function obtenerCodigo(){
			return $this->codigo;
		}
		/**
		 * Metodo que permite asignar el nombre del objeto instituto.
		 *
		 * 	Esta funcion permite asignar al objeto instituto el nombre que 
		 * este posee, mediante el parammetro pasado a la función.
		 *
		 * @param String $nombre 		        Nombre del instituto.
		 * 
		 * @return $this                         Instancia del objeto. 
		 *
		 * @throws Exception 					No lanza exceptiones.
		 */
		public function asignarNombre($nombre){
			$this->nombre = $nombre;
		}
		/**
		 * Metodo que permite obtener el nombre del objeto instituto.
		 *
		 * 	Esta funcion permite obtener el nombre del objeto instituto.
		 * 
		 * @return String                     Nombre del instituto. 
		 *
		 * @throws Exception 					No lanza exceptiones.
		 */
		public function obtenerNombre(){
			return $this->nombre;
		}
		/**
		 * Metodo que permite asignar el nombre corto del objeto instituto.
		 *
		 * 	Esta funcion permite asignar al objeto instituto el nombre corto que 
		 * este posee, mediante el parammetro pasado a la función.
		 *
		 * @param String $nombreCorto 		     Nombre Corto del instituto.
		 * 
		 * @return $this                         Instancia del objeto. 
		 *
		 * @throws Exception 					No lanza exceptiones.
		 */
		public function asignarNombreCorto($nombreCorto){
			$this->nombreCorto = $nombreCorto;
		}
			/**
		 * Metodo que permite obtener el nombre corto del objeto instituto.
		 *
		 * 	Esta funcion permite obtener el nombre corto del objeto instituto.
		 * 
		 * @return String                     Nombre corto del instituto. 
		 *
		 * @throws Exception 					No lanza exceptiones.
		 */
		public function obtenerNombreCorto(){
			return $this->nombreCorto;
		}
			/**
		 * Metodo que permite asignar la dirección del objeto instituto.
		 *
		 * 	Esta funcion permite asignar al objeto instituto la dirección que 
		 * este posee, mediante el parammetro pasado a la función.
		 *
		 * @param String $direccion 		     Dirección del instituto.
		 * 
		 * @return $this                         Instancia del objeto. 
		 *
		 * @throws Exception 					No lanza exceptiones.
		 */		
		public function asignarDireccion($direccion){
			$this->direccion = $direccion;
		}
			/**
		 * Metodo que permite obtener la dirección del objeto instituto.
		 *
		 * 	Esta funcion permite obtener la dirección del objeto instituto.
		 * 
		 * @return Strin                     Dirección del instituto. 
		 *
		 * @throws Exception 					No lanza exceptiones.
		 */
		public function obtenerDireccion(){
			return $this->direccion;
		}	
	}
?>
