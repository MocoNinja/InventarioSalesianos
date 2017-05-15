<!DOCTYPE html>
<html>
  <head>
    <title>Gestionar Incidencias</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
  <body>
    <form name="informe" id="informe" method="post" action="insusuarios.php">
      <table align="center" width="50%">
      <tr>
        <td colspan="2"><img src="./imagenes/logosz.jpg" width="500"></td>
      </tr>
      <tr><td colspan="2" align="center"><h2>USUARIOS</h2></td></tr>
    <?php
      include("conexion.php");
      $materialesDisponibles = "SELECT idMaterial, nombre FROM Materiales";
      $registros = mysqli_query($conexion,$materialesDisponibles) or die("Error en la consulta de inserción $materialesDisponibles");
      echo "<select name='materiales' id='materiales'>";
      while($linea=mysqli_fetch_array($registros))
      {
        echo"<option value='$linea[idMaterial]'>$linea[nombre]";
      }
      echo "</select>";
      mysqli_close($conexion);?>

     </form>
 
 
  </body>
</html>