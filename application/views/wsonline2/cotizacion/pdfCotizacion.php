<html>
    <head>
        <style>
            body {
                background-image: url(../pdf/Imagenes/marca.jpg);
                background-size: 50;
                background-position: bottom left;
                background-repeat: no-repeat;
                background-image-resize: 4;
                background-image-resolution: from-image;
                text-align: justify;
                font-size: 10pt;
                font-family: Arial, Helvetica, sans-serif;
                /*font-family: sans-serif,serif,arial;*/
                /*font-family: sans-serif;*/
            }
            .firmaH4{
                margin-top: -25px;
                padding: 0px;
            }
            .table-border-0{
                width: 100%;
                border:0px;
                margin-left: -3px;
                padding: 0px;
            }
            .table-border-0 tr td{
                border:0px;
                margin: 0px;
                padding: 0px;
            }
            .table-border-1 tr td{
                border:1px;
                margin: 0px;
                padding: 0px; 
            }
            .table-border-1 tr td, .table-border-1 tr th{
                padding: 0;
                border: 1px solid #000;
                vertical-align: middle;
            }
            .text-10px{
                font-size: 11px !important;
            }
            th{
                background-color:#44546a;
                color: #FFF;
                font-weight: bold;
            }
            .otros {
                border: 0px solid white;
            }
            .otros td{
                border: 0px solid white;
            }
            ul.ul_medios li{
                padding-top:0.5px;
                line-height : 2.0;
            }
        </style>
    </head>
    <body>
        <div class="marcaAgua"> 

            <table class="table-border-0">
                <tr>
                    <td>
                        Bogot&aacute; D.C.,<?= date('d-m-Y') ?> 
                    </td>
