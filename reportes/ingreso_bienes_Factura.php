<?php
//Activamos el almacenamiento en el buffer
ob_start();
if (strlen(session_id()) < 1) 
  session_start();

if (!isset($_SESSION["nombre"]))
{
  echo 'Debe ingresar al sistema correctamente para visualizar el reporte';
}
else
{
if ($_SESSION['bienes']==1)
{
//Incluímos el archivo Factura.php
require('Factura_ingresos.php');

//Establecemos los datos de la empresa
$logo = "logo.png";
$ext_logo = "png";
$empresa = "UNIDAD DE MOVILIDAD DE LATACUNGA";
$documento = "DIRECCION DE MOVILIDAD";
$direccion = "MERCADO MAYORISTA LATACUNGA";
$telefono = "TELEFONO";
$email = "EMAIL";

//Obtenemos los datos de la cabecera de la venta actual
require_once "../modelos/Ingreso_bienes.php";
$ingreso_bienes= new Ingreso_bienes();
$rsptav = $ingreso_bienes->ingreso_bienes_cabecera($_GET["id"]);
//Recorremos todos los valores obtenidos
$regv = $rsptav->fetch_object();

//Establecemos la configuración de la factura
$pdf = new PDF_Invoice( 'P', 'mm', 'A4' );
$pdf->AddPage();

//Enviamos los datos de la empresa al método addSociete de la clase Factura
$pdf->addSociete(utf8_decode($empresa),
                  $documento."\n" .
                  utf8_decode("Dirección: ").utf8_decode($direccion)."\n".
                  utf8_decode("Teléfono: ").$telefono."\n" .
                  "Email : ".$email,$logo,$ext_logo);

$pdf->fact_dev( utf8_decode("Ingreso Nº "), " $regv->numero_ingreso" );
$pdf->temporaire( "" );
$pdf->addDate( $regv->fecha);

//Enviamos los datos del cliente al método addClientAdresse de la clase Factura
$pdf->addClientAdresse(utf8_decode($regv->ubicacion),"Detalle: ".utf8_decode($regv->detalle),"Usuario: ".$regv->nombre,"","");

//Establecemos las columnas que va a tener la sección donde mostramos los detalles de la venta

$cols=array( "CODIGO"=>30,
             "DESCRIPCION"=>71,
             "CANTIDAD"=>22,
             "P.U."=>25,
             "DSCTO"=>20,
             "SUBTOTAL"=>22);
$pdf->addCols( $cols);
$cols=array( "CODIGO"=>"L",
             "DESCRIPCION"=>"L",
             "CANTIDAD"=>"C",
             "P.U."=>"R",
             "DSCTO" =>"R",
             "SUBTOTAL"=>"C");
$pdf->addLineFormat( $cols);
$pdf->addLineFormat($cols);
//Actualizamos el valor de la coordenada "y", que será la ubicación desde donde empezaremos a mostrar los datos
$y= 89;

//Obtenemos todos los detalles de la venta actual
$rsptad = $ingreso_bienes->detalle_bienes_cabecera($_GET["id"]);

while ($regd = $rsptad->fetch_object()) {
  $subtotal=$regd->cantidad*$regd->consto_unitario;
  $line = array( "CODIGO"=> "$regd->codigo",
                "DESCRIPCION"=> utf8_decode("$regd->nombre"),
                "CANTIDAD"=> "$regd->cantidad",
                "P.U."=> "$regd->consto_unitario",
                "DSCTO" => "0",
                "SUBTOTAL"=> "$subtotal");
            $size = $pdf->addLine( $y, $line );
            $y   += $size + 2;
}

//Convertimos el total en letras
require_once "Letras.php";
$V=new EnLetras(); 
$con_letra=strtoupper($V->ValorEnLetras($regv->total,"DOLARES"));
$pdf->addCadreTVAs("---".$con_letra);

//Mostramos el impuesto
$pdf->addTVAs( 0, $regv->total,"$ ");
$pdf->addCadreEurosFrancs("IVA"." 0 %");
$pdf->Output('Reporte de Venta','I');


}
else
{
  echo 'No tiene permiso para visualizar el reporte';
}

}
ob_end_flush();
?>