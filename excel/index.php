<?php
$archivo=$_POST['ARCHIVO'];
echo file_get_contents('../uploads/' .$archivo);
?>