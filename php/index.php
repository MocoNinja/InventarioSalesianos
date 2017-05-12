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
      $estado = $_SESSION['fallo'];
      if ($estado == "pass") $mensaje = "Contraseña incorrecta";
      else if ($estado == "user") $mensaje = "Usuario no encontrado";
      else $mensaje = "";
      echo "<p>$mensaje</p>";
    ?>
    <form name="usuarios" id="usuarios" method="post" action="login.php">
      <table>
      <tr>
        <td colspan="2"><img src="./img/logosz.jpg" width="500" height= "350"></td>
      </tr>
      <tr>
        <td>Nombre de usuario: </td>
        <td><input type="text" name="username" id="username" placeholder="user">
      </tr>
      <tr>
        <td>Contraseña:</td>
        <td><input type="password" name="password" id="password" placeholder="password"></td>
      </tr>
      <tr>
        <td>
          <input type="submit" value="Login">
        </td>
      </tr>
      </table>
    </form>
  </body>
</html>