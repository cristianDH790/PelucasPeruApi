<section class="bg_menu_page" style="background-image: url(<?= base_url(); ?>public/template/images/fondo-nosotros.jpg);background-size: cover;width: 100%;height: 200px;display: table;background-repeat: no-repeat;background-position: center center;">
    <div class="inner_subpage_banner">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="text-banner">
                        <h1>Registrarme</h1>

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
                <p><a href="<?= base_url(); ?>">Inicio</a> <span>></span> Registro</p>
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
            <div class="col-md-12">
                <form class="form-contacto" id="formRegistro">
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
                                    <option value="537">DNI</option>
                                    <option value="538">PASAPORTE</option>
                                    <option value="539">CE</option>
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
                                        Aceptar <a style="cursor:pointer; border-bottom: 1px solid black;" href="<?= base_url(); ?>terminos-y-condiciones" target="_blank">
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
                                    <svg class="svg-inline--fa fa-arrows-rotate" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="arrows-rotate" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
                                        <path fill="currentColor" d="M105.1 202.6c7.7-21.8 20.2-42.3 37.8-59.8c62.5-62.5 163.8-62.5 226.3 0L386.3 160 352 160c-17.7 0-32 14.3-32 32s14.3 32 32 32l111.5 0c0 0 0 0 0 0l.4 0c17.7 0 32-14.3 32-32l0-112c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 35.2L414.4 97.6c-87.5-87.5-229.3-87.5-316.8 0C73.2 122 55.6 150.7 44.8 181.4c-5.9 16.7 2.9 34.9 19.5 40.8s34.9-2.9 40.8-19.5zM39 289.3c-5 1.5-9.8 4.2-13.7 8.2c-4 4-6.7 8.8-8.1 14c-.3 1.2-.6 2.5-.8 3.8c-.3 1.7-.4 3.4-.4 5.1L16 432c0 17.7 14.3 32 32 32s32-14.3 32-32l0-35.1 17.6 17.5c0 0 0 0 0 0c87.5 87.4 229.3 87.4 316.7 0c24.4-24.4 42.1-53.1 52.9-83.8c5.9-16.7-2.9-34.9-19.5-40.8s-34.9 2.9-40.8 19.5c-7.7 21.8-20.2 42.3-37.8 59.8c-62.5 62.5-163.8 62.5-226.3 0l-.1-.1L125.6 352l34.4 0c17.7 0 32-14.3 32-32s-14.3-32-32-32L48.4 288c-1.6 0-3.2 .1-4.8 .3s-3.1 .5-4.6 1z"></path>
                                    </svg><!-- <i class="fa-solid fa-refresh"></i> Font Awesome fontawesome.com -->
                                </button>
                                <input class="form-control" type="text" name="captcha" id="captcha" placeholder="Complete el captcha" pattern="[A-Za-z]{6}">
                                <span style="color:red;" class="validacion captcha"></span>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <button type="submit" class="enviar-servicios">Registrarme <svg class="svg-inline--fa fa-user-pen" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="user-pen" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" data-fa-i2svg="">
                                    <path fill="currentColor" d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l293.1 0c-3.1-8.8-3.7-18.4-1.4-27.8l15-60.1c2.8-11.3 8.6-21.5 16.8-29.7l40.3-40.3c-32.1-31-75.7-50.1-123.9-50.1l-91.4 0zm435.5-68.3c-15.6-15.6-40.9-15.6-56.6 0l-29.4 29.4 71 71 29.4-29.4c15.6-15.6 15.6-40.9 0-56.6l-14.4-14.4zM375.9 417c-4.1 4.1-7 9.2-8.4 14.9l-15 60.1c-1.4 5.5 .2 11.2 4.2 15.2s9.7 5.6 15.2 4.2l60.1-15c5.6-1.4 10.8-4.3 14.9-8.4L576.1 358.7l-71-71L375.9 417z"></path>
                                </svg><!-- <i class="fa fa-user-edit"></i> Font Awesome fontawesome.com -->
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
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.getElementById("formRegistro");
        const loader = document.querySelector(".carga");

        if (form) {
            form.addEventListener("submit", function(e) {
                e.preventDefault();

                // Scroll suave al formulario
                form.scrollIntoView({
                    behavior: "smooth"
                });

                if (loader) loader.style.display = "block";

                fetch(`${BASE_URL}api/FormularioController/registro`, {
                        method: "POST",
                        body: new FormData(form),
                    })
                    .then(response => response.json())
                    .then(res => {
                        removerClases();

                        if (res.status === 'exito') {
                            Swal.fire({
                                title: 'Registro!',
                                text: 'Sus datos se han registrado exitosamente.',
                                icon: 'success',
                                confirmButtonText: 'Aceptar'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            showErrores(res.errors);
                        }

                        if (loader) loader.style.display = "none";
                    })
                    .catch(err => {
                        removerClases();

                        if (loader) loader.style.display = "none";

                        Swal.fire({
                            title: 'Contáctenos!',
                            text: 'Errores encontrados. Verifique y complete la información requerida',
                            icon: 'warning',
                            confirmButtonText: 'Continuar'
                        }).then(() => {
                            location.reload();
                        });
                    });
            });
        }
    });
</script>