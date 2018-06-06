<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Especies
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método <para></para> insertar registros
	public function insertar($categorias_especies_idcategorias_especies,$codigo,$nombre,$descripcion,$imagen,$hasta,$stock,$desde)
	{
		$sql="INSERT INTO especies (categorias_especies_idcategorias_especies,codigo,nombre,descripcion,imagen,hasta,stock,desde)
		VALUES ('$categorias_especies_idcategorias_especies','$codigo','$nombre','$descripcion','$imagen','$hasta','$stock','$desde')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	public function editar($idespecies,$categorias_especies_idcategorias_especies,$codigo,$nombre,$descripcion,$imagen,$hasta,$stock,$desde)
	{
		$sql="UPDATE especies SET categorias_especies_idcategorias_especies='$categorias_especies_idcategorias_especies',codigo='$codigo',nombre='$nombre',descripcion='$descripcion',imagen='$imagen',hasta='$hasta',stock='$stock',desde='$desde' WHERE idespecies='$idespecies'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idespecies)
	{
		$sql="SELECT * FROM especies WHERE idespecies='$idespecies'";
		return ejecutarConsultaSimpleFila($sql);
	}

	

	//Implementamos un método para desactivar categorías
	public function desactivar($idespecies)
	{
		$sql="UPDATE especies SET condicion='0' WHERE idespecies='$idespecies'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar categorías
	public function activar($idespecies)
	{
		$sql="UPDATE especies SET condicion='1' WHERE idespecies='$idespecies'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT especies.idespecies,especies.codigo,especies.nombre,especies.descripcion,especies.imagen,especies.condicion,especies.stock,especies.hasta,especies.desde,categorias_especies.nombre as categoria FROM `especies` INNER JOIN categorias_especies ON especies.categorias_especies_idcategorias_especies=categorias_especies.idcategorias_especies";
		return ejecutarConsulta($sql);		
	}

	public function select()
	{
		$sql="SELECT * FROM especies";
		return ejecutarConsulta($sql);		
	}

	public function listarActivos()
	{
		$sql="SELECT especies.*,categorias_especies.nombre as 'categoria' FROM `especies` INNER JOIN categorias_especies ON categorias_especies.idcategorias_especies=especies.categorias_especies_idcategorias_especies WHERE especies.condicion=1";
		return ejecutarConsulta($sql);		
	}

	
}

?>
