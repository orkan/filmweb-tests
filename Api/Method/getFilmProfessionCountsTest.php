<?php
use Orkan\Filmweb\Api\Method\getFilmProfessionCounts;
use PHPUnit\Framework\TestCase;

class getFilmProfessionCountsTest extends TestCase
{

	public function test_type()
	{
		$this->assertSame( 'get', ( new getFilmProfessionCounts() )::TYPE );
	}

	public function test_format( $id = 36797 )
	{
		/* @formatter:off */
		$args = array(
			getFilmProfessionCounts::ID => $id,
		);
		/* @formatter:on */

		$this->assertSame( "getFilmProfessionCounts [$id]", ( new getFilmProfessionCounts() )->format( $args ) );
	}
}
