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
    <h2 style="color: #1C5394; padding-left:  1%;" class="titulo-iz">Tarjetas</h2>
    <br/>
    <br/>
    <div class="col-lg-5 col-a">
        <img class="soporte-menu-img-int" src="/static/img/portal/soporte/soporte-i-tarjetas.png" />
        <br>
        <a href="/portal/soporte/tarjetas" class="a_selected"><span class="online-number online-numerselected">1</span>Información general sobre el uso de las tarjetas</a>
        <br>
        <a href="/portal/soporte/tarjetas2"><span class="online-number">2</span>Cancelación definitiva de tarjeta</a>
        <br>
        <a href="/portal/soporte/tarjetas3"><span class="online-number">3</span>Solicitud de cancelación ó Modificación de pedido de tarjetas</a>
        <br>
        <a href="/portal/soporte/tarjetas4"><span class="online-number">4</span>Bloqueo preventivo de tarjeta</a>
        <br>
    </div>
    <div class="col-lg-4">
        <form method="POST" >
            <label class="soporte-label">*Campos OBLIGATORIOS</label>
            <input type="text" style="padding-left:10px" class="textPat"  name="telefono" placeholder="Teléfono de contacto*" required value=""><br>
            <input  type="email" pattern="[A-Za-z0-9._%+-]{3,}@[A-Za-z]{3,}\.[A-Za-z]{2,}(?:\.[A-Za-z]{2,})?" style="padding-left:10px" name="correo" placeholder="Correo electrónico de contacto*" required value=""><br><br>
            <textarea rows="5" class="" style="width:100%;border-radius:10px;padding-left:10px"name="desc" placeholder="Descripción*" required maxlength="3500" value=""></textarea><br>

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

