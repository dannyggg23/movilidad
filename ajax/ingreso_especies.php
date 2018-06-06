<?php 
if (strlen(session_id()) < 1) 
  session_start();

require_once "../modelos/Ingreso_especies.php";

$ingreso=new Ingreso_especies();
$idingreso_especies=isset($_POST["idingreso_especies"])? limpiarCadena($_POST["idingreso_especies"]):"";

$ubicacion=isset($_POST["ubicacion"])? limpiarCadena($_POST["ubicacion"]):"";
$detalle=isset($_POST["detalle"])? limpiarCadena($_POST["detalle"]):"";
$fecha=isset($_POST["fecha"])? limpiarCadena($_POST["fecha"]):"";
$numero_docuemnto=isset($_POST["numero_docuemnto"])? limpiarCadena($_POST["numero_docuemnto"]):"";
$total=isset($_POST["total"])? limpiarCadena($_POST["total"]):"";
$usuario_idusuario=$_SESSION["idusuario"];


switch ($_GET["op"]){
	case 'guardaryeditar':
		if (empty($idingreso_especies)){
			$rspta=$ingreso->insertar($usuario_idusuario,$fecha,$numero_docuemnto,$ubicacion,$detalle,$total,$_POST["cantidad"],$_POST["desde"],$_POST["subtotal"],$_POST["especies_idespecies"]);
			echo $rspta ? "Ingreso registrado" : "No se pudieron registrar todos los datos del ingreso";
		}
		else {
		}
	break;

	case 'anular':
		$rspta=$ingreso->anular($idingreso_especies);
 		echo $rspta ? "Ingreso anulado" : "Ingreso no se puede anular";
	break;

	case 'mostrar':
		$rspta=$ingreso->mostrar($idingreso_especies);
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
                                    <th>Desde</th>
                                    <th>Hasta</th>
                                </thead>';

		while ($reg = $rspta->fetch_object())
				{
					echo '<tr class="filas"><td></td><td>'.$reg->nombre.'</td><td>'.$reg->desde.'</td><td>'.$reg->hasta.'</td><td>'.$reg->cantidad.'</td></tr>';
					$total=$total+$reg->cantidad;
				}
		echo '<tfoot>
                                    <th>TOTAL</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th><h4 id="total">S/.'.$total.'</h4><input type="hidden" name="total" id="total"></th> 
                                </tfoot>';
	break;

	case 'listar':
		$rspta=$ingreso->listar();
 		//Vamos a declarar un array
 		$data= Array();

 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
 				"0"=>($reg->condicion=='Aceptado')?'<button class="btn btn-warning" onclick="mostrar('.$reg->idingreso_especies.')"><i class="fa fa-eye"></i></button>'.
 					' <button class="btn btn-danger" onclick="anular('.$reg->idingreso_especies.')"><i class="fa fa-close"></i></button>':
 					'<button class="btn btn-warning" onclick="mostrar('.$reg->idingreso_especies.')"><i class="fa fa-eye"></i></button>',
 				"1"=>$reg->fecha,
 				"2"=>$reg->ubicacion,
 				"3"=>$reg->detalle,
 				"4"=>$reg->numero_docuemnto,
 				"5"=>$reg->nombre,
 				"6"=>$reg->total,
 				"7"=>($reg->condicion=='Aceptado')?'<span class="label bg-green">Aceptado</span>':
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


	case 'listarespecies':
		require_once "../modelos/Especies.php";
		$articulo=new especies();

		$rspta=$articulo->listarActivos();
 		//Vamos a declarar un array
 		$data= Array();

 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
 				"0"=>'<button class="btn btn-warning" onclick="agregarDetalle('.$reg->idespecies.',\''.$reg->nombre.'\',\''.$reg->hasta.'\')"><span class="fa fa-plus"></span></button>',
 				"1"=>$reg->nombre,
                "2"=>$reg->codigo,
 				"3"=>$reg->categoria,
 				"4"=>$reg->stock,
 				"5"=>"<img src='../files/especies/".$reg->imagen."' height='50px' width='50px' >"
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