var categorias, idlastCategoria;
$(function () {
    // Configuración del JqGrid
    $.jgrid.defaults.responsive = true;

    // Aplicamos las clases de Bootstrap en la tabla dinámica
    $.jgrid.styleUI.Bootstrap5.base.headerRowTable = "table table-bordered table-hover table-sm bg-warning";
    $.jgrid.styleUI.Bootstrap5.base.rowTable = "table table-bordered table-hover table-sm";

    categorias = $('#categorias').jqGrid({
        url: 'categorias/obtener_categorias',
        datatype: "json",
        styleUI: "Bootstrap5",
        iconSet: "fontAwesome",
        regional: "es",
        mtype: "POST",
        colModel: [ // Establece la estructura de la tabla dinamica
            { label: 'CATEGORÍA', name: 'categoria', index: 'categoria', width: 260 },
            { label: 'CANTIDAD DE LIBROS', name: 'total_libros', index: 'total_libros', width: 160, align: "center" }
        ],
        shrinkToFit: false,
        width: $('.workspace').width(),
        height: $(window).height() * 0.55,
        rowNum: 10, // Establece el número de filas o registros que se veran en la tabla
        rownumbers: true,
        rowNumWidth: 35,
        pager: '#navcategorias', // Indica el div de la barra de navegacion
        sortname: 'categoria', // Indica el nombre del campo por el que se ordenan los registros
        viewrecords: true,
        sortorder: "asc", // Indica el ordenamiento ascendente o descendente
        onSelectRow: function (rowid, status, e) {
            idlastCategoria = rowid;
        }
    });

    // Configuramos la barra de navegación del JqGrid
    categorias.navGrid('#navcategorias', { edit: false, add: false, del: false, view: true, search: false });

    const offcanvasCategoriaEl = document.getElementById('offcanvasCategoria');
    const offcanvasCategoria = new bootstrap.Offcanvas(offcanvasCategoriaEl);
    const formCategoria = document.getElementById('formCategoria');
    const formCategoriaError = document.getElementById('formCategoriaError');
    const offcanvasCategoriaTitulo = document.getElementById('offcanvasCategoriaTitulo');

    function recargarGrid() {
        categorias.trigger('reloadGrid');
    }

    function mostrarError(mensaje) {
        formCategoriaError.textContent = mensaje;
        formCategoriaError.classList.remove('d-none');
    }

    function limpiarFormulario() {
        formCategoria.reset();
        document.getElementById('id_categoria').value = '';
        formCategoriaError.classList.add('d-none');
    }

    document.getElementById('btnNuevaCategoria').addEventListener('click', function () {
        limpiarFormulario();
        offcanvasCategoriaTitulo.textContent = 'Nueva Categoría';
        offcanvasCategoria.show();
    });

    document.getElementById('btnEditarCategoria').addEventListener('click', function () {
        if (!idlastCategoria) {
            alert('Selecciona primero una categoría de la tabla');
            return;
        }

        const fila = categorias.jqGrid('getRowData', idlastCategoria);

        limpiarFormulario();
        document.getElementById('id_categoria').value = idlastCategoria;
        document.getElementById('categoria').value = fila.categoria || '';

        offcanvasCategoriaTitulo.textContent = 'Editar Categoría';
        offcanvasCategoria.show();
    });

    document.getElementById('btnEliminarCategoria').addEventListener('click', function () {
        if (!idlastCategoria) {
            alert('Selecciona primero una categoría de la tabla');
            return;
        }

        if (!confirm('¿Deseas eliminar esta categoría? Esta acción no se puede deshacer.')) {
            return;
        }

        const fd = new FormData();
        fd.append('id', idlastCategoria);

        fetch('categorias/eliminar', {
            method: 'POST',
            body: fd
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                idlastCategoria = null;
                recargarGrid();
            } else {
                alert(data.message || 'No se pudo eliminar la categoría');
            }
        })
        .catch(() => alert('Ocurrió un error al eliminar la categoría'));
    });

    formCategoria.addEventListener('submit', function (e) {
        e.preventDefault();

        formCategoriaError.classList.add('d-none');

        const id = document.getElementById('id_categoria').value;
        const url = id ? 'categorias/actualizar' : 'categorias/guardar';

        const fd = new FormData(formCategoria);

        fetch(url, {
            method: 'POST',
            body: fd
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                offcanvasCategoria.hide();
                recargarGrid();
            } else {
                mostrarError(data.message || 'Ocurrió un error al guardar la categoría');
            }
        })
        .catch(() => mostrarError('Ocurrió un error de conexión al guardar la categoría'));
    });
});