<style>
	.rating {
		display: inline-flex;
		gap: 4px;
	}

	.star {
		transition: color 0.2s ease;
	}

	.star.active {
		color: #FFD700;
	}
</style>

<section class="bg_menu_page" style="background-image: url(<?= base_url(); ?>public/template/images/fondo-nosotros.jpg);background-size: cover;width: 100%;height: 200px;display: table;background-repeat: no-repeat;background-position: center center;">
	<div class="inner_subpage_banner">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="text-banner">
						<h1>Productos</h1>

					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="miga">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<p>
					<a href="<?= base_url(); ?>">Inicio</a> <span>»</span>
					<a href="<?= base_url(); ?>productos">Productos</a> <span>»</span>
					<?= $producto->nombre ?>
				</p>
			</div>
		</div>
	</div>
</section>

<section class="producto-detalle">
	<div class="container-fluid">
		<div class="row">

			<div class="col-md-6">
				<div class="imgs-juntas">
					<div class="min" id="imagen-contenedor">

					</div>
					<div class="box-img" id="imagen-principal">
						<!-- <img src="<?= base_url(); ?>public/template/images/productos/PELUCA-BARBARA.jpg"> -->
					</div>
				</div>
			</div>

			<div class="col-md-6">
				<div class="contenido">
					<h2><?= $producto->nombre ?></h2>
					<div class="box-precios">
						<? if ($producto->precioventa == $producto->preciolista): ?>
							<h3>S/ <?= $producto->precioventa; ?></h3>
						<? else: ?>
							<h3>S/ <?= $producto->precioventa; ?></h3>
							<h4>S/ <?= $producto->preciolista; ?></h4>
						<? endif; ?>


					</div>

					<div class="cantidad">
						<p>Cantidad:</p>
						<div class="cart-plus-minus">
							<!-- <button type="button" onclick="cambioStock('resta',<?= $producto->stock ?>)" class="dec qtybutton qty-down" style="display:inline-block">
								<i class="fa-solid fa-minus"></i>
							</button> -->
							<button type="button" onclick="cambioStock('resta', <?= $producto->stock ?>)" class="dec qtybutton qty-down" style="display:inline-block">
								<i class="fa-solid fa-minus"></i>
							</button>
							<input step="1" min="1" max="<?= $producto->stock; ?>" type="text" readonly="" class="qty" title="Qty" name="cantidad" id="cantidad" value="1">
							<button type="button" onclick="cambioStock('suma', <?= $producto->stock ?>)" class="inc qtybutton qty-up" style="display:inline-block">
								<i class="fa fa-plus"></i>
							</button>
						</div>
					</div>

					<? if ($producto->categoria->idrproductocategoria == 2): ?>
						<div>
							<img src="https://pelucasperu.com/template/images/banner-productoooos.png" alt="">
						</div>
						<br>
					<? endif; ?>

					<div class="btns-compartir">
						<a href="#" onclick="addCarrito(<?= $producto->stock ?>, event)" class="btn-carrito">
							<i class="fa-solid fa-shopping-cart"></i> Agregar al carrito
						</a>

						<a href="https://wa.me/<?= $wspventa->valor ?>" target="_blank" class="btn-compartir">Comprar por whatsapp</a>
					</div>


					<?= $producto->contenido ?>

					<div class="complementos">
						<h5>Agrega a tu compra</h6>

							<div class="row" id="productocomplemento">
								<?php foreach ($producto->complementos as $key => $complemento): ?>
									<div class="col-md-4 complemento-<?= $key + 1 ?>">
										<div class="bg-image">
											<img src="<?= base_url(); ?>archivos/productoimagen/<?= $complemento->urlimagen ?>" alt="">
										</div>
										<div class="bg-resumen">
											<h3><?= $complemento->nombre ?></h3>
											<div class="box-precio">
												<?php if ($complemento->precioventa == $complemento->preciolista): ?>
													<h5>S/ <?= $complemento->precioventa; ?></h5>
												<?php else: ?>
													<h5>S/ <?= $complemento->precioventa; ?></h5>
													<h6>S/ <?= $complemento->preciolista; ?></h6>
												<?php endif; ?>
											</div>
											<div class="btns">
												<?php if ($complemento->stock > 0): ?>
													<a href="javascript:void(0)"
														id="btn-complemento-<?= $complemento->idproducto ?>"
														class="comprar"
														data-complemento='<?= json_encode($complemento) ?>'
														onclick="handleComplementoClick(<?= $complemento->idproducto ?>)">
														Comprar
													</a>
												<?php else: ?>
													<a href="javascript:void(0)"
														id="btn-complemento-<?= $complemento->idproducto ?>"
														class="comprar"
														data-complemento='<?= json_encode($complemento) ?>'
														style="opacity: 0.5; cursor: not-allowed; pointer-events: none;"
														onclick="handleComplementoClick(<?= $complemento->idproducto ?>)">
														Sin stock
													</a>
												<?php endif; ?>
											</div>
										</div>
									</div>
								<?php endforeach; ?>

							</div>

					</div>

				</div>
			</div>

			<div class="col-md-12">
				<div class="valoracion">
					<h4>Calificar producto</h4>
					<div class="rating" id="rating_<?= $producto->idproducto ?>">
						<?php
						$valor = (!empty($producto->valoracionpromedio)) ? ceil($producto->valoracionpromedio) : 0;
						for ($i = 1; $i <= 5; $i++): ?>
							<span
								class="star <?= ($i <= $valor) ? 'active' : '' ?>"
								data-value="<?= $i ?>"
								style="cursor:pointer; font-size: 34px; color: <?= ($i <= $valor) ? '#FFD700' : '#ccc' ?>;"
								onclick="valorar(<?= $i ?>, <?= $producto->idproducto ?>)"
								onmouseover="previewEstrellas(<?= $producto->idproducto ?>, <?= $i ?>)"
								onmouseout="resetEstrellas(<?= $producto->idproducto ?>)">
								★
							</span>
						<?php endfor; ?>
					</div>



					<p id="resultado"></p>

					<p class="average" id="averageText">Promedio: <?= $valor ?> / 5</p>


					<div class="rating-summary" id="ratingSummary"></div>



					<form id="feedbackForm">
						<div class="comentarios">


							<div class="row">
								<div id="listaComentarios" class="col-12">
									<!-- Aquí se insertarán los comentarios dinámicamente -->
								</div>
							</div>

						</div>
						<h3>Tu opinión</h3>

						<? if (session()->get('usuarioSesion')) : ?>

							<div class="row">

								<div class="col-md-12">
									<label for="comentario">Comentario:</label>
									<textarea id="comentario" name="comentario" placeholder="Escribe aquí tu opinión..."></textarea>
									<span class="validacion comentario"></span>
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<img class="captcha-imagen" src="https://pelucasperu.com/captcha?1758657280020" alt="CAPTCHA">
										<button type="button" id="refres" class="refresh-captcha">
											<svg class="svg-inline--fa fa-arrows-rotate" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="arrows-rotate" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
												<path fill="currentColor" d="M105.1 202.6c7.7-21.8 20.2-42.3 37.8-59.8c62.5-62.5 163.8-62.5 226.3 0L386.3 160 352 160c-17.7 0-32 14.3-32 32s14.3 32 32 32l111.5 0c0 0 0 0 0 0l.4 0c17.7 0 32-14.3 32-32l0-112c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 35.2L414.4 97.6c-87.5-87.5-229.3-87.5-316.8 0C73.2 122 55.6 150.7 44.8 181.4c-5.9 16.7 2.9 34.9 19.5 40.8s34.9-2.9 40.8-19.5zM39 289.3c-5 1.5-9.8 4.2-13.7 8.2c-4 4-6.7 8.8-8.1 14c-.3 1.2-.6 2.5-.8 3.8c-.3 1.7-.4 3.4-.4 5.1L16 432c0 17.7 14.3 32 32 32s32-14.3 32-32l0-35.1 17.6 17.5c0 0 0 0 0 0c87.5 87.4 229.3 87.4 316.7 0c24.4-24.4 42.1-53.1 52.9-83.8c5.9-16.7-2.9-34.9-19.5-40.8s-34.9 2.9-40.8 19.5c-7.7 21.8-20.2 42.3-37.8 59.8c-62.5 62.5-163.8 62.5-226.3 0l-.1-.1L125.6 352l34.4 0c17.7 0 32-14.3 32-32s-14.3-32-32-32L48.4 288c-1.6 0-3.2 .1-4.8 .3s-3.1 .5-4.6 1z"></path>
											</svg><!-- <i class="fa-solid fa-refresh"></i> Font Awesome fontawesome.com -->
										</button>
										<input class="form-control" type="text" name="captcha" id="captcha" placeholder="Complete el captcha">
										<span style="color:red;" class="validacion captcha"></span>
									</div>
								</div>

								<button type="submit" class="btn-enviar">Enviar</button>
							</div>
						<? else : ?>
							<textarea disabled placeholder="Inicie sesión para comentar" name="contenido" id="contenido" cols="30" rows="5"></textarea>
							<span class="validacion contenido"></span>
							<a href="#" data-bs-toggle="modal" data-bs-target="#modalSesion">
								<button class="btn-inicioo">Iniciar sesión</button></a>

						<? endif; ?>

					</form>



				</div>
			</div>

		</div>
	</div>
