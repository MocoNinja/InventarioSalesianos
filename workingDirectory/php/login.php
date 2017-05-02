<?php
  session_start();
  $nombreusuario = $_POST['nombreLogin'];
  $password = $_POST['password'];
  include("./php/conexion.php");

  $sqlUsuario = "SELECT usuarios.* FROM usuarios WHERE usuarios.nombreLogin = $nombreusuario";
  $registros=mysqli_query($conexion,$sqlUsuario);
  $total=mysqli_num_rows($registros);
  if($total==0)
  {
  	$_SESSION['fallo'] = 'usuarioNoExiste';
    header("location:fail.php");
  }
  else
  {
  	while($linea=mysqli_fetch_array($registros))
  	{
  		if($linea['password']==$password)
  		{
  			$_SESSION['nombre']=$linea['nombre'];
  			$_SESSION['nombreLogin']=$linea['nombreLogin'];
        // ROLES POSIBLES
        // profesor: consultar y generar averías
        // sat: consultar material y modificar averías
        // encargado: hacer todo y acceder a todo
  			$_SESSION['rolUsuario'] = $linea['rol'];
        header("location:panel.php");
  		}
  		else
  		{
        $_SESSION['fallo'] = 'passwordInvalida';
        header("location:fail.php");
  		}
    }
  }
?>
