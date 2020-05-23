<?php
use Orkan\Filmweb\Api\Method\getFilmVideos;
use PHPUnit\Framework\TestCase;

class getFilmVideosTest extends TestCase
{

	public function test_type()
	{
		$this->assertSame( 'get', ( new getFilmVideos() )::TYPE );
	}

	public function test_format( $id = 36797 )
	{
		/* @formatter:off */
		$args = array(
			getFilmVideos::ID => $id,
		);
		/* @formatter:on */

		$cfg = ( new getFilmVideos() )->getDefaults( [] );

		$expected = sprintf( 'getFilmVideos [%u, %u, %u]', $args[getFilmVideos::ID], $cfg['offset'], $cfg['limit'] );
		$result = ( new getFilmVideos() )->format( $args );

		$this->assertSame( $expected, $result );
	}
}