<!--                    <td style="width: 235">
                        FO-GC-03 21/09/17 V2.0
                    </td>-->
                </tr>
            </table>

            <br/>
            <br/>
            <br/>

            <p>
                Se&ntilde;ores:
                <br/>
                <?= $empresa['RAZON_SOCIAL'] ?>
                <br/>
                <br/>
                <br/>
            </p>

            <br/>
            <br/>

            <div style="text-align: center;">
                <h3>Ref.: OFERTA COMERCIAL DE MEDIOS DE PAGO PEOPLEPASS -No. <?= $proceso ?></h3>
            </div>

            <div>
                <p>Reciba un cordial saludo, </p>

                <p>PeoplePass S.A. se esfuerza en establecer relaciones comerciales personalizadas y de largo plazo,
                    mediante la innovaci&oacute;n en soluciones tecnol&oacute;gicas y operativas integrales para contribuir con la motivaci&oacute;n
                    y el desarrollo de la calidad de vida de las personas.</p>

                <p> En este contexto, nos permitimos presentar nuestra oferta comercial sobre el servicio y bondades de las tarjetas peoplepass,
                    las cuales est&aacute;n dise&ntilde;adas para apoyar con eficiencia los procesos de compensaci&oacute;n, administrativos y de marketing,
                    entre otros. Estas tarjetas le permitir&aacute;n entregar a sus funcionarios y a terceros, bonos y vales electr&oacute;nicos.
                </p>

                <p> Nuestros productos le ayudar&aacute;n a cumplir con los criterios de seguridad, calidad, eficiencia,
                    costo y oportunidad que su compa&ntilde;&iacute;a necesita o desea mejorar, con la garant&iacute;a de contar con los mayores
                    est&aacute;ndares de seguridad de la industria.Estamos seguros de que podremos apoyar y
                    solventar de una forma contundente sus requerimientos. </p>

                <br/>
                <br/>

                <p>Le agradecemos su atenci&oacute;n.<P>

                <p>  Cordialmente,</p>
                <br/>
                <br/>
                <br/>
                <br/>


                <div>   
                    <img src="../pdf/Imagenes/Firma.png" width="200"/>                   
                    <h4 class="firmaH4">SANDRA GONZALEZ</h4>
                    <h6>Gerente Comercial</h6>

                </div>
                <br/>
                <br/>
            </div> <!-- Portada -->


            <div><!--pagina PRODUCTOS  -->
                <h4> NUESTROS PRODUCTOS</h4>
                <p>Nuestra experiencia de m&aacute;s de 11 a&ntilde;os y la confianza de nuestros clientes nos convierten en un proveedor
                    de soluciones con un servicio altamente eficaz y oportuno, respaldado por la mejor tecnolog&iacute;a que nos
                    permite dise&ntilde;ar los siguientes productos:</p>
                <div> <!--linea beneflex  -->

                    <br/>

                    <table class="table-border-0">
                        <tr>
                            <td style="width: 120px;"><img src="../pdf/Imagenes/beneflex.png" width="110"></td>
                            <td style="text-align: justify;">Nuestra l&iacute;nea de productos Beneflex est&aacute; especialmente dise&ntilde;ada para asistirle y brindarle una efectiva y
                                eficiente soluci&oacute;n para cada una de las necesidades de la empresa,
                                permitiendo enriquecer sus ofertas laborales con planes de beneficios para sus colaboradores.</td>
                        </tr>
                    </table>

                    <br/>
                    <br/>

                    <p style="margin-left: 50px">
                        - &nbsp; Tarjeta Market
                        <br/>
                        - &nbsp; Tarjeta Bienestar
                        <br/>
                        - &nbsp; Tarjeta Bienestar Salud
                        <br/>
                        - &nbsp; Tarjeta Combustible
                        <br/>
                        - &nbsp; Tarjeta Vestuario
                    </p>
                </div>
                <div><!--linea pasarela  -->
                    <br/>
                    <br/>
                    <table class="table-border-0">
                        <tr>
                            <td style="width: 120px;"><img src="../pdf/Imagenes/pasarela.png" width="110"></td>
                            <td style="text-align: justify;">La mejor opci&oacute;n en la administraci&oacute;n de pagos por incentivos, premios y bonificaciones con tarjetas recargables
                                y de una sola carga que facilitan la operaci&oacute;n y brindan control y seguridad para su organizaci&oacute;n. Con los productos
                                de la l&iacute;nea Pasarela premie y reconozca la labor de todos los impulsos comerciales que le permiten
                                motivar e incentivar su productividad.</td>
                        </tr>
                    </table>

                    <br/>
                    <br/>

                    <p>
                        - &nbsp; Tarjeta Premio
                        <br/>
                        - &nbsp; Tarjeta Premio Plus
                        <br/>
                        - &nbsp; Tarjeta Zafiro
                        <br/>
                        - &nbsp; Tarjeta Zafiro Plus
                    </p>
                </div>
                <div><!--linea business  -->
                    <br/>
                    <br/>
                    <table class="table-border-0">
                        <tr>
                            <td style="width: 120px;"><img src="../pdf/Imagenes/business.png" width="110"></td>
                            <td style="text-align: justify;">Es la &uacute;nica l&iacute;nea de productos creada para desnaturalizar los gastos corporativos,
                                evitando hacer pagos o giros a las cuentas de sus colaboradores,
                                facilitando la justificaci&oacute;n y administraci&oacute;n de los recursos entregados para el cumplimiento de sus labores.</td>
                        </tr>
                    </table>
                    <br/>
                    <br/>
                    <p>
                        - &nbsp; Tarjeta Medios de Transporte
                        <br/>
                        - &nbsp; Tarjeta Gastos de Viaje
                        <br/>
                        - &nbsp; Tarjeta Gastos de Representaci&oacute;n
                        <br/>
                        - &nbsp; Tarjeta Caja Menor
                        <br/>
                        - &nbsp; Tarjeta Business Car - Gasolina
                        <br/>
                        - &nbsp; Tarjeta Gastos Corporativos
                        <br/>
                        - &nbsp; Tarjeta Gastos de Legalización
                    </p>
                </div>


                <br/>
                <br/>
            </div> <!--pagina PRODUCTOS  -->


            <div> <!--pagina caracteristicas medios de pago  -->
                <h3> CARACTERÍSTICAS DE LOS MEDIOS DE PAGO  </h3>
                <ul class='ul_medios'>
                    <li>La entrega de las tarjetas ser&aacute; en 5 d&iacute;as h&aacute;biles despu&eacute;s de solicitadas en PassOnline.</li>
                    <li>Podr&aacute; hacer pago de los productos mediante los siguientes medios: Punto PSE en PassOnline,
                        transferencia electr&oacute;nica, consignaci&oacute;n bancaria  y/o cheque en consignaci&oacute;n.</li>
                    <li>Los abonos tendr&aacute;n un tiempo m&aacute;ximo de 24 horas para ser aplicados debido a los tiempos de identificaci&oacute;n de los pagos.</li>
                    <li>Los medios de pago son emitidos por una entidad vigilada por la Superintendencia Financiera de Colombia.</li>
                    <li>Los medios de pago pueden ser recargables o no recargables de acuerdo a las caracter&iacute;sticas de cada producto.</li>
                    <li>Los medios de pago tienen aceptaci&oacute;n total a nivel nacional e internacional en los establecimientos con datafono
                        vinculado a la franquicia VISA y en la red de cajeros automaticos, seg&uacute;n los par&aacute;metros del producto.</li>
                    <li>Los medios de pago permiten acumular saldos y estos no se vencen.</li>
                    <li>Los medios de pago admiten pagos exactos.</li>
                    <li>Los medios de pago son vigilados y monitoreados transaccionalmente, como también cumpliendo el SARLAFT.</li>
                    <li>Los tarjetahabientes de los medios de pago cuentan con los siguientes canales de atención: App (IOS, Android, Huawei), 
                        Portal web a través del sitio web <a href="http://www.peoplepass.com.co" target="_blank" rel="noopener noreferrer">www.peoplepass.com.co </a> o 
                        telefónicamente a través de nuestra línea de atención 7 x 24 en Bogotá en el 329 75 00 y a nivel nacional en el 01 8000 127 771.</li>
                    <li>Canales de atención Empresarial: e-Mail soporte.cliente@peoplepass.com.co, Telefónico (071)7434700 opc. 1 (L - V de 7:00 a.m., a 5:00 p.m., S de 8:00 a.m., a 12:00 m.)</li>
                    <li>Para usar los medios de pago en dat&aacute;fonos y cajeros autom&aacute;ticos se debe seleccionar la opci&oacute;n de tarjeta d&eacute;bito y cuenta de ahorros.</li>
                    <li>El primer env&iacute;o de tarjetas del mes a un &uacute;nico destino es gratuito.</li>
                    <li>El monto m&aacute;ximo de retiro diario en la red de cajeros automaticos es de $1.200.000.</li>
                    <li>El monto m&aacute;ximo de compras diarias en la red de dat&aacute;fonos es de $2.000.000.</li>
                </ul>
            </div> <!--pagina caracteristicas medios de pago  -->
            <br/>
            <div>
                <h3>SERVICIOS ADICIONALES DE VALOR AGREGADO  </h3>
                <ul>
                    <li> Notificación empresarial de abono de facturas por SMS y/o Email (Coordinador de la campaña) en el momento que se realice la dispersión de la factura a las tarjetas programadas.</li>
                    <br/>
                    <li> Notificaciones transaccionales por SMS y/o Email para los usuarios de tarjetas Peoplepass de la empresa cliente, en el momento que existan utilizaciones de abonos, compras, retiros.</li>
                    <br/>
                    <li> Llave maestra, Servicio empresarial el cual permite gestionar y controlar los dineros corporativos mediante grupos de trabajo (llaveros) de esta forma se automatizan procesos de recargas de la cuenta, cargas de tarjetas, consultas saldos, reversos, devoluciones y legalizaciones a través de nuestros canales como la App y el portal empresarial sin requerir la intervención operativa sin restricciones de tiempo 7 x 24. </li>
                </ul>
            </div>
            <br/>
            <div> <!-- modelo funcional -->
                <h3>MODELO FUNCIONAL PEOPLEPASS</h3>
                <p>Peoplepass, como parte de su propuesta de valor, ha dise&ntilde;ado un modelo funcional de medios
                    de pago que le permitir&aacute; realizar transacciones a la empresa y sus colaboradores, con el fin
                    de apoyar los procesos de administraci&oacute;n, compensaci&oacute;n y motivaci&oacute;n de la compa&ntilde;&iacute;a:</p>

                <div style="text-align: center;">
                    <img src="../pdf/Imagenes/empresa.jpg" width="80%">
                </div>
                <br/>

                <h4>VALORES AGREGADOS DE LA OFERTA</h4>
                <ul>
                    <li>
                        Administraci&oacute;n sin costo del portal empresa 	
                        <img src="../pdf/Imagenes/passonline.jpg" style="margin-bottom: -17px; width:90px"> , App (IOS, Android y Huawei) y portal usuario.</li>
                    <br>
                    <li>Consulta de saldos sin costo a trav&eacute;s de nuestros. </li>
                    <br>
                    <li>Los medios de pago permiten realizar transacciones en comercio electrónico (Internet). </li>
                    <br>
                    <li>Los bonos no tienen cuota de manejo.</li>
                    <br>
                    <li>Es posible la reexpedición o reposición de tarjetas, conservando su saldo.</li>
                    <br>
                    <li>Los medios de pago tendrán acceso a las promociones que disponga la franquicia VISA.</li>
                    <br>
                    <li>Con el fin de apoyar su gestión contable, las facturas que recibirá manejaran los siguientes conceptos, los cuales se discriminan a continuación:</li>
                    <ol>
                        <li>La cantidad de tarjetas y el valor de la generación de estas.</li>
                        <li>La cantidad y valor de los servicios adquiridos.</li>
                        <li>El valor de sus abonos y la comisión del servicio de administración.</li>
                        <li>El valor de las notificaciones de abonos.</li>
                        Junto con el correspondiente IVA.  Los impuestos y retenciones derivados de la generación de los medios de pago son administrados y ejecutados por el cliente.
                    </ol>
                </ul>
            </div> <!-- modelo funcional -->
            <br/> 
            <div> <!-- valores de la cotizacion  -->
                <h3>VALOR OFERTA COMERCIAL -No. <?= $proceso ?>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  Fecha:<?= date('d-m-Y') ?></h3>
                <p>Los valores descritos a continuación están determinados por las cantidades requeridas por el cliente.</p>
                <div style="text-align: center;">
                    <table cellspacing="0" class="table-border-1" style="text-align: center; font-size: 10px;width: 75%;margin: 0 auto;"  >
                        <tr>
                            <th>Servicios </th>
                            <th>Cantidad</th>
                            <th>Valor Unitario</th>
                            <th>Valor iva 19%</th>
                            <th>Valor Total</th>
                        </tr>
                        <?php foreach ($servicios as $key => $value) { ?>
                            <tr>
                                <td><?= $value['PARAMETRO'] = 'LLAVE MAESTRA' ? 'SERVICIO ' . $value['PARAMETRO'] : $value['PARAMETRO'] ?></td>
                                <td><?= $value['CANTIDAD'] ?></td>
                                <td>$ <?= number_format($value['TOTAL'] , 0, ',', '.') ?></td>
                                <td>$ <?= number_format($value['IVA'], 0, ',', '.') ?></td>
                                <td>$ <?= number_format($value['TOTAL']+$value['IVA'], 0, ',', '.') ?></td>
                            </tr>
                            <?php
                            $sTarjetas += $value['CANTIDAD'];
                            $sTarjetasV += $value['TOTAL'] ;
                            $sIva += $value['IVA'];
                            $sTotal += $value['TOTAL']+$value['IVA'];
                            ?>
                        <?php } ?>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <th>SUBTOTAL</th>
                            <td style='font-weight: bold;'><?= number_format($sTarjetas, 0) ?></td>
                            <td style='font-weight: bold;'>$ <?= number_format($sTarjetasV, 0) ?></td>
                            <td style='font-weight: bold;'>$ <?= number_format($sIva, 0) ?></td>
                            <td style='font-weight: bold;'>$ <?= number_format($sTotal, 0) ?></td>
                        </tr>
                        <tr>
                            <th>TOTAL GENERAL</th>
                            <td colspan='4'  style='font-weight: bold;'>$ <?= number_format(( $sTarjetasV + $sIva), 0) ?></td>
                        </tr>
                    </table>  
                </div>
                <ul>
                    <li>El servicio de notificación de abonos empresarial y usuario, es un servicio que se incluye en la negociación inicial por un valor mensual de $15.000.</li>
                    <li>
                        El servicio de notificaciones transaccionales es un servicio adicional que varía en función de la cantidad de tarjetas activas que tenga el cliente por un valor mensual.
                    </li>
                    <li>
                        El servicio de Llave maestra es un servicio adicional que se incluye cuando sea requerido por el cliente empresa, por un valor mensual de $50.000, este valor no incluye los movimientos transaccionales internos del proceso Llave Maestra.
                    </li>
                </ul>
                <br/>
                <table cellspacing="0" class="table-border-1" style="text-align: center; font-size: 10px"  >
                    <tr>
                        <th>Producto </th>
                        <th>Cant. tarjetas</th>
                        <th>Costo tarjeta</th>
                        <th>Valor total tarjetas</th>
                        <th>Abono a realizar</th>
                        <th>% Serv. Admin.</th>
                        <th>Valor Serv. Admin.</th>
                        <th>Valor IVA 19%</th>
                    </tr>
                    <?php
                    $cantlineas = 0;
                    foreach ($dataCotizacion as $key => $value) {
                        ?>
                        <tr>
                            <td><?= $value['PARAMETRO'] ?></td>
                            <td><?= number_format($value['CANTIDAD']) ?></td>
                            <td>$ <?= number_format($value['VALOR'], 0, ',', '.'); ?></td>
                            <td>$ <?= number_format($value['TOTAL'], 0, ',', '.'); ?></td>
                            <td>$ <?= number_format($value['VALOR_ABONO'], 0, ',', '.'); ?></td>
                            <?php if ($value['TASA'] > 100) { ?>
                                <td>$ <?= number_format($value['TASA']) ?> por Cada Dispersi&oacute;n</td>
                            <?php } else { ?>
                                <td><?= $value['TASA'] ?> %</td>
                            <?php } ?>
                            <td>$ <?= number_format($value['COSTO_ADMINISTRACION'], 0, ',', '.'); ?></td>
                            <td>$ <?= number_format($value['IVA'] + ($value['COSTO_ADMINISTRACION'] * 0.19)) ?></td>

                        </tr>
                        <?php
                        $tTarjetas += $value['CANTIDAD'];
                        $tTarjetasV += $value['TOTAL'];
                        $tAbono += $value['VALOR_ABONO'];
                        $tAdmin += $value['COSTO_ADMINISTRACION'];
                        $tIva += $value['IVA'] + ($value['COSTO_ADMINISTRACION'] * 0.19);
                        ?>
                        <?php
                        $cantlineas++;
                    }
                    ?>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <th>SUBTOTAL</th>
                        <td style='font-weight: bold;'><?= number_format($tTarjetas, 0) ?></td>
                        <td style='background-color: #777'></td>
                        <td style='font-weight: bold;'>$ <?= number_format($tTarjetasV, 0) ?></td>
                        <td style='font-weight: bold;'>$ <?= number_format($tAbono, 0) ?></td>
                        <td style='background-color: #777'></td>
                        <td style='font-weight: bold;'>$ <?= number_format($tAdmin, 0) ?></td>
                        <td style='font-weight: bold;'>$ <?= number_format($tIva, 0) ?></td>
                    </tr>
                    <tr>
                        <th>TOTAL GENERAL</th>
                        <td colspan='7'  style='font-weight: bold;'>$ <?= number_format(($tTarjetasV + $tAbono + $tAdmin + $tIva), 0) ?></td>
                    </tr>
                </table>
                <p class="text-10px" style="margin-top:-1px;padding-left: 6px;padding-right: 6px">
                    El valor total de la oferta comercial corresponde a la suma del total general de las siguientes tablas de servicios y productos.
                </p>
                <br/>
                <ul>
                    <li>El cálculo del IVA se calcula con base a: 1. el valor total de las tarjetas, 2. más el valor total del servicio de administración, y 3. más el valor total de los servicios.</li>
                    <br>
                    <li>La vigencia de la oferta de es de 90 días a partir de la fecha, una vez pasado este tiempo se evaluará la posibilidad de mantener o actualizar los valores.</li>
                    <br>
                    <li>Peoplepass S.A.  se reserva el derecho de realizar cambios al valor de la presente oferta una vez se compruebe que las anteriores proyecciones propuestas por el cliente no han sido cumplidas.</li>
                    <br>
                    <li>El valor de las tarjetas podrá ser modificado de acuerdo con los costos de creación de las tarjetas, la personalización de las mismas y las condiciones de mercado.</li>
                    <br>
                    <li>Con la recepción de la oferta, usted acepta el tratamiento de datos personales conforme a lo contenido en nuestra “Política de Tratamiento de Datos Personales” la cual se encuentra en la página web <a href="https://www.peoplepass.com.co/descargas" target="_blank" rel="noopener noreferrer">www.peoplepass.com.co/descargas</a>.</li>
                    <br>
                    <li>Toda la información contenida en la presente oferta comercial es de carácter confidencial de las partes y no podrá ser comunicada a terceros ajenos a la negociación o empresas que desempeñen actividades iguales, similares o conexas a las de Peoplepass S.A., so pena de adelantar las acciones legales pertinentes.</li>
                    <br>
                    <li>Los formatos solicitados por el ejecutivo comercial deberán ser remitidos debidamente firmados y en original para la posterior creación como cliente, la cual tomará cinco (5) días hábiles contados desde la recepción de todos los documentos.</li>
                    <br>
                    <li>Las notificaciones (mensajes de texto a su celular o a su email), se realizarán con base en los datos suministrados en el momento de la activación de las tarjetas para usuarios y activación del portal passonline a nivel empresarial, Peoplepass no se hace responsable de la veracidad de esta información como también de las consecuencias derivadas de una información errónea o no suministrada en debida forma a través de los canales dispuestos por Peoplepass.</li>
                    <br>
                    <li>La firma de todos los documentos se hará por medio de firma electrónica a través de la herramienta tecnológica designada por Peoplepass, conforme a lo contenido en la ley 527 de 1999 y Decreto 1747 de 2000.</li>
                </ul>
                <?php if (($cantlineas >= 8 && $cantlineas <= 15) || $cantlineas > 15) { ?>
                    <!--<br/>
                    <br/>
                    <br/> 06-07-2020-->
                <?php } else { ?>
                    <!-- <br/>
                     <br/>
                     <br/>
                     <br/>
                     <br/>
                     <br/>
                     <br/> 06-07-2020-->
                <?php } ?>

            </div> <!-- valores de la cotizacion  -->

            <!--<br/>
            <br/>
            <br/>
            <br/>
            <br/>
            <br/> 06-07-2020 -->
            <div > <!-- Otros costos asociados-->

                <h4>  OTROS COSTOS ASOCIADOS </h4>

                <p>Los costos relacionados a continuaci&oacute;n no est&aacute;n incluidos en la comisi&oacute;n de servicio
                    de administraci&oacute;n y en caso de causarse, estar&aacute;n a cargo del cliente o el beneficiario
                    dependiendo del caso, as&iacute;:</p>
                <table>
                    <tr>
                        <td width="87%">
                    <li>	Env&iacute;os adicionales (Urbanos y rurales) - Cliente - Usuario<sup>1</sup></li>
                    </td> 
                    <td width="13%">
                        $ 13.000
                    </td> 
                    </tr>
                    <tr>
                        <td>
                    <li>	Reexpedici&oacute;n de tarjeta al tarjetahabiente<sup>1</sup></li>
                    </td>
                    <td>
                        $ 15.000
                    </td>  
                    </tr>
                    <tr>
                        <td>
                    <li>Cambio de PIN (clave) en cajero Servibanca<sup>1</sup></li>
                    </td> 
                    <td>
                        $   2.200
                    </td> 
                    </tr>
                    <tr>
                        <td>
                    <li>Costos por retiro de efectivo en cajeros automaticos tarjetahabientes<sup>2</sup><span style="font-size: 8pt;">-Nal-</span></li>
                    </td> 
                    <td>
                        $   3.540
                    </td> 
                    </tr>
                    <tr>
                        <td>
                    <li>Costos por retiro de efectivo en cajeros automaticos tarjetahabientes<sup>3</sup><span style="font-size: 8pt;">-Internal-</span></li>
                    </td> 
                    <td>$ 12.000</td> 
                    </tr>
                    <tr>
                        <td>
                    <li>Costos por consultas de saldo en cajeros automaticos tarjetahabientes<sup>2</sup><span style="font-size: 8pt;">-Nal-</span></li>
                    </td>  
                    <td>
                        $   3.540
                    </td> 
                    </tr>
                    <tr>
                        <td>
                    <li>Costos por retiro de efectivo en cajeros automaticos tarjetahabientes<sup>3</sup><span style="font-size: 8pt;">-Internal-</span></li>
                    </td> 
                    <td>$ 12.000</td> 
                    </tr>
                    <tr>
                        <td>
                            <li>Transacci&oacute;n no exitosa (Fondos insuficientes - Pin Errado)<sup>1</sup> </li>
                        </td>
                        <td>
                            $   3.540
                        </td> 
                    </tr>
                    <tr>
                        <td>
                            <li>Devolución de fondos por tarjeta – Movimiento transaccional interno (Llave maestra) </li>
                        </td>
                        <td>
                        $   200
                        </td>
                    </tr>
                    <tr>
                    <td>
                        <li>Reverso de fondos por tarjeta – Movimiento transaccional interno (Llave maestra) </li>
                    </td>
                    <td>
                        $   200
                    </td>
                    </tr>
                </table>
                <br>
                <p class="text-10px">
                    <sup>1</sup>El servicio aplica para el servicio de tarjetas empresariales que hayan sido otorgadas por la empresa cliente. Los anteriores valores podrán ser actualizados por Peoplepass en cualquier momento durante la prestación del servicio. Esta notificación de aumento o disminución de las tarifas estará disponible en el portal web del tarjetahabiente. Estas modificaciones obedecen a la variación de las condiciones económicas del mercado que impactan la operación.
                    <br>
                    <br>
                    <sup>2</sup>Estos valores son establecidos por la red de cajeros, que ser&aacute; descontado del saldo de la tarjeta para cada
                    transacci&oacute;n procesada. Este servicio aplica para los bonos Bienestar, Zafiro plus, Premio Plus, Tarjeta Medios de
                    Transporte, Tarjeta gastos de viaje, Tarjeta Gastos de Representaci&oacute;n, Tarjeta Caja Menor,
                    Tarjeta Business Car – Gasolina, Tarjeta Gastos Corporativos, Tarjeta Gastos de Legalizaci&oacute;n.
                    <br>
                    <br>
                    <sup>3</sup>Estos valores son aproximados y establecidos por la red de cajeros internacional independiente mente de los costos nacionales,
                    que será descontado del saldo de la tarjeta para cada transacción procesada. Este servicio aplica para los bonos Bienestar, Zafiro plus,
                    Premio Plus, Tarjeta Medios de Transporte, Tarjeta Gastos de Viaje, Tarjeta Gastos de Representación, Tarjeta Caja Menor, Tarjeta Business Car – Gasolina,
                    Tarjeta Gastos Corporativos, Tarjeta Gastos de Legalización.
                </p>
            </div>

           
           
            <?php  if ($cantlineas >= 1 && $cantlineas <= 5) { ?>

                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <div>
                    <p class="text-10px" >
                        Derechos Reservados 2011, PEOPLEPASS S.A.<br>
                        No est&aacute; permitida la reproducci&oacute;n total o parcial de este documento, ni su tratamiento inform&aacute;tico, ni la transmisi&oacute;n de ninguna forma o por cualquier medio, ya sea electr&oacute;nico o mec&aacute;nico, por fotocopia, por registro u otros m&eacute;todos, sin el permiso previo y por escrito de los titulares.
                    </p>
                    <p style="text-align:right;" class="text-10px">People Pass S.A<br>
                        Bogot&aacute;, Colombia, Sur Am&eacute;rica<br>
                    </p>
                </div>
            <?php } elseif ($cantlineas >= 6 && $cantlineas <= 10) { ?>

                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <div>
                    <p class="text-10px" >
                        Derechos Reservados 2011, PEOPLEPASS S.A.<br>
                        No est&aacute; permitida la reproducci&oacute;n total o parcial de este documento, ni su tratamiento inform&aacute;tico, ni la transmisi&oacute;n de ninguna forma o por cualquier medio, ya sea electr&oacute;nico o mec&aacute;nico, por fotocopia, por registro u otros m&eacute;todos, sin el permiso previo y por escrito de los titulares.
                    </p>
                    <p style="text-align:right;" class="text-10px">People Pass S.A<br>
                        Bogot&aacute;, Colombia, Sur Am&eacute;rica<br>
                    </p>
                </div>
                <?php } elseif(($cantlineas >= 11 && $cantlineas <= 15)){ ?>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <div>
                    <p class="text-10px" >
                        Derechos Reservados 2011, PEOPLEPASS S.A.<br>
                        No est&aacute; permitida la reproducci&oacute;n total o parcial de este documento, ni su tratamiento inform&aacute;tico, ni la transmisi&oacute;n de ninguna forma o por cualquier medio, ya sea electr&oacute;nico o mec&aacute;nico, por fotocopia, por registro u otros m&eacute;todos, sin el permiso previo y por escrito de los titulares.
                    </p>
                    <p style="text-align:right;" class="text-10px">People Pass S.A<br>
                        Bogot&aacute;, Colombia, Sur Am&eacute;rica<br>
                    </p>
                </div>
            <?php } elseif(($cantlineas >= 16 && $cantlineas <= 20)){ ?>
                
                
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                <div>
                    <p class="text-10px" >
                        Derechos Reservados 2011, PEOPLEPASS S.A.<br>
                        No est&aacute; permitida la reproducci&oacute;n total o parcial de este documento, ni su tratamiento inform&aacute;tico, ni la transmisi&oacute;n de ninguna forma o por cualquier medio, ya sea electr&oacute;nico o mec&aacute;nico, por fotocopia, por registro u otros m&eacute;todos, sin el permiso previo y por escrito de los titulares.
                    </p>
                    <p style="text-align:right;" class="text-10px">People Pass S.A<br>
                        Bogot&aacute;, Colombia, Sur Am&eacute;rica<br>
                    </p>
                </div>
             <?php } ?>  
        </div>
    </body>
</html>
