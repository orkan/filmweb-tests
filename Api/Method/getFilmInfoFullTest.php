<?php
use Orkan\Filmweb\Api\Method\getFilmInfoFull;
use PHPUnit\Framework\TestCase;

class getFilmInfoFullTest extends TestCase
{

	public function test_type()
	{
		$this->assertSame( 'get', ( new getFilmInfoFull() )::TYPE );
	}

	public function test_format( $filmId = 36797 )
	{
		$args = array( getFilmInfoFull::FILMID => $filmId );
		$this->assertSame( "getFilmInfoFull [$filmId]", ( new getFilmInfoFull() )->format( $args ) );
	}
}
