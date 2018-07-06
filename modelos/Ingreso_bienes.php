<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Ingreso_bienes
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método <para></para> insertar registros
	public function insertar($ubicacion,$detalle,$numero_ingreso,$fecha,$n_documento,$total,$usuario_idusuario,$consto_unitario,$cantidad,$bienes_idbienes)
	{
		$sql="INSERT INTO ingreso_bienes (ubicacion,detalle,numero_ingreso,fecha,n_documento,estado,total,usuario_idusuario)
		VALUES ('$ubicacion','$detalle','$numero_ingreso','$fecha','$n_documento','Aceptado','$total','$usuario_idusuario')";
		//return ejecutarConsulta($sql);
		$idingresonew=ejecutarConsulta_retornarID($sql);
		$num_elementos=0;
		$sw=true;

		while ($num_elementos < count($bienes_idbienes))
		 {
			$sql_detalle="INSERT INTO detalle_ingreso_bienes(consto_unitario,cantidad,bienes_idbienes,ingreso_bienes_idingreso_bienes) 
			VALUES('$consto_unitario[$num_elementos]',
			'$cantidad[$num_elementos]','$bienes_idbienes[$num_elementos]',
			'$idingresonew')";
			ejecutarConsulta($sql_detalle) or $sw=false;
			$num_elementos=$num_elementos+1;
		}
		return $sw;
	}


	//Implementamos un método para desactivar categorías
	public function anular($idingreso_bien)
	{
		$sql="UPDATE ingreso_bienes SET estado='Anulado' WHERE idingreso_bienes='$idingreso_bien'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idingreso_bienes)
	{
		$sql="SELECT * FROM ingreso_bienes WHERE idingreso_bienes='$idingreso_bienes' ";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function listarDetalle($idingreso)
	{
		$sql="SELECT detalle_ingreso_bienes.*,bienes.nombre FROM `detalle_ingreso_bienes` INNER JOIN bienes on bienes.idbienes=detalle_ingreso_bienes.bienes_idbienes where detalle_ingreso_bienes.ingreso_bienes_idingreso_bienes='$idingreso'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT ingreso_bienes.*,usuario.nombre FROM `ingreso_bienes` INNER JOIN usuario ON usuario.idusuario=ingreso_bienes.usuario_idusuario ORDER BY fecha desc";
		return ejecutarConsulta($sql);		
	}
	public function ingreso_bienes_cabecera($id)
	{
		$sql="SELECT ingreso_bienes.*,usuario.nombre FROM `ingreso_bienes` INNER JOIN usuario ON usuario.idusuario=ingreso_bienes.usuario_idusuario WHERE ingreso_bienes.idingreso_bienes='$id'";
		return ejecutarConsulta($sql);		
	}

	public function detalle_bienes_cabecera($id)
	{
		$sql="SELECT detalle_ingreso_bienes.*,bienes.nombre,bienes.codigo FROM `detalle_ingreso_bienes` INNER JOIN bienes on bienes.idbienes=detalle_ingreso_bienes.bienes_idbienes where detalle_ingreso_bienes.ingreso_bienes_idingreso_bienes='$id'";
		return ejecutarConsulta($sql);		
	}
}

?>
