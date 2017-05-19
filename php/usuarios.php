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
     <form name="usuario" id="usuario" method="post" action="insertarUsuario.php">
      <table>
        <tr>
          <td>Nombre</td>
          <td><input type="text" name="nombre" id="nombre"></td>
        </tr>
        <tr>
          <td>Apellidos</td>
          <td><input type="text" name="apellidos" id="apellidos"></td>
        </tr>
        <tr>
          <td>Rol</td>
          <td><select name="rol" id="rol">
            <option value="administrador">Administrador</option>
            <option value="sat">SAT</option>
            <option value="Profesor">Profesor</option>
            </select>
          </td>
        </tr>
        <tr>
          <td>Username</td>
          <td><input type="text" name="username" id="username"></td>
        </tr>
        <tr>
          <td>Contraseña</td>
          <td><input type="text" name="password" id="password"></td>
        </tr>
        <tr>
          <td colspan="2"><input type="submit" name="enviar" id="enviar"></td>
        </tr>
      </table>
  </form>  
    </div>
    <div id="verUsuarios">
    <table>
      <tr>
        <th>Nombre</th>
        <th>Apellidos</th>
        <th>Rol</th>
        <th>Usuario</th>
        <th>Contraseña</th>
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
          echo"<tr><td>$nombre</td><td>$apellidos</td><td>$rol</td><td>$usuario</td><td>$password</td>
          <td>$botonBorrar</td></tr>";
        }
        mysqli_close($conexion);
      ?>
      </table>
    </div>
  </body>
</html>