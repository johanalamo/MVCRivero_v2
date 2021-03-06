<?php
/**
 * AccionServicio.php - Servicio del módulo accion.
 * 
 * Esta clase ofrece el servicio de conexión a la base de datos, recibe 
 * los parámetros, construye las consultas SQL, hace las peticiones a 
 * la base de datos y retorna los objetos o datos correspondientes a la
 * acción. Todas las funciones de esta clase relanzan las excepciones si son capturadas,
 * esto con el fin de que la clase manejadora de errores las capture y procese.
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

class AccionServicio{


	/**
	 * Método que permite agregar una acción a la tabla t_accion 
	 *
	 * @param Accion $accion	    		Objeto que contiene la información de la acción.
	 *
	 * @return Integer || null              Código de la acción agregado. 
	 * @throws Exception 					En caso de haber un error al agregar la acción.
	 */

	public static function agregar($accion){
			try{
				$conexion=Conexion::conectar();
				$consulta="SELECT per.f_accion_ins(?,?,?)";
				$ejecutar=$conexion->prepare($consulta);
				$ejecutar->execute(array($accion->obtenerNombre(),
										$accion->obtenerDescripcion(),
										$accion->obtenerCodModulo(),
										));
				if ($ejecutar->rowCount()==0)
					throw new Exception("No se puede agregar la acción:".$accion->obtenerNombre());	
				$codigo = $ejecutar->fetchColumn(0);
				return $codigo;
			}catch(Exception $e){
				throw $e;
			} 
	}
	/**
	 * Método que permite modificar una acción de la tabla t_accion. 
	 * @param Accion $accion	    		Objeto que contiene la información de la acción.
	 *
	 * @throws Exception 					En caso de haber un error al agregar el módulo.
	 */
	public static function modificar($accion){
			try{
				$conexion=Conexion::conectar();
				$consulta="select per.f_accion_modificar(?,?,?,?)";
				$ejecutar=$conexion->prepare($consulta);
				$ejecutar->execute(array($accion->obtenerNombre(),
										 $accion->obtenerDescripcion(),
										 $accion->obtenerCodModulo(),
										 $accion->obtenerCodigo()
										));
										
				$row = $ejecutar->fetchColumn(0);
				if ($row==0)
					throw new Exception("Error al modificar la acción");	
			}catch(Exception $e){
				throw $e;
			} 
	}
	/**
	 * Método que permite obtener una acción según su código . 
	 * @param Integer $codigo	    		código de la acción a buscar.
	 * @return Arreglo Accion 	            Arreglo de objeto Accion si hay coincidencias
	 * @throws Exception 					En caso de error.
	 */
	public static function obtener($codigo){
			try{
				$conexion = Conexion::conectar();
				$consulta = "select per.f_accion_obtener('accion',?)";
				$ejecutar = $conexion->prepare($consulta);
				$conexion->beginTransaction();
					$ejecutar->execute(array($codigo));
					$cursors = $ejecutar->fetchAll();
					$ejecutar->closeCursor();
					$ejecutar = $conexion->query('FETCH ALL IN accion;');
					$lista=$ejecutar->fetchAll();
					$ejecutar->closeCursor();
				$conexion->commit();
				if(count($lista)>0)
					return self::retornar(true,$lista);
				else
					throw new Exception("Error al mostrar la acción");	
			}catch(Exception $e){
				throw $e;
			} 
	}
	/**
	 * Método que permite retornar un arreglo de objetos Accion si $tipObjeto es true y sino retorna la matriz
	 * de la información extraída de base de datos.
	 *
	 * @param Boolean $tipObjeto 		    Si se quiere la respuesta un arreglo objetos Accion.
	 * @param matriz $accMatriz			    Matriz que contiene la información extraída de base de datos.
	 * @return  Ninguno. 
	 * @throws Exception 					No lanza exceptiones.
	 */
	private static function retornar($tipObjeto,$accMatriz){
			if ($tipObjeto){
				for($i = 0; $i < count($accMatriz); $i++){
					$accion=new Accion();
					$accion->asignarCodigo($accMatriz[$i][0]);
					$accion->asignarNombre($accMatriz[$i][1]);
					$accion->asignarCodModulo($accMatriz[$i][2]);
					$accion->asignarDescripcion($accMatriz[$i][3]);
					$acciones[]=$accion;
		
				}
				return $acciones;
			}
			else
				return $accMatriz;
	}
	
	/**
	 * Método que permite obtener las acciones por un patrón que se desee . 
	 * @param String $patron	    		Patrón de búsqueda.
	 * @return Arreglo Accion	            Arreglo de objetos Accion si hay coincidencias
	 * @throws Exception 					En caso de error.
	 */
	public static function listarAcciones($patron=''){
			try{

				$conexion = Conexion::conectar();
				$consulta = " SELECT per.f_accion_listar('acciones','".$patron."')";
				$ejecutar = $conexion->prepare($consulta);
				$conexion->beginTransaction();
					$ejecutar->execute();
					$cursors = $ejecutar->fetchAll();
					$ejecutar->closeCursor();
					$ejecutar = $conexion->query('FETCH ALL IN acciones;');
					$lista=$ejecutar->fetchAll();
					$ejecutar->closeCursor();
				$conexion->commit();
				if(count($lista)>0)
					return $lista;
				else
					return null;
			}
			catch(Exception $e){
				throw $e;
			}
		}
		
	/**
	 * Método que permite eliminar una acción de la tabla t_accion. 
	 * @param Integer $codigo	    	Entero que representa el código de la acción.
	 * @return Ninguno. 
	 * @throws Ninguna.     
	 */
	public static function eliminar($codigo){
			try{
				$conexion=Conexion::conectar();
				$consulta="select per.f_accion_eliminar(?)";
				$ejecutar=$conexion->prepare($consulta);
				$ejecutar->execute(array($codigo									
										));
				$row = $ejecutar->fetchColumn(0);
				if ($row==0)
					throw new Exception("No se pudo eliminar la accion");
			}catch(Exception $e){
				throw $e;
			} 
	}
	/**
	 * Método que permite eliminar los permisos de una acción sobre una tabla. 
	 * @param TablaAccion $tabAccion	   	Objeto TablaAccion.
	 * @return Ninguno. 
	 * @throws Ninguna.     
	 */
	public static function eliTabAccion($tabAccion){
			try{
				$conexion=Conexion::conectar();
				$consulta="select per.f_accion_tabla_eliminar(?,?)";
								
				$ejecutar=$conexion->prepare($consulta);
				$ejecutar->execute(array($tabAccion->obtenerCodAccion(),$tabAccion->obtenerCodTabla()									
										));
				$row = $ejecutar->fetchColumn(0);
				if ($row==0)
					throw new Exception("Error al eliminar la permisologia de la acción: ".$tabAccion->obtenerCodAccion()." con realacion de la tabla:".$tabAccion->obtenerCodTabla());	
			}catch(Exception $e){
				throw $e;
			} 
	}
	
	/**
	 * Método que permite agregar los permisos que tiene una acción sobre una tabla.
	 *
	 * @param TablaAcion $tabAccion    		Objeto TablaAccion.
	 *
	 * @return Integer || null              Código del registro. 
	 * @throws Exception 					En caso de haber un error al agregar.
	 */	
	public static function agrTabAccion($tabAccion){
			try{
				$conexion=Conexion::conectar();
				$consulta="select per.f_tab_accion_ins(?,?,?)";
								
				$ejecutar=$conexion->prepare($consulta);
				
				$ejecutar->execute(array($tabAccion->obtenerCodTabla(),
										$tabAccion->obtenerCodAccion(),
										$tabAccion->obtenerPermisos(),
										));
				if ($ejecutar->rowCount()==0)
					throw new Exception("No se pueden agregar los permisos relacionados con  la acción: ".$tabAccion->obtenerCodAccion()."en la tabla: ".$tabAccion->obtenerCodTabla());
				$codigo = $ejecutar->fetchColumn(0);
				return $codigo;	
			}catch(Exception $e){
				throw $e;
			} 
	}

	/**
	 * Método que permite modificar los permisos que tiene una acción sobre una tabla
	 * @param TablaAcion $tabAccion    		Objeto TablaAccion.
	 * @throws Exception 					En caso de haber un error 
	 */
	public static function modTabAccion($tabAccion){
			try{
				$conexion=Conexion::conectar();
				$consulta="select per.f_tab_accion_modificar(?,?,?)";
								
				$ejecutar=$conexion->prepare($consulta);
				$ejecutar->execute(array($tabAccion->obtenerCodTabla(),
										 $tabAccion->obtenerCodAccion(),
										 $tabAccion->obtenerPermisos()
										
										));
				$row = $ejecutar->fetchColumn(0);
				if ($row==0)
					throw new Exception("Error al modificar los permisos de la acción: ".$tabAccion->obtenerCodAccion()." respecto a la tabla: ".$tabAccion->obtenerCodTabla());	
			}catch(Exception $e){
				throw $e;
			} 
	}

		

}












?>
