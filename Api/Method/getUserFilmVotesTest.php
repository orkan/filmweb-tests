<?php
use Orkan\Filmweb\Api\Method\getUserFilmVotes;
use PHPUnit\Framework\TestCase;

class getUserFilmVotesTest extends TestCase
{

	public function test_type()
	{
		$this->assertSame( 'get', ( new getUserFilmVotes() )::TYPE );
	}

	public function test_format( $userId = 36797 )
	{
		/* @formatter:off */
		$args = array(
			getUserFilmVotes::USERID => $userId,
		);
		/* @formatter:on */
		$this->assertSame( "getUserFilmVotes [$userId, 1]", ( new getUserFilmVotes() )->format( $args ) );
	}
}
