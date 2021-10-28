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

<div style="padding-left:  10%;padding-bottom:270px;margin-bottom:210px">
    <h2 style="color: #1C5394; padding-left:  1%;" class="titulo-iz">Tarjetas</h2>
    <br/>
    <br/>
    <div class="col-lg-5 col-a">
        <img class="soporte-menu-img-int" src="/static/img/portal/soporte/soporte-i-tarjetas.png" />
        <br>
        <a href="/portal/soporte/tarjetas"><span class="online-number">1</span>Información general sobre el uso de las tarjetas</a>
        <br>
        <a href="/portal/soporte/tarjetas2"><span class="online-number">2</span>Cancelación definitiva de tarjeta</a>
        <br>
        <a href="/portal/soporte/tarjetas3" class="a_selected"><span class="online-number online-numerselected">3</span>Solicitud de cancelación ó Modificación de pedido de tarjetas</a>
        <br>
        <a href="/portal/soporte/tarjetas4"><span class="online-number">4</span>Bloqueo preventivo de tarjeta</a>
        <br>
    </div>
    <div class="col-lg-4">
        <form method="POST" >
            <label class="soporte-label">Es necesario diligenciar todos los campos</label>
            <input type="text" class="textPat" style="padding-left:10px" name="telefono" placeholder="Teléfono de contacto" value="" required><br>
            <input type="text" type="email" pattern="[A-Za-z0-9._%+-]{3,}@[A-Za-z]{3,}\.[A-Za-z]{2,}(?:\.[A-Za-z]{2,})?" style="padding-left:10px" name="correo" placeholder="Correo electrónico de contacto" value="" required><br><br>
            <select style="width:100%;border-radius:10px;padding-left:10px" name='tdocumento' required>
                <option value="">Tipo de Documento</option>
                <?php foreach ($tipoDocumento as $key => $value) { ?>
                <option value="<?= $value['PK_TD_CODIGO'] ?>"> <?= $value['NOMBRE'] ?></option>
                <?php } ?>
            </select><br>
            <input type="text" class="textPat"  style="padding-left:10px" name="documento" placeholder="Número de Documento" value="" required><br>
            <input type="text" class="textPat" style="padding-left:10px" name="prod" placeholder="Producto" value="" required><br><br>
            <textarea rows="5"class="" style="width:100%;border-radius:10px;padding-left:10px"name="desc" placeholder="Descripción" maxlength="2000" value="" required></textarea><br>

            <div class="row">
                <div class="button col-sm-6 col-sm-push-3">
                    <button type="submit">
                        <span class="glyphicon glyphicon-cloud-download"></span>Ajuntar SOPORTE
                    </button>
                </div>
            </div>
            <div class="row" style="margin-bottom: 50px">
                <div class="button col-sm-6 col-sm-push-3">
                    <button type="submit">
                        E N V I A R
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

