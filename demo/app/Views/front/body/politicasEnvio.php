<section class="bg_menu_page" style="background-image: url(<?= base_url(); ?>/public/template/images/fondo-nosotros.webp);background-size: cover;width: 100%;height: 200px;display: table;background-repeat: no-repeat;background-position: center center;">
	<div class="inner_subpage_banner">
		<div class="container-fluid">
			<div class="text-banner">
				Políticas de envío
			</div>
		</div>
	</div>
</section>

<section class="miga">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<p><a href="<?= base_url(); ?>">Inicio</a> <span>»</span> Políticas de envío</p>
			</div>
		</div>
	</div>
</section>

<section class="politicas-int">
	<div class="container-fluid">
		<div class="row">

			<div class="col-md-12">

				
				<? if ($politicasEnvio): ?>
					<?= $politicasEnvio->contenido ?>
				<? endif ?>

			</div>

		</div>
	</div>
</section>