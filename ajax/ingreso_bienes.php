<?php 
if (strlen(session_id()) < 1) 
  session_start();

require_once "../modelos/Ingreso_bienes.php";

$ingreso=new Ingreso_bienes();
$idingreso_bienes=isset($_POST["idingreso_bienes"])? limpiarCadena($_POST["idingreso_bienes"]):"";

$ubicacion=isset($_POST["ubicacion"])? limpiarCadena($_POST["ubicacion"]):"";
$detalle=isset($_POST["detalle"])? limpiarCadena($_POST["detalle"]):"";
$numero_ingreso=isset($_POST["numero_ingreso"])? limpiarCadena($_POST["numero_ingreso"]):"";
$fecha=isset($_POST["fecha"])? limpiarCadena($_POST["fecha"]):"";
$n_documento=isset($_POST["n_documento"])? limpiarCadena($_POST["n_documento"]):"";
$total=isset($_POST["total"])? limpiarCadena($_POST["total"]):"";
$usuario_idusuario=$_SESSION["idusuario"];


switch ($_GET["op"]){
	case 'guardaryeditar':
		if (empty($idingreso_bienes)){
			$rspta=$ingreso->insertar($ubicacion,$detalle,$numero_ingreso,$fecha,$n_documento,$total,$usuario_idusuario,$_POST["consto_unitario"],$_POST["cantidad"],$_POST["bienes_idbienes"]);
			echo $rspta ? "Ingreso registrado" : "No se pudieron registrar todos los datos del ingreso";
		}
		else {
		}
	break;

	case 'anular':
		$rspta=$ingreso->anular($idingreso_bienes);
 		echo $rspta ? "Ingreso anulado" : "Ingreso no se puede anular";
	break;

	case 'mostrar':
		$rspta=$ingreso->mostrar($idingreso_bienes);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break;

	case 'listarDetalle':
		//Recibimos el idingreso
		$id=$_GET['id'];

        $rspta = $ingreso->listarDetalle($id);
        
		$total=0;
		echo '<thead style="background-color:#A9D0F5">
                                    <th>Opciones</th>
                                    <th>Artículo</th>
                                    <th>Cantidad</th>
                                    <th>Precio unitario</th>
                                    <th>Subtotal</th>
                                </thead>';

		while ($reg = $rspta->fetch_object())
				{
					echo '<tr class="filas"><td></td><td>'.$reg->nombre.'</td><td>'.$reg->cantidad.'</td><td>'.$reg->consto_unitario.'</td><td>'.$reg->cantidad*$reg->consto_unitario.'</td></tr>';
					$total=$total+($reg->cantidad*$reg->consto_unitario);
				}
		echo '<tfoot>
                                    <th>TOTAL</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                   
                                    <th><h4 id="totalL">S/.'.$total.'</h4><input type="hidden" name="total" id="total"></th> 
                                </tfoot>';
	break;

	case 'listar':
		$rspta=$ingreso->listar();
 		//Vamos a declarar un array
 		$data= Array();
		 $url='../reportes/ingreso_bienes_Factura.php?id=';

 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
 				"0"=>(($reg->estado=='Aceptado')?'<button class="btn btn-warning" onclick="mostrar('.$reg->idingreso_bienes.')"><i class="fa fa-eye"></i></button>'.
				 ' <button class="btn btn-danger" onclick="anular('.$reg->idingreso_bienes.')"><i class="fa fa-close"></i></button>':
				 '<button class="btn btn-warning" onclick="mostrar('.$reg->idingreso_bienes.')"><i class="fa fa-eye"></i></button>').
				 '<a target="_blank" href="'.$url.$reg->idingreso_bienes.'"> <button class="btn btn-info"><i class="fa fa-file"></i></button></a>',
 				"1"=>$reg->fecha,
 				"2"=>$reg->ubicacion,
 				"3"=>$reg->detalle,
 				"4"=>$reg->numero_ingreso,
 				"5"=>$reg->nombre,
 				"6"=>$reg->total,
 				"7"=>($reg->estado=='Aceptado')?'<span class="label bg-green">Aceptado</span>':
 				'<span class="label bg-red">Anulado</span>'
 				);
 		}
 		$results = array(
 			"sEcho"=>1, //Información para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
 			"aaData"=>$data);
 		echo json_encode($results);

	break;


	case 'listarBienes':
		require_once "../modelos/Bienes.php";
		$articulo=new Bienes();

		$rspta=$articulo->listarActivos();
 		//Vamos a declarar un array
 		$data= Array();

 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
 				"0"=>'<button class="btn btn-warning" onclick="agregarDetalle('.$reg->idbienes.',\''.$reg->nombre.'\')"><span class="fa fa-plus"></span></button>',
 				"1"=>$reg->nombre,
                 "2"=>$reg->codigo,
 				"3"=>$reg->categoria,
 				"4"=>$reg->stock,
 				"5"=>$reg->tipo,
 				"6"=>"<img src='../files/bienes/".$reg->imagen."' height='50px' width='50px' >"
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