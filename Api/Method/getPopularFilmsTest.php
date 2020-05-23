<?php
use Orkan\Filmweb\Api\Method\getPopularFilms;
use PHPUnit\Framework\TestCase;

class getPopularFilmsTest extends TestCase
{

	public function test_type()
	{
		$this->assertSame( 'get', ( new getPopularFilms() )::TYPE );
	}

	public function test_format()
	{
		/* @formatter:off */
		$args = array(
		);
		/* @formatter:on */
		$this->assertSame( 'getPopularFilms', ( new getPopularFilms() )->format( $args ) );
	}
}
