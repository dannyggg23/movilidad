<?php 
require_once "../modelos/Proyectos.php";

$proyectos=new Proyectos();

$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
$fecha=isset($_POST["fecha"])? limpiarCadena($_POST["fecha"]):"";
$idproyectos=isset($_POST["idproyectos"])? limpiarCadena($_POST["idproyectos"]):"";


switch ($_GET["op"]){
	case 'guardaryeditar':

		if (empty($idproyectos)){
			$rspta=$proyectos->insertar($nombre,$descripcion,$fecha);
			echo $rspta ? "Proyecto registrado" : "No se pudo registrar";
		}
		else {
			$rspta=$proyectos->editar($idproyectos,$nombre,$descripcion,$fecha);
			echo $rspta ? "Proyecto actualizado" : "No se pudo actualizar";
		}
	break;

	case 'desactivar':
		$rspta=$proyectos->desactivar($idproyectos);
 		echo $rspta ? "Proyecto Desactivado" : "No se puede desactivar";
	break;

	case 'activar':
		$rspta=$proyectos->activar($idproyectos);
 		echo $rspta ? "Proyecto activado" : "No se puede activar";
	break;

	case 'mostrar':
		$rspta=$proyectos->mostrar($idproyectos);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break;

	case 'listar':
		$rspta=$proyectos->listar();
 		//Vamos a declarar un array
 		$data= Array();

 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
 				"0"=>($reg->condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idproyectos.')"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-danger" onclick="desactivar('.$reg->idproyectos.')"><i class="fa fa-close"></i></button>':
 					'<button class="btn btn-warning" onclick="mostrar('.$reg->idproyectos.')"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-primary" onclick="activar('.$reg->idproyectos.')"><i class="fa fa-check"></i></button>',
 				"1"=>$reg->nombre,
 				"2"=>$reg->descripcion,
 				"3"=>$reg->fecha,
 				"4"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':
 				'<span class="label bg-red">Desactivado</span>'
 				);
 		}
 		$results = array(
 			"sEcho"=>1, //Información para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
 			"aaData"=>$data);
 		echo json_encode($results);

	break;

	
}
?>