<?php
use Orkan\Filmweb\Api\Method\getFilmUserRecommendation;
use PHPUnit\Framework\TestCase;

class getFilmUserRecommendationTest extends TestCase
{

	public function test_type()
	{
		$this->assertSame( 'get', ( new getFilmUserRecommendation() )::TYPE );
	}

	public function test_format( $id = 36797 )
	{
		/* @formatter:off */
		$args = array(
			getFilmUserRecommendation::ID => $id,
		);
		/* @formatter:on */

		$this->assertSame( "getFilmUserRecommendation [$id]", ( new getFilmUserRecommendation() )->format( $args ) );
	}
}
