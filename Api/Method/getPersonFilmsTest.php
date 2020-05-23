<?php
use Orkan\Filmweb\Api\Method\getPersonFilms;
use Orkan\Filmweb\Api\Method\FilmType;
use Orkan\Filmweb\Api\Method\PersonRole;

use PHPUnit\Framework\TestCase;

class getPersonFilmsTest extends TestCase
{

	public function test_type()
	{
		$this->assertSame( 'get', ( new getPersonFilms() )::TYPE );
	}

	public function test_format( $id = 36797, $istype = FilmType::SERIES, $isrole = PersonRole::SCREENWRITER )
	{
		/* @formatter:off */
		$args = array(
			getPersonFilms::ID => $id,
			getPersonFilms::IS_TYPE => $istype,
			getPersonFilms::IS_ROLE => $isrole,
		);
		/* @formatter:on */

		$args = $args + ( new getPersonFilms() )->getDefaults( [] );

		$expected = sprintf(
			/* @formatter:off */
			'getPersonFilms [%u, %u, %u, %u, %u]',
			$args[getPersonFilms::ID],
			$args[getPersonFilms::IS_TYPE],
			$args[getPersonFilms::IS_ROLE],
			$args['offset'],
			$args['limit']
		);
		/* @formatter:on */

		$result = ( new getPersonFilms() )->format( $args );

		$this->assertSame( $expected, $result );
	}
}
