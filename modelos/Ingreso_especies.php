<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Ingreso_especies
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método <para></para> insertar registros
	public function insertar($usuario_idusuario,$cajeros_idcajeros,$fecha_reporte,$fecha_Entrega,$numero_docuemnto,$ingreso_especiescol,$ubicacion,$retirado_por,$responsable,$detalle,$total,$condicion)
	{
		$sql="INSERT INTO ingreso_especies (usuario_idusuario,cajeros_idcajeros,fecha_reporte,fecha_Entrega,numero_docuemnto,ingreso_especiescol,ubicacion,retirado_por,responsable,detalle,total,condicion)
		VALUES ('$usuario_idusuario','$cajeros_idcajeros','$fecha_reporte','$fecha_Entrega','$numero_docuemnto','$ingreso_especiescol','$ubicacion','$retirado_por','$responsable','$detalle','$total','$condicion')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	public function editar($idingreso_especies,$usuario_idusuario,$cajeros_idcajeros,$fecha_reporte,$fecha_Entrega,$numero_docuemnto,$ingreso_especiescol,$ubicacion,$retirado_por,$responsable,$detalle,$total,$condicion)
	{
		$sql="UPDATE ingreso_especies SET usuario_idusuario='$usuario_idusuario',cajeros_idcajeros='$cajeros_idcajeros',fecha_reporte='$fecha_reporte',fecha_Entrega='$fecha_Entrega',numero_docuemnto='$numero_docuemnto',ingreso_especiescol='$ingreso_especiescol',ubicacion='$ubicacion',retirado_por='$retirado_por',responsable='$responsable',detalle='$detalle',total='total',condicion='$condicion' WHERE idingreso_especies='$idingreso_especies'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar categorías
	public function desactivar($idcategorias_especies)
	{
		$sql="UPDATE ingreso_especies SET condicion='0' WHERE idingreso_especies='$idingreso_especies'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar categorías
	public function activar($idcategorias_especies)
	{
		$sql="UPDATE ingreso_especies SET condicion='1' WHERE idingreso_especies='$idingreso_especies'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idingreso_especies)
	{
		$sql="SELECT * FROM ingreso_especies WHERE idcategorias_especies='$idcategorias_especies'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT * FROM ingreso_especies";
		return ejecutarConsulta($sql);		
	}
	//Implementar un método para listar los registros y mostrar en el select
	public function select()
	{
		$sql="SELECT * FROM ingreso_especies where condicion=1";
		return ejecutarConsulta($sql);		
	}
}

?>
