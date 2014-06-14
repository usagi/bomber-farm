<?php namespace wonder_rabbit_project\exception;

final class exception
{
  static public function error_to_exception( $enable )
  {
    if ( ! is_bool( $enable ) )
      throw new InvalidArgumentException( $enable . ' is invalid. require bool.' );
    
    set_error_handler
      ( $enable
        ? function( $errno, $errstr, $errfile, $errline )
          {
            throw new \ErrorException( $errstr, 0, $errno, $errfile, $errline );
          }
        : null
      );
  }
}
