<!DOCTYPE HTML>
<html lang="es">

<head>
	<!--Title-->
	<title><?= ((isset($titulo)) ? $titulo : ((isset($tituloGeneral)) ? $tituloGeneral : 'Pelucas Perú - Marca N° 1 de pelcuas del Perú')) ?></title>
	<meta name="description" content="<?= ((isset($descripcion)) ? strip_tags($descripcion) : ((isset($descripcionGeneral)) ? $descripcionGeneral : '')) ?>">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
	<!--description-->
	<meta property="og:title" content="<?= ((isset($titulo)) ? $titulo : ((isset($tituloGeneral)) ? $tituloGeneral : 'Pelucas Perú - Marca N° 1 de pelcuas del Perú')) ?>" />
	<meta property="og:description" content="<?= ((isset($descripcion)) ? $descripcion : ((isset($descripcionGeneral)) ? $descripcionGeneral : '')) ?>" />
	<meta property="og:url" content="<?= base_url() ?><?= ((isset($url)) ? $url : '') ?>" />
	<!--Key Words-->
	<meta name="keywords" content="<?= ((isset($keywords)) ? $keywords : ((isset($keywordsGeneral)) ? $keywordsGeneral : '')) ?>">

	<!--bootstrap-->
	<script src="<?= base_url(); ?>template/js/jquery-3.3.1.min.js"></script>
	<link rel="stylesheet" href="<?= base_url(); ?>template/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?= base_url(); ?>template/css/all.min.css">
	<link rel="stylesheet" href="<?= base_url(); ?>template/css/fontawesome.min.css">
	<link rel="stylesheet" href="<?= base_url(); ?>template/css/owl.carousel.css" />
	<link rel="stylesheet" href="<?= base_url(); ?>template/css/owl.theme.default.min.css" />
	<link rel="stylesheet" href="<?= base_url(); ?>template/css/style.css">
	<link rel="stylesheet" href="<?= base_url(); ?>template/paginator/paginator.css">
	<link rel="stylesheet" href="<?= base_url(); ?>template/css/responsive.css">
	<link rel="shortcut icon" href="<?= base_url(); ?>template/images/favicon.png">
	<link rel="stylesheet" href="<?= base_url(); ?>/template/css/aos.css">
	<script src="<?= base_url(); ?>template/js/owl.carousel.js"></script>


	<!-- Flatpickr CSS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
	<!-- Flatpickr JS -->
	<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
	<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>

	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="<?= base_url(); ?>template/paginator/paginator.js"></script>
	<script>
		const BASE_URL = "<?= base_url() ?>";
		const USUARIO_LOGIN = <?= json_encode(session()->get('usuarioSesion')) ?>;
	</script>

	<!-- <script src="https://checkout.izipay.pe/payments/v1/js/index.js" defer></script> -->

	<!-- GetButton.io widget -->
	<script type="text/javascript">
		(function() {
			var options = {
				facebook: "", // Facebook page ID
				whatsapp: "+51977533398", // WhatsApp number
				call_to_action: "Escríbenos", // Call to action
				button_color: "#0b9228ff", // Color of button
				position: "left", // Position may be 'right' or 'left'
				order: "whatsapp,facebook", // Order of buttons
				pre_filled_message: "Hola, solicito más información", // WhatsApp pre-filled message
			};
			var proto = 'https:',
				host = "getbutton.io",
				url = proto + '//static.' + host;
			var s = document.createElement('script');
			s.type = 'text/javascript';
			s.async = true;
			s.src = url + '/widget-send-button/js/init.js';
			s.onload = function() {
				WhWidgetSendButton.init(host, proto, options);
			};
			var x = document.getElementsByTagName('script')[0];
			x.parentNode.insertBefore(s, x);
		})();
	</script>
	<!-- /GetButton.io widget -->

</head>

