<?php
use Orkan\Filmweb\Api\Method\getFilmImages;
use PHPUnit\Framework\TestCase;

class getFilmImagesTest extends TestCase
{

	public function test_type()
	{
		$this->assertSame( 'get', ( new getFilmImages() )::TYPE );
	}

	public function test_format( $id = 36797 )
	{
		/* @formatter:off */
		$args = array(
			getFilmImages::ID => $id,
		);
		/* @formatter:on */

		$cfg = ( new getFilmImages() )->getDefaults( [] );

		$expected = sprintf( 'getFilmImages [%u, %u, %u]', $args[getFilmImages::ID], $cfg['offset'], $cfg['limit'] );
		$result = ( new getFilmImages() )->format( $args );

		$this->assertSame( $expected, $result );
	}
}
