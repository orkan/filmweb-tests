<?php
use Orkan\Filmweb\Api\Method\getFilmInfoFull;
use PHPUnit\Framework\TestCase;

class getFilmInfoFullTest extends TestCase
{

	public function test_type()
	{
		$this->assertSame( 'get', ( new getFilmInfoFull() )::TYPE );
	}

	public function test_format( $id = 36797 )
	{
		/* @formatter:off */
		$args = array(
			getFilmInfoFull::ID => $id,
		);
		/* @formatter:on */

		$this->assertSame( "getFilmInfoFull [$id]", ( new getFilmInfoFull() )->format( $args ) );
	}
}
