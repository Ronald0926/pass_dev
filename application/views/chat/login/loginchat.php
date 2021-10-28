 <?php include 'templates/headerlogin.php'; ?>

 <body>
     <div class="container">
         <div class="panel panel-default">
             <div class="panel-heading">Chat Online 2</div>
             <div class="panel-body">
                 <p class="text-danger"><?php echo $message; ?></p>
                 <form method="post" action="loginController">
                     <div class="form-group">
                     <label>Seleccione el tipo de Documento</label>
                         <select class="form-control" required name="tipoDocumento">
                             <option value=""> Seleccione el tipo de Documento</option>
                             <?php foreach ($tipoDocumento as $key => $value) { ?>
                                 <option value="<?= $value['PK_TD_CODIGO'] ?>"> <?= $value['NOMBRE'] ?></option>
                             <?php } ?>
                         </select>
                     </div>
                     <div class="form-group">
                         <label>Ingrese Numero Documento</label>
                         <input type="text" name="documento" class="form-control" required />
                     </div>
                     <div class="form-group">
                         <label>Ingrese Contraseña</label>
                         <input type="password" name="pass" class="form-control" required />
                     </div>
                     <div class="form-group">
                         <input type="submit" name="login" class="btn btn-info" value="Iniciar Sesion" />
                     </div>
                 </form>
                 <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                 <!-- webslesson_mainblogsec_Blog1_1x1_as -->
                 <ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-4529508631166774" data-ad-host="ca-host-pub-1556223355139109" data-ad-host-channel="L0007" data-ad-slot="6573078845" data-ad-format="auto"></ins>
                 <script>
                     (adsbygoogle = window.adsbygoogle || []).push({});
                 </script>                 
             </div>
         </div>
     </div>

 </body>
 <?php include 'templates/footerlogin.php'; ?>