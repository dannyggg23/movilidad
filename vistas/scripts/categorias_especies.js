var tabla;

//Función que se ejecuta al inicio
function init() {
    console.log("entra al js");
    mostrarform(false);
    listar();

    $("#formulario").on("submit", function(e) {
        guardaryeditar(e);
    })

}

//Función limpiar
function limpiar() {
    $("#idcategorias_especies").val("");
    $("#nombre").val("");


}

//Función mostrar formulario
function mostrarform(flag) {
    limpiar();
    if (flag) {
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        $("#btnGuardar").prop("disabled", false);
        $("#btnagregar").hide();
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
            url: '../ajax/categorias_especies.php?op=listar',
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
        url: "../ajax/categorias_especies.php?op=guardaryeditar",
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

function mostrar(idcategorias_especies) {
    $.post("../ajax/categorias_especies.php?op=mostrar", { idcategorias_especies: idcategorias_especies }, function(data, status) {
        data = JSON.parse(data);
        mostrarform(true);

        $("#idcategorias_especies").val(data.idcategorias_especies);
        $("#nombre").val(data.nombre);





    })
}

//Función para desactivar registros
function desactivar(idcategorias_especies) {
    bootbox.confirm("¿Está Seguro de desactivar?", function(result) {
        if (result) {
            $.post("../ajax/categorias_especies.php?op=desactivar", { idcategorias_especies: idcategorias_especies }, function(e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    })
}

//Función para activar registros
function activar(idcategorias_especies) {
    bootbox.confirm("¿Está Seguro de activar?", function(result) {
        if (result) {
            $.post("../ajax/categorias_especies.php?op=activar", { idcategorias_especies: idcategorias_especies }, function(e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    })
}



init();