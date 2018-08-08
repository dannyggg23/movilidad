<?php 
require_once "../modelos/Seguimiento.php";

$seguimiento=new Seguimiento();

$idseguimiento_egrese_bienes=isset($_POST["idseguimiento_egrese_bienes"])? limpiarCadena($_POST["idseguimiento_egrese_bienes"]):"";
$fecha=isset($_POST["fecha"])? limpiarCadena($_POST["fecha"]):"";
$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
$egreso_bienes_idegreso_bienes=isset($_POST["egreso_bienes_idegreso_bienes"])? limpiarCadena($_POST["egreso_bienes_idegreso_bienes"]):"";
$imagen=isset($_POST["imagen"])? limpiarCadena($_POST["imagen"]):"";


switch ($_GET["op"]){
	case 'guardaryeditar':

		if (!file_exists($_FILES['imagen']['tmp_name']) || !is_uploaded_file($_FILES['imagen']['tmp_name']))
		{
			$imagen=$_POST["imagenactual"];
		}
		else 
		{
			$ext = explode(".", $_FILES["imagen"]["name"]);
			if ($_FILES['imagen']['type'] == "image/jpg" || $_FILES['imagen']['type'] == "image/jpeg" || $_FILES['imagen']['type'] == "image/png")
			{
				$imagen = round(microtime(true)) . '.' . end($ext);
				move_uploaded_file($_FILES["imagen"]["tmp_name"], "../files/seguimiento/" . $imagen);
			}
		}
		if (empty($idseguimiento_egrese_bienes)){
			$rspta=$seguimiento->insertar($fecha,$descripcion,$egreso_bienes_idegreso_bienes,$imagen);
			echo $rspta ? "Datos registrado" : "Datos no se pudo registrar";
		}
		else {
			$rspta=$seguimiento->editar($idseguimiento_egrese_bienes,$fecha,$descripcion,$egreso_bienes_idegreso_bienes,$imagen);
			echo $rspta ? "Datos actualizado" : "Datos no se pudo actualizar";
		}
	break;

	case 'desactivar':
		$rspta=$seguimiento->desactivar($idseguimiento_egrese_bienes);
 		echo $rspta ? "Datos Desactivado" : "Datos no se puede desactivar";
	break;

	case 'activar':
		$rspta=$seguimiento->activar($idseguimiento_egrese_bienes);
 		echo $rspta ? "Datos activado" : "Datos no se puede activar";
	break;

	case 'mostrar':
		$rspta=$seguimiento->mostrar($idseguimiento_egrese_bienes);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break;

	case 'listar':
		$rspta=$seguimiento->listar();
 		//Vamos a declarar un array
 		$data= Array();

 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
 				"0"=>($reg->condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idseguimiento_egrese_bienes.')"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-danger" onclick="desactivar('.$reg->idseguimiento_egrese_bienes.')"><i class="fa fa-close"></i></button>':
 					'<button class="btn btn-warning" onclick="mostrar('.$reg->idseguimiento_egrese_bienes.')"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-primary" onclick="activar('.$reg->idseguimiento_egrese_bienes.')"><i class="fa fa-check"></i></button>',
 				"1"=>$reg->numero_egreso,
 				"2"=>$reg->nombre,
 				"3"=>$reg->fecha,
 				"4"=>$reg->descripcion,
 				"5"=>"<img src='../files/seguimiento/".$reg->imagen."' height='100px' width='100px' >",
 				"6"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':
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

	case "selectEgresoBienes":
		require_once "../modelos/Egreso_bienes.php";
		$egreso_bienes = new Egreso_bienes();

        $rspta = $egreso_bienes->select();
        
        echo '<option value="">--SELECCIONE--</option>';

		while ($reg = $rspta->fetch_object())
				{
					echo '<option value=' . $reg->idegreso_bienes . '>' . $reg->numero_egreso . '</option>';
				}
	break;
}
?>