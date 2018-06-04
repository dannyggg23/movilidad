var tabla;

//Función que se ejecuta al inicio
function init() {
    mostrarform(false);
    listar();

    $("#formulario").on("submit", function(e) {
        guardaryeditar(e);
    })

    //Cargamos los items al select categoria
    $.post("../ajax/bienes.php?op=selectCategoria", function(r) {
        $("#categorias_bienes_idcategorias_bienes").html(r);
        $('#categorias_bienes_idcategorias_bienes').selectpicker('refresh');

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
    $("#idbienes").val("");
    $("#valor").val("");
    $("#tipo").val("");

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
            url: '../ajax/bienes.php?op=listar',
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
        url: "../ajax/bienes.php?op=guardaryeditar",
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

function mostrar(idbienes) {
    $.post("../ajax/bienes.php?op=mostrar", { idbienes: idbienes }, function(data, status) {
        data = JSON.parse(data);
        mostrarform(true);

        $("#categorias_bienes_idcategorias_bienes").val(data.categorias_bienes_idcategorias_bienes);
        $('#categorias_bienes_idcategorias_bienes').selectpicker('refresh');
        $("#codigo").val(data.codigo);
        $("#nombre").val(data.nombre);
        $("#stock").val(data.stock);
        $("#descripcion").val(data.descripcion);
        $("#imagenmuestra").show();
        $("#imagenmuestra").attr("src", "../files/bienes/" + data.imagen);
        $("#imagenactual").val(data.imagen);
        $("#idbienes").val(data.idbienes);
        $("#tipo").val(data.tipo);
        $("#valor").val(data.valor);


    })
}

//Función para desactivar registros
function desactivar(idbienes) {
    bootbox.confirm("¿Está Seguro de desactivar el artículo?", function(result) {
        if (result) {
            $.post("../ajax/bienes.php?op=desactivar", { idbienes: idbienes }, function(e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    })
}

//Función para activar registros
function activar(idbienes) {
    bootbox.confirm("¿Está Seguro de activar el Artículo?", function(result) {
        if (result) {
            $.post("../ajax/bienes.php?op=activar", { idbienes: idbienes }, function(e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    })
}

init();