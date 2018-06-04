var tabla;

//Función que se ejecuta al inicio
function init() {
    mostrarform(false);
    listar();

    $("#formulario").on("submit", function(e) {
        guardaryeditar(e);
    })

    //Cargamos los items al select categoria
    $.post("../ajax/especies.php?op=selectCategoria", function(r) {
        $("#categorias_especies_idcategorias_especies").html(r);
        $('#categorias_especies_idcategorias_especies').selectpicker('refresh');

    });
    $("#imagenmuestra").hide();
}

//Función limpiar
function limpiar() {
    $("#codigo").val("");
    $("#nombre").val("");
    $("#descripcion").val("");
    $("#stock").val("");
    $("#imagenmuestra").attr("src", "");
    $("#imagenactual").val("");
    $("#print").hide();
    $("#idespecies").val("");
    $("#desde").val("");
    $("#hasta").val("");

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
            url: '../ajax/especies.php?op=listar',
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
        url: "../ajax/especies.php?op=guardaryeditar",
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

function mostrar(idespecies) {
    $.post("../ajax/especies.php?op=mostrar", { idespecies: idespecies }, function(data, status) {
        data = JSON.parse(data);
        mostrarform(true);

        $("#categorias_especies_idcategorias_especies").val(data.categorias_especies_idcategorias_especies);
        $('#categorias_especies_idcategorias_especies').selectpicker('refresh');
        $("#codigo").val(data.codigo);
        $("#nombre").val(data.nombre);
        $("#stock").val(data.stock);
        $("#descripcion").val(data.descripcion);
        $("#imagenmuestra").show();
        $("#imagenmuestra").attr("src", "../files/especies/" + data.imagen);
        $("#imagenactual").val(data.imagen);
        $("#idespecies").val(data.idespecies);
        $("#desde").val(data.desde);
        $("#hasta").val(data.hasta);

    })
}

//Función para desactivar registros
function desactivar(idespecies) {
    bootbox.confirm("¿Está Seguro de desactivar el artículo?", function(result) {
        if (result) {
            $.post("../ajax/especies.php?op=desactivar", { idespecies: idespecies }, function(e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    })
}

//Función para activar registros
function activar(idespecies) {
    bootbox.confirm("¿Está Seguro de activar el Artículo?", function(result) {
        if (result) {
            $.post("../ajax/especies.php?op=activar", { idespecies: idespecies }, function(e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    })
}

init();