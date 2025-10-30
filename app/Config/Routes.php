<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');

$routes->get('/', 'Front::inicio');
$routes->get('peluca', 'Front::inicio');
$routes->get('lentes', 'Front::inicio');
// $routes->get('inicio', 'Front::inicio');
$routes->get('nosotros', 'Front::nosotros');
$routes->get('productos', 'Front::productos');
$routes->get('productos/(:any)', 'Front::productos/$1');
$routes->get('productos/(:any)/(:any)', 'Front::productos/$1/$2');

$routes->get('producto-detalle/(:any)', 'Front::productoDetalle/$1');

$routes->get('registro', 'Front::registrarme');
$routes->get('mi-cuenta', 'Front::miCuenta');
$routes->get('mi-cuenta-editar', 'Front::miCuentaEditar');
$routes->get('mis-pedidos', 'Front::misPedidos');
$routes->get('mis-pedidos-detalle/(:any)', 'Front::misPedidosDetalle/$1');
// $routes->get('proyectos', 'Front::proyectos');
$routes->get('blog', 'Front::blog');
$routes->get('blog-detalle/(:any)', 'Front::blogDetalle/$1');
$routes->get('contactenos', 'Front::contactenos');
$routes->get('carrito-de-compras', 'Front::carritoCompras');
$routes->get('lentes-de-contacto', 'Front::lentesContacto');
$routes->get('lentes-de-contacto-listado', 'Front::lentesContactoListado');
$routes->get('lentes-de-contacto-detalle', 'Front::lentesContactoDetalle');
$routes->get('carteras', 'Front::carteras');
$routes->get('cartera-detalle', 'Front::carteraDetalle');

$routes->get('captcha', 'Front::creaCaptcha');

