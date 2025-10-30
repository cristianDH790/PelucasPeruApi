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
					Peluca Barbara
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
					<div class="min">
						<a href="#"><img src="<?= base_url(); ?>public/template/images/productos/PELUCA-BARBARA.jpg" alt=""></a>
						<a href="#"><img src="<?= base_url(); ?>public/template/images/productos/PELUCA-CLARA.jpg" alt=""></a>
						<a href="#"><img src="<?= base_url(); ?>public/template/images/productos/PELUCA-ARIA.jpg" alt=""></a>
					</div>
					<div class="box-img">
						<img src="<?= base_url(); ?>public/template/images/productos/PELUCA-BARBARA.jpg">
					</div>
				</div>
			</div>

			<div class="col-md-6">
				<div class="contenido">
					<h2>Peluca Barbara</h2>
					<div class="box-precios">
						<h3>S/ 150.00</h3>
					</div>

					<div class="cantidad">
						<p>Cantidad:</p>
						<div class="cart-plus-minus">
							<button type="button" onclick="cambioStock('resta')" class="dec qtybutton qty-down" style="display:inline-block">
								<i class="fa-solid fa-minus"></i>
							</button>
							<input step="1" min="1" type="text" readonly="" class="qty" title="Qty" name="cantidad" id="cantidad" value="1">
							<button type="button" onclick="cambioStock('suma')" class="inc qtybutton qty-up" style="display:inline-block">
								<i class="fa fa-plus"></i>
							</button>
						</div>
					</div>

					<img src="<?= base_url(); ?>public/template/images/banner-productoooos.png" alt="">

					<div class="btns-compartir">
						<a href="<?= base_url(); ?>carrito-de-compras" class="btn-carrito">
							<i class="fa-solid fa-shopping-cart"></i> Agregar al carrito
						</a>
						<a href="https://wa.me/51977533398" class="btn-compartir">Comprar por whatsapp</a>
					</div>

					<div class="resumen-producto">
						<div class="accordion accordion-flush" id="accordionFlushExample">
							<div class="accordion-item">
								<h2 class="accordion-header">
									<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
										Descripción
									</button>
								</h2>
								<div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
									<div class="accordion-body">
										<p>.....</p>
									</div>
								</div>
							</div>
							<div class="accordion-item">
								<h2 class="accordion-header">
									<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
										Qué incluye tu compra?
									</button>
								</h2>
								<div id="flush-collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
									<div class="accordion-body">
										<p>...</p>
									</div>
								</div>
							</div>
							<div class="accordion-item">
								<h2 class="accordion-header">
									<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
										Como llevar tu peluca?
									</button>
								</h2>
								<div id="flush-collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
									<div class="accordion-body">
										<p>...</p>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="complementos">
						<h5>Agrega a tu compra</h6>

							<div class="row">
								<div class="col-md-4">
									<div class="bg-image">
										<img src="<?= base_url(); ?>public/template/images/productos/liga-de-goma.jpg" alt="">
									</div>
									<div class="bg-resumen">
										<h3>Liga de goma</h3>
										<div class="box-precio">
											<h5>S/ 40.00</h5>
										</div>
										<div class="btns">
											<a href="<?= base_url(); ?>producto-detalle" class="comprar">Comprar</a>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="bg-image">
										<img src="<?= base_url(); ?>public/template/images/productos/desenredante.jpg" alt="">
									</div>
									<div class="bg-resumen">
										<h3>Desenredante</h3>
										<div class="box-precio">
											<h5>S/ 15.00</h5>
										</div>
										<div class="btns">
											<a href="<?= base_url(); ?>producto-detalle" class="comprar">Comprar</a>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="bg-image">
										<img src="<?= base_url(); ?>public/template/images/productos/malla.jpg" alt="">
									</div>
									<div class="bg-resumen">
										<h3>Malla Premium</h3>
										<div class="box-precio">
											<h5>S/ 20.00</h5>
										</div>
										<div class="btns">
											<a href="<?= base_url(); ?>producto-detalle" class="comprar">Comprar</a>
										</div>
									</div>
								</div>
							</div>

					</div>

				</div>
			</div>

			<div class="col-md-12">
				<div class="valoracion">
					<h4>Calificar producto</h4>
					<div class="rating" id="rating">
						<span class="star" data-value="1">★</span>
						<span class="star" data-value="2">★</span>
						<span class="star" data-value="3">★</span>
						<span class="star" data-value="4">★</span>
						<span class="star" data-value="5">★</span>
					</div>

					<p id="resultado"></p>

					<p class="average" id="averageText">Promedio: 0.0 / 5</p>

					<!-- Barras de progreso -->
					<div class="rating-summary" id="ratingSummary"></div>


					<form id="feedbackForm">
						<h2>Tu opinión</h2>

						<div class="row">
							<div class="col-md-6">
								<label for="nombre">Nombres y Apellidos:</label>
								<input type="text" id="nombre" name="nombre" placeholder="Escribe tu nombre completo" required>
							</div>
							<div class="col-md-6">
								<label for="email">Correo Electrónico:</label>
								<input type="email" id="email" name="email" placeholder="tu@email.com" required>
							</div>
							<div class="col-md-12">
								<label for="comentario">Comentario:</label>
								<textarea id="comentario" name="comentario" placeholder="Escribe aquí tu opinión..." required></textarea>
							</div>
							<div class="col-md-12">
								<div class="captcha">
									<span class="captcha-code" id="captchaCode"></span>
									<input type="text" id="captchaInput" placeholder="Ingresa el código" required>
								</div>
								<button type="submit">Enviar</button>
								<!-- <p class="success" id="successMsg">✅ ¡Gracias por tu comentario!</p> -->
							</div>
						</div>

					</form>

				</div>
			</div>

		</div>
	</div>
