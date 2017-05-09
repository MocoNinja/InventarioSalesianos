<?php
session_start();

$username=$_POST['username'];
$password=$_POST['password'];
include("conexion.php");

$sql = "SELECT * FROM usuarios WHERE login ='$username'";
$registros = mysqli_query($conexion,$sql);
$total = mysqli_num_rows($registros);
if($total == 0)
{
	echo "El usuario introducido no existe en la base de datos. Pulse <a href='index.php'>aquí</a> para volver al menú de login.";
}
else
{
	while($linea = mysqli_fetch_array($registros))
	{
		if($linea['password'] == $password)
		{
			$_SESSION['username'] = $linea['username'];
			$_SESSION['nombre'] = $linea['nombre'];
			$_SESSION['rol'] = $linea['rol'];
			header("location:principal.php");
		}
		else
		{
			echo "Contraseña incorrecta. Pulse <a href='index.php'>aquí</a> para continuar.";
		}
	}
}
?>
