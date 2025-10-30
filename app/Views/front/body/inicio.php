<section class="slider-home">
	<div class="owl-carousel2 owl-theme">
		<!-- <div class="item">
			<a href="#">
				<video width="100%" height="auto" autoplay loop muted playsinline>
					<source src="<?= base_url(); ?>template/images/slider-pelucas.mp4" type="video/mp4">
					Tu navegador no soporta el elemento de video.
				</video>
			</a>
		</div> -->
		<? if ($sliders):
			foreach ($sliders as $slider): ?>
				<? if ($slider->idptiporecurso != 567): ?>
					<div class="item">
						<a href="<?= $slider->urlrecurso ?>">
							<img src="<?= base_url(); ?>archivos/slider/<?= $slider->urlimagen1 ?>" alt="">
						</a>
					</div>

				<? else: ?>
					<a href="<?= $slider->urlrecurso ?>">
						<video autoplay loop muted playsinline>
							<source src="<?= base_url(); ?>archivos/slider/<?= $slider->urlimagen1 ?>" type="video/mp4" />
						</video>
					</a>
				<? endif; ?>

		<? endforeach;
		endif; ?>
	</div>




	<div class="owl-carousel3 owl-theme">
		<!-- <div class="item">
			<a href="#">
				<img src="<?= base_url(); ?>template/images/slider-pelucas.jpg">
			</a>
		</div>
		<div class="item">
			<a href="#">
				<img src="<?= base_url(); ?>template/images/slider-pelucas.jpg">
			</a>
		</div> -->
		<? if ($sliders):
			foreach ($sliders as $slider): ?>
				<? if ($slider->idptiporecurso != 567): ?>
					<div class="item">
						<a href="<?= $slider->urlrecurso ?>">
							<img src="<?= base_url(); ?>archivos/slider/<?= $slider->urlimagen2 ?>" alt="">
						</a>
					</div>

				<? else: ?>
					<div class="item">
						<a href="<?= $slider->urlrecurso ?>">
							<video autoplay loop muted playsinline>
								<source src="<?= base_url(); ?>archivos/slider/<?= $slider->urlimagen2 ?>" type="video/mp4" />
							</video>
						</a>
					</div>
				<? endif; ?>

		<? endforeach;
		endif; ?>
	</div>

</section>


<? if ($bannerpelucas): ?>
	<section class="banner-pelucas">
		<div class="container">
			<div class="row">
				<?= $bannerpelucas->contenido ?>

			</div>
		</div>
	</section>
<? endif ?>

<? if ($productocategorias): ?>
	<section class="categorias-home">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<h2>Nuestras categorías</h2>

				</div>
				<div class="owl-carousel7 owl-theme">
					<? foreach ($productocategorias as $productocategoria): ?>
						<div class="item">
							<div class="cate-img">
								<a href="<?= base_url(); ?>productos/peluca/<?= $productocategoria->urlamigable ?>">
									<img src="<?= base_url(); ?>archivos/productocategoria/<?= $productocategoria->urlimagen ?>">
								</a>
								<h4><?= $productocategoria->nombre ?></h4>
								<a href="<?= base_url(); ?>productos/peluca/<?= $productocategoria->urlamigable ?>" class="comprar">Comprar</a>
							</div>
						</div>
					<? endforeach; ?>
				</div>

			</div>
		</div>
	</section>
<? endif ?>
<? if ($bannercolores): ?>
	<section class="pelu-unicas">
		<div class="container">
			<div class="row">
				<?= $bannercolores->contenido ?>
				<!-- <div class="col-md-12">
				<img src="<?= base_url(); ?>template/images/banner-pelucas-unicas.png">
			</div> -->
			</div>
		</div>
	</section>
<? endif ?>
<? if ($productosfavoritos): ?>
	<section class="fav-home">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<h2>Pelucas Favoritas</h2>
					<div class="owl-carousel8 owl-theme">
						<? foreach ($productosfavoritos as $productosfavorito): ?>
							<div class="item">
								<div class="bg-image">
									<?php if ($productosfavorito->preciolista > $productosfavorito->precioventa) : ?>
										<span>
											<?= getOferta($productosfavorito) ?>%
										</span>
									<?php endif; ?>
									<a href="<?= base_url(); ?>producto-detalle/<?= $productosfavorito->urlamigable ?>">
										<img src="<?= base_url(); ?>archivos/productoimagen/<?= $productosfavorito->urlimagen ?>" class="img1" alt="">
									</a>
									<? if ($productosfavorito->urlimagen2): ?>
										<a href="<?= base_url(); ?>producto-detalle/<?= $productosfavorito->urlamigable ?>">
											<img src=" <?= base_url(); ?>archivos/productoimagen/<?= $productosfavorito->urlimagen2 ?>" class="img2" alt="">
										</a>
									<?php endif; ?>
								</div>
								<div class="bg-resumen">
									<h3><?= $productosfavorito->nombre ?></h3>
									<div class="box-precio">
										<h5>S/ <?= $productosfavorito->precioventa ?></h5>
										<? if ($productosfavorito->preciolista > $productosfavorito->precioventa): ?>
											<h6>S/ <?= $productosfavorito->preciolista ?></h6>
										<? endif ?>
									</div>
									<div class="btns">
										<a href="<?= base_url(); ?>producto-detalle/<?= $productosfavorito->urlamigable ?>" class=" comprar">Comprar</a>
									</div>
								</div>
							</div>
						<? endforeach; ?>
					</div>

				</div>
			</div>
	</section>
<? endif ?>

