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
    <h2 style="color: #1C5394; padding-left:  1%;" class="titulo-iz">Comercial</h2>
    <br/>
    <br/>
    <div class="col-lg-5 col-a">
        <img class="soporte-menu-img-int" src="/static/img/portal/soporte/soporte-i-comercial.png" />
        <br>
        <a href="/portal/soporte/comercial/1" class="<?php if($pantalla == 1) echo 'a_selected';?>"><span class="online-number <?php if($pantalla == 1) echo 'online-numerselected';?>">1</span>Solicitud de modificación de condiciones comerciales</a>
        <br>
        <a href="/portal/soporte/comercial/2" class="<?php if($pantalla == 2) echo 'a_selected';?>"><span class="online-number <?php if($pantalla == 2) echo 'online-numerselected';?>">2</span>Solicitud de nueva empresa del grupo empresarial</a>
        <br>
        <a href="/portal/soporte/comercial/3" class="<?php if($pantalla == 3) echo 'a_selected';?>"><span class="online-number <?php if($pantalla == 3) echo 'online-numerselected';?>">3</span>Solicitud de asesoria comercial</a>
        <br>
    </div>
    <div class="col-lg-4">
        <form method="POST" >
            <label class="soporte-label">*Campos OBLIGATORIOS</label>
            <input type="text" style="padding-left:10px" class="textPat" name="telefono" placeholder="Teléfono de contacto*" value="" required><br><br>
            <input type="text" style="padding-left:10px" pattern="[A-Za-z0-9._%+-]{3,}@[A-Za-z]{3,}\.[A-Za-z]{2,}(?:\.[A-Za-z]{2,})?" name="correo" placeholder="Correo electrónico de contacto*" value="" required><br><br>
            <textarea rows="5" style="width:100%;border-radius:10px;padding-left:10px"name="desc" placeholder="Descripción*" value="" maxlength="3500" class="" required></textarea><br>

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

