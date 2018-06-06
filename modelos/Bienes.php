<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Bienes
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método <para></para> insertar registros
	public function insertar($codigo,$nombre,$descripcion,$tipo,$imagen,$valor,$categorias_bienes_idcategorias_bienes,$stock)
	{
		$sql="INSERT INTO Bienes (codigo,nombre,descripcion,tipo,imagen,valor,categorias_bienes_idcategorias_bienes,stock)
		VALUES ('$codigo','$nombre','$descripcion','$tipo','$imagen','$valor','$categorias_bienes_idcategorias_bienes','$stock')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	public function editar($idbienes,$codigo,$nombre,$descripcion,$tipo,$imagen,$valor,$categorias_bienes_idcategorias_bienes,$stock)
	{
		$sql="UPDATE Bienes SET codigo='$codigo',nombre='$nombre',descripcion='$descripcion',tipo='$tipo',imagen='$imagen',valor='$valor',categorias_bienes_idcategorias_bienes='$categorias_bienes_idcategorias_bienes',stock='$stock' WHERE idbienes='$idbienes'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar categorías
	public function desactivar($idbienes)
	{
		$sql="UPDATE Bienes SET condicion='0' WHERE idbienes='$idbienes'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar categorías
	public function activar($idbienes)
	{
		$sql="UPDATE Bienes SET condicion='1' WHERE idbienes='$idbienes'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idbienes)
	{
		$sql="SELECT * FROM Bienes WHERE idbienes='$idbienes'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT bienes.idbienes,bienes.codigo,bienes.nombre,bienes.descripcion,bienes.tipo,bienes.imagen,bienes.valor,bienes.valor,bienes.stock,bienes.condicion,categorias_bienes.nombre as categoria FROM `bienes` INNER JOIN categorias_bienes on categorias_bienes.idcategorias_bienes=bienes.categorias_bienes_idcategorias_bienes";
		return ejecutarConsulta($sql);		
	}
	//Implementar un método para listar los registros y mostrar en el select
	public function select()
	{
		$sql="SELECT * FROM Bienes where condicion=1";
		return ejecutarConsulta($sql);		
	}
	public function listarActivos()
	{
		$sql="SELECT bienes.idbienes,bienes.codigo,bienes.nombre,bienes.descripcion,bienes.tipo,bienes.imagen,bienes.valor,bienes.stock,bienes.condicion,categorias_bienes.nombre as categoria FROM `bienes` INNER JOIN categorias_bienes on categorias_bienes.idcategorias_bienes=bienes.categorias_bienes_idcategorias_bienes where condicion=1";
		return ejecutarConsulta($sql);		
	}
}

?>
