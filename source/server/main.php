<?php namespace wonder_rabbit_project\bomber_farm\main;

const module_name = 'wonder_rabbit_project::bomber_farm::main';

date_default_timezone_set( 'Asia/Tokyo' );

require_once( 'logger.php' );
use \wonder_rabbit_project\logger\logger;
logger::file( 'bomber-farm' );
logger::level( 'DEBUG' );

require_once( 'exception.php' );
use \wonder_rabbit_project\exception\exception;
exception::error_to_exception( true );

require_once( 'vec.php' );
use \wonder_rabbit_project\math\vec;

$a = new vec( 3, 4 );
$b = new vec( 1, 2 );

logger::info( module_name, 'norm( $a ): ' . vec::norm( $a ) );
logger::info( module_name, 'norm( $b ): ' . vec::norm( $b ) );
logger::info( module_name, 'distance( $a, $b ): ' . vec::distance( $a, $b ) );

//logger::fatal( module_name, 'fatal testing' );
