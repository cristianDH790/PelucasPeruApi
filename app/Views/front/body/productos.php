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

						<select class="form-select numero_productos" aria-label="Default select example">
							<option selected>9</option>
							<option value="18">18</option>
							<option value="36">36</option>
							<option value="0">Todos</option>
						</select>
					</div>
					<div class="seleccion2">

						<div class="input-group">
							<input type="text" class="form-control" placeholder="Buscar..." aria-label="Buscar..." id="buscar" aria-describedby="button-addon2">
							<button class="btn btn-outline-secondary" id="btn-buscar" type="button" id="button-addon2"><i class="fa-solid fa-search"></i></button>
						</div>

						<select class="form-select form-select1 ordenamiento" aria-label="Default select example">
							<option selectedvalue="1">Más recientes</option>
							<option value="1">Más antiguos</option>
							<option value="3">Menor precio</option>
							<option value="4">Mayor precio</option>
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
									<li>
										<a href="<?= base_url('productos/' . $categoriaproducto->urlamigable) ?>"
											class="<?= (uri_string() === 'productos/' . $categoriaproducto->urlamigable) ? 'active' : '' ?>">
											Todas
										</a>
									</li>
									<?php foreach ($productocategorias as $productocategoria): ?>
										<li>
											<a href="<?= base_url('productos/' . $categoriaproducto->urlamigable . '/' . $productocategoria->urlamigable) ?>"
												class="<?= (uri_string() === 'productos/' . $categoriaproducto->urlamigable . '/' . $productocategoria->urlamigable) ? 'active' : '' ?>">
												<?= $productocategoria->nombre ?>
											</a>
										</li>
									<?php endforeach; ?>
								</ul>

							</div>
						</div>
					</div>
					<!-- <div class="accordion-item">
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
					</div> -->
					<div class="accordion-item">
						<h2 class="accordion-header" id="headingTree">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTree" aria-expanded="false" aria-controls="collapseTree">
								Colores
							</button>
						</h2>
						<!-- <div id="collapseTree" class="accordion-collapse collapse" aria-labelledby="headingTree" data-bs-parent="#accordionExample">
							<div class="accordion-body">
								<ul>
									<li><a href="#">Todos</a></li>
									<? foreach ($colores as $color): ?>
										<li><a href="#"><?= $color->nombre  ?></a></li>
									<? endforeach; ?>
								</ul>
							</div>
						</div> -->
						<div id="collapseTree" class="accordion-collapse collapse" aria-labelledby="headingTree" data-bs-parent="#accordionExample">
							<div class="accordion-body">
								<ul id="lista-colores">
									<li>
										<a href="?color=0"
											class="color-link <?= (!isset($_GET['color']) || $_GET['color'] == 0) ? 'active' : '' ?>">
											Todos
										</a>
									</li>
									<?php foreach ($colores as $color): ?>
										<li>
											<a href="?color=<?= $color->idcolor ?>"
												class="color-link <?= (isset($_GET['color']) && $_GET['color'] == $color->idcolor) ? 'active' : '' ?>"
												data-id="<?= $color->idcolor ?>">
												<?= $color->nombre ?>
											</a>
										</li>
									<?php endforeach; ?>

								</ul>
							</div>
						</div>
					</div>
					<!-- <div class="accordion-item">
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
					</div> -->
				</div>
			</div>

			<div class="col-md-9">
				<div class="productos-todos">
					<div class="row" id="container-productos">

					</div>
				</div>

				<div class="paginacion" style="display: block;">
					<!-- <ul>
						<li class="disabled page-item"><a class="page-link" href="1"><i class="fa-solid fa-angles-left"></i></a></li>
						<li class="disabled page-item"><a class="page-link" href="1"><i class="fa-solid fa-angle-left"></i></a></li>
						<li class=" page-item active"><span class="page-link">1</span></li>
						<li class=" page-item"><span class="page-link">2</span></li>
						<li class=" page-item"><span class="page-link">3</span></li>
						<li class="disabled page-item"><a class="page-link" href="1"><i class="fa-solid fa-angle-right"></i></a></li>
						<li class="disabled page-item"><a class="page-link" href="1"><i class="fa-solid fa-angles-right"></i></a></li>
					</ul> -->
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



