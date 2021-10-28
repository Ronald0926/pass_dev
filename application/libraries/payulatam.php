<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Payulatam {

    public function transaccion(
    $propietario_payu_transacciones, $autor_payu_transacciones, $description_payu_transacciones, $amount_payu_transacciones, $buyerEmail_payu_transacciones, 
            $billing_address_payu_transacciones, $telephone_payu_transacciones, $extra1_payu_transacciones, $extra2_payu_transacciones, $extra3_payu_transacciones
    ) {
        $CI = & get_instance();

        $configuracion = $CI->modelo->query("SELECT * FROM payu_configuracion");
        $configuracion = $configuracion[0];

        //valores estaticos
        $transaccion['id_payu_estados'] = 1; // 1 = Pendiente por respuesta de payulatam
        $transaccion['tax_payu_transacciones'] = '0'; // se envia vacio para que calcule 16%iva automatico
        $transaccion['tax_return_base_payu_transacciones'] = '0'; // se envia vacio y calcula la base sobre un 16% de iva
        
        //valores para la creación de la transaccion
        $transaccion['propietario_payu_transacciones'] = $propietario_payu_transacciones;
        $transaccion['autor_payu_transacciones'] = $autor_payu_transacciones;
        $transaccion['description_payu_transacciones'] = $description_payu_transacciones;
        $transaccion['amount_payu_transacciones'] = $amount_payu_transacciones;
        $transaccion['buyerEmail_payu_transacciones'] = $buyerEmail_payu_transacciones;
        $transaccion['billing_address_payu_transacciones'] = $billing_address_payu_transacciones;
        $transaccion['telephone_payu_transacciones'] = $telephone_payu_transacciones;
        $transaccion['extra1_payu_transacciones'] = $extra1_payu_transacciones;
        $transaccion['extra2_payu_transacciones'] = $extra2_payu_transacciones;
        $transaccion['extra3_payu_transacciones'] = $extra3_payu_transacciones;

        //obtengo el refenceCode
        $id_payu_transacciones = $CI->modelo->addPayu_transacciones($transaccion);
        $signature_payu_transacciones = md5("{$configuracion['api_key_payu_configuracion']}~{$configuracion['merchan_id_payu_configuracion']}~$id_payu_transacciones~$amount_payu_transacciones~{$configuracion['currency_payu_configuracion']}");
        $id_payu_transacciones = $CI->modelo->addPayu_transacciones(array(
            'id_payu_transacciones' => $id_payu_transacciones,
            'signature_payu_transacciones' => $signature_payu_transacciones
        ));
        return $id_payu_transacciones;
    }

}
