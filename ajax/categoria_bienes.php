<?php
require_once "../modelos/Categoria_bienes.php";

$categorias_bienes=new Categoria_bienes();

$idcategorias_bienes=isset($_POST["idcategorias_bienes"])? limpiarCadena($_POST["idcategorias_bienes"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";


switch ($_GET["op"]){
	case 'guardaryeditar':

		if (empty($idcategorias_bienes)){
			$rspta=$categorias_bienes->insertar($nombre);
			echo $rspta ? "Datos registrados" : "No se pudo registrar";
		}
		else {
			$rspta=$categorias_bienes->editar($idcategorias_bienes,$nombre);
			echo $rspta ? "Datos actualizado" : "No se pudo actualizar";
		}
	break;

	case 'desactivar':
		$rspta=$categorias_bienes->desactivar($idcategorias_bienes);
 		echo $rspta ? "Datos Desactivado" : "No se puede desactivar";
	break;

	case 'activar':
		$rspta=$categorias_bienes->activar($idcategorias_bienes);
 		echo $rspta ? "Datos activados" : "No se puede activar";
	break;

	case 'mostrar':
		$rspta=$categorias_bienes->mostrar($idcategorias_bienes);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break;

	case 'listar':
		$rspta=$categorias_bienes->listar();
 		//Vamos a declarar un array
 		$data= Array();

 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
 				"0"=>($reg->estado)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idcategorias_bienes.')"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-danger" onclick="desactivar('.$reg->idcategorias_bienes.')"><i class="fa fa-close"></i></button>':
 					'<button class="btn btn-warning" onclick="mostrar('.$reg->idcategorias_bienes.')"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-primary" onclick="activar('.$reg->idcategorias_bienes.')"><i class="fa fa-check"></i></button>',
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
