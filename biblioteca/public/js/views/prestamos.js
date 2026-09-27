var prestamos, idlastPrestamo;
$(function () {
    // Configuración del JqGrid
    $.jgrid.defaults.responsive = true;

    $.jgrid.styleUI.Bootstrap5.base.headerRowTable = "table table-bordered table-hover table-sm bg-warning";
    $.jgrid.styleUI.Bootstrap5.base.rowTable = "table table-bordered table-hover table-sm";

    prestamos = $('#prestamos').jqGrid({
        url: 'prestamos/obtener_prestamos',
        datatype: "json",
        styleUI: "Bootstrap5",
        iconSet: "fontAwesome",
        regional: "es",
        mtype: "POST",
        colModel: [
            { name: 'id_libro', index: 'id_libro', hidden: true }, // oculto, solo referencia
            { label: 'LIBRO', name: 'titulo', index: 'titulo', width: 220 },
            { label: 'LECTOR', name: 'nombre_lector', index: 'nombre_lector', width: 160 },
            { label: 'IDENTIFICACIÓN', name: 'tipo_identificacion', index: 'tipo_identificacion', width: 130 },
            { label: 'N.º DOCUMENTO', name: 'numero_documento', index: 'numero_documento', width: 120 },
            { label: 'FECHA PRÉSTAMO', name: 'fecha_prestamo', index: 'fecha_prestamo', width: 110, align: "center" },
            { label: 'FECHA LÍMITE', name: 'fecha_devolucion', index: 'fecha_devolucion', width: 110, align: "center" },
            {
                label: 'ESTADO',
                name: 'estado',
                index: 'estado',
                width: 110,
                align: "center",
                formatter: function (cellvalue) {
                    const clases = {
                        'Prestado': 'badge prestado',
                        'Devuelto': 'badge devuelto',
                        'Vencido': 'badge vencido'
                    };
                    const clase = clases[cellvalue] || 'badge';
                    return '<span class="' + clase + '">' + cellvalue + '</span>';
                }
            },
            { label: 'DEVUELTO EL', name: 'fecha_devolucion_real', index: 'fecha_devolucion_real', width: 110, align: "center" }
        ],
        shrinkToFit: false,
        width: $('.workspace').width(),
        height: $(window).height() * 0.55,
        rowNum: 10,
        rownumbers: true,
        rowNumWidth: 35,
        pager: '#navprestamos',
        sortname: 'fecha_prestamo',
        viewrecords: true,
        sortorder: "desc",
        onSelectRow: function (rowid, status, e) {
            idlastPrestamo = rowid;
        }
    });

    prestamos.navGrid('#navprestamos', { edit: false, add: false, del: false, view: true, search: false });

    const offcanvasPrestamoEl = document.getElementById('offcanvasPrestamo');
    const offcanvasPrestamo = new bootstrap.Offcanvas(offcanvasPrestamoEl);
    const formPrestamo = document.getElementById('formPrestamo');
    const formPrestamoError = document.getElementById('formPrestamoError');

    function recargarGrid() {
        prestamos.trigger('reloadGrid');
    }

    function mostrarError(mensaje) {
        formPrestamoError.textContent = mensaje;
        formPrestamoError.classList.remove('d-none');
    }

    function limpiarFormulario() {
        formPrestamo.reset();
        formPrestamoError.classList.add('d-none');
    }

    function cargarLibrosDisponibles() {
        const select = document.getElementById('id_libro');
        select.innerHTML = '<option value="">Selecciona un libro disponible</option>';

        fetch('prestamos/libros_disponibles')
            .then(response => response.json())
            .then(data => {
                (data.registros || []).forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id_libro;
                    option.textContent = item.titulo + (item.autor_nombre ? ' — ' + item.autor_nombre : '');
                    select.appendChild(option);
                });
            })
            .catch(() => alert('No se pudo cargar la lista de libros disponibles'));
    }

    function fechaLimitePorDefecto() {
        const fecha = new Date();
        fecha.setDate(fecha.getDate() + 7); // 7 días de préstamo por defecto
        return fecha.toISOString().split('T')[0];
    }

    document.getElementById('btnNuevoPrestamo').addEventListener('click', function () {
        limpiarFormulario();
        cargarLibrosDisponibles();
        document.getElementById('fecha_devolucion').value = fechaLimitePorDefecto();
        offcanvasPrestamo.show();
    });

    document.getElementById('btnDevolverPrestamo').addEventListener('click', function () {
        if (!idlastPrestamo) {
            alert('Selecciona primero un préstamo de la tabla');
            return;
        }

        const fila = prestamos.jqGrid('getRowData', idlastPrestamo);

        if (fila.estado && fila.estado.includes('>Devuelto<')) {
            alert('Ese préstamo ya fue devuelto');
            return;
        }

        if (!confirm('¿Confirmas que el libro fue devuelto?')) {
            return;
        }

        const fd = new FormData();
        fd.append('id', idlastPrestamo);

        fetch('prestamos/devolver', {
            method: 'POST',
            body: fd
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                idlastPrestamo = null;
                recargarGrid();
            } else {
                alert(data.message || 'No se pudo registrar la devolución');
            }
        })
        .catch(() => alert('Ocurrió un error al registrar la devolución'));
    });

    document.getElementById('btnEliminarPrestamo').addEventListener('click', function () {
        if (!idlastPrestamo) {
            alert('Selecciona primero un préstamo de la tabla');
            return;
        }

        if (!confirm('¿Deseas eliminar este préstamo? Esta acción no se puede deshacer.')) {
            return;
        }

        const fd = new FormData();
        fd.append('id', idlastPrestamo);

        fetch('prestamos/eliminar', {
            method: 'POST',
            body: fd
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                idlastPrestamo = null;
                recargarGrid();
            } else {
                alert(data.message || 'No se pudo eliminar el préstamo');
            }
        })
        .catch(() => alert('Ocurrió un error al eliminar el préstamo'));
    });

    formPrestamo.addEventListener('submit', function (e) {
        e.preventDefault();

        formPrestamoError.classList.add('d-none');

        const fd = new FormData(formPrestamo);

        fetch('prestamos/guardar', {
            method: 'POST',
            body: fd
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                offcanvasPrestamo.hide();
                recargarGrid();
            } else {
                mostrarError(data.message || 'Ocurrió un error al guardar el préstamo');
            }
        })
        .catch(() => mostrarError('Ocurrió un error de conexión al guardar el préstamo'));
    });
});