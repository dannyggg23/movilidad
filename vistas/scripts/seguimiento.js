var tabla;

//Función que se ejecuta al inicio
function init() {
    mostrarform(false);
    listar();

    $("#formulario").on("submit", function(e) {
        guardaryeditar(e);
    })

    //Cargamos los items al select categoria
    $.post("../ajax/seguimiento.php?op=selectEgresoBienes", function(r) {
        $("#egreso_bienes_idegreso_bienes").html(r);
        $('#egreso_bienes_idegreso_bienes').selectpicker('refresh');

    });
    $("#imagenmuestra").hide();
}

//Función limpiar
function limpiar() {
    $("#idseguimiento_egrese_bienes").val("");
    $("#fecha").val("");
    $("#descripcion").val("");
    $("#imagenmuestra").attr("src", "");
    $("#imagenactual").val("");

    $("#egreso_bienes_idegreso_bienes").val("");
    $('#egreso_bienes_idegreso_bienes').selectpicker('refresh');
    $("#imagen").val("");

}

//Función mostrar formulario
function mostrarform(flag) {
    limpiar();
    if (flag) {
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        $("#btnGuardar").prop("disabled", false);
        $("#btnagregar").hide();
        $("#imagenmuestra").hide();
    } else {
        $("#listadoregistros").show();
        $("#formularioregistros").hide();
        $("#btnagregar").show();
    }
}

//Función cancelarform
function cancelarform() {
    limpiar();
    mostrarform(false);
}

//Función Listar
function listar() {
    tabla = $('#tbllistado').dataTable({
        "aProcessing": true, //Activamos el procesamiento del datatables
        "aServerSide": true, //Paginación y filtrado realizados por el servidor
        dom: 'Bfrtip', //Definimos los elementos del control de tabla
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdf'
        ],
        "ajax": {
            url: '../ajax/seguimiento.php?op=listar',
            type: "get",
            dataType: "json",
            error: function(e) {
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "iDisplayLength": 5, //Paginación
        "order": [
                [0, "desc"]
            ] //Ordenar (columna,orden)
    }).DataTable();
}
//Función para guardar o editar

function guardaryeditar(e) {
    e.preventDefault(); //No se activará la acción predeterminada del evento
    $("#btnGuardar").prop("disabled", true);
    var formData = new FormData($("#formulario")[0]);

    $.ajax({
        url: "../ajax/seguimiento.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function(datos) {
            bootbox.alert(datos);
            mostrarform(false);
            tabla.ajax.reload();
        }

    });
    limpiar();
}

function mostrar(idseguimiento_egrese_bienes) {
    $.post("../ajax/seguimiento.php?op=mostrar", { idseguimiento_egrese_bienes: idseguimiento_egrese_bienes }, function(data, status) {
        data = JSON.parse(data);
        mostrarform(true);

        $("#imagenmuestra").hide();

        $("#idseguimiento_egrese_bienes").val(data.idseguimiento_egrese_bienes);
        $("#fecha").val(data.fecha);
        $("#descripcion").val(data.descripcion);
        $("#egreso_bienes_idegreso_bienes").val(data.egreso_bienes_idegreso_bienes);
        $('#egreso_bienes_idegreso_bienes').selectpicker('refresh');

        $("#imagenmuestra").show();
        $("#imagenmuestra").attr("src", "../files/seguimiento/" + data.imagen);
        $("#imagenactual").val(data.imagen);

    })
}

//Función para desactivar registros
function desactivar(idseguimiento_egrese_bienes) {
    bootbox.confirm("¿Está Seguro de desactivar ?", function(result) {
        if (result) {
            $.post("../ajax/seguimiento.php?op=desactivar", { idseguimiento_egrese_bienes: idseguimiento_egrese_bienes }, function(e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    })
}

//Función para activar registros
function activar(idseguimiento_egrese_bienes) {
    bootbox.confirm("¿Está Seguro de activar ?", function(result) {
        if (result) {
            $.post("../ajax/seguimiento.php?op=activar", { idseguimiento_egrese_bienes: idseguimiento_egrese_bienes }, function(e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    })
}

init();