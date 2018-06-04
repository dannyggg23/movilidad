<?php 
require_once "../modelos/Especies.php";

$especies=new Especies();

$idespecies=isset($_POST["idespecies"])? limpiarCadena($_POST["idespecies"]):"";
$categorias_especies_idcategorias_especies=isset($_POST["categorias_especies_idcategorias_especies"])? limpiarCadena($_POST["categorias_especies_idcategorias_especies"]):"";
$codigo=isset($_POST["codigo"])? limpiarCadena($_POST["codigo"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$stock=isset($_POST["stock"])? limpiarCadena($_POST["stock"]):"";
$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
$imagen=isset($_POST["imagen"])? limpiarCadena($_POST["imagen"]):"";
$desde=isset($_POST["desde"])? limpiarCadena($_POST["desde"]):"";
$hasta=isset($_POST["hasta"])? limpiarCadena($_POST["hasta"]):"";


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
				move_uploaded_file($_FILES["imagen"]["tmp_name"], "../files/especies/" . $imagen);
			}
		}
		if (empty($idespecies)){
			$rspta=$especies->insertar($categorias_especies_idcategorias_especies,$codigo,$nombre,$descripcion,$imagen,$hasta,$stock,$desde);
			echo $rspta ? "Artículo registrado" : "Artículo no se pudo registrar";
		}
		else {
			$rspta=$especies->editar($idespecies,$categorias_especies_idcategorias_especies,$codigo,$nombre,$descripcion,$imagen,$hasta,$stock,$desde);
			echo $rspta ? "Artículo actualizado" : "Artículo no se pudo actualizar";
		}
	break;

	case 'desactivar':
		$rspta=$especies->desactivar($idespecies);
 		echo $rspta ? "Artículo Desactivado" : "Artículo no se puede desactivar";
	break;

	case 'activar':
		$rspta=$especies->activar($idespecies);
 		echo $rspta ? "Artículo activado" : "Artículo no se puede activar";
	break;

	case 'mostrar':
		$rspta=$especies->mostrar($idespecies);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break;

	case 'listar':
		$rspta=$especies->listar();
 		//Vamos a declarar un array
 		$data= Array();

 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
 				"0"=>($reg->condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idespecies.')"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-danger" onclick="desactivar('.$reg->idespecies.')"><i class="fa fa-close"></i></button>':
 					'<button class="btn btn-warning" onclick="mostrar('.$reg->idespecies.')"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-primary" onclick="activar('.$reg->idespecies.')"><i class="fa fa-check"></i></button>',
 				"1"=>$reg->nombre,
 				"2"=>$reg->categoria,
 				"3"=>$reg->codigo,
 				"4"=>$reg->stock,
 				"5"=>$reg->desde,
 				"6"=>$reg->hasta,
 				"7"=>$reg->descripcion,
 				"8"=>"<img src='../files/especies/".$reg->imagen."' height='50px' width='50px' >",
 				"9"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':
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

	case "selectCategoria":
		require_once "../modelos/Categorias_especies.php";
		$categoria_especies = new Categorias_especies();

		$rspta = $categoria_especies->select();

		while ($reg = $rspta->fetch_object())
				{
					echo '<option value=' . $reg->idcategorias_especies . '>' . $reg->nombre . '</option>';
				}
	break;
}
?>