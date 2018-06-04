var tabla;

//Función que se ejecuta al inicio
function init() {
    mostrarform(false);
    listar();

    $("#formulario").on("submit", function(e) {
        guardaryeditar(e);
    });

}

//Función limpiar
function limpiar() {
    $("#idingreso_bienes_bienes").val("");
    $("#ubicacion").val("");
    $("#detalle").val("");
    $("#numero_ingreso_bienes").val("");
    $("#n_documento").val("0");


    $("#total").val("");
    $(".filas").remove();
    $("#totalL").html("0");


    //Obtenemos la fecha actual
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear() + "-" + (month) + "-" + (day);
    $('#fecha').val(today);


}

//Función mostrar formulario
function mostrarform(flag) {
    limpiar();
    if (flag) {
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        //$("#btnGuardar").prop("disabled",false);
        $("#btnagregar").hide();
        listarArticulos();

        $("#btnGuardar").hide();
        $("#btnCancelar").show();
        detalles = 0;
        $("#btnAgregarArt").show();
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
            url: '../ajax/ingreso_bienes.php?op=listar',
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


//Función ListarArticulos
function listarArticulos() {
    tabla = $('#tblarticulos').dataTable({
        "aProcessing": true, //Activamos el procesamiento del datatables
        "aServerSide": true, //Paginación y filtrado realizados por el servidor
        dom: 'Bfrtip', //Definimos los elementos del control de tabla
        buttons: [

        ],
        "ajax": {
            url: '../ajax/ingreso_bienes.php?op=listarBienes',
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
    //$("#btnGuardar").prop("disabled",true);
    var formData = new FormData($("#formulario")[0]);

    $.ajax({
        url: "../ajax/ingreso_bienes.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function(datos) {
            bootbox.alert(datos);
            mostrarform(false);
            listar();
        }

    });
    limpiar();
}

function mostrar(idingreso_bienes) {
    $.post("../ajax/ingreso_bienes.php?op=mostrar", { idingreso_bienes: idingreso_bienes }, function(data, status) {
        data = JSON.parse(data);
        mostrarform(true);

        $("#ubicacion").val(data.ubicacion);

        $("#detalle").val(data.detalle);

        $("#numero_ingreso").val(data.numero_ingreso);
        $("#fecha").val(data.fecha);
        $("#total").val(data.total);


        //Ocultar y mostrar los botones
        $("#btnGuardar").hide();
        $("#btnCancelar").show();
        $("#btnAgregarArt").hide();
    });

    $.post("../ajax/ingreso_bienes.php?op=listarDetalle&id=" + idingreso_bienes, function(r) {
        $("#detalles").html(r);
    });
}

//Función para anular registros
function anular(idingreso_bienes) {
    bootbox.confirm("¿Está Seguro de anular el ingreso?", function(result) {
        if (result) {
            $.post("../ajax/ingreso_bienes.php?op=anular", { idingreso_bienes: idingreso_bienes }, function(e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    })
}

//Declaración de variables necesarias para trabajar con las compras y
//sus detalles

var cont = 0;
var detalles = 0;
//$("#guardar").hide();
$("#btnGuardar").hide();




function agregarDetalle(idbienes, nombre) {

    var cantidad = 1;
    var consto_unitario = 1;

    if (idbienes != "") {

        var subtotal = cantidad * consto_unitario;
        var fila = '<tr class="filas" id="fila' + cont + '">' +
            '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ')">X</button></td>' +
            '<td><input type="hidden" name="bienes_idbienes[]" id="bienes_idbienes[]" value="' + idbienes + '">' + nombre + '</td>' +
            '<td><input type="number" name="cantidad[]" id="cantidad[]" value="' + cantidad + '"></td>' +
            '<td><input type="number" name="consto_unitario[]" id="consto_unitario[]" value="' + consto_unitario + '"></td>' +
            '<td><span name="subtotal" id="subtotal' + cont + '">' + subtotal + '</span></td>' +
            '<td><button type="button" onclick="modificarSubototales()" class="btn btn-info"><i class="fa fa-refresh"></i></button></td>' +
            '</tr>';
        cont++;
        detalles = detalles + 1;
        $('#detalles').append(fila);
        modificarSubototales();
    } else {
        alert("Error al ingresar el detalle, revisar los datos del artículo");
    }
}

function modificarSubototales() {
    var cant = document.getElementsByName("cantidad[]");
    var prec = document.getElementsByName("consto_unitario[]");
    var sub = document.getElementsByName("subtotal");

    for (var i = 0; i < cant.length; i++) {
        var inpC = cant[i];
        var inpP = prec[i];
        var inpS = sub[i];

        inpS.value = inpC.value * inpP.value;
        document.getElementsByName("subtotal")[i].innerHTML = inpS.value;
    }
    calcularTotales();

}

function calcularTotales() {
    var sub = document.getElementsByName("subtotal");
    var total = 0.0;

    for (var i = 0; i < sub.length; i++) {
        total += document.getElementsByName("subtotal")[i].value;
    }
    $("#totalL").html("S/. " + total);
    $("#total").val(total);
    evaluar();
}

function evaluar() {
    if (detalles > 0) {
        $("#btnGuardar").show();
    } else {
        $("#btnGuardar").hide();
        cont = 0;
    }
}

function eliminarDetalle(indice) {
    $("#fila" + indice).remove();
    calcularTotales();
    detalles = detalles - 1;
    evaluar();
}

init();