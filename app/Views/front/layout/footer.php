<? if ($tipopago): ?>
	<section class="pagos-home">
		<div class="container-fluid">
			<?= $tipopago->contenido ?>

		</div>
	</section>
<? endif ?>

<div class="carga" style="display:none;opacity: 1;pointer-events: auto;position: fixed;top: 0;bottom: 0;left: 0;right: 0;text-align: center;font-size: 0;overflow-y: scroll;background-color: rgba(0,0,0,.4);z-index: 10000;transition: opacity .3s;">
	<div class="gif">
		<img src="<?= base_url() ?>template/images/loader.svg" style="margin-top: 20%;width: 5%;">
	</div>
</div>
<footer>
	<div class="container-fluid">
		<div class="row">

			<div class="col-md-3">
				<a href="<?= base_url(); ?>"><img src="<?= base_url(); ?>archivos/configuracion/<?= $logo2->urlimagen ?>" alt=""></a>
				<div class="d-flex">
					<ul>
						<li><a href="#" target="_blank"><i class="fa-brands fa-facebook-f"></i></a></li>
						<li><a href="#" target="_blank"><i class="fa-brands fa-instagram"></i></a></li>
						<li><a href="#" target="_blank"><i class="fa-brands fa-tiktok"></i></a></li>
					</ul>
				</div>
			</div>

			<div class="col-md-3">
				<div class="datos">
					<h4>Contáctenos</h4>
					<ul>
						<li><a target="_blank" href="https://wa.me/51<?= $numerocontacto->valor ?>" class="wsp">+51 <?= $numerocontacto->valor ?></a></li>
						<li><a target="_blank" href="mailto:<?= $correofooter->valor ?>" class="msj"><?= $correofooter->valor ?></a></li>
					</ul>
				</div>
			</div>

			<div class="col-md-3">
				<div class="empresa2">
					<h4>Categorías</h4>
					<ul>
						<li><a href="#">Pelucas</a></li>
						<li><a href="#">Lacefront</a></li>
						<li><a href="#">Coletas</a></li>
						<li><a href="#">Accesorios</a></li>
					</ul>
				</div>
			</div>

			<div class="col-md-3">
				<div class="empresa2">
					<h4>Atención al cliente</h4>
					<ul>
						<li><a href="#">Libro de reclamaciones</a></li>
						<li><a href="#">Términos y Condiciones</a></li>
						<li><a href="#">Política de Privacidad</a></li>
					</ul>
				</div>
			</div>

		</div>
	</div>
</footer>

<section class="footer-bottom">
	<div class="container">
		<div class="row">
			<div class="col-dm-12">
				<p>
					<a href="<?= getenv("ADMIN_SITE") ?>" target="_blank">
						<i class="fa-solid fa-cog"></i></a>
					© PELUCAS PERÚ 2025. Todos los derechos reservados.</a>
				</p>
			</div>
		</div>
	</div>
</section>



<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= base_url(); ?>template/js/bootstrap.min.js"></script>
<script src="<?= base_url(); ?>template/js/jquery.validate.js"></script>
<script src="<?= base_url(); ?>template/js/owl.carousel.js"></script>
<script src="<?= base_url(); ?>template/js/carrusel.js"></script>
<script src="<?= base_url(); ?>template/js/all.min.js"></script>
<script src="<?= base_url(); ?>template/js/fontawesome.min.js"></script>
<script src="<?= base_url(); ?>template/js/aos.js"></script>

</body>

</html>

