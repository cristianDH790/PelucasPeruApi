<section class="bg_menu_page" style="background-image: url(<?= base_url(); ?>public/template/images/fondo-nosotros.jpg);background-size: cover;width: 100%;height: 200px;display: table;background-repeat: no-repeat;background-position: center center;">
	<div class="inner_subpage_banner">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="text-banner">
						<h1>Carrito</h1>

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
				<p><a href="<?= base_url(); ?>">Inicio</a> <span>></span> Carrito de compras</p>
			</div>
		</div>
	</div>
</section>

<section class="carrito-int aos-init aos-animate" data-aos="fade-up">
	<div class="container-fluid">
		<div class="row">

			<div class="col-md-12">
				<h2>Carrito de compras</h2>

			</div>

			<div class="col-md-9">
				<div class="sticky-top">
					<div class="cuadro-checkout">
						<div id="productos">
							<!-- PRODUCTOS -->
						</div>
					</div>
					<div class="total-precios">
						<h5>Subtotal <span> S/&nbsp; <span class="subtotal">0.00</span></span></h5>
						<!-- <h5 id="enbalaje" style="display: none;">COMISION <span> S/&nbsp; <span class="comision">0.00</span></span></h5> -->
						<h5 id="enbalaje" style="display: none;">COMISION <span>
								<p class="comision">0</p>&nbsp;%
							</span></h5>
						<h5>ENVÍO <span> S/&nbsp; <span class="envio">0.00</span></span></h5>
						<h5>DESCUENTO <span> S/&nbsp; <span class="descuento">0.00</span></span></h5>
						<h5>TOTAL <span>S/&nbsp; <span class="total">0.00</span></span></h5>
					</div>
				</div>
			</div>

			<div class="col-md-3">

				<div class="cuadro-identifacion" id="box-identificacion">
					<h5>Identificación</h5>
					<a class="box-iden" data-bs-toggle="modal" data-bs-target="#modalSesionCarrito">Iniciar Sesión</a>
				</div>
				<? if (!session()->get('usuarioSesion')): ?>
					<div id="formularioRegistro" class="formRegistro2">

						<input type="hidden" id="referencia" name="referencia" value="1742379472">
						<form id="formReg" name="formReg" method="post">
							<div class="col-md-12">
								<label>Tipo de documento</label>
								<select class="form-select" name="ptipodoc" id="ptipodoc">
									<? if ($ptipodocumentos):
										foreach ($ptipodocumentos as $ptipodocumento): ?>
											<option value="<?= $ptipodocumento->idparametro ?>"><?= $ptipodocumento->nombre ?></option>
									<? endforeach;
									endif ?>
								</select>
								<small id="ptipodoc" class="validaform ptipodoc"></small>
							</div>
							<div class="col-md-12">
								<label>Número de documento</label>
								<input class="form-control" id="documento" name="documento">
								<small id="documento" class="validaform documento"></small>
							</div>
							<div class="col-md-12">
								<label>Nombres</label>
								<input class="form-control" id="nombres" name="nombres">
								<small id="nombres" class="validaform nombres"></small>

							</div>
							<div class="col-md-12">
								<label>Primer apellido</label>
								<input class="form-control" id="pApellido" name="pApellido">
								<small id="pApellido" class="validaform pApellido"></small>

							</div>
							<div class="col-md-12">
								<label>Segundo apellido</label>
								<input class="form-control" id="sApellido" name="sApellido">
								<small id="sApellido" class="validaform sApellido"></small>
							</div>

							<div class="col-md-12">
								<label>Correo electrónico</label>
								<input class="form-control" id="correo" name="correo">
								<small id="correo" class="validaform correo"></small>
							</div>
							<div class="col-md-12">
								<label>Teléfono</label>
								<input class="form-control" id="telefono" name="telefono">
								<small id="telefono" class="validaform telefono"></small>
							</div>
							<div class="col-md-12">
								<label>Sexo</label>
								<select class="form-select" name="sexo" id="sexo">
									<option value="">Seleccione</option>
									<option value="M">Masculino</option>
									<option value="F">Femenino</option>
								</select>
								<small id="sexo" class="validaform sexo"></small>
							</div>
							<div class="col-md-12">
								<label>Fecha de nacimiento</label>
								<input class="form-control" id="fechaNacimiento" name="fechaNacimiento" type="date">
								<small id="fechaNacimiento" class="validaform fechaNacimiento"></small>
							</div>
							<div class="col-md-12">
								<label class="termiCondi" for="terminos"><input type="checkbox" name="terminos" id="terminos" style=" display: inline-block;width: auto;height: auto;margin-right: 5px;"> Acepto los
									<a data-bs-toggle="modal" data-bs-target="#modalTerminos" style="cursor: pointer;">
										Términos y condiciones y las políticas de protección de datos
									</a>
								</label>
								<small id="terminos" class="validaform terminos"></small>
							</div>
							<div class="col-md-12">
								<label for="boletin"><input type="checkbox" name="boletin" id="boletin" style="display: inline-block;width: auto;height: auto;margin-right: 5px;"> Quiero recibir el newsletter con promociones.</label>
							</div>
							<div class="col-md-12">
								<button class="btn btn-success btn-sesion" value="Guardar y continuar">Registrarme</button>
							</div>
						</form>
					</div>
				<? else: ?>
					<div id="formularioRegistro" class="formRegistro">
						<form id="formCheckout" name="formCheckout" data-gtm-form-interact-id="0">

							<input type="hidden" id="referencia" name="referencia" value="">

							<div class="cuadro-entrega" style="display: block;">
								<h5 style="cursor:pointer">Entrega del producto</h5>
								<div class="cuadro-completo" data-bs-toggle="colapse">
									<div>
										<div class="col-md-12">
											<select id="entrega" name="entrega">
												<option value="0">Seleccione</option>
												<? if ($entregas):
													foreach ($entregas as $entrega): ?>
														<option data-costoenvio="<?= $entrega->costoenvio ?>" value="<?= $entrega->identrega ?>"><?= $entrega->nombre ?></option>
												<? endforeach;
												endif ?>
											</select>
											<span class="entrega validaclass"></span>
											<!-- <h1 style="display: none;" class="entregaDesc">Envío gratis en Lima para pedidos mayores a S/ <span id="importe-minimo-gratis">0</span></h1> -->
										</div>

										<!-- <div class="col-md-12 col-sm-12" id="fechaentrega-caja" style="display: none;">
											<label>Fecha de entrega *</label>
											<div class="input-group">
												<input type="text" id="fechaEntrega" autocomplete="off" name="fechaEntrega" style="width: 100%;">

											</div>
											<span class="fechaEntrega validaclass"></span>
										</div> -->



										<div class="destinonuevo" id="destinonuevo" style="display: none;">


											<a href="https://wa.me/<?= $WSPVENTA->valor ?>" target="_blank" rel="noopener noreferrer"
												class="btn btn-success d-flex align-items-center justify-content-center w-100">
												<i class="fab fa-whatsapp me-2"></i> Coordinar en WhatsApp
											</a>
											<!-- <div class="existe-destino">
												<input type="hidden" id="dubigeo" name="dubigeo">
												<input type="hidden" id="dlatitud" name="dlatitud">
												<input type="hidden" id="dlongitud" name="dlongitud">
												<input type="hidden" id="dubigeoTemporal" name="dubigeoTemporal">
												<input type="hidden" id="costoTemporal" name="costoTemporal">
												<input type="hidden" id="dlatitudTemporal" name="dlatitudTemporal">
												<input type="hidden" id="dlongitudTemporal" name="dlongitudTemporal">
												<div class="col-md-12">
													<div class="form-group">
														<label>Dirección *</label>
														<div class="input-group">
															<input id="ddireccion" readonly="" name="ddireccion" onclick="cargarMapa()" style="width: 85%;margin-bottom:0;">
															<button type="button" title="Ver mapa" onclick="cargarMapa()" style="width: 15%;" class="input-group-text"><svg class="svg-inline--fa fa-map" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="map" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg="">
																	<path fill="currentColor" d="M384 476.1L192 421.2l0-385.3L384 90.8l0 385.3zm32-1.2l0-386.5L543.1 37.5c15.8-6.3 32.9 5.3 32.9 22.3l0 334.8c0 9.8-6 18.6-15.1 22.3L416 474.8zM15.1 95.1L160 37.2l0 386.5L32.9 474.5C17.1 480.8 0 469.2 0 452.2L0 117.4c0-9.8 6-18.6 15.1-22.3z"></path>
																</svg></button>
														</div>
														<span class="ddireccion validaclass"></span>
													</div>
												</div>
												<div class="col-md-12">
													<div class="form-group">
														<label>Referencia</label>
														<input id="dreferencia" name="dreferencia">
														<span class="dreferencia validaclass"></span>
													</div>
												</div>
												<div class="col-md-12">
													<h6 class="textos1"><strong>Persona que recibe el pedido</strong></h6>
												</div>
												<div class="col-md-12">
													<div class="form-group">
														<label>
															<input checked="" type="checkbox" style="display: inline-block; width:auto;height: auto;margin-right: 5px;" id="checkDestino" name="checkDestino"> Completar con mis datos.</label>
													</div>
												</div>
												<div id="container-checkDestino" style="display: none;">
													<div class="col-md-12">
														<div class="form-group">
															<label>Nombres *</label>
															<input id="dnombres" name="dnombres">
															<span class="dnombres validaclass"></span>
														</div>
													</div>
													<div class="col-md-12">
														<div class="form-group">
															<label>Apellidos *</label>
															<input id="dapellidos" name="dapellidos">
															<span class="dapellidos validaclass"></span>
														</div>
													</div>
													<div class="col-md-12">
														<div class="form-group">
															<label>DNI/CE/PAS *</label>
															<input id="ddocumento" name="ddocumento">
															<span class="ddocumento validaclass"></span>
														</div>
													</div>
													<div class="col-md-12">
														<div class="form-group">
															<label>Teléfono *</label>
															<input id="dtelefono" name="dtelefono">
															<span class="dtelefono validaclass"></span>
														</div>
													</div>
												</div>
											</div> -->

										</div>

										<div class="recojonuevo" id="recojonuevo" style="display: none;">
											<a href="https://wa.me/<?= $WSPVENTA->valor ?>" target="_blank" rel="noopener noreferrer"
												class="btn btn-success d-flex align-items-center justify-content-center w-100">
												<i class="fab fa-whatsapp me-2"></i> Coordinar en WhatsApp
											</a>
										</div>

										<div class="provincianuevo" id="provincianuevo" style="display: none;">

											<div class="col-md-12">
												<h6 class="textos">Datos de la agencia</h6>
											</div>
											<div class="col-md-12">
												<label>Nombre de la agencia *</label>
												<input id="agencia2" disabled value="shalom" name="agencia2"> <!-- aqui dale su stilo de gris -->
												<input hidden id="agencia" value="shalom" name="agencia">
												<span class="agencia validaclass"></span>
											</div>
											<!-- <div class="col-md-12">
												<label>Agencia *</label>
												<input id="adireccion" name="adireccion">
												<span class="adireccion validaclass"></span>
											</div> -->
											<div class="col-md-12">
												<label>Agencia más cercana *</label>
												<div class="input-group">
													<input id="adireccion" name="adireccion" class="form-control" placeholder="Ingresa tu dirección">
													<a href="#" target="_blank" class="btn btn-outline-primary" id="btnUbicacion" title="buscar ubicación">
														<i class="fas fa-map-marker-alt"></i>
													</a>
												</div>
												<span class="adireccion validaclass"></span>
											</div>



											<div class="col-md-12">
												<label>Departamento *</label>
												<select name="adepartamento" id="adepartamento">
													<option value="0">Seleccione</option>
													<option value="2">Amazonas</option>
													<option value="3207">Ancash</option>
													<option value="11212">Apurimac</option>
													<option value="15433">Arequipa</option>
													<option value="20864">Ayacucho</option>
													<option value="28693">Cajamarca</option>
													<option value="35202">Cuzco</option>
													<option value="45036">Huancavelica</option>
													<option value="52107">Huanuco</option>
													<option value="59008">Ica</option>
													<option value="60483">Junín</option>
													<option value="65022">La Libertad</option>
													<option value="68770">Lambayeque</option>
													<option value="70283">Lima</option>
													<option value="75963">Loreto</option>
													<option value="78412">Madre de Dios</option>
													<option value="78751">Moquegua</option>
													<option value="80111">Pasco</option>
													<option value="83016">Piura</option>
													<option value="85949">Puno</option>
													<option value="95614">San Martín</option>
													<option value="98430">Tacna</option>
													<option value="99274">Tumbes</option>
													<option value="99485">Ucayali</option>
												</select>
												<span class="adepartamento validaclass"></span>
											</div>
											<div class="col-md-12">
												<label>Provincia *</label>
												<select name="aprovincia" id="aprovincia">
													<option value="">Seleccione --</option>
												</select>
												<span class="aprovincia validaclass"></span>
											</div>
											<div class="col-md-12">
												<label>Distrito *</label>
												<select name="adistrito" id="adistrito">
													<option value="">Seleccione --</option>
												</select>
												<span class="adistrito validaclass"></span>
											</div>

											<div class="col-md-12">
												<h6 class="textos1">Persona que recibe el pedido</h6>
											</div>
											<div class="col-md-12">
												<label>
													<input checked="" type="checkbox" style="display: inline-block; width:auto" id="checkagencia" name="checkagencia"> Completar con mis datos.</label>

											</div>
											<div id="container-checkagencia">
												<div class="col-md-12">
													<label>Nombres *</label>
													<input id="anombres" name="anombres">
													<span class="anombres validaclass"></span>
												</div>
												<div class="col-md-12">
													<label>Apellidos *</label>
													<input id="aapellidos" name="aapellidos">
													<span class="aapellidos validaclass"></span>
												</div>
												<div class="col-md-12">
													<label>DNI/CE/PAS *</label>
													<input id="adocumento" name="adocumento">
													<span class="adocumento validaclass"></span>
												</div>
												<div class="col-md-12">
													<label>Teléfono *</label>
													<input id="atelefono" name="atelefono">
													<span class="atelefono validaclass"></span>
												</div>
											</div>
										</div>

									</div>
								</div>
								<div class="cuadro-pago">
									<h5 style="cursor:pointer">FORMA DE PAGO</h5>
									<div class="pago cuadro-pago-completo">
										<select id="formapago" class="fpago" name="formapago" data-gtm-form-interact-field-id="0">
											<? if ($formaPagos):
												foreach ($formaPagos as $formaPago): ?>
													<option value="<?= $formaPago->idformapago ?>"><?= $formaPago->nombre ?></option>
											<? endforeach;
											endif ?>
										</select>
										<span class="formapago validaclass"></span>
										<? if ($formaPagos):
											foreach ($formaPagos as $key => $formaPago): ?>
												<div class="" id="formapago-<?= $formaPago->idformapago ?>" <?= $key == 0 ? "style='display:block;'" : "style='display:none;'" ?>>
													<?= $formaPago->contenido2 ?>
													<?= $formaPago->contenido ?>
												</div>
										<? endforeach;
										endif ?>

										<p class="formaPagoDes" <?= $key == 0 ? "style='display:block;'" : "style='display:none;'" ?>></p>

										<div class="transfer bloque-transfer1" <?= $key == 0 ? "style='display:block;'" : "style='display:none;'" ?>>
											<label for="">Adjuntar constancia</label>
											<input type="file" class="validaciones" name="constancia" id="constancia" style=" height:auto;padding:0;">
											<span class="constancia validaclass"></span>

										</div>
									</div>
								</div>
								<div class="cuadro-cupon">
									<h5 style="cursor:pointer">Cupón de descuento</h5>
									<div class="pago cuadro-cupon-completo">
										<input type="text" id="cupon" name="cupon" style="width: 63%;display: inline-block;border-radius: 50px;margin-right: 5px;">
										<button id="limpiaCupon" type="button" onclick="limpiarCupon()" class="btn btn-secondary"><svg class="svg-inline--fa fa-trash" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="trash" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">
												<path fill="currentColor" d="M135.2 17.7L128 32 32 32C14.3 32 0 46.3 0 64S14.3 96 32 96l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0-7.2-14.3C307.4 6.8 296.3 0 284.2 0L163.8 0c-12.1 0-23.2 6.8-28.6 17.7zM416 128L32 128 53.2 467c1.6 25.3 22.6 45 47.9 45l245.8 0c25.3 0 46.3-19.7 47.9-45L416 128z"></path>
											</svg></button>
										<button type="button" style="cursor: pointer;" id="cuponBoton" onclick="validarCupon()"><svg class="svg-inline--fa fa-circle-check" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="circle-check" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
												<path fill="currentColor" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"></path>
											</svg></button>
										<span class="cupon validaclass"></span>
									</div>
								</div>
								<div class="comprobante">
									<h5 style="cursor:pointer">Comprobante</h5>
									<div class="comprobante2 cuadro-comprobante">
										<div class="col-md-12">
											<select name="tipocomprobante" id="tipocomprobante">
												<? if ($tipoComprobantes):
													foreach ($tipoComprobantes as $key => $tipoComprobante): ?>
														<option value="<?= $tipoComprobante->idparametro ?>"><?= $tipoComprobante->nombre ?></option>
												<? endforeach;
												endif ?>
											</select>
											<span class="tipocomprobante validaclass"></span>
										</div>

										<div id="container-comprobanteanteriores">
											<label>Anteriores</label>
											<select name="misComprobantes" id="misComprobantes">
												<option value="">Seleccione</option>
												<? foreach ($misComprobantes as $miComprobante): ?>
													<option value="<?= $miComprobante->idcomprobante ?>"
														data-nombres="<?= $miComprobante->razonsocial ?>"
														data-documento="<?= $miComprobante->ruc ?>">
														<?= $miComprobante->ruc . ' - ' . $miComprobante->razonsocial ?>
													</option>
												<? endforeach; ?>
											</select>
										</div>

										<? if ($misComprobantes): ?>
											<? foreach ($misComprobantes as $miComprobante): ?>
												<div id="misComprobantes-container-<?= $miComprobante->idcomprobante ?>" style="display: none;">
													<ul style="list-style: circle;">
														<li><?= $miComprobante->ruc ?></li>
														<li><?= $miComprobante->razonsocial ?></li>
													</ul>
												</div>
											<? endforeach; ?>
										<? endif ?>

										<div class="existe-comprobante" id="box-boleta" style="display: none;">
											<div class="col-md-12">
												<label id="label-comprobante">
													<input checked="" type="checkbox" style="display: inline-block; width:auto" id="checkcomprobanteboleta" name="checkcomprobante">
													Completar con mis datos.
												</label>
											</div>
											<div id="container-checkcomprobanteboleta">
												<div class="col-md-12">
													<label class="texto3">Nombres *</label>
													<input id="bnombres" name="bnombres">
													<span class="bnombres validaclass"></span>
												</div>
												<div class="col-md-12">
													<label class="texto2">DNI / PAS / CE / RUC *</label>
													<input id="bdocumento" name="bdocumento">
													<span class="bdocumento validaclass"></span>
												</div>
											</div>
										</div>


										<div id="box-factura" class="comprobantenuevo" style="display: none;">

											<div class="col-md-12">
												<label class="texto3">Razón social *</label>
												<input id="fnombres" name="fnombres">
												<span class="fnombres validaclass"></span>
											</div>
											<div class="col-md-12">
												<label class="texto2">RUC</label>
												<input id="fdocumento" name="fdocumento">
												<span class="fdocumento validaclass"></span>
											</div>

											<div class="col-md-12 bcomprob">
												<label>Dirección</label>
												<input id="fdireccion" name="fdireccion">
												<span class="fdireccion validaclass"></span>
											</div>
										</div>


										<input type="hidden" name="idProductos[]" id="idProductos">
										<input type="hidden" name="idProductoTallas[]" id="idProductoTallas" value="5662,6631">
										<input type="hidden" name="cantidades[]" id="cantidades" value="1,1">
										<input type="hidden" name="productoTallas[]" id="productoTallas">
										<input type="hidden" name="descuentoProductos[]" id="descuentoProductos">

										<input type="hidden" name="costoenvio" id="costoenvio">
										<input type="hidden" name="total" id="total">
										<input type="hidden" name="subtotal" id="subtotal">
										<input type="hidden" name="comision" id="comision">
										<input type="hidden" name="descuento" id="descuento">
										<input type="hidden" name="minimoGratis" id="minimoGratis">
										<input type="hidden" name="horaReferencia" id="horaReferencia">
										<input type="hidden" name="pesoXcostoEnvio" id="pesoXcostoEnvio">

									</div>
								</div>
								<div class="cuadro-rojo" style="display: block;">
									<!--<h5>FORMA DE PAGO</h5>-->
									<div class="cuadro-rojo-completo" style="display: none;">
										Monto mínimo de pago: S/ <span id="monto-minimo-pago">0</span>
									</div>
								</div>
								<div class="cuadro-obs">
									<h5 style="cursor:pointer">Observaciones</h5>
									<div class="pago cuadro-cupon-completo">
										<textarea class="form-control" name="observaciones" id="observaciones"></textarea>

									</div>
								</div>
								<div class="cuadro-boton">
									<!--<h5>FORMA DE PAGO</h5>-->
									<div class="cuadro-boton-completo">
										<div class="row" style="padding: 0px;margin: 0;">
											<div class="checkbox" style="padding: 0px;">
												<label style="font-size:14px;">
													<input type="checkbox" name="terminos" id="terminos" tabindex="11" style="display: inline-block;width: auto;margin-top: 10px;margin-right: 10px;">
													Aceptar <a style="cursor: pointer;" class="termiCondi" data-bs-toggle="modal" data-bs-target="#modalTerminos"> términos y condiciones</a>
													<span class="terminos validaclass"></span>
												</label>

											</div>
										</div>
										<button type="submit" style="cursor: pointer;" id="comprar">Comprar ahora</button>
									</div>
								</div>
							</div>
						</form>
					</div>
				<? endif ?>

			</div>

		</div>
	</div>