$routes->group('', ['filter' => 'cors'], static function (RouteCollection $routes): void {


    $routes->post('izipay/ipn-prueba', 'Api\Publico\PedidoPublicoController::ipnIzipay');
    $routes->post('pedidos/getPedido', 'Api\Publico\PedidoPublicoController::getPedido');


    $routes->group('api', function ($routes) {
        $routes->post('login', 'Api\\Auth\\AuthController::login'); // Ruta pública
        $routes->get('pass', 'Api\\Auth\\AuthController::pass'); // Ruta pública

        //LLAMADAS AL CONTROLADOR PARA EL FRONTED
        $routes->post('ComentarioController/eliminarComentario', 'ComentarioController::eliminarComentario');
        $routes->post('ComentarioController/comentarios', 'ComentarioController::comentarios');
        $routes->post('ComentarioController/guardarComentario', 'ComentarioController::guardarComentario');
        $routes->post('ValoracionController/valorarPublicacion', 'ValoracionController::valorarPublicacion');
        $routes->post('FormularioController/suscripcion', 'FormularioController::suscripcion');
        $routes->post('FormularioController/mailContacto', 'FormularioController::mailContacto');
        $routes->post('FormularioController/mailLibroReclamaciones', 'FormularioController::mailLibroReclamaciones');
        $routes->post('FormularioController/registro', 'FormularioController::registro');
        $routes->post('FormularioController/envioCorreoVerificacion', 'FormularioController::envioCorreoVerificacion');

        $routes->post('PedidoController/getPedidos', 'PedidoController::getPedidos');

        $routes->post('ListaDeseoController/eliminarDeseo', 'ListaDeseoController::eliminarDeseo');
        $routes->post('ListaDeseoController/getListaDeseos', 'ListaDeseoController::getListaDeseos');
        $routes->post('ListaDeseoController/checkListaDeseo', 'ListaDeseoController::checkListaDeseo');
        $routes->post('UsuarioController/usuarioEditar', 'UsuarioController::usuarioEditar');


        $routes->post('Front/setSesionEditarDatos', 'Front::setSesionEditarDatos');
        $routes->post('Front/generaToken', 'Front::generaToken');

        $routes->post('SeguridadController/registrarUsuario', 'SeguridadController::registrarUsuario');
        $routes->post('SeguridadController/login', 'SeguridadController::login');
        $routes->post('SeguridadController/cerrarSesion', 'SeguridadController::cerrarSesion');

        $routes->post('CuponController/validarCupon', 'CuponController::validarCupon');
        $routes->post('FormaPagoController/formaPagoPorIdFormaPago', 'FormaPagoController::formaPagoPorIdFormaPago');
        $routes->post('EntregaController/entregaPorIdEntrega', 'EntregaController::entregaPorIdEntrega');

        $routes->post('UbigeoController/checkEntregaUbigeo', 'UbigeoController::checkEntregaUbigeo');

        $routes->post('CheckoutController/checkFormCarritoCompras', 'CheckoutController::checkFormCarritoCompras');

        $routes->post('publico/pedido/sesion', 'Api\Publico\PedidoPublicoController::sesionPedido');
        $routes->post('publico/pedido/guardar', 'Api\Publico\PedidoPublicoController::checkPedido');
        $routes->post('publico/pedido/guardar-izipay', 'Api\Publico\PedidoPublicoController::checkPedidoIzipay');


        //FIN DE LLAMADAS AL CONTROLADOR PARA EL FRONTED

        //LLAMADAS A RUTAS PUBLICAS
        $routes->post('publico/producto-color/listar', 'Api\Publico\ProductoColorPublicoController::productoColores');
        $routes->post('publico/producto/listar', 'Api\Publico\ProductoPublicoController::listar');
        $routes->post('publico/producto-imagen/listar', 'Api\Publico\ProductoImagenPublicoController::listar');
        $routes->post('publico/ubigeos/listar', 'Api\UbigeoController::listar');
        $routes->post('publico/noticias/listar', 'Api\Publico\NoticiaPublicoController::listar');




        //FIN LLAMADAS RUTAS PUBLICAS

        $routes->post('usuario/recuperarclave', 'Api\Auth\AuthController::recuperarClave');

        // $routes->get('usuario', 'Api\\Usuario::index', ['filter' => 'jwt']);
        $routes->get('authorities', 'Api\\Auth\\AuthController::permisos', ['filter' => 'jwtfilter']);
        //usuarios
        $routes->post('usuario/listar', 'Api\\UsuarioController::listar', ['filter' => 'jwtfilter']);
        $routes->get('usuario/obtenerPorId/(:num)', 'Api\\UsuarioController::obtenerPorId/$1', ['filter' => 'jwtfilter']);
        $routes->post('usuario/guardar', 'Api\UsuarioController::guardar', ['filter' => 'jwtfilter']);
        $routes->put('usuario/actualizar/(:num)', 'Api\\UsuarioController::actualizar/$1', ['filter' => 'jwtfilter']);
        $routes->delete('usuario/eliminar/(:num)', 'Api\\UsuarioController::eliminar/$1', ['filter' => 'jwtfilter']);
        $routes->post('usuario/reporte', 'Api\\UsuarioController::reporte', ['filter' => 'jwtfilter']);

        //cupon
        $routes->post('producto-cupon/listar', 'Api\\ProductoCuponController::listar', ['filter' => 'jwtfilter']);
        $routes->get('producto-cupon/obtenerPorId/(:num)', 'Api\\ProductoCuponController::obtenerPorId/$1', ['filter' => 'jwtfilter']);
        $routes->post('producto-cupon/guardar', 'Api\ProductoCuponController::guardar', ['filter' => 'jwtfilter']);
        $routes->put('producto-cupon/actualizar/(:num)', 'Api\\ProductoCuponController::actualizar/$1', ['filter' => 'jwtfilter']);
        $routes->delete('producto-cupon/eliminar/(:num)', 'Api\\ProductoCuponController::eliminar/$1', ['filter' => 'jwtfilter']);
        //$routes->post('producto-cupon/reporte', 'Api\\ProductoCuponController::reporte', ['filter' => 'jwtfilter']);

        //producto cupon asociacion
        $routes->delete('producto-cupon/eliminar-asociacion-cupon-producto', 'Api\\ProductoCuponController::eliminarAsociacion', ['filter' => 'jwtfilter']);
        $routes->delete('producto-cupon/eliminar-asociacion/(:num)', 'Api\\ProductoCuponController::eliminarCuponDeProducto/$1', ['filter' => 'jwtfilter']);
        $routes->post('producto-cupon/asociar', 'Api\\ProductoCuponController::asociarCupon', ['filter' => 'jwtfilter']);
        $routes->get('producto-cupon/listarPorIdProducto/(:num)', 'Api\\ProductoCuponController::listarCuponesAsociados/$1', ['filter' => 'jwtfilter']);
        //producto
        $routes->post('producto/listar', 'Api\\ProductoController::listar', ['filter' => 'jwtfilter']);
        $routes->get('producto/obtenerPorId/(:num)', 'Api\\ProductoController::obtenerPorId/$1', ['filter' => 'jwtfilter']);
        $routes->post('producto/guardar', 'Api\ProductoController::guardar', ['filter' => 'jwtfilter']);
        $routes->put('producto/actualizar/(:num)', 'Api\\ProductoController::actualizar/$1', ['filter' => 'jwtfilter']);
        $routes->delete('producto/eliminar/(:num)', 'Api\\ProductoController::eliminar/$1', ['filter' => 'jwtfilter']);
        $routes->post('producto/reporte', 'Api\\ProductoController::reporte', ['filter' => 'jwtfilter']);
        $routes->post('producto/actualizar/excel', 'Api\\ProductoController::productoActualizarExcel', ['filter' => 'jwtfilter']);


        $routes->post('producto/carga-masiva', 'Api\\ProductoController::productosCargaMasiva', ['filter' => 'jwtfilter']);
        $routes->post('producto/actualizacion-masiva', 'Api\\ProductoController::productosActualizacionMasiva', ['filter' => 'jwtfilter']);
        $routes->post('producto/actualizacion-imagen-masiva', 'Api\\ProductoImagenController::imagenesCargaMasiva', ['filter' => 'jwtfilter']);

        //producto complemento
        $routes->post('producto-complemento/asociar', 'Api\\ProductoComplementoController::asociarComplemento');
        $routes->delete('producto-complemento/eliminar/(:num)/(:num)', 'Api\\ProductoComplementoController::eliminarComplemento/$1/$2');
        $routes->get('producto-complemento/listar/(:num)', 'Api\\ProductoComplementoController::listarComplementos/$1');
        $routes->post('producto-complemento/editar', 'Api\\ProductoComplementoController::editarComplemento');



        //producto  imagen
        $routes->post('producto-imagen/listar', 'Api\\ProductoImagenController::listar', ['filter' => 'jwtfilter']);
        $routes->get('producto-imagen/obtenerPorId/(:num)', 'Api\\ProductoImagenController::obtenerPorId/$1', ['filter' => 'jwtfilter']);
        $routes->post('producto-imagen/guardar', 'Api\ProductoImagenController::guardar', ['filter' => 'jwtfilter']);
        $routes->put('producto-imagen/actualizar/(:num)', 'Api\\ProductoImagenController::actualizar/$1', ['filter' => 'jwtfilter']);
        $routes->delete('producto-imagen/eliminar/(:num)', 'Api\\ProductoImagenController::eliminar/$1', ['filter' => 'jwtfilter']);
        $routes->post('producto-imagen/upload', 'Api\\ProductoImagenController::uploadImagen1', ['filter' => 'jwtfilter']);
        $routes->post('producto-imagen/eliminar-imagen', 'Api\\ProductoImagenController::eliminarImagen', ['filter' => 'jwtfilter']);
        //tienda
        $routes->post('tiendas/listar', 'Api\\TiendaController::listar', ['filter' => 'jwtfilter']);
        $routes->get('tiendas/obtenerPorId/(:num)', 'Api\\TiendaController::obtenerPorId/$1', ['filter' => 'jwtfilter']);
        $routes->post('tiendas/guardar', 'Api\TiendaController::guardar', ['filter' => 'jwtfilter']);
        $routes->put('tiendas/actualizar/(:num)', 'Api\\TiendaController::actualizar/$1', ['filter' => 'jwtfilter']);
        $routes->delete('tiendas/eliminar/(:num)', 'Api\\TiendaController::eliminar/$1', ['filter' => 'jwtfilter']);
        $routes->post('tiendas/upload', 'Api\\TiendaController::uploadImagen1', ['filter' => 'jwtfilter']);
        $routes->post('tiendas/eliminar-imagen', 'Api\\TiendaController::eliminarImagen', ['filter' => 'jwtfilter']);

        //producto categorias
        $routes->post('productocategorias/listar', 'Api\\ProductoCategoriaController::listar', ['filter' => 'jwtfilter']);
        $routes->get('productocategorias/obtenerPorId/(:num)', 'Api\\ProductoCategoriaController::obtenerPorId/$1', ['filter' => 'jwtfilter']);
        $routes->post('productocategorias/guardar', 'Api\ProductoCategoriaController::guardar', ['filter' => 'jwtfilter']);
        $routes->put('productocategorias/actualizar/(:num)', 'Api\\ProductoCategoriaController::actualizar/$1', ['filter' => 'jwtfilter']);
        $routes->delete('productocategorias/eliminar/(:num)', 'Api\\ProductoCategoriaController::eliminar/$1', ['filter' => 'jwtfilter']);
        $routes->post('productocategorias/reporte', 'Api\\ProductoCategoriaController::reporte', ['filter' => 'jwtfilter']);
        $routes->post('productocategorias/upload', 'Api\\ProductoCategoriaController::uploadImagen1', ['filter' => 'jwtfilter']);
        $routes->post('productocategorias/upload2', 'Api\\ProductoCategoriaController::uploadImagen2', ['filter' => 'jwtfilter']);
        $routes->post('productocategorias/eliminar-imagen', 'Api\\ProductoCategoriaController::eliminarImagen', ['filter' => 'jwtfilter']);

        //comentario
        $routes->post('comentarios/listar', 'Api\\ComentarioController::listar', ['filter' => 'jwtfilter']);
        $routes->get('comentarios/obtenerPorId/(:num)', 'Api\\ComentarioController::obtenerPorId/$1', ['filter' => 'jwtfilter']);
        $routes->post('comentarios/guardar', 'Api\ComentarioController::guardar', ['filter' => 'jwtfilter']);
        $routes->put('comentarios/actualizar/(:num)', 'Api\\ComentarioController::actualizarEstado/$1', ['filter' => 'jwtfilter']);
        $routes->delete('comentarios/eliminar/(:num)', 'Api\\ComentarioController::eliminar/$1', ['filter' => 'jwtfilter']);
        //marcas
        $routes->post('marcas/listar', 'Api\\MarcaController::listar', ['filter' => 'jwtfilter']);
        $routes->get('marcas/obtenerPorId/(:num)', 'Api\\MarcaController::obtenerPorId/$1', ['filter' => 'jwtfilter']);
        $routes->post('marcas/guardar', 'Api\MarcaController::guardar', ['filter' => 'jwtfilter']);
        $routes->put('marcas/actualizar/(:num)', 'Api\\MarcaController::actualizar/$1', ['filter' => 'jwtfilter']);
        $routes->delete('marcas/eliminar/(:num)', 'Api\\MarcaController::eliminar/$1', ['filter' => 'jwtfilter']);
        $routes->post('marcas/reporte', 'Api\\MarcaController::reporte', ['filter' => 'jwtfilter']);
        $routes->post('marcas/upload', 'Api\\MarcaController::uploadImagen', ['filter' => 'jwtfilter']);
        $routes->post('marcas/eliminar-imagen', 'Api\\MarcaController::eliminarImagen', ['filter' => 'jwtfilter']);

        //contenidoWebs
        $routes->post('contenidoWebs/listar', 'Api\\ContenidoWebController::listar', ['filter' => 'jwtfilter']);
        $routes->get('contenidoWebs/obtenerPorId/(:num)', 'Api\\ContenidoWebController::obtenerPorId/$1', ['filter' => 'jwtfilter']);
        $routes->post('contenidoWebs/guardar', 'Api\ContenidoWebController::guardar', ['filter' => 'jwtfilter']);
        $routes->put('contenidoWebs/actualizar/(:num)', 'Api\\ContenidoWebController::actualizar/$1', ['filter' => 'jwtfilter']);
        $routes->delete('contenidoWebs/eliminar/(:num)', 'Api\\ContenidoWebController::eliminar/$1', ['filter' => 'jwtfilter']);
        $routes->post('contenidoWebs/upload', 'Api\\ContenidoWebController::uploadImagen', ['filter' => 'jwtfilter']);
        $routes->post('contenidoWebs/eliminar-imagen', 'Api\\ContenidoWebController::eliminarImagen', ['filter' => 'jwtfilter']);
        //categoriacontenidoWebs
        $routes->post('contenidoWebCategorias/listar', 'Api\\ContenidoWebCategoriaController::listar', ['filter' => 'jwtfilter']);
        $routes->get('contenidoWebCategorias/obtenerPorId/(:num)', 'Api\\ContenidoWebCategoriaController::obtenerPorId/$1', ['filter' => 'jwtfilter']);
        $routes->post('contenidoWebCategorias/guardar', 'Api\ContenidoWebCategoriaController::guardar', ['filter' => 'jwtfilter']);
        $routes->put('contenidoWebCategorias/actualizar/(:num)', 'Api\\ContenidoWebCategoriaController::actualizar/$1', ['filter' => 'jwtfilter']);
        $routes->delete('contenidoWebCategorias/eliminar/(:num)', 'Api\\ContenidoWebCategoriaController::eliminar/$1', ['filter' => 'jwtfilter']);
        //Noticia Catgoria
        $routes->post('noticiaCategoria/listar', 'Api\\NoticiaCategoriaController::listar', ['filter' => 'jwtfilter']);
        $routes->get('noticiaCategoria/obtenerPorId/(:num)', 'Api\\NoticiaCategoriaController::obtenerPorId/$1', ['filter' => 'jwtfilter']);
        $routes->post('noticiaCategoria/guardar', 'Api\NoticiaCategoriaController::guardar', ['filter' => 'jwtfilter']);
        $routes->put('noticiaCategoria/actualizar/(:num)', 'Api\\NoticiaCategoriaController::actualizar/$1', ['filter' => 'jwtfilter']);
        $routes->delete('noticiaCategoria/eliminar/(:num)', 'Api\\NoticiaCategoriaController::eliminar/$1', ['filter' => 'jwtfilter']);
        //Noticia 
        $routes->post('noticia/listar', 'Api\\NoticiaController::listar', ['filter' => 'jwtfilter']);
        $routes->get('noticia/obtenerPorId/(:num)', 'Api\\NoticiaController::obtenerPorId/$1', ['filter' => 'jwtfilter']);
        $routes->post('noticia/guardar', 'Api\NoticiaController::guardar', ['filter' => 'jwtfilter']);
        $routes->put('noticia/actualizar/(:num)', 'Api\\NoticiaController::actualizar/$1', ['filter' => 'jwtfilter']);
        $routes->delete('noticia/eliminar/(:num)', 'Api\\NoticiaController::eliminar/$1', ['filter' => 'jwtfilter']);
        $routes->post('noticia/upload', 'Api\\NoticiaController::uploadImagen', ['filter' => 'jwtfilter']);
        $routes->post('noticia/eliminar-imagen', 'Api\\NoticiaController::eliminarImagen', ['filter' => 'jwtfilter']);
        //entrega
        $routes->post('entregas/listar', 'Api\\EntregaController::listar', ['filter' => 'jwtfilter']);
        $routes->get('entregas/obtenerPorId/(:num)', 'Api\\EntregaController::obtenerPorId/$1', ['filter' => 'jwtfilter']);
        $routes->post('entregas/guardar', 'Api\EntregaController::guardar', ['filter' => 'jwtfilter']);
        $routes->put('entregas/actualizar/(:num)', 'Api\\EntregaController::actualizar/$1', ['filter' => 'jwtfilter']);
        $routes->delete('entregas/eliminar/(:num)', 'Api\\EntregaController::eliminar/$1', ['filter' => 'jwtfilter']);
        $routes->post('entregas/reporte', 'Api\\EntregaController::reporte', ['filter' => 'jwtfilter']);
        //formasdepago
        $routes->post('formapagos/listar', 'Api\\FormaPagoController::listar', ['filter' => 'jwtfilter']);
        $routes->get('formapagos/obtenerPorId/(:num)', 'Api\\FormaPagoController::obtenerPorId/$1', ['filter' => 'jwtfilter']);
        $routes->post('formapagos/guardar', 'Api\FormaPagoController::guardar', ['filter' => 'jwtfilter']);
        $routes->put('formapagos/actualizar/(:num)', 'Api\\FormaPagoController::actualizar/$1', ['filter' => 'jwtfilter']);
        $routes->delete('formapagos/eliminar/(:num)', 'Api\\FormaPagoController::eliminar/$1', ['filter' => 'jwtfilter']);
        $routes->post('formapagos/reporte', 'Api\\FormaPagoController::reporte', ['filter' => 'jwtfilter']);
        //ubigeo
        $routes->post('ubigeos/listar', 'Api\\UbigeoController::listar', ['filter' => 'jwtfilter']);
        $routes->get('ubigeos/obtenerPorId/(:num)', 'Api\\UbigeoController::obtenerPorId/$1', ['filter' => 'jwtfilter']);
        $routes->post('ubigeos/guardar', 'Api\UbigeoController::guardar', ['filter' => 'jwtfilter']);
        $routes->put('ubigeos/actualizar/(:num)', 'Api\\UbigeoController::actualizar/$1', ['filter' => 'jwtfilter']);
        $routes->delete('ubigeos/eliminar/(:num)', 'Api\\UbigeoController::eliminar/$1', ['filter' => 'jwtfilter']);
        $routes->post('ubigeos/disponibles', 'Api\\UbigeoController::ubigeosDisponibles', ['filter' => 'jwtfilter']);
        //entrega
        $routes->post('entregas/listar', 'Api\\EntregaController::listar', ['filter' => 'jwtfilter']);
        $routes->get('entregas/obtenerPorId/(:num)', 'Api\\EntregaController::obtenerPorId/$1', ['filter' => 'jwtfilter']);
        $routes->post('entregas/guardar', 'Api\EntregaController::guardar', ['filter' => 'jwtfilter']);
        $routes->put('entregas/actualizar/(:num)', 'Api\\EntregaController::actualizar/$1', ['filter' => 'jwtfilter']);
        $routes->delete('entregas/eliminar/(:num)', 'Api\\EntregaController::eliminar/$1', ['filter' => 'jwtfilter']);
        $routes->post('entregas/reporte', 'Api\\EntregaController::reporte', ['filter' => 'jwtfilter']);
        //zonareparto
        $routes->post('zona-reparto/listar', 'Api\\ZonaRepartoController::listar', ['filter' => 'jwtfilter']);
        $routes->get('zona-reparto/obtenerPorId/(:num)', 'Api\\ZonaRepartoController::obtenerPorId/$1', ['filter' => 'jwtfilter']);
        $routes->post('zona-reparto/guardar', 'Api\ZonaRepartoController::guardar', ['filter' => 'jwtfilter']);
        $routes->put('zona-reparto/actualizar/(:num)', 'Api\\ZonaRepartoController::actualizar/$1', ['filter' => 'jwtfilter']);
        $routes->delete('zona-reparto/eliminar/(:num)', 'Api\\ZonaRepartoController::eliminar/$1', ['filter' => 'jwtfilter']);

        //marcas
        $routes->post('marcas/listar', 'Api\\MarcaController::listar', ['filter' => 'jwtfilter']);
        $routes->get('marcas/obtenerPorId/(:num)', 'Api\\MarcaController::obtenerPorId/$1', ['filter' => 'jwtfilter']);
        $routes->post('marcas/guardar', 'Api\MarcaController::guardar', ['filter' => 'jwtfilter']);
        $routes->put('marcas/actualizar/(:num)', 'Api\\MarcaController::actualizar/$1', ['filter' => 'jwtfilter']);
        $routes->delete('marcas/eliminar/(:num)', 'Api\\MarcaController::eliminar/$1', ['filter' => 'jwtfilter']);
        $routes->post('marcas/reporte', 'Api\\MarcaController::reporte', ['filter' => 'jwtfilter']);
        $routes->post('marcas/upload', 'Api\\MarcaController::uploadImagen', ['filter' => 'jwtfilter']);
        $routes->post('marcas/eliminar-imagen', 'Api\\MarcaController::eliminarImagen', ['filter' => 'jwtfilter']);
        //publicidad
        $routes->post('publicidades/listar', 'Api\\PublicidadController::listar', ['filter' => 'jwtfilter']);
        $routes->get('publicidades/obtenerPorId/(:num)', 'Api\\PublicidadController::obtenerPorId/$1', ['filter' => 'jwtfilter']);
        $routes->post('publicidades/guardar', 'Api\PublicidadController::guardar', ['filter' => 'jwtfilter']);
        $routes->put('publicidades/actualizar/(:num)', 'Api\\PublicidadController::actualizar/$1', ['filter' => 'jwtfilter']);
        $routes->delete('publicidades/eliminar/(:num)', 'Api\\PublicidadController::eliminar/$1', ['filter' => 'jwtfilter']);
        $routes->post('publicidades/reporte', 'Api\\PublicidadController::reporte', ['filter' => 'jwtfilter']);
        $routes->post('publicidades/upload1', 'Api\\PublicidadController::uploadImagen', ['filter' => 'jwtfilter']);
        $routes->post('publicidades/eliminar-imagen', 'Api\\PublicidadController::eliminarImagen', ['filter' => 'jwtfilter']);

        //sliders 
        $routes->post('sliders/listar', 'Api\\SliderController::listar', ['filter' => 'jwtfilter']);
        $routes->get('sliders/obtenerPorId/(:num)', 'Api\\SliderController::obtenerPorId/$1', ['filter' => 'jwtfilter']);
        $routes->post('sliders/guardar', 'Api\SliderController::guardar', ['filter' => 'jwtfilter']);
        $routes->put('sliders/actualizar/(:num)', 'Api\\SliderController::actualizar/$1', ['filter' => 'jwtfilter']);
        $routes->delete('sliders/eliminar/(:num)', 'Api\\SliderController::eliminar/$1', ['filter' => 'jwtfilter']);
        $routes->post('sliders/reporte', 'Api\\SliderController::reporte', ['filter' => 'jwtfilter']);
        $routes->post('sliders/upload', 'Api\\SliderController::uploadImagen1', ['filter' => 'jwtfilter']);
        $routes->post('sliders/upload2', 'Api\\SliderController::uploadImagen2', ['filter' => 'jwtfilter']);
        $routes->post('sliders/eliminar-imagen', 'Api\\SliderController::eliminarImagen', ['filter' => 'jwtfilter']);
        //mensaje
        $routes->get('mensajes/obtenerPorId/(:num)', 'Api\Base\MensajeController::obtenerPorId/$1', ['filter' => 'jwtfilter']);
        $routes->post('mensajes/listar', 'Api\Base\MensajeController::listar', ['filter' => 'jwtfilter']);
        $routes->post('mensajes/guardar', 'Api\Base\MensajeController::guardar', ['filter' => 'jwtfilter']);
        $routes->put('mensajes/actualizar/(:num)', 'Api\Base\MensajeController::actualizar/$1', ['filter' => 'jwtfilter']);
        $routes->delete('mensajes/eliminar/(:num)', 'Api\Base\MensajeController::eliminar/$1', ['filter' => 'jwtfilter']);

        //menu
        $routes->post('menus/listar', 'Api\\MenuController::listar', ['filter' => 'jwtfilter']);
        $routes->get('menus/obtenerPorId/(:num)', 'Api\\MenuController::obtenerPorId/$1', ['filter' => 'jwtfilter']);
        $routes->post('menus/guardar', 'Api\MenuController::guardar', ['filter' => 'jwtfilter']);
        $routes->put('menus/actualizar/(:num)', 'Api\\MenuController::actualizar/$1', ['filter' => 'jwtfilter']);
        $routes->delete('menus/eliminar/(:num)', 'Api\\MenuController::eliminar/$1', ['filter' => 'jwtfilter']);


        //configuracion
        $routes->post('configuraciones/listar', 'Api\\Base\\ConfiguracionController::listar', ['filter' => 'jwtfilter']);
        $routes->get('configuraciones/obtenerPorId/(:num)', 'Api\\Base\\ConfiguracionController::obtenerPorId/$1', ['filter' => 'jwtfilter']);
        $routes->post('configuraciones/guardar', 'Api\\Base\\ConfiguracionController::guardar', ['filter' => 'jwtfilter']);
        $routes->put('configuraciones/actualizar/(:num)', 'Api\\Base\\ConfiguracionController::actualizar/$1', ['filter' => 'jwtfilter']);
        $routes->delete('configuraciones/eliminar/(:num)', 'Api\\Base\\ConfiguracionController::eliminar/$1', ['filter' => 'jwtfilter']);
        $routes->post('configuraciones/upload', 'Api\\Base\\ConfiguracionController::uploadImagen', ['filter' => 'jwtfilter']);
        $routes->post('configuraciones/eliminar-imagen', 'Api\\Base\\ConfiguracionController::eliminarImagen', ['filter' => 'jwtfilter']);

        //perfil
        $routes->post('perfiles/listar', 'Api\\PerfilController::listar', ['filter' => 'jwtfilter']);
        $routes->get('perfiles/obtenerPorId/(:num)', 'Api\\PerfilController::obtenerPorId/$1', ['filter' => 'jwtfilter']);
        $routes->post('perfiles/guardar', 'Api\\PerfilController::guardar', ['filter' => 'jwtfilter']);
        $routes->put('perfiles/actualizar/(:num)', 'Api\\PerfilController::actualizar/$1', ['filter' => 'jwtfilter']);
        $routes->delete('perfiles/eliminar/(:num)', 'Api\\PerfilController::eliminar/$1', ['filter' => 'jwtfilter']);
        //parametro
        $routes->post('parametro/listar', 'Api\\Base\\ParametroController::listar', ['filter' => 'jwtfilter']);
        $routes->get('parametro/obtenerPorId/(:num)', 'Api\\Base\\ParametroController::obtenerPorId/$1', ['filter' => 'jwtfilter']);
        $routes->post('parametro/guardar', 'Api\\Base\\ParametroController::guardar', ['filter' => 'jwtfilter']);
        $routes->put('parametro/actualizar/(:num)', 'Api\\Base\\ParametroController::actualizar/$1', ['filter' => 'jwtfilter']);
        $routes->delete('parametro/eliminar/(:num)', 'Api\\Base\\ParametroController::eliminar/$1', ['filter' => 'jwtfilter']);

        //estado
        $routes->post('estado/listar', 'Api\\Base\\EstadoController::listar', ['filter' => 'jwtfilter']);
        $routes->get('estado/obtenerPorId/(:num)', 'Api\\Base\\EstadoController::obtenerPorId/$1', ['filter' => 'jwtfilter']);
        $routes->post('estado/guardar', 'Api\\Base\\EstadoController::guardar', ['filter' => 'jwtfilter']);
        $routes->put('estado/actualizar/(:num)', 'Api\\Base\\EstadoController::actualizar/$1', ['filter' => 'jwtfilter']);
        $routes->delete('estado/eliminar/(:num)', 'Api\\Base\\EstadoController::eliminar/$1', ['filter' => 'jwtfilter']);

        //tipo
        $routes->post('tipo/listar', 'Api\\Base\\TipoController::listar', ['filter' => 'jwtfilter']);
        $routes->get('tipo/obtenerPorId/(:num)', 'Api\\Base\\TipoController::obtenerPorId/$1', ['filter' => 'jwtfilter']);
        $routes->post('tipo/guardar', 'Api\\Base\\TipoController::guardar', ['filter' => 'jwtfilter']);
        $routes->put('tipo/actualizar/(:num)', 'Api\\Base\\TipoController::actualizar/$1', ['filter' => 'jwtfilter']);
        $routes->delete('tipo/eliminar/(:num)', 'Api\\Base\\TipoController::eliminar/$1', ['filter' => 'jwtfilter']);

        //clase
        $routes->post('clases/listar', 'Api\\Base\\ClaseController::listar', ['filter' => 'jwtfilter']);
        $routes->get('clases/obtenerPorId/(:num)', 'Api\\Base\\ClaseController::obtenerPorId/$1', ['filter' => 'jwtfilter']);
        $routes->post('clases/guardar', 'Api\\Base\\ClaseController::guardar', ['filter' => 'jwtfilter']);
        $routes->put('clases/actualizar/(:num)', 'Api\\Base\\ClaseController::actualizar/$1', ['filter' => 'jwtfilter']);
        $routes->delete('clases/eliminar/(:num)', 'Api\\Base\\ClaseController::eliminar/$1', ['filter' => 'jwtfilter']);
        //suscripcion
        $routes->post('suscripcion/listar', 'Api\\SuscripcionController::listar', ['filter' => 'jwtfilter']);
        $routes->get('suscripcion/obtenerPorId/(:num)', 'Api\\SuscripcionController::obtenerPorId/$1', ['filter' => 'jwtfilter']);
        $routes->post('suscripcion/guardar', 'Api\SuscripcionController::guardar', ['filter' => 'jwtfilter']);
        $routes->put('suscripcion/actualizar/(:num)', 'Api\\SuscripcionController::actualizar/$1', ['filter' => 'jwtfilter']);
        $routes->delete('suscripcion/eliminar/(:num)', 'Api\\SuscripcionController::eliminar/$1', ['filter' => 'jwtfilter']);

        // RUTAS PARA COLOR
        $routes->get('color/obtenerPorId/(:num)', 'Api\ColorController::colorPorIdColor/$1', ['filter' => 'jwtfilter']);
        $routes->post('color/guardar', 'Api\ColorController::colorGuardar', ['filter' => 'jwtfilter']);
        $routes->put('color/actualizar/(:num)', 'Api\ColorController::colorActualizar/$1', ['filter' => 'jwtfilter']);
        $routes->delete('color/eliminar/(:num)', 'Api\ColorController::colorEliminar/$1', ['filter' => 'jwtfilter']);
        $routes->post('color/listar', 'Api\ColorController::colores', ['filter' => 'jwtfilter']);


        /// PRODUCTO COLOR
        $routes->get('producto-color/obtenerPorId/(:num)', 'Api\ProductoColorController::productoColorPorIdProductoColor/$1');
        $routes->post('producto-color/guardar', 'Api\ProductoColorController::productoColorGuardar');
        $routes->put('producto-color/actualizar/(:num)', 'Api\ProductoColorController::productoColorActualizar/$1');
        $routes->delete('producto-color/eliminar/(:num)', 'Api\ProductoColorController::productoColorEliminar/$1');
        $routes->post('producto-color/listar', 'Api\ProductoColorController::productoColores');

        /// DASHBOARD
        $routes->post('dashboard', 'Api\Base\DashboardController::dashboardStats', ['filter' => 'jwtfilter']);

        //pedidos
        $routes->get('pedido/{idPedido}', 'Api\PedidoController::obtenerPorId', ['filter' => 'jwtfilter']);
        $routes->post('pedido/guardar', 'Api\PedidoController::pedidoGuardar', ['filter' => 'jwtfilter']);
        $routes->put('pedido/guardar/{idPedido}', 'Api\PedidoController::pedidoActualizar', ['filter' => 'jwtfilter']);
        $routes->delete('pedido/eliminar/{idPedido}', 'Api\PedidoController::eliminar', ['filter' => 'jwtfilter']);
        $routes->post('pedidos', 'Api\PedidoController::listar', ['filter' => 'jwtfilter']);
        $routes->post('pedido/actualizar-estado', 'Api\PedidoController::cambiarEstado', ['filter' => 'jwtfilter']);
        $routes->post('pedido/cambiar-pago', 'Api\PedidoController::cambiarPago', ['filter' => 'jwtfilter']);
        $routes->post('pedido/reporte', 'Api\PedidoController::pedidoReporteExcel', ['filter' => 'jwtfilter']);
        $routes->post('pedido/enviar-correo', 'Api\PedidoController::enviarCorreo', ['filter' => 'jwtfilter']);
        $routes->post('pedido/enviar-correo-reporte', 'Api\PedidoController::enviarCorreoReporte', ['filter' => 'jwtfilter']);

        //filemanager
        $routes->get('carpetas', 'Api\FileManagerController::getCarpetasTodas', ['filter' => 'jwtfilter']);



        //  $routes->get('carpetas/listar', 'Api\FileManagerController::getCarpetas', ['filter' => 'jwtfilter']);
        // $routes->get('archivos', 'Api\FileManagerController::getArchivos', ['filter' => 'jwtfilter']);
        $routes->post('archivos', 'Api\FileManagerController::getArchivos', ['filter' => 'jwtfilter']);

        $routes->post('nuevo-directorio', 'Api\FileManagerController::nuevoDirectorio', ['filter' => 'jwtfilter']);
        $routes->post('archivoUpload', 'Api\FileManagerController::archivoSubirImagen', ['filter' => 'jwtfilter']);
        $routes->post('eliminarArchivoCarpeta', 'Api\FileManagerController::eliminarArchivoCarpeta', ['filter' => 'jwtfilter']);
        $routes->post('descargarArchivo', 'Api\FileManagerController::descargarArchivo', ['filter' => 'jwtfilter']);
        $routes->post('renombrar-archivo', 'Api\FileManagerController::renombrarArchivo', ['filter' => 'jwtfilter']);
        $routes->post('copiar-archivo', 'Api\FileManagerController::copiarArchivo', ['filter' => 'jwtfilter']);
    });
    $routes->options('api/(:any)', function () {
        return service('response')
            ->setHeader('Access-Control-Allow-Origin', '*')
            ->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE')
            ->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization,X-Authorization')
            ->setStatusCode(200);
    });
});
