<section class="bg_menu_page" style="background-image: url(<?= base_url(); ?>public/template/images/fondo-nosotros.jpg);background-size: cover;width: 100%;height: 200px;display: table;background-repeat: no-repeat;background-position: left bottom;">
	<div class="inner_subpage_banner">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="text-banner">
						<h1>Mi cuenta</h1>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="miga">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<p><a href="<?= base_url(); ?>">Inicio</a> <span>></span> Mi cuenta</p>
			</div>
		</div>
	</div>
</section>

<section class="bloque-cuenta" id="main-container col2-right-layout">
	<div class="main container">
		<div class="row">

			<aside class="mostrar-movil sidebar col-md-3 col-sm-12 col-xs-12">
				<div class="sidebar-checkout block">

					<div class="block-content">
						<dl>
							<dt>
								<div class="sidebar-bar-title">
									<h3>Mi cuenta</h3>
								</div>
							</dt>
							<dt class="complete">
								<h4 class="nombreUsu"><?= $usuario->nombres . ' ' . $usuario->papellido . ' ' . $usuario->sapellido ?: "" ?></h4>
							</dt>
							<dt class="complete"><a href="<?= base_url('mi-cuenta'); ?>" class="activo cuenta">Mi Cuenta</a></dt>


							<dt class="complete"><a href="<?= base_url('mis-pedidos'); ?>" class="cuenta-pedido">Mis pedidos</a></dt>
							<dt class="complete"><a class="cuenta-sesion" style="cursor: pointer;" onclick="cerrarSesion()">Cerrar sesión</a></dt>
						</dl>
					</div>

				</div>
			</aside>


			<div class="col-main col-md-9 col-sm-12 col-xs-12">
				<div class="page-content checkout-page">
					<div class="page-title">
						<h2 class="irAqui" style="display: inline-block;">EDITAR MIS DATOS</h2>
						<div id="mensaje" class="mensajeCaja" style="display: none">
							<h4 class="title"><i class="fa-solid fa-warning"></i> Errores encontrados:</h4>
							<ul></ul>
						</div>
					</div>

					<div class="my-account checkout-page">

						<form id="formModifica" method="post">
							<div class="row">
								<h4 class="checkout-sep">Datos de Acceso</h4>

								<div class="col-sm-6">
									<input type="hidden" name="idusuario" id="idusuario" value="1">
									<label>Usuario</label>
									<input disabled type="input" class="form-control" id="usuario" name="usuario" value="<?= $usuario->documento ?>">
								</div>
								<div class="col-sm-6">
									<label>Perfil del usuario</label>
									<input type="input" class="form-control" id="rol" name="rol" value="<?= $usuario->perfil->nombre ?>" disabled>
								</div>
								<div style="clear:both;"></div>

								<div class="col-sm-6" style="margin-bottom: 25px;">
									<label>Fecha de registro</label>
									<input type="input" class="form-control" name="fecha" id="fecha" value="<?= date('d-m-Y', strtotime($usuario->fecha))  ?>" disabled>
								</div>

								<h4 class="checkout-sep">Datos Personales</h4>

								<div class="col-sm-6">
									<label>Tipo de documento *</label>
									<select name="ptipodocumento" disabled id="ptipodocumento" class="form-control form-select">
										<? if ($pdocumentos):
											foreach ($pdocumentos as $pdocumento): ?>
												<option <?= $pdocumento->idparametro == $usuario->idpdocumento ? 'selected' : '' ?> value="<?= $pdocumento->idparametro ?>"><?= $pdocumento->nombre ?></option>
										<? endforeach;
										endif ?>

									</select>
									<div class="validacion ptipodocumento"></div>
								</div>

								<div class="col-sm-6">
									<label>Número de documento *</label>
									<input disabled type="input" class="form-control" id="documento" name="documento" value="<?= $usuario->documento ?>">
									<div id="documento" class="validacion documento"></div>
								</div>

								<div class="col-sm-6">
									<label>Nombres *</label>
									<input type="input" class="form-control" id="nombres" name="nombres" value="<?= $usuario->nombres ?>">
									<div class="validacion nombres"></div>
								</div>

								<div class="col-sm-6">
									<label>Apellido paterno *</label>
									<input type="input" class="form-control" id="pApellido" name="pApellido" value="<?= $usuario->papellido ?>">
									<div class="validacion pApellido"></div>

								</div>

								<div class="col-sm-6">
									<label>Apellido materno </label>
									<input type="input" class="form-control" id="sApellido" name="sApellido" value="<?= $usuario->sapellido ?>">
								</div>

								<div class="col-sm-6">
									<label>Sexo *</label>
									<select name="sexo" id="sexo" class="form-control form-select">
										<option <?= $usuario->sexo == "" ? 'selected' : '' ?> value="">Seleccione</option>
										<option <?= $usuario->sexo == "F" ? 'selected' : '' ?> value="F">Femenino</option>
										<option <?= $usuario->sexo == "M" ? 'selected' : '' ?> value="M">Masculino</option>
									</select>
									<div class="validacion sexo"></div>
								</div>

								<div class="col-sm-6">
									<label>Fecha de nacimiento </label>
									<input type="date" class="form-control" id="fechaNacimiento" placeholder="Fecha de Nacimiento (DD/MM/AAAA)" value="<?= $usuario->fechanacimiento ?>" name="fechaNacimiento">
									<div class="validacion fechaNacimiento"></div>
								</div>
								<div class="col-sm-6">
									<label>Correo electrónico *</label>
									<input id="correo" name="correo" disabled class="form-control" type="email" value="<?= $usuario->correo ?>">
									<div class="validacion correo"></div>
								</div>
								<div class="col-sm-6">
									<label>Teléfono *</label>
									<input id="telefono" name="telefono" class="form-control" type="text" value="<?= $usuario->telefono ?>">
									<div class="validacion telefono"></div>
								</div>
								<div class="col-sm-6">
									<label>Recibir boletin</label>
									<select name="boletin" id="boletin" class="form-control form-select">
										<option <?= $usuario->boletin == 0 ? 'selected' : '' ?> value="0">No</option>
										<option <?= $usuario->boletin == 1 ? 'selected' : '' ?> value="1">Si</option>
									</select>
								</div>

								<div class="col-sm-12">
									<button style="margin-top: 20px" type="submit" name="guardar" class="button">
										<i class="fa-solid fa-lock"></i> &nbsp; <span>Guardar</span>
									</button>
									<a href="<?= base_url() ?>mi-cuenta" class="regresar"><i class="fa-solid fa-undo" aria-hidden="true"></i> Regresar</a>
									<div style="clear:both;"></div>
								</div>
							</div>
						</form>

					</div>

				</div>
			</div>

		</div>
	</div>
