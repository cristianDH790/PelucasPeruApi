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
			<div class="col-md-12">
				<p><a href="<?= base_url(); ?>">Inicio</a> <span>></span> Productos</p>
			</div>
		</div>
	</div>
</section>

<section class="productos-int">
	<div class="container-fluid">
		<div class="row">

			<div class="col-md-12">
				<h2>Productos</h2>

				<div class="d-flex">
					<div class="seleccion">
						<select class="form-select" aria-label="Default select example">
							<option selected>9</option>
							<option value="1">18</option>
							<option value="2">36</option>
							<option value="3">Todos</option>
						</select>
					</div>
					<div class="seleccion2">
						<select class="form-select form-select1" aria-label="Default select example">
							<option selected>Más recientes</option>
							<option value="1">Más antiguos</option>
							<option value="2">Menor precio</option>
							<option value="3">Mayor precio</option>
						</select>
					</div>
				</div>

			</div>

			<div class="col-md-3">
				<div class="accordion" id="accordionExample">
					<div class="accordion-item">
						<h2 class="accordion-header" id="headingOne">
							<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
								Categorías
							</button>
						</h2>
						<div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
							<div class="accordion-body">
								<ul>
									<li><a href="#" class="active">Todas</a></li>
									<li><a href="#">Pelucas</a></li>
									<li><a href="#">Lacefront</a></li>
									<li><a href="#">Coletas</a></li>
									<li><a href="#">Accesorios</a></li>
									<li><a href="#">Lentes de contacto</a></li>
									<li><a href="#">Carteras</a></li>
								</ul>
							</div>
						</div>
					</div>
					<div class="accordion-item">
						<h2 class="accordion-header" id="headingTwo">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
								Precio
							</button>
						</h2>
						<div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
							<div class="accordion-body" id="precio-rango">
								<div class="price-input d-flex">
									<div class="field">
										<input style="margin-left: 0;" type="number" class="input-min" value="0.00">
									</div>
									<div class="field">
										<input type="number" class="input-max" value="150.00">
									</div>
								</div>
								<div class="slider">
									<div class="progress"></div>
								</div>
								<div class="range-input">
									<input type="range" class="range-min" min="0.00" max="149.00" value="0.00" step="1">
									<input type="range" class="range-max" min="1.00" max="150.00" value="150.00" step="1">
								</div>

							</div>
						</div>
					</div>
					<div class="accordion-item">
						<h2 class="accordion-header" id="headingTree">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTree" aria-expanded="false" aria-controls="collapseTree">
								Colores
							</button>
						</h2>
						<div id="collapseTree" class="accordion-collapse collapse" aria-labelledby="headingTree" data-bs-parent="#accordionExample">
							<div class="accordion-body">
								<ul>
									<li><a href="#">Todos</a></li>
									<li><a href="#">Marrón</a></li>
									<li><a href="#">Rubio</a></li>
									<li><a href="#">Rojo</a></li>
									<li><a href="#">Negro</a></li>
									<li><a href="#">Cobrizo</a></li>
								</ul>
							</div>
						</div>
					</div>
					<div class="accordion-item">
						<h2 class="accordion-header" id="headingFour">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
								Características
							</button>
						</h2>
						<div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
							<div class="accordion-body">
								<ul>
									<li><a href="#">Largos</a></li>
									<li><a href="#">Cortos</a></li>
									<li><a href="#">Laceas</a></li>
									<li><a href="#">Ondualdas</a></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-9">
				<div class="productos-todos">
					<div class="row">

						<div class="col-md-4">
							<div class="bg-image">
								<span>40%</span>
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
									<h5>S/ 90.00</h5>
									<h6>S/ 150.00</h6>
								</div>
								<div class="btns">
									<a href="<?= base_url(); ?>producto-detalle" class="comprar">Comprar</a>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="bg-image">
								<a href="<?= base_url(); ?>producto-detalle">
									<img src="<?= base_url(); ?>public/template/images/productos/PELUCA-FAVIANNA.jpg" class="img1" alt="">
								</a>
								<a href="<?= base_url(); ?>producto-detalle">
									<img src="<?= base_url(); ?>public/template/images/productos/PELUCA-FAVIANNA.jpg" class="img2" alt="">
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
						<div class="col-md-4">
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
						<div class="col-md-4">
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
						<div class="col-md-4">
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
						<div class="col-md-4">
							<div class="bg-image">
								<a href="<?= base_url(); ?>producto-detalle">
									<img src="<?= base_url(); ?>public/template/images/productos/PELUCA-FAVIANNA.jpg" class="img1" alt="">
								</a>
								<a href="<?= base_url(); ?>producto-detalle">
									<img src="<?= base_url(); ?>public/template/images/productos/PELUCA-FAVIANNA.jpg" class="img2" alt="">
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
						<div class="col-md-4">
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
						<div class="col-md-4">
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
				</div>

				<div class="paginacion" style="display: block;">
					<ul>
						<li class="disabled page-item"><a class="page-link" href="1"><i class="fa-solid fa-angles-left"></i></a></li>
						<li class="disabled page-item"><a class="page-link" href="1"><i class="fa-solid fa-angle-left"></i></a></li>
						<li class=" page-item active"><span class="page-link">1</span></li>
						<li class=" page-item"><span class="page-link">2</span></li>
						<li class=" page-item"><span class="page-link">3</span></li>
						<li class="disabled page-item"><a class="page-link" href="1"><i class="fa-solid fa-angle-right"></i></a></li>
						<li class="disabled page-item"><a class="page-link" href="1"><i class="fa-solid fa-angles-right"></i></a></li>
					</ul>
				</div>

			</div>

		</div>
	</div>
