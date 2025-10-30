<section class="bg_menu_page imagenbanner">
	<div class="inner_subpage_banner">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="text-banner">
						<h1>Mis pedidos</h1>
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
				<p><a href="<?= base_url(); ?>">Inicio</a> <span>></span> Mis pedidos</p>
			</div>
		</div>
	</div>
</section>


<section class="bloque-cuenta" id="main-container-fluid col2-right-layout">
	<div class="main container-fluid">
		<div class="row">

			<aside class="mostrar-movil sidebar col-md-3 col-sm-12 col-xs-12">
				<div class="sidebar-checkout block">

					<div class="block-content">
						<dl>
							<dt>
								<div class="sidebar-bar-title">
									<h3>Mi cuenta</h3>
								</div>
							</dt>
							<dt class="complete">
								<h4 class="nombreUsu"><?= $usuario->nombres . ' ' . $usuario->papellido . ' ' . $usuario->sapellido ?: "" ?></h4>
							</dt>
							<dt class="complete"><a href="<?= base_url('mi-cuenta'); ?>" class="cuenta">Mi Cuenta</a></dt>


							<dt class="complete"><a href="<?= base_url('mis-pedidos'); ?>" class="activo cuenta-pedido">Mis pedidos</a></dt>
							<dt class="complete"><a class="cuenta-sesion" style="cursor: pointer;" onclick="cerrarSesion()">Cerrar sesión</a></dt>
						</dl>
					</div>

				</div>
			</aside>

			<div class="col-main col-md-9 col-sm-12 col-xs-12">
				<div class="mis-pedidos">
					<h5>Mis pedidos</h5>
					<div class="cuadro-mis-pedidos">
						<form id="form-mis-pedidos" name="form-mis-pedidos" class="formSus">
							<div class="row">
								<div class="col-md-3 col-sm-12">
									<label>Estados</label>
									<select name="idEstado" id="idEstado">
										<option value="0">Todos</option>
										<? if ($estados):
											foreach ($estados as $estado): ?>
												<option value="<?= $estado->idestado ?>"><?= $estado->nombre ?></option>
										<? endforeach;
										endif ?>
									</select>
								</div>
								<div class="col-md-3 col-sm-12">
									<label>Pago</label>
									<select name="idpPago" id="idpPago">
										<option value="0">Todos</option>
										<? if ($ppagos):
											foreach ($ppagos as $ppago): ?>
												<option value="<?= $ppago->idparametro ?>"><?= $ppago->nombre ?></option>
										<? endforeach;
										endif ?>
									</select>
								</div>
								<div class="col-md-3 col-sm-12">
									<label>Número de pedido</label>
									<input type="text" name="valor" id="valor">
								</div>
								<div class="col-md-2 col-sm-12">
									<button class="btn-buscar" type="submit" style="cursor: pointer;">Buscar</button>
								</div>
								<!-- <div class="col-md-1 col-sm-12">
                                <a style="cursor: pointer;" onclick="limpiar();"><i class="fas fa-refresh"></i></a>
                            </div> -->
							</div>
						</form>
					</div>
				</div>

				<div class="pedidos-detallado">
					<h5>Pedidos</h5>
					<div id="container-mis-pedidos">


					</div>
				</div>
			</div>
		</div>
	</div>
</section>


