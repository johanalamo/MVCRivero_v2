<?php

/**
* AccionControlador.php - Controlador del módulo accion.
*	
* Este es el controlador del módulo accion, permite manejar las 
* operaciones relacionadas con las acciones(agregar, modificar,
* eliminar, consultar y buscar), es el intermediario entre la base de datos y la vista.
*  
* @copyright 2016 - Instituto Universtiario de Tecnología Dr. Federico Rivero Palacio
* @license GPLv3
* @license http://www.gnu.org/licenses/gpl-3.0.html
*
* @author GERALDINE CASTILLO (geralcs94@gmail.com)
* @author JHONNY VIELMA 	  (jhonnyvq1@gmail.com)
* @author Johan Alamo (lider de proyecto) <johan.alamo@gmail.com>
* 
* @package MVC
*/

require_once"modulos/accion/modelo/AccionServicio.php";
require_once "negocio/Accion.php";
require_once "negocio/TablaAccion.php";

class AccionControlador{
	
	/**
	 * Método que permite manejar las acciones relacionadas a este módulo obteniendolas por POST o GET.
	 * @param 	Ninguno.
	 * @return  Ninguno. 
	 *
	 * @throws Exception 					En caso de acción no reconocida por el módulo.
	 */
	
	public static function manejarRequerimiento(){
			try {
				$accion = PostGet::obtenerPostGet('m_accion');
				$usuario=Sesion::obtenerLogin();
				if($usuario==null)
					throw new exception ("No podrá realizar ninguna operación sin antes loguearse, inicie sesión.");
				if (!$accion) 
					$accion = 'mostrar';

				if ($accion == 'mostrar'){
					if($usuario->obtenerPermiso("AccionListar"))	
						self::mostrar();
					else 
						throw new exception ("Permiso denegado a la lista de acciones, revise sus privilegios o contacte al administrador.");
				}else if ($accion == 'agregar')	{
					if($usuario->obtenerPermiso("AccionAgregar"))
						self::agregar();
					else 
						throw new exception ("Permiso denegado para agregar acciones, revise sus privilegios o contacte al administrador.");
				}else if ($accion == 'modificar')	{
					if($usuario->obtenerPermiso("AccionModificar"))	
						self::modificar();
					else 
						throw new exception ("Permiso denegado para modificar acciones, revise sus privilegios o contacte al administrador.");
				}else if ($accion == 'obtener')	{
					if($usuario->obtenerPermiso("AccionListar"))	
						self::obtener();
					else 
						throw new exception ("Permiso denegado para listar acciones acciones, revise sus privilegios o contacte al administrador.");
				}else if ($accion == 'eliminar')	{
					if($usuario->obtenerPermiso("AccionEliminar"))
						self::eliminar();
					else 
						throw new exception ("Permiso denegado para eliminar acciones, revise sus privilegios o contacte al administrador.");
				}else if ($accion == 'agrTabAccion'){
					if(($usuario->obtenerPermiso("AccionAgregar"))||($usuario->obtenerPermiso("AccionModificar")))
						self::agrTabAcion();
					else 
						throw new exception ("Permiso Denegado para agregar privilegios de acción sobre tablas, revise sus privilegios o contacte al administrador.");
				}else if ($accion == 'modTabAccion'){
					if(($usuario->obtenerPermiso("AccionAgregar"))||($usuario->obtenerPermiso("AccionModificar")))	
						self::modTabAccion();
					else 
						throw new exception ("Permiso Denegado para modificar privilegios de acción sobre tablas, revise sus privilegios o contacte al administrador.");
				}else if ($accion == 'eliTabAccion'){
					if(($usuario->obtenerPermiso("AccionAgregar"))||($usuario->obtenerPermiso("AccionModificar")))		
						self::eliTabAccion();
					else 
						throw new exception ("Permiso Denegado para eliminar privilegios de acción sobre tablas, revise sus privilegios o contacte al administrador.");
				}else
					throw new Exception ("acción inválida en el controlador de acción: ".$accion);
			}catch (Exception $e){
					throw $e;
			}
	}
	/**
	 * Método que permite obtener las acciones de la tabla t_accion a través de un patrón 
	 * enviado por POSt o GET. 
	 *
	 * @param 	Ninguno.
	 * @return  Ninguno. 
	 * @throws  No lanza excepción.
	 */
	public static function mostrar(){
		$patron = PostGet::obtenerPostGet('patron');
		$acciones= AccionServicio::listarAcciones($patron);
		Vista::asignarDato("acciones",$acciones);
		Vista::mostrar();
	}	
	/**
	 * Método que permite agregar una acción a través de los parámtros obtenidos por POST o GET. 
	 * por medio del servicio de accion.
	 * @param 	Ninguno.
	 * @return  Ninguno. 
	 * @throws  No lanza excepción.
	 */
	
	private static function agregar() {
		try{
			$accion = new Accion();
			$accion->asignarNombre(PostGet::obtenerPostGet('nombre'));
			$accion->asignarDescripcion(PostGet::obtenerPostGet('descripcion'));
			$accion->asignarCodModulo(PostGet::obtenerPostGet('codModulo'));
			$codigo=AccionServicio::agregar($accion);
			Vista::asignarDato('codAccion',$codigo);
			Vista::asignarDato('mensaje','Se ha agregado la acción '.$accion->obtenerNombre());
			Vista::asignarDato('estatus',1);
			Vista::mostrar();
		}catch(Exception $e){
			throw $e;	
		}
	}
	/**
	 * Método que permite modificar una acción a través de su código obtenido por el POST o GET. 
	 *
	 * @param 	Ninguno.
	 * @return  Ninguno. 
	 * @throws  No lanza excepción.
	 */
	
