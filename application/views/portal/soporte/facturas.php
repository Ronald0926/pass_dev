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
        .hiddenFileInput > input{
        height: 100%;
        width: 100%;
        opacity: 0;
        cursor: pointer;
    }
    .hiddenFileInput{
        border: none;
        width: 50%;
        height: 40px;
        display: inline-block;
        overflow: hidden;
        margin-left: 25%;
        /*for the background, optional*/
        background: center center no-repeat;
        background-size: 100% 100%;
        background-image:  url(/static/img/portal/soporte/soporte-i-adjuntar.png);
    }
    .hiddenFileInput:hover{
        border: none;
        width: 50%;
        height: 40px;
        display: inline-block;
        overflow: hidden;
        margin-left: 25%;
        /*for the background, optional*/
        background: center center no-repeat;
        background-size: 100% 100%;
        background-image:  url(/static/img/portal/soporte/soporte-i-adjuntar-hover.png);
    }
</style> 

<div style="padding-left:  10%;padding-bottom:270px;margin-bottom:210px">
    <h2 style="color: #1C5394; padding-left:  1%;" class="titulo-iz">Facturas</h2>
    <br/>
    <br/>
    <div class="col-lg-5 col-a">
        <img class="soporte-menu-img-int" src="/static/img/portal/soporte/soporte-i-facturas.png" />
        <br>
        <a href="/portal/soporte/facturas" class="a_selected"><span class="online-number online-numerselected">1</span>Solicitud de revisión de los parámetros de impuestos comerciales</a>
        <br>
        <a href="/portal/soporte/facturas2"><span class="online-number">2</span>Anulación de factura</a>
        <br>
        <a href="/portal/soporte/facturas3"><span class="online-number">3</span>Reverso nota crédito</a>
        <br>
    </div>
    <div class="col-lg-4">
        <form method="POST" enctype="multipart/form-data">
            <label class="soporte-label">Es necesario diligenciar todos los campos</label>
            <input type="text" style="padding-left:10px" class="textPat" name="telefono" placeholder="Teléfono de contacto" value="" required><br><br>
            <input type="email" style="padding-left:10px" pattern="[A-Za-z0-9._%+-]{3,}@[A-Za-z]{3,}\.[A-Za-z]{2,}(?:\.[A-Za-z]{2,})?" name="correo" placeholder="Correo electrónico de contacto" value=""><br><br>
            <textarea rows="5" style="width:100%;border-radius:10px;padding-left:10px" name="desc" placeholder="Descripción" value="" maxlength="2000" required></textarea><br>
            <p>Por favor adjunte un soporte y/o RUT y/o Decreto reglamentario y/o resolución y/o Certificación de contador o revisor fiscal</p>
            <div class="row">
                <div class="button col-sm-12" style="text-aling:center">
                    <span class="hiddenFileInput">
                        <input type="file" name="file" required/>
                    </span>
                </div>
            </div>
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

