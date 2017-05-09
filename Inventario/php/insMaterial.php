<?php
// Recuperamos datos del formulario
$nom=$_POST['nombre'];
$pro=$_POST['proveedor'];
$marca=$_POST['marca'];
$mode=$_POST['modelo'];
$nSer=$_POST['nSerie'];
$cant=$_POST['cantidad'];
$fEnt=$_POST['fechaEntrada'];
$auto=$_POST['autorizador'];
$ubi=$_POST['ubicacion'];
$nInt=$_POST['nInterno'];
$gara=$_POST['garantia'];
$fBa=$_POST['fechaBaja'];
$esta=$_POST['estado'];
$obs=$_POST['observacion'];
// conectamos con la BD
include("conexion.php");
// creamos consulta
$sql="INSERT INTO materiales(nombre,idProveedor,idMarca,modelo,numeroSerie,cantidad, fechaEntrada, idAutorizador, idUbicacion, numeroInterno, garantia, fechaBaja, estado, observaciones)
 VALUES ('$nom',$pro,$marca, '$mode','$nSer',$cant,'$fEnt',$auto,$ubi,'$nInt',$gara,'$fBa','$esta','$obs')";
// ejecutamos la consulta
mysqli_query($conexion,$sql) or die("Error en la consulta de inserci�n $sql");
// cerramos la conexion
mysqli_close($conexion);
// redirigimos a la pagina inicial
header("location:../html/material.php");
?>
