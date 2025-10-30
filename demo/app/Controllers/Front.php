<?php

namespace App\Controllers;

use App\Entities\CarreraCorta;
use App\Entities\CarreraCortaCurricula;
use App\Entities\CarreraProfesional;
use App\Entities\CarreraProfesionalCurricula;
use App\Entities\Categoria;
use App\Entities\Coleccion;
use App\Entities\Configuracion;
use App\Entities\ContenidoWeb;
use App\Entities\CursoLibre;
use App\Entities\Evento;
use App\Entities\Parametro;
use App\Entities\Producto;
use App\Entities\ProductoImagen;
use App\Entities\ProductoTalla;
use App\Entities\Slider;
use App\Entities\Testimonio;
use App\Helpers\Paginator;


class Front extends BaseController
{

	public function inicio(){

		$data["seccion"] = "inicio";
		$data["titulo"] = "Pelucas Perú - Marca N° 1 de pelcuas del Perú";
		$data["url"] = "";
		$this->front_views('front/body/inicio',$data);
		
	}

	public function nosotros(){
		$data["seccion"] = "nosotros";

		$this->front_views("front/body/nosotros",$data);
		
	}

	public function politicasCambiosDevoluciones(){
		$data["seccion"] = "politicas";

		$this->front_views("front/body/politicasCambiosDevoluciones",$data);
		
	}

	public function productos(){
		$data["seccion"] = "productos";

		$this->front_views("front/body/productos",$data);
		
	}

	public function productoDetalle(){
		$data["seccion"] = "productos";

		$this->front_views("front/body/productoDetalle",$data);
		
	}

	public function lentesContacto(){
		$data["seccion"] = "prooductos";

		$this->front_views("front/body/lentesContacto",$data);
		
	}

	public function lentesContactoListado(){
		$data["seccion"] = "prooductos";

		$this->front_views("front/body/lentesContactoListado",$data);
		
	}
	
	public function lentesContactoDetalle(){
		$data["seccion"] = "productos";

		$this->front_views("front/body/lentesContactoDetalle",$data);
		
	}

	public function carteras(){
		$data["seccion"] = "prooductos";

		$this->front_views("front/body/carteras",$data);
		
	}

	public function carteraDetalle(){
		$data["seccion"] = "productos";

		$this->front_views("front/body/carteraDetalle",$data);
		
	}

	public function carritoCompras(){
		$data["seccion"] = "carrito";

		$this->front_views("front/body/carritoCompras",$data);
		
	}

	public function blog(){
		$data["seccion"] = "blog";

		$this->front_views("front/body/blog",$data);
		
	}

	public function blogDetalle(){
		$data["seccion"] = "blog";

		$this->front_views("front/body/blogDetalle",$data);
		
	}

	public function contactenos(){
		$data["seccion"] = "contactenos";

		$this->front_views("front/body/contactenos",$data);
		
	}
	
}
