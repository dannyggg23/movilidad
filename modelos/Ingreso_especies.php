<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php"; 

Class Ingreso_especies
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	public function insertar($usuario_idusuario,$fecha,$numero_docuemnto,$ubicacion,$detalle,$total,$cantidad,$desde,$hasta,$especies_idespecies)
	{
		$sql="INSERT INTO `ingreso_especies`(`usuario_idusuario`, `fecha`, `numero_docuemnto`, `ubicacion`, `detalle`, `total`, `condicion`) 
		VALUES ('$usuario_idusuario','$fecha','$numero_docuemnto','$ubicacion','$detalle','$total','Aceptado')";
		//return ejecutarConsulta($sql);
		$idingresonew=ejecutarConsulta_retornarID($sql);
		$num_elementos=0;
		$sw=true;

		while ($num_elementos < count($especies_idespecies))
		 {
			 $hastaa=$desde[$num_elementos]+$cantidad[$num_elementos];

			$sql_detalle="INSERT INTO `detalle_ingreso_especies`( `ingreso_especies_idingreso_especies`, 
			`especies_idespecies`, 
			`cantidad`,
			`desde`, 
			`hasta`) 
			VALUES(
				'$idingresonew',
				'$especies_idespecies[$num_elementos]',
				'$cantidad[$num_elementos]',
				'$desde[$num_elementos]',
				'$hastaa'
			)";
			ejecutarConsulta($sql_detalle) or $sw=false;
			$num_elementos=$num_elementos+1;
		}
		return $sw;
	}


	//Implementamos un método para desactivar categorías
	public function anular($idingreso_especies)
	{
		$sql="UPDATE ingreso_especies SET estado='Anulado' WHERE idingreso_especies='$idingreso_especies'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idingreso_especies)
	{
		$sql="SELECT * FROM ingreso_especies WHERE idingreso_especies='$idingreso_especies' ";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function listarDetalle($idingreso)
	{
		$sql="SELECT detalle_ingreso_especies.*,especies.nombre FROM `detalle_ingreso_especies` INNER JOIN especies on especies.idespecies=detalle_ingreso_especies.especies_idespecies where detalle_ingreso_especies.ingreso_especies_idingreso_especies='$idingreso'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT ingreso_especies.*,usuario.nombre FROM `ingreso_especies` INNER JOIN usuario ON usuario.idusuario=ingreso_especies.usuario_idusuario ORDER BY fecha desc";
		return ejecutarConsulta($sql);		
	}

	public function ingreso_especies_cabecera($idingreso_especies)
	{
		$sql="SELECT 
		`ingreso_especies`.`idingreso_especies`,
		`ingreso_especies`.`fecha`,
		`ingreso_especies`.`numero_docuemnto`,
		`ingreso_especies`.`ubicacion`,
		`ingreso_especies`.`detalle`,
		`ingreso_especies`.`total`,
		`ingreso_especies`.`condicion`,
		`usuario`.`nombre`
	    FROM
		`ingreso_especies`
		INNER JOIN `usuario` ON (`ingreso_especies`.`usuario_idusuario` = `usuario`.`idusuario`)	   
		WHERE idingreso_especies='$idingreso_especies' ";
		return ejecutarConsulta($sql);
	}

	public function detalles_especies_cabecera($idingreso)
	{
		$sql="SELECT 
		`detalle_ingreso_especies`.`cantidad`,
		`detalle_ingreso_especies`.`desde`,
		`detalle_ingreso_especies`.`hasta`,
		`detalle_ingreso_especies`.`ingreso_especies_idingreso_especies`,
		`detalle_ingreso_especies`.`iddetalle_ingreso_especies`,
		`especies`.`nombre`,
		`especies`.`codigo`
	    FROM
		`detalle_ingreso_especies`
		INNER JOIN `especies` ON (`detalle_ingreso_especies`.`especies_idespecies` = `especies`.`idespecies`)
	    where detalle_ingreso_especies.ingreso_especies_idingreso_especies='$idingreso'";
		return ejecutarConsulta($sql);
	}
}

?>
