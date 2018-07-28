<?php

// include composer's autoloader
require_once dirname(__DIR__) . '/vendor/autoload.php';

// show all errors
error_reporting(E_ALL);

// set error and exception handlers
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

session_start();

use \Core\Router;

$url = $_SERVER['QUERY_STRING'];

Router::add('', [
	'controller' => 'Home'
]);

// custom route for logout
Router::add('logout', [
	'controller'	=>	'Login',
	'action'			=>	'destroy'
]);

Router::add('{controller}');
Router::add('{controller}/{action}');
Router::add('{controller}/{id:\d+}/{action}');

// custom route for password reset token
Router::add('password/reset/{token:[\da-f]+}', [
	'controller'	=>	'Password',
	'action'			=>	'reset'
]);

Router::dispatch($url);

