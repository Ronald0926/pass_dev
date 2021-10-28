<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/****************** Control de usuarios y roles ******************/
//cambiar contraseña
$route['contrasena'] = "administrador/usuario/contrasena";
//recuperar contraseña
$route['recuperar'] = "administrador/login/recuperar";
//Usuarios y privilegios
$route['administrador/(:any)'] = "administrador/$1";
$route['administrador'] = "administrador/login/validar";

/****************** GRID PHP ******************/
$route['phpGrid/(:any)'] = "phpGrid/$1";

/****************** Administración de contenidos ******************/
$route['contenido/(:any)'] = "contenido/contenido/$1";

/****************** portal ******************/
$route['portal/(:any)'] = "portal/$1";

/****************** chat ******************/
$route['chat/(:any)'] = "chat/$1";

/****************** portal ******************/
$route['remesaWsOnline'] = "wsonline2/remesa/crear";
$route['xlsunoauno'] = "wsonline2/xlsunoauno/crear";
$route['sobreWsOnline'] = "wsonline2/sobreflex/crear";
$route['emailWsOnline'] = "wsonline2/email/enviar";
/*******************wsonline2 *****************/
$route['wsonline2/(:any)'] = "wsonline2/$1";
/*******************otp masivian**************/
$route['enviarOTP'] = "wsonline2/masivian/enviarOTP";

/*******************modulosac *****************/
$route['modulosac/(:any)'] = "modulosac/$1";
/****************** enrutamiento principal ******************/
$route['default_controller'] = "portal/login/validar";
$route['404_override'] = "portal/login/validar";

/***********************WSTALOS*************************/
$route['validarth'] = "wstalosfacturador/ProcesamientoTalos/validar_th";
$route['crearth'] = "wstalosfacturador/ProcesamientoTalos/crear_th";
$route['obtenerCiuDep'] = "wstalosfacturador/ProcesamientoTalos/retornar_ciudad_departamento";
$route['crearFactura'] = "wstalosfacturador/ProcesamientoTalos/crear_factura_minero";
$route['transmitirFactMinero'] = "wstalosfacturador/ProcesamientoFacturador/crear_factura";
$route['transmitirNotaMinero'] = "wstalosfacturador/ProcesamientoFacturador/crear_nota";
/* End of file routes.php */
/* Location: ./application/config/routes.php */
