<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Inventario Salesianos</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
  <body>
    <?php
    include("conexion.php");
    if (empty($_SESSION['rol'])) header("location:index.php"); // No listillos allowed
    $nombre = $_SESSION['nombre'];
    $rol = $_SESSION['rol'];
    $username = $_SESSION['username'];
    echo "Bienvenido $nombre. Login: $username. Rol: $rol";
    echo "<br/>";
    switch ($rol)
    {
        case 'administrador':
            echo "<p><a>Gestionar usuarios</a></p>";
            echo "<p><a>Gestionar materiales</a></p>";
            echo "<p><a>Gestionar incidencias</a></p>";
            break;
        case 'sat':
            echo "<p><a>Gestionar materiales</a></p>";
            echo "<p><a>Gestionar incidencias</a></p>";
            break;
        case 'profesor':
            echo "<p><a>Gestionar materiales</a></p>";
            echo "<p><a>Gestionar incidencias</a></p>";
            break;

    }
    ?>
    <br/>
<input type="button" value="Cerrar sesión actual" onclick="window.location.href='logout.php'"/>
</body>
</html>