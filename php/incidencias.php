<!DOCTYPE html>
<html>
  <head>
    <title>Gestionar Incidencias</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
  <body>
  <h2>Inserte su incidencia</h2>
    <form name="informe" id="informe" method="post" action="insincidencia.php">
      <table>
      <tr><th>Material Afectado</th><th>Fecha de incidencia</th><th>Descripción de la incidencia</th></tr>
      <tr>
        <td>
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
        mysqli_close($conexion);
        ?>
        </td>
        <td>
        <input type="date" name="fecha" value="fecha">
        </td>
        <td><input type="text" name="problema" value="problema"></td>
      </tr>
      </table>

     </form>
 
 
  </body>
</html>