<?php
require_once "../modelos/Categorias_especies.php";

$categorias_especies=new Categorias_especies();

$idcategorias_especies=isset($_POST["idcategorias_especies"])? limpiarCadena($_POST["idcategorias_especies"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";


switch ($_GET["op"]){
	case 'guardaryeditar':

		if (empty($idcategorias_especies)){
			$rspta=$categorias_especies->insertar($nombre);
			echo $rspta ? "Datos registrados" : "No se pudo registrar";
		}
		else {
			$rspta=$categorias_especies->editar($idcategorias_especies,$nombre);
			echo $rspta ? "Datos actualizado" : "No se pudo actualizar";
		}
	break;

	case 'desactivar':
		$rspta=$categorias_especies->desactivar($idcategorias_especies);
 		echo $rspta ? "Datos Desactivado" : "No se puede desactivar";
	break;

	case 'activar':
		$rspta=$categorias_especies->activar($idcategorias_especies);
 		echo $rspta ? "Datos activados" : "No se puede activar";
	break;

	case 'mostrar':
		$rspta=$categorias_especies->mostrar($idcategorias_especies);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break;

	case 'listar':
		$rspta=$categorias_especies->listar();
 		//Vamos a declarar un array
 		$data= Array();

 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
 				"0"=>($reg->estado)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idcategorias_especies.')"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-danger" onclick="desactivar('.$reg->idcategorias_especies.')"><i class="fa fa-close"></i></button>':
 					'<button class="btn btn-warning" onclick="mostrar('.$reg->idcategorias_especies.')"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-primary" onclick="activar('.$reg->idcategorias_especies.')"><i class="fa fa-check"></i></button>',
 				"1"=>$reg->nombre,
      			"2"=>($reg->estado)?'<span class="label bg-green">Activado</span>':
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