</section>
<!-- modal mapa confirmado -->
<div class="modal fade mapaConfirmado" id="modalSelectDir" style="z-index: 3000 !important;background:rgba(0, 0, 0, .5)" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalSelectDirTitle">Confirmar dirección seleccionada</h5>
				<button type="button" class="close btn-secondary" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">

				<div class="form-group">
					<label for="correo" class="control-label" style="display:block;">Verifique la dirección. Si no es la correcta intentelo nuevamente.</label>
					<input type="text" class="form-control" name="dirSelect" id="dirSelect" placeholder="">

				</div>

				<div class="modal-footer">
					<input type="submit" class="btn btn-secondary2 btn-popup-checkout" style="width: 120px;" id="inputSelectDir" value="Seleccionar">
					<button type="button" class="btn btn-secondary" id="cierramodalSelect" data-bs-dismiss="modal" style="background: #777;padding: 6px 20px;border-radius:50px;">Regresar</button>
				</div>

			</div>

		</div>
	</div>
</div>

<!-- modal izipay carrito -->
<div class="modal fade" id="modalpago" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel" style="display: inline-block;">Pago en línea</h5>
				<a style="cursor: pointer;" class="close closePago" data-bs-dismiss="modal">
					<span aria-hidden="true">&times;</span>
				</a>
			</div>
			<div class="modal-body" id="modal-pago-body">
				<img src="<?= base_url() ?>/public/template/images/yhassir-logotipo-negro.png" style="width: 50%; display: block; margin: 0 auto 20px;" alt="" srcset="">
				<div id="paymentForm" style="text-align: center;width: 260px; margin: auto;">
				</div>
			</div>
		</div>
	</div>
</div>
</div>

<!-- modal sesion carrito -->
<div class="modal fade" id="modalSesionCarrito" tabindex="-1" style="z-index: 10000;" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="exampleModalLabel">Inicio de sesión</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="form-login-carrito" name="form-login-carrito" method="post">
					<div class="form-group">
						<label for="login-usuario">Usuario (DNI/PAS/CEX)</label>
						<input class="form-control" type="text" id="login-usuario" name="login-usuario">
						<small style="color: red;" class="validacion login-usuario"></small>
					</div>
					<div class="form-group">
						<button type="submit" style="text-transform: none;">Continuar</button>
						<button type="button" onclick="registro()" style="background: #6f6f6f;margin-left:10px; text-transform: none;">Registrarme</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>




