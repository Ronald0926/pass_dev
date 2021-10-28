$(document).ready(function () {
    /*
     * FUNCIONALIDAD SELECT
     */
    $('.select').find('select').change(function () {
        $(this).parent().find('div').html(
                $(this).find(':selected').html()
                );
    });
    /*
     * FUNCIONALIDAD CHECKBOX
     */
    $('.login-checkbox').click(function () {
        if($(this).find('.login-checkbox-check').css('display') == 'none'){
            $(this).find('.login-checkbox-check').show(100);
            $(this).find('input[type=checkbox]').prop('checked', true);
        } else {
            $(this).find('.login-checkbox-check').hide(100);
            $(this).find('input[type=checkbox]').prop('checked', false);
        }
    });
});