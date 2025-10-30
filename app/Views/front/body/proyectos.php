<section class="bg_menu_page">
	<div class="inner_subpage_banner">
		<div class="container-fluid">
			<div class="text-banner">
				Proyectos
			</div>
		</div>
	</div>
</section>

<section class="miga">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<p><a href="<?= base_url(); ?>">Inicio</a> <span>»</span> Proyectos</p>
			</div>
		</div>
	</div>
</section>

<section class="proyectos-int">
	<div class="container-fluid">
		<div class="row">

			<div class="col-md-12">

				<h2>Proyectos</h2>

				<div class="box-filtros">
					<div class="d-flex">
						<div class="form-group">
							<label>Ordenar por</label>
							<select name="ordenCriterio" id="ordenCriterio">
								<option value="fecha_desc">Más reciente</option>
								<option value="fecha_asc">Más antiguo</option>
								<option value="nombre_asc">Título: A - Z</option>
								<option value="nombre_asc">Título: Z - A</option>
								
							</select>
						</div>
						<div class="form-group">
							<label>Categoría</label>
							<select name="idPcategoria" id="idPcategoria">
								<option value="">Todas</option>
								<? if ($pcategorias):
									foreach ($pcategorias as $pcategoria): ?>
										<option value="<?= $pcategoria->idparametro ?>"><?= $pcategoria->nombre ?></option>
								<? endforeach;
								endif ?>
							</select>
						</div>
						<div class="form-group">
							<label>Buscar</label>
							<input type="text" name="valor" id="valor">
						</div>
						<div class="form-group">
							<label class="ocultar-label">&nbsp;</label>
							<button onclick="buscarProyectos()" class="buscar">Buscar</button>
							<button onclick="refresh()" class="refrescar"><i class="fa-solid fa-refresh"></i></button>
						</div>
					</div>
				</div>

				<div class="totalidad d-flex">
					<p>4 de 50 proyectos</p>
					<div class="mostrar-mas">
						<p>Mostrar</p>
						<select name="registros" id="registros">
							<option value="4">4</option>
							<option value="8">8</option>
							<option value="12">12</option>
						</select>
					</div>
				</div>

				<div id="container-proyectos">

				</div>
				<div class="paginacion" style="display: block;">
					<!-- =================== Paginado ===================  -->
				</div>

			</div>

		</div>
	</div>
</section>

<script>
	showProyectos(1)

	function showProyectos(pagina) {
		$(".carga").show();

		const filtros = JSON.parse(localStorage.getItem('Casal-filtroProyectos')) || {};
		console.log("filtros")
		console.log(filtros)
		if (JSON.stringify(filtros) != '{}') {
			$("#ordenCriterio").val(filtros.ordenCriterio)
			$("#ordenTipo").val(filtros.ordenTipo)
			$("#valor").val(filtros.valor)
			$("#idPcategoria").val(filtros.idPcategoria)
			$("#idCliente").val(filtros.idCliente)
		}

		const data = {
			ordenCriterio: filtros.ordenCriterio || $("#ordenCriterio").val(),
			ordenTipo: "asc",
			parametro: "nombre",
			valor: filtros.valor || $("#valor").val(),
			idPcategoria: filtros.idPcategoria || $("#idPcategoria").val(),
			registros: filtros.registros || $("#registros").val(),
			pagina: pagina,
		};

		localStorage.setItem('Casal-filtroProyectos', JSON.stringify(data));

		$.ajax({
				url: `${BASE_URL}ProyectoController/proyectos?nocache=${new Date().getTime()}`,
				type: "post",
				dataType: "json",
				data: data,

			}).done(proyectosResponse)
			.fail(function(response) {

				$("#container-proyectos").html(' <div class="resultados"><p>No se encontraron resultados</p></div>');
				$(".carga").css("display", "none");
			});
	}

	function proyectosResponse(response) {
		if (!response.content || response.content.length === 0) {
			$("#container-proyectos").html('<td colspan="9" class="text-center">No se encontraron resultados</td>');
			$(".paginacion, .carga").hide();
			return;
		}

		const filas = response.content.map((item, index) => {
			let imagenesArray = []
			if (item.imagenes) {
				imagenesArray = item.imagenes.split(',');
			}

			return `
				<div class="bg-proyectos">
						<div class="row" style="display: flex; ${index%2==0?'flex-direction: row-reverse;':''}">
							<div class="col-md-6">
								<div class="owl-carousel10 owl-theme">
									
						${imagenesArray.map(imagen => `
                            <div class="item">
                                <img src="${BASE_URL}archivos/proyectoImagen/${imagen}" alt="${item.nombre}">
                            </div>
                        `).join('')}
								</div>
							</div>
							<div class="col-md-6">
								<div class="contenido">
									<h3>${item.nombre}</h3>
									${item.descripcion}
								</div>
							</div>
						</div>
					</div>`;
		}).join('');

		$("#container-proyectos").html(filas);
		$("#proyectos-mostrados").html(`${response.paginator.numberOfElements} de ${response.content.length} productos`);
		$(".carga").hide();
		$(".paginacion").show();


		setTimeout(() => {
			$('.owl-carousel10').owlCarousel({
				loop: true,
				margin: 10,
				nav: true,
				responsive: {
					0: {
						items: 1
					},
					600: {
						items: 1
					},
					1000: {
						items: 1
					}
				}
			});
		}, 100); // Pequeño retraso para asegurar que el DOM ya está actualizado




		const paginatorType = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ? [3, 2] : [5, 1];
		paginacion(response.paginator, ...paginatorType);
	}

	$("#registros").on("change", function(e) {
		$(".carga").show();
		e.preventDefault();
		let val = $(this).val();
		data = JSON.parse(localStorage.getItem('Casal-filtroProyectos'));
		data.registros = val;
		localStorage.setItem('Casal-filtroProyectos', JSON.stringify(data));

		showProyectos(1);
	})

	function buscarProyectos() {
		$(".carga").show();

		const data = {
			ordenCriterio: $("#ordenCriterio").val(),
			ordenTipo: "asc",
			parametro: "nombre",
			valor: $("#valor").val(),
			idPcategoria: $("#idPcategoria").val(),
			registros: $("#registros").val(),
			pagina: 1,
		};

		console.log("data")
		console.log(data)

		localStorage.setItem('Casal-filtroProyectos', JSON.stringify(data));

		showProyectos(1);
	}

	function refresh() {
		$(".carga").show();
		
		const data = {
			ordenCriterio: "fecha_desc",
			ordenTipo: "asc",
			parametro: "nombre",
			valor: "",
			idPcategoria: "",
			registros: 4,
			pagina: 1,
		};

		$("#valor").val();
		$("#ordenCriterio").val("fecha_desc");

		localStorage.setItem('Casal-filtroProyectos', JSON.stringify(data));

		showProyectos(1);
	}
</script>