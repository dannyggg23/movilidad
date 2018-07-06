<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php"; 

Class Egreso_especies
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	public function insertar($numero_documento,$ubicacion, 
    $detalle,$total,$usuario_idusuario,$personas_idcajeros,
    $especies_idespecies,
    $cantidad,
    $desde
    )

	{
		$sql=sprintf("INSERT INTO `egreso_especies`(
         `fecha`,
         `numero_documento`, 
         `ubicacion`, 
         `detalle`,
         `total`,
         `condicion`,
         `usuario_idusuario`,
         `personas_idcajeros`)
          VALUES (CURDATE(),'%s','%s','%s','%s','Aceptado','%s','%s')",$numero_documento,$ubicacion,$detalle,$total,$usuario_idusuario,$personas_idcajeros);
		//return ejecutarConsulta($sql);
		$idegresonew=ejecutarConsulta_retornarID($sql);
		$num_elementos=0;
		$sw=true;

		while ($num_elementos < count($especies_idespecies))
		 {
			 $hastaa=$desde[$num_elementos]+$cantidad[$num_elementos];
           
			$sql_detalle="INSERT INTO `detalle_egreso_especies`
            (`cantidad`,`desde`,`hasta`,`egreso_especies_idegreso_especies`,`especies_idespecies`) 
            VALUES (
                '$cantidad[$num_elementos]',
                '$desde[$num_elementos]',
                '$hastaa',
                '$idegresonew',
                '$especies_idespecies[$num_elementos]'
                )";
			ejecutarConsulta($sql_detalle) or $sw=false;
			$num_elementos=$num_elementos+1;
		}
		return $sw;
	}


	//Implementamos un método para desactivar categorías
	public function anular($idegreso_especies)
	{
		$sql="UPDATE egreso_especies SET estado='Anulado' WHERE idegreso_especies='$idegreso_especies'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idegreso_especies)
	{
		$sql="SELECT * FROM egreso_especies WHERE idegreso_especies='$idegreso_especies' ";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function listarDetalle($idingreso)
	{
		$sql="SELECT detalle_egreso_especies.*,especies.nombre FROM `detalle_egreso_especies` INNER JOIN especies on especies.idespecies=detalle_egreso_especies.especies_idespecies where detalle_egreso_especies.egreso_especies_idegreso_especies='$idingreso'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT egreso_especies.*,usuario.nombre,personas.nombre as persona FROM `egreso_especies` INNER JOIN usuario ON usuario.idusuario=egreso_especies.usuario_idusuario  INNER JOIN personas on personas.idcajeros=egreso_especies.personas_idcajeros ORDER BY fecha desc";
		return ejecutarConsulta($sql);		
	}

	public function egreso_especies_cabecera($id)
	{
		$sql="SELECT `egreso_especies`.`fecha`, `egreso_especies`.`numero_documento`,
		 `egreso_especies`.`ubicacion`, `egreso_especies`.`detalle`,
		  `egreso_especies`.`total`, `usuario`.`nombre` as usuario, 
		  `personas`.`nombre`, `personas`.`cedula`, `personas`.`funcion` 
		  FROM `egreso_especies` 
		  INNER JOIN `usuario` ON (`egreso_especies`.`usuario_idusuario` = `usuario`.`idusuario`) 
		  INNER JOIN `personas` ON (`egreso_especies`.`personas_idcajeros` = `personas`.`idcajeros`)
		   WHERE egreso_especies.idegreso_especies='$id'";
		return ejecutarConsulta($sql);		
	}

	public function detalle_especies_cabecera($idegreso)
	{
		$sql="SELECT detalle_egreso_especies.*,
		especies.nombre ,especies.codigo FROM `detalle_egreso_especies` 
		INNER JOIN especies on especies.idespecies=detalle_egreso_especies.especies_idespecies 
		where detalle_egreso_especies.egreso_especies_idegreso_especies='$idegreso'";
		return ejecutarConsulta($sql);
	}


}

?>