<script>
	$(document).ready(function() {
		showMisPedidos(0);

		$("#form-mis-pedidos").on("submit", function(e) {
			e.preventDefault();
			$(".carga").show();
			$('html, body').animate({
				scrollTop: 0
			}, 'slow');

			const filtros = {
				ordenCriterio: "fecha",
				ordenTipo: "desc",
				idEstado: $("#idEstado").val(),
				idpPago: $("#idpPago").val(),
				valor: $("#valor").val(),
				parametro: "referencia",
				registros: 0,
				pagina: 0,
			};

			localStorage.setItem('Joel-filtroMisPedidos', JSON.stringify(filtros));
			showMisPedidos(1);
		});
	});

	function showMisPedidos(pagina) {
		$(".carga").show();

		const filtros = JSON.parse(localStorage.getItem('Joel-filtroMisPedidos')) || {};

		const data = {
			ordenCriterio: filtros.ordenCriterio || "fecha",
			ordenTipo: filtros.ordenTipo || "desc",
			idEstado: filtros.idEstado || $("#idEstado").val(),
			idpPago: filtros.idpPago || $("#idpPago").val(),
			valor: filtros.valor || $("#valor").val(),
			parametro: filtros.parametro || "referencia",
			registros: 0,
			pagina: pagina,
		};

		if (Object.keys(filtros).length > 0) {
			$("#ordenCriterio").val(filtros.ordenCriterio);
			$("#ordenTipo").val(filtros.ordenTipo);
			$('#idEstado').val(filtros.idEstado);
			$('#idpPago').val(filtros.idpPago);
			$("#valor").val(filtros.valor);
			$("#parametro").val(filtros.parametro);
		}

		localStorage.setItem('Joel-filtroMisPedidos', JSON.stringify(data));

		$.ajax({
				url: `${BASE_URL}api/PedidoController/getPedidos`,
				type: "POST",
				dataType: "json",
				data: data,
			})
			.done(misPedidosResponse)
			.fail(function() {
				$("#container-mis-pedidos").html('<div class="resultados"><p>No se encontraron resultados</p></div>');
				$(".carga").hide();
			});
	}

	function misPedidosResponse(response) {
		console.log(response);
		if (!response.lista || response.lista.length === 0) {
			$("#container-mis-pedidos").html('<td colspan="9" class="text-center">No se encontraron resultados</td>');
			$(".paginacion, .carga").hide();
			return;
		}

		const opcionesFecha = {
			year: 'numeric',
			month: '2-digit',
			day: '2-digit',
			hour: '2-digit',
			minute: '2-digit',
			second: '2-digit',
			hour12: false,
		};

		const filas = response.lista.map(item => {
			console.log(item);
			const fecha = new Date(item.fecha).toLocaleString('es-PE', opcionesFecha);

			const entregas = {
				584: 'DELIVERY',
				585: 'RECOJO',
				586: 'ENVIO'
			};
			const pagos = {
				452: 'norealizado',
				453: 'realizado',
				454: 'reportado'
			};

			const estados = {
				424: 'aceptado',
				425: 'atendido',
				426: 'transito',
				427: 'entregado',
				428: 'anulado',
				429: 'reservado'
			};

			const pagoClass = pagos[item.idppago] || '';
			const estadoClass = estados[item.idestado] || '';
			// const estadoClass = estados[item.idestado] || '';

			return `
            <div class="row cuadro-pedidos-oscuro">
                <div class="col-md-10 col-sm-12">
                    <p><strong>P${item.idpedido} - ${item.referencia}</strong></p>
                </div>
                <div class="col-md-2 col-sm-12">
                    <a class="bloqueado" href="${BASE_URL}mis-pedidos-detalle/${item.idpedido}" onclick="cargar();" data-toggle="tooltip">
                        <svg class="svg-inline--fa fa-magnifying-glass" ...></svg>
                    </a>
                </div>
                <div class="col-md-4 col-sm-12">
                    <ul><li>Pedido: ${fecha}</li></ul>
                </div>
                <div class="col-md-4 col-sm-12">
                    <ul>
                        <li>S/ ${item.total}</li>
                        <li>${item.formapago.nombre}</li>
                        <li>${item.usuario.nombres} ${item.usuario.papellido} ${item.usuario.sapellido || ''}</li>
                    </ul>
                </div>
                <div class="col-md-4 col-sm-12">
                    <ul>
                        <li>${item.entrega.nombre}</li>
                        <li>Pago: <span class="${pagoClass}">${item.ppago.nombre}</span></li>
                        <li>Estado: <span class="${estadoClass}">${item.estado.nombre}</span></li>
                    </ul>
                </div>
            </div>`;
		}).join('');

		$("#container-mis-pedidos").html(filas);
		$(".carga").hide();
		$(".paginacion").show();
	}
</script>