<script>
	document.addEventListener("DOMContentLoaded", function() {

		const CARGA = document.querySelector('.carga');
		const paginacionviews = document.querySelectorAll('.paginacion');
		CARGA.style.display = 'block'; // Muestra loader
		// Oculta paginación y muestra loader inicial
		paginacionviews.forEach(pag => pag.classList.add('d-none'));

		// Cargar productos por primera vez

		const idProductoCategoria = <?= json_encode($productocategoriaurl->idproductocategoria) ?>;
		const idProductoCategoriaPadre = <?= json_encode($categoriaproducto->idproductocategoria) ?>;

		console.log("idProductoCategoria", idProductoCategoria);
		console.log("idProductoCategoriaPadre", idProductoCategoriaPadre);
		let pagina = 1;

		// Captura parámetros de URL
		const params = new URLSearchParams(window.location.search);
		const buscar = params.get("buscar") ?? '';
		let parametro = buscar ? 'nombre' : '';
		let valor = buscar || '';

		//capturmos el color si existe
		const color = params.get("color") ?? '';
		let idcolor = 0;
		if (color) {
			idcolor = parseInt(color, 10);
		}


		// Capturamos inputs
		const NUMEROPRODUCTOS = document.querySelectorAll(".numero_productos");
		const ORDENAMIENTO = document.querySelectorAll(".ordenamiento");

		// Obtener valor del primero como referencia
		let registro = parseInt(NUMEROPRODUCTOS[0].value, 10);

		let ordenCriterio;
		let ordenTipo;

		if (ORDENAMIENTO[0].value === '1') {
			ordenCriterio = 'fechapublicacion';
			ordenTipo = 'asc';
		} else if (ORDENAMIENTO[0].value === '2') {
			ordenCriterio = 'fechapublicacion';
			ordenTipo = 'desc';
		} else if (ORDENAMIENTO[0].value === '3') {
			ordenCriterio = 'precioventa';
			ordenTipo = 'asc';
		} else if (ORDENAMIENTO[0].value === '4') {
			ordenCriterio = 'precioventa';
			ordenTipo = 'desc';
		}

		// Cargar productos
		showProductos(pagina, idProductoCategoria, registro, parametro, valor, ordenCriterio, ordenTipo, idcolor, idProductoCategoriaPadre);

		// Manejar cambios en cualquiera de los inputs
		NUMEROPRODUCTOS.forEach((input, index) => {
			input.addEventListener("change", function() {
				let registro = parseInt(input.value, 10);

				// Sincronizar el otro input
				NUMEROPRODUCTOS.forEach((otroInput, i) => {
					if (i !== index) {
						otroInput.value = input.value;
					}
				});

				showProductos(pagina, idProductoCategoria, registro, parametro, valor, ordenCriterio, ordenTipo, idcolor, idProductoCategoriaPadre);
			});
		});

		//capturamos el ordenamiento , su change
		ORDENAMIENTO.forEach((input, index) => {
			input.addEventListener("change", function() {

				// Sincronizar el otro input
				ORDENAMIENTO.forEach((otroInput, i) => {
					if (i !== index) {
						otroInput.value = input.value;
					}
				});

				let ordenCriterio;
				let ordenTipo;

				if (input.value === '1') {
					ordenCriterio = 'fechapublicacion';
					ordenTipo = 'asc';
				} else if (input.value === '2') {
					ordenCriterio = 'fechapublicacion';
					ordenTipo = 'desc';
				} else if (input.value === '3') {
					ordenCriterio = 'precioventa';
					ordenTipo = 'asc';
				} else if (input.value === '4') {
					ordenCriterio = 'precioventa';
					ordenTipo = 'desc';
				}

				let registro = parseInt(NUMEROPRODUCTOS[0].value, 10);
				showProductos(pagina, idProductoCategoria, registro, parametro, valor, ordenCriterio, ordenTipo, idcolor, idProductoCategoriaPadre);
			});
		});



		// Paginador (evento click global)
		document.body.addEventListener("click", function(e) {
			const target = e.target;
			const carga = document.querySelector(".carga");

			if (target.tagName === "A" && target.closest(".paginacion")) {
				e.preventDefault();

				// Muestra loader y hace scroll arriba
				carga.style.display = "block";
				window.scrollTo({
					top: 0,
					behavior: "smooth"
				});

				const pagina = target.getAttribute("href");



				showProductos(pagina, idProductoCategoria, registro, parametro, valor, ordenCriterio, ordenTipo, idcolor, idProductoCategoriaPadre);
			}
		});

		const btnBuscar = document.getElementById('btn-buscar');
		const inputBuscar = document.getElementById('buscar');

		// Escuchar el click del botón
		btnBuscar.onclick = function() {
			const valorInput = inputBuscar.value; // Captura el valor del input
			console.log("Valor del input:", valorInput);

			let parametro = "nombre";
			let valor = valorInput;


			// Aquí puedes llamar a tu función capturaQR o hacer otra acción
			showProductos(pagina, idProductoCategoria, registro, parametro, valor, ordenCriterio, ordenTipo, idcolor, idProductoCategoriaPadre);
		};

		//

		//funciones
		function showProductos(pagina, idProductoCategoria, registro, parametro, valor, ordenCriterio, ordenTipo, idcolor, idProductoCategoriaPadre) {
			const CARGA = document.querySelector('.carga');
			CARGA.style.display = 'block';
			const url = BASE_URL + 'api/publico/producto/listar';
			const data = {
				ordenCriterio: ordenCriterio,
				ordenTipo: ordenTipo,
				parametro: parametro,
				valor: valor,
				idEstado: 325,
				idProductoCategoria: idProductoCategoria,
				idrProductoCategoria: idProductoCategoriaPadre,
				idpDestacado: 0,
				idpPromocion: 0,
				idpColor: idcolor,
				idpComplemento: [400, 402],
				registros: registro,
				pagina: pagina,
			};

			fetch(url, {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json'
					},
					body: JSON.stringify(data)
				})
				.then(res => res.json())
				.then(response => {
					renderProductos(response);
					console.log("response", response);
				})
				.catch(error => {
					console.error(error);
					document.getElementById('container-productos').innerHTML = '<div class="resultados"><p>No se encontraron resultados</p></div>';
				})
				.finally(() => {
					CARGA.style.display = 'none'; // Oculta loader
				});
		}

		function getDescuento(precioVenta, precioLista) {
			const venta = parseFloat(precioVenta);
			const lista = parseFloat(precioLista);
			if (lista <= venta || isNaN(venta) || isNaN(lista)) return 0;
			return Math.round(((lista - venta) / lista) * 100);
		}

		function renderProductos(response) {
			const CARGA = document.querySelector('.carga');
			const container = document.getElementById('container-productos');
			const paginacionviews = document.querySelectorAll('.paginacion');
			// Si no hay productos
			if (!response.content || response.content.length === 0) {
				container.innerHTML = '<div class="resultados"><p>No se encontraron resultados</p></div>';
				paginacionviews.classList.add('d-none');
				return;
			}
			const productosHTML = response.content.map(item => {
				console.log("item", item);
				const descuento = getDescuento(item.precioVenta, item.precioLista);

				const precioVenta = parseFloat(item.precioVenta).toFixed(2);
				const precioLista = parseFloat(item.precioLista).toFixed(2);
				const tieneDescuento = precioLista > precioVenta;
				return `

				        <div class="col-md-4">
							<div class="bg-image">
								${descuento > 0 ? `<span>${descuento}%</span>` : ''}
								<a href="<?= base_url(); ?>producto-detalle/${item.urlAmigable}">
                                    <img src="<?= base_url(); ?>archivos/productoimagen/${item.urlImagen ? item.urlImagen : 'imagen.png'}"  class="img1" alt="">
                                </a>

								
								${item.urlImagen2 ? `
                                <a href="<?= base_url(); ?>producto-detalle/${item.urlAmigable}">
                                    <img src="<?= base_url(); ?>archivos/productoimagen/${item.urlImagen2}" class="img2" alt="">
                                </a>
                                ` : ''}

							</div>
							<div class="bg-resumen">
								<h3>${item.nombre}</h3>
								<div class="box-precio">
									 ${item.precioVenta== item.precioLista?`<h5>S/ ${item.precioVenta}</h5>`: `<h5>S/ ${item.precioVenta}</h5><h6>S/ ${item.precioLista}</h6>` }
								</div>
								<div class="btns">
									<a href="<?= base_url(); ?>producto-detalle/${item.urlAmigable}" class="comprar">Comprar</a>
								</div>
							</div>
						</div>

			           
		      `;

			}).join('');

			container.innerHTML = productosHTML;

			// Mostrar paginación si hay más de una página
			const paginatorType = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ? [3, 2] : [5, 1];

			paginacion(response.paginator, ...paginatorType);

			paginacionviews.forEach(pag => pag.classList.remove('d-none'));
			CARGA.style.display = 'none';
			// marcarListaDeseos();
			// actualizarContadorListaDeseos();
			// actualizarContadorCarrito();

		}

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




	});
</script>