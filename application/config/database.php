<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$active_group = 'default';
$active_record = TRUE;

$tnsname='(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=192.168.10.50)(PORT=1521))(CONNECT_DATA=(SERVER=DEDICATE)(SERVICE_NAME=linedev)))';

$db['default']['hostname'] = $tnsname;
$db['default']['username'] = 'MODCONEXION';
$db['default']['password'] = 'Tecnolet2020';
$db['default']['database'] = 'linedev';
$db['default']['dbdriver'] = 'oci8';
$db['default']['dbprefix'] = '';
//$db['default']['pconnect'] = TRUE;
$db['default']['pconnect'] = FALSE;
$db['default']['db_debug'] = TRUE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = '';
$db['default']['char_set'] = 'utf8';
$db['default']['dbcollat'] = 'utf8_general_ci';
$db['default']['swap_pre'] = '';
$db['default']['autoinit'] = TRUE;
$db['default']['stricton'] = FALSE;


/* End of file database.php */
/* Location: ./application/config/database.php */
