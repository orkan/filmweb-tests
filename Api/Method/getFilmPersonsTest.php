<?php

use Orkan\Filmweb\Api\Method\getFilmPersons;
use PHPUnit\Framework\TestCase;

class getFilmPersonsTest extends TestCase
{
	public function testType()
	{
		$this->assertSame( 'get', ( new getFilmPersons )::TYPE );
	}
	
	public function testFormat( $filmId = 36797, $role = getFilmPersons::ROLE_ACTOR )
	{
		$args = array(
			getFilmPersons::FILMID => $filmId,
			getFilmPersons::ROLE   => $role,
		);
		$this->assertSame( "getFilmPersons [$filmId, $role, 0, 50]", ( new getFilmPersons )->format( $args ) );
	}
}