</section>



<style>
	input[type="number"]::-webkit-outer-spin-button,
	input[type="number"]::-webkit-inner-spin-button {
		-webkit-appearance: none;
	}
</style>

<!-- Script para el rango de los precios -->
<script>
	const rangeInput = document.querySelectorAll(".range-input input"),
		priceInput = document.querySelectorAll(".price-input input"),
		range = document.querySelector(".slider .progress");
	let priceGap = 0.00;

	priceInput.forEach(input => {
		input.addEventListener("input", e => {
			let minPrice = parseInt(priceInput[0].value),
				maxPrice = parseInt(priceInput[1].value);

			if ((maxPrice - minPrice >= priceGap) && maxPrice <= rangeInput[1].max) {
				if (e.target.className === "input-min") {
					rangeInput[0].value = minPrice;
					range.style.left = ((minPrice / rangeInput[0].max) * 100) + "%";
				} else {
					rangeInput[1].value = maxPrice;
					range.style.right = 100 - (maxPrice / rangeInput[1].max) * 100 + "%";
				}
			}
		});
	});

	rangeInput.forEach(input => {
		input.addEventListener("input", e => {
			let minVal = parseInt(rangeInput[0].value),
				maxVal = parseInt(rangeInput[1].value);

			if ((maxVal - minVal) < priceGap) {
				if (e.target.className === "range-min") {
					rangeInput[0].value = maxVal - priceGap
				} else {
					rangeInput[1].value = minVal + priceGap;
				}
			} else {
				priceInput[0].value = minVal;
				priceInput[1].value = maxVal;
				range.style.left = ((minVal / rangeInput[0].max) * 100) + "%";
				range.style.right = 100 - (maxVal / rangeInput[1].max) * 100 + "%";
			}
		});
	});


	const rangeInput2 = document.querySelectorAll(".range-input2 input"),
		priceInput2 = document.querySelectorAll(".price-input2 input"),
		range2 = document.querySelector(".slider2 .progress2");
	let priceGap2 = 0.00;

	priceInput2.forEach(input => {
		input.addEventListener("input", e => {
			let minPrice = parseInt(priceInput2[0].value),
				maxPrice = parseInt(priceInput2[1].value);

			if ((maxPrice - minPrice >= priceGap2) && maxPrice <= rangeInput2[1].max) {
				if (e.target.className === "input-min") {
					rangeInput2[0].value = minPrice;
					range2.style.left = ((minPrice / rangeInput2[0].max) * 100) + "%";
				} else {
					rangeInput2[1].value = maxPrice;
					range2.style.right = 100 - (maxPrice / rangeInput2[1].max) * 100 + "%";
				}
			}
		});
	});

	rangeInput2.forEach(input => {
		input.addEventListener("input", e => {
			let minVal = parseInt(rangeInput2[0].value),
				maxVal = parseInt(rangeInput2[1].value);

			if ((maxVal - minVal) < priceGap2) {
				if (e.target.className === "range-min") {
					rangeInput2[0].value = maxVal - priceGap2
				} else {
					rangeInput2[1].value = minVal + priceGap2;
				}
			} else {
				priceInput2[0].value = minVal;
				priceInput2[1].value = maxVal;
				range2.style.left = ((minVal / rangeInput2[0].max) * 100) + "%";
				range2.style.right = 100 - (maxVal / rangeInput2[1].max) * 100 + "%";
			}
		});
	});
</script>