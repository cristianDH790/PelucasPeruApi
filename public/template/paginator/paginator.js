function paginacion(paginator, paginasVisible, version) {

	var medio = Math.ceil(paginasVisible / 2);
	var hasta = paginator.totalPages < paginasVisible ? paginator.totalPages : ((paginator.number <= medio) ? paginasVisible : (((paginasVisible + paginator.number) - medio) > paginator.totalPages ? paginator.totalPages : ((paginasVisible + paginator.number) - medio)));
	var desde = paginator.totalPages < paginasVisible ? 1 : (((hasta + 1 - paginasVisible) == 0) ? 1 : (hasta + 1 - paginasVisible));
	var paginas = new Array(paginator.totalPages < paginasVisible ? paginator.totalPages : paginasVisible).fill(0).map((valor, indice) => indice + desde);

	var paginador = "";

	if (paginas.length > 0) {
		$(".paginacion").css('display', 'block');
		var paginador = "<ul>";
		paginador += '<li class="page-item disabled">' +
			'<span style="width:auto" class="page-link">' + ((version == 1) ? 'Mostrando ' : '') +
			paginator.numberOfElements + ' de ' + paginator.totalElements +
			'</span>' +
			'</li>';
		paginador += '<li class="' + ((paginator.number == 1) ? 'disabled page-item' : '') + '" >' +
			'<a class="page-link" href="1" ><i class="fa fa-fast-backward"></i></a>' +
			'</li>';
		if (paginator.number > 1) {
			paginador += '<li class="page-item">' +
				'<a class="page-link" href="' + parseInt(paginator.number - 1) + '"><i class="fa fa-backward"></i></a>' +
				'</li>';
		}

		$.each(paginas, function (key, pagina) {
			paginador += '<li class=" page-item ' + ((pagina == paginator.number) ? 'active' : '') + '" >';
			if (pagina == paginator.number) {
				paginador += '<span class="page-link" >' + pagina + '</span>';
			} else {
				paginador += ' <a class="page-link" href="' + pagina + '" ' +
					'>' + pagina + '</a>';

			}
		});



		if (paginator.number < paginator.totalPages) {
			paginador += '<li class="page-item" ">' +
				'<a class="page-link" href="' + parseInt(paginator.number + 1) + '" ><i class="fa fa-forward"></i></a>' +
				'</li>';
		}
		paginador += '<li class="' + ((paginator.number == paginator.totalPages) ? 'disabled page-item' : '') + '" >' +
			'<a class="page-link" href="' + paginator.totalPages + '" style="" ><i class="fa fa-fast-forward"></i></a>' +
			'</li>';

		paginador += "</ul>";



	}
	$(".paginacion").html(paginador);
}
