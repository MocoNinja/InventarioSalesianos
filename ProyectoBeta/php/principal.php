<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Inventario Salesianos</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="import" href="headers.html">
  </head>
<body class="is-loading">

        <!-- Wrapper -->
            <div id="wrapper">

                <!-- Main -->
                    <section id="main">
                        <header>
                            <h2>Menu Principal</h2>
                        </header>
                        <hr />
                        <?php
                            include("conexion.php");
                            
                            $nombre = $_SESSION['nombre'];
                            $rol = $_SESSION['rol'];
                            $username = $_SESSION['username'];
                            echo "Bienvenido $nombre. Login: $username. Rol: $rol";
                            $botonLogout = "<br><a href='logout.php'> <img width='25' src='./img/logout.png'></a>Cerrar sesión";
                            echo $botonLogout;
                            echo "<br/>";
                            switch ($rol)
                            {
                                case 'administrador':
                                    $columna1 = "<a href='usuarios.php'> <img width='150' src='./img/user_management.png'></a><br/>Gestionar usuarios";
                                    $columna2 =  "<a href='materiales.php'> <img width='150' src='./img/materials_management.png'></a><br/>Gestionar materiales";
                                    $columna3 =  "<a href='Desplegables.php'> <img width='150' src='./img/materials_view.png'></a><br/>Ver materiales";
                                    $columna4 = "<a href='incidencias.php'> <img width='150' src='./img/report_management.png'></a><br/>Gestionar incidencias";
                                    echo "<table><tr><td>$columna1</td><td>$columna2</td><td>$columna3</td><td>$columna4</td></tr></table>";
                                    break;
                                case 'sat':
                                    $columna2 =  "<a href='materiales.php'> <img width='150' src='./img/materials_management.png'></a><br/>Gestionar materiales";
                                 	$columna3 =  "<a href='Desplegables.php'> <img width='150' src='./img/materials_view.png'></a><br/>Ver materiales";
                                    $columna4 = "<a href='incidencias.php'> <img width='150' src='./img/report_management.png'></a><br/>Gestionar incidencias";
                                    echo "<table><tr><td>$columna2</td><td>$columna3</td><td>$columna4</td></tr></table>";
                                    break;
                                case 'profesor':
                                    $columna2 =  "<a href='Desplegables.php'> <img width='150' src='./img/materials_view.png'></a><br/>Ver materiales";
                                    $columna3 = "<a href='incidencias.php'> <img width='150' src='./img/report_management.png'></a><br/>Gestionar incidencias";
                                    echo "<table><tr><td>$columna2</td><td>$columna3</td></tr></table>";
                                    break;

                            }
                        ?>
                        <br/>
                        <footer>
                            <ul class="icons">
                            </ul>
                        </footer>
                    </section>

                <!-- Footer -->
                    <footer id="footer">
                    </footer>

            </div>

        <!-- Scripts -->
            <!--[if lte IE 8]><script src="assets/js/respond.min.js"></script><![endif]-->
            <script>
                if ('addEventListener' in window) {
                    window.addEventListener('load', function() { document.body.className = document.body.className.replace(/\bis-loading\b/, ''); });
                    document.body.className += (navigator.userAgent.match(/(MSIE|rv:11\.0)/) ? ' is-ie' : '');
                }
            </script>

    </body>
</html>