<!DOCTYPE html>
<html>
  <head>
    <title>Gestión de usuarios</title>
    <meta charset="UTF-8">
    <script language="javascript">
      function meterUsuario()
      {
        // console.log(document.getElementById('insertarUsuarios').style.opacity);
        if (document.getElementById('insertarUsuarios').style.opacity == 0)
        {
          document.getElementById('verUsuarios').style.opacity = "0.1";
          document.getElementById('insertarUsuarios').style.opacity = "1";
          // document.getElementById('insertarUsuarios').style.zIndex = "1";
        } else {
          document.getElementById('verUsuarios').style.opacity = "1";
          document.getElementById('insertarUsuarios').style.opacity = "0";
        }
      }
    </script>
  </head>
  <body>
  <img src="./img/add_usuario.png" alt="Meter usuario" id="add_usuario" width="50" onclick="meterUsuario()"> Insertar usuario <br/>
    <?php
      include("conexion.php");
      $usuarios = "SELECT * FROM Usuarios";
      $registros = mysqli_query($conexion,$usuarios) or die("Error en la consulta de inserción $usuarios");
    ?>
    <div id="insertarUsuarios" style="opacity:0;">
      <p>Oculto al principio</p>
    </div>
    <div id="verUsuarios">
    <table>
      <tr>
        <th>Nombre</th>
        <th>Apellidos</th>
        <th>Rol</th>
        <th>Usuario</th>
        <th>Contraseña</th>
        <th>Modificar Usuario</th>
        <th>Eliminar Usuario</th>
      </tr>
      <?php
        while($linea=mysqli_fetch_array($registros))
        {
          $botonBorrar = "<img src='./img/usuario_borrar.png' onclick='borrarUsuario()' width='25'>";
          $botonEditar = "<img src='./img/usuario_editar.png' onclick='editarUsuario()' width='25'>";
          $nombre = $linea['nombre'];
          $apellidos = $linea['apellidos'];
          $rol = $linea['rol'];
          $usuario = $linea['username'];
          $password = $linea['password'];
          echo"<tr><td>$nombre</td><td>$apellidos</td><td>$rol</td><td>$usuario</td><td>$password</td><td>$botonEditar</td><td>$botonBorrar</td></tr>";
        }
        mysqli_close($conexion);
      ?>
      </table>
    </div>
  </body>
</html>