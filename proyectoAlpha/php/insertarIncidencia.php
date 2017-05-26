<?php
// Recuperamos datos del formulario
$inci=$_POST['nombre'];
$fecIn=$_POST['proveedor'];
$fecSo=$_POST['marca'];
$solu=$_POST['modelo'];
$idMa=$_POST['nSerie'];

// conectamos con la BD
include("conexion.php");
// creamos consulta
$sql="INSERT INTO Incidencias(incidencia,fechaIncidencia,fechaSolucion,solucion,idMaterial)
 VALUES ('$inci',$fecIn,$fecSo, '$solu',$idMa)";
 // ejecutamos la consulta
mysqli_query($conexion,$sql) or die("Error en la consulta de insercion $sql");

// cerramos la conexion
mysqli_close($conexion);
// redirigimos a la pagina inicial
header("location:../html/incidencia.php");
?>