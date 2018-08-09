<?php
//Activamos el alamaceamiento en el buffer
ob_start();
session_start();
if(!isset($_SESSION['nombre']))
{
  header("Location:login.html");
}
else
{

require 'header.php';

if($_SESSION['escritorio']==1)
{
    require_once "../modelos/Consultas.php";
   
    $consulta=new Consultas;
    $resptcb=$consulta->NUMcategorias_bienes();
    $regcb=$resptcb->fetch_object();
    $totacb=$regcb->NUMcategorias_bienes;

    $resptce=$consulta->NUMcategorias_especies();
    $regce=$resptce->fetch_object();
    $totalce=$regce->NUMcategorias_especies;

    $resptne=$consulta->NUMespecies();
    $regne=$resptne->fetch_object();
    $totalne=$regne->NUMespecies;

    $resptnb=$consulta->NUMbienes();
    $regnb=$resptnb->fetch_object();
    $totalnb=$regnb->NUMbienes;

    $resptnc=$consulta->NUMcajeros();
    $regnc=$resptnc->fetch_object();
    $totalnc=$regnc->NUMcajeros;

    $resptnab=$consulta->NUMalertabienes();
    $regnab=$resptnab->fetch_object();
    $totalnab=$regnab->NUMalertabienes;

    $resptnae=$consulta->NUMalertaespecies();
    $regnae=$resptnae->fetch_object();
    $totalnae=$regnae->NUMalertaespecies;
  

?>
<!--Contenido-->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">        
        <!-- Main content -->
        <section class="content">
            <div class="row">
              <div class="col-md-12">
                  <div class="box">
                    <div class="box-header with-border">
                          <h1 class="box-title">Escritorio </h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body" >

                    <div class="col-lg-16 col-md-16 col-sm-16 col-xs-6">
                    <div class="small-box bg-aqua">
                    <div class="inner">
                    <h4 style="font-size:17px;">
                    <strong> <?php  echo $totacb; ?>  </strong>
                    </h4>
                    <p>Categorías Bienes</p>
                    </div>
                    <div class="icon">
                    <i class="ion ion-bag"></i>
                    </div>
                    <a href="categoria_bienes.php" class="small-box-footer">Categorías Bienes
                    <i class="fa fa-arrow-circle-right"></i>
                    </a>
                    </div>
                    </div>


                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <div class="small-box bg-green">
                    <div class="inner">
                    <h4 style="font-size:17px;">
                    <strong>  <?php  echo $totalce; ?> </strong>
                    </h4>
                    <p>Categorías Especies</p>
                    </div>
                    <div class="icon">
                    <i class="ion ion-bag"></i>
                    </div>
                    <a href="categorias_especies.php" class="small-box-footer">Categorías Especies
                    <i class="fa fa-arrow-circle-right"></i>
                    </a>
                    </div>
                    </div>



                     <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <div class="small-box bg-navy">
                    <div class="inner">
                    <h4 style="font-size:17px;">
                    <strong>  <?php  echo $totalne; ?> </strong>
                    </h4>
                    <p>Especies</p>
                    </div>
                    <div class="icon">
                    <i class="ion ion-bag"></i>
                    </div>
                    <a href="especies.php" class="small-box-footer">Especies
                    <i class="fa fa-arrow-circle-right"></i>
                    </a>
                    </div>
                    </div>

                       <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <div class="small-box bg-yellow">
                    <div class="inner">
                    <h4 style="font-size:17px;">
                    <strong>  <?php  echo $totalnb; ?> </strong>
                    </h4>
                    <p>Bienes</p>
                    </div>
                    <div class="icon">
                    <i class="ion bg-orange"></i>
                    </div>
                    <a href="bienes.php" class="small-box-footer">Bienes
                    <i class="fa fa-arrow-circle-right"></i>
                    </a>
                    </div>
                    </div>

                       <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <div class="small-box bg-olive">
                    <div class="inner">
                    <h4 style="font-size:17px;">
                    <strong>  <?php  echo $totalnc; ?> </strong>
                    </h4>
                    <p>Personas</p>
                    </div>
                    <div class="icon">
                    <i class="ion ion-bag"></i>
                    </div>
                    <a href="cajeros.php" class="small-box-footer">Personas
                    <i class="fa fa-arrow-circle-right"></i>
                    </a>
                    </div>
                    </div>

                       <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <div class="small-box bg-red">
                    <div class="inner">
                    <h4 style="font-size:40px;">
                    <strong>  <?php  echo $totalnab; ?> </strong>
                    </h4>
                    <p>Alerta bienes</p>
                    <small>Tiene menos de 50 unidades en Artículos</small>
                    </div>
                    <div class="icon">
                    <i class="ion ion-bag"></i>
                    </div>
                    <a href="bienes.php" class="small-box-footer">* ALERTA BIENES
                    <i class="fa fa-arrow-circle-right"></i>
                    </a>
                    </div>
                    </div>

                       <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <div class="small-box bg-maroon">
                    <div class="inner">
                    <h4 style="font-size:40px;">
                    <strong>  <?php  echo $totalnae; ?> </strong>
                    </h4>
                    <p>Alerta Especies</p>
                    <small>Tiene menos de 5000 unidades en Artículos</small>

                    </div>
                    <div class="icon">
                    <i class="ion ion-bag"></i>
                    </div>
                    <a href="especies.php" class="small-box-footer">* ALERTA ESPECIES
                    <i class="fa fa-arrow-circle-right"></i>
                    </a>
                    </div>
                    </div>


                    </div>
                    <div class="panel-body" style="heigth: 400px" >
                    </div>


                    <!--Fin centro -->
                  </div><!-- /.box -->
              </div><!-- /.col -->
          </div><!-- /.row -->
      </section><!-- /.content -->

    </div><!-- /.content-wrapper -->
  <!--Fin-Contenido-->
<?php
}
else
{
  require 'noacceso.php';
}
require 'footer.php';
?>

<script type="text/javascript" src="scripts/categoria.js"></script>
<?php
}

ob_end_flush();

 ?>
