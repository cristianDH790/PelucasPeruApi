<section class="bg_menu_page" style="background-image: url(<?= base_url(); ?>public/template/images/fondo-nosotros.jpg);background-size: cover;width: 100%;height: 200px;display: table;background-repeat: no-repeat;background-position: left bottom;">
	<div class="inner_subpage_banner">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="text-banner">
						<h1>Mis pedidos</h1>
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
				<p><a href="<?= base_url(); ?>">Inicio</a> <span>></span> Mis pedidos</p>
			</div>
		</div>
	</div>
</section>
<!-- 
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
							<dt class="complete"><a href="<?= base_url('mi-cuenta'); ?>" class="cuenta">Mi Cuenta</a></dt>


							<dt class="complete"><a href="<?= base_url('mis-pedidos'); ?>" class="cuenta-pedido activo">Mis pedidos</a></dt>
							<dt class="complete"><a class="cuenta-sesion" style="cursor: pointer;" onclick="cerrarSesion()">Cerrar sesión</a></dt>
						</dl>
					</div>

				</div>
			</aside>

			<div class="col-main col-md-9 col-sm-12 col-xs-12">
				<div class="informacion-pedido">
					<h5>INFORMACIÓN DEL PEDIDO</h5>
					<div class="cuadro-informacion">
						<ul>
							<li>Pedido - N° 0001</li>
							<li>Fecha de pedido: 23/09/2025</li>
							<li>Fecha de entrega: 25/09/2025</li>
							<li>Estado <span class="aceptado5">Aceptado</spa>
							</li>
						</ul>

					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="pedidos-identifacion">
							<h5>Identificación</h5>
							<div class="cuadro-pedidos-identicacion">
								<p>Correo: <span class="correoUsu"></span></p>
								<p>Nombre: <span class="nombreUsu"></span></p>
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="pedidos-identifacion" style="overflow: hidden;">
							<h5>FORMA DE PAGO</h5>
							<div class="cuadro-pedidos-identicacion">
								<div class="col-md-12 col-sm-12">
									<p class="formapago2"></p>

									<div class="botones-info">
										<a class="pendiente">Pago pendiente</a>
										<a style="cursor: pointer;" class="pago">Realizar pago</a>
										<span class="confirmado5">Pago confirmado</span>
										<span class="reportado5">Pago reportado</span>
									</div>

								</div>
							</div>
							<div class="col-md-12 col-sm-12">
								<p class="formapago2"></p>

								<div class="botones-info">
									<a class="pendiente">Pago pendiente</a>
									<span class="confirmado5">Pago confirmado</span>
									<span class="reportado5">Pago reportado</span>
								</div>
							</div>
							<hr>
							<div class="col-md-12 col-sm-12">
								<div class="row">
									<h6 class="reportePago">Reportar pago</h6>
								</div>
								<div class="row" style="margin: 0;">
									<div class="col-md-3 col-sm-12" style="float: left;">
										<div id="list">
											<img class="thumb2" width="80" src="<?= base_url() ?>public/template/images/productos/reloj.jpg" title="" />
										</div>

									</div>
									<div class="col-md-9 col-sm-12 botones-info" style="float: left;">

										<form action="#" method="POST" id="formconstancia" name="formconstancia" enctype="multipart/form-data">
											<input type="hidden" value="" name="idPedido" id="idPedido" style="width: 100%;">
											<div class="row" style="margin-right: 0;">
												<input type="file" value="" class="" accept="image/*" name="imagen" id="files" style="width: 100%;">
												<button type="submit" onclick=' $(".carga").css("display", "block");' id="guardaroperacion">Adjuntar constancia</button>
												<a style="cursor: pointer;" onclick="cargarImage('<?= base_url() ?>public/template/images/productos/reloj.jpg')" data-bs-toggle="modal" data-bs-target="#modalConstancia" class="pago-descarga"><i class="fa-solid fa-search"></i></a>
											</div>
										</form>

									</div>
								</div>
							</div>
							<div class="col-md-12 col-sm-12">
								<p class="formapago2"></p>

								<div class="botones-info">
									<a class="pendiente">Pago pendiente</a>
									<span class="confirmado5">Pago confirmado</span>
									<span class="reportado5">Pago reportado</span>
								</div>
							</div>
							<hr>
							<div class="col-md-12 col-sm-12" style="overflow: hidden; display: block; padding: 0 0 15px 0;">
								<div class="row">
									<h6 class="reportePago">Reportar pago</h6>
								</div>
								<div class="col-md-3 col-sm-12" style="float: left;">
									<div id="list">
										<img class="thumb2" width="80" src="<?= base_url() ?>public/template/images/productos/reloj.jpg" title="" />
									</div>
								</div>
								<div class="col-md-9 col-sm-12 botones-info" style="float: left;">

									<form action="#" method="POST" id="formconstancia" name="formconstancia" enctype="multipart/form-data">

										<input type="hidden" value="" name="idPedido" id="idPedido" style="width: 100%;">
										<div class="row" style="margin-right: 0;">
											<input type="file" value="" class="" accept="image/*" name="imagen" id="files" style="width: 100%;">
											<button type="submit" onclick=' $(".carga").css("display", "block");' class="pago-constancia" id="guardaroperacion">Adjuntar constancia</button>
											<a style="cursor: pointer;" onclick="cargarImage('<?= base_url() ?>public/template/images/productos/reloj.jpg')" data-bs-toggle="modal" data-bs-target="#modalConstancia" class="pago-descarga"><i class="fa-solid fa-search"></i></a>
										</div>
									</form>

								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="entrega-producto">
							<h5>ENTREGA DEL PRODUCTO</h5>
							<div class="cuadro-entrega-producto">
								<ul>
									<li>Dirección</li>
									<li>Jhoany Fasabi</li>
									<li>DNI/CE/PAS: 74163443</li>
									<li>Télefono: +51 987 654 321</li>
								</ul>
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="pedidos-comprobante">
							<h5>COMPROBANTE DE PAGO</h5>
							<div class="cuadro-pedidos-comprobante">
								<ul>
									<li>Jhoany Fasabi</li>
									<li>OLS</li>
									<li>DNI/CE/PAS: 20741634438</li>
									<li>Dirección</li>
								</ul>
							</div>
						</div>
						<div class="informacion-pedido">
							<h5>Cupones</h5>
							<div class="cuadro-informacion">
								<ul>
									<li>Cupón usado: <strong>10</strong></li>
									<li>Descuento: <strong>30%</strong> </li>

								</ul>
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="pedidos-comprobante">
							<h5>Observaciones</h5>
							<div class="cuadro-pedidos-comprobante">
								<p>Hola...</p>
							</div>
						</div>
					</div>
				</div>

				<div class="pedido-producto">
					<h5>Productos</h5>
					<div class="cuadro-checkout row">
						<div class="col-md-2 col-sm-12">
							<a href="#"> <img width="40" src="<?= base_url() ?>public/template/images/productos/reloj.jpg"></a>
						</div>
						<div class="col-md-10 col-sm-12">
							<h2>Nombre del producto</h2>
							<span class="atributos" style="font-weight: 600;">Preferecias</span>
							<ul>
								<li>Atributos</li>
							</ul>
							<hr>
							<ul>
								<li>Código: 1093210</li>
								<li>Cantidad: 1</li>
								<li>Precio unitario: S/ 50.00</li>
								<li>Descuento: S/ 20.00</li>
							</ul>
							<div class="cuadro-precio">
								<h1>S/ 30.00</h1>
							</div>
						</div>
					</div>

					<div class="total-precios">
						<h5>Subtotal <span>S/ 50.00</span></h5>
						<h5>COMISIÓN 30% tarjeta <span>S/ 20.00</span></h5>
						<h5>DESCUENTO 20% <span>S/ 20.00</span></h5>
						<h5>ENVÍO <span>S/ 10.00</span></h5>
						<h5>TOTAL<span>S/ 30.00</span></h5>
					</div>
				</div>

				<div class="botones-info">

					<a href="<?= base_url() ?>/mis-pedidos" class="regresar-pedido">
						<i class="fa-solid fa-undo" aria-hidden="true"></i> Regresar</a>
				</div>

			</div>

		</div>
	</div>
