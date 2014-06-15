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

final class system_configuration
  implements \JsonSerializable
{
  public function __construct( $json = null )
  {
    if ( ! is_null( $json ) )
      json_deserialize( $json );
  }
  
  private function json_deserialize( $json )
  {
    
  }
  
  private function json_serialize()
  {
    
  }
  
  public function jsonSerialize()
  { return $this -> json_serialize(); }
}

final class main
  implements default_construct_parameters
{
  private $_is_continue       = false;
  private $_configuration     = null;
  
  private $_before_time_in_us =  0.0;
  private $_clock_in_hz       = 30.0;
  
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
    
    $this -> save_before_time();
    
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
    
    $this -> update();
    $this -> process_commands();
    $this -> adjust_game_clock();
    
    $this -> _is_continue = false;
  }
  
  private function update()
  {
    logger::debug( module_name, __FUNCTION__ );
  }
  
  private function process_commands()
  {
    logger::debug( module_name, __FUNCTION__ );
  }
  
  private function adjust_game_clock()
  {
    logger::debug( module_name, __FUNCTION__ );
    
    $delta_time_in_us = microtime( true ) - $this -> _before_time_in_us;
    
    $wait = 1.0e+6 / $this -> _clock_in_hz - $delta_time_in_us;
    
    logger::debug( module_name, __FUNCTION__ . ' sleep ' . sprintf( '%.3f', $wait * 1.0e-3 ) . ' [ms]' );
    usleep( $wait );
    $this -> save_before_time();
    logger::debug( module_name, __FUNCTION__ . ' awake' );
  }
  
  private function save_before_time()
  { $this -> _before_time_in_us = microtime( true ); }
  
}

main();