$(document).ready(function() {

    /*  deshabiliatar  boton al adra  submit*/


    /*$("#formSolicitudUno").submit(function(e) {
        console.log('aa')
            //stop submitting the form to see the disabled button effect
        e.preventDefault();

        //disable the submit button
        $("#btnSubmitSolicitudUno").attr("disabled", true);


        return true;

    });*/


    /*   $('.dataTableAbonos').DataTable({
     dom: 'Bfrtip',
     buttons: [
     'copy', 'csv', 'excel', 'pdf', 'print'
     ]
     });*/
    /*
     * Pattern
     */
    $(".moneda").attr("pattern", "^\$\d{1,3}(,\d{3})*(\.\d+)?$");
    $(".numPat").attr("pattern", "[0-9]+");
    $(".textPat").attr("maxlength", "32");
    $(".textPatDir").attr("maxlength", "150");
    $(".textPatSt").attr("maxlength", "50");
    $(".numPat").attr("maxlength", "32");
    $(".correoPat").attr("pattern", "[A-Za-z0-9._%+-]{3,}@[A-Za-z]{3,}\.[A-Za-z]{2,}(?:\.[A-Za-z]{2,})?");
    $(".textPatCc").attr("minlength", "7");
    $(".textPatCc").attr("maxlength", "32");

    jQuery('.textPatDir').keypress(function(tecla) {
        if (tecla.charCode >= 33 && tecla.charCode <= 47 || tecla.charCode >= 58 && tecla.charCode <= 64 || tecla.charCode >= 91 && tecla.charCode <= 96 ||
            tecla.charCode >= 123 && tecla.charCode <= 254) return false;
    });

    jQuery('.textPat').keypress(function(tecla) {
        if (tecla.charCode >= 33 && tecla.charCode <= 47 || tecla.charCode >= 58 && tecla.charCode <= 64 || tecla.charCode >= 91 && tecla.charCode <= 96 ||
            tecla.charCode >= 123 && tecla.charCode <= 254) return false;
    });

    jQuery('.textPatSt').keypress(function(tecla) {
        if (tecla.charCode >= 33 && tecla.charCode <= 64 || tecla.charCode >= 58 && tecla.charCode <= 64 || tecla.charCode >= 91 && tecla.charCode <= 96 ||
            tecla.charCode >= 123 && tecla.charCode <= 254)
            return false;
    });
    jQuery('.textPatStNum').keypress(function(tecla) {
        if (tecla.charCode >= 33 && tecla.charCode <= 47 || tecla.charCode >= 58 && tecla.charCode <= 64 || tecla.charCode >= 91 && tecla.charCode <= 96 ||
            tecla.charCode >= 123 && tecla.charCode <= 254)
            return false;
    });
    jQuery('.numPat').keypress(function(tecla) {
        if (tecla.charCode < 48 || tecla.charCode > 57)
            return false;

    });


    jQuery('.textPatObserFactura').keypress(function(tecla) {
        if (tecla.charCode >= 58 && tecla.charCode <= 64 || tecla.charCode >= 91 && tecla.charCode <= 96 ||
            tecla.charCode >= 123 && tecla.charCode <= 254 || tecla.charCode >= 60 && tecla.charCode <= 62 || tecla.charCode === 38 || tecla.charCode === 36) return false;
    });

    /*jQuery('.textPatCc').keypress(function(tecla) {
        if (tecla.charCode >= 32 && tecla.charCode <= 34 || tecla.charCode >= 39 && tecla.charCode <= 47 || tecla.charCode >= 58 && tecla.charCode <= 64 ||
            tecla.charCode >= 91 && tecla.charCode <= 96||tecla.charCode >= 123 && tecla.charCode <= 255)
            return false;
    });*/
    /*
     * FUNCIONALIDAD SELECT
     */
    $('.select').find('select').change(function() {
        $(this).parent().find('div').html(
            $(this).find(':selected').html()
        );
    });
    /*
     * FUNCIONALIDAD CHECKBOX
     */
    $('.login-checkbox').click(function() {
        if ($(this).find('.login-checkbox-check').css('display') == 'none') {
            $(this).find('.login-checkbox-check').show(100);
            $(this).find('input[type=checkbox]').prop('checked', true);
        } else {
            $(this).find('.login-checkbox-check').hide(100);
            $(this).find('input[type=checkbox]').prop('checked', false);
        }
    });
    /*
     * 
     * 
     * 
     * HEADER PERFIL
     * 
     * 
     * 
     */
    $('.header-user').click(function() {
        $('.perfil-dropdown-help').hide(150);
        $('.perfil-help').hide(150);
        if ($('.perfil-dropdown').css('display') == "none") {
            $('.header-user').css("background-image", "url(/static/img/portal/menu/usuario-hover.jpg)");
            $('.perfil-dropdown').show(150);
            if ($('.notificacion').css('display') == "block") {
                $('.header-bell').css("background-image", "");
                $('.notificacion').hide(150);
                $('.puntero-notificacion').hide(150);
            }
        } else {
            $('.header-user').css("background-image", "");
            $('.perfil-dropdown').hide(150);
        }
    });
    setInterval(function() {
        mantener(sessionStorage.getItem('clave'));
    }, 600000);

    /*
     * 
     * 
     * 
     * HEADER TUTORIAL
     * 
     * 
     * 
     */
    $('.header-help').click(function() {
        $('.perfil-dropdown').hide(150);
        if ($('.perfil-dropdown-help').css('display') == "none") {
            $('.perfil-help').show(150);
            $('.perfil-dropdown-help').show(150);
            if ($('.notificacion').css('display') == "block") {
                $('.header-bell').css("background-image", "");
                $('.notificacion').hide(150);
                $('.puntero-notificacion').hide(150);
            }
        } else {
            $('.perfil-help').hide(150);
            $('.header-help').css("background-image", "");
            $('.perfil-dropdown-help').hide(150);
        }
    });
    setInterval(function() {
        mantener(sessionStorage.getItem('clave'));
    }, 600000);

    /**
     *
     * VIDEOS  
     *  
     */
    $("#vOne").attr("controlsList", "nodownload");
    $("#vTwo").attr("controlsList", "nodownload");
    /*
     abonos
     */
    //    $('body').on('click', '#total span', function () {
    //        var y = $('.tCantidad');
    //        for (i = 1; i < y.length; i++) {
    //            y[i].value = y[0].value;
    //        }
    //
    //    });
    //    $('body').on('click', '#totalF span', function () {
    //        var x = $('.tFecha');
    //        for (j = 1; j < x.length; j++) {
    //            x[j].value = x[0].value;
    //        }
    //
    //    });

    $('.buttonDetail').each(function() {
        var urlDetail = $(this).attr("href").replace(/\*/g, '%20');
        $(this).attr("href", urlDetail);
    });


    $(".alert").fadeTo(3000, 100).slideUp(2000, function() {
        $(".alert").slideUp(300);
    });

    //formato moneda input 
    $("input[data-type='currency']").on({
        keyup: function() {
            formatCurrency($(this));
        },
        blur: function() {
            formatCurrency($(this), "blur");
        }
    });


    function formatNumber(n) {
        // format number 1000000 to 1,234,567
        return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
    }


    function formatCurrency(input, blur) {
        // appends $ to value, validates decimal side
        // and puts cursor back in right position.

        // get input value
        var input_val = input.val();

        // don't validate empty input
        if (input_val === "") {
            return;
        }

        // original length
        var original_len = input_val.length;

        // initial caret position 
        var caret_pos = input.prop("selectionStart");

        // check for decimal
        if (input_val.indexOf(".") >= 0) {

            // get position of first decimal
            // this prevents multiple decimals from
            // being entered
            var decimal_pos = input_val.indexOf(".");

            // split number by decimal point
            var left_side = input_val.substring(0, decimal_pos);
            var right_side = input_val.substring(decimal_pos);

            // add commas to left side of number
            left_side = formatNumber(left_side);

            // validate right side
            right_side = formatNumber(right_side);

            // On blur make sure 2 numbers after decimal
            if (blur === "blur") {
                right_side += "00";
            }

            // Limit decimal to only 2 digits
            right_side = right_side.substring(0, 2);

            // join number by .
            input_val = "$" + left_side + "." + right_side;

        } else {
            // no decimal entered
            // add commas to number
            // remove all non-digits
            input_val = formatNumber(input_val);
            input_val = "$" + input_val;

            // final formatting
            if (blur === "blur") {
                input_val += ".00";
            }
        }

        // send updated string to input
        input.val(input_val);

        // put caret back in the right position
        var updated_len = input_val.length;
        caret_pos = updated_len - original_len + caret_pos;
        input[0].setSelectionRange(caret_pos, caret_pos);
    }
});

