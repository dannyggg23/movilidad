<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Categorias_especies
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método <para></para> insertar registros
	public function insertar($nombre)
	{
		$sql="INSERT INTO categorias_especies (nombre,estado)
		VALUES ('$nombre','1')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	public function editar($idcategorias_especies,$nombre)
	{
		$sql="UPDATE Categorias_especies SET nombre='$nombre' WHERE idcategorias_especies='$idcategorias_especies'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar categorías
	public function desactivar($idcategorias_especies)
	{
		$sql="UPDATE categorias_especies SET estado='0' WHERE idcategorias_especies='$idcategorias_especies'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar categorías
	public function activar($idcategorias_especies)
	{
		$sql="UPDATE categorias_especies SET estado='1' WHERE idcategorias_especies='$idcategorias_especies'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idcategorias_especies)
	{
		$sql="SELECT * FROM categorias_especies WHERE idcategorias_especies='$idcategorias_especies'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT * FROM categorias_especies";
		return ejecutarConsulta($sql);		
	}
	//Implementar un método para listar los registros y mostrar en el select
	public function select()
	{
		$sql="SELECT * FROM categorias_especies where estado=1";
		return ejecutarConsulta($sql);		
	}
}

?>