</section>

<section class="productos-relacionados">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">

				<h2>Productos relacionados</h2>

				<section class="slider-home">
					<div class="owl-carousel6 owl-theme">
						<div class="item">
							<div class="bg-image">
								<a href="<?= base_url(); ?>producto-detalle">
									<img src="<?= base_url(); ?>public/template/images/productos/PELUCA-ARIA.jpg" class="img1" alt="">
								</a>
								<a href="<?= base_url(); ?>producto-detalle">
									<img src="<?= base_url(); ?>public/template/images/productos/PELUCA-ARIA.jpg" class="img2" alt="">
								</a>
							</div>
							<div class="bg-resumen">
								<h3>Peluca Barbara</h3>
								<div class="box-precio">
									<h5>S/ 150.00</h6>
								</div>
								<div class="btns">
									<a href="<?= base_url(); ?>producto-detalle" class="comprar">Comprar</a>
								</div>
							</div>
						</div>
						<div class="item">
							<div class="bg-image">
								<a href="<?= base_url(); ?>producto-detalle">
									<img src="<?= base_url(); ?>public/template/images/productos/PELUCA-BARBARA.jpg" class="img1" alt="">
								</a>
								<a href="<?= base_url(); ?>producto-detalle">
									<img src="<?= base_url(); ?>public/template/images/productos/PELUCA-CLARA.jpg" class="img2" alt="">
								</a>
							</div>
							<div class="bg-resumen">
								<h3>Peluca Barbara</h3>
								<div class="box-precio">
									<h5>S/ 150.00</h5>
								</div>
								<div class="btns">
									<a href="<?= base_url(); ?>producto-detalle" class="comprar">Comprar</a>
								</div>
							</div>
						</div>
						<div class="item">
							<div class="bg-image">
								<a href="<?= base_url(); ?>producto-detalle">
									<img src="<?= base_url(); ?>public/template/images/productos/PELUCA-AMARA.jpg" class="img1" alt="">
								</a>
								<a href="<?= base_url(); ?>producto-detalle">
									<img src="<?= base_url(); ?>public/template/images/productos/PELUCA-AMARA.jpg" class="img2" alt="">
								</a>
							</div>
							<div class="bg-resumen">
								<h3>Peluca Barbara</h3>
								<div class="box-precio">
									<h5>S/ 150.00</h5>
								</div>
								<div class="btns">
									<a href="<?= base_url(); ?>producto-detalle" class="comprar">Comprar</a>
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>
		</div>
	</div>
</section>

<script>
	// Generar CAPTCHA aleatorio
	function generarCaptcha() {
		const letras = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
		let codigo = '';
		for (let i = 0; i < 5; i++) {
			codigo += letras.charAt(Math.floor(Math.random() * letras.length));
		}
		document.getElementById('captchaCode').textContent = codigo;
		return codigo;
	}

	let codigoActual = generarCaptcha();

	// Validar formulario
	document.getElementById('feedbackForm').addEventListener('submit', function(e) {
		e.preventDefault();

		const captchaIngresado = document.getElementById('captchaInput').value.trim();
		const successMsg = document.getElementById('successMsg');

		if (captchaIngresado.toUpperCase() !== codigoActual) {
			alert('❌ El código CAPTCHA no coincide. Inténtalo de nuevo.');
			codigoActual = generarCaptcha();
			document.getElementById('captchaInput').value = '';
			return;
		}

		// Si pasa validación
		successMsg.style.display = 'block';
		setTimeout(() => successMsg.style.display = 'none', 3000);

		// Limpia formulario
		this.reset();
		codigoActual = generarCaptcha();
	});

	// Datos iniciales de ejemplo
	let ratings = {
		5: 10,
		4: 5,
		3: 3,
		2: 1,
		1: 0
	};

	const stars = document.querySelectorAll('.star');
	const summary = document.getElementById('ratingSummary');
	const averageText = document.getElementById('averageText');

	function renderSummary() {
		summary.innerHTML = '';
		const total = Object.values(ratings).reduce((a, b) => a + b, 0) || 1;
		const average = (
			Object.entries(ratings).reduce((sum, [k, v]) => sum + k * v, 0) / total
		).toFixed(1);
		averageText.textContent = `Promedio: ${average} / 5`;

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

	// Inicializar gráfico
	renderSummary();

	// Hover y clic en estrellas
	function resetHover() {
		stars.forEach(s => s.classList.remove('hover'));
	}

	stars.forEach(star => {
		star.addEventListener('mouseover', () => {
			resetHover();
			for (let i = 0; i < star.dataset.value; i++) stars[i].classList.add('hover');
		});
		star.addEventListener('mouseout', resetHover);
		star.addEventListener('click', () => {
			const value = parseInt(star.dataset.value);
			ratings[value]++; // Suma un voto
			renderSummary(); // Actualiza las barras
			stars.forEach(s => s.classList.remove('active'));
			for (let i = 0; i < value; i++) stars[i].classList.add('active');
		});
	});
</script>