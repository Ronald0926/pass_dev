<form name="frm_com" id="frm_com" action="https://www.pagosvirtuales.com/Gateway.aspx" method="post" target="_blank">
<?php
    foreach ($_POST as $a => $b) {
        echo '<input type="hidden" name="'.htmlentities($a).'" value="'.htmlentities($b).'">';
    }
?>
</form>
<script type="text/javascript">
    document.getElementById('frm_com').submit();
</script>