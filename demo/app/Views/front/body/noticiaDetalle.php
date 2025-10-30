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
				<p>
					<a href="<?= base_url(); ?>">Inicio</a> <span>»</span>
					<a href="<?= base_url(); ?>/noticias">Noticias</a> <span>»</span>
					<?= $noticia->titulo ?>
				</p>
			</div>
		</div>
	</div>
</section>

<section class="noticia-detalle">
	<div class="container-fluid">
		<div class="row">

			<div class="col-md-9">

				<h6><?= $noticia->noticiacategoria ?></h6>
				<h2><?= $noticia->titulo ?></h2>
				<div class="bloque-compartir">
					<h5><i class="fa-solid fa-calendar-days"></i>
						<?=
						formatearFecha($noticia->fechapublicacion);
						?>
					</h5>
					<span>|</span>
					<div class="redes-productos">
						<h5>Compartir</h5>
						<ul>
							<li><a style="cursor:pointer;" href="" class="share-fb"><i class="fa-brands fa-facebook-f"></i></a></li>
							<li><a style="cursor:pointer;" href="" class="share-linkedin"><i class="fa-brands fa-linkedin-in"></i></a></li>
							<li><a style="cursor:'pointer';" href="" class="share-twitter"><i class="fa-brands fa-x-twitter"></i></a></li>
						</ul>
					</div>
				</div>

				<div class="contenido">
					<?= $noticia->contenido ?>
				</div>
			</div>

			<div class="col-md-3">
				<div class="box-noticias">
					<div class="row">
						<div class="col-md-12">
							<h4>Noticias relacionadas</h4>
						</div>
						<? if ($noticias_relacionadas):
							foreach ($noticias_relacionadas as $noticia_relacionada): ?>
								<div class="col-md-12">
									<div class="noti-img">
										<img src="<?= base_url('archivos/noticia/' . $noticia_relacionada->urlimagen) ?>" alt="<?= $noticia_relacionada->nombre ?>">
									</div>
									<div class="box-noti">
										<span class="fecha">

											<h1><?= date("d", strtotime($noticia_relacionada->fechapublicacion)) ?></h1>
											<h6><?= formatearFecha2($noticia_relacionada->fechapublicacion) ?></h6>
										</span>
										<h3><?= $noticia_relacionada->titulo ?></h3>
										<p><?= $noticia_relacionada->resumen ?></p>
										<a href="<?= base_url('noticia/' . $noticia_relacionada->urlamigable) ?>">Ver más</a>
									</div>
								</div>
						<? endforeach;
						endif ?>
					</div>
				</div>
			</div>

		</div>
	</div>
</section>

<script>
	$(function() {
		/*Define some constants */
		const ARTICLE_TITLE = document.title;
		const ARTICLE_URL = encodeURIComponent(window.location.href);
		const MAIN_IMAGE_URL = encodeURIComponent($('meta[property="og:image"]').attr('content'));

		$('.share-fb').click(function() {
			open_window('http://www.facebook.com/sharer/sharer.php?u=' + ARTICLE_URL, 'facebook_share');
		});

		$('.share-twitter').click(function() {
			open_window('http://twitter.com/share?url=' + ARTICLE_URL, 'twitter_share');
		});


		$('.share-linkedin').click(function() {
			open_window('https://www.linkedin.com/shareArticle?mini=true&url=' + ARTICLE_URL + '&title=' + ARTICLE_TITLE + '&summary=&source=', 'linkedin_share');
		});

		/*
			$('.share-pinterest').click(function(){
				open_window('https://pinterest.com/pin/create/button/?url='+ARTICLE_URL+'&media='+MAIN_IMAGE_URL+'&description='+ARTICLE_TITLE, 'pinterest_share');
			});
			
			$('.share-tumblr').click(function(){
				open_window('http://www.tumblr.com/share/link?url='+ARTICLE_URL+'&name='+ARTICLE_TITLE+'&description='+ARTICLE_TITLE, 'tumblr_share');
			});
		*/
		function open_window(url, name) {
			window.open(url, name, 'height=320, width=640, toolbar=no, menubar=no, scrollbars=yes, resizable=yes, location=no, directories=no, status=no');
		}
	});
</script>

<?
function formatearFecha($fecha)
{
	$meses = array("enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre");
	$fecha_formateada = date("d", strtotime($fecha)) . " de " . $meses[date('n', strtotime($fecha)) - 1] . " del " . date("Y", strtotime($fecha));

	return $fecha_formateada;
}

function formatearFecha2($fecha)
{
	$meses_cortos = array("Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic");
	$mes = $meses_cortos[date('n', strtotime($fecha)) - 1];

	return $mes;
}
?>