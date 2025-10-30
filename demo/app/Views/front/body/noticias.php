<section class="bg_menu_page">
	<div class="inner_subpage_banner">
		<div class="container-fluid">
			<div class="text-banner">
				Noticias
			</div>
		</div>
	</div>
</section>

<section class="miga">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<p><a href="<?= base_url(); ?>">Inicio</a> <span>»</span> Noticias</p>
			</div>
		</div>
	</div>
</section>

<section class="noticias-int">
	<div class="container-fluid">
		<div class="row">

			<div class="col-md-12">

				<h2>Noticias</h2>

				<div class="box-filtros">
					<div class="d-flex">
						<div class="form-group">
							<label>Ordenar por</label>
							<select name="ordenCriterio" id="ordenCriterio">
								<option value="fecha_desc">Fecha: Más reciente</option>
								<option value="fecha_asc">Fecha: Más antiguo</option>
								<option value="titulo_asc">Nombre: A - Z</option>
								<option value="titulo_desc">Nombre: Z - A</option>
							</select>
						</div>
						<div class="form-group">
							<label>Categoría</label>
							<select name="idNoticiaCategoria" id="idNoticiaCategoria">
								<option value="0">Todos</option>
								<? if ($noticiaCategorias):
									foreach ($noticiaCategorias as $noticiaCategoria): ?>
										<option value="<?= $noticiaCategoria->idnoticiacategoria ?>"><?= $noticiaCategoria->nombre ?></option>
								<? endforeach;
								endif ?>
							</select>
						</div>
						<div class="form-group">
							<label>Buscar</label>
							<input type="text" id="valor" name="valor">
						</div>
						<div class="form-group">
							<label class="ocultar-label">&nbsp;</label>
							<button class="buscar" onclick="buscarNoticias()">Buscar</button>
							<button onclick="refresh()" class="refrescar"><i class="fa-solid fa-refresh"></i></button>
						</div>
					</div>
				</div>

				<div class="totalidad d-flex">
					<p>4 de 50 Noticias</p>
					<div class="mostrar-mas">
						<p>Mostrar</p>
						<select name="registros" id="registros">
							<option value="9">9</option>
							<option value="12">12</option>
							<option value="18">18</option>
						</select>
					</div>
				</div>

				<div class="box-noticias">
					<div class="row" id="container-noticias">
						<!-- ============== Contenedor de listado de noticias ============= -->
					</div>
				</div>

				<div class="paginacion" style="display: block;">
					<ul>
						<li class="disabled page-item"><a class="page-link" href="1">
								<i class="fa fa-fast-backward"></i></a></li>
						<li class=" page-item active"><span class="page-link">1</span></li>
						<li class="disabled page-item"><a class="page-link" href="1" style=""><i class="fa fa-fast-forward"></i></a></li>
					</ul>
				</div>

			</div>

		</div>
	</div>
</section>