</section>


<? if (!empty($productosrelacionados)): ?>
	<section class="productos-relacionados">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">

					<h2>Productos relacionados</h2>

					<section class="slider-home">
						<div class="owl-carousel5 owl-theme">
							<?php foreach ($productosrelacionados as $productosrelacionado):
								$precioVenta = floatval($productosrelacionado->precioventa);
								$precioLista = floatval($productosrelacionado->preciolista);
								$descuento = 0;

								if ($precioLista > $precioVenta) {
									$descuento = round((($precioLista - $precioVenta) / $precioLista) * 100);
								}
							?>
								<? if ($productosrelacionado->idproducto != $producto->idproducto): ?>
									<div class="item">
										<div class="bg-image">
											<a href="<?= base_url(); ?>producto-detalle/<?= $productosrelacionado->urlamigable; ?>">
												<img src="<?= base_url(); ?>archivos/productoimagen/<?= $productosrelacionado->urlimagen ?? 'imagen.png'; ?>" class="img1" alt="">
											</a>
											<? if ($productosrelacionado->urlimagen2): ?>
												<a href="<?= base_url(); ?>producto-detalle/<?= $productosrelacionado->urlamigable; ?>">
													<img src="<?= base_url(); ?>archivos/productoimagen/<?= $productosrelacionado->urlimagen2; ?>" class="img2" alt="">
												</a>
											<? endif; ?>
										</div>
										<div class="bg-resumen">
											<h3> <?= $productosrelacionado->nombre; ?></h3>
											<div class="box-precio">
												<?php if ($productosrelacionado->precioventa == $productosrelacionado->preciolista): ?>
													<h5>S/ <?= $productosrelacionado->precioventa; ?></h5>
												<?php else: ?>
													<h5>S/ <?= $productosrelacionado->precioventa; ?></h5>
													<h6>S/ <?= $productosrelacionado->preciolista; ?></h6>
												<?php endif; ?>
											</div>
											<div class="btns">
												<a href="<?= base_url(); ?>producto-detalle/<?= $productosrelacionado->urlamigable; ?>" class="comprar">Comprar</a>
											</div>
										</div>
									</div>
								<? endif; ?>
							<?php endforeach; ?>

						</div>
					</section>
				</div>
			</div>
		</div>
	</section>
