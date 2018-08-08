<?php 
if (strlen(session_id()) < 1) 
  session_start();
 
require_once "../modelos/egreso_bienes.php";

$egreso=new Egreso_bienes();

$idegreso_bienes=isset($_POST["idegreso_bienes"])? limpiarCadena($_POST["idegreso_bienes"]):"";


$fecha=isset($_POST["fecha"])? limpiarCadena($_POST["fecha"]):"";
$total=isset($_POST["total"])? limpiarCadena($_POST["total"]):"";
$lugar=isset($_POST["lugar"])? limpiarCadena($_POST["lugar"]):"";
$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
$personas_idcajeros=isset($_POST["personas_idcajeros"])? limpiarCadena($_POST["personas_idcajeros"]):"";
$numero_egreso=isset($_POST["numero_egreso"])? limpiarCadena($_POST["numero_egreso"]):"";
$calle=isset($_POST["calle"])? limpiarCadena($_POST["calle"]):"";
$interseccion=isset($_POST["interseccion"])? limpiarCadena($_POST["interseccion"]):"";
$proyectos_idproyectos=isset($_POST["proyectos_idproyectos"])? limpiarCadena($_POST["proyectos_idproyectos"]):"";
$usuario_idusuario=$_SESSION["idusuario"];

switch ($_GET["op"]){
	case 'guardaryeditar':
		if (empty($idegreso_bienes)){
			$rspta=$egreso->insertar($fecha,$total,$lugar,$descripcion,$personas_idcajeros,$usuario_idusuario,$numero_egreso,$calle,$interseccion,$proyectos_idproyectos,$_POST["cantidad"],$_POST["precio"],$_POST["bienes_idbienes"]);
			echo $rspta ? "egreso registrado" : "No se pudieron registrar todos los datos del egreso";
		}
		else {
		}
	break;

	case 'anular':
		$rspta=$egreso->anular($idegreso_bienes);
 		echo $rspta ? "egreso anulado" : "egreso no se puede anular";
	break;

	case 'mostrar':
		$rspta=$egreso->mostrar($idegreso_bienes);
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
                                    <th>Precio unitario</th>
                                    <th>Subtotal</th>
                                     </thead>';

		while ($reg = $rspta->fetch_object())
				{
					echo '<tr class="filas"><td></td><td>'.$reg->nombre.'</td><td>'.$reg->cantidad.'</td><td>'.$reg->precio.'</td><td>'.$reg->cantidad*$reg->precio.'</td></tr>';
					$total=$total+($reg->cantidad*$reg->precio);
				}
		echo '<tfoot>
                                    <th>TOTAL</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    
                                    <th><h4 id="totalL">$/.'.$total.'</h4><input type="hidden" name="total" id="total"></th> 
                                </tfoot>';
	break;

	case 'listar':
		$rspta=$egreso->listar();
 		//Vamos a declarar un array
		 $data= Array();
		 
		 $url='../reportes/egreso_bienes_Factura.php?id=';
		 

 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
 				"0"=>(($reg->estado=='Aceptado')?'<button class="btn btn-warning" onclick="mostrar('.$reg->idegreso_bienes.')"><i class="fa fa-eye"></i></button>'.
				 ' <button class="btn btn-danger" onclick="anular('.$reg->idegreso_bienes.')"><i class="fa fa-close"></i></button>':
				 '<button class="btn btn-warning" onclick="mostrar('.$reg->idegreso_bienes.')"><i class="fa fa-eye"></i></button>').
				 '<a target="_blank" href="'.$url.$reg->idegreso_bienes.'"> <button class="btn btn-info"><i class="fa fa-file"></i></button></a>',
 				"1"=>$reg->fecha,
 				"2"=>$reg->cedula,
 				"3"=>$reg->proyecto,
 				"4"=>$reg->lugar,
 				"5"=>$reg->descripcion,
 				"6"=>$reg->numero_egreso,
 				"7"=>$reg->nombre,
 				"8"=>$reg->total,
 				"9"=>($reg->estado=='Aceptado')?'<span class="label bg-green">Aceptado</span>':
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

    
 				"0"=>'<button class="btn btn-warning" onclick="agregarDetalle('.$reg->idbienes.',\''.$reg->nombre.'\',\''.$reg->valor.'\')"><span class="fa fa-plus"></span></button>',
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
    
    case "selectPersonas":
		require_once "../modelos/Personas.php";
		$personas = new Personas();

		$rspta = $personas->select();

		echo '<option value="">--SELECCIONE--</option>';


		while ($reg = $rspta->fetch_object())
				{
					echo '<option value=' . $reg->idcajeros . '>' . $reg->nombre . '</option>';
				}
	break;

	case "selectProyectos":
	require_once "../modelos/Proyectos.php";
	$personas = new Proyectos();

	$rspta = $personas->select();

	echo '<option value="">--SELECCIONE--</option>';


	while ($reg = $rspta->fetch_object())
			{
				echo '<option value=' . $reg->idproyectos . '>' . $reg->nombre . '</option>';
			}
break;
}
?>