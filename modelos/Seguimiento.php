<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Seguimiento
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método <para></para> insertar registros
	public function insertar($fecha,$descripcion,$egreso_bienes_idegreso_bienes,$imagen)
	{
		$sql="INSERT INTO `seguimiento_egreso_bienes`(`fecha`, `descripcion`, `egreso_bienes_idegreso_bienes`,imagen)
         VALUES ('$fecha','$descripcion','$egreso_bienes_idegreso_bienes','$imagen')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	public function editar($idseguimiento_egrese_bienes,$fecha,$descripcion,$egreso_bienes_idegreso_bienes,$imagen)
	{
		$sql="UPDATE `seguimiento_egreso_bienes` SET 
        `fecha`='$fecha',
        `descripcion`='$descripcion',
        imagen='$imagen',
        `egreso_bienes_idegreso_bienes`='$egreso_bienes_idegreso_bienes' WHERE `idseguimiento_egrese_bienes`='$idseguimiento_egrese_bienes'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar categorías
	public function desactivar($idseguimiento_egrese_bienes)
	{
		$sql="UPDATE seguimiento_egreso_bienes SET condicion='0' WHERE idseguimiento_egrese_bienes='$idseguimiento_egrese_bienes'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar categorías
	public function activar($idseguimiento_egrese_bienes)
	{
		$sql="UPDATE seguimiento_egreso_bienes SET condicion='1' WHERE idseguimiento_egrese_bienes='$idseguimiento_egrese_bienes'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idseguimiento_egrese_bienes)
	{
		$sql="SELECT * FROM seguimiento_egreso_bienes WHERE idseguimiento_egrese_bienes='$idseguimiento_egrese_bienes'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT 
        `egreso_bienes`.`numero_egreso`,
        `egreso_bienes`.`lugar`,
        `seguimiento_egreso_bienes`.`fecha`,
        `seguimiento_egreso_bienes`.`descripcion`,
        `seguimiento_egreso_bienes`.`imagen`,
        `seguimiento_egreso_bienes`.`condicion`,
        `seguimiento_egreso_bienes`.`idseguimiento_egrese_bienes`,
        `personas`.`nombre`
        FROM
        `seguimiento_egreso_bienes`
        INNER JOIN `egreso_bienes` ON (`seguimiento_egreso_bienes`.`egreso_bienes_idegreso_bienes` = `egreso_bienes`.`idegreso_bienes`)
        INNER JOIN `personas` ON (`egreso_bienes`.`personas_idcajeros` = `personas`.`idcajeros`) ORDER BY fecha desc";
		return ejecutarConsulta($sql);		
	}
	
}

?>
