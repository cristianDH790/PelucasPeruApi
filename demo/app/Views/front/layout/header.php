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
	<script src="<?= base_url(); ?>public/template/js/jquery-3.3.1.min.js"></script>
	<link rel="stylesheet" href="<?= base_url(); ?>public/template/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?= base_url(); ?>public/template/css/all.min.css">
	<link rel="stylesheet" href="<?= base_url(); ?>public/template/css/fontawesome.min.css">
	<link rel="stylesheet" href="<?= base_url(); ?>public/template/css/owl.carousel.css" />
	<link rel="stylesheet" href="<?= base_url(); ?>public/template/css/owl.theme.default.min.css" />
	<link rel="stylesheet" href="<?= base_url(); ?>public/template/css/style.css">
	<link rel="stylesheet" href="<?= base_url(); ?>public/template/paginator/paginator.css">
	<link rel="stylesheet" href="<?= base_url(); ?>public/template/css/responsive.css">
	<link rel="shortcut icon" href="<?= base_url(); ?>public/template/images/favicon.png">
	<link rel="stylesheet" href="<?= base_url(); ?>/public/template/css/aos.css">
	<script src="<?= base_url(); ?>public/template/js/owl.carousel.js"></script>

	<script src="<?= base_url(); ?>public/template/paginator/paginator.js"></script>
	<script>
		const BASE_URL = "<?= base_url() ?>";
	</script>

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
							<a href="<?= base_url(); ?>"><img src="<?= base_url(); ?>/public/template/images/milislens-logo.png" alt=""></a>
						</div>
						<div class="usuario">
							<ul>
								<li class="sesion">
									<a href="#"><i class="fa-solid fa-user"></i></a>
									<ul class="dropdown-menu">
										<li><a href="#">Ingresar</a></li>
										<li><a href="#">Registrarse</a></li>
									</ul>
								</li>
								<li><a data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"><i class="fa-solid fa-bag-shopping"></i></a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<a href="#" class="carrito-flotante"><i class="fa-solid fa-bag-shopping"></i></a>

	<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
		<div class="offcanvas-header">
			<img src="<?= base_url(); ?>/public/template/images/milislens-logo-blanco.png" alt="">
			<button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
		</div>
		<div class="offcanvas-body">
			<ul class="menu-izq">
				<li><a href="#">Productos</a></li>
				<li><a href="#">Pelucas</a></li>
				<li><a href="#">Lacefront</a></li>
				<li><a href="#">Coletas</a></li>
				<li><a href="#">Accesorios</a></li>
				<li><a href="#">Promos</a></li>
				<li><a href="#" class="menu-hover">Lentes de contacto</a></li>
				<li><a href="#" class="menu-hover">Carteras</a></li>
				<li><a href="#">Blog</a></li>
				<li><a href="#">Tracking Shalom</a></li>
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