<section class="bg_menu_page" style="background-image: url(<?= base_url(); ?>public/template/images/fondo-nosotros.jpg);background-size: cover;width: 100%;height: 200px;display: table;background-repeat: no-repeat;background-position: left bottom;">
	<div class="inner_subpage_banner">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="text-banner">
						<h1>Contáctenos</h1>
						<p><a href="<?= base_url(); ?>">Inicio</a> <span>|</span> Contáctenos</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="contactenos">
	<div class="container-fluid">
		<div class="row">

			<div class="col-md-12">
				<h2>Contáctenos</h2>

				<div class="d-flex">
					<div class="box-contacto">
						<h4>Información de contacto</h4>
						<ul>
							<li><a href="#" class="ubi">Los Rubíes 2134 – Lima 15434</a></li>
							<li><a href="#" class="wsp">+51 900 601 820</a></li>
							<li><a href="#" class="msj">gerencia@ironplast.com.pe</a></li>
						</ul>
						<h4>Redes sociales</h4>
						<ul class="redes-contacto">
							<li><a href="https://www.facebook.com/ironplast.com.pe" target="_blank"><i class="fa-brands fa-facebook-f"></i></a></li>
							<li><a href="https://www.instagram.com/ironplastperu/" target="_blank"><i class="fa-brands fa-instagram"></i></a></li>
							<li><a href="https://www.linkedin.com/company/ironplast-sac" target="_blank"><i class="fa-brands fa-linkedin-in"></i></a></li>
							<li><a href="https://www.youtube.com/@ironplastperu" target="_blank"><i class="fa-brands fa-youtube"></i></a></li>
							<li><a href="https://www.tiktok.com/@ironplast" target="_blank"><i class="fa-brands fa-tiktok"></i></a></li>
							<li><a href="https://www.pinterest.com/ironplastperu/" target="_blank"><i class="fa-brands fa-pinterest-p"></i></a></li>
						</ul>
					</div>

					<form class="form-contacto" id="formContacto">
						<h4>Escríbenos</h4>
						<div class="row">

							<div class="col-md-6">
								<div class="form-group">
									<input class="form-control" name="nombres" id="nombres" placeholder="Nombres y apellidos *" type="text">
									<span class="validacion nombres"></span>
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<input class="form-control" id="correo" name="correo" placeholder="Correo electrónico *" type="text">
									<span class="validacion correo"></span>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<input class="form-control" id="telefono" name="telefono" placeholder="Teléfono *" type="text">
									<span class="validacion telefono"></span>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<input class="form-control" id="asunto" name="asunto" placeholder="Asunto" type="text">
									<span class="validacion asunto"></span>
								</div>
							</div>

							<div class="col-md-12">
								<div class="form-group">
									<textarea class="form-control" name="mensaje" id="mensaje" placeholder="Mensaje" cols="30" rows="4"></textarea>
									<span class="validacion mensaje"></span>
								</div>
							</div>

							<div class="col-md-12">
								<button type="submit" class="enviar-servicios">Enviar <i class="fa fa-paper-plane"></i></button>
							</div>

						</div>
					</form>

				</div>

				<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3902.698023909381!2d-77.00823592269907!3d-11.995384788237422!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x9105c581e1a06413%3A0x225942538be8e764!2sC.%20los%20Rubies%202134%2C%20Lima%2015434!5e0!3m2!1ses-419!2spe!4v1754952147477!5m2!1ses-419!2spe" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

			</div>

		</div>
	</div>
</section>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
	$(document).ready(function() {
		document.querySelector(".captcha-imagen").src = `${BASE_URL}captcha?` + Date.now();
	});

	let refreshButton = document.querySelector(".refresh-captcha");
	refreshButton.onclick = function() {
		document.querySelector(".captcha-imagen").src = `${BASE_URL}captcha?` + Date.now();
	}
</script>
<script>
	$("#formContacto").on("submit", function(e) {
		e.preventDefault();
		$('html, body').animate({
			scrollTop: $("#formContacto").offset().top
		}, 2000);

		$(".carga").show();

		$.ajax({
			url: `${BASE_URL}FormularioController/mailContacto`,
			type: "post",
			data: new FormData(this),
			processData: false,
			contentType: false,
			dataType: "json",

		}).done(function(res) {
			removerClases();

			if (res.status == 'exito') {
				Swal.fire({
					title: 'Contáctenos!',
					text: 'Sus datos se han registrado exitosamente. Pronto nos pondremos en contacto con usted',
					icon: 'success',
					confirmButtonText: 'Aceptar'
				}).then((result) => {
					location.reload();
				})
			} else {
				showErrores(res.data);
			}
			$(".carga").hide();

		}).fail(function(err) {
			removerClases();
			$(".carga").hide();
			Swal.fire({
				title: 'Contáctenos!',
				text: 'Errores encontrados. Verifique y complete la información requerida',
				icon: 'warning',
				confirmButtonText: 'Continuar'
			}).then((result) => {
				location.reload();
			})
		});

	})


	function removerClases() {
		$(".validacion").css("display", "none");
		$(".validaclass").css("display", "none");
		$(".validaform").css("display", "none");
		$(".validacion").html("");
		$(".validaform").html("");
		$(".validaclass").html("");
		$("select").removeClass("is-invalid");
		$("textarea").removeClass("is-invalid");
		$("input").removeClass("is-invalid");
		$(".carga").css("display", "none");
	}

	function showErrores(errors) {
		$.each(errors, function(key, item) {
			$("#" + item.campo).addClass("is-invalid");
			$("." + item.campo).addClass("invalid-feedback");
			$("." + item.campo).css("display", "inline");
			$("." + item.campo).html(" (" + item.valor + ")");
		});
		$(".carga").hide();
	}
</script>