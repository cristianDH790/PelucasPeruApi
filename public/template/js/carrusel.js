$('.owl-carousel3').owlCarousel({
	loop:true,
	margin:10,
	nav:true,
	autoplay:true,
	autoplayTimeout: 10000,
	dots:false,
	responsive:{
		0:{
			items:1
		},
		600:{
			items:1
		},
		1000:{
			items:1
		}
	}
})
$('.owl-carousel2').owlCarousel({
	loop:true,
	margin:30,
	nav:true,
	autoplay:true,
	autoplayTimeout: 10000,
	dots:false,
	responsive:{
		0:{
			items:1
		},
		600:{
			items:1
		},
		1000:{
			items:1
		}
	}
})
$('.owl-carousel4').owlCarousel({
	loop:true,
	margin:20,
	nav:false,
	autoplay:true,
	autoplayTimeout: 800,
	dots:false,
	responsive:{
		0:{
			items:3
		},
		600:{
			items:4
		},
		1000:{
			items:6
		}
	}
})
$('.owl-carousel5').owlCarousel({
	loop:true,
	margin:20,
	nav:true,
	autoplay:true,
	autoplayTimeout: 700,
	dots:false,
	responsive:{
		0:{
			items:2
		},
		600:{
			items:3
		},
		1000:{
			items:4
		},
		1400:{
			items:5
		}
	}
})
$('.owl-carousel6').owlCarousel({
	loop:true,
	margin:15,
	nav:false,
	autoplay:true,
	autoplayTimeout: 7000,
	dots:false,
	responsive:{
		0:{
			items:1
		},
		600:{
			items:3
		},
		1000:{
			items:5
		}
	}
})
$('.owl-carousel7').owlCarousel({
	loop:true,
	margin:10,
	nav:false,
	autoplay:true,
	autoplayTimeout: 10000,
	dots:true,
	responsive:{
		0:{
			items:2
		},
		600:{
			items:3
		},
		1000:{
			items:4
		}
	}
})
$('.owl-carousel8').owlCarousel({
	loop:true,
	margin:20,
	nav:false,
	autoplay:false,
	autoplayTimeout: 6000,
	dots:true,
	responsive:{
		0:{
			items:2
		},
		600:{
			items:3
		},
		1000:{
			items:4
		}
	}
})
$('.owl-carousel9').owlCarousel({
	loop:false,
	margin:10,
	nav:false,
	autoplay:false,
	autoplayTimeout: 7000,
	dots:true,
	responsive:{
		0:{
			items:2
		},
		600:{
			items:3
		},
		1000:{
			items:4
		}
	}
})

$('.owl-carousel10').owlCarousel({
	loop:false,
	margin:10,
	nav:false,
	autoplay:false,
	autoplayTimeout: 9000,
	dots:false,
	responsive:{
		0:{
			items:2
		},
		600:{
			items:3
		},
		1000:{
			items:4
		}
	}
})

	function toggleClassOnMobile() {
		const tabs = document.querySelectorAll('.tab-pane');
		const screenWidth = window.innerWidth;

		// Revisa si la pantalla tiene un ancho menor o igual a 768px (móvil)
		if (screenWidth <= 768) {
			// Agregar la clase 'show' solo en pantallas pequeñas
			tabs.forEach(tab => {
				tab.classList.add('show');
			});
		} else {
			// Eliminar la clase 'show' en pantallas más grandes
			tabs.forEach(tab => {
				tab.classList.remove('show');
			});
		}
	}

	// Llamar la función cuando la página se carga y también cada vez que la ventana se redimensiona
	window.addEventListener('load', toggleClassOnMobile);
	window.addEventListener('resize', toggleClassOnMobile);