<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Egreso_bienes
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}
 
	//Implementamos un método <para></para> insertar registros
	public function insertar($fecha,
	$total,$lugar,$descripcion,$personas_idcajeros,$usuario_idusuario,$numero_egreso,
	$calle,$interseccion,
	$cantidad,$precio,$bienes_idbienes)
	{
		$sql="INSERT INTO `egreso_bienes`( `fecha`, `total`, `lugar`, `descripcion`, `personas_idcajeros`, `usuario_idusuario`,estado,numero_egreso,calle,interseccion) VALUES ('$fecha','$total','$lugar','$descripcion','$personas_idcajeros','$usuario_idusuario','Aceptado','$numero_egreso','$calle','$interseccion')";
		//return ejecutarConsulta($sql);
		$idegresonew=ejecutarConsulta_retornarID($sql);
		$num_elementos=0;
		$sw=true;

		while ($num_elementos < count($bienes_idbienes))
		 {
			$sql_detalle="INSERT INTO `detalle_egreso_bienes`( `cantidad`, `precio`, `egreso_bienes_idegreso_bienes`, `bienes_idbienes`) 
            VALUES(
			'$cantidad[$num_elementos]',
            '$precio[$num_elementos]',
            '$idegresonew',
			'$bienes_idbienes[$num_elementos]')";
			ejecutarConsulta($sql_detalle) or $sw=false;
			$num_elementos=$num_elementos+1;
		}
		return $sw;
	}


	//Implementamos un método para desactivar categorías
	public function anular($idegreso_bienes)
	{
		$sql="UPDATE egreso_bienes SET estado='Anulado' WHERE idegreso_bienes='$idegreso_bienes'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idegreso_bienes)
	{
		$sql="SELECT * FROM egreso_bienes WHERE idegreso_bienes='$idegreso_bienes' ";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function listarDetalle($idegreso)
	{
		$sql="SELECT detalle_egreso_bienes.*,bienes.nombre FROM `detalle_egreso_bienes` INNER JOIN bienes on bienes.idbienes=detalle_egreso_bienes.bienes_idbienes where detalle_egreso_bienes.egreso_bienes_idegreso_bienes='$idegreso'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT egreso_bienes.*,usuario.nombre,personas.cedula FROM `egreso_bienes` INNER JOIN usuario ON usuario.idusuario=egreso_bienes.usuario_idusuario INNER JOIN personas ON personas.idcajeros=egreso_bienes.personas_idcajeros";
		return ejecutarConsulta($sql);		
	}
	public function select()
	{
		$sql="SELECT * FROM `egreso_bienes` ";
		return ejecutarConsulta($sql);		
	}

	public function egreso_bienes_cabecera($id)
	{
		$sql="SELECT egreso_bienes.*,usuario.nombre,personas.nombre as cajero FROM `egreso_bienes` 
		INNER JOIN usuario ON usuario.idusuario=egreso_bienes.usuario_idusuario 
		INNER JOIN personas ON personas.idcajeros=egreso_bienes.personas_idcajeros 
		where egreso_bienes.idegreso_bienes='$id'";
		return ejecutarConsulta($sql);		
	}

	

	public function detalle_bienes_cabecera($idegreso)
	{
		$sql="SELECT detalle_egreso_bienes.*,bienes.nombre,bienes.codigo FROM `detalle_egreso_bienes` INNER JOIN bienes on bienes.idbienes=detalle_egreso_bienes.bienes_idbienes where detalle_egreso_bienes.egreso_bienes_idegreso_bienes='$idegreso'";
		return ejecutarConsulta($sql);
	}
}

?>