<body>

	<section class="header-menu">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="menu">
						<div class="bar">
							<a data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample"><i class="fa-solid fa-bars"></i></a>
						</div>
						<div class="logo">
							<a href="<?= base_url(); ?>"><img src="<?= base_url(); ?>archivos/configuracion/<?= $logo->urlimagen ?>" alt=""></a>
						</div>
						<div class="usuario">
							<ul>
								<li class="sesion">
									<a href="#"><i class="fa-solid fa-user"></i></a>
									<? if (!session()->get('usuarioSesion')): ?>
										<ul class="dropdown-menu">
											<li><a style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalSesion">Ingresar</a></li>
											<li><a href="<?= base_url(); ?>registro">Registrarse</a></li>
										</ul>
									<? else: ?>
										<ul class="dropdown-menu" style="">
											<li>
												<!-- <h5 id="user"><i class="fa-solid fa-user"></i></h5> -->
												<h5 id="user"></h5>
											</li>
											<li><a href="<?= base_url('mi-cuenta'); ?>"><i class="fa-regular fa-id-card"></i> Mi cuenta</a></li>
											<li><a href="<?= base_url('mis-pedidos'); ?>"><i class="fa-solid fa-list"></i> Mis pedidos</a></li>
											<li><a href="#" onclick="cerrarSesion()"><i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión</a></li>
										</ul>
									<? endif ?>
								</li>
								<li>
									<!-- <a data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"><i class="fa-solid fa-bag-shopping"></i>
										<span id="carrito-contador" class="badge rounded-pill bg-danger position-absolute top-0 start-100 translate-middle">
											0
											<span class="visually-hidden">productos en el carrito</span>
										</span>
									</a> -->

									<a class="position-relative " href="<?= base_url(); ?>carrito-de-compras">
										<i class="fa-solid fa-bag-shopping fa-lg"></i>
										<span class="badge rounded-pill bg-danger position-absolute top-0 start-100 translate-middle conteo-carrito" style="transform: translate(25%, -50%);">
											0
										</span>
									</a>



								</li>
								<!-- <li class="nav-item position-relative">
									<a href="#" class="nav-link carrito-flotante">
										<i class="fa-solid fa-bag-shopping fa-lg"></i>
										<span id="carrito-contador" class="badge rounded-pill bg-danger position-absolute top-0 start-100 translate-middle">
											0
											<span class="visually-hidden">productos en el carrito</span>
										</span>
									</a>
								</li> -->


							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<a href="<?= base_url(); ?>carrito-de-compras" class="carrito-flotante"><i class="fa-solid fa-bag-shopping"></i> <span class="badge rounded-pill bg-danger position-absolute top-0 start-100 translate-middle conteo-carrito" style="transform: translate(25%, -50%);">
			0
		</span></a>

	<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
		<div class="offcanvas-header">
			<img src="<?= base_url(); ?>/template/images/milislens-logo-blanco.png" alt="">
			<button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
		</div>
		<div class="offcanvas-body">
			<ul class="menu-izq">
				<li><a href="<?= base_url(); ?>productos/peluca">Productos</a></li>
				<li><a href="<?= base_url(); ?>productos/peluca/pelucas">Pelucas</a></li>
				<li><a href="<?= base_url(); ?>productos/peluca/lacefront">Lacefront</a></li>
				<li><a href="<?= base_url(); ?>productos/peluca/coletas">Coletas</a></li>
				<li><a href="<?= base_url(); ?>productos/peluca/accesorios">Accesorios</a></li>
				<!-- <li><a href="<?= base_url(); ?>productos/peluca/promos">Promos</a></li> -->
				<li><a href="<?= base_url(); ?>lentes" class="menu-hover">Lentes de contacto</a></li>
				<!-- <li><a href="<?= base_url(); ?>productos/carteras" class="menu-hover">Carteras</a></li> -->
				<li><a href="<?= base_url(); ?>blog">Blog</a></li>
				<li><a href="https://agencias.shalom.pe/" target="_blank">Tracking Shalom</a></li>
			</ul>
			<ul class="redes">
				<li><a href="#"><i class="fa-brands fa-facebook-f"></i></a></li>
				<li><a href="#"><i class="fa-brands fa-instagram"></i></a></li>
				<li><a href="#"><i class="fa-brands fa-tiktok"></i></a></li>
			</ul>
		</div>
	</div>

	<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
		<div class="offcanvas-header">
			<h5 class="offcanvas-title" id="offcanvasRightLabel">Offcanvas right</h5>
			<button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
		</div>
		<div class="offcanvas-body">
			...
		</div>
		<div class="offcanvas-footer">
			...
		</div>
	</div>



	<!-- Modal -->
	<div class="modal fade" id="modalSesion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="exampleModalLabel">Inicio de sesión</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form id="form-login" name="form-login" method="post">
						<div class="form-group">
							<label for="login-usuario">Usuario (DNI/PAS/CEX)</label>
							<input autofocus="" class="form-control" type="text" id="login-usuario" name="login-usuario">
							<small style="color: red;" class="validacion login-usuario"></small>
						</div>
						<div class="form-group">
							<button type="submit" style="text-transform: none;">Continuar</button>
							<!-- <button type="button" onclick="registro()"  style="background: #6f6f6f; text-transform: none; ">Registrar como nuevo cliente</button> -->
							<button type="button" onclick="registronew()" class="registrarme">Registrarme</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<script>
		document.addEventListener("DOMContentLoaded", function() {
			// marcarListaDeseos();
			// actualizarContadorListaDeseos();
			actualizarContadorCarrito();
			// const inputBusqueda = document.getElementById("buscador-input");
			// const btnBuscar = document.getElementById("btn-buscar");
			// const categoriaItems = document.querySelectorAll(".categoria-item");
			// const dropdownButton = document.querySelector(".dropdown-toggle");

			// const iniciado = document.querySelector('.iniciado');
			// const ingresar = document.querySelector('.ingresar');
			const user = document.getElementById('user');


			if (USUARIO_LOGIN) {
				// Si está logueado

				if (user) {
					user.innerHTML = '<i class="fa-solid fa-user"></i> ' + USUARIO_LOGIN.nombres + ' ' + USUARIO_LOGIN.papellido + ' ' + USUARIO_LOGIN.sapellido;
				}
				// setListaDeseos();
			} else {
				// Si no está logueado

			}


			// btnBuscar.addEventListener("click", function() {
			// 	console.log("input", inputBusqueda.value);
			// 	//verificamos si el input no esta vacio
			// 	let texto = inputBusqueda.value.trim();
			// 	if (texto == '') {
			// 		inputBusqueda.classList.add("is-invalid");
			// 	} else {
			// 		let URL = BASE_URL + 'productos?';
			// 		URL += "buscar=" + encodeURIComponent(texto);
			// 		window.location.href = URL;
			// 	}

			// });

			// categoriaItems.forEach(item => {
			// 	item.addEventListener("click", function(e) {
			// 		e.preventDefault();

			// 		const categoriaurlamigable = this.dataset.urlamigable;
			// 		// const categoriaNombre = this.dataset.nombre;

			// 		categoriaSeleccionada = categoriaurlamigable;

			// 		// Cambiar texto del botón dropdown
			// 		// if (dropdownButton) {
			// 		// 	dropdownButton.textContent = categoriaNombre;
			// 		// }

			// 		// Ejecutar búsqueda inmediata si hay texto
			// 		const texto = inputBusqueda.value.trim();
			// 		if (texto !== "") {
			// 			realizarBusqueda(texto, categoriaSeleccionada);
			// 		} else {
			// 			inputBusqueda.classList.add("is-invalid");
			// 		}
			// 	});
			// });




		});

		// function realizarBusqueda(texto, categoriaSeleccionada) {



		// 	if (categoriaSeleccionada) {
		// 		let URL = BASE_URL + 'productos/' + categoriaSeleccionada + '?';
		// 		URL += "buscar=" + encodeURIComponent(texto);
		// 		window.location.href = URL;
		// 	} else {
		// 		let URL = BASE_URL + 'productos?';
		// 		URL += "buscar=" + encodeURIComponent(texto);
		// 		window.location.href = URL;
		// 	}


		// }

		function registronew() {
			let url = BASE_URL + "registro";
			window.location.href = url;
		}
	</script>