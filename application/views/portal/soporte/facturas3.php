<style>
    .online-number {
        height: 50px;
        width: 50px;
        background-color: #fdc500;
        border-radius: 50%;
        display: inline-block;
        color:#1b5e9b;
        text-align: center;
        margin:5px;
        vertical-align: middle;
        font-size:35px;
    }
    .online-numerselected {
        height: 50px;
        width: 50px;
        background-color: #1b5e9b ;
        border-radius: 50%;
        display: inline-block;
        color:white;
        text-align: center;
        margin:5px;
        vertical-align: middle;
        font-size:35px;
    }
</style> 

<div style="padding-left:  10%">
    <h2 style="color: #1C5394; padding-left:  1%;" class="titulo-iz">Facturas</h2>
    <br/>
    <br/>
    <div class="col-lg-5 col-a">
        <img class="soporte-menu-img-int" src="/static/img/portal/soporte/soporte-i-facturas.png" />
        <br>
        <a href="/portal/soporte/facturas"><span class="online-number">1</span>Solicitud de revisión de los parámetros de impuestos comerciales</a>
        <br>
        <a href="/portal/soporte/facturas2"><span class="online-number">2</span>Anulación de factura</a>
        <br>
        <a href="/portal/soporte/facturas3" class="a_selected"><span class="online-number online-numerselected">3</span>Reverso nota crédito</a>
        <br>
    </div>
    <div class="col-lg-4">
        <form method="POST" >
            <label class="soporte-label">Es necesario diligenciar todos los campos</label>
            <input type="text" style="padding-left:10px" class="textPat" name="telefono" placeholder="Teléfono de contacto" value="" required><br>
            <input type="email" style="padding-left:10px" pattern="[A-Za-z0-9._%+-]{3,}@[A-Za-z]{3,}\.[A-Za-z]{2,}(?:\.[A-Za-z]{2,})?" name="correo" placeholder="Correo electrónico de contacto" value="" required><br><br>
            <select style="width:100%;border-radius:10px;padding-left:10px" name='tdocumento' required>
                <option value="">Tipo de Documento</option>
                <?php foreach ($tipoDocumento as $key => $value) { ?>
                <option value="<?= $value['PK_TD_CODIGO'] ?>"> <?= $value['NOMBRE'] ?></option>
                <?php } ?>
            </select><br>
            <input type="text" style="padding-left:10px" class="textPat" name="numeroDocumento" placeholder="Numero de Documento" value="" required><br>
            <input type="text" style="padding-left:10px" class="textPat" name="producto" placeholder="Producto" value="" required><br>
            <input type="text" style="padding-left:10px" class="textPat" name="numeroTarjeta" placeholder="Número de tarjeta (últimos 4 digitos)" value="" required><br>
            <input type="text" style="padding-left:10px" class="numPat" name="valorNotaCredito" placeholder="Ingrese valor de la nota crédito" value="" required><br>
            
            <div class="row">
                <div class="button col-sm-6 col-sm-push-3">
                    <button type="submit">
                        E N V I A R
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

