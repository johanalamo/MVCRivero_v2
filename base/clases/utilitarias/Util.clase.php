<?php
/**
 * util.clase.php - Componente de MVCRIVERO.
 *
 * Clase que ofrece utilidades para el programador
 * 
 * @copyright 2014 - Instituto Universtiario de Tecnología Dr. Federico Rivero Palacio
 * @license GPLv3
 * @license http://www.gnu.org/licenses/gpl-3.0.html
 *
 *
 *  
 * @author Johan Alamo (johan.alamo@gmail.com)
 * 
 * @package Componentes
 */


abstract class Util {
    
	/**
		 * función que permite crear una clase.
		 *
		 *
		 * @param String  $clase 				Nombre de la clase a crear.
		 * @param []String  $atributos			Arreglo de atributo.
		 * 
		 * @throws Exception 					Mensaje que entra en la funcion.
		 */

	public static function crearClase($clase, $atributos) 
	{	

		//separar en un arreglo cuando encuentre comas en la variable $atributos
		$arrAtributos = explode(",",$atributos);


		//crea el archivo
		$arch = fopen("$clase.clase.php","w");
		$cl = "\r\n"; //caracter de cambio de linea
		$tab = "\t";  //caracter tabulador
		echo "entro a crear clase";
		
		$cad = "<?php$cl$cl";
		$cad .= "class $clase { $cl";
		
		$cad .= "$tab//atributos de la clase$cl";
		foreach ( $arrAtributos as $atrib){
				$cad .= "${tab}private \$$atrib;$cl";
		}
	
		$cad .= "$cl$cl";
		
		//*********  hacer el constructor  *****************
		$cad .= "${tab}public function __construct(";
		$coma = "";
		foreach ($arrAtributos as $atrib){  //los parámetros del constructor
			$cad .= $coma;
			$cad .= "\$$atrib=NULL";
			$coma = ", ";
		}
		$cad .= ")$cl$tab" . '{' . "$cl";
		foreach ($arrAtributos as $atrib){
			$atribMayuscula = ucfirst($atrib);
			$cad .= "$tab$tab\$this->asignar$atribMayuscula(\$$atrib);$cl";
		}
		$cad .= "$cl${tab}}"; 
		//********* fin hacer constructor  **********************
		
		$cad .= "$cl$cl$cl$tab//Coloque aquí los métodos y operaciones de la clase$cl$cl$cl";
		
		$cad .= "$tab//Asignar y obtener de cada atributo$cl";
		foreach($arrAtributos as $atrib){
			$atribMayuscula = ucfirst($atrib);
			
			$cad .= "${tab}public function asignar$atribMayuscula(\$$atrib)" . '{' . $cl;
			$cad .= "$tab$tab\$this->$atrib = \$$atrib;$cl";
			$cad .= $tab . '}' . $cl;
			
			$cad .= "${tab}public function obtener$atribMayuscula()" . '{' . $cl;
			$cad .= "$tab${tab}return \$this->$atrib;$cl";
			$cad .= $tab . '}' . $cl . $cl;
		}
		
		
		$cad .= '}' . $cl . $cl; //llave de cerrado de la clase
		$cad .= "?>";
		fputs($arch, $cad);  //grabar en el archivo.
		fclose($arch);		//cerrar archivo

		//$cad =  htmlentities($cad);
		//$cad = str_replace( "\n","<br>",$cad );
		//echo $cad; 
		
		//cambiar dueño y permisos al archivo creado
		//chown("$clase.php", "nobody");
		chmod("$clase.php",  0777);
	}
}
?>
