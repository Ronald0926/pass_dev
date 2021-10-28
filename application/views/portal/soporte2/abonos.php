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
    <h2 style="color: #1C5394; padding-left:  1%;" class="titulo-iz">Abonos</h2>
    <br/>
    <br/>
    <div class="col-lg-5 col-a">
        <img class="soporte-menu-img-int" src="/static/img/portal/soporte/soporte-i-abonos.png" />
        <br>
        <a href="/portal/soporte/abonos" class="a_selected"><span class="online-number online-numerselected">1</span>Solicitud de abono a tarjetas de otras empresas</a>
        <br>
        <a href="/portal/soporte/abonos2"><span class="online-number">2</span>Anticipo con bloqueo</a>
        <br>
        <a href="/portal/soporte/abonos3"><span class="online-number">3</span>Anticipo con extracupo</a>
        <br>
        <a href="/portal/soporte/abonos4"><span class="online-number">4</span>Activación de tarjetas para abono</a>
        <br>
    </div>
    <div class="col-lg-4">
        <form method="POST" >
            <label class="soporte-label">*Campos OBLIGATORIOS</label>
            <input type="text" style="padding-left:10px" class="textPat" name="telefono" placeholder="Teléfono de contacto*" value="" required><br>
            <input type="email" style="padding-left:10px" class="textPat" pattern="[A-Za-z0-9._%+-]{3,}@[A-Za-z]{3,}\.[A-Za-z]{2,}(?:\.[A-Za-z]{2,})?" name="correo" placeholder="Correo electrónico de contacto*" value="" required><br><br>
            <select style="width:100%;border-radius:10px;padding-left:10px" name='tdocumento' required>
                <option value="">Tipo de Documento*</option>
                <?php foreach ($tipoDocumento as $key => $value) { ?>
                <option value="<?= $value['PK_TD_CODIGO'] ?>"> <?= $value['NOMBRE'] ?></option>
                <?php } ?>
            </select><br>
            <input type="text" style="padding-left:10px" name="documento" class="textPat" placeholder="Número de Documento*" value="" required><br>
            <input type="text" style="padding-left:10px" name="prod" class="textPat" placeholder="Producto*" value="" required><br>
            <input type="number" style="padding-left:10px" name="tarjeta" class="numPat" placeholder="Número de tarjeta (últimos 4 digitos)*" value="" required><br>
            <input type="text" style="padding-left:10px" name="empresaComp" class="textPat" placeholder="Nombre de la empresa a compartir*" value="" required><br><br>
            <textarea rows="5" class="" style="width:100%;border-radius:10px;padding-left:10px"name="desc" maxlength="3500" placeholder="Descripción*" value="" required></textarea><br>

            <div class="row" style="padding-bottom: 50px">
                <div class="button col-sm-6 col-sm-push-3">
                    <button type="submit">
                        E N V I A R
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

