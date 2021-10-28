<style>
    #titulo{
        color:#366199 ;
        font-size: 56px;
    }
    #checks{
        width: 50px;
    }
    #seleccionarTodo{
        padding: 9%;
    }
    #background{
        width: 20%;
        height: 60px;
        background-color: #366199;
        margin-left: 90%;
        color: #fff;
    }
    #imagen{
        vertical-align: middle;
        padding: 5%;
        width: 50%;
    }  
    #imagen1{
        padding-left: 5%;
        vertical-align: middle;
        padding-top: 8%;
        width: 50%;
    } 
    #selectButton{
        width: 10%;
    }
</style>
<div class="col-sm-9" style="margin-left:20px" >
    <h1 id="titulo" class="titulo-iz-cat">Confirmar Pedido Tarjetas</h1>
    <br>
    <br>
    <form name="activarTarjetas" method="post" id="activarTarjetas">
        <div class="grid" style="margin: 2%;">
            <table class="table table-hover" id="tableEntregas"> 

                <thead>
                    <tr>
                        <th> ESTADO </th>
                        <th> DOCUMENTO </th>
                        <th> NOMBRE </th>
                        <th> NUMEROTARJETA</th>
                        <th> PRODUCTO</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tarjetas as $value) { ?>
                        <tr class="gradeC">
                            <td>
                                <input id="checks" type="checkbox" 
                                <?php if ($value['ESTADOENVIO'] <> 6) { ?>
                                           checked disabled="true"
                                       <?php } ?>   
                                       name="checks[]" value="<?= $value['CODDETPED'] ?>">
                            </td>
                            <td> <?= $value['DOCUMENTO'] ?></td>
                            <td> <?= $value['NOMBRE'] ?></td>
                            <td> <?= $value['NUMEROTARJETA'] ?></td>
                            <td> &nbsp; &nbsp;<?= ucwords(strtolower($value['PRODUCTO'])) ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <br>
        <div class="button  col-md-3 col-md-push-5" style="margin-bottom: 5%">
            <button class="spacing" type="submit">
                ACEPTAR
            </button>
            <br><br>
            <div class=" linkgenerico spacing">
                <a href="/portal/entregas/lista">VOLVER</a>
            </div>
        </div>

    </form>
</div>
<div  id="seleccionarTodo" class="cols-sm-2">
    <div id="background" onclick="seleccionarTodo()">
        <table>
            <td>
                <img id="imagen" src="/static/img/portal/entregas/seleccionar_todos.png" alt=""/>  
            </td>
            <td style="padding:0;">Seleccionar todos
            </td>
        </table>
    </div>
    <br>
    <div id="background" onclick="reportarPedido()">
        <table>
            <td>
                <img id="imagen1" src="/static/img/portal/entregas/reportar_pedido.png" alt=""/>  
            </td>
            <td>
                Reportar pedido incompleto
            </td>
        </table>
    </div>
</div>


<?php if (isset($error) == 1) { ?>
    <!-- Modal confirmacion recarga-->
    <div class="modal fade" id="ModalConfirmarEntrega" role="dialog" style="margin-top: 15%;"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content" style="border-radius:35px">

                <div class="modal-body" style="text-align: center;height: 200px;">

                    <p  style="font-size:24px;color:#888686;font-weight: bold;padding-top: 25px">¡Pedido confirmado exitosamente! 
                    </p>
                    <div style="">
                        <div class="button col-sm-6 col-sm-push-3" >
                            <div class="row linkgenerico" style="/*padding-bottom: 100px; padding-left: 100px;*/">
                                <a  href="/portal/entregas/lista" class="spacing">ACEPTAR</a>
                            </div>
                        </div>
                    </div>
                    <br>
                </div>
            </div>
        </div>
    </div>
<?php } ?>


<script>
    var confEntrega = <?php
if (isset($error) == 1) {
    echo "1;";
} else {
    echo "0;";
}
?>
    if (confEntrega == 1) {
        $('#ModalConfirmarEntrega').modal('show');
    }
    var seleccionados = 0;
    function seleccionarTodo() {
//        if (seleccionados === 0) {
//            var items = document.getElementsByName('checks[]');
//            for (var i = 0; i < items.length; i++) {
//                if (items[i].type == 'checkbox')
//                    items[i].checked = true;
//            }
//            seleccionados = 1;
//        } else {
//            var items = document.getElementsByName('checks[]');
//            for (var i = 0; i < items.length; i++) {
//                if (items[i].type == 'checkbox')
//                    items[i].checked = false;
//            }
//            seleccionados = 0;
//        }
    }

    function reportarPedido() {
        parent.location = "/portal/soporte/entregas";
    }

    $(document).ready(function () {
        var table = $('#tableEntregas').DataTable({
            "bJQueryUI": true,
            "bSort": false,
            "bPaginate": true,
            "sPaginationType": "full_numbers",
            "oLanguage": {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
                "sSearch": "",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            }

        });

        // Handle click on "Select all" control
        $('#seleccionarTodo').on('click', function () {
            // Check/uncheck all checkboxes in the table
            var rows = table.rows({'search': 'applied'}).nodes();
            for (i = 0; i < rows.length; i++) {
                var isSelected = $('#checks', rows[i]).prop('checked');
                var estCheck = $('#checks', rows[i]).prop("disabled");
                if (!estCheck) {
                    if (isSelected) {
                        $('#checks', rows[i]).prop("checked", false);
                    } else {
                        $('#checks', rows[i]).prop("checked", true);
                    }
                }

            }
        });

        // Handle click on checkbox to set state of "Select all" control
        $('#activarTarjetas tbody').on('change', 'input[type="checkbox"]', function () {
            // If checkbox is not checked
            if (!this.checked) {
                var el = $('#seleccionarTodo').get(0);
                // If "Select all" control is checked and has 'indeterminate' property
                if (el && el.checked && ('indeterminate' in el)) {
                    // Set visual state of "Select all" control 
                    // as 'indeterminate'
                    el.indeterminate = true;
                }
            }
        });
        $('#activarTarjetas').on('submit', function (e) {
            var form = this;

            // Iterate over all checkboxes in the table
            table.$('input[type="checkbox"]').each(function () {
                // If checkbox doesn't exist in DOM
                if (!$.contains(document, this)) {
                    // If checkbox is checked
                    if (this.checked) {
                        // Create a hidden element 
                        $(form).append(
                                $('<input>')
                                .attr('type', 'hidden')
                                .attr('name', this.name)
                                .val(this.value)
                                );
                    }
                }
            });

            // FOR TESTING ONLY

            // Output form data to a console
//      $('#example-console').text($(form).serialize()); 
//      console.log("Form submission", $(form).serialize()); 

            // Prevent actual form submission
//      e.preventDefault();
        });

    });


</script>