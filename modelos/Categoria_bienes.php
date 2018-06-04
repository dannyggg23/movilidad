<?php
require "../config/Conexion.php";
Class Categoria_bienes
{
  public function _construct(){

  }

  public function insertar($nombre){

    $sql=sprintf("INSERT INTO `categorias_bienes`(`nombre`) VALUES ('%s')",$nombre);
    return ejecutarConsulta($sql);
  }

  public function editar($idcategorias_bienes,$nombre)
  {
  $sql=sprintf("UPDATE `categorias_bienes` SET 
  `nombre`='%s' WHERE idcategorias_bienes = '%s' ",$nombre,$idcategorias_bienes);
    return ejecutarConsulta($sql);
  }


  public function desactivar($idcategorias_bienes)
  {
    $sql=sprintf("UPDATE categorias_bienes SET estado='0'  WHERE `idcategorias_bienes`='%s' ",$idcategorias_bienes);
    return ejecutarConsulta($sql);
  }


  public function activar($idcategorias_bienes)
  {
    $sql=sprintf("UPDATE categorias_bienes SET estado='1'  WHERE `idcategorias_bienes`='%s' ",$idcategorias_bienes);
    return ejecutarConsulta($sql);
  }

  public function mostrar($idcategorias_bienes)
  {
    $sql=sprintf("SELECT * FROM `categorias_bienes` WHERE idcategorias_bienes='%s'",$idcategorias_bienes);
    return ejecutarConsultaSimpleFila($sql);
  }
  public function listar(){
    $sql="SELECT * FROM categorias_bienes ";
    return ejecutarConsulta($sql);
  }

   public function select(){
    $sql="SELECT * FROM categorias_bienes ";
    return ejecutarConsulta($sql);
  }
}
 ?>
