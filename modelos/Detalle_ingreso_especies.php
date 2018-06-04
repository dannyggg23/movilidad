<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Detalle_ingreso_especies
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método <para></para> insertar registros
	public function insertar($ingreso_especies_idingreso_especies,$especies_idespecies,$cantidad,$desde,$hasta)
	{
		$sql="INSERT INTO detalle_ingreso_especies (ingreso_especies_idingreso_especies,especies_idespecies,cantidad,desde,hasta)
		VALUES ('$ingreso_especies_idingreso_especies','$especies_idespecies','$cantidad','$desde','$hasta')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	public function editar($iddetalle_ingreso_especies,$ingreso_especies_idingreso_especies,$especies_idespecies,$cantidad,$desde,$hasta)
	{
		$sql="UPDATE detalle_ingreso_especies SET ingreso_especies_idingreso_especies='$ingreso_especies_idingreso_especies',especies_idespecies='$especies_idespecies',cantidad='$cantidad',desde='$desde',hasta='$hasta' WHERE iddetalle_ingreso_especies='$iddetalle_ingreso_especies'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($iddetalle_ingreso_especies)
	{
		$sql="SELECT * FROM detalle_ingreso_especies WHERE iddetalle_ingreso_especies='$iddetalle_ingreso_especies'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT * FROM detalle_ingreso_especies";
		return ejecutarConsulta($sql);		
	}
}

?>