function notifications() {

    if ($('.notificacion').css('display') == "none") {
        $('.header-bell').css("background-image", "url(/static/img/portal/menu/campana-hover.png)");
        $('.notificacion').show(150);
        $('.puntero-notificacion').show(150);
        if ($('.perfil-dropdown').css('display') == "block") {
            $('.header-user').css("background-image", "");
            $('.perfil-dropdown').hide(150);
        }
    } else {
        $('.header-bell').css("background-image", "");
        $('.notificacion').hide(150);
        $('.puntero-notificacion').hide(150);
    }

};
/*
 * Actualizar estado NOTIFICACIONES
 *
 */

function changestatus(id) {
    if (id != "") {
        $.ajax({
                url: "/portal/notificacion/updatenotificacion/" + id
            })
            .done(function(msg) {
                $('#' + id).remove();
                var noti = parseInt($('#numNoti').text()) - 1;
                $('#numNoti').text(noti);
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                console.log("error" + msg);
            });
    }


}

function mantener(pkendidad) {
    sessionStorage.setItem('clave', pkendidad);

    $.ajax({
            url: "/portal/ajax/notification/" + pkendidad
        })
        .done(function(msg) {
            $('#notificacion').html(msg);
            $('#numNoti').html($('#numNotiback').text());

            var noti = parseInt($('#numNotiback').text());
            if (noti == 0) {
                $('#cant-noti').hide();
                $('#header-bell').attr('href', '');
            }
            //$.each(noti, function(key, value ) {
            //      console.log(key+'='+value.NOMBRE);
            //  });
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log("error".msg);
        });
}