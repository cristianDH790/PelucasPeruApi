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

				<div class="productos-todos" id="lentes">
					<div class="row">

						<div class="col-md-3">
							<div class="bg-image">
								<a href="<?= base_url(); ?>producto-detalle">
									<img src="<?= base_url(); ?>public/template/images/cate/verde.jpg" class="img1" alt="">
								</a>
							</div>
							<div class="bg-resumen">
								<h3>Verdes</h3>
								<div class="btns">
									<a href="<?= base_url(); ?>producto-detalle" class="comprar">Comprar</a>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="bg-image">
								<a href="<?= base_url(); ?>producto-detalle">
									<img src="<?= base_url(); ?>public/template/images/cate/azul.jpg" class="img1" alt="">
								</a>
							</div>
							<div class="bg-resumen">
								<h3>Azules</h3>
								<div class="btns">
									<a href="<?= base_url(); ?>producto-detalle" class="comprar">Comprar</a>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="bg-image">
								<a href="<?= base_url(); ?>producto-detalle">
									<img src="<?= base_url(); ?>public/template/images/cate/miel.jpg" class="img1" alt="">
								</a>
							</div>
							<div class="bg-resumen">
								<h3>Miel</h3>
								<div class="btns">
									<a href="<?= base_url(); ?>producto-detalle" class="comprar">Comprar</a>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="bg-image">
								<a href="<?= base_url(); ?>producto-detalle">
									<img src="<?= base_url(); ?>public/template/images/cate/gris.jpg" class="img1" alt="">
								</a>
							</div>
							<div class="bg-resumen">
								<h3>Grises</h3>
								<div class="btns">
									<a href="<?= base_url(); ?>producto-detalle" class="comprar">Comprar</a>
								</div>
							</div>
						</div>

						<div class="col-md-3">
							<div class="bg-image">
								<a href="<?= base_url(); ?>producto-detalle">
									<img src="<?= base_url(); ?>public/template/images/cate/celeste.jpg" class="img1" alt="">
								</a>
							</div>
							<div class="bg-resumen">
								<h3>Verdes</h3>
								<div class="btns">
									<a href="<?= base_url(); ?>producto-detalle" class="comprar">Comprar</a>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="bg-image">
								<a href="<?= base_url(); ?>producto-detalle">
									<img src="<?= base_url(); ?>public/template/images/cate/marron.jpg" class="img1" alt="">
								</a>
							</div>
							<div class="bg-resumen">
								<h3>Verdes</h3>
								<div class="btns">
									<a href="<?= base_url(); ?>producto-detalle" class="comprar">Comprar</a>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="bg-image">
								<a href="<?= base_url(); ?>producto-detalle">
									<img src="<?= base_url(); ?>public/template/images/cate/halloween.jpg" class="img1" alt="">
								</a>
							</div>
							<div class="bg-resumen">
								<h3>Verdes</h3>
								<div class="btns">
									<a href="<?= base_url(); ?>producto-detalle" class="comprar">Comprar</a>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="bg-image">
								<a href="<?= base_url(); ?>producto-detalle">
									<img src="<?= base_url(); ?>public/template/images/cate/accesorios.jpg" class="img1" alt="">
								</a>
							</div>
							<div class="bg-resumen">
								<h3>Verdes</h3>
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