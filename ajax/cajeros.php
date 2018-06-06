<?php 
require_once "../modelos/Personas.php";

$cajeros=new Personas();

$cedula=isset($_POST["cedula"])? limpiarCadena($_POST["cedula"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$funcion=isset($_POST["funcion"])? limpiarCadena($_POST["funcion"]):"";
$idcajeros=isset($_POST["idcajeros"])? limpiarCadena($_POST["idcajeros"]):"";


switch ($_GET["op"]){
	case 'guardaryeditar':

		if (empty($idcajeros)){
			$rspta=$cajeros->insertar($cedula,$nombre,$funcion);
			echo $rspta ? "Persona registrado" : "Persona no se pudo registrar";
		}
		else {
			$rspta=$cajeros->editar($idcajeros,$cedula,$nombre,$funcion);
			echo $rspta ? "Persona actualizado" : "Persona no se pudo actualizar";
		}
	break;

	case 'desactivar':
		$rspta=$cajeros->desactivar($idcajeros);
 		echo $rspta ? "Persona Desactivado" : "Persona no se puede desactivar";
	break;

	case 'activar':
		$rspta=$cajeros->activar($idcajeros);
 		echo $rspta ? "Persona activado" : "Persona no se puede activar";
	break;

	case 'mostrar':
		$rspta=$cajeros->mostrar($idcajeros);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break;

	case 'listar':
		$rspta=$cajeros->listar();
 		//Vamos a declarar un array
 		$data= Array();

 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
 				"0"=>($reg->condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idcajeros.')"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-danger" onclick="desactivar('.$reg->idcajeros.')"><i class="fa fa-close"></i></button>':
 					'<button class="btn btn-warning" onclick="mostrar('.$reg->idcajeros.')"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-primary" onclick="activar('.$reg->idcajeros.')"><i class="fa fa-check"></i></button>',
 				"1"=>$reg->cedula,
 				"2"=>$reg->nombre,
 				"3"=>$reg->funcion,
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