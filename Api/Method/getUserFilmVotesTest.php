<?php

use Orkan\Filmweb\Api\Method\getUserFilmVotes;
use PHPUnit\Framework\TestCase;

class getUserFilmVotesTest extends TestCase
{
	public function testType()
	{
		$this->assertSame( 'get', ( new getUserFilmVotes )::TYPE );
	}

	public function testFormat( $userId = 36797 )
	{
		$args = array(
			getUserFilmVotes::USERID => $userId,
		);
		$this->assertSame( "getUserFilmVotes [$userId, 1]", ( new getUserFilmVotes )->format( $args ) );
	}
}
