
<?php
/**
 * TablaServicio.php - Servicio del módulo Tabla.
 *
 * Esta clase ofrece el servicio de conexión a la base de datos, recibe 
 * los parámetros, ejucuta las funciones PLPSQL correpsondientes, hace las peticiones a 
 * la base de datos y retorna los objetos o datos correspondientes a la
 * acción. Todas las funciones de esta clase relanzan las excepciones si son capturadas.
 * Esto con el fin de que la clase manejadora de errores las capture y procese.
 * Esta clase trabaja en conjunto con la clase Conexion.
 *
 * @copyright 2016 - Instituto Universtiario de Tecnología Dr. Federico Rivero Palacio
 * @license GPLv3
 * @license http://www.gnu.org/licenses/gpl-3.0.html
 *
 *
 * @link base/clases/utilitarias/errores/manejoErrores.php 		Clase manejadora de errores.
 * 
 * @link /base/clases/conexion/Conexion.php 	Clase Conexion
 *  
 * @author Geraldine Castillo (geralcs94@gmail.com)
 * @author Jhonny Vielma      (jhonnyvq1@gmail.com)
 * @author Johan Alamo (lider de proyecto) <johan.alamo@gmail.com>
 * 
 * @package MVC
 */
	class TablaServicio{


		/**
		 * Método que permite retornar un arreglo de objeto TabAccion si el  $tipObjeto es true y sino retorna la matriz
		 * de la información extraída de base de datos.
		 *
		 * @param Boolean $tipObjeto 		    Si se quiere de respuesta un arreglo de objeto TabAccion.
		 * @param matriz  $accMatriz			Matriz que contiene la información extraída de base de datos.
		 * @return []TabAccion || [][]  
		 * @throws Exception 					No lanza exceptiones.
	    */

		private static function retTabAccion($accMatriz,$tipObjeto=true){
			if ($tipObjeto){
				for($i = 0; $i < count($accMatriz); $i++){
					$tabAccion=new tablaAccion();

					$tabAccion->asignarCodAccion($accMatriz[$i]["cod_accion"]);
					$tabAccion->asignarNombre($accMatriz[$i]["nombre"]);
					$tabAccion->asignarCodTabla($accMatriz[$i]["codigo"]);
					$tabAccion->asignarPermisos($accMatriz[$i]["permiso"]);
					$tabAcciones[]=$tabAccion;
				}
				return $tabAcciones;
			}else
				return $accMatriz;
		}
		/**
		 *  Método permite obtener todas las tablas implicadas en un acción con las permisología así como las que no están relacionas con dicha acción y	 *  permite filtrar la lista por un patrón.
		 *
		 * @param String  $patron 		        Patrón para filtrar la lista de permisos de la acción en las tablas.
		 * @param matriz  $codigo			    Bodigo de la acción de cual se quiere obtener la permisología en tabla, si no se pasa código traerá todas 	  *										 las  tablas sin relacionarla a una acción.
		 * @return []TabAccion || null   
		 * @throws Exception 					No lanza excepciones.
	    */
		public static function listar($patron='',$codigo=null){
			try{
				$conexion = Conexion::conectar();
				$consulta="select per.f_tabla_listar('lis_tabla','".$patron."',?) ";
				$ejecutar= $conexion->prepare($consulta);
				$conexion->beginTransaction();
					$ejecutar->execute(array($codigo));
					$cursors = $ejecutar->fetchAll();
					$ejecutar->closeCursor();
					$ejecutar = $conexion->query('FETCH ALL IN lis_tabla;');
					$lista=$ejecutar->fetchAll();
					$ejecutar->closeCursor();
				$conexion->commit();
			
				if(count($lista)>0){
					return self::retTabAccion($lista);
				}else
					return null;
			}catch(Exception $e){
				throw $e;
			}
	  	}
		/**
		 *  Método permite obtener las tablas que se encuentran en el catalogo de postgres y las que están en la tabla per.t_tabla y
		 *  calificarlas para de esta manera saber cuales están agregadas correctamente, cuales no existen en base de datos y 
		 *  las que faltan por agregar, cuando el campo nombrec posee el carácter I es que es una tabla que esta agregada pero
     	 *  en la base de datos no existen una tabla con ese nombre, y cuando en el campo nombret se encuentra la letra N es
         *  por que esa tabla existe en la base de datos pero no esta agregada en la tabla per.t_tabla.
		 *
		 * @param String  $patron 		        Patrón para filtrar la lista de tablas.
		 * @param Char    $buscar               Carácter que representa que tablas se quieren listar 'T'= todas, 'I'= no existen en la base de datos
		 *										pero esta agregada en tabla, 'A'= agregadas y 'N'= No agregadas en base de datos.
		 * @return []Tabla || null   
		 * @throws Exception 					Capturadas de postgres y lan relanza.
	    */
		public static function listarTablasAgregadasOno($patron='',$buscar="T"){
			try{
				$conexion = Conexion::conectar();
				$consulta="select per.f_tabla_listar_agregadas_o_no('lis_tab_agr_catalogo','".$patron."')";
				$ejecutar= $conexion->prepare($consulta);
				$conexion->beginTransaction();
					$ejecutar->execute();
					$cursors = $ejecutar->fetchAll();
					$ejecutar->closeCursor();
					$ejecutar = $conexion->query('FETCH ALL IN lis_tab_agr_catalogo;');
					$lista=$ejecutar->fetchAll();
					$ejecutar->closeCursor();
				$conexion->commit();
				
				if(count($lista)>0)
					return self::armarTablas($lista,true,$buscar);
				else
					return null;
			}
			catch(Exception $e){
				throw $e;
			}
		}
	    /**
		 *  Método permite obtener una tabla, la que coincida con los parámetros pasado a la función.
		 *
		 * @param Integer  $codigo 		        Codigo de la tabla a obtener.
		 * @param String   $nombre              Nombre de la tabla a obtener.
		 *
		 * @return []Tabla || null   
		 * @throws Exception 					Capturadas de postgres y las relanza.
	    */
		public static function obtenerDeTabla($codigo=null,$nombre=""){
			try{
				$conexion = Conexion::conectar();
				$consulta = "select per.f_tabla_obt_tablas('tablas',?,?)";
				$ejecutar = $conexion->prepare($consulta);
				$conexion->beginTransaction();
					$ejecutar->execute(array($nombre,$codigo));
					$cursors = $ejecutar->fetchAll();
					$ejecutar->closeCursor();
					$ejecutar = $conexion->query('FETCH ALL IN tablas;');
					$lista=$ejecutar->fetchAll();
					$ejecutar->closeCursor();
				$conexion->commit();

				if(count($lista)>0)
					return self::armarTablas($lista);
				else
					return null;
			}
			catch(Exception $e){
				throw $e;
			}
		}
	   /**
		 * Método Permite obtener un tabla que se encuentran en el catalogo de postgres y coincida con el parámetro $nombre.
		 *
		 * @param String   $nombre              Nombre de la tabla a obtener.
		 *
		 * @return []Tabla || null   
		 * @throws Exception 					Capturadas de postgres y las relanza.
	    */
		public static function obtenerDeCatalogo($nombre=""){
			try{
				$conexion = Conexion::conectar();
				$consulta = "select per.f_tabla_listar_de_catalogo('tab_catalogo',?)";
				$ejecutar = $conexion->prepare($consulta);
				$conexion->beginTransaction();
					$ejecutar->execute(array($nombre));
					$cursors = $ejecutar->fetchAll();
					$ejecutar->closeCursor();
					$ejecutar = $conexion->query('FETCH ALL IN tab_catalogo;');
					$lista=$ejecutar->fetchAll();
					$ejecutar->closeCursor();
				$conexion->commit();
				if(count($lista)>0)
					return self::armarTablas($lista);
				else
					return null;
			}
			catch(Exception $e){
				throw $e;
			}
		}
	    /**
		 * Método que permite agregar una tabla a la base de datos.
		 *
		 * @param Tabla   $tabla              Objeto Tabla con los datos de la tabla a agregar.
		 *
		 * @return Integer 					  Código de la tabla agregada.   
		 * @throws Exception 				  De no poder insertar la tabla y las capturadas de postgres que son relanzadas.
	   */
		public static function agregar($tabla){
			try{
				$conexion=Conexion::conectar();
				$consulta="select per.f_tabla_ins(?)";
				$ejecutar=$conexion->prepare($consulta);
				$ejecutar->execute(array($tabla->obtenerNombre(),
										));
				if ($ejecutar->rowCount()==0)
					throw new Exception("No se puede agregar la tabla");
				$codigo = $ejecutar->fetchColumn(0);
				return $codigo;
			}catch(Exception $e){
				throw $e;
			} 
		}
		/**
		 * Método que permite modificar una tabla en la base de datos.
		 *
		 * @param Tabla   $tabla              Objeto Tabla con los datos nuevos a actualizar.
		 *   
		 * @throws Exception 				  De no poder modificar la tabla y las capturadas de postgres que son relanzadas.
	   */
		public static function modificar($tabla){
			try{
				$conexion=Conexion::conectar();
				$consulta="select per.f_tabla_modificar(?,?)";
								
				$ejecutar=$conexion->prepare($consulta);
				$ejecutar->execute(array($tabla->obtenerNombre(),
										 $tabla->obtenerCodigo()));
				$row = $ejecutar->fetchColumn(0);
				if ($row==0)
					throw new Exception("No se puede modificar la tabla");	
			}catch(Exception $e){
				throw $e;
			} 
		}
		/**
		 * Método que permite retornar un arreglo de objeto Tabla si el  $tipObjeto es true y sino retorna la matriz
		 * de la información extraída de base de datos y permite filtrar las tablas que realmente se desean mediante el 
		 * patron $buscar.
		 *
		 * @param matriz  $tablasF		    Matriz que contiene la información extraída de base de datos.
		 * @param Boolean $tipo 	        Tipo de datos que quiere de retorno, true= Arreglo  de objetos tabla y false= matriz de datos.
		 * @param Char    $buscar 		    Tablas que se desean obtener de la matriz pasada, 'T'= todas, 'A'= agregadas, 'I'= agregadas pero inexistente en *                                  base de datos y 'N'= no agregadas.
		 * @return []Tabla || [][]  
		 * @throws Exception 				No lanza exceptiones.
	   */
		public static function armarTablas($tablasF,$tipo=true,$buscar="T"){

			if ($tipo){
				$tablas=null;
				for($i = 0; $i < count($tablasF); $i++){
						if ($tablasF[$i]['nombret']==$tablasF[$i]['nombrec']){
							if (($buscar=="T") ||($buscar=="A")){
								$tabla=new Tabla();
								$tabla->asignarCodigo($tablasF[$i]['codigo']);
								$tabla->asignarNombre($tablasF[$i]['nombret']);
								$tabla->asignarEstatus("S");
								$tablas[]=$tabla;
							}
						}else{
							if($tablasF[$i]['codigo']==-1){
								if (($buscar=="T") || ($buscar=="N")){
									$tabla=new Tabla();
									$tabla->asignarCodigo($tablasF[$i]['codigo']);
									$tabla->asignarNombre($tablasF[$i]['nombrec']);
									$tabla->asignarEstatus($tablasF[$i]['nombret']);
									$tablas[]=$tabla;
								}
							}
							else{
								if (($buscar=="T") ||($buscar=="I")){
									$tabla=new Tabla();
									$tabla->asignarCodigo($tablasF[$i]['codigo']);
									$tabla->asignarNombre($tablasF[$i]['nombret']);
									$tabla->asignarEstatus($tablasF[$i]['nombrec']);
									$tablas[]=$tabla;
								}
							}
						}
				}
				return $tablas;
			}else{
				return $tablasF;
			}


		}
		
	    /**
		 * Método que permite eliminar una tabla en la base de datos.
		 *
		 * @param  Integer $codigo            Codigo de la tabla a eliminar.
		 * 
		 * @throws Exception 				  De no poder eliminar la tabla y las capturadas de postgres que son relanzadas.
	   */	
		public static function eliminar($codigo){
			try{
				$conexion=Conexion::conectar();
				$consulta="select per.f_tabla_eliminar(?)";
				$ejecutar=$conexion->prepare($consulta);
				$ejecutar->execute(array($codigo));
				$row = $ejecutar->fetchColumn(0);
				if ($row==0)
					throw new Exception("No se pudo eliminar la tabla");	
			}catch(Exception $e){
				throw $e;
			} 
		}
	}
?>







