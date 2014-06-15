<?php namespace wonder_rabbit_project\bomber_farm\main;

const module_name = 'wonder_rabbit_project::bomber_farm::main';

require_once( 'initialize.php' );
use \wonder_rabbit_project\logger\logger;
use \wonder_rabbit_project\math\vec;

function main()
{
  global $argv;
  $main = new main( $argv );
  $main -> run();
}

interface default_construct_parameters
{
  const pdo_dsn      = 'sqlite:bomber-farm.sqlite3';
  const pdo_user     = null;
  const pdo_password = null;
  const pdo_options  = null;
}

final class configuration
  implements JsonSerializable
{
  public __construct( $json = null )
  {
    if ( ! is_null( $json ) )
      json_deserialize( $json )
  }
  
  private json_deserialize( $json )
  {
    
  }
  
  private json_serialize()
  {
    
  }
  
  public jsonSerialize()
  { return $this -> json_serialize(); }
}

final class main
  implements default_construct_parameters
{
  private $_is_continue = false;
  private $_configuration = null;
  
  public function __construct( $construct_parameters = null )
  {
    $pdo_parameters =
      [ 'dsn'      => isset( $construct_parameters['dsn'     ] ) ? $construct_parameters['dsn'     ] : self::pdo_dsn
      , 'user'     => isset( $construct_parameters['user'    ] ) ? $construct_parameters['user'    ] : self::pdo_user
      , 'password' => isset( $construct_parameters['password'] ) ? $construct_parameters['password'] : self::pdo_password
      , 'options'  => isset( $construct_parameters['options' ] ) ? $construct_parameters['options' ] : self::pdo_options
      ];
    $this -> connect_database( $pdo_parameters );
    $this -> load_configuration();
  }
  
  public function run()
  {
    logger::debug( module_name, __FUNCTION__ . ' loop in' );
    
    $this -> _is_continue = true;
    
    while( $this -> _is_continue )
      $this -> step();
    
    logger::debug( module_name, __FUNCTION__ . ' loop out' );
  }
  
  private function connect_database( $pdo_parameters )
  {
    logger::debug( module_name, __FUNCTION__ . '( ' . logger::array_to_string( $pdo_parameters ) . ')' );
    
  }
  
  private function load_configuration()
  {
    logger::debug( module_name, __FUNCTION__ );
    
  }
  
  private function step()
  {
    logger::debug( module_name, __FUNCTION__ );
    $this -> _is_continue = false;
  }
}

main();