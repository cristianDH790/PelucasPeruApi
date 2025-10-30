<section class="bg_menu_page" style="background-image: url(<?= base_url(); ?>/public/template/images/fondo-nosotros.webp);background-size: cover;width: 100%;height: 200px;display: table;background-repeat: no-repeat;background-position: center center;">
    <div class="inner_subpage_banner">
        <div class="container">
            <div class="font_size_40 font_weight_900 color_fff line_height_110 oswald_font text-uppercase">
                Registro
            </div>
        </div>
    </div>
</section>

<section class="miga">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <p>
                    <a href="<?= base_url(); ?>">Inicio</a> <span>»</span> Contacto
                </p>
            </div>
        </div>
    </div>
</section>


<section class="contactenos">
    <div class="container">
        <div class="row">

            <div class="col-md-12 text-center">
                <h2>Registro</h2>
            </div>



            <!-- <div class="col-md-5">


                <div class="box-contacto2 bcs">

                </div>
            </div> -->
            <div class="col-md-12">
                <form class="form-contacto" id="formRegistro">
                    <h4>Escríbenos</h4>
                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Nombres *</label>
                                <input class="form-control" name="nombres" id="nombres" type="text">
                                <span class="validacion nombres"></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Apellido paterno *</label>
                                <input class="form-control" name="apellido-paterno" id="apellido-paterno" type="text">
                                <span class="validacion apellido-paterno"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Apellido materno *</label>
                                <input class="form-control" name="apellido-materno" id="apellido-materno" type="text">
                                <span class="validacion apellido-materno"></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Sexo *</label>
                                <select name="sexo" class="form-select" id="sexo">
                                    <option value="M">Masculino</option>
                                    <option value="F">Femenino</option>
                                </select>
                                <span class="validacion sexo"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Tipo de documento *</label>
                                <select name="pdocumento" class="form-select" id="pdocumento">
                                    <? if ($pdocumentos) : foreach ($pdocumentos as $key => $value) : ?>
                                            <option value="<?= $value->idparametro ?>"><?= $value->nombre ?></option>
                                    <? endforeach;
                                    endif; ?>
                                </select>
                                <span class="validacion pdocumento"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Documento *</label>
                                <input class="form-control" id="documento" name="documento" type="text">
                                <span class="validacion documento"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Correo electrónico *</label>
                                <input class="form-control" id="correo" name="correo" type="text">
                                <span class="validacion correo"></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Celular </label>
                                <input class="form-control" id="telefono" name="telefono" type="text">
                                <span class="validacion telefono"></span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="checkbox" style="padding: 0px;">
                                    <label style="font-size:14px;">
                                        <input type="checkbox" name="terminos" id="terminos" tabindex="11" style="height: auto;display: inline-block;width: auto;margin-top: 10px;margin-right: 10px;">
                                        Aceptar <a style="cursor:pointer; border-bottom: 1px solid black;" data-bs-toggle="modal" data-bs-target="#modalTerminos">
                                            términos y condiciones</a> *
                                    </label>
                                    <span class="validacion terminos"></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <img class="captcha-imagen" src="https://pelucasperu.com/captcha?1758657280020" alt="CAPTCHA">
                                <button type="button" id="refres" class="refresh-captcha">
                                    <i class="fa-solid fa-refresh"></i>
                                </button>
                                <input class="form-control" type="text" name="captcha" id="captcha" placeholder="Complete el captcha" pattern="[A-Za-z]{6}">
                                <span style="color:red;" class="validacion captcha"></span>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <button type="submit" class="enviar-servicios">Registrame <i class="fa fa-user-edit"></i>
                            </button>
                        </div>

                    </div>
                </form>
            </div>

        </div>
    </div>
</section>
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
    $("#formRegistro").on("submit", function(e) {
        e.preventDefault();
        $('html, body').animate({
            scrollTop: $("#formRegistro").offset().top
        }, 2000);

        $(".carga").show();

        $.ajax({
            url: `${BASE_URL}FormularioController/registro`,
            type: "post",
            data: new FormData(this),
            processData: false,
            contentType: false,
            dataType: "json",

        }).done(function(res) {
            // if (res.status == "error") {
            // 	return showErrores(res.errors);
            // }
            // removerClases();

            if (res.status == 'exito') {
                Swal.fire({
                    title: 'Registro!',
                    text: 'Sus datos se han registrado exitosamente. Pronto nos pondremos en contacto con usted',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                }).then((result) => {
                    location.reload();
                })
            } else {
                showErrores(res.errors);
            }
            $(".carga").hide();

        }).fail(function(err) {
            removerClases();
            $(".carga").hide();
            Swal.fire({
                title: 'Registro!',
                text: 'Errores encontrados. Verifique y complete la información requerida',
                icon: 'warning',
                confirmButtonText: 'Continuar'
            }).then((result) => {
                location.reload();
            })
        });

    })
</script>