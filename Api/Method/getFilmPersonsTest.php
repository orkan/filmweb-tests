<?php
use Orkan\Filmweb\Api\Method\PersonRole;
use Orkan\Filmweb\Api\Method\getFilmPersons;
use PHPUnit\Framework\TestCase;

class getFilmPersonsTest extends TestCase
{

	public function test_type()
	{
		$this->assertSame( 'get', ( new getFilmPersons() )::TYPE );
	}

	public function test_format( $id = 36797, $role = PersonRole::ACTOR )
	{
		/* @formatter:off */
		$args = array(
			getFilmPersons::ID   => $id,
			getFilmPersons::ROLE => $role,
		);
		/* @formatter:on */
		$this->assertSame( "getFilmPersons [$id, $role, 0, 50]", ( new getFilmPersons() )->format( $args ) );
	}
}
