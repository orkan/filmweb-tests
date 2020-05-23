<?php
use Orkan\Filmweb\Api\Method\getFilmReview;
use PHPUnit\Framework\TestCase;

class getFilmReviewTest extends TestCase
{

	public function test_type()
	{
		$this->assertSame( 'get', ( new getFilmReview() )::TYPE );
	}

	public function test_format( $id = 36797 )
	{
		/* @formatter:off */
		$args = array(
			getFilmReview::ID => $id,
		);
		/* @formatter:on */

		$this->assertSame( "getFilmReview [$id]", ( new getFilmReview() )->format( $args ) );
	}
}
