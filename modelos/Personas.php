<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Personas
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método <para></para> insertar registros
	public function insertar($cedula,$nombre,$funcion)
	{
		$sql="INSERT INTO personas (cedula,nombre,funcion)
		VALUES ('$cedula','$nombre','$funcion')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	public function editar($idcajeros,$cedula,$nombre,$funcion)
	{
		$sql="UPDATE personas SET cedula='$cedula',nombre='$nombre',funcion='$funcion'  WHERE idcajeros='$idcajeros'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar categorías
	public function desactivar($idcajeros)
	{
		$sql="UPDATE personas SET condicion='0' WHERE idcajeros='$idcajeros'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar categorías
	public function activar($idcajeros)
	{
		$sql="UPDATE personas SET condicion='1' WHERE idcajeros='$idcajeros'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idcajeros)
	{
		$sql="SELECT * FROM personas WHERE idcajeros='$idcajeros'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT * FROM personas";
		return ejecutarConsulta($sql);		
	}
	//Implementar un método para listar los registros y mostrar en el select
	public function select()
	{
		$sql="SELECT * FROM personas where condicion=1";
		return ejecutarConsulta($sql);		
	}
}

?>
