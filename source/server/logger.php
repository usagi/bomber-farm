<?php namespace wonder_rabbit_project\logger;

const module_name = 'wonder_rabbit_project::logger';

final class logger
{
  static private $_level = 5;
  static private $_tee   = true;
  static private $_file  = null;
  static private $_file_append_time = true;
  static private $_file_extension = '.log';
  static private $_exclude_modules = [];
  
  static public function at_exit()
  {
    self::debug( module_name, __FUNCTION__ );
    if ( self::$_file )
    {
      self::debug( module_name, __FUNCTION__ . ' close file' . self::$_file );
      fclose( self::$_file );
    }
  }
  
  static private function exception_if_not_string( $value )
  {
    if ( ! is_string( $value ) )
      throw new InvalidArgumentException( $value . ' is not string.' );
  }
  
  static private function exception_if_not_bool( $value )
  {
    if ( ! is_bool( $value ) )
      throw new InvalidArgumentException( $value . ' is not bool.' );
  }
  
  static public function push_exclude_module( $module )
  {
    self::debug( module_name, __FUNCTION__ . '( ' . $module . ' )' );
    
    self::exception_if_not_string( $module );
    
    if ( in_array( $module, self::$_exclude_modules ) )
      throw new LogicException( $module . ' is exists already.' );
    
    array_push( self::$_exclude_modules, $module );
  }
  
  static public function remove_exclude_module( $module )
  {
    self::debug( module_name, __FUNCTION__ . '( ' . $module . ' )' );
    
    self::exception_if_not_string( $module );
    
    if ( ! in_array( $module, self::$_exclude_modules ) )
      throw new LogicException( $module . ' is not exists.' );
    
    self::$_exclude_modules = array_values( array_diff( self::$_exclude_modules, [ $module ] ) );
  }
  
  static public function file_append_time( $enable )
  {
    self::debug( module_name, __FUNCTION__ . '( ' . $enable . ' )' );
    
    self::exception_if_not_bool( $enable );
    self::$_file_append_time = $enable;
  }
  
  static public function file_extension( $extension )
  {
    self::debug( module_name, __FUNCTION__ . '( ' . $extension . ' )' );
    
    self::exception_if_not_string( $extension );
    self::$_file_extension = $extension;
  }
  
  static public function file( $filename )
  {
    self::debug( module_name, __FUNCTION__ . '( ' . $filename . ' )' );
    
    self::exception_if_not_string( $filename );
    
    if ( self::$_file )
      fclose( self::$_file );
    
    $f = $filename;
    if ( self::$_file_append_time )
      $f .= time();
    if ( self::$_file_extension )
      $f .= self::$_file_extension;
    
    self::$_file = fopen( $f, 'a');
    
    if ( ! self::$_file )
      throw RuntimeException( 'file( ' . $f . ' ) could not opened.' );
    
    register_shutdown_function( function(){ logger::at_exit(); } );
  }
  
  static public function tee( $enable )
  {
    self::debug( module_name, __FUNCTION__ . '( ' . $enable . ' )' );
    
    self::exception_if_not_bool( $enable );
    self::$_tee = $enable;
  }

  static public function level( $value )
  {
    self::debug( module_name, __FUNCTION__ . '( ' . $value . ' )' );
    
    if ( is_string( $value ) )
      $value = strtolower( $value );
    else if ( ! is_int( $value ) )
      throw new InvalidArgumentException( $value . ' is invalid type. require string or int.' );
    
    switch( $value )
    {
      case 5:
      case 'debug':
        self::$_level = 5;
        return;
      case 4:
      case 'info':
        self::$_level = 4;
        return;
      case 3:
      case 'warn':
        self::$_level = 3;
        return;
      case 2:
      case 'error':
        self::$_level = 2;
        return;
      case 1:
      case 'fatal':
        self::$_level = 1;
        return;
      case 0:
      case 'none':
        self::$_level = 0;
        return;
    }
    
    throw new InvalidArgumentException( $value . ' is not correct parameter. require 5 or debug, 4 or info, 3 or warn, 2 or error, 1 or fatal, 0 or none.' );
  }
  
  static public function level_to_string( $level )
  {
    switch( $level )
    {
      case 5: return 'debug';
      case 4: return 'info';
      case 3: return 'warn';
      case 2: return 'error';
      case 1: return 'fatal';
      case 0: return 'none';
    }
  }
  
  static private function log( $level, $module, $message )
  {
    if ( self::$_level < $level || in_array( $module, self::$_exclude_modules ) )
      return;
      
    $out = date( 'c', time() ) . "\t"
         . str_pad( self::level_to_string( $level ), 5 ) . "\t"
         . $module . "\t"
         . $message . PHP_EOL
         ;
    
    if ( self::$_file )
      fwrite( self::$_file, $out );
    
    if ( self::$_tee )
      fputs( STDERR, $out );
    
    if ( $level == 1 )
      throw new \Exception( 'fatal error. for detail to see the last message of STRERR.' );
  }
  
  static public function debug( $module, $message )
  { self::log( 5, $module, $message ); }
  
  static public function info( $module, $message )
  { self::log( 4, $module, $message ); }
  
  static public function warn( $module, $message )
  { self::log( 3, $module, $message ); }
  
  static public function error( $module, $message )
  { self::log( 2, $module, $message ); }
  
  static public function fatal( $module, $message )
  { self::log( 1, $module, $message ); }
  
}