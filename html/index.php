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
        <td colspan="2"><img src="./BBDD/imagenes/logosz.jpg" width="500"></td>
      </tr>
      <tr>
        <td>Dni: </td>
        <td><input type="text" name="dni" id="dni" placeholder="dni">
      </tr>
      <tr>
        <td>Clave:</td>
        <td><input type="password" name="clave" id="clave" placeholder="Clave"></td>
      </tr>
      <tr>
        <td colspan="2" align="center">
          <input type="submit" value="Entrar">
        </td>
      </tr>
      </table>
</form>
  </body>
</html>