<script>
	document.addEventListener("DOMContentLoaded", function() {
		const form = document.getElementById("form-suscripcion");

		if (form) {
			form.addEventListener("submit", function(e) {
				e.preventDefault();

				// Mostrar el loader
				const loader = document.querySelector(".carga");
				if (loader) loader.style.display = "block";

				// Crear objeto FormData
				const formData = new FormData(form);

				// Enviar datos con fetch
				fetch(`${BASE_URL}api/FormularioController/suscripcion`, {
						method: "POST",
						body: formData
					})
					.then(response => response.json())
					.then(res => {
						console.log(res); // Verifica estructura de la respuesta

						// Llama a removerClases si existe
						if (typeof removerClases === "function") removerClases();

						if (res && res.status === "error") {
							// Llama a showErrores si existe
							if (typeof showErrores === "function") {
								showErrores(res.errors);
							}
							if (loader) loader.style.display = "none";
							return;
						}

						if (res && res.status === "exito") {
							Swal.fire({
								title: '¡Suscripción!',
								text: 'Se ha suscrito con éxito',
								icon: 'success',
								showCancelButton: false,
								confirmButtonColor: '#3085d6',
								confirmButtonText: 'Continuar'
							}).then(() => {
								form.reset();
								if (loader) loader.style.display = "none";
							});
							return;
						}

						// Error inesperado
						console.error("Respuesta no válida:", res);
						if (loader) loader.style.display = "none";
					})
					.catch(err => {
						console.error("Error en la petición:", err);
						if (loader) loader.style.display = "none";
					});
			});
		}



		//Login
		const login2 = document.getElementById("form-login");
		if (login2) {
			login2.addEventListener("submit", function(e) {
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
							document.getElementById("modalSesion").querySelector(".btn-close").click();
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
									const documentoInput = document.getElementById("documento");

									if (documentoInput) {
										const documen = document.getElementById("login-usuario").value;
										documentoInput.value = documen;
										document.getElementById("modalSesionCarrito").querySelector(".btn-close").click();
									} else {
										window.location.href = "https://pelucasperu.com/registro";
									}



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

	});

	function cerrarSesion() {
		document.querySelector(".carga").style.display = "block";

		fetch(`${BASE_URL}api/SeguridadController/cerrarSesion`, {
				method: "POST",
				headers: {
					'Accept': 'application/json',
				}
			})
			.then(response => response.json())
			.then(res => {
				location.reload();
			})
			.catch(error => {
				console.error('Error:', error);
				document.querySelector(".carga").style.display = "none";
			});
	}

	function removerItemCarrito(idProducto) {
		localStorage.removeItem(`Pelucas-Producto-${idProducto}`);
		actualizarContadorCarrito();
		actualizarInputsArrays();
		// setCarritoCompras();
	}



	function removerClases() {
		// Ocultar y limpiar textos
		document.querySelectorAll('.validacion, .validaclass, .validaform').forEach(el => {
			el.style.display = 'none';
			el.innerHTML = '';
		});

		// Remover clase is-invalid de inputs, selects y textareas
		document.querySelectorAll('select, textarea, input').forEach(el => {
			el.classList.remove('is-invalid');
		});

		// Ocultar el elemento con clase carga
		const carga = document.querySelector('.carga');
		if (carga) carga.style.display = 'none';
	}

	function showErrores(errors) {
		errors.forEach(item => {
			const input = document.getElementById(item.campo);
			if (input) input.classList.add('is-invalid');

			const errorElems = document.querySelectorAll('.' + item.campo);
			errorElems.forEach(el => {
				el.classList.add('invalid-feedback');
				el.style.display = 'inline';
				el.innerHTML = item.valor;
			});

		});

		const carga = document.querySelector('.carga');
		if (carga) carga.style.display = 'none';
	}

	// async function setListaDeseos() {
	// 	const deseos = JSON.parse(localStorage.getItem('listaDeseoTemp')) || [];
	// 	console.log("Deseos temporales:", deseos);

	// 	if (deseos.length === 0) return;
	// 	console.log(deseos);
	// 	try {
	// 		const response = await fetch(`${BASE_URL}api/ListaDeseoController/checkListaDeseo`, {
	// 			method: 'POST',
	// 			headers: {
	// 				'Content-Type': 'application/json'
	// 			},

	// 			body: JSON.stringify({
	// 				idProducto: deseos // ← Enviar array completo
	// 			})
	// 		});

	// 		if (!response.ok) throw new Error('Error en la respuesta del servidor');

	// 		const resp = await response.json();
	// 		console.log("Productos agregados a lista real:", resp);

	// 		// Limpiar solo si fue exitoso
	// 		if (resp.status === 'exito') {
	// 			localStorage.removeItem('listaDeseoTemp');
	// 		}

	// 	} catch (error) {
	// 		console.error("Error al agregar productos a la lista de deseos:", error);
	// 	}

	// 	// Luego obtener la lista actual para actualizar iconos y contador
	// 	try {
	// 		const response = await fetch(`${BASE_URL}api/ListaDeseoController/getListaDeseos`, {
	// 			method: 'POST',
	// 			headers: {
	// 				'Content-Type': 'application/json'
	// 			},
	// 			body: JSON.stringify({})
	// 		});

	// 		if (!response.ok) throw new Error('Error en la respuesta del servidor');

	// 		const res = await response.json();

	// 		if (res.status === 'exito') {
	// 			const encanta = document.querySelector(".encanta");
	// 			if (encanta) encanta.textContent = res.lista.length;

	// 			res.lista.forEach(item => {
	// 				const el = document.querySelector(`.lista-deseo-${item.idproducto}`);
	// 				if (el) {
	// 					el.setAttribute("src", `${BASE_URL}public/template/images/corazon-hover.svg`);
	// 				}
	// 			});
	// 		}
	// 	} catch (error) {
	// 		console.error("Error al obtener la lista de deseos actualizada", error);
	// 	}
	// }


	// async function addListaDeseo(idProducto) {
	// 	const contenedor = document.querySelector(`.wishlist-icon[data-id="${idProducto}"]`);
	// 	const img = contenedor?.querySelector('.wishlist-icon-img');

	// 	if (!contenedor || !img) {
	// 		console.warn("No se encontró el ícono para idProducto:", idProducto);
	// 		return;
	// 	}

	// 	img.style.pointerEvents = 'none';

	// 	if (!USUARIO_LOGIN) {
	// 		let deseos = JSON.parse(localStorage.getItem('listaDeseoTemp')) || [];

	// 		const index = deseos.indexOf(idProducto);
	// 		if (index === -1) {
	// 			deseos.push(idProducto);
	// 			img.src = img.dataset.hover;
	// 		} else {
	// 			deseos.splice(index, 1);
	// 			img.src = img.dataset.default;
	// 		}

	// 		localStorage.setItem('listaDeseoTemp', JSON.stringify(deseos));

	// 		// const modal = document.querySelector("#modalSesion");
	// 		// if (modal) modal.classList.add("show");

	// 		img.style.pointerEvents = 'auto';
	// 		actualizarContadorListaDeseos();
	// 		marcarListaDeseos();
	// 		return;
	// 	}

	// 	try {
	// 		const response = await fetch(`${BASE_URL}api/ListaDeseoController/checkListaDeseo`, {
	// 			method: "POST",
	// 			headers: {
	// 				"Content-Type": "application/x-www-form-urlencoded"
	// 			},
	// 			body: new URLSearchParams({
	// 				idProducto
	// 			})
	// 		});

	// 		if (!response.ok) throw new Error("Error en la respuesta del servidor");

	// 		const res = await response.json();

	// 		if (parseInt(res.listaDeseo?.idEstado) === 414) {
	// 			img.src = img.dataset.hover;
	// 		} else {
	// 			img.src = img.dataset.default;
	// 		}

	// 		const encanta = document.querySelector(".encanta");
	// 		if (encanta && res.total !== undefined) encanta.textContent = res.total;
	// 		actualizarContadorListaDeseos();
	// 		marcarListaDeseos();

	// 	} catch (error) {
	// 		console.error("Error en wishlist:", error);
	// 	} finally {
	// 		img.style.pointerEvents = 'auto';
	// 	}
	// }
	// async function actualizarContadorListaDeseos() {


	// 	const contador = document.querySelector(".conteo-cora");
	// 	if (!contador) {

	// 		return;
	// 	}



	// 	// Verificar si la variable USUARIO_LOGUEADO está definida
	// 	if (typeof USUARIO_LOGIN === 'undefined') {

	// 		contador.textContent = 0;
	// 		return;
	// 	}

	// 	console.log("🔍 USUARIO_LOGUEADO:", USUARIO_LOGIN);

	// 	if (USUARIO_LOGIN) {

	// 		if (typeof BASE_URL === 'undefined') {
	// 			contador.textContent = 0;
	// 			return;
	// 		}
	// 		const url = `${BASE_URL}api/ListaDeseoController/getListaDeseos`;


	// 		try {
	// 			const response = await fetch(url, {
	// 				method: 'POST',
	// 				headers: {
	// 					'Content-Type': 'application/json'
	// 				},
	// 				body: JSON.stringify({})
	// 			});



	// 			if (!response.ok) throw new Error('Error al obtener datos del servidor');

	// 			const res = await response.json();


	// 			const total = Array.isArray(res.lista) ? res.lista.length : 0;

	// 			contador.textContent = total;
	// 		} catch (error) {

	// 			contador.textContent = 0;
	// 		}
	// 	} else {
	// 		// Usuario NO logueado
	// 		console.log("🔓 Usuario NO logueado. Consultando localStorage...");

	// 		try {
	// 			const lista = localStorage.getItem('listaDeseoTemp');


	// 			const deseos = JSON.parse(lista) || [];


	// 			contador.textContent = deseos.length;
	// 		} catch (error) {

	// 			contador.textContent = 0;
	// 		}
	// 	}
	// }


	// function marcarListaDeseos() {
	// 	const iconos = document.querySelectorAll('.wishlist-icon-img');

	// 	if (!iconos.length) return;

	// 	if (!USUARIO_LOGIN) {
	// 		const deseos = JSON.parse(localStorage.getItem('listaDeseoTemp')) || [];

	// 		iconos.forEach(icon => {
	// 			const id = parseInt(icon.dataset.id);
	// 			icon.src = deseos.includes(id) ? icon.dataset.hover : icon.dataset.default;
	// 		});
	// 		actualizarContadorListaDeseos();
	// 	} else {
	// 		fetch(`${BASE_URL}api/ListaDeseoController/getListaDeseos`, {
	// 				method: "POST"
	// 			})
	// 			.then(res => res.json())
	// 			.then(data => {
	// 				console.log("respuesta de deseos", data);
	// 				if (data.status === 'exito') {
	// 					const ids = data.lista.map(item => parseInt(item.idproducto)); // ← todo minúsculas

	// 					iconos.forEach(icon => {
	// 						const id = parseInt(icon.dataset.id);
	// 						icon.src = ids.includes(id) ? icon.dataset.hover : icon.dataset.default;
	// 					});

	// 					const encanta = document.querySelector(".encanta");
	// 					if (encanta) encanta.textContent = data.lista.length;
	// 					actualizarContadorListaDeseos();
	// 				}
	// 			})
	// 			.catch(err => console.error("Error cargando deseos:", err));
	// 	}

	// }
	function actualizarInputsArrays() {
		const idProductosInput = document.getElementById("idProductos");
		const idProductoTallasInput = document.getElementById("idProductoTallas");
		const cantidadesInput = document.getElementById("cantidades");
		const productoTallasInput = document.getElementById("productoTallas");
		const descuentoProductosInput = document.getElementById("descuentoProductos");

		// Limpiar valores
		if (idProductosInput) idProductosInput.value = "";
		if (idProductoTallasInput) idProductoTallasInput.value = "";
		if (cantidadesInput) cantidadesInput.value = "";
		if (productoTallasInput) productoTallasInput.value = "";
		if (descuentoProductosInput) descuentoProductosInput.value = "";

		// Obtener los datos desde localStorage
		const idProductos = [];
		const idProductoTallas = [];
		const cantidades = [];
		const productoTallas = [];
		const descuentos = [];

		Object.keys(localStorage).forEach(key => {
			if (key.includes("Pelucas-Producto-")) {
				const item = JSON.parse(localStorage.getItem(key));
				idProductos.push(item.idProducto);
				idProductoTallas.push(item.idProductoBase || 0);
				cantidades.push(item.cantidad);
				productoTallas.push(item.talla || "");
				descuentos.push(item.descuento || 0);
			}
		});

		// Asignar como string separado por comas
		if (idProductosInput) idProductosInput.value = idProductos.join(",");
		if (idProductoTallasInput) idProductoTallasInput.value = idProductoTallas.join(",");
		if (cantidadesInput) cantidadesInput.value = cantidades.join(",");
		if (productoTallasInput) productoTallasInput.value = productoTallas.join(",");
		if (descuentoProductosInput) descuentoProductosInput.value = descuentos.join(",");
	}

	function actualizarContadorCarrito() {
		const contadores = document.querySelectorAll(".conteo-carrito");
		if (!contadores.length) return;

		try {
			let total = 0;

			// Recorremos todas las claves del localStorage
			for (let i = 0; i < localStorage.length; i++) {
				const key = localStorage.key(i);
				// Verificamos si la clave empieza con "Pelucas-Producto-"
				if (key && key.startsWith("Pelucas-Producto-")) {
					total++;
				}
			}

			// Actualizamos todos los contadores
			contadores.forEach(contador => {
				contador.textContent = total;
			});

		} catch (error) {
			// En caso de error, ponemos 0 en todos
			contadores.forEach(contador => {
				contador.textContent = 0;
			});
		}
	}
</script>