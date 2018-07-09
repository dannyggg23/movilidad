<?php 
if (strlen(session_id()) < 1) 
  session_start();

require_once "../modelos/Egreso_especies.php";

$egreso=new Egreso_especies();

$idegreso_especies=isset($_POST["idegreso_especies"])? limpiarCadena($_POST["idegreso_especies"]):"";

$ubicacion=isset($_POST["ubicacion"])? limpiarCadena($_POST["ubicacion"]):"";
$detalle=isset($_POST["detalle"])? limpiarCadena($_POST["detalle"]):"";
$numero_documento=isset($_POST["numero_documento"])? limpiarCadena($_POST["numero_documento"]):"";
$total=isset($_POST["total"])? limpiarCadena($_POST["total"]):"";
$personas_idcajeros=isset($_POST["personas_idcajeros"])? limpiarCadena($_POST["personas_idcajeros"]):"";
$usuario_idusuario=$_SESSION["idusuario"];


switch ($_GET["op"]){
	case 'guardaryeditar':
		if (empty($idegreso_especies)){
			$rspta=$egreso->insertar($numero_documento,$ubicacion, 
            $detalle,$total,$usuario_idusuario,$personas_idcajeros,
            $_POST["especies_idespecies"],$_POST["cantidad"],$_POST["desde"]);
			echo $rspta ? "Egreso registrado" : "No se pudieron registrar todos los datos del egreso";
		}
		else {
		}
	break;

	case 'anular':
		$rspta=$egreso->anular($idegreso_especies);
 		echo $rspta ? "Egreso anulado" : "egreso no se puede anular";
	break;

	case 'mostrar':
		$rspta=$egreso->mostrar($idegreso_especies);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break;

	case 'listarDetalle':
		//Recibimos el idegreso
		$id=$_GET['id'];

        $rspta = $egreso->listarDetalle($id);
         
		$total=0;
		echo '<thead style="background-color:#A9D0F5">
                                    <th>Opciones</th>
                                    <th>Artículo</th>
                                    <th>Cantidad</th>
                                    <th>Desde</th>
                                    <th>hasta</th>
                                </thead>';

		while ($reg = $rspta->fetch_object())
				{
					echo '<tr class="filas"><td></td><td>'.$reg->nombre.'</td><td>'.$reg->cantidad.'</td><td>'.$reg->desde.'</td><td>'.$reg->hasta.'</td></tr>';
					$total=$total+$reg->cantidad;
				}
		echo '<tfoot>
                                    <th>TOTAL</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th><h4 id="totalL">/.'.$total.'</h4><input type="hidden" name="total" id="total"></th> 
                                </tfoot>';
	break;

	case 'listar':
		$rspta=$egreso->listar();
 		//Vamos a declarar un array
		 $data= Array();
		 
		 $url='../reportes/egreso_especies_factura.php?id=';

 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
 				"0"=>(($reg->condicion=='Aceptado')?'<button class="btn btn-warning" onclick="mostrar('.$reg->idegreso_especies.')"><i class="fa fa-eye"></i></button>'.
				 ' <button class="btn btn-danger" onclick="anular('.$reg->idegreso_especies.')"><i class="fa fa-close"></i></button>':
				 '<button class="btn btn-warning" onclick="mostrar('.$reg->idegreso_especies.')"><i class="fa fa-eye"></i></button>').
				 '<a target="_blank" href="'.$url.$reg->idegreso_especies.'"> <button class="btn btn-info"><i class="fa fa-file"></i></button></a>',
 				"1"=>$reg->fecha,	
 				"2"=>$reg->numero_documento,
 				"3"=>$reg->persona,
 				"4"=>$reg->nombre,
 				"5"=>$reg->total,
 				"6"=>($reg->condicion=='Aceptado')?'<span class="label bg-green">Aceptado</span>':
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
 				"0"=>'<button class="btn btn-warning" onclick="agregarDetalle('.$reg->idespecies.',\''.$reg->nombre.'\',\''.$reg->desde.'\')"><span class="fa fa-plus"></span></button>',
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
    
    case "selectPersonas":
		require_once "../modelos/Personas.php";
		$personas = new Personas();

		$rspta = $personas->select();

		echo '<option>--SELECCIONE--</option>';

		while ($reg = $rspta->fetch_object())
				{
					echo '<option value=' . $reg->idcajeros . '>' . $reg->nombre . '</option>';
				}
	break;
}
?>