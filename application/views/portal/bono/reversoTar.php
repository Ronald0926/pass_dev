<div class='row'>
    <div class='col-md-3'></div>
    <div class='col-md-6'>
        <h1 class="titulo-iz">Reverso Tarjetas</h1>
        <form method="POST">
            <?php foreach ($tarjeta as $key => $value) { ?>
                <div class='col-md-5' style="background-color:#366199; color: white;">

                    <label required type="text" style="padding-left:10px">
                        Tarjeta a Reversar: </label><br>
                    <label required type="text" style="padding-left:10px">
                        <?= $value['NUMTAR'] ?> </label><br>
                    <label required type="text" style="padding-left:10px">
                        Identificador</label><br>
                    <label required type="text" style="padding-left:10px">
                        <?= $value['IDENTI'] ?></label><br>

                </div>
                <div class='col-md-2'>
                    <div class="arrow"></div>
                </div>
                <div class='col-md-5' style="background-color:#366199; color: white;">

                    <label required type="text" style="padding-left:10px">
                        Destino: </label><br>
                    <label required type="text" style="padding-left:10px">
                        Saldo disponible </label><br>
                    <label required type="text" style="padding-left:10px">
                        Transportador </label><br><br>


                </div>
                <div class='col-md-12'>
                    <label required type="text" style="padding-left:10px">
                        Valor a reversar</label>
                    <input required name="<?= $value['COD'] ?>" type="text" placeholder="$0" style="width: 28%;border: 1px solid #757575; border-radius: 50px;" class="textPat"><br>
                    <label required type="text" style="padding-left:10px">
                        Fecha de operacion: <?php echo date('d/m/Y') ?></label><br>
                </div>
            <?php } ?>
            <div class="button col-md-12">
                <button type="submit" class="btn btn-default">
                    REVERSAR</button>
            </div>

        </form>
    </div>
    <div class='col-md-3'></div>
</div>
<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <?php if ($error == 1) { ?>
                    <h4 class="modal-title">Reverso Exitoso</h4>
                <?php } else { ?>
                    <h4 class="modal-title">Error en la creacion <?= $error ?></h4>
                <?php } ?>
            </div>
            <div class="modal-body">
                <p>Numero de movimiento: <?= $movimiento ?></p>

                <div class="button col-sm-6 CLOSE">
                    <button name="solabono" type="submit" class="btn btn-default" data-dismiss="modal">ACEPTAR</button>
                </div>
            </div>

        </div>

    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            var error = '<?= $error ?>';
            if (error != null && error != '') {
                $('#myModal').modal('show');
            }
        });
    </script>

    <style>
        div.arrow {
            width: 6vmin;
            height: 6vmin;
            box-sizing: border-box;
            position: absolute;
            left: 50%;
            top: 50%;
            transform: rotate(45deg);

            &::before {
                content: '' !important;
                width: 100% !important;
                height: 100% !important;
                border-width: .8vmin .8vmin 0 0 !important;
                border-style: solid !important;
                border-color: #366199 !important;
                transition: .2s ease !important;
                display: block !important;
                transform-origin: 100% 0 !important;
            }
        }

        .triangulo {
            width: 0;
            height: 0;
            border-left: 100px solid #366199;
            border-top: 50px solid transparent;
            border-bottom: 50px solid transparent;
        }
    </style>