var clientes, idlast;
$(function () {
    // Configuración del JqGrid
    // Configuramos la tabla dinámica para que sea responsive
    $.jgrid.defaults.responsive = true;

    // Aplicamos las clases de Bootstrap en la tabla dinámica
    $.jgrid.styleUI.Bootstrap5.base.rowTable = "table table-bordered table-hover table-sm";

    clientes = $('#clientes').jqGrid({
        url: 'clientes/obtener_clientes',
        datatype: "json",
        styleUI: "Bootstrap5",
        iconSet: "fontAwesome",
        mtype: "POST",
        colModel: [ // Establece la estructura de la tabla dinamica
            { label: 'NRC', name: 'nrc', index: 'nrc', width: 100 },
            { label: 'NOMBRE DE LA EMPRESA', name: 'nombre_empresa', index: 'nombre_empresa', width: 300 },
            { label: 'CORREO EMPRESARIAL', name: 'correo_empresarial', index: 'correo_empresarial', width: 250 },
            { label: 'TELEFONO EMPRESA', name: 'telefono_empresa', index: 'telefono_empresa', width: 120, align: "center" },
            { label: 'CONTACTO', name: 'persona_contacto', index: 'persona_contacto', width: 250 },
            { label: 'TELÉFONO', name: 'telefono_contacto', index: 'telefono_contacto', width: 120, align: "center" },
            { label: 'CORREO ELECTRÓNICO', name: 'correo_contacto', index: 'correo_contacto', width: 400 }
        ],
        shrinkToFit: false,
        width: $('.workspace').width(),
        height: $(window).height() * 0.55,
        rowNum: 500, // Establece el número de filas o registros que se veran en la tabla
        rownumbers: true,
        rowNumWidth: 35,
        pager: '#navclientes', // Indica el div de la barra de navegacion
        sortname: 'nombre_empresa', // Indica el nombre del campo por el que se ordenan los registros
        viewrecords: true,
        sortorder: "asc", // Indica el ordenamiento ascendente o descendente
        onSelectRow: function (rowid, status, e) {
            idlast = rowid;
        }
    });

    // Configuramos la barra de navegación del JqGrid
    clientes.navGrid('#navclientes', { edit: false, add: false, del: false, view: true, search: false });
});

/*$(function () {

    // ---------- Configuración de jqGrid ----------
    $("#gridClientes").jqGrid({
        url: "/clientes/registros",
        datatype: "json",
        mtype: "GET",

        jsonReader: {
            root: "rows",
            page: "page",
            total: "total",
            records: "records",
            repeatitems: false,
            id: "id_cliente"
        },

        colModel: [
            { name: "id_cliente", label: "ID", key: true, width: 60, sorttype: "int" },
            { name: "nombre_empresa", label: "Empresa", width: 180, search: true },
            { name: "correo_empresarial", label: "Correo Empresarial", width: 180, search: true },
            { name: "telefono_empresa", label: "Tel. Empresa", width: 110, search: true },
            { name: "persona_contacto", label: "Contacto", width: 140, search: true },
            { name: "correo_contacto", label: "Correo Contacto", width: 180, search: true },
            { name: "telefono_contacto", label: "Tel. Contacto", width: 110, search: true },
            { name: "celular_contacto", label: "Celular Contacto", width: 120, search: true },
            { name: "fecha_creacion", label: "Fecha Creación", width: 140, search: false },
            {
                name: "acciones",
                label: "Acciones",
                width: 130,
                sortable: false,
                search: false,
                formatter: function (cellvalue, options, rowObject) {
                    return `
                        <button type="button" class="btn-accion btn-editar" data-id="${rowObject.id_cliente}">Editar</button>
                        <button type="button" class="btn-accion btn-eliminar" data-id="${rowObject.id_cliente}">Eliminar</button>
                    `;
                }
            }
        ],

        pager: "#pagerClientes",
        rowNum: 10,
        rowList: [10, 20, 50],
        loadonce: true,
        sortname: "id_cliente",
        sortorder: "asc",
        viewrecords: true,
        height: "auto",
        autowidth: true,
        caption: "Clientes",

        loadComplete: function () {
            $("#gridClientes").jqGrid("filterToolbar", { stringResult: true, searchOnEnter: false });
        }
    });

    $(window).on("resize", function () {
        $("#gridClientes").jqGrid("setGridWidth", $(".table-section").width());
    });

    // Recarga los datos desde el servidor (loadonce mantiene caché local, por eso se fuerza datatype:json)
    function recargarGrid() {
        $("#gridClientes").jqGrid("setGridParam", { datatype: "json" }).trigger("reloadGrid");
    }

    // ---------- Manejo del Modal ----------
    const modal = document.getElementById("modalCliente");
    const form = document.getElementById("formCliente");
    const modalTitulo = document.getElementById("modalTitulo");
    const formInfo = document.getElementById("form-info");

    function abrirModal(titulo) {
        modalTitulo.textContent = titulo;
        formInfo.classList.add("hide");
        modal.classList.remove("hide");
    }

    function cerrarModal() {
        modal.classList.add("hide");
        form.reset();
        document.getElementById("id_cliente").value = "";
    }

    document.getElementById("btnNuevo").addEventListener("click", () => {
        form.reset();
        document.getElementById("id_cliente").value = "";
        abrirModal("Nuevo Cliente");
    });

    document.getElementById("btnCancelar").addEventListener("click", cerrarModal);

    // Editar: se leen los datos directamente de la fila seleccionada en el grid
    $("#gridClientes").on("click", ".btn-editar", function () {
        const id = $(this).data("id");
        const fila = $("#gridClientes").jqGrid("getRowData", id);

        document.getElementById("id_cliente").value = id;
        document.getElementById("nombre_empresa").value = fila.nombre_empresa || "";
        document.getElementById("correo_empresarial").value = fila.correo_empresarial || "";
        document.getElementById("telefono_empresa").value = fila.telefono_empresa || "";
        document.getElementById("persona_contacto").value = fila.persona_contacto || "";
        document.getElementById("correo_contacto").value = fila.correo_contacto || "";
        document.getElementById("telefono_contacto").value = fila.telefono_contacto || "";
        document.getElementById("celular_contacto").value = fila.celular_contacto || "";

        abrirModal("Editar Cliente");
    });

    // Eliminar
    $("#gridClientes").on("click", ".btn-eliminar", function () {
        const id = $(this).data("id");

        if (!confirm("¿Está seguro de eliminar este cliente? Esta acción no se puede deshacer.")) {
            return;
        }

        const fd = new FormData();
        fd.append("id_cliente", id);

        fetch("/clientes/eliminar", {
            method: "POST",
            body: fd
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                recargarGrid();
            } else {
                alert(data.message || "No se pudo eliminar el cliente");
            }
        })
        .catch(() => alert("Ocurrió un error al eliminar el cliente"));
    });

    // Crear / Actualizar
    form.addEventListener("submit", function (e) {
        e.preventDefault();

        const id = document.getElementById("id_cliente").value;
        const url = id ? "/clientes/actualizar" : "/clientes/crear";
        const fd = new FormData(form);

        fetch(url, {
            method: "POST",
            body: fd
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                cerrarModal();
                recargarGrid();
            } else {
                formInfo.textContent = data.message || "Ocurrió un error al guardar";
                formInfo.classList.remove("hide");
            }
        })
        .catch(() => {
            formInfo.textContent = "Ocurrió un error al guardar";
            formInfo.classList.remove("hide");
        });
    });
});*/