<? endif; ?>
<script>
	$(document).ready(function() {
		document.querySelector(".captcha-imagen").src = `${BASE_URL}captcha?` + Date.now();
	});

	let refreshButton = document.querySelector(".refresh-captcha");
	refreshButton.onclick = function() {
		document.querySelector(".captcha-imagen").src = `${BASE_URL}captcha?` + Date.now();
	}
</script>
<script>
	//pelucas
	const PRODUCTO = <?= json_encode($producto) ?>;


	// const PRODUCTO_COLOR = <?= json_encode($producto) ?>;
	document.addEventListener("DOMContentLoaded", async function() {

		showImageProducto(PRODUCTO.idproducto);
		cargarComentarios();

		obtenerResumenValoraciones(PRODUCTO.idproducto);
		//porcentaje de estrellas 
		// Datos iniciales de ejemplo


		if (typeof USUARIO_LOGIN !== "undefined" && USUARIO_LOGIN && USUARIO_LOGIN.idusuario) {
			try {
				const response = await fetch("<?= base_url() ?>api/ValoracionController/obtenerValoracionUsuario", {
					method: "POST",
					headers: {
						"Content-Type": "application/json"
					},
					body: JSON.stringify({
						idProducto: PRODUCTO.idproducto,
						idUsuario: USUARIO_LOGIN.idusuario,
					}),
				});

				const data = await response.json();
				console.log("Valoración del usuario:", data);

				if (data.status === "exito" && data.valoracion) {
					actualizarEstrellas(PRODUCTO.idproducto, data.valoracion);
				}
			} catch (error) {
				console.error("Error al obtener valoración del usuario:", error);
			}
		}
	});


	function cambioStock(tipo, maximo) {

		let cantidad = parseInt($(`#cantidad`).val(), 10);
		//const stock = parseInt($('input[name="talla"]:checked').data('stock'), 10);


		if (tipo == 'suma') {
			cantidad = cantidad + 1;
			if (cantidad > maximo) {
				Swal.fire({
					title: 'Producto!',
					text: "Maximo de productos disponibles",
					icon: 'warning',
					showCancelButton: false,
					confirmButtonColor: '#3085d6',
					confirmButtonText: 'Continuar'
				});
			} else {
				$(`#cantidad`).val(cantidad)
			}
		} else if (tipo == 'resta') {
			cantidad = cantidad - 1;
			if (cantidad > 0) {
				$(`#cantidad`).val(cantidad)
			}
		}
	}

	function addCarrito(stock, event) {

		if (event) event.preventDefault();
		const carga = document.querySelector('.carga');
		carga.style.display = 'block';
		const cantidad = document.getElementById('cantidad');


		if (parseInt(cantidad.value) > parseInt(stock)) {
			carga.style.display = 'none';
			Swal.fire({
				title: 'Stock insuficiente!',
				text: `No hay suficientes unidades disponibles. Stock disponible: ${stock}`,
				icon: 'error',
				confirmButtonColor: '#3085d6',
				confirmButtonText: 'Aceptar'
			});
			return;
		}


		console.log("Producto recibido:", PRODUCTO);
		//capturamos los valores del producto 
		const data = {
			"idProducto": PRODUCTO.idproducto,
			"nombre": PRODUCTO.nombre,
			"idProductoCategoria": PRODUCTO.idproductocategoria,
			"stock": parseInt(stock),
			"cantidad": parseInt(cantidad.value),
			"precioVenta": PRODUCTO.precioventa,
			"precioLista": PRODUCTO.preciolista,
			"urlAmigable": PRODUCTO.urlamigable,
			"urlImagen": PRODUCTO.urlimagen,
			"codigo": PRODUCTO.codigo,
			"descripcion": PRODUCTO.resumen
		}

		localStorage.setItem(`Pelucas-Producto-` + data.idProducto, JSON.stringify(data));
		actualizarContadorCarrito();
		carga.style.display = 'none';
		// actualizarContadorCarrito();
		Swal.fire({
			title: 'Producto agregado!',
			text: "El producto se agregó correctamente al carrito.",
			icon: 'success',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ver carrito y pagar',
			cancelButtonText: 'Continuar comprando!'
		}).then((result) => {
			// return result.isConfirmed ? window.location.href = `${BASE_URL}carrito-de-compras` : location.reload();
			if (result.isConfirmed) {
				window.location.href = `${BASE_URL}carrito-de-compras`;;
			}
		})

	}


	function handleComplementoClick(idProducto) {
		const btn = document.getElementById(`btn-complemento-${idProducto}`);
		const complemento = JSON.parse(btn.getAttribute('data-complemento'));

		// Verificar si el botón está deshabilitado (sin stock)
		if (btn.style.pointerEvents === 'none') {
			return;
		}

		if (btn.classList.contains('comprar')) {
			addComplemento(complemento);
		} else {
			removeComplemento(idProducto);
		}
	}

	function addComplemento(complemento) {
		// Verificar stock antes de agregar el complemento
		if (parseInt(complemento.stock) <= 0) {
			Swal.fire({
				title: 'Sin stock disponible',
				text: `Lo sentimos, "${complemento.nombre}" no tiene stock disponible.`,
				icon: 'warning',
				confirmButtonColor: '#3085d6',
				confirmButtonText: 'Aceptar'
			});
			return;
		}

		Swal.fire({
			title: '¿Agregar complemento?',
			text: `¿Deseas agregar "${complemento.nombre}" al carrito?`,
			icon: 'question',
			showCancelButton: true,
			confirmButtonText: 'Sí, agregar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {
			if (result.isConfirmed) {
				const data = {
					"idProducto": complemento.idproducto,
					"nombre": complemento.nombre,
					"idProductoCategoria": complemento.idproductocategoria,
					"stock": parseInt(complemento.stock),
					"cantidad": 1,
					"precioVenta": complemento.precioventa,
					"precioLista": complemento.preciolista,
					"urlAmigable": complemento.urlamigable,
					"urlImagen": complemento.urlimagen,
					"codigo": complemento.codigo,
					"descripcion": complemento.resumen
				};

				localStorage.setItem(`Pelucas-Producto-` + data.idProducto, JSON.stringify(data));

				const btn = document.getElementById(`btn-complemento-${data.idProducto}`);
				btn.textContent = 'Eliminar';
				btn.classList.remove('comprar');
				btn.classList.add('eliminar');

				Swal.fire({
					icon: 'success',
					title: 'Agregado',
					text: 'El complemento se agregó al carrito.'
				});
			}
		});
	}

	function removeComplemento(idProducto) {
		localStorage.removeItem(`Pelucas-Producto-` + idProducto);

		const btn = document.getElementById(`btn-complemento-${idProducto}`);
		btn.textContent = 'Comprar';
		btn.classList.remove('eliminar');
		btn.classList.add('comprar');

		Swal.fire({
			icon: 'info',
			title: 'Eliminado',
			text: 'El complemento fue eliminado del carrito.'
		});
	}


	function showImageProducto(idproducto) {
		const carga = document.querySelector('.carga');
		const url = BASE_URL + 'api/publico/producto-imagen/listar'

		const data = {
			ordenCriterio: 'orden',
			ordenTipo: 'asc',
			parametro: '',
			valor: '',
			idEstado: 346,
			idpTipo: 0,
			idProducto: idproducto,
			registros: 0,
			pagina: 0,
		}
		fetch(url, {
				method: 'Post',
				headers: {
					'Content-Type': 'application/json'
				},
				body: JSON.stringify(data)
			})
			.then(res => res.json())
			.then(response => {
				console.log("responseimagen", response);
				renderImagenesProducto(response);
			})
			.catch(error => {
				console.error(error);
				// document.getElementById('container-productos').innerHTML = '<div class="resultados"><p>No se encontraron resultados</p></div>';
			})
			.finally(() => {
				carga.style.display = 'none'; // Oculta loader
			});


	}

	function cambiarImagen(url, id, event) {
		//con esto evitamos el deslisamiento hacia arriba
		if (event) event.preventDefault();
		// Quitar la clase 'active' de todas las imágenes/carousel
		const imagenes = document.querySelectorAll('.img-carosel-x');
		imagenes.forEach(img => img.classList.remove('active'));

		// Cambiar el src de la imagen principal
		const imagenPrincipal = document.getElementById('imgcambio');
		if (imagenPrincipal) {
			imagenPrincipal.src = url;
		}

		// Agregar la clase 'active' a la imagen seleccionada
		const imagenSeleccionada = document.getElementById('li-' + id);
		if (imagenSeleccionada) {
			imagenSeleccionada.classList.add('active');
		}
	}

	function getDescuento(precioVenta, precioLista) {
		const venta = parseFloat(precioVenta);
		const lista = parseFloat(precioLista);
		if (lista <= venta || isNaN(venta) || isNaN(lista)) return 0;
		return Math.round(((lista - venta) / lista) * 100);
	}

	async function valorar(numero, idproducto) {
		console.log("Usuario:", USUARIO_LOGIN);
		console.log("Producto:", idproducto);

		if (USUARIO_LOGIN && USUARIO_LOGIN.idusuario) {
			try {
				const response = await fetch("<?= base_url() ?>api/ValoracionController/valorarPublicacion", {
					method: "POST",
					headers: {
						"Content-Type": "application/json",
					},
					body: JSON.stringify({
						idProducto: idproducto,
						idUsuario: USUARIO_LOGIN.idusuario,
						valoracion: numero,
					}),
				});

				const data = await response.json();
				console.log("Respuesta del servidor:", data);

				if (data.status === "exito" || data.status === "actualizado") {
					const mensaje =
						data.status === "exito" ?
						"Gracias por tu valoración 😊" :
						"Tu valoración ha sido actualizada 👍";

					await Swal.fire({
						title: "¡Valoración registrada!",
						text: mensaje,
						icon: "success",
						confirmButtonText: "Aceptar",
					});

					// 🔥 Pinta las estrellas seleccionadas
					actualizarEstrellas(idproducto, numero);
					obtenerResumenValoraciones(PRODUCTO.idproducto);
				} else {
					Swal.fire({
						title: "¡Valoración de publicación!",
						text: data.mensaje || "Ocurrió un problema con la valoración.",
						icon: "warning",
						confirmButtonText: "Aceptar",
					});
				}
			} catch (error) {
				console.error("Error en la solicitud:", error);
				Swal.fire({
					title: "Error",
					text: "Ocurrió un problema al enviar tu valoración.",
					icon: "error",
					confirmButtonText: "Aceptar",
				});
			}
		} else {
			Swal.fire({
				title: "¡Valoración de publicación!",
				text: "Para valorar debes iniciar sesión",
				icon: "warning",
				confirmButtonText: "Iniciar sesión",
			}).then((result) => {
				if (result.isConfirmed) {
					const modalSesion = new bootstrap.Modal(document.getElementById("modalSesion"));
					modalSesion.show();
				}
			});
		}
	}


	// ⭐ Pinta las estrellas seleccionadas permanentemente
	function actualizarEstrellas(idproducto, numero) {
		const estrellas = document.querySelectorAll(`#rating_${idproducto} .star`);
		estrellas.forEach((estrella, index) => {
			estrella.style.color = index < numero ? "#FFD700" : "#ccc";
			estrella.classList.toggle("active", index < numero);
		});
		// Guarda el valor actual para el hover
		document.querySelector(`#rating_${idproducto}`).setAttribute("data-valor", numero);
	}

	// ⭐ Vista previa al pasar el mouse
	function previewEstrellas(idproducto, numero) {
		const estrellas = document.querySelectorAll(`#rating_${idproducto} .star`);
		estrellas.forEach((estrella, index) => {
			estrella.style.color = index < numero ? "#FFD700" : "#ccc";
		});
	}

	// 🔙 Restaura al valor actual al quitar el mouse
	function resetEstrellas(idproducto) {
		const ratingDiv = document.querySelector(`#rating_${idproducto}`);
		const valor = ratingDiv.getAttribute("data-valor") || 0;
		const estrellas = document.querySelectorAll(`#rating_${idproducto} .star`);
		estrellas.forEach((estrella, index) => {
			estrella.style.color = index < valor ? "#FFD700" : "#ccc";
		});
	}
	async function cargarComentarios() {
		const carga = document.querySelector('.carga');
		carga.style.display = 'block'; // mostrar loader

		const formData = new FormData();
		formData.append('idclase', 343); // ejemplo
		formData.append('idreferencia', <?= $producto->idproducto ?>);
		formData.append('registros', 8);

		try {
			const response = await fetch("<?= base_url() ?>api/ComentarioController/comentarios", {
				method: "POST",
				body: formData
			});
			const res = await response.json();

			const contenedor = document.getElementById("listaComentarios");
			contenedor.innerHTML = "";

			if (res.status === "exito" && res.data.length > 0) {
				res.data.forEach(c => {
					contenedor.innerHTML += `
			<div class="comentario-item mb-3 p-4 border rounded shadow-sm bg-light">
				<div class="d-flex justify-content-between align-items-center mb-2">
					<h6 class="fw-bold mb-0 text-primary">${c.usuario}</h6>
					<span class="text-muted small">
						<i class="fa-solid fa-calendar-days"></i> ${c.fecha}
					</span>
				</div>
				<p class="text-dark mb-3" style="text-align: justify;">
					${c.contenido}
				</p>
				<div class="d-flex justify-content-end">
					<a href="#" onclick="eliminarComentario(${c.idcomentario})" class="text-danger fw-semibold text-decoration-none">
						<i class="fa-solid fa-trash-can me-1"></i> Eliminar
					</a>
				</div>
			</div>
		`;
				});
			} else {
				contenedor.innerHTML = `<p class="text-center text-muted">No hay comentarios aún.</p>`;
			}

		} catch (error) {
			console.error("Error al cargar comentarios:", error);
		} finally {
			carga.style.display = 'none'; // ocultar loader
		}
	}
	//obtener porcentaje de estrellas
	async function obtenerResumenValoraciones(idproducto) {
		try {
			const response = await fetch(`<?= base_url() ?>api/ValoracionController/resumen/${idproducto}`, {
				method: "POST",
				headers: {
					"Content-Type": "application/json"
				}
			});
			const data = await response.json();

			if (response.ok) {
				console.log("Resumen:", data);
				renderResumen(data); // función para mostrarlo
			} else {
				console.error("Error:", data);
			}
		} catch (error) {
			console.error("Error en la petición:", error);
		}
	}



	function renderResumen(data) {
		const summary = document.getElementById('ratingSummary');
		const averageText = document.getElementById('averageText');

		// Si aún no hay data o ratings vacíos
		if (!data || !data.ratings) {
			summary.innerHTML = '<p>No hay valoraciones aún.</p>';
			averageText.textContent = 'Promedio: 0 / 5';
			return;
		}

		const ratings = data.ratings;
		const average = data.average;

		summary.innerHTML = '';
		averageText.textContent = `Promedio: ${average} / 5`;

		const total = Object.values(ratings).reduce((a, b) => a + b, 0);

		// Si el total es 0, mostrar vacío
		if (total === 0) {
			summary.innerHTML = '<p>No hay valoraciones aún.</p>';
			return;
		}

		Object.entries(ratings)
			.sort((a, b) => b[0] - a[0]) // 5→1
			.forEach(([stars, count]) => {
				const percent = ((count / total) * 100).toFixed(1);
				const row = document.createElement('div');
				row.classList.add('rating-row');
				row.innerHTML = `
                <div class="stars-label">${'★'.repeat(stars)}</div>
                <progress value="${percent}" max="100"></progress>
                <div class="percent">${percent}%</div>
            `;
				summary.appendChild(row);
			});
	}


	async function eliminarComentario(idComentario) {
		const confirmacion = await Swal.fire({
			title: "¿Estás seguro?",
			text: "Este comentario se eliminará permanentemente.",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Sí, eliminar",
			cancelButtonText: "Cancelar"
		});

		if (!confirmacion.isConfirmed) return;

		try {
			const formData = new FormData();
			formData.append('idcomentario', idComentario);

			const response = await fetch(`<?= base_url() ?>api/ComentarioController/eliminarComentario`, {
				method: "POST",
				body: formData
			});
			const result = await response.json();

			if (result.status === "exito") {
				await Swal.fire({
					icon: "success",
					title: "Eliminado",
					text: "El comentario fue eliminado correctamente.",
					timer: 1800,
					confirmButtonText: "Aceptar"
				});
				cargarComentarios();
			} else {
				Swal.fire({
					icon: "warning",
					title: "No se pudo eliminar",
					text: result.message || "Ocurrió un problema al intentar eliminar el comentario."
				});
			}
		} catch (error) {
			console.error("Error al eliminar comentario:", error);
			Swal.fire({
				icon: "error",
				title: "Error del servidor",
				text: "No se pudo conectar con el servidor. Inténtalo más tarde."
			});
		}
	}

	document.getElementById("feedbackForm").addEventListener("submit", async function(e) {
		e.preventDefault();

		// 🔹 Mover la vista hacia el formulario
		window.scrollTo({
			top: document.getElementById("feedbackForm").offsetTop,
			behavior: "smooth"
		});

		// 🔹 Mostrar loader
		const carga = document.querySelector('.carga');
		carga.style.display = 'block';

		const form = e.target;
		const formData = new FormData(form);

		// 🔹 Completar datos adicionales desde el usuario logueado
		const usuario = USUARIO_LOGIN; // Tu objeto ya disponible globalmente
		const idreferencia = <?= $producto->idproducto ?>; // o el valor que corresponda al producto/clase actual
		const idclase = 343; // ejemplo: clase de producto/comentario, puedes ajustarlo según tu sistema
		const estado = 428;
		formData.append("idestado", estado);
		formData.append("idusuario", usuario.idusuario);
		formData.append("idreferencia", idreferencia);
		formData.append("idclase", idclase);
		formData.append("fecha", new Date().toISOString().slice(0, 19).replace('T', ' '));

		try {
			// Enviar datos al backend
			const response = await fetch("<?= base_url() ?>api/ComentarioController/guardarComentario", {
				method: "POST",
				body: formData
			});

			const res = await response.json();
			console.log(res);

			// 🔹 Quitar validaciones previas
			form.querySelectorAll("input, textarea").forEach(el => el.classList.remove("is-invalid"));
			document.querySelectorAll(".validacion").forEach(v => {
				v.style.display = "none";
				v.innerHTML = "";
			});

			// ✅ Si el registro fue exitoso
			if (res.status === "exito") {
				carga.style.display = 'none';
				await Swal.fire({
					title: "¡Comentario registrado!",
					text: "Tu opinión se ha guardado correctamente. Nuestro equipo verificará tu mensaje antes de publicarlo.",
					icon: "success",
					confirmButtonText: "Aceptar"
				});
				form.reset();
				//carga.style.display = 'none'; // 👈 oculta el loader antes de recargar
				location.reload();
			}


			// ⚠️ Si hubo errores de validación
			else if (res.data && Array.isArray(res.data)) {
				res.data.forEach(value => {
					const campo = document.getElementById(value.campo);
					const feedback = document.querySelector("." + value.campo);

					if (campo) campo.classList.add("is-invalid");
					if (feedback) {
						feedback.classList.add("invalid-feedback");
						feedback.style.display = "inline-block";
						feedback.innerHTML = value.valor;
					}
				});
				document.getElementById("comentario").focus();
			}

			// ⚠️ Otro tipo de error
			else {
				await Swal.fire({
					title: "Error",
					text: res.mensaje || "Ocurrió un problema al enviar tu comentario.",
					icon: "error",
					confirmButtonText: "Aceptar"
				});
			}
		} catch (error) {
			console.error("Error al enviar el comentario:", error);
			await Swal.fire({
				title: "Error",
				text: "No se pudo enviar el comentario.",
				icon: "error",
				confirmButtonText: "Aceptar"
			});
		} finally {
			// 🔹 Ocultar loader
			carga.style.display = 'none';
		}
	});





	// function renderImagenesProducto(response) {
	// const imagenes = response.content || [];
	// const contenedorImagen = document.querySelector('#imagen-contenedor .min'); // thumbnails
	// const imagenPrincipal = document.getElementById('imagen-principal'); // contenedor principal

	// if (imagenes.length === 0) return;

	// const primera = imagenes[0];
	// const base = primera.productoColor || {};
	// const precioVenta = base.precioventa ?? 0;
	// const precioLista = base.preciolista ?? 0;
	// const descuento = getDescuento(precioVenta, precioLista);

	// // Imagen principal
	// imagenPrincipal.innerHTML = `
	// <img id="imgcambio" class="img-fluid rounded" src="${BASE_URL}archivos/productoimagen/${primera.urlImagen}" alt="${primera.nombre}">
	// ${descuento > 0 ? `<p class="descuento">${descuento}%</p>` : ''}
	// `;

	// // Thumbnails
	// const filas = imagenes.map((item) => `
	// <a href="#" onclick="cambiarImagen('${BASE_URL}archivos/productoimagen/${item.urlImagen}', ${item.idProductoImagen}, event)">
	// <img class="img-fluid img-carosel-x" id="li-${item.idProductoImagen}" src="${BASE_URL}archivos/productoimagen/${item.urlImagen}" alt="${item.nombre}">
	// </a>
	// `).join('');

	// contenedorImagen.innerHTML = filas;

	// // Primera imagen activa
	// const primeraImg = document.getElementById('li-' + primera.idProductoImagen);
	// if (primeraImg) primeraImg.classList.add('active');
	// }

	function renderImagenesProducto(response) {
		const imagenes = response.content || [];
		const contenedorImagen = document.getElementById('imagen-contenedor'); // thumbnails
		const imagenPrincipal = document.getElementById('imagen-principal'); // contenedor principal

		if (imagenes.length === 0) {
			imagenPrincipal.innerHTML = `<img class="img-fluid rounded" src="${BASE_URL}public/template/images/no-image.png" alt="Sin imagen">`;
			contenedorImagen.innerHTML = '';
			return;
		}

		const primera = imagenes[0];
		const base = primera.productoColor || {};
		const precioVenta = base.precioventa ?? 0;
		const precioLista = base.preciolista ?? 0;
		const descuento = getDescuento(precioVenta, precioLista);

		// Imagen principal
		imagenPrincipal.innerHTML = `
<img id="imgcambio" class="img-fluid rounded w-100" src="${BASE_URL}archivos/productoimagen/${primera.urlImagen}" alt="${primera.nombre}">
${descuento > 0 ? `<p class="descuento">${descuento}%</p>` : ''}
`;

		// Miniaturas
		const filas = imagenes.map(item => `
<a href="#" onclick="cambiarImagen('${BASE_URL}archivos/productoimagen/${item.urlImagen}', ${item.idProductoImagen}, event)">
	<img class="img-fluid img-carosel-x" id="li-${item.idProductoImagen}" src="${BASE_URL}archivos/productoimagen/${item.urlImagen}" alt="${item.nombre}">
</a>
`).join('');

		contenedorImagen.innerHTML = filas;

		// Activar la primera miniatura
		const primeraImg = document.getElementById(`li-${primera.idProductoImagen}`);
		if (primeraImg) primeraImg.classList.add('active');
	}

	// function renderImagenesProducto(response) {
	// console.log('response', response);
	// const imagenes = response.content || [];
	// const contenedorImagen = document.getElementById('imagen-contenedor');
	// const imagenPrincipal = document.getElementById('imagen-principal');

	// if (imagenes.length === 0) {
	// imagenPrincipal.innerHTML = `
	// <div class="position-relative w-100">
	// <img id="imgcambio" class="img-fluid rounded w-100"
	// src="${BASE_URL}public/template/images/no-image.png"
	// alt="Sin imagen">
	// </div>
	// `;
	// contenedorImagen.innerHTML = '';
	// return;
	// }

	// const primera = imagenes[0];
	// const base = primera.producto || {};
	// const descuento = getDescuento(base.precioVenta ?? 0, base.precioLista ?? 0);

	// // Imagen principal ocupando todo el ancho
	// imagenPrincipal.innerHTML = `
	// <div class="position-relative w-100">
	// <img id="imgcambio" class="img-fluid rounded w-100"
	// src="${BASE_URL}archivos/productoimagen/${primera.urlImagen}"
	// alt="${primera.nombre}">
	// ${descuento > 0 ? `
	// <span class="badge bg-danger position-absolute top-0 start-0 m-2 fs-6">
	// ${descuento}% OFF
	// </span>`
	// : ''}
	// </div>
	// `;

	// // Miniaturas (Bootstrap grid)
	// const filas = imagenes.map(item => `
	// <div class="col-3 p-1">
	// <a href="#" onclick="cambiarImagen('${BASE_URL}archivos/productoimagen/${item.urlImagen}', ${item.idProductoImagen}, event)">
	// <img class="img-fluid rounded border"
	// src="${BASE_URL}archivos/productoimagen/${item.urlImagen}"
	// alt="${item.nombre}">
	// </a>
	// </div>
	// `).join('');

	// contenedorImagen.innerHTML = `<div class="row g-2">${filas}</div>`;
	// }


	// function renderImagenesProducto(response) {
	// console.log('response', response);
	// const imagenes = response.content || [];
	// const contenedorImagen = document.getElementById('imagen-contenedor');
	// const imagenPrincipal = document.getElementById('imagen-principal');

	// if (imagenes.length === 0) return;

	// const primera = imagenes[0];
	// const base = primera.producto;
	// const descuento = getDescuento(base.precioVenta, base.precioLista);
	// console.log("descuento", descuento);

	// // Imagen principal con descuento
	// imagenPrincipal.innerHTML = `
	// <div class="position-relative zoom-container">
	// <img id="imgcambio" class="img-fluid rounded zoom-img" src="${BASE_URL}archivos/productoimagen/${primera.urlImagen}" alt="${primera.nombre}">
	// ${descuento > 0 ? `<p class="descuento">${descuento}%</p>` : ''}
	// </div>
	// `;




	// // Thumbnails
	// const filas = imagenes.map((item) => `
	// <a href="#" class="d-block me-2" style="width: 23%;" onclick="cambiarImagen('${BASE_URL}archivos/productoimagen/${item.urlImagen}', ${item.idProductoImagen}, event)">
	// <img class="img-fluid rounded" src="${BASE_URL}archivos/productoimagen/${item.urlImagen}" alt="${item.nombre}">
	// </a>
	// `).join('');

	// contenedorImagen.innerHTML = filas;
	// }
</script>