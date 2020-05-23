<?php
use Orkan\Filmweb\Api\Method\getFilmDescription;
use PHPUnit\Framework\TestCase;

class getFilmDescriptionTest extends TestCase
{

	public function test_type()
	{
		$this->assertSame( 'get', ( new getFilmDescription() )::TYPE );
	}

	public function test_format( $id = 36797 )
	{
		/* @formatter:off */
		$args = array(
			getFilmDescription::ID => $id,
		);
		/* @formatter:on */

		$this->assertSame( "getFilmDescription [$id]", ( new getFilmDescription() )->format( $args ) );
	}
}
