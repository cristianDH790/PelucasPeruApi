<section class="bg_menu_page" style="background-image: url(<?= base_url(); ?>public/template/images/fondo-nosotros.jpg);background-size: cover;width: 100%;height: 200px;display: table;background-repeat: no-repeat;background-position: center center;">
	<div class="inner_subpage_banner">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="text-banner">
						<h1>Blog</h1>

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
				<p><a href="<?= base_url(); ?>">Inicio</a> <span>»</span> Blog</p>
			</div>
		</div>
	</div>
</section>

<section class="noticias-int">
	<div class="container-fluid">
		<div class="row">

			<div class="col-md-12">

				<h2>Blog</h2>

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
								<option value="1">dsa</option>
							</select>
						</div>
						<div class="form-group">
							<label>Buscar</label>
							<input type="text" id="valor" name="valor">
						</div>
						<div class="form-group">
							<label class="ocultar-label">&nbsp;</label>
							<button class="buscar" onclick="buscarNoticias()">Buscar</button>
							<button onclick="refresh()" class="refrescar"><svg class="svg-inline--fa fa-arrows-rotate" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="arrows-rotate" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
									<path fill="currentColor" d="M105.1 202.6c7.7-21.8 20.2-42.3 37.8-59.8c62.5-62.5 163.8-62.5 226.3 0L386.3 160 352 160c-17.7 0-32 14.3-32 32s14.3 32 32 32l111.5 0c0 0 0 0 0 0l.4 0c17.7 0 32-14.3 32-32l0-112c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 35.2L414.4 97.6c-87.5-87.5-229.3-87.5-316.8 0C73.2 122 55.6 150.7 44.8 181.4c-5.9 16.7 2.9 34.9 19.5 40.8s34.9-2.9 40.8-19.5zM39 289.3c-5 1.5-9.8 4.2-13.7 8.2c-4 4-6.7 8.8-8.1 14c-.3 1.2-.6 2.5-.8 3.8c-.3 1.7-.4 3.4-.4 5.1L16 432c0 17.7 14.3 32 32 32s32-14.3 32-32l0-35.1 17.6 17.5c0 0 0 0 0 0c87.5 87.4 229.3 87.4 316.7 0c24.4-24.4 42.1-53.1 52.9-83.8c5.9-16.7-2.9-34.9-19.5-40.8s-34.9 2.9-40.8 19.5c-7.7 21.8-20.2 42.3-37.8 59.8c-62.5 62.5-163.8 62.5-226.3 0l-.1-.1L125.6 352l34.4 0c17.7 0 32-14.3 32-32s-14.3-32-32-32L48.4 288c-1.6 0-3.2 .1-4.8 .3s-3.1 .5-4.6 1z"></path>
								</svg><!-- <i class="fa-solid fa-refresh"></i> Font Awesome fontawesome.com --></button>
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

						<div class="col-md-3">
							<div class="box-img">
								<a href="<?= base_url(); ?>blog-detalle"><img src="<?= base_url(); ?>public/template/images/blog/blog1.jpg" alt=""></a>
							</div>
							<div class="box-noti">
								<span class="fecha">
									<h1>13</h1>
									<h6>Sep</h6>
								</span>
								<h3>Uso de pelucas</h3>
								<a href="<?= base_url(); ?>blog-detalle">Ver más</a>
							</div>
						</div>
						<div class="col-md-3">
							<div class="box-img">
								<a href="<?= base_url(); ?>blog-detalle"><img src="<?= base_url(); ?>public/template/images/blog/blog2.jpg" alt=""></a>
							</div>
							<div class="box-noti">
								<span class="fecha">
									<h1>13</h1>
									<h6>Sep</h6>
								</span>
								<h3>Uso de pelucas</h3>
								<a href="<?= base_url(); ?>blog-detalle">Ver más</a>
							</div>
						</div>
						<div class="col-md-3">
							<div class="box-img">
								<a href="<?= base_url(); ?>blog-detalle"><img src="<?= base_url(); ?>public/template/images/blog/blog3.jpg" alt=""></a>
							</div>
							<div class="box-noti">
								<span class="fecha">
									<h1>13</h1>
									<h6>Sep</h6>
								</span>
								<h3>Uso de pelucas</h3>
								<a href="<?= base_url(); ?>blog-detalle">Ver más</a>
							</div>
						</div>
						<div class="col-md-3">
							<div class="box-img">
								<a href="<?= base_url(); ?>blog-detalle"><img src="<?= base_url(); ?>public/template/images/blog/blog4.jpg" alt=""></a>
							</div>
							<div class="box-noti">
								<span class="fecha">
									<h1>13</h1>
									<h6>Sep</h6>
								</span>
								<h3>Uso de pelucas</h3>
								<a href="<?= base_url(); ?>blog-detalle">Ver más</a>
							</div>
						</div>

						<div class="col-md-3">
							<div class="box-img">
								<a href="<?= base_url(); ?>blog-detalle"><img src="<?= base_url(); ?>public/template/images/blog/blog1.jpg" alt=""></a>
							</div>
							<div class="box-noti">
								<span class="fecha">
									<h1>13</h1>
									<h6>Sep</h6>
								</span>
								<h3>Uso de pelucas</h3>
								<a href="<?= base_url(); ?>blog-detalle">Ver más</a>
							</div>
						</div>
						<div class="col-md-3">
							<div class="box-img">
								<a href="<?= base_url(); ?>blog-detalle"><img src="<?= base_url(); ?>public/template/images/blog/blog2.jpg" alt=""></a>
							</div>
							<div class="box-noti">
								<span class="fecha">
									<h1>13</h1>
									<h6>Sep</h6>
								</span>
								<h3>Uso de pelucas</h3>
								<a href="<?= base_url(); ?>blog-detalle">Ver más</a>
							</div>
						</div>
						<div class="col-md-3">
							<div class="box-img">
								<a href="<?= base_url(); ?>blog-detalle"><img src="<?= base_url(); ?>public/template/images/blog/blog3.jpg" alt=""></a>
							</div>
							<div class="box-noti">
								<span class="fecha">
									<h1>13</h1>
									<h6>Sep</h6>
								</span>
								<h3>Uso de pelucas</h3>
								<a href="<?= base_url(); ?>blog-detalle">Ver más</a>
							</div>
						</div>
						<div class="col-md-3">
							<div class="box-img">
								<a href="<?= base_url(); ?>blog-detalle"><img src="<?= base_url(); ?>public/template/images/blog/blog4.jpg" alt=""></a>
							</div>
							<div class="box-noti">
								<span class="fecha">
									<h1>13</h1>
									<h6>Sep</h6>
								</span>
								<h3>Uso de pelucas</h3>
								<a href="<?= base_url(); ?>blog-detalle">Ver más</a>
							</div>
						</div>

					</div>
				</div>

				<div class="paginacion" style="display: block;">
					<ul>
						<li class="disabled page-item"><a class="page-link" href="1"><svg class="svg-inline--fa fa-backward-fast" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="backward-fast" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
									<path fill="currentColor" d="M493.6 445c-11.2 5.3-24.5 3.6-34.1-4.4L288 297.7 288 416c0 12.4-7.2 23.7-18.4 29s-24.5 3.6-34.1-4.4L64 297.7 64 416c0 17.7-14.3 32-32 32s-32-14.3-32-32L0 96C0 78.3 14.3 64 32 64s32 14.3 32 32l0 118.3L235.5 71.4c9.5-7.9 22.8-9.7 34.1-4.4S288 83.6 288 96l0 118.3L459.5 71.4c9.5-7.9 22.8-9.7 34.1-4.4S512 83.6 512 96l0 320c0 12.4-7.2 23.7-18.4 29z"></path>
								</svg><!-- <i class="fa fa-fast-backward"></i> Font Awesome fontawesome.com --></a></li>
						<li class=" page-item active"><span class="page-link">1</span></li>
						<li class="disabled page-item"><a class="page-link" href="1" style=""><svg class="svg-inline--fa fa-forward-fast" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="forward-fast" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
									<path fill="currentColor" d="M18.4 445c11.2 5.3 24.5 3.6 34.1-4.4L224 297.7 224 416c0 12.4 7.2 23.7 18.4 29s24.5 3.6 34.1-4.4L448 297.7 448 416c0 17.7 14.3 32 32 32s32-14.3 32-32l0-320c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 118.3L276.5 71.4c-9.5-7.9-22.8-9.7-34.1-4.4S224 83.6 224 96l0 118.3L52.5 71.4c-9.5-7.9-22.8-9.7-34.1-4.4S0 83.6 0 96L0 416c0 12.4 7.2 23.7 18.4 29z"></path>
								</svg><!-- <i class="fa fa-fast-forward"></i> Font Awesome fontawesome.com --></a></li>
					</ul>
				</div>

			</div>

		</div>
	</div>
</section>