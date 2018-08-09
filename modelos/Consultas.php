<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Consultas
{
	//Implementamos nuestro constructor
	public function __construct()
	{

    }
    

    public function NUMcategorias_bienes(){
    $sql="SELECT COUNT(categorias_bienes.idcategorias_bienes) as NUMcategorias_bienes FROM `categorias_bienes` WHERE categorias_bienes.estado=1";
    return ejecutarConsulta($sql);
  }

  public function NUMcategorias_especies(){
    $sql="SELECT COUNT(categorias_especies.idcategorias_especies) as NUMcategorias_especies FROM `categorias_especies` WHERE categorias_especies.estado=1";
    return ejecutarConsulta($sql);
  }


  public function NUMbienes(){
    $sql="SELECT COUNT(bienes.idbienes) as NUMbienes FROM `bienes` WHERE bienes.condicion=1";
    return ejecutarConsulta($sql);
  }

  public function NUMespecies(){
    $sql="SELECT COUNT(especies.idespecies) as NUMespecies FROM `especies` WHERE especies.condicion=1";
    return ejecutarConsulta($sql);
  }

  public function NUMcajeros(){
    $sql="SELECT COUNT(personas.idcajeros) NUMcajeros FROM `personas` WHERE personas.condicion=1";
    return ejecutarConsulta($sql);
  }

  public function NUMalertabienes(){
    $sql="SELECT COUNT(bienes.idbienes) as NUMalertabienes FROM `bienes` WHERE bienes.condicion=1 AND bienes.stock<50";
    return ejecutarConsulta($sql);
  }

  public function NUMalertaespecies(){
    $sql="SELECT COUNT(especies.idespecies) as NUMalertaespecies FROM `especies` WHERE especies.condicion=1 AND especies.stock <= 5000";
    return ejecutarConsulta($sql);
  }


  
}

?>