<? if ($productoscombos): ?>
	<section class="combos-home">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<h2>Nuestros Combos</h2>
				</div>
				<? foreach ($productoscombos as $productoscombo): ?>
					<div class="col-md-3">
						<div class="bg-image">
							<?php if ($productoscombo->preciolista > $productoscombo->precioventa) : ?>
								<span>
									<?= getOferta($productoscombo) ?>%
								</span>
							<?php endif; ?>
							<a href="<?= base_url(); ?>producto-detalle/<?= $productoscombo->urlamigable ?>">
								<img src="<?= base_url(); ?>archivos/productoimagen/<?= $productoscombo->urlimagen ?>" class="img1" alt="">
							</a>
							<? if ($productoscombo->urlimagen2): ?>
								<a href="<?= base_url(); ?>producto-detalle/<?= $productoscombo->urlamigable ?>">
									<img src="<?= base_url(); ?>archivos/productoimagen/<?= $productoscombo->urlimagen2 ?>" class="img2" alt="">
								</a>
							<?php endif; ?>
						</div>
						<div class="bg-resumen">
							<h3>Combo para avanzados</h3>
							<div class="box-precio">
								<h5>S/ <?= $productoscombo->precioventa ?></h5>
								<? if ($productoscombo->preciolista > $productoscombo->precioventa): ?>
									<h6>S/ <?= $productoscombo->preciolista ?></h6>
								<? endif ?>
							</div>
							<div class="btns">
								<a href="<?= base_url(); ?>producto-detalle/<?= $productoscombo->urlamigable ?>" class="comprar">Comprar</a>
							</div>
						</div>
					</div>
				<? endforeach; ?>



			</div>
		</div>
	</section>
<? endif ?>



<? if ($comonidad): ?>
	<section class="comodidad-home">
		<div class="container">
			<?= $comonidad->contenido ?>
		</div>
	</section>
<? endif ?>


<? if ($noticiasdestacadas): ?>
	<section class="blog-home">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<h2>Blog</h2>
					<div class="owl-carousel10 owl-theme">

						<? foreach ($noticiasdestacadas as $noticiasdestacada): ?>
							<div class="item">
								<div class="box-img">
									<a href="<?= base_url(); ?>blog-detalle/<?= $noticiasdestacada->urlamigable ?>"><img src="<?= base_url(); ?>archivos/noticia/<?= $noticiasdestacada->urlimagen ?>" alt=""></a>
								</div>
								<div class="box-noti">
									<?php
									$meses = ['Jan' => 'Ene', 'Feb' => 'Feb', 'Mar' => 'Mar', 'Apr' => 'Abr', 'May' => 'May', 'Jun' => 'Jun', 'Jul' => 'Jul', 'Aug' => 'Ago', 'Sep' => 'Sep', 'Oct' => 'Oct', 'Nov' => 'Nov', 'Dec' => 'Dic'];
									$fecha = new DateTime($noticiasdestacada->fechapublicacion);
									?>
									<span class="fecha">
										<h1><?= $fecha->format('d') ?></h1>
										<h6><?= $meses[$fecha->format('M')] ?></h6>
									</span>


									<h3><?= $noticiasdestacada->nombre ?></h3>
									<a href="<?= base_url(); ?>blog-detalle/<?= $noticiasdestacada->urlamigable ?>">Ver más</a>
								</div>
							</div>
						<? endforeach; ?>

						<!-- <div class="item">
							<div class="box-img">
								<a href="#"><img src="<?= base_url(); ?>public/template/images/blog/blog2.jpg" alt=""></a>
							</div>
							<div class="box-noti">
								<span class="fecha">
									<h1>13</h1>
									<h6>Sep</h6>
								</span>
								<h3>Uso de pelucas</h3>
								<a href="#">Ver más</a>
							</div>
						</div>
						<div class="item">
							<div class="box-img">
								<a href="#"><img src="<?= base_url(); ?>public/template/images/blog/blog3.jpg" alt=""></a>
							</div>
							<div class="box-noti">
								<span class="fecha">
									<h1>13</h1>
									<h6>Sep</h6>
								</span>
								<h3>Uso de pelucas</h3>
								<a href="#">Ver más</a>
							</div>
						</div>
						<div class="item">
							<div class="box-img">
								<a href="#"><img src="<?= base_url(); ?>public/template/images/blog/blog4.jpg" alt=""></a>
							</div>
							<div class="box-noti">
								<span class="fecha">
									<h1>13</h1>
									<h6>Sep</h6>
								</span>
								<h3>Uso de pelucas</h3>
								<a href="#">Ver más</a>
							</div>
						</div> -->
					</div>

				</div>
			</div>
		</div>
	</section>
<? endif ?>

<?
function getNew($fechaPublicacion, $configuracionValor)
{
	// Verificamos si configuracionValor es numérico, de lo contrario usamos 1
	$mesesRestar = is_numeric($configuracionValor) ? (int)$configuracionValor : 1;

	// Fecha actual
	$fechaActual = new DateTime();

	// Crear fecha anterior restando "mesesRestar" meses a la fecha actual
	$fechaAnterior = clone $fechaActual;
	$fechaAnterior->modify("-{$mesesRestar} months");

	// Convertimos la fecha de publicación a objeto DateTime
	$fechaProducto = new DateTime($fechaPublicacion);

	// Comparamos si está dentro del rango
	return $fechaProducto >= $fechaAnterior && $fechaProducto <= $fechaActual;
}

function getOferta($producto)
{
	$descuento = 0;
	if ($producto->precioventa && $producto->preciolista && $producto->precioventa > 0 &&  $producto->preciolista > 0) {
		$descuento = ($producto->precioventa * 100) / $producto->preciolista;
		$descuento = 100 - $descuento;
		$descuento = intval($descuento);
	}

	return $descuento;
}
?>