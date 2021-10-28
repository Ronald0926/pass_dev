<?php
//phpinfo();

try {
ini_set ("display_errors", "1");
error_reporting(E_ALL);
echo "before";
$conn = oci_connect('MODCONEXION', 'Tecno2018', '192.168.10.50:1521/linedev');
echo "after";
} catch(Exception $e) {
    echo $e->getMessage();
}

$stid = oci_parse($conn, 'select * from dual');
oci_execute($stid);

echo "<table>\n";
while (($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
    echo "<tr>\n";
    foreach ($row as $item) {
        echo "  <td>".($item !== null ? htmlentities($item, ENT_QUOTES) : " ")."</td>\n";
    }
    echo "</tr>\n";
}
echo "</table>\n";
?>