</section>


<script>
	document.getElementById('formModifica').addEventListener('submit', function(e) {
		e.preventDefault();

		// Scroll suave al formulario
		window.scrollTo({
			top: this.offsetTop,
			behavior: 'smooth'
		});

		// Mostrar carga (suponiendo que es un elemento con clase .carga)
		const carga = document.querySelector('.carga');
		if (carga) carga.style.display = 'block';

		// Construir FormData del formulario
		const formData = new FormData(this);

		fetch(`${BASE_URL}api/UsuarioController/usuarioEditar`, {
				method: 'POST',
				body: formData,
			})
			.then(response => response.json())
			.then(res => {
				removerClases();
				if (res.status === 'exito') {
					Swal.fire({
						title: 'Editar mis datos!',
						text: 'Sus datos se actualizaron exitosamente.',
						icon: 'success',
						confirmButtonText: 'Aceptar'
					}).then((result) => {
						window.location.href = `${BASE_URL}mi-cuenta`;
					});
				} else {
					showErrores(res.errors);
				}
				if (carga) carga.style.display = 'none';
			})
			.catch(err => {
				removerClases();
				if (carga) carga.style.display = 'none';
				Swal.fire({
					title: 'Editar mis datos!',
					text: 'Errores encontrados. Verifique y complete la información requerida',
					icon: 'warning',
					confirmButtonText: 'Continuar'
				}).then(() => {
					location.reload();
				});
			});
	});
</script>