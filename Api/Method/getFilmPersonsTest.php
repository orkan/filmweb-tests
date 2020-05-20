<?php
use Orkan\Filmweb\Api\Method\getFilmPersons;
use PHPUnit\Framework\TestCase;

class getFilmPersonsTest extends TestCase
{

	public function test_type()
	{
		$this->assertSame( 'get', ( new getFilmPersons() )::TYPE );
	}

	public function test_format( $filmId = 36797, $role = getFilmPersons::ROLE_ACTOR )
	{
		/* @formatter:off */
		$args = array(
			getFilmPersons::FILMID => $filmId,
			getFilmPersons::ROLE   => $role,
		);
		/* @formatter:on */
		$this->assertSame( "getFilmPersons [$filmId, $role, 0, 50]", ( new getFilmPersons() )->format( $args ) );
	}
}
