<?php namespace wonder_rabbit_project\bomber_farm\main;

date_default_timezone_set( 'Asia/Tokyo' );

require_once( 'logger.php' );
use \wonder_rabbit_project\logger\logger;
logger::save_base_time();
logger::file( 'bomber-farm' );
logger::level( 'DEBUG' );

require_once( 'exception.php' );
use \wonder_rabbit_project\exception\exception;
exception::error_to_exception( true );

require_once( 'vec.php' );
use \wonder_rabbit_project\math\vec;
