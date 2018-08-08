<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Proyectos
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método <para></para> insertar registros
	public function insertar($nombre, $descripcion, $fecha)
	{
		$sql="INSERT INTO `proyectos`(`nombre`, `descripcion`, `fecha`) 
        VALUES ('$nombre','$descripcion','$fecha')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	public function editar($idproyectos,$nombre, $descripcion, $fecha)
	{
		$sql="UPDATE `proyectos` SET 
        `nombre`='$nombre',
        `descripcion`='$descripcion',
        `fecha`='$fecha' WHERE  `idproyectos`='$idproyectos'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar categorías
	public function desactivar($idproyectos)
	{
		$sql="UPDATE proyectos SET condicion='0' WHERE idproyectos='$idproyectos'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar categorías
	public function activar($idproyectos)
	{
		$sql="UPDATE proyectos SET condicion='1' WHERE idproyectos='$idproyectos'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idproyectos)
	{
		$sql="SELECT * FROM proyectos WHERE idproyectos='$idproyectos'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT * FROM proyectos";
		return ejecutarConsulta($sql);		
	}
	//Implementar un método para listar los registros y mostrar en el select
	public function select()
	{
		$sql="SELECT * FROM proyectos where condicion=1";
		return ejecutarConsulta($sql);		
	}
}

?>
