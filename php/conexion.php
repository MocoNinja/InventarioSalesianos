<?php
  session_start();
  if ($_SESSION["fallo"] == "") $_SESSION["fallo"] = "ninguno";
  $conexion = mysqli_connect("localhost", "root", "", "inventario") or die("Error en la conexión de base de datos");
?>