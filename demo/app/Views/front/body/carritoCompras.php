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
				<!-- <p class="pt">Total: <span id="unidades-productos">2</span> unidades de <span id="num-productos">2</span> productos. </p> -->
			</div>

			<div class="col-md-9">
				<div class="sticky-top">
					<div class="cuadro-checkout">
						<div id="productos">
							<div class="row">
								<div class="col-md-2 col-sm-12">
									<a target="_blank" href="#"><img src="<?= base_url(); ?>public/template/images/productos/PELUCA-BARBARA.jpg"></a>
								</div>
								<div class="col-md-10 col-sm-12">
									<div class="d-flex">
										<div class="box-descripcion">
											<span>
												<a style="cursor:pointer;" onclick="removerItemCarrito(5662);showProductosCarrito()">
													<i class="fa-solid fa-trash"></i>
												</a>
											</span>
											<h3>Peluca Barbara</h3>
											<p>Resumen</p>
											<div class="detail-qty info-qty">
												<a style="cursor: pointer;" onclick="cambioStock('resta','5662')" class="qty-down">
													<i class="fa-solid fa-minus" aria-hidden="true"></i>
												</a>
												<input type="text" step="1" min="1" max="" readonly="" class="input-text text qty qty-val" name="cantidad-5662" id="cantidad-5662" value="1">
												<input type="hidden" id="stock-5662" value="8">
												<a style="cursor: pointer;" onclick="cambioStock('suma','5662')" class="qty-up">
													<i class="fa-solid fa-plus" aria-hidden="true"></i>
												</a>
											</div>
										</div>
										<div class="cuadro-precio ms-auto">
											<h1>S/ 150.00</h1>
										</div>
									</div>
									<a style="cursor:pointer;" onclick="removerItemCarrito(5662);showProductosCarrito()" class="btn-eli">
										<i class="fa-solid fa-trash"></i> Eliminar
									</a>
								</div>
							</div>
						</div>
					</div>
					<div class="total-precios">
						<h5>Subtotal <span> S/&nbsp; <span class="subtotal">150.00</span></span></h5>
						<h5>ENVÍO <span> S/&nbsp; <span class="envio">0.00</span></span></h5>
						<h5>DESCUENTO <span> S/&nbsp; <span class="descuento">0.00</span></span></h5>
						<h5>TOTAL <span>S/&nbsp; <span class="total">150.00</span></span></h5>
					</div>
				</div>
			</div>

			<div class="col-md-3">

				<div class="cuadro-identifacion" id="box-identificacion">
					<h5>Identificación</h5>
					<a class="box-iden" data-bs-toggle="modal" data-bs-target="#modalSesionCarrito">Iniciar Sesión</a>
				</div>
				<div id="formularioRegistro" class="formRegistro2">
					<form id="formReg" name="formReg" method="post">
						<div class="col-md-12">
							<label>Tipo de documento</label>
							<select class="form-select" name="ptipodoc" id="ptipodoc">
								<option value="0">Seleccione</option>
								<option value="537">DNI</option>
								<option value="538">PASAPORTE</option>
								<option value="539">CE</option>
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

				<div class="cuadro-identifacion" id="box-identificacion">
					<h5>Identificación</h5>
					<div class="identificacion cuadro-nombre">
						<p>Correo: <strong>jhoanyf17@gmail.com</strong></p>
						<p>Nombres: <strong>Jhoany Jesús Fasabi Freyre </strong></p>
						<a style="cursor:pointer; display:block; margin:10px auto" onclick="cerrarSesion()">No soy yo, cerrar sesión</a>
					</div>
				</div>
				<div id="formularioRegistro" class="formRegistro">
					<form id="formCheckout" name="formCheckout" data-gtm-form-interact-id="0">

						<input type="hidden" id="referencia" name="referencia" value="1742379472">

						<div class="cuadro-entrega" style="display: block;">
							<h5 style="cursor:pointer">Entrega del producto</h5>
							<div class="cuadro-completo" data-bs-toggle="colapse">
								<div>
									<div class="col-md-12">
										<select id="entrega" name="entrega">
											<option value="0">Seleccione</option>
											<option data-costoenvio="0.00" value="1">Delivery</option>
											<option data-costoenvio="0.00" value="3">Recojo en tienda</option>
											<option data-costoenvio="12.00" value="4">Envío a provincia</option>
										</select>
										<span class="entrega validaclass"></span>
										<h1 style="display: none;" class="entregaDesc">Envío gratis en Lima para pedidos mayores a S/ <span id="importe-minimo-gratis">0</span></h1>
									</div>

									<div class="col-md-12 col-sm-12" id="fechaentrega-caja" style="display: none;">
										<label>Fecha de entrega *</label>
										<div class="input-group">
											<input type="text" id="fechaEntrega" autocomplete="off" name="fechaEntrega" style="width: 100%;">
											<!--<span class="input-group-text" onclick="$('#fechaentrega').datetimepicker('toggle');" id="basic-addon1"><i class="fa-solid fa-calendar"></i></span>-->
										</div>
										<span class="fechaEntrega validaclass"></span>
									</div>



									<div class="destinonuevo" id="destinonuevo" style="display: none;">

										<div class="existe-destino">
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
															</svg><!-- <i class="fas fa-map"></i> Font Awesome fontawesome.com --></button>
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
										</div>

									</div>

									<div class="recojonuevo" id="recojonuevo" style="display: none;">
										<div class="col-md-12">
											<label>Mis direcciones de recojo *</label>
											<select name="misDireccionesRecojo" id="misDireccionesRecojo">
												<option value="">Nuevo</option>
											</select>
											<span class="misDireccionesRecojo validaclass"></span>
										</div>

										<div id="misDireccionesRecojo-container-42" style="display: none;">
											<ul style="list-style: disc;">
												<li>Nombres y apellidos: Jhoany Jesús Fasabi Freyre</li>
												<li>Local: Tienda</li>
												<li>Documento: 74163443</li>
												<li>Teléfono: 977549197</li>
											</ul>
										</div>
										<div class="existe-recojo">

											<div class="col-md-12">
												<label>Local *</label>
												<select name="tienda" id="tienda">
													<option value="0">Seleccione</option>
												</select>
												<span class="tienda validaclass"></span>
											</div>
											<div class="col-md-12">
												<h6 class="textos1">Persona que recoge el pedido</h6>
											</div>
											<div class="col-md-12">
												<label>
													<input checked="" type="checkbox" style="display: inline-block; width:auto" id="checkrecojo" name="checkrecojo"> Completar con mis datos.</label>

											</div>
											<div id="container-checkrecojo" style="display: none;">
												<div class="col-md-12">
													<label>Nombres *</label>
													<input id="rnombres" name="rnombres">
													<span class="rnombres validaclass"></span>
												</div>
												<div class="col-md-12">
													<label>Apellidos *</label>
													<input id="rapellidos" name="rapellidos">
													<span class="rapellidos validaclass"></span>
												</div>
												<div class="col-md-12">
													<label>DNI/CE/PAS *</label>
													<input id="rdocumento" name="rdocumento" readonly="readonly">
													<span class="rdocumento validaclass"></span>
												</div>
												<div class="col-md-12">
													<label>Teléfono *</label>
													<input id="rtelefono" name="rtelefono">
													<span class="rtelefono validaclass"></span>
												</div>
											</div>
										</div>

									</div>

									<div class="provincianuevo" id="provincianuevo" style="display: none;">

										<div class="col-md-12">
											<h6 class="textos">Datos de la agencia</h6>
										</div>
										<div class="col-md-12">
											<label>Nombre de la agencia *</label>
											<input id="agencia" name="agencia">
											<span class="agencia validaclass"></span>
										</div>
										<div class="col-md-12">
											<label>Dirección *</label>
											<input id="adireccion" name="adireccion">
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
										<div id="container-checkagencia" style="display: none;">
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
										<option value="1">IZIPAY</option>
										<option value="2">Yape</option>
										<option value="6">Transferencia</option>
									</select>
									<span class="formapago validaclass"></span>
									<div class="" id="formapago-1" style="">
										Pagos rápidos con izipay<br>
										<div class="logos">
											<h4>Pago en línea</h4>
											<img src="https://yhassir.com/public/template/images/tarjetas/izipay.jpg" alt="">
											<img src="https://yhassir.com/public/template/images/tarjetas/visa.jpg" alt="">
											<img src="https://yhassir.com/public/template/images/tarjetas/american-express.jpg" alt="">
											<img src="https://yhassir.com/public/template/images/tarjetas/mastercard.jpg" alt="">
											<img src="https://yhassir.com/public/template/images/tarjetas/maestro.jpg" alt="">
											<img src="https://yhassir.com/public/template/images/tarjetas/diners-club.jpg" alt="">
											<img src="https://yhassir.com/public/template/images/tarjetas/yape.jpg" alt="">
											<img src="https://yhassir.com/public/template/images/tarjetas/plin.jpg" alt="">
										</div>
									</div>
									<div class="" id="formapago-2" style="display:none;">
										<div class="logos">
											<h4>Pago con Yape o Plin</h4>
											<p>966 709 354</p>
											<img src="https://yhassir.com/public/template/images/yape-yhassir.jpg" alt="" style="width:150px;">
											<p>Yhassir &amp; Co Sac</p>
										</div>
									</div>
									<div class="" id="formapago-6" style="display:none;">
										<br>
										<div class="logos">
											<h4>Transferencia y/o depósito bancario</h4>
											<img src="https://yhassir.com/public/template/images/tarjetas/bcp.jpg" alt="">
											<h6>Número 1912590077092&nbsp;</h6>
											<h6>CCI: 00219100259007709253&nbsp;</h6>
											<h6>A nombre: Yhassir &amp; Co Sac</h6>
										</div>
									</div>
									<p class="formaPagoDes" style="display:none;"></p>

									<div class="transfer bloque-transfer1" style="display:none;">
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
											<option value="445">Boleta</option>
											<option value="446">Factura</option>
										</select>
										<span class="tipocomprobante validaclass"></span>
									</div>

									<div id="container-comprobanteanteriores">
										<label>Anteriores</label>
										<select name="misComprobantes" id="misComprobantes">
											<option value="">Seleccione</option>
											<option value="190">74163443 - Jhoany Jesús Fasabi Freyre</option>
											<option value="189">74163443 - Jhoany Jesús Fasabi Freyre</option>
										</select>
									</div>

									<div id="misComprobantes-container-190" style="display: none;">
										<ul style="list-style: circle;">
											<li>74163443</li>
											<li>Jhoany Jesús Fasabi Freyre</li>
										</ul>
									</div>
									<div id="misComprobantes-container-189" style="display: none;">
										<ul style="list-style: circle;">
											<li>74163443</li>
											<li>Jhoany Jesús Fasabi Freyre</li>
										</ul>
									</div>

									<div class="existe-comprobante" id="box-boleta" style="">
										<div class="col-md-12">
											<label id="label-comprobante">
												<input checked="" type="checkbox" style="display: inline-block; width:auto" id="checkcomprobanteboleta" name="checkcomprobante">
												Completar con mis datos.
											</label>
										</div>
										<div id="container-checkcomprobanteboleta" style="display: none;">
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
										<!-- <div class="col-md-12 bcomprob">
												<label>Departamento *</label>
												<select name="fdepartamento" id="fdepartamento">
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
												<span class="fdepartamento validaclass"></span>
											</div>
											<div class="col-md-12 bcomprob">
												<label>Provincia *</label>
												<select name="fprovincia" id="fprovincia">
													<option value="">Seleccione --</option>
												</select>
												<span class="fprovincia validaclass"></span>
											</div>
											<div class="col-md-12 bcomprob">
												<label>Distrito *</label>
												<select id="fdistrito" name="fdistrito">
													<option value="">Seleccione --</option>
												</select>
												<span class="fdistrito validaclass"></span>
											</div> -->
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
							<div class="cuadro-rojo">
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

			</div>

		</div>
	</div>
</section>