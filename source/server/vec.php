<?php namespace wonder_rabbit_project\math;

require_once( 'logger.php' );
use \wonder_rabbit_project\logger\logger;

const module_name = 'wonder_rabbit_project::math::vec';

class vec
{
  protected $_values = [];
  
  public function __construct( $x = null, $y = null, $z = null, $w =null )
  {
    logger::debug( module_name, "vec -> __construct( $x, $y, $z, $w )" );
    switch( false )
    {
      case is_null( $w ): $this -> w = $w;
      case is_null( $z ): $this -> z = $z;
      case is_null( $y ): $this -> y = $y;
      case is_null( $x ): $this -> x = $x;
    }
    ksort( $this -> _values );
  }
  
  public function __toString()
  { return 'vec[' . $this -> size() . ']{ ' . implode( ', ', $this -> _values ) . ' }'; }
  
  private function property_name_to_index( $property_name )
  {
    logger::debug( module_name, 'vec -> property_name_to_index( ' . $property_name . ' )' );
    switch ( $property_name )
    {
    case 'x':
    case 'y':
    case 'z':
    case 'w':
      $index = (int)( ord( $property_name)  - ord( 'x' ) );
      
      if ( $index < 0 || $index > 3 )
        throw new \invalidargumentexception( 'unsupported char code platform. index = ' . $index . ' from ' . $property_name );
      
      logger::debug( module_name, 'vec -> property_name_to_index return: ' . $index );
      return $index;
    }
    
    throw new \invalidargumentexception( 'unsupported property.' );
  }
  
  private function exception_if_not_numeric( $v )
  {
    logger::debug( module_name, 'vec -> exception_if_not_numeric( ' . $v . ' )' );
    if ( ! is_numeric( $v ) )
      throw new invalidargumentexception( 'require numeric type. typeof $v is: ' . gettype( $v ) );
  }
  
  public function __get( $key )
  {
    logger::debug( module_name, 'vec -> __get( ' . $key . ' )' );
    return $this -> _values[ $this -> property_name_to_index( $key ) ];
  }
  
  public function __set( $key, $value )
  {
    logger::debug( module_name, 'vec -> __set( ' . $key . ', ' . $value .' )' );
    $this -> exception_if_not_numeric( $value );
    $this -> _values[ $this -> property_name_to_index( $key ) ] = $value;
    return $this;
  }
  
  public function size()
  {
    $r = count( $this -> _values );
    logger::debug( module_name, 'vec -> size return: ' . $r );
    return $r;
  }
  
  static public function exception_if_not_vec( $v )
  {
    logger::debug( module_name, 'vec :: exception_if_not_vec( ' . $v . ' )' );
    if ( ! $v instanceof vec )
      throw new InvalidArgumentException( 'require vec type. typeof $v is: ' . gettype( $v ) );
  }
  
  static public function warn_if_dimension_not_eq( $a, $b )
  {
    logger::debug( module_name, 'vec :: warn_if_dimension_not_eq( ' . "$a, $b" . ' )' );
    $r = $b -> size() - $a -> size();
    if ( $r !== 0 )
      logger::warn( module_name, 'vec :: warn_if_dimension_not_eq( ' . "$a, $b" . ' ): dimension is not equals.' );
    return $r;
  }
  
  static private function test_binary_operation_parameter( $a, $b )
  {
    logger::debug( module_name , 'vec :: test_binary_operation_parameter( ' . "$a, $b" . ' )' );
    self::exception_if_not_vec( $a );
    self::exception_if_not_vec( $b );
    $d = self::warn_if_dimension_not_eq( $a, $b );
  }
  
  static public function add( $a, $b )
  {
    logger::debug( module_name, 'vec :: add( ' . "$a, $b" . ' )' );
    self::test_binary_operation_parameter( $a, $b );
    $r = new vec;
    $r -> _values = array_map( function( $a, $b ){ return $a + $b; }, $a -> _values, $b -> _values );
    logger::debug( module_name, 'vec :: add return: ' . $r );
    return $r;
  }
  
  static public function sub( $a, $b )
  {
    logger::debug( module_name, 'vec :: sub( ' . "$a, $b" . ' )' );
    self::test_binary_operation_parameter( $a, $b );
    $r = new vec;
    $r -> _values = array_map( function( $a, $b ){ return $a - $b; }, $a -> _values, $b -> _values );
    logger::debug( module_name, 'vec :: sub return: ' . $r );
    return $r;
  }
  
  static public function mul( $a, $b )
  {
    logger::debug( module_name, 'vec :: mul( ' . "$a, $b" . ' )' );
    self::test_binary_operation_parameter( $a, $b );
    $r = new vec;
    $r -> _values = array_map( function( $a, $b ){ return $a * $b; }, $a -> _values, $b -> _values );
    logger::debug( module_name, 'vec :: mul return: ' . $r );
    return $r;
  }
  
  static public function div( $a, $b )
  {
    logger::debug( module_name, 'vec :: div( ' . "$a, $b" . ' )' );
    self::test_binary_operation_parameter( $a, $b );
    $r = new vec;
    $r -> _values = array_map( function( $a, $b ){ return $a / $b; }, $a -> _values, $b -> _values );
    logger::debug( module_name, 'vec :: div return: ' . $r );
    return $r;
  }
  
  static public function norm( $v )
  {
    logger::debug( module_name, 'vec :: norm( ' . $v . ' )' );
    self::exception_if_not_vec( $v );
    $s = array_map( function( $v ){ return $v * $v; }, $v -> _values );
    $r = array_reduce( $s, function( $a, $v ){ return $a + $v; } );
    $n = sqrt( $r );
    logger::debug( module_name, 'vec :: add return: ' . $n );
    return $n;
  }
  
  static public function distance( $a, $b )
  {
    logger::debug( module_name, 'vec :: distance( ' . "$a, $b" . ' )' );
    self::test_binary_operation_parameter( $a, $b );
    $d = self::sub( $a, $b );
    $n = self::norm( $d );
    logger::debug( module_name, 'vec :: distance return: ' . $n );
    return $n;
  }
  
}