<!-- Link de izipay -->
<script src="https://static.micuentaweb.pe/static/js/krypton-client/V4.0/stable/kr-payment-form.min.js" kr-language="es-ES"></script>
<link href="https://static.micuentaweb.pe/static/js/krypton-client/V4.0/ext/classic-reset.css" rel="stylesheet">
<script>
	let DEPARTAMENTO = "";
	let IMPORTE_MINIMO = 0.00;
	let IMPORTE_MINIMO_GRATIS = 0.00;
	let HORA_REFERENCIA = 0;
	let PESOXCOSTO_ENVIO = 0;
	let COMISION_ENTREGA = 0;
	let COMISION_FORMAPAGO = 0;
	let COSTO_ZONA_REPARTO = 0;
	let DESCUENTO = 0.00;
	let SUBTOTAL = 0.00;
	let CANTIDADES = [];

	let PESOXCOSTO_ENVIO_AUX = 0;
	let COMISION_ENTREGA_AUX = 0;

	let COMISION_TOTAL = 0.00;
	let TOTAL = 0.00;

	let REFERENCIA = "";

	let DATAPICKER;

	const referencia = document.getElementById('referencia');
	const boxIdentificacion = document.getElementById('box-identificacion');
	const formularioRegistro = document.getElementById('formularioRegistro');
	const formularioCompra = document.getElementById('formularioCompra');
	document.addEventListener("DOMContentLoaded", function() {
		//verificamos los datos del localstorage
		REFERENCIA = localStorage.getItem('codigo-compra');
		if (!REFERENCIA) {
			REFERENCIA = '<?= strtotime(date('Y-m-d h:i:s')) ?>'

			localStorage.setItem('codigo-compra', REFERENCIA)
			referencia.value = REFERENCIA;
		} else {
			referencia.value = REFERENCIA;

		}

		// jQuery.noConflict();

		DATAPICKER = flatpickr("#fechaEntrega", {
			enableTime: false,
			dateFormat: "d-m-Y",
			minDate: "today",
			locale: "es", // usa el locale español completo
			onDayCreate: function(dObj, dStr, fp, dayElem) {
				const today = new Date();
				const dayDate = dayElem.dateObj;

				if (
					dayDate.getDate() === today.getDate() &&
					dayDate.getMonth() === today.getMonth() &&
					dayDate.getFullYear() === today.getFullYear()
				) {
					dayElem.style.backgroundColor = "#160085"; // azul oscuro
					dayElem.style.color = "white"; // texto blanco
					dayElem.style.borderRadius = "50%"; // para redondear
				}
			}
		});

		if (!USUARIO_LOGIN) {
			boxIdentificacion.innerHTML = `
				<h5>Identificación</h5>
				<a class="box-iden" data-bs-toggle="modal" data-bs-target="#modalSesionCarrito" >Iniciar Sesión</a>
				`;

		} else {
			boxIdentificacion.innerHTML = `
			<h5>Identificación</h5>
			<div class="identificacion cuadro-nombre">
				<p>Correo: <strong>${USUARIO_LOGIN.correo}</strong></p>
				<p>Nombres: <strong>${USUARIO_LOGIN.nombres} ${USUARIO_LOGIN.papellido} ${USUARIO_LOGIN.sapellido||''} </strong></p><a style="cursor:pointer; display:block; margin:10px auto" onclick="cerrarSesion()">No soy yo, cerrar sesión</a>
			</div>`;


			//amrcamos los checkbock 
			const checkboxBoleta = document.getElementById("checkcomprobanteboleta");
			checkboxBoleta.checked = true; // marca el checkbox
			checkboxBoleta.click(); // dispara el listener 'change' como un clic real

			// const checkRecojo = document.getElementById("checkrecojo");
			// checkRecojo.checked = true;
			// checkRecojo.click();

			// const checkDestino = document.getElementById("checkDestino");
			// checkDestino.checked = true;
			// checkDestino.click();


			document.getElementById("formapago").dispatchEvent(new Event('change'));
			document.getElementById("tipocomprobante").dispatchEvent(new Event('change'));
			document.getElementById("checkcomprobanteboleta").dispatchEvent(new Event('change'));
			// document.getElementById("checkDestino").dispatchEvent(new Event('change'));
			// document.getElementById("checkrecojo").dispatchEvent(new Event('change'));
			document.getElementById("checkagencia").dispatchEvent(new Event('change'));

			document.getElementById("checkcomprobanteboleta").addEventListener("change", function(e) {
				const valor = e.target.checked;

				if (valor === true) {
					document.getElementById("bnombres").value = (USUARIO_LOGIN.nombres + ' ' + USUARIO_LOGIN.papellido + ' ' + (USUARIO_LOGIN.sapellido ?? "")).trim();
					document.getElementById("bdocumento").value = USUARIO_LOGIN.documento;
					document.getElementById("container-checkcomprobanteboleta").style.display = "none";
				} else {
					document.getElementById("bnombres").value = "";
					document.getElementById("bdocumento").value = "";
					document.getElementById("container-checkcomprobanteboleta").style.display = "block";
				}
			});
			document.getElementById("checkagencia").addEventListener("change", function(e) {
				const valor = e.target.checked;

				if (valor === true) {
					document.getElementById("anombres").value = USUARIO_LOGIN.nombres;
					document.getElementById("aapellidos").value = (USUARIO_LOGIN.papellido + ' ' + (USUARIO_LOGIN.sapellido ?? "")).trim();
					document.getElementById("adocumento").value = USUARIO_LOGIN.documento;
					document.getElementById("atelefono").value = USUARIO_LOGIN.telefono;
					document.getElementById("container-checkagencia").style.display = "none";
				} else {
					document.getElementById("anombres").value = "";
					document.getElementById("aapellidos").value = "";
					document.getElementById("adocumento").value = "";
					document.getElementById("atelefono").value = "";
					document.getElementById("container-checkagencia").style.display = "block";
				}
			});
			const selectComprobantes = document.getElementById('misComprobantes');
			const inputNombres = document.getElementById('bnombres');
			const inputDocumento = document.getElementById('bdocumento');
			selectComprobantes.addEventListener('change', () => {
				const selectedOption = selectComprobantes.selectedOptions[0];

				if (selectedOption && selectedOption.value !== "") {
					// Llenar inputs con los datos del comprobante
					inputNombres.value = selectedOption.getAttribute('data-nombres') || '';
					inputDocumento.value = selectedOption.getAttribute('data-documento') || '';
				} else {
					// Si no hay selección, limpiar los inputs
					inputNombres.value = '';
					inputDocumento.value = '';
				}
			});

			// document.getElementById("checkcomprobantefactura").addEventListener("change", function(e) {
			// 	const valor = e.target.checked;

			// 	if (valor === true) {
			// 		document.getElementById("fnombres").value = USUARIO_LOGIN.nombres;
			// 		document.getElementById("fdocumento").value = USUARIO_LOGIN.documento;
			// 	} else {
			// 		document.getElementById("fnombres").value = "";
			// 		document.getElementById("fdocumento").value = "";
			// 	}
			// });

		}

		showProductosCarrito();
		actualizarInputsArrays();

	});


	// Formulario login
	const login = document.getElementById("form-login-carrito");
	if (login) {
		login.addEventListener("submit", function(e) {
			e.preventDefault();
			document.querySelector(".carga").style.display = "block";

			const formData = new FormData(this);

			fetch(`${BASE_URL}api/SeguridadController/login`, {
					method: "POST",
					body: formData,
				})
				.then(response => response.json())
				.then(res => {
					removerClases();

					if (res.status === "exito") {
						console.log(res);
						document.getElementById("modalSesionCarrito").querySelector(".btn-close").click();
						Swal.fire({
							title: '¡Iniciar sesión!',
							text: `Bienvenid@. ${res.usuario.nombres} ${res.usuario.papellido} ${res.usuario.sapellido || ''}`,
							icon: 'success',
							showCancelButton: false,
							confirmButtonColor: '#3085d6',
							confirmButtonText: 'Continuar'
						}).then(() => {
							location.reload();
							document.querySelector(".carga").style.display = "none";
						});
						setListaDeseos();
					} else {
						showErrores(res.errors);
						Swal.fire({
							title: 'Ingresar',
							width: 700,
							text: `El documento ingresado no se encuentra registrado`,
							icon: 'warning',
							showCancelButton: true,
							confirmButtonColor: '#3085d6',
							cancelButtonColor: '#d33',
							confirmButtonText: 'Registrarme como nuevo cliente',
							cancelButtonText: 'Ingresar con otro documento'
						}).then((result) => {
							if (result.isConfirmed) {
								const documen = this["login-usuario"].value;
								document.getElementById("documento").value = documen;
								document.getElementById("modalSesionCarrito").querySelector(".btn-close").click();
							}
							document.querySelector(".carga").style.display = "none";
						});
					}
				})
				.catch(error => {
					console.error('Error:', error);
					document.querySelector(".carga").style.display = "none";
				});
		});
	}

	const chechout = document.getElementById("formCheckout");
	if (chechout) {
		chechout.addEventListener("submit", function(e) {
			e.preventDefault();
			document.querySelectorAll(".carga").forEach(el => el.style.display = "block");

			const form = this;
			const data = new FormData(form);


			const REFERENCIA = localStorage.getItem('codigo-compra');
			const referenciaInput = document.getElementById("referencia");
			if (referenciaInput) {
				referenciaInput.value = REFERENCIA;
			}

			//actualisando los registros de los inputs
			actualizarInputsArrays();

			for (let pair of data.entries()) {
				console.log(pair[0] + ': ' + pair[1]);
			}

			// if (typeof agenciaSeleccionada !== 'undefined' && agenciaSeleccionada) {
			// 	data.append("agencia", JSON.stringify(agenciaSeleccionada));
			// }

			fetch(`${BASE_URL}api/CheckoutController/checkFormCarritoCompras`, {
					method: "POST",
					body: data,
				})
				.then(response => response.json())
				.then(res => {
					removerClases();
					if (res.status === "error") {
						showErrores(res.errors);
						if (res.errors2 && res.errors2.length > 0) {
							showModalStock(res.errors2);
							showProductosCarrito();
						}
						document.querySelectorAll(".carga").forEach(el => el.style.display = "none");
						return;
					} else {
						if (parseFloat(IMPORTE_MINIMO) > TOTAL) {
							Swal.fire({
								title: '¡Carrito de compras!',
								text: `El importe mínimo de compra es de ${IMPORTE_MINIMO}.`,
								icon: 'warning',
								showCancelButton: false,
								confirmButtonColor: '#3085d6',
								confirmButtonText: 'Continuar'
							});
						} else {
							procesarPago(data);
						}
					}
				})
				.catch(() => {
					document.querySelectorAll(".carga").forEach(el => el.style.display = "none");
				});
		});
	}

	const entrega = document.getElementById("entrega");
	if (entrega) {
		entrega.addEventListener("change", function(e) {
			const idEntrega = e.target.value;

			// Reiniciar variables globales
			COMISION_ENTREGA = 0;
			COSTO_ZONA_REPARTO = 0;

			// Mostrar loader
			document.querySelector(".carga").style.display = "block";

			// Resetear DatePicker si existe
			if (window.DATAPICKER) {
				window.DATAPICKER?.clear?.();
				window.DATAPICKER?.destroy?.();
				window.DATAPICKER = null;

			}

			// Mostrar caja de fecha y secciones base
			// document.getElementById("fechaentrega-caja").style.display = "block";
			document.getElementById("destinonuevo").style.display = "none";
			document.getElementById("recojonuevo").style.display = "none";
			document.getElementById("provincianuevo").style.display = "none";
			// document.getElementById("ddireccion").value = '';
			// document.getElementById("costoTemporal").value = 0;

			document.querySelector(".cuadro-rojo-completo").style.display = "block";
			// document.querySelector(".entregaDesc").style.display = "block";

			// Mostrar secciones según el tipo de entrega
			if (idEntrega == "1") {
				document.getElementById("destinonuevo").style.display = "block";
				// document.getElementById("enbalaje").style.display = "none";
			} else if (idEntrega == "2") {
				document.getElementById("recojonuevo").style.display = "block";
				// document.getElementById("enbalaje").style.display = "none";
			} else if (idEntrega == "4") {
				document.getElementById("recojonuevo").style.display = "block";
				// document.getElementById("enbalaje").style.display = "none";
			} else if (idEntrega == "3") {
				document.getElementById("provincianuevo").style.display = "block";
				// document.getElementById("enbalaje").style.display = "block";
			} else {
				// document.getElementById("enbalaje").style.display = "none";
				document.querySelector(".carga").style.display = "none";
				document.querySelector(".cuadro-rojo-completo").style.display = "none";
				// document.querySelector(".entregaDesc").style.display = "none";
				return;
			}

			// Preparar datos del fetch
			const formData = new URLSearchParams();
			formData.append('idEntrega', idEntrega);

			fetch(`${BASE_URL}api/EntregaController/entregaPorIdEntrega`, {
					method: "POST",
					headers: {
						"Content-Type": "application/x-www-form-urlencoded",
					},
					body: formData.toString(),
				})
				.then(res => res.json())
				.then(response => {
					console.log(response);

					const diasHabiles = response.diashabiles ?
						response.diashabiles.split(',').map(Number) : [];

					const diasNoDisponibles = [0, 1, 2, 3, 4, 5, 6].filter(dia => {
						const diaFormateado = (dia === 0) ? 7 : dia; // Dom = 0 => 7
						return !diasHabiles.includes(diaFormateado);
					});

					// Fecha mínima = hoy + días
					const hoy = new Date();
					const fechaMinima = new Date(hoy);
					fechaMinima.setDate(hoy.getDate() + parseInt(response.dias || 0));

					// Buscar primer día permitido
					function encontrarSiguienteDiaPermitido(fecha, diasPermitidos) {
						let intento = new Date(fecha);
						while (true) {
							const dia = intento.getDay();
							const diaFormateado = (dia === 0) ? 7 : dia;
							if (diasPermitidos.includes(diaFormateado)) {
								return intento;
							}
							intento.setDate(intento.getDate() + 1);
						}
					}

					const fechaDefault = encontrarSiguienteDiaPermitido(fechaMinima, diasHabiles);

					// Iniciar Flatpickr
					window.DATAPICKER = flatpickr("#fechaEntrega", {
						dateFormat: "d-m-Y",
						minDate: fechaMinima,
						defaultDate: fechaDefault,
						disable: [
							function(date) {
								return diasNoDisponibles.includes(date.getDay());
							}
						],
						locale: "es",
						firstDayOfWeek: 1,
						onDayCreate: function(dObj, dStr, fp, dayElem) {
							const today = new Date();
							const dayDate = dayElem.dateObj;

							if (
								dayDate.getDate() === today.getDate() &&
								dayDate.getMonth() === today.getMonth() &&
								dayDate.getFullYear() === today.getFullYear()
							) {
								dayElem.style.backgroundColor = "#160085";
								dayElem.style.color = "white";
								dayElem.style.borderRadius = "50%";
							}
						}
					});

					// Variables globales
					IMPORTE_MINIMO = parseFloat(response.importeminimo) || 0.00;
					IMPORTE_MINIMO_GRATIS = parseFloat(response.minimogratis) || 0.00;
					HORA_REFERENCIA = parseInt(response.horareferencia) || 0;
					// PESOXCOSTO_ENVIO = parseFloat(response.pesoxcostoenvio) || 0.00;
					COMISION_ENTREGA = parseFloat(response.costoenvio) || 0.00;

					console.log("entrega", response);
					COMISION_ENTREGA_AUX = COMISION_ENTREGA;
					PESOXCOSTO_ENVIO_AUX = PESOXCOSTO_ENVIO;

					COSTO_ZONA_REPARTO = COMISION_ENTREGA;




					// if (!response.minimogratis || parseFloat(response.minimogratis) === 0) {
					// 	document.querySelector(".entregaDesc").style.display = "none";
					// }

					// Refrescar productos del carrito
					showProductosCarrito();

				})
				.catch(error => {
					console.error("Error:", error);
				})
				.finally(() => {
					document.querySelector(".carga").style.display = "none";
				});
		});
	}


	const departamento = document.getElementById("adepartamento");
	if (departamento) {
		departamento.addEventListener("change", function(e) {
			e.preventDefault();

			const carga = document.querySelector(".carga");
			if (carga) carga.style.display = "block";

			const valor = e.target.value;
			if (!valor) {
				if (carga) carga.style.display = "none";
				return false;
			}



			fetch(`${BASE_URL}api/publico/ubigeos/listar`, {
					method: "POST",
					headers: {
						"Content-Type": "application/x-www-form-urlencoded"
					},
					body: new URLSearchParams({
						ordenCriterio: "nombre",
						ordenTipo: "asc",
						idrUbigeo: valor
					})
				})
				.then(response => response.json())
				.then(res => {
					const provinciaSelect = document.getElementById("aprovincia");
					provinciaSelect.innerHTML = '';

					res.content.forEach(item => {
						const option = document.createElement("option");
						option.value = item.idUbigeo;
						option.textContent = item.nombre;
						provinciaSelect.appendChild(option);
					});

					if (carga) carga.style.display = "none";

					//guardamos el valor de departamento
					console.log("Departamento seleccionado:", departamento.value);
					if (departamento.value == 75963) {
						COSTO_ZONA_REPARTO = 18;
						showProductosCarrito();
					}

				})
				.catch(error => {
					console.error("Error en la petición:", error);
					if (carga) carga.style.display = "none";
				});
		});
	}


	const provincia = document.getElementById("aprovincia");
	if (provincia) {
		provincia.addEventListener("change", function(e) {
			e.preventDefault();

			const carga = document.querySelector(".carga");
			if (carga) carga.style.display = "block";

			const valor = e.target.value;
			if (!valor) {
				if (carga) carga.style.display = "none";
				return false;
			}

			fetch(`${BASE_URL}api/publico/ubigeos/listar`, {
					method: "POST",
					headers: {
						"Content-Type": "application/x-www-form-urlencoded"
					},
					body: new URLSearchParams({
						ordenCriterio: "nombre",
						ordenTipo: "asc",
						idrUbigeo: valor
					})
				})
				.then(response => response.json())
				.then(res => {
					const distritoSelect = document.getElementById("adistrito");

					// Limpiar opciones existentes antes de agregar nuevas
					distritoSelect.innerHTML = '';

					res.content.forEach(item => {
						const option = document.createElement("option");
						option.value = item.idUbigeo;
						option.textContent = item.nombre;
						distritoSelect.appendChild(option);
					});

					if (carga) carga.style.display = "none";
				})
				.catch(error => {
					console.error("Error en la petición:", error);
					if (carga) carga.style.display = "none";
				});
		});
	}

	const formapago = document.getElementById("formapago");
	if (formapago) {
		document.getElementById("formapago").addEventListener("change", function(e) {
			document.querySelector(".carga").style.display = "block";
			document.querySelector(".formaPagoDes").style.display = "none";

			const idFormaPago = e.target.value;

			// Ocultar todos los bloques con id que empieza con formapago-
			document.querySelectorAll('[id^="formapago-"]').forEach(el => {
				el.style.display = "none";
			});

			if (idFormaPago != 1) {
				document.querySelector(".bloque-transfer1").style.display = "block";
			} else {
				document.querySelector(".bloque-transfer1").style.display = "none";
			}

			const bloqueForma = document.getElementById("formapago-" + idFormaPago);
			if (bloqueForma) {
				bloqueForma.style.display = "block";
			}

			// Petición con fetch
			fetch(`${BASE_URL}api/FormaPagoController/formaPagoPorIdFormaPago`, {
					method: "POST",
					headers: {
						"Content-Type": "application/x-www-form-urlencoded",
					},
					body: `idFormaPago=${encodeURIComponent(idFormaPago)}`,
				})
				.then(res => res.json())
				.then(res => {
					if (!res) return;
					console.log("formapago", res);
					if (res.comision > 0) {
						document.querySelector(".formaPagoDes").innerHTML = "Se cobra una comision de " + Number(res.comision) + " %";
						document.querySelector(".formaPagoDes").style.display = "block";
						document.getElementById("enbalaje").style.display = "block";
						document.querySelector(".comision").style.display = "block";
						document.querySelector(".comision").innerHTML = parseFloat(res.comision).toFixed(0);
						COMISION_FORMAPAGO = parseFloat(res.comision);
					} else {
						COMISION_FORMAPAGO = 0;
					}

					document.querySelectorAll(".comision").forEach(el => {
						el.innerHTML = res.comision;
					});

					showProductosCarrito();
				})
				.catch(err => {
					document.querySelector(".carga").style.display = "none";
				});
		});
	}


	const comprobante = document.getElementById("tipocomprobante");
	if (comprobante) {
		comprobante.addEventListener("change", function(e) {
			e.preventDefault();
			document.querySelectorAll(".carga").forEach(el => el.style.display = "block");

			const idComprobante = e.target.value;

			if (idComprobante == 445) {
				const boxBoleta = document.getElementById("box-boleta");
				if (boxBoleta) boxBoleta.style.display = "block";

				const labelComprobante = document.getElementById("label-comprobante");
				if (labelComprobante) labelComprobante.style.display = "block";

				const containerComprobanteanteriores = document.getElementById("container-comprobanteanteriores");
				if (containerComprobanteanteriores) containerComprobanteanteriores.style.display = "block";

				// Ocultar factura
				const boxFactura = document.getElementById("box-factura");
				if (boxFactura) boxFactura.style.display = "none";

			} else if (idComprobante == 446) {
				// Ocultar contenedores misComprobantes solo si existen
				const misComprobantesContainers = document.querySelectorAll('[id^="misComprobantes-container"]');
				if (misComprobantesContainers.length > 0) {
					misComprobantesContainers.forEach(el => {
						el.style.display = "none";
					});
				}

				const labelComprobante = document.getElementById("label-comprobante");
				if (labelComprobante) labelComprobante.style.display = "none";

				const misComprobantes = document.getElementById("misComprobantes");
				if (misComprobantes) misComprobantes.value = "";

				const containerComprobanteanteriores = document.getElementById("container-comprobanteanteriores");
				if (containerComprobanteanteriores) containerComprobanteanteriores.style.display = "none";

				const boxFactura = document.getElementById("box-factura");
				if (boxFactura) boxFactura.style.display = "block";

				const boxBoleta = document.getElementById("box-boleta");
				if (boxBoleta) boxBoleta.style.display = "none";
			}

			document.querySelectorAll(".carga").forEach(el => el.style.display = "none");
		});

	}



	const miscomprobantes = document.getElementById("misComprobantes");
	if (miscomprobantes) {
		miscomprobantes.addEventListener("change", function(e) {
			const valor = e.target.value;

			document.querySelectorAll('[id^="misComprobantes-container"]').forEach(el => {
				el.style.display = "none";
			});

			if (valor) {
				const contenedor = document.getElementById("misComprobantes-container-" + valor);
				if (contenedor) {
					contenedor.style.display = "block";
				}
				document.querySelectorAll(".existe-comprobante").forEach(el => {
					el.style.display = "none";
				});
			} else {
				document.querySelectorAll(".existe-comprobante").forEach(el => {
					el.style.display = "block";
				});
			}
		});
	}



	// Registrar Usuario
	const registro = document.getElementById("formReg");
	if (registro) {
		registro.addEventListener("submit", function(e) {
			e.preventDefault();

			// Mostrar loader
			document.querySelectorAll(".carga").forEach(el => el.style.display = "block");

			const form = this;
			const url = `${BASE_URL}api/SeguridadController/registrarUsuario`;

			fetch(url, {
					method: "POST",
					body: new FormData(form),
					credentials: 'include'
				})
				.then(response => response.json())
				.then(res => {
					// Limpiar errores previos
					removerClases();

					if (res.status === "error") {
						showErrores(res.errors);
						return; // Sale si hay errores
					}

					if (res.usuario && res.usuario.nombres && res.usuario.papellido) {
						Swal.fire({
							title: '¡Bienvenid@!',
							text: `Hola ${res.usuario.nombres} ${res.usuario.papellido}. Gracias por registrarte. Estamos listos para atender tu pedido.`,
							icon: 'success',
							showCancelButton: false,
							confirmButtonColor: '#3085d6',
							confirmButtonText: 'Continuar'
						}).then(() => {
							location.reload(); // O actualizar solo la UI si quieres
						});
					}

					// Actualizar lista de deseos si existe
					if (typeof setListaDeseos === "function") setListaDeseos();
				})
				.catch(error => {
					console.error('Error:', error);
					Swal.fire({
						title: 'Error',
						text: 'Ocurrió un error al registrar tu usuario. Inténtalo nuevamente.',
						icon: 'error',
						confirmButtonColor: '#d33',
						confirmButtonText: 'Cerrar'
					});
				})
				.finally(() => {
					// Ocultar loader siempre, éxito o error
					document.querySelectorAll(".carga").forEach(el => el.style.display = "none");
				});
		});
	}



	function cambioStock(tipo, idProducto) {
		DESCUENTO = 0;

		const inputCantidad = document.getElementById(`cantidad-${idProducto}`);
		const inputStock = document.getElementById(`stock-${idProducto}`);

		if (!inputCantidad || !inputStock) return;

		let cantidad = parseInt(inputCantidad.value, 10);
		const stock = parseInt(inputStock.value, 10);

		const key = 'Pelucas-Producto-' + idProducto;
		const dataJSON = localStorage.getItem(key);
		if (!dataJSON) return;

		const data = JSON.parse(dataJSON);
		console.log("cantidad", cantidad);
		console.log("stock", parseInt(stock));

		if (tipo === 'suma') {
			cantidad += 1;
			if (cantidad > parseInt(stock)) {
				Swal.fire({
					title: 'Producto',
					text: 'Máximo de productos disponibles',
					icon: 'warning',
					confirmButtonColor: '#3085d6',
					confirmButtonText: 'Continuar'
				});
				return;
			}
		} else if (tipo === 'resta') {
			cantidad -= 1;
			if (cantidad < 1) {
				cantidad = 1;
			}
		}

		data.cantidad = cantidad;
		inputCantidad.value = cantidad;
		localStorage.setItem(key, JSON.stringify(data));

		showProductosCarrito();
		actualizarInputsArrays();
	}

	function showProductosCarrito() {
		const carga = document.querySelector('.carga');
		carga.style.display = 'block';
		const productos = [];
		const idProductos = [];
		CANTIDADES = [];
		// const idProductoTallas=[];
		let numProductos = 0;
		let unidadesProductos = 0;
		SUBTOTAL = 0;
		//capturamos todos los keys del localstorage
		Object.keys(localStorage).forEach(key => {
			if (key.includes(`Pelucas-Producto-`)) {
				const item = JSON.parse(localStorage.getItem(key));
				const precioVenta = parseFloat(item.precioVenta);
				const cantidad = parseInt(item.cantidad);

				SUBTOTAL += precioVenta * cantidad;
				unidadesProductos += cantidad;
				numProductos++

				idProductos.push(item.idProducto);
				CANTIDADES.push(item.cantidad);
				// idProductoTallas.push(item.idProductoBase);
				productos.push(`
                <div class="row">
                    <div class="col-md-3 col-sm-12">
                        <a target="_blank" href="${BASE_URL}producto/${item.urlAmigable}">
                            <img src="${BASE_URL}archivos/productoimagen/${item.urlImagen}" alt="${item.nombre}">
                        </a>
                    </div>
                    <div class="col-md-9 col-sm-12">
                      <div class="d-flex">
                        <div class="box-descripcion">
                          <span>
                            <a style="cursor:pointer;" onclick="removerItemCarrito(${item.idProducto}); showProductosCarrito()">
                              <i class="fa-solid fa-trash"></i>
                            </a>
                          </span>
                          <h3>${item.nombre}</h3>
                          <h5>Características:</h5>
                          <p>${item.descripcion || 'Sin descripción'}</p>
                          <div class="detail-qty info-qty">
                            <a style="cursor: pointer;" onclick="cambioStock('resta','${item.idProducto}')" class="qty-down">
                              <i class="fa-solid fa-minus" aria-hidden="true"></i>
                            </a>
                            <input type="text" step="1" min="1" max="${item.stock}" readonly class="input-text text qty qty-val" name="cantidad-${item.idProducto}" id="cantidad-${item.idProducto}" value="${item.cantidad}">
                            <input type="hidden" id="stock-${item.idProducto}" value="${item.stock}">
                            <a style="cursor: pointer;" onclick="cambioStock('suma','${item.idProducto}')" class="qty-up">
                              <i class="fa-solid fa-plus" aria-hidden="true"></i>
                            </a>
                          </div>
                        </div>
                        <div class="cuadro-precio ms-auto">
                          <h1>S/ ${(item.cantidad * item.precioVenta).toFixed(2)}</h1>
                        </div>
                      </div>
                      <a style="cursor:pointer;" onclick="removerItemCarrito(${item.idProducto}); showProductosCarrito()" class="btn-eli">
                        <i class="fa-solid fa-trash"></i> Eliminar
                      </a>
                    </div>
                  </div>`);

			}
		});
		const productosHTML = numProductos > 0 ? productos.join('') : "<h4>No hay productos en el carrito</h4>";

		// Calcular total sin comisión del método de pago
		TOTAL = (SUBTOTAL + COSTO_ZONA_REPARTO) - DESCUENTO

		// Aplicar el porcentaje de comisión del método de pago
		if (COMISION_FORMAPAGO > 0) {
			COMISION_TOTAL = TOTAL * (COMISION_FORMAPAGO / 100); // monto de la comisión
			TOTAL = TOTAL + COMISION_TOTAL; // total con comisión
		} else {
			COMISION_TOTAL = 0; // si no hay comisión
		}

		// Redondeamos a 2 decimales para mostrar
		COMISION_TOTAL = parseFloat(COMISION_TOTAL.toFixed(2));
		TOTAL = parseFloat(TOTAL.toFixed(2));

		console.log("Comisión total: ", COMISION_TOTAL);
		console.log("Total final: ", TOTAL);


		document.getElementById("productos").innerHTML = productosHTML;
		// Asignar valores al DOM sin jQuery
		const cantidadesInput = document.getElementById("cantidades");
		if (cantidadesInput) {
			cantidadesInput.value = CANTIDADES;
		}


		const montoMinimoPago = document.getElementById("monto-minimo-pago");
		if (montoMinimoPago) {
			montoMinimoPago.innerHTML = IMPORTE_MINIMO;
		}
		// const importeMinimoGratis = document.getElementById("importe-minimo-gratis");
		// if (importeMinimoGratis) {
		// 	importeMinimoGratis.innerHTML = IMPORTE_MINIMO_GRATIS
		// }


		// Para elementos que pueden estar duplicados en clase, usamos querySelectorAll
		document.querySelectorAll(".subtotal").forEach(el => {
			el.innerHTML = SUBTOTAL.toFixed(2);
		});
		document.querySelectorAll(".envio").forEach(el => {
			el.innerHTML = COSTO_ZONA_REPARTO.toFixed(2);
		});

		document.querySelectorAll(".descuento").forEach(el => {
			el.innerHTML = DESCUENTO.toFixed(2);
		});
		document.querySelectorAll(".total").forEach(el => {
			el.innerHTML = TOTAL.toFixed(2);
		});



		carga.style.display = 'none';
	}

	// async function procesarPago() {
	// 	try {
	// 		// Mostrar loader
	// 		document.querySelector('.carga').style.display = 'block';
	// 		const CODIGO = localStorage.getItem('codigo-compra');

	// 		// Verificar carrito vacío
	// 		if (!Object.keys(localStorage).some(key => key.includes('Pelucas-Producto-'))) {
	// 			Swal.fire({
	// 				title: 'Carrito de compras!',
	// 				text: "Su pedido está vacío, no hay productos seleccionados.",
	// 				icon: 'warning',
	// 				showCancelButton: false,
	// 				confirmButtonColor: '#3085d6',
	// 				confirmButtonText: 'Continuar'
	// 			});
	// 			document.querySelector('.carga').style.display = 'none';
	// 			return;
	// 		}

	// 		const formapago = document.getElementById('formapago').value;

	// 		if (formapago != 1) {
	// 			// Pago normal con FormData
	// 			const formData = new FormData();
	// 			formData.append('idProductoTallas', document.getElementById('idProductoTallas').value);
	// 			formData.append('documento', USUARIO_LOGIN.documento);
	// 			formData.append('correo', USUARIO_LOGIN.correo);
	// 			formData.append('telefono', USUARIO_LOGIN.telefono);
	// 			formData.append('nombres', USUARIO_LOGIN.nombres);
	// 			formData.append('pApellido', USUARIO_LOGIN.papellido);
	// 			formData.append('sApellido', USUARIO_LOGIN.sapellido);
	// 			formData.append('constancia', document.getElementById('constancia').files[0]);
	// 			formData.append('cupon', document.getElementById('cupon').value);
	// 			formData.append('cantidades', CANTIDADES);
	// 			formData.append('comision', COMISION_TOTAL);
	// 			formData.append('descuento', DESCUENTO);
	// 			formData.append('descuentoProductos', document.getElementById('descuentoProductos').value);
	// 			formData.append('subtotal', SUBTOTAL);
	// 			formData.append('total', TOTAL);
	// 			formData.append('observacion', document.getElementById('observaciones').value);
	// 			formData.append('codigo', CODIGO);
	// 			// formData.append('fechaEntrega', document.getElementById('fechaEntrega').value);
	// 			formData.append('terminos', document.getElementById('terminos').checked);
	// 			formData.append('costoEnvio', COSTO_ZONA_REPARTO);

	// 			// Ejemplo de JSON anidado
	// 			console.log("usuario", USUARIO_LOGIN);
	// 			formData.append('usuario', JSON.stringify({
	// 				idUsuario: USUARIO_LOGIN.idusuario
	// 			}));
	// 			formData.append('formapago', JSON.stringify({
	// 				idFormaPago: formapago // aquí pones la variable que contiene la forma de pago seleccionada
	// 			}));

	// 			// Entrega (ejemplo: 1 = domicilio, 4 = agencia)
	// 			formData.append('entrega', JSON.stringify({
	// 				idEntrega: entrega // aquí pones la variable que contiene la opción de entrega seleccionada
	// 			}));

	// 			const response = await fetch(`${BASE_URL}api/publico/pedido/guardar`, {
	// 				method: 'POST',
	// 				body: formData
	// 			});

	// 			const res = await response.json();

	// 			if (res.status === "exito") {
	// 				document.querySelector('.carga').style.display = 'none';
	// 				Swal.fire({
	// 					title: 'Carrito de compras!',
	// 					text: "¡Pedido realizado con éxito!",
	// 					icon: 'success',
	// 					confirmButtonColor: '#3085d6',
	// 					confirmButtonText: 'Continuar'
	// 				}).then(() => {
	// 					localStorage.clear();
	// 					window.location.href = `${BASE_URL}pedido/${res.pedido.idPedido}`;
	// 				});
	// 			}

	// 		} else {
	// 			// Pago IZIPAY
	// 			const data = {
	// 				idProductoTallas: document.getElementById('idProductoTallas').value,
	// 				documento: USUARIO_LOGIN.documento,
	// 				correo: USUARIO_LOGIN.correo,
	// 				telefono: USUARIO_LOGIN.telefono,
	// 				nombres: USUARIO_LOGIN.nombres,
	// 				pApellido: USUARIO_LOGIN.pApellido,
	// 				cupon: document.getElementById('cupon').value,
	// 				cantidades: CANTIDADES,
	// 				comision: COMISION_TOTAL,
	// 				descuento: DESCUENTO,
	// 				subtotal: SUBTOTAL,
	// 				total: TOTAL,
	// 				observacion: document.getElementById('observaciones').value,
	// 				codigo: CODIGO,
	// 				fechaEntrega: document.getElementById('fechaEntrega').value,
	// 				terminos: document.getElementById('terminos').checked,
	// 				costoEnvio: COSTO_ZONA_REPARTO,
	// 				usuario: {
	// 					idUsuario: USUARIO_LOGIN.idusuario
	// 				},
	// 				formapago: {
	// 					idFormaPago: formapago
	// 				}
	// 			};

	// 			const response = await fetch(`${BASE_URL}Front/generaToken`, {
	// 				method: 'POST',
	// 				headers: {
	// 					'Content-Type': 'application/json'
	// 				},
	// 				body: JSON.stringify(data)
	// 			});

	// 			const res = await response.json();

	// 			if (res.status === 'exito') {
	// 				document.querySelector('.carga').style.display = 'none';
	// 				KR.setFormConfig({
	// 						formToken: res.token,
	// 						'kr-language': res.lenguajeform,
	// 						'kr-public-key': res.publicKey
	// 					}).then(({
	// 						KR
	// 					}) => KR.addForm('#paymentForm'))
	// 					.then(({
	// 						KR,
	// 						result
	// 					}) => KR.showForm(result.formId));

	// 				KR.onError(() => {
	// 					document.querySelector('.carga').style.display = 'none';
	// 				});
	// 				KR.onSubmit(() => {
	// 					localStorage.clear();
	// 					window.location.href = `${BASE_URL}pedido/${res.pedido.idPedido}`;
	// 				});

	// 				const modalpago = new bootstrap.Modal(document.getElementById('modalpago'));
	// 				modalpago.show();

	// 			} else {
	// 				Swal.fire({
	// 					title: 'Carrito de compras!',
	// 					text: "Errores encontrados, por favor intente nuevamente!",
	// 					icon: 'warning',
	// 					confirmButtonColor: '#3085d6',
	// 					confirmButtonText: 'Continuar'
	// 				});
	// 				document.querySelector('.carga').style.display = 'none';
	// 			}
	// 		}

	// 	} catch (error) {
	// 		console.error('Error procesando el pago:', error);
	// 		document.querySelector('.carga').style.display = 'none';
	// 	}
	// }
	// async function procesarPago() {
	// 	try {
	// 		// Mostrar loader
	// 		document.querySelector('.carga').style.display = 'block';
	// 		const CODIGO = localStorage.getItem('codigo-compra');

	// 		// Verificar carrito vacío
	// 		if (!Object.keys(localStorage).some(key => key.includes('Pelucas-Producto-'))) {
	// 			Swal.fire({
	// 				title: 'Carrito de compras!',
	// 				text: "Su pedido está vacío, no hay productos seleccionados.",
	// 				icon: 'warning',
	// 				confirmButtonColor: '#3085d6',
	// 				confirmButtonText: 'Continuar'
	// 			});
	// 			document.querySelector('.carga').style.display = 'none';
	// 			return;
	// 		}

	// 		const formapago = document.getElementById('formapago').value;
	// 		const entregaSeleccionada = document.getElementById('entrega').value;

	// 		// ==================================================
	// 		// 🧾 PAGO NORMAL (no IZIPAY)
	// 		// ==================================================
	// 		if (formapago != 1) {
	// 			const formData = new FormData();

	// 			// Datos del usuario
	// 			formData.append('idProductoTallas', document.getElementById('idProductoTallas').value);
	// 			formData.append('documento', USUARIO_LOGIN.documento);
	// 			formData.append('correo', USUARIO_LOGIN.correo);
	// 			formData.append('telefono', USUARIO_LOGIN.telefono);
	// 			formData.append('nombres', USUARIO_LOGIN.nombres);
	// 			formData.append('pApellido', USUARIO_LOGIN.papellido);
	// 			formData.append('sApellido', USUARIO_LOGIN.sapellido);

	// 			// Constancia y cupon
	// 			formData.append('constancia', document.getElementById('constancia').files[0] || '');
	// 			formData.append('cupon', document.getElementById('cupon').value || '');

	// 			// Datos de productos
	// 			formData.append('cantidades', CANTIDADES);
	// 			formData.append('comision', COMISION_TOTAL);
	// 			formData.append('descuento', DESCUENTO);
	// 			formData.append('descuentoProductos', document.getElementById('descuentoProductos').value || '');
	// 			formData.append('subtotal', SUBTOTAL);
	// 			formData.append('total', TOTAL);
	// 			formData.append('observacion', document.getElementById('observaciones').value || '');
	// 			formData.append('codigo', CODIGO);
	// 			formData.append('terminos', document.getElementById('terminos').checked ? 'on' : 'off');
	// 			formData.append('costoEnvio', COSTO_ZONA_REPARTO);
	// 			formData.append('tipocomprobante', tipoComprobanteSeleccionado);

	// 			// Usuario y forma de pago
	// 			formData.append('usuario', JSON.stringify({
	// 				idUsuario: USUARIO_LOGIN.idusuario
	// 			}));
	// 			formData.append('formapago', JSON.stringify({
	// 				idFormaPago: formapago
	// 			}));

	// 			// ==================================================
	// 			// 🚚 DATOS SEGÚN TIPO DE ENTREGA
	// 			// ==================================================

	// 			formData.append('entrega', JSON.stringify({
	// 				idEntrega: entregaSeleccionada
	// 			}));

	// 			// 🏠 Envío a domicilio
	// 			if (entregaSeleccionada == '1') {
	// 				formData.append('ddireccion', document.getElementById('ddireccion')?.value || '');
	// 				formData.append('dlatitud', document.getElementById('dlatitud')?.value || '');
	// 				formData.append('dlongitud', document.getElementById('dlongitud')?.value || '');
	// 				formData.append('dnombres', document.getElementById('dnombres')?.value || '');
	// 				formData.append('dapellidos', document.getElementById('dapellidos')?.value || '');
	// 				formData.append('ddocumento', document.getElementById('ddocumento')?.value || '');
	// 				formData.append('dtelefono', document.getElementById('dtelefono')?.value || '');
	// 			}

	// 			// 🏣 Envío a agencia
	// 			else if (entregaSeleccionada == '3') {
	// 				formData.append('adireccion', document.getElementById('adireccion')?.value || '');
	// 				formData.append('adepartamento', document.getElementById('adepartamento')?.value || '');
	// 				formData.append('aprovincia', document.getElementById('aprovincia')?.value || '');
	// 				formData.append('adistrito', document.getElementById('adistrito')?.value || '');
	// 				formData.append('checkagencia', document.getElementById('checkagencia')?.checked ? 'on' : 'off');
	// 				formData.append('anombres', document.getElementById('anombres')?.value || '');
	// 				formData.append('aapellidos', document.getElementById('aapellidos')?.value || '');
	// 				formData.append('adocumento', document.getElementById('adocumento')?.value || '');
	// 				formData.append('atelefono', document.getElementById('atelefono')?.value || '');
	// 			}

	// 			// ==================================================
	// 			// Enviar datos al backend
	// 			// ==================================================
	// 			const response = await fetch(`${BASE_URL}api/publico/pedido/guardar`, {
	// 				method: 'POST',
	// 				body: formData
	// 			});

	// 			const res = await response.json();

	// 			if (res.status === "exito") {
	// 				document.querySelector('.carga').style.display = 'none';
	// 				Swal.fire({
	// 					title: 'Carrito de compras!',
	// 					text: "¡Pedido realizado con éxito!",
	// 					icon: 'success',
	// 					confirmButtonColor: '#3085d6',
	// 					confirmButtonText: 'Continuar'
	// 				}).then(() => {
	// 					localStorage.clear();
	// 					window.location.href = `${BASE_URL}pedido/${res.pedido.idPedido}`;
	// 				});
	// 			} else {
	// 				throw new Error(res.mensaje || 'Error al registrar el pedido.');
	// 			}
	// 		}

	// 		// ==================================================
	// 		// 💳 PAGO CON IZIPAY
	// 		// ==================================================
	// 		else {
	// 			const data = {
	// 				idProductoTallas: document.getElementById('idProductoTallas').value,
	// 				documento: USUARIO_LOGIN.documento,
	// 				correo: USUARIO_LOGIN.correo,
	// 				telefono: USUARIO_LOGIN.telefono,
	// 				nombres: USUARIO_LOGIN.nombres,
	// 				pApellido: USUARIO_LOGIN.papellido,
	// 				cupon: document.getElementById('cupon').value,
	// 				cantidades: CANTIDADES,
	// 				comision: COMISION_TOTAL,
	// 				descuento: DESCUENTO,
	// 				subtotal: SUBTOTAL,
	// 				total: TOTAL,
	// 				observacion: document.getElementById('observaciones').value,
	// 				codigo: CODIGO,
	// 				terminos: document.getElementById('terminos').checked,
	// 				costoEnvio: COSTO_ZONA_REPARTO,
	// 				usuario: {
	// 					idUsuario: USUARIO_LOGIN.idusuario
	// 				},
	// 				formapago: {
	// 					idFormaPago: formapago
	// 				},
	// 				entrega: entregaSeleccionada
	// 			};

	// 			const response = await fetch(`${BASE_URL}Front/generaToken`, {
	// 				method: 'POST',
	// 				headers: {
	// 					'Content-Type': 'application/json'
	// 				},
	// 				body: JSON.stringify(data)
	// 			});

	// 			const res = await response.json();

	// 			if (res.status === 'exito') {
	// 				document.querySelector('.carga').style.display = 'none';

	// 				KR.setFormConfig({
	// 						formToken: res.token,
	// 						'kr-language': res.lenguajeform,
	// 						'kr-public-key': res.publicKey
	// 					}).then(({
	// 						KR
	// 					}) => KR.addForm('#paymentForm'))
	// 					.then(({
	// 						KR,
	// 						result
	// 					}) => KR.showForm(result.formId));

	// 				KR.onError(() => document.querySelector('.carga').style.display = 'none');
	// 				KR.onSubmit(() => {
	// 					localStorage.clear();
	// 					window.location.href = `${BASE_URL}pedido/${res.pedido.idPedido}`;
	// 				});

	// 				const modalpago = new bootstrap.Modal(document.getElementById('modalpago'));
	// 				modalpago.show();
	// 			} else {
	// 				throw new Error(res.mensaje || 'Error generando el pago.');
	// 			}
	// 		}

	// 	} catch (error) {
	// 		console.error('Error procesando el pago:', error);
	// 		Swal.fire({
	// 			title: 'Carrito de compras!',
	// 			text: 'Ocurrió un error al procesar el pedido. Verifique los campos e intente nuevamente.',
	// 			icon: 'error',
	// 			confirmButtonColor: '#3085d6',
	// 			confirmButtonText: 'Aceptar'
	// 		});
	// 		document.querySelector('.carga').style.display = 'none';
	// 	}
	// }


	// async function procesarPago() {
	// 	document.querySelector('.carga').style.display = 'block';
	// 	const CODIGO = localStorage.getItem('codigo-compra');

	// 	// Verificar carrito vacío
	// 	if (!Object.keys(localStorage).some(key => key.includes('Pelucas-Producto-'))) {
	// 		Swal.fire({
	// 			title: 'Carrito de compras!',
	// 			text: "Su pedido está vacío, no hay productos seleccionados.",
	// 			icon: 'warning',
	// 			confirmButtonColor: '#3085d6',
	// 			confirmButtonText: 'Continuar'
	// 		});
	// 		document.querySelector('.carga').style.display = 'none';
	// 		return;
	// 	}

	// 	const formaPago = document.getElementById("formapago").value;

	// 	if (formaPago != 1) {
	// 		// === Pedido normal ===
	// 		const formData = new FormData();

	// 		formData.append('idProducto', document.getElementById("idProductos").value);
	// 		formData.append('documento', USUARIO_LOGIN.documento);
	// 		formData.append('correo', USUARIO_LOGIN.correo);
	// 		formData.append('telefono', USUARIO_LOGIN.telefono);
	// 		formData.append('nombres', USUARIO_LOGIN.nombres);
	// 		formData.append('pApellido', USUARIO_LOGIN.pApellido);
	// 		formData.append('constancia', document.getElementById('constancia')?.files[0] || '');
	// 		formData.append('cupon', document.getElementById("cupon").value);
	// 		formData.append('cantidades', CANTIDADES);
	// 		formData.append('comision', COMISION_TOTAL);
	// 		formData.append('descuento', DESCUENTO);
	// 		formData.append('descuentoProductos', document.getElementById("descuentoProductos").value);
	// 		formData.append('subtotal', SUBTOTAL);
	// 		formData.append('total', TOTAL);
	// 		formData.append('observacion', document.getElementById("observaciones").value);
	// 		formData.append('codigo', CODIGO);
	// 		// formData.append('fechaEntrega', document.getElementById("fechaEntrega").value);
	// 		formData.append('terminos', document.getElementById("terminos").checked);
	// 		formData.append('costoEnvio', COSTO_ZONA_REPARTO);
	// 		// formData.append('misDireccionesRecojo', document.getElementById("misDireccionesRecojo").value);
	// 		// formData.append('misDireccionesDestino', document.getElementById("misDireccionesDestino").value);
	// 		formData.append('misComprobantes', document.getElementById("misComprobantes").value || null);

	// 		// Campos tipo objeto
	// 		formData.append('usuario', JSON.stringify({
	// 			idUsuario: USUARIO_LOGIN.idUsuario
	// 		}));
	// 		formData.append('entrega', JSON.stringify({
	// 			idEntrega: document.getElementById("entrega").value
	// 		}));
	// 		formData.append('formapago', JSON.stringify({
	// 			idFormaPago: formaPago
	// 		}));

	// 		// Comprobante
	// 		formData.append('comprobante', JSON.stringify({
	// 			razonSocial: document.getElementById("fnombres").value,
	// 			documento: document.getElementById("bdocumento").value,
	// 			nombres: document.getElementById("bnombres").value,
	// 			ruc: document.getElementById("fdocumento").value,
	// 			direccion: document.getElementById("fdireccion").value,
	// 			usuario: {
	// 				idUsuario: USUARIO_LOGIN.idUsuario
	// 			},
	// 			// ubigeo: {
	// 			// 	idUbigeo: document.getElementById("fdistrito").value
	// 			// },
	// 			ptipo: {
	// 				idParametro: document.getElementById("tipocomprobante").value
	// 			} // 👈 importante
	// 		}));

	// 		// Destino
	// 		formData.append('destino', JSON.stringify({
	// 			idDestino: document.getElementById("anteriores").value,
	// 			alias: document.getElementById("ddireccion").value,
	// 			nombres: document.getElementById("dnombres").value,
	// 			apellidos: document.getElementById("dapellidos").value,
	// 			dni: document.getElementById("ddocumento").value,
	// 			direccion: document.getElementById("ddireccion").value,
	// 			referencia: document.getElementById("dreferencia").value,
	// 			telefono: document.getElementById("dtelefono").value,
	// 			latitud: document.getElementById("dlatitud").value,
	// 			longitud: document.getElementById("dlongitud").value,
	// 			usuario: {
	// 				idUsuario: USUARIO_LOGIN.idUsuario
	// 			},
	// 			ubigeo: {
	// 				idUbigeo: document.getElementById("dubigeo").value
	// 			}
	// 		}));

	// 		// Recojo
	// 		formData.append('recojo', JSON.stringify({
	// 			dni: document.getElementById("rdocumento").value,
	// 			nombres: document.getElementById("rnombres").value,
	// 			apellidos: document.getElementById("rapellidos").value,
	// 			telefono: document.getElementById("rtelefono").value,
	// 			usuario: {
	// 				idUsuario: USUARIO_LOGIN.idUsuario
	// 			},
	// 			tienda: {
	// 				idTienda: document.getElementById("tienda").value
	// 			}
	// 		}));

	// 		// Agencia
	// 		formData.append('agencia', JSON.stringify({
	// 			idAgencia: document.getElementById("anteriores").value,
	// 			agencia: document.getElementById("agencia").value,
	// 			direccion: document.getElementById("adireccion").value,
	// 			nombres: document.getElementById("anombres").value,
	// 			apellidos: document.getElementById("aapellidos").value,
	// 			dni: document.getElementById("adocumento").value,
	// 			telefono: document.getElementById("atelefono").value,
	// 			usuario: {
	// 				idUsuario: USUARIO_LOGIN.idUsuario
	// 			},
	// 			ubigeo: {
	// 				idUbigeo: document.getElementById("adistrito").value
	// 			}
	// 		}));

	// 		// 👇 Aquí hacemos el envío con fetch
	// 		try {
	// 			const res = await fetch(`${BASE_URL}api/publico/pedido/guardar`, {
	// 				method: 'POST',
	// 				body: formData
	// 			});

	// 			const data = await res.json();

	// 			if (data.status === "exito") {
	// 				document.querySelector('.carga').style.display = 'none';
	// 				Swal.fire({
	// 					title: 'Carrito de compras!',
	// 					text: "¡Pedido realizado con éxito!",
	// 					icon: 'success',
	// 					confirmButtonColor: '#3085d6',
	// 					confirmButtonText: 'Continuar'
	// 				}).then(() => {
	// 					localStorage.clear();
	// 					window.location.href = `${BASE_URL}pedido/${data.pedido.idPedido}`;
	// 				});
	// 			} else {
	// 				throw new Error("Error en respuesta del servidor");
	// 			}

	// 		} catch (error) {
	// 			console.error("Error al procesar el pedido:", error);
	// 			document.querySelector('.carga').style.display = 'none';
	// 			Swal.fire('Error', 'Hubo un problema al guardar el pedido.', 'error');
	// 		}

	// 	} else {
	// 		// === Pago IZIPAY ===
	// 		try {
	// 			const payload = {
	// 				idProductoTallas: document.getElementById("idProductoTallas").value,
	// 				documento: USUARIO_LOGIN.documento,
	// 				correo: USUARIO_LOGIN.correo,
	// 				telefono: USUARIO_LOGIN.telefono,
	// 				nombres: USUARIO_LOGIN.nombres,
	// 				pApellido: USUARIO_LOGIN.pApellido,
	// 				cupon: document.getElementById("cupon").value,
	// 				cantidades: document.getElementById("cantidades").value,
	// 				comision: COMISION_TOTAL,
	// 				descuento: DESCUENTO,
	// 				descuentoProductos: document.getElementById("descuentoProductos").value,
	// 				subtotal: SUBTOTAL,
	// 				total: TOTAL,
	// 				observacion: document.getElementById("observaciones").value,
	// 				codigo: CODIGO,
	// 				fechaEntrega: document.getElementById("fechaEntrega").value,
	// 				terminos: document.getElementById("terminos").checked,
	// 				costoEnvio: COSTO_ZONA_REPARTO,
	// 				usuario: {
	// 					idUsuario: USUARIO_LOGIN.idUsuario
	// 				},
	// 				formapago: {
	// 					idFormaPago: formaPago
	// 				},
	// 				entrega: {
	// 					idEntrega: document.getElementById("entrega").value
	// 				},
	// 				comprobante: {
	// 					razonSocial: document.getElementById("fnombres").value,
	// 					documento: document.getElementById("bdocumento").value,
	// 					nombres: document.getElementById("bnombres").value,
	// 					ruc: document.getElementById("fdocumento").value,
	// 					direccion: document.getElementById("fdireccion").value,
	// 					usuario: {
	// 						idUsuario: USUARIO_LOGIN.idUsuario
	// 					},
	// 					ubigeo: {
	// 						idUbigeo: document.getElementById("fdistrito").value
	// 					},
	// 					ptipo: {
	// 						idParametro: document.getElementById("tipocomprobante").value
	// 					}
	// 				}
	// 			};

	// 			const res = await fetch(`${BASE_URL}Front/generaToken`, {
	// 				method: 'POST',
	// 				headers: {
	// 					'Content-Type': 'application/json'
	// 				},
	// 				body: JSON.stringify(payload)
	// 			});

	// 			const data = await res.json();
	// 			console.log(data);

	// 			if (data.status === 'exito') {
	// 				document.querySelector('.carga').style.display = 'none';
	// 				// Aquí puedes reactivar tu integración con IZIPAY
	// 			} else {
	// 				Swal.fire('Carrito de compras!', 'Errores encontrados, por favor intente nuevamente!', 'warning');
	// 				document.querySelector('.carga').style.display = 'none';
	// 			}
	// 		} catch (error) {
	// 			console.error("Error en Izipay:", error);
	// 			Swal.fire('Error', 'No se pudo generar el token de pago.', 'error');
	// 			document.querySelector('.carga').style.display = 'none';
	// 		}
	// 	}
	// }

	// async function procesarPago() {
	// 	document.querySelector('.carga').style.display = 'block';
	// 	const CODIGO = localStorage.getItem('codigo-compra');

	// 	// 🛒 Verificar carrito vacío
	// 	if (!Object.keys(localStorage).some(key => key.includes('Pelucas-Producto-'))) {
	// 		Swal.fire({
	// 			title: 'Carrito de compras!',
	// 			text: "Su pedido está vacío, no hay productos seleccionados.",
	// 			icon: 'warning',
	// 			confirmButtonColor: '#3085d6',
	// 			confirmButtonText: 'Continuar'
	// 		});
	// 		document.querySelector('.carga').style.display = 'none';
	// 		return;
	// 	}

	// 	const formaPago = document.getElementById("formapago")?.value || null;

	// 	// 🧾 Pedido normal (sin Izipay)
	// 	if (formaPago != 1) {
	// 		const formData = new FormData();

	// 		// ✅ Helper seguro
	// 		const appendIfExists = (key, value) => {
	// 			if (value !== undefined && value !== null && value !== '') {
	// 				formData.append(key, value);
	// 			}
	// 		};

	// 		// Datos simples
	// 		appendIfExists('idProducto', document.getElementById("idProductos")?.value);
	// 		appendIfExists('documento', USUARIO_LOGIN?.documento);
	// 		appendIfExists('correo', USUARIO_LOGIN?.correo);
	// 		appendIfExists('telefono', USUARIO_LOGIN?.telefono);
	// 		appendIfExists('nombres', USUARIO_LOGIN?.nombres);
	// 		appendIfExists('pApellido', USUARIO_LOGIN?.pApellido);
	// 		appendIfExists('constancia', document.getElementById('constancia')?.files?.[0]);
	// 		appendIfExists('cupon', document.getElementById("cupon")?.value);
	// 		appendIfExists('cantidades', typeof CANTIDADES !== "undefined" ? CANTIDADES : '');
	// 		appendIfExists('comision', typeof COMISION_TOTAL !== "undefined" ? COMISION_TOTAL : 0);
	// 		appendIfExists('descuento', typeof DESCUENTO !== "undefined" ? DESCUENTO : 0);
	// 		appendIfExists('descuentoProductos', document.getElementById("descuentoProductos")?.value);
	// 		appendIfExists('subtotal', typeof SUBTOTAL !== "undefined" ? SUBTOTAL : 0);
	// 		appendIfExists('total', typeof TOTAL !== "undefined" ? TOTAL : 0);
	// 		appendIfExists('observacion', document.getElementById("observaciones")?.value);
	// 		appendIfExists('codigo', CODIGO);
	// 		appendIfExists('terminos', document.getElementById("terminos")?.checked ? 'on' : '');
	// 		appendIfExists('costoEnvio', typeof COSTO_ZONA_REPARTO !== "undefined" ? COSTO_ZONA_REPARTO : 0);
	// 		appendIfExists('misComprobantes', document.getElementById("misComprobantes")?.value);

	// 		// Objetos
	// 		appendIfExists('usuario', JSON.stringify({
	// 			idUsuario: USUARIO_LOGIN?.idusuario
	// 		}));
	// 		appendIfExists('entrega', JSON.stringify({
	// 			idEntrega: document.getElementById("entrega")?.value
	// 		}));
	// 		appendIfExists('formapago', JSON.stringify({
	// 			idFormaPago: formaPago
	// 		}));

	// 		// Comprobante
	// 		if (document.getElementById("tipocomprobante")) {
	// 			appendIfExists('comprobante', JSON.stringify({
	// 				razonSocial: document.getElementById("fnombres")?.value,
	// 				documento: document.getElementById("bdocumento")?.value,
	// 				nombres: document.getElementById("bnombres")?.value,
	// 				ruc: document.getElementById("fdocumento")?.value,
	// 				direccion: document.getElementById("fdireccion")?.value,
	// 				usuario: {
	// 					idUsuario: USUARIO_LOGIN?.idUsuario
	// 				},
	// 				ptipo: {
	// 					idParametro: document.getElementById("tipocomprobante")?.value
	// 				}
	// 			}));
	// 		}

	// 		// 🚀 Envío con fetch
	// 		// try {
	// 		// 	console.log("🧾 Enviando datos:", Object.fromEntries(formData.entries()));

	// 		// 	const res = await fetch(`${BASE_URL}api/publico/pedido/guardar`, {
	// 		// 		method: 'POST',
	// 		// 		body: formData
	// 		// 	});

	// 		// 	const data = await res.json();
	// 		// 	console.log("📥 Respuesta servidor:", data);

	// 		// 	if (data.status === "exito") {
	// 		// 		document.querySelector('.carga').style.display = 'none';
	// 		// 		await Swal.fire({
	// 		// 			title: 'Carrito de compras!',
	// 		// 			text: "¡Pedido realizado con éxito!",
	// 		// 			icon: 'success',
	// 		// 			confirmButtonColor: '#3085d6',
	// 		// 			confirmButtonText: 'Continuar'
	// 		// 		});
	// 		// 		localStorage.clear();
	// 		// 		window.location.href = `${BASE_URL}pedido/${data.pedido.idPedido}`;
	// 		// 	} else {
	// 		// 		throw new Error(data.mensaje || "Error en respuesta del servidor");
	// 		// 	}

	// 		// } catch (error) {
	// 		// 	console.error("❌ Error al procesar el pedido:", error);
	// 		// 	document.querySelector('.carga').style.display = 'none';
	// 		// 	Swal.fire('Error', 'Hubo un problema al guardar el pedido.', 'error');
	// 		// }
	// 		console.log("🧾 Enviando datos:", Object.fromEntries(formData.entries()));
	// 		//document.querySelector('.carga').style.display = 'block'; // 🔥 Mostrar loader antes del envío

	// 		fetch(`${BASE_URL}api/publico/pedido/guardar`, {
	// 				method: 'POST',
	// 				body: formData
	// 			})
	// 			.then(res => res.text())
	// 			.then(async text => {
	// 				console.log("📜 Respuesta cruda del servidor:", text);

	// 				let data;
	// 				try {
	// 					data = JSON.parse(text);
	// 				} catch (e) {
	// 					throw new Error("La respuesta del servidor no es JSON válido.");
	// 				}

	// 				console.log("📥 Respuesta parseada:", data);

	// 				if (data.status === "exito") {
	// 					// 🔥 Ocultamos el loader ANTES del Swal
	// 					document.querySelector('.carga').style.display = 'none';

	// 					await Swal.fire({
	// 						title: 'Carrito de compras!',
	// 						text: "¡Pedido realizado con éxito!",
	// 						icon: 'success',
	// 						confirmButtonColor: '#3085d6',
	// 						confirmButtonText: 'Continuar'
	// 					});

	// 					localStorage.clear();
	// 					const destino = `${BASE_URL}pedido/${data.pedido.idPedido}`;
	// 					console.log("➡️ Redirigiendo a:", destino);
	// 					window.location.href = destino;

	// 				} else {
	// 					document.querySelector('.carga').style.display = 'none';
	// 					Swal.fire('Error', data.mensaje || 'No se pudo guardar el pedido.', 'error');
	// 				}
	// 			})
	// 			.catch(error => {
	// 				console.error("❌ Error al procesar el pedido:", error);
	// 				document.querySelector('.carga').style.display = 'none';
	// 				Swal.fire('Error', 'Hubo un problema al guardar el pedido.', 'error');
	// 			});




	// 	} else {
	// 		// 💳 Pago con IZIPAY
	// 		try {
	// 			const payload = {
	// 				documento: USUARIO_LOGIN?.documento,
	// 				correo: USUARIO_LOGIN?.correo,
	// 				telefono: USUARIO_LOGIN?.telefono,
	// 				nombres: USUARIO_LOGIN?.nombres,
	// 				pApellido: USUARIO_LOGIN?.pApellido,
	// 				cupon: document.getElementById("cupon")?.value,
	// 				cantidades: document.getElementById("cantidades")?.value,
	// 				comision: typeof COMISION_TOTAL !== "undefined" ? COMISION_TOTAL : 0,
	// 				descuento: typeof DESCUENTO !== "undefined" ? DESCUENTO : 0,
	// 				descuentoProductos: document.getElementById("descuentoProductos")?.value,
	// 				subtotal: typeof SUBTOTAL !== "undefined" ? SUBTOTAL : 0,
	// 				total: typeof TOTAL !== "undefined" ? TOTAL : 0,
	// 				codigo: CODIGO,
	// 				terminos: document.getElementById("terminos")?.checked,
	// 				costoEnvio: typeof COSTO_ZONA_REPARTO !== "undefined" ? COSTO_ZONA_REPARTO : 0,
	// 				usuario: {
	// 					idUsuario: USUARIO_LOGIN?.idUsuario
	// 				},
	// 				formapago: {
	// 					idFormaPago: formaPago
	// 				},
	// 				entrega: {
	// 					idEntrega: document.getElementById("entrega")?.value
	// 				},
	// 			};

	// 			const res = await fetch(`${BASE_URL}Front/generaToken`, {
	// 				method: 'POST',
	// 				headers: {
	// 					'Content-Type': 'application/json'
	// 				},
	// 				body: JSON.stringify(payload)
	// 			});

	// 			const data = await res.json();
	// 			console.log("💳 Respuesta Izipay:", data);

	// 			document.querySelector('.carga').style.display = 'none';

	// 			if (data.status === 'exito') {
	// 				// Aquí continuarías con el flujo de pago Izipay
	// 				console.log("✅ Token generado correctamente.");
	// 			} else {
	// 				Swal.fire('Carrito de compras!', 'Errores encontrados, por favor intente nuevamente!', 'warning');
	// 			}

	// 		} catch (error) {
	// 			console.error("💥 Error en Izipay:", error);
	// 			Swal.fire('Error', 'No se pudo generar el token de pago.', 'error');
	// 			document.querySelector('.carga').style.display = 'none';
	// 		}
	// 	}
	// }
	// function procesarPago() {
	// 	document.querySelector('.carga').style.display = 'block';
	// 	const CODIGO = localStorage.getItem('codigo-compra');

	// 	// 🛒 Verificar carrito vacío
	// 	if (!Object.keys(localStorage).some(key => key.includes('Pelucas-Producto-'))) {
	// 		Swal.fire({
	// 			title: 'Carrito de compras!',
	// 			text: "Su pedido está vacío, no hay productos seleccionados.",
	// 			icon: 'warning',
	// 			confirmButtonColor: '#3085d6',
	// 			confirmButtonText: 'Continuar'
	// 		});
	// 		document.querySelector('.carga').style.display = 'none';
	// 		return;
	// 	}

	// 	const formaPago = document.getElementById("formapago")?.value || null;

	// 	// 🧾 Pedido normal (sin Izipay)
	// 	if (formaPago != 1) {
	// 		const formData = new FormData();

	// 		// ✅ Helper seguro
	// 		const appendIfExists = (key, value) => {
	// 			if (value !== undefined && value !== null && value !== '') {
	// 				formData.append(key, value);
	// 			}
	// 		};

	// 		// Datos simples
	// 		appendIfExists('idProducto', document.getElementById("idProductos")?.value);
	// 		appendIfExists('documento', USUARIO_LOGIN?.documento);
	// 		appendIfExists('correo', USUARIO_LOGIN?.correo);
	// 		appendIfExists('telefono', USUARIO_LOGIN?.telefono);
	// 		appendIfExists('nombres', USUARIO_LOGIN?.nombres);
	// 		appendIfExists('pApellido', USUARIO_LOGIN?.pApellido);
	// 		appendIfExists('constancia', document.getElementById('constancia')?.files?.[0]);
	// 		appendIfExists('cupon', document.getElementById("cupon")?.value);
	// 		appendIfExists('cantidades', typeof CANTIDADES !== "undefined" ? CANTIDADES : '');
	// 		appendIfExists('comision', typeof COMISION_TOTAL !== "undefined" ? COMISION_TOTAL : 0);
	// 		appendIfExists('descuento', typeof DESCUENTO !== "undefined" ? DESCUENTO : 0);
	// 		appendIfExists('descuentoProductos', document.getElementById("descuentoProductos")?.value);
	// 		appendIfExists('subtotal', typeof SUBTOTAL !== "undefined" ? SUBTOTAL : 0);
	// 		appendIfExists('total', typeof TOTAL !== "undefined" ? TOTAL : 0);
	// 		appendIfExists('observacion', document.getElementById("observaciones")?.value);
	// 		appendIfExists('codigo', CODIGO);
	// 		appendIfExists('terminos', document.getElementById("terminos")?.checked ? 'on' : '');
	// 		appendIfExists('costoEnvio', typeof COSTO_ZONA_REPARTO !== "undefined" ? COSTO_ZONA_REPARTO : 0);
	// 		appendIfExists('misComprobantes', document.getElementById("misComprobantes")?.value);

	// 		// Objetos
	// 		appendIfExists('usuario', JSON.stringify({
	// 			idUsuario: USUARIO_LOGIN?.idusuario
	// 		}));
	// 		appendIfExists('entrega', JSON.stringify({
	// 			idEntrega: document.getElementById("entrega")?.value
	// 		}));
	// 		appendIfExists('formapago', JSON.stringify({
	// 			idFormaPago: formaPago
	// 		}));

	// 		// Comprobante
	// 		if (document.getElementById("tipocomprobante")) {
	// 			appendIfExists('comprobante', JSON.stringify({
	// 				razonSocial: document.getElementById("fnombres")?.value,
	// 				documento: document.getElementById("bdocumento")?.value,
	// 				nombres: document.getElementById("bnombres")?.value,
	// 				ruc: document.getElementById("fdocumento")?.value,
	// 				direccion: document.getElementById("fdireccion")?.value,
	// 				usuario: {
	// 					idUsuario: USUARIO_LOGIN?.idUsuario
	// 				},
	// 				ptipo: {
	// 					idParametro: document.getElementById("tipocomprobante")?.value
	// 				}
	// 			}));
	// 		}

	// 		console.log("🧾 Enviando datos:", Object.fromEntries(formData.entries()));

	// 		fetch(`${BASE_URL}api/publico/pedido/guardar`, {
	// 				method: 'POST',
	// 				body: formData
	// 			})
	// 			.then(res => res.text())
	// 			.then(text => {
	// 				console.log("📜 Respuesta cruda del servidor:", text);

	// 				let data;
	// 				try {
	// 					data = JSON.parse(text);
	// 				} catch (e) {
	// 					throw new Error("La respuesta del servidor no es JSON válido.");
	// 				}

	// 				console.log("📥 Respuesta parseada:", data);
	// 				document.querySelector('.carga').style.display = 'none';

	// 				if (data.status === "exito") {

	// 					Swal.fire({
	// 						title: 'Carrito de compras!',
	// 						text: "¡Pedido realizado con éxito!",
	// 						icon: 'success',
	// 						confirmButtonColor: '#3085d6',
	// 						confirmButtonText: 'Continuar'
	// 					}).then(() => {
	// 						//localStorage.clear();
	// 						const destino = `${BASE_URL}pedido/${data.pedido.idPedido}`;
	// 						console.log("➡️ Redirigiendo a:", destino);
	// 						// window.location.href = destino;
	// 					});
	// 				} else {
	// 					document.querySelector('.carga').style.display = 'none';
	// 					Swal.fire('Error', data.mensaje || 'No se pudo guardar el pedido.', 'error');
	// 				}
	// 			})
	// 			.catch(error => {
	// 				console.error("❌ Error al procesar el pedido:", error);
	// 				document.querySelector('.carga').style.display = 'none';
	// 				Swal.fire('Error', 'Hubo un problema al guardar el pedido.', 'error');
	// 			});

	// 	} else {
	// 		// 💳 Pago con IZIPAY
	// 		const payload = {
	// 			documento: USUARIO_LOGIN?.documento,
	// 			correo: USUARIO_LOGIN?.correo,
	// 			telefono: USUARIO_LOGIN?.telefono,
	// 			nombres: USUARIO_LOGIN?.nombres,
	// 			pApellido: USUARIO_LOGIN?.pApellido,
	// 			cupon: document.getElementById("cupon")?.value,
	// 			cantidades: document.getElementById("cantidades")?.value,
	// 			comision: typeof COMISION_TOTAL !== "undefined" ? COMISION_TOTAL : 0,
	// 			descuento: typeof DESCUENTO !== "undefined" ? DESCUENTO : 0,
	// 			descuentoProductos: document.getElementById("descuentoProductos")?.value,
	// 			subtotal: typeof SUBTOTAL !== "undefined" ? SUBTOTAL : 0,
	// 			total: typeof TOTAL !== "undefined" ? TOTAL : 0,
	// 			codigo: CODIGO,
	// 			terminos: document.getElementById("terminos")?.checked,
	// 			costoEnvio: typeof COSTO_ZONA_REPARTO !== "undefined" ? COSTO_ZONA_REPARTO : 0,
	// 			usuario: {
	// 				idUsuario: USUARIO_LOGIN?.idUsuario
	// 			},
	// 			formapago: {
	// 				idFormaPago: formaPago
	// 			},
	// 			entrega: {
	// 				idEntrega: document.getElementById("entrega")?.value
	// 			}
	// 		};

	// 		fetch(`${BASE_URL}Front/generaToken`, {
	// 				method: 'POST',
	// 				headers: {
	// 					'Content-Type': 'application/json'
	// 				},
	// 				body: JSON.stringify(payload)
	// 			})
	// 			.then(res => res.json())
	// 			.then(data => {
	// 				console.log("💳 Respuesta Izipay:", data);
	// 				document.querySelector('.carga').style.display = 'none';

	// 				if (data.status === 'exito') {
	// 					console.log("✅ Token generado correctamente.");
	// 				} else {
	// 					Swal.fire('Carrito de compras!', 'Errores encontrados, por favor intente nuevamente!', 'warning');
	// 				}
	// 			})
	// 			.catch(error => {
	// 				console.error("💥 Error en Izipay:", error);
	// 				document.querySelector('.carga').style.display = 'none';
	// 				Swal.fire('Error', 'No se pudo generar el token de pago.', 'error');
	// 			});
	// 	}
	// }



	// function procesarPago() {
	// 	$('.carga').show();
	// 	const CODIGO = localStorage.getItem('codigo-compra');

	// 	//Verificar si el carrito está vacio
	// 	if (!Object.keys(localStorage).some(key => key.includes('Pelucas-Producto-'))) {
	// 		Swal.fire({
	// 			title: 'Carrito de compras!',
	// 			text: "Su pedido esta vacío, no hay productos seleccionados.",
	// 			icon: 'warning',
	// 			showCancelButton: false,
	// 			confirmButtonColor: '#3085d6',
	// 			confirmButtonText: 'Continuar'
	// 		})
	// 		return false;
	// 	}

	// 	if ($("#formapago").val() != 1) {


	// 		let formData = new FormData();

	// 		formData.append('idProductoTallas', $("#idProductoTallas").val());
	// 		formData.append('documento', USUARIO_LOGIN.documento);
	// 		formData.append('correo', USUARIO_LOGIN.correo);
	// 		formData.append('telefono', USUARIO_LOGIN.telefono);
	// 		formData.append('nombres', USUARIO_LOGIN.nombres);
	// 		formData.append('pApellido', USUARIO_LOGIN.pApellido);
	// 		formData.append('constancia', $('#constancia')[0].files[0]);
	// 		formData.append('cupon', $("#cupon").val());
	// 		formData.append('cantidades', CANTIDADES);
	// 		formData.append('comision', COMISION_TOTAL);
	// 		formData.append('descuento', DESCUENTO);
	// 		formData.append('descuentoProductos', $("#descuentoProductos").val());
	// 		formData.append('subtotal', SUBTOTAL);
	// 		formData.append('total', TOTAL);
	// 		formData.append('observacion', $("#observaciones").val());
	// 		formData.append('codigo', CODIGO);
	// 		formData.append('fechaEntrega', $("#fechaEntrega").val());
	// 		formData.append('terminos', $("#terminos").prop("checked"));
	// 		formData.append('costoEnvio', COSTO_ZONA_REPARTO);
	// 		formData.append('misDireccionesRecojo', $("#misDireccionesRecojo").val());
	// 		formData.append('misDireccionesDestino', $("#misDireccionesDestino").val());
	// 		formData.append('misComprobantes', $("#misComprobantes").val() || null);

	// 		formData.append('usuario', JSON.stringify({
	// 			idUsuario: USUARIO_LOGIN.idUsuario
	// 		}));
	// 		formData.append('entrega', JSON.stringify({
	// 			idEntrega: $("#entrega").val()
	// 		}));
	// 		formData.append('formapago', JSON.stringify({
	// 			idFormaPago: $("#formapago").val()
	// 		}));
	// 		formData.append('comprobante', JSON.stringify({
	// 			razonSocial: $("#fnombres").val(),
	// 			documento: $("#bdocumento").val(),
	// 			nombres: $("#bnombres").val(),
	// 			ruc: $("#fdocumento").val(),
	// 			direccion: $("#fdireccion").val(),
	// 			usuario: {
	// 				idUsuario: USUARIO_LOGIN.idUsuario
	// 			},
	// 			ubigeo: {
	// 				idUbigeo: $("#fdistrito").val()
	// 			},
	// 			ptipo: {
	// 				idParametro: $("#tipocomprobante").val()
	// 			}
	// 		}));
	// 		formData.append('destino', JSON.stringify({
	// 			idDestino: $("#anteriores").val(),
	// 			alias: $("#ddireccion").val(),
	// 			nombres: $("#dnombres").val(),
	// 			apellidos: $("#dapellidos").val(),
	// 			dni: $("#ddocumento").val(),
	// 			direccion: $("#ddireccion").val(),
	// 			referencia: $("#dreferencia").val(),
	// 			telefono: $("#dtelefono").val(),
	// 			latitud: $("#dlatitud").val(),
	// 			longitud: $("#dlongitud").val(),
	// 			usuario: {
	// 				idUsuario: USUARIO_LOGIN.idUsuario
	// 			},
	// 			ubigeo: {
	// 				idUbigeo: $("#dubigeo").val()
	// 			}
	// 		}));

	// 		formData.append('recojo', JSON.stringify({
	// 			dni: $("#rdocumento").val(),
	// 			nombres: $("#rnombres").val(),
	// 			apellidos: $("#rapellidos").val(),
	// 			telefono: $("#rtelefono").val(),
	// 			usuario: {
	// 				idUsuario: USUARIO_LOGIN.idUsuario
	// 			},
	// 			tienda: {
	// 				idTienda: $("#tienda").val()
	// 			}
	// 		}));

	// 		formData.append('agencia', JSON.stringify({
	// 			idAgencia: $("#anteriores").val(),
	// 			agencia: $("#agencia").val(),
	// 			direccion: $("#adireccion").val(),
	// 			nombres: $("#anombres").val(),
	// 			apellidos: $("#aapellidos").val(),
	// 			dni: $("#adocumento").val(),
	// 			telefono: $("#atelefono").val(),
	// 			usuario: {
	// 				idUsuario: USUARIO_LOGIN.idUsuario
	// 			},
	// 			ubigeo: {
	// 				idUbigeo: $("#adistrito").val()
	// 			}
	// 		}));

	// 		$.ajax({
	// 			url: `${API_URL}publico/pedido/guardar`,
	// 			type: "post",
	// 			data: formData,
	// 			dataType: 'json',
	// 			contentType: false,
	// 			processData: false
	// 		}).done(function(res) {

	// 			if (res.status == "exito") {
	// 				$('.carga').hide();
	// 				Swal.fire({
	// 					title: 'Carrito de compras!',
	// 					text: "¡Pedido realizado con éxito!",
	// 					icon: 'success',
	// 					showCancelButton: false,
	// 					confirmButtonColor: '#3085d6',
	// 					confirmButtonText: 'Continuar'
	// 				}).then((result) => {
	// 					localStorage.clear();
	// 					window.location.href = `${BASE_URL}pedido/${res.pedido.idPedido}`;
	// 				});
	// 			}

	// 		})
	// 	} else {
	// 		//IZIPAY

	// 		data = {
	// 			'idProductoTallas': $("#idProductoTallas").val(),
	// 			'documento': USUARIO_LOGIN.documento,
	// 			'correo': USUARIO_LOGIN.correo,
	// 			'telefono': USUARIO_LOGIN.telefono,
	// 			'nombres': USUARIO_LOGIN.nombres,
	// 			'pApellido': USUARIO_LOGIN.pApellido,
	// 			'cupon': $("#cupon").val(),
	// 			'cantidades': $("#cantidades").val(),
	// 			'comision': COMISION_TOTAL,
	// 			'descuento': DESCUENTO,
	// 			'descuentoProductos': $("#descuentoProductos").val(),
	// 			'subtotal': SUBTOTAL,
	// 			'total': TOTAL,
	// 			'observacion': $("#observaciones").val(),
	// 			'codigo': CODIGO,
	// 			'fechaEntrega': $("#fechaEntrega").val(),
	// 			'terminos': $("#terminos").prop("checked"),
	// 			'costoEnvio': COSTO_ZONA_REPARTO,
	// 			'misDireccionesRecojo': $("#misDireccionesRecojo").val(),
	// 			'misDireccionesDestino': $("#misDireccionesDestino").val(),
	// 			'misComprobantes': $("#misComprobantes").val() || null,

	// 			'usuario': {
	// 				'idUsuario': USUARIO_LOGIN.idUsuario
	// 			},
	// 			'entrega': {
	// 				'idEntrega': $("#entrega").val()
	// 			},
	// 			'formapago': {
	// 				'idFormaPago': $("#formapago").val()
	// 			},
	// 			'comprobante': {
	// 				'razonSocial': $("#fnombres").val(),
	// 				'documento': $("#bdocumento").val(),
	// 				'nombres': $("#bnombres").val(),
	// 				'ruc': $("#fdocumento").val(),
	// 				'direccion': $("#fdireccion").val(),
	// 				'usuario': {
	// 					'idUsuario': USUARIO_LOGIN.idUsuario
	// 				},
	// 				'ubigeo': {
	// 					'idUbigeo': $("#fdistrito").val()
	// 				},
	// 				'ptipo': {
	// 					'idParametro': $("#tipocomprobante").val()
	// 				}
	// 			},
	// 			'destino': {
	// 				'idDestino': $("#anteriores").val(),
	// 				'alias': $("#ddireccion").val(),
	// 				'nombres': $("#dnombres").val(),
	// 				'apellidos': $("#dapellidos").val(),
	// 				'dni': $("#ddocumento").val(),
	// 				'direccion': $("#ddireccion").val(),
	// 				'referencia': $("#dreferencia").val(),
	// 				'telefono': $("#dtelefono").val(),
	// 				'latitud': $("#dlatitud").val(),
	// 				'longitud': $("#dlongitud").val(),
	// 				'usuario': {
	// 					'idUsuario': USUARIO_LOGIN.idUsuario
	// 				},
	// 				'ubigeo': {
	// 					'idUbigeo': $("#dubigeoTemporal").val()
	// 				}
	// 			},
	// 			'recojo': {
	// 				'dni': $("#rdocumento").val(),
	// 				'nombres': $("#rnombres").val(),
	// 				'apellidos': $("#rapellidos").val(),
	// 				'telefono': $("#rtelefono").val(),
	// 				'usuario': {
	// 					'idUsuario': USUARIO_LOGIN.idUsuario
	// 				},
	// 				'tienda': {
	// 					'idTienda': $("#tienda").val()
	// 				}
	// 			},
	// 			'agencia': {
	// 				'idAgencia': $("#anteriores").val(),
	// 				'agencia': $("#agencia").val(),
	// 				'direccion': $("#adireccion").val(),
	// 				'nombres': $("#anombres").val(),
	// 				'apellidos': $("#aapellidos").val(),
	// 				'dni': $("#adocumento").val(),
	// 				'telefono': $("#atelefono").val(),
	// 				'usuario': {
	// 					'idUsuario': USUARIO_LOGIN.idUsuario
	// 				},
	// 				'ubigeo': {
	// 					'idUbigeo': $("#adistrito").val()
	// 				}
	// 			}
	// 		}


	// 		$.ajax({
	// 			url: `${BASE_URL}Front/generaToken`,
	// 			type: "post",
	// 			data: data,
	// 			dataType: 'json',
	// 		}).done(function(res) {
	// 			console.log(res);
	// 			if (res.status == 'exito') {
	// 				$('.carga').hide();
	// 				KR.setFormConfig({
	// 						/* set the minimal configuration */
	// 						formToken: res.token,
	// 						'kr-language': res.lenguajeform,
	// 						/* to update initialization parameter */
	// 						'kr-public-key': res.publicKey,
	// 						// 'kr-post-url-success': `${BASE_URL}pago/procesado`,
	// 					})
	// 					.then(({
	// 						KR
	// 					}) => KR.addForm('#paymentForm')) /* create a payment form */
	// 					.then(({
	// 						KR,
	// 						result
	// 					}) => KR.showForm(result.formId)) /* show the payment form */


	// 				$(".carga").css("display", "none");
	// 				KR.onError(function(event) {
	// 					$(".carga").css("display", "none");
	// 				});
	// 				KR.button.onClick(async function() {
	// 					$(".carga").css("display", "block");
	// 				});
	// 				KR.onSubmit(function() {

	// 					window.location.href = `${BASE_URL}pedido/${res.pedido.idPedido}`;
	// 					localStorage.clear();

	// 				}).catch(({
	// 						KR,
	// 						result
	// 					}) => {}

	// 				);
	// 				let modalpago = new bootstrap.Modal(document.getElementById('modalpago'))
	// 				modalpago.show();

	// 			} else {
	// 				Swal.fire({
	// 					title: 'Carrito de compras!',
	// 					text: "Errores encontrados, por favor intente nuevamente!",
	// 					icon: 'warning',
	// 					showCancelButton: false,
	// 					confirmButtonColor: '#3085d6',
	// 					confirmButtonText: 'Continuar'
	// 				})
	// 				$(".carga").hide();

	// 			}

	// 		});
	// 	}
	// }



	function validarCupon() {
		$(".carga").show();

		const codigo = document.getElementById("cupon").value;

		$.ajax({
			url: `${BASE_URL}api/CuponController/validarCupon`,
			type: "post",
			data: {
				codigo
			},
			dataType: 'json',
		}).done(function(response) {
			removerClases();

			if (response.status === 'error') {
				$('#cupon').val('');
				return showErrores(response.errors);
			}

			const {
				cupon,
				productos
			} = response;
			let totalDescuento = 0;
			const descuentoProductos = [];
			let aplicable = false;

			// Recorrer el carrito desde localStorage
			for (let i = 0; i < localStorage.length; i++) {
				const key = localStorage.key(i);
				if (key.includes('Pelucas-Producto')) {
					const producto = JSON.parse(localStorage.getItem(key));
					const idProductoCarrito = producto.idProducto; // ahora usamos idproducto directamente

					// Verificar si el producto está en la lista de productos aplicables al cupón
					const item = productos.find(p => Number(p.idproducto) === Number(idProductoCarrito));

					if (item) {
						aplicable = true;
						const cantidadAplicable = Math.min(parseInt(producto.cantidad), parseInt(cupon.limite));
						const precio = parseFloat(producto.precioVenta) || 0;
						const descuentoPorcentaje = parseFloat(cupon.descuento) || 0;
						const descuento = precio * (descuentoPorcentaje / 100) * cantidadAplicable;

						totalDescuento += descuento;
						descuentoProductos.push(descuento.toFixed(2));
					} else {
						descuentoProductos.push('0.00');
					}
				}
			}

			if (!aplicable) {
				$('#cupon').val('');
				$("#cupon").addClass("is-invalid");
				$(".validacupon").addClass("invalid-feedback");
				$(".validacupon").html("No aplica a ningún producto seleccionado.");
				$(".validacupon").css("display", "block");

				Swal.fire({
					title: 'Cupón de descuento!',
					text: "El cupón ingresado no aplica para ningún producto seleccionado.",
					icon: 'warning',
					confirmButtonColor: '#3085d6',
					confirmButtonText: 'Continuar'
				}).then(() => {
					$('html, body').animate({
						scrollTop: 0
					}, 'slow');
				});

			} else {
				$('#cupon').attr("readonly", "readonly");
				$('#limpiaCupon').removeAttr("disabled");
				DESCUENTO = totalDescuento;
				$('#descuentoProductos').val(descuentoProductos);

				Swal.fire({
					title: 'Cupón de descuento!',
					text: "El cupón ha sido aplicado exitosamente.",
					icon: 'success',
					confirmButtonColor: '#3085d6',
					confirmButtonText: 'Continuar'
				});
			}

			showProductosCarrito();
		});
	}


	function limpiarCupon() {
		$(".carga").show();
		$("#cupon").val("");
		DESCUENTO = 0.00
		$("#cupon").removeAttr("readonly");
		showProductosCarrito();
	}

	async function procesarPago() {
		try {
			$('.carga').show();
			const CODIGO = localStorage.getItem('codigo-compra');

			// Verificar si el carrito está vacío
			if (!Object.keys(localStorage).some(key => key.includes('Pelucas-Producto-'))) {
				Swal.fire({
					title: 'Carrito de compras!',
					text: "Su pedido está vacío, no hay productos seleccionados.",
					icon: 'warning',
					confirmButtonColor: '#3085d6',
					confirmButtonText: 'Continuar'
				});
				$('.carga').hide();
				return;
			}

			const isIzipay = $("#formapago").val() == 1;

			if (!isIzipay) {
				// Pago normal con archivos
				const formData = new FormData();
				formData.append('idProductos', $("#idProductos").val());
				formData.append('documento', USUARIO_LOGIN.documento);
				formData.append('correo', USUARIO_LOGIN.correo);
				formData.append('telefono', USUARIO_LOGIN.telefono);
				formData.append('nombres', USUARIO_LOGIN.nombres);
				formData.append('pApellido', USUARIO_LOGIN.pApellido);
				formData.append('constancia', $('#constancia')[0].files[0]);
				formData.append('cupon', $("#cupon").val());
				formData.append('cantidades', CANTIDADES);
				formData.append('comision', COMISION_TOTAL);
				formData.append('descuento', DESCUENTO);
				formData.append('descuentoProductos', $("#descuentoProductos").val());
				formData.append('subtotal', SUBTOTAL);
				formData.append('total', TOTAL);
				formData.append('observacion', $("#observaciones").val());
				formData.append('codigo', CODIGO);
				formData.append('referencia', CODIGO);
				formData.append('fechaEntrega', $("#fechaEntrega").val() || new Date().toISOString().split('T')[0]);
				formData.append('terminos', $("#terminos").prop("checked"));
				formData.append('costoEnvio', COSTO_ZONA_REPARTO);
				formData.append('misComprobantes', $("#misComprobantes").val() || null);

				formData.append('usuario', JSON.stringify({
					idUsuario: USUARIO_LOGIN.idusuario
				}));
				formData.append('entrega', JSON.stringify({
					idEntrega: $("#entrega").val()
				}));
				formData.append('formapago', JSON.stringify({
					idFormaPago: $("#formapago").val()
				}));
				formData.append('comprobante', JSON.stringify({
					razonSocial: $("#fnombres").val(),
					documento: $("#bdocumento").val(),
					nombres: $("#bnombres").val(),
					ruc: $("#fdocumento").val(),
					direccion: $("#fdireccion").val(),
					usuario: {
						idUsuario: USUARIO_LOGIN.idUsuario
					},
					ubigeo: {
						idUbigeo: $("#fdistrito").val()
					},
					ptipo: {
						idParametro: $("#tipocomprobante").val()
					}
				}));
				formData.append('agencia', JSON.stringify({
					idAgencia: $("#anteriores").val(), // ID de la agencia
					agencia: $("#agencia").val(), // Nombre de la agencia
					direccion: $("#adireccion").val(), // Dirección de la agencia
					nombres: $("#anombres").val(), // Nombre del contacto
					apellidos: $("#aapellidos").val(), // Apellido del contacto
					dni: $("#adocumento").val(), // DNI del contacto
					telefono: $("#atelefono").val(), // Teléfono del contacto
					usuario: {
						idUsuario: USUARIO_LOGIN.idusuario
					},
					ubigeo: {
						idUbigeo: $("#adistrito").val() // ID del distrito
					},
					ptipo: {
						idParametro: $("#tipocomprobante").val() // Tipo de comprobante asociado
					}
				}));



				console.log([...formData.entries()].filter(([k, v]) => !(v instanceof File)));

				const response = await fetch(`${BASE_URL}api/publico/pedido/guardar`, {
					method: 'POST',
					body: formData
				});

				const res = await response.json();
				$('.carga').hide();

				if (res.status === 'exito') {
					Swal.fire({
						title: 'Carrito de compras!',
						text: "¡Pedido realizado con éxito!",
						icon: 'success',
						confirmButtonColor: '#3085d6',
						confirmButtonText: 'Continuar'
					}).then(() => {
						localStorage.clear();
						window.location.href = `${BASE_URL}mis-pedidos-detalle/${res.pedido.idpedido}`;
					});
				} else {
					Swal.fire('Error', 'No se pudo procesar el pedido. Intente nuevamente.', 'error');
				}

			} else {
				// Pago IZIPAY (sin archivos)
				// const formData = new FormData();
				// formData.append('idProductos', $("#idProductos").val());
				// formData.append('documento', USUARIO_LOGIN.documento);
				// formData.append('correo', USUARIO_LOGIN.correo);
				// formData.append('telefono', USUARIO_LOGIN.telefono);
				// formData.append('nombres', USUARIO_LOGIN.nombres);
				// formData.append('pApellido', USUARIO_LOGIN.pApellido);
				// formData.append('constancia', $('#constancia')[0].files[0]);
				// formData.append('cupon', $("#cupon").val());
				// formData.append('cantidades', CANTIDADES);
				// formData.append('comision', COMISION_TOTAL);
				// formData.append('descuento', DESCUENTO);
				// formData.append('descuentoProductos', $("#descuentoProductos").val());
				// formData.append('subtotal', SUBTOTAL);
				// formData.append('total', TOTAL);
				// formData.append('observacion', $("#observaciones").val());
				// formData.append('codigo', CODIGO);
				// formData.append('referencia', CODIGO);
				// formData.append('fechaEntrega', $("#fechaEntrega").val() || new Date().toISOString().split('T')[0]);
				// formData.append('terminos', $("#terminos").prop("checked"));
				// formData.append('costoEnvio', COSTO_ZONA_REPARTO);
				// formData.append('misComprobantes', $("#misComprobantes").val() || null);

				// formData.append('usuario', JSON.stringify({
				// 	idUsuario: USUARIO_LOGIN.idusuario
				// }));
				// formData.append('entrega', JSON.stringify({
				// 	idEntrega: $("#entrega").val()
				// }));
				// formData.append('formapago', JSON.stringify({
				// 	idFormaPago: $("#formapago").val()
				// }));
				// formData.append('comprobante', JSON.stringify({
				// 	razonSocial: $("#fnombres").val(),
				// 	documento: $("#bdocumento").val(),
				// 	nombres: $("#bnombres").val(),
				// 	ruc: $("#fdocumento").val(),
				// 	direccion: $("#fdireccion").val(),
				// 	usuario: {
				// 		idUsuario: USUARIO_LOGIN.idUsuario
				// 	},
				// 	ubigeo: {
				// 		idUbigeo: $("#fdistrito").val()
				// 	},
				// 	ptipo: {
				// 		idParametro: $("#tipocomprobante").val()
				// 	}
				// }));
				// formData.append('agencia', JSON.stringify({
				// 	idAgencia: $("#anteriores").val(), // ID de la agencia
				// 	agencia: $("#agencia").val(), // Nombre de la agencia
				// 	direccion: $("#adireccion").val(), // Dirección de la agencia
				// 	nombres: $("#anombres").val(), // Nombre del contacto
				// 	apellidos: $("#aapellidos").val(), // Apellido del contacto
				// 	dni: $("#adocumento").val(), // DNI del contacto
				// 	telefono: $("#atelefono").val(), // Teléfono del contacto
				// 	usuario: {
				// 		idUsuario: USUARIO_LOGIN.idusuario
				// 	},
				// 	ubigeo: {
				// 		idUbigeo: $("#adistrito").val() // ID del distrito
				// 	},
				// 	ptipo: {
				// 		idParametro: $("#tipocomprobante").val() // Tipo de comprobante asociado
				// 	}
				// }));
				// Funciones auxiliares

				// antiguo
				// 	const getValue = (id) => document.getElementById(id)?.value?.trim() || '';
				// 	const getChecked = (id) => document.getElementById(id)?.checked || false;

				// 	// Crear objeto JSON
				// 	const data = {
				// 		idProductos: getValue('idProductos'),
				// 		documento: USUARIO_LOGIN.documento,
				// 		correo: USUARIO_LOGIN.correo,
				// 		telefono: USUARIO_LOGIN.telefono,
				// 		nombres: USUARIO_LOGIN.nombres,
				// 		pApellido: USUARIO_LOGIN.pApellido,
				// 		cupon: getValue('cupon'),
				// 		cantidades: CANTIDADES,
				// 		comision: COMISION_TOTAL,
				// 		descuento: DESCUENTO,
				// 		descuentoProductos: getValue('descuentoProductos'),
				// 		subtotal: SUBTOTAL,
				// 		total: TOTAL,
				// 		observacion: getValue('observaciones'),
				// 		codigo: CODIGO,
				// 		referencia: CODIGO,
				// 		fechaEntrega: getValue('fechaEntrega') || new Date().toISOString().split('T')[0],
				// 		terminos: getChecked('terminos'),
				// 		costoEnvio: COSTO_ZONA_REPARTO,
				// 		misComprobantes: getValue('misComprobantes') || null,

				// 		usuario: {
				// 			idUsuario: USUARIO_LOGIN.idusuario
				// 		},

				// 		entrega: {
				// 			idEntrega: getValue('entrega')
				// 		},

				// 		formapago: {
				// 			idFormaPago: getValue('formapago')
				// 		},

				// 		comprobante: {
				// 			razonSocial: getValue('fnombres'),
				// 			documento: getValue('bdocumento'),
				// 			nombres: getValue('bnombres'),
				// 			ruc: getValue('fdocumento'),
				// 			direccion: getValue('fdireccion'),
				// 			usuario: {
				// 				idUsuario: USUARIO_LOGIN.idusuario
				// 			},
				// 			ubigeo: {
				// 				idUbigeo: getValue('fdistrito')
				// 			},
				// 			ptipo: {
				// 				idParametro: getValue('tipocomprobante')
				// 			}
				// 		},

				// 		agencia: {
				// 			idAgencia: getValue('anteriores'),
				// 			agencia: getValue('agencia'),
				// 			direccion: getValue('adireccion'),
				// 			nombres: getValue('anombres'),
				// 			apellidos: getValue('aapellidos'),
				// 			dni: getValue('adocumento'),
				// 			telefono: getValue('atelefono'),
				// 			usuario: {
				// 				idUsuario: USUARIO_LOGIN.idusuario
				// 			},
				// 			ubigeo: {
				// 				idUbigeo: getValue('adistrito')
				// 			},
				// 			ptipo: {
				// 				idParametro: getValue('tipocomprobante')
				// 			}
				// 		}
				// 	};

				// 	const response = await fetch(`${BASE_URL}api/Front/generaToken`, {
				// 		method: 'POST',
				// 		headers: {
				// 			'Content-Type': 'application/json'
				// 		},
				// 		body: JSON.stringify(data)
				// 	});

				// 	const res = await response.json();
				// 	$('.carga').hide();

				// 	if (res.status === 'exito') {
				// 		// Mostrar carga
				// 		document.querySelector('.carga').style.display = 'block';

				// 		// Configurar el formulario de pago
				// 		KR.setFormConfig({
				// 			formToken: res.token, // generado por el backend
				// 			'kr-language': res.lenguajeform || 'es-PE',
				// 			'kr-public-key': res.publicKey
				// 		}).then(({
				// 			KR
				// 		}) => {
				// 			// Insertar formulario en tu contenedor
				// 			KR.addForm('#paymentForm');
				// 			KR.showForm();
				// 			document.querySelector('.carga').style.display = 'none';
				// 			new bootstrap.Modal(document.getElementById('modalpago')).show();

				// 			// Eventos
				// 			KR.onSubmit((paymentData) => {
				// 				console.log('✅ Pago completado:', paymentData);
				// 				localStorage.clear();
				// 				window.location.href = `${BASE_URL}pedido/${res.pedido.idpedido}`;
				// 			});

				// 			KR.onError((err) => {
				// 				console.error('❌ Error en Izipay:', err);
				// 				alert('Error al procesar el pago.');
				// 			});
				// 		});
				// 	} else {
				// 		Swal.fire('Carrito de compras!', "Errores encontrados, por favor intente nuevamente!", 'warning');
				// 		document.querySelector('.carga').style.display = 'none';
				// 	}

				// }

				// nuevo

				const getValue = (id) => document.getElementById(id)?.value?.trim() || '';
				const getChecked = (id) => document.getElementById(id)?.checked || false;

				// Crear objeto JSON con todos los datos del pedido
				const data = {
					idProductos: getValue('idProductos'),
					documento: USUARIO_LOGIN.documento,
					correo: USUARIO_LOGIN.correo,
					telefono: USUARIO_LOGIN.telefono,
					nombres: USUARIO_LOGIN.nombres,
					pApellido: USUARIO_LOGIN.pApellido,
					cupon: getValue('cupon'),
					cantidades: CANTIDADES,
					comision: COMISION_TOTAL,
					descuento: DESCUENTO,
					descuentoProductos: getValue('descuentoProductos'),
					subtotal: SUBTOTAL,
					total: TOTAL,
					observacion: getValue('observaciones'),
					codigo: CODIGO,
					referencia: CODIGO,
					fechaEntrega: getValue('fechaEntrega') || new Date().toISOString().split('T')[0],
					terminos: getChecked('terminos'),
					costoEnvio: COSTO_ZONA_REPARTO,
					misComprobantes: getValue('misComprobantes') || null,
					usuario: {
						idUsuario: USUARIO_LOGIN.idusuario
					},
					entrega: {
						idEntrega: getValue('entrega')
					},
					formapago: {
						idFormaPago: getValue('formapago')
					},
					comprobante: {
						razonSocial: getValue('fnombres'),
						documento: getValue('bdocumento'),
						nombres: getValue('bnombres'),
						ruc: getValue('fdocumento'),
						direccion: getValue('fdireccion'),
						usuario: {
							idUsuario: USUARIO_LOGIN.idusuario
						},
						ubigeo: {
							idUbigeo: getValue('fdistrito')
						},
						ptipo: {
							idParametro: getValue('tipocomprobante')
						}
					},
					agencia: {
						idAgencia: getValue('anteriores'),
						agencia: getValue('agencia'),
						direccion: getValue('adireccion'),
						nombres: getValue('anombres'),
						apellidos: getValue('aapellidos'),
						dni: getValue('adocumento'),
						telefono: getValue('atelefono'),
						usuario: {
							idUsuario: USUARIO_LOGIN.idusuario
						},
						ubigeo: {
							idUbigeo: getValue('adistrito')
						},
						ptipo: {
							idParametro: getValue('tipocomprobante')
						}
					}
				};

				const response = await fetch(`${BASE_URL}api/Front/generaToken`, {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json'
					},
					body: JSON.stringify(data)
				});

				const res = await response.json();

				if (res.status === 'exito') {
					const carga = document.querySelector('.carga');
					// carga.style.display = 'block'; // Comentado - ya se mostró en línea 2795

					KR.setFormConfig({
							formToken: res.token,
							'kr-language': res.lenguajeform,
							'kr-public-key': res.publicKey,
						})
						.then(({
							KR
						}) => {
							// Crear y mostrar formulario
							return KR.addForm('#paymentForm')
								.then(() => KR.showForm())
								.then(() => KR); // Pasar KR al siguiente then	
						})
						.then(KR => {
							// Todo lo que depende de KR debe estar aquí
							carga.style.display = 'none'; // Ocultar loader cuando formulario ya se muestra

							KR.onError(function(event) {
								carga.style.display = 'none';
								console.error('Error en KR:', event);
							});

							if (KR.button) {
								KR.button.onClick(() => {
									carga.style.display = 'block'; // Comentado para no bloquear el formulario
								});
							}

							KR.onSubmit(() => {
								localStorage.clear();
								window.location.href = `${BASE_URL}mis-pedidos-detalle/${res.pedido.idpedido}`;
							});

							// Abrir modal de pago
							const modalpago = new bootstrap.Modal(document.getElementById('modalpago'));
							modalpago.show();
						})
						.catch(err => {
							carga.style.display = 'none';
							console.error('Error configurando KR:', err);
						});
				} else {
					document.querySelector('.carga').style.display = 'none';
					Swal.fire({
						title: 'Carrito de compras!',
						text: "Errores encontrados, por favor intente nuevamente!",
						icon: 'warning',
						confirmButtonColor: '#3085d6',
						confirmButtonText: 'Continuar'
					});
				}

			}

		} catch (error) {
			$('.carga').hide();
			Swal.fire('Error', 'No se pudo procesar el pedido. Intente nuevamente.', 'error');
			console.error(error);
		}
	}
</script>