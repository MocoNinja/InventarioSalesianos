<!DOCTYPE HTML PUBLIC "-//W3C//Ddiv HTML 4.0 divansitional//EN">
<HTML>
<HEAD>
	<TITLE> formalumnos.php </TITLE>
	<META NAME="Generator" CONTENT="EditPlus">
	<META NAME="Author" CONTENT="">
	<META NAME="Keywords" CONTENT="">
	<META NAME="Description" CONTENT="">
	<META charset="UTF-8">

	<link href="../css/bootstrap.css"></link>
	<link href="../css/bootstrap.min.css"></link>
	<link href="../css/bootstrap-theme.css"></link>
	<link href="../css/bootstrap-theme.min.css"></link>
</HEAD>
<BODY>
	<?php
	include("../php/conexion.php");
	?>
	<form name="material" id="material" method="post" action="../php/insMaterial.php">
		<div class="col-md-9">
		<div>
			<div>Nombre </div>
			<div><input type="text" name="nombre" id="nombre" placeholder="Nombre"></div>
		</div>
		<div>
			<div>Proveedor</div>
			<div><select name="proveedor" id="proveedor">
						<option value="">
							<?php
								$sql="SELECT * FROM proveedores";
								$regisdivos=mysqli_query($conexion,$sql);
								while($linea=mysqli_fetch_array($regisdivos))
								{
									echo"<option value='$linea[idProveedor]'>$linea[nombre]";
								}
							?>
					</select>
			</div>
		</div>
		<div>
			<div>Marca</div>
			<div><select name="marca" id="marca">
						<option value="">
							<?php
								$sql="SELECT * FROM marcas";
								$regisdivos=mysqli_query($conexion,$sql);
								while($linea=mysqli_fetch_array($regisdivos))
								{
									echo"<option value='$linea[idMarca]'>$linea[Nombre]";
								}
							?>
					</select>
			</div>
		</div>
		<div>
			<div>Modelo</div>
			<div><input type="text" name="modelo" id="modelo" placeholder="Modelo"></div>
		</div>
		<div>
			<div>Número de serie </div>
			<div><input type="text" name="nSerie" id="nSerie" placeholder="Número de serie"></div>
		</div>
		<div>
			<div>Cantidad </div>
			<div><input type="number" name="cantidad" id="cantidad" placeholder="Cantidad"></div>
		</div>
		<div>
			<div>Fecha de entrada</div>
			<div><input type="date" name="fechaEntrada" id="fechaEntrada"></div>
		</div>
		<div>
			<div>Autorizado por:</div>
			<div><div><select name="autorizador" id="autorizador">
						<option value="">
							<?php
								$sql="SELECT * FROM usuarios";
								$regisdivos=mysqli_query($conexion,$sql);
								while($linea=mysqli_fetch_array($regisdivos))
								{
									echo"<option value='$linea[idUsuario]'>$linea[nombre] $linea[apellidos]";
								}
							?>
					</select>
			</div>
		</div>
		<div>
			<div>Ubicación</div>
			<div><select name="ubicacion" id="ubicacion">
						<option value="">
							<?php
								$sql="SELECT * FROM ubicaciones";
								$regisdivos=mysqli_query($conexion,$sql);
								while($linea=mysqli_fetch_array($regisdivos))
								{
									echo"<option value='$linea[idUbicacion]'>$linea[nombre]";
								}
							?>
					</select>
			</div>
		</div>
		<div>
			<div>Número interno</div>
			<div><input type="text" name="nInterno" id="nInterno" placeholder="Número interno"></div>
		</div>
		<div>
			<div>Garantía</div>
			<div><select name="garantia" id="garantia">
						<option value="0">No
						<option value='1'>Si
					</select>
		</div>
		<div>
			<div>Fecha de baja</div>
			<div><input type="date" name="fechaBaja" id="fechaBaja"></div>
		</div>
		<div>
			<div>Estado</div>
			<div><input type="text" name="estado" id="estado" placeholder="Estado"></div>
		</div>
		<div>
			<div>Observaciones</div>
			<div><textarea rows="4" cols="50" name="observacion" id="observacion">Observaciones</textarea></div>
		</div>
		<div>
			<div colspan="2" align="center">
				<input type="submit"class="btn btn-lg btn-ionic-green" value="Enviar">
			</div>
		</div>
	</div>
	</form>
</BODY>
</HTML>
