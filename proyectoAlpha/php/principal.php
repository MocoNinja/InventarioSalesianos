<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Inventario Salesianos</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="import" href="headers.html">
  </head>
  <body>
    <?php
    include("conexion.php");
    if (empty($_SESSION['rol'])) header("location:index.php"); // No listillos allowed
    $nombre = $_SESSION['nombre'];
    $rol = $_SESSION['rol'];
    $username = $_SESSION['username'];
    echo "Bienvenido $nombre. Login: $username. Rol: $rol";
    $botonLogout = "<a href='logout.php'> <img width='50' src='./img/logout.png'></a>Cerrar sesión";
    echo $botonLogout;
    echo "<br/>";
    switch ($rol)
    {
        case 'administrador':
            $columna1 = "<a href='usuarios.php'> <img width='150' src='./img/user_management.png'></a><br/>Gestionar usuarios";
            $columna2 =  "<a href='materiales.php'> <img width='150' src='./img/materials_management.png'></a><br/>Gestionar materiales";
            $columna3 = "<a href='incidencias.php'> <img width='150' src='./img/report_management.png'></a><br/>Gestionar incidencias";
            echo "<table><tr><td>$columna1</td><td>$columna2</td><td>$columna3</td></tr></table>";
            break;
        case 'sat':
            $columna2 =  "<a href='materiales.php'> <img width='150' src='./img/materials_management.png'></a><br/>Gestionar materiales";
            $columna3 = "<a href='incidencias.php'> <img width='150' src='./img/report_management.png'></a><br/>Gestionar incidencias";
            echo "<table>00<tr><td>$columna2</td><td>$columna3</td></tr></table>";
            break;
        case 'profesor':
            $columna2 =  "<a href='materiales.php'> <img width='150' src='./img/materials_management.png'></a><br/>Gestionar materiales";
            $columna3 = "<a href='incidencias.php'> <img width='150' src='./img/report_management.png'></a><br/>Gestionar incidencias";
            echo "<table>00<tr><td>$columna2</td><td>$columna3</td></tr></table>";
            break;

    }
    ?>
    <br/>

</body>
</html>