	private static function modificar() {
		try{
		 
			$accion = new Accion();
			$accion->asignarNombre(PostGet::obtenerPostGet('nombre'));
			$accion->asignarCodigo(PostGet::obtenerPostGet('codigo'));
			$accion->asignarDescripcion(PostGet::obtenerPostGet('descripcion'));
			$accion->asignarCodModulo(PostGet::obtenerPostGet('codModulo'));
			

			AccionServicio::modificar($accion);
			Vista::asignarDato('estatus',1);
			vista::asignarDato("codigo",$accion->obtenerCodigo());
			Vista::asignarDato('mensaje','Se ha modificado la acción '.$accion->obtenerNombre());
			Vista::mostrar();
		}catch(Exception $e){
			throw $e;	
		}
	}
	/**
	 * Método que permite obtener las acciones de la tabla t_accion a través de su código obtenido por
	 * POST o GET. 
	 *
	 * @param 	Ninguno.
	 * @return  Ninguno. 
	 * @throws  No lanza excepción.
	 */

	private static function obtener(){
		try{
			$codigo=PostGet::obtenerPostGet('codigo');
		 	$accion=AccionServicio::obtener($codigo);
		 	
			Vista::asignarDato('estatus',1);
			Vista::asignarDato('accion',$accion);
			Vista::mostrar();
		}catch(Exception $e){
			throw $e;	
		}
	}
		/**
	 * Método que permite eliminar una acción de la tabla t_accion. 
	 *
	 * @param 	Ninguno.
	 * @return  Ninguno. 
	 * @throws  No lanza excepción.
	 */
	private static function eliminar(){
		try{
			$codigo=PostGet::obtenerPostGet('codigo');
		 	AccionServicio::eliminar($codigo);
		 	
			Vista::asignarDato('estatus',1);
			Vista::asignarDato('codigo',$codigo);
			Vista::mostrar();
		}catch(Exception $e){
			throw $e;	
		}
	}
	/**
	 * Método que permite agregar los permisos que tiene una acción sobre una tabla
	 * a través de los parámtros obtenidos por POST o GET por medio del servicio de accion.
	 * @param 	Ninguno.
	 * @return  Ninguno. 
	 * @throws  No lanza excepción.
	 */
	private static function agrTabAcion() {
		try{
			$tabAccion = new TablaAccion();
			$tabAccion->asignarCodTabla(PostGet::obtenerPostGet('codTabla'));
			$tabAccion->asignarCodAccion(PostGet::obtenerPostGet('codAccion'));
			$tabAccion->asignarPermisos(PostGet::obtenerPostGet('permisos'));

			AccionServicio::agrTabAccion($tabAccion);
				
			Vista::asignarDato("codTabla",$tabAccion->obtenerCodTabla());
			Vista::asignarDato('mensaje','Se han agregado lo permisos de la acción: '.$tabAccion->obtenerCodAccion()." con respecto a la tabla:".$tabAccion->obtenerCodTabla());
			Vista::asignarDato('estatus',1);
			Vista::mostrar();
		}catch(Exception $e){
			throw $e;	
		}
	}
	/**
	 * Método que permite modificar los permisos que tiene una acción sobre una tabla
	 * a través de su código de acción y código de tabla obtenido por el POST o GET. 
	 *
	 * @param 	Ninguno.
	 * @return  Ninguno. 
	 * @throws  No lanza excepción.
	 */
	private static function modTabAccion() {
		try{
		 
			$tabAccion = new TablaAccion();
			$tabAccion ->asignarCodAccion(PostGet::obtenerPostGet('codAccion'));
			$tabAccion ->asignarCodTabla(PostGet::obtenerPostGet('codTabla'));
			$tabAccion ->asignarPermisos(PostGet::obtenerPostGet('permisos'));
			

			AccionServicio::modTabAccion($tabAccion);
			Vista::asignarDato('estatus',1);
			vista::asignarDato("codTabla",$tabAccion ->obtenerCodTabla());
			Vista::asignarDato('mensaje','Se han modificado los permisos de la acción: '.$tabAccion ->obtenerCodAccion()." con respecto a la tabla: ".$tabAccion->obtenerCodTabla());
			Vista::mostrar();
		}catch(Exception $e){
			throw $e;	
		}
	}
		/**
	 * Método que permite eliminar los permisos de una acción sobre una tabla a través de su 
	 * código de acción y código de tabla enviado por POST o GET. 
	 *
	 * @param 	Ninguno.
	 * @return  Ninguno. 
	 * @throws  No lanza excepción.
	 */
	private static function eliTabAccion() {
		try{
		 
			$tabAccion = new TablaAccion();
			$tabAccion ->asignarCodAccion(PostGet::obtenerPostGet('codAccion'));
			$tabAccion ->asignarCodTabla(PostGet::obtenerPostGet('codTabla'));
			

			AccionServicio::eliTabAccion($tabAccion);
			Vista::asignarDato('estatus',1);
			vista::asignarDato("codTabla",$tabAccion ->obtenerCodTabla());
			Vista::asignarDato('mensaje','Se han eliminado los permisos de la acción: '.$tabAccion ->obtenerCodAccion()." con respecto a la tabla: ".$tabAccion->obtenerCodTabla());
			Vista::mostrar();
		}catch(Exception $e){
			throw $e;	
		}
	}
	
}

?>
