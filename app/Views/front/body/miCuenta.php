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


<section class="bloque-cuenta" id="main-container-fluid col2-right-layout">
	<div class="main container-fluid">
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

			<div class=" col-main col-md-9 col-sm-12 col-xs-12">


				<div class="row">

					<div class="col-md-12">
						<div class="d-flex">
							<h1>Mi cuenta</h1>
							<a style="cursor: pointer;" onclick="correoEditarMisDatos()" class="editar-datos ms-auto">EDITAR MIS DATOS</a>
						</div>
					</div>

					<div class="col-md-6 col-sm-12">
						<div class="cuadro-datos">
							<h5>Datos de acceso</h5>
							<div class="cuadro-acceso">
								<ul>
									<li>Usuario: <span class="usuario"><?= $usuario->correo ?></span></li>
									<li>Perfil: <span class="rol"><?= $usuario->perfil->nombre ?></span></li>
									<li>Fecha de registro: <span class="fechaRegistro"><?= date('d-m-Y', strtotime($usuario->fecha))  ?></span></li>
								</ul>
							</div>
						</div>

					</div>

					<div class="col-md-6 col-sm-12">
						<div class="cuadro-datos">
							<h5>Datos personales</h5>
							<div class="cuadro-acceso">
								<ul>
									<li>Nombres y apellidos: <span class="nombres"><?= $usuario->nombres . ' ' . $usuario->papellido . ' ' . $usuario->sapellido ?: '' ?></span></li>
									<li>DNI: <span class="dni"><?= $usuario->documento ?></span></li>
									<li>Sexo: <span class="sexo"><?= $usuario->sexo ?></span></li>
									<li>Fecha de nacimiento: <span class="fnacimiento"><?= date('d-m-Y', strtotime($usuario->fechanacimiento)) ?></span></li>
									<li>Correo: <span class="correo" style="display: inline-block; margin-top:auto"><?= $usuario->correo ?></span></li>
									<li>Teléfono: <span class="telefono"><?= $usuario->telefono ?></span></li>
								</ul>
							</div>
						</div>
					</div>


					<div class="col-md-12">
						<div class="cuadro-direccion">
							<h5>Direcciones de agencia</h5>
							<div class="col-md-12" id="destinos" style="padding: 0;">
								<? if ($agencias):
									foreach ($agencias as $agencia): ?>
										<div class="row cuadro-oscuro">
											<div class="col-md-5 col-sm-12">
												<p><?= $agencia->direccion ?> - <?= $agencia->referencia ?><br><?= $agencia->ubigeo ?? '' ?></p>
											</div>
											<div class="col-md-7 col-sm-12">
												<p><?= $agencia->nombres ?> <?= $agencia->apellidos ?></p>
												<p>DNI: <?= $agencia->dni ?></p>
												<p>Teléfono: <?= $agencia->telefono ?></p>
											</div>
										</div>
										<hr>
									<? endforeach; ?>
								<? else: ?>
									<h6>No se encontró direcciones de entrega registradas</h6>
								<? endif; ?>
							</div>
						</div>
						<div class="cuadro-recojo">


							<div class="cuadro-comprobantes2">
								<h5>Comprobantes</h5>
								<div class="col-md-12" id="comprobantes" style="padding: 0;">
									<? if ($comprobantes):
										foreach ($comprobantes as $comprobante): ?>
											<div class="row cuadro-oscuro">
												<div class="col-md-4 col-sm-12">
													<p><?= $comprobante->ptipo ?></p>
												</div>
												<div class="col-md-4 col-sm-12">
													<p><?= $comprobante->razonsocial ?></p>
												</div>
												<div class="col-md-2 col-sm-12">
													<p><?= $comprobante->ruc ?></p>
												</div>
												<div class="col-md-2 col-sm-12 text-right">
													<a style="cursor:pointer" class="estado-<?= $comprobante->idestado ? '' : '' ?>activo"><?= $comprobante->estado ?></a>
												</div>
											</div>
											<hr>
										<? endforeach; ?>
									<? else: ?>
										<h6>No se encontró direcciones de entrega registradas</h6>
									<? endif; ?>
								</div>
							</div>
						</div>

					</div>

				</div>

			</div>
		</div>
</section>


<script>
	function correoEditarMisDatos() {
		$.ajax({
			url: `${BASE_URL}api/FormularioController/envioCorreoVerificacion`,
			type: "post",
			data: {
				'codigo': <?= $codigoVerificacion ?>,
				'correo': USUARIO_LOGIN.correo
			},
			dataType: 'json',
		}).done(function(res) {
			Swal.fire({
				title: 'Editar mis datos: Código de verificación',
				text: 'Ingrese el código que le enviamos al correo: ' + USUARIO_LOGIN.correo,
				input: 'text',
				inputAttributes: {
					autocapitalize: 'off'
				},
				showCancelButton: true,
				confirmButtonText: 'Aceptar',
				cancelButtonText: 'Cancelar',
				showLoaderOnConfirm: true,
			}).then((result) => {
				if (result.isConfirmed) {
					if (result.value !== '<?= $codigoVerificacion ?>') {
						Swal.fire({
							title: 'Editar mis datos: Código de verificación',
							text: 'El código ingresado no es válido.',
							icon: 'warning',
							confirmButtonText: 'Aceptar'
						});
					} else {
						$.ajax({
							url: `${BASE_URL}api/Front/setSesionEditarDatos`,
							type: "post",
							data: {
								'codigo': '<?= $codigoVerificacion ?>',
							},
							dataType: 'json',
						}).done(function(res) {
							window.location.href = `${BASE_URL}mi-cuenta-editar`;
						});
					}
				}
			});
		});
	}
</script>