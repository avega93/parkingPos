<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/****************** Control de usuarios y roles ******************/
//cambiar contraseña
$route['contrasena'] = "administrador/usuario/contrasena";
//recuperar contraseña
$route['recuperar'] = "administrador/login/recuperar";
//Usuarios y privilegios
$route['administrador/(:any)'] = "administrador/$1";
$route['parqueadero/(:any)'] = "parqueadero/$1";
/****************** GRID PHP ******************/
$route['phpGrid/(:any)'] = "phpGrid/$1";

/****************** enrutamiento principal ******************/
$route['default_controller'] = "administrador/login/validar";
$route['404_override'] = "administrador/login/validar";


/* End of file routes.php */
/* Location: ./application/config/routes.php */