</section> -->

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
								<h4 class="nombreUsu"><?= $usuario->nombres . ' ' . $usuario->papellido . ' ' . $usuario->sapellido ?></h4>
							</dt>
							<dt class="complete"><a href="<?= base_url(); ?>mi-cuenta" class=" cuenta">Mi Cuenta</a></dt>


							<dt class="complete"><a href="<?= base_url(); ?>mis-pedidos" class="activo cuenta-pedido">Mis pedidos</a></dt>
							<dt class="complete"><a class="cuenta-sesion" style="cursor: pointer;" onclick="localStorage.clear();$(location).attr('href','https://lechic.pe');">Cerrar sesión</a></dt>
						</dl>
					</div>

				</div>
			</aside>

			<div class="col-main col-md-9 col-sm-12 col-xs-12">

				<div class="informacion-pedido">
					<h5>INFORMACIÓN DEL PEDIDO</h5>
					<div class="cuadro-informacion">

						<ul>
							<li><?= $pedido->referencia ?></li>
							<li>Fecha de pedido: <?= date('d-m-Y', strtotime($pedido->fecha)) ?></li>
							<li>Hora de pedido: <?= date('H:i:s', strtotime($pedido->fecha)) ?></li>


							<li>Estado: <span class="aceptado5"><?= $pedido->estado ?></span></li>

						</ul>

					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="pedidos-identifacion">
							<h5>Identificación</h5>
							<div class="cuadro-pedidos-identicacion">
								<p>Correo: <span class="correoUsu"><?= $usuario->correo ?></span></p>
								<p>Nombre: <span class="nombreUsu"><?= $usuario->nombres . ' ' . $usuario->papellido . ' ' . $usuario->sapellido ?: "" ?></span></p>
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="pedidos-identifacion" style="overflow: hidden;">
							<h5>FORMA DE PAGO</h5>
							<div class="col-md-12 col-sm-12">
								<p class="formapago2"><?= $pedido->formapago ?></p>

								<div class="botones-info">
									<span class="confirmado5"><?= $pedido->ppago ?></span>
								</div>

								<!--p class="ppago2">Pago realizado</p-->
							</div>
							<hr>

							<? if ($pedido->idformapago != 1): ?>
								<div class="col-md-12 col-sm-12" style="overflow: hidden; display: block; padding: 0 15px 15px;">
									<div class="row">
										<div class="col-md-5 col-sm-12" style="float: left;">
											<div id="list">
												<img class="thumb2" width="300" src="<?= base_url() ?>archivos/pedido/<?= $pedido->urlconstancia ?>" title="<?= $pedido->referencia ?>">
											</div>
										</div>
									</div>
								</div>
							<? endif ?>
						</div>
					</div>

					<div class="col-md-6">
						<div class="entrega-producto">
							<h5>ENTREGA DEL PRODUCTO</h5>

							<div class="cuadro-entrega-producto">

								<? if ($pedido->identrega == 3 || $pedido->identrega == 4): ?>
									<ul>
										<li><?= $pedido->entrega ?></li>
										<li><?= $pedido->agencia_agencia ?> </li>
										<li><?= $pedido->agencia_direccion ?> </li>
										<li><?= $pedido->agencia_nombres ?> <?= $pedido->agencia_apellidos ?></li>
										<li>DNI/CE/PAS: <?= $pedido->agencia_dni ?></li>
										<li>Télefono: <?= $pedido->agencia_telefono ?></li>
									</ul>

								<? else: ?>
									<a href="https://wa.me/<?= $WSPVENTA->valor ?>" target="_blank" class="btn btn-green">Whatsapp</a>
								<? endif; ?>
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="pedidos-comprobante">
							<h5>COMPROBANTE DE PAGO</h5>
							<div class="cuadro-pedidos-comprobante">
								<ul>
									<li><?= $pedido->comprobante_ptipo ?></li>
									<li><?= $pedido->comprobante_razonsocial ?></li>
									<li>DNI/CE/PAS: <?= $pedido->comprobante_ruc ?></li>
									<? if ($pedido->comprobante_direccion): ?>
										<li><?= $pedido->comprobante_direccion ?></li>
									<? endif ?>
								</ul>
							</div>
						</div>
					</div>

					<div class="col-md-12">
						<div class="pedido-producto">
							<h5>Productos</h5>
							<? if ($pedidoDetalles):
								foreach ($pedidoDetalles as $pedidoDetalle): ?>
									<div class="cuadro-checkout row">
										<div class="col-md-4 col-sm-12">
											<a href="<?= base_url() ?>producto/<?= $pedidoDetalle->urlamigable ?>">
												<img src="<?= base_url() ?>archivos/productoimagen/<?= str_replace(".webp", "-mini.webp", $pedidoDetalle->urlImagen)  ?: 'imagen.jpg' ?>" alt="<?= $pedidoDetalle->nombre ?>">
											</a>
										</div>
										<div class="col-md-8 col-sm-12">
											<h2><?= $pedidoDetalle->nombre ?></h2>

											<ul>
												<!--<li>Código: </li>-->
												<li>Cantidad:<?= $pedidoDetalle->cantidad ?></li>
												<li>Precio unitario: S/ <?= $pedidoDetalle->precio ?></li>

											</ul>
											<div class="cuadro-precio">
												<!--<h4>S/ 1450</h4>-->
												<h1>S/ <?= number_format($pedidoDetalle->precio * $pedidoDetalle->cantidad, 2) ?></h1>
											</div>
										</div>
									</div>
							<? endforeach;
							endif ?>
							<div class="total-precios">
								<h5>Subtotal <span>S/ <?= $pedido->subtotal ?></span></h5>
								<h5>ENVÍO <span>S/<?= $pedido->costoenvio ?></span></h5>
								<h5>COMISIÓN <span>S/<?= $pedido->comision ?></span></h5>
								<h5>DESCUENTO <span>S/<?= $pedido->descuento ?></span></h5>
								<h5>TOTAL<span>S/ <?= $pedido->total ?></span></h5>
							</div>
						</div>
					</div>

					<div class="col-md-12">
						<div class="botones-info">
							<a href="<?= base_url(); ?>mis-pedidos" class="regresar-pedido"><svg class="svg-inline--fa fa-arrow-rotate-left" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="arrow-rotate-left" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
									<path fill="currentColor" d="M125.7 160l50.3 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L48 224c-17.7 0-32-14.3-32-32L16 64c0-17.7 14.3-32 32-32s32 14.3 32 32l0 51.2L97.6 97.6c87.5-87.5 229.3-87.5 316.8 0s87.5 229.3 0 316.8s-229.3 87.5-316.8 0c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0c62.5 62.5 163.8 62.5 226.3 0s62.5-163.8 0-226.3s-163.8-62.5-226.3 0L125.7 160z"></path>
								</svg><!-- <i class="fa fa-undo" aria-hidden="true"></i> Font Awesome fontawesome.com --> Regresar</a>
						</div>
					</div>

				</div>

			</div>

		</div>
	</div>
</section>