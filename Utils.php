<?php
namespace Orkan\Filmweb\Tests;

use ReflectionClass;

class Utils
{

	/**
	 * Set private property in an object
	 *
	 * @param object $obj
	 * @param string $property Property name
	 * @param mixed $value New value
	 */
	public static function setPrivateProperty( object $obj, string $property, $value ): void
	{
		$class = new ReflectionClass( $obj );
		$item = $class->getProperty( $property );
		$item->setAccessible( true );
		$item->setValue( $obj, $value );
	}

	/**
	 * Get private property from an object
	 *
	 * @param object $obj
	 * @param string $property Property name
	 * @return mixed
	 */
	public static function getPrivateProperty( object $obj, string $property )
	{
		$class = new ReflectionClass( $obj );
		$item = $class->getProperty( $property );
		$item->setAccessible( true );
		return $item->getValue( $obj );
	}

	/**
	 * Call private Api method of an object
	 *
	 * @param object $obj
	 * @param string $method
	 * @param array $args
	 * @return mixed
	 */
	public static function callPrivateMethod( object $obj, string $method, $args = [] )
	{
		$class = new ReflectionClass( $obj );
		$item = $class->getMethod( $method );
		$item->setAccessible( true ); // Set method to public
		return $item->invokeArgs( $obj, $args ); // Call Api->$method($args);
	}

	/**
	 * Replace e.g. ApiTest::testGetRequest => getRequest
	 *
	 * @param string $method Fully qualified method name
	 * @return string
	 */
	public static function getTestedMethod( $method )
	{
// 		$s = explode( '::', $method );
// 		$s = array_pop( $s );
// 		$s = substr( $s, strlen( 'test' ) );

		$s = substr( $method, strrpos($method, 'test') + 4);
		$s[0] = strtolower( $s[0] );
		return $s;
	}
}
