<?php
use Orkan\Filmweb\Api\Method\getPersonImages;
use PHPUnit\Framework\TestCase;

class getPersonImagesTest extends TestCase
{

	public function test_type()
	{
		$this->assertSame( 'get', ( new getPersonImages() )::TYPE );
	}

	public function test_format( $id = 36797 )
	{
		/* @formatter:off */
		$args = array(
			getPersonImages::ID => $id,
		);
		/* @formatter:on */

		$cfg = ( new getPersonImages() )->getDefaults( [] );

		$expected = sprintf( 'getPersonImages [%u, %u, %u]', $args[getPersonImages::ID], $cfg['offset'], $cfg['limit'] );
		$result = ( new getPersonImages() )->format( $args );

		$this->assertSame( $expected, $result );
	}
}
