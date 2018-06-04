<?php 
require_once "../modelos/Bienes.php";

$bienes=new bienes();

$idbienes=isset($_POST["idbienes"])? limpiarCadena($_POST["idbienes"]):"";
$categorias_bienes_idcategorias_bienes=isset($_POST["categorias_bienes_idcategorias_bienes"])? limpiarCadena($_POST["categorias_bienes_idcategorias_bienes"]):"";
$codigo=isset($_POST["codigo"])? limpiarCadena($_POST["codigo"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$stock=isset($_POST["stock"])? limpiarCadena($_POST["stock"]):"";
$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
$imagen=isset($_POST["imagen"])? limpiarCadena($_POST["imagen"]):"";
$tipo=isset($_POST["tipo"])? limpiarCadena($_POST["tipo"]):"";
$valor=isset($_POST["valor"])? limpiarCadena($_POST["valor"]):"";

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
				move_uploaded_file($_FILES["imagen"]["tmp_name"], "../files/bienes/" . $imagen);
			}
		}
		if (empty($idbienes)){
			$rspta=$bienes->insertar($codigo,$nombre,$descripcion,$tipo,$imagen,$valor,$categorias_bienes_idcategorias_bienes,$stock);
			echo $rspta ? "Artículo registrado" : "Artículo no se pudo registrar";
		}
		else {
			$rspta=$bienes->editar($idbienes,$codigo,$nombre,$descripcion,$tipo,$imagen,$valor,$categorias_bienes_idcategorias_bienes,$stock);
			echo $rspta ? "Artículo actualizado" : "Artículo no se pudo actualizar";
		}
	break;

	case 'desactivar':
		$rspta=$bienes->desactivar($idbienes);
 		echo $rspta ? "Artículo Desactivado" : "Artículo no se puede desactivar";
	break;

	case 'activar':
		$rspta=$bienes->activar($idbienes);
 		echo $rspta ? "Artículo activado" : "Artículo no se puede activar";
	break;

	case 'mostrar':
		$rspta=$bienes->mostrar($idbienes);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break;

	case 'listar':
		$rspta=$bienes->listar();
 		//Vamos a declarar un array
 		$data= Array();

 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
 				"0"=>($reg->condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idbienes.')"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-danger" onclick="desactivar('.$reg->idbienes.')"><i class="fa fa-close"></i></button>':
 					'<button class="btn btn-warning" onclick="mostrar('.$reg->idbienes.')"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-primary" onclick="activar('.$reg->idbienes.')"><i class="fa fa-check"></i></button>',
 				"1"=>$reg->nombre,
 				"2"=>$reg->categoria,
 				"3"=>$reg->codigo,
 				"4"=>$reg->stock,
 				"5"=>$reg->tipo,
 				"6"=>$reg->valor,
 				"7"=>$reg->descripcion,
 				"8"=>"<img src='../files/bienes/".$reg->imagen."' height='50px' width='50px' >",
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
		require_once "../modelos/Categoria_bienes.php";
		$categoria_bienes = new Categoria_bienes();

		$rspta = $categoria_bienes->select();

		while ($reg = $rspta->fetch_object())
				{
					echo '<option value=' . $reg->idcategorias_bienes . '>' . $reg->nombre . '</option>';
				}
	break;
}
?>