<!DOCTYPE HTML>
<html>
	<head>
		<title>Base De Datos</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="import" href="headers.html">
	</head>
	<body class="is-loading">

		<!-- Wrapper -->
			<div id="wrapper">

				<!-- Main -->
						<section id="main">
						<header>
							<h1>Base de datos</h1>
						</header>
						<hr />
							<!--AULA-->
								<?php
									include("conexion.php");
								//creamos la consulta
								$sql="SELECT ub.nombre FROM ubicaciones as ub, materiales as ma, tipomaterial as ti WHERE ub.idUbicacion=ma.idUbicacion AND ma.tipoMaterial=ti.idTipoMaterial";
								$sql2="SELECT ti.nombre FROM  ubicaciones as ub, materiales as ma, tipomaterial as ti  WHERE ub.idUbicacion=ma.idUbicacion AND ma.tipoMaterial=ti.idTipoMaterial";
								$sql3="SELECT ma.nombre FROM ubicaciones as ub, materiales as ma, tipomaterial as ti WHERE ub.idUbicacion=ma.idUbicacion AND ma.tipoMaterial=ti.idTipoMaterial";
								//ejecutamos la consulta
								$registros=mysqli_query($conexion,$sql);
								$registros2=mysqli_query($conexion,$sql2);
								$registros3=mysqli_query($conexion,$sql3);
								//leemos el contenido de $registros
								$linea=mysqli_fetch_array($registros);
								$linea2=mysqli_fetch_array($registros2);
								$linea3=mysqli_fetch_array($registros3);
								{
								echo "<ul class='cd-accordion-menu'>
									<li class='has-children'>
										<input type='checkbox' name ='group-1' id='group-1' checked='none'>
										<label for='group-1'>$linea[nombre]</label>

											<ul>
												<li class='has-children'>
												<input type='checkbox' name ='sub-group-1' id='sub-group-1' checked='none'><label for='sub-group-1'>$linea2[nombre]</label>
													<ul>
														<li>$linea3[nombre]</li>
													</ul>
												</li>
											</ul>
										</li>
									</ul>";

									
							}
							mysqli_close($conexion);
							?>
					
						<hr />
						
						<footer>
							<ul class="icons">
								<li><a href="#" class="fa-twitter">Twitter</a></li>
								<li><a href="#" class="fa-instagram">Instagram</a></li>
								<li><a href="#" class="fa-facebook">Facebook</a></li>
							</ul>
						</footer>
					</section>

				<!-- Footer -->
					<footer id="footer">
						<ul class="copyright">
						</ul>
					</footer>

			</div>

		
			<script>
				if ('addEventListener' in window) {
					window.addEventListener('load', function() { document.body.className = document.body.className.replace(/\bis-loading\b/, ''); });
					document.body.className += (navigator.userAgent.match(/(MSIE|rv:11\.0)/) ? ' is-ie' : '');
				}
			</script>

	</body>
</html>