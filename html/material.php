<!DOCTYPE HTML PUBLIC "-//W3C//Ddiv HTML 4.0 divansitional//EN">
<HTML>
<HEAD>
	<TITLE> formalumnos.php </TITLE>
	<META NAME="Generator" CONTENT="EditPlus">
	<META NAME="Author" CONTENT="">
	<META NAME="Keywords" CONTENT="">
	<META NAME="Description" CONTENT="">
	<META charset="UTF-8">
	<style type="text/css">
		#formOrdenador, #formImpresora, #formMonitor, #formSoftware, #formMateriales{
			display: none;
			margin-left: 50px;
		}
	</style>
	<link rel="import" href="headers.html">
	
	<script language="JavaScript">
		function formOrdenadorShow(){
			document.getElementById('formOrdenador').style.display= 'table-row';
			document.getElementById('formImpresora').style.display= 'none';
			document.getElementById('formMonitor').style.display= 'none';
		}
		function formImpresoraShow(){
			document.getElementById('formOrdenador').style.display= 'none';
			document.getElementById('formImpresora').style.display= 'table-row';
			document.getElementById('formMonitor').style.display= 'none';
		}
		function formMonitorShow(){
			document.getElementById('formOrdenador').style.display= 'none';
			document.getElementById('formImpresora').style.display= 'none';
			document.getElementById('formMonitor').style.display= 'table-row';
		}
		function formTipoNinguno(){
			document.getElementById('formOrdenador').style.display= 'none';
			document.getElementById('formImpresora').style.display= 'none';
			document.getElementById('formMonitor').style.display= 'none';
		}
		function formSoftwareShow(){
			document.getElementById('formOrdenador').style.display= 'none';
			document.getElementById('formImpresora').style.display= 'none';
			document.getElementById('formMonitor').style.display= 'none';
		}
		$(document).ready(function(){
		    $("#nuevoMaterial").click(function(){
		        $("#formMateriales").fadeIn()
		    });
		});
	</script>
</HEAD>
<BODY>
	<?php
	include("../php/conexion.php");
	?>
	<button id="nuevoMaterial" class="btn1">+ Nuevo</button>
	<div id="formMateriales">
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
				<div><input type="checkbox" checked data-toggle="toggle">
				<!--<select name="garantia" id="garantia">
							<option value="0">No
							<option value='1'>Si
						</select>-->
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
				<div >Tipo Material</div>
				<div>
					<input type="radio"g name="tipoMaterial" value="ninguna" checked onclick="formTipoNinguno()"> Ninguna
					<input type="radio" name="tipoMaterial" value="ordenador" onclick="formOrdenadorShow()"> Ordenador
					<input type="radio" name="tipoMaterial" value="impresora" onclick="formImpresoraShow()"> Impresora
					<input type="radio" name="tipoMaterial" value="monitor" onclick="formMonitorShow()"> Monitor
				</div>
			</div><br>
			<div id="formOrdenador">
				<div>
					<div>Placa</div>
					<div><input type="text" name="placa" id="placa" placeholder="Placa"></div>
				</div>
				<div>
					<div>Procesador</div>
					<div><input type="text" name="procesador" id="procesador" placeholder="Procesador"></div>
				</div>
				<div>
					<div>Memoria RAM</div>
					<div><input type="text" name="ram" id="ram" placeholder="Memoria RAM"></div>
				</div>
				<div>
					<div>Disco duro</div>
					<div><input type="text" name="discoDuro" id="discoDuro" placeholder="Disco duro"></div>
				</div>
				<div>
					<div>Tarjetas</div>
					<div><input type="text" name="tarjeta" id="tarjeta" placeholder="Tarjeta"></div>
				</div>
				<div>
					<div>Identificador</div>
					<div><input type="text" name="identificador" id="identificador" placeholder="Identificador"></div>
				</div>
				<div>
					<div>Dominio</div>
					<div><input type="text" name="dominio" id="dominio" placeholder="Dominio"></div>
				</div>
			</div>
			

			<div id="formImpresora">
				<div>
					<div>Tipo de Impresora</div>
					<div><input type="text" name="tipoImpresora" id="tipoImpresora" placeholder="Tipo Impresora"></div>
				</div>
				<div>
					<div>Combustible</div>
					<div><input type="text" name="consumible" id="consumible" placeholder="consumible"></div>
				</div>
			</div>

			<div id="formMonitor">
				<div>
					<div>Tamaño</div>
					<div><input type="text" name="tamanyo" id="tamanyo" placeholder="Tamaño"></div>
				</div>
				<div>
					<div>Tipo de monitor</div>
					<div><input type="text" name="tipoMonitor" id="tipoMonitor" placeholder="Tipo de monitor"></div>
				</div>
			</div>
			<div>
				<div colspan="2" align="center">
					<input type="submit" class="btn btn-lg btn-ionic-green btn-success" value="Enviar">
				</div>
			</div>
		</div>
		</form>
	</div>
</BODY>
</HTML>
