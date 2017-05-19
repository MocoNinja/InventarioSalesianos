<!DOCTYPE html>
<html>
  <head>
    <title>Gestión de usuarios</title>
    <meta charset="UTF-8">
    <script language="javascript">
      function meterIncidencia()
      {
        // console.log(document.getElementById('insertarIncidencias').style.opacity);
        if (document.getElementById('insertarIncidencias').style.opacity == 0)
        {
          document.getElementById('verIncidencias').style.opacity = "0.1";
          document.getElementById('insertarIncidencias').style.opacity = "1";
          // document.getElementById('insertarIncidencias').style.zIndex = "1";
        } else {
          document.getElementById('verIncidencias').style.opacity = "1";
          document.getElementById('insertarIncidencias').style.opacity = "0";
        }
      }
    </script>
  </head>
  <body>
  <img src="./img/report_management.png" alt="Meter usuario" id="add_report" width="50" onclick="meterIncidencia()"> Insertar incidencia <br/>
    <?php
      include("conexion.php");
      $incidencias = "SELECT * FROM Incidencias";
      $registros = mysqli_query($conexion,$incidencias) or die("Error en la consulta de inserción $incidencias");
    ?>
    <div id="insertarIncidencias" style="opacity:0;">
     <form name="usuario" id="usuario" method="post" action="insertarUsuario.php">
      <table>
        <tr>
          <td>Nombre Incidencia</td>
          <td><input type="text" name="incidencia" id="incidencia"></td>
        </tr>
        <tr>
          <td>Fecha Incidencia</td>
          <td><input type="date" name="fechaIncidencia" id="fechaIncidencia"></td>
        </tr>
        <?php
          if ($_SESSION['rol'] != "profesor"){
            echo '        <tr>
          <td>Solución Incidencia</td>
          <td><input type="text" name="solucion" id="solucion"></td>
        </tr>
        <tr>
          <td>Fecha Solucion</td>
          <td><input type="date" name="fechaSolucion" id="fechaSolucion"></td>
        </tr>';
          }
        ?>
        <tr>
          <td colspan="2"><input type="submit" name="enviar" id="enviar"></td>
        </tr>
      </table>
  </form>  
    </div>
    <div id="verIncidencias">
    <table>
      <tr>
        <th>Nombre de la incidencia</th>
        <th>Fecha Incidencia</th>
        <?php
          if ($_SESSION['rol'] != "profesor"){
            echo '        <tr>
          <td>Solución Incidencia</td>
          <td>Fecha Solucion</td>';
          }
        ?>
      </tr>
      <?php
        while($linea=mysqli_fetch_array($registros))
        {
          $botonBorrar = "<img src='./img/report_kill.png' onclick='borrarUsuario()' width='25'>";
          $incidencia = $linea['incidencia'];
          $fechaIncidencia = $linea['fechaIncidencia'];
          if ($_SESSION['rol'] != "profesor"){
          $solucion = $linea['solucion'];
          $fechaSolucion = $linea['fechaSolucion'];
          }
        }
        echo"<tr><td>$incidencia</td><td>$fechaIncidencia</td>";
        if ($_SESSION['rol'] != "profesor"){
          echo "<td>$solucion</td><td>$fechaSolucion</td>";
        }
         echo " <td>$botonBorrar</td></tr>";
        ?>
          
        }
        <?php
        mysqli_close($conexion);
      ?>
      </table>
    </div>
  </body>
</html>