<script>
	showNoticias(1)

	function showNoticias(pagina) {
		$(".carga").show();

		const filtros = JSON.parse(localStorage.getItem('Casal-filtroNoticias')) || {};

		if (JSON.stringify(filtros) != '{}') {
			$("#ordenCriterio").val(filtros.ordenCriterio)
			$("#ordenTipo").val(filtros.ordenTipo)
			$("#valor").val(filtros.valor)
			$("#idNoticiaCategoria").val(filtros.idNoticiaCategoria)
			$("#registros").val(filtros.registros)
		}

		const data = {
			ordenCriterio: filtros.ordenCriterio || $("#ordenCriterio").val(),
			ordenTipo: "asc",
			parametro: "nombre",
			valor: filtros.valor || $("#valor").val(),
			idNoticiaCategoria: filtros.idNoticiaCategoria || $("#idNoticiaCategoria").val(),
			registros: filtros.registros || $("#registros").val(),
			pagina: pagina,
		};

		localStorage.setItem('Casal-filtroNoticias', JSON.stringify(data));

		$.ajax({
				url: `${BASE_URL}NoticiaController/noticias`,
				type: "post",
				dataType: "json",
				data: data,

			}).done(noticiasResponse)
			.fail(function(response) {

				$("#container-noticias").html(' <div class="resultados"><p>No se encontraron resultados</p></div>');
				$(".carga").css("display", "none");
			});
	}

	function noticiasResponse(response) {
		if (!response.content || response.content.length === 0) {
			$("#container-noticias").html('<td colspan="9" class="text-center">No se encontraron resultados</td>');
			$(".paginacion, .carga").hide();
			return;
		}

		console.log("response")
		console.log(response)

		const filas = response.content.map(item => {
			return `
					<div class="col-md-4">
						<div class="box-img">
							<a href="${BASE_URL}noticia/${item.urlAmigable}"><img src="${BASE_URL}archivos/noticia/${item.urlImagen}" alt="${item.nombre}"></a>
							<a href="${BASE_URL}noticia/${item.urlAmigable}" class="${item.idNoticiaCategoria==1?'icon-play':''}">${item.idNoticiaCategoria==1?'<i class="fa-solid fa-play"></i>':''}</a>
						</div>
						<div class="box-noti">
							<span class="fecha">
							${formatearFecha(item.fechaPublicacion)}
							</span>
							<h3>${item.titulo}</h3>
							<p>${item.resumen}</p>
							<a href="${BASE_URL}noticia/${item.urlAmigable}">Ver más</a>
						</div>
					</div>`;
		}).join('');

		$("#container-noticias").html(filas);
		$("#noticias-mostrados").html(`${response.paginator.numberOfElements} de ${response.content.length} noticias`);
		$(".carga").hide();
		$(".paginacion").show();

		const paginatorType = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ? [3, 2] : [5, 1];
		paginacion(response.paginator, ...paginatorType);
	}

	$("#registros").on("change", function(e) {
		$(".carga").show();
		e.preventDefault();
		let val = $(this).val();
		data = JSON.parse(localStorage.getItem('Casal-filtroNoticias'));
		data.registros = val;
		localStorage.setItem('Casal-filtroNoticias', JSON.stringify(data));

		showNoticias(1);
	})

	function buscarNoticias() {
		$(".carga").show();

		const data = {
			ordenCriterio: $("#ordenCriterio").val(),
			ordenTipo: "asc",
			parametro: "nombre",
			valor: $("#valor").val(),
			idNoticiaCategoria: $("#idNoticiaCategoria").val(),
			registros: $("#registros").val(),
			pagina: 1,
		};

		localStorage.setItem('Casal-filtroNoticias', JSON.stringify(data));

		showNoticias(1);
	}

	function refresh() {
		$(".carga").show();

		const data = {
			ordenCriterio: "fecha_desc",
			ordenTipo: "asc",
			parametro: "nombre",
			valor: "",
			registros: 9,
			pagina: 1,
		};

		$("#valor").val();
		$("#ordenCriterio").val("fecha_desc");

		localStorage.setItem('Casal-filtroNoticias', JSON.stringify(data));

		showNoticias(1);
	}

	function refresh() {
		$(".carga").show();

		const data = {
			ordenCriterio: "fecha_desc",
			ordenTipo: "asc",
			parametro: "nombre",
			valor: "",
			idNoticiaCategoria: 0,
			registros: 9,
			pagina: 1,
		};

		$("#valor").val();
		$("#ordenCriterio").val("fecha_desc");

		localStorage.setItem('Casal-filtroNoticias', JSON.stringify(data));

		showNoticias(1);
	}

	function formatearFecha(fechaStr) {
		const fecha = new Date(fechaStr);
		const dia = fecha.getDate().toString().padStart(2, '0');

		const meses = ['Ene', 'Feb', 'Mar', 'aAbr', 'May', 'Jun',
			'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'
		];

		const mes = meses[fecha.getMonth()];

		return `
		<h1>${dia}</h1>
		<h6>${mes}</h6> `;
	}
</script>