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
    ?>
    <form name="usuarios" id="usuarios" method="post" action="login.php">
      <table>
      <tr>
        <td colspan="2"><img src="./img/logosz.jpg"></td>
      </tr>
      <tr>
        <td>Nombre de usuario: </td>
        <td><input type="text" name="user" id="user" placeholder="user">
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