var inventario, idlast;
$(function () {
    // Configuración del JqGrid
    // Configuramos la tabla dinámica para que sea responsive
    $.jgrid.defaults.responsive = true;

    // Aplicamos las clases de Bootstrap en la tabla dinámica
    $.jgrid.styleUI.Bootstrap5.base.headerRowTable = "table table-bordered table-hover table-sm bg-warning";
    $.jgrid.styleUI.Bootstrap5.base.rowTable = "table table-bordered table-hover table-sm";

    inventario = $('#inventario').jqGrid({
        url: 'inventario/obtener_libros',
        datatype: "json",
        styleUI: "Bootstrap5",
        iconSet: "fontAwesome",
        regional: "es",
        mtype: "POST",
        colModel: [ // Establece la estructura de la tabla dinamica
            { label: 'ISBN', name: 'isbn', index: 'isbn', width: 120 },
            { label: 'TÍTULO', name: 'titulo', index: 'titulo', width: 260 },
            { name: 'id_categoria', index: 'id_categoria', hidden: true }, // oculto, solo se usa para editar
            { label: 'CATEGORÍA', name: 'categoria', index: 'categoria', width: 140 },
            { label: 'AUTOR', name: 'autor', index: 'autor', width: 160 },
            { label: 'EDITORIAL', name: 'editorial', index: 'editorial', width: 180 },
            { label: 'PÁGINAS', name: 'cantidad_paginas', index: 'cantidad_paginas', width: 90, align: "center" },
            { label: 'TOMO', name: 'tomo', index: 'tomo', width: 90 },
            { 
                label: 'DISPONIBLE', 
                name: 'disponible', 
                index: 'disponible', 
                width: 100, 
                align: "center",
                formatter: function (cellvalue) {
                    if (cellvalue === 'Sí') {
                        return '<span class="badge badge-disponible">Sí</span>';
                    } else {
                        return '<span class="badge bg-secondary">No</span>';
                    }
                }
            }
        ],
        shrinkToFit: false,
        width: $('.workspace').width(),
        height: $(window).height() * 0.55,
        rowNum: 10, // Establece el número de filas o registros que se veran en la tabla
        rownumbers: true,
        rowNumWidth: 35,
        pager: '#navinventario', // Indica el div de la barra de navegacion
        sortname: 'titulo', // Indica el nombre del campo por el que se ordenan los registros
        viewrecords: true,
        sortorder: "asc", // Indica el ordenamiento ascendente o descendente
        onSelectRow: function (rowid, status, e) {
            idlast = rowid;
        }
    });

    // Configuramos la barra de navegación del JqGrid
    inventario.navGrid('#navinventario', { edit: false, add: false, del: false, view: true, search: false });
    const offcanvasLibroEl = document.getElementById('offcanvasLibro');
    const offcanvasLibro = new bootstrap.Offcanvas(offcanvasLibroEl);
    const formLibro = document.getElementById('formLibro');
    const formLibroError = document.getElementById('formLibroError');
    const offcanvasLibroTitulo = document.getElementById('offcanvasLibroTitulo');

    function recargarGrid() {
        inventario.trigger('reloadGrid');
    }

    function mostrarError(mensaje) {
        formLibroError.textContent = mensaje;
        formLibroError.classList.remove('d-none');
    }

    function limpiarFormulario() {
        formLibro.reset();
        document.getElementById('id_libro').value = '';
        formLibroError.classList.add('d-none');
        document.getElementById('disponible').checked = true;
    }

    function cargarSelect(url, selectId, valueField, textField) {
        fetch(url)
            .then(response => response.json())
            .then(data => {
                const select = document.getElementById(selectId);
                (data.registros || []).forEach(item => {
                    const option = document.createElement('option');
                    option.value = item[valueField];
                    option.textContent = item[textField];
                    select.appendChild(option);
                });
            })
            .catch(() => alert('No se pudo cargar la lista de opciones (' + selectId + ')'));
    }

    cargarSelect('inventario/categorias', 'id_categoria', 'id_categoria', 'categoria');

    fetch('inventario/autores')
        .then(response => response.json())
        .then(data => {
            const lista = document.getElementById('listaAutores');
            (data.registros || []).forEach(item => {
                const option = document.createElement('option');
                option.value = item.nombre;
                lista.appendChild(option);
            });
        })
        .catch(() => {});

    document.getElementById('btnNuevoLibro').addEventListener('click', function () {
        limpiarFormulario();
        offcanvasLibroTitulo.textContent = 'Nuevo Libro';
        offcanvasLibro.show();
    });

    document.getElementById('btnEditarLibro').addEventListener('click', function () {
        if (!idlast) {
            alert('Selecciona primero un libro de la tabla');
            return;
        }

        const fila = inventario.jqGrid('getRowData', idlast);

        limpiarFormulario();
        document.getElementById('id_libro').value = idlast;
        document.getElementById('isbn').value = fila.isbn || '';
        document.getElementById('titulo').value = fila.titulo || '';
        document.getElementById('id_categoria').value = fila.id_categoria || '';
        document.getElementById('autor').value = fila.autor || '';
        document.getElementById('editorial').value = fila.editorial || '';
        document.getElementById('cantidad_paginas').value = fila.cantidad_paginas || '';
        document.getElementById('tomo').value = fila.tomo || '';
        document.getElementById('disponible').checked = fila.disponible.includes('>Sí<');

        offcanvasLibroTitulo.textContent = 'Editar Libro';
        offcanvasLibro.show();
    });

    document.getElementById('btnEliminarLibro').addEventListener('click', function () {
        if (!idlast) {
            alert('Selecciona primero un libro de la tabla');
            return;
        }

        if (!confirm('¿Deseas eliminar este libro? Esta acción no se puede deshacer.')) {
            return;
        }

        const fd = new FormData();
        fd.append('id', idlast);

        fetch('inventario/eliminar', {
            method: 'POST',
            body: fd
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                idlast = null;
                recargarGrid();
            } else {
                alert(data.message || 'No se pudo eliminar el libro');
            }
        })
        .catch(() => alert('Ocurrió un error al eliminar el libro'));
    });

    formLibro.addEventListener('submit', function (e) {
        e.preventDefault();

        formLibroError.classList.add('d-none');

        const id = document.getElementById('id_libro').value;
        const url = id ? 'inventario/actualizar' : 'inventario/guardar';

        const fd = new FormData(formLibro);
        fd.set('disponible', document.getElementById('disponible').checked ? '1' : '0');

        fetch(url, {
            method: 'POST',
            body: fd
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                offcanvasLibro.hide();
                recargarGrid();
            } else {
                mostrarError(data.message || 'Ocurrió un error al guardar el libro');
            }
        })
        .catch(() => mostrarError('Ocurrió un error de conexión al guardar el libro'